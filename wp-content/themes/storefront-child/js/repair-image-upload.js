/**
 * Client-side image compression and upload for repair forms.
 * Compresses images via Canvas API (max 1920px, JPEG 80%) before uploading.
 */
(function ($) {
    'use strict';

    var pendingUploads = 0;
    var $submitBtn = null;

    function updateSubmitButton() {
        if (!$submitBtn) {
            $submitBtn = $('.repair-form .btn.btn-primary[type="submit"]');
        }
        if (pendingUploads > 0) {
            $submitBtn.prop('disabled', true).text('Uploading images... (' + pendingUploads + ' remaining)');
        } else {
            $submitBtn.prop('disabled', false).text('Send Repair Info');
        }
    }

    function compressImage(file) {
        return new Promise(function (resolve, reject) {
            var maxDimension = 1920;
            var quality = 0.80;

            var reader = new FileReader();
            reader.onload = function (e) {
                var img = new Image();
                img.onload = function () {
                    var width = img.width;
                    var height = img.height;

                    // Scale down if needed
                    if (width > maxDimension || height > maxDimension) {
                        if (width > height) {
                            height = Math.round(height * (maxDimension / width));
                            width = maxDimension;
                        } else {
                            width = Math.round(width * (maxDimension / height));
                            height = maxDimension;
                        }
                    }

                    var canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;

                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob(function (blob) {
                        if (blob) {
                            resolve(blob);
                        } else {
                            reject(new Error('Canvas compression failed'));
                        }
                    }, 'image/jpeg', quality);
                };
                img.onerror = function () {
                    reject(new Error('Failed to load image'));
                };
                img.src = e.target.result;
            };
            reader.onerror = function () {
                reject(new Error('Failed to read file'));
            };
            reader.readAsDataURL(file);
        });
    }

    function uploadImage(blob, fileName, itemId) {
        return new Promise(function (resolve, reject) {
            var formData = new FormData();
            formData.append('action', 'repair_upload_image');
            formData.append('nonce', repairUploadData.nonce);
            formData.append('repair_image', blob, fileName);

            $.ajax({
                url: repairUploadData.ajaxurl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        if (e.lengthComputable) {
                            var percent = Math.round((e.loaded / e.total) * 100);
                            var $status = $('.repair-upload-status-' + itemId);
                            $status.find('.upload-progress-current').text('Uploading ' + percent + '%...');
                        }
                    });
                    return xhr;
                },
                success: function (response) {
                    if (response.success) {
                        resolve(response.data);
                    } else {
                        reject(new Error(response.data && response.data.message ? response.data.message : 'Upload failed'));
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var msg = 'Upload failed: ' + (errorThrown || textStatus || 'server error');
                    if (jqXHR.responseJSON && jqXHR.responseJSON.data && jqXHR.responseJSON.data.message) {
                        msg = jqXHR.responseJSON.data.message;
                    }
                    reject(new Error(msg));
                }
            });
        });
    }

    function processFile(file, itemId) {
        var $status = $('.repair-upload-status-' + itemId);

        // Add a progress line for this file
        var $progress = $('<div class="upload-progress-current" style="color:#666;font-size:12px;margin:2px 0;">Compressing ' + file.name + '...</div>');
        $status.append($progress);

        pendingUploads++;
        updateSubmitButton();

        compressImage(file)
            .then(function (blob) {
                var compressedName = file.name.replace(/\.[^.]+$/, '') + '.jpg';
                $progress.text('Uploading ' + compressedName + '...');
                return uploadImage(blob, compressedName, itemId);
            })
            .then(function (data) {
                // Remove progress text
                $progress.remove();

                // Inject hidden input (same structure as frontend-repair.js)
                var $placeholder = $('#myplugin-placeholder-' + itemId);
                $placeholder.after(
                    '<input type="hidden" name="myplugin_attachment_id_array[' + itemId + '][]" value="' + data.url + '">'
                );

                // Add thumbnail preview
                $placeholder.after(
                    '<div class="myplugin-image-preview-' + itemId + '" style="display:inline-block;margin:4px;">' +
                    '<img src="' + data.url + '" style="max-width:120px;max-height:120px;border:1px solid #ddd;border-radius:3px;">' +
                    '</div>'
                );

                pendingUploads--;
                updateSubmitButton();
            })
            .catch(function (err) {
                $progress.text('Error: ' + err.message).css('color', 'red');
                pendingUploads--;
                updateSubmitButton();
            });
    }

    $(document).ready(function () {
        $(document).on('change', '.repair-file-input', function () {
            var $input = $(this);
            var itemId = $input.data('item-id');
            var files = this.files;

            if (!files || !files.length) {
                return;
            }

            for (var i = 0; i < files.length; i++) {
                if (files[i].type.indexOf('image/') === 0) {
                    processFile(files[i], itemId);
                }
            }

            // Reset file input so same files can be selected again
            $input.val('');
        });
    });

})(jQuery);

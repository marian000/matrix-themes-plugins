
    jQuery(document).ready(function () {
        var file_frame; // variable for the wp.media file_frame

        // attach a click event (or whatever you want) to some element on your page
        var id_item = 0;
        jQuery('.upload-images').on('click', function (event) {
            event.preventDefault();
            id_item = jQuery(this).attr('id-item');
            console.log(id_item);
            // if the file_frame has already been created, just reuse it
            if (file_frame) {
                file_frame.open();
                return;
            }
            file_frame = wp.media.frames.file_frame = wp.media({
                title: jQuery(this).data('uploader_title'),
                button: {
                    text: jQuery(this).data('uploader_button_text'),
                },
                multiple: true // set this to true for multiple file selection
            });

            file_frame.on('select', function () {
                var attachments = file_frame.state().get('selection').map(
                    function (attachment) {
                        attachment.toJSON();
                        return attachment;
                    });
                //loop through the array and do things with each attachment
                var i;
                for (i = 0; i < attachments.length; ++i) {
                    console.log(id_item);
                    //sample function 1: add image preview
                    jQuery( '#myplugin-placeholder-' + id_item ).after(
                        '<div class="myplugin-image-preview-'+id_item+'"><img src="' + attachments[i].attributes.url + '" ></div>'
                    );
                    //sample function 2: add hidden input for each image
                    jQuery( '#myplugin-placeholder-' + id_item ).after(
                        '<input id="myplugin-image-input-' +
                        attachments[i].id + '" type="hidden" name="myplugin_attachment_id_array[' +id_item+ '][]"  value="' + attachments[i].attributes.url + '">'
                    );
                }
            });
            file_frame.open();
        });
    });

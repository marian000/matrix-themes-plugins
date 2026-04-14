/**
 * Shutter Configurator Frontend JavaScript
 * 
 * Handles all frontend interactions for the shutter configurator
 * with proper AJAX integration and user experience.
 * 
 * @package ShutterModule
 * @since 1.2.0
 */

(function($) {
    'use strict';

    /**
     * Shutter Configurator Class
     */
    class ShutterConfigurator {
        constructor() {
            this.form = $('#shutter-configurator-form');
            this.loadingOverlay = $('#loading-overlay');
            this.messageContainer = $('#message-container');
            this.priceDisplay = $('#calculated-price');
            
            this.init();
        }

        /**
         * Initialize the configurator
         */
        init() {
            this.bindEvents();
            this.initSections();
            this.calculateInitialPrice();
        }

        /**
         * Bind event handlers
         */
        bindEvents() {
            // Form submission
            this.form.on('submit', this.handleFormSubmit.bind(this));
            
            // Section toggles
            $('.section-toggle').on('click', this.toggleSection.bind(this));
            
            // Material/Style changes
            $('#property_material, input[name="property_style"]').on('change', this.handleConfigChange.bind(this));
            
            // Real-time price calculation
            $('.form-control').on('change input', this.debounce(this.calculatePrice.bind(this), 500));
            
            // Action buttons
            $('#calculate-price').on('click', this.calculatePrice.bind(this));
            $('#validate-config').on('click', this.validateConfiguration.bind(this));
            $('#add-to-cart').on('click', this.addToCart.bind(this));
            
            // File upload
            $('#attachment_upload').on('change', this.handleFileUpload.bind(this));
            
            // Drawing canvas
            $('#btnDrawModal').on('click', this.openDrawingModal.bind(this));
            $('.close-drawing').on('click', this.closeDrawingModal.bind(this));
            $('.export-canvas').on('click', this.exportCanvas.bind(this));
        }

        /**
         * Initialize accordion sections
         */
        initSections() {
            $('.section-toggle').each(function() {
                const $toggle = $(this);
                const $content = $toggle.closest('.config-section').find('.section-content');
                const isExpanded = $toggle.attr('aria-expanded') === 'true';
                
                if (!isExpanded) {
                    $content.hide();
                }
            });
        }

        /**
         * Toggle section visibility
         */
        toggleSection(e) {
            e.preventDefault();
            
            const $toggle = $(e.currentTarget);
            const $section = $toggle.closest('.config-section');
            const $content = $section.find('.section-content');
            const isExpanded = $toggle.attr('aria-expanded') === 'true';
            
            if (isExpanded) {
                $content.slideUp(300);
                $toggle.attr('aria-expanded', 'false');
            } else {
                $content.slideDown(300);
                $toggle.attr('aria-expanded', 'true');
            }
        }

        /**
         * Handle form submission
         */
        handleFormSubmit(e) {
            e.preventDefault();
            
            this.showLoading('Saving configuration...');
            
            const formData = new FormData(this.form[0]);
            formData.append('nonce', shutterAjax.nonce);
            
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: this.handleSaveSuccess.bind(this),
                error: this.handleSaveError.bind(this)
            });
        }

        /**
         * Handle configuration changes (material/style)
         */
        handleConfigChange() {
            const material = $('#property_material').val();
            const style = $('input[name="property_style"]:checked').val();
            
            if (material && style) {
                this.loadFrameOptions(material, style);
                this.loadStileOptions(material);
                this.calculatePrice();
            }
        }

        /**
         * Load frame options based on material and style
         */
        loadFrameOptions(material, style) {
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: {
                    action: 'get_frame_options',
                    material: material,
                    style: style,
                    nonce: shutterAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('#property_frametype').html(response.data.html);
                    }
                }
            });
        }

        /**
         * Load stile options based on material
         */
        loadStileOptions(material) {
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: {
                    action: 'get_stile_options',
                    material: material,
                    nonce: shutterAjax.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('#property_stile').html(response.data.html);
                    }
                }
            });
        }

        /**
         * Calculate price
         */
        calculatePrice() {
            const formData = this.form.serialize();
            
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: formData + '&action=calculate_shutter_price&nonce=' + shutterAjax.nonce,
                success: this.handlePriceCalculation.bind(this),
                error: function() {
                    this.showMessage('Error calculating price', 'error');
                }.bind(this)
            });
        }

        /**
         * Calculate initial price on page load
         */
        calculateInitialPrice() {
            // Small delay to ensure form is fully rendered
            setTimeout(() => {
                this.calculatePrice();
            }, 500);
        }

        /**
         * Handle price calculation response
         */
        handlePriceCalculation(response) {
            if (response.success) {
                this.updatePriceDisplay(response.data.formatted_price);
            }
        }

        /**
         * Update price display
         */
        updatePriceDisplay(formattedPrice) {
            this.priceDisplay.find('.amount').text(formattedPrice.replace(/[^\d.,]/g, ''));
            
            // Add animation effect
            this.priceDisplay.addClass('price-updated');
            setTimeout(() => {
                this.priceDisplay.removeClass('price-updated');
            }, 1000);
        }

        /**
         * Validate configuration
         */
        validateConfiguration() {
            this.showLoading('Validating configuration...');
            
            const formData = this.form.serialize();
            
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: formData + '&action=validate_shutter_config&nonce=' + shutterAjax.nonce,
                success: this.handleValidationResponse.bind(this),
                error: this.handleValidationError.bind(this)
            });
        }

        /**
         * Handle validation response
         */
        handleValidationResponse(response) {
            this.hideLoading();
            
            if (response.success) {
                this.showMessage(shutterAjax.strings.valid_config || 'Configuration is valid!', 'success');
            } else {
                this.showMessage(response.data.message || 'Validation failed', 'error');
            }
        }

        /**
         * Handle validation error
         */
        handleValidationError() {
            this.hideLoading();
            this.showMessage('Error validating configuration', 'error');
        }

        /**
         * Add to cart
         */
        addToCart() {
            // First validate
            this.showLoading('Adding to cart...');
            
            const formData = this.form.serialize();
            
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: formData + '&action=add_shutter_to_cart&nonce=' + shutterAjax.nonce,
                success: this.handleAddToCartSuccess.bind(this),
                error: this.handleAddToCartError.bind(this)
            });
        }

        /**
         * Handle add to cart success
         */
        handleAddToCartSuccess(response) {
            this.hideLoading();
            
            if (response.success) {
                this.showMessage(response.data.message, 'success');
                
                // Optionally redirect to cart
                if (response.data.cart_url) {
                    setTimeout(() => {
                        window.location.href = response.data.cart_url;
                    }, 2000);
                }
            } else {
                this.showMessage(response.data || 'Failed to add to cart', 'error');
            }
        }

        /**
         * Handle add to cart error
         */
        handleAddToCartError() {
            this.hideLoading();
            this.showMessage('Error adding to cart', 'error');
        }

        /**
         * Handle file upload
         */
        handleFileUpload(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            // Validate file type and size
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
            const maxSize = 5 * 1024 * 1024; // 5MB
            
            if (!allowedTypes.includes(file.type)) {
                this.showMessage('Invalid file type. Only images and PDF files are allowed.', 'error');
                return;
            }
            
            if (file.size > maxSize) {
                this.showMessage('File too large. Maximum size is 5MB.', 'error');
                return;
            }
            
            this.uploadFile(file);
        }

        /**
         * Upload file via AJAX
         */
        uploadFile(file) {
            this.showLoading('Uploading file...');
            
            const formData = new FormData();
            formData.append('attachment', file);
            formData.append('action', 'upload_shutter_attachment');
            formData.append('nonce', shutterAjax.nonce);
            
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: this.handleFileUploadSuccess.bind(this),
                error: this.handleFileUploadError.bind(this)
            });
        }

        /**
         * Handle file upload success
         */
        handleFileUploadSuccess(response) {
            this.hideLoading();
            
            if (response.success) {
                $('input[name="attachment"]').val(response.data.url);
                this.showMessage('File uploaded successfully!', 'success');
                
                // Show current attachment link
                const $currentAttachment = $('.current-attachment');
                if ($currentAttachment.length) {
                    $currentAttachment.find('a').attr('href', response.data.url);
                } else {
                    $('#attachment_upload').after(
                        '<div class="current-attachment">' +
                        '<a href="' + response.data.url + '" target="_blank">View Uploaded File</a>' +
                        '</div>'
                    );
                }
            } else {
                this.showMessage(response.data || 'File upload failed', 'error');
            }
        }

        /**
         * Handle file upload error
         */
        handleFileUploadError() {
            this.hideLoading();
            this.showMessage('Error uploading file', 'error');
        }

        /**
         * Open drawing modal
         */
        openDrawingModal() {
            $('#drawing-modal').show();
            // Initialize canvas here if needed
        }

        /**
         * Close drawing modal
         */
        closeDrawingModal() {
            $('#drawing-modal').hide();
        }

        /**
         * Export canvas drawing
         */
        exportCanvas() {
            if (typeof litCanv === 'undefined') {
                this.showMessage('Drawing canvas not initialized', 'error');
                return;
            }
            
            this.showLoading('Exporting drawing...');
            
            const imageData = litCanv.getImage().toDataURL();
            const roomName = $('#property_room_other').val() || 'drawing';
            
            $.ajax({
                url: shutterAjax.url,
                type: 'POST',
                data: {
                    action: 'export_canvas_drawing',
                    imageSrc: imageData,
                    roomName: roomName,
                    nonce: shutterAjax.nonce
                },
                success: this.handleCanvasExportSuccess.bind(this),
                error: this.handleCanvasExportError.bind(this)
            });
        }

        /**
         * Handle canvas export success
         */
        handleCanvasExportSuccess(response) {
            this.hideLoading();
            
            if (response.success) {
                $('input[name="attachmentDraw"]').val(response.data.url);
                this.showMessage('Drawing exported successfully!', 'success');
                this.closeDrawingModal();
            } else {
                this.showMessage(response.data || 'Export failed', 'error');
            }
        }

        /**
         * Handle canvas export error
         */
        handleCanvasExportError() {
            this.hideLoading();
            this.showMessage('Error exporting drawing', 'error');
        }

        /**
         * Handle save success
         */
        handleSaveSuccess(response) {
            this.hideLoading();
            
            if (response.success) {
                this.showMessage(response.data.message, 'success');
                if (response.data.formatted_price) {
                    this.updatePriceDisplay(response.data.formatted_price);
                }
            } else {
                this.showMessage(response.data || 'Save failed', 'error');
            }
        }

        /**
         * Handle save error
         */
        handleSaveError() {
            this.hideLoading();
            this.showMessage('Error saving configuration', 'error');
        }

        /**
         * Show loading overlay
         */
        showLoading(message = 'Loading...') {
            this.loadingOverlay.find('.loading-text').text(message);
            this.loadingOverlay.show();
        }

        /**
         * Hide loading overlay
         */
        hideLoading() {
            this.loadingOverlay.hide();
        }

        /**
         * Show message
         */
        showMessage(message, type = 'info') {
            const $message = $('<div class="message ' + type + '">' + message + '</div>');
            
            this.messageContainer.append($message);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $message.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }

        /**
         * Debounce function
         */
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    }

    /**
     * Initialize configurator when document is ready
     */
    $(document).ready(function() {
        if ($('#shutter-configurator-form').length) {
            new ShutterConfigurator();
        }
    });

    /**
     * Form validation helper
     */
    function validateForm() {
        let isValid = true;
        
        // Check required fields
        $('.required').each(function() {
            const $field = $(this);
            if (!$field.val().trim()) {
                $field.addClass('error');
                isValid = false;
            } else {
                $field.removeClass('error');
            }
        });
        
        return isValid;
    }

    /**
     * Add CSS for price animation
     */
    const style = document.createElement('style');
    style.textContent = `
        .price-updated {
            animation: priceHighlight 1s ease;
        }
        
        @keyframes priceHighlight {
            0% { background-color: transparent; }
            50% { background-color: #28a745; color: white; }
            100% { background-color: transparent; }
        }
        
        .form-control.error {
            border-color: #dc3545;
            box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.1);
        }
    `;
    document.head.appendChild(style);

})(jQuery); 
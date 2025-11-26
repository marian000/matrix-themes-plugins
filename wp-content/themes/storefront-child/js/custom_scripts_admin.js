jQuery.noConflict();
(function ($) {

    // On click send id clicked order to generate invoice for QuickBooks
    jQuery('.send_qcuickbooks_invoice').on('click', function (e) {
        e.preventDefault();

        var id_ord_original = jQuery(this).attr('id-order');
        var name = jQuery('input[name="order_name"]').val();
        var success = false;
        console.log(id_ord_original);


        jQuery.ajax({
            method: "POST",
            url: "/wp-content/themes/storefront-child/quickBooks/InvoiceAndBilling.php",
            data: {
                id_ord_original: id_ord_original,
                name: name
            },
            beforeSend: function () {
                jQuery("body .spinner-modal").show();
            },
            complete: function () {
                jQuery("body .spinner-modal").hide();
            },
            success: function (data) {
                success = true;
                alert('QuickBooks Invoice Created!');
                // console.log("QuickBooks Invoice: " + data);
                jQuery('[id-order="'+id_ord_original+'"]').replaceWith('<span>Invoice Created</span>');
            },
            error: function (xhr, textStatus, errorThrown) {
                alert('PROBLEM! QuickBooks Invoice Failed!');
                // console.log("QuickBooks Invoice Failed: " + errorThrown);
            }
        });

    });

})(jQuery);

// Other code using $ as an alias to the other library

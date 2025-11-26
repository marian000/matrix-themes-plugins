(function ($) {
    "use strict";

    var orderClient = [];

    $(function () {
        jQuery.ajax({
            method: "POST",
            url: "/wp-content/themes/storefront-child/ajax/autocomplete-orders-json.php",
            data: { 
                orders: 'all',
            }
        })
        .done(function( json ) {
            console.log( "Data Saved: " + json );
            orderClient = json;
        });


        $( "#stgh-contact-order-crm-autocomplete" ).autocomplete({
            source: $.ajax({
                method: "POST",
                url: "/wp-content/themes/storefront-child/ajax/autocomplete-orders-json.php",
                data: { 
                    orders: 'all',
                }
            })
            .done(function( json ) {
                console.log( "Data Saved: " + json );
                return json;
            }),
          });

    });


}(jQuery));


/*
* if type_item is update_item
* */
jQuery(document).ready(function () {
    var installation = jQuery('input[name="property_style"]:checked').val();
    jQuery('input[name="property_style"]').on('click',function(){
        if (installation == '27' || installation == '28') {
            aluPanels();
        }
    });

    setTimeout(function () {
        if (jQuery('input[name="type_item"]').length > 0 && jQuery('input[name="type_item"]').val() == "update_item") {

            /*
            if installation is alu fixed show only frame u-channel and hide others
            */
            if (installation == '27' || installation == '28') {
                aluPanels();
            }
        }
    }, 1000);
});

function aluPanels() {
    var installation = $('input[name=property_style]:checked').data('title');
    if (installation.indexOf('ALU Panel Only') > -1) {
        jQuery('#choose-frametype').hide();
        jQuery('.row.frames').hide();
    } else {
        jQuery('#choose-frametype').show();
        jQuery('.row.frames').show();
    }
    if (installation.indexOf('ALU Fixed Shutter') > -1) {
        jQuery('input[name="property_frametype"][value="291"]').prop('checked', true).click().parent().show();
        jQuery('input[name="property_frametype"][value="300"]').parent().hide();
        jQuery('input[name="property_frametype"][value="301"]').parent().hide();
        jQuery('input[name="property_frametype"][value="302"]').parent().hide();
    } else {
        jQuery('input[name="property_frametype"][value="291"]').parent().hide();
        jQuery('input[name="property_frametype"][value="300"]').parent().show();
        jQuery('input[name="property_frametype"][value="301"]').parent().show();
        jQuery('input[name="property_frametype"][value="302"]').parent().show();
    }
}
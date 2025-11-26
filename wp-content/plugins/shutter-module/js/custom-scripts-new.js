// on next step button click add class done
jQuery('span.js-next-step.next-step, .panel-heading').click(function () {
    console.log('js-next-step click!');
    if (jQuery(this).hasClass('js-next-step')) {
        console.log(jQuery(this).parent().parent().parent().parent());
        jQuery(this).parent().parent().parent().parent().addClass('done');
        jQuery(this).parent().parent().parent().parent().removeClass('inactive');
    }
    if (jQuery(this).hasClass('panel-heading')) {
        console.log("jQuery(this).hasClass('panel-heading')");
        if (jQuery(this).parent().hasClass('inactive')) {
            jQuery(this).parent().addClass('done');
            jQuery(this).parent().removeClass('inactive');
        }
    }
});

jQuery('.btn.btn-info.show-next-panel').click(function () {
    var idPanel = jQuery(this).attr('next-panel');
    console.log(idPanel);
    jQuery(idPanel).parent().addClass('done');
    jQuery(idPanel).parent().removeClass('inactive');
});

// original submit
function getStyleTitle() {
    var title = '';
    if ($('input[name=property_style]:checked').length > 0) {
        title = $('input[name=property_style]:checked').data('title');
    }
    return title;
}

function getPropertyMidrailheight() {
    var midrail = [];
    if ($("#property_midrailheight").val().length > 0) {
        midrail[0] = parseFloat($("#property_midrailheight").val());
    }

    return midrail;
}

function getPropertyTotHeight() {
    var tot_height = 0;
    if ($("#property_totheight").val() != '') {
        tot_height = parseInt($("#property_totheight").val());
    }
    return tot_height;
}

function getPropertyStile() {
    if ($("#property_stile").select2('data')) {
        return parseFloat($("#property_stile").select2('data').value);
    } else {
        return 0;
    }
}

function getPropertyBladesize() {
    if ($("#property_bladesize").select2('data')) {
        return parseFloat($("#property_bladesize").select2('data').value);
    } else {
        return 0;
    }
}

var shutter_type = "Shutter";


//adds error to field or select box
function addError(field_id, error) {
    // console.log("Adding error " + error + " for field " + field_id);
    if ($("#" + field_id).prev().find('.select2-choice').length > 0) {
        $("#" + field_id).prev().addClass("error-field");
        $("#" + field_id).prev().css('display', 'block');
        if ($("#" + field_id).closest('.input-group-container').length > 0) {
            $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).closest('.input-group-container'));
        } else {
            $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).after());
        }
        //show the div with the error if hidden
        //$("#" + field_id).closest(".panel").find(".panel-collapse").collapse("show");

    } else {
        $("#" + field_id).addClass("error-field");
        if ($("#" + field_id).closest('.input-group-container').length > 0) {
            $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).closest('.input-group-container'));
        } else {
            $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id));
        }
        //show the div with the error if hidden
        //$("#" + field_id).closest(".panel").find(".panel-collapse").collapse("show");
    }
}

//Reset error to field or select box
function resetErrors() {
    $(".error-field").removeClass("error-field");
    $("span.error-text").remove();
}


function modalShowError(errortext) {
    jQuery('#errorModal .btn-close').on('click', function (e) {
        jQuery('#errorModal').hide();
    });

    var nowarranty_checked = $("#property_nowarranty").prop("checked");

    $("#nowarranty").show();
    jQuery("#errorModal").show();
    jQuery("#errorModal.fade").css('opacity', '1');
    jQuery('#errorModal .modal-body').html("This shutter is outside of warranty. The following errors have occured: <br/>" + errortext + "<br/><br/>Either <strong>accept</strong> that there will be no warranty or <strong>change</strong> the configuration. ");
    return false;
}

function modalShowErrorNoWarranty(errortext) {
    jQuery('#errorModal .btn-close').on('click', function (e) {
        jQuery('#errorModal').hide();
    });

    var nowarranty_checked = $("#property_nowarranty").prop("checked");

    //$("#nowarranty").show();
    jQuery("#errorModal").show();
    jQuery("#errorModal.fade").css('opacity', '1');
    jQuery('#errorModal .modal-body').html("The following errors have occured: <br/>" + errortext + "<br/>. ");
    return false;
}

// Function to collect and return all shared variables
function getPropertyValues() {
    return {
        property_material: jQuery('#property_material').val(),
        installation: jQuery('input[name="property_style"]:checked').val(),
        property_width: jQuery('#property_width').val(),
        property_height: jQuery('#property_height').val(),
        property_midrailheight: jQuery('#property_midrailheight').val(),
        property_midrailheight2: jQuery('#property_midrailheight2').val(),
        property_bladesize: jQuery('#property_bladesize').val(),
        property_room_other: jQuery('#property_room_other').val(),
        property_stile: jQuery('input[name=property_stile]:checked').val(),
        property_solidtype: jQuery('input[name=property_solidtype]:checked').val(),
        property_hingecolour: jQuery('#property_hingecolour').val(),
        property_shuttercolour: jQuery('#property_shuttercolour').val(),
        property_blackoutblindcolour: jQuery('#property_blackoutblindcolour').val(),
        property_controltype: jQuery('#property_controltype').val(),
        property_midraidevider1: jQuery('#property_midraidevider1').val(),
        property_midraidevider2: jQuery('#property_midraidevider2').val(),
        errors: 0,
        errors_no_warranty: 0,
        nowarranty_checked: $("#property_nowarranty").prop("checked")
    };
}

function checkErrors() {
    var errors = 0;
    var errors_no_warranty = 0;
    var nowarranty_checked = $("#property_nowarranty").prop("checked");


    //check if select or input that are marked as required have values
    jQuery("select.required, input.required").each(function (index) {
        //// console.log(jQuery(this).attr('id') + ' required ' + index);
        if ((jQuery(this).val() === '' || jQuery(this).val() === null) && !jQuery(this).is(":hidden")) {
            // console.log(' empty');
            errors++;
            // console.log('errors: ' + errors);
            addError(jQuery(this).attr('id'), 'Please fill in this field');
            modalShowError('Please fill in this field - ' + jQuery(this).attr('id'));
        }
    });

    style_check = $('input[name=property_style]:checked').data('title');
    if ((property_bladesize.length === 0) && !jQuery('#s2id_property_bladesize').is(":hidden") || property_bladesize === null || property_bladesize === '' && style_check.indexOf('Solid') == -1) {
        //  console.log('bladesize nu e setat');
        // alert('bladesize nu e setat');
        errors++;
        errors_no_warranty++;
        // console.log('errors: ' + errors);
        $("#s2id_property_bladesize").closest(".panel").find(".panel-collapse").collapse("show");
        error_text = 'Louvre Size is empty';
        addError("property_bladesize", error_text);
        modalShowErrorNoWarranty(error_text);
    }

    if ((property_room_other.length === 0) && !jQuery('#s2id_property_room_other').is(":hidden")) {
        //// console.log('room name nu e setat');
        //alert('bladesize nu e setat');
        errors_no_warranty++;
        errors++;
        alert('No room name');
        // console.log('errors: ' + errors);
        error_text = 'Room Name is empty';
        addError("property_room_other", error_text);
        modalShowErrorNoWarranty(error_text);
    }

    //check if property Installation Style is selected & if the selected value is visible
    if ($('input[name="property_style"]:checked').length == 0) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("#choose-style").closest(".panel").find(".panel-collapse").collapse("show");
        addError("choose-style", 'Please select a style for shutter!');
        error_text = 'Please select Installation Style';
        modalShowErrorNoWarranty(error_text);
    }

    if ((property_stile.length === 0) && !jQuery('#s2id_property_stile').is(":hidden")) {
        //// console.log('property_stile nu e setat');
        //alert('bladesize nu e setat');
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'property stile is empty';
        addError("property_stile", error_text);
        modalShowError(error_text);
    }

    if (property_solidtype == null && $('#solidtype').css('display') != 'none') {
        //// console.log('property_solidtype nu e setat');
        //alert('bladesize nu e setat');
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'property stile type is empty';
        addError("solidtype", error_text);
        modalShowError(error_text);
    }

    if (property_hingecolour.length === 0) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("#property_hingecolour").closest(".panel").find(".panel-collapse").collapse("show");
        addError("property_hingecolour", 'Please select hinge colour for shutter!');
        error_text = 'Please select hinge colour';
        modalShowErrorNoWarranty(error_text);
    }

    if (property_shuttercolour.length === 0) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("#property_shuttercolour").closest(".panel").find(".panel-collapse").collapse("show");
        addError("property_shuttercolour", 'Please select shutter colour!');
        error_text = 'Please select shutter colour';
        modalShowErrorNoWarranty(error_text);
    }

    if ($('input[name=property_frametype]:checked').val() == '171') {
        if (property_blackoutblindcolour === 0 || property_blackoutblindcolour === '' || property_blackoutblindcolour === null) {
            errors_no_warranty++;
            errors++;
            // console.log('errors_no_warranty 3');
            $("#property_blackoutblindcolour").closest(".panel").find(".panel-collapse").collapse("show");
            addError("property_blackoutblindcolour", 'Please select Blackout Blind Colour!');
            error_text = 'Please select Blackout Blind Colour';
            modalShowErrorNoWarranty(error_text);
        }
    }

    if (!$("#property_controltype").closest('div').hasClass('solid-selected')) {
        console.log('inside property_controltype $(\'#s2id_property_controltype\').is(\':visible\')');
        // Check if input#property_controltype has no value
        var controlTypeValue = $('#property_controltype').val().trim();
        console.log('controlTypeValue: ' + controlTypeValue);

        if (controlTypeValue === '') {
            errors_no_warranty++;
            errors++;
            console.log('errors_no_warranty 3');
            $("#property_controltype").closest(".panel").find(".panel-collapse").collapse("show");
            addError("property_controltype", 'Please select control type!');
            error_text = 'Please select controltype';
            modalShowErrorNoWarranty(error_text);
        }
    }

    if (property_material === '' || property_material === null) {
        alert('material must be Selected');
    }

    /////  calculate original sizes


    // console.log('bla bla submit');

    var check_height = parseFloat($("#property_height").val());
    var style_check = getStyleTitle();

    var check_controltype = $("#property_controltype").val();
    var check_controlsplitheight = parseFloat($("#property_controlsplitheight").val());
    var check_controlsplitheight2 = parseFloat($("#property_controlsplitheight2").val());
    var check_midrailheight = getPropertyMidrailheight();
    if (check_midrailheight == '') {
        check_midrailheight = 0;
    }
    if (isNaN(check_controlsplitheight)) {
        check_controlsplitheight = 0;
    }
    var check_totheight = getPropertyTotHeight();
    var width_and_height_errors = '';
    var width_and_height_errors_count = 0;
    var tpost_count = 0;


    //midrail should be below 1800mm
    if (style_check.indexOf('Special') < -1) {
        if (check_midrailheight > 1800) {

            error_text = 'Midrail Height should be below 1800mm';
            width_and_height_errors = width_and_height_errors + error_text + '. ';
            width_and_height_errors_count++;
            errors++;
            // console.log('errors: ' + errors);
            addError("property_midrailheight", error_text);

        } else if (check_midrailheight > (property_height - 300)) {
            error_text = 'Midrail Height should be below ' + (property_height - 300) + 'mm';
            width_and_height_errors = width_and_height_errors + error_text + '. ';
            width_and_height_errors_count++;
            errors++;
            // console.log('errors: ' + errors);
            addError("property_midrailheight", error_text)

        }
    } else {
        // console.log('este special shapeee');
    }

    //midrailheight required for >1800 height and NOT Tier styles
    if (property_height >= 1800 && property_height <= 3000 && style_check.indexOf('Tier') == -1 && check_midrailheight == 0) {
        if (style_check.indexOf('Combi') < -1) {
            if ($("#property_midrailheight").val() === '' || $("#property_midrailheight").val() === 0) {

                $("#property_midrailheight").addClass("required");
                // console.log('midrail required');
                error_text = 'Midrail Height should be completed';
                width_and_height_errors = width_and_height_errors + error_text + '. ';
                width_and_height_errors_count++;
                errors++;
                // console.log('errors: ' + errors);
                addError("property_midrailheight", error_text);
                modalShowError(error_text);
                //$("#midrail-height").show();
            }
        }
    }

    if (property_height >= 1800 && property_height <= 3000 && style_check.indexOf('Tracked') >= 0 && check_midrailheight == 0) {
        $("#property_midrailheight").addClass("required");
        // console.log('midrail required');
        error_text = 'Midrail Height should be completed';
        width_and_height_errors = width_and_height_errors + error_text + '. ';
        width_and_height_errors_count++;
        errors++;
        // console.log('errors: ' + errors);
        addError("property_midrailheight", error_text);
        modalShowError(error_text);
    }

    if ($("#property_height").val() == '') {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Height should completed!';
        width_and_height_errors = width_and_height_errors + error_text + '. ';
        width_and_height_errors_count++;
        addError("property_height", error_text);
        modalShowError(error_text);
    }


    if ($("#property_width").val() == '') {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Width should completed!';
        width_and_height_errors = width_and_height_errors + error_text + '. ';
        width_and_height_errors_count++;
        addError("property_width", error_text);
        modalShowError(error_text);
    }

    //minimum height check
    if ($("#property_height").val() != '' && $("#property_material").val() == '137' && parseFloat($("#property_height").val()) < 300) {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Height should be at least 300mm';
        width_and_height_errors = width_and_height_errors + error_text + '. ';
        width_and_height_errors_count++;
        addError("property_height", error_text);
        modalShowError(error_text);
    }

    if ($("#property_height").val() != '' && $("#property_material").val() == '187' && parseFloat($("#property_height").val()) < 400) {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Height should be at least 400mm';
        width_and_height_errors = width_and_height_errors + error_text + '. ';
        width_and_height_errors_count++;
        addError("property_height", error_text);
        modalShowError(error_text);
    }

    if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) < 250) {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Height should be at least 250mm';
        width_and_height_errors = width_and_height_errors + error_text + '. ';
        width_and_height_errors_count++;
        addError("property_height", error_text);
        modalShowError(error_text);
    }


    //height check for tot and not tot
    var id_material = $("input#property_material").val();
    //var stile_check = getPropertyStile();
    //green-137
    if (id_material == 137) {
        panel_height = 1350;
    }
    //biowood-138, supreme-139, earth-187
    else {
        panel_height = 1500;
    }
    max_height = panel_height * 2;

    if (style_check.indexOf('Tier') > -1) {
        if (check_height > panel_height && check_totheight == 0) {
            errors++;
            // console.log('errors: ' + errors);
            error_text = 'T-o-t height required for height more than ' + panel_height.toString() + 'mm. ';
            width_and_height_errors = width_and_height_errors + error_text;
            width_and_height_errors_count++;
            addError("property_totheight", error_text);
            modalShowError(error_text);
        }
    } else if (style_check.indexOf('Solid') > -1) {
        //$("#midrail-height").hide();
        // $("#midrail-height input").val('');
        $("#property_midrailheight").removeClass("required");
        // $("#midrail-height2").hide();
        // $("#midrail-height2 input").val('');
    } else {
        if (style_check.indexOf('Combi') < -1) {
            if (check_height > panel_height && check_midrailheight == 0 && $("#property_midrailheight").hasClass('required')) {
                errors++;
                // console.log('errors: ' + errors);
                error_text = 'Midrail height required for height more than ' + panel_height.toString() + 'mm. ';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_midrailheight", error_text);
                modalShowError(error_text);
            }
        }
    }


    //max height
    if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) > max_height) {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Height should not exceed ' + max_height.toString() + 'mm. ';
        width_and_height_errors = width_and_height_errors + error_text;
        width_and_height_errors_count++;
        addError("property_height", error_text);
        modalShowError(error_text);
    }

    //midrailheight should not be more than height of shutter
    if (check_midrailheight > 0 && (check_midrailheight > (check_height - 300))) {
        error_text = 'Midrail Height should not exceed height of shutter ' + (check_height - 300) + 'mm. ';
        errors++;
        // console.log('errors: ' + errors);
        addError("property_midrailheight", error_text);
        modalShowError(error_text);
    }


    //check if property frametype is selected & if the selected value is visible
    //parent is used, because input is by default hidden, but the parent is not
    if ($('input[name=property_frametype]:checked').length == 0) {
        if (installation != 27) {
            errors++;
            // console.log('errors: ' + errors);
            $("<span class=\"error-text\">Please select frame type</span>").insertBefore($("#choose-frametype"));
            //$("#choose-frametype").closest(".panel").find(".panel-collapse").collapse("show");
            error_text = 'Please select frame type';
            addError("property_frametype", error_text);
            modalShowError(error_text);
        }
    }

    var layout_code = $("#property_layoutcode").val();

    var nr_individuals = $("#property_nr_sections").val();
    if (nr_individuals) {
        layout_code = "";
        for (var i = 1; i <= nr_individuals; i++) {
            var bTxt = 'B';
            if (nr_individuals == i) {
                bTxt = '';
            }
            layout_code = layout_code + $("#property_layoutcode" + i + "").val() + bTxt;
        }
        console.log('Individual layout_code: ' + layout_code);
    }


    var layoutcode_tracked = $("#property_layoutcode_tracked").val();
    if (layout_code) {
        layout_code = layout_code.toUpperCase();
    } else {
        layout_code = layoutcode_tracked.toUpperCase();
    }
    if (layout_code.length == 0) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("#property_layoutcode").closest(".panel").find(".panel-collapse").collapse("show");
        addError("choose-style", 'Please complete with Layout Code!');
        error_text = 'Please complete with Layout Code!';
        modalShowErrorNoWarranty(error_text);
    }

    //check if property frametype is selected & if the selected value is visible
    if ($('input[name="property_trackedtype"]:checked').length == 0 && $('input[name="property_style"]:checked').val() == 35) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("<span class=\"error-text\">Please select Track Installation type</span>").insertBefore($("#trackedtype"));
        $("#trackedtype").closest(".panel").find(".panel-collapse").collapse("show");
        error_text = 'Please select Track Installation type';
        addError("property_trackedtype", error_text);
        modalShowErrorNoWarranty(error_text);
    }


    if ($('input[name="property_trackedtype"]:checked').length == 0 && $('input[name="property_style"]:checked').val() == 37) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("<span class=\"error-text\">Please select Track Installation type</span>").insertBefore($("#trackedtype"));
        $("#trackedtype").closest(".panel").find(".panel-collapse").collapse("show");
        error_text = 'Please select Track Installation type';
        addError("property_trackedtype", error_text);
        modalShowErrorNoWarranty(error_text);
    }

    if ($('input[name="property_bypasstype"]:checked').length == 0 && $('input[name="property_style"]:checked').val() == 37) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("<span class=\"error-text\">Please select By-pass Type</span>").insertBefore($("#bypasstype"));
        $("#bypasstype").closest(".panel").find(".panel-collapse").collapse("show");
        error_text = 'Please select By-pass Type';
        addError("property_bypasstype", error_text);
        modalShowErrorNoWarranty(error_text);
    }


    if ($('input[name="property_lightblocks"]:checked').length == 0 && $('input[name="property_style"]:checked').val() == 37) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("<span class=\"error-text\">Please select Light Blocks</span>").insertBefore($("#lightblocks"));
        $("#lightblocks").closest(".panel").find(".panel-collapse").collapse("show");
        error_text = 'Please select Light Blocks';
        addError("property_lightblocks", error_text);
        modalShowErrorNoWarranty(error_text);
    }


    var layoutcode_tracked = $("#property_layoutcode_tracked").val();
    var max_width = 0;
    var min_width = 0;
    var current_max_width = 0;

    if (layout_code) {
        layout_code = layout_code.toUpperCase();
    } else {
        layout_code = layoutcode_tracked.toUpperCase();
    }

    counter = 0;
    last_char = ''; //used to check if last character is different
    total_panels = 0;
    all_panels = 0;
    tracked_layout_error = false

    var id_material = $("input#property_material").val();

    var check_louvresize = $("input#property_bladesize").val();

    //calculate width   biowood-138  supreme-139  green-137   earth-187
    panel1_width = (id_material == 137 ? 890 : 890);
    panel2_width = (id_material == 137 ? 550 : 550);
    panel2_width = (id_material == 138 ? 625 : 625);
    panel2_width = (id_material == 139 ? 625 : 625);
    panel3_width = (id_material == 138 ? 550 : 550);
    panel2_width = (id_material == 188 ? 625 : 625);
    panel3_width = (id_material == 188 ? 550 : 550);
    panel3_width = (id_material == 137 ? 550 : 550);

    var panel1_width = 750;
    if (id_material == 187) {
        panel1_width = (id_material == 187 ? 1500 : 1500);
    }
    if (id_material == 139 || id_material == 138) {
        panel1_width = 890;
    }
    if (id_material == 188 || id_material == 137) {
        panel1_width = 850;
    }

    var arrayPanels = {
        'panels1': 0,
        'panels2': 0,
        'panels3': 0,
        'multiPanels': 0
    };
    var lastCharacter = '';
    var currentCharacter = '';
    for (var i = 0; i < layout_code.length; i++) {
        all_panels++;
        if (i === 0) {
            lastCharacter = layout_code.charAt(i);
        }
        currentCharacter = layout_code.charAt(i);
        console.log('======================================');
        console.log('currentCharacter ' + currentCharacter);
        console.log('lastCharacter ' + lastCharacter);

        if (style_check.indexOf('Tracked') > -1) {
            arrayPanels.multiPanels = arrayPanels.multiPanels + 1;
            console.log('Total Panels finded Tracked : ' + total_panels);
        } else {
            // If current layout character is B , C, T, G
            if (layout_code.charAt(i) != 'L' && layout_code.charAt(i) != 'R') {
                last_char = layout_code.charAt(i);
                console.log('Last Char ' + last_char);
                // continue;
                console.log('Total Panels finded : ' + total_panels);
                console.log('TOTAL PANELS BCTG ' + total_panels);
                console.log(arrayPanels);
                console.log('reset total');
                total_panels = 0;
                lastCharacter = layout_code.charAt(i);
            } else {
                // If current layout character is L or R
                // and current is different from last character
                // and last character is NOT B, C, T, G
                if (currentCharacter !== lastCharacter && 'BCTG'.indexOf(lastCharacter) < 0) {
                    console.log('Total Panels finded : ' + total_panels);
                    console.log('add to array panel1 !!! ');
                    arrayPanels.panels1 = arrayPanels.panels1 + 1;
                    total_panels = 1;
                    console.log('TOTAL PANELS RL ' + arrayPanels.panels1);
                    console.log(arrayPanels);
                    console.log('reset total');
                    lastCharacter = layout_code.charAt(i);
                } else if (currentCharacter !== lastCharacter && 'BCTG'.indexOf(lastCharacter) >= 0) {
                    // If current layout character is L or R
                    // and current is different from last character
                    // and last character is B, C, T, G
                    console.log('add to array panel1 !!! ');
                    console.log(arrayPanels.panels1);
                    arrayPanels.panels1 = arrayPanels.panels1 + 1;
                    total_panels = 1;
                    lastCharacter = layout_code.charAt(i);
                } else {
                    // If current layout character is L or R
                    // and current is same with last character
                    total_panels++;
                    lastCharacter = layout_code.charAt(i);
                    if (total_panels === 2) {
                        arrayPanels.panels2 = arrayPanels.panels2 + total_panels;
                        console.log('reset arrayPanels1');
                        // if current character is same with last, panels1 must reset
                        arrayPanels.panels1 = arrayPanels.panels1 - 1;
                    } else if (total_panels === 3) {
                        arrayPanels.panels3 = arrayPanels.panels3 + total_panels;
                        console.log('reset arrayPanels2');
                        // if current character is same with last, panels2 must reset
                        arrayPanels.panels2 = arrayPanels.panels2 - (total_panels - 1);
                    } else if (total_panels > 3) {
                        arrayPanels.multiPanels = arrayPanels.multiPanels + total_panels;
                        console.log('reset arrayPanels3');
                        // if current character is same with last, panels3 must reset
                        arrayPanels.panels3 = arrayPanels.panels3 - (total_panels - 1);
                    }
                    if (i == 0) arrayPanels.panels1 = 1;
                    console.log('TOTAL PANELS same ' + total_panels);
                    console.log(arrayPanels);
                    lastCharacter = layout_code.charAt(i);
                }
            }
        }
        multipanel_singlepanel_width = $("input#property_width").val() / total_panels;
        // console.log('multipanel_singlepanel_width ' + multipanel_singlepanel_width);
    }
    console.log(arrayPanels);

    // se verifica daca sunt caractere pentru 1 panel si se verifica daca totalul la max width poisibil pentru toate caracterele 1 panel este mai mai mic sau mai mare (eroare) decat limita sumei posibila a acestora
    current_max_width = 0;
    width_shutter = jQuery('#property_width').val();
    var error_text = "";

    var nr_individuals = $("#property_nr_sections").val();
    if (nr_individuals) {
        width_shutter = 0;
        for (var i = 1; i <= nr_individuals; i++) {
            width_shutter = width_shutter + parseFloat($("#property_width" + i + "").val());
        }
        console.log('width_shutter: ' + width_shutter);
    }

    if (arrayPanels.panels1 > 0) {

        var panel1_max_width_limit = 750;
        if (id_material == 187) {
            panel1_max_width_limit = (id_material == 187 ? 1500 : 1500);
        }
        if (id_material == 139 || id_material == 138) {
            panel1_max_width_limit = 890;
        }
        if (id_material == 188 || id_material == 137) {
            panel1_max_width_limit = 850;
        }
        if (style_check.indexOf('Solid') > -1) {
            panel1_max_width_limit = 550;
        }
        current_max_width = current_max_width + (arrayPanels.panels1 * panel1_max_width_limit);
    }
    if (arrayPanels.panels2 > 0) {

        var multi_panel_max_width_limit = 550;
        if (id_material == 138 || id_material == 139) {
            multi_panel_max_width_limit = 625;
        }
        if (id_material == 137) {
            multi_panel_max_width_limit = 500;
        }
        if (style_check.indexOf('Solid') > -1) {
            multi_panel_max_width_limit = 550;
        }
        current_max_width = current_max_width + (arrayPanels.panels2 * multi_panel_max_width_limit);
    }
    if (arrayPanels.panels3 > 0) {
        // biowood-138  supreme-139  green-137   earth-187 ecowood - 188
        var multi_panel_max_width_limit = 550;
        if (id_material == 138 || id_material == 139) {
            multi_panel_max_width_limit = 625;
        }
        if (id_material == 137) {
            multi_panel_max_width_limit = 500;
        }
        if (style_check.indexOf('Solid') > -1) {
            multi_panel_max_width_limit = 550;
        }
        current_max_width = current_max_width + (arrayPanels.panels3 * multi_panel_max_width_limit);
        console.log('arrayPanels.panels3 > 0');
    }
    if (arrayPanels.multiPanels > 0) {

        var panel1_max_width_limit = 750;
        if (id_material == 187) {
            panel1_max_width_limit = (id_material == 187 ? 1500 : 1500);
        }
        if (id_material == 139 || id_material == 138) {
            panel1_max_width_limit = 890;
        }
        if (id_material == 188 || id_material == 137) {
            panel1_max_width_limit = 850;
        }
        if (style_check.indexOf('Solid') > -1) {
            panel1_max_width_limit = 550;
        }
        current_max_width = arrayPanels.panels1 * panel1_max_width_limit;

        if (!(style_check.indexOf('Tracked') > -1)) {
            error_text = '<br/>Layout code is invalid. No more than 3 consecutive ' + last_char + ' panels allowed.';
            errors++;
            // console.log('errors: ' + errors);
            width_and_height_errors_count++;
            width_and_height_errors = width_and_height_errors + error_text;
            // console.log('counter litere: ' + counter);

            addError("property_layoutcode", error_text);
            modalShowError(error_text);
        }
    }


    // afisare erori daca sunt numai panels 1
    if (style_check.indexOf('Tracked') == -1) {
        if (arrayPanels.panels1 > 0 && arrayPanels.panels2 === 0 && arrayPanels.panels3 === 0 && arrayPanels.multiPanels === 0) {
            if (current_max_width < width_shutter) {
                console.log('width shutter: ' + width_shutter + ' width panels max ' + current_max_width);
                error_text = '<br/>Max width for single panel too high. Max width ' + panel1_max_width_limit;
                errors++;
                // console.log('errors: ' + errors);

            }
            if ((width_shutter / arrayPanels.panels1) < 200) {
                // console.log('counter litere: ' + counter);
                error_text = '<br/>Min width for single panel too low.';
                errors++;
                // console.log('errors: ' + errors);

            }
        }

        if (arrayPanels.panels2 > 0 || arrayPanels.panels3 > 0 && arrayPanels.multiPanels === 0) {
            if (current_max_width < width_shutter) {
                // console.log('counter litere: ' + counter);
                error_text = '<br/>Max width for multi-fold panels too high.';
                errors++;
                console.log('current_max_width: ' + current_max_width);

            }
            if (((width_shutter / arrayPanels.panels1) + (width_shutter / arrayPanels.panels2) + (width_shutter / arrayPanels.panels3)) < 200) {
                // console.log('counter litere: ' + counter);
                error_text = '<br/>Min width for multi-fold panels too low.';
                errors++;
                // console.log('errors: ' + errors);

            }
        }
        if (shutter_type == 'Blackout' && width_shutter > 1400) {
            error_text = error_text + '<br/>Maximum single BoB width is 1400mm';
        }
        if (errors > 0) {
            addError("property_layoutcode", error_text);
            modalShowError(error_text);
        }
    }


    //check if property frametype is selected & if the selected value is visible
    if ($('input[name="property_trackedtype"]:checked').length == 0 && $('input[name="21"]:checked').val() == 35) {
        errors_no_warranty++;
        errors++;
        // console.log('errors_no_warranty 3');
        $("<span class=\"error-text\">Please select Track Installation type</span>").insertBefore($("#trackedtype"));
        $("#trackedtype").closest(".panel").find(".panel-collapse").collapse("show");
        error_text = 'Please select Track Installation type';
        addError("property_trackedtype", error_text);
        modalShowErrorNoWarranty(error_text);
    }


    if (style_check.indexOf('Tracked') > -1) {
        // console.log('In Tracked');
        var multi_panel_max_width_limit = 890;
        // if (id_material == 187) {
        //     multi_panel_max_width_limit = 750;
        // }
        // if (id_material == 139 || id_material == 138) {
        //     multi_panel_max_width_limit = 890;
        // }
        // if (id_material == 188 || id_material == 137) {
        //     multi_panel_max_width_limit = 850;
        // }

        current_max_width = arrayPanels.multiPanels * multi_panel_max_width_limit;
        console.log('current_max_width: ' + current_max_width);
        if (current_max_width < width_shutter) {
            // console.log('current_max_width: ' + current_max_width);
            error_text = '<br/>Max width for single panel too high.';
            errors++;
            // console.log('errors: ' + errors);
            addError("property_layoutcode", error_text);
            modalShowError(error_text);
        }
        if ((width_shutter / arrayPanels.multiPanels) < 200) {
            // console.log('counter litere: ' + counter);
            error_text = '<br/>Min width for multi-fold panels too low.';
            errors++;
            // console.log('errors: ' + errors);
            addError("property_layoutcode", error_text);
            modalShowError(error_text);
        }

        if ((style_check.indexOf('Tracked') > -1) && (arrayPanels.multiPanels % 2 != 0)) {
            tracked_layout_error = true;
        }

        if (style_check.indexOf('Tracked By-Fold') > -1) {
            const layoutConfig = $('input[name="property_layoutcode_tracked"]').val().toUpperCase();
            const freeloadingVal = $('input[name="property_freefolding"]').val();
            if (freeloadingVal === "yes") {
                const output = layoutConfig.split('/');
                const count = (layoutConfig.match(/F/g) || []).length;
                let notEven = false;
                $('#layoutcode-column .error-text').remove();
                if (count <= 12) {
                    output.forEach(element => {
                        const elCount = (element.match(/F/g) || []).length;
                        if (elCount % 2 !== 0) {
                            notEven = true;
                        }
                    });
                    if (notEven) {
                        // console.log('errors: ' + errors);
                        addError("property_layoutcode_tracked", 'Layout Configuration not supported. Please correct.');
                        errors++;
                        // console.log('errors: ' + errors);
                        error_text = 'Layout Configuration not supported. Please correct.';
                        modalShowError(error_text);
                    } else {
                        tracked_layout_error = false;
                    }
                } else {
                    // console.log('errors: ' + errors);
                    addError("property_layoutcode_tracked", 'Layout Configuration not supported. Please correct.');
                    errors++;
                    // console.log('errors: ' + errors);
                    error_text = 'Layout Configuration not supported. Please correct.';
                    modalShowError(error_text);
                }
            }
        }

        if (tracked_layout_error) {
            errors++;
            // console.log('errors: ' + errors);
            addError("property_layoutcode", 'Tracked shutters require even number of panels per layout code 22');
            error_text = 'Tracked shutters require even number of panels per layout code';
            modalShowError(error_text);
        }

    }


    //calculate min width based on the number of panels (Ls&Rs)
    min_width = all_panels * 200;

    //minimum for Green panels and raised is 250mm
    if (id_material == 137) {
        min_width = all_panels * 250;

        if (style_check.indexOf('Tracked') > -1) {

        }
    }

    if (width_shutter != '' && width_shutter < min_width) {
        errors++;
        // console.log('errors: ' + errors);
        error_text = 'Width should be at least ' + min_width + 'mm. ';
        width_and_height_errors = width_and_height_errors + error_text;
        width_and_height_errors_count++;
        addError("property_width", error_text);
        modalShowError(error_text);
    }


    //For Blackout shutters a Tpost is required for >1400mm width
    if (shutter_type == 'Blackout' && ((layout_code.match(/t/ig) || []).length == 0 && (layout_code.match(/b/ig) || []).length == 0 && (layout_code.match(/c/ig) || []).length == 0) && width_shutter > 1400) {
        errors++;
        // console.log('errors: ' + errors);
        addError("property_layoutcode", 'Shutter and Blackout Blind require a T-post if width is more than 1400mm');
    }

    $indiv = $("#property_nr_sections").val();
    if (!$indiv) {
        if ((layout_code.indexOf("B") > 0 || layout_code.indexOf("C") > 0) && (style_check.indexOf('Bay') == -1) && (style_check.indexOf('Tracked') == -1)) {
            errors++;
            console.log('errors: ' + errors);
            addError("property_layoutcode", 'Please choose Bay Window style with a layout code containing B or C.');
        }
    }


    /**
     * if layout have t must contain t-post selected
     */
    if (layout_code.indexOf("T") > 0) {
        $property_tposttype = $('input[name="property_tposttype"]').val();

        // property_tposttype
        if ($('input[name=property_tposttype]:checked').length < 1) {
            errors++;
            console.log('errors: ' + errors);
            modalShowError('Please choose T-Post type.');
        }
    }


    /* clearview checks */
    var check_louvresize = $("input#property_bladesize").val();
    if (check_controltype == '96' || check_controltype == '95') {
        var split_required = false;
        var split2_required = false;

        var split_min_height = 0;
        var split_max_height = 0;

        var split2_min_height = 0;
        var split2_max_height = 0;

        var height_required_split;
        if (check_louvresize == '47') {
            height_required_split = 890;
            if (check_midrailheight > 0)
                height_required_split = 890;
        }

        if (check_louvresize == '63') {
            height_required_split = 890;
            if (check_midrailheight > 0)
                height_required_split = 890;
        }
        if (check_louvresize == '76') {
            height_required_split = 890;
            if (check_midrailheight > 0)
                height_required_split = 890;
        }
        if (check_louvresize == '89') {
            height_required_split = 890;
            if (check_midrailheight > 0)
                height_required_split = 890;
        }

        if (id_material == '187') height_required_split = 1500;

        if (check_midrailheight == 0 && check_totheight == 0 && check_height > height_required_split) {
            split_min_height = 0;
            split_max_height = height_required_split;
            split_required = true;

            //**REMOVE THE BELOW ADD SOMEWHERE ELSE
            //if(parseInt(check_controlsplitheight)==0 && check_height>height_required_split){
            //    split_required = true;
            //}
        } else if (check_midrailheight > 0 || check_totheight > 0) {

            var split_panel_at_height = 0;
            if (check_midrailheight > 0) split_panel_at_height = parseInt(check_midrailheight);
            if (check_totheight > 0) split_panel_at_height = parseInt(check_totheight);
            var panel1_height = split_panel_at_height;
            var panel2_height = check_height - panel1_height;

            // console.log("Panel 1 height:" + panel1_height);
            // console.log("Panel 2 height:" + panel2_height);
            // console.log("Height required split:" + height_required_split);

            if (panel1_height > height_required_split && panel2_height > height_required_split) {
                split_required = true;
                split2_required = true;

                split_min_height = 0;
                split_max_height = split_panel_at_height;

                split2_min_height = split_panel_at_height + 1;
                split2_max_height = check_height;
            } else if (panel1_height > height_required_split || panel2_height > height_required_split) {
                split_required = true;
                if (panel1_height > height_required_split) {
                    split_min_height = 0;
                    split_max_height = split_panel_at_height;
                } else {
                    split_min_height = split_panel_at_height + 1;
                    split_max_height = check_height;
                }
            }
        }

        if (check_midrailheight > 0 && check_controlsplitheight > 0) {
            var distance = Math.abs(check_midrailheight - check_controlsplitheight);
            if (distance <= 10) {
                error_text = '<br/>Distance between split height and midrail should be more than 10mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight", error_text);
            }
        }

        if (check_midrailheight > 0 && parseInt(check_controlsplitheight2) > 0) {
            var distance = Math.abs(check_midrailheight - parseInt(check_controlsplitheight2));
            if (distance <= 10) {
                error_text = '<br/>Distance between split height 2 and midrail should be more than 10mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight2", error_text);
            }
        }

        if (split_required) {
            $("#property_controltype").select2("val", 95); //change to clearview split if not already changed
            $("#control-split-height").show();
            if (parseInt(check_controlsplitheight) == 0) {
                error_text = '<br/>Split height is required for ' + check_louvresize + 'mm and height ' + check_height + 'mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight", error_text);
            }

            if (check_controlsplitheight > split_max_height) {
                error_text = '<br/>Split height should be less than ' + split_max_height + 'mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight", error_text);
            }
            if (check_controlsplitheight < split_min_height) {
                error_text = '<br/>Split height should be more than ' + split_min_height + 'mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight", error_text);
            }
        }

        if (split2_required) {
            $("#property_controltype").select2("val", 95); //change to clearview split if not already changed
            $("#control-split-height").show();
            $("#property_controlsplitheight2").show();
            if (parseInt(check_controlsplitheight2) == 0) {
                error_text = '<br/>Second split height is required for ' + check_louvresize + 'mm and height ' + check_height + 'mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight2", error_text);
            }

            if (check_controlsplitheight2 > split2_max_height) {
                error_text = '<br/>Second split height should be less than ' + split2_max_height + 'mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight2", error_text);
            }
            if (check_controlsplitheight2 < split2_min_height) {
                error_text = '<br/>Second split height should be more than ' + split2_min_height + 'mm';
                errors++;
                // console.log('errors: ' + errors);
                width_and_height_errors_count++;
                width_and_height_errors = width_and_height_errors + error_text;
                addError("property_controlsplitheight2", error_text);
            }
        } else {
            $("#property_controlsplitheight2").hide();
        }
    }


    if ($('input[name=property_style]:checked').length > 0) {
        style_check = $('input[name=property_style]:checked').data('title');
        if (style_check.indexOf('Shaped') > -1) {
            existingShapeFile = $('#provided-shape').html().trim();
            newShapeFile = $('#attachment').val();
            newDrawFile = $('#attachment_draw').val();
            if (existingShapeFile == '' && newShapeFile == '' && newDrawFile == '') {
                errors++;
                // console.log('errors: ' + errors);
                addError("shape-upload-container", 'Please provide the desired shape for style "Shaped & French Cut Out"');
            }
        }
    }


    if (property_material == 139 || property_material == 138 || property_material == 188) {
        if ((property_midrailheight > 1800) && jQuery('#property_midrailheight').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height should be less than 1800');
        }
        if ((property_midrailheight > property_height - 300) && jQuery('#property_midrailheight').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height should be less than ' + property_height - 300);
        }
        if ((property_midrailheight2 > 2700) && jQuery('#property_midrailheight2').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height 2 should be less than 2700');
        }
        if ((property_midrailheight2 > property_height - 300) && jQuery('#property_midrailheight2').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height 2 should be less than ' + property_height - 300);
        }
        if ((property_midraidevider1 > 3000) && jQuery('#property_midraidevider1').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider1 should be less than 3000');
        }
        if ((property_midraidevider1 > property_height - 300) && jQuery('#property_midraidevider1').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider1 should be less than ' + property_height - 300);
        }
        if ((property_midraidevider2 > 3000) && jQuery('#property_midraidevider2').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider2 should be less than 3000');
        }
        if ((property_midraidevider2 > property_height - 300) && jQuery('#property_midraidevider2').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider2 should be less than ' + property_height - 300);
        }

    } else if (property_material == 137) {
        if ((property_midrailheight > 1500) && jQuery('#property_midrailheight').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height should be less than 1500');
        }
        if ((property_midrailheight > property_height - 300) && jQuery('#property_midrailheight').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height should be less than ' + property_height - 300);
        }
        if ((property_midrailheight2 > 2400) && jQuery('#property_midrailheight2').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height 2 should be less than 2400');
        }
        if ((property_midrailheight2 > property_height - 300) && jQuery('#property_midrailheight2').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height 2 should be less than ' + property_height - 300);
        }
        if ((property_midraidevider1 > 2700) && jQuery('#property_midraidevider1').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider1 should be less than 2700');
        }
        if ((property_midraidevider1 > property_height - 300) && jQuery('#property_midraidevider1').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider1 should be less than ' + property_height - 300);
        }
        if ((property_midraidevider2 > 2700) && jQuery('#property_midraidevider2').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider2 should be less than 2700');
        }
        if ((property_midraidevider2 > property_height - 300) && jQuery('#property_midraidevider2').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider2 should be less than ' + property_height - 300);
        }

    } else if (property_material == 187) {
        if ((property_midrailheight > 1500) && jQuery('#property_midrailheight').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height should be less than 1500');
        }
        if ((property_midrailheight > property_height - 300) && jQuery('#property_midrailheight').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height should be less than ' + property_height - 300);
        }
        if ((property_midrailheight2 > 2700) && jQuery('#property_midrailheight2').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height 2 should be less than 2700');
        }
        if ((property_midrailheight2 > property_height - 300) && jQuery('#property_midrailheight2').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail Height 2 should be less than ' + property_height - 300);
        }
        if ((property_midraidevider1 > 3000) && jQuery('#property_midraidevider1').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider1 should be less than 3000');
        }
        if ((property_midraidevider1 > property_height - 300) && jQuery('#property_midraidevider1').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider1 should be less than ' + property_height - 300);
        }
        if ((property_midraidevider2 > 3000) && jQuery('#property_midraidevider2').is(":visible")) {
            //alert('Midrail Height must be between 1800 and 2700');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider2 should be less than 3000');
        }
        if ((property_midraidevider2 > property_height - 300) && jQuery('#property_midraidevider2').is(":visible")) {
            //alert('Midrail Height must be between 1500 and 2400');
            errors++;
            // console.log('errors: ' + errors);
            modalShowError('Midrail divider2 should be less than ' + property_height - 300);
        }

    }


    console.log('errors ' + errors_no_warranty);
    console.log('errors_no_warranty ' + errors_no_warranty);


}


/**************************************************
 Start add product form
 **************************************************/

jQuery('#add-product-single-form .btn-success').on('click', function (e) {

    e.preventDefault();
    resetErrors();


    jQuery(document).ajaxStart(function () {
        jQuery('.spinner').show();
    });

    jQuery(document).ajaxComplete(function () {
        jQuery('.spinner').hide();
    });

    if (jQuery('#property_layoutcode').val()) {
        jQuery('#property_layoutcode').val().toUpperCase();
    }


    // verify limits and set errors

    var property_material = jQuery('#property_material').val();
    var installation = jQuery('input[name="property_style"]:checked').val();
    var property_width = jQuery('#property_width').val();
    var property_height = jQuery('#property_height').val();
    var property_midrailheight = jQuery('#property_midrailheight').val();
    var property_midrailheight2 = jQuery('#property_midrailheight2').val();
    var property_bladesize = jQuery('#property_bladesize').val();
    var property_room_other = jQuery('#property_room_other').val();
    var property_stile = jQuery('input[name=property_stile]:checked').val();
    var property_solidtype = jQuery('input[name=property_solidtype]:checked').val();
    var property_hingecolour = jQuery('#property_hingecolour').val();
    var property_shuttercolour = jQuery('#property_shuttercolour').val();
    var property_blackoutblindcolour = jQuery('#property_blackoutblindcolour').val();
    var property_controltype = jQuery('#property_controltype').val();
    var property_midraidevider1 = jQuery('#property_midraidevider1').val();
    var property_midraidevider2 = jQuery('#property_midraidevider2').val();
    var errors = 0;
    var errors_no_warranty = 0;
    var nowarranty_checked = $("#property_nowarranty").prop("checked");

    let tracked_layout_error;
    if (!nowarranty_checked) {


        checkErrors();


        /**
         * verify cart items name
         * @type {any}
         */
        let cart_items_name = $('input[name="cart_items_name"]').val();
        let itemsName = JSON.parse(cart_items_name);
        console.log(itemsName);
        if (itemsName !== null) {
            if (itemsName.includes(property_room_other)) {
                errors_no_warranty++;
                errors++;

                const error_text = 'Item name exists in order, please change!';
                modalShowErrorNoWarranty(error_text);
            }
        }

        console.log('errors ', errors);

        if (errors === 0 && errors_no_warranty === 0) {


            var formser = jQuery('#add-product-single-form').serialize();
            var svg = jQuery('#canvas_container1').html();

            // console.log(formser);
            //alert(formser);
            // console.log('submit 1');
            var url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod.php";
            var nr_individuals = $("#property_nr_sections").val();
            if (nr_individuals) {
                url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-individual.php";
            }


            $.ajax({
                method: "POST",
                url: url_ajax,
                data: {
                    prod: formser,
                    // svg: svg
                }
            })
                .done(function (data) {

                    console.log(data);
                    alert('Shutter added to order!');
                    //jQuery('.show-prod-info').html(data);
                    var edit_customer = jQuery('input[name="edit_customer"]').val();
                    var order_edit = jQuery('input[name="order_edit"]').val();

                    setTimeout(function () {
                        if (edit_customer.length !== 0 && order_edit.length !== 0) {
                            window.location.replace(document.referrer);
                        } else {
                            window.location.replace("/checkout");
                        }
                    }, 500);
                });

        }


        ///////////// END CALCULATE ORIGINAL SIZES


    } else {
        checkErrors();


        /**
         * verify cart items name
         * @type {any}
         */
        let cart_items_name = $('input[name="cart_items_name"]').val();
        let itemsName = JSON.parse(cart_items_name);
        console.log(itemsName);
        if (itemsName !== null) {
            if (itemsName.includes(property_room_other)) {
                errors_no_warranty++;
                errors++;

                const error_text = 'Item name exists in order, please change!';
                modalShowErrorNoWarranty(error_text);
            }
        }


        if (errors_no_warranty === 0) {

            var formser = jQuery('#add-product-single-form').serialize();
            var svg = jQuery('#canvas_container1').html();


            // console.log(formser);
            //alert(formser);

            var url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod.php";
            var nr_individuals = $("#property_nr_sections").val();
            if (nr_individuals) {
                url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-individual.php";
            }


            $.ajax({
                method: "POST",
                url: url_ajax,
                data: {
                    prod: formser,
                    // svg: svg
                }
            })
                .done(function (data) {
                    console.log(data);
                    alert('Shutter added to order!');
                    //jQuery('.show-prod-info').html(data);
                    var edit_customer = jQuery('input[name="edit_customer"]').val();
                    var order_edit = jQuery('input[name="order_edit"]').val();

                    setTimeout(function () {
                        if (edit_customer.length !== 0 && order_edit.length !== 0) {
                            window.location.replace(document.referrer);
                        } else {
                            window.location.replace("/checkout");
                        }
                    }, 500);
                });

        }

    }
});


/***********************************************************
 **   Update product added by customer   **
 ***********************************************************/


jQuery('#add-product-single-form .update-btn').on('click', function (e) {

    e.preventDefault();
    resetErrors();


    console.log('in update cusrom-script');

    jQuery(document).ajaxStart(function () {
        jQuery('.spinner').show();
    });

    jQuery(document).ajaxComplete(function () {
        jQuery('.spinner').hide();
    });
    if (jQuery('#property_layoutcode').val()) {
        jQuery('#property_layoutcode').val().toUpperCase();
    }

    // verify limits and set errors

    var property_material = jQuery('#property_material').val();
    var installation = jQuery('input[name="property_style"]:checked').val();
    var property_width = jQuery('#property_width').val();
    var property_height = jQuery('#property_height').val();
    var property_midrailheight = jQuery('#property_midrailheight').val();
    var property_midrailheight2 = jQuery('#property_midrailheight2').val();
    var property_bladesize = jQuery('#property_bladesize').val();
    console.log(property_bladesize);
    var property_room_other = jQuery('#property_room_other').val();
    var property_stile = jQuery('input[name=property_stile]:checked').val();
    var property_solidtype = jQuery('input[name=property_solidtype]:checked').val();
    var property_hingecolour = jQuery('#property_hingecolour').val();
    var property_shuttercolour = jQuery('#property_shuttercolour').val();
    var property_blackoutblindcolour = jQuery('#property_blackoutblindcolour').val();
    var property_controltype = jQuery('#property_controltype').val();
    var property_midraidevider1 = jQuery('#property_midraidevider1').val();
    var property_midraidevider2 = jQuery('#property_midraidevider2').val();
    var errors = 0;
    var errors_no_warranty = 0;
    var nowarranty_checked = $("#property_nowarranty").prop("checked");

    if (!nowarranty_checked) {


        checkErrors();


        console.log('errors_no_warranty ' + errors_no_warranty);
        if (errors === 0 && errors_no_warranty === 0) {

            var formser = jQuery('#add-product-single-form').serialize();
            // var svg = jQuery('#canvas_container1').html();


            //// console.log(formser);
            //alert(formser);
            // console.log('submit 2');
            var url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update.php";
            var nr_individuals = $("#property_nr_sections").val();
            if (nr_individuals) {
                url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update-individual.php";
            }


            $.ajax({
                method: "POST",
                url: url_ajax,
                data: {
                    prod: formser,
                    // svg: svg
                }
            })
                .done(function (data) {
                    //e.preventDefault();
                    console.log(data);
                    alert('Shutter updated to order!');
                    //jQuery('.show-prod-info').html(data);
                    var edit_customer = jQuery('input[name="edit_customer"]').val();
                    var order_edit = jQuery('input[name="order_edit"]').val();

                    setTimeout(function () {
                        if (edit_customer.length !== 0 && order_edit.length !== 0) {
                            window.location.replace(document.referrer);
                        } else {
                            window.location.replace("/checkout");
                        }
                    }, 500);
                });

        }


        ///////////// END CALCULATE ORIGINAL SIZES


    } else {
        checkErrors();


        if (errors_no_warranty === 0) {
            var formser = jQuery('#add-product-single-form').serialize();
            // var svg = jQuery('#canvas_container1').html();


            // console.log(formser);
            //alert(formser);
            // console.log('submit 3');
            var url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update.php";
            var nr_individuals = $("#property_nr_sections").val();
            if (nr_individuals) {
                url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update-individual.php";
            }


            $.ajax({
                method: "POST",
                url: url_ajax,
                data: {
                    prod: formser,
                    // svg: svg
                }
            })
                .done(function (data) {
                    // json encode data

                    console.log(data);
                    alert('Shutter updated to order!');
                    //jQuery('.show-prod-info').html(data);
                    var edit_customer = jQuery('input[name="edit_customer"]').val();
                    var order_edit = jQuery('input[name="order_edit"]').val();

                    setTimeout(function () {
                        if (edit_customer.length !== 0 && order_edit.length !== 0) {
                            window.location.replace(document.referrer);
                        } else {
                            window.location.replace("/checkout");
                        }
                    }, 500);
                });
        }
    }


// custom script json


    var property_values = getSomething();

    function getSomething() {

        $.getJSON("/wp-content/plugins/shutter-module/ajax/shutter-values.php", function () {
            // console.log("success");
        })
            .done(function (data) {

                localStorage.setItem("prop_val", JSON.stringify(data));
                var result = JSON.stringify(data);
                sessionStorage.prop_val = JSON.stringify(data);
                //property_values.push( sessionStorage.prop_val );
                //// console.log( "JSON success in get " + JSON.stringify(data) );
                return result;
            });
        //return result;
        // }
    }

    var property_values = localStorage.getItem("prop_val");
    // console.log("json values outside get: " + property_values);
    // alert(property_values);


//      var fields = [];

// for (i = 0; i < property_values.length; i++) {
//     if (property_values[i].all_products == 0) {
//         fields.push(property_values[i].property_id);
//     }
// }

// return uniqueItems(fields);


// custom script json end


});


/***********************************************************
 **   Update product added by Admin   **
 ***********************************************************/


jQuery('#add-product-single-form .update-btn-admin').on('click', function (e) {

    e.preventDefault();
    resetErrors();


    jQuery(document).ajaxStart(function () {
        jQuery('.spinner').show();
    });

    jQuery(document).ajaxComplete(function () {
        jQuery('.spinner').hide();
    });

    if (jQuery('#property_layoutcode').val()) {
        jQuery('#property_layoutcode').val().toUpperCase();
    }


    // verify limits and set errors

    var property_material = jQuery('#property_material').val();
    var installation = jQuery('input[name="property_style"]:checked').val();
    var property_width = jQuery('#property_width').val();
    var property_height = jQuery('#property_height').val();
    var property_midrailheight = jQuery('#property_midrailheight').val();
    var property_midrailheight2 = jQuery('#property_midrailheight2').val();
    var property_bladesize = jQuery('#property_bladesize').val();
    console.log(property_bladesize);
    var property_room_other = jQuery('#property_room_other').val();
    var property_stile = jQuery('input[name=property_stile]:checked').val();
    var property_solidtype = jQuery('input[name=property_solidtype]:checked').val();
    var property_hingecolour = jQuery('#property_hingecolour').val();
    var property_shuttercolour = jQuery('#property_shuttercolour').val();
    var property_blackoutblindcolour = jQuery('#property_blackoutblindcolour').val();
    var property_controltype = jQuery('#property_controltype').val();
    var property_midraidevider1 = jQuery('#property_midraidevider1').val();
    var property_midraidevider2 = jQuery('#property_midraidevider2').val();
    var errors = 0;
    var errors_no_warranty = 0;
    var nowarranty_checked = $("#property_nowarranty").prop("checked");

    if (!nowarranty_checked) {

        checkErrors();


        console.log('errors_no_warranty ' + errors_no_warranty);
        if (errors === 0 && errors_no_warranty === 0) {

            var formser = jQuery('#add-product-single-form').serialize();
            // var svg = jQuery('#canvas_container1').html();


            // console.log(formser);
            //alert(formser);
            var url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update.php";
            var nr_individuals = $("#property_nr_sections").val();
            if (nr_individuals) {
                url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update-individual.php";
            }


            $.ajax({
                method: "POST",
                url: url_ajax,
                data: {
                    prod: formser,
                    // svg: svg
                }
            })
                .done(function (data) {
                    console.log(data);
                    alert('Shutter updated to order!');
                    //jQuery('.show-prod-info').html(data);
                    // location.reload();
                    window.location.replace(document.referrer);
                });

        }


        ///////////// END CALCULATE ORIGINAL SIZES


    } else {


        console.log('5 property_controltype.length ' + property_controltype.length);

        console.log('before property_controltype ');
        if ((property_controltype.length === 0) && !jQuery('#s2id_property_controltype').is(":hidden")) {
            console.log('inside property_controltype');
            errors_no_warranty++;
            errors++;
            // console.log('errors_no_warranty 3');
            $("#property_controltype").closest(".panel").find(".panel-collapse").collapse("show");
            addError("property_controltype", 'Please select control type!');
            error_text = 'Please select controltype';
            modalShowErrorNoWarranty(error_text);
        }
        console.log('afeter property_controltype ');

        if (errors_no_warranty === 0) {

            var formser = jQuery('#add-product-single-form').serialize();
            // var svg = jQuery('#canvas_container1').html();


            // console.log(formser);
            //alert(formser);
            var url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update.php";
            var nr_individuals = $("#property_nr_sections").val();
            if (nr_individuals) {
                url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-prod-update-individual.php";
            }


            $.ajax({
                method: "POST",
                url: url_ajax,
                data: {
                    prod: formser,
                    // svg: svg
                }
            })
                .done(function (data) {
                    console.log(data);
                    alert('Shutter updated to order!');
                    //jQuery('.show-prod-info').html(data);
                    setTimeout(function () {
                        //location.reload(true);
                        window.location.replace(document.referrer);
                    }, 500);
                });
        }
    }


// custom script json


    var property_values = getSomething();

    function getSomething() {

        $.getJSON("/wp-content/plugins/shutter-module/ajax/shutter-values.php", function () {
            // console.log("success");
        })
            .done(function (data) {

                localStorage.setItem("prop_val", JSON.stringify(data));
                var result = JSON.stringify(data);
                sessionStorage.prop_val = JSON.stringify(data);
                //property_values.push( sessionStorage.prop_val );
                //// console.log( "JSON success in get " + JSON.stringify(data) );
                return result;
            });
        //return result;
        // }
    }

    var property_values = localStorage.getItem("prop_val");
    // console.log("json values outside get: " + property_values);
    // alert(property_values);


//      var fields = [];

// for (i = 0; i < property_values.length; i++) {
//     if (property_values[i].all_products == 0) {
//         fields.push(property_values[i].property_id);
//     }
// }

// return uniqueItems(fields);


// custom script json end


});


// Batten submit form

jQuery('#add-product-single-form .btn.btn-primary.submit-batten').on('click', function (e) {
    e.preventDefault();
    resetErrors();

    var property_width = jQuery('#property_width').val();
    var property_height = jQuery('#property_height').val();
    var property_depth = jQuery('#property_depth').val();
    var property_room_other = jQuery('#property_room_other').val();
    var id_material = jQuery("#property_material").val();
    console.log('id_material: ' + id_material);
    //biowood-138, supreme-139, earth-187, ecowood-188, green-137
    console.log('parseFloat(jQuery("#property_height").val()) ' + parseFloat(jQuery("#property_height").val()));
    console.log('id_material !== 188 || id_material !== 137 ' + id_material !== 188 || id_material !== 137);
    if (parseFloat(property_depth) < 3 && (id_material == 188 || id_material == 137)) {
        error_text = 'The minimum size of the batten can not be less than 3mm.';
        addError("property_depth", error_text);
        modalShowError(error_text);

    } else if (parseFloat(property_height) < 3 && (id_material == 188 || id_material == 137)) {
        error_text = 'The minimum size of the batten can not be less than 3mm.';
        addError("property_height", error_text);
        modalShowError(error_text);

    } else if (parseFloat(property_width) < 3 && (id_material == 188 || id_material == 137)) {
        error_text = 'The minimum size of the batten can not be less than 3mm.';
        addError("property_width", error_text);
        modalShowError(error_text);

    } else if (parseFloat(property_height) < 5 && (id_material == 138 || id_material == 139 || id_material == 187)) {
        error_text = 'The minimum size of the batten can not be less than 5mm.';
        addError("property_height", error_text);
        modalShowError(error_text);
        console.log('property_height The minimum size of the batten can not be less than 5mm.');
    } else if (parseFloat(property_width) < 5 && (id_material == 138 || id_material == 139 || id_material == 187)) {
        error_text = 'The minimum size of the batten can not be less than 5mm.';
        addError("property_width", error_text);
        modalShowError(error_text);
        console.log('property_width The minimum size of the batten can not be less than 5mm.');
    } else if (parseFloat(property_depth) < 5 && (id_material == 138 || id_material == 139 || id_material == 187)) {
        error_text = 'The minimum size of the batten can not be less than 5mm.';
        addError("property_depth", error_text);
        modalShowError(error_text);
        console.log('property_depth The minimum size of the batten can not be less than 5mm.');
    } else if (property_width === '' || property_height === '' || property_depth === '' || property_room_other === '') {

        alert('Please compplete all the properties fields');

    } else {

        var formser = jQuery('#add-product-single-form').serialize();
        //var svg = jQuery('#canvas_container1').html();


        // console.log(formser);
        //alert(formser);
        // console.log('submit 4');

        $.ajax({
            method: "POST",
            url: "/wp-content/plugins/shutter-module/ajax/ajax-batten.php",
            data: {
                prod: formser
            }
        })
            .done(function (data) {

                console.log(data);
                alert('Batten added!');
                jQuery('.show-prod-info').html(data);

                var edit_customer = jQuery('input[name="edit_customer"]').val();
                var order_edit = jQuery('input[name="order_edit"]').val();

                setTimeout(function () {
                    if (edit_customer.length !== 0 && order_edit.length !== 0) {
                        window.location.replace(document.referrer);
                    } else {
                        window.location.replace("/checkout");
                    }
                }, 500);
            });

    }

});


// Print action svg

jQuery(".print-drawing").click(function () {

    var mywindow = window.open('', 'PRINT', 'height=700,width=800');

    mywindow.document.write('<html><head><title>' + document.title + '</title>');
    mywindow.document.write('</head><body >');
    mywindow.document.write('<h1>' + document.title + '</h1>');
    mywindow.document.write(document.getElementById('canvas_container1').innerHTML);
    mywindow.document.write('</body></html>');

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    mywindow.print();
    mywindow.close();

    return true;

});


jQuery(document).ready(function () {
    for (var i = 0; i < 6; i++) {
        if (jQuery("#property_t" + i).length != 0) {
            // console.log('exists t' + i);
            var t = jQuery('#t' + i).val();
            jQuery('#property_t' + i).val(t);
        }
        if (jQuery("#property_b" + i).length != 0) {
            // console.log('exists b' + i);
            var b = jQuery('#b' + i).val();
            jQuery('#property_b' + i).val(b);
        }
    }

    if (jQuery('#property_layoutcode').val()) {
        jQuery('#property_layoutcode').val().toUpperCase();
    }

    setTimeout(function () {
        if (jQuery('#property_frametype').val()) {
            var val_frametype = jQuery('#property_frametype').val();
            if (val_frametype == 144) {
                jQuery('input[value="144"]').attr('checked');
                // console.log('checked 144');
            } else if (val_frametype == 143) {
                jQuery('input[value="143"]').attr('checked');
                // console.log('checked 143');
            }
        }
    }, 2000);

});


// show stile iamges by material selected
jQuery("#property_material").change(function () {

    var material_id = jQuery(this).val();
    // console.log(material_id);
    jQuery('.tpost-img label').hide();
    var tpost = false;
    for (var i = 0; i < 6; i++) {
        if (jQuery("#property_t" + i).length != 0) {
            console.log('exists t' + i);
            var t = jQuery('#t' + i).val();
            jQuery('#property_t' + i).val(t);
            tpost = true;
        }
    }

    // Earth 187
    if (material_id == 187) {
        // console.log('show earth img');
        jQuery('#stile-img-earth').show();
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-supreme').hide();
        jQuery('#stile-img-biowood').hide();
        jQuery('#stile-img-green').hide();
        jQuery('#stile-img-ecowood').hide();
        jQuery('#stile-img-ecowoodPlus').hide();
        jQuery('#stile-img-biowoodPlus').hide();
        //tpost-type
        if (tpost) {
            jQuery('.type-img-earth').show();
            jQuery('.type-img-earth').parent().show();
        }
    }
    // Supreme 139
    else if (material_id == 139) {
        // console.log('show Supreme img');
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-supreme').show();
        jQuery('#stile-img-earth').hide();
        jQuery('#stile-img-ecowood').hide();
        jQuery('#stile-img-biowood').hide();
        jQuery('#stile-img-green').hide();
        jQuery('#stile-img-ecowoodPlus').hide();
        jQuery('#stile-img-biowoodPlus').hide();
        //tpost-type

        if (tpost) {
            jQuery('.type-img-supreme').show();
            jQuery('.type-img-supreme').parent().show();
        }
    }
    // Biowood 138
    else if (material_id == 138) {
        // console.log('show Biowood img');
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-biowoodPlus').show();
        jQuery('#stile-img-biowood').hide();
        jQuery('#stile-img-supreme').hide();
        jQuery('#stile-img-earth').hide();
        jQuery('#stile-img-ecowood').hide();
        jQuery('#stile-img-green').hide();
        jQuery('#stile-img-ecowoodPlus').hide();
        //tpost-type

        if (tpost) {
            jQuery('.type-img-biowoodPlus').show();
            jQuery('.type-img-biowoodPlus').parent().show();
        }
    }
    // Biowood 6
    else if (material_id == 6) {
        // console.log('show Biowood img');
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-biowood').show();
        jQuery('#stile-img-biowoodPlus').hide();
        jQuery('#stile-img-supremePlus').hide();
        jQuery('#stile-img-supreme').hide();
        jQuery('#stile-img-earth').hide();
        jQuery('#stile-img-ecowood').hide();
        jQuery('#stile-img-ecowoodPlus').hide();
        //tpost-type
        if (tpost) {
            jQuery('.type-img-biowood').show();
            jQuery('.type-img-biowood').parent().show();
            console.log('jQuery(\'.type-img-biowood\').show();');
        }
    }
    // ecowood plus
    else if (material_id == 5) {
        // console.log('show Green img');
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-ecowoodPlus').show();
        jQuery('#stile-img-supreme').hide();
        jQuery('#stile-img-green').hide();
        jQuery('#stile-img-biowood').hide();
        jQuery('#stile-img-biowoodPlus').hide();
        jQuery('#stile-img-earth').hide();
        jQuery('#stile-img-ecowood').hide();
        //tpost-type
        if (tpost) {
            jQuery('.type-img-ecowoodPlus').show();
            jQuery('.type-img-ecowoodPlus').parent().show();
            console.log('jQuery(\'.type-img-ecowoodPlus\').show();');
        }
    }
    // Green 137
    else if (material_id == 137) {
        // console.log('show Green img');
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-green').show();
        jQuery('#stile-img-supreme').hide();
        jQuery('#stile-img-biowood').hide();
        jQuery('#stile-img-earth').hide();
        jQuery('#stile-img-ecowood').hide();
        jQuery('#stile-img-ecowoodPlus').hide();
        jQuery('#stile-img-biowoodPlus').hide();
        //tpost-type

        if (tpost) {
            jQuery('.type-img-green').show();
            jQuery('.type-img-green').parent().show();
        }
    }
    // ecowood 188
    else if (material_id == 188) {
        // console.log('show ecowood img');
        jQuery('.tpost-img').hide();
        jQuery('#stile-img-ecowood').show();
        jQuery('#stile-img-supreme').hide();
        jQuery('#stile-img-biowood').hide();
        jQuery('#stile-img-earth').hide();
        jQuery('#stile-img-green').hide();
        jQuery('#stile-img-ecowoodPlus').hide();
        jQuery('#stile-img-biowoodPlus').hide();
        //tpost-type

        if (tpost) {
            jQuery('.type-img-ecowood').show();
            jQuery('.type-img-ecowood').parent().show();
        }

    }


    setTimeout(function () {
        jQuery('#choose-stiletype label, #stile-img-biowoodPlus label, #stile-img-ecowood label, #stile-img-earth label, #stile-img-supreme label, #stile-img-biowood label').show();
        // console.log('show stile images by material selected');
    }, 500);

});


jQuery(document).ready(function () {
    var val_frametype = jQuery('#property_frametype').val();

    // console.log('Hidden frame: ' + jQuery('#property_frametype').val());

    if (jQuery('#property_frametype').val() == 143) {
        jQuery('input[name="property_frametype"][value="143"]').click();
    }
    if (jQuery('#property_frametype').val() == 144) {
        jQuery('input[name="property_frametype"][value="144"]').click();
    }

    jQuery("select#buildout-select").change(function () {
        // Check input( $( this ).val() ) for validity here
        if (jQuery(this).val() === 'flexible') {
            // console.log('flexible');
            jQuery('input[name="property_b_buildout1"]').prop('checked', false);
            jQuery('.pull-left.extra-column-buildout.property_b_buildout1').hide();
        } else {
            jQuery('.pull-left.extra-column-buildout.property_b_buildout1').show();
        }
    });
});


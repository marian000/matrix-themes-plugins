import {names, property_fields, property_values} from './data/blackout-data.js';

//get the property code based on id of property eg: property with id 9 = property_fit

// Define constants for minimum and maximum widths to avoid magic numbers in the code
const MIN_WIDTH = 200;
const MAX_WIDTH_SINGLE_PANEL = 890;
const MAX_WIDTH_MULTIPLE_PANEL = 550; // This applies when counter is 2
const MAX_WIDTH_EXCEPTION = 6250; // This seems to be an exceptionally high value; adjust based on actual requirements

jQuery.noConflict();
(function ($) {
    $(function () {


        // ========== START - customize some properties by user =========
        let shutter_type = "Blackout";
        let idCustomer = null;
        let idDealer = null;
        let selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}";

        idCustomer = jQuery('input[name="customer_id"]').val();
        idDealer = jQuery('input[name="dealer_id"]').val();

        // "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
        if (idCustomer == 274 || idDealer == 274) {
            selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"137\"]}"
        }
        // console.log('idCustomer ', idCustomer);
        // console.log('selectedPropertyValuesEcowood ', selectedPropertyValuesEcowood);
        // ========== END - customize some properties by user =========


        $(".show-next-panel").click(function () {
            $(this).closest(".panel").find(".panel-collapse").collapse("hide");
            $(this).closest(".panel").next().find(".panel-collapse").collapse("show");
            return false;
        });

        function getPropertyCodeById(id) {
            let code = '';
            for (let i = 0; i < property_fields.length; i++) {
                if (property_fields[i].id == id) {
                    code = property_fields[i].code;
                }
            }
            return code;
        }

        //get which fields depend on the specific field
        function getRelatedFields(field_id) {
            let fields = [];

            for (let i = 0; i < property_values.length; i++) {
                if (property_values[i].all_property_values == 0) {
                    let selected_property_values = JSON.parse(property_values[i].selected_property_values);
                    for (let j = 0; j < selected_property_values.property_value_ids.length; j++) {
                        if (field_id == selected_property_values.property_field) {
                            fields.push(property_values[i].property_id);
                        }
                    }
                }
            }

            return uniqueItems(fields);
        }

        //get which fields depend on a product
        function getRelatedFieldsByProduct() {
            let fields = [];

            for (let i = 0; i < property_values.length; i++) {
                if (property_values[i].all_products == 0) {
                    fields.push(property_values[i].property_id);
                }
            }

            return uniqueItems(fields);
        }

        //get the data for a field, based on another field's value
        function getRelatedFieldData(property_id, changed_property_id, value) {
            let data = [];
            for (let i = 0; i < property_values.length; i++) {
                if (property_values[i].property_id == property_id) {
                    if (property_values[i].all_property_values == 0) {
                        let selected_property_values = JSON.parse(property_values[i].selected_property_values);
                        if (changed_property_id == selected_property_values.property_field) {
                            for (let j = 0; j < selected_property_values.property_value_ids.length; j++) {
                                if (selected_property_values.property_value_ids[j] == value) {
                                    data.push(property_values[i]);
                                }
                            }
                        }
                    } else {
                        data.push(property_values[i]);
                    }
                }
            }
            return data;
        }

        //get the data for a field, based on another field's value
        function getRelatedFieldDataByProductId(property_id, value) {
            let data = [];
            for (let i = 0; i < property_values.length; i++) {
                if (property_values[i].property_id == property_id) {
                    if (property_values[i].all_products == 0) {
                        let selected_products = JSON.parse(property_values[i].selected_products);
                        for (let j = 0; j < selected_products.product_ids.length; j++) {
                            if (selected_products.product_ids[j] == value) {
                                data.push(property_values[i]);
                            }
                        }
                    } else {
                        data.push(property_values[i]);
                    }
                }

            }
            return data;
        }

        //get all field data
        function getAllFieldData(property_id) {
            let data = [];
            let idCustomer = $('input[name="customer_id"]').val();
            if (idCustomer == 274 || idDealer == 274) {
                for (let i = 0; i < property_values.length; i++) {
                    if (property_values[i].property_id == property_id && property_values[i].value !== 'Ecowood') {
                        data.push(property_values[i]);
                    }
                }
            } else {
                for (let i = 0; i < property_values.length; i++) {
                    if (property_values[i].property_id == property_id) {
                        data.push(property_values[i]);
                    }
                }
            }
            return data;
        }

        //get field data whose value contains...
        function getFieldDataContains(property_id, contains, field_values) {
            let data = [];
            field_values = typeof field_values !== 'undefined' ? field_values : property_values;
            console.log(field_values);
            for (let i = 0; i < field_values.length; i++) {
                if (field_values[i].property_id == property_id && field_values[i].value.indexOf(contains) > -1) {
                    data.push(field_values[i]);
                }

            }
            return data;
        }

        function loadItems(property_code, values) {
            $('#' + property_code).select2({
                data: {
                    results: values,
                    text: 'name'
                },
                formatSelection: format,
                formatResult: format,
                dropdownAutoWidth: true,
                escapeMarkup: function (m) {
                    return m;
                }
            });
        }

        //adds error to field or select box
        function addError(field_id, error) {
            console.log("Adding error " + error + " for field " + field_id);
            if ($("#" + field_id).prev().find('.select2-choice').length > 0) {
                $("#" + field_id).prev().addClass("error-field");
                $("#" + field_id).prev().css('display', 'block');
                if ($("#" + field_id).closest('.input-group-container').length > 0) {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).closest('.input-group-container'));
                } else {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).after());
                }
                //show the div with the error if hidden
                $("#" + field_id).closest(".panel").find(".panel-collapse").collapse("show");

            } else {
                $("#" + field_id).addClass("error-field");
                if ($("#" + field_id).closest('.input-group-container').length > 0) {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).closest('.input-group-container'));
                } else {
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id));
                }
                //show the div with the error if hidden
                $("#" + field_id).closest(".panel").find(".panel-collapse").collapse("show");
            }
        }

        function getPropertyBladesize() {
            if ($("#property_bladesize").select2('data')) {
                // if flat louvre is selected do:
                if ($("#property_bladesize").select2('data').value == '81.2mm Flat Louver') {
                    // if warning flat louvre not exists show worning
                    if ($('.warning-louvre').length < 1) {
                        let html_flat_louvre = '<div class="warning-louvre">WARNING: please select only for painted colors!</div>';
                        $('div#s2id_property_bladesize').append(html_flat_louvre);
                    }
                } else {
                    // if flat louvre is not selected remove worning:
                    $(".warning-louvre").remove();
                }
                // if ($("#property_bladesize").select2('data').value == 'Flat Louver') {
                //     return parseFloat(53);
                // } else {
                return parseFloat($("#property_bladesize").select2('data').value);
                // }
            } else {
                return 0;
            }
        }

        function getPropertyMidrailheight() {
            let midrail = [];
            if ($("#property_midrailheight").val().length > 0) {
                midrail[0] = parseFloat($("#property_midrailheight").val());
            }

            return midrail;
        }

        function getPropertyMidrailheight2() {
            let midrail = [];
            if ($("#property_midrailheight2").val().length > 0) {
                midrail[0] = parseFloat($("#property_midrailheight2").val());
            }

            return midrail;
        }

        function getPropertyMidrailtotal() {
            let midrail = [];
            if ($("#property_midrailheight2").val().length > 0) {
                let mid1 = parseInt($("#property_midrailheight").val());
                let mid2 = parseInt($("#property_midrailheight2").val());
                midrail[0] = parseFloat(mid1 + mid2);
            }

            return midrail;
        }

        function getPropertyMidrailDivider() {
            let midrail = [];
            if ($("#property_midraildivider1").val().length > 0) {
                midrail[0] = parseFloat($("#property_midraildivider1").val());
            }

            return midrail;
        }

        function getPropertyMidrailDivider2() {
            let midrail = [];
            if ($("#property_midraildivider2").val().length > 0) {
                midrail[0] = parseFloat($("#property_midraildivider2").val());
            }

            return midrail;
        }

        function getPropertyMidrailCombiPanel() {
            // let midrail = [];
            // if ($("#property_solidpanelheight").val().length > 0) {
            //     midrail[0] = parseFloat($("#property_solidpanelheight").val());
            // }

            // return midrail;
        }

        function getPropertyControltype() {
            let controltype = '';
            if ($("#property_controltype").select2('data')) {
                controltype = $("#property_controltype").select2('data').value;
            }
            return controltype;
        }

        function getPropertyControlsplitheight() {
            let controlsplitheight = 0;
            if ($("#property_controlsplitheight").val() != '') {
                controlsplitheight = parseInt($("#property_controlsplitheight").val());
            }
            return controlsplitheight;
        }

        function getPropertyBuiltout() {
            let builtout = 0;
            if ($("#property_builtout").val() != '') {
                builtout = parseInt($("#property_builtout").val());
            }
            return builtout;
        }

        function getPropertyControlsplitheight2() {
            let controlsplitheight2 = 0;
            let totheight = getPropertyTotHeight();
            let midrailheight = getPropertyMidrailheight();
            let split_start = 0;

            if ($("#property_controlsplitheight2").val() != '') {
                controlsplitheight2 = parseInt($("#property_controlsplitheight2").val());
            }

            if (midrailheight > 0) split_start = midrailheight;
            if (totheight > 0) split_start = totheight;

            if (split_start > 0 && controlsplitheight2 > 0) {
                return [split_start, controlsplitheight2];
            } else {
                return null;
            }
        }

        function getPropertyTotHeight() {
            let tot_height = 0;
            if ($("#property_totheight").val() != '') {
                tot_height = parseInt($("#property_totheight").val());
            }
            return tot_height;
        }


        //When changing the attachment, check file size
        $('#attachment').on('change', function () {
            let field = this;
            if (this.files && this.files[0]) {
                if (this.files[0].size > (8 * (1024 * 1024))) { //limit to 1MB
                    alert("The file is too big, choose a smaller one!");
                    $(this).val("");
                }
            }
        });

        /** Hide t-post frame after layout code T
         * if frame type have P in name (P4008w) show only T-post type with P in name
         * else hide
         */
        function hideTpostBtFrameType() {
            let titleFrameType = $('input[name="property_frametype"]:checked').attr('data-title');
            let result = titleFrameType.includes("P4");
            let cusid_ele = document.querySelectorAll('[name="property_tposttype"]');
            for (let i = 0; i < cusid_ele.length; ++i) {
                let item = cusid_ele[i];
                let itemAttr = $(item).attr('data-title');
                if (itemAttr.includes("P7") === true && titleFrameType.includes("P4") === true) {
                    $(item).parent().css("display", "block");
                } else if (itemAttr.includes("P7") === false && titleFrameType.includes("P4") === false) {
                    $(item).parent().css("display", "block");
                } else {
                    $(item).parent().css("display", "none");
                }
            }
        }

        $(".property-select").css('width', '100%');

        //$("#property_style").css('width', '18em');

        function format(item) {
            let row = item.value;
            if (item.image_file_name !== 'undefined' && item.image_file_name !== null) {
                row = "<span><img src='/uploads/property_values/images/" + item.id + "/thumb_" + item.image_file_name + "' height='44' width='44' /> " + row + "</span>";
            }

            return row;
        }


        //console.log('CUSTOM PROPERTY:  '+JSON.stringify(property_values_original));
        let layout_columns = {
            t: 0,
            c: 0,
            b: 0
        };
        let configuration = {};

        let style_check = getStyleTitle();
        //property fit hidden
        if (style_check.indexOf('Bay') > -1) {
            $(".property_fit").show();
        } else {
            $(".property_fit").hide();
        }

        showShapeUploadFileAccordingToStyle();
        initFilterByProduct();

        //updateLayoutFields($("#property_layoutcode").val());
        checkStyleTier();
        checkShutterType();

        if ($('input[name=property_frametype]').length == 1) {
            $('input[name=property_frametype]').first().closest('label').trigger('click');
        }
        $(".property-select").each(function () {
            let id = $(this).attr('id');
            let property_id = getPropertyIdByCode(id);
            let values = getAllFieldData(property_id);
            loadItems(id, values);
            // console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
        });

        calculateTotal();
        filterControlType();

        $('select').select2({
            dropdownAutoWidth: true
        });

        $("#property_width, #property_height").change(function () {
            // if height is more then 2000 then hide stile where data-title contain string '41mm'
            hideStileByHeight(1800);
            calculateTotal();
        });

        $("#property_totheight").change(function () {
            // if height is more then 2000 then hide stile where data-title contain string '41mm'
            hideStileByHeight(1800);
        });

        $('input[name="property_frametype"], .property-select').click(function () {
            calculateTotal();
        });

        $("#add-buildout button").click(function () {
            $("#add-buildout").hide();
            $("#buildout").fadeIn();
            $("#buildout input").addClass("number");
            return false;
        });

        $("#remove-buildout").click(function () {
            $("#buildout").hide();
            $("#buildout input").val('');
            $("#buildout input").removeClass("number");
            $("#add-buildout").fadeIn();
            return false;
        });

        $("#property_room").change(function () {
            if ($(this).val() == '94') {
                $("#room-other").fadeIn();
                $("#room-other input").addClass('required');
            } else {
                $("#room-other").fadeOut();
                $("#room-other input").removeClass('required');
            }
        });

        $("#property_shuttercolour").change(function () {
            if ($(this).val() == '145') {
                $("#colour-other").fadeIn();
                $("#colour-other input").addClass('required');
            } else {
                $("#colour-other").fadeOut();
                $("#colour-other input").val('');
                $("#colour-other input").removeClass('required');
            }

            if ($("#property_shuttercolour").select2('data')) {
                let property_shcolour_check = $("#property_shuttercolour").select2('data').value;
                console.log('step 1');
                let property_stile_check = $("input[name=property_stile]:checked").attr('data-title');
                console.log('step 2');
                if (typeof property_stile_check !== "undefined") {
                    if (property_shcolour_check.includes("brushed") || property_shcolour_check.includes("BRUSHED")) {
                        if (!property_stile_check.includes("51mm")) {
                            showErrorModal("Shutter Colour", "Brushed Shutter Colour need to have Frame Stile with 51mm. ");
                        }
                    }
                }
            }
        });

        $("#property_material").change(function () {

            if ($("#property_material").select2('data')) {
                let product_title_check = $("#property_material").select2('data').value;
                if (product_title_check.indexOf('PVC') > -1) {
                    let property_id = getPropertyIdByCode('property_hingecolour');
                    for (let i = 0; i < property_values.length; i++) {
                        if (property_values[i].property_id == property_id) {
                            //set default value only if there is not
                            if (property_values[i].value.indexOf('Stainless') > -1 && !$("#property_hingecolour").select2('data')) {
                                $("#property_hingecolour").select2("val", property_values[i].id);
                            }
                        }
                    }
                    if (product_title_check.indexOf('UK') > -1) {
                        if ($('form').attr('edit') === 'no') {
                            $("#property_frameleft").select2("val", '70');
                            $("#property_frameright").select2("val", '75');
                            $("#property_frametop").select2("val", '80');
                            $("#property_framebottom").select2("val", '85');

                            $(".frames #property_frameleft").prop("readonly", true);
                            $(".frames #property_frameright").prop("readonly", true);
                            $(".frames #property_frametop").prop("readonly", true);
                            $(".frames #property_framebottom").prop("readonly", true);
                        }
                    }
                } else {
                    if ($('form').attr('edit') === 'no') {
                        $(".frames #property_frameleft").prop("readonly", false);
                        $(".frames #property_frameright").prop("readonly", false);
                        $(".frames #property_frametop").prop("readonly", false);
                        $(".frames #property_framebottom").prop("readonly", false);
                        $("#add-buildout").parent().show();
                    }
                }
                if (product_title_check.indexOf('Earth') > -1) { //teo_01-Earth Hidden Only
                    showAluminumOptions(true);
                } else {
                    showAluminumOptions(false);
                }
            }
            showMidrailPositionCritical();
        });

        $('.property-select').on('change', function () {
            let id = $(this).attr('id');
            let field_id = getPropertyIdByCode(id);
            let related_fields = getRelatedFields(field_id);

            for (let i = 0; i < related_fields.length; i++) {
                let field_data = getRelatedFieldData(related_fields[i], field_id, $(this).val());
                let property_code = getPropertyCodeById(related_fields[i]);
                //   console.log("Loading to " + property_code + " data: " + field_data);


                if ($("#" + "property_" + property_code).data('select2')) {
                    loadItems("property_" + property_code, field_data);
                } else {
                    let field_check = "property_" + property_code;
                    $('input[name=' + field_check + ']').each(function () {
                        let found = false;
                        for (let i = 0; i < field_data.length; i++) {
                            if ($(this).val() == field_data[i].id)
                                found = true;
                        }
                        if (found) {
                            $(this).closest('label').fadeIn();
                        } else {
                            $(this).prop('checked', false);
                            $(this).closest('label').hide();
                        }
                    });
                }

            }
            // console.log("Length: " + $("#choose-frametype label").filter(":visible").length);
            if ($("#choose-frametype label").filter(":visible").length == 0) {
                $("#required-choices-frametype").show();
            } else {
                //($("#choose-frametype label").filter(":visible").length);
                $("#required-choices-frametype").hide();
            }

            //after filtering if style is checked (selected) we need to apply some filters again
            if ($(this).attr('id') == 'property_material' && $('input[name=property_style]:checked').length > 0) {
                $('input[name=property_style]:checked').trigger('click', false);
            }

            if ($("#property_material").select2('data')) {
                let product_title_check = $("#property_material").select2('data').value;
                $("#locks").show();
                $("#property_locks").val('No');
                if (product_title_check.indexOf('Earth') > -1) {  //teo_02-Earth Hidden Only
                    showAluminumOptions(true);
                    $("#property_sparelouvres").val('No');
                    $("#spare-louvres").hide();
                    $("#spare-louvres").closest("div.row").hide();
                    // $("#locks").show();
                    // $("#property_locks").val('No');
                    //select material
                    // let id_material = $("#property_material").select2('data').id;
                    // if(id_material == 187){
                    //     $("#property_sparelouvres").val('No');
                    //     $("#spare-louvres").hide();
                    //     $("#spare-louvres").closest("div.row").hide();
                    // }
                } else {
                    // $("#locks").hide();
                    showAluminumOptions(false);
                }
            }

            if ($("#canvas_container1").filter(":visible").length > 0) {
                updateShutter();
            }
        });

        $(document).on("change", "input", function () {
            try {
                filterControlType();
                if ($("#canvas_container1").filter(":visible").length > 0) {
                    updateShutter();
                }
            } catch (err) {
                //console.log('Shutter data not ready yet' + err);
            }
        });

        $("#choose-style label").click(function (event, trigger_change) {
            if (typeof trigger_change === 'undefined') {
                trigger_change = true;
            }

            if ($('input[name=property_style]:checked').length > 0) {
                let style_check = $('input[name=property_style]:checked').data('title');
                if (style_check.indexOf('Bay Window') > -1) {
//teo                        $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeIn();

                } else {
//teo                        $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeOut();

                }
                //set default value only if new record or empty value
                if (style_check.indexOf('Bay ') > -1) {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeIn();
                    //$("#property_fit").parent().parent().parent().show();
                } else {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeOut();
                }


                //if style is 'Shaped & French Cut Out', allow to upload a file for the shape
                if (style_check.indexOf('Shaped') > -1 || style_check.indexOf('French') > -1) {
                    console.log('123');
                    $("#shape-section").fadeIn();
                    $("#shape-section").addClass("required");
                }
                // else {
                //     console.log('456');
                //     $("#shape-section").fadeOut();
                //     $("#shape-section").removeClass("required");
                // }

                checkStyleTier();

                let value = $('input[name=property_style]:checked').val();
                let field_id = getPropertyIdByCode('property_style');
                let style_related_fields = getRelatedFields(field_id);
                console.log(style_related_fields);
                for (let i = 0; i < style_related_fields.length; i++) {
                    let field_data = getRelatedFieldData(style_related_fields[i], field_id, value);
                    let property_code = getPropertyCodeById(style_related_fields[i]);
                    console.log("Loading to " + property_code + " data: " + field_data);
                    if ($("#" + "property_" + property_code).data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        //filter only when track is selected
                        if (value == 35) {
                            let field_check = "property_" + property_code;
                            console.log("filtering " + field_check + ' from style');
                            $('input[name=' + field_check + ']').each(function () {
                                let found = false;
                                for (let i = 0; i < field_data.length; i++) {
                                    if ($(this).val() == field_data[i].id)
                                        found = true;
                                }
                                if (found) {
                                    $(this).closest('label').fadeIn();
                                } else {
                                    $(this).closest('label').fadeOut();
                                    $(this).prop('checked', false);
                                }
                            });
                        } else {
                            if (trigger_change)
                                $("#property_material").trigger('change');
                        }
                    }
                }

                //if style is 'Shaped & French Cut Out', allow to upload a file for the shape
                if (style_check.indexOf('Shaped') > -1) {
                    $("#choose-frametype input[value=141]").prop("checked", false).closest("label").hide();
                } else {
                    if ($("#property_material").val() == '138' || $("#property_material").val() == '139' || $("#property_material").val() == '188') {
                        $("#choose-frametype input[value=141]").closest("label").show();
                    }
                    // Selectare default tposttype
                    // if ($("#property_material").val() == '138' || $("#property_material").val() == '137') {
                    //     $("#property_tposttype").select2("val", '437');
                    // }
                    // if ($("#property_material").val() == '139' ) {
                    //     $("#property_tposttype").select2("val", '438');
                    // }
                }

                if (style_check.indexOf('Café') > -1) {
                    $("#property_frametop").select2("val", '81');
                    $("#property_tposttype").select2("val", '439');
                } else {
                    $("#property_frametop").select2("val", '80');
                }

                //default values for tracked style
                if ($("#order_product_id").val() == '') {
                    //default values for tracked style
                    if (style_check.indexOf('Tracked') > -1) {
                        //$("#property_frametype").select2("val", '68');
                        //$("#property_frametype").trigger('change'); //needed so that filtering will work correctly

                        if ($('form').attr('edit') === 'no') {
                            $("#property_frameleft").select2("val", '73');
                            $("#property_frameright").select2("val", '78');
                            $("#property_frametop").select2("val", '135');
                            $("#property_framebottom").select2("val", '136');
                        }
                    }
                }

                if (style_check.indexOf('Tracked') > -1) {
                    $('input[name="property_stile"][type="radio"]').parent().hide();
                    $('input[name="property_stile"][value="374"]').parent().show();
                    $('input[name="property_stile"][value="375"]').parent().show();
                    $('input[name="property_stile"][value="360"]').parent().show();
                    $('input[name="property_stile"][value="359"]').parent().show();
                    $('input[name="property_stile"][value="366"]').parent().show();
                    $('input[name="property_stile"][value="365"]').parent().show();
                    $('input[name="property_stile"][value="385"]').parent().show();
                    $('input[name="property_stile"][value="384"]').parent().show();
                    $('input[name="property_stile"][value="350"]').parent().show();
                }


                setProductByMaterialAndStyle();
                hideStileByHeight(1800);
            }
        });

        function hideStileByHeight(minHeght) {
            // if height is more then 2000 then hide stile where data-title contain string '41mm'

            if ($("#property_height").val() > minHeght && $("#property_totheight").val() == '') {
                console.log('cond 1');
                $('input[name="property_stile"]').each(function () {
                    if ($(this).data('title').indexOf('41mm') > -1) {
                        $(this).parent().hide();
                    }
                });
            } else if ($("#property_height").val() < minHeght && $("#property_totheight").val() == '') {
                console.log('cond 2');
                $('input[name="property_stile"]').each(function () {
                    if ($(this).data('title').indexOf('41mm') > -1) {
                        $(this).parent().show();
                    }
                });
            }

            if ($("#property_totheight").val() > minHeght) {
                console.log('cond 3');
                $('input[name="property_stile"]').each(function () {
                    if ($(this).data('title').indexOf('41mm') > -1) {
                        $(this).parent().hide();
                    }
                });
            } else if ($("#property_height").val() > minHeght && ($("#property_totheight").val() < 2000 && $("#property_totheight").val() != '')) {
                console.log('cond 4');
                $('input[name="property_stile"]').each(function () {
                    if ($(this).data('title').indexOf('41mm') > -1) {
                        $(this).parent().show();
                    }
                });
            }
        }

        $("#property_material").click(function () {
            //alert( $(this).val() );
            // Selectare default tposttype
            if ($("#property_material").val() == '138' || $("#property_material").val() == '137' || $("#property_material").val() == '188') {
                $("#property_tposttype").select2("val", '437');
            }
            if ($("#property_material").val() == '139') {
                $("#property_tposttype").select2("val", '438');
            }
            // select frame checked
            $('input[name="property_frametype"]:checked').trigger('click');
        });

        // When any label within #choose-frametype is clicked, execute this function
        $("#choose-frametype label").click(function () {
            // Check if any frame type is selected
            if ($('input[name="property_frametype"]:checked').length > 0) {
                // Get the value of the selected frame type
                let value = $('input[name=property_frametype]:checked').val();
                // Get the ID of the property 'property_frametype'
                let field_id = getPropertyIdByCode('property_frametype');
                // Get related fields based on the frame type
                let related_fields = getRelatedFields(field_id);

                // Iterate over each related field
                related_fields.forEach(field => {
                    // Get data related to the current field, based on the selected frame type
                    let field_data = getRelatedFieldData(field, field_id, value);
                    // Construct the property code
                    let property_code = "property_" + getPropertyCodeById(field);

                    // Check if the current field has a select2 component initialized
                    if ($("#" + property_code).data('select2')) {
                        // Load items into the select2 component
                        loadItems(property_code, field_data);
                    } else {
                        // If not a select2 component, filter inputs based on the frame type
                        console.log("filtering " + property_code + ' from frametype');
                        filterInputs(property_code, field_data);
                    }
                });

                // Set default values for certain properties based on the selected frame type
                setDefaultValues(value);
            }
        });

        $("#property_controltype").change(function () {
            if ($("#property_controltype").select2('data')) {
                let controltype_check = $("#property_controltype").select2('data').value;
                if (controltype_check.indexOf('Split') > -1) {
                    $("#control-split-height").addClass("required");
                    $("#control-split-height").addClass("number");
                    $("#control-split-height").fadeIn();
                } else {
                    $("#control-split-height").fadeOut();
                    $("#control-split-height").removeClass("required");
                    $("#control-split-height").removeClass("number");
                    $("#control-split-height input").val('');
                }
            }
            showHideControlSplit2();
        });

        $("#property_frametype").change(function () {
            if (!$("#property_frametop").select2("data")) {
                let style_check = getStyleTitle();
                //default values for cafe style
                if (style_check.indexOf('Café') > -1) {
                    $("#property_frametop").select2("val", '81');
                }
            }
        });

        $("#property_midrailheight").change(function () {
            showMidrailPositionCritical();
            showHideControlSplit2();
        });

        $(" #property_midrailheight2").change(function () {
            showMidrailPositionCritical();
            showHideControlSplit2();
        });

        $("#property_material, #attachment").change(function () {
            setProductByMaterialAndStyle();
            filterStiles();
        });

        $('.property-select').each(function () {
            $(this).trigger('change');
        });

        $("#property_layoutcode").on('keyup', function () {
            let text = $(this).val().toUpperCase();
            updateLayoutFields(text);

            if (text.length > 1) {
                // console.log('keyup press ' + text);
                // console.log(text.length);
                if (text == 'L' || text == 'R') {
                    // console.log("text == 'L' || text == 'R'");
                    // console.log(text.length);
                    $('#property_opendoor').parent().hide();
                } else if ((text == 'LL' || text == 'RR') && text.length === 2) {
                    // console.log("text == 'LL' && text.length === 2");
                    // console.log(text.length);
                    $('#property_opendoor').parent().hide();
                } else if ((text == 'LLL' || text == 'RRR') && text.length === 3) {
                    // console.log("text == 'LLL' && text.length === 3");
                    // console.log(text.length);
                    $('#property_opendoor').parent().hide();
                } else {
                    $('#property_opendoor').parent().show();
                    $('#property_opendoor').val('Right');
                    $('#property_opendoor').trigger('change');
                }

                // $('#property_opendoor option[value="Right"]').attr("selected", "selected");
            } else {
                $('#property_opendoor').parent().hide();
            }
            /** Hide t-post frame after layout code T
             * if frame type have P in name (P4008w) show only T-post type with P in name
             * else hide
             */
            hideTpostBtFrameType();
        });

        let lengKeyR = 0;

        // Bind the keypress event to the #property_layoutcode element
        $("#property_layoutcode").on('keypress', function (event) {
            // Hide extra columns when keypress is detected
            $('.pull-left.extra-column-buildout.property_b_buildout1').hide();

            // Get the character code of the pressed key
            let charCode = event.which;
            // If charCode is not defined, exit the function early
            if (!charCode) return; // Optionally, return false

            // Convert the pressed key to an uppercase character
            let character = String.fromCharCode(charCode).toUpperCase();
            // Get the current value of the input and convert it to uppercase
            let text = $(this).val().toUpperCase();
            // Extract the first character of the input text
            let letter = text.charAt(0);

            // Initialize a variable to count occurrences of 'R'
            let lengKeyR = (text.match(/R/g) || []).length;

            // Condition to prevent specific character input based on certain criteria
            if ((letter == 'R' && lengKeyR == 1 && character == "L") ||
                ($("#property_material").val() == '138' && letter == 'R' && (lengKeyR == 2 || lengKeyR == 3) && character == "L")) {
                return false; // Prevent input
            }

            // Allow characters L, R, or backspace regardless of conditions
            if (character == "L" || character == "R" || event.keyCode == 8) {
                return true;
            }

            // Specific conditions for characters T, B, C following a 'G'
            if ("T" == character || "B" == character || "C" == character) {
                if (text.slice(-1) == 'G') {
                    return false; // Prevent input if last character is 'G'
                }
                return true;
            }

            // Allow 'G' only if it follows an 'L' or 'R'
            if ("G" == character) {
                let lastChar = text.slice(-1);
                return lastChar == 'L' || lastChar == 'R';
            }

            // Default action for other characters is to prevent input
            return false;
        });

        $("#property_material, #property_bladesize, #property_stile, #property_hingecolour").change(function () {
            filterControlType();
        });

        $("#property_hingecolour").change(function () {
            // if hinge color id is mot equal to 93 (Stainless Steel) and material is green then show alert-info about waterproof
            let property_val = $('input[name="property_hingecolour"]').val();
            if (product_title_check.indexOf('Green') > -1) {
                // console.log(property_val);
                if (property_val !== '93') {
                    $('#step4-info .alert-info').show();
                } else {
                    $('#step4-info .alert-info').hide();
                }
            }
        });

        $("#buildout-select").change(function () {
            // Check input( $( this ).val() ) for validity here
            if ($(this).val() === 'flexible') {
                console.log('flexible');
                $('input[name="property_b_buildout1"]').prop('checked', false);
                $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
            } else {
                $('.pull-left.extra-column-buildout.property_b_buildout1').show();
            }
        });

        function initFilterByProduct() {
            let value = $("#product_id").val();

            let related_fields = getRelatedFieldsByProduct();
            // console.log(related_fields);
            for (let i = 0; i < related_fields.length; i++) {
                let field_data = getRelatedFieldDataByProductId(related_fields[i], value);
                let property_code = getPropertyCodeById(related_fields[i]);
                // console.log("Loading to " + property_code + " data: " + field_data);
                loadItems("property_" + property_code, field_data);
            }
        }

        function filterStiles() {
            let stile_id = getPropertyIdByCode('property_stile');
            let style_check = getStyleTitle();
            let stile_default = 155; //butt rebated 50.8
            let stile_data = [];
            //get the stile data based on property material
            let property_material = $("#property_material").val();
            let field_id = getPropertyIdByCode('property_material');
            let related_fields = getRelatedFields(field_id);

            for (let i = 0; i < related_fields.length; i++) {
                let property_code = getPropertyCodeById(related_fields[i]);
                if (property_code == 'stile') {
                    let stile_data = getRelatedFieldData(related_fields[i], field_id, property_material);
                }
            }


            // if (parseFloat($("#property_height").val()) >= 1500 && style_check.indexOf('Tier') == -1) {
            //     new_stile_data = getFieldDataContains(stile_id, '50.8', stile_data);
            // } else {
            //     new_stile_data = stile_data;
            // }
            let new_stile_data = stile_data;

            // loadItems('property_stile', new_stile_data);
            // if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == 'undefined') {
            //     $("#property_stile").select2("val", stile_default);
            // }
        }

        function showShapeUploadFileAccordingToStyle() {
            //if 'Shaped & French Cut Out' style is chosen when configuration is loaded, show the upload file section
            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
                //console.log('sdfdsf ');
                //show the upload file section to allow uploading of a shape
                if (style_check.indexOf('Shaped') > -1 || style_check.indexOf('French') > -1) {
                    //console.log('clickkkk');
                    $("#shape-section").show();
                    $("#shape-section").addClass("required");
                }
            }
        }

        function setProductByMaterialAndStyle() {
            let material_id = '';
            if ($("#property_material").select2('data')) {
                material_id = $("#property_material").select2('data').id;
            }

            let material_code = '';
            let style_code = '';
            let product_id = '';
            for (let i = 0; i < property_values.length; i++) {
                if (property_values[i].id == material_id)
                    material_code = property_values[i].code;
            }
            if ($('input[name=property_style]:checked').length > 0) {
                style_code = $('input[name=property_style]:checked').data('code');
            }
            let existingShapeFile = $('#provided-shape').html().trim();
            if ($('input[name=attachment]').val() != '' || existingShapeFile.length > 0) {
                style_code = 'specialshape';
            }

            let product_code = material_code + '-' + style_code;
            if (shutter_type == 'Blackout') {
                product_code = 'blackout-' + product_code;
            }

            // console.log('Checking product code: ' + product_code);
            // console.log('With products' + names);
            for (let i = 0; i < names.length; i++) {
                if (product_code == names[i].part_number) {
                    product_id = names[i].id;
                }
            }

            $("#product_id").val(product_id);
        }

        //todo: this might be removed, because allowed values are set by backend
        function showAluminumOptions(aluminum_selected) {
            if (aluminum_selected) {
                $(".locks").fadeIn();
                $(".locks input").removeClass('not-required');
                //        $("#add-buildout").parent().hide(); //teo - show buildout for Earth
            } else {
                $(".locks").fadeOut();
                $(".locks input").addClass('not-required');
                $(".locks input").val('No');
                $("#add-buildout").parent().show();
            }

            //buildout needs to be hidden for PVC UK made
            if ($("#property_material").select2('data')) {
                let product_title_check = $("#property_material").select2('data').value;

                if (product_title_check.indexOf('UK') > -1 && product_title_check.indexOf('PVC') > -1) {
                    $("#add-buildout").parent().hide();
                }
            }
        }

        $('#property_b_buildout1').on('click', function () {
            if ($(this).prop('checked') == false) {
                $(this).attr("value", "no");
            } else {
                $(this).attr("value", "yes");
            }
        });
        $('#property_c_buildout1').on('click', function () {
            if ($(this).prop('checked') == false) {
                $(this).attr("value", "no");
            } else {
                $(this).attr("value", "yes");
            }
        });
        $('#property_t_buildout1').on('click', function () {
            if ($(this).prop('checked') == false) {
                $(this).attr("value", "no");
            } else {
                $(this).attr("value", "yes");
            }
        });

        //create new columns based on the layout code types
        function updateLayoutFields(text) {
            text = $("#property_layoutcode").val();
            let property_material = $("#property_material").val();

            if (property_material == 188) {
                $('.note-ecowood-angle').show();
            } else {
                $('.note-ecowood-angle').hide();
            }
            //count T occurences

            // $("#property_layoutcode").on('keyup', function () {
            //     let text = $("#property_layoutcode").val();
            //     updateLayoutFields(text);
            // });

            //pull data from prototype html
            //console.log('add extra-column 1');
            let new_column_contents = $("#extra-column").html();
            let new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";

            layout_columns.t = 0;
            layout_columns.c = 0;
            layout_columns.b = 0;
            layout_columns.g = 0;
            let bchar_nr = 1;
            let tchar_nr = 1;
            let gchar_nr = 1;
            let cchar_nr = 1;

            $(".extra-column").remove();
            $(".extra-column-buildout").remove();
            $('.tpost-type').hide();
            //$('.tpost-type label').hide();

            let count_lr = 0;
            let count_t = 0;
            let count_g = 0;

            //clear extra fields
            for (let i = 0; i < text.length; i++) {
                //console.log('t-post press in for '+new_column_contents);
                if (text.charAt(i).toUpperCase() == 'L' || text.charAt(i).toUpperCase() == 'R') {
                    count_lr++;
                    //console.log('Panels left-right: '+count_lr);
                    $('#panels_left_right').val(count_lr);
                }

                // Main logic for handling 'T' character input
                if (text.charAt(i).toUpperCase() === 'T') {
                    count_t++;
                    layout_columns.t++;
                    let label = 'T-Post ' + layout_columns.t;
                    let id = "property_t" + layout_columns.t;
                    addField(label, id, 1);

                    if (tchar_nr < 2) {
                        label = 'T-Post Buildout ' + tchar_nr;
                        let label2 = 'T-Post Style ';
                        let id2 = "property_t_buildout" + tchar_nr;
                        addFieldCheckboxBuildoutSelect(label2, id2, 'help text demo', 't');
                        addFieldCheckboxBuildout(label, id2, 1);
                        tchar_nr++;
                        // Define material mapping to ids and corresponding elements to show
                        const materialConfig = {
                            187: {img: '#stile-img-earth', typeClass: '.type-img-earth'},
                            139: {img: '#stile-img-basswood', typeClass: '.type-img-basswood'},
                            138: {img: '#stile-img-biowood', typeClass: '.type-img-biowood'},
                            137: {img: '#stile-img-green', typeClass: '.type-img-green'},
                            188: {img: '#stile-img-ecowood', typeClass: '.type-img-ecowood'}
                        };

                        // Hide all images and types initially
                        $('.tpost-img, .stile-img, .type-img').hide();

                        // Show the specific elements based on the material_id
                        if (materialConfig[property_material]) {
                            $(materialConfig[property_material].img).show();
                            $(materialConfig[property_material].typeClass).show().parent().show();
                        }
                        $('.tpost-type').show().find('label').show();
                    } else {
                        console.log('tchar_nr exceeded: ' + tchar_nr);
                    }
                }

                // Define an object to track the count of different post types
                let layoutColumns = {c: 0, b: 0, g: 0};

// Variables to track the number of specific post buildouts
                let cCharNr = 0, bCharNr = 0, gcharNr = 0;

// Loop through each character of the input text
                for (let i = 0; i < text.length; i++) {
                    let currentChar = text.charAt(i).toUpperCase();
                    // Check if the current character matches any of the post types
                    if (['C', 'B', 'G'].includes(currentChar)) {
                        console.log(`${currentChar}char_nr : ${window[currentChar.toLowerCase() + 'CharNr']}`);
                        layoutColumns[currentChar.toLowerCase()]++;
                        let postCount = layoutColumns[currentChar.toLowerCase()];

                        addPostField(currentChar, layoutColumns[currentChar.toLowerCase()]);
                        // C-Post specific logic
                        if (postType === 'C' && cCharNr < 2) {
                            let label = 'C-Post Buildout ' + cCharNr;
                            let id = "property_c_buildout" + cCharNr;
                            addFieldCheckboxBuildout(label, id, 1); // Assuming a predefined function
                            cCharNr++;
                        }

                        // B-Post specific logic
                        if (postType === 'B') {
                            if (property_material == 188) { // Assuming property_material is a predefined variable
                                let label = 'Bay Angle ' + postCount;
                                let id = "property_ba" + postCount;
                                addFieldBuildAngleSelect(label, id, 1); // Assuming a predefined function
                            } else {
                                let label = 'Bay Angle ' + postCount;
                                let id = "property_ba" + postCount;
                                addField(label, id, 1); // Assuming addField is a predefined function
                            }

                            if (bCharNr < 2) {
                                let label = 'B-Post Buildout ';
                                let id = "property_b_buildout" + bCharNr;
                                addFieldCheckboxBuildoutSelect(label, id, 'help text demo', 'b'); // Assuming a predefined function
                                bCharNr++;
                            }

                            // Toggle visibility based on material
                            $('select[name="bay-post-type"]').parent().parent().css('display', property_material == 188 ? 'none' : 'block');
                        }

                        // G-Post specific logic
                        if (postType === 'G' && gcharNr < 20) {
                            let label = 'G-Post Buildout ' + gcharNr;
                            let id = "property_g_buildout" + gcharNr;
                            addFieldCheckboxBuildout(label, id, 1); // Assuming a predefined function
                            gcharNr++;
                            $('.gpost-type').show(); // Assuming a jQuery selector for UI element
                        }
                    }
                }

                if (text.charAt(i).toUpperCase() == 'C') {
                    //console.log('t-post press in if');
                    console.log('cchar_nr : ' + cchar_nr);
                    layout_columns.c++;
                    let label = 'C-Post ' + layout_columns.c;
                    let id = "property_c" + layout_columns.c;
                    addField(label, id, 1);

                    if (cchar_nr < 2) {
                        label = 'C-Post Buildout ' + cchar_nr;
                        let id3 = "property_c_buildout" + cchar_nr;
                        addFieldCheckboxBuildout(label, id3, 1);
                        cchar_nr++;
                        console.log('cchar_nr after : ' + bchar_nr);
                    } else {
                        console.log('cchar_nr depasit : ' + cchar_nr);
                    }

                }


                if (text.charAt(i).toUpperCase() == 'B') {
                    // console.log('bchar_nr : ' + bchar_nr);

                    if (property_material == 188 || property_material == 147) {
                        $('.note-ecowood-angle').show();
                    } else {
                        $('.note-ecowood-angle').hide();
                    }

                    layout_columns.b++;

                    label = 'Bay Post ' + layout_columns.b;
                    id2 = "property_bp" + layout_columns.b;
                    addField(label, id2, 1);

                    if (property_material == 188 || property_material == 147) {
                        labela = 'Bay Angle ' + layout_columns.b;
                        id1a = "property_ba" + layout_columns.b;
                        addFieldBuildAngleSelect(labela, id1a, 1);
                    } else {
                    label = 'Bay Angle ' + layout_columns.b;
                    id1 = "property_ba" + layout_columns.b;
                    addField(label, id1, 1);
                    }

                    if (bchar_nr < 2) {
                        label = 'B-Post Buildout ';
                        label2 = 'B-Post Type ';
                        id3 = "property_b_buildout" + bchar_nr;
                        addFieldCheckboxBuildoutSelect(label2, id3, 'help text demo', 'b');
                        addFieldCheckboxBuildout(label, id3, 1);

                        bchar_nr++;
                        // console.log('bchar_nr after : ' + bchar_nr);
                    } else {
                        // console.log('bchar_nr depasit : ' + bchar_nr);
                    }

                    if (property_material == 188 || property_material == 147) {
                        $('select[name="bay-post-type"]').parent().parent().css('display', 'none');
                    } else {
                        $('select[name="bay-post-type"]').parent().parent().css('display', 'block');
                    }


                }


                if (text.charAt(i).toUpperCase() == 'G') {
                    //console.log('g-post press in if');
                    //console.log('gchar_nr : '+gchar_nr);
                    count_g++;
                    layout_columns.g++;
                    let label = 'G-Post ' + layout_columns.g;
                    let id = "property_g" + layout_columns.g;
                    addField(label, id, 1);

                    if (gchar_nr < 20) {
                        label = 'G-Post Buildout ' + gchar_nr;
                        let id2 = "property_g_buildout" + gchar_nr;
                        addFieldCheckboxBuildout(label, id2, 1);
                        gchar_nr++;
                        console.log('G-char_nr after : ' + bchar_nr);
                        $('.gpost-type').show();
                    } else {
                        console.log('gchar_nr depasit : ' + gchar_nr);

                    }
                }


            }


// Define the total count of elements for each category
            const totalCount = 10;

// Transfer values for each category
            transferValues('bp', 'property_bp', totalCount);
            transferValues('ba', 'property_ba', totalCount);
            transferValues('t', 'property_t', totalCount);
            transferValues('c', 'property_c', totalCount);


            //restart tooltips because of new fields
            $('[data-toggle="tooltip"]').tooltip({
                'placement': 'top'
            });
        }

        /**
         * Adds a new field dynamically based on a template element.
         *
         * @param {string} label - The label for the new field.
         * @param {string} id - The unique identifier for the new field.
         * @param {string} helptext - The help text for the new field.
         */
        function addField(label, id, helptext) {
            // Use the HTML of an existing template column as the basis for the new field
            let templateColumnHtml = $("#extra-column").html();

            // Create a new element based on the template
            let newColumn = $(`<div class="pull-left extra-column">${templateColumnHtml}</div>`);

            // Update the new column's label, ID, and name attributes for the input element
            newColumn.find(".extra-column-label").html(label);
            newColumn.find("input")
                .attr({
                    "id": id,
                    "name": id
                })
                .addClass('required');

            // If there is a predefined value for this field, set it
            if (configuration[id] !== 'undefined') {
                newColumn.find("input").val(configuration[id]);
            }

            // Determine the insertion point for the new column and insert it
            if ($(".extra-column").length > 0) {
                newColumn.insertAfter($(".extra-column").last());
            } else {
                newColumn.appendTo(".extra-columns-row div");
            }
        }


        /**
         * Adds a new checkbox field to the form based on a prototype layout.
         * @param {string} label - The label for the new field.
         * @param {string} id - The unique identifier for the new field.
         * @param {string} helptext - The help text associated with the new field.
         */
        function addFieldCheckbox(label, id, helptext) {
            // Retrieve the HTML template from the prototype column.
            let prototypeHtml = $("#extra-column").html();

            // Construct the new column element with the given label and ID,
            // setting the input type to 'checkbox' and default value to 'yes'.
            let newColumnHtml = `
        <div class="pull-left extra-column">
            ${prototypeHtml}
        </div>
    `;
            let $newColumn = $(newColumnHtml);

            // Update the new column's label, input ID, name, and type.
            $newColumn.find(".extra-column-label").text(label);
            $newColumn.find("input")
                .attr({
                    "type": "checkbox",
                    "id": id,
                    "name": id,
                    "value": "yes"
                });

            // If there's a stored value for this field, set it.
            if (configuration[id] !== undefined) {
                $newColumn.find("input").prop("checked", configuration[id] === "yes");
            }

            // Insert the new column after the last extra-column or at the end if it's the first.
            if ($(".extra-column").length) {
                $newColumn.insertAfter($(".extra-column").last());
            } else {
                $newColumn.appendTo(".extra-columns-row div");
            }
        }


        /**
         * Adds a new checkbox field specifically for buildout configurations.
         * @param {string} label - The label for the new field.
         * @param {string} id - The ID for the new checkbox input element.
         * @param {string} helptext - Help text associated with the field (unused but included for consistency).
         */
        function addFieldCheckboxBuildout(label, id, helptext) {
            // Use data from a prototype column as a template.
            let newColumnContents = $("#extra-column").html();

            // Create a new column element for buildout with the template contents.
            let newColumn = $("<div>", {"class": "pull-left extra-column-buildout"}).html(newColumnContents);

            // Set up the new column with specific attributes and elements for buildout.
            newColumn.find(".extra-column-label").html(label);
            newColumn.find("input")
                .attr({"type": "checkbox", "id": id, "name": id, "value": "yes"});

            // Append a select dropdown specifically for buildout options.
            let selectDropdown = $("<select>", {"id": "buildout-select", "name": "bay-post-type"})
                .append("<option value='normal'>Normal</option>", "<option value='flexible'>Flexible</option>");
            newColumn.find(".extra-column-label").append(selectDropdown);

            // Remove the input group element and clean up the label.
            newColumn.find('.input-group').remove();
            newColumn.find(".extra-column-label").html(function (i, html) {
                return html.replace(":", "");
            });

            // Handle changes to the buildout select dropdown.
            selectDropdown.change(function () {
                if ($(this).val() === 'flexible') {
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });

            // Load existing configuration if available.
            if (configuration[id] !== 'undefined') {
                newColumn.find("input").val(configuration[id]);
            }

            // Insert or append the new column to the DOM.
            if ($(".extra-column-buildout").length > 0) {
                newColumn.insertAfter(".extra-columns-buildout-row div .extra-column-buildout:last");
            } else {
                newColumn.appendTo(".extra-columns-buildout-row div");
            }
        }


        /**
         * Adds a new select field for angle selection when material is ecowood.
         * @param {string} label - The label for the new select field.
         * @param {string} id - The ID for the new select element.
         * @param {string} helptext - Help text associated with the field (unused but included for consistency).
         */
        function addFieldBuildAngleSelect(label, id, helptext) {
            // Retrieve template contents from a prototype column.
            let newColumnContents = $("#extra-column").html();

            // Create a new column element for the angle select, using the template.
            let newColumn = $("<div>", {"class": "pull-left extra-column"}).html(newColumnContents);

            // Update the new column: set label, remove existing input, and add angle select.
            newColumn.find(".extra-column-label").html(label);
            newColumn.find("input").remove(); // Remove any existing input element.
            // Append a new select element for angle selection.
            let selectHtml = "<select class='b-angle-select' id='" + id + "' name='" + id + "'>" +
                "<option value='90'>90</option><option value='135'>135</option></select>";
            newColumn.find(".extra-column-label").append(selectHtml);

            // Clean up: remove the input group element and unnecessary colons.
            newColumn.find('.input-group').remove();
            newColumn.html(function (i, html) {
                return html.replace(":", "");
            });

            // Insert or append the new column into the DOM.
            if ($(".extra-column").length > 0) {
                newColumn.insertAfter(".extra-column:last");
            } else {
                newColumn.appendTo(".extra-columns-row div");
            }
        }


        /**
         * Adds a new select field for buildout selection based on the provided character.
         * @param {string} label - The label for the new select field.
         * @param {string} id - The ID for the new select element.
         * @param {string} helptext - Help text associated with the field (unused but included for consistency).
         * @param {string} character - Determines the type of buildout options ('b' for bay, 't' for T-posts).
         */
        function addFieldCheckboxBuildoutSelect(label, id, helptext, character) {
            // Clone the prototype column to use as a template.
            let newColumnContents = $("#extra-column").html();

            // Create a new column for the buildout select field.
            let newColumn = $('<div>', {'class': 'pull-left extra-column-buildout'}).html(newColumnContents);

            // Set the label and remove any default input elements.
            newColumn.find(".extra-column-label").html(label).end()
                .find("input").remove();

            // Append a select element based on the character parameter.
            let selectHTML = character === 'b' ?
                "<select id='buildout-select' name='bay-post-type'><option value='normal'>Normal</option><option value='flexible'>Flexible</option></select>" :
                "<select id='buildout-select-t' name='t-post-type'><option value='normal'>Normal</option><option value='adjustable'>Adjustable</option></select>";
            newColumn.find(".extra-column-label").append(selectHTML);

            // Append the new column to the DOM.
            newColumn.appendTo(".extra-columns-buildout-row > div");

            // Clean-up: remove input groups and unnecessary colons.
            newColumn.find('.input-group').remove();
            newColumn.html(function (i, html) {
                return html.replace(":", "");
            });

            // Event listener for select element changes to dynamically show/hide related fields.
            newColumn.find("select").change(function () {
                let selection = $(this).val();
                if (character === 'b' && selection === 'flexible') {
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.extra-column-buildout.property_b_buildout1').show();
                }
            });
        }


        function calculateTotal() {
            let total = $("#property_width").val() * $("#property_height").val();
            if (total == 'NaN')
                total = 0;
            $("#property_total").val(parseFloat(total) / parseFloat(1000000));

            //midrailheight required for >1800 height and NOT Tier styles
            style_check = getStyleTitle();
            if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                $("#property_midrailheight").addClass("required");
                console.log('midrail required');
                //$("#midrail-height").show();
            } else {
                //$("#property_midrailheight").removeClass("required");
                //$("#midrail-height").hide();
            }

            filterStiles();
            showHideControlSplit2();
        }

        //get the property id based on ui property code eg: property_fit = property with id 9
        function getPropertyIdByCode(code) {
            let id = 0;
            for (let i = 0; i < property_fields.length; i++) {
                if (("property_" + property_fields[i].code) == code) {
                    id = property_fields[i].id;
                }
            }
            return id;
        }


        function showMidrailPositionCritical() {
            if ($("#property_material").select2('data')) {
                let product_title_check = $("#property_material").select2('data').value;
                let property_midrailpositioncritical = $("#property_midrailpositioncritical").val();

                if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight").val() > 0) {
                    $("#midrail-position-critical").show();
                    $("midrail-position-critical input").removeClass('not-required');
                    if (property_midrailpositioncritical === '') {
                        $("#property_midrailpositioncritical").select2("val", '170');
                    }
                } else if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight2").val() > 0) {
                    $("#midrail-position-critical").show();
                    $("midrail-position-critical input").removeClass('not-required');
                    if (property_midrailpositioncritical === '') {
                        $("#property_midrailpositioncritical").select2("val", '170');
                    }
                } else {
                    $("#midrail-position-critical").hide();
                    $("#midrail-position-critical input").addClass('not-required');
                }
            } else {
                $("#midrail-position-critical").hide();
                $("#midrail-position-critical input").addClass('not-required');
            }
        }

        function resetErrors() {
            $(".error-field").removeClass("error-field");
            $("span.error-text").remove();
        }

        function getStyleTitle() {
            let title = '';
            if ($('input[name=property_style]:checked').length > 0) {
                title = $('input[name=property_style]:checked').data('title');
            }
            return title;
        }

        function checkStyleTier() {
            var inputValue = $('#property_controltype').val();
            // Set a default value (assuming 'default_value' is the value you want to set)
            $("#property_controltype").select2('val', inputValue);

            // Remove the 'not-required' class and add 'required' class
            $("#property_controltype").removeClass('not-required').addClass('required');

            // Show the element
            $("#property_controltype").closest('div').show();

            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
            } else {
                return;
            }

            if (style_check.indexOf('Café') > -1) {
                $("#property_frametop").select2("val", '81');
            }


            // console.log('select style ----------------');

//teo                    if (style_check.indexOf('Café') > -1) {
//teo                        console.log('selected CAFE Frame NO  ----------------  1');
//teo                        $("#property_material").select2("val", '187');
//teo                    }

            if (!$("#property_frametop").select2("data")) {
                console.log('selected CAFE Frame NO  ----------------  2');
                if (style_check.indexOf('Café') > -1) {
                    $("#property_frametop").select2("val", '81');
                }
            }
//teo
            if (style_check.indexOf('Special') > -1) {
                $(".tot-height").fadeIn();
                $("#property_totheight").fadeIn();
                $("#property_totheight").removeClass("required");
                $("#property_totheight").addClass("not-required");
            } else if (style_check.indexOf('Tier') > -1 && style_check.indexOf('Solid') == -1) {
                $(".tot-height").fadeIn();
                $("#property_totheight").fadeIn();
                $("#property_totheight").addClass("required");
                $("#property_midrailheight").removeClass("required");
                $("#property_totheight").removeClass("not-required");
                //ring-pull
                $("#ring-pull").hide();
                $("#property_ringpull").val('No');
                $("#solid-panel-height").hide();
            } else if (style_check.indexOf('Solid') > -1) {

                //$("#solid-panel-height").show();
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#midrail-divider").hide();
                $("#midrail-divider2").hide();
                $("#midrail-height input").val('');
                $("#solidtype").show();
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').hide();
                $("#property_bladesize").val('');
                $("#property_bladesize").addClass('not-required');

                //$("#property_sparelouvres").prop('checked', false);
                $("#property_sparelouvres").val('No');
                $("#spare-louvres").hide();
                $("#spare-louvres").closest("div.row").hide();
                $("#ring-pull").show();
                $("#property_controltype").select2('val', '');
                $("#property_controltype").addClass('not-required');
                $("#property_controltype").closest('div').hide();
                $("#control-split-height").hide();

                if ($('form').attr('edit') === 'no') {

                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_frametop").select2("val", '80');
                    $("#property_framebottom").select2("val", '85');

                }

                if (style_check.indexOf('Tier') > -1) {
                    $(".tot-height").fadeIn();
                    $("#property_totheight").fadeIn();
                    $("#property_totheight").addClass("required");
                    $("#midrail-height").hide();
                    $("#midrail-height2").hide();
                } else if (style_check.indexOf('Café') > -1) {
                    $("#midrail-height").hide();
                    $("#midrail-height2").hide();
                } else {
                    $(".tot-height").hide();
                    $("#property_totheight").val('');
                    $("#property_totheight").removeClass("required");
                    $("#property_totheight").addClass("not-required");
                }
            } else if (style_check.indexOf('Combi') > -1) {
                $("#spare-louvres").hide();

                $("#midrail-height").hide();
                $("#midrail-height2").hide();
                $("#midrail-divider").hide();
                $("#midrail-divider2").hide();
                $("#midrail-height input").val('');
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').show();
                $("#property_bladesize").val('');
                $("#property_bladesize").addClass('not-required');

                //$("#property_sparelouvres").prop('checked', false);
                $("#property_sparelouvres").val('No');
                $("#spare-louvres").hide();
                $("#spare-louvres").closest("div.row").hide();
                $("#ring-pull").show();
                $("#property_controltype").select2('val', '');
                $("#property_controltype").addClass('not-required');
                $("#property_controltype").closest('div').hide();
                $("#control-split-height").hide();

                if ($('form').attr('edit') === 'no') {
                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_frametop").select2("val", '80');
                    $("#property_framebottom").select2("val", '85');
                }

                if (style_check.indexOf('Tier') > -1) {
                    $(".tot-height").fadeIn();
                    $("#property_totheight").fadeIn();
                    $("#property_totheight").addClass("required");
                } else {
                    $(".tot-height").hide();
                    $("#property_totheight").val('');
                    $("#property_totheight").removeClass("required");
                    $("#property_totheight").addClass("not-required");
                }

                $("#solid-panel-height").show();
                $("#property_midrailheight").removeClass("required");

            } else {
                $("#ring-pull").hide();
                $("#solidtype").hide();
                $("#solid-panel-height").hide();
                //$("#property_ringpull").prop('checked', false);
                $("#property_ringpull").val('No');
                $(".tot-height").fadeOut();
                $("#property_horizontaltpost").prop('checked', false);
                $("#property_totheight").val('');
                $("#property_totheight").removeClass("required");
                //if no tier style then add required to midrailheight if height>1800
                if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                    $("#property_midrailheight").addClass("required");
                    console.log('midrail required');
                }

                //$("#solid-panel-height").hide();
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#midrail-divider").show();
                $("#midrail-divider2").show();
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').show();

                //$("#property_sparelouvres").prop('checked', false);
                $("#spare-louvres").show();
                $("#spare-louvres").closest("div.row").show();
                $("#property_controltype").closest('div').show();
                $("#control-split-height").show();
            }
            if (style_check.indexOf('Solid') == -1 && style_check.indexOf('Combi') < -1) {
                $("#midrail-height").show();
                $("#property_bladesize").closest('div').show();
                $("#property_bladesize").removeClass('not-required');

                $("#spare-louvres").show();
                $("#spare-louvres").closest("div.row").show();
                $("#property_controltype").closest('div').show();

                //$("#property_framebottom, #property_frametop,
                // #property_frameright,#property_frameleft").prop('readonly',false);
            }
            if (style_check.indexOf('Solid Panel Bay Window Full Height') > -1) {
                console.log('SELECTED Solid Panel Bay Window Full Height');
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#solid-panel-height").hide();
            }
            if (style_check.indexOf('Solid Panel Bay Window Cafe Style') > -1) {
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
            } else if (style_check.indexOf('Solid Combi Panel Bay Window') > -1) {
                console.log('Solid Combi Panel Bay Window');
                $("#solid-panel-height").show();
                $("#property_bladesize").closest('div').show();
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
            }
            // else{
            //     $("#solid-panel-height").hide();
            //     $("#midrail-height").hide();
            //     $("#midrail-height2").hide();
            // }

            if ($('#property_horizontaltpost').is(":visible") === false) {
                $("#property_horizontaltpost").prop('checked', false);
                $("#property_horizontaltpost").attr('value', 'No');
            } else {
                $("#property_horizontaltpost").attr('value', 'Yes');
            }
        }

        //we need to show/hide control split height 2 if we have midrail or totheight
        function showHideControlSplit2() {
            let check_height = parseFloat($("#property_height").val());
            let check_louvresize = getPropertyBladesize();
            let check_controltype = $("#property_controltype").val();
            let check_controlsplitheight = parseFloat($("#property_controlsplitheight").val());
            let check_controlsplitheight2 = parseFloat($("#property_controlsplitheight2").val());
            let check_midrailheight = getPropertyMidrailheight();
            let check_totheight = getPropertyTotHeight();
            let show_split2 = false;

            if (check_controltype == '96' || check_controltype == '95') {
                let height_required_split;
                if (check_louvresize == '63') height_required_split = 876;
                if (check_louvresize == '76') height_required_split = 1060;
                if (check_louvresize == '89') height_required_split = 1105;

                if (check_midrailheight > 0 || check_totheight > 0) {

                    let split_panel_at_height = 0;
                    if (check_midrailheight > 0) split_panel_at_height = check_midrailheight;
                    if (check_totheight > 0) split_panel_at_height = check_totheight;
                    let panel1_height = check_height - split_panel_at_height;
                    let panel2_height = check_height - panel1_height;

                    if (panel1_height > height_required_split && panel2_height > height_required_split) {
                        show_split2 = true;
                    }
                }
            }
            if (show_split2) {
                $("#property_controlsplitheight2").show();
            } else {
                $("#property_controlsplitheight2").hide();
                $("#property_controlsplitheight2").val(0);
            }
        }

        function checkShutterType() {
            let disable_property_values = [];
            if (shutter_type == 'Shutter') {
                let disable_property_values = [];
            } else if (shutter_type == 'Blackout') {
                let disable_property_values = [187, 33, 35];
            }

            for (let i = 0; i < disable_property_values.length; i++) {
                for (let j = 0; j < property_values.length; j++) {
                    if (property_values[j].id == disable_property_values[i]) {
                        property_values.splice(j);
                    }
                }
            }
        }

        function filterControlType() {
            let property_material = ($("#property_material").select2('data') ? $("#property_material").select2('data').value : '');
            let property_bladesize = ($("#property_bladesize").select2('data') ? $("#property_bladesize").select2('data').value : '');
            let property_stile = ($('input[name=property_stile]:checked').attr('data-title') ? $('input[name=property_stile]:checked').attr('data-title') : '');
            let property_hingecolour = ($("#property_hingecolour").select2('data') ? $("#property_hingecolour").select2('data').value : '');
            let show_hidden_tilt = false

            if (
                property_material.indexOf('PVC') == -1 &&
                property_bladesize.indexOf('47mm') == -1 &&
                property_bladesize.indexOf('114mm') == -1 &&
                //property_stile.indexOf('38.1') == -1 &&
                property_hingecolour.indexOf('Hidden') == -1) {
                show_hidden_tilt = true;
            } else {
                show_hidden_tilt = false;
            }

            let controltype_data = getAllFieldData(getPropertyIdByCode('property_controltype'));
            // console.log(controltype_data);
            let new_controltype_data = [];
            $.each(controltype_data, function (index, row) {
                //aluminum has only hidden rod teo_03-Earth Hidden Only
//teo_04-Earth Hidden Only                    if (property_material.indexOf('Earth') > -1 &&
// row.value.indexOf('Clearview') == -1) {
                if (property_material.indexOf('Earth') > -1 && row.value.indexOf('Hidden') == -1) {
                    return true;
                }

                if (property_material.indexOf('PVC') > -1 && property_material.indexOf('UK') > -1) {
                    if (row.value.indexOf('Clearview') == -1)
                        return true;
                }

                if (show_hidden_tilt) {
                    new_controltype_data.push(row);
                } else {
                    if (row.value.indexOf('Hidden') == -1) {
                        new_controltype_data.push(row);
                    }
                }
            });
            // console.log(new_controltype_data);

            loadItems('property_controltype', new_controltype_data);
        }

        /* submit checks */
        $("#add-product-single-form").submit(function (e) {
            e.preventDefault();
            alert('submit-press');
            resetErrors();


            let check_height = parseFloat($("#property_height").val());
            let style_check = getStyleTitle();

            let check_controltype = $("#property_controltype").val();
            let check_controlsplitheight = parseFloat($("#property_controlsplitheight").val());
            let check_controlsplitheight2 = parseFloat($("#property_controlsplitheight2").val());
            let check_midrailheight = getPropertyMidrailheight();
            if (check_midrailheight == '') {
                check_midrailheight = 0;
            }
            if (isNaN(check_controlsplitheight)) {
                check_controlsplitheight = 0;
            }
            let check_totheight = getPropertyTotHeight();
            let width_and_height_errors = '';
            let width_and_height_errors_count = 0;
            let tpost_count = 0;

            //find any validation errors
            errors = 0;
            $(".select2-container").removeClass("error-field");
            $("input").removeClass("error-field");
            $(".error-text").remove();
            $("#nowarranty").hide();
            if ($('input[name=property_style]:checked').length == 0) {
                errors++;
                $("<span class=\"error-text\">Please select a style</span>").insertAfter($("#choose-style"));
            }

            //check if property frametype is selected & if the selected value is visible
            //parent is used, because input is by default hidden, but the parent is not
            if ($('input[name=property_frametype]:checked').length == 0) {
                errors++;
                $("<span class=\"error-text\">Please select frame type</span>").insertBefore($("#choose-frametype"));
                $("#choose-frametype").closest(".panel").find(".panel-collapse").collapse("show");
                let error_text = 'Please select frame type';
                addError("property_frametype", error_text);
                modalShowError(error_text);
            }
            //check if select or input that are marked as required have values
            $("select.required, input.required").not('.not-required').each(function () {
                if ($(this).val() == '' && $(this).is(":visible")) {
                    errors++;
                    addError($(this).attr('id'), 'Please fill in this field');
                } else {
                    //also check if select2('data') has values for required select boxes, we need this check for
                    // filtered values
                    if ($(this).data('select2') != 'undefined') {
                        console.log("Checking valid select2 data for id " + $(this).attr('id'));
                        console.log($(this).data('select2'));
                        if (!$(this).select2('data') || $(this).select2('data').id == 'undefined') {
                            errors++;
                            addError($(this).attr('id'), 'Please fill in this field');
                        }
                    }
                }
            });

            $(".extra-columns-row input.required").each(function () {
                if ($(this).val() == '') {
                    errors++;
                    addError($(this).attr('id'), 'Please fill in this field');
                }
            });

            $(".number").each(function () {
                if ($(this).val() != '' & !isPositiveInteger($(this).val())) {
                    errors++;
                    addError($(this).attr('id'), 'Please enter a correct number');
                }
            });

            $("input.property-select").not('.not-required').each(function () {
                if ($(this).val() == '' && $(this).is(":visible")) {
                    errors++;
                    addError($(this).attr('id'), 'Please fill in this field');
                } else {
                    //also check if select2('data') has values for required select boxes, we need this check for
                    // filtered values
                    if ($(this).data('select2') != 'undefined') {
                        console.log("Checking valid select2 data for id " + $(this).attr('id'));
                        console.log($(this).data('select2'));
                        if (!$(this).select2('data') || $(this).select2('data').id == 'undefined' && $(this).is(":visible")) {
                            errors++;
                            addError($(this).attr('id'), 'Please fill in this field');
                        }
                    }
                }
            });

            //midrail should be below 1800mm
            if (check_midrailheight > 1800) {
                let error_text = 'Midrail Height should be below 1800mm';
                width_and_height_errors = width_and_height_errors + error_text + '. ';
                width_and_height_errors_count++;
                errors++;
                addError("property_midrailheight", error_text);
            }

            //minimum height check
            if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) < 260) {
                errors++;
                let error_text = 'Height should be at least 260mm';
                width_and_height_errors = width_and_height_errors + error_text + '. ';
                width_and_height_errors_count++;
                addError("property_height", error_text);
            }

            //height check for tot and not tot
            // let stile_check = getPropertyStile();
            // if (stile_check == 50.8) {
            //     panel_height = 1800;
            // } else {
            //     panel_height = 1500;
            // }
            // max_height = panel_height * 2;

            //height check for tot and not tot
            let id_material = $("input#property_material").val();
            //let stile_check = getPropertyStile();
            //green-137
            if (id_material == 137) {
                let panel_height = 1350;
            }
            //biowood-138, basswood-139, earth-187
            else {
                let panel_height = 1500;
            }
            let max_height = panel_height * 2;

            if (style_check.indexOf('Tier') > -1) {
                if (check_height > panel_height && check_totheight == 0) {
                    errors++;
                    let error_text = 'T-o-t height required for height more than ' + panel_height.toString() + 'mm. ';
                    width_and_height_errors = width_and_height_errors + error_text;
                    width_and_height_errors_count++;
                    addError("property_totheight", error_text);
                }
            } else {
                if (check_height > panel_height && check_midrailheight == 0) {
                    errors++;
                    let error_text = 'Midrail height required for height more than ' + panel_height.toString() + 'mm. ';
                    width_and_height_errors = width_and_height_errors + error_text;
                    width_and_height_errors_count++;
                    addError("property_midrailheight", error_text);
                }
            }

            //max height
            if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) > max_height) {
                errors++;
                let error_text = 'Height should not exceed ' + max_height.toString() + 'mm. ';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_height", error_text);
            }

            //midrailheight should not be more than height of shutter
            if (check_midrailheight > 0 && (check_midrailheight > check_height)) {
                let error_text = 'Midrail Height should not exceed height of shutter ' + check_height.toString() + 'mm. ';
                errors++;
                addError("property_midrailheight", error_text);
            }

            //calculate max width
            //consecutive same panels 1=850,2=650,3=550
            let layout_code = $("#property_layoutcode").val();
            layout_code = layout_code.toUpperCase();


            // Extract material ID and louvre size from the DOM
            id_material = $("#property_material").select2('data').id;
            check_louvresize = $("input#property_bladesize").val();

// Default panel widths
            let panel1_width = 890;
            let panel2_width = (id_material == 138 || id_material == 188) ? 625 : 550;
            let panel3_width = 550;

// Adjust panel1_width for specific conditions
            if (id_material == 137 && check_louvresize == 53) {
                panel1_width = 750; // Special case for louvre size 53 and material 137
            }

// Variables for layout parsing
            let last_char = '', counter = 0, total_panels = 0, tracked_layout_error = false;
            let max_width = 0, min_width = 0, current_max_width = 0;

// Process layout code
            for (let i = 0; i < layout_code.length; i++) {
                let char = layout_code.charAt(i);
                if (char != 'L' && char != 'R') {
                    last_char = char; // Update last_char if it's not 'L' or 'R'
                    continue;
                }

                total_panels++;
                let next_char = layout_code.charAt(i + 1);
                let new_panel = last_char != char;
                let end_of_sequence = next_char == 'undefined' || next_char != char;

                if (new_panel) {
                    // Check for tracked layout errors
                    if (style_check.includes('Tracked') && counter % 2 != 0) {
                        tracked_layout_error = true;
                    }
                    counter = 1; // Reset counter for a new sequence
                } else {
                    counter++; // Increment counter for consecutive panels
                }

                if (end_of_sequence) {
                    // Calculate width based on the number of panels
                    current_max_width = [panel1_width, panel2_width, panel3_width][Math.min(counter, 3) - 1] * counter;

                    // Check for errors in non-tracked layouts
                    if (counter > 3 && !style_check.includes('Tracked')) {
                        reportError("property_layoutcode", `Layout code is invalid. No more than 3 consecutive ${char} panels allowed.`);
                    }

                    max_width += current_max_width; // Update max width
                    console.log('counter letters: ' + counter);
                }

                last_char = char; // Update last_char for the next iteration
            }
            //we need to check again tracked at the end if the panels are even
            //check if we dont have an even number of panels for tracked
            if ((style_check.indexOf('Tracked') > -1) && (counter % 2 != 0)) {
                tracked_layout_error = true;
            }

            if (tracked_layout_error) {
                errors++;
                addError("property_layoutcode", 'Tracked shutters require even number of panels per layout code');
            }
            //calculate min width based on the number of panels (Ls&Rs)
            min_width = total_panels * 200;

            //minimum for solid panels and raised is 260mm
            if (style_check.indexOf('Solid') > -1 && style_check.indexOf('Raised') > -1) {
                min_width = total_panels * 250;
            }

            if ($("#property_width").val() != '' && parseFloat($("#property_width").val()) < min_width) {
                errors++;
                let error_text = 'Width should be at least ' + min_width + 'mm. ';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_width", error_text);
            }

            if ($("#property_width").val() != '' && parseFloat($("#property_width").val()) > max_width && !(style_check.indexOf('Tracked') > -1)) {
                errors++;
                let error_text = 'Width should be at most ' + max_width + 'mm for this layout code.';
                width_and_height_errors = width_and_height_errors + error_text;
                width_and_height_errors_count++;
                addError("property_width", error_text);
            }

            //For Blackout shutters a Tpost is required for >1400mm width
            if (shutter_type == 'Blackout' && ((layout_code.match(/t/ig) || []).length == 0 && (layout_code.match(/b/ig) || []).length == 0 && (layout_code.match(/c/ig) || []).length == 0) && parseFloat($("#property_width").val()) > 1400) {
                errors++;
                addError("property_layoutcode", 'Shutter and Blackout Blind require a T-post if width is more than 1400mm');
            }

            if ((layout_code.indexOf("B") > 0 || layout_code.indexOf("C") > 0) && (style_check.indexOf('Bay') == -1)) {
                errors++;
                addError("property_layoutcode", 'Please choose Bay Window style with a layout code containing B or C.');
            }

            /* clearview checks */
            let check_louvresize = getPropertyBladesize();
            if (check_controltype == '96' || check_controltype == '95') {
                const LOUVRE_HEIGHT_MAP = {
                    '47': {default: 923, midrail: 878},
                    '63': {default: 960, midrail: 900},
                    '76': {default: 1155, midrail: 1085},
                    '89': {default: 1190, midrail: 1130},
                    '114': {default: 1200, midrail: null} // Assuming no midrail adjustment is needed for 114
                };

                let split_required = false;
                let split2_required = false;

                let split_min_height = 0;
                let split_max_height = 0;

                let split2_min_height = 0;
                let split2_max_height = 0;

// Simplify the calculation of height_required_split
                let height_required_split = LOUVRE_HEIGHT_MAP[check_louvresize]?.default || 0;
                if (check_midrailheight > 0 && LOUVRE_HEIGHT_MAP[check_louvresize]?.midrail !== null) {
                    height_required_split = LOUVRE_HEIGHT_MAP[check_louvresize].midrail;
                }

                if (check_midrailheight == 0 && check_totheight == 0 && check_height > height_required_split) {
                    split_min_height = 0;
                    split_max_height = height_required_split;
                    split_required = true;


                } else if (check_midrailheight > 0 || check_totheight > 0) {

                    // Simplified calculation of split_panel_at_height based on the greater of check_midrailheight or check_totheight
                    let split_panel_at_height = Math.max(parseInt(check_midrailheight) || 0, parseInt(check_totheight) || 0);

                    let panel1_height = split_panel_at_height;
                    let panel2_height = check_height - panel1_height;

                    console.log("Panel 1 height:" + panel1_height);
                    console.log("Panel 2 height:" + panel2_height);
                    console.log("Height required split:" + height_required_split);

// Determine if splits are required based on the heights of panel1 and panel2 relative to height_required_split
                    split_required = panel1_height > height_required_split || panel2_height > height_required_split;
                    split2_required = panel1_height > height_required_split && panel2_height > height_required_split;

// Update split_min_height and split_max_height based on whether splits are required
                    if (split_required) {
                        if (split2_required) {
                            // Both splits are required
                            split_min_height = 0;
                            split_max_height = split_panel_at_height;
                            split2_min_height = split_panel_at_height + 1;
                            split2_max_height = check_height;
                        } else {
                            // Only one split is required
                            split_min_height = panel1_height > height_required_split ? 0 : split_panel_at_height + 1;
                            split_max_height = panel1_height > height_required_split ? split_panel_at_height : check_height;
                            // Reset the second split as it's not required
                            split2_min_height = 0;
                            split2_max_height = 0;
                        }
                    } else {
                        // No split required, reset values
                        split_min_height = 0;
                        split_max_height = 0;
                        split2_min_height = 0;
                        split2_max_height = 0;
                    }
                }

                if (check_midrailheight > 0 && check_controlsplitheight > 0) {
                    let distance = Math.abs(check_midrailheight - check_controlsplitheight);
                    if (distance <= 10) {
                        let error_text = '<br/>Distance between split height and midrail should be more than 10mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight", error_text);
                    }
                }

                if (check_midrailheight > 0 && parseInt(check_controlsplitheight2) > 0) {
                    let distance = Math.abs(check_midrailheight - parseInt(check_controlsplitheight2));
                    if (distance <= 10) {
                        let error_text = '<br/>Distance between split height 2 and midrail should be more than 10mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }
                }


                if (split2_required) {
                    $("#property_controltype").select2("val", 95); //change to clearview split if not already changed
                    $("#control-split-height").show();
                    $("#property_controlsplitheight2").show();
                    if (parseInt(check_controlsplitheight2) == 0) {
                        let error_text = '<br/>Second split height is required for ' + check_louvresize + 'mm and height ' + check_height + 'mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }

                    if (check_controlsplitheight2 > split2_max_height) {
                        let error_text = '<br/>Second split height should be less than ' + split2_max_height + 'mm';
                        errors++;
                        width_and_height_errors_count++;
                        width_and_height_errors = width_and_height_errors + error_text;
                        addError("property_controlsplitheight2", error_text);
                    }
                    if (check_controlsplitheight2 < split2_min_height) {
                        let error_text = '<br/>Second split height should be more than ' + split2_min_height + 'mm';
                        errors++;
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
                    let existingShapeFile = $('#provided-shape').html().trim();
                    let newShapeFile = $('#attachment').val();
                    /* if (existingShapeFile == '' && newShapeFile == '') {
                            errors++;
                            addError("shape-upload-container", 'Please provide the desired shape for style "Shaped & French Cut Out"');
                        } */
                }
            }

            if ($("#canvas_container1 svg").length > 0) {
                $("#shutter_svg").html($("#canvas_container1").html());
            }

            let nowarranty_checked = $("#property_nowarranty").prop("checked");
            if (errors > 0) {
                if (width_and_height_errors.length > 0 && !nowarranty_checked) {
                    $("#nowarranty").show();
                    showErrorModal("Width and height errors", "This shutter is outside of warranty. The following errors have occured: <br/>" + width_and_height_errors + "<br/><br/>Either <strong>accept</strong> that there will be no warranty or <strong>change</strong> the configuration. ");
                    return false;
                } else if (width_and_height_errors.length > 0 && errors == width_and_height_errors_count && nowarranty_checked) { //if the errors are only width and height and no warranty is checked we can allow everything
                    return true
                } else {
                    return false;
                }
            } else {
                return true;
            }
        });

        $('[data-toggle="tooltip"]').tooltip({
            'placement': 'top'
        });

        if ($('input[name=property_style]:checked').length > 0) {
            $('input[name=property_style]:checked').closest('label').trigger('click');
        }

        $('.drawing-panel').on('shown.bs.collapse', function () {
            //alert('test');
            updateShutter();
        });

        $(window).resize(function () {
            if ($("#canvas_container1").filter(":visible").length > 0) {
                updateShutter();
            }
        });


        $(".print-drawing").click(function () {
            let w = window.open();
            w.document.write($('#canvas_container1').html());
            w.print();
            w.close();

            return false;
        });

        if ($("#property_nowarranty").prop("checked")) {
            $("#nowarranty").show();
        }
        //open first collapsing content on page load
        $(document).ready(function () {
            $("#accordion").find(".panel-collapse").first().collapse("show");
            if ($('input[name=property_frametype]:checked').length > 0) {
                $('input[name=property_frametype]:checked').closest('label').trigger('click');
            }

            $("select#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    console.log('flexible');
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });
        });


        ////////////////////// L50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];

            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// SBS50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_SBS50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", 10 - 22, 0,
                "L", 9 - 22, 1.5,
                "C", 9 - 22, 1.5, 4 - 22, -2, -22, 2.5,

                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z60SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z60SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 20.5,
                "L", 16, 29.5 + 30.5 - 20.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", 16 + 10, 30.5 + 9,
                "L", 16 + 15, 30.5 + 9,
                "L", 16 + 15, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 9
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 60, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L70(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 39.5 + 30.5,
                "L", 16, 39.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 70, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L90 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 90, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 50, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L50MF /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50MF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 25.4 + 30.6,
                "L", 16, 25.4 + 30.6,
                "C", 16, 56, 21.5, 53.4, 16, 51,
                "L", 12.5, 51,
                "L", 12.5, 35.6,
                "L", 16, 35.6,
                "C", 16, 35.6, 21.5, 33, 16, 30.6,
                "L", 12.5, 30.6,
                "L", 0, 30.6,
                "L", 0, 2.5,

                "M", 23, 51,
                "L", 28, 51,
                "L", 28, 36,
                "L", 23, 36,
                "L", 23, 51,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11


            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 56, 43.9, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L60SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_L60SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -22, 0,
                "L", -22, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 20.5,
                "L", 16, 29.5 + 30.5 - 20.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", 16 + 10, 30.5 + 9,
                "L", 16 + 15, 30.5 + 9,
                "L", 16 + 15, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 9
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 60, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F70(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 39.5 + 30.5,
                "L", 16, 39.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 70, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 90, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        ////////////////////// Z40 /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", 10 - 22 - 18, 0,
                "C", 10 - 22 - 18, 0, 10 - 22 - 28 + 1, 1, 10 - 22 - 28, 9,
                "L", 10 - 22 - 28, 9,
                "L", -22, 9,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z40SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", 8 - 22 - 18, 0,
                "C", 8 - 22 - 18, 0, 8 - 22 - 28 + 1, 1, 8 - 22 - 28, 9,
                "L", 8 - 22 - 28, 9,
                "L", -22, 9,
                "L", -22, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5,
                "L", 16, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 9,
                "L", 16 - 5, 29.5 + 30.5 - 20.5,
                "L", 16, 29.5 + 30.5 - 20.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", 16 + 10, 30.5 + 9,
                "L", 16 + 15, 30.5 + 9,
                "L", 16 + 15, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 20.5,
                "L", 16 + 10, 30.5 + 9
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 60, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -22 - 18, 0,
                "C", -22 - 18, 0, -22 - 28 + 1, 1, -22 - 28, 9,
                "L", -22 - 28, 9,
                "L", -22, 9,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z2BS /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z2BS(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = [
                "M", 0, 0,
                "L", -12, 0,
                "C", -12, 0, -13, 0, -14, 1.5,
                "C", -15, 1.5, -18.5, -2.5, -22, 4,
                "L", -22 - 13.8, 0,
                "C", -22 - 13.8, 0, -22 - 15.8, -0.5, -22 - 17.8, 2,
                "C", -22 - 17.8, 2, -22 - 25, -4, -22 - 28.8, 5,
                "L", -22 - 28.8, 12,
                "L", -22, 12,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z3CS /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z3CS(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -6, 0, //rightmost half (straight)
                "C", -6, 0, -8, -1, -10, 1, //rightmost half arc
                "C", -10, 1, -11.5, 3, -13, 2, //opposite arc between
                "C", -13, 2, -15, 0.5, -18, 3, //right low arc
                /////////////////////
                "L", -22, 6,
                "C", -22, 6, -22 - 16.1, -7, -22 - 32.2, 6, //center arc
                "L", -22 - 36.2, 3,
                /////////////////////
                "C", -22 - 36.2, 3, -22 - 38.2, 0.5, -22 - 41.2, 2, //left low arc
                "C", -22 - 41.2, 2, -22 - 42.2, 3, -22 - 44.2, 1, //opposite arc between
                "C", -22 - 44.2, 1, -22 - 50.2, -3, -22 - 54.2, 4, //leftmost half arc
                "L", -22 - 54.2, 4,
                "L", -22 - 54.2, 14,
                "L", -22, 14,
                "L", -22, 19.5 + 30.5,
                "L", 16, 19.5 + 30.5,
                "L", 16, 0 + 30.5,
                "L", 0, 30.5,
                'L', 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// D50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_D50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -12, 0,
                "C", -12, 0, -13, 0, -14, 1.5,
                "C", -15, 1.5, -19, -2.5, -22, 4,
                "L", -22, 30.5 - 6.5,
                "L", -22 - 22, 30.5 - 6.5,
                "L", -22 - 22, 30.5 - 13,
                "C", -22 - 22, 30.5 - 13, -22 - 26, 13, -22 - 28.8, 30.5 - 11,
                "L", -22 - 28.8, 30.5 + 6.5,
                "L", 16, 30.5 + 6.5,
                "L", 16, 30.5,
                "L", 0, 30.5,
                "L", 0, 0,
                "M", -22 - 22, 30.5 - 13,
                "C", -22 - 22, 30.5 - 13, -22 - 16, 5, -22, 6
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-50.8, 37, 66.8, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// BL90 /////////////////////////////////////////////////////////////////////////
        function drawFrame_BL90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6 - 26,
                "L", 16, 59.5 + 30.5 - 6 - 26,
                // "L", 16, 30.5,
                "L", 16, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 20.5,
                "L", 16, 27.5 + 30.5 - 20.5,
                "L", 16, 30.5,

                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -11,
                "L", -5, -11,
                "L", -5, -5,
                "L", -5 - 15.4, -5,
                "L", -5 - 15.4, -11,

                "M", 16 + 10, 30.5 + 8,
                "L", 16 + 15, 30.5 + 8,
                "L", 16 + 15, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 8
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 90, 41.4, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", 7 - 25.4, 0,
                "L", 6 - 25.4, 1.5,
                "L", 5 - 25.4, 1.5,
                "C", 5 - 25.4, 1.5, 2.5 - 25.4, -2.5, -25.4, 1.5,
                "L", -25.4, 47.15,
                "L", 9.53, 47.15,
                "L", 9.53, 28.1,
                "L", 0, 28.1,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-25.4, 47.15, 34.93, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        ////////////////////// F50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "M", 0, 2.5,
                "C", 0, 2.5, -3, -2.5, -6, 2.5,
                "L", -6, 2.5,
                "L", -6, 7,
                "L", -7, 7,
                "L", -7, 12,
                "L", -15, 12,
                "L", -15, 7,
                "L", -16, 7,
                "L", -16, 2.5,
                "C", -16, 2.5, -19, -2.5, -22, 2.5,
                "L", -22, 19.5 + 30.5,
                "L", -11, 19.5 + 30.5,
                "C", -11, 19.5 + 30.5, -8, 19.5 + 30.5, -8, 19.5 + 30.5 - 3,
                "L", 2.5, 19.5 + 30.5 - 3,
                "C", 2.5, 19.5 + 30.5 - 3, 2.5, 19.5 + 30.5, 5.5, 19.5 + 30.5,
                "L", 5.5, 19.5 + 30.5,
                "L", 16.5, 19.5 + 30.5,
                "L", 16.5, 30.5,
                "L", 0, 30.5,
                "L", 0, 2,
                "M", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 50, 38.5, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F70PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "M", 0, 2.5,
                "C", 0, 2.5, -3, -2.5, -6, 2.5,
                "L", -6, 2.5,
                "L", -6, 7,
                "L", -7, 7,
                "L", -7, 12,
                "L", -15, 12,
                "L", -15, 7,
                "L", -16, 7,
                "L", -16, 2.5,
                "C", -16, 2.5, -19, -2.5, -22, 2.5,
                "L", -22, 39.5 + 30.5,
                "L", -11, 39.5 + 30.5,
                "C", -11, 39.5 + 30.5, -8, 39.5 + 30.5, -8, 39.5 + 30.5 - 3,
                "L", 2.5, 39.5 + 30.5 - 3,
                "C", 2.5, 39.5 + 30.5 - 3, 2.5, 39.5 + 30.5, 5.5, 39.5 + 30.5,
                "L", 5.5, 39.5 + 30.5,
                "L", 16.5, 39.5 + 30.5,
                "L", 16.5, 30.5,
                "L", 0, 30.5,
                "L", 0, 2,
                "M", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 70, 38.5, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F90PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "M", 0, 2.5,
                "C", 0, 2.5, -3, -2.5, -6, 2.5,
                "L", -6, 2.5,
                "L", -6, 7,
                "L", -7, 7,
                "L", -7, 12,
                "L", -15, 12,
                "L", -15, 7,
                "L", -16, 7,
                "L", -16, 2.5,
                "C", -16, 2.5, -19, -2.5, -22, 2.5,
                "L", -22, 59.5 + 30.5,
                "L", -11, 59.5 + 30.5,
                "C", -11, 59.5 + 30.5, -8, 59.5 + 30.5, -8, 59.5 + 30.5 - 3,
                "L", 2.5, 59.5 + 30.5 - 3,
                "C", 2.5, 59.5 + 30.5 - 3, 2.5, 59.5 + 30.5, 5.5, 59.5 + 30.5,
                "L", 5.5, 59.5 + 30.5,
                "L", 16.5, 59.5 + 30.5,
                "L", 16.5, 30.5,
                "L", 0, 30.5,
                "L", 0, 2,
                "M", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-22, 70, 38.5, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z40PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", 10 - 22 - 20, 0,
                "C", 10 - 22 - 20, 0, 10 - 22 - 28 + 1, 1, 10 - 22 - 28, 9,
                "L", 10 - 22 - 28, 9.53,
                "L", -19.5, 9.53,

                "L", -19.5, 19.05 + 28.1 - 24 - 1,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 24,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 12,
                "L", -19.5, 19.05 + 28.1 - 12 + 1,

                "L", -19.5, 19.05 + 28.1,
                "L", 9.53, 19.05 + 28.1,
                "L", 9.53, 28.1,
                "L", 0, 28.1,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-19.05, 47.15, 28.58, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", 10 - 22 - 30, 0,
                "C", 10 - 22 - 30, 0, 10 - 22 - 38 + 1, 1, 10 - 22 - 38, 9,
                "L", 10 - 22 - 38, 9.53,
                "L", -19.5, 9.53,

                "L", -19.5, 19.05 + 28.1 - 24 - 1,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 24,
                "L", -19.5 + 1.5, 19.05 + 28.1 - 12,
                "L", -19.5, 19.05 + 28.1 - 12 + 1,

                "L", -19.5, 19.05 + 28.1,
                "L", 9.53, 19.05 + 28.1,
                "L", 9.53, 28.1,
                "L", 0, 28.1,
                "L", 0, 0
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-19.05, 47.15, 28.58, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z2BSPVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z2BSPVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 4,
                "C", 0, 4, -4, -3, -10, 4, //rightmost arc
                "L", -15, 4,
                "C", -15, 4, -15 - 2.25, 6, -19.5, 4,
                "L", -24.5, 4,
                "C", -24.5, 4, -24.5 - 7.25, -3, -24.5 - 14.5, 4, //center arc
                "L", -24.5 - 19.5, 4,
                "C", -24.5 - 19.5, 4, -24.5 - 21.75, 6, -24.5 - 24, 4,
                "L", -22 - 31.5, 4,
                "C", -22 - 31.5, 4, -22 - 37.5, -3, -22 - 41.5, 4, //leftmost half arc
                "L", -63.5, 14.5,
                "L", -20, 14.5,
                "L", -20, 14.5 + 8.65,
                "L", -20, 14.5 + 8.65 + 16,
                "L", -20, 15.5 + 31.65,
                "L", 6.5 - 20, 15.5 + 31.65,
                "L", 7.5 - 20, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 7.5, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 6.5, 15.5 + 31.65,
                "L", 9.5, 15.5 + 31.65,
                "L", 9.5, 31.65,
                "L", 0, 31.65,
                "L", 0, 4
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-19.05, 47.15, 28.58, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z3CSPVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z3CSPVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 4,
                "C", 0, 4, -4, -3, -10, 4, //rightmost arc
                "C", -10, 4, -14, 0, -18, 5, //right low arc
                "C", -18, 5, -22 - 16.1, -6, -22 - 36.2, 5, //center arc
                "C", -22 - 36.2, 5, -22 - 39.2, 0, -22 - 44.2, 4, //left low arc
                "C", -22 - 44.2, 4, -22 - 50.2, -3, -22 - 54.2, 4, //leftmost half arc
                "L", -22 - 54.2, 14.5,
                "L", -26, 14.5,
                "L", -26, 14.5 + 8.65,
                "L", 10 - 26, 14.5 + 8.65,
                "L", 10 - 26, 14.5 + 8.65 + 16,
                "L", -26, 14.5 + 8.65 + 16,
                "L", -26, 15.5 + 31.65,
                "L", 6.5 - 26, 15.5 + 31.65,
                "L", 7.5 - 26, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 7.5, 15.5 + 31.65 - 1.5,
                "L", 9.5 - 6.5, 15.5 + 31.65,
                "L", 9.5, 15.5 + 31.65,
                "L", 9.5, 31.65,
                "L", 0, 31.65,
                'L', 0, 4
            ];
            if (buildoutHeight && buildoutHeight > 0) {
                path_a = path_a.concat(drawBuildoutPath(-26, 47.15, 35.47, buildoutHeight));
            }
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };
        ///////////////////////////////////////////////////
        /////////////// STILES and PANEL //////////////////
        ///////////////////////////////////////////////////
        function drawPanelStile_FS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 38.1;
            let rightStileWidth = 38.1;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            let left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RFS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 38.1 - (leftFlat == false ? 6 : 0);
            let rightStileWidth = 38.1;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 38.1, -27,
                    "L", -6 + 38.1, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat == true) {
                let right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                let right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, -27 + 14.5,
                    "L", -6 + 38.1, -27 + 14.5,
                    "L", -6 + 38.1, 0,
                    "L", 0, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DFS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 38.1;
            let rightStileWidth = 38.1;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 38.1;
            let rightStileWidth = 38.1;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            let left_path = [
                "M", 0, 0,
                "L", 0, -27,

                "L", 38.1 - 9, -27,
                "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                "L", 38.1, -3,
                "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                "L", 38.1 - 9, 0,

                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 38.1 - (leftFlat == false ? 6 : 0);
            let rightStileWidth = 38.1;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 38.1 - 9, -27,
                    "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                    "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                    "L", 38.1, -3,
                    "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                    "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                    "L", 38.1 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 38.1 - 9, -27,
                    "C", -6 + 38.1 - 9, -27, -6 + 38.1 - 9 + 1, -27, -6 + 38.1 - 9 + 3, -27 + 2,
                    "C", -6 + 38.1 - 9 + 3, -27 + 2, -6 + 38.1 - 9 + 6.5, -27 - 1, -6 + 38.1, -27 + 3,
                    "L", -6 + 38.1, -3,
                    "C", -6 + 38.1, -3, -6 + 38.1 - 9 + 6.5, 1, -6 + 38.1 - 9 + 3, -2,
                    "C", -6 + 38.1 - 9 + 3, -2, -6 + 38.1 - 9 + 1, 0, -6 + 38.1 - 9, 0,

                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat) {
                let right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 9, 0
                ];
            } else {
                let right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 38.1, -27,
                    "L", 38.1, -27 + 14.5,
                    "L", -6 + 38.1, -27 + 14.5,
                    "L", -6 + 38.1, 0,
                    "L", 9, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 38.1;
            let rightStileWidth = 38.1;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 38.1 - 9, -27,
                    "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                    "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                    "L", 38.1, -3,
                    "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                    "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                    "L", 38.1 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 38.1 - 9, -27,
                    "C", 38.1 - 9, -27, 38.1 - 9 + 1, -27, 38.1 - 9 + 3, -27 + 2,
                    "C", 38.1 - 9 + 3, -27 + 2, 38.1 - 9 + 6.5, -27 - 1, 38.1, -27 + 3,
                    "L", 38.1, -3,
                    "C", 38.1, -3, 38.1 - 9 + 6.5, 1, 38.1 - 9 + 3, -2,
                    "C", 38.1 - 9 + 3, -2, 38.1 - 9 + 1, 0, 38.1 - 9, 0,
                    "L", 38.1 - 9, 0,

                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_FS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8;
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            let left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RFS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");   //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 50.8, -27,
                    "L", -6 + 50.8, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat == true) {
                let right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                let right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, -27 + 14.5,
                    "L", -6 + 50.8, -27 + 14.5,
                    "L", -6 + 50.8, 0,
                    "L", 0, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DFS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8;
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8;
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            let left_path = [
                "M", 0, 0,
                "L", 0, -27,

                "L", 50.8 - 9, -27,
                "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                "L", 50.8, -3,
                "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                "L", 50.8 - 9, 0,

                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 50.8 - 9, -27,
                    "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                    "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                    "L", 50.8, -3,
                    "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                    "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                    "L", 50.8 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", -6, 0,
                    "L", -6, -14.5,
                    "L", 0, -14.5,
                    "L", 0, -27,
                    "L", -6 + 50.8 - 9, -27,
                    "C", -6 + 50.8 - 9, -27, -6 + 50.8 - 9 + 1, -27, -6 + 50.8 - 9 + 3, -27 + 2,
                    "C", -6 + 50.8 - 9 + 3, -27 + 2, -6 + 50.8 - 9 + 6.5, -27 - 1, -6 + 50.8, -27 + 3,
                    "L", -6 + 50.8, -3,
                    "C", -6 + 50.8, -3, -6 + 50.8 - 9 + 6.5, 1, -6 + 50.8 - 9 + 3, -2,
                    "C", -6 + 50.8 - 9 + 3, -2, -6 + 50.8 - 9 + 1, 0, -6 + 50.8 - 9, 0,

                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat) {
                let right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 9, 0
                ];
            } else {
                let right_path = [
                    "M", 9, 0,
                    "C", 9, 0, 9 - 1, 0, 6, -2,
                    "C", 6, -2, 2.5, 1, 0, -3,
                    "L", 0, -27 + 3,
                    "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                    "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                    "L", 50.8, -27,
                    "L", 50.8, -27 + 14.5,
                    "L", -6 + 50.8, -27 + 14.5,
                    "L", -6 + 50.8, 0,
                    "L", 9, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8;
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 50.8 - 9, -27,
                    "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                    "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                    "L", 50.8, -3,
                    "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                    "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                    "L", 50.8 - 9, 0,

                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,

                    "L", 50.8 - 9, -27,
                    "C", 50.8 - 9, -27, 50.8 - 9 + 1, -27, 50.8 - 9 + 3, -27 + 2,
                    "C", 50.8 - 9 + 3, -27 + 2, 50.8 - 9 + 6.5, -27 - 1, 50.8, -27 + 3,
                    "L", 50.8, -3,
                    "C", 50.8, -3, 50.8 - 9 + 6.5, 1, 50.8 - 9 + 3, -2,
                    "C", 50.8 - 9 + 3, -2, 50.8 - 9 + 1, 0, 50.8 - 9, 0,
                    "L", 50.8 - 9, 0,

                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 9, 0,
                "C", 9, 0, 9 - 1, 0, 6, -2,
                "C", 6, -2, 2.5, 1, 0, -3,
                "L", 0, -27 + 3,
                "C", 0, -27 + 3, 2.5, -27 - 1, 6, -27 + 2,
                "C", 6, -27 + 2, 9 - 1, -27, 9, -27,

                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 9, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8;
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            let left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8 - 7, -27,
                "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                "L", 50.8, -27 + 4,
                "L", 50.8 - 5, -27 + 4,
                "L", 50.8 - 5, -27 + 4 + 3,
                "L", 50.8 - 5 - 9, -27 + 4 + 3,
                "L", 50.8 - 5 - 9, -27 + 4,
                "L", 50.8 - 32, -27 + 4,
                "L", 50.8 - 32, -4,
                "L", 50.8 - 5 - 9, -4,
                "L", 50.8 - 5 - 9, -4 - 3,
                "L", 50.8 - 5, -4 - 3,
                "L", 50.8 - 5, -4,
                "L", 50.8, -4,
                "L", 50.8, -1,
                "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 7, 0,
                "C", 7, 0, 4.75, -2.5, 2.5, -1,
                "C", 2.5, -1, 1, 1, 0, -1,
                "L", 0, -4,
                "L", 5, -4,
                "L", 5, -4 - 3,
                "L", 5 + 9, -4 - 3,
                "L", 5 + 9, -4,
                "L", 32, -4,
                "L", 32, -27 + 4,
                "L", 5 + 9, -27 + 4,
                "L", 5 + 9, -27 + 4 + 3,
                "L", 5, -27 + 4 + 3,
                "L", 5, -27 + 4,
                "L", 0, -27 + 4,
                "L", 0, -27 + 1,
                "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 7, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");   //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8 - 7, -27,
                    "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                    "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                    "L", 50.8, -27 + 4,
                    "L", 50.8 - 5, -27 + 4,
                    "L", 50.8 - 5, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4,
                    "L", 50.8 - 32, -27 + 4,
                    "L", 50.8 - 32, -4,
                    "L", 50.8 - 5 - 9, -4,
                    "L", 50.8 - 5 - 9, -4 - 3,
                    "L", 50.8 - 5, -4 - 3,
                    "L", 50.8 - 5, -4,
                    "L", 50.8, -4,
                    "L", 50.8, -1,
                    "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                    "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -14.5,
                    "L", -6, -14.5,
                    "L", -6, -27,
                    "L", -6 + 50.8 - 7, -27,
                    "L", -6 + 50.8 - 7, -27,
                    "C", -6 + 50.8 - 7, -27, -6 + 50.8 - 4.75, -27 + 2.5, -6 + 50.8 - 2.5, -27 + 1,
                    "C", -6 + 50.8 - 2.5, -27 + 1, -6 + 50.8 - 1, -27 - 1, -6 + 50.8, -27 + 1,
                    "L", -6 + 50.8, -27 + 4,
                    "L", -6 + 50.8 - 5, -27 + 4,
                    "L", -6 + 50.8 - 5, -27 + 4 + 3,
                    "L", -6 + 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", -6 + 50.8 - 5 - 9, -27 + 4,
                    "L", -6 + 50.8 - 32, -27 + 4,
                    "L", -6 + 50.8 - 32, -4,
                    "L", -6 + 50.8 - 5 - 9, -4,
                    "L", -6 + 50.8 - 5 - 9, -4 - 3,
                    "L", -6 + 50.8 - 5, -4 - 3,
                    "L", -6 + 50.8 - 5, -4,
                    "L", -6 + 50.8, -4,
                    "L", -6 + 50.8, -1,
                    "C", -6 + 50.8, -1, -6 + 50.8 - 1, 1, -6 + 50.8 - 2.5, -1,
                    "C", -6 + 50.8 - 2.5, -1, -6 + 50.8 - 4.75, -2.5, -6 + 50.8 - 7, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            if (rightFlat == true) {
                let right_path = [
                    "M", 7, 0,
                    "C", 7, 0, 4.75, -2.5, 2.5, -1,
                    "C", 2.5, -1, 1, 1, 0, -1,
                    "L", 0, -4,
                    "L", 5, -4,
                    "L", 5, -4 - 3,
                    "L", 5 + 9, -4 - 3,
                    "L", 5 + 9, -4,
                    "L", 32, -4,
                    "L", 32, -27 + 4,
                    "L", 5 + 9, -27 + 4,
                    "L", 5 + 9, -27 + 4 + 3,
                    "L", 5, -27 + 4 + 3,
                    "L", 5, -27 + 4,
                    "L", 0, -27 + 4,
                    "L", 0, -27 + 1,
                    "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                    "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 7, 0
                ];
            } else {
                let right_path = [
                    "M", 7, 0,
                    "C", 7, 0, 4.75, -2.5, 2.5, -1,
                    "C", 2.5, -1, 1, 1, 0, -1,
                    "L", 0, -4,
                    "L", 5, -4,
                    "L", 5, -4 - 3,
                    "L", 5 + 9, -4 - 3,
                    "L", 5 + 9, -4,
                    "L", 32, -4,
                    "L", 32, -27 + 4,
                    "L", 5 + 9, -27 + 4,
                    "L", 5 + 9, -27 + 4 + 3,
                    "L", 5, -27 + 4 + 3,
                    "L", 5, -27 + 4,
                    "L", 0, -27 + 4,
                    "L", 0, -27 + 1,
                    "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                    "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                    "L", -6 + 50.8, -27,
                    "L", -6 + 50.8, -27 + 14.5,
                    "L", 50.8, -27 + 14.5,
                    "L", 50.8, 0,
                    "L", 7, 0
                ];
            }
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let leftStileWidth = 50.8;
            let rightStileWidth = 50.8;
            let louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8 - 7, -27,
                    "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                    "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                    "L", 50.8, -27 + 4,
                    "L", 50.8 - 5, -27 + 4,
                    "L", 50.8 - 5, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4,
                    "L", 50.8 - 32, -27 + 4,
                    "L", 50.8 - 32, -4,
                    "L", 50.8 - 5 - 9, -4,
                    "L", 50.8 - 5 - 9, -4 - 3,
                    "L", 50.8 - 5, -4 - 3,
                    "L", 50.8 - 5, -4,
                    "L", 50.8, -4,
                    "L", 50.8, -1,
                    "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                    "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                    "L", 0, 0
                ];
            } else {
                let left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8 - 7, -27,
                    "C", 50.8 - 7, -27, 50.8 - 4.75, -27 + 2.5, 50.8 - 2.5, -27 + 1,
                    "C", 50.8 - 2.5, -27 + 1, 50.8 - 1, -27 - 1, 50.8, -27 + 1,
                    "L", 50.8, -27 + 4,
                    "L", 50.8 - 5, -27 + 4,
                    "L", 50.8 - 5, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4 + 3,
                    "L", 50.8 - 5 - 9, -27 + 4,
                    "L", 50.8 - 32, -27 + 4,
                    "L", 50.8 - 32, -4,
                    "L", 50.8 - 5 - 9, -4,
                    "L", 50.8 - 5 - 9, -4 - 3,
                    "L", 50.8 - 5, -4 - 3,
                    "L", 50.8 - 5, -4,
                    "L", 50.8, -4,
                    "L", 50.8, -1,
                    "C", 50.8, -1, 50.8 - 1, 1, 50.8 - 2.5, -1,
                    "C", 50.8 - 2.5, -1, 50.8 - 4.75, -2.5, 50.8 - 7, 0,
                    "L", 0, 0,
                    "L", -11, 0,
                    "L", -11, 5,
                    "L", 11, 5,
                    "L", 11, 0,
                    "L", 0, 0
                ];
            }
            // create louvre path and relocate next to left stile
            let louvre_path = [
                "M", 0, -3,
                "L", 0, -24,
                "L", louvreWidth, -24,
                "L", louvreWidth, -3,
                "L", 0, -3
            ];
            pathRelocation(louvre_path, {
                "x": leftStileWidth,
                "y": 0
            });
            // create right stile path and relocate next to louvre
            let right_path = [
                "M", 7, 0,
                "C", 7, 0, 4.75, -2.5, 2.5, -1,
                "C", 2.5, -1, 1, 1, 0, -1,
                "L", 0, -4,
                "L", 5, -4,
                "L", 5, -4 - 3,
                "L", 5 + 9, -4 - 3,
                "L", 5 + 9, -4,
                "L", 32, -4,
                "L", 32, -27 + 4,
                "L", 5 + 9, -27 + 4,
                "L", 5 + 9, -27 + 4 + 3,
                "L", 5, -27 + 4 + 3,
                "L", 5, -27 + 4,
                "L", 0, -27 + 4,
                "L", 0, -27 + 1,
                "C", 0, -27 + 1, 1, -27 - 1, 2.5, -27 + 1,
                "C", 2.5, -27 + 1, 4.75, -27 + 2.5, 7, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 7, 0
            ];
            pathRelocation(right_path, {
                "x": leftStileWidth + louvreWidth,
                "y": 0
            });

            // concat all paths in one
            let panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        //////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////// POSTS ////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        function drawHalfPost_Post50(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            let width = 25.4;

            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 50,
                "L", 9.53, 50,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    let path_b = ["M", 0, 50,
                        "L", 9.53, 50,
                        "L", 9.53, 50+buildoutHeight,
                        "L", -width/2, 50+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post70(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            let width = 25.4;

            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 70,
                "L", 9.53, 70,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    let path_b = ["M", 0, 70,
                        "L", 9.53, 70,
                        "L", 9.53, 70+buildoutHeight,
                        "L", -width/2, 70+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post50PVC(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            let width = 25.4;
            //        scale = 2;
            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2,
                "C", 0, 2, -2.5, -2.5, -5, 2,
                "L", -6, 2,
                "L", -7, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 50,
                "L", 9.53, 50,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 2
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    let path_b = ["M", 0, 50,
                        "L", 9.53, 50,
                        "L", 9.53, 50+buildoutHeight,
                        "L", -width/2, 50+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post70PVC(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            let width = 25.4;

            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2,
                "C", 0, 2, -2.5, -2.5, -5, 2,
                "L", -6, 2,
                "L", -7, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 70,
                "L", 9.53, 70,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 2
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    let path_b = ["M", 0, 70,
                        "L", 9.53, 70,
                        "L", 9.53, 70+buildoutHeight,
                        "L", -width/2, 70+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_TPostBL90(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            let width = 25.4;

            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", -width / 2, 6,

                "M", -width / 2, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6 - 26,
                "L", 16, 59.5 + 30.5 - 6 - 26,
                // "L", 16, 30.5,
                "L", 16, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 20.5,
                "L", 16, 27.5 + 30.5 - 20.5,
                "L", 16, 30.5,

                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5, -11,
                "L", -5, -5,
                "L", -5 - 7.7, -5,
                "M", -5 - 7.7, -11,
                "L", -5, -11,

                "M", 16 + 10, 30.5 + 8,
                "L", 16 + 15, 30.5 + 8,
                "L", 16 + 15, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 8
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    let path_b = ["M", 0, 90,
                        "L", 16, 90,
                        "L", 16, 90+buildoutHeight,
                        "L", -width/2, 90+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_PostBL90(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            let width = 25.4;

            let scaleX = scale * (mirrorX == true ? -1 : 1);
            let scaleY = scale * (mirrorY == true ? -1 : 1);

            let path_a = ["M", 0, 2.5,
                "C", 0, 2.5, -2.5, -3, -5, 2.5,
                "L", -5, 6,
                "L", 5 - 25.4, 6,
                "L", 5 - 25.4, 2.5,
                "C", 5 - 25.4, 2.5, 2.5 - 25.4, -3, -25.4, 2.5,

                "L", -25.4, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5,
                "L", 16, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6,
                "L", 0, 59.5 + 30.5 - 6 - 26,
                "L", 16, 59.5 + 30.5 - 6 - 26,
                // "L", 16, 30.5,
                "L", 16, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 7,
                "L", 16 - 5, 27.5 + 30.5 - 20.5,
                "L", 16, 27.5 + 30.5 - 20.5,
                "L", 16, 30.5,

                "L", 0, 30.5,
                "L", 0, 2.5,

                "M", -5 - 15.4, -6,
                "L", -5, -6,
                "L", -5, 0,
                "L", -5 - 15.4, 0,
                "L", -5 - 15.4, -6,

                "M", 16 + 10, 30.5 + 8,
                "L", 16 + 15, 30.5 + 8,
                "L", 16 + 15, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 19.5,
                "L", 16 + 10, 30.5 + 8
            ];

            if (typeof relocationPos !== "undefined") {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                let line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        //NOTE: controlType= "Centre Rod", "Centre Rod Split", "Clearview", "Clearview Split", "Off Centre Rod", "Off
        // Centre Rod Split",

        let paper1;

        let fitWidth = 0;
        let fitHeight = 0;

        function shutterConfig() {
            var availableWidth = fitWidth;
            var availableHeight = fitHeight;

            var myShutter = {
                "buildoutHeight": getPropertyBuiltout(),
                "scale": 1,
                "frameType": getFrameTypeCode(),
                "frameTypeBottom": getFrameTypeCodeBottom(),
                "stileType": getStileCode(),
                "description": getShutterDescription(),
                "width": getPropertyWidth(),
                "height": getPropertyHeight(),
                "layoutCode": getPropertyLayoutCode(),
                "b_positions": getPropertyLayoutcodeExtra('bp'),
                "b_angles": getPropertyLayoutcodeExtra('ba'),
                "c_positions": getPropertyLayoutcodeExtra('c'),
                "t_positions": getPropertyLayoutcodeExtra('t'),
                "g_positions": getPropertyLayoutcodeExtra('g'),
                "louvreHeight": getPropertyBladesize(),
                "totHeight": getPropertyTotHeight(),
                "totPostChecked": getPropertyHorizontaltpost(),
                "midrails": getPropertyMidrailheight(), //set distance from bottom for each
                "midrails2": getPropertyMidrailheight2(), //set distance from bottom for each
                "midrailstotal": getPropertyMidrailtotal(), //set distance from bottom for each
                "midrailsdivider": getPropertyMidrailDivider(), //set distance from bottom for each
                "midrailsdivider2": getPropertyMidrailDivider2(), //set distance from bottom for each
                "midrailscombi": getPropertyMidrailCombiPanel(), //set distance from bottom for each
                "controlType": getPropertyControltype(),
                "splitHeight": getPropertyControlsplitheight(),
                "secondSplitHeight": getPropertyControlsplitheight2(), //use array [from(midrail), to(splitHeight)] eg.
                                                                       // [400, 600]
                "stileWidth": getPropertyStile(),
                "frameLeft": getPropertyFramePosition("left"),
                "frameRight": getPropertyFramePosition("right"),
                "frameTop": getPropertyFramePosition("top"),
                "frameBottom": getPropertyFramePosition("bottom"),
                "frameSize": 30,
                "frameImage": getFrameImageInformation(),
                "postsWidth": 30,
                "railHeight": getRailHeight(),
                "at": {
                    "x": 30,
                    "y": 52
                },
                "fit": {
                    "width": availableWidth,
                    "height": availableHeight
                },
            };

            return myShutter;
        }

        window.onload = function () {


            var currentWidth = Math.floor($("#accordion").width());
            var height = Math.floor(currentWidth * 1.2);

            fitWidth = currentWidth - 130;
            fitHeight = height / 2.0 - 100;
            paper1 = new Raphael(document.getElementById('canvas_container1'), currentWidth, height);

            var shutter = shutterConfig();

            $("#drawingConfig").val(JSON.stringify(shutter, null, 2));

            if ($("#canvas_container1 svg").length > 1) {
                console.log('delete svg length: ' + $("#canvas_container1 svg").length);
                $("#canvas_container1 > svg:first-child").remove();
            }

        };

        //update the shutter drawing only if variables have changed
        function updateShutter() {
            var currentWidth = Math.floor($("#canvas_container1").width());
            var height = Math.floor(currentWidth * 1.2);

            fitWidth = currentWidth - 230;
            fitHeight = height / 2.0 - 100;

            if ($("#canvas_container1 svg").length > 0) {
                $("#canvas_container1 svg").remove();
            }

            var style = getStyleTitle();
            if (style.indexOf("Shaped") >= 0) {
                $("#canvas_container1").html("&nbsp;Drawing not available for shaped shutters. Manual drawing will be issued.");
                $(".print-drawing").hide();
                return;
            } else {
                $("#canvas_container1").html("");
                $(".print-drawing").show();
            }

            paper1 = new Raphael(document.getElementById('canvas_container1'), currentWidth, height);

            var shutter_new_json = shutterConfig();
            var shutter_new_string = JSON.stringify(shutter_new_json, null, 2);

            //always redraw shutter because drawing is done on click
            $("#drawingConfig").val(shutter_new_string);
            drawing(paper1, shutter_new_json, true);

            var shutterScaled = scaleShutterConfig(shutter_new_json);
            $("#drawingConfigScaled").val(JSON.stringify(shutterScaled, null, 2));
        }


        function shutterInit(shutter) {
            if (shutter.midrails == null) {
                shutter.midrails = [];
            }
            if (shutter.midrails2 == null) {
                shutter.midrails2 = [];
            }
        };

        function drawPanels(rPaper, x, y, shutter) {
            var drawSet = [];
            var elem1 = null;
            var elem2 = null;
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();

            var xPanel = x;
            var yPanel = y;
            var totPostWidth = (shutter.totPostChecked ? shutter.frameSize : 1);

            var midRails = shutter.midrails.slice(); // copy array
            midRails.push(shutter.height - shutter.railHeight / 2.0);
            if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                midRails.push(shutter.totHeight);
            }
            midRails.sort(sortNumber);


            var midRails2 = shutter.midrails2.slice(); // copy array
            midRails2.push(shutter.height - shutter.railHeight / 2.0);
            if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                midRails2.push(shutter.totHeight);
            }
            midRails2.sort(sortNumber);
            for (var i = 0, len = layoutCode.length; i < len; i++) {
                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                    elem1 = drawPanelStiles(rPaper, xPanel, yPanel, panelWidth, shutter.stileWidth, shutter.height, layoutCode, i);
                    // var rect = rPaper.rect(xPanel, yPanel, panelWidth, shutter.height);
                    var fromHeight = shutter.railHeight;
                    for (var j = 0; j < midRails.length; j++) {
                        var toHeight = midRails[j] - shutter.railHeight / 2.0;
                        //var toHeight2 = midRails2[j] - shutter.railHeight / 2.0;
                        if (midRails[j] == shutter.totHeight) {
                            toHeight -= shutter.railHeight / 2.0 - totPostWidth / 2.0; //if this midrail is totHeight
                                                                                       // increase toHeight
                        }
                        if (j - 1 >= 0 && midRails[j - 1] == shutter.totHeight) {
                            fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was totHeight
                                                                                   // increase fromHeight
                        }

                        [elem2, firstY, lastY] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter);

                        //[elemm2, firstYm2, lastYm2] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth,
                        // fromHeight, toHeight2, shutter);

                        if (j > 0 && (midRails[j] == shutter.totHeight || shutter.totHeight == 0)) {
                            elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                        }

                        // // Deseneaza midrail2
                        // if (j > 0 && (midRails2[j] == shutter.totHeight || shutter.totHeight == 0)) {
                        //     elem_midrail2 = drawPanelMidrail(rPaper, xPanel, lastYm2, panelWidth, lastYm2,
                        // previousFirstYm2, shutter); }

                        elem3 = false;
                        if (shutter.controlType != '') {
                            elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                        }

                        previousFirstY = firstY;
                        //previousFirstYm2 = firstYm2;
                        fromHeight = toHeight + shutter.railHeight;
                    }
                    xPanel += panelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T' || layoutCode[i] == 'G') {

                    xPanel += shutter.postsWidth;
                }
                drawSet = drawSet.concat(elem1);
                drawSet = drawSet.concat(elem2);
                if (typeof elemMidrail !== 'undefined') {
                    drawSet = drawSet.concat(elemMidrail);
                }
                if (elem3) {
                    drawSet = drawSet.concat(elem3);
                }

            }


            // draw midrail2 Marian Desen Midrail2

            var drawSet = [];
            var elem1 = null;
            var elem2 = null;
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();

            var xPanel = x;
            var yPanel = y;
            var totPostWidth = (shutter.totPostChecked ? shutter.frameSize : 1);

            var midRails2 = shutter.midrails2.slice(); // copy array
            midRails2.push(shutter.height - shutter.railHeight / 2.0);
            if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                //midRails2.push(shutter.totHeight);
            }
            midRails2.sort(sortNumber);
            for (var i = 0, len = layoutCode.length; i < len; i++) {
                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                    elem1 = drawPanelStiles(rPaper, xPanel, yPanel, panelWidth, shutter.stileWidth, shutter.height, layoutCode, i);
                    // var rect = rPaper.rect(xPanel, yPanel, panelWidth, shutter.height);
                    var fromHeight = shutter.railHeight;
                    for (var j = 0; j < midRails2.length; j++) {
                        var toHeight = midRails2[j] - shutter.railHeight / 2.0;
                        if (midRails2[j] == shutter.totHeight) {
                            toHeight -= shutter.railHeight / 2.0 - totPostWidth / 2.0; //if this midrail is totHeight
                                                                                       // increase toHeight
                        }
                        if (j - 1 >= 0 && midRails2[j - 1] == shutter.totHeight) {
                            fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was totHeight
                                                                                   // increase fromHeight
                        }
                        // deseneaza linii
                        [elem2, firstY, lastY] = drawPanelLouvresMidrail2(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter);

                        // Deseneaza midrail2
                        if (j > 0 && (midRails2[j] == shutter.totHeight || shutter.totHeight == 0)) {
                            elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                        }


                        elem3 = false;
                        if (shutter.controlType != '') {
                            elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                        }

                        previousFirstY = firstY;
                        fromHeight = toHeight + shutter.railHeight;
                    }
                    xPanel += panelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T' || layoutCode[i] == 'G') {

                    xPanel += shutter.postsWidth;
                }
                // drawSet = drawSet.concat(elem1);
                // drawSet = drawSet.concat(elem2);
                // if (typeof elemMidrail !== 'undefined') {
                //     drawSet = drawSet.concat(elemMidrail);
                // }
                // if (elem3) {
                //     drawSet = drawSet.concat(elem3);
                // }

            }


            // Draw ToT post
            if (shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                var yToT = shutter.at.y + shutter.height - shutter.totHeight - totPostWidth / 2;
                //            drawSet.push( drawRect(rPaper, shutter.at.x, yToT, shutter.width, totPostWidth, true,
                // true, true, true)  );
                var rect = rPaper.rect(shutter.at.x, yToT, shutter.width, totPostWidth);
                rect.attr("fill", "#fff");
                rect.attr("stroke", "#000");
            }
            // drawXLine(rPaper, x+0, y+shutter.fit.height/2.0, shutter.fit.width, {color:"#FF0000"});
            // drawYLine(rPaper, x+shutter.fit.width/2.0, y+0, shutter.fit.height, {color:"#FF0000"});
            return drawSet;
        };

        function sortNumber(a, b) {
            return a - b;
        };

        function drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter) {
            var drawSet = [];
            var height = toHeight - fromHeight;
            var louvreHeight = shutter.louvreHeight;
            if (louvreHeight == 0)
                louvreHeight = 10;

            var louvres = height / parseFloat(louvreHeight);
            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel + shutter.height - toHeight;
            var firstYLouvre = yLouvre;

            for (var j = 0; j < louvres; j += 1) {
                var xLouvre = xPanel + shutter.stileWidth;
                var widthLouvre = louvreWidth;

                if (shutter.louvreHeight > 0) {
                    louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, louvreHeight);
                    //louvre.attr("stroke-width", "2"); //make the line look stronger
                    drawSet.push(louvre);
                } else {
                    if (j == 0) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, 1);
                    } else if (j + 1 == parseInt(louvres)) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre + louvreHeight, widthLouvre, 1);
                    }

                    //louvre.attr("stroke-width", "2"); //make the line look stronger
                    drawSet.push(louvre);
                }
                yLouvre += louvreHeight;
            }

            return [drawSet, firstYLouvre, yLouvre];
        };


        function drawPanelLouvresMidrail2(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter) {
            var drawSet = [];
            var height = toHeight - fromHeight;
            var louvreHeight = shutter.louvreHeight;
            if (louvreHeight == 0)
                louvreHeight = 10;

            var louvres = height / parseFloat(louvreHeight);
            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel + shutter.height - toHeight;
            var firstYLouvre = yLouvre;

            for (var j = 0; j < louvres; j += 1) {
                var xLouvre = xPanel + shutter.stileWidth;
                var widthLouvre = louvreWidth;

                if (shutter.louvreHeight > 0) {
                    louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, louvreHeight);
                    louvre.attr("stroke-width", "0"); //make the line look stronger
                    drawSet.push(louvre);
                } else {
                    if (j == 0) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, 1);
                    } else if (j + 1 == parseInt(louvres)) {
                        louvre = drawRect(rPaper, xLouvre, yLouvre + louvreHeight, widthLouvre, 1);
                    }

                    louvre.attr("stroke-width", "0"); //make the line look stronger
                    drawSet.push(louvre);
                }
                yLouvre += louvreHeight;
            }

            return [drawSet, firstYLouvre, yLouvre];
        };

        function drawPanelMidrail(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter) {
            var drawSet = [];
            var height = fromHeight - toHeight;

            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel;

            var xLouvre = xPanel + shutter.stileWidth;
            var heightLouvre = toHeight - fromHeight;
            louvre = drawRect(rPaper, xLouvre, yLouvre, louvreWidth, heightLouvre);
            louvre.attr("stroke-width", "2"); //make the line look stronger
            drawSet.push(louvre);

            return drawSet;
        };

        function drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, panelType, shutter) {
            var drawSet = [];
            if (shutter.controlType != null && !(shutter.controlType.substring(0, "Concealed".length) === "Concealed") && !(shutter.controlType.substring(0, "Hidden".length) === "Hidden")) {
                var rodWidth = 3;
                var rodDistanceUp = 5;
                var splitDistance = shutter.louvreHeight;
                var rodHeight = toHeight - fromHeight;
                var louvres = rodHeight / parseFloat(shutter.louvreHeight);
                if (louvres > 0) {
                    var actualHeight = Math.ceil(louvres) * shutter.louvreHeight;
                    var louvreWidth = panelWidth - 2 * shutter.stileWidth;
                    var leftDistance = louvreWidth / 2.0; // "Centre Rod" is at the center
                    if (shutter.controlType.substring(0, "Offset Tilt Rod".length) === "Offset Tilt Rod") { //starts with "Offset Tilt Rod"
                        leftDistance = panelType == 'L' ? louvreWidth / 5.0 : louvreWidth * 4.0 / 5.0;
                    }
                    var xRod = xPanel + shutter.stileWidth + leftDistance;
                    var yRod = yPanel + shutter.height - toHeight;
                    var splitHeight1 = shutter.splitHeight;
                    var splitHeight2 = (shutter.secondSplitHeight != null && shutter.secondSplitHeight.length >= 2) ? shutter.secondSplitHeight[1] : null;
                    var selectedSplitHeight = null;
                    if (!((splitHeight1 == null ||
                            splitHeight1 <= shutter.railHeight ||
                            splitHeight1 >= shutter.height - shutter.railHeight) ||
                        !(splitHeight1 >= fromHeight - rodDistanceUp + splitDistance / 2.0 &&
                            splitHeight1 <= toHeight + rodDistanceUp - splitDistance / 2.0))) {
                        selectedSplitHeight = splitHeight1;
                    }
                    if (selectedSplitHeight == null &&
                        !((splitHeight2 == null ||
                                splitHeight2 <= shutter.railHeight ||
                                splitHeight2 >= shutter.height - shutter.railHeight) ||
                            !(splitHeight2 >= fromHeight - rodDistanceUp + splitDistance / 2.0 &&
                                splitHeight2 <= toHeight + rodDistanceUp - splitDistance / 2.0))) {
                        selectedSplitHeight = splitHeight2;
                    }
                    // draw rod
                    if (selectedSplitHeight == null) { //without split
                        drawSet.push(drawRect(rPaper, xRod, yRod - rodDistanceUp, rodWidth, actualHeight));
                    } else { //with split
                        var relativeSplitHeight = toHeight - selectedSplitHeight;
                        var upperPartHeight = relativeSplitHeight + rodDistanceUp - splitDistance / 2.0;
                        if (upperPartHeight >= splitDistance / 2.0) {
                            drawSet.push(drawRect(rPaper, xRod, yRod - rodDistanceUp, rodWidth, upperPartHeight));
                        }
                        var bottomPartHeight = actualHeight - relativeSplitHeight - rodDistanceUp - splitDistance / 2.0;
                        if (bottomPartHeight >= splitDistance / 2.0) {
                            drawSet.push(drawRect(rPaper, xRod, yRod + relativeSplitHeight + splitDistance / 2.0, rodWidth, bottomPartHeight));
                        }
                    }
                }
            }
            return drawSet;
        }

        function drawPanelStiles(rPaper, x, y, panelWidth, stileWidth, height, layoutCode, index) {
            var showLeft = true;
            var showRight = true;
            if (index > 0 && "BC".indexOf(layoutCode[index - 1]) >= 0) {
                showLeft = false;
            }
            if (index < layoutCode.length - 2 && "BC".indexOf(layoutCode[index + 1]) >= 0) {
                showRight = false;
            }
            var stileLeft = drawRect(rPaper, x, y, stileWidth, height,
                showLeft, false, true, false);
            var stileRight = drawRect(rPaper, x + panelWidth - stileWidth, y, stileWidth, height,
                true, false, showRight, false);
            // stileLeftRight.attr("stroke", "#FF0000");
            return [stileLeft, stileRight];
        };

        function drawShutterFrame(rPaper, x, y, shutter) {
            var rectPanel = drawRect(rPaper, x, y, shutter.width, shutter.height,
                shutter.frameLeft,
                shutter.frameTop,
                shutter.frameRight,
                shutter.frameBottom,
                shutter.frameSize);

            return [rectPanel];
        };

        function drawHeader(rPaper, shutter) {
            if (shutter.description) {
                rPaper.text(shutter.at.x - shutter.frameSize / 2, 10, shutter.description).attr({
                    'text-anchor': 'start',
                    "font-size": 12
                });
            }
        };

        function drawShutter(rPaper, shutter) {
            var x = shutter.at.x;
            var y = shutter.at.y;
            // console.log("Shutter: "+x+", "+y+", "+shutter.width+", "+shutter.height);

            var drawSet = [];
            var elem1 = drawShutterFrame(rPaper, x, y, shutter);
            var elem2 = drawPanels(rPaper, x, y, shutter);

            drawSet = drawSet.concat(elem1);
            drawSet = drawSet.concat(elem2);
            var set = rPaper.set(drawSet);
            // set.transform("s0.5");
            // set.attr("stroke", "#000000");
            // set.attr("fill", "#FF0000");
            // set.attr({"transform": "S0.8"});
            return drawSet;
        };

        function drawing(rPaper, shutterConfig, toScale) {
            shutterInit(shutterConfig);
            var shutter = shutterConfig;
            if (toScale) {
                shutter = scaleShutterConfig(shutterConfig);
            }
            rPaper.clear(); //clear everything
            drawHeader(rPaper, shutter);
            var maxYpos = drawTopView(rPaper, shutter);
            shutter.at.y = maxYpos + 100;
            maxYpos += 500 + shutter.height;
            $("#canvas_container1 svg").attr("height", (maxYpos));
            drawShutter(rPaper, shutter);
            drawRulers(rPaper, shutter, shutterConfig);
            drawFrameImage(rPaper, shutter);
            drawSideView(rPaper, shutter);
        }

        /*/////////////////////////////////////////////////////////////////////// */

        /*//////////////////////////// SCALING ////////////////////////////////// */
        function scaleShutterConfig(config, scale) {
            if (typeof scale == 'undefined') {
                var scale = getShutterScale(config);
            }
            if (config == null) {
                return null
            }
            ;
            // console.log("Object.prototype.toString.call(config) = "+Object.prototype.toString.call(config));
            if (Object.prototype.toString.call(config) == "[object String]") {
                return config;
            }
            if (Object.prototype.toString.call(config) == "[object Boolean]") {
                return config;
            }
            if (Object.prototype.toString.call(config) == "[object Number]") {
                return config * parseFloat(scale);
            }
            if (Object.prototype.toString.call(config) == "[object Array]") {
                var copyArray = [];
                for (var i = 0; i < config.length; i++) {
                    copyArray.push(scaleShutterConfig(config[i], scale));
                }
                return copyArray;
            }
            // console.log("scale = "+scale);

            var scaledShutter = jQuery.extend(true, {}, config); //deep copy object
            for (var propertyName in scaledShutter) {
                // console.log("unscaled:: "+propertyName);
                if (propertyName != "at" && propertyName != "fit" && propertyName != "b_angles" && propertyName != "buildoutHeight") {
                    scaledShutter[propertyName] = scaleShutterConfig(scaledShutter[propertyName], scale);
                    // console.log('scaled:: '+propertyName +" > "+scaledShutter[propertyName]);
                }
            }
            if ((typeof scaledShutter['frameSize'] != 'undefined') && scaledShutter['frameSize'] > 15) {
                scaledShutter['frameSize'] = 15;
            }
            return scaledShutter;
        };

        function getShutterScale(config) {
            var xScale = config.fit.width / parseFloat(config.width);
            var yScale = config.fit.height / parseFloat(config.height);
            return Math.min(xScale, yScale);
        }

        /*//////////////////////////// SCALING ////////////////////////////////// */
        /*/////////////////////////////////////////////////////////////////////// */


        /*/////////////////////////////////////////////////////////////////////// */

        /*////////////////////////// PANEL WIDTH //////////////////////////////// */
        function getPanelWidth(layoutCode, index, shutter) {
            if ("BCTG".indexOf(layoutCode[index]) >= 0) {
                return shutter.postsWidth;
            }

            var positionFrom = getPanelSpacePositionFrom(layoutCode, index, shutter);
            var positionTo = getPanelSpacePositionTo(layoutCode, index, shutter);
            var divideWith = getPanelSpaceDivideWith(layoutCode, index, shutter);
            // console.log("positionFrom: "+positionFrom);
            // console.log("positionTo: "+positionTo);
            // console.log("divideWith: "+divideWith);
            return (positionTo - positionFrom) / parseFloat(divideWith);
            //////////////////////////////////////////////////////
            // posts_count = (layoutCode.match(/B/g)||[]).length +
            //			   (layoutCode.match(/C/g)||[]).length +
            //			   (layoutCode.match(/T/g)||[]).length;
            // lr_count = (layoutCode.match(/L/g)||[]).length +
            //			(layoutCode.match(/R/g)||[]).length;
            // return (shutter.width - posts_count*shutter.postsWidth) / parseFloat(lr_count);
        };

        function getPanelSpacePositionFrom(layoutCode, index, shutter) {
            var bctPostIndex = -1;
            var b_posts_before = 0;
            var c_posts_before = 0;
            var t_posts_before = 0;
            var g_posts_before = 0;
            var lr_count_before = 0;
            for (var i = 0; i < index; i++) {
                if (layoutCode[i] == "B") {
                    b_posts_before++;
                }
                if (layoutCode[i] == "C") {
                    c_posts_before++;
                }
                if (layoutCode[i] == "T") {
                    t_posts_before++;
                }
                if (layoutCode[i] == "G") {
                    g_posts_before++;
                }
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    bctPostIndex = i;
                    lr_count_before = 0;
                } else {
                    lr_count_before++;
                }
            }
            var positionFrom = 0;
            if (bctPostIndex >= 0) {
                if (layoutCode[bctPostIndex] == "B") {
                    positionFrom = shutter.b_positions[b_posts_before - 1];
                }
                if (layoutCode[bctPostIndex] == "C") {
                    positionFrom = shutter.c_positions[c_posts_before - 1];
                }
                if (layoutCode[bctPostIndex] == "T") {
                    positionFrom = shutter.t_positions[t_posts_before - 1];
                }
                if (layoutCode[bctPostIndex] == "G") {
                    positionFrom = shutter.g_positions[g_posts_before - 1];
                }
                positionFrom += shutter.postsWidth / 2.0;
            }
            return positionFrom;
        };

        function getPanelSpacePositionTo(layoutCode, index, shutter) {
            var bctPostIndex = -1;
            var b_posts_after = 0;
            var c_posts_after = 0;
            var t_posts_after = 0;
            var g_posts_after = 0;
            for (var i = layoutCode.length; i > index; i--) {
                if (layoutCode[i] == "B") {
                    b_posts_after++;
                }
                if (layoutCode[i] == "C") {
                    c_posts_after++;
                }
                if (layoutCode[i] == "T") {
                    t_posts_after++;
                }
                if (layoutCode[i] == "G") {
                    g_posts_after++;
                }
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    bctPostIndex = i;
                }
            }
            var positionTo = shutter.width;
            if (bctPostIndex >= 0) {
                if (layoutCode[bctPostIndex] == "B") {
                    positionTo = shutter.b_positions[shutter.b_positions.length - b_posts_after];
                }
                if (layoutCode[bctPostIndex] == "C") {
                    positionTo = shutter.c_positions[shutter.c_positions.length - c_posts_after];
                }
                if (layoutCode[bctPostIndex] == "T") {
                    positionTo = shutter.t_positions[shutter.t_positions.length - t_posts_after];
                }
                if (layoutCode[bctPostIndex] == "G") {
                    positionTo = shutter.g_positions[shutter.g_positions.length - g_posts_after];
                }
                positionTo -= shutter.postsWidth / 2.0;
            }
            return positionTo;
        };

        function getPanelSpaceDivideWith(layoutCode, index, shutter) {
            var lr_count_before = 0;
            for (var i = index - 1; i >= 0; i--) {
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    break;
                }
                lr_count_before++;
            }
            var lr_count_after = 0;
            for (var i = index + 1; i < layoutCode.length; i++) {
                if ("BCTG".indexOf(layoutCode[i]) >= 0) {
                    break;
                }
                lr_count_after++;
            }
            return lr_count_before + 1 + lr_count_after;
        };
        /*////////////////////////// PANEL WIDTH //////////////////////////////// */
        /*/////////////////////////////////////////////////////////////////////// */


        /*/////////////////////////////////////////////////////////////////////// */

        /*///////////////////////// DRAW RECTANGLE ////////////////////////////// */
        function drawRect(rPaper, x, y, width, height,
                          left, top, right, bottom, size, color) {
            var left = typeof left !== 'undefined' ? left : true;
            var top = typeof top !== 'undefined' ? top : true;
            var right = typeof right !== 'undefined' ? right : true;
            var bottom = typeof bottom !== 'undefined' ? bottom : true;
            var size = size || "normal";
            // var color = typeof color !== 'undefined' ? color : "#FFFFFF";
            // var color = "#FF0000";
            // console.log("color: "+color+" size: "+size);

            if (size == "normal") {
                var line = rPaper.path(["M", x, y, //M for move, L for line
                    (left == true ? "L" : "M"), x, y + height,
                    (bottom == true ? "L" : "M"), x + width, y + height,
                    (right == true ? "L" : "M"), x + width, y,
                    (top == true ? "L" : "M"), x, y
                ]);
            } else {
                var t = size; //thickness

                //draw inside line first
                var line = rPaper.path(["M", x, y, //M for move, L for line
                    "L", x, y + height,
                    "L", x + width, y + height,
                    "L", x + width, y,
                    "L", x, y
                ]);
                line.attr("stroke-width", "2"); //make the line look stronger

                //Draw outer line one side at a time
                //draw left side
                var xLeft = (left == true ? x - t : x);
                var path = ["M", xLeft, y - t,
                    (top == true ? "L" : "M"), xLeft, y,
                    (left == true ? "L" : "M"), xLeft, y + height,
                    (bottom == true ? "L" : "M"), xLeft, y + height + t
                ];
                rPaper.path(path).attr("stroke-width", "2");
                //draw right side
                var xRight = (right == true ? x + width + t : x + width);
                var path = ["M", xRight, y - t,
                    (top == true ? "L" : "M"), xRight, y,
                    (right == true ? "L" : "M"), xRight, y + height,
                    (bottom == true ? "L" : "M"), xRight, y + height + t
                ];
                rPaper.path(path).attr("stroke-width", "2");
                //draw top side
                var yTop = (top == true ? y - t : y);
                var path = ["M", x - t, yTop,
                    (left == true ? "L" : "M"), x, yTop,
                    (top == true ? "L" : "M"), x + width, yTop,
                    (right == true ? "L" : "M"), x + width + t, yTop
                ];
                rPaper.path(path).attr("stroke-width", "2");
                //draw bottom side
                var yBottom = (bottom == true ? y + height + t : y + height);
                var path = ["M", x - t, yBottom,
                    (left == true ? "L" : "M"), x, yBottom,
                    (bottom == true ? "L" : "M"), x + width, yBottom,
                    (right == true ? "L" : "M"), x + width + t, yBottom
                ];
                rPaper.path(path).attr("stroke-width", "2");
            }
            return line;
        };
        /*///////////////////////// DRAW RECTANGLE ////////////////////////////// */

        /*/////////////////////////////////////////////////////////////////////// */

        function drawXLine(rPaper, x, y, width, options) {
            var defaultOptions = {
                distance: 0,
                gap: 0,
                color: "#000000",
                strokeWidth: null,
                strokeStyle: "",
                text: null, //text at the level of the line
                textUp: null, //text above the line
                textDown: null, //text below the line
                textAngle: null, //text below the line
                skipLine: false //skip line drawing, draw text only
            };
            options = jQuery.extend(true, defaultOptions, options);

            if (options.skipLine == false) {
                // // console.log("xLine x:"+x+" y:"+y+" width:"+width+" options.color:"+options.color+"
                // options.text:"+options.text);
                var line = rPaper.path(["M", x, y - options.distance,
                    "L", x, y, //M for move, L for line
                    "L", x + width / 2.0 - options.gap / 2.0, y,
                    "M", x + width / 2.0 + options.gap / 2.0, y,
                    "L", x + width, y,
                    "L", x + width, y - options.distance
                ]);
                line.attr("stroke", options.color).attr({
                    'stroke-dasharray': options.strokeStyle
                }); // http://stackoverflow.com/a/13884772/5562559
            }
            if (options.strokeWidth != null) {
                line.attr("stroke-width", options.strokeWidth);
            }
            if (options.text != null) {
                rPaper.text(x + width / 2.0, y, options.text);
            }
            if (options.textUp != null) {
                rPaper.text(x + width / 2.0, y - 8, options.textUp);
            }
            if (options.textDown != null) {
                rPaper.text(x + width / 2.0, y + 8, options.textDown);
            }
            if (options.textAngle != null) {
                rPaper.image('/wp-content/uploads/2021/03/angle.png', x + width - 55 / 2.0, y + 6, 17, 17);
                rPaper.text(x + width + 3 / 2.0, y + 16, options.textAngle).attr("font-size", "14");
            }
        };

        function drawYLine(rPaper, x, y, height, options) {
            var defaultOptions = {
                distance: 0,
                gap: 0,
                color: "#FFFFFF",
                strokeWidth: null,
                strokeStyle: "",
                text: null
            };
            options = jQuery.extend(true, defaultOptions, options);

            // console.log("yLine x:"+x+" y:"+y+" height:"+height+" options.color:"+options.color+"
            // options.text:"+options.text);
            var line = rPaper.path(["M", x - options.distance, y,
                "L", x, y, //M for move, L for line
                "L", x, y + height / 2.0 - options.gap / 2.0,
                "M", x, y + height / 2.0 + options.gap / 2.0,
                "L", x, y + height,
                "L", x - options.distance, y + height
            ]);
            line.attr("stroke", options.color).attr({
                'stroke-dasharray': options.strokeStyle
            }); // http://stackoverflow.com/a/13884772/5562559
            if (options.strokeWidth != null) {
                line.attr("stroke-width", options.strokeWidth);
            }
            if (options.text != null) {
                rPaper.text(x, y + height / 2.0, options.text)
            }
        };

        function drawPanelRulers(rPaper, x, y, shutter, originalShutter, leftFrameLength, rightFrameLength) {
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();

            var xFrom = x;
            var xPanel = x;
            var totalWidth = 0;
            var scaledTotalWidth = 0;
            var property_g = 0;
            var width_g = 0;

            var middleLR = findMiddleLRIndex(layoutCode, shutter);
            var widthMiddleLR = widthBeforeMiddleLR(layoutCode, middleLR, shutter);

            var b_pos = findMiddleBIndex(layoutCode, middleLR);
            var pos = {
                x: x + widthMiddleLR,
                y: y,
                angle: 180
            };

            for (var i = 0, len = layoutCode.length; i < len; i++) {
                var angle = 180;
                var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                    drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                        skipLine: true,
                        textUp: layoutCode[i]
                    });
                    scaledTotalWidth += scaledPanelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                    angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                    angle = (layoutCode[i] == 'C') ? 90 : angle;
                    var currentPostPosition = getPostPosition(layoutCode, i, originalShutter);
                    var panelsSpace = currentPostPosition - totalWidth;
                    drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                        skipLine: true,
                        textUp: layoutCode[i],
                        // textAngle: angle,
                    });
                    drawXLine(rPaper, xFrom - leftFrameLength, y, scaledTotalWidth + leftFrameLength, {
                        color: "#FF0000",
                        distance: 15,
                        textDown: panelsSpace,
                        strokeStyle: "-"
                    });
                    leftFrameLength = 0; //apply only on leftmost ruler
                    xFrom += scaledTotalWidth + scaledPanelWidth;
                    scaledTotalWidth = 0;
                    totalWidth = currentPostPosition;
                } else if (layoutCode[i] == "G") {
                    property_g++;
                    width_g = $('#property_g' + property_g).val();
                    var currentPostPosition = getPostPosition(layoutCode, i, originalShutter);
                    var panelsSpace = currentPostPosition - totalWidth;
                    // afisare G pe desen intre L si R
                    drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                        skipLine: true,
                        textUp: layoutCode[i],
                        // textDown: angle,
                    });
                    drawXLine(rPaper, xFrom - leftFrameLength, y, scaledTotalWidth + leftFrameLength, {
                        color: "#FF0000",
                        distance: 25,
                        textDown: panelsSpace,
                        strokeStyle: "-"
                    });
                    leftFrameLength = 0; //apply only on leftmost ruler
                    xFrom += scaledTotalWidth + scaledPanelWidth;
                    scaledTotalWidth = 0;
                    totalWidth = currentPostPosition;
                }
                xPanel += scaledPanelWidth;
            }
            var panelsSpace = originalShutter.width - totalWidth;
            drawXLine(rPaper, xFrom - leftFrameLength, y, scaledTotalWidth + leftFrameLength + rightFrameLength, {
                color: "#FF0000",
                distance: 15,
                textDown: panelsSpace,
                strokeStyle: "-"
            });
        };

        function getPostPosition(layoutCode, index, shutter) {
            if (index >= layoutCode.length) {
                return shutter.width;
            }
            var b_posts_before = 0;
            var c_posts_before = 0;
            var t_posts_before = 0;
            var g_posts_before = 0;
            for (var i = 0; i <= index; i++) {
                if (layoutCode[i] == "B") {
                    b_posts_before++;
                }
                if (layoutCode[i] == "C") {
                    c_posts_before++;
                }
                if (layoutCode[i] == "T") {
                    t_posts_before++;
                }
                if (layoutCode[i] == "G") {
                    g_posts_before++;
                }
            }
            var postPosition = 0;
            if (layoutCode[index] == "B") {
                postPosition = shutter.b_positions[b_posts_before - 1];
            }
            if (layoutCode[index] == "C") {
                postPosition = shutter.c_positions[c_posts_before - 1];
            }
            if (layoutCode[index] == "T") {
                postPosition = shutter.t_positions[t_posts_before - 1];
            }
            if (layoutCode[index] == "G") {
                postPosition = shutter.g_positions[g_posts_before - 1];
            }
            return postPosition;
        };

        function drawXRulers(rPaper, shutter, originalShutter) {
            var leftFrameLength = (shutter.frameLeft ? shutter.frameSize : 0);
            var rightFrameLength = (shutter.frameRight ? shutter.frameSize : 0);

            // Draw Top ruler
            var xLineYPosition = shutter.at.y - (shutter.frameTop ? shutter.frameSize : 2) - 10;
            var xLineXPosition = shutter.at.x - leftFrameLength;
            var xLineTotalWidth = shutter.width + leftFrameLength + rightFrameLength;
            drawXLine(rPaper, xLineXPosition, xLineYPosition, xLineTotalWidth, {
                color: "#FF0000",
                distance: -10,
                gap: 25,
                text: originalShutter.width
            });

            // Draw bottom rulers
            var panelRulersYPos = shutter.at.y + shutter.height + shutter.frameSize + 15;
            drawPanelRulers(rPaper, shutter.at.x, panelRulersYPos, shutter, originalShutter, leftFrameLength, rightFrameLength);
        };

        function drawYRulers(rPaper, shutter, originalShutter) {
            var topFrameLength = (shutter.frameTop ? shutter.frameSize : 0);
            var bottomFrameLength = (shutter.frameBottom ? shutter.frameSize : 0);

            // Draw total Height ruler
            var yLineXPosition = shutter.at.x + shutter.width + (shutter.frameRight ? shutter.frameSize : 2) + 17;
            var yLineYPosition = shutter.at.y - topFrameLength;
            var yLineTotalHeight = shutter.height + topFrameLength + bottomFrameLength;
            drawYLine(rPaper, yLineXPosition, yLineYPosition, yLineTotalHeight, {
                color: "#FF0000",
                distance: 17,
                gap: 15,
                text: originalShutter.height
            });


            // "totHeight": getPropertyTotHeight() //set distance from bottom for each
            // "midrails2": getPropertyMidrailheight2(), //set distance from bottom for each
            // "midrailsdivider": getPropertyMidrailDivider(), //set distance from bottom for each
            // "midrailsdivider2": getPropertyMidrailDivider2(), //set distance from bottom for each
            // "midrailscombi": getPropertyMidrailCombiPanel(), //set distance from bottom for each
            // Draw first midrail height divider - desen midrail custom marian
            if (shutter.totHeight !== 0) {
                var totHeight2 = 0;
                var totHeightposition2 = 0;
                //if (shutter.totHeight.length > 0 && shutter.totHeight[0] > 0 && shutter.totHeight[0] <
                // shutter.height) {

                yLineXPosition += 25;
                totHeight2 = shutter.totHeight;
                //totHeightposition2 = midRailYposition - totHeight2;
                totHeightposition2 = shutter.at.y + shutter.height - shutter.totHeight;
                console.log('shutter.at.y ' + shutter.at.y);
                console.log('shutter.height ' + shutter.height);
                console.log('getPropertyTotHeight ' + shutter.totHeight);


                drawYLine(rPaper, yLineXPosition, totHeightposition2, totHeight2 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "T-o-T:\n" + getPropertyTotHeight().toString(),
                    strokeStyle: "- "
                });
            }
            //}


            // Draw first midrail ruler
            var midRailPos = 0;
            var midRailYposition = 0;
            if (shutter.midrails.length > 0 && shutter.midrails[0] > 0 && shutter.midrails[0] < shutter.height) {
                yLineXPosition += 25;
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                drawYLine(rPaper, yLineXPosition, midRailYposition, midRailPos + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "midrail:\n" + originalShutter.midrails[0].toString(),
                    strokeStyle: "- "
                });
            }


            // "midrails": getPropertyMidrailheight(), //set distance from bottom for each
            // "midrails2": getPropertyMidrailheight2(), //set distance from bottom for each
            // "midrailsdivider": getPropertyMidrailDivider(), //set distance from bottom for each
            // "midrailsdivider2": getPropertyMidrailDivider2(), //set distance from bottom for each
            // "midrailscombi": getPropertyMidrailCombiPanel(), //set distance from bottom for each
            // Draw first midrail height divider - desen midrail custom marian
            var midRailPos2 = 0;
            var midRailYposition2 = 0;
            if (shutter.midrails2.length > 0 && shutter.midrails2[0] > 0 && shutter.midrails2[0] < shutter.height) {
                /////
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                /////
                yLineXPosition += 30;
                midRailPos2 = shutter.midrails2[0];
                //midRailYposition2 = midRailYposition - midRailPos2;
                midRailYposition2 = shutter.at.y + shutter.height - midRailPos2;

                drawYLine(rPaper, yLineXPosition, midRailYposition2, midRailPos2 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 45,
                    gap: 22,
                    text: "midrail 2:\n" + originalShutter.midrails2[0].toString(),
                    strokeStyle: "- "
                });
            }

            var midRailPos3 = 0;
            var midRailYposition2 = 0;
            if (shutter.midrailsdivider.length > 0 && shutter.midrailsdivider[0] > 0 && shutter.midrailsdivider[0] < shutter.height) {
                /////
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                /////
                yLineXPosition += 30;
                midRailPos3 = shutter.midrailsdivider[0];
                //midRailYposition2 = midRailYposition - midRailPos2;
                midRailYposition2 = shutter.at.y + shutter.height - midRailPos3;

                drawYLine(rPaper, yLineXPosition, midRailYposition2, midRailPos3 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 53,
                    gap: 22,
                    text: "divider:\n" + originalShutter.midrailsdivider[0].toString(),
                    strokeStyle: "- "
                });
            }

            var midRailPos4 = 0;
            var midRailYposition2 = 0;
            if (shutter.midrailsdivider2.length > 0 && shutter.midrailsdivider2[0] > 0 && shutter.midrailsdivider2[0] < shutter.height) {
                /////
                midRailPos = shutter.midrails[0];
                midRailYposition = shutter.at.y + shutter.height - midRailPos;
                /////
                yLineXPosition += 30;
                midRailPos4 = shutter.midrailsdivider2[0];
                //midRailYposition2 = midRailYposition - midRailPos2;
                midRailYposition2 = shutter.at.y + shutter.height - midRailPos4;

                drawYLine(rPaper, yLineXPosition, midRailYposition2, midRailPos4 + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 81,
                    gap: 22,
                    text: "divider 2:\n" + originalShutter.midrailsdivider2[0].toString(),
                    strokeStyle: "- "
                });
            }


            // END Marian custom tot


            // Draw split Height rulers
            if (shutter.splitHeight != null && shutter.splitHeight > 0 && shutter.splitHeight < shutter.height) {
                var splitYposition = shutter.at.y + shutter.height - shutter.splitHeight;
                drawYLine(rPaper, yLineXPosition + 25, splitYposition, shutter.splitHeight + bottomFrameLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "split:\n" + originalShutter.splitHeight.toString(),
                    strokeStyle: "- "
                });
            }
            if (shutter.secondSplitHeight != null && shutter.secondSplitHeight.length >= 2 && shutter.secondSplitHeight[1] > 0 && shutter.secondSplitHeight[1] < shutter.height) {
                var splitYposition = shutter.at.y + shutter.height - shutter.secondSplitHeight[1];
                var splitLength = shutter.secondSplitHeight[1] - shutter.secondSplitHeight[0];
                drawYLine(rPaper, yLineXPosition + 25, splitYposition, splitLength, {
                    color: "#FF0000",
                    distance: 17,
                    gap: 22,
                    text: "split:\n" + originalShutter.secondSplitHeight[1].toString(),
                    strokeStyle: "- "
                });
            }
        };

        function drawRulers(rPaper, shutter, originalShutter) {

            drawXRulers(rPaper, shutter, originalShutter);

            drawYRulers(rPaper, shutter, originalShutter);
        };


        /*///////////////////////////////////////////////////////////////////////////////////////////////////// */
        /*/////////////////////////////////////////// TOP VIEW //////////////////////////////////////////////// */

        /*///////////////////////////////////////////////////////////////////////////////////////////////////// */


        function drawTopView(rPaper, shutterConfig, toScale) {
            shutterInit(shutterConfig);
            var shutter = shutterConfig;
            if (toScale) {
                shutter = scaleShutterConfig(shutterConfig);
            }
            var x = shutter.at.x;
            var y = 100;

            // drawTopRect(rPaper, {x: x+200, y: y, angle:180}, 100.0, 160.0, false, shutter);
            // drawTopRect(rPaper, {x: x+200, y: y, angle:180}, 100.0, 160.0, true, shutter);
            var maxYpos = drawTopPanels(rPaper, x, y, shutter);
            return maxYpos;
        };


        function drawFrameImage(rPaper, shutterConfig, toScale) {
            shutterInit(shutterConfig);
            var shutter = shutterConfig;
            if (toScale) {
                shutter = scaleShutterConfig(shutterConfig);
            }
            var x = shutter.at.x;
            var y = shutter.at.y + shutter.height + 50;
            var imageText = shutter.frameImage.text;
            var imagePath = shutter.frameImage.path;
            var img_src = '';
            $('input[name="property_frametype"]').each(function () {
                if (this.checked) {
                    console.log($(this).val());
                    img_src = $(this).parent().find('img').attr('src');
                }
            });

            //var img_src = $('input[name="property_frametype"]').is(":checked").parent().find('img').attr('src');


            rPaper.image(img_src, x, y, 100, 100);
            rPaper.text(x + 120, y + 50, "Frame:\n" + imageText).attr({
                'text-anchor': 'start'
            });

            var message = rPaper.text(shutter.at.x + shutter.fit.width / 2.0, y + 125, "* Drawing shown is an indication only. Actual product may vary.");

            message.attr("stroke-width", "0.5");
            message.attr("stroke", "#777777");

        };

        function findMiddleLRIndex(layoutCode, shutter) {
            var shutterWidth = shutter.width;
            width = 0;
            for (var i = 0, len = layoutCode.length; i < len; i++) {
                panelWidth = getPanelWidth(layoutCode, i, shutter);
                if ("LR".indexOf(layoutCode[i]) >= 0 && width + panelWidth >= shutterWidth / 2.0) {
                    return i;
                }
                width += panelWidth;
            }
            return 0;
        };

        function findMiddleBIndex(layoutCode, middleLR) {
            var b_index = -1;

            for (var i = 0; i < middleLR; i++) {
                if (layoutCode[i] == 'B') {
                    b_index++;
                }
            }
            return b_index;
        };

        function widthBeforeMiddleLR(layoutCode, middleLR, shutter) {
            var width = 0;
            for (var i = 0; i < middleLR; i++) {
                width += getPanelWidth(layoutCode, i, shutter);
            }
            return width;
        };

        function drawTopPanels(rPaper, x, y, shutter) {
            var scale = shutter.scale;
            //// console.log(scale);
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();
            //// console.log(layoutCode);
            var middleLR = findMiddleLRIndex(layoutCode, shutter);
            var widthMiddleLR = widthBeforeMiddleLR(layoutCode, middleLR, shutter);
            var positions = [];

            var b_pos = findMiddleBIndex(layoutCode, middleLR) + 1;
            var pos = {
                x: x + widthMiddleLR,
                y: y,
                angle: 180
            };
            positions.push(pos);

            //draw right panels
            var angle;
            for (var i = middleLR, len = layoutCode.length; i < len; i++) {

                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                var angle = 180;
                // // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R' || layoutCode[i] == 'G') {
                    var newPos = drawTopPanel_LR(rPaper, pos, panelWidth, angle, false, shutter, scale, i);
                    pos = newPos;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                    angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                    angle = (layoutCode[i] == 'C') ? 90 : angle;
                    var newPos = drawTopPanelPost(rPaper, pos, angle, false, scale, layoutCode[i], shutter.frameType, shutter.buildoutHeight);
                    pos = newPos;
                    b_pos += (layoutCode[i] == 'B') ? 1 : 0;
                    var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                    if (layoutCode[i] != 'T') {
                        drawXLine(rPaper, pos.x, pos.y - 50, scaledPanelWidth, {
                            skipLine: true,
                            // textUp: layoutCode[i],
                            textAngle: angle,
                        });
                    }
                }
                positions.push(pos);
            }
            if (shutter.frameRight) {
                drawFrame_by_type(shutter.frameType, rPaper, pos.x, pos.y, 180 - pos.angle, true, true, scale, shutter.buildoutHeight);
                positions.push(pos);
            }

            var b_pos = findMiddleBIndex(layoutCode, middleLR);
            var pos = {
                x: x + widthMiddleLR,
                y: y,
                angle: 180
            };

            for (var i = middleLR - 1; i >= 0; i--) {

                var panelWidth = getPanelWidth(layoutCode, i, shutter);
                var angle = 180;
                // // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                if (layoutCode[i] == 'L' || layoutCode[i] == 'R' || layoutCode[i] == 'G') {
                    var newPos = drawTopPanel_LR(rPaper, pos, panelWidth, 180, true, shutter, scale, i);

                    pos = newPos;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                    angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                    angle = (layoutCode[i] == 'C') ? 90 : angle;
                    var newPos = drawTopPanelPost(rPaper, pos, angle, true, scale, layoutCode[i], shutter.frameType, shutter.buildoutHeight);
                    pos = newPos;
                    b_pos -= (layoutCode[i] == 'B') ? 1 : 0;
                    var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                    if (layoutCode[i] != 'T') {
                        drawXLine(rPaper, pos.x, pos.y - 50, scaledPanelWidth, {
                            skipLine: true,
                            // textUp: layoutCode[i],
                            textAngle: angle,
                        });
                    }
                }
                positions.push(pos);
            }
            if (shutter.frameLeft) {
                drawFrame_by_type(shutter.frameType, rPaper, pos.x, pos.y, pos.angle, true, false, scale, shutter.buildoutHeight);
                positions.push(pos);
            }

            var maxYPos = Math.max.apply(Math, positions.map(function (o) {
                return o.y;
            }));

            return maxYPos;
        };

        function getPostType(panelType, frameType) {

            if (frameType.indexOf('BL90') != -1 && (panelType == 'T')) {
                return 'TPostBL90';
            }

            if (frameType.indexOf('BL90') != -1 && (panelType == 'B' || panelType == 'C' || panelType == 'G')) {
                return 'PostBL90';
            }

            if (frameType.indexOf('PVC') > 0 && (panelType == 'T')) {
                if (frameType.indexOf('70') > 0) {
                    return 'Post70PVC';
                } else {
                    return 'Post50PVC';
                }
            }
            //else
            if (frameType.indexOf('70') > 0) {
                return 'Post70';
            } else {
                return 'Post50';
            }
        };

        function drawTopPanelPost(rPaper, pos, angle, trueForLeft, scale, panelType, frameType, buildoutHeight) {
            var width = 25.4;
            var postType = getPostType(panelType, frameType);
            if (postType == 'PostBL90') {
                width = width * 2;
            }

            //find the middle and end (down) position of the post
            var middlePos = findNextPos(pos, width / 2.0, 180, trueForLeft, scale);
            var endPos = findNextPos(middlePos, width / 2.0, angle, trueForLeft, scale);
            //draw two halves of the post, in the correct position/angle
            if (trueForLeft) {
                eval("drawHalfPost_" + postType + "(rPaper, pos, pos.angle, true, false, scale, buildoutHeight);");
                eval("drawHalfPost_" + postType + "(rPaper, endPos, endPos.angle, false, false, scale, buildoutHeight);");
            } else {
                eval("drawHalfPost_" + postType + "(rPaper, pos, 180-pos.angle, true, true, scale, buildoutHeight);");
                eval("drawHalfPost_" + postType + "(rPaper, endPos, 180-endPos.angle, false, true, scale, buildoutHeight);");
            }
            //Draw connector line of two halves:
            //find the middle and end (up) position of the post
            //draw the upper line of the post that connects the two half posts
            if (panelType != 'T' || panelType != 'G') {
                var height = (postType.indexOf('70') > 0) ? 70 : 50;
                if (postType.indexOf('70') > 0) {
                    height = 70;
                } else if (postType.indexOf('BL90') > 0) {
                    height = 90;
                } else {
                    height = 50;
                }
                var middlePosUp1 = findNextPos(middlePos, -height, 180 - 90, trueForLeft, scale);
                var middlePosUp2 = findNextPos(middlePos, -height, angle - 90, trueForLeft, scale);
                drawLineBetweenPos(rPaper, middlePosUp1, middlePosUp2);
                //Draw buildout if needed
                /*if (buildoutHeight && buildoutHeight > 0) {
                        height += buildoutHeight;
                        middlePosUp1 = findNextPos(middlePos, -height, 180-90, trueForLeft, scale);
                        middlePosUp2 = findNextPos(middlePos, -height, angle-90, trueForLeft, scale);
                        drawLineBetweenPos(rPaper, middlePosUp1, middlePosUp2);
                    }*/
            }

            return endPos;
        };

        function findNextPos(pos, width, angle, trueForLeft, scale) {
            var x = pos.x;
            var y = pos.y;
            angle = pos.angle - 180 + angle;

            var rotateAngle = angle;
            if (trueForLeft == false) {
                width *= -1;
                rotateAngle *= -1;
            }
            var newPos = {
                x: x - width * scale * Math.cos(toRad(180 - rotateAngle)),
                y: y + width * scale * Math.sin(toRad(180 - rotateAngle)),
                angle: angle
            };
            return newPos;
        }

        function drawLineBetweenPos(rPaper, pos, newPos) {
            var path = [
                "M", pos.x, pos.y,
                "L", newPos.x, newPos.y
            ];
            var line = rPaper.path(path);
            //        line.attr("stroke", "#FF0000");
        }


        function drawTopPanel_LR(rPaper, pos, width, angle, trueForLeft, shutter, scale, layoutIndex) {
            var height = 3;
            var x = pos.x;
            var y = pos.y;
            angle = pos.angle - 180 + angle;

            var rotateAngle = angle;

            if (trueForLeft == false) {
                width *= -1;
                rotateAngle *= -1;
            }

            var endPos = {
                x: x - width * Math.cos(toRad(180 - rotateAngle)),
                y: y + width * Math.sin(toRad(180 - rotateAngle)),
                angle: angle
            };
            // drawXLine(rPaper, endPos.x, endPos.y, shutter.width, {color:"#FF0000"});
            // drawYLine(rPaper, endPos.x, endPos.y, shutter.height, {color:"#FF0000"});
            var actualWidth = Math.abs(width / scale);
            var leftStileFlat = isLeftStileFlat(shutter, layoutIndex);
            var rightStileFlat = isRightStileFlat(shutter, layoutIndex);
            if (trueForLeft) {
                drawPanelStile_by_type(shutter.stileType, rPaper, endPos.x, endPos.y, 180 + rotateAngle, false, false, scale, actualWidth, leftStileFlat, rightStileFlat);
            } else {
                drawPanelStile_by_type(shutter.stileType, rPaper, pos.x, pos.y, 180 + rotateAngle, false, false, scale, actualWidth, leftStileFlat, rightStileFlat);
            }
            return endPos;
        };

        function isLeftStileFlat(shutter, layoutIndex) {
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();
            if (layoutIndex <= 0 || "BCTG".indexOf(layoutCode[layoutIndex - 1]) >= 0) {
                return true;
            }
            return false;
        };

        function isRightStileFlat(shutter, layoutIndex) {
            var layoutCode = shutter.layoutCode.match(/[lrtbcgLRTBCG]+/g).join('').toUpperCase();
            if (layoutIndex >= layoutCode.length - 1 || "BCTG".indexOf(layoutCode[layoutIndex + 1]) >= 0) {
                return true;
            }
            return false;
        };

        function drawTopRect(rPaper, pos, width, angle, trueForLeft) {
            var height = 5;
            var x = pos.x;
            var y = pos.y;
            angle = pos.angle - 180 + angle;

            var rotateAngle = angle;
            if (trueForLeft == false) {
                width *= -1;
                rotateAngle *= -1;
            }

            var line = rPaper.path(["M", x, y,
                "L", x - width, y,
                "L", x - width, y - height,
                "L", x, y - height,
                "L", x, y
            ]);
            line.transform("r" + (180 + rotateAngle) + " " + x + " " + y); //Rotate!

            var newPos = {
                x: x - width * Math.cos(toRad(180 - rotateAngle)),
                y: y + width * Math.sin(toRad(180 - rotateAngle)),
                angle: angle
            };

            // drawXLine(rPaper, newPos.x, newPos.y, shutter.width, {color:"#FF0000"});
            // drawYLine(rPaper, newPos.x, newPos.y, shutter.height, {color:"#FF0000"});
            return newPos;
        };

        function toRad(degrees) {
            return degrees * Math.PI / 180.0;
        };

        function arc(center, radius, startAngle, endAngle) {
            angle = startAngle;
            coords = toCoords(center, radius, angle);
            path = "M " + coords[0] + " " + coords[1];
            while (angle <= endAngle) {
                coords = toCoords(center, radius, angle);
                path += " L " + coords[0] + " " + coords[1];
                angle += 1;
            }
            return path;
        };

        function toCoords(center, radius, angle) {
            var radians = (angle / 180) * Math.PI;
            var x = center[0] + Math.cos(radians) * radius;
            var y = center[1] + Math.sin(radians) * radius;
            return [x, y];
        };

        ///////////////////////////////////////////////////
        ///////////////////// FRAMES //////////////////////
        ///////////////////////////////////////////////////


        // Desen lateral distanta Marian

        function drawSideView(rPaper, shutter) {
            var x_pos = shutter.width + 180 + 100 * shutter.scale;
            var y_pos = shutter.at.y;

            var line = ["M", 0, 0,
                "L", -27 * shutter.scale, 0,
                "L", -27 * shutter.scale, shutter.height,
                "L", 0, shutter.height,
                "L", 0, 0
            ];
            pathRelocation(line, {
                "x": x_pos,
                "y": y_pos
            });
            rPaper.path(line);

            if (shutter.frameTop) {
                drawFrame_by_type(shutter.frameType, rPaper, x_pos, y_pos, 90, false, false, shutter.scale, shutter.buildoutHeight);
            }
            if (shutter.frameBottom) {
                drawFrame_by_type(shutter.frameTypeBottom, rPaper, x_pos, y_pos + shutter.height, 90, true, false, shutter.scale, shutter.buildoutHeight);
            }

        };

        function drawFrame_by_type(type, rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            if (typeof relocationPos !== "undefined") {
                eval("drawFrame_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos);");
            } else {
                eval("drawFrame_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight);");
            }
        };

        function drawPanelStile_by_type(type, rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            if (typeof relocationPos !== "undefined") {
                eval("drawPanelStile_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos);");
            } else {
                eval("drawPanelStile_" + type + "(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos);");
            }
        };

        function drawBuildoutPath(x, y, width, buildoutHeight) {
            var path_b = ["M", x, y,
                "L", x + width, y,
                "L", x + width, y + buildoutHeight,
                "L", x, y + buildoutHeight,
                "L", x, y
            ]
            return path_b;
        };

        function drawFrames_by_type(rPaper, type) {
            rPaper.text(rPaper.width / 2.0, 5, type);
            eval("drawFrame_" + type + "(rPaper, 100, 100, 0, false, false, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 100, 200, 0, false, false, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 100, 300, 0, false, false, 1.5);");

            eval("drawFrame_" + type + "(rPaper, 200, 100, 0, true, false, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 200, 200, 0, true, false, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 200, 300, 0, true, false, 1.5);");

            eval("drawFrame_" + type + "(rPaper, 300, 100, 0, true, true, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 300, 200, 0, true, true, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 300, 300, 0, true, true, 1.5);");

            eval("drawFrame_" + type + "(rPaper, 400, 100, 20, true, true, 1.0);");
            eval("drawFrame_" + type + "(rPaper, 400, 200, 20, true, true, 0.5);");
            eval("drawFrame_" + type + "(rPaper, 400, 300, 20, true, true, 1.5);");
        };

        function drawFrames_custom(path, rPaper) {
            drawFrame_custom(path, rPaper, 100, 100, 0, false, false, 1.0);
            drawFrame_custom(path, rPaper, 100, 200, 0, false, false, 0.5);
            drawFrame_custom(path, rPaper, 100, 300, 0, false, false, 1.5);

            drawFrame_custom(path, rPaper, 200, 100, 0, true, false, 1.0);
            drawFrame_custom(path, rPaper, 200, 200, 0, true, false, 0.5);
            drawFrame_custom(path, rPaper, 200, 300, 0, true, false, 1.5);

            drawFrame_custom(path, rPaper, 300, 100, 0, true, true, 1.0);
            drawFrame_custom(path, rPaper, 300, 200, 0, true, true, 0.5);
            drawFrame_custom(path, rPaper, 300, 300, 0, true, true, 1.5);

            drawFrame_custom(path, rPaper, 400, 100, 20, true, true, 1.0);
            drawFrame_custom(path, rPaper, 400, 200, 20, true, true, 0.5);
            drawFrame_custom(path, rPaper, 400, 300, 20, true, true, 1.5);
        }

        /* *** Helper functions *** */
        function drawCirle(rPaper, x, y, color) {
            var circle = rPaper.circle(x, y, 1);
            circle.attr("stroke", color);
            circle.attr("stroke-width", 2);
        }

        function transform_and_draw_path(rPaper, path, x, y, rotation, scaleX, scaleY, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            if (typeof relocationPos !== "undefined") {
                pathRelocation(path, relocationPos);
            }
            var line = rPaper.path(path);

            if (rotation != 0) {
                line.transform("r" + rotation + " " + x + " " + y);
            }
            line.translate(x, y);
            line.scale(scaleX, scaleY, 0, 0);
            // line.attr("stroke", "#0000FF");
            return line;
        };

        function pathRelocation(path, relocationPos) {
            var pathType = "y"; //can be "M","L","C","Z","x","y"
            var lastPathType = "y";
            for (var i = 0, pathLength = path.length; i < pathLength; i++) {
                pathType = path[i];
                // check if we have a letter path type
                if ("MLCZ".indexOf(path[i]) >= 0) {
                } else { // we have a coordinate x or y
                    //check if it is x or y
                    if ("MLCZ".indexOf(lastPathType) >= 0 || lastPathType == "y") { // it is a x
                        pathType = "x";
                        path[i] += relocationPos.x;
                    } else {
                        pathType = "y";
                        path[i] += relocationPos.y;
                    }
                }
                lastPathType = pathType;
                //            console.log(pathType + " = " + path[i]);
            }
        }

        function removeEmptyFromArray(actual) {
            var newArray = new Array();
            for (var i = 0; i < actual.length; i++) {
                var val = actual[i].trim();
                if (val != null && val != "" && val.indexOf('//') < 0) {
                    newArray.push(actual[i]);
                }
            }
            return newArray;
        }

        //the following functions are used in order to create the json for creating the drawings
        function getPropertyWidth() {
            return parseInt($("#property_width").val());
        }

        function getPropertyHeight() {
            return parseInt($("#property_height").val());
        }

        function getPropertyLayoutCode() {
            return $("#property_layoutcode").val();
        }

        function getPropertyHorizontaltpost() {
            if ($("#property_horizontaltpost").length > 0 && $("#property_horizontaltpost").prop("checked")) {
                return true;
            }
            return false;
        }

        function getPropertyStile() {
            if ($('input[name=property_stile]:checked').attr('data-title')) {
                return parseFloat($('input[name=property_stile]:checked').attr('data-title'));
            } else {
                return 0;
            }
        }

        function getPropertyFramePosition(position) {
            let value = false;
            let data = $("#property_frame" + position).select2('data');
            if (data && data.value) {
                if (data.value.indexOf("Yes") == 0 || data.value.indexOf("Sill") == 0) {
                    value = true;
                }
            }
            return value;
        }

        function getPropertyFramePositionText(position) {
            let value = false;
            let data = $("#property_frame" + position).select2('data');
            if (data && data.value) {
                value = data.value;
            }
            return value;
        }

        function getPropertyLayoutcodeExtra(extra_field) {
            var i = 1;
            var data = [];
            while ($("#property_" + extra_field + i).length > 0) {
                data.push(parseInt($("#property_" + extra_field + i).val()));
                i++;
            }

            return data;
        }

        function getFrameImageInformation() {
            let chosenFrameType = $('input[type="radio"]:checked', '#choose-frametype');
            if (chosenFrameType.size() > 0) {
                return {
                    "text": chosenFrameType.parent().text().trim(),
                    "path": '/' + chosenFrameType.next().attr('src')
                };
            } else {
                return null;
            }
        }

        function getShutterDescription() {
            let room = '';
            if ($("#property_room").val() == '94') {
                room = $("#property_room_other").val();
            } else {
                if ($("#property_room").select2('data') && $("#property_room").select2('data').value != 'undefined') {
                    room = $("#property_room").select2('data').value;
                }
            }
            if (room.length > 0)
                room = room + ' - ';

            return room + getPropertyLayoutCode().toUpperCase();
        }

        function getFrameTypeCode() {
            let value = $('input[name=property_frametype]:checked').val();
            let material_id = $("#property_material").select2('data').id;
            let material_code = getCodeByPropertyValueId(material_id);

            let code = getCodeByPropertyValueId(value);
            if (material_code == 'upvc') {
                code = code + "PVC";
            }
            return code;
        }

        function getFrameTypeCodeBottom() {
            let value = $('input[name=property_frametype]:checked').val();
            let material_id = $("#property_material").select2('data').id;
            let material_code = getCodeByPropertyValueId(material_id);

            let code = getCodeByPropertyValueId(value);
            if (material_code == 'upvc') {
                code = code + "PVC";
            }

            if (getPropertyFramePositionText("bottom") == 'Sill' && code.charAt(0) == 'Z') {
                code = 'L50';
            }

            return code;
        }

        function getStileCode() {
            //value = $("#property_stile").select2('data').id;
            let value = $('input[name=property_stile]:checked').val();
            let material_id = $("#property_material").select2('data').id;
            let material_code = getCodeByPropertyValueId(material_id);

            let code = getCodeByPropertyValueId(value);
            code = code.replace(".", "");
            code = code.replace(" ", "");
            if (material_code == 'upvc') {
                code = code + "PVC";
            }
            return code;
        }


        function getCodeByPropertyValueId(property_value_id) {
            let code = '';
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].id == property_value_id) {
                    code = property_values[i].code;
                    break;
                }
            }
            return code;
        }

        function getRailHeight() {
            if (getPropertyBladesize() == 0) {
                return 60;
            } else {
                return 110;
            }
        }


// Function to check and handle the width constraints
        function checkWidthConstraints(counter, panelWidth, panelType) {
            let currentMaxWidth = counter * panelWidth;
            let maxWidthLimit = MAX_WIDTH_MULTIPLE_PANEL * counter;

            // Adjust max width limit based on the counter or specific conditions
            if (counter === 1) {
                maxWidthLimit = MAX_WIDTH_SINGLE_PANEL;
            } else if (counter === 3) {
                maxWidthLimit = MAX_WIDTH_EXCEPTION * counter; // This condition seems to be exceptionally high, adjust as needed
            }

            // Check against the max width limit
            if (currentMaxWidth > maxWidthLimit) {
                console.log('counter litere: ' + counter);
                let errorText = `<br/>Max width for ${panelType} panel too high.`;
                reportError("property_layoutcode", errorText);
            }

            // Check against the min width
            if (panelWidth < MIN_WIDTH) {
                console.log('counter litere: ' + counter);
                let errorText = `<br/>Min width for ${panelType} panel too low.`;
                reportError("property_layoutcode", errorText);
            }
        }

// Helper function to report errors
        function reportError(elementId, errorText) {
            errors++;
            addError(elementId, errorText);
            modalShowError(errorText);
            alert(errorText); // You might want to remove multiple alerts for a better user experience
        }

        // Function to handle drawing based on panel type
        function handlePanelDrawing(code, panelWidth) {
            let newPos;
            switch (code) {
                case 'L':
                case 'R':
                case 'G':
                    newPos = drawTopPanel_LR(rPaper, pos, panelWidth, 180, false, shutter, scale, i);
                    break;
                case 'B':
                case 'C':
                case 'T':
                    const angle = code === 'B' ? shutter.b_angles[b_pos++] : (code === 'C' ? 90 : 180);
                    newPos = drawTopPanelPost(rPaper, pos, angle, false, scale, code, shutter.frameType, shutter.buildoutHeight);
                    if (code !== 'T') {
                        drawXLine(rPaper, pos.x, pos.y - 50, panelWidth, {skipLine: true, textAngle: angle});
                    }
                    break;
            }
            return newPos;
        }


        // Function to handle drawing based on panel type, considering the reverse iteration
        function handlePanelDrawingReverse(code, panelWidth) {
            let newPos;
            let angle = 180; // Default angle
            switch (code) {
                case 'L':
                case 'R':
                case 'G':
                    // Drawing top panel with reverse flag set to true
                    newPos = drawTopPanel_LR(rPaper, pos, panelWidth, angle, true, shutter, scale, i);
                    break;
                case 'B':
                case 'C':
                case 'T':
                    // Simplified angle determination for B and C, default remains for T
                    angle = code === 'B' ? shutter.b_angles[b_pos--] : (code === 'C' ? 90 : angle);
                    newPos = drawTopPanelPost(rPaper, pos, angle, true, scale, code, shutter.frameType, shutter.buildoutHeight);
                    if (code !== 'T') {
                        drawXLine(rPaper, pos.x, pos.y - 50, panelWidth, {skipLine: true, textAngle: angle});
                    }
                    break;
            }
            return newPos;
        }

        // Function to filter inputs based on field data
        function filterInputs(field_check, field_data) {
            // For each input with the name 'field_check'
            $('input[name=' + field_check + ']').each(function () {
                // Check if the current input's value is in the field_data
                let found = field_data.some(data => $(this).val() == data.id);
                // Fade in or out the label based on if the input was found
                $(this).closest('label').fadeToggle(found);
                // If not found, uncheck the input
                if (!found) $(this).prop('checked', false);
            });
        }

// Function to set default values for frame properties
        function setDefaultValues(value) {
            // Check the edit state of the form
            const formEditState = $('form').attr('edit') === 'no';
            // Default values for left, right, and top properties
            let left = '70', right = '75', top = '80', bottom;

            // Set bottom property based on the selected frame type and form edit state
            if (value == 144 && formEditState) { // Bottom m-track
                bottom = '151';
            } else if (value == 143 && formEditState) { // Track in board
                bottom = '136';
            } else {
                // For other values, set bottom to '85' and adjust top based on style
                console.log('frame_top 10');
                bottom = '85';
                top = style_check.includes('Café') ? '81' : '80';
            }

            // Apply the values using select2 if the form is not in edit state or for specific frame type values
            if (formEditState || value != 144 && value != 143) {
                $("#property_frameleft").select2("val", left);
                $("#property_frameright").select2("val", right);
                $("#property_frametop").select2("val", top);
                $("#property_framebottom").select2("val", bottom);
            }
        }


// Function to add fields based on post type
        function addPostField(postType, postCount) {
            const labelPrefix = {'C': 'C-Post ', 'B': 'Bay Post ', 'G': 'G-Post '};
            const label = labelPrefix[postType] + postCount;
            const id = `property_${postType.toLowerCase()}` + postCount;
            addField(label, id, 1); // Assuming addField is a predefined function
        }

        // Function to get and set values for a specific category (prefix)
        function transferValues(prefixFrom, prefixTo, count) {
            for (let i = 1; i <= count; i++) {
                const value = $(`#${prefixFrom}${i}`).val();
                $(`#${prefixTo}${i}`).val(value);
            }
        }


    });
})(jQuery);

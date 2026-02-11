//get the property code based on id of property eg: property with id 9 = property_fit
jQuery.noConflict();
(function ($) {
    $(function () {


          // ========== START - customize some properties by user =========
          var idCustomer = null;
          var idDealer = null;
          var selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\",\"147\",\"6\"]}";
          // var selectedPropertyValuesEcowoodShutterColours = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\", \"6\"]}";

          idCustomer = jQuery('input[name="customer_id"]').val();
          idDealer = jQuery('input[name="dealer_id"]').val();

          // "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
          if (idCustomer == 274 || idDealer == 274) {
              selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\",\"147\",\"6\"]}"
          }
          console.log('idCustomer ', idCustomer);
          console.log('selectedPropertyValuesEcowood ', selectedPropertyValuesEcowood);
          // ========== END - customize some properties by user =========


        var showBiowood = {};
        console.log('Show Biowood:', my_showBiowood_object.showBiowood);
        if (my_showBiowood_object.showBiowood === 'yes') {
            showBiowood = {
                "id": 6,
                "property_id": 18,
                "value": "Biowood",
                "created_at": "2015-10-19T20:31:50.000+01:00",
                "updated_at": "2015-11-08T19:36:15.000+00:00",
                "code": "paulownia",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            };
        }

        // ========== END - customize some properties by user =========

        var showBasswood = {};
        console.log('Show Basswood:', my_showBiowood_object.showBasswood);
        if (my_showBiowood_object.showBasswood === 'yes') {
            showBasswood = {
                "id": 147,
                "property_id": 18,
                "value": "Basswood",
                "created_at": "2015-10-19T20:32:01.000+01:00",
                "updated_at": "2015-10-19T20:32:01.000+01:00",
                "code": "basswood",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }
        }

        function getPropertyCodeById(id) {
            code = '';
            for (i = 0; i < property_fields.length; i++) {
                if (property_fields[i].id == id) {
                    code = property_fields[i].code;
                }
            }
            return code;
        }

        //get which fields depend on the specific field
        function getRelatedFields(field_id) {
            var fields = [];

            for (i = 0; i < property_values.length; i++) {
                if (property_values[i].all_property_values == 0) {
                    selected_property_values = JSON.parse(property_values[i].selected_property_values);
                    for (j = 0; j < selected_property_values.property_value_ids.length; j++) {
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
            var fields = [];

            for (i = 0; i < property_values.length; i++) {
                if (property_values[i].all_products == 0) {
                    fields.push(property_values[i].property_id);
                }
            }

            return uniqueItems(fields);
        }

        //get the data for a field, based on another field's value
        function getRelatedFieldData(property_id, changed_property_id, value) {
            data = [];
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].property_id == property_id) {
                    if (property_values[i].all_property_values == 0) {
                        selected_property_values = JSON.parse(property_values[i].selected_property_values);
                        if (changed_property_id == selected_property_values.property_field) {
                            for (j = 0; j < selected_property_values.property_value_ids.length; j++) {
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
            data = [];
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].property_id == property_id) {
                    if (property_values[i].all_products == 0) {
                        selected_products = JSON.parse(property_values[i].selected_products);
                        for (j = 0; j < selected_products.product_ids.length; j++) {
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
            data = [];
            var idCustomer = $('input[name="customer_id"]').val();
            // if (idCustomer == 274 || idDealer == 274 || idCustomer == 1) {
            //     for (var i = 0; i < property_values.length; i++) {
            //         if (property_values[i].property_id == property_id && property_values[i].value !== 'Ecowood') {
            //             data.push(property_values[i]);
            //         }
            //     }
            // } else {
                for (var i = 0; i < property_values.length; i++) {
                    if (property_values[i].property_id == property_id) {
                        data.push(property_values[i]);
                    }
                }
            // }
            return data;
        }

        //get field data whose value contains...
        function getFieldDataContains(property_id, contains, field_values) {
            data = [];
            field_values = typeof field_values !== 'undefined' ? field_values : property_values;
            //// console.log(field_values);
            for (var i = 0; i < field_values.length; i++) {
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


        function getPropertyBladesize() {
            if ($("#property_bladesize").select2('data')) {
                // if flat louvre is selected do:
                if ($("#property_bladesize").select2('data').value == '81.2mm Flat Louver') {
                    // if warning flat louvre not exists show worning
                    if ($('.warning-louvre').length < 1) {
                        var html_flat_louvre = '<div class="warning-louvre">WARNING: please select only for painted colors!</div>';
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
            var midrail = [];
            if ($("#property_midrailheight").val().length > 0) {
                midrail[0] = parseFloat($("#property_midrailheight").val());
            } else if ($("#property_solidpanelheight").val() > 0) {
                midrail[0] = parseFloat($("#property_solidpanelheight").val());
            }

            return midrail;
        }

        function getPropertyMidrailheight2() {
            var midrail = [];
            if ($("#property_midrailheight2").val().length > 0) {
                midrail[0] = parseFloat($("#property_midrailheight2").val());
            }

            return midrail;
        }

        function getPropertyMidrailtotal() {
            var midrail = [];
            if ($("#property_midrailheight2").val().length > 0) {
                var mid1 = parseInt($("#property_midrailheight").val());
                var mid2 = parseInt($("#property_midrailheight2").val());
                midrail[0] = parseFloat(mid1 + mid2);
            }

            return midrail;
        }

        function getPropertyMidrailDivider() {
            var midrail = [];
            if ($("#property_midraildivider1").val().length > 0) {
                midrail[0] = parseFloat($("#property_midraildivider1").val());
            }

            return midrail;
        }

        function getPropertyMidrailDivider2() {
            var midrail = [];
            if ($("#property_midraildivider2").val().length > 0) {
                midrail[0] = parseFloat($("#property_midraildivider2").val());
            }

            return midrail;
        }

        function getPropertyMidrailCombiPanel() {
            // var midrail = [];
            // if ($("#property_solidpanelheight").val().length > 0) {
            //     midrail[0] = parseFloat($("#property_solidpanelheight").val());
            // }

            // return midrail;
        }

        function getPropertyControltype() {
            var controltype = '';
            if ($("#property_controltype").select2('data')) {
                controltype = $("#property_controltype").select2('data').value;
            }
            return controltype;
        }

        function getPropertyControlsplitheight() {
            var controlsplitheight = 0;
            if ($("#property_controlsplitheight").val() != '') {
                controlsplitheight = parseInt($("#property_controlsplitheight").val());
            }
            return controlsplitheight;
        }

        function getPropertyBuiltout() {
            var builtout = 0;
            if ($("#property_builtout").val() != '') {
                builtout = parseInt($("#property_builtout").val());
            }
            return builtout;
        }

        function getPropertyControlsplitheight2() {
            var controlsplitheight2 = 0;
            var totheight = getPropertyTotHeight();
            var midrailheight = getPropertyMidrailheight();
            var split_start = 0;

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
            var tot_height = 0;
            if ($("#property_totheight").val() != '') {
                tot_height = parseInt($("#property_totheight").val());
            }
            return tot_height;
        }

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


        //When changing the attachment, check file size
        $('#attachment').on('change', function () {
            field = this;
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
            var cusid_ele = document.querySelectorAll('[name="property_tposttype"]');
            for (var i = 0; i < cusid_ele.length; ++i) {
                var item = cusid_ele[i];
                var itemAttr = $(item).attr('data-title');
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
            var row = item.value;
            if (item.image_file_name !== 'undefined' && item.image_file_name !== null) {
                row = "<span><img src='/uploads/property_values/images/" + item.id + "/thumb_" + item.image_file_name + "' height='44' width='44' /> " + row + "</span>";
            }

            return row;
        }

        var names = [{
            "id": 78,
            "name": "Aluminium Full Height",
            "description": "",
            "part_number": "aluminum-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-07T21:20:44.000+01:00",
            "updated_at": "2017-11-02T14:45:27.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 63,
            "name": "Basswood Bay",
            "description": "",
            "part_number": "basswood-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:06:43.000+00:00",
            "updated_at": "2015-11-08T10:06:43.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 64,
            "name": "Basswood Bay Tier-on-tier",
            "description": "",
            "part_number": "basswood-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:07:49.000+00:00",
            "updated_at": "2015-11-08T10:07:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 67,
            "name": "Basswood Cafe",
            "description": "",
            "part_number": "basswood-cafe",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:10:44.000+00:00",
            "updated_at": "2015-11-08T10:10:44.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 95,
            "name": "Basswood Café Bay",
            "description": "",
            "part_number": "basswood-cafe-bay",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T19:56:11.000+01:00",
            "updated_at": "2017-08-08T19:59:02.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 66,
            "name": "Basswood Fullheight",
            "description": "",
            "part_number": "basswood-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:09:53.000+00:00",
            "updated_at": "2015-11-08T10:09:53.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 92,
            "name": "Basswood Individual Shutter",
            "description": "",
            "part_number": "basswood-individualshutter",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-05-15T09:36:51.000+01:00",
            "updated_at": "2017-05-15T09:36:51.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 62,
            "name": "Basswood Shaped",
            "description": "",
            "part_number": "basswood-shaped",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:05:49.000+00:00",
            "updated_at": "2015-11-08T10:05:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 60,
            "name": "Basswood Special Shape",
            "description": "",
            "part_number": "basswood-specialshape",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:03:41.000+00:00",
            "updated_at": "2015-11-08T10:03:41.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 61,
            "name": "Basswood Tracked",
            "description": "",
            "part_number": "basswood-tracked",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T10:04:49.000+00:00",
            "updated_at": "2015-11-08T10:04:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 3,
            "name": "PAULOWNIA",
            "description": "PAULOWNIA",
            "part_number": "PAULOWNIA",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": "3.0",
            "price2": null,
            "price3": "0.0",
            "created_at": "2014-11-28T09:32:20.000+00:00",
            "updated_at": "2015-10-06T14:24:32.000+01:00",
            "image_file_name": "1A1212.jpg",
            "image_content_type": "image/jpeg",
            "image_file_size": 154522,
            "image_updated_at": "2015-03-27T13:41:14.000+00:00",
            "old_id": null,
            "minimum_quantity": "1.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 44,
            "name": "Paulownia Bay",
            "description": "",
            "part_number": "paulownia-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:32:58.000+01:00",
            "updated_at": "2015-10-26T11:44:20.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 51,
            "name": "Paulownia Bay Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-07T20:02:11.000+00:00",
            "updated_at": "2015-11-07T20:02:11.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 41,
            "name": "Paulownia Cafe",
            "description": "",
            "part_number": "paulownia-cafe",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:29:21.000+01:00",
            "updated_at": "2015-10-26T11:44:39.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 97,
            "name": "Paulownia Café Bay",
            "description": "",
            "part_number": "paulownia-cafe-bay",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T20:04:27.000+01:00",
            "updated_at": "2017-08-08T20:04:27.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 42,
            "name": "Paulownia Fullheight",
            "description": "",
            "part_number": "paulownia-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:30:32.000+01:00",
            "updated_at": "2015-11-15T12:31:35.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "1.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 90,
            "name": "Paulownia Individual Shutter",
            "description": "",
            "part_number": "paulownia-individualshutter",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-05-15T09:32:48.000+01:00",
            "updated_at": "2017-05-15T09:32:48.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 45,
            "name": "Paulownia Shaped",
            "description": "",
            "part_number": "paulownia-specialshape",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:35:17.000+01:00",
            "updated_at": "2018-01-18T12:13:05.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 106,
            "name": "Paulownia Shaped",
            "description": "",
            "part_number": "paulownia-shaped",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2018-01-19T10:57:49.000+00:00",
            "updated_at": "2018-01-19T10:57:49.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 94,
            "name": "Paulownia Solid Flat",
            "description": "",
            "part_number": "paulownia-solid-flat",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-07-13T16:55:16.000+01:00",
            "updated_at": "2017-07-13T16:55:16.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 102,
            "name": "Paulownia Solid Flat Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-solid-flat-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-09-11T01:28:52.000+01:00",
            "updated_at": "2017-09-11T01:28:52.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 93,
            "name": "Paulownia Solid Raised",
            "description": "",
            "part_number": "paulownia-solid-raised",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-07-13T16:55:09.000+01:00",
            "updated_at": "2017-07-13T16:55:09.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 98,
            "name": "Paulownia Solid Raised Café Style",
            "description": "",
            "part_number": "paulownia-solid-raised-cafe-style",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T20:12:55.000+01:00",
            "updated_at": "2017-08-08T20:12:55.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 103,
            "name": "Paulownia Solid Raised Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-solid-raised-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-09-11T01:31:32.000+01:00",
            "updated_at": "2017-09-11T01:31:32.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 43,
            "name": "Paulownia Tier-on-Tier",
            "description": "",
            "part_number": "paulownia-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:31:30.000+01:00",
            "updated_at": "2015-10-26T11:44:27.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 46,
            "name": "Paulownia Tracked",
            "description": "",
            "part_number": "paulownia-tracked",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-10-20T00:36:08.000+01:00",
            "updated_at": "2015-10-26T11:43:58.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 84,
            "name": "Shutter \u0026 Blackout Blind Basswood Bay",
            "description": "",
            "part_number": "blackout-basswood-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:25:48.000+01:00",
            "updated_at": "2016-09-10T11:25:48.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 83,
            "name": "Shutter \u0026 Blackout Blind Basswood Bay Tier-on-Tier",
            "description": "",
            "part_number": "blackout-basswood-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:25:27.000+01:00",
            "updated_at": "2016-09-10T11:25:27.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 74,
            "name": "Shutter \u0026 Blackout Blind Basswood Fullheight",
            "description": "",
            "part_number": "blackout-basswood-fullheight",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T20:58:26.000+01:00",
            "updated_at": "2016-06-12T20:58:26.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 75,
            "name": "Shutter \u0026 Blackout Blind Basswood Tier-on-tier",
            "description": "",
            "part_number": "blackout-basswood-tot",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T21:01:34.000+01:00",
            "updated_at": "2016-09-10T11:08:43.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 82,
            "name": "Shutter \u0026 Blackout Blind Paulownia Bay",
            "description": "",
            "part_number": "blackout-paulownia-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:25:00.000+01:00",
            "updated_at": "2016-09-10T11:25:00.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 81,
            "name": "Shutter \u0026 Blackout Blind Paulownia Bay Tier-on-Tier",
            "description": "",
            "part_number": "blackout-paulownia-bay-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-09-10T11:24:29.000+01:00",
            "updated_at": "2016-09-10T11:24:29.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 76,
            "name": "Shutter \u0026 Blackout Blind Paulownia Fullheight",
            "description": "",
            "part_number": "blackout-paulownia-fullheight",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T21:02:23.000+01:00",
            "updated_at": "2016-06-12T21:02:23.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 77,
            "name": "Shutter \u0026 Blackout Blind Paulownia Tier-on-Tier",
            "description": "",
            "part_number": "blackout-paulownia-tot",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2016-06-12T21:02:48.000+01:00",
            "updated_at": "2016-06-12T21:02:48.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 107,
            "name": "UPVC (UK Made)",
            "description": "",
            "part_number": "upvc-uk-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2018-02-03T01:56:58.000+00:00",
            "updated_at": "2018-02-06T10:26:09.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "1.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 55,
            "name": "UPVC Bay",
            "description": "",
            "part_number": "upvc-bay",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:47:15.000+00:00",
            "updated_at": "2015-11-08T09:47:15.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 59,
            "name": "UPVC Cafe",
            "description": "",
            "part_number": "upvc-cafe",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:51:39.000+00:00",
            "updated_at": "2015-11-08T09:51:39.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 96,
            "name": "UPVC Café Bay",
            "description": "",
            "part_number": "upvc-cafe-bay",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-08-08T19:58:31.000+01:00",
            "updated_at": "2017-08-08T19:58:31.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 58,
            "name": "UPVC Fullheight",
            "description": "",
            "part_number": "upvc-fullheight",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:50:03.000+00:00",
            "updated_at": "2015-11-08T09:50:03.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 91,
            "name": "UPVC Individual Shutter",
            "description": "",
            "part_number": "upvc-individualshutter",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2017-05-15T09:34:16.000+01:00",
            "updated_at": "2017-07-27T18:10:26.000+01:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 52,
            "name": "UPVC Special Shape",
            "description": "",
            "part_number": "upvc-specialshape",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-07T20:03:46.000+00:00",
            "updated_at": "2015-11-07T20:03:46.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 57,
            "name": "UPVC Tier-on-Tier",
            "description": "",
            "part_number": "upvc-tot",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-08T09:49:11.000+00:00",
            "updated_at": "2015-11-08T09:49:11.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 53,
            "name": "UPVC Tracked",
            "description": "",
            "part_number": "upvc-tracked",
            "is_active": true,
            "status_id": null,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": null,
            "price2": null,
            "price3": null,
            "created_at": "2015-11-07T20:04:45.000+00:00",
            "updated_at": "2015-11-07T20:04:45.000+00:00",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }, {
            "id": 4,
            "name": "UPVC Waterproof",
            "description": "UPVC Waterproof",
            "part_number": "UPVC Waterproof",
            "is_active": true,
            "status_id": 1,
            "category_id": 1,
            "promote_category": false,
            "promote_front": false,
            "price1": "5.37",
            "price2": null,
            "price3": "0.0",
            "created_at": "2014-11-28T09:32:40.000+00:00",
            "updated_at": "2015-03-27T13:41:41.000+00:00",
            "image_file_name": "1A1616.jpg",
            "image_content_type": "image/jpeg",
            "image_file_size": 163368,
            "image_updated_at": "2015-03-27T13:41:38.000+00:00",
            "old_id": null,
            "minimum_quantity": "0.0",
            "product_type": "Shutter",
            "vat_class_id": 1
        }];

        var property_values = [{
            "id": 165,
            "property_id": 8,
            "value": "114.3mm",
            "created_at": "2016-01-18T17:15:44.000+00:00",
            "updated_at": "2016-05-18T17:35:18.000+01:00",
            "code": "114.3mm",
            "uplift": "0.0",
            "color": "",
            "all_products": true,
            "selected_products": "{\"product_ids\":null}",
            "all_property_values": false,
            "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\",\"139\",\"147\",\"137\",\"5\",\"187\"]}",
            "graphic": "none",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "is_active": true,
            "property": {
                "id": 8,
                "name": "Louvre Size",
                "created_at": "2015-09-07T20:04:50.000+01:00",
                "updated_at": "2015-09-07T20:04:50.000+01:00",
                "code": "bladesize",
                "sort": null,
                "help_text": "",
                "input_type": "select"
            }
        }, {
            "id": 55,
            "property_id": 8,
            "value": "88.9mm",
            "created_at": "2016-01-06T09:03:52.000+00:00",
            "updated_at": "2016-01-06T09:03:52.000+00:00",
            "code": null,
            "uplift": "0.0",
            "color": null,
            "all_products": true,
            "selected_products": null,
            "all_property_values": true,
            "selected_property_values": null,
            "graphic": "none",
            "image_file_name": null,
            "image_content_type": null,
            "image_file_size": null,
            "image_updated_at": null,
            "is_active": true,
            "property": {
                "id": 8,
                "name": "Louvre Size",
                "created_at": "2015-09-07T20:04:50.000+01:00",
                "updated_at": "2015-09-07T20:04:50.000+01:00",
                "code": "bladesize",
                "sort": null,
                "help_text": "",
                "input_type": "select"
            }
        },
            {
                "id": 52,
                "property_id": 8,
                "value": "81.2mm Flat Louver",
                "created_at": "2015-09-07T20:05:51.000+01:00",
                "updated_at": "2018-03-02T00:29:12.000+00:00",
                "code": "Flat Louver",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 54,
                "property_id": 8,
                "value": "76.2mm",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-03-02T00:29:29.000+00:00",
                "code": "76.2mm",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\",\"292\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 53,
                "property_id": 8,
                "value": "63.5mm",
                "created_at": "2015-09-07T20:05:51.000+01:00",
                "updated_at": "2018-03-02T00:29:12.000+00:00",
                "code": "63.5mm",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 164,
                "property_id": 8,
                "value": "50.8mm",
                "created_at": "2016-01-18T17:15:16.000+00:00",
                "updated_at": "2016-02-22T09:52:32.000+00:00",
                "code": "50.8mm",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 8,
                    "name": "Louvre Size",
                    "created_at": "2015-09-07T20:04:50.000+01:00",
                    "updated_at": "2015-09-07T20:04:50.000+01:00",
                    "code": "bladesize",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 57,
                "property_id": 9,
                "value": "Outside",
                "created_at": "2015-09-07T20:15:08.000+01:00",
                "updated_at": "2017-09-11T01:11:36.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"34\",\"221\",\"229\",\"227\",\"226\",\"222\",\"228\",\"230\",\"231\",\"232\",\"233\",\"31\",\"35\",\"36\",\"37\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 9,
                    "name": "Fit Position",
                    "created_at": "2015-09-07T20:13:07.000+01:00",
                    "updated_at": "2015-11-08T19:32:00.000+00:00",
                    "code": "fit",
                    "sort": null,
                    "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
                    "input_type": "select"
                }
            },
            {
                "id": 56,
                "property_id": 9,
                "value": "Inside",
                "created_at": "2015-09-07T20:14:42.000+01:00",
                "updated_at": "2017-09-11T01:11:46.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"34\",\"221\",\"229\",\"227\",\"226\",\"222\",\"228\",\"230\",\"231\",\"232\",\"233\",\"31\",\"35\",\"37\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 9,
                    "name": "Fit Position",
                    "created_at": "2015-09-07T20:13:07.000+01:00",
                    "updated_at": "2015-11-08T19:32:00.000+00:00",
                    "code": "fit",
                    "sort": null,
                    "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
                    "input_type": "select"
                }
            },
            {
                "id": 58,
                "property_id": 9,
                "value": "Surface",
                "created_at": "2015-09-07T20:16:20.000+01:00",
                "updated_at": "2015-09-07T20:17:28.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"33\",\"34\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 9,
                    "name": "Fit Position",
                    "created_at": "2015-09-07T20:13:07.000+01:00",
                    "updated_at": "2015-11-08T19:32:00.000+00:00",
                    "code": "fit",
                    "sort": null,
                    "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
                    "input_type": "select"
                }
            },
            // {
            //     "id": 88,
            //     "property_id": 14,
            //     "value": "Lightblock",
            //     "created_at": "2015-09-07T21:25:06.000+01:00",
            //     "updated_at": "2016-03-03T15:22:31.000+00:00",
            //     "code": "",
            //     "uplift": "0.0",
            //     "color": "",
            //     "all_products": true,
            //     "selected_products": "{\"product_ids\":null}",
            //     "all_property_values": false,
            //     "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
            //     "graphic": "none",
            //     "image_file_name": null,
            //     "image_content_type": null,
            //     "image_file_size": null,
            //     "image_updated_at": null,
            //     "is_active": true,
            //     "property": {
            //         "id": 14,
            //         "name": "Frame Bottom",
            //         "created_at": "2015-09-07T21:28:16.000+01:00",
            //         "updated_at": "2015-09-07T21:28:16.000+01:00",
            //         "code": "framebottom",
            //         "sort": null,
            //         "help_text": "",
            //         "input_type": "select"
            //     }
            // },
            {
                "id": 151,
                "property_id": 14,
                "value": "M Track",
                "created_at": "2015-12-07T17:30:04.000+00:00",
                "updated_at": "2016-03-03T14:27:47.000+00:00",
                "code": "M",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 86,
                "property_id": 14,
                "value": "No",
                "created_at": "2016-01-06T09:03:53.000+00:00",
                "updated_at": "2017-04-04T08:42:52.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"307\",\"323\",\"62\",\"310\",\"313\",\"318\",\"319\",\"321\",\"216\",\"322\",\"59\",\"300\",\"303\",\"305\",\"320\",\"305\",\"320\",\"173\",\"141\",\"60\",\"306\",\"308\",\"311\",\"150\",\"309\",\"312\",\"63\",\"217\",\"67\",\"326\",\"66\",\"315\",\"316\",\"317\",\"329\",\"64\",\"314\",\"325\",\"328\",\"140\",\"65\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 89,
                "property_id": 14,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:28:17.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 87,
                "property_id": 14,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-12-15T10:10:05.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"67\",\"326\",\"66\",\"315\",\"316\",\"317\",\"329\",\"64\",\"314\",\"325\",\"328\",\"140\",\"65\",\"301\",\"302\",\"303\",\"324\",\"327\",\"352\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 136,
                "property_id": 14,
                "value": "Track in Board",
                "created_at": "2015-10-08T09:34:24.000+01:00",
                "updated_at": "2016-03-03T14:25:59.000+00:00",
                "code": "B",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 85,
                "property_id": 14,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:43:21.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 14,
                    "name": "Frame Bottom",
                    "created_at": "2015-09-07T21:28:16.000+01:00",
                    "updated_at": "2015-09-07T21:28:16.000+01:00",
                    "code": "framebottom",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            // {
            //     "id": 73,
            //     "property_id": 11,
            //     "value": "Lightblock",
            //     "created_at": "2015-09-07T21:25:06.000+01:00",
            //     "updated_at": "2016-03-03T15:22:17.000+00:00",
            //     "code": "",
            //     "uplift": "0.0",
            //     "color": "",
            //     "all_products": true,
            //     "selected_products": "{\"product_ids\":null}",
            //     "all_property_values": false,
            //     "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
            //     "graphic": "none",
            //     "image_file_name": null,
            //     "image_content_type": null,
            //     "image_file_size": null,
            //     "image_updated_at": null,
            //     "is_active": true,
            //     "property": {
            //         "id": 11,
            //         "name": "Frame Left",
            //         "created_at": "2015-09-07T21:03:52.000+01:00",
            //         "updated_at": "2015-09-07T21:03:52.000+01:00",
            //         "code": "frameleft",
            //         "sort": null,
            //         "help_text": "",
            //         "input_type": "select"
            //     }
            // },
            {
                "id": 71,
                "property_id": 11,
                "value": "No",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-04-04T08:42:16.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 74,
                "property_id": 11,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:27:54.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 72,
                "property_id": 11,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-10-23T00:29:56.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"67\",\"326\",\"66\",\"315\",\"316\",\"317\",\"329\",\"64\",\"314\",\"325\",\"328\",\"140\",\"65\",\"301\",\"302\",\"303\",\"324\",\"327\",\"352\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 70,
                "property_id": 11,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:42:11.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\",\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 11,
                    "name": "Frame Left",
                    "created_at": "2015-09-07T21:03:52.000+01:00",
                    "updated_at": "2015-09-07T21:03:52.000+01:00",
                    "code": "frameleft",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            // {
            //     "id": 78,
            //     "property_id": 12,
            //     "value": "Lightblock",
            //     "created_at": "2015-09-07T21:25:06.000+01:00",
            //     "updated_at": "2016-03-03T15:22:21.000+00:00",
            //     "code": "",
            //     "uplift": "0.0",
            //     "color": "",
            //     "all_products": true,
            //     "selected_products": "{\"product_ids\":null}",
            //     "all_property_values": false,
            //     "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
            //     "graphic": "none",
            //     "image_file_name": null,
            //     "image_content_type": null,
            //     "image_file_size": null,
            //     "image_updated_at": null,
            //     "is_active": true,
            //     "property": {
            //         "id": 12,
            //         "name": "Frame Right",
            //         "created_at": "2015-09-07T21:27:18.000+01:00",
            //         "updated_at": "2015-09-07T21:27:18.000+01:00",
            //         "code": "frameright",
            //         "sort": null,
            //         "help_text": "",
            //         "input_type": "select"
            //     }
            // },
            {
                "id": 76,
                "property_id": 12,
                "value": "No",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-04-04T08:43:09.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 79,
                "property_id": 12,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:28:00.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 77,
                "property_id": 12,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-10-23T00:32:49.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"67\",\"326\",\"66\",\"315\",\"316\",\"317\",\"329\",\"64\",\"314\",\"325\",\"328\",\"140\",\"65\",\"301\",\"302\",\"303\",\"324\",\"327\",\"352\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 75,
                "property_id": 12,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:43:14.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\",\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 12,
                    "name": "Frame Right",
                    "created_at": "2015-09-07T21:27:18.000+01:00",
                    "updated_at": "2015-09-07T21:27:18.000+01:00",
                    "code": "frameright",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            // {
            //     "id": 83,
            //     "property_id": 13,
            //     "value": "Lightblock",
            //     "created_at": "2015-09-07T21:25:06.000+01:00",
            //     "updated_at": "2016-03-03T15:22:26.000+00:00",
            //     "code": "",
            //     "uplift": "0.0",
            //     "color": "",
            //     "all_products": true,
            //     "selected_products": "{\"product_ids\":null}",
            //     "all_property_values": false,
            //     "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
            //     "graphic": "none",
            //     "image_file_name": null,
            //     "image_content_type": null,
            //     "image_file_size": null,
            //     "image_updated_at": null,
            //     "is_active": true,
            //     "property": {
            //         "id": 13,
            //         "name": "Frame Top",
            //         "created_at": "2015-09-07T21:27:46.000+01:00",
            //         "updated_at": "2015-09-07T21:27:46.000+01:00",
            //         "code": "frametop",
            //         "sort": null,
            //         "help_text": "",
            //         "input_type": "select"
            //     }
            // },
            {
                "id": 81,
                "property_id": 13,
                "value": "No",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-04-04T08:43:01.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 84,
                "property_id": 13,
                "value": "None",
                "created_at": "2015-09-07T21:25:36.000+01:00",
                "updated_at": "2016-03-03T15:28:04.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 82,
                "property_id": 13,
                "value": "Sill",
                "created_at": "2015-09-07T21:24:07.000+01:00",
                "updated_at": "2015-10-23T00:35:26.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"67\",\"326\",\"66\",\"315\",\"316\",\"317\",\"329\",\"64\",\"314\",\"325\",\"328\",\"140\",\"65\",\"301\",\"302\",\"303\",\"324\",\"327\",\"352\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 135,
                "property_id": 13,
                "value": "Top Track",
                "created_at": "2015-10-08T09:33:59.000+01:00",
                "updated_at": "2016-03-03T15:28:10.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 80,
                "property_id": 13,
                "value": "Yes",
                "created_at": "2015-09-07T21:17:27.000+01:00",
                "updated_at": "2017-04-04T08:42:56.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"143\",\"144\",\"142\",\"307\",\"323\",\"310\",\"313\",\"318\",\"319\",\"321\",\"322\",\"300\",\"303\",\"305\",\"320\",\"306\",\"308\",\"311\",\"309\",\"312\",\"326\",\"315\",\"316\",\"317\",\"329\",\"314\",\"325\",\"328\",\"301\",\"302\",\"324\",\"327\",\"330\",\"331\",\"332\",\"351\",\"352\",\"353\",\"333\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 13,
                    "name": "Frame Top",
                    "created_at": "2015-09-07T21:27:46.000+01:00",
                    "updated_at": "2015-09-07T21:27:46.000+01:00",
                    "code": "frametop",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 144,
                "property_id": 10,
                "value": "Bottom M Track",
                "created_at": "2015-10-22T09:26:58.000+01:00",
                "updated_at": "2016-06-12T21:40:12.000+01:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"37\",\"35\",\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"38\",\"39\",\"40\",\"41\"]}",
                "graphic": "image",
                "image_file_name": "Bottom_M_Track.png",
                "image_content_type": "image/png",
                "image_file_size": 5522,
                "image_updated_at": "2015-10-22T09:33:12.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 142,
                "property_id": 10,
                "value": "4009",
                "created_at": "2015-10-21T19:41:49.000+01:00",
                "updated_at": "2016-06-12T21:35:36.000+01:00",
                "code": "D50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4009.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 4364,
                "image_updated_at": "2015-10-21T19:41:48.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 316,
                "property_id": 10,
                "value": "4013",
                "created_at": "2015-10-21T19:41:49.000+01:00",
                "updated_at": "2016-06-12T21:35:36.000+01:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4013.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 4364,
                "image_updated_at": "2015-10-21T19:41:48.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 317,
                "property_id": 10,
                "value": "4014",
                "created_at": "2015-10-21T19:41:49.000+01:00",
                "updated_at": "2016-06-12T21:35:36.000+01:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4014.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 4364,
                "image_updated_at": "2015-10-21T19:41:48.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 61,
                "property_id": 10,
                "value": "F50",
                "created_at": "2015-09-07T20:37:53.000+01:00",
                "updated_at": "2016-06-12T21:35:38.000+01:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "F50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 4141,
                "image_updated_at": "2015-10-21T19:07:06.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 62,
                "property_id": 10,
                "value": "F70",
                "created_at": "2015-09-07T20:38:37.000+01:00",
                "updated_at": "2016-06-12T21:35:46.000+01:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "F70.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3826,
                "image_updated_at": "2015-10-21T19:07:20.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 216,
                "property_id": 10,
                "value": "F90",
                "created_at": "2016-12-10T15:56:47.000+00:00",
                "updated_at": "2017-12-01T14:22:20.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\"]}",
                "graphic": "image",
                "image_file_name": "F90_square.png",
                "image_content_type": "image/png",
                "image_file_size": 17228,
                "image_updated_at": "2016-12-10T15:56:46.000+00:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 59,
                "property_id": 10,
                "value": "L50",
                "created_at": "2015-09-07T20:35:24.000+01:00",
                "updated_at": "2018-02-03T01:34:19.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"292\"]}",
                "graphic": "image",
                "image_file_name": "L50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3732,
                "image_updated_at": "2015-10-21T19:07:32.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 173,
                "property_id": 10,
                "value": "G",
                "created_at": "2016-07-02T23:01:07.000+01:00",
                "updated_at": "2016-07-05T12:34:38.000+01:00",
                "code": "L50MF",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "l50mf.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 50499,
                "image_updated_at": "2016-07-05T12:34:37.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 141,
                "property_id": 10,
                "value": "H",
                "created_at": "2015-10-21T19:41:28.000+01:00",
                "updated_at": "2016-06-12T21:35:58.000+01:00",
                "code": "L60SF",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "L60-SF.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3667,
                "image_updated_at": "2015-10-21T19:41:27.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 60,
                "property_id": 10,
                "value": "I",
                "created_at": "2015-09-07T20:36:11.000+01:00",
                "updated_at": "2017-11-27T14:03:14.000+00:00",
                "code": "L70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "L70.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3371,
                "image_updated_at": "2015-10-21T19:09:40.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 150,
                "property_id": 10,
                "value": "J",
                "created_at": "2015-11-15T19:16:18.000+00:00",
                "updated_at": "2016-06-14T09:25:30.000+01:00",
                "code": "L90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "L90.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3371,
                "image_updated_at": "2015-11-15T19:16:17.000+00:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 63,
                "property_id": 10,
                "value": "SBS 50",
                "created_at": "2015-09-07T20:39:28.000+01:00",
                "updated_at": "2016-06-12T21:38:52.000+01:00",
                "code": "SBS50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\"]}",
                "graphic": "image",
                "image_file_name": "SBS-50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3759,
                "image_updated_at": "2015-10-21T19:12:11.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 143,
                "property_id": 10,
                "value": "Track in Board",
                "created_at": "2015-10-22T09:26:04.000+01:00",
                "updated_at": "2016-06-12T21:40:08.000+01:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"35\",\"37\",\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"38\",\"39\",\"40\",\"41\"]}",
                "graphic": "image",
                "image_file_name": "Track_in_Board.png",
                "image_content_type": "image/png",
                "image_file_size": 5378,
                "image_updated_at": "2015-10-22T09:32:58.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 67,
                "property_id": 10,
                "value": "Z2BS",
                "created_at": "2015-09-07T20:44:18.000+01:00",
                "updated_at": "2016-06-12T21:39:01.000+01:00",
                "code": "Z2BS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Z2BS.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3975,
                "image_updated_at": "2015-10-21T19:14:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 66,
                "property_id": 10,
                "value": "Z3CS",
                "created_at": "2015-09-07T20:43:15.000+01:00",
                "updated_at": "2016-06-12T21:39:08.000+01:00",
                "code": "Z3CS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":[\"2\",\"3\",\"4\"]}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Z3CS.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 4190,
                "image_updated_at": "2015-10-21T19:15:18.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 64,
                "property_id": 10,
                "value": "Z40",
                "created_at": "2015-09-07T20:40:10.000+01:00",
                "updated_at": "2016-06-12T21:39:13.000+01:00",
                "code": "Z40",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Z40.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3261,
                "image_updated_at": "2015-10-21T19:15:30.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 140,
                "property_id": 10,
                "value": "Z40SF",
                "created_at": "2015-10-21T19:41:09.000+01:00",
                "updated_at": "2016-06-12T21:39:18.000+01:00",
                "code": "Z40SF",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\"]}",
                "graphic": "image",
                "image_file_name": "Z40-SF.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3413,
                "image_updated_at": "2015-10-21T19:41:07.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 65,
                "property_id": 10,
                "value": "Z50",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"292\"]}",
                "graphic": "image",
                "image_file_name": "Z50.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 90,
                "property_id": 15,
                "value": "White",
                "created_at": "2015-09-07T23:00:49.000+01:00",
                "updated_at": "2016-04-01T23:26:53.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"139\",\"147\",\"187\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }

            }, {
                "id": 440,
                "property_id": 15,
                "value": "Black",
                "created_at": "2015-09-07T23:00:49.000+01:00",
                "updated_at": "2016-04-01T23:26:53.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }

            }, {
                "id": 167,
                "property_id": 15,
                "value": "Pearl",
                "created_at": "2016-01-25T10:55:34.000+00:00",
                "updated_at": "2016-04-01T23:26:45.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 174,
                "property_id": 15,
                "value": "Bisque",
                "created_at": "2016-01-25T10:55:34.000+00:00",
                "updated_at": "2016-04-01T23:26:45.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 92,
                "property_id": 15,
                "value": "Antique Brass",
                "created_at": "2015-09-07T23:01:43.000+01:00",
                "updated_at": "2016-04-22T09:10:21.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 91,
                "property_id": 15,
                "value": "Brass",
                "created_at": "2015-09-07T23:01:18.000+01:00",
                "updated_at": "2016-04-01T23:26:30.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 186,
                "property_id": 15,
                "value": "Hidden",
                "created_at": "2016-08-04T12:37:29.000+01:00",
                "updated_at": "2016-08-04T12:37:29.000+01:00",
                "code": "hidden",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"292\",\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 168,
                "property_id": 15,
                "value": "Brushed Nickel",
                "created_at": "2016-02-25T09:16:36.000+00:00",
                "updated_at": "2016-04-01T23:26:38.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 93,
                "property_id": 15,
                "value": "Stainless Steel (+10%)",
                "created_at": "2015-09-07T23:02:36.000+01:00",
                "updated_at": "2016-01-06T06:50:37.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 180,
                "property_id": 15,
                "value": "Security S/S Hinge (+10%)",
                "created_at": "2015-09-07T23:02:36.000+01:00",
                "updated_at": "2016-01-06T06:50:37.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 188,
                "property_id": 18,
                "value": "Ecowood",
                "created_at": "2016-09-05T19:55:06.000+01:00",
                "updated_at": "2016-09-05T19:55:06.000+01:00",
                "code": "ecowood",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true
            },
            {
                "id": 5,
                "property_id": 18,
                "value": "EcowoodPlus",
                "created_at": "2015-10-19T20:31:42.000+01:00",
                "updated_at": "2015-10-19T20:31:42.000+01:00",
                "code": "pvc",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            showBiowood,
            {
                "id": 138,
                "property_id": 18,
                "value": "BiowoodPlus",
                "created_at": "2015-10-19T20:31:50.000+01:00",
                "updated_at": "2015-11-08T19:36:15.000+00:00",
                "code": "paulownia",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            showBasswood,
            {
                "id": 139,
                "property_id": 18,
                "value": "Basswood Plus",
                "created_at": "2015-10-19T20:32:01.000+01:00",
                "updated_at": "2015-10-19T20:32:01.000+01:00",
                "code": "basswood",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 18,
                    "name": "Material",
                    "created_at": "2015-10-19T20:30:37.000+01:00",
                    "updated_at": "2015-10-19T21:47:55.000+01:00",
                    "code": "material",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 187,
                "property_id": 18,
                "value": "Earth",
                "created_at": "2016-09-05T19:55:06.000+01:00",
                "updated_at": "2016-09-05T19:55:06.000+01:00",
                "code": "aluminum",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true
            },
            // {
            //     "id": 137,
            //     "property_id": 18,
            //     "value": "Green",
            //     "created_at": "2015-10-19T20:31:42.000+01:00",
            //     "updated_at": "2015-10-19T20:31:42.000+01:00",
            //     "code": "pvc",
            //     "uplift": "0.0",
            //     "color": "",
            //     "all_products": true,
            //     "selected_products": "{\"product_ids\":null}",
            //     "all_property_values": true,
            //     "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
            //     "graphic": "none",
            //     "image_file_name": null,
            //     "image_content_type": null,
            //     "image_file_size": null,
            //     "image_updated_at": null,
            //     "is_active": true,
            //     "property": {
            //         "id": 18,
            //         "name": "Material",
            //         "created_at": "2015-10-19T20:30:37.000+01:00",
            //         "updated_at": "2015-10-19T21:47:55.000+01:00",
            //         "code": "material",
            //         "sort": 0,
            //         "help_text": "",
            //         "input_type": "select"
            //     }
            // },
            {
                "id": 172,
                "property_id": 15,
                "value": "Sea Mist",
                "created_at": "2016-02-25T09:16:36.000+00:00",
                "updated_at": "2016-04-01T23:26:38.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"139\",\"147\",\"187\",\"188\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 170,
                "property_id": 20,
                "value": "No",
                "created_at": "2016-06-12T20:35:48.000+01:00",
                "updated_at": "2017-11-27T12:05:58.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 20,
                    "name": "Midrail Position Critical",
                    "created_at": "2016-06-12T20:34:21.000+01:00",
                    "updated_at": "2016-06-12T20:34:21.000+01:00",
                    "code": "midrailpositioncritical",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 169,
                "property_id": 20,
                "value": "Yes",
                "created_at": "2016-06-12T20:35:36.000+01:00",
                "updated_at": "2017-11-27T12:05:53.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 20,
                    "name": "Midrail Position Critical",
                    "created_at": "2016-06-12T20:34:21.000+01:00",
                    "updated_at": "2016-06-12T20:34:21.000+01:00",
                    "code": "midrailpositioncritical",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 24,
                "property_id": 6,
                "value": "Bathroom Downstairs",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 25,
                "property_id": 6,
                "value": "Bathroom Upstairs",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 17,
                "property_id": 6,
                "value": "Bed 1",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 18,
                "property_id": 6,
                "value": "Bed 2",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 19,
                "property_id": 6,
                "value": "Bed 3",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 20,
                "property_id": 6,
                "value": "Bed 4",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 21,
                "property_id": 6,
                "value": "Bed 5",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 15,
                "property_id": 6,
                "value": "Dining Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 14,
                "property_id": 6,
                "value": "Family Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 27,
                "property_id": 6,
                "value": "Games Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 22,
                "property_id": 6,
                "value": "Guest Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 28,
                "property_id": 6,
                "value": "Hallway",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 13,
                "property_id": 6,
                "value": "Kitchen",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 12,
                "property_id": 6,
                "value": "Living Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 10,
                "property_id": 6,
                "value": "Lounge",
                "created_at": "2015-09-07T18:47:06.000+01:00",
                "updated_at": "2015-09-07T18:47:06.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 94,
                "property_id": 6,
                "value": "Other",
                "created_at": "2015-09-22T11:54:49.000+01:00",
                "updated_at": "2015-09-22T11:54:49.000+01:00",
                "code": "other",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 23,
                "property_id": 6,
                "value": "Spare Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 26,
                "property_id": 6,
                "value": "Study",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 11,
                "property_id": 6,
                "value": "TV Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 16,
                "property_id": 6,
                "value": "Utility Room",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2016-01-06T09:03:52.000+00:00",
                "code": null,
                "uplift": "0.0",
                "color": null,
                "all_products": true,
                "selected_products": null,
                "all_property_values": true,
                "selected_property_values": null,
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 6,
                    "name": " Room",
                    "created_at": "2015-09-07T18:46:17.000+01:00",
                    "updated_at": "2015-09-22T11:55:40.000+01:00",
                    "code": "room",
                    "sort": 0,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 145,
                "property_id": 17,
                "value": "Other",
                "created_at": "2015-10-23T01:56:04.000+01:00",
                "updated_at": "2018-02-03T01:54:46.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            },
            {
                "id": 411,
                "property_id": 17,
                "value": "Frosted White",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 412,
                "property_id": 17,
                "value": "Neutral White",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 413,
                "property_id": 17,
                "value": "Shell White",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 415,
                "property_id": 17,
                "value": "Vanilla",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": selectedPropertyValuesEcowood,
                //"selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 101,
                "property_id": 17,
                "value": "LS 601 PURE WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2018-02-08T10:49:01.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 103,
                "property_id": 17,
                "value": "LS 003 SILK WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 104,
                "property_id": 17,
                "value": "LS 630 MOST WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 105,
                "property_id": 17,
                "value": "LS 637 HOG BRISTLE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 106,
                "property_id": 17,
                "value": "LS 609 CHAMPAGNE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 107,
                "property_id": 17,
                "value": "LS 105 PEARL",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 108,
                "property_id": 17,
                "value": "LS 618 ALABASTER",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 109,
                "property_id": 17,
                "value": "LS 619 CREAMY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 110,
                "property_id": 17,
                "value": "LS 632 MISTRA",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 111,
                "property_id": 17,
                "value": "LS 910 JET BLACK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 112,
                "property_id": 17,
                "value": "LS 615 CLASSICAL WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 113,
                "property_id": 17,
                "value": "LS 617 New EGGSHELL",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 114,
                "property_id": 17,
                "value": "LS 620 LIME WHITE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 115,
                "property_id": 17,
                "value": "LS 621 SAND",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 116,
                "property_id": 17,
                "value": "LS 622 STONE",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 117,
                "property_id": 17,
                "value": "LS 032 SEA MIST",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 118,
                "property_id": 17,
                "value": "LS 049 STONE GREY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 119,
                "property_id": 17,
                "value": "LS 051 BROWN GREY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 120,
                "property_id": 17,
                "value": "LS 053 CLAY",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 121,
                "property_id": 17,
                "value": "LS 072 MATTINGLEY 267",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 122,
                "property_id": 17,
                "value": "LS 108 RUSTIC GREY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2015-10-01T18:11:03.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 123,
                "property_id": 17,
                "value": "LS 109 WEATHERED TEAK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:35.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 124,
                "property_id": 17,
                "value": "LS 110 CHIQUE WHITE (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:41.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 125,
                "property_id": 17,
                "value": "LS 114 TAUPE (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:19.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 126,
                "property_id": 17,
                "value": "LS 202 GOLDEN OAK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:30.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 127,
                "property_id": 17,
                "value": "LS 204 OAK MANTEL (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:54.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 128,
                "property_id": 17,
                "value": "LS 205 GOLDENROD (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:59.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 129,
                "property_id": 17,
                "value": "LS 211 CHERRY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:06.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 130,
                "property_id": 17,
                "value": "LS 212 DARK TEAK (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:32:13.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 131,
                "property_id": 17,
                "value": "LS 214 COCOA (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:20.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 132,
                "property_id": 17,
                "value": "LS 215 CORDOVAN (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:26.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 133,
                "property_id": 17,
                "value": "LS 219 MAHOGANY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:34.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 134,
                "property_id": 17,
                "value": "LS 220 NEW EBONY (+20%)",
                "created_at": "2015-10-01T18:11:03.000+01:00",
                "updated_at": "2016-04-01T23:31:40.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 166,
                "property_id": 17,
                "value": "LS 221 BLACK WALNUT (+20%)",
                "created_at": "2016-01-20T11:49:59.000+00:00",
                "updated_at": "2016-04-01T23:31:47.000+01:00",
                "code": "",
                "uplift": "10.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 220,
                "property_id": 17,
                "value": "LS 227 RED OAK (+20%)",
                "created_at": "2017-06-26T10:18:55.000+01:00",
                "updated_at": "2017-06-26T10:23:57.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 253,
                "property_id": 17,
                "value": "LS 229 RICH WALNUT (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:03.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 254,
                "property_id": 17,
                "value": "LS 230 OLD TEAK (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:23.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 255,
                "property_id": 17,
                "value": "LS 232 RED MAHOGANY (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:42.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 256,
                "property_id": 17,
                "value": "LS 237 WENGE (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:48.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 257,
                "property_id": 17,
                "value": "LS 862 FRENCH OAK (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:52.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 258,
                "property_id": 17,
                "value": "A100 (WHITE )",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:57.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 259,
                "property_id": 17,
                "value": "A103 (PERAL)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:02.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 260,
                "property_id": 17,
                "value": "A107( BLACK)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:06.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 261,
                "property_id": 17,
                "value": "A108 (SILVER)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 262,
                "property_id": 17,
                "value": "A202 (LIGHT CEDAR) (+10)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:14.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 263,
                "property_id": 17,
                "value": "A203 (GOLDEN OAK ) (+10%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:18.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 264,
                "property_id": 17,
                "value": "P601 PURE WHITE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:22.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 265,
                "property_id": 17,
                "value": "P603 VANILLA BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:31.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 266,
                "property_id": 17,
                "value": "P630 WINTER WHITE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-02T00:06:34.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 267,
                "property_id": 17,
                "value": "P631 STONE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:39.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 268,
                "property_id": 17,
                "value": "P632 MISTRAL BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:43.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 269,
                "property_id": 17,
                "value": "LS615 CLASSICAL WHITE BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:49.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 270,
                "property_id": 17,
                "value": "P910 JET BLACK BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:45:53.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 271,
                "property_id": 17,
                "value": "P817 OLD TEAK BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:10.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 272,
                "property_id": 17,
                "value": "P819 COFFEE BEAN BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:14.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 273,
                "property_id": 17,
                "value": "PS-1 HONEY BRUSHED (+20%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:18.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 274,
                "property_id": 17,
                "value": "A200 (BLACK WALNUT) (+10%)",
                "created_at": null,
                "updated_at": "2018-03-01T23:46:18.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 293,
                "property_id": 17,
                "value": "UK01 White",
                "created_at": "2018-02-08T09:16:40.000+00:00",
                "updated_at": "2018-02-08T09:16:40.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"292\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 32,
                "property_id": 7,
                "value": "Bay Window",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:50:12.000+00:00",
                "code": "bay",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Bay_Window.png",
                "image_content_type": "image/png",
                "image_file_size": 76251,
                "image_updated_at": "2015-10-04T21:08:52.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 146,
                "property_id": 7,
                "value": "Bay Window Tier-on-Tier",
                "created_at": "2015-11-04T14:03:03.000+00:00",
                "updated_at": "2017-11-27T13:50:25.000+00:00",
                "code": "bay-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "thumb_Bay_Window.png",
                "image_content_type": "image/png",
                "image_file_size": 13179,
                "image_updated_at": "2015-11-04T14:03:26.000+00:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 30,
                "property_id": 7,
                "value": "Café Style",
                "created_at": "2015-09-07T19:22:28.000+01:00",
                "updated_at": "2018-02-03T01:30:59.000+00:00",
                "code": "cafe",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Cafe_Style.png",
                "image_content_type": "image/png",
                "image_file_size": 42727,
                "image_updated_at": "2015-10-04T21:09:25.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 225,
                "property_id": 7,
                "value": "Café Style Bay Window",
                "created_at": "2017-08-08T20:08:10.000+01:00",
                "updated_at": "2017-11-27T13:50:38.000+00:00",
                "code": "cafe-bay",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Bay_Window_Cafe_Style.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 103406,
                "image_updated_at": "2017-08-09T12:20:56.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 29,
                "property_id": 7,
                "value": "Full Height",
                "created_at": "2015-09-07T19:20:15.000+01:00",
                "updated_at": "2015-10-19T21:58:32.000+01:00",
                "code": "fullheight",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": true,
                "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
                "graphic": "image",
                "image_file_name": "Full_Height.png",
                "image_content_type": "image/png",
                "image_file_size": 43607,
                "image_updated_at": "2015-10-04T21:09:35.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 36,
                "property_id": 7,
                "value": "Arched Shaped",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:50:52.000+00:00",
                "code": "shaped",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Special-Shape.png",
                "image_content_type": "image/png",
                "image_file_size": 55123,
                "image_updated_at": "2015-10-04T21:10:12.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 33,
                "property_id": 7,
                "value": "Special Shaped",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:50:52.000+00:00",
                "code": "shaped",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Special-Shape.png",
                "image_content_type": "image/png",
                "image_file_size": 55123,
                "image_updated_at": "2015-10-04T21:10:12.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 34,
                "property_id": 7,
                "value": "French Door Cut",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:50:52.000+00:00",
                "code": "french",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "French-Door-Cut.png",
                "image_content_type": "image/png",
                "image_file_size": 55123,
                "image_updated_at": "2015-10-04T21:10:12.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 221,
                "property_id": 7,
                "value": "Solid Flat Panel",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-flat",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Solid_Flat_Panel.png",
                "image_content_type": "image/png",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 227,
                "property_id": 7,
                "value": "Solid Flat\u003cbr/\u003eTier-on-Tier",
                "created_at": "2017-09-11T01:05:00.000+01:00",
                "updated_at": "2017-09-11T01:05:00.000+01:00",
                "code": "solid-flat-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Solid_Panel_Tier_on_Tier_Flat_bcf18cd5d2.png",
                "image_content_type": "image/png",
                "image_file_size": 200757,
                "image_updated_at": "2017-09-11T01:04:58.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 226,
                "property_id": 7,
                "value": "Solid Raised Café Style",
                "created_at": "2017-08-08T20:14:06.000+01:00",
                "updated_at": "2017-08-08T20:14:06.000+01:00",
                "code": "solid-raised-cafe-style",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Solid-Raised-Panel-Cafe-Style.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 123246,
                "image_updated_at": "2017-08-08T20:14:05.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 222,
                "property_id": 7,
                "value": "Solid Raised\u003cbr/\u003ePanel",
                "created_at": "2017-07-13T16:50:20.000+01:00",
                "updated_at": "2017-07-13T16:57:36.000+01:00",
                "code": "solid-raised",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Solid_Raised_Panels.png",
                "image_content_type": "image/png",
                "image_file_size": 49290,
                "image_updated_at": "2017-07-13T16:50:19.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 228,
                "property_id": 7,
                "value": "Solid Raised\u003cbr/\u003eTier-on-Tier",
                "created_at": "2017-09-11T01:05:46.000+01:00",
                "updated_at": "2017-09-11T01:05:46.000+01:00",
                "code": "solid-raised-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Solid_Panel_Tier_on_Tier_Raised_6ced5183ab.png",
                "image_content_type": "image/png",
                "image_file_size": 239469,
                "image_updated_at": "2017-09-11T01:05:44.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 229,
                "property_id": 7,
                "value": "Solid Combi Panel",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-combi",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 230,
                "property_id": 7,
                "value": "Solid Panel Bay Window Full Height",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-flat",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 231,
                "property_id": 7,
                "value": "Solid Panel Bay Window Tier-on-Tier",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-flat-tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 232,
                "property_id": 7,
                "value": "Solid Panel Bay Window Cafe Style",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-raised-cafe-style",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 233,
                "property_id": 7,
                "value": "Solid Combi Panel Bay Window",
                "created_at": "2017-07-13T16:49:43.000+01:00",
                "updated_at": "2017-07-13T16:49:43.000+01:00",
                "code": "solid-combi",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "combi-panel.jpg",
                "image_content_type": "image/jpg",
                "image_file_size": 38799,
                "image_updated_at": "2017-07-13T16:49:42.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 31,
                "property_id": 7,
                "value": "Tier-on-Tier",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2017-11-27T13:53:54.000+00:00",
                "code": "tot",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"187\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Tier-on-Tier.png",
                "image_content_type": "image/png",
                "image_file_size": 55654,
                "image_updated_at": "2015-10-04T21:10:24.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 35,
                "property_id": 7,
                "value": "Tracked",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-02-03T01:31:22.000+00:00",
                "code": "tracked",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Tracked.png",
                "image_content_type": "image/png",
                "image_file_size": 108320,
                "image_updated_at": "2015-10-04T21:10:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 37,
                "property_id": 7,
                "value": "Tracked",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-02-03T01:31:22.000+00:00",
                "code": "tracked",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"147\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Tracked.png",
                "image_content_type": "image/png",
                "image_file_size": 108320,
                "image_updated_at": "2015-10-04T21:10:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 38,
                "property_id": 7,
                "value": "Tracked",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-02-03T01:31:22.000+00:00",
                "code": "tracked",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Tracked.png",
                "image_content_type": "image/png",
                "image_file_size": 108320,
                "image_updated_at": "2015-10-04T21:10:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 39,
                "property_id": 7,
                "value": "Tracked",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-02-03T01:31:22.000+00:00",
                "code": "tracked",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "Tracked.png",
                "image_content_type": "image/png",
                "image_file_size": 108320,
                "image_updated_at": "2015-10-04T21:10:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 40,
                "property_id": 7,
                "value": "Tracked",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-02-03T01:31:22.000+00:00",
                "code": "tracked",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Tracked.png",
                "image_content_type": "image/png",
                "image_file_size": 108320,
                "image_updated_at": "2015-10-04T21:10:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 41,
                "property_id": 7,
                "value": "Tracked",
                "created_at": "2016-01-06T09:03:52.000+00:00",
                "updated_at": "2018-02-03T01:31:22.000+00:00",
                "code": "tracked",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"6\",\"137\",\"5\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "Tracked.png",
                "image_content_type": "image/png",
                "image_file_size": 108320,
                "image_updated_at": "2015-10-04T21:10:38.000+01:00",
                "is_active": true,
                "property": {
                    "id": 7,
                    "name": "Style",
                    "created_at": "2015-09-07T19:18:34.000+01:00",
                    "updated_at": "2015-09-07T19:18:34.000+01:00",
                    "code": "style",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 289,
                "property_id": 10,
                "value": "P4023B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"6\",\"138\",\"188\",\"5\"]}",
                "graphic": "image",
                "image_file_name": "P4023B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 290,
                "property_id": 10,
                "value": "4011B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4011B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 300,
                "property_id": 10,
                "value": "A4001A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "image",
                "image_file_name": "A4001A.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 301,
                "property_id": 10,
                "value": "A4028",
                "created_at": "2015-10-22T09:26:58.000+01:00",
                "updated_at": "2016-06-12T21:40:12.000+01:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "image",
                "image_file_name": "A4028.png",
                "image_content_type": "image/png",
                "image_file_size": 5522,
                "image_updated_at": "2015-10-22T09:33:12.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 305,
                "property_id": 10,
                "value": "4001A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4001A.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 306,
                "property_id": 10,
                "value": "4007A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4007A.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 307,
                "property_id": 10,
                "value": "4008A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4008A.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 308,
                "property_id": 10,
                "value": "4001B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4001B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 309,
                "property_id": 10,
                "value": "4007B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4007B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 310,
                "property_id": 10,
                "value": "4008B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4008B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 311,
                "property_id": 10,
                "value": "4001C",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4001C.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 312,
                "property_id": 10,
                "value": "4007C",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4007C.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 313,
                "property_id": 10,
                "value": "4008C",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "40078C.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 420,
                "property_id": 10,
                "value": "4108C",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "40078C.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 314,
                "property_id": 10,
                "value": "4003",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z40",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4003.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 315,
                "property_id": 10,
                "value": "4004",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z3CS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4004.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 320,
                "property_id": 10,
                "value": "P4001N",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "L50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4001N.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 321,
                "property_id": 10,
                "value": "P4008H",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4008H.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 322,
                "property_id": 10,
                "value": "P4008T",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4008T.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 318,
                "property_id": 10,
                "value": "P4028B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4008T.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 319,
                "property_id": 10,
                "value": "P4008W",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4008T.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 330,
                "property_id": 10,
                "value": "P4008S",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4008S.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 331,
                "property_id": 10,
                "value": "P4007A",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4007A.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 332,
                "property_id": 10,
                "value": "4022B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4022B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 351,
                "property_id": 10,
                "value": "P4022B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4022B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 352,
                "property_id": 10,
                "value": "4024",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\", \"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4008T.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 353,
                "property_id": 10,
                "value": "4008T",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F90",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4008T.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 333,
                "property_id": 10,
                "value": "4028B",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F70",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "image",
                "image_file_name": "4028B.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 323,
                "property_id": 10,
                "value": "P4008K",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "F50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4008K.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 324,
                "property_id": 10,
                "value": "P4073",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4073.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 325,
                "property_id": 10,
                "value": "P4013",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z40",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4013.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 326,
                "property_id": 10,
                "value": "P4023",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z2BS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4023.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 327,
                "property_id": 10,
                "value": "P4033",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z50",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4033.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 328,
                "property_id": 10,
                "value": "P4043",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z40",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4043.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 329,
                "property_id": 10,
                "value": "P4014",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z3CS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4014.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 303,
                "property_id": 10,
                "value": "P4009",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z3CS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4014.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 421,
                "property_id": 10,
                "value": "P4019",
                "created_at": "2015-09-07T20:40:56.000+01:00",
                "updated_at": "2018-02-03T01:34:49.000+00:00",
                "code": "Z3CS",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"188\"]}",
                "graphic": "image",
                "image_file_name": "P4014.png",
                "image_content_type": "image/jpeg",
                "image_file_size": 3286,
                "image_updated_at": "2015-10-21T19:16:03.000+01:00",
                "is_active": true,
                "property": {
                    "id": 10,
                    "name": "Frame Type",
                    "created_at": "2015-09-07T20:26:47.000+01:00",
                    "updated_at": "2015-09-07T20:26:47.000+01:00",
                    "code": "frametype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            },
            {
                "id": 350,
                "property_id": 19,
                "value": "60mm A1002B (Std.beaded stile)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 354,
                "property_id": 19,
                "value": "60mm A1006D (beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 355,
                "property_id": 19,
                "value": "51mm 1001B( plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:04:20.000+00:00",
                    "updated_at": "2016-01-06T07:16:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 356,
                "property_id": 19,
                "value": "51mm 1005B(plain D-mould",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 357,
                "property_id": 19,
                "value": "51mm 1002B(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 358,
                "property_id": 19,
                "value": "51mm 1006B(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 359,
                "property_id": 19,
                "value": "51mm 1004B(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 360,
                "property_id": 19,
                "value": "51mm 1003B(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 361,
                "property_id": 19,
                "value": "35mm 1001A(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 362,
                "property_id": 19,
                "value": "35mm 1005A(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 363,
                "property_id": 19,
                "value": "35mm 1002A(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 364,
                "property_id": 19,
                "value": "35mm 1006A(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 365,
                "property_id": 19,
                "value": "35mm 1004A(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 366,
                "property_id": 19,
                "value": "35mm 1003A(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 38.1",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 370,
                "property_id": 19,
                "value": "51mm T1001K(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 371,
                "property_id": 19,
                "value": "51mm T1005K(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 372,
                "property_id": 19,
                "value": "51mm T1002K(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 373,
                "property_id": 19,
                "value": "51mm T1006K(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 374,
                "property_id": 19,
                "value": "51mm T1004K(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"143\",\"144\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 375,
                "property_id": 19,
                "value": "51mm T1003K(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 376,
                "property_id": 19,
                "value": "41mm T1001M(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 377,
                "property_id": 19,
                "value": "41mm T1005M(plain D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 378,
                "property_id": 19,
                "value": "41mm T1003M(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"139\",\"147\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 380,
                "property_id": 19,
                "value": "51mm PVC-P1001B(plain butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "FS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 381,
                "property_id": 19,
                "value": "51mm PVC-P1005B(plain D-mould)",
                "created_at": "2016-01-06T07:06:54.000+00:00",
                "updated_at": "2016-02-19T10:48:59.000+00:00",
                "code": "DFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 382,
                "property_id": 19,
                "value": "51mm PVC-P1002B(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "BS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 383,
                "property_id": 19,
                "value": "51mm PVC-P1006B(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "DBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 384,
                "property_id": 19,
                "value": "51mm PVC-P1004E(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RBS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 385,
                "property_id": 19,
                "value": "51mm PVC-P1003E(plain rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"188\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 400,
                "property_id": 16,
                "value": "Centre Rod",
                "created_at": "2015-09-26T01:29:12.000+01:00",
                "updated_at": "2017-11-27T12:03:46.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"139\",\"147\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 402,
                "property_id": 16,
                "value": "Offset Rod",
                "created_at": "2015-09-26T01:29:43.000+01:00",
                "updated_at": "2017-11-27T12:04:18.000+00:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 401,
                "property_id": 16,
                "value": "Hidden tilt",
                "created_at": "2017-07-21T02:51:01.000+01:00",
                "updated_at": "2017-11-27T12:04:38.000+00:00",
                "code": "",
                "uplift": "15.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\",\"5\",\"138\",\"6\",\"139\",\"147\",\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 403,
                "property_id": 16,
                "value": "Concealed tilt(+5%)",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 16,
                    "name": "Control Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "controltype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 437,
                "property_id": 22,
                "value": "P7032 - Standard T-Post",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 438,
                "property_id": 22,
                "value": "7001 - Basswood std. T-Post",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }
            , {
                "id": 439,
                "property_id": 22,
                "value": "7201 - T-Post with insert",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 441,
                "property_id": 22,
                "value": "7011 - Large T-Post",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"147\",\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 442,
                "property_id": 22,
                "value": "A7001",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 443,
                "property_id": 22,
                "value": "T7032",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 444,
                "property_id": 22,
                "value": "P7030",
                "created_at": "2015-09-26T01:28:40.000+01:00",
                "updated_at": "2015-09-26T01:28:40.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\",\"137\",\"5\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 22,
                    "name": "T Post Type",
                    "created_at": "2015-09-26T01:25:55.000+01:00",
                    "updated_at": "2015-09-26T01:25:55.000+01:00",
                    "code": "tposttype",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 445,
                "property_id": 19,
                "value": "41mm T1002M(beaded butt)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 446,
                "property_id": 19,
                "value": "41mm T1004M(beaded rebate)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 447,
                "property_id": 19,
                "value": "41mm T1006M(beaded D-mould)",
                "created_at": "2016-01-06T07:05:36.000+00:00",
                "updated_at": "2016-02-19T10:48:43.000+00:00",
                "code": "RFS 50.8",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"6\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 19,
                    "name": "Stile",
                    "created_at": "2016-01-06T07:03:20.000+00:00",
                    "updated_at": "2016-01-06T07:15:59.000+00:00",
                    "code": "stile",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }, {
                "id": 457,
                "property_id": 17,
                "value": "A400 (Surfmist)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:57.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 456,
                "property_id": 17,
                "value": "A500 (Antracit)",
                "created_at": null,
                "updated_at": "2018-03-01T23:44:57.000+00:00",
                "code": "",
                "uplift": "20.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 17,
                    "name": "Shutter Colour",
                    "created_at": "2015-10-01T18:09:08.000+01:00",
                    "updated_at": "2015-10-01T18:09:08.000+01:00",
                    "code": "shuttercolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "input"
                }
            }, {
                "id": 458,
                "property_id": 15,
                "value": "Earth hinge",
                "created_at": "2016-02-25T09:16:36.000+00:00",
                "updated_at": "2016-04-01T23:26:38.000+01:00",
                "code": "",
                "uplift": "0.0",
                "color": "",
                "all_products": true,
                "selected_products": "{\"product_ids\":null}",
                "all_property_values": false,
                "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\"]}",
                "graphic": "none",
                "image_file_name": null,
                "image_content_type": null,
                "image_file_size": null,
                "image_updated_at": null,
                "is_active": true,
                "property": {
                    "id": 15,
                    "name": "Hinge Color",
                    "created_at": "2015-09-07T23:00:03.000+01:00",
                    "updated_at": "2015-09-07T23:01:58.000+01:00",
                    "code": "hingecolour",
                    "sort": null,
                    "help_text": "",
                    "input_type": "select"
                }
            }

        ];
        var property_fields = [{
            "id": 7,
            "name": "Style",
            "created_at": "2015-09-07T19:18:34.000+01:00",
            "updated_at": "2015-09-07T19:18:34.000+01:00",
            "code": "style",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 8,
            "name": "Louvre Size",
            "created_at": "2015-09-07T20:04:50.000+01:00",
            "updated_at": "2015-09-07T20:04:50.000+01:00",
            "code": "bladesize",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 9,
            "name": "Fit Position",
            "created_at": "2015-09-07T20:13:07.000+01:00",
            "updated_at": "2015-11-08T19:32:00.000+00:00",
            "code": "fit",
            "sort": null,
            "help_text": "IMPORTANT! Default should be Outside. If you are unsure please check specification sheet under Downloads. ",
            "input_type": "select"
        }, {
            "id": 10,
            "name": "Frame Type",
            "created_at": "2015-09-07T20:26:47.000+01:00",
            "updated_at": "2015-09-07T20:26:47.000+01:00",
            "code": "frametype",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 11,
            "name": "Frame Left",
            "created_at": "2015-09-07T21:03:52.000+01:00",
            "updated_at": "2015-09-07T21:03:52.000+01:00",
            "code": "frameleft",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 12,
            "name": "Frame Right",
            "created_at": "2015-09-07T21:27:18.000+01:00",
            "updated_at": "2015-09-07T21:27:18.000+01:00",
            "code": "frameright",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 13,
            "name": "Frame Top",
            "created_at": "2015-09-07T21:27:46.000+01:00",
            "updated_at": "2015-09-07T21:27:46.000+01:00",
            "code": "frametop",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 14,
            "name": "Frame Bottom",
            "created_at": "2015-09-07T21:28:16.000+01:00",
            "updated_at": "2015-09-07T21:28:16.000+01:00",
            "code": "framebottom",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 15,
            "name": "Hinge Color",
            "created_at": "2015-09-07T23:00:03.000+01:00",
            "updated_at": "2015-09-07T23:01:58.000+01:00",
            "code": "hingecolour",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 16,
            "name": "Control Type",
            "created_at": "2015-09-26T01:25:55.000+01:00",
            "updated_at": "2015-09-26T01:25:55.000+01:00",
            "code": "controltype",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 17,
            "name": "Shutter Colour",
            "created_at": "2015-10-01T18:09:08.000+01:00",
            "updated_at": "2015-10-01T18:09:08.000+01:00",
            "code": "shuttercolour",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 18,
            "name": "Material",
            "created_at": "2015-10-19T20:30:37.000+01:00",
            "updated_at": "2015-10-19T21:47:55.000+01:00",
            "code": "material",
            "sort": 0,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 19,
            "name": "Stile",
            "created_at": "2016-01-06T07:03:20.000+00:00",
            "updated_at": "2016-01-06T07:15:59.000+00:00",
            "code": "stile",
            "sort": null,
            "help_text": "",
            "input_type": "select"
        }, {
            "id": 20,
            "name": "Midrail Position Critical",
            "created_at": "2016-06-12T20:34:21.000+01:00",
            "updated_at": "2016-06-12T20:34:21.000+01:00",
            "code": "midrailpositioncritical",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 21,
            "name": "Locks",
            "created_at": "2016-09-05T23:48:25.000+01:00",
            "updated_at": "2016-09-05T23:49:53.000+01:00",
            "code": "locks",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }, {
            "id": 22,
            "name": "T Post Type",
            "created_at": "2016-09-05T23:48:25.000+01:00",
            "updated_at": "2016-09-05T23:49:53.000+01:00",
            "code": "tposttype",
            "sort": null,
            "help_text": "",
            "input_type": "input"
        }];

        //// console.log('CUSTOM PROPERTY:  '+JSON.stringify(property_values_original));
        var layout_columns = {
            t: 0,
            c: 0,
            b: 0
        };
        var configuration = {};
        var shutter_type = "Shutter";

        style_check = getStyleTitle();
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

        if ($('input[name="property_frametype"]').length > 0) {
            $('input[name="property_frametype"]:checked').first().closest('label').trigger('click');
        }
        if ($('input[name="property_frametype"][checked]').length > 0) {
            $('input[name="property_frametype"][checked]').first().closest('label').trigger('click');
        }

        $(".property-select").each(function () {
            var id = $(this).attr('id');
            var property_id = getPropertyIdByCode(id);
            values = getAllFieldData(property_id);
            loadItems(id, values);
            //// console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
        });

        calculateTotalSection();
        calculateTotal();
        filterControlType();

        $('select').select2({
            dropdownAutoWidth: true
        });

        $("#property_height").change(function () {
            console.log('change width 2');
            calculateTotalSection();
            calculateTotal();
        });

        $('input[name="property_frametype"], .property-select').click(function () {
            console.log('change width 1');
            calculateTotalSection();
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
                var property_shcolour_check = $("#property_shuttercolour").select2('data').value;
                console.log('step 1');
                var property_stile_check = $("input[name=property_stile]:checked").attr('data-title');
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
            $('#step4-info .alert-info').hide();
            if ($("#property_material").select2('data')) {
                let product_title_check = $("#property_material").select2('data').value;
                if (product_title_check.indexOf('Green') > -1 || product_title_check.indexOf('EcowoodPlus') > -1) {
                    //teo - only stainless steeel hinges for Green
                    if ($('input[name="product_id_updated"]').val() === "") {
                        $("#property_hingecolour").select2("val", '93');
                    }

                    let property_id = getPropertyIdByCode('property_hingecolour');
                    for (i = 0; i < property_values.length; i++) {
                        if (property_values[i].property_id === property_id) {
                            //set default value only if there is not
                            if (property_values[i].value.indexOf('Stainless') > -1 && !$("#property_hingecolour").select2('data')) {
                                $("#property_hingecolour").select2("val", property_values[i].id);
                            }
                        }
                    }
                    if (product_title_check.indexOf('UK') > -1) {

                        if ($('form').attr('edit') === 'no') {
                            console.log('frame_top 6');
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
                        console.log('frame_top 7');
                        $("#property_frameleft").select2("val", '70');
                        $("#property_frameright").select2("val", '75');
                        $("#property_frametop").select2("val", '80');
                        $("#property_framebottom").select2("val", '85');


                        $(".frames #property_frameleft").prop("readonly", false);
                        $(".frames #property_frameright").prop("readonly", false);
                        $(".frames #property_frametop").prop("readonly", false);
                        $(".frames #property_framebottom").prop("readonly", false);
                        $("#add-buildout").parent().show();
                    }
                }
                if (product_title_check.indexOf('Earth') > -1) {  //teo_02-Earth Hidden Only

                    showAluminumOptions(true);
                    $("#property_sparelouvres").val('No');
                    $("#spare-louvres").hide();
                    $("#spare-louvres").closest("div.row").hide();
                    $("#doubleClosingLouvres, #locks").hide();
                    $("#central_lock_position").show();
                    // $("#locks2").show();
                    // $("#property_locks").val('No');
                    //select material
                    // var id_material = $("#property_material").select2('data').id;
                    // if(id_material == 187){
                    //     $("#property_sparelouvres").val('No');
                    //     $("#spare-louvres").hide();
                    //     $("#spare-louvres").closest("div.row").hide();
                    // }
                } else {
                    $("#locks2").hide();
                    $("#central_lock_position").hide();
                    showAluminumOptions(false);
                }
            }
            showMidrailPositionCritical();
        });

        $('.property-select').on('change', function () {
            id = $(this).attr('id');
            field_id = getPropertyIdByCode(id);
            related_fields = getRelatedFields(field_id);

            for (var i = 0; i < related_fields.length; i++) {
                field_data = getRelatedFieldData(related_fields[i], field_id, $(this).val());
                property_code = getPropertyCodeById(related_fields[i]);
                //// console.log("Loading to " + property_code + " data: " + field_data);


                if ($("#" + "property_" + property_code).data('select2')) {
                    loadItems("property_" + property_code, field_data);
                } else {
                    var field_check = "property_" + property_code;
                    $('input[name=' + field_check + ']').each(function () {
                        var found = false;
                        for (var i = 0; i < field_data.length; i++) {
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
            //// console.log("Length: " + $("#choose-frametype label").filter(":visible").length);
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
                product_title_check = $("#property_material").select2('data').value;
                $("#locks").show();
                $("#property_locks").val('No');
                if (product_title_check.indexOf('Biowood') > -1) {
                    // Top&Bottom Locks
                    $("#locks span.lbl").text("Top & Bottom Locks");
                }
                if (product_title_check.indexOf('Biowood Plus') > -1) {
                    $("#locks span.lbl").text("Top & Bottom Locks");
                }
                if (product_title_check.indexOf('Earth') > -1) {  //teo_02-Earth Hidden Only
                    showAluminumOptions(true);
                    $("#property_sparelouvres").val('No');
                    $("#spare-louvres").hide();
                    $("#spare-louvres").closest("div.row").hide();
                    $("#locks").show();
                    // $("#property_locks").val('No');
                    //select material
                    // var id_material = $("#property_material").select2('data').id;
                    // if(id_material == 187){
                    //     $("#property_sparelouvres").val('No');
                    //     $("#spare-louvres").hide();
                    //     $("#spare-louvres").closest("div.row").hide();
                    // }
                } else {
                    $("#locks2").hide();
                    $("#central_lock_position").hide();
                    // $("#locks").hide();
                    showAluminumOptions(false);
                }
            }

            var nr_individuals = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nr_individuals; i++) {
                if ($("#canvas_container" + i + "").filter(":visible").length > 0) {
                    updateShutter(i);
                }
            }
        });

        $(document).on("change", "input", function () {
            try {
                filterControlType();

                var nr_individuals = parseInt($("#property_nr_sections").val());
                for (var i = 1; i <= nr_individuals; i++) {
                    if ($("#canvas_container" + i + "").filter(":visible").length > 0) {
                        updateShutter(i);
                    }
                }
            } catch (err) {
                //// console.log('Shutter data not ready yet' + err);
            }
        });

        $("#choose-style label").click(function (event, trigger_change) {
            if (typeof trigger_change === 'undefined') {
                trigger_change = true;
            }

            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
                if (style_check.indexOf('Bay Window') > -1) {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeIn();

                } else {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeOut();
                }
                //set default value only if new record or empty value
                if (style_check.indexOf('Bay ') > -1) {
                    // $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeIn();
                    //$("#property_fit").parent().parent().parent().show();
                } else {
                    $("#property_fit").select2("val", '57');
                    $(".property_fit").fadeOut();
                }


                //if style is 'Shaped & French Cut Out', allow to upload a file for the shape
                if (style_check.indexOf('Shaped') > -1 || style_check.indexOf('French') > -1) {
                    if (style_check.indexOf('Arched') > -1) {
                        $('#shape-section-draw .images .shapes').hide();
                        $('#shape-section-draw .images .arched').show();
                    } else if (style_check.indexOf('Special') > -1) {
                        $('#shape-section-draw .images .shapes').hide();
                        $('#shape-section-draw .images .special').show();
                    } else if (style_check.indexOf('French') > -1) {
                        $('#shape-section-draw .images .shapes').hide();
                        $('#shape-section-draw .images .french').show();
                    }

                    // console.log('123');
                    $("#shape-section").fadeIn();
                    $("#shape-section").addClass("required");
                    $('#shape-section-draw').addClass("required");
                    $('#shape-section-draw').show();
                } else {
                    $('#shape-section-draw').hide();
                }

                checkStyleTier();

                value = $('input[name=property_style]:checked').val();
                field_id = getPropertyIdByCode('property_style');
                style_related_fields = getRelatedFields(field_id);
                //// console.log(style_related_fields);
                for (var i = 0; i < style_related_fields.length; i++) {
                    field_data = getRelatedFieldData(style_related_fields[i], field_id, value);
                    property_code = getPropertyCodeById(style_related_fields[i]);
                    //// console.log("Loading to " + property_code + " data: " + field_data);
                    if ($("#" + "property_" + property_code).data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        // filter only when track is selected
                        // Show tracked frame type
                        if (value == 35 || value == 37 || value == 38 || value == 39 || value == 40 || value == 41) {
                            var field_check = "property_" + property_code;
                            //// console.log("filtering " + field_check + ' from style');
                            $('input[name=' + field_check + ']').each(function () {
                                var found = false;
                                for (var i = 0; i < field_data.length; i++) {
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
                    if ($("#property_material").val() == '138' || $("#property_material").val() == '188' || $("#property_material").val() == '139' || $("#property_material").val() == '147') {
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
                    // console.log('frame_top 1');
                    $("#property_frametop").select2("val", '81');
                    $("#property_tposttype").select2("val", '439');
                } else {
                    // console.log('frame_top 2');
                    $("#property_frametop").select2("val", '80');
                }


                setProductByMaterialAndStyle();

                hideStileByHeight(1800);

            }
        });

        function hideStileByHeight(minHeght) {
            // if height is more then 2000 then hide stile where data-title contain string '41mm'

            if ($("#property_totheight").val() > minHeght) {
                $('input[name="property_stile"]').each(function () {
                    if ($(this).data('title').indexOf('41mm') > -1) {
                        $(this).parent().hide();
                    }
                });
            } else if ($("#property_height").val() > minHeght) {
                $('input[name="property_stile"]').each(function () {
                    if ($(this).data('title').indexOf('41mm') > -1) {
                        $(this).parent().hide();
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
            if ($("#property_material").val() == '139' || $("#property_material").val() == '147') {
                $("#property_tposttype").select2("val", '438');
            }
            // select frame checked
            $('input[name="property_frametype"]:checked').trigger('click');
        });


        $("#choose-frametype label").click(function () {

            let value;
            if ($('input[name="property_frametype"]:checked').length > 0 || $('input[name="property_frametype"][checked]').length > 0) {

                value = $('input[name="property_frametype"]:checked').val();
                field_id = getPropertyIdByCode('property_frametype');
                related_fields = getRelatedFields(field_id);
                //// console.log(related_fields);
                for (var i = 0; i < related_fields.length; i++) {
                    field_data = getRelatedFieldData(related_fields[i], field_id, value);
                    property_code = getPropertyCodeById(related_fields[i]);
                    //// console.log("Loading to " + property_code + " data: " + field_data);
                    if ($("#" + "property_" + property_code).data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        //filter only when track is selected
                        var field_check = "property_" + property_code;
                        //// console.log("filtering " + field_check + ' from frametype');
                        $('input[name=' + field_check + ']').each(function () {
                            var found = false;
                            for (var i = 0; i < field_data.length; i++) {
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
                    }
                }

                //set default values for frame left-right-top-bottom
                if (value == 144) { //bottom m-track
                    // console.log('selected 144');
                    // console.log('#choose-frametype label 144');
                    // console.log('frame_top 8');
                    $("#property_framebottom").select2('val', '151');
                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_frametop").select2("val", '80');
                }

                if (value == 143) { //track in board
                    // console.log('selected 143');
                    // console.log('#choose-frametype label 143');
                    // console.log('frame_top 9');
                    $("#property_framebottom").select2('val', '136');
                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_frametop").select2("val", '80');
                }

                if (value === 144 || value === 143) {
                    //$("#frame-left, #frame-right, #frame-top, #frame-bottom").hide();
                } else {
                    // console.log('frame_top 10');
                    //$("#frame-left, #frame-right, #frame-top, #frame-bottom").show();
                    // console.log('selectare property_frametype 3');

                    // freme top problem on add new shutter

                    $("#property_frameleft").select2("val", '70');
                    $("#property_frameright").select2("val", '75');
                    $("#property_framebottom").select2("val", '85');
                    if (style_check.indexOf('Café') > -1) {
                        $("#property_frametop").select2("val", '81');
                    } else {
                        $("#property_frametop").select2("val", '80');
                    }
                }
            }
        });

        $("#property_controltype").change(function () {
            if ($("#property_controltype").select2('data')) {
                controltype_check = $("#property_controltype").select2('data').value;
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
                style_check = getStyleTitle();
                //  console.log('selectare property_frametype');
                //default values for cafe style
                if (style_check.indexOf('Café') > -1) {
                    // console.log('frame_top 11');
                    $("#property_frametop").select2("val", '81');
                } else {
                    // console.log('frame_top 12');
                    $("#property_frametop").select2("val", '80');
                }
            }
        });

        $("#property_midrailheight").change(function () {
            showMidrailPositionCritical();
            showHideControlSplit2();
            // console.log('2 showMidrailPositionCritical #property_midrailheight, #property_midrailheight2');
        });

        $("#property_midrailheight2").change(function () {
            showMidrailPositionCritical();
            showHideControlSplit2();
            // console.log('3 showMidrailPositionCritical #property_midrailheight, #property_midrailheight2');
        });

        $("#property_material, #attachment").change(function () {
            setProductByMaterialAndStyle();
            filterStiles();
        });

        $('.property-select').each(function () {
            $(this).trigger('change');
        });

        var lengKeyR = 0;

        $(".layoutcode").on('keypress', function (event) {
            var layout_id = $(this).attr('id');
            var k = layout_id.substr(layout_id.length - 1);
            //console.log(layout_id);
            var charCode = event.which;

            if (!charCode) { // <-- charCode === 0
                return; // return false, optionally
            }

            var character = String.fromCharCode(charCode).toUpperCase();
            // console.log('Character key press: ' + character);

            var text = $(this).val().toUpperCase();
            let letter = text.charAt(0);
            let lastChar = text.charAt(text.length - 1);
            // console.log('last letter press ' + lastChar, text);
            // console.log('character press ' + character);
            if (lastChar != "C" && lastChar != "G" && lastChar != "B" && lastChar != "T") {
                console.log('lastChar !== "C"');
                if (letter == 'R' && lengKeyR == 1 && "L" == character) { // <-- charCode === 0
                    console.log('letter r and lengkeyr1');
                    return false; // return false, optionally
                }
                if ($("#property_material").val() == '138' && letter == 'R' && (lengKeyR == 2 || lengKeyR == 3) && "L" == character) {
                    console.log('letter r and lengkeyr2 || lengkeyr3');
                    return false; // return false, optionally
                }
            }

            if (letter == 'R') {
                var str = text;
                var stringsearch = "R"
                for (var i = lengKeyR = 0; i < str.length; lengKeyR += +(stringsearch === str[i++])) ;
            }

            $("#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    // console.log('flexible');
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });

            if ("L" == character || "R" == character || event.keyCode == 8) {
                return true;
            } else if ("T" == character) {
                $('#layoutcode-column' + k + ' .error-text').remove();
                var text = $(this).val();
                console.log('in t press ' + text);
                if (text.charAt(text.length - 1).toUpperCase() == 'G') {
                    return false;
                } else {
                    return true;
                }
            } else if ("G" == character) {
                var text = $(this).val();
                //// console.log('in G press '+text.length);
                if (text.charAt(text.length - 1).toUpperCase() == 'L' || text.charAt(text.length - 1).toUpperCase() == 'R' || text.charAt(text.length - 1).toUpperCase() == 'G') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });

        $(".layoutcode").on('keyup', function () {
            var layout_id = $(this).attr('id');
            var k = layout_id.substr(layout_id.length - 1);
            // console.log('layout_id: ' + layout_id);
            var text = $(this).val().toUpperCase();
            // console.log('code: ' + text);
            updateLayoutFields(text, layout_id);

            if (text.length > 1) {
                // console.log('keyup press ' + text);
                // console.log(text.length);
                if (text == 'L' || text == 'R') {
                    $('#property_opendoor' + k + '').parent().show();
                } else if ((text == 'LL' && text.length === 2) || (text == 'RR' && text.length === 2)) {
                    $('#property_opendoor' + k + '').parent().hide();
                } else if ((text == 'LLL' && text.length === 3) || (text == 'RRR' && text.length === 3)) {
                    $('#property_opendoor' + k + '').parent().hide();
                } else {
                    $('#property_opendoor' + k + '').parent().show();
                    $('#property_opendoor' + k + '').val('Right');
                    $('#property_opendoor' + k + '').trigger('change');
                }
                // console.log('#property_opendoor' + k);
                $('#property_opendoor' + k + ' option[value="Right"]').attr("selected", "selected");
            } else {
                $('#property_opendoor' + k + '').parent().hide();
            }

            /** Hide t-post frame after layout code T
             * if frame type have P in name (P4008w) show only T-post type with P in name
             * else hide
             */
            hideTpostBtFrameType();
        });

        $("input.p_width").change(function () {
            console.log('change width');
            calculateTotalSection();
            calculateTotal();

            hideStileByHeight(1800);
        });

        $("#property_nr_sections").on('keypress', function (ev) {
            var nrSections = String.fromCharCode(ev.which);
            console.log('nrSections ' + nrSections);
            if (nrSections < 1) { // <-- charCode === 0
                return false; // return false, optionally
            }

            console.log('selectare numar sectiuni');
            var nr_individuals = nrSections;
            $('.row.layout-row').html('');
            $('#outside_canvas').html('');
            for (var i = 1; i <= nr_individuals; i++) {
                var html_canvas = '<p>Drawing Section ' + i + '</p><div id="canvas_container' + i + '" class="canvas_container" style="min-height: 500px;border: 1px solid #aaa;background-image: url(/wp-content/plugins/shutter-module/imgs/drawing_graph.png);"></div> <br/>'

                var html_layout = '<div class="col-sm-4" id="layoutcode-column' + i + '">\n' +
                    '                                                        Layout Configuration Section ' + i + ' :' +
                    '                                                        <br>\n' +
                    '                                                        <div class="input-group-container">\n' +
                    '                                                            <div class="input-group">\n' +
                    '\n' +
                    '                                                                <input class="required input-medium layoutcode" id="property_layoutcode' + i + '" name="property_layoutcode' + i + '" style="text-transform:uppercase" type="text" value="">\n' +
                    '                                                            </div>\n' +
                    '                                                        </div>\n' +
                    '                                                    </div>\n' +
                    '                                                    <div class="col-sm-4"> Width (mm):\n' +
                    '                                                        <br>\n' +
                    '                                                        <input class="required number input-medium p_width" id="property_width' + i + '" name="property_width' + i + '" type="text" value="">\n' +
                    '                                                    </div>' +
                    '<div class="col-sm-4" style="display: none;"> Door to open first: <br/>' +
                    '                                                        <select id="property_opendoor' + i + '"\n' +
                    '                                                                name="property_opendoor' + i + '">\n' +
                    '<option value=""></option>' +
                    '<option value="Right">Right\n' +
                    '                                                            </option>\n' +
                    '                                                            <option value="Left">Left\n' +
                    '                                                            </option>\n' +
                    '                                                        </select>' +
                    '                                                        <input class="required input-medium" id="property_total_section' + i + '" name="property_total_section' + i + '" style="text-transform:uppercase" type="hidden" value="">\n' +
                    '</div><div class="clearfix"></div>' +
                    '<div class=" extra-columns-row' + i + '"><div class="col-sm-12"></div></div>' +
                    '<div class=" extra-columns-buildout-row' + i + '"><div class="col-sm-12"></div></div>';

                $('.row.layout-row').append(html_layout);
                // $( html_canvas ).insertAfter( $( ".before-canvas" ) );
                $('#outside_canvas').append(html_canvas);

            }

            $("input.p_width").change(function () {
                console.log('change width');
                calculateTotalSection();
                calculateTotal();

                hideStileByHeight(1800);

            });

            $(".layoutcode").on('keypress', function (event) {
                var layout_id = $(this).attr('id');
                var k = layout_id.substr(layout_id.length - 1);
                //console.log(layout_id);
                var charCode = event.which;

                if (!charCode) { // <-- charCode === 0
                    return; // return false, optionally
                }

                var character = String.fromCharCode(charCode).toUpperCase();
                // console.log('Character key press: ' + character);

                $("#buildout-select").change(function () {
                    // Check input( $( this ).val() ) for validity here
                    if ($(this).val() === 'flexible') {
                        // console.log('flexible');
                        $('input[name="property_b_buildout1"]').prop('checked', false);
                        $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                    } else {
                        $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                    }
                });

                if ("L" == character || "R" == character || event.keyCode == 8) {
                    return true;
                } else if ("T" == character) {
                    $('#layoutcode-column' + k + ' .error-text').remove();
                    var text = $(this).val();
                    console.log('in t press ' + text);
                    if (text.charAt(text.length - 1).toUpperCase() == 'G') {
                        return false;
                    } else {
                        return true;
                    }
                } else if ("G" == character) {
                    var text = $(this).val();
                    //// console.log('in G press '+text.length);
                    if (text.charAt(text.length - 1).toUpperCase() == 'L' || text.charAt(text.length - 1).toUpperCase() == 'R' || text.charAt(text.length - 1).toUpperCase() == 'G') {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            });

            $(".layoutcode").on('keyup', function () {
                var layout_id = $(this).attr('id');
                var k = layout_id.substr(layout_id.length - 1);
                // console.log('layout_id: ' + layout_id);
                var text = $(this).val().toUpperCase();
                // console.log('code: ' + text);
                updateLayoutFields(text, layout_id);

                if (text.length > 1) {
                    // console.log('keyup press ' + text);
                    // console.log(text.length);
                    if (text == 'L' || text == 'R') {
                        $('#property_opendoor' + k + '').parent().show();
                    } else if ((text == 'LL' && text.length === 2) || (text == 'RR' && text.length === 2)) {
                        $('#property_opendoor' + k + '').parent().hide();
                    } else if ((text == 'LLL' && text.length === 3) || (text == 'RRR' && text.length === 3)) {
                        $('#property_opendoor' + k + '').parent().hide();
                    } else {
                        $('#property_opendoor' + k + '').parent().show();
                        $('#property_opendoor' + k + '').val('Right');
                        $('#property_opendoor' + k + '').trigger('change');
                    }
                    // console.log('#property_opendoor' + k);
                    $('#property_opendoor' + k + ' option[value="Right"]').attr("selected", "selected");
                } else {
                    $('#property_opendoor' + k + '').parent().hide();
                }
            });

        });


        $("#property_material, #property_bladesize, #property_stile, #property_hingecolour").change(function () {
            filterControlType();
        });

        $("#property_hingecolour").change(function () {
            // if hinge color id is mot equal to 93 (Stainless Steel) and material is green then show alert-info about waterproof
            property_val = $('input[name="property_hingecolour"]').val();
            if (product_title_check.indexOf('Green') > -1 || product_title_check.indexOf('EcowoodPlus') > -1) {
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
                // console.log('flexible');
                $('input[name="property_b_buildout1"]').prop('checked', false);
                $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
            } else {
                $('.pull-left.extra-column-buildout.property_b_buildout1').show();
            }
        });

        function initFilterByProduct() {
            value = $("#product_id").val();

            related_fields = getRelatedFieldsByProduct();
            //// console.log(related_fields);
            for (var i = 0; i < related_fields.length; i++) {
                field_data = getRelatedFieldDataByProductId(related_fields[i], value);
                property_code = getPropertyCodeById(related_fields[i]);
                // console.log("Loading to " + property_code + " data: " + field_data);
                loadItems("property_" + property_code, field_data);
            }
        }

        function filterStiles() {
            stile_id = getPropertyIdByCode('property_stile');
            style_check = getStyleTitle();
            //stile_default = 155; //butt rebated 50.8
            stile_data = [];
            //get the stile data based on property material
            property_material = $("#property_material").val();
            field_id = getPropertyIdByCode('property_material');
            related_fields = getRelatedFields(field_id);

            for (var i = 0; i < related_fields.length; i++) {
                property_code = getPropertyCodeById(related_fields[i]);
                if (property_code == 'stile') {
                    stile_data = getRelatedFieldData(related_fields[i], field_id, property_material);
                }
            }


            // if tracked style
            if (style_check.indexOf('Tracked') == -1) {
                stile_default = 155;
                new_stile_data = getFieldDataContains(stile_id, '51', stile_data);

                loadItems('property_stile', new_stile_data);
                if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == 'undefined') {
                    $("#property_stile").select2("val", stile_default);
                }
            } else {
                stile_default = 375;
                //new_stile_data = stile_data;
                new_stile_data = getFieldDataContains(stile_id, '51', stile_data);

                loadItems('property_stile', new_stile_data);
                if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == 'undefined') {
                    $("#property_stile").select2("val", stile_default);
                }
            }
            //new_stile_data = stile_data;
            // // console.log('before stile data');
            // // console.log(new_stile_data);
            // // console.log('after stile data');

            if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == '375') {
                //$("#property_stile").select2('data').remove();
            }

            // loadItems('property_stile', new_stile_data);
            // if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == 'undefined') {
            //     $("#property_stile").select2("val", stile_default);
            // }
        }

        function showShapeUploadFileAccordingToStyle() {
            //if 'Shaped & French Cut Out' style is chosen when configuration is loaded, show the upload file section
            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
                // console.log('sdfdsf ');
                //show the upload file section to allow uploading of a shape
                if (style_check.indexOf('Shaped') > -1 || style_check.indexOf('French') > -1) {
                    // console.log('clickkkk');
                    $("#shape-section").show();
                    $("#shape-section").addClass("required");
                    $('#shape-section-draw').addClass("required");
                    $('#shape-section-draw').show();
                }
            }
        }

        function setProductByMaterialAndStyle() {
            material_id = '';
            if ($("#property_material").select2('data')) {
                material_id = $("#property_material").select2('data').id;
            }


            material_code = '';
            style_code = '';
            product_id = '';
            for (var i = 0; i < property_values.length; i++) {
                if (property_values[i].id == material_id)
                    material_code = property_values[i].code;
            }
            if ($('input[name=property_style]:checked').length > 0) {
                style_code = $('input[name=property_style]:checked').data('code');
            }
            existingShapeFile = $('#provided-shape').html().trim();
            if ($('input[name=attachment]').val() != '' || existingShapeFile.length > 0) {
                style_code = 'specialshape';
            }

            product_code = material_code + '-' + style_code;
            if (shutter_type == 'Blackout') {
                product_code = 'blackout-' + product_code;
            }

            //// console.log('Checking product code: ' + product_code);
            //// console.log('With products' + names);
            for (var i = 0; i < names.length; i++) {
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
                product_title_check = $("#property_material").select2('data').value;

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
        function updateLayoutFields(text, layout_id) {
            console.log('updateLayoutFields: ' + text);
            console.log('layout_id: ' + layout_id);
            var sec = layout_id.slice(layout_id.length - 1);
            var property_material = $("#property_material").val();

            //count T occurences

            // $("#property_layoutcode").on('keyup', function () {
            //     var text = $("#property_layoutcode").val();
            //     updateLayoutFields(text);
            // });

            //pull data from prototype html
            //// console.log('add extra-column 1');
            var column_html = '<div class="col-sm-2" id="extra-column' + sec + '" style="display: none">\n' +
                '<span class="extra-column-label' + sec + '">Label</span>:\n' +
                '<br/>\n' +
                '<div class="input-group">\n' +
                '<input type="text" name="property_extra_column' + sec + '" id="property_extra_column' + sec + '"\n' +
                'class="input-small">\n' +
                '</div>\n' +
                '</div>'

            // var new_column_contents = $("#extra-column").html();
            // var new_column = '<div class="pull-left extra-column' + sec + '">' + column_html + '</div>';

            var new_column_contents = $("#extra-column").html();
            var new_column = "<div class=\"pull-left extra-column" + sec + "\">" + new_column_contents + "</div>";

            layout_columns.t = 0;
            layout_columns.c = 0;
            layout_columns.b = 0;
            layout_columns.g = 0;
            bchar_nr = 1;
            tchar_nr = 1;
            cchar_nr = 1;
            gchar_nr = 1;

            $(".extra-column" + sec + "").remove();
            $(".extra-column-buildout" + sec + "").remove();
            $('.tpost-type' + sec + '').hide();
            //$('.tpost-type label').hide();

            var count_lr = 0;
            var count_t = 0;
            var count_g = 0;

            //clear extra fields
            for (var i = 0; i < text.length; i++) {
                //// console.log('Litera: '+text.charAt(i).toUpperCase());
                //// console.log('t-post press in for '+new_column_contents);
                if (text.charAt(i).toUpperCase() == 'L' || text.charAt(i).toUpperCase() == 'R') {
                    count_lr++;
                    //// console.log('Panels left-right: '+count_lr);
                    $('#panels_left_right').val(count_lr);
                }

                if (text.charAt(i).toUpperCase() == 'T') {
                    //// console.log('t-post press in if');
                    //// console.log('tchar_nr : '+tchar_nr);
                    count_t++;
                    layout_columns.t++;
                    label = 'T-Post ' + layout_columns.t;
                    id = "property_t" + layout_columns.t;
                    addField(label, id, 1, sec);

                    if (tchar_nr < 2) {
                        label = 'T-Post Buildout ' + tchar_nr;
                        label2 = 'T-Post Style ';
                        id2 = "property_t_buildout" + tchar_nr;
                        addFieldCheckboxBuildoutSelect(label2, id2, 'help text demo', 't', sec);
                        addFieldCheckboxBuildout(label, id2, 1, sec);
                        tchar_nr++;
                        // console.log('tchar_nr after : ' + bchar_nr);

                        jQuery('.tpost-img label').hide();

                        // Earth 187
                        if (material_id == 187) {
                            // console.log('show earth img');
                            jQuery('#stile-img-earth').show();
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-ecowoodPlus').hide();
                            jQuery('#stile-img-biowoodPlus').hide();
                            //tpost-type
                            jQuery('.type-img-earth').show();
                            jQuery('.type-img-earth').parent().show();

                        }
                        // BasswoodPlus 139
                        else if (material_id == 139) {
                            // console.log('show BasswoodPlus img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-basswoodPlus').show();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-green').hide();
                            //tpost-type
                            jQuery('.type-img-basswoodPlus').show();
                            jQuery('.type-img-basswoodPlus').parent().show();
                        }
                        else if (material_id == 147) {
                            // console.log('show BasswoodPlus img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-basswood').show();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-green').hide();
                            //tpost-type
                            jQuery('.type-img-basswood').show();
                            jQuery('.type-img-basswood').parent().show();
                        }
                        // Biowood 138
                        else if (material_id == 138) {
                            // console.log('show Biowood img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-biowoodPlus').show();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-ecowoodPlus').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-green').hide();
                            //tpost-type
                            jQuery('.type-img-biowoodPlus').show();
                            jQuery('.type-img-biowoodPlus').parent().show();
                        } else if (material_id == 6) {
                            // console.log('show Biowood img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-biowood').show();
                            jQuery('#stile-img-biowoodPlus').hide();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-ecowoodPlus').hide();
                            jQuery('#stile-img-green').hide();
                            //tpost-type
                            jQuery('.type-img-biowood').show();
                            jQuery('.type-img-biowood').parent().show();
                        }
                        // Green 137
                        else if (material_id == 137) {
                            // console.log('show Green img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-green').show();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-ecowoodPlus').hide();
                            jQuery('#stile-img-biowoodPlus').hide();
                            //tpost-type
                            jQuery('.type-img-green').show();
                            jQuery('.type-img-green').parent().show();
                        } else if (material_id == 5) {
                            // console.log('show Green img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-ecowoodPlus').show();
                            jQuery('#stile-img-green').hide();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-ecowood').hide();
                            jQuery('#stile-img-biowoodPlus').hide();
                            //tpost-type
                            jQuery('.type-img-ecowoodPlus').show();
                            jQuery('.type-img-ecowoodPlus').parent().show();
                        }
                        // ecowood 188
                        else if (material_id == 188) {
                            console.log('show ecowood img');
                            jQuery('.tpost-img').hide();
                            jQuery('#stile-img-ecowood').show();
                            jQuery('#stile-img-basswood').hide();
                            jQuery('#stile-img-basswoodPlus').hide();
                            jQuery('#stile-img-biowood').hide();
                            jQuery('#stile-img-earth').hide();
                            jQuery('#stile-img-green').hide();
                            jQuery('#stile-img-ecowoodPlus').hide();
                            jQuery('#stile-img-biowoodPlus').hide();
                            //tpost-type
                            jQuery('.type-img-ecowood').show();
                            jQuery('.type-img-ecowood').parent().show();

                        }

                        $('.tpost-type').show();
                        $('.tpost-type label').show();
                    } else {
                        // console.log('tchar_nr depasit : ' + tchar_nr);

                    }
                }

                if (text.charAt(i).toUpperCase() == 'G') {
                    //// console.log('g-post press in if');
                    //// console.log('gchar_nr : '+gchar_nr);
                    count_g++;
                    layout_columns.g++;
                    label = 'G-Point ' + layout_columns.g;
                    id = "property_g" + layout_columns.g;
                    addField(label, id, 1, sec);

                    if (gchar_nr < 20) {
                        //teo                            label = 'G-Post Buildout ' + gchar_nr;
                        //teo                            id2 = "property_g_buildout" + gchar_nr;
                        //teo                            addFieldCheckboxBuildout(label, id2, 1);
                        //teo                            gchar_nr++;
                        // // console.log('G-char_nr after : '+bchar_nr);
                        // $('.gpost-type').show();
                    } else {
                        // console.log('gchar_nr depasit : ' + gchar_nr);

                    }
                }


                if (text.charAt(i).toUpperCase() == 'C') {
                    //// console.log('t-post press in if');
                    // console.log('cchar_nr : ' + cchar_nr);
                    layout_columns.c++;
                    label = 'C-Post ' + layout_columns.c;
                    id = "property_c" + layout_columns.c;
                    addField(label, id, 1, sec);

                    if (cchar_nr < 2) {
                        label = 'C-Post Buildout ' + cchar_nr;
                        id3 = "property_c_buildout" + cchar_nr;
                        addFieldCheckboxBuildout(label, id3, 1, sec);
                        cchar_nr++;
                        // console.log('cchar_nr after : ' + bchar_nr);
                    } else {
                        // console.log('cchar_nr depasit : ' + cchar_nr);
                    }

                }

            }

            var t1 = $('#t1').val();
            var t2 = $('#t2').val();
            var t3 = $('#t3').val();
            var t4 = $('#t4').val();
            var t5 = $('#t5').val();
            var t6 = $('#t6').val();
            var t7 = $('#t7').val();
            var t8 = $('#t8').val();
            var t9 = $('#t9').val();
            var t10 = $('#t10').val();
            var t11 = $('#t11').val();
            var t12 = $('#t12').val();
            var t13 = $('#t13').val();
            var t14 = $('#t14').val();
            var t15 = $('#t15').val();

            var c1 = $('#c1').val();
            var c2 = $('#c2').val();
            var c3 = $('#c3').val();
            var c4 = $('#c4').val();
            var c5 = $('#c5').val();
            var c6 = $('#c6').val();
            var c7 = $('#c7').val();
            var c8 = $('#c8').val();
            var c9 = $('#c9').val();


            $('#property_t1').val(t1);
            $('#property_t2').val(t2);
            $('#property_t3').val(t3);
            $('#property_t4').val(t4);
            $('#property_t5').val(t5);
            $('#property_t6').val(t6);
            $('#property_t7').val(t7);
            $('#property_t8').val(t8);
            $('#property_t9').val(t9);
            $('#property_t10').val(t10);
            $('#property_t11').val(t11);
            $('#property_t12').val(t12);
            $('#property_t13').val(t13);
            $('#property_t14').val(t14);
            $('#property_t15').val(t15);

            $('#property_c1').val(c1);
            $('#property_c2').val(c2);
            $('#property_c3').val(c3);
            $('#property_c4').val(c4);
            $('#property_c5').val(c5);
            $('#property_c6').val(c6);
            $('#property_c7').val(c7);
            $('#property_c8').val(c8);
            $('#property_c9').val(c9);
            $('#property_c10').val(c10);
            $('#property_c11').val(c11);
            $('#property_c12').val(c12);
            $('#property_c13').val(c13);
            $('#property_c14').val(c14);
            $('#property_c15').val(c15);


            //restart tooltips because of new fields
            $('[data-toggle="tooltip"]').tooltip({
                'placement': 'top'
            });
        }

        //add new field based on layout code
        function addField(label, id, helptext, sec) {

            //use data from prototype column
            //// console.log('add extra-column 2');
            var new_column_contents = '<span class="extra-column-label' + sec + '">Label</span>:\n' +
                '<br/>\n' +
                '<div class="input-group">\n' +
                '<input type="text" name="property_extra_column' + sec + '" id="property_extra_column' + sec + '"\n' +
                'class="input-small">\n' +
                '</div>';

            // var new_column_contents = $("#extra-column" + sec + "").html();
            //// console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column" + sec + "\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label" + sec + "").html(label);
            $(element).find("input").attr("id", id + "_" + sec);
            $(element).find("input").addClass('required');
            $(element).find("input").attr("name", id + "_" + sec);

            if (configuration[id] !== 'undefined') {
                $(element).find("input").val(configuration[id]);
            }

            if ($(".extra-column" + sec + "").length > 0) {
                $(element).insertAfter($(".extra-column" + sec + "").last());
            } else {
                $(element).appendTo(".extra-columns-row" + sec + " div");
            }
        }


        //add new field Buildout based on layout code checkbox
        function addFieldCheckboxBuildout(label, id, helptext, sec) {

            //use data from prototype column
            //// console.log('add extra-column 2');
            var new_column_contents = '<span class="extra-column-label' + sec + '">T-Post Buildout 1:</span>\n' +
                '<br/>\n' +
                '<div class="input-group">\n' +
                '<input type="text" name="property_extra_column' + sec + '" id="property_extra_column' + sec + '"\n' +
                'class="input-small">\n' +
                '</div>';

            // var new_column_contents = $("#extra-column").html();
            //// console.log(new_column_contents);

            //create extra column element
            var new_column = '<div class="pull-left extra-column-buildout' + sec + ' ' + id + '">' + new_column_contents + '</div>';
            var element = $(new_column);
            $(element).find(".extra-column-label" + sec + "").html(label);
            $(element).find("input").attr(
                {
                    "type": "checkbox",
                    "id": id + '_' + sec,
                    "name": id + '_' + sec,
                    "value": "yes",
                }
            );


            if (configuration[id] !== 'undefined') {
                $(element).find("input").val('yes');
            }

            if ($(".extra-column-buildout" + sec + "").length > 0) {
                $(element).insertAfter($(".extra-columns-buildout-row" + sec + " div .extra-column-buildout" + sec + "").last());
            } else {
                // property_framebottom
                $(element).appendTo(".extra-columns-buildout-row" + sec + " div");
            }
        }

        //add new field select based on layout code if material is ecowood
        function addFieldBuildAngleSelect(label, id, helptext) {

            //use data from prototype column
            //// console.log('add extra-column 2');
            var new_column_contents = '<span class="extra-column-label' + sec + '">Label</span>:\n' +
                '<br/>\n' +
                '<div class="input-group">\n' +
                '<input type="text" name="property_extra_column' + sec + '" id="property_extra_column' + sec + '"\n' +
                'class="input-small">\n' +
                '</div>';

            // var new_column_contents = $("#extra-column").html();
            //// console.log(new_column_contents);

            //create extra column element
            var new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";
            var element = $(new_column);
            $(element).find(".extra-column-label").html(label);
            $(element).find("input").remove();
            $(element).find(".extra-column-label").append("<select class='b-angle-select' id='" + id + "' name='" + id + "'><option value='90'>90</option><option value='135'>135</option></select>");

            $(element).find('.input-group').remove();

            $(element).html(function (i, html) {
                return html.replace(":", "");
            });

            if ($(".extra-column").length > 0) {
                $(element).insertAfter($(".extra-column").last());
            } else {
                $(element).appendTo(".extra-columns-row div");
            }
        }


        //add new field Buildout based on layout code checkbox
        function addFieldCheckboxBuildoutSelect(label, id, helptext, character, sec) {

            //use data from prototype column
            //// console.log('add extra-column 2');
            var new_column_contents = '<span class="extra-column-label' + sec + '">' + character + '-Post Buildout 1:</span>\n' +
                '<br/>\n' +
                '<div class="input-group">\n' +
                '<input type="text" name="property_extra_column' + sec + '" id="property_extra_column' + sec + '"\n' +
                'class="input-small">\n' +
                '</div>';


            // var new_column_contents = $("#extra-column").html();
            //// console.log(new_column_contents);

            //create extra column element
            var new_column = '<div class="pull-left extra-column-buildout' + sec + '">' + new_column_contents + '</div>';
            var element = $(new_column);

            $(element).find(".extra-column-label" + sec + "").html(label);
            $(element).find("input").remove();
            if (character === 'b') {
                $(element).find(".extra-column-label" + sec + "").append("<select id='buildout-select' name='bay-post-type'><option value='normal'>Normal</option><option value='flexible'>Flexible</option></select>");
            } else if (character === 't') {
                $(element).find(".extra-column-label" + sec + "").append("<select id='buildout-select-t" + sec + "'  name='t-post-type" + sec + "'><option value='normal'>Normal</option><option value='adjustable'>Adjustable</option></select>");
            }

            // if (configuration[id] !== 'undefined') {
            //     $(element).find("input").val('normal');
            // }
            $(element).appendTo(".extra-columns-buildout-row" + sec + " > div");

            $('#buildout-select' + sec + '').parent().parent().find('.input-group').remove();

            $('#buildout-select' + sec + '').parent().parent().html(function (i, html) {
                return html.replace(":", "");
            });
            $('#buildout-select-t' + sec + '').parent().parent().html(function (i, html) {
                return html.replace(":", "");
            });


            $("select#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    // console.log('flexible');
                    $('input[name="property_b_buildout1"]').prop('checked', false);
                    $('.pull-left.extra-column-buildout.property_b_buildout1').hide();
                } else {
                    $('.pull-left.extra-column-buildout.property_b_buildout1').show();
                }
            });

            // if ($(".extra-column-buildout").length > 0) {
            //     $(element).insertAfter($(".extra-columns-buildout-row div .extra-column-buildout").last());
            // } else {
            //     $(element).appendTo(".extra-columns-buildout-row div");
            // }
        }


        function calculateTotal() {


            // Marian - Calculate number frames
            var total;
            if (total == 'NaN')
                total = 0;
            var width = 0;

            var nr_individuals = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nr_individuals; i++) {
                width = width + parseFloat($("#property_width" + i + "").val());
            }
            var height = $("#property_height").val();

            var frametype = $('input[name=property_frametype]:checked').val();

            var frameTypes = {
                289: 22,
                325: 15,
                326: 16,
                327: 17,
                328: 31,
                324: 28.5,
                329: 44.5,
                142: 0,
                314: 22,
                315: 50,
                316: 19.05,
                317: 44.45,
                302: 11,
                301: 11,
                352: 57.15,
            };

            var frameLeft = $("#property_frameleft").val();
            var frameRight = $("#property_frameright").val();
            var frameTop = $("#property_frametop").val();
            var frameBottom = $("#property_framebottom").val();

            var framesWidth = [frameLeft, frameRight];
            var framesHeight = [frameTop, frameBottom];
            var x;
            var y;
            var framesNrWidth = 2;
            var framesNrHeight = 2;

            // console.log('framesWidth ' + framesWidth);
            // console.log('framesHeight ' + framesHeight);

            // for (x of framesWidth) {
            //     // // console.log('x ' + x);
            //     if (x == 71 || x == 76 || x == 72 || x == 77) {
            //         framesNrWidth = framesNrWidth - 1;
            //     }
            // }
            // // // console.log('framesNrWidth ' + framesNrWidth);
            //
            // for (y of framesHeight) {
            //    // // console.log('y ' + y);
            //     if (y == 81 || y == 86 || y == 82 || y == 87) {
            //         framesNrHeight = framesNrHeight - 1;
            //     }
            // }

            // console.log('framesNrHeight ' + framesNrHeight);
            // console.log('framesNrWidth ' + framesNrWidth);

            var n = 0;

            $.each(frameTypes, function (key, value) {
                if (key === frametype) {
                    n = value;
                }
            });
            // console.log(n);

            total = (parseFloat(width) + parseFloat(framesNrWidth) * parseFloat(n)) * (parseFloat(height) + parseFloat(framesNrHeight) * parseFloat(n)) / parseFloat(1000000);

            console.log('total sqm: ' + total.toFixed(2));
            // total = $("#property_width").val() * $("#property_height").val();


            var total_sqm = 0;
            for (var i = 1; i <= nr_individuals; i++) {
                var sqm_section = parseFloat($("#property_total_section" + i + "").val());
                total_sqm = total_sqm + sqm_section;
                console.log('total sqm All ' + i + ' : ' + sqm_section);
            }
            console.log('total sqm All: ' + total_sqm);
            // $("#property_total").val(parseFloat(total).toFixed(2));

            //midrailheight required for >1800 height and NOT Tier styles
            style_check = getStyleTitle();
            if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                $("#property_midrailheight").addClass("required");
                // console.log('midrail required');

                //$("#midrail-height").show();
            } else {
                //$("#property_midrailheight").removeClass("required");
                //$("#midrail-height").hide();
            }

            filterStiles();
            showHideControlSplit2();
        }

        function calculateTotalSection() {
            var total_sqm = 0;
            var nr_individuals = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nr_individuals; i++) {
                // Marian - Calculate number frames
                var total;
                if (total == 'NaN')
                    total = 0;
                var width = 0;

                width = parseFloat($("#property_width" + i + "").val());

                var height = $("#property_height").val();
                // console.log('width: ' + width);

                var frametype = $('input[name=property_frametype]:checked').val();

                var frameTypes = {
                    289: 22,
                    325: 15,
                    326: 16,
                    327: 17,
                    328: 31,
                    324: 28.5,
                    329: 44.5,
                    142: 0,
                    314: 22,
                    315: 50,
                    316: 19.05,
                    317: 44.45,
                    302: 11,
                    301: 11,
                    352: 57.15,
                };

                var frameLeft = $("#property_frameleft").val();
                var frameRight = $("#property_frameright").val();
                var frameTop = $("#property_frametop").val();
                var frameBottom = $("#property_framebottom").val();

                var framesWidth = [frameLeft, frameRight];
                var framesHeight = [frameTop, frameBottom];
                var x;
                var y;
                var framesNrWidth = 2;
                var framesNrHeight = 2;


                var n = 0;

                $.each(frameTypes, function (key, value) {
                    if (key === frametype) {
                        n = value;
                    }
                });
                // console.log(n);

                total = (parseFloat(width) + parseFloat(framesNrWidth) * parseFloat(n)) * (parseFloat(height) + parseFloat(framesNrHeight) * parseFloat(n)) / parseFloat(1000000);

                console.log('total sqm ' + i + ' -- ' + parseFloat(total).toFixed(2));
                // total = $("#property_width").val() * $("#property_height").val();


                $("#property_total_section" + i + "").val(parseFloat(total).toFixed(2));
                total_sqm += parseFloat(total);
            }
            $("#property_total").val(parseFloat(total_sqm).toFixed(2));
            console.log('total multi sqm -- ' + parseFloat(total_sqm).toFixed(2));
        }

        //get the property id based on ui property code eg: property_fit = property with id 9
        function getPropertyIdByCode(code) {
            id = 0;
            for (i = 0; i < property_fields.length; i++) {
                if (("property_" + property_fields[i].code) == code) {
                    id = property_fields[i].id;
                }
            }
            return id;
        }


        function showMidrailPositionCritical() {
            if ($("#property_material").select2('data')) {
                product_title_check = $("#property_material").select2('data').value;
                var property_midrailpositioncritical = $("#property_midrailpositioncritical").val();
                if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight").val() > 0) {
                    $("#midrail-position-critical").show();
                    if (property_midrailpositioncritical === '') {
                        $("#property_midrailpositioncritical").select2("val", '170');
                    }
                    $("midrail-position-critical input").removeClass('not-required');
                } else if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight2").val() > 0) {
                    $("#midrail-position-critical").show();
                    if (property_midrailpositioncritical === '') {
                        $("#property_midrailpositioncritical").select2("val", '170');
                    }
                    $("midrail-position-critical input").removeClass('not-required')
                } else {
                    $("#midrail-position-critical").hide();
                    $("#midrail-position-critical input").addClass('not-required');
                }
            } else {
                $("#midrail-position-critical").hide();
                $("#midrail-position-critical input").addClass('not-required');
            }
            if (property_midrailpositioncritical === '') {
                $("#property_midrailpositioncritical").select2("val", '170');
            }
            // console.log('showMidrailPositionCritical #property_midrailheight, #property_midrailheight2');

        }


        function getStyleTitle() {
            var title = '';
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
            $("#property_controltype").closest('div').show().removeClass('solid-selected');

            if ($('input[name=property_style]:checked').length > 0) {
                style_check = $('input[name=property_style]:checked').data('title');
            } else {
                return;
            }

            //  console.log('selectare property_frametype 2');
            if (style_check.indexOf('Café') > -1) {
                // console.log('frame_top 3');
                $("#property_frametop").select2("val", '81');
            } else {
                // console.log('frame_top 4');
                $("#property_frametop").select2("val", '80');
            }


            // console.log('select style ----------------');

            //teo                    if (style_check.indexOf('Café') > -1) {
            //teo                        // console.log('selected CAFE Frame NO  ----------------  1');
            //teo                        $("#property_material").select2("val", '187');
            //teo                    }

            if (!$("#property_frametop").select2("data")) {
                // console.log('selected CAFE Frame NO  ----------------  2');
                if (style_check.indexOf('Café') > -1) {
                    // console.log('frame_top 13');
                    $("#property_frametop").select2("val", '81');
                } else {
                    // console.log('frame_top 14');
                    $("#property_frametop").select2("val", '80');
                }
            }
            //teo
            if (style_check.indexOf('Tracked By-Fold') > -1) {

                if ($('input[name="property_frametype"]:checked').length > 0) {
                    $('input[name="property_frametype"]:checked').first().closest('label').trigger('click');
                }
                if ($('input[name="property_frametype"][checked]').length > 0) {
                    $('input[name="property_frametype"][checked]').first().closest('label').trigger('click');
                }
                $('img[src="/wp-content/plugins/shutter-module/imgs/Track_in_Boardx2.png"]').attr("src", "/wp-content/plugins/shutter-module/imgs/Track_in_Board.png");

                // console.log('Selected tracked even if is edit');
                $("#trackedtype").show();
                $("#property_layoutcode").hide();
                $("#property_layoutcode_tracked").show();
                $("#property_layoutcode").val("");
                $("#trackedtyperecess").hide();
                $("#property_tracksnumber").val("");
                $("#tracksnumber").hide();
                $("#lightblocks").hide();
                $("#bypasstype").hide();

                let property_framebottom = $("#property_framebottom").val();
                // console.log('property_framebottom value: ' + property_framebottom);
                let property_frameleft = $("#property_frameleft").val();
                let property_frameright = $("#property_frameright").val();
                // console.log('frame_top 15');
                let property_frametop = $("#property_frametop").val();
                //set default values for frame left-right-top-bottom
                if (property_framebottom) { //bottom m-track
                    // console.log('#choose-frametype property_framebottom');
                    $("#property_framebottom").select2('val', property_framebottom);
                    $("#property_frameleft").select2("val", property_frameleft);
                    $("#property_frameright").select2("val", property_frameright);
                    // console.log('frame_top 16');
                    $("#property_frametop").select2("val", property_frametop);
                }
            } else if (style_check.indexOf('Tracked By-Pass') > -1) {
                if ($('input[name="property_frametype"]:checked').length > 0) {
                    $('input[name="property_frametype"]:checked').first().closest('label').trigger('click');
                }
                if ($('input[name="property_frametype"][checked]').length > 0) {
                    $('input[name="property_frametype"][checked]').first().closest('label').trigger('click');
                }
                $('img[src="/wp-content/plugins/shutter-module/imgs/Track_in_Board.png"]').attr("src", "/wp-content/plugins/shutter-module/imgs/Track_in_Boardx2.png");

                $("#property_layoutcode").hide();
                $("#property_layoutcode_tracked").show();
                $("#property_layoutcode").val("");
                $("#trackedtyperecess, #trackedtype").show();
                $("#tracksnumber").show();
                $("#lightblocks").show();
                $("#bypasstype").show();

                let property_framebottom = $("#property_framebottom").val();
                // console.log('property_framebottom value: ' + property_framebottom);
                let property_frameleft = $("#property_frameleft").val();
                let property_frameright = $("#property_frameright").val();
                // console.log('frame_top 17');
                let property_frametop = $("#property_frametop").val();
                //set default values for frame left-right-top-bottom
                if (property_framebottom) { //bottom m-track
                    // console.log('#choose-frametype property_framebottom');
                    $("#property_framebottom").select2('val', property_framebottom);
                    $("#property_frameleft").select2("val", property_frameleft);
                    $("#property_frameright").select2("val", property_frameright);
                    // console.log('frame_top 18');
                    $("#property_frametop").select2("val", property_frametop);
                }
            } else {
                $("#property_layoutcode_tracked").hide();
                $("#property_layoutcode_tracked").val("");
                $("#property_layoutcode").show();
                $("#trackedtype").hide();
                $("#property_trackedtype").val("");
                $("#trackedtyperecess").hide();
                $("#property_tracksnumber").val("");
                $("#tracksnumber").hide();
                $("#lightblocks").hide();
                $("#bypasstype").hide();
                $("#property_bypasstype").val("");

                if ($('input[name="property_frametype"]:checked').length > 0) {
                    $('input[name="property_frametype"]:checked').first().closest('label').trigger('click');
                }
                if ($('input[name="property_frametype"][checked]').length > 0) {
                    $('input[name="property_frametype"][checked]').first().closest('label').trigger('click');
                }
            }


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
                $("#property_controltype").closest('div').hide().addClass('solid-selected');
                $("#control-split-height").hide();

                if ($('form').attr('edit') === 'no') {
                    // console.log('frame_top 19');
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
                $("#solidtype").show();
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
                $("#midrail-divider").hide();
                $("#midrail-divider2").hide();
                $("#midrail-height input").val('');
                // $("#midrail-height2").hide();
                // $("#midrail-height2 input").val('');
                $("#property_bladesize").closest('div').show();
                // $("#property_bladesize").val('');
                $("#property_bladesize").addClass('not-required');

                //$("#property_sparelouvres").prop('checked', false);
                $("#property_sparelouvres").val('No');
                $("#spare-louvres").hide();
                $("#spare-louvres").closest("div.row").hide();
                $("#ring-pull").show();
                $("#control-split-height").hide();

                if ($('form').attr('edit') === 'no') {
                    // console.log('frame_top 5');
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
                    // console.log('midrail required');
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
                $("#property_controltype").closest('div').show().removeClass('solid-selected');
                $("#control-split-height").show();
            }
            if (style_check.indexOf('Solid') == -1 && style_check.indexOf('Combi') < -1) {
                $("#midrail-height").show();
                $("#property_bladesize").closest('div').show();
                $("#property_bladesize").removeClass('not-required');

                $("#spare-louvres").show();
                $("#spare-louvres").closest("div.row").show();
                $("#property_controltype").closest('div').show().removeClass('solid-selected');
                console.log('frame_top 20');
                //$("#property_framebottom, #property_frametop,
                // #property_frameright,#property_frameleft").prop('readonly',false);
            }
            if (style_check.indexOf('Solid Panel Bay Window Full Height') > -1) {
                // console.log('SELECTED Solid Panel Bay Window Full Height');
                $("#midrail-height").show();
                $("#midrail-height2").show();
                $("#solid-panel-height").hide();
            }
            if (style_check.indexOf('Solid Panel Bay Window Cafe Style') > -1) {
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
            } else if (style_check.indexOf('Solid Combi Panel Bay Window') > -1) {
                // console.log('Solid Combi Panel Bay Window');
                $("#solid-panel-height").show();
                $("#property_bladesize").closest('div').show();
                $("#midrail-height").hide();
                $("#midrail-height2").hide();
                $("#property_controltype").closest('div').show().removeClass('solid-selected');
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
            var check_height = parseFloat($("#property_height").val());
            var check_louvresize = getPropertyBladesize();
            var check_controltype = $("#property_controltype").val();
            var check_controlsplitheight = parseFloat($("#property_controlsplitheight").val());
            var check_controlsplitheight2 = parseFloat($("#property_controlsplitheight2").val());
            var check_midrailheight = getPropertyMidrailheight();
            var check_totheight = getPropertyTotHeight();
            var show_split2 = false;

            if (check_controltype == '96' || check_controltype == '95') {
                var height_required_split;
                if (check_louvresize == '63') height_required_split = 876;
                if (check_louvresize == '76') height_required_split = 1060;
                if (check_louvresize == '89') height_required_split = 1105;

                if (check_midrailheight > 0 || check_totheight > 0) {

                    var split_panel_at_height = 0;
                    if (check_midrailheight > 0) split_panel_at_height = check_midrailheight;
                    if (check_totheight > 0) split_panel_at_height = check_totheight;
                    var panel1_height = check_height - split_panel_at_height;
                    var panel2_height = check_height - panel1_height;

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
            if (shutter_type == 'Shutter') {
                var disable_property_values = [];
            } else if (shutter_type == 'Blackout') {
                var disable_property_values = [137, 33, 35];
            }

            for (var i = 0; i < disable_property_values.length; i++) {
                for (var j = 0; j < property_values.length; j++) {
                    if (property_values[j].id == disable_property_values[i]) {
                        property_values.splice(j);
                    }
                }
            }
        }

        function filterControlType() {
            var property_material = ($("#property_material").select2('data') ? $("#property_material").select2('data').value : '');
            var property_bladesize = ($("#property_bladesize").select2('data') ? $("#property_bladesize").select2('data').value : '');
            var property_stile = ($('input[name=property_stile]:checked').attr('data-title') ? $('input[name=property_stile]:checked').attr('data-title') : '');
            var property_hingecolour = ($("#property_hingecolour").select2('data') ? $("#property_hingecolour").select2('data').value : '');
            var show_hidden_tilt = false;

            if ( /*property_material.indexOf('Green') == -1 && */
                property_bladesize.indexOf('47mm') == -1
                // property_bladesize.indexOf('114.3mm') == -1
                // property_stile.indexOf('38.1') == -1 &&
                /*property_hingecolour.indexOf('Hidden') == -1*/) {
                show_hidden_tilt = true;
            } else {
                show_hidden_tilt = false;
            }

            controltype_data = getAllFieldData(getPropertyIdByCode('property_controltype'));
            //// console.log(controltype_data);
            var new_controltype_data = [];
            $.each(controltype_data, function (index, row) {
                //aluminum has only hidden rod teo_03-Earth Hidden Only
                //teo_04-Earth Hidden Only
                if (property_material.indexOf('Earth') > -1 && row.value.indexOf('Clearview') == -1) {
                    if (property_material.indexOf('Earth') > -1 && row.value.indexOf('Hidden') == -1) {
                        return true;
                    }
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
            //// console.log(new_controltype_data);

            loadItems('property_controltype', new_controltype_data);
        }


        $('[data-toggle="tooltip"]').tooltip({
            'placement': 'top'
        });

        if ($('input[name=property_style]:checked').length > 0) {
            $('input[name=property_style]:checked').closest('label').trigger('click');
        }

        $('.drawing-panel').on('shown.bs.collapse', function () {
            // console.log('update shutter draw');
            var nr_individuals = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nr_individuals; i++) {
                if ($("#canvas_container" + i + "").filter(":visible").length > 0) {
                    updateShutter(i);
                }
            }
        });

        $(window).resize(function () {
            var nr_individuals = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nr_individuals; i++) {
                if ($("#canvas_container" + i + "").filter(":visible").length > 0) {
                    updateShutter(i);
                }
            }
        });

        $(".show-next-panel").click(function () {
            $(this).closest(".panel").find(".panel-collapse").collapse("hide");
            $(this).closest(".panel").next().find(".panel-collapse").collapse("show");
            return false;
        });

        $(".print-drawing").click(function () {
            w = window.open();
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


            $("select#buildout-select").change(function () {
                // Check input( $( this ).val() ) for validity here
                if ($(this).val() === 'flexible') {
                    // console.log('flexible');
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
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// SBS50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_SBS50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z60SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z60SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L70(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L90 /////////////////////////////////////////////////////////////////////////
        function drawFrame_L90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L50MF /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50MF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L60SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_L60SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F70(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70 /////////////////////////////////////////////////////////////////////////
        function drawFrame_F90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        ////////////////////// Z40 /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z40SF /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40SF(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z2BS /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z2BS(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = [
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z3CS /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z3CS(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// D50 /////////////////////////////////////////////////////////////////////////
        function drawFrame_D50(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// BL90 /////////////////////////////////////////////////////////////////////////
        function drawFrame_BL90(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// L50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_L50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };


        ////////////////////// F50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F70PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// F70PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_F90PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z40PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z40PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z50PVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z50PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z2BSPVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z2BSPVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 4,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        ////////////////////// Z3CSPVC /////////////////////////////////////////////////////////////////////////
        function drawFrame_Z3CSPVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, buildoutHeight, relocationPos) {
            // drawCirle(rPaper, x,y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 4,
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
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, x, y, rotation, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };
        ///////////////////////////////////////////////////
        /////////////// STILES and PANEL //////////////////
        ///////////////////////////////////////////////////
        function drawPanelStile_FS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 38.1, -27,
                "L", 38.1, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RFS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
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
            var louvre_path = [
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
                var right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DFS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 38.1, -27,
                    "L", 38.1, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat) {
                var left_path = [
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
                var left_path = [
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
            var louvre_path = [
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
                var right_path = [
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
                var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS381(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 38.1;
            var rightStileWidth = 38.1;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
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
                var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_FS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
                "M", 0, 0,
                "L", 0, -27,
                "L", 50.8, -27,
                "L", 50.8, 0,
                "L", 0, 0
            ];
            // create louvre path and relocate next to left stile
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RFS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");   //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
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
            var louvre_path = [
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
                var right_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DFS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
                    "M", 0, 0,
                    "L", 0, -27,
                    "L", 50.8, -27,
                    "L", 50.8, 0,
                    "L", 0, 0
                ];
            } else {
                var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat) {
                var left_path = [
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
                var left_path = [
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
            var louvre_path = [
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
                var right_path = [
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
                var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS508(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
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
                var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_BS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_RBS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000");   //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8 - (leftFlat == false ? 6 : 0);
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
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
                var left_path = [
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
            var louvre_path = [
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
                var right_path = [
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
                var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        function drawPanelStile_DBS508PVC(rPaper, x, y, rotation, mirrorX, mirrorY, scale, panelWidth, leftFlat, rightFlat, relocationPos) {
            // drawCirle(rPaper, x, y, "FF0000"); //start of panel
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var leftStileWidth = 50.8;
            var rightStileWidth = 50.8;
            var louvreWidth = panelWidth - leftStileWidth - rightStileWidth;

            //create left stile path
            if (leftFlat == true) {
                var left_path = [
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
                var left_path = [
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
            var louvre_path = [
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
            var right_path = [
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
            var panel_path = [];
            panel_path.push.apply(panel_path, left_path);
            panel_path.push.apply(panel_path, louvre_path);
            panel_path.push.apply(panel_path, right_path);

            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, panel_path, x, y, rotation, scaleX, scaleY);
            }
            // line.attr({"stroke": "#0000FF"});
        };

        //////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////// POSTS ////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////

        function drawHalfPost_Post50(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 50,
                "L", 9.53, 50,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 50,
                        "L", 9.53, 50,
                        "L", 9.53, 50+buildoutHeight,
                        "L", -width/2, 50+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post70(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 0,
                "L", -width / 2, 0,
                "M", -width / 2, 70,
                "L", 9.53, 70,
                "L", 9.53, 30.5,
                "L", 0, 30.5,
                "L", 0, 0
            ];
            /*if (buildoutHeight && buildoutHeight > 0) {
                    var path_b = ["M", 0, 70,
                        "L", 9.53, 70,
                        "L", 9.53, 70+buildoutHeight,
                        "L", -width/2, 70+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post50PVC(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;
            //        scale = 2;
            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2,
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
                    var path_b = ["M", 0, 50,
                        "L", 9.53, 50,
                        "L", 9.53, 50+buildoutHeight,
                        "L", -width/2, 50+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_Post70PVC(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2,
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
                    var path_b = ["M", 0, 70,
                        "L", 9.53, 70,
                        "L", 9.53, 70+buildoutHeight,
                        "L", -width/2, 70+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_TPostBL90(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                    var path_b = ["M", 0, 90,
                        "L", 16, 90,
                        "L", 16, 90+buildoutHeight,
                        "L", -width/2, 90+buildoutHeight
                    ]
                    path_a = path_a.concat(path_b);
                }*/
            if (typeof relocationPos !== "undefined") {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");

        };

        function drawHalfPost_PostBL90(rPaper, pos, angle, mirrorX, mirrorY, scale, buildoutHeight) {
            var width = 25.4;

            var scaleX = scale * (mirrorX == true ? -1 : 1);
            var scaleY = scale * (mirrorY == true ? -1 : 1);

            var path_a = ["M", 0, 2.5,
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
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY, relocationPos);
            } else {
                var line = transform_and_draw_path(rPaper, path_a, pos.x, pos.y, angle, scaleX, scaleY);
            }
            // line.attr("stroke", "#0000FF");
        };

        //NOTE: controlType= "Centre Rod", "Centre Rod Split", "Clearview", "Clearview Split", "Off Centre Rod", "Off
        // Centre Rod Split",

        var paper1;

        var fitWidth = 0;
        var fitHeight = 0;


        function shutterConfig(nrSection) {
            var availableWidth = fitWidth;
            var availableHeight = fitHeight;

            var myShutter = {
                "buildoutHeight": getPropertyBuiltout(),
                "scale": 1,
                "frameType": getFrameTypeCode(),
                "frameTypeBottom": getFrameTypeCodeBottom(),
                "stileType": getStileCode(),
                "description": getShutterDescription(),
                "width": getPropertyWidth(nrSection),
                "height": getPropertyHeight(),
                "layoutCode": getPropertyLayoutCode(nrSection),
                "b_positions": getPropertyLayoutcodeExtra('bp', nrSection),
                "b_angles": getPropertyLayoutcodeExtra('ba'), nrSection,
                "c_positions": getPropertyLayoutcodeExtra('c'),
                "t_positions": getPropertyLayoutcodeExtra('t', nrSection),
                "g_positions": getPropertyLayoutcodeExtra('g', nrSection),
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
            var nr_individuals = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nr_individuals; i++) {
                var html_canvas = '<div id="canvas_container' + i + '" class="canvas_container" style="min-height: 500px;border: 1px solid #aaa;background-image: url(/wp-content/plugins/shutter-module/imgs/drawing_graph.png);"></div> <br/>'

                $('#outside_canvas').append(html_canvas);
            }
            setTimeout(function () {
                prepareShutterDrawingPaper();
            }, 2000);
        };

        function prepareShutterDrawingPaper() {
            var nrPapers = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nrPapers; i++) {
                var currentWidth = Math.floor($("#accordion").width());
                var height = Math.floor(currentWidth * 1.2);

                fitWidth = currentWidth - 130;
                fitHeight = height / 2.0 - 100;
                console.log('nrPapers ' + nrPapers);
                paper1 = new Raphael(document.getElementById('canvas_container' + i + ''), currentWidth, height);
                var shutter = shutterConfig(i);
                $("#drawingConfig").val(JSON.stringify(shutter, null, 2));

                if ($("#canvas_container" + i + " svg").length > 1) {
                    // console.log('delete svg length: ' + $("#canvas_container1 svg").length);
                    $("#canvas_container" + i + " > svg:first-child").remove();
                }
            }
        }

        //update the shutter drawing only if variables have changed
        function updateShutter() {
            var nrPapers = parseInt($("#property_nr_sections").val());
            for (var i = 1; i <= nrPapers; i++) {
                var currentWidth = Math.floor($("#canvas_container" + i + "").width());
                var height = Math.floor(currentWidth * 1.2);

                fitWidth = currentWidth - 230;
                fitHeight = height / 2.0 - 100;

                if ($("#canvas_container" + i + " svg").length > 0) {
                    $("#canvas_container" + i + " svg").remove();
                }

                var style = getStyleTitle();
                if (style.indexOf("Shaped") >= 0) {
                    $("#canvas_container" + i + "").html("&nbsp;Drawing not available for shaped shutters. Manual drawing will be issued.");
                    $(".print-drawing").hide();
                    return;
                } else if (style.indexOf("Tracked") >= 0) {
                    // $("#comments_customer").val("Please supply drawings to be confirmed");
                    $("#canvas_container" + i + "").html("&nbsp;Factory will suply drawings to be confirmed");
                    $("#canvas_container" + i + " svg").remove();
                    $(".print-drawing").hide();
                    return;
                } else {
                    // $("#comments_customer").val("");
                    $("#canvas_container1" + i + "").html("");
                    $(".print-drawing").show();
                }

                paper1 = new Raphael(document.getElementById('canvas_container' + i + ''), currentWidth, height);

                var shutter_new_json = shutterConfig(i);
                // console.log(shutter_new_json);
                var shutter_new_string = JSON.stringify(shutter_new_json, null, 2);

                //always redraw shutter because drawing is done on click
                $("#drawingConfig").val(shutter_new_string);
                drawing(paper1, shutter_new_json, true);

                var shutterScaled = scaleShutterConfig(shutter_new_json);
                $("#drawingConfigScaled").val(JSON.stringify(shutterScaled, null, 2));
            }
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
                // // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                // console.log("layoutCode.length i : " + i);
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
                            // scade din max Y shutter adica railheight, dimensiunea totHeight si ajungem pana unde
                            // este Tot in shutter

                            console.log(
                                'shutter.totHeight = ' + shutter.totHeight + '\n' +
                                'midRails[j] = ' + midRails[j] + '\n' +
                                'shutter.railHeight = ' + shutter.railHeight + '\n' +
                                'totPostWidth = ' + totPostWidth + '\n' +
                                'toHeight = ' + toHeight);
                        }
                        if (j - 1 >= 0 && midRails[j - 1] == shutter.totHeight) {
                            fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was totHeight
                            // increase fromHeight

                            console.log(
                                'shutter.totHeight = ' + shutter.totHeight + '\n' +
                                'midRails[j] = ' + midRails[j] + '\n' +
                                'shutter.railHeight = ' + shutter.railHeight + '\n' +
                                'totPostWidth = ' + totPostWidth + '\n' +
                                'fromHeight = ' + fromHeight);
                        }

                        [elem2, firstY, lastY] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter, j);

                        //[elemm2, firstYm2, lastYm2] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth,
                        // fromHeight, toHeight2, shutter);

                        if (style_check.indexOf('Café Style') >= 0) {
                            if (j % 2) {
                                if (j > 0 && (midRails[j] == shutter.totHeight || shutter.totHeight == 0)) {
                                    elem_midrail = drawPanelMidrail(rPaper, xPanel, previousFirstY, panelWidth, lastY, lastY - 12, shutter);
                                }
                            } else {
                                elem3 = false;
                                if (shutter.controlType != '') {
                                    elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                                }
                            }
                        } else if (style_check.indexOf('Combi') >= 0) {
                            if (!(j % 2)) {
                                if (j > 0 && (midRails[j] == shutter.totHeight || shutter.totHeight == 0)) {
                                    elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                                }
                            } else {
                                elem3 = false;
                                if (shutter.controlType != '') {
                                    elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                                }
                            }
                        } else {
                            if (j > 0 && (midRails[j] == shutter.totHeight || shutter.totHeight == 0)) {
                                elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                            }

                            elem3 = false;
                            if (shutter.controlType != '') {
                                elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);
                            }
                        }

                        // // Deseneaza midrail2
                        // if (j > 0 && (midRails2[j] == shutter.totHeight || shutter.totHeight == 0)) {
                        //     elem_midrail2 = drawPanelMidrail(rPaper, xPanel, lastYm2, panelWidth, lastYm2,
                        // previousFirstYm2, shutter); }

                        // elem3 = false;
                        // if (shutter.controlType != '') {
                        //     elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight,
                        // layoutCode[i], shutter); }

                        previousFirstY = firstY;
                        //previousFirstYm2 = firstYm2;
                        fromHeight = toHeight + shutter.railHeight;
                    }
                    xPanel += panelWidth;
                } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
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
            // for (var i = 0, len = layoutCode.length; i < len; i++) {
            //     console.log("layoutCode.length i 2 : " + i);
            //     var panelWidth = getPanelWidth(layoutCode, i, shutter);
            //     // // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
            //     if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
            //         elem1 = drawPanelStiles(rPaper, xPanel, yPanel, panelWidth, shutter.stileWidth, shutter.height,
            // layoutCode, i); // var rect = rPaper.rect(xPanel, yPanel, panelWidth, shutter.height); var fromHeight =
            // shutter.railHeight; for (var j = 0; j < midRails2.length; j++) { var toHeight = midRails2[j] -
            // shutter.railHeight / 2.0; if (midRails2[j] == shutter.totHeight) { toHeight -= shutter.railHeight / 2.0
            // - totPostWidth / 2.0; //if this midrail is totHeight increase toHeight } if (j - 1 >= 0 && midRails2[j -
            // 1] == shutter.totHeight) { fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was
            // totHeight increase fromHeight } // deseneaza linii [elem2, firstY, lastY] =
            // drawPanelLouvresMidrail2(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter);  //
            // Deseneaza midrail2 if (j > 0 && (midRails2[j] == shutter.totHeight || shutter.totHeight == 0)) {
            // elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter); }
            // elem3 = false; if (shutter.controlType != '') { //   elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter); }  previousFirstY = firstY; fromHeight = toHeight + shutter.railHeight; } xPanel += panelWidth; } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {  xPanel += shutter.postsWidth; } // drawSet = drawSet.concat(elem1); // drawSet = drawSet.concat(elem2); // if (typeof elemMidrail !== 'undefined') { //     drawSet = drawSet.concat(elemMidrail); // } // if (elem3) { //     drawSet = drawSet.concat(elem3); // }  }


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

        function drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter, k) {

            var drawSet = [];
            var height = toHeight - fromHeight;
            var louvreHeight = shutter.louvreHeight;
            if (louvreHeight == 0) louvreHeight = 10;

            var louvres = height / parseFloat(louvreHeight);
            var louvreWidth = panelWidth - 2 * shutter.stileWidth;
            var yLouvre = yPanel + shutter.height - toHeight;
            var firstYLouvre = yLouvre;

            if (style_check.indexOf('Combi') >= 0) {
                if (k % 2) {
                    console.log('drawPanelLouvres k ' + k);
                    louvres = height / parseFloat(louvreHeight);
                } else {
                    var louvres = height / parseFloat(louvreHeight);
                    louvreWidth = 0;
                }
            } else if (style_check.indexOf('Café Style') >= 0) {
                if (style_check.indexOf('Solid') >= 0) {
                    if (!(k % 2)) {
                        console.log('drawPanelLouvres k ' + k);
                        louvres = height / parseFloat(louvreHeight);
                        louvreWidth = 0;
                    } else {
                        louvres = 0;
                        louvreHeight = 0;
                    }
                } else {
                    if (!(k % 2)) {
                        console.log('drawPanelLouvres k ' + k);
                        louvres = height / parseFloat(louvreHeight);
                    } else {
                        louvres = 0;
                        louvreHeight = 0;
                    }
                }
            } else if (style_check.indexOf('Solid') >= 0) {
                louvreWidth = 0;
            } else {
                louvres = height / parseFloat(louvreHeight);
            }

            // console.log(
            //     'xPanel = '+xPanel+ '\n' +
            //     'yPanel = '+yPanel+ '\n' +
            //     'panelWidth = '+panelWidth+ '\n' +
            //     'toHeight = '+toHeight+ '\n' +
            //     'fromHeight = '+fromHeight+  '\n' +
            //     'height = toHeight - fromHeight = '+height+ '\n' +
            //     'louvreHeight = '+louvreHeight+ '\n' +
            //     'louvres = '+louvres+ '\n' +
            //     'louvreWidth = '+louvreWidth+ '\n' +
            //     'yLouvre = '+yLouvre+ '\n' +
            //     'firstYLouvre = '+firstYLouvre
            // );

            for (var j = 0; j < louvres; j += 1) {
                //  console.log('louvres j : '+ j);
                var xLouvre = xPanel + shutter.stileWidth;
                var widthLouvre = louvreWidth;

                if (louvreHeight > 0) {
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
            // // console.log("Shutter: "+x+", "+y+", "+shutter.width+", "+shutter.height);

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
            // // console.log("Object.prototype.toString.call(config) = "+Object.prototype.toString.call(config));
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
            // // console.log("scale = "+scale);

            var scaledShutter = jQuery.extend(true, {}, config); //deep copy object
            for (var propertyName in scaledShutter) {
                // // console.log("unscaled:: "+propertyName);
                if (propertyName != "at" && propertyName != "fit" && propertyName != "b_angles" && propertyName != "buildoutHeight") {
                    scaledShutter[propertyName] = scaleShutterConfig(scaledShutter[propertyName], scale);
                    // // console.log('scaled:: '+propertyName +" > "+scaledShutter[propertyName]);
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
            // // console.log("positionFrom: "+positionFrom);
            // // console.log("positionTo: "+positionTo);
            // // console.log("divideWith: "+divideWith);
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
        function drawRect(rPaper, x, y, width, height, left, top, right, bottom, size, color) {
            var left = typeof left !== 'undefined' ? left : true;
            var top = typeof top !== 'undefined' ? top : true;
            var right = typeof right !== 'undefined' ? right : true;
            var bottom = typeof bottom !== 'undefined' ? bottom : true;
            var size = size || "normal";
            // var color = typeof color !== 'undefined' ? color : "#FFFFFF";
            // var color = "#FF0000";
            // // console.log("color: "+color+" size: "+size);

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

            // // console.log("yLine x:"+x+" y:"+y+" height:"+height+" options.color:"+options.color+"
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
                // console.log('shutter.at.y ' + shutter.at.y);
                // console.log('shutter.height ' + shutter.height);
                // console.log('getPropertyTotHeight ' + shutter.totHeight);


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
                    // console.log($(this).val());
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

            if (frameType.indexOf('BL90') != -1 && (panelType == 'B' || panelType == 'C')) {
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
            if (panelType != 'T') {
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
                //            // console.log(pathType + " = " + path[i]);
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
        function getPropertyWidth(i) {
            return parseInt($("#property_width" + i + "").val());
        }

        function getPropertyHeight() {
            return parseInt($("#property_height").val());
        }

        function getPropertyLayoutCode(i) {
            return $("#property_layoutcode" + i + "").val();
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
            value = false;
            data = $("#property_frame" + position).select2('data');
            if (data && data.value) {
                if (data.value.indexOf("Yes") == 0 || data.value.indexOf("Sill") == 0) {
                    value = true;
                }
            }
            return value;
        }

        function getPropertyFramePositionText(position) {
            value = false;
            data = $("#property_frame" + position).select2('data');
            if (data && data.value) {
                value = data.value;
            }
            return value;
        }

        function getPropertyLayoutcodeExtra(extra_field, nrSection) {
            var i = 1;
            var data = [];
            while ($("#property_" + extra_field + i + "_" + nrSection).length > 0) {
                data.push(parseInt($("#property_" + extra_field + i + "_" + nrSection).val()));
                i++;
            }

            return data;
        }

        function getFrameImageInformation() {
            chosenFrameType = $('input[type="radio"]:checked', '#choose-frametype');
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
            room = '';
            if ($("#property_room").select2('data') && $("#property_room").select2('data').value != 'undefined') {
                room = $("#property_room").select2('data').value;
            }
            if (room.length > 0)
                room = room + ' - ';

            // return room + getPropertyLayoutCode().toUpperCase();
        }

        function getFrameTypeCode() {
            value = $('input[name="property_frametype"]:checked').val();
            material_id = $("#property_material").select2('data').id;
            material_code = getCodeByPropertyValueId(material_id);

            code = getCodeByPropertyValueId(value);
            if (material_code == 'upvc') {
                code = code + "PVC";
            }
            return code;
        }

        function getFrameTypeCodeBottom() {
            value = $('input[name="property_frametype"]:checked').val();
            material_id = $("#property_material").select2('data').id;
            material_code = getCodeByPropertyValueId(material_id);

            code = getCodeByPropertyValueId(value);
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
            value = $('input[name=property_stile]:checked').val();

            material_id = $("#property_material").select2('data').id;
            material_code = getCodeByPropertyValueId(material_id);

            code = getCodeByPropertyValueId(value);
            code = code.replace(".", "");
            code = code.replace(" ", "");
            if (material_code == 'upvc') {
                code = code + "PVC";
            }
            return code;
        }


        function getCodeByPropertyValueId(property_value_id) {
            code = '';
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
    });

})(jQuery);

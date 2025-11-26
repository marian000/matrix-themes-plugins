 //get the property code based on id of property eg: property with id 9 = property_fit
 jQuery.noConflict();
 (function ($) {
     $(function () {
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
                         selected_property_values = JSON.parse(property_values[i].selected_property_values)
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
             for (var i = 0; i < property_values.length; i++) {
                 if (property_values[i].property_id == property_id) {
                     data.push(property_values[i]);
                 }

             }
             return data;
         }

         //get field data whose value contains...
         function getFieldDataContains(property_id, contains, field_values) {
             data = [];
             field_values = typeof field_values !== 'undefined' ? field_values : property_values;
             console.log(field_values);
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
                 return parseFloat($("#property_bladesize").select2('data').value);
             } else {
                 return 0;
             }
         }

         function getPropertyMidrailheight() {
             var midrail = [];
             if ($("#property_midrailheight").val().length > 0) {
                 midrail[0] = parseFloat($("#property_midrailheight").val());
             }

             return midrail;
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
                 return null
             }
         }

         function getPropertyTotHeight() {
             var tot_height = 0;
             if ($("#property_totheight").val() != '') {
                 tot_height = parseInt($("#property_totheight").val());
             }
             return tot_height;
         }


         //When changing the attachment, check file size
         $('#attachment').on('change', function () {
             field = this;
             if (this.files && this.files[0]) {
                 if (this.files[0].size > 1024 * 1024) { //limit to 1MB
                     alert("The file is too big, choose a smaller one!");
                     $(this).val("");
                 }
             }
         });

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
             "value": "114mm",
             "created_at": "2016-01-18T17:15:44.000+00:00",
             "updated_at": "2016-05-18T17:35:18.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 164,
             "property_id": 8,
             "value": "47mm",
             "created_at": "2016-01-18T17:15:16.000+00:00",
             "updated_at": "2016-02-22T09:52:32.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 53,
             "property_id": 8,
             "value": "63mm",
             "created_at": "2015-09-07T20:05:51.000+01:00",
             "updated_at": "2018-03-02T00:29:12.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"292\"]}",
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
             "id": 54,
             "property_id": 8,
             "value": "76mm",
             "created_at": "2016-01-06T09:03:52.000+00:00",
             "updated_at": "2018-03-02T00:29:29.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\",\"292\"]}",
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
             "value": "89mm",
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
         }, {
             "id": 97,
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "id": 99,
             "property_id": 16,
             "value": "Centre Rod Split",
             "created_at": "2015-09-30T16:33:37.000+01:00",
             "updated_at": "2017-11-27T12:03:57.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "id": 96,
             "property_id": 16,
             "value": "Clearview",
             "created_at": "2015-09-26T01:28:40.000+01:00",
             "updated_at": "2015-09-26T01:28:40.000+01:00",
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
             "id": 95,
             "property_id": 16,
             "value": "Clearview Split",
             "created_at": "2015-09-26T01:28:11.000+01:00",
             "updated_at": "2015-09-30T16:31:24.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":[\"2\"]}",
             "all_property_values": true,
             "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
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
             "id": 223,
             "property_id": 16,
             "value": "Hidden Tilt",
             "created_at": "2017-07-21T02:51:01.000+01:00",
             "updated_at": "2017-11-27T12:04:38.000+00:00",
             "code": "",
             "uplift": "15.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "id": 224,
             "property_id": 16,
             "value": "Hidden Tilt Split",
             "created_at": "2017-07-21T02:51:15.000+01:00",
             "updated_at": "2017-11-27T12:04:28.000+00:00",
             "code": "",
             "uplift": "15.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "id": 98,
             "property_id": 16,
             "value": "Off Centre Rod",
             "created_at": "2015-09-26T01:29:43.000+01:00",
             "updated_at": "2017-11-27T12:04:18.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "id": 100,
             "property_id": 16,
             "value": "Off Centre Rod Split",
             "created_at": "2015-09-30T16:34:18.000+01:00",
             "updated_at": "2017-11-27T12:04:08.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"221\",\"227\",\"226\",\"222\",\"228\",\"31\",\"35\"]}",
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
         }, {
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
             "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"32\",\"218\",\"146\",\"30\",\"225\",\"29\",\"33\",\"221\",\"227\",\"226\",\"222\",\"228\",\"31\",\"35\"]}",
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
         }, {
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
             "selected_property_values": "{\"property_field\":\"7\",\"property_value_ids\":[\"33\"]}",
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
         }, {
             "id": 88,
             "property_id": 14,
             "value": "Lightblock",
             "created_at": "2015-09-07T21:25:06.000+01:00",
             "updated_at": "2016-03-03T15:22:31.000+00:00",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "id": 73,
             "property_id": 11,
             "value": "Lightblock",
             "created_at": "2015-09-07T21:25:06.000+01:00",
             "updated_at": "2016-03-03T15:22:17.000+00:00",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "id": 78,
             "property_id": 12,
             "value": "Lightblock",
             "created_at": "2015-09-07T21:25:06.000+01:00",
             "updated_at": "2016-03-03T15:22:21.000+00:00",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"144\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "id": 83,
             "property_id": 13,
             "value": "Lightblock",
             "created_at": "2015-09-07T21:25:06.000+01:00",
             "updated_at": "2016-03-03T15:22:26.000+00:00",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "selected_property_values": "{\"property_field\":\"10\",\"property_value_ids\":[\"171\",\"144\",\"142\",\"61\",\"62\",\"216\",\"59\",\"173\",\"141\",\"60\",\"150\",\"63\",\"143\",\"217\",\"67\",\"66\",\"64\",\"140\",\"65\"]}",
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
             "id": 217,
             "property_id": 10,
             "value": "U Channel",
             "created_at": "2017-04-04T08:41:45.000+01:00",
             "updated_at": "2017-04-04T09:14:34.000+01:00",
             "code": "UCHANNEL",
             "uplift": "0.0",
             "color": "",
             "all_products": false,
             "selected_products": "{\"product_ids\":[\"81\",\"82\",\"83\",\"84\"]}",
             "all_property_values": true,
             "selected_property_values": "{\"property_field\":null,\"property_value_ids\":null}",
             "graphic": "image",
             "image_file_name": "main_D50.png",
             "image_content_type": "image/png",
             "image_file_size": 53092,
             "image_updated_at": "2017-04-04T08:41:45.000+01:00",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "value": "Nickel",
             "created_at": "2016-02-25T09:16:36.000+00:00",
             "updated_at": "2016-04-01T23:26:38.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "value": "Stainless Steel",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 187,
             "property_id": 18,
             "value": "Aluminum",
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
         }, {
             "id": 139,
             "property_id": 18,
             "value": "BASSWOOD",
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
         }, {
             "id": 138,
             "property_id": 18,
             "value": "PAULOWNIA FSC",
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
         }, {
             "id": 172,
             "property_id": 20,
             "value": "-",
             "created_at": "2016-06-16T13:59:23.000+01:00",
             "updated_at": "2016-06-16T13:59:23.000+01:00",
             "code": "-",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"137\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
             "id": 101,
             "property_id": 17,
             "value": "TS01 White",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2018-02-08T10:49:01.000+00:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS02 Pearl",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS03 Alabaster",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS04 Swiss Coffee",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS05 Vivid White",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS06 Lexicon",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS07 Antique White",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS08 Whisper White",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS09 Chalk",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS10 Soft Lime",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS11 Rope",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS12 Worn Paper",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS13 Parisienne",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS14 Ash White",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS15 Soot",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS16 Shadowed",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS17 Urban Grey",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS18 Off Mortar",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS19 Olive",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS20 Faint Sky",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS21 Little Blue",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2015-10-01T18:11:03.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS22 Baltic (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:32:35.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS23 Red Wood (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:32:41.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS24 Walnut (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:32:19.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS25 Natural (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:32:30.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS26 Light Cedar (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:31:54.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS27 Dark Cedar (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:31:59.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS28 Teak (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:32:06.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS29 Oak (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:32:13.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS30 Fern (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:31:20.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS31 Branch (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:31:26.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS32 Bark (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:31:34.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS33 Limed White (+10%)",
             "created_at": "2015-10-01T18:11:03.000+01:00",
             "updated_at": "2016-04-01T23:31:40.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS34 Whitewash (+10%)",
             "created_at": "2016-01-20T11:49:59.000+00:00",
             "updated_at": "2016-04-01T23:31:47.000+01:00",
             "code": "",
             "uplift": "10.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS38 Faded Grey",
             "created_at": "2017-06-26T10:18:55.000+01:00",
             "updated_at": "2017-06-26T10:23:57.000+01:00",
             "code": "",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS40 White Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:44:03.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS41 Pearl Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:44:23.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS42 Alabaster Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:44:42.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS43 Swiss Coffee Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:44:48.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS44 Vivid White Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:44:52.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS45 Lexicon Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:44:57.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS46 Antique White Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:02.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS47 Whisper White Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:06.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS48 Chalk Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:10.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS49 Soft Lime Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:14.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS50 Rope Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:18.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS51 Worn Paper Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:22.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS52 Parisienne Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:31.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS53 Ash White Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-02T00:06:34.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS54 Soot Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:39.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS55 Shadowed Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:43.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS56 Urban Grey Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:49.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS57 Off Mortar Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:45:53.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS58 Olive Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:46:10.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS59 Faint Sky Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:46:14.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS60 Little Blue Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:46:18.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "value": "TS61 Faded Grey Grained (+20%)",
             "created_at": null,
             "updated_at": "2018-03-01T23:46:23.000+00:00",
             "code": "",
             "uplift": "20.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
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
             "id": 163,
             "property_id": 19,
             "value": "38.1mm Astragal Beaded",
             "created_at": "2016-01-06T07:10:07.000+00:00",
             "updated_at": "2016-02-19T09:52:48.000+00:00",
             "code": "DBS 38.1",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 162,
             "property_id": 19,
             "value": "38.1mm Astragal Flat",
             "created_at": "2016-01-06T07:09:53.000+00:00",
             "updated_at": "2016-02-19T09:52:59.000+00:00",
             "code": "DFS 38.1",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 160,
             "property_id": 19,
             "value": "38.1mm Butt Beaded",
             "created_at": "2016-01-06T07:09:21.000+00:00",
             "updated_at": "2016-02-19T09:53:08.000+00:00",
             "code": "BS 38.1",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 158,
             "property_id": 19,
             "value": "38.1mm Butt Flat",
             "created_at": "2016-01-06T07:08:53.000+00:00",
             "updated_at": "2016-02-19T09:53:17.000+00:00",
             "code": "FS 38.1",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 161,
             "property_id": 19,
             "value": "38.1mm Rebated Beaded",
             "created_at": "2016-01-06T07:09:38.000+00:00",
             "updated_at": "2016-02-19T09:53:26.000+00:00",
             "code": "RBS 38.1",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 159,
             "property_id": 19,
             "value": "38.1mm Rebated Flat",
             "created_at": "2016-01-06T07:09:06.000+00:00",
             "updated_at": "2016-02-19T09:53:34.000+00:00",
             "code": "RFS 38.1",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 157,
             "property_id": 19,
             "value": "50.8mm Astragal Beaded",
             "created_at": "2016-01-06T07:07:11.000+00:00",
             "updated_at": "2018-02-03T01:36:44.000+00:00",
             "code": "DBS 50.8",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
             "id": 156,
             "property_id": 19,
             "value": "50.8mm Astragal Flat",
             "created_at": "2016-01-06T07:06:54.000+00:00",
             "updated_at": "2016-02-19T10:48:59.000+00:00",
             "code": "DFS 50.8",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 154,
             "property_id": 19,
             "value": "50.8mm Butt Beaded",
             "created_at": "2016-01-06T07:06:18.000+00:00",
             "updated_at": "2018-02-03T01:37:00.000+00:00",
             "code": "BS 50.8",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"137\"]}",
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
             "id": 152,
             "property_id": 19,
             "value": "50.8mm Butt Flat",
             "created_at": "2016-01-06T07:05:36.000+00:00",
             "updated_at": "2016-02-19T10:48:43.000+00:00",
             "code": "FS 50.8",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 155,
             "property_id": 19,
             "value": "50.8mm Rebated Beaded",
             "created_at": "2016-01-06T07:06:37.000+00:00",
             "updated_at": "2016-01-21T18:14:24.000+00:00",
             "code": "RBS 50.8",
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
             "id": 153,
             "property_id": 19,
             "value": "50.8mm Rebated Flat",
             "created_at": "2016-01-06T07:05:56.000+00:00",
             "updated_at": "2016-02-19T10:48:51.000+00:00",
             "code": "RFS 50.8",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\"]}",
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
             "id": 218,
             "property_id": 7,
             "value": "Bay Window Individual Shutter",
             "created_at": "2017-05-15T09:04:03.000+01:00",
             "updated_at": "2017-05-19T13:10:09.000+01:00",
             "code": "individualshutter",
             "uplift": "0.0",
             "color": "",
             "all_products": true,
             "selected_products": "{\"product_ids\":null}",
             "all_property_values": false,
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
             "graphic": "image",
             "image_file_name": "Individual_Frames.jpg",
             "image_content_type": "image/jpeg",
             "image_file_size": 15197,
             "image_updated_at": "2017-05-19T13:09:24.000+01:00",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"137\"]}",
             "graphic": "image",
             "image_file_name": "Bay_Window_Cafe_Style_copy.jpg",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
             "graphic": "image",
             "image_file_name": "Solid-Panel-Raised-Cafe-Style-1.jpg",
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
             "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\"]}",
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
         }];
         var property_fields = [{
             "id": 6,
             "name": " Room",
             "created_at": "2015-09-07T18:46:17.000+01:00",
             "updated_at": "2015-09-22T11:55:40.000+01:00",
             "code": "room",
             "sort": 0,
             "help_text": "",
             "input_type": "select"
         }, {
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
         }];
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

         checkStyleTier();
         checkShutterType();

         if ($('input[name=property_frametype]').length == 1) {
             $('input[name=property_frametype]').first().closest('label').trigger('click');
         }
         $(".property-select").each(function () {
             var id = $(this).attr('id');
             var property_id = getPropertyIdByCode(id);
             values = getAllFieldData(property_id);
             loadItems(id, values);
             console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
         });

         calculateTotal();

         $('select').select2({
             dropdownAutoWidth: true
         });

         $("#property_width, #property_height").change(function () {
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
         });

         $("#property_material").change(function () {

             if ($("#property_material").select2('data')) {
                 product_title_check = $("#property_material").select2('data').value;
                 if (product_title_check.indexOf('PVC') > -1) {
                     property_id = getPropertyIdByCode('property_hingecolour');
                     for (i = 0; i < property_values.length; i++) {
                         if (property_values[i].property_id == property_id) {
                             //set default value only if there is not
                             if (property_values[i].value.indexOf('Stainless') > -1 && !$("#property_hingecolour").select2('data')) {
                                 $("#property_hingecolour").select2("val", property_values[i].id);
                             }
                         }
                     }
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
                 console.log("Loading to " + property_code + " data: " + field_data);


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
             console.log("Length: " + $("#choose-frametype label").filter(":visible").length);
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

             if ($("#canvas_container1").filter(":visible").length > 0) {
                 updateShutter();
             }
         });

         $(document).on("change", "input", function () {
             try {
                 if ($("#canvas_container1").filter(":visible").length > 0) {
                     updateShutter();
                 }
             } catch (err) {
                 console.log('Shutter data not ready yet' + err);
             }
         });

         $("#choose-style label").click(function (event, trigger_change) {
             if (typeof trigger_change === 'undefined') {
                 trigger_change = true;
             }

             if ($('input[name=property_style]:checked').length > 0) {
                 style_check = $('input[name=property_style]:checked').data('title');
                 if (style_check.indexOf('Bay Window') > -1) {
                     $(".property_fit").fadeIn();
                 } else {
                     $(".property_fit").fadeOut();
                 }
                 //set default value only if new record or empty value
                 if (style_check.indexOf('Bay ') > -1) {
                     $("#property_fit").select2("val", '57');
                     $(".property_fit").fadeIn();
                     //$("#property_fit").parent().parent().parent().show();
                 } else {
                     $("#property_fit").select2("val", '56');
                     $(".property_fit").fadeOut();
                 }


                 //if style is 'Shaped & French Cut Out', allow to upload a file for the shape
                 if (style_check.indexOf('Shaped') > -1) {
                     $("#shape-section").fadeIn();
                     $("#shape-section").addClass("required");
                 } else {
                     $("#shape-section").fadeOut();
                     $("#shape-section").removeClass("required");
                 }

                 checkStyleTier();

                 value = $('input[name=property_style]:checked').val();
                 field_id = getPropertyIdByCode('property_style');
                 style_related_fields = getRelatedFields(field_id);
                 console.log(style_related_fields);
                 for (var i = 0; i < style_related_fields.length; i++) {
                     field_data = getRelatedFieldData(style_related_fields[i], field_id, value);
                     property_code = getPropertyCodeById(style_related_fields[i]);
                     console.log("Loading to " + property_code + " data: " + field_data);
                     if ($("#" + "property_" + property_code).data('select2')) {
                         loadItems("property_" + property_code, field_data);
                     } else {
                         //filter only when track is selected
                         if (value == 35) {
                             var field_check = "property_" + property_code;
                             console.log("filtering " + field_check + ' from style');
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
                     if ($("#property_material").val() == '138' || $("#property_material").val() == '139')
                         $("#choose-frametype input[value=141]").closest("label").show();
                 }

                 //default values for tracked style
                 if ($("#order_product_id").val() == '') {
                     //default values for tracked style
                     if (style_check.indexOf('Tracked') > -1) {
                         //$("#property_frametype").select2("val", '68');
                         //$("#property_frametype").trigger('change'); //needed so that filtering will work correctly

                         $("#property_frameleft").select2("val", '73');
                         $("#property_frameright").select2("val", '78');
                         $("#property_frametop").select2("val", '135');
                         $("#property_framebottom").select2("val", '136');
                     }
                 }
             }
         });

         $("#choose-frametype label").click(function () {
             if ($('input[name=property_frametype]:checked').length > 0) {

                 value = $('input[name=property_frametype]:checked').val();
                 field_id = getPropertyIdByCode('property_frametype');
                 related_fields = getRelatedFields(field_id);
                 console.log(related_fields);
                 for (var i = 0; i < related_fields.length; i++) {
                     field_data = getRelatedFieldData(related_fields[i], field_id, value);
                     property_code = getPropertyCodeById(related_fields[i]);
                     console.log("Loading to " + property_code + " data: " + field_data);
                     if ($("#" + "property_" + property_code).data('select2')) {
                         loadItems("property_" + property_code, field_data);
                     } else {
                         //filter only when track is selected
                         var field_check = "property_" + property_code;
                         console.log("filtering " + field_check + ' from frametype');
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
                     $("#property_frameleft").select2("val", '70');
                     $("#property_frameright").select2("val", '75');
                     $("#property_frametop").select2("val", '80');
                     $("#property_framebottom").select2("val", '151');
                 }

                 if (value == 143) { //track in board
                     $("#property_frameleft").select2("val", '70');
                     $("#property_frameright").select2("val", '75');
                     $("#property_frametop").select2("val", '80');
                     $("#property_framebottom").select2("val", '136');
                 }

                 if (value == 144 || value == 143) {
                     $("#frame-left, #frame-right, #frame-top, #frame-bottom").hide();
                 } else {
                     $("#frame-left, #frame-right, #frame-top, #frame-bottom").show();
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
         });

         $("#property_frametype").change(function () {
             if ($("#order_product_id").val() == '' || !$("#property_frametop").select2("data")) {
                 style_check = getStyleTitle();
                 //default values for cafe style
                 if (style_check.indexOf('Café') > -1) {
                     $("#property_frametop").select2("val", '81');
                 }
             }
         });

         $("#property_midrailheight").change(function () {
             showMidrailPositionCritical();
         });

         $("#property_material, #attachment").change(function () {
             filterStiles();
         });

         $('.property-select').each(function () {
             $(this).trigger('change');
         });


         $("#property_layoutcode").on('keypress', function (event) {
             var charCode = event.which;
             if (!charCode) { // <-- charCode === 0
                 return; // return false, optionally
             }
             var character = String.fromCharCode(charCode).toUpperCase();
             if ("L" == character || "R" == character ||
                 "T" == character || "B" == character ||
                 "C" == character || event.keyCode == 8) {
                 return true;
             } else {
                 return false;
             }
         });

         function initFilterByProduct() {
             value = $("#product_id").val();

             related_fields = getRelatedFieldsByProduct();
             console.log(related_fields);
             for (var i = 0; i < related_fields.length; i++) {
                 field_data = getRelatedFieldDataByProductId(related_fields[i], value);
                 property_code = getPropertyCodeById(related_fields[i]);
                 console.log("Loading to " + property_code + " data: " + field_data);
                 loadItems("property_" + property_code, field_data);
             }
         }

         function filterStiles() {
             stile_id = getPropertyIdByCode('property_stile');
             style_check = getStyleTitle();
             stile_default = 155; //butt rebated 50.8
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


             if (parseFloat($("#property_height").val()) >= 1500 && style_check.indexOf('Tier') == -1) {
                 new_stile_data = getFieldDataContains(stile_id, '50.8', stile_data);
             } else {
                 new_stile_data = stile_data;
             }

             loadItems('property_stile', new_stile_data);
             if (!$("#property_stile").select2('data') || $("#property_stile").select2('data').id == 'undefined') {
                 $("#property_stile").select2("val", stile_default);
             }
         }

         function showShapeUploadFileAccordingToStyle() {
             //if 'Shaped & French Cut Out' style is chosen when configuration is loaded, show the upload file section
             if ($('input[name=property_style]:checked').length > 0) {
                 style_check = $('input[name=property_style]:checked').data('title');

                 //show the upload file section to allow uploading of a shape
                 if (style_check.indexOf('Shaped') > -1) {
                     $("#shape-section").show();
                     $("#shape-section").addClass("required");
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

             console.log('Checking product code: ' + product_code);
             console.log('With products' + names);
             for (var i = 0; i < names.length; i++) {
                 if (product_code == names[i].part_number) {
                     product_id = names[i].id;
                 }
             }

             $("#product_id").val(product_id);
         }

         //add new field based on layout code
         function addField(label, id, helptext) {
             var new_column_contents = $("#extra-column").html();
             var new_column = "<div class=\"pull-left extra-column\">" + new_column_contents + "</div>";
             var element = $(new_column);
             $(element).find(".extra-column-label").html(label);
             $(element).find("input").attr("id", id);
             $(element).find("input").addClass('required');
             $(element).find("input").attr("name", id);

             if (configuration[id] !== 'undefined') {
                 $(element).find("input").val(configuration[id]);
             }

             if ($(".extra-column").length > 0) {
                 $(element).insertAfter($(".extra-column").last());
             } else {
                 $(element).appendTo(".extra-columns-row div");
             }
         }

         function calculateTotal() {
             total = $("#property_width").val() * $("#property_height").val();
             if (total == 'NaN')
                 total = 0;
             $("#property_total").val(parseFloat(total) / parseFloat(1000000));

             //midrailheight required for >1800 height and NOT Tier styles
             style_check = getStyleTitle();
             if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                 $("#property_midrailheight").addClass("required");
                 //$("#midrail-height").show();
             } else {
                 $("#property_midrailheight").removeClass("required");
                 //$("#midrail-height").hide();
             }

             filterStiles();
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
                 if (product_title_check.indexOf('PVC') == -1 && $("#property_midrailheight").val() > 0) {
                     $("#midrail-position-critical").show();
                     $("midrail-position-critical input").removeClass('not-required')
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
             $("#property_controltype").closest('div').show();

             if ($('input[name=property_style]:checked').length > 0) {
                 style_check = $('input[name=property_style]:checked').data('title');
             } else {
                 return;
             }

             if ($("#order_product_id").val() == '' || !$("#property_frametop").select2("data")) {
                 if (style_check.indexOf('Café') > -1) {
                     $("#property_frametop").select2("val", '81');
                 }
             }

             if (style_check.indexOf('Tier') > -1) {
                 $(".tot-height").fadeIn();
                 $("#property_totheight").fadeIn();
                 $("#property_totheight").addClass("required");
                 $("#property_midrailheight").removeClass("required");
             } else if (style_check.indexOf('Shaped') > -1) {
                 $(".horizontal-t-post").fadeIn();
             } else {
                 $(".tot-height").fadeOut();
                 $("#property_horizontaltpost").prop('checked', false);
                 $("#property_totheight").val('');
                 $("#property_totheight").removeClass("required");
                 //if no tier style then add required to midrailheight if height>1800
                 if (parseFloat($("#property_height").val()) >= 1800 && parseFloat($("#property_height").val()) <= 3000 && style_check.indexOf('Tier') == -1) {
                     $("#property_midrailheight").addClass("required");
                 }
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
         /* submit checks */
         $("#add-product-single-form").submit(function (e) {
    //         e.preventDefault();
    // // serialize form THECON
	// var formser = jQuery('#add-product-single-form').serialize();
	// console.log(formser);
	// alert(formser);
	

	//     $.ajax({
	// 		method: "POST",
	// 		url: "/wp-content/themes/storefront/ajax/ajax-prod.php",
	// 		data: {
	// 			prod: formser
	// 		}
	// 	})
	// 	.done(function (data) {
	// 		console.log(data);
	// 		jQuery('.show-prod-info').html(data);
    //     });
        
        // END - serialize form THECON
	
             resetErrors();

             var check_height = parseFloat($("#property_height").val());

             //find any validation errors
             errors = 0;
             $(".select2-container").removeClass("error-field");
             $("input").removeClass("error-field");
             $(".error-text").remove();
             $("#nowarranty").hide();

             //check if property frametype is selected & if the selected value is visible
             //parent is used, because input is by default hidden, but the parent is not
             if ($('input[name=property_frametype]:checked').length == 0) {
                 errors++;
                 $("<span class=\"error-text\">Please select frame type</span>").insertBefore($("#choose-frametype"));
                 $("#choose-frametype").closest(".panel").find(".panel-collapse").collapse("show");
             }
             //check if select or input that are marked as required have values
             $("select.required, input.required").not('.not-required').each(function () {
                 if ($(this).val() == '' && $(this).is(":visible")) {
                     errors++;
                     addError($(this).attr('id'), 'Please fill in this field');
                 } else {
                     //also check if select2('data') has values for required select boxes, we need this check for filtered values
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
                     //also check if select2('data') has values for required select boxes, we need this check for filtered values
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

             //minimum height check
             if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) < 260) {
                 errors++;
                 error_text = 'Height should be at least 260mm';
                 addError("property_height", error_text);
             }

             max_height = 3000;
             //max height
             if ($("#property_height").val() != '' && parseFloat($("#property_height").val()) > max_height) {
                 errors++;
                 error_text = 'Height should not exceed ' + max_height.toString() + 'mm. ';
                 addError("property_height", error_text);
             }

             var nowarranty_checked = $("#property_nowarranty").prop("checked");
             if (errors == 0 && parseFloat($("#property_height").val()) > 1600 && !nowarranty_checked) {
                 $("#nowarranty").show();
                 showErrorModal("Width and height errors", "Please note, because your blind is over 1600 it will have small puncture holes within the blind");
                 return false;
             }

             //calculate min width based on the number of panels (Ls&Rs)
             min_width = 200;

             if ($("#property_width").val() != '' && parseFloat($("#property_width").val()) < min_width) {
                 errors++;
                 error_text = 'Width should be at least ' + min_width + 'mm. ';
                 addError("property_width", error_text);
             }

             //max width: 1600mm 
             max_width = 1600;
             if ($("#property_width").val() != '' && parseFloat($("#property_width").val()) > max_width) {
                 error_text = 'Width should be at most ' + max_width + 'mm.';
                 errors++;
                 addError("property_width", error_text);
             }

             if ($("#canvas_container1 svg").length > 0) {
                 $("#shutter_svg").html($("#canvas_container1").html());
             }

             if (errors > 0) {
                 return false;
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
             if ($('input[name=property_frametype]:checked').length > 0) {
                 $('input[name=property_frametype]:checked').closest('label').trigger('click');
             }

             $(".frames #property_frameleft").prop("readonly", true);
             $(".frames #property_frameright").prop("readonly", true);
             $(".frames #property_frametop").prop("readonly", true);
             $(".frames #property_framebottom").prop("readonly", true);
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


         //NOTE: controlType= "Centre Rod", "Centre Rod Split", "Clearview", "Clearview Split", "Off Centre Rod", "Off Centre Rod Split",

         var paper1;

         var fitWidth = 0;
         var fitHeight = 0;

         function shutterConfig() {
             var availableWidth = fitWidth;
             var availableHeight = fitHeight;

             var myShutter = {
                 "buildoutHeight": getPropertyBuiltout(),
                 "scale": 1,
                 "frameType": 'F50',
                 "frameTypeBottom": getFrameTypeCodeBottom(),
                 "stileType": 'FS508',
                 "description": getShutterDescription(),
                 "width": getPropertyWidth(),
                 "height": getPropertyHeight(),
                 "layoutCode": 'L',
                 "b_positions": getPropertyLayoutcodeExtra('bp'),
                 "b_angles": getPropertyLayoutcodeExtra('ba'),
                 "c_positions": getPropertyLayoutcodeExtra('c'),
                 "t_positions": getPropertyLayoutcodeExtra('t'),
                 "louvreHeight": getPropertyBladesize(),
                 "totHeight": getPropertyTotHeight(),
                 "totPostChecked": getPropertyHorizontaltpost(),
                 "midrails": [], //set distance from bottom for each
                 "controlType": getPropertyControltype(),
                 "splitHeight": [],
                 "secondSplitHeight": [], //use array [from(midrail), to(splitHeight)] eg. [400, 600]
                 "stileWidth": getPropertyStile(),
                 "frameLeft": getPropertyFramePosition("left"),
                 "frameRight": getPropertyFramePosition("right"),
                 "frameTop": getPropertyFramePosition("top"),
                 "frameBottom": getPropertyFramePosition("bottom"),
                 "frameSize": 100,
                 "frameImage": getFrameImageInformation(),
                 "postsWidth": 30,
                 "railHeight": 110,
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

             /*var currentWidth = Math.floor($("#accordion").width());
             var height = Math.floor(currentWidth * 1.2);

             fitWidth = currentWidth - 130;
             fitHeight = height/2.0 - 100;
             paper1 = new Raphael(document.getElementById('canvas_container1'), currentWidth, height);

             var shutter = shutterConfig();

             $("#drawingConfig").val( JSON.stringify(shutter, null, 2) );*/

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

             svg = $("#canvas_container1 svg")[0];
             bbox = svg.getBBox();
             canvas_width = bbox.x + bbox.width + 20;
             canvas_height = bbox.y + bbox.height + 20;
             //$("#canvas_container1").width( canvas_width  + "px");
             $(svg).width(canvas_width + "px");
             $("#canvas_container1").height(canvas_height + "px");
         }


         function shutterInit(shutter) {
             if (shutter.midrails == null) {
                 shutter.midrails = [];
             }
         };

         function drawPanels(rPaper, x, y, shutter) {
             var drawSet = [];
             var elem1 = null;
             var elem2 = null;
             var layoutCode = shutter.layoutCode.match(/[lrtbcLRTBC]+/g).join('').toUpperCase();

             var xPanel = x;
             var yPanel = y;
             var totPostWidth = (shutter.totPostChecked ? shutter.frameSize : 1);

             var midRails = shutter.midrails.slice(); // copy array
             midRails.push(shutter.height - shutter.railHeight / 2.0);
             if (shutter.totHeight != null && shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                 midRails.push(shutter.totHeight);
             }
             midRails.sort(sortNumber);
             for (var i = 0, len = layoutCode.length; i < len; i++) {
                 var panelWidth = getPanelWidth(layoutCode, i, shutter);
                 // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                 if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                     elem1 = drawPanelStiles(rPaper, xPanel, yPanel, panelWidth, shutter.stileWidth, shutter.height, layoutCode, i);
                     // var rect = rPaper.rect(xPanel, yPanel, panelWidth, shutter.height);
                     var fromHeight = shutter.railHeight;
                     for (var j = 0; j < midRails.length; j++) {
                         var toHeight = midRails[j] - shutter.railHeight / 2.0;
                         if (midRails[j] == shutter.totHeight) {
                             toHeight -= shutter.railHeight / 2.0 - totPostWidth / 2.0; //if this midrail is totHeight increase toHeight
                         }
                         if (j - 1 >= 0 && midRails[j - 1] == shutter.totHeight) {
                             fromHeight += shutter.railHeight + totPostWidth / 2.0; //if last midrail was totHeight increase fromHeight
                         }

                         [elem2, firstY, lastY] = drawPanelLouvres(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, shutter);

                         if (j > 0 && (midRails[j] == shutter.totHeight || shutter.totHeight == 0)) {
                             elem_midrail = drawPanelMidrail(rPaper, xPanel, lastY, panelWidth, lastY, previousFirstY, shutter);
                         }

                         elem3 = drawPanelRod(rPaper, xPanel, yPanel, panelWidth, fromHeight, toHeight, layoutCode[i], shutter);

                         previousFirstY = firstY;
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
                 drawSet = drawSet.concat(elem3);

             }
             // Draw ToT post
             if (shutter.totHeight >= shutter.railHeight && shutter.totHeight <= shutter.height - shutter.railHeight) {
                 var yToT = shutter.at.y + shutter.height - shutter.totHeight - totPostWidth / 2;
                 //            drawSet.push( drawRect(rPaper, shutter.at.x, yToT, shutter.width, totPostWidth, true, true, true, true)  );
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
             var louvres = height / parseFloat(shutter.louvreHeight);
             var louvreWidth = panelWidth - 2 * shutter.stileWidth;
             var yLouvre = yPanel + shutter.height - toHeight;
             var firstYLouvre = yLouvre;

             for (var j = 0; j < louvres; j += 1) {
                 var xLouvre = xPanel + shutter.stileWidth;
                 var widthLouvre = louvreWidth;
                 var heightLouvre = shutter.louvreHeight;
                 louvre = drawRect(rPaper, xLouvre, yLouvre, widthLouvre, heightLouvre);
                 //louvre.attr("stroke-width", "2"); //make the line look stronger
                 drawSet.push(louvre);
                 yLouvre += shutter.louvreHeight;
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
             if (shutter.controlType != null && !(shutter.controlType.substring(0, "Clearview".length) === "Clearview")) {
                 var rodWidth = 3;
                 var rodDistanceUp = 5;
                 var splitDistance = shutter.louvreHeight;
                 var rodHeight = toHeight - fromHeight;
                 var louvres = rodHeight / parseFloat(shutter.louvreHeight);
                 if (louvres > 0) {
                     var actualHeight = Math.ceil(louvres) * shutter.louvreHeight;
                     var louvreWidth = panelWidth - 2 * shutter.stileWidth;
                     var leftDistance = louvreWidth / 2.0; // "Centre Rod" is at the center
                     if (shutter.controlType.substring(0, "Off Centre Rod".length) === "Off Centre Rod") { //starts with "Off Centre Rod"
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
                 shutter.frameSize, true);

             return [rectPanel];
         };

         function drawHeader(rPaper, shutter) {
             if (shutter.description) {
                 rPaper.text(shutter.at.x - shutter.frameSize / 2, 33, shutter.description).attr({
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
             var maxYpos = 5;
             shutter.at.y = maxYpos + 100;
             maxYpos += 500 + shutter.height;
             $("#canvas_container1 svg").attr("height", (maxYpos));
             drawShutter(rPaper, shutter);
             drawRulers(rPaper, shutter, shutterConfig);
             drawFrameImage(rPaper, shutter);
             //drawSideView(rPaper, shutter);
         }

         /*/////////////////////////////////////////////////////////////////////// */
         /*//////////////////////////// SCALING ////////////////////////////////// */
         function scaleShutterConfig(config, scale) {
             if (typeof scale == 'undefined') {
                 var scale = getShutterScale(config);
             }
             if (config == null) {
                 return null
             };
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
             if ("BCT".indexOf(layoutCode[index]) >= 0) {
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
                 if ("BCT".indexOf(layoutCode[i]) >= 0) {
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
                 positionFrom += shutter.postsWidth / 2.0;
             }
             return positionFrom;
         };

         function getPanelSpacePositionTo(layoutCode, index, shutter) {
             var bctPostIndex = -1;
             var b_posts_after = 0;
             var c_posts_after = 0;
             var t_posts_after = 0;
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
                 if ("BCT".indexOf(layoutCode[i]) >= 0) {
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
                 positionTo -= shutter.postsWidth / 2.0;
             }
             return positionTo;
         };

         function getPanelSpaceDivideWith(layoutCode, index, shutter) {
             var lr_count_before = 0;
             for (var i = index - 1; i >= 0; i--) {
                 if ("BCT".indexOf(layoutCode[i]) >= 0) {
                     break;
                 }
                 lr_count_before++;
             }
             var lr_count_after = 0;
             for (var i = index + 1; i < layoutCode.length; i++) {
                 if ("BCT".indexOf(layoutCode[i]) >= 0) {
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
                 if (color !== "undefined") {
                     line.attr("fill", "#f2f2f2");
                 }

                 var line = rPaper.path(["M", x, y, //M for move, L for line
                     "L", x, y + (height * 2 / 3),
                     "L", x + width, y + (height * 2 / 3),
                     "L", x + width, y,
                     "L", x, y
                 ]);
                 line.attr("stroke-width", "2"); //make the line look stronger
                 if (color !== "undefined") {
                     line.attr("fill", "#111111");
                 }

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
                 skipLine: false //skip line drawing, draw text only
             };
             options = jQuery.extend(true, defaultOptions, options);

             if (options.skipLine == false) {
                 // console.log("xLine x:"+x+" y:"+y+" width:"+width+" options.color:"+options.color+" options.text:"+options.text);
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
                 rPaper.text(x + width / 2.0, y + 6, options.textDown);
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

             // console.log("yLine x:"+x+" y:"+y+" height:"+height+" options.color:"+options.color+" options.text:"+options.text);
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
             var layoutCode = shutter.layoutCode.match(/[lrtbcLRTBC]+/g).join('').toUpperCase();

             var xFrom = x;
             var xPanel = x;
             var totalWidth = 0;
             var scaledTotalWidth = 0;
             for (var i = 0, len = layoutCode.length; i < len; i++) {
                 var scaledPanelWidth = getPanelWidth(layoutCode, i, shutter);
                 if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                     drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                         skipLine: true,
                         textUp: ''
                     }); //removed layout code text
                     scaledTotalWidth += scaledPanelWidth;
                 } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                     var currentPostPosition = getPostPosition(layoutCode, i, originalShutter);
                     var panelsSpace = currentPostPosition - totalWidth;
                     drawXLine(rPaper, xPanel, y, scaledPanelWidth, {
                         skipLine: true,
                         textUp: layoutCode[i]
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
             rPaper.image(imagePath, x, y, 100, 100);
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
             //        console.log(scale);
             var layoutCode = shutter.layoutCode.match(/[lrtbcLRTBC]+/g).join('').toUpperCase();
             var middleLR = findMiddleLRIndex(layoutCode, shutter);
             var widthMiddleLR = widthBeforeMiddleLR(layoutCode, middleLR, shutter);
             var positions = [];

             var b_pos = findMiddleBIndex(layoutCode, middleLR) + 1;
             var pos = {
                 x: x + widthMiddleLR,
                 y: y,
                 angle: 180
             }
             positions.push(pos);

             //draw right panels
             var angle;
             for (var i = middleLR, len = layoutCode.length; i < len; i++) {
                 var panelWidth = getPanelWidth(layoutCode, i, shutter);
                 var angle = 180;
                 // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                 if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                     var newPos = drawTopPanel_LR(rPaper, pos, panelWidth, angle, false, shutter, scale, i);
                     pos = newPos;
                 } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                     angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                     angle = (layoutCode[i] == 'C') ? 90 : angle;
                     var newPos = drawTopPanelPost(rPaper, pos, angle, false, scale, layoutCode[i], shutter.frameType, shutter.buildoutHeight);
                     pos = newPos;
                     b_pos += (layoutCode[i] == 'B') ? 1 : 0;
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
             }

             for (var i = middleLR - 1; i >= 0; i--) {
                 var panelWidth = getPanelWidth(layoutCode, i, shutter);
                 var angle = 180;
                 // console.log("Panel: "+xPanel+", "+yPanel+", "+panelWidth+", "+shutter.height);
                 if (layoutCode[i] == 'L' || layoutCode[i] == 'R') {
                     var newPos = drawTopPanel_LR(rPaper, pos, panelWidth, 180, true, shutter, scale, i);

                     pos = newPos;
                 } else if (layoutCode[i] == 'B' || layoutCode[i] == 'C' || layoutCode[i] == 'T') {
                     angle = (layoutCode[i] == 'B') ? shutter.b_angles[b_pos] : angle;
                     angle = (layoutCode[i] == 'C') ? 90 : angle;
                     var newPos = drawTopPanelPost(rPaper, pos, angle, true, scale, layoutCode[i], shutter.frameType, shutter.buildoutHeight);
                     pos = newPos;
                     b_pos -= (layoutCode[i] == 'B') ? 1 : 0;
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

             if (frameType.indexOf('PVC') > 0 && panelType == 'T') {
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
             var layoutCode = shutter.layoutCode.match(/[lrtbcLRTBC]+/g).join('').toUpperCase();
             if (layoutIndex <= 0 || "BCT".indexOf(layoutCode[layoutIndex - 1]) >= 0) {
                 return true;
             }
             return false;
         };

         function isRightStileFlat(shutter, layoutIndex) {
             var layoutCode = shutter.layoutCode.match(/[lrtbcLRTBC]+/g).join('').toUpperCase();
             if (layoutIndex >= layoutCode.length - 1 || "BCT".indexOf(layoutCode[layoutIndex + 1]) >= 0) {
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

         function drawSideView(rPaper, shutter) {
             var x_pos = shutter.width + 100 + 100 * shutter.scale;
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
                 if ("MLCZ".indexOf(path[i]) >= 0) {} else { // we have a coordinate x or y
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
             if ($("#property_stile").select2('data')) {
                 return parseFloat($("#property_stile").select2('data').value);
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
             chosenFrameType = $('input[type="radio"]:checked', '#choose-frametype');
             if (chosenFrameType.size() > 0) {
                 return {
                     "text": chosenFrameType.parent().text().trim(),
                     "path": '' + chosenFrameType.next().attr('src')
                 };
             } else {
                 return null;
             }
         }

         function getShutterDescription() {
             room = '';
             if ($("#property_room").val() == '94') {
                 room = $("#property_room_other").val();
             } else {
                 if ($("#property_room").select2('data') && $("#property_room").select2('data').value != 'undefined') {
                     room = $("#property_room").select2('data').value;
                 }
             }

             return room;
         }

         function getFrameTypeCode() {
             value = $('input[name=property_frametype]:checked').val();
             material_id = $("#property_material").select2('data').id;
             material_code = getCodeByPropertyValueId(material_id);

             code = getCodeByPropertyValueId(value);
             if (material_code == 'upvc') {
                 code = code + "PVC";
             }
             return code;
         }

         function getFrameTypeCodeBottom() {
             value = $('input[name=property_frametype]:checked').val();
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
             value = $("#property_stile").select2('data').id;

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

     });
 })(jQuery);
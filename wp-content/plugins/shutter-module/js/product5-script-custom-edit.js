jQuery.noConflict();
(function ($) {
    $(function () {


            // ========== START - customize some properties by user =========
            var idCustomer = null;
            var idDealer = null;
            var selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\",\"6\"]}";

            idCustomer = jQuery('input[name="customer_id"]').val();
            idDealer = jQuery('input[name="dealer_id"]').val();

            // "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"188\"]}",
            if (idCustomer == 274 || idDealer == 274) {
                selectedPropertyValuesEcowood = "{\"property_field\":\"18\",\"property_value_ids\":[\"188\",\"6\"]}"
            }
            // console.log('idCustomer ', idCustomer);
            // console.log('selectedPropertyValuesEcowood ', selectedPropertyValuesEcowood);


            var showBiowood = {};
            // console.log('Show Biowood:', my_showBiowood_object.showBiowood);
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


            function format(item) {
                var row = item.value;
                if (item.image_file_name !== 'undefined' && item.image_file_name !== null) {
                    row = "<span><img src='/uploads/property_values/images/" + item.id + "/thumb_" + item.image_file_name + "' height='44' width='44' /> " + row + "</span>";
                }

                return row;
            };

            var names = [{
                "id": 40,
                "name": "Batten",
                "description": "",
                "part_number": "Batten",
                "is_active": true,
                "status_id": 1,
                "category_id": 1,
                "promote_category": false,
                "promote_front": false,
                "price1": null,
                "price2": null,
                "price3": null,
                "created_at": "2015-09-30T13:12:52.000+01:00",
                "updated_at": "2015-09-30T13:20:41.000+01:00",
                "image_file_name": "batten.jpg",
                "image_content_type": "image/jpeg",
                "image_file_size": 5665,
                "image_updated_at": "2015-09-30T13:18:36.000+01:00",
                "old_id": null,
                "minimum_quantity": "0.0",
                "product_type": "Batten",
                "vat_class_id": 1
            }];

            var property_values = [
                {
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"138\",\"5\",\"139\",\"187\",\"188\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\"]}",
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
                    "id": 5,
                    "property_id": 18,
                    "value": "Ecowood Plus",
                    "created_at": "2016-09-05T19:55:06.000+01:00",
                    "updated_at": "2016-09-05T19:55:06.000+01:00",
                    "code": "ecowoodPlus",
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
                    "id": 139,
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
                },
                showBiowood,
                {
                    "id": 138,
                    "property_id": 18,
                    "value": "Biowood Plus",
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
                // {
                //     "id": 137,
                //     "property_id": 18,
                //     "value": "Green",
                //     "created_at": "2015-10-19T20:31:42.000+01:00",
                //     "updated_at": "2015-10-19T20:31:42.000+01:00",
                //     "code": "upvc",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"187\",\"139\",\"138\",\"5\",\"137\"]}",
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
                }
                , {
                    "id": 415,
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
                    "id": 414,
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "value": "LS 602 SILK WHITE",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2015-10-01T18:11:03.000+01:00",
                    "code": "",
                    "uplift": "0.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "value": "LS 910 JET BLACK",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2015-10-01T18:11:03.000+01:00",
                    "code": "",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "value": "LS 108 RUSTIC GREY",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2015-10-01T18:11:03.000+01:00",
                    "code": "",
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
                    "value": "LS 109 WEATHERED TEAK",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:32:35.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 110 CHIQUE WHITE",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:32:41.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 114 TAUPE",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:32:19.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 202 GOLDEN OAK",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:32:30.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 204 OAK MANTEL",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:31:54.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 205 GOLDENROD",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:31:59.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 211 CHERRY",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:32:06.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 212 DARK TEAK",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:32:13.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 214 COCOA",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:31:20.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 215 CORDOVAN",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:31:26.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 219 MAHOGANY",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:31:34.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 220 NEW EBONY",
                    "created_at": "2015-10-01T18:11:03.000+01:00",
                    "updated_at": "2016-04-01T23:31:40.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 221 BLACK WALNUT",
                    "created_at": "2016-01-20T11:49:59.000+00:00",
                    "updated_at": "2016-04-01T23:31:47.000+01:00",
                    "code": "",
                    "uplift": "10.0",
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
                    "value": "LS 227 RED OAK",
                    "created_at": "2017-06-26T10:18:55.000+01:00",
                    "updated_at": "2017-06-26T10:23:57.000+01:00",
                    "code": "",
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
                    "value": "LS 229 RICH WALNUT",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:44:03.000+00:00",
                    "code": "",
                    "uplift": "20.0",
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
                    "value": "LS 230 OLD TEAK",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:44:23.000+00:00",
                    "code": "",
                    "uplift": "20.0",
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
                    "value": "LS 232 RED MAHOGANY",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:44:42.000+00:00",
                    "code": "",
                    "uplift": "20.0",
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
                    "value": "LS 237 WENGE",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:44:48.000+00:00",
                    "code": "",
                    "uplift": "20.0",
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
                    "value": "LS 862 FRENCH OAK",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:44:52.000+00:00",
                    "code": "",
                    "uplift": "20.0",
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
                    "value": "A103 (PEARL)",
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
                    "value": "A202 (LIGHT CEDAR)",
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
                    "value": "A203 (GOLDEN OAK )",
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
                    "value": "P601 WHITE BRUSHED (+20%)",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:45:22.000+00:00",
                    "code": "",
                    "uplift": "20.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "value": "P615 CLASSICAL WHITE BRUSHED (+20%)",
                    "created_at": null,
                    "updated_at": "2018-03-01T23:45:49.000+00:00",
                    "code": "",
                    "uplift": "20.0",
                    "color": "",
                    "all_products": true,
                    "selected_products": "{\"product_ids\":null}",
                    "all_property_values": false,
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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
                    "selected_property_values": "{\"property_field\":\"18\",\"property_value_ids\":[\"139\",\"138\",\"5\",\"137\"]}",
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

            data = [];

            for (i = 0; i < property_values.length; i++) {
                if (property_values[i].all_property_values == 0) {
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
                    // console.log('data: ' + data);
                }
            }

            initfunction();

            setTimeout(function () {

                $('input[name="property_material"]').val();
                $('input[name="property_material"]').trigger('change');

            }, 1000);

            function initfunction() {
                var selectedMaterialId = $('input[name="property_material"]').val();

                // Check if the selected material is Basswood (id: 139)
                // if (selectedMaterialId == 139) {
                //     loadColorsForBasswood();
                // }

                // Existing code to handle other initialization logic...
                // Loop over each set of inputs
                const id = $('input[name="property_material"]').attr('name').replace(/_\d+$/, '');
                console.log('init id: ', id);
                let field_id = getPropertyIdByCode(id);
                console.log('init field_id: ', field_id);
                let related_fields = getRelatedFields(field_id);
                console.log('init related_fields: ', related_fields);


                let field_data;
                let property_code;
                for (var i = 0; i < related_fields.length; i++) {
                    field_data = getRelatedFieldData(related_fields[i], field_id, 139);
                    console.log('field_data: ', field_data);
                    property_code = getPropertyCodeById(related_fields[i]);
                    //// console.log("Loading to " + property_code + " data: " + field_data);


                    if ($('input[name^="property_' + property_code + '"]').data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        var field_check = "property_" + property_code;
                        $('input[name^=' + field_check + ']').each(function () {
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


                //after filtering if style is checked (selected) we need to apply some filters again
                if ($(this).attr('name') == 'property_material' && $('input[name="property_style_1"]:checked').length > 0) {
                    $('input[name^="property_style_"]:checked').trigger('click', false);
                }

                if ($('input[name="property_material"]').select2('data')) {
                    product_title_check = $('input[name="property_material"]').select2('data').value;
                }
            }

            $(".property-select").each(function () {
                // Get the id attribute of the current element
                var id = $(this).attr('id');
                console.log('this: ', this);

                // Check if the id is undefined
                if (typeof id === 'undefined') {
                    return;
                }

                // Get the property_id based on the id (or first option value)
                var property_id = getPropertyIdByCode(id);

                // Get all field data for the property_id
                var values = getAllFieldData(property_id);

                // Load items based on the id and values
                loadItems(id, values);

                // Log the loaded values for debugging
                console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
            });

            jQuery('#add-batten').click(function () {
                setTimeout(function () {
                    // Loop over each set of inputs

                    $(".property-select").each(function () {
                        // var id = $(this).attr('name').replace(/_\d+$/, '');
                        var id = $(this).attr('id');
                        console.log('select id: ' + id);

                        var property_id = getPropertyIdByCode(id);
                        values = getAllFieldData(property_id);
                        loadItems(id, values);
                        console.log("Loaded values for element with id: " + id + " and property_id: " + property_id);
                    });

                }, 300);
            });


            $(document).on('change', '.property-select', function () {
                calculateTotal();

                const id = $(this).attr('name').replace(/_\d+$/, '');
                console.log('id: ', id);
                let field_id = getPropertyIdByCode(id);
                console.log('field_id: ', field_id);
                let related_fields = getRelatedFields(field_id);
                console.log('related_fields: ', related_fields);

                let field_data;
                let property_code;
                for (var i = 0; i < related_fields.length; i++) {
                    field_data = getRelatedFieldData(related_fields[i], field_id, $(this).val());
                    property_code = getPropertyCodeById(related_fields[i]);
                    //// console.log("Loading to " + property_code + " data: " + field_data);


                    if ($('input[name^="property_' + property_code + '"]').data('select2')) {
                        loadItems("property_" + property_code, field_data);
                    } else {
                        var field_check = "property_" + property_code;
                        $('input[name^=' + field_check + ']').each(function () {
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


                //after filtering if style is checked (selected) we need to apply some filters again
                if ($(this).attr('name') == 'property_material' && $('input[name="property_style_1"]:checked').length > 0) {
                    $('input[name^="property_style_"]:checked').trigger('click', false);
                }

                if ($('input[name="property_material"]').select2('data')) {
                    product_title_check = $('input[name="property_material"]').select2('data').value;
                }

                if ($("#canvas_container1").filter(":visible").length > 0) {
                    updateShutter();
                }

            });


            $(".property-select").css('width', '100%');

            jQuery('input[name^="property_width"], input[name^="property_height"], input[name^="property_depth"]').on('change', function () {
                // Your code here
                console.log('change with ^ works');
                calculateTotal();
            });


            $('input[name="batten_type"]').change(function () {
                console.log('change type');
                calculateTotal();
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

            $("#property_room").change(function () {
                if ($(this).val() == '94') {
                    $("#room-other").fadeIn();
                    $("#room-other input").addClass('required');
                } else {
                    $("#room-other").fadeOut();
                    $("#room-other input").removeClass('required');
                }
            });

        /**
         * Calculează volumul total în metri cubi pe baza dimensiunilor introduse.
         * Ține cont de valorile minime pentru diferite materiale și actualizează câmpul de total în formular.
         *
         * Problemele corectate:
         * 1. Eliminată bucla pentru secțiuni care nu mai există
         * 2. Folosește jQuery consecvent
         * 3. Verifică dacă dimensiunile sunt valide (NaN)
         * 4. Utilizează Math.max() pentru calcul minim mai clar
         */
        function calculateTotal() {
            console.log('calculateTotal - start');

            // Parsează înălțimea, lățimea și adâncimea din inputurile formularului ca numere cu virgulă mobilă
            var height = parseFloat($('input[name="property_height"]').val());
            var width = parseFloat($('input[name="property_width"]').val());
            var depth = parseFloat($('input[name="property_depth"]').val());

            // Verificăm dacă dimensiunile sunt valide
            if (isNaN(height) || isNaN(width) || isNaN(depth)) {
                console.log('ATENȚIE: Dimensiuni invalide!');
                console.log('height:', height, 'width:', width, 'depth:', depth);
                $('input[name="property_total"]').val('0.0000000');
                return;
            }

            console.log('Dimensiuni - height: ' + height + ', width: ' + width + ', depth: ' + depth);

            // Obține ID-ul materialului selectat folosind jQuery consecvent
            var material_id = parseInt($('input[name="property_material"]').val());
            console.log('ID material selectat: ' + material_id);

            // Determină valoarea minimă bazată pe material
            var minValue = (material_id == 188 || material_id == 137) ? 3 : 5;
            console.log('Valoare minimă pentru acest material: ' + minValue);

            // Ajustează dimensiunile la valorile minime dacă este necesar
            // Folosește Math.max pentru o abordare mai clară
            height = Math.max(height, minValue);
            width = Math.max(width, minValue);
            depth = Math.max(depth, minValue);

            console.log('Dimensiuni ajustate - height: ' + height + ', width: ' + width + ', depth: ' + depth);

            // Calculează volumul total. Dimensiunile sunt în milimetri, se împart la 1000 pentru a converti în metri
            var total = (height / 1000) * (width / 1000) * (depth / 1000);
            console.log('Volum calculat: ' + total.toFixed(7) + ' m³');

            // Actualizează totalul în formular, rotunjit la 7 zecimale
            $('input[name="property_total"]').val(total.toFixed(7));

            console.log('calculateTotal - end');

            return total; // Opțional: returnăm valoarea pentru a putea fi folosită în alte funcții
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

            // Function to get related fields that depend on a specific field
            function getRelatedFields(field_id) {
                // Initialize an array to hold the dependent fields
                var fields = [];

                // Loop through the global property_values array
                for (var i = 0; i < property_values.length; i++) {
                    // If all_property_values for the current item is 0
                    if (property_values[i].all_property_values == 0) {
                        // Parse the selected_property_values from JSON to an object
                        var selected_property_values = JSON.parse(property_values[i].selected_property_values);

                        // Loop through property_value_ids in the selected_property_values
                        for (var j = 0; j < selected_property_values.property_value_ids.length; j++) {
                            // If the current property_field matches the field_id parameter
                            if (field_id == selected_property_values.property_field) {
                                // Push the property_id into the fields array
                                fields.push(property_values[i].property_id);
                            }
                        }
                    }
                }

                // Remove duplicate items from the fields array and return the result
                return uniqueItems(fields);
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

            //get the property code based on id of property eg: property with id 9 = property_fit
            function getPropertyCodeById(id) {
                code = '';
                for (i = 0; i < property_fields.length; i++) {
                    if (property_fields[i].id == id) {
                        code = property_fields[i].code;
                    }
                }
                return code;
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

            calculateTotal();
            chooseOneBattenIfNoneChosen();

            function addError(field_id, error) {
                if ($("#" + field_id).prev().find('.select2-choice').length > 0) {
                    $("#" + field_id).prev().addClass("error-field");
                    $("#" + field_id).prev().css('display', 'block');
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id).prev());
                } else {
                    $("#" + field_id).addClass("error-field");
                    $("<span class=\"error-text\">" + error + "</span>").insertAfter($("#" + field_id));
                }
            }

            function resetErrors() {
                $(".error-field").removeClass("error-field");
                $("span.error-text").remove();
            }

            function chooseOneBattenIfNoneChosen() {
                if ($("input[name=product_id]:checked").length == 0) {
                    if ($("input[name=product_id]").length > 0) {
                        $("input[name=product_id]")[0].checked = true; //choose first one
                    }
                }
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

            $("#add-product-single-form").submit(function () {
                errors = 0;
                $(".select2-container").removeClass("error-field");
                $(".error-text").remove();
                if ($("input[name=product_id]:checked").length == 0) {
                    errors++;
                    $("<span class=\"error-text\">Please select a product</span>").insertAfter($("#choose-product-shutter"));
                }
                $(".required").each(function () {
                    if ($(this).val() == '') {
                        errors++;
                        addError($(this).attr('id'), 'Please fill in this field');
                    }
                });

                $("input.property-select").each(function () {
                    if ($(this).val() == '') {
                        errors++;
                        addError($(this).attr('id'), 'Please fill in this field');
                    }
                });


                if ($("#property_productquantity").val() != '' && parseFloat($("#property_height").val()) < 1) {
                    errors++;
                    addError("property_productquantity", 'Quantity should be greater than 0');
                }

                if (parseFloat($("#property_height").val()) < 3) {
                    errors++;
                    addError("property_height", 'The minimum size of the batten can not be less than 3mm.');
                }
                if (parseFloat($("#property_width").val()) < 3) {
                    errors++;
                    addError("property_width", 'The minimum size of the batten can not be less than 3mm.');
                }
                if (parseFloat($("#property_depth").val()) < 3) {
                    errors++;
                    addError("property_depth", 'The minimum size of the batten can not be less than 3mm.');
                }

                //we must look for the longest/biggest field of the bellow
                //largest value is the length of the batten, based on the length of the batten there are restrictions to the other two
                //dimensions
                largest_field = '';
                largest_field_value = 0;
                var fields = ['property_width', 'property_height', 'property_depth'];
                for (var i = 0; i < fields.length; i++) {
                    for (var j = 0; j < fields.length; j++) {
                        if (i != j) {
                            compare_a = parseFloat($("#" + fields[i]).val());
                            compare_b = parseFloat($("#" + fields[j]).val());
                            if (compare_a >= compare_b && compare_a >= largest_field_value) {
                                largest_field = fields[i];
                                largest_field_value = compare_a;
                            }
                        }
                    }
                }

                //if length<2500, then the other two dimensions should be more than 3
                if (largest_field_value < 2500) {
                    for (var i = 0; i < fields.length; i++) {
                        value = parseFloat($("#" + fields[i]).val());
                        if (value < 3) {
                            errors++;
                            addError(fields[i], 'Value should be more than 3mm');
                        }
                    }
                }

                //if length>=2500, then the other two dimensions should be more than 5
                if (largest_field_value >= 2500) {
                    for (var i = 0; i < fields.length; i++) {
                        value = parseFloat($("#" + fields[i]).val());
                        if (value < 5) {
                            errors++;
                            addError(fields[i], 'Value should be more than 5mm');
                        }
                    }
                }

                if (errors > 0) {
                    return false;
                } else {
                    return true;
                }
            });

            $(document).ready(function () {
                $("#property_room").trigger('change');
            });

            $('[data-toggle="tooltip"]').tooltip({
                'placement': 'top'
            });


        }
    );
})(jQuery);


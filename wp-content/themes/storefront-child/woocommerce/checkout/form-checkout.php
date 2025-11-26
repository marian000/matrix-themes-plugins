<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

wc_print_notices();

$cart_id = BMC\WooCommerce\MultiSession\customer_id();
//print_r($cart_id);

$my_session = BMC\WooCommerce\MultiSession\get_session($cart_id, get_current_user_id());

//echo '<pre>';
//print_r($my_session['carts'][$cart_id]);
//echo '</pre>';

$cart_type = $my_session['carts'][$cart_id]['type'];
$cart_name = $my_session['carts'][$cart_id]['name'];

?>

<?php
$j = 0;
$i = 0;
$atributes = get_post_meta(1, 'attributes_array', true);
$user_id = get_current_user_id();

$cart_items = WC()->cart->get_cart();

// Create an array to store product IDs and quantities
$old_products = [];

$my_cart = BMC\WooCommerce\MultiSession\get_cart();

//echo '<pre>';
//print_r($my_cart);
//echo '</pre>';


// Loop through existing cart items and store their product IDs and quantities
foreach ($cart_items as $cart_item_key => $cart_item) {
	$old_products[] = [
	  'cart_item_key' => $cart_item_key,
	  'product_id' => $cart_item['product_id'],
	  'variation_id' => $cart_item['variation_id'],
	  'quantity' => $cart_item['quantity'],
	  'variation' => $cart_item['variation'],
	];
}

//print_r($old_products);
$first_element_cart = reset($cart_items);
$product_id = $first_element_cart['product_id'];

$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));
$fob_components = false;

// Check if $term_list is an array and is not empty
if (is_array($term_list) && !empty($term_list)) {
	// Check if $term_list[0] is an object
	if (is_object($term_list[0])) {
		if ($term_list[0]->slug == 'components-fob') {
			$fob_components = true;
		}
	}
}

$nr_code_prod = array();
foreach (WC()->cart->get_cart() as $item_id => $item_data) {
	$i++;
	// $product = $item_data->get_product();
	$product_id = $item_data['product_id'];

	$nr_t = get_post_meta($product_id, 'counter_t', true);
	$nr_b = get_post_meta($product_id, 'counter_b', true);
	$nr_c = get_post_meta($product_id, 'counter_c', true);
	$nr_g = get_post_meta($product_id, 'counter_g', true);

	if (!empty($nr_code_prod)) {
		if ($nr_code_prod['t'] < $nr_t) {
			$nr_code_prod['t'] = $nr_t;
		}
		if ($nr_code_prod['g'] < $nr_g) {
			$nr_code_prod['g'] = $nr_g;
		}
		if ($nr_code_prod['b'] < $nr_b) {
			$nr_code_prod['b'] = $nr_b;
		}
		if ($nr_code_prod['c'] < $nr_c) {
			$nr_code_prod['c'] = $nr_c;
		}
	} else {
		$nr_code_prod['g'] = $nr_g;
		$nr_code_prod['t'] = $nr_t;
		$nr_code_prod['b'] = $nr_b;
		$nr_code_prod['c'] = $nr_c;
	}
}

?>

<div class="alert alert-warning">
	<?php echo get_post_meta(1, 'notification_users_message', true); ?>
</div>

<br>

<?php

/* ***********************************************
//  Show POS order items
*********************************************** */

$mystring = get_the_title();
$findme = '-pos';
$findmeCompoenent = 'component';
$pos = strpos($mystring, $findme);

if (strpos($mystring, $findme) !== false) { ?>

    <a href="/order-pos/" class="btn btn-primary blue"> Order POS</a>
    <br>

    <div class="order-summary-table" style="overflow: auto;">
        <table id="example" style="width:100%" class="table table-striped">
            <thead>
            <tr>
                <!-- <th>item</th> -->

                <th>Item</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

			<?php

			$array_att = array();
			foreach (WC()->cart->get_cart() as $item_id => $item_data) {

				$j++;
				//$product = $item_data->get_product();
				$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
				//print_r($item_id);
				$product_id = $item_data['product_id'];

				$attribute_pa_colors = $item_data['variation']['attribute_pa_colors'];
				$attribute_pa_covered = $item_data['variation']['attribute_pa_covered'];

				$price = get_post_meta($product_id, '_price', true);
				$regular_price = get_post_meta($product_id, '_regular_price', true);
				$sale_price = get_post_meta($product_id, '_sale_price', true);

				$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

				if ($term_list[0]->slug == 'pos') {
					?>
                    <tr>
                        <td>
                            <a href="<?php the_permalink($product_id); ?>">
								<?php echo get_the_title($product_id); ?>
                            </a>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
							<?php echo number_format($price, 2); ?>
                        </td>
                        <td><?php echo $item_data['quantity']; ?></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
							<?php echo number_format($price, 2); ?>
                        </td>
                        <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
							  '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
							  esc_url(wc_get_cart_remove_url($item_id)),
							  __('Remove this item', 'woocommerce'),
							  esc_attr($product_id),
							  esc_attr($_product->get_sku())
							), $item_id);

							?></td>
                    </tr>
					<?php
				}
			}
			?>

            </tbody>
        </table>
    </div>
	<?php
} elseif (strpos($mystring, $findmeCompoenent) !== false) {

	$first_element_cart = reset(WC()->cart->get_cart());

	$product_id = $first_element_cart['product_id'];
	$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

	$user = wp_get_current_user();
	$roles = $user->roles;
	if (in_array('china_admin', $roles)) {
		// echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
		echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
	} else {
		if ($term_list[0]->slug == 'components') {
			echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
			// echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components FOB</a>';
		} elseif ($term_list[0]->slug == 'components-fob') {
			echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
		} else {
			// echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components - FOB</a>';
			echo '<a href="/order-components/" class="btn btn-primary blue"> Order Components UK</a>';
			echo '<a href="/order-components-fob/" class="btn btn-primary blue"> Order Components FOB</a>';
		}
	}

	?>


    <br>

    <div class="order-summary-table" style="overflow: auto;">
        <table id="example" style="width:100%" class="table table-striped">
            <thead>
            <tr>
                <!-- <th>item</th> -->

                <th>Item</th>
                <th></th>
                <th></th>
                <th></th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

			<?php

			$array_att = array();
			//            echo '<pre>';
			//            print_r(reset(WC()->cart->get_cart()));
			//            echo '</pre>';

			foreach (WC()->cart->get_cart() as $item_id => $item_data) {

				$j++;

				// print_r($item_data);
				//$product = $item_data->get_product();
				$_product = apply_filters('woocommerce_cart_item_product', $item_data['data'], $item_data, $item_id);
//                echo '<pre>';
//                print_r($item_data['data']);
//                echo '</pre>';
				$attribute_pa_colors = $item_data['variation']['attribute_pa_colors'];
				$attribute_pa_covered = $item_data['variation']['attribute_pa_covered'];
				$product_id = $item_data['product_id'];

				$price = $item_data['data']->get_price();
				$regular_price = get_post_meta($product_id, '_regular_price', true);
				$sale_price = get_post_meta($product_id, '_sale_price', true);

				$term_list = wp_get_post_terms($product_id, 'product_cat', array("fields" => "all"));

				if ($term_list[0]->slug == 'components' || $term_list[0]->slug == 'components-fob') {
					?>
                    <tr>
                        <td>
                            <a href="<?php the_permalink($product_id); ?>">
                                <strong><?php echo get_the_title($product_id); ?></strong>
                            </a>
                        </td>
                        <td><?php
							if (!empty($attribute_pa_colors)) echo '<strong>Color:</strong> ' . $attribute_pa_colors . '<br>';
							if (!empty($attribute_pa_covered)) echo '<strong>Covered:</strong> ' . $attribute_pa_covered . '<br>';
							if (!empty($item_data['comp_note'])) echo '<strong>Your Note:</strong> ' . $item_data['comp_note'];
							?>
                        </td>
                        <td></td>
                        <td></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
							<?php echo number_format($price, 2); ?>
                        </td>
                        <td><?php echo $item_data['quantity']; ?></td>
                        <td><?php echo $fob_components ? '$' : '£'; ?>
							<?php echo number_format($price * $item_data['quantity'], 2); ?>
                        </td>
                        <td><?php echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
							  '<a href="%s" class=" btn btn-danger" aria-label="%s" data-product_id="%s" data-product_sku="%s">Delete</a>',
							  esc_url(wc_get_cart_remove_url($item_id)),
							  __('Remove this item', 'woocommerce'),
							  esc_attr($product_id),
							  esc_attr($_product->get_sku())
							), $item_id);
							?></td>
                    </tr>
					<?php
				}
			}
			?>

            </tbody>
        </table>
    </div>
	<?php
} elseif ($cart_type == 'Awning') {
	?>

    <div class="row">
        <div class="col-md-8">
            <a href="/prod-awning/" class="btn btn-primary blue"> + Add New Awning</a>
        </div>
    </div>

    <br><br>
    <input type="hidden" id="my_field_name" value="<?php echo $cart_name; ?>">
	<?php

	echo do_shortcode('[awning_table_items]');

}
else {
	/* ***********************************************
		//  Show Shutters order items
		*********************************************** */
	?>

    <div class="row">
        <div class="col-md-8">
            <a href="/prod1-all/" class="btn btn-primary blue"> + Add New Shutter</a>
            <a href="/prod-individual/" class="btn btn-primary blue"> + Add Individual Bay Shutter</a>
            <a href="/prod2-all/" class="btn btn-primary blue"> + Add New Shutter & Blackout Blind</a>
            <a href="/prod5/" class="btn btn-danger blue"> + Add Batten</a>
        </div>
        <!--    <div style="text-align: right" class="col-md-4">-->
        <!--      <form id="uploadForm" style="float: right;">-->
        <!--        <input type="file" id="fileInput" accept=".xlsx"/>-->
        <!--        <button type="submit" class="btn btn-primary white">Process file</button>-->
        <!--      </form>-->
        <!--    </div>-->
    </div>

    <br>

    <div class="order-summary-table" style="overflow: auto;">
		<?php

		/*
				 * Change duplicated quantity items in custom quantity set in shutter module by post meta
				 */
		global $woocommerce;
		//		foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
		//          print_r($cart_item);
		//			$custom_qty = get_post_meta($cart_item['product_id'], 'quantity', true);
		//			$woocommerce->cart->set_quantity($cart_item_key, $custom_qty);
		//		}

		echo do_shortcode('[table_items_shc editable="true" admin="false" table_id="example" table_class="table table-striped" ]');
		?>
    </div>

	<?php
}

?>

<br>

<div class="alert alert-danger hide">
    <strong>Place order canceled!</strong>
</div>

<a href="/" class="btn btn-primary transparent"> Save for Later </a>
<!--button class="btn btn-primary blue download"> Create CSV </button>
< <a href="/checkout/" class="btn brn-default"> Continue </a> -->

<div class="cart-collaterals">
	<?php
	/**
	 * Cart collaterals hook.
	 *
	 * @hooked woocommerce_cross_sell_display
	 * @hooked woocommerce_cart_totals - 10
	 */
	do_action('woocommerce_cart_collaterals');
	?>
</div>

<?php do_action('woocommerce_after_cart'); ?>

<script>
    function download_csv(csv, filename) {
        let csvFile;
        let downloadLink;

        // CSV FILE
        csvFile = new Blob([csv], {
            type: "text/csv"
        });

        // Download link
        downloadLink = document.createElement("a");

        // File name
        downloadLink.download = filename;

        // We have to create a link to the file
        downloadLink.href = window.URL.createObjectURL(csvFile);

        //console.log(downloadLink.href);
        //jQuery('.url_down').attr('href',downloadLink.href);
        // Make sure that the link is not displayed
        downloadLink.style.display = "none";

        // Add the link to your DOM
        document.body.appendChild(downloadLink);

        // Lanzamos
        downloadLink.click();
    }

    function export_table_to_csv(html, filename) {
        let csv = [];
        let rows = document.querySelectorAll("table#example tr");

        for (let i = 0; i < rows.length; i++) {
            let row = [],
                cols = rows[i].querySelectorAll("td, th");

            for (let j = 0; j < cols.length; j++)
                row.push(cols[j].innerText);

            csv.push(row.join(","));
        }

        // Download CSV
        download_csv(csv.join("\n"), filename);

        console.log(csv);
        console.log(filename);
    }


    document.addEventListener("DOMContentLoaded", function () {
        let button = document.querySelector("button.download");
        if (button) {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                let html = document.querySelector("table#example").outerHTML;
                export_table_to_csv(html, "table-order.csv");
            });
        }
    });
</script>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <div class="col-md-6 box">
        <!--    <p>Would you like to see a technical drawing of your shutter before confirming your order to manufacture?</p>-->
        <!--    <div class="flex-center">-->
        <!--    <button class="btn btn-danger red technical_drawing_btn" style="width: 80%;" id="technical_drawing_yes" data-tech="yes">Yes</button>-->
        <!--    <button class="btn btn-primary blue technical_drawing_btn" style="width: 80%;" id="technical_drawing_no" data-tech="no">No</button>-->
        <!--    </div>-->
        <!--    <input type="hidden" name="technical_drawing" value="no">-->
        <!--    <p>Choosing "No" above means that once your payment has been processed, your shutters will immediately enter production</p>-->
        <!--    <p>Selected: <span id="selected_technical_drawing">No</span></p>-->
    </div>

	<?php if ($checkout->get_checkout_fields()) : ?>

		<?php do_action('woocommerce_checkout_before_customer_details'); ?>

        <div class="col2-set" id="customer_details">
            <div class="col-1">
				<?php do_action('woocommerce_checkout_billing'); ?>
            </div>

            <div class="col-2">
				<?php do_action('woocommerce_checkout_shipping'); ?>
            </div>
        </div>

		<?php do_action('woocommerce_checkout_after_customer_details'); ?>

	<?php endif; ?>


	<?php do_action('woocommerce_checkout_before_order_review'); ?>

    <div id="order_review" class="woocommerce-checkout-review-order 213">
		<?php do_action('woocommerce_checkout_order_review'); ?>
    </div>

	<?php do_action('woocommerce_checkout_after_order_review'); ?>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>

<script>
    // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').hide();
    jQuery(document).ready(function () {
        // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').hide();
        setTimeout(function () {
            let totalSqm = jQuery('#totalSqm').text();
            let findme = "$";
            console.log(totalSqm);
            if (totalSqm.indexOf(findme) > -1) {
                console.log("found it");
                let modif_tax = jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').text();
                console.log(modif_tax);
                jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').text(modif_tax.replace('£', '$'));
                // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').show();
            } else {
                // jQuery('.woocommerce-checkout-review-order-table .tax-total .woocommerce-Price-currencySymbol').show();
            }
        }, 1500);
    });

    jQuery('.technical_drawing_btn').click(function(e) {
        e.preventDefault();
        let val = jQuery(this).data('tech');
        console.log('val', val);
        jQuery('#selected_technical_drawing').text(val == 'yes' ? 'Yes' : 'No');

        if (val == 'yes') {
            jQuery('input[name="technical_drawing"]').val('yes');
        } else {
            jQuery('input[name="technical_drawing"]').val('no');
        }
    });

</script>


<script>
    // Add event listener to the upload form
    document.getElementById('uploadForm').addEventListener('submit', handleFileUpload);

    // Function to handle the file upload process
    function handleFileUpload(event) {
        event.preventDefault(); // Prevent default form submission

        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];

        if (!file) {
            alert('Please select a file first.');
            return;
        }

        const reader = new FileReader();
        reader.onload = handleFileLoad; // Set the onload function to handleFileLoad
        reader.readAsArrayBuffer(file); // Read the file as an array buffer
    }

    // Function to handle the file load event
    function handleFileLoad(event) {
        const data = new Uint8Array(event.target.result);
        const workbook = XLSX.read(data, { type: 'array' });

        const sheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[sheetName];

        // Convert the XLSX data to JSON
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        // Process and structure data
        const structuredData = processJsonData(jsonData);

        console.log('structuredData', structuredData);

        // Send the structured data to the server
        sendStructuredDataToServer(structuredData);
    }

    // Function to process JSON data from XLSX
    function processJsonData(jsonData) {
        let structuredData = [];
        let currentProduct = null;
        let tableHeader = null;

        jsonData.forEach((row) => {
            // Check if the third column contains "Location"
            if (row[2] === 'Location') {
                tableHeader = row; // Store the header row
                return;
            }

            // Check if the first column has a numeric value, indicating a new product
            if (typeof row[1] === 'number') {
                if (currentProduct) {
                    structuredData.push(currentProduct); // Push the current product to structured data
                }

                // Start a new product
                currentProduct = {
                    tableHeader: tableHeader,
                    id: row[1],
                    configurations: [row] // Include the row as the first configuration
                };
            } else if (currentProduct) {
                currentProduct.configurations.push(row); // Add row to current product's configurations
            }
        });

        if (currentProduct) {
            structuredData.push(currentProduct); // Push the last product to structured data
        }

        return structuredData;
    }

    // Function to send structured data to the server
    function sendStructuredDataToServer(structuredData) {
        const url_ajax = "/wp-content/plugins/shutter-module/ajax/xls-to-prod.php";

        console.log('sendStructuredDataToServer');
        fetch(url_ajax, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(structuredData) // Send structured data as JSON
        })
            .then(response => response.json())
            .then(data => {
                console.log('Success1:', data); // Log success message
                sendProductsToAddToCart(data);
            })
            .catch((error) => {
                console.error('Error:', error); // Log error message
            });
    }


    // Function to send structured data to the server
    function sendProductsToAddToCart(productsData) {
        const url_ajax = "/wp-content/plugins/shutter-module/ajax/ajax-shutter-xls.php";

        console.log('sendProductsToAddToCart');
        fetch(url_ajax, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(productsData) // Send structured data as JSON
        })
            .then(response => {
                    response.json()
                    // redirect to /checkout
                    window.location.href = '/checkout/';
                }
            )
            .then(data => {
                console.log('Success2:', data); // Log success message
                alert('Items imported to cart!');
                // redirect to /checkout
                window.location.href = '/checkout/';

            })
            .catch((error) => {
                console.error('Error:', error); // Log error message
            });
    }

</script>


<style>
    input[type="file"]:focus-within::file-selector-button,
    input[type="file"]:focus::file-selector-button {
        outline: 2px solid #0964b0;
        outline-offset: 2px;
    }

    input[type="file"]::before {
        top: 7px;
    }

    input[type="file"]::after {
        top: 7px;
    }

    /* ------- From Step 2 ------- */

    input[type="file"] {
        position: relative;
    }

    input[type="file"]::file-selector-button {
        width: 110px;
        color: transparent;
    }

    /* Faked label styles and icon */
    input[type="file"]::before {
        position: absolute;
        pointer-events: none;
        left: 35px;
        color: #0964b0;
        content: "Upload File";
    }

    input[type="file"]::after {
        position: absolute;
        pointer-events: none;
        /*   top: 10px; */
        left: 10px;
        height: 34px;
        width: 20px;
        content: "";
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%230964B0'%3E%3Cpath d='M18 15v3H6v-3H4v3c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2v-3h-2zM7 9l1.41 1.41L11 7.83V16h2V7.83l2.59 2.58L17 9l-5-5-5 5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
    }

    /* ------- From Step 1 ------- */

    /* file upload button */
    input[type="file"]::file-selector-button {
        border-radius: 4px;
        /*padding: 0 12px;*/
        height: 34px;
        cursor: pointer;
        background-color: white;
        border: 1px solid rgba(0, 0, 0, 0.16);
        box-shadow: 0px 1px 0px rgba(0, 0, 0, 0.05);
        margin-right: 16px;
        transition: background-color 200ms;
    }

    /* file upload button hover state */
    input[type="file"]::file-selector-button:hover {
        background-color: #f3f4f6;
    }

    /* file upload button active state */
    input[type="file"]::file-selector-button:active {
        background-color: #e5e7eb;
    }

    form#uploadForm {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-content: center;
        justify-content: flex-start;
        align-items: center;
    }
</style>
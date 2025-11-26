<?php
$table_class = $attributes['table_class'];
$table_id = $attributes['table_id'];
$order_id = $attributes['order_id'];
$edit = $attributes['editable'];
$admin = $attributes['admin'];

if ($order_id) {
	$order = wc_get_order($order_id);
	$order_data = $order->get_data(); // The Order data
	foreach ($order->get_items('tax') as $item_id => $item_tax) {
		$tax_shipping_total = $item_tax->get_shipping_tax_total(); // Tax shipping total
	}
}

//$order = new WC_Order( $order_id );
$items = $order->get_items();
$order_data = $order->get_data();
// echo '<pre>';
// print_r($order_data);
// echo '</pre>';

$i = 0;
$atributes = get_post_meta(1, 'attributes_array_csv', true);
//print_r($atributes);
//Iterating through each "line" items in the order

$nr_code_prod = array();

$product_name = 'awning product'; // Înlocuiește cu numele dorit
$product = get_page_by_title( $product_name, OBJECT, 'product' );

if ( $product ) {
	$product_id = $product->ID;
} else {
	$product_id = 0; // Nu a fost găsit produsul
}
$_product = wc_get_product($product_id);
$shipclass = $_product->get_shipping_class();

if ($shipclass == 'ship') {
	$transport = 'BY SEA';
} elseif ($shipclass == 'air') {
	$transport = 'BY AIR';
}

// Start output buffering to capture the HTML
ob_start();
?>

<table id="<?php echo $table_id ?>" style="width:100%" class="<?php echo $table_class; ?>">
    <thead>
    <tr>
        <th>Customer:</th>
        <th><?php echo $order_data['billing']['company']; ?></th>
        <th>Client details:</th>
        <th><?php echo get_post_meta($order->get_id(), 'cart_name', true); ?></th>
        <th>Order date:</th>
        <th><?php echo $order_data['date_created']->date('Y-m-d H:i:s'); ?></th>
    </tr>
    <tr>
        <th>Reference no:</th>
        <th>LF0<?php echo $order->get_order_number(); ?></th>
        <th>Clients address:</th>
        <th><?php echo $order_data['billing']['address_1']; ?></th>
        <th>Clients contact:</th>
        <th><?php echo $order_data['billing']['phone'] . ' ' . $order_data['billing']['email']; ?></th>
    </tr>
    <tr>
        <th>Delivery method:</th>
        <th><?php echo $transport; ?></th>
    </tr>
    <tr>
    </tr>
    <tr>
        <th>Nr.</th>
        <th>Product Name</th>
		<?php
		// Define an associative array mapping meta keys to header labels.
		$meta_keys = array(
		  'name' => 'Name',
		  'material' => 'Material',
		  'width' => 'Width (mm)',
		  'drop' => 'Drop (mm)',
		  'color' => 'Colour',
		  'special_color_name' => 'Special Colour Name',
		  'cassette_colour' => 'Cassette Colour',
		  'cassette_cover' => 'Cassette Cover',
		  'motor' => 'Motor',
		  'led' => 'LED Arm Lights',
		  'position' => 'Position',
		  'sensor' => 'Wind & Rain Sensor',
		);

		// Loop through meta keys to create header cells.
		foreach ($meta_keys as $key => $label) {
			echo '<th>' . esc_html($label) . '</th>';
		}
		?>
        <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
	<?php
	$i = 1;
	foreach ($items as $item_id => $item):
		// Get the product information
		$product = $item->get_product();
		$product_name = $product ? $product->get_name() : $item->get_name();

		// Calculate unit price, quantity, and line total
		$product_qty = $item->get_quantity();
		$line_total = $item->get_total();
		$unit_price = $product_qty > 0 ? $line_total / $product_qty : 0;

		// Build an array of meta values for each key
		$meta_values = array();
		foreach ($meta_keys as $key => $label) {
			$meta_values[$key] = $item->get_meta($key);
		}
		?>
        <tr data-id="<?php echo esc_attr($item_id); ?>">
            <td><?php echo esc_html($i); ?></td>
            <td><?php echo esc_html($product_name); ?></td>
			<?php
			// Loop through each meta key and display its value
			foreach ($meta_keys as $key => $label) {
				echo '<td>' . esc_html($meta_values[$key]) . '</td>';
			}
			?>
            <td><?php echo esc_html($product_qty); ?></td>
        </tr>
		<?php
		$i++;
	endforeach;
	?>
    </tbody>
</table>
<?php
$email_body = ob_get_clean(); // Capturăm tot conținutul în variabila $email_body

if ($admin == 'true') { ?>
	<?php echo $email_body; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/2.3.5/jspdf.plugin.autotable.js"></script>

    <script>
        function download_csv(csv, filename) {
            var csvFile;
            var downloadLink;

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

            console.log(downloadLink.href);
            jQuery('.url_down').attr('href', downloadLink.href);
            // Make sure that the link is not displayed
            downloadLink.style.display = "none";

            // Add the link to your DOM
            document.body.appendChild(downloadLink);

            // Lanzamos
            downloadLink.click();
        }

        function export_table_to_csv(html, filename) {
            var csv = [];
            var rows = document.querySelectorAll(".show_tabble > table#example tr");

            for (var i = 0; i < rows.length; i++) {
                var row = [],
                    cols = rows[i].querySelectorAll("td, th");

                for (var j = 0; j < cols.length; j++)
                    row.push(cols[j].innerText);

                csv.push(row.join(","));
            }

            // Download CSV
            download_csv(csv.join(" \n "), filename);

            console.log(csv);
            console.log(filename);
        }

        jQuery("button.download").on("click", function (e) {
            e.preventDefault();
            var html = document.querySelector("#external-csv.show_tabble > table#example").outerHTML;
            export_table_to_csv(html, "table-order.csv");

            //console.log(html);

        });


        jQuery(document).ready(function () {


            jQuery('#send_mail.send-m').on('click', function (e) {
                e.preventDefault();

                var content = jQuery('textarea[name="table"]').val();
                var id = jQuery('input[name="id_ord"]').val();
                var id_ord_original = jQuery('input[name="id_ord_original"]').val();
                var name = jQuery('input[name="order_name"]').val();
                var items_buy = jQuery('#items-info').html();

                var data = {
                    table: content,
                    id: id,
                    id_ord_original: id_ord_original,
                    name: name,
                    items_table: items_buy
                }
                console.log('send mail: ', data);

                jQuery.ajax({
                    method: "POST",
                    url: "/wp-content/themes/storefront-child/csvs/create-csv-admin.php",
                    data: {
                        table: content,
                        id: id,
                        id_ord_original: id_ord_original,
                        name: name,
                        items_table: items_buy
                    }
                })
                    .done(function (msg) {
                        alert("mail send");
                        console.log("Data Saved: " + msg);
                    });

            });


            jQuery('#send_qcuickbooks_invoice').on('click', function (e) {
                e.preventDefault();
                console.log('send_qcuickbooks_invoice clicked');


                var id_ord_original = jQuery('input[name="id_ord_original"]').val();
                var name = jQuery('input[name="order_name"]').val();

                jQuery.ajax({
                    method: "POST",
                    url: "/wp-content/themes/storefront-child/quickBooks/InvoiceAndBilling.php",
                    data: {
                        id_ord_original: id_ord_original,
                        name: name
                    }
                })
                    .done(function (data) {

                        alert('QuickBooks Invoice Created!');
                        console.log("QuickBooks Invoice: " + data);

                    });

            });

        });


        // cand se incarca pagina se verifica pretul de la fiecare item

    </script>

    <input type="hidden" name="id_ord_original" value="<?php echo $order_id; ?>">
    <input type="hidden" name="id_ord" value="<?php echo $order->get_order_number(); ?>">

    <textarea style="display: none;" name="table" id="tableToMakeMail" cols="30"
              rows="10"><?php echo $email_body; ?></textarea>

<?php } else { ?>
    <textarea style="display: none;" name="table" id="tableToMakeMail" cols="30"
              rows="10"><?php echo $email_body; ?></textarea>
<?php } ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.css"
/>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.js"></script>


<?php

$order = new WC_Order( $order_id );
$order_data = $order->get_data();

//$order = new WC_Order( $order_id );
$items = $order->get_items();
// echo '<pre>';
// print_r($order_data);
// echo '</pre>';


$user_id = get_current_user_id();
$meta_key = 'wc_multiple_shipping_addresses';

global $wpdb;
if ( $addresses = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = %d AND meta_key = %s", $user_id, $meta_key)) ) {
    $addresses = maybe_unserialize($addresses);
}

$addresses = $wpdb->get_results("SELECT meta_value FROM `wp_usermeta` WHERE user_id = $user_id AND meta_key = '_woocom_multisession'");


$userialize_data = unserialize($addresses[0]->meta_value);
$session_key = $userialize_data['customer_id'];


?>
<a class="btn btn-danger delete-btn" id="checkout-delete-btn" href="#delete" target="<?php echo $session_key; ?>" onclick="return BMCWcMs.command('delete', this);">Delete</a>
<?php


$i = 0;
$atributes = get_post_meta(1,'attributes_array',true);
//print_r($atributes);
//Iterating through each "line" items in the order
foreach ($order->get_items() as $item_id => $item_data) {

    // Get an instance of corresponding the WC_Product object
    $product = $item_data->get_product();
    $product_name = $product->get_name(); // Get the product name

    $item_quantity = $item_data->get_quantity(); // Get the item quantity

    $item_total = $item_data->get_total(); // Get the item line total

    // Displaying this data (to check)
    //echo 'Product name: '.$product_name.' | Quantity: '.$item_quantity.' | Item total: '. number_format( $item_total, 2 );
}

$nr_code_prod = array();
foreach ($order->get_items() as $prod => $item_data) {
    $i++;
    $product = $item_data->get_product();
    $product_id = $product->id;


    $nr_t = get_post_meta($product_id, 'counter_t', true);
    $nr_b = get_post_meta($product_id, 'counter_b', true);
    $nr_c = get_post_meta($product_id, 'counter_c', true);

    if(!empty($nr_code_prod)){
        if( $nr_code_prod['t'] < $nr_t ){
            $nr_code_prod['t'] = $nr_t;
        }
        if( $nr_code_prod['b'] < $nr_b ){
            $nr_code_prod['b'] = $nr_b;
        }
        if( $nr_code_prod['c'] < $nr_c ){
            $nr_code_prod['c'] = $nr_c;
        }
    }
    else{
        $nr_code_prod['t'] = $nr_t;
        $nr_code_prod['b'] = $nr_b;
        $nr_code_prod['c'] = $nr_c;
    }


}

?>
<?php
$email_body = '<html>
<head></head>
<body>
<table id="example" style="width:100%" class="table table-striped">
        <thead>
            <tr>
                    <th>Company:</th>
                    <th>'.  get_user_meta($customer_id,'billing_company',true) .'</th>
                    <th>Client details:</th>
                    <th>'. get_post_meta($order_data['id'],'cart_name',true) .'</th>
                    <th>Order date:</th>
                    <th>'.  $order_data['date_created']->date('Y-m-d H:i:s') .'</th>
            </tr>
            <tr>
                    <th>Reference no:</th>
                    <th>LF0'.  $order->get_order_number() .'</th>
                   
            </tr>
            <tr>
            </tr>
                <tr>
                <th>item</th>
                <th>material</th>
                <th>Room</th>
                <th>instalation style</th>
                <th>Shape</th>
                <th>how many at this size</th>
                <th>width</th>
                <th>height</th>
                <th>sqm</th>
                <th>Midrail Height </th>
                <th>T-o-T Height</th>
                <th>Midrail Height 2</th>
                <th>Hidden Divider 1 </th>
                <th>Hidden Divider 2 </th>
                <th>Position is Critical</th>
                <th>Horizontal T Post </th>
                <th>louver</th>
                <th>Fit</th>
                <th>frame type</th>
                <th>stile type</th>
                <th>Frame Left</th>
                <th>Frame Right</th>
                <th>Frame Bottom</th>
                <th>Frame Left</th>
                <th>Buildout</th>
                <th>Hinge Colour</th>
                <th>Shutter Colour</th>
                <th>Other Colour</th>
                <th>Control Type</th>
                <th>Control Split Height</th>
                <th>Layout code</th>';
            foreach($nr_code_prod as $key => $val){ 
                for($i=1; $i < $val+1 ;$i++){
                    if($key == 'b'){
                        $email_body .= '<th>'.$key.'p '. $i .'</th>';
                        $email_body .= '<th>'.$key.'a '. $i .'</th>';
                    }
                    else{
                        $email_body .= '<th>'.$key.' '. $i .'</th>';
                    }
                }
            } 

            $email_body .= '<th>Include 2 x Spare Louvre</th>
                <th>Ring Pull</th>
                <th>notes</th>
            </tr>
        </thead>
        <tbody>';
         $array_att = array();
         $email_body .= ''. print_r($order->get_items());
        foreach ($order->get_items() as $prod => $item_data) {
            $i++;
            $product = $item_data->get_product();
            $product_id = $product->id;

            $property_room_other = get_post_meta($product_id,'property_room_other',true);
$property_style = get_post_meta($product_id,'property_style',true); 
$property_frametype = get_post_meta($product_id,'property_frametype',true); 

$attachment = get_post_meta( $product_id, 'attachment', true );
$array_att[] = $attachment;

$property_material = get_post_meta($product_id,'property_material',true);
$property_width = get_post_meta($product_id,'property_width',true);
$property_height = get_post_meta($product_id,'property_height',true);
$property_midrailheight = get_post_meta($product_id,'property_midrailheight',true);
$property_midrailheight2 = get_post_meta($product_id,'property_midrailheight2',true);
$property_midraildivider1 = get_post_meta($product_id,'property_midraildivider1',true);
$property_midraildivider2 = get_post_meta($product_id,'property_midraildivider2',true);
$property_midrailpositioncritical = get_post_meta($product_id,'property_midrailpositioncritical',true);
$property_totheight = get_post_meta($product_id,'property_totheight',true);
$property_horizontaltpost = get_post_meta($product_id,'property_horizontaltpost',true);
$property_bladesize = get_post_meta($product_id,'property_bladesize',true);
$property_fit = get_post_meta($product_id,'property_fit',true);
$property_frameleft = get_post_meta($product_id,'property_frameleft',true);
$property_frameright = get_post_meta($product_id,'property_frameright',true);
$property_frametop = get_post_meta($product_id,'property_frametop',true);
$property_framebottom = get_post_meta($product_id,'property_framebottom',true);
$property_builtout = get_post_meta($product_id,'property_builtout',true);
$property_stile = get_post_meta($product_id,'property_stile',true);
$property_hingecolour = get_post_meta($product_id,'property_hingecolour',true);
$property_shuttercolour = get_post_meta($product_id,'property_shuttercolour',true);
$property_shuttercolour_other = get_post_meta($product_id,'property_shuttercolour_other',true);
$property_controltype = get_post_meta($product_id,'property_controltype',true);
$property_controlsplitheight = get_post_meta($product_id,'property_controlsplitheight',true);
$property_controlsplitheight2 = get_post_meta($product_id,'property_controlsplitheight2',true);
$property_layoutcode = get_post_meta($product_id,'property_layoutcode',true);
$property_t1 = get_post_meta($product_id,'property_t1',true);
$property_total = get_post_meta($product_id,'property_total',true);

$property_sparelouvres = get_post_meta($product_id,'property_sparelouvres',true);
$property_ringpull = get_post_meta($product_id,'property_ringpull',true);
$price = get_post_meta($product_id, '_price', true);
$regular_price = get_post_meta($product_id, '_regular_price',true);
$sale_price = get_post_meta($product_id, '_sale_price', true);
$svg_product = get_post_meta($product_id, 'svg_product', true);
$comments_customer = get_post_meta($product_id, 'comments_customer', true);


$email_body .= '<tr>
                <td>sasdsa'. $i .'sdfsdf</td>
                <td>'. $atributes[$property_material] .'</td>
                <td>'.  $property_room_other .'</td>
                <td>'.  $atributes[$property_style] .'</td>
                <td>';
				if(!empty($attachment)){	 $email_body .= 'yes';		}
				else {  $email_body .= 'no'; }
				$email_body .= '</td>
                <td>'.  $item_quantity .'</td>
                <td>'.  $property_width .'</td>
                <td>'.  $property_height .'</td>
                <td>'.  $property_total .'</td>
                <td>'.  $property_midrailheight .'</td>
                <td>'.  $property_totheight .'</td>
                <td>'.  $property_midrailheight2 .'</td>
                <td>'.  $property_midraildivider1 .'</td>
                <td>'.  $property_midraildivider2 .'</td>
                <td>'.  $atributes[$property_midrailpositioncritical] .'</td>
                <th>'.  $property_horizontaltpost .'</th>
                <td>'.  $atributes[$property_bladesize] .'</td>
                <td>'.  $atributes[$property_fit] .'</td>
                <td>'.  $atributes[$property_frametype] .'</td>
                <td>'.  $atributes[$property_stile] .'</td>
                <td>'.  $atributes[$property_frameleft] .'</td>
                <td>'.  $atributes[$property_frameright] .'</td>
                <td>'.  $atributes[$property_frametop] .'</td>
                <td>'.  $atributes[$property_framebottom] .'</td>
                <td>'.  $property_builtout .'</td>
                <td>'.  $atributes[$property_hingecolour] .'</td>
                <td>'.  $atributes[$property_shuttercolour] .'</td>
                <td>'.  $atributes[$property_shuttercolour_other] .'</td>
                <td>'.  $atributes[$property_controltype] .'</td>
                <td>'.  $atributes[$property_controlsplitheight] .'</td>
                <td style="text-transform: uppercase;">'.  $property_layoutcode .'</td>';
                    foreach($nr_code_prod as $key => $val){ 
                        for( $i=1 ; $i < $val+1 ; $i++){
                            if($key == 'b'){
                                $email_body .= '<td>'.get_post_meta( $product_id, 'property_bp'.$i, true ).'</td>';
                                $email_body .= '<td>'.get_post_meta( $product_id, 'property_ba'.$i, true ).'</td>';
                            }
                            else{
                                $email_body .= '<td>'.get_post_meta( $product_id, 'property_t'.$i, true ).'</td>';
                            }
                        }
                    }
                    $email_body .= '<td>'.  $property_sparelouvres .'</td>
                <td>'.  $property_ringpull .'</td>
                <td>'.  $comments_customer .'</td>
            </tr>';
         } 
         $email_body .= '</tbody>
    </table>
    </body>
    </html>';

    // $to = 'marianputirac@thecon.ro';
    // $subject = 'test matrix mail send';
    // $body = $email_body;
    // $headers = array('Content-Type: text/html; charset=UTF-8','From: My Site Name &lt;support@example.com');

   // wp_mail( $to, $subject, $body, $headers );

?>


<textarea name="table" id="" cols="30" rows="10"><?php echo $email_body; ?></textarea>
<input type="text" name="csvname" value="numecsv" >
<script>

jQuery(document).ready(function(){

    var content = jQuery( 'textarea[name="table"]' ).val();
    var name = jQuery( 'input[name="csvname"]' ).val();
    jQuery('#checkout-delete-btn').click();

    jQuery.ajax({
        method: "POST",
        url: "/wp-content/themes/storefront-child/csvs/createcsv.php",
        data: { table: content, name: name }
    })
    .done(function( msg ) {
        //alert( "Data Saved: " + msg );
        console.log( "Data Saved: " + msg );
        jQuery('#checkout-delete-btn').click();
    });

    alert('csv created');

});


</script>

<script>


// window.onload = function() {

// 	var csv = [];
// 	var rows = document.querySelectorAll("table#example tr");
//     var html = document.querySelector("table#example").outerHTML;
    
//     for (var i = 0; i < rows.length; i++) {
// 		var row = [], cols = rows[i].querySelectorAll("td, th");
		
//         for (var j = 0; j < cols.length; j++) 
//             row.push(cols[j].innerText);
        
// 		csv.push(row.join(","));		
// 	}

//     // Download CSV
//     //download_csv(csv.join("\n"), "table-order.csv");




//     var csvFile;
//     var downloadLink;

//     // CSV FILE
//     csvFile = new Blob([(csv.join("\n")], {type: "text/csv"});

//     // Download link
//     downloadLink = document.createElement("a");

//     // File name
//     downloadLink.download = "table-order.csv";

//     // We have to create a link to the file
//     downloadLink.href = window.URL.createObjectURL(csvFile);

//     // Make sure that the link is not displayed
//     downloadLink.style.display = "none";

//     // Add the link to your DOM
//     document.body.appendChild(downloadLink);

//     // Lanzamos

//     //downloadLink.click();
//     var order_id = <?php echo $order_id; ?>;

//     $.ajax({
//         method: "POST",
//         url: "/wp-content/themes/storefront-child/ajax/mailorder-ajax.php",
//         data: { link: downloadLink, order_id: order_id }
//         })
//         .done(function( msg ) {
//             alert( "Data Saved: " + msg );
//     });

// }

</script>




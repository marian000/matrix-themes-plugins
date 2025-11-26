<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.css"
/>

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.0.2/js/tableexport.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sprintf/1.1.1/sprintf.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.4.1/jspdf.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Base64/1.0.1/base64.js"></script>

<script type="text/javascript"
        src="https://cdn.datatables.net/v/bs-3.3.7/jszip-2.5.0/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/b-flash-1.5.1/b-html5-1.5.1/b-print-1.5.1/cr-1.4.1/fc-3.2.4/fh-3.1.3/kt-2.3.2/r-2.2.1/rg-1.0.2/rr-1.2.3/sc-1.4.4/sl-1.2.5/datatables.min.js"></script>



<style>
    #external-csv {
        overflow: auto;
        width: auto;
        display: block;
    }
    
    /* #example {
        overflow: auto;
        width: auto;
        display: block;
    } */
    #woocommerce-order-items, #postcustom {
        display: none;
    }
    
    #items-order td {
        min-width: 15%;
    }
    
    #items-order td:nth-child(3) {
        max-width: 16vw;
    }

</style>

<button id="send_mail" class="send-m btn btn-primary">Send Mail</button>

<button class="download btn btn-primary">Download CSV</button>

<button id="send_qcuickbooks_invoice" class="btn btn-primary">Test QuickBooks Invoice</button>

<div class="show_tabble">
    <div id="external-csv" class="show_tabble" style="overflow: auto; ">
        <?php
            echo do_shortcode('[table_csv_shc order_id="' . get_the_id() . '" table_id="example" table_class="table table-striped shortcode" admin="true" editable="false" ]');
        ?>
    </div>
</div>

<!-- <button class="btn btn-danger pdf-download" >PDF TEST</button> -->

<?php
//    $mystring = get_post_meta($order->get_id(), 'cart_name', true);
//    $findme = '-pos';
//    $pos = strpos($mystring, $findme);
//
//    if (strpos($mystring, $findme) !== false) {
//
//    } else {
//
//        ?>
<!--            -->
<!--        <a class="btn btn-danger delete-btn hidden" id="checkout-delete-btn" href="#delete" target="--><?php //echo $session_key; ?><!--"-->
<!--           onclick="return BMCWcMs.command('delete', this);">Delete</a>-->
<!--        -->
<!--        <textarea style="display: none;" name="table" id="" cols="30" rows="10">-->
<!--        --><?php ////echo $email_body; ?>
<!--    </textarea>-->
<!--        <input type="hidden" name="id_ord" value="--><?php //echo $order->get_order_number(); ?><!--">-->
<!--        <input type="hidden" name="id_ord_original" value="--><?php //echo $order->get_id(); ?><!--">-->
        <input type="hidden" name="order_name" value="<?php echo get_post_meta(get_the_id(), 'cart_name', true); ?>">
<!--        -->
<!--        --><?php
//    }

?>

<!-- <script type="text/javascript" src="http://matrix.nexloc.com/wp-content/themes/storefront-child/js/pdfmake.min.js"></script>
<script type="text/javascript" src="http://matrix.nexloc.com/wp-content/themes/storefront-child/js/vfs_fonts.js"></script> -->
<!-- <script src="http://matrix.nexloc.com/wp-content/themes/storefront-child/js/jspdf.min.js"></script> -->

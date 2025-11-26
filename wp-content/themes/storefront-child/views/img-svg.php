<?php
$prod_id = get_the_id();
$svg = get_post_meta($prod_id, 'svg_product',true); 
$attachment = get_post_meta( $prod_id, 'attachment', true );

echo '<img src="'.$attachment.'" alt="" >';
echo '<div class="svg-content"> '.$svg.' </div>';




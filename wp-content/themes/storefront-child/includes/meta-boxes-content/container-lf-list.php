<?php

$container_orders = get_post_meta($meta_id->ID, 'container_orders', true);

echo 'orders: ';
//print_r($container_orders); 

$outline = '';
$outline .= '<br/><textarea name="listLF" id="lf-list"></textarea>';

$outline .= '<div id="wait-sync-lfs" style="display: none;">Waiting to Load...</div>';
$outline .= '<div id="wait-sync-done-lfs" style="display: none;">Loading Done</div>';
$outline .= '<br/><br/><button type="button" id="conteiner-load-lf" class="btn btn-primary" data-id="'.$meta_id->ID.'">Load LF-s list</button>';

$outline .= "<script>
                jQuery('button#conteiner-load-lf').click(function(){
                    console.log('sync start');
                    jQuery('#wait-sync-lfs').show();
                    var elem = document.getElementById('conteiner-load-lf');
                    var contId = elem.getAttribute('data-id');
                    var list = jQuery('#lf-list').val();
                  
                    var data = {
                        'action': 'container_insert_list',
                        'list': list,
                        'containerId': contId,
                    };
                     var ajaxurl = '" . admin_url('admin-ajax.php') . "';
                    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                    jQuery.post(ajaxurl, data, function(response) {
                        console.log('response ajax ', response);
                       jQuery('#wait-sync-lfs').hide();
                       jQuery('#wait-sync-done-lfs').show();
                    });
                });
            </script>";

echo $outline;
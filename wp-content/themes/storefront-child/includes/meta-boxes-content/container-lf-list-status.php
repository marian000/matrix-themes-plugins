<?php

$container_orders = get_post_meta($meta_id->ID, 'container_orders_status', true);

echo 'orders: ';
//print_r($container_orders);

$outline = '';
$outline .= '<br/><textarea name="listStatusLF" id="lf-list-status"></textarea>';

// statuses  Processing; In Production; Completed;
$outline .= '<select name="statusSelected" id="lf-list-status-selected">
															<option value="processing">Processing</option>
															<option value="inproduction">In Production</option>
															<option value="completed">Completed</option>	
</select>';

$outline .= '<div id="wait-sync-lfs-status" style="display: none;">Waiting to Load...</div>';
$outline .= '<div id="wait-sync-done-lfs-status" style="display: none;">Loading Done</div>';
$outline .= '<br/><br/><button type="button" id="conteiner-load-lf-status" class="btn btn-primary" data-id="'.$meta_id->ID.'">Change LF-s status list</button>';

$outline .= "<script>
                jQuery('button#conteiner-load-lf-status').click(function(){
                    console.log('sync start');
                    jQuery('#wait-sync-lfs-status').show();
                    var elem = document.getElementById('conteiner-load-lf-status');
                    var contId = elem.getAttribute('data-id');
                    var list = jQuery('#lf-list-status').val();
                    var status = jQuery('#lf-list-status-selected').val();
                  
                    var data = {
                        'action': 'container_insert_list_status',
                        'list': list,
                        'containerId': contId,
                        'status': status,
                    };
                     var ajaxurl = '" . admin_url('admin-ajax.php') . "';
                    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                    jQuery.post(ajaxurl, data, function(response) {
                        console.log('response ajax ', response);
                       jQuery('#wait-sync-lfs-status').hide();
                       jQuery('#wait-sync-done-lfs-status').show();
                    });
                });
            </script>";

echo $outline;
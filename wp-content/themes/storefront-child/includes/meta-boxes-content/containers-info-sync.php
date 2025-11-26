<?php

$outline = '<button type="button" id="conteiner-info-sync" data-id="'.$meta_id->ID.'">Sync Container Info</button>';

$outline .= '<div id="wait-sync" style="display: none;">Waiting to sync...</div>';
$outline .= '<div id="wait-sync-done" style="display: none;">Sync Done</div>';

$outline .= "<script>
                jQuery('button#conteiner-info-sync').click(function(){
                    console.log('sync start');
                    jQuery('#wait-sync').show();
                    var elem = document.getElementById('conteiner-info-sync');
                    var contId = elem.getAttribute('data-id');
                  
                    var data = {
                        'action': 'container_info_sync',
                        'container_id': contId,
                    };
                    // We can also pass the url value separately from ajaxurl for front end AJAX implementations
                    jQuery.post('/wp-admin/admin-ajax.php', data, function(response) {
                        console.log(response);
                       jQuery('#wait-sync').hide();
                       jQuery('#wait-sync-done').show();
                    });
                });
            </script>";

echo $outline;
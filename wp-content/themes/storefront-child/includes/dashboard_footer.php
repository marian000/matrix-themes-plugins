<?php
    
    function frontfooter()
    {
        echo '<div class="spinner-modal">
                <img src="/wp-content/themes/storefront-child/imgs/loading.svg" alt="Loading" />
               </div>
               
               <style>
                .spinner-modal {
                    display:    none;
                    position:   fixed;
                    z-index:    1000;
                    top:        0;
                    left:       0;
                    height:     100%;
                    width:      100%;
                    background: rgba(0,0,0,0.7);
                }
                
                .spinner-modal img {
                    position: absolute;
                    top: 45%;
                    left: calc(50% + 45px);
                }
                
                /* When the body has the loading class, we turn
                   the scrollbar off with overflow:hidden */
                body.loading .spinner-modal {
                    overflow: hidden;
                }
                
                /* Anytime the body has the loading class, our
                   modal element will be visible */
                body.loading .spinner-modal {
                    display: block;
                }
                .post-type-shop_order .wp-list-table tbody tr:hover:not(.status-trash):not(.no-link) td {
                    cursor: default;
                }
                </style>';
    }
    
    add_action('admin_footer', 'frontfooter');

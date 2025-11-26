<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: POS-Shop
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">


            <div class="page-content" style="background-color: #F7F7F7">
                <div class="page-content-area">
                    <div class="page-header">
                        <h1>Add POS Products</h1>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-warning">
		                        <?php
		                        // Get the saved notification message
		                        $notification_message = get_post_meta(1, 'notification_users_message', true);
		                        echo wp_kses_post($notification_message); // Safely display the HTML content
		                        ?>
                            </div>


                            <div class="products-list-simple">
                                <ul class="thumbnails" style="text-align:center;list-style:outside none none;">
                                    <?php 
                                        $args = array( 'post_type' => 'product',
                                                    'tax_query' => array(
                                                        array(
                                                            'taxonomy' => 'product_cat',
                                                            'field'    => 'slug',
                                                            'terms'    => 'pos',
                                                        ),
                                                    ), );
                                        $query = new WP_Query( $args ); ?>
                                    <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                                    <li class="col-sm-3" style="opacity: 1;">
                                        <div class="thumbnail">
                                            <a href="<?php the_permalink(); ?>" class="main" title="<?php the_title(); ?>">
                                                <img alt="<?php the_title(); ?>" src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>">
                                            </a>
                                            <p style="height:40px">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </p>
                                            <p class="status">&nbsp;</p>
                                            <p class="price">
                                                £<?php echo $price = get_post_meta( get_the_ID(), '_regular_price', true); ?>
                                            </p>

                                        </div>

                                    </li>

                                    <?php endwhile; 
                                        wp_reset_postdata();
                                        else : ?>
                                    <p>
                                        <?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?>
                                    </p>
                                    <?php endif; ?>
                                    <div class="clearfix clear-fix"></div>

                                </ul>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <?php
get_footer();
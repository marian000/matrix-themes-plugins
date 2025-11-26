<?php
/**
 * The template for displaying full width pages.
 *
 * Template Name: Components-Shop
 *
 */

get_header(); ?>

    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">


            <div class="page-content row" style="background-color: #F7F7F7">
                <div class="page-content-area col-sm-12">
                    <div class="page-header">
                        <h1><b>Add Components</b></h1>
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

                                <?php
                                $taxonomies = get_terms(array(
                                    'taxonomy' => 'component_type',
                                    'orderby' => 'meta_value_num',
                                    'order' => 'ASC',
                                    'meta_query' => [[
                                        'key' => 'position',
                                        'type' => 'NUMERIC',
                                    ]],
                                ));

                                foreach ($taxonomies as $subcategory) {

                                    echo '<h2>'.$subcategory->name.'</h1>
<ul class="thumbnails" style="text-align:center;list-style:outside none none;margin: 0;">';

                                    $args = array('post_type' => 'product',
                                        'tax_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'taxonomy' => 'product_cat',
                                                'field' => 'slug',
                                                'terms' => 'components',
                                            ),
                                            array(
                                                'taxonomy' => 'component_type',
                                                'field' => 'slug',
                                                'terms' => $subcategory->slug,
                                            ),
                                        ),);
                                    $query = new WP_Query($args); ?>
                                    <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
                                        global $product; ?>
                                        <li class="col-sm-3" style="opacity: 1;">
                                            <div class="thumbnail">
                                                <a href="<?php the_permalink(); ?>" class="main"
                                                   title="<?php the_title(); ?>">
                                                    <img alt="<?php the_title(); ?>"
                                                         src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>">
                                                </a>
                                                <p style="height:40px;margin:10px 0 0;">
                                                    <strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
                                                </p>
                                                <p class="status">&nbsp;</p>
                                                <p class="price"><strong>
                                                    £<?php echo '<span class="price">' . $product->get_price() . '</span><small>per BOX</small>'; ?></strong>
                                                </p>

                                            </div>

                                        </li>

                                    <?php endwhile;
                                        wp_reset_postdata();
                                    else : ?>
                                        <p>
                                            <?php esc_html_e('Sorry, no posts matched your criteria.'); ?>
                                        </p>
                                    <?php endif;

                                    echo '<div class="clearfix clear-fix"></div></ul>';

                                } ?>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


<?php
get_footer();

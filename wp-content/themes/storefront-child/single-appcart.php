<?php
/**
 * Single Post Template for AppCart
 */

get_header(); // Include the header

?>


    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

			<?php

			if (have_posts()) :
				while (have_posts()) : the_post(); ?>

                    <div class="appcart-container">
                        <h2><?php the_title(); ?></h2>
                        <br>
                        <div class="appcart-content">
							<?php the_content(); ?>
                        </div>

                        <!-- Display the shortcode output -->
						<?php
						// Display the table using the custom shortcode with the current post ID
						echo do_shortcode('[table_items_appcart appcart_id="' . get_the_ID() . '" editable="false" admin="false" table_id="example" table_class="table table-striped"]');

						?>
<br>
                        <button class="button btn btn-primary confirmation-app-order disable" style="float: right;">Transfer App Order to My Orders</button>
                    </div>

				<?php endwhile;
			else :
				echo '<p>No appCart found.</p>';
			endif;

			?>
        </main>
    </div>

<?php

get_footer(); // Include the footer
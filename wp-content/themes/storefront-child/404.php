<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area">

		<main id="main" class="site-main" role="main">

			<div class="error-404 not-found">

				<div class="page-content">

					<div class="content-404">
						<img src="/wp-content/uploads/2018/10/404.png" alt="404">
						<h1><b>Sorry bad news,</b></h1>
						<h1><b>we can't find your page :(</b></h1>
						<h3>But, the good news</h3>
						<h3>that you still can jump to the <span><a href="/">Homepage</a></span></h3>
					</div>

				</div><!-- .page-content -->
			</div><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer();

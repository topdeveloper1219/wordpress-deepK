<?php
/**
 * The template for displaying category archives.
 *
 * When active, applies to all category archives.
 * To target a specific category, rename file to category-{slug/id}.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#category
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

get_header();

/*
 * Include the component stylesheet for the content.
 * This call runs only once on index and archive pages.
 * At some point, override functionality should be built in similar to the template part below.
 *
 * Note: If this was already done it will be skipped.
 */
wp_print_styles( array( 'wp-rig-content' ) );

?>
	<main id="primary" class="site-main">
		<?php
		if ( have_posts() ) {

			get_template_part( 'template-parts/content/page_header' );

			while ( have_posts() ) {
				the_post();

				get_template_part( 'template-parts/content/entry', get_post_type() );
			}

			the_posts_navigation();
		} else {
			get_template_part( 'template-parts/content/error' );
		}
		?>
	</main><!-- #primary -->
<?php
get_sidebar();
get_footer();

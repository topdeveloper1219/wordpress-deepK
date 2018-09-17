<?php
/**
 * WP Rig Hooks_Tests integration test class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Tests\Integration;

use WP_Rig\WP_Rig\Tests\Framework\Integration_Test_Case;

/**
 * Class integration-testing the hooks registered by the theme.
 *
 * @group hooks
 */
class Hooks_Tests extends Integration_Test_Case {

	/**
	 * Tests that the theme required actions are present.
	 *
	 * @param string   $hook_name Hook name.
	 * @param callable $callback  Callback attached to the hook.
	 * @param int      $priority  Optional. Hook callback priority. Default 10.
	 *
	 * @dataProvider data_added_actions
	 */
	public function test_added_actions( string $hook_name, callable $callback, int $priority = 10 ) {
		$result = has_action( $hook_name, $callback );

		$this->assertNotFalse( 'integer', $result );
		$this->assertSame( $priority, $result );
	}

	/**
	 * Gets the actions, callbacks and priorities to test for.
	 *
	 * @return array List of test datasets.
	 */
	public function data_added_actions() : array {
		return [
			[
				'after_setup_theme',
				'wp_rig_setup',
				10,
			],
			[
				'widgets_init',
				'wp_rig_widgets_init',
				10,
			],
			[
				'wp_enqueue_scripts',
				'wp_rig_styles',
				10,
			],
			[
				'wp_enqueue_scripts',
				'wp_rig_scripts',
				10,
			],
			[
				'enqueue_block_editor_assets',
				'wp_rig_gutenberg_styles',
				10,
			],
			[
				'after_setup_theme',
				'wp_rig_custom_header_setup',
				10,
			],
			[
				'wp_head',
				'wp_rig_pingback_header',
				10,
			],
			[
				'wp_head',
				'wp_rig_add_body_style',
				10,
			],
			[
				'customize_register',
				'wp_rig_customize_register',
				10,
			],
			[
				'customize_preview_init',
				'wp_rig_customize_preview_js',
				10,
			],
			[
				'wp',
				'wp_rig_lazyload_images',
				10,
			],
		];
	}

	/**
	 * Tests that the theme required filters are present.
	 *
	 * @param string   $hook_name Hook name.
	 * @param callable $callback  Callback attached to the hook.
	 * @param int      $priority  Optional. Hook callback priority. Default 10.
	 *
	 * @dataProvider data_added_filters
	 */
	public function test_added_filters( string $hook_name, callable $callback, int $priority = 10 ) {
		$result = has_filter( $hook_name, $callback );

		$this->assertNotFalse( 'integer', $result );
		$this->assertSame( $priority, $result );
	}

	/**
	 * Gets the filters, callbacks and priorities to test for.
	 *
	 * @return array List of test datasets.
	 */
	public function data_added_filters() : array {
		return [
			[
				'embed_defaults',
				'wp_rig_embed_dimensions',
				10,
			],
			[
				'wp_resource_hints',
				'wp_rig_resource_hints',
				10,
			],
			[
				'wp_calculate_image_sizes',
				'wp_rig_content_image_sizes_attr',
				10,
			],
			[
				'get_header_image_tag',
				'wp_rig_header_image_tag',
				10,
			],
			[
				'wp_get_attachment_image_attributes',
				'wp_rig_post_thumbnail_sizes_attr',
				10,
			],
			[
				'body_class',
				'wp_rig_body_classes',
				10,
			],
			[
				'script_loader_tag',
				'wp_rig_filter_script_loader_tag',
				10,
			],
			[
				'walker_nav_menu_start_el',
				'wp_rig_add_primary_menu_dropdown_symbol',
				10,
			],
			[
				'nav_menu_link_attributes',
				'wp_rig_add_nav_menu_aria_current',
				10,
			],
			[
				'page_menu_link_attributes',
				'wp_rig_add_nav_menu_aria_current',
				10,
			],
		];
	}
}

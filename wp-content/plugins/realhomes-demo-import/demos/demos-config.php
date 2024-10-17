<?php
/**
 * This file has the configuration of available demos to import, related plugins to install and
 * settings after a demo import.
 *
 * @since      1.0.0
 * @package    realhomes-demo-import
 * @subpackage realhomes-demo-import/demos
 */

/**
 * Retrieves a page given its title.
 *
 * @since 2.0.4
 *
 * @param string $page_title Page title.
 *
 * @return WP_Post|null WP_Post on success, or null on failure.
 */
function rhdi_get_page_by_title( $page_title ) {

	$args = array(
		'post_type'              => 'page',
		'title'                  => $page_title,
		'post_status'            => 'publish',
		'posts_per_page'         => 1,
		'no_found_rows'          => true,
		'ignore_sticky_posts'    => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
		'orderby'                => 'post_date ID',
		'order'                  => 'ASC',
	);

	$query = new WP_Query( $args );
	if ( ! empty( $query->post ) ) {
		return $query->post;
	}

	return null;
}

/**
 * Retrieves the demo name based on given demo index.
 *
 * @since 2.0.5
 *
 * @param string $index Demo Index.
 *
 * @return string|boolean Demo name on success, or false on failure.
 */
function rhdi_get_demo_name( $index ) {

	$all_demos = rhdi_import_files();
	if ( isset( $all_demos[ $index ] ) ) {
		return $all_demos[ $index ]['import_file_name'];
	}

	return false;
}

/**
 * Demos to Import.
 *
 * @return array[]
 */

if ( ! function_exists( 'rhdi_mime_types' ) ) {
	// Allow SVG icon
	function rhdi_mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';

		return $mimes;
	}
}

if ( ! function_exists( 'rhdi_enable_svg_permission' ) ) {
	// Enable SVG files upload for demo imports only
	function rhdi_enable_svg_permission() {
		add_filter( 'upload_mimes', 'rhdi_mime_types' );
	}

	add_action( 'import_start', 'rhdi_enable_svg_permission' );
}

function rhdi_import_files(): array {
	$path_to_demos_dir = trailingslashit( plugin_dir_path( __FILE__ ) );
	$url_to_demos_dir  = plugin_dir_url( __FILE__ );
	$demos             = array();

	// Ultra - Elementor
	$demos[] = array(
		'import_file_name'             => 'Ultra - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-ultra/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-ultra/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-ultra/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-ultra/demo.jpg',
		'preview_url'                  => 'https://ultra.realhomes.io/',
		'categories'                   => array( 'New', 'Elementor', 'Ultra' ),
		'is_new'                       => true,
	);

	// Hotel - Elementor
	$demos[] = array(
		'import_file_name'             => 'Hotel - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'hotel-ultra/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'hotel-ultra/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'hotel-ultra/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'hotel-ultra/demo.jpg',
		'preview_url'                  => 'https://ultra.realhomes.io/hotel',
		'categories'                   => array( 'New', 'Elementor', 'Ultra', 'Vacation Rentals' ),
		'is_new'                       => true,
	);

	// Agency - Elementor
	$demos[] = array(
		'import_file_name'             => 'Agency - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'agency-ultra/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'agency-ultra/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'agency-ultra/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'agency-ultra/demo.jpg',
		'preview_url'                  => 'https://ultra.realhomes.io/agency/',
		'categories'                   => array( 'New', 'Elementor', 'Ultra' ),
		'is_new'                       => true,
	);

	// Vacation Rentals Ultra - Elementor
	$demos[] = array(
		'import_file_name'             => 'Vacation Rentals Ultra - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'vacation-rentals-ultra/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'vacation-rentals-ultra/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'vacation-rentals-ultra/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'vacation-rentals-ultra/demo.jpg',
		'preview_url'                  => 'https://ultra.realhomes.io/vacation-rentals/',
		'categories'                   => array( 'New', 'Elementor', 'Ultra', 'Vacation Rentals' ),
		'is_new'                       => true,
	);

	// Condominium - Elementor
	$demos[] = array(
		'import_file_name'             => 'Condominium - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-condominium/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-condominium/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-condominium/customizer.dat',
		'local_import_slider_file'     => $path_to_demos_dir . 'elementor-condominium/realhomes-condominium-slider.zip',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-condominium/demo.jpg',
		'preview_url'                  => 'https://ultra.realhomes.io/condominium/',
		'categories'                   => array( 'New', 'Elementor', 'Ultra', 'Slider Revolution' ),
		'is_new'                       => true,
	);

	// Modern - Elementor
	$demos[] = array(
		'import_file_name'             => 'Modern - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-modern/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-modern/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-modern/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-modern/demo.jpg',
		'preview_url'                  => 'https://modern.realhomes.io/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Modern 02 - Elementor
	$demos[] = array(
		'import_file_name'             => 'Modern 02 - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-modern-02/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-modern-02/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-modern-02/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-modern-02/demo.jpg',
		'preview_url'                  => 'https://sample.realhomes.io/modern02/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Modern 03 - Elementor
	$demos[] = array(
		'import_file_name'             => 'Modern 03 - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-modern-03/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-modern-03/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-modern-03/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-modern-03/demo.jpg',
		'preview_url'                  => 'https://sample.realhomes.io/modern03/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Modern 04 - Elementor
	$demos[] = array(
		'import_file_name'             => 'Modern 04 - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-modern-04/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-modern-04/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-modern-04/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-modern-04/demo.jpg',
		'preview_url'                  => 'https://sample.realhomes.io/modern04/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Modern 05 - Elementor
	$demos[] = array(
		'import_file_name'             => 'Modern 05 - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-modern-05/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-modern-05/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-modern-05/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-modern-05/demo.jpg',
		'preview_url'                  => 'https://sample.realhomes.io/demo05/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Single Property - Elementor
	$demos[] = array(
		'import_file_name'             => 'Single Property - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'single-property/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'single-property/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'single-property/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'single-property/demo.jpg',
		'preview_url'                  => 'https://demo.realhomes.io/single-property/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Single Property 02 - Elementor
	$demos[] = array(
		'import_file_name'             => 'Single Property 02 - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'single-property-02/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'single-property-02/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'single-property-02/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'single-property-02/demo.jpg',
		'preview_url'                  => 'https://sample.realhomes.io/single-property-02/',
		'categories'                   => array( 'Elementor', 'Modern' ),
	);

	// Single Agent - Elementor
	$demos[] = array(
		'import_file_name'             => 'Single Agent - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'single-agent/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'single-agent/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'single-agent/customizer.dat',
		'local_import_slider_file'     => $path_to_demos_dir . 'single-agent/realhomes-agent-slider.zip',
		'import_preview_image_url'     => $url_to_demos_dir . 'single-agent/demo.jpg',
		'preview_url'                  => 'https://demo.realhomes.io/single-agent/',
		'categories'                   => array( 'Elementor', 'Modern', 'Slider Revolution' ),
	);

	// Vacation Rentals - Elementor
	$demos[] = array(
		'import_file_name'             => 'Vacation Rentals - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'vacation-rentals/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'vacation-rentals/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'vacation-rentals/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'vacation-rentals/demo.jpg',
		'preview_url'                  => 'https://vacation-rentals.realhomes.io/',
		'categories'                   => array( 'Elementor', 'Modern', 'Vacation Rentals' ),
	);

	// Standard Modern Demo
	$demos[] = array(
		'import_file_name'             => 'Modern',
		'local_import_file'            => $path_to_demos_dir . 'modern/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'modern/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'modern/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'modern/demo.jpg',
		'preview_url'                  => 'https://modern.realhomes.io/',
		'categories'                   => array( 'Modern' ),
	);

	// Standard Classic Demo
	$demos[] = array(
		'import_file_name'             => 'Classic',
		'local_import_file'            => $path_to_demos_dir . 'classic/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'classic/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'classic/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'classic/demo.jpg',
		'preview_url'                  => 'https://classic.realhomes.io/',
		'categories'                   => array( 'Classic' ),
	);

	// Classic - Elementor
	$demos[] = array(
		'import_file_name'             => 'Classic - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'elementor-classic/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'elementor-classic/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'elementor-classic/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'elementor-classic/demo.jpg',
		'preview_url'                  => 'https://di.realhomes.io/classic-elementor/',
		'categories'                   => array( 'Elementor', 'Classic' ),
	);

	// Español Modern - Elementor
	$demos[] = array(
		'import_file_name'             => 'Español Modern - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'spanish-elementor-modern/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'spanish-elementor-modern/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'spanish-elementor-modern/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'spanish-elementor-modern/demo.jpg',
		'preview_url'                  => 'https://demo.realhomes.io/spanish/',
		'categories'                   => array( 'Elementor', 'Modern', 'Language' ),
	);

	// Arabic (العربية) Modern - Elementor
	$demos[] = array(
		'import_file_name'             => 'Arabic (العربية) Modern - Elementor',
		'local_import_file'            => $path_to_demos_dir . 'arabic-elementor-modern/contents.xml',
		'local_import_widget_file'     => $path_to_demos_dir . 'arabic-elementor-modern/widgets.wie',
		'local_import_customizer_file' => $path_to_demos_dir . 'arabic-elementor-modern/customizer.dat',
		'import_preview_image_url'     => $url_to_demos_dir . 'arabic-elementor-modern/demo.jpg',
		'preview_url'                  => 'https://sample.realhomes.io/arabic',
		'categories'                   => array( 'Elementor', 'Modern', 'Language' ),
	);

	return $demos;
}

add_filter( 'ocdi/import_files', 'rhdi_import_files' );

/**
 * Required plugins for demo imports.
 *
 * @return array|array[]
 */
function rhdi_register_plugins( $plugins ): array {

	// Theme plugins array variable
	$theme_plugins = array();

	// Checking if RealHomes_Helper class exists which means that required theme is available and active
	if ( class_exists( 'RealHomes_Helper' ) ) {

		// list of plugins recommended by RealHomes
		$theme_plugins[] = [
			'name'        => 'Easy Real Estate',
			'description' => esc_html__( 'Provides real estate functionality for RealHomes theme.', 'realhomes-demo-import' ),
			'slug'        => 'easy-real-estate',
			'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/easy-real-estate.zip',
			'version'     => RealHomes_Helper::get_plugin_version( 'easy-real-estate' ),
			'required'    => true,
			'preselected' => true,
		];

		// Check if user is on the theme recommended plugins step and a demo was selected.
		if ( isset( $_GET['step'] ) && $_GET['step'] === 'import' && isset( $_GET['import'] ) ) {
			$demo_to_import = rhdi_get_demo_name( $_GET['import'] );

			// Demos require the 'Elementor Page Builder' and 'RealHomes Elementor Addon' plugins.
			if ( ! in_array( $demo_to_import, array( 'Modern', 'Classic' ) ) ) {
				$theme_plugins[] = [
					'name'        => esc_html__( 'Elementor Page Builder', 'realhomes-demo-import' ),
					'description' => esc_html__( 'The page builder supported by RealHomes theme.', 'realhomes-demo-import' ),
					'slug'        => 'elementor',
					'required'    => true,
					'preselected' => true,
				];

				$theme_plugins[] = [
					'name'        => 'RealHomes Elementor Addon',
					'description' => esc_html__( 'Provides RealHomes based Elementor widgets.', 'realhomes-demo-import' ),
					'slug'        => 'realhomes-elementor-addon',
					'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/realhomes-elementor-addon.zip',
					'version'     => RealHomes_Helper::get_plugin_version( 'realhomes-elementor-addon' ),
					'required'    => true,
					'preselected' => true,
				];
			}

			// Demos require the 'Slider Revolution' plugin.
			if ( in_array( $demo_to_import, array( 'Condominium - Elementor', 'Single Agent - Elementor' ) ) ) {
				$theme_plugins[] = [
					'name'        => 'Slider Revolution',
					'description' => esc_html__( 'Slider Revolution plugin.', 'realhomes-demo-import' ),
					'slug'        => 'revslider',
					'version'     => RealHomes_Helper::get_plugin_version( 'revslider' ),
					'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/revslider.zip',
					'required'    => true,
					'preselected' => true,
				];
			}

			// Demos require the 'Vacation Rentals' plugin.
			if ( in_array( $demo_to_import, array( 'Hotel - Elementor', 'Vacation Rentals - Elementor', 'Vacation Rentals Ultra - Elementor' ) ) ) {
				$theme_plugins[] = [
					'name'        => 'RealHomes Vacation Rentals',
					'description' => esc_html__( 'Provides vacation rentals functionality for RealHomes.', 'realhomes-demo-import' ),
					'slug'        => 'realhomes-vacation-rentals',
					'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/realhomes-vacation-rentals.zip',
					'version'     => RealHomes_Helper::get_plugin_version( 'realhomes-vacation-rentals' ),
					'required'    => true,
					'preselected' => true,
				];
			}

			// Fix of a bug in OCDI
			// For AJAX calls during plugins installation
			// We get slug in $_POST based on AJAX calls from installPluginsAjaxCall function in main.js file.
		} else if ( isset( $_POST['slug'] ) ) {

			if ( $_POST['slug'] == 'elementor' ) {
				$theme_plugins[] = [
					'name'        => esc_html__( 'Elementor Page Builder', 'realhomes-demo-import' ),
					'description' => esc_html__( 'The page builder supported by RealHomes theme.', 'realhomes-demo-import' ),
					'slug'        => 'elementor',
					'required'    => true,
					'preselected' => true,
				];
			}

			if ( $_POST['slug'] == 'realhomes-elementor-addon' ) {
				$theme_plugins[] = [
					'name'        => 'RealHomes Elementor Addon',
					'description' => esc_html__( 'Provides RealHomes based Elementor widgets.', 'realhomes-demo-import' ),
					'slug'        => 'realhomes-elementor-addon',
					'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/realhomes-elementor-addon.zip',
					'version'     => RealHomes_Helper::get_plugin_version( 'realhomes-elementor-addon' ),
					'required'    => true,
					'preselected' => true,
				];
			}

			if ( $_POST['slug'] == 'revslider' ) {
				$theme_plugins[] = [
					'name'        => 'Slider Revolution',
					'description' => esc_html__( 'Slider Revolution plugin.', 'realhomes-demo-import' ),
					'slug'        => 'revslider',
					'version'     => RealHomes_Helper::get_plugin_version( 'revslider' ),
					'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/revslider.zip',
					'required'    => true,
					'preselected' => true,
				];
			}

			if ( $_POST['slug'] == 'realhomes-vacation-rentals' ) {
				$theme_plugins[] = [
					'name'        => 'RealHomes Vacation Rentals',
					'description' => esc_html__( 'Provides vacation rentals functionality for RealHomes.', 'realhomes-demo-import' ),
					'slug'        => 'realhomes-vacation-rentals',
					'source'      => 'https://inspiry-plugins.s3.us-east-2.amazonaws.com/realhomes-vacation-rentals.zip',
					'version'     => RealHomes_Helper::get_plugin_version( 'realhomes-vacation-rentals' ),
					'required'    => true,
					'preselected' => true,
				];
			}

		}

		// A recommended plugin in the end
		$theme_plugins[] = [
			'name'        => 'Quick and Easy FAQs',
			'description' => esc_html__( 'Provides FAQs functionality.', 'realhomes-demo-import' ),
			'slug'        => 'quick-and-easy-faqs',
			'required'    => false,
		];
	}

	return array_merge( $plugins, $theme_plugins );
}

add_filter( 'ocdi/register_plugins', 'rhdi_register_plugins' );


/**
 * After import setup.
 *
 * @param $selected_import
 */
function rhdi_after_import_setup( $selected_import ) {

	$import_demo_name = $selected_import['import_file_name'];
	$design_variation = 'ultra';

	if ( in_array( $import_demo_name, array( 'Classic', 'Classic - Elementor' ) ) ) {
		$design_variation = 'classic';
	} else if ( in_array( $import_demo_name, array(
		'Modern',
		'Modern - Elementor',
		'Modern 02 - Elementor',
		'Modern 03 - Elementor',
		'Modern 04 - Elementor',
		'Modern 05 - Elementor',
		'Single Agent - Elementor',
		'Single Property - Elementor',
		'Single Property 02 - Elementor',
		'Vacation Rentals - Elementor',
		'Español Modern - Elementor',
		'Arabic (العربية) Modern - Elementor'
	) ) ) {
		$design_variation = 'modern';
	}

	// Update the design variation setting as per selected demo.
	update_option( 'inspiry_design_variation', $design_variation );

	// Current design variation .
	$current_variation = get_option( 'inspiry_design_variation' );

	// RealHomes theme default menu locations.
	$theme_nav_menu_locations = array(
		'main-menu',
		'responsive-menu',
	);

	// Menu title in demos.
	$menu_title = 'Main Menu';

	$set_locations = array();
	$menu          = get_term_by( 'name', $menu_title, 'nav_menu' );
	foreach ( $theme_nav_menu_locations as $nav_menu_location ) {
		if ( ! empty( $menu ) ) {
			$set_locations[ $nav_menu_location ] = $menu->term_id;
		}
	}

	// Assign menu to right location.
	set_theme_mod( 'nav_menu_locations', $set_locations );

	// Set homepage as front page and blog page as posts page.
	if ( 'Condominium - Elementor' === $import_demo_name ) {
		$home_page = rhdi_get_page_by_title( 'Condominium' );
		$blog_page = 0;

	} else if ( 'Arabic (العربية) Modern - Elementor' === $import_demo_name ) {
		$home_page = rhdi_get_page_by_title( 'الرئيسية' );
		$blog_page = rhdi_get_page_by_title( 'اتصال' );

	} else if ( 'Español Modern - Elementor' === $import_demo_name ) {
		$home_page = rhdi_get_page_by_title( 'Inicio' );
		$blog_page = rhdi_get_page_by_title( 'Blog' );

	} else if ( in_array( $import_demo_name, array( 'Hotel - Elementor', 'Vacation Rentals - Elementor', 'Vacation Rentals Ultra - Elementor' ) ) ) {
		$home_page = rhdi_get_page_by_title( 'Home' );
		$blog_page = rhdi_get_page_by_title( 'Blog' );

		// Enable RVR in its settings if it's not yet.
		$rvr_settings = get_option( 'rvr_settings' );
		$rvr_enabled  = isset( $rvr_settings['rvr_activation'] ) ? $rvr_settings['rvr_activation'] : false;

		if ( ! $rvr_enabled && class_exists( 'Realhomes_Vacation_Rentals' ) ) {
			$rvr_settings['rvr_activation'] = 1;
			update_option( 'rvr_settings', $rvr_settings );
		}

	} else if ( in_array( $import_demo_name, array(
		'Ultra - Elementor',
		'Hotel - Elementor',
		'Single Property - Elementor',
		'Single Property 02 - Elementor',
		'Single Agent - Elementor',
		'Modern - Elementor',
		'Modern 02 - Elementor',
		'Modern 03 - Elementor',
		'Modern 04 - Elementor',
		'Modern 05 - Elementor',
	) ) ) {
		$home_page = rhdi_get_page_by_title( 'Home' );
		$blog_page = rhdi_get_page_by_title( 'Blog' );

	} else {
		$home_page = rhdi_get_page_by_title( 'Home' );
		$blog_page = rhdi_get_page_by_title( 'News' );
	}

	// Show page as front
	if ( $home_page || $blog_page ) {
		update_option( 'show_on_front', 'page' );
	}

	// Set homepage as front page
	if ( $home_page ) {
		update_option( 'page_on_front', $home_page->ID );
	}

	// Set blog page as posts page
	if ( $blog_page ) {
		update_option( 'page_for_posts', $blog_page->ID );
		update_option( 'posts_per_page', 4 );
	}

	// Importing Slider Revolution Zip
	if ( in_array( $import_demo_name, array( 'Single Agent - Elementor', 'Condominium - Elementor' ) ) ) {
		if ( class_exists( 'RevSliderSliderImport' ) ) {
			$rev_slider_importer = new RevSliderSliderImport();
			$rev_slider_zip      = $selected_import['local_import_slider_file'];

			if ( file_exists( $rev_slider_zip ) ) {
				$is_template       = false;
				$single_slide      = false;
				$update_animation  = true;
				$update_navigation = true;
				$install           = true;

				if ( 'Single Agent - Elementor' === $import_demo_name ) {
					$single_slide = true;
				}

				// Finally import rev slider zip file
				$slider_import_result = $rev_slider_importer->import_slider( $update_animation, $rev_slider_zip, $is_template, $single_slide, $update_navigation, $install );

				if ( ! $slider_import_result['success'] ) {
					inspiry_log( $slider_import_result );
				}
			}
		}
	}

	// No need of migration after latest demo import.
	update_option( 'inspiry_home_settings_migration', 'true' );

	// Set fonts to Default.
	if ( in_array( $import_demo_name, array( 'Hotel - Elementor', 'Single Property - Elementor', 'Condominium - Elementor' ) ) ) {
		// Ignore custom fonts demos.
	} else {
		update_option( 'inspiry_heading_font', 'Default' );
		update_option( 'inspiry_secondary_font', 'Default' );
		update_option( 'inspiry_body_font', 'Default' );
	}

	// Set scroll to top default position
	update_option( 'inspiry_stp_position_from_bottom', '15px' );

	// Disable Elementor typography and color schemes.
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'elementor_disable_color_schemes', 'yes' );

	// Update Elementor container width.
	$get_elementor_container_width = get_option( 'elementor_container_width' );
	if ( empty( $get_elementor_container_width ) ) {
		update_option( 'elementor_container_width', 1240 );
	}
}

add_action( 'ocdi/after_import', 'rhdi_after_import_setup' );

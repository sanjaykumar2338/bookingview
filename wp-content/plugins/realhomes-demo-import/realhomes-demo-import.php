<?php
/**
 * Plugin Name: RealHomes Demo Import
 * Plugin URI: http://themeforest.net/item/real-homes-wordpress-real-estate-theme/5373914
 * Description: Import RealHomes demo contents with one click.
 * Version: 2.0.9
 * Tested up to: 6.6.2
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Author: InspiryThemes
 * Author URI: https://themeforest.net/user/inspirythemes/portfolio?order_by=sales
 * Text Domain: realhomes-demo-import
 * Domain Path: /languages
 * @package realhomes-demo-import
 */

// Block direct access to the main plugin file.
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

/**
 * Main plugin class with initialization tasks.
 */
class RHDI_Plugin {
	/**
	 * Plugin's current version
	 *
	 * @var string
	 */
	public $version;

	/**
	 * Plugin Name
	 *
	 * @var string
	 */
	public $plugin_name;

	/**
	 * Admin notice message.
	 *
	 * @var string
	 */
	public string $message;

	/**
	 * Constructor for this class.
	 */
	public function __construct( $message ) {
		/**
		 * Deactivate the OneClickDemoImport plugin if it's activated.
		 */
		add_action( 'admin_init', array( $this, 'deactivate_ocdi_plugin' ) );

		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		$plugin_info       = get_plugin_data( __FILE__ );
		$this->plugin_name = $plugin_info['Name'];
		$this->version     = $plugin_info['Version'];

		// Show notice and stop further execution of the plugin.
		if ( ! empty( $message ) ) {
			$this->message = $message;

			add_action( 'admin_notices', array( $this, 'realhomes_activation_notice' ) );

			return;
		}

		/**
		 * Display admin error message if PHP version is older than 5.6.
		 * Otherwise execute the main plugin class.
		 */
		if ( version_compare( phpversion(), '5.6', '<' ) ) {
			add_action( 'admin_notices', array( $this, 'old_php_admin_error_notice' ) );
		} else {
			// Set plugin constants.
			$this->set_plugin_constants();

			// Import demos configuration.
			require_once OCDI_PATH . 'demos/demos-config.php';

			// Composer autoloader.
			require_once OCDI_PATH . 'vendor/autoload.php';

			// Instantiate the main plugin class *Singleton*.
			$one_click_demo_import = OCDI\OneClickDemoImport::get_instance();

			// Register WP CLI commands
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::add_command( 'ocdi list', array( 'OCDI\WPCLICommands', 'list_predefined' ) );
				WP_CLI::add_command( 'ocdi import', array( 'OCDI\WPCLICommands', 'import' ) );
			}

			// Adding action links to admin plugins list page
			add_filter( 'plugin_action_links_' . OCDI_BASENAME, [ $this, 'plugin_action_links' ] );
		}
	}

	/**
	 * Displays theme activation notice on the admin screen.
	 *
	 * @return void
	 */
	public function realhomes_activation_notice() {
		printf( '<div class="notice notice-warning is-dismissible"><p><strong>%s</strong> %s</p></div>', $this->plugin_name, esc_html( $this->message ) );
	}

	/**
	 * Deactivate the OneClickDemoImport plugin if it's activated.
	 */
	public function deactivate_ocdi_plugin() {
		if ( is_plugin_active( 'one-click-demo-import/one-click-demo-import.php' ) ) {
			deactivate_plugins( 'one-click-demo-import/one-click-demo-import.php' );
			add_action( 'admin_notices', array( $this, 'admin_notice_ocdi_deactivated' ) );
		}
	}

	/**
	 * Display the OCDI plugin deactivation notice.
	 */
	public function admin_notice_ocdi_deactivated() {
		$class   = 'notice notice-error';
		$message = sprintf( esc_html__( '%1$sOne Click Demo Import%2$s plugin has been deactivated to avoid any conflict with %1$sRealHomes Demo Import%2$s plugin.', 'rhdi' ), '<strong>', '</strong>' );
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
	}

	/**
	 * Display an admin error notice when PHP is older the version 5.6.
	 * Hook it to the 'admin_notices' action.
	 */
	public function old_php_admin_error_notice() { /* translators: %1$s - the PHP version, %2$s and %3$s - strong HTML tags, %4$s - br HTMl tag. */
		$message = sprintf( esc_html__( 'The %2$sOne Click Demo Import%3$s plugin requires %2$sPHP 5.6+%3$s to run properly. Please contact your hosting company and ask them to update the PHP version of your site to at least PHP 7.4%4$s Your current version of PHP: %2$s%1$s%3$s', 'realhomes-demo-import' ), phpversion(), '<strong>', '</strong>', '<br>' );

		printf( '<div class="notice notice-error"><p>%1$s</p></div>', wp_kses_post( $message ) );
	}

	/**
	 * Set plugin constants.
	 *
	 * Path/URL to root of this plugin, with trailing slash and plugin version.
	 */
	private function set_plugin_constants() {
		// Path/URL to root of this plugin, with trailing slash.
		if ( ! defined( 'OCDI_PATH' ) ) {
			define( 'OCDI_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'OCDI_URL' ) ) {
			define( 'OCDI_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'OCDI_BASENAME' ) ) {
			define( 'OCDI_BASENAME', plugin_basename( __FILE__ ) );
		}

		// Used for backward compatibility.
		if ( ! defined( 'PT_OCDI_PATH' ) ) {
			define( 'PT_OCDI_PATH', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'PT_OCDI_URL' ) ) {
			define( 'PT_OCDI_URL', plugin_dir_url( __FILE__ ) );
		}

		// Action hook to set the plugin version constant.
		add_action( 'admin_init', array( $this, 'set_plugin_version_constant' ) );
	}

	/**
	 * Set plugin version constant -> OCDI_VERSION.
	 */
	public function set_plugin_version_constant() {
		$plugin_data = get_plugin_data( __FILE__ );

		if ( ! defined( 'OCDI_VERSION' ) ) {
			define( 'OCDI_VERSION', $plugin_data['Version'] );
		}

		// Used for backward compatibility.
		if ( ! defined( 'PT_OCDI_VERSION' ) ) {
			define( 'PT_OCDI_VERSION', $plugin_data['Version'] );
		}
	}

	/**
	 * Plugin action links.
	 *
	 * Adds action links to the plugin list table
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @since 2.0.7
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An array of plugin action links.
	 */
	public function plugin_action_links( $links ) {
		$demos_list = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=realhomes-demo-import' ), esc_html__( 'Demos List', 'realhomes-demo-import' ) );

		array_unshift( $links, $demos_list );

		return $links;
	}
}

/**
 * Initializes the RealHomes Demo Import Plugin.
 *
 * @return void
 */
function RHDI_init() {
	$notice = '';

	// Check if the activated theme is not RealHomes or RealHomes Child.
	$theme = wp_get_theme();
	if ( ! in_array( $theme->get( 'Name' ), array( 'RealHomes', 'RealHomes Child Theme' ) ) ) {
		$notice = esc_html__( 'plugin requires the RealHomes theme to be activated in order to function properly. Please activate the RealHomes theme to ensure optimal performance and avoid any inconsistencies.', 'realhomes-demo-import' );
	}

	// Instantiate the plugin class.
	new RHDI_Plugin( $notice );
}

// Run the plugin.
RHDI_init();
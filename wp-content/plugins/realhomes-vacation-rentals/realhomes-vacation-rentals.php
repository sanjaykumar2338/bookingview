<?php
/**
 * Plugin Name:         RealHomes Vacation Rentals
 * Plugin URI:          https://themeforest.net/item/real-homes-wordpress-real-estate-theme/5373914
 * Description:         Add vacation rentals functionality to RealHomes theme by InspiryThemes.
 * Author:              InspiryThemes
 * Author URI:          https://inspirythemes.com/
 * Text Domain:         realhomes-vacation-rentals
 * Version:             1.4.4
 * Tested up to:        6.6.2
 * Requires at least:   6.0
 * Requires PHP:        7.4
 * License:             GPL v2+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Realhomes_Vacation_Rentals' ) ) {
	/**
	 * Class Easy_Real_Estate
	 *
	 * Plugin's main class.
	 *
	 * @since 1.0.0
	 */
	final class Realhomes_Vacation_Rentals {
		/**
		 * Realhomes Vacation Rentals Version
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
		 * Single instance of Class.
		 *
		 * @since 1.0.0
		 * @var Realhomes_Vacation_Rentals
		 */
		protected static $_instance;

		/**
		 * Provides singleton instance.
		 *
		 * @since 1.0.0
		 */
		public static function instance( $message ) {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self( $message );
			}

			return self::$_instance;
		}

		/**
		 * Realhomes_Vacation_Rentals constructor.
		 * @since 1.0.0
		 */
		public function __construct( $message ) {
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

			$this->define_constants();
			$this->includes();
			$this->init_hooks();

			// Realhomes Vacations Rentals plugin loaded action hook
			do_action( 'rvr_loaded' );
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
		 * Define constants.
		 *
		 * @since 1.0.0
		 */
		protected function define_constants() {

			if ( ! defined( 'RVR_VERSION' ) ) {
				define( 'RVR_VERSION', $this->version );
			}

			// Full path and filename
			if ( ! defined( 'RVR_PLUGIN_FILE' ) ) {
				define( 'RVR_PLUGIN_FILE', __FILE__ );
			}

			// Plugin directory path
			if ( ! defined( 'RVR_PLUGIN_DIR' ) ) {
				define( 'RVR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin directory URL
			if ( ! defined( 'RVR_PLUGIN_URL' ) ) {
				define( 'RVR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin file path relative to plugins directory
			if ( ! defined( 'RVR_PLUGIN_BASENAME' ) ) {
				define( 'RVR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			}

		}

		/**
		 * Includes files required on admin and on frontend.
		 *
		 * @since 1.0.0
		 */
		public function includes() {

			require_once RVR_PLUGIN_DIR . 'include/realhomes-vacation-rentals-include.php';

			require_once RVR_PLUGIN_DIR . 'admin/realhomes-vacation-rentals-admin.php';

			new Realhomes_Vacation_Rentals_Admin();

			if ( class_exists( 'ERE_Subscription_API' ) && ERE_Subscription_API::status() ) {
				require_once RVR_PLUGIN_DIR . 'admin/plugin-update.php';   // plugin update functions.
			}

		}


		/**
		 * Initialize hooks.
		 *
		 * @since 1.0.0
		 */
		public function init_hooks() {
			add_action( 'init', array( $this, 'init' ) );

			// Adding action links to admin plugins list page
			add_filter( 'plugin_action_links_' . RVR_PLUGIN_BASENAME, [ $this, 'plugin_action_links' ] );
		}

		/**
		 * Initialize plugin.
		 *
		 * @since 1.0.0
		 */
		public function init() {
			// before init action
			do_action( 'before_rvr_init' );

			// load text domain for translation.
			$this->load_plugin_textdomain();

			// done with init
			do_action( 'rvr_init' );
		}

		/**
		 * Load text domain for translation.
		 * @since 1.0.0
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'realhomes-vacation-rentals', false, dirname( RVR_PLUGIN_BASENAME ) . '/languages/' );
		}

		/**
		 * Plugin action links.
		 *
		 * Adds action links to the plugin list table
		 *
		 * Fired by `plugin_action_links` filter.
		 *
		 * @since 1.3.9
		 *
		 * @param array $links An array of plugin action links.
		 *
		 * @return array An array of plugin action links.
		 */
		public function plugin_action_links( $links ) {
			$settings_link      = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=rvr-settings' ), esc_html__( 'Settings', 'realhomes-vacation-rentals' ) );
			$documentation_link = sprintf( '<a href="%1$s" target="_blank">%2$s</a>', 'https://realhomes.io/documentation/vr-settings/', esc_html__( 'Documentation', 'realhomes-vacation-rentals' ) );

			array_unshift( $links, $settings_link, $documentation_link );

			return $links;
		}
	}
}

if ( ! function_exists( 'rvr_is_wc_payment_enabled' ) ) {
	/**
	 * Check if woocommerce payments method enabled.
	 */
	function rvr_is_wc_payment_enabled() {

		$rvr_settings   = get_option( 'rvr_settings' );
		$payment_method = '';

		if ( ! empty( $rvr_settings['rvr_wc_payments'] ) ) {
			$payment_method = $rvr_settings['rvr_wc_payments'];
		}

		if ( rvr_is_enabled() && class_exists( 'WooCommerce' ) && class_exists( 'Realhomes_WC_Payments_Addon' ) && $payment_method ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'rvr_get_booking_mode' ) ) {
	/**
	 * Get the booking mode to initialize the payment process accordingly.
	 *
	 * @since 1.4.2
	 *
	 * @param $property_id
	 *
	 * @return bool|mixed
	 */
	function rvr_get_booking_mode( $property_id = '' ) {
		$rvr_settings = get_option( 'rvr_settings' );

		if ( ! empty( $rvr_settings['rvr_wc_payments_scope'] ) ) {
			if ( 'global' === $rvr_settings['rvr_wc_payments_scope'] ) {
				return $rvr_settings['rvr_booking_mode'];
			}

			if ( 'property' === $rvr_settings['rvr_wc_payments_scope'] && ! empty( $property_id ) ) {
				$property_booking_mode = get_post_meta( $property_id, 'rvr_booking_mode', true );
				if ( $property_booking_mode ) {
					return $property_booking_mode;
				} else {
					return $rvr_settings['rvr_booking_mode'];
				}
			}
		}

		return false;
	}
}

/**
 * Main instance of Realhomes_Vacation_Rentals.
 *
 * Returns the main instance of Realhomes_Vacation_Rentals to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Realhomes_Vacation_Rentals
 */
function RVR() {
	$notice = '';

	// Check if the activated theme is not RealHomes or RealHomes Child.
	$theme = wp_get_theme();
	if ( ! in_array( $theme->get( 'Name' ), array( 'RealHomes', 'RealHomes Child Theme' ) ) ) {
		$notice = esc_html__( 'plugin requires the RealHomes theme to be activated in order to function properly. Please activate the RealHomes theme to ensure optimal performance and avoid any inconsistencies.', 'realhomes-vacation-rentals' );
	}
	
	return Realhomes_Vacation_Rentals::instance( $notice );
}

/**
 * Get RVR Running - Only if ERE plugin is activated.
 */
if ( class_exists( 'Easy_Real_Estate' ) ) {
	RVR();
}
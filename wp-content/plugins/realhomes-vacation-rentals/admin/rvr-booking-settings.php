<?php
/**
 * This file add and manage realhomes vacation rentals settings.
 *
 * @package    realhomes_vacation_rentals
 * @subpackage realhomes_vacation_rentals/admin
 */

if ( ! class_exists( 'RVR_Settings_Page' ) ) {
	/**
	 * Class RVR_Settings_Page
	 */
	class RVR_Settings_Page {

		function __construct() {

			// update settings to default initially.
			$rvr_settings = get_option( 'rvr_settings' );

			$rvr_settings_default = array(
				'rvr_activation'                      => '0',
				'rvr_contact_phone'                   => '',
				'rvr_contact_page'                    => '',
				'rvr_notification_email'              => get_option( 'admin_email' ),
				'rvr_owner_notification'              => '0',
				'rvr_booking_type'                    => 'full_day',
				'rvr_terms_info'                      => '0',
				'rvr_terms_anchor_text'               => '',
				'rvr_outdoor_features_label'          => esc_html__( 'Outdoor Features', 'realhomes-vacation-rentals' ),
				'rvr_optional_services_label'         => esc_html__( 'Services', 'realhomes-vacation-rentals' ),
				'rvr_optional_services_inc_label'     => esc_html__( 'What is included', 'realhomes-vacation-rentals' ),
				'rvr_optional_services_not_inc_label' => esc_html__( 'What is not included', 'realhomes-vacation-rentals' ),
				'rvr_property_policies_label'         => esc_html__( 'Property Rules', 'realhomes-vacation-rentals' ),
				'rvr_surroundings_label'              => esc_html__( 'Surroundings', 'realhomes-vacation-rentals' ),
				'rvr_date_format_method'              => 'default',
				'rvr_date_format_custom'              => 'YYYY-MM-DD',
				'rvr_wc_payments'                     => '0',
				'rvr_booking_mode'                    => 'deferred',
				'rvr_wc_payments_scope'               => 'property',
				'rvr_instant_booking_button_label'    => esc_html__( 'Instant Booking', 'realhomes-vacation-rentals' ),
			);

			if ( ! $rvr_settings ) {
				update_option( 'rvr_settings', $rvr_settings_default );
			} else {
				foreach ( $rvr_settings_default as $key => $value ) {
					if ( ! isset( $rvr_settings[ $key ] ) ) {
						$rvr_settings[ $key ] = $value;
					}
				}
				update_option( 'rvr_settings', $rvr_settings );
			}
		}

		public function rvr_add_admin_menu() {
			add_submenu_page(
				'edit.php?post_type=booking',
				esc_html__( 'Settings', 'realhomes-vacation-rentals' ),
				esc_html__( 'Settings', 'realhomes-vacation-rentals' ),
				'manage_options',
				'rvr-settings',
				array( $this, 'rvr_options_page' )
			);
		}

		/**
		 * Register Realhomes Vacation Rentals settings sub menu under Real Homes dashboard menu
		 *
		 * @param $sub_menus
		 *
		 * @return array
		 */
		public function rvr_sub_menus( $sub_menus ) {

			$new_menu_item = array(
				'rvr_settings' => array(
					'rvr',
					esc_html__( 'Settings', 'realhomes-vacation-rentals' ),
					esc_html__( 'Settings', 'realhomes-vacation-rentals' ),
					'edit_posts',
					'admin.php?page=rvr-settings',
				),
			);

			$key   = 'owner';
			$keys  = array_keys( $sub_menus );
			$index = array_search( $key, $keys );
			$pos   = false === $index ? count( $sub_menus ) : $index + 1;

			return array_merge( array_slice( $sub_menus, 0, $pos ), $new_menu_item, array_slice( $sub_menus, $pos ) );
		}

		/**
		 * Open Bookings menu when on a booking post edit page.
		 *
		 * @param $sub_menus
		 *
		 * @return array
		 */
		public function rvr_real_homes_open_menus_slugs( $sub_menus ) {
			$new_menu_item_slug = array(
				'admin_page_rvr-settings',
			);

			$key   = 'owner';
			$keys  = array_keys( $sub_menus );
			$index = array_search( $key, $keys );
			$pos   = false === $index ? count( $sub_menus ) : $index + 1;

			return array_merge( array_slice( $sub_menus, 0, $pos ), $new_menu_item_slug, array_slice( $sub_menus, $pos ) );
		}

		public function rvr_options_page() {
			$current_tab = 'general';
			if ( isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->tabs() ) ) {
				$current_tab = $_GET['tab'];
			}
			?>
            <style>
                .wp-admin #realhomes-settings-wrap .form-wrapper > div:not(.rvr-settings-<?php echo $current_tab; ?>-section) {
                    display: none;
                }
            </style>
            <div id="realhomes-settings-wrap">
                <form action='options.php' method='post'>
                    <header class="settings-header">
                        <h1><?php esc_html_e( 'Realhomes Vacation Rentals Settings', 'realhomes-vacation-rentals' ); ?><span class="current-version-tag"><?php echo RVR_VERSION; ?></span></h1>
                        <p class="credit">
                            <a class="logo-wrap" href="https://themeforest.net/item/real-homes-wordpress-real-estate-theme/5373914?aid=inspirythemes" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" height="29" width="29" viewBox="0 0 36 41">
                                    <style>
                                        .a {
                                            fill: #4E637B;
                                        }
                                        .b {
                                            fill: white;
                                        }
                                        .c {
                                            fill: #27313D !important;
                                        }
                                    </style><g>
                                        <path d="M25.5 14.6C28.9 16.6 30.6 17.5 34 19.5L34 11.1C34 10.2 33.5 9.4 32.8 9 30.1 7.5 28.4 6.5 25.5 4.8L25.5 14.6Z" class="a"></path>
                                        <path d="M15.8 38.4C16.5 38.8 17.4 38.8 18.2 38.4 20.8 36.9 22.5 35.9 25.5 34.2 22.1 32.2 20.4 31.3 17 29.3 13.6 31.3 11.9 32.2 8.5 34.2 11.5 35.9 13.1 36.9 15.8 38.4" mask="url(#mask-2)" class="a"></path>
                                        <path d="M24.3 25.1C25 24.7 25.5 23.9 25.5 23L25.5 14.6 17 19.5 17 29.3 24.3 25.1Z" fill="#C8ED1E"></path>
                                        <path d="M18.2 10.4C17.4 10 16.5 10 15.8 10.4L8.5 14.6 17 19.5 25.5 14.6 18.2 10.4Z" fill="#F9FAF8"></path>
                                        <path d="M8.5 23C8.5 23.9 8.9 24.7 9.7 25.1L17 29.3 17 19.5 8.5 14.6 8.5 23Z" fill="#88B2D7"></path>
                                        <path d="M8.5 14.6C5.1 16.6 3.4 17.5 0 19.5L0 11.1C0 10.2 0.5 9.4 1.2 9 3.8 7.5 5.5 6.5 8.5 4.8L8.5 14.6Z" mask="url(#mask-4)" class="a"></path>
                                        <path d="M34 27.9L34 19.5 25.5 14.6 25.5 23C25.5 23.4 25.4 23.8 25.1 24.2L33.6 29.1C33.8 28.7 34 28.3 34 27.9" fill="#5E9E2D"></path>
                                        <path d="M25.1 24.2C24.9 24.6 24.6 24.9 24.3 25.1L17 29.3 25.5 34.2 32.8 30C33.1 29.8 33.4 29.5 33.6 29.1L25.1 24.2Z" fill="#6FBF2C"></path>
                                        <path d="M17 10.1C17.4 10.1 17.8 10.2 18.2 10.4L25.5 14.6 25.5 4.8 18.2 0.6C17.8 0.4 17.4 0.3 17 0.3L17 10.1Z" fill="#BDD2E1"></path>
                                        <path d="M1.2 30L8.5 34.2 17 29.3 9.7 25.1C9.3 24.9 9 24.6 8.8 24.2L0.3 29.1C0.5 29.5 0.8 29.8 1.2 30" fill="#418EDA"></path>
                                        <path d="M8.8 24.2C8.6 23.8 8.5 23.4 8.5 23L8.5 14.6 0 19.5 0 27.9C0 28.3 0.1 28.7 0.3 29.1L8.8 24.2Z" fill="#3570AA"></path>
                                        <path d="M15.8 0.6L8.5 4.8 8.5 14.6 15.8 10.4C16.2 10.2 16.6 10.1 17 10.1L17 0.3C16.6 0.3 16.2 0.4 15.8 0.6" fill="#A7BAC8"></path>
                                    </g>
                                </svg>InspiryThemes
                            </a>
                        </p>
                    </header>
                    <div class="settings-content">
						<?php
						settings_errors();

						$this->tabs_nav( $current_tab );
						?>
                        <div class="form-wrapper">
							<?php
							settings_fields( 'rvr_settings_page' );
							do_settings_sections( 'rvr_settings_page' );
							submit_button();
							?>
                        </div>
                    </div>
                    <footer class="settings-footer">
                        <p>
                            <span class="dashicons dashicons-editor-help"></span>
							<?php printf( esc_html__( 'For help, please consult the %1$s documentation %2$s of the plugin.', 'realhomes-vacation-rentals' ), '<a href="https://realhomes.io/documentation/vr-settings/" target="_blank">', '</a>' ); ?>
                        </p>
                        <p>
                            <span class="dashicons dashicons-feedback"></span>
							<?php printf( esc_html__( 'For feedback, please provide your %1$s feedback here! %2$s', 'realhomes-vacation-rentals' ), '<a href="' . esc_url( add_query_arg( array( 'page' => 'realhomes-feedback' ), get_admin_url() . 'admin.php' ) ) . '" target="_blank">', '</a>' ); ?>
                        </p>
                    </footer>
                </form>
            </div>
			<?php
		}

		public function rvr_settings_init() {
			register_setting( 'rvr_settings_page', 'rvr_settings' );

			add_settings_section(
				'rvr_settings_general_section',
				'', // no heading.
				'', // no callback function.
				'rvr_settings_page',
				array(
					'before_section' => '<div class="rvr-settings-general-section">',
					'after_section'  => '</div>',
				)
			);

			add_settings_section(
				'rvr_settings_contact_section',
				'',
				'', // no callback function.
				'rvr_settings_page',
				array(
					'before_section' => '<div class="rvr-settings-contact_notify-section">',
					'after_section'  => '</div>',
				)
			);

			add_settings_section(
				'rvr_settings_terms_section',
				'',
				'', // no callback function.
				'rvr_settings_page',
				array(
					'before_section' => '<div class="rvr-settings-ts_cs-section">',
					'after_section'  => '</div>',
				)
			);

			add_settings_section(
				'rvr_settings_labels_section',
				'',
				'', // no callback function.
				'rvr_settings_page',
				array(
					'before_section' => '<div class="rvr-settings-labels-section">',
					'after_section'  => '</div>',
				)
			);

			add_settings_section(
				'rvr_settings_date_format_section',
				'',
				'', // no callback function.
				'rvr_settings_page',
				array(
					'before_section' => '<div class="rvr-settings-date_format-section">',
					'after_section'  => '</div>',
				)
			);

			if ( class_exists( 'WooCommerce' ) && class_exists( 'Realhomes_WC_Payments_Addon' ) ) {
				add_settings_section(
					'rvr_settings_payment_section',
					'',
					'', // no callback function.
					'rvr_settings_page',
					array(
						'before_section' => '<div class="rvr-settings-booking_payments-section">',
						'after_section'  => '</div>',
					)
				);
			}

			// Settings Fields
			add_settings_field(
				'rvr_activation_button',
				esc_html__( 'Realhomes Vacation Rentals', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_activation_button_render' ),
				'rvr_settings_page',
				'rvr_settings_general_section'
			);

			add_settings_field(
				'rvr_max_guests',
				esc_html__( 'Max Guests', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'max_guests_render' ),
				'rvr_settings_page',
				'rvr_settings_general_section'
			);

			add_settings_field(
				'rvr_booking_type',
				esc_html__( 'Booking Type', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_booking_type_render' ),
				'rvr_settings_page',
				'rvr_settings_general_section'
			);

			add_settings_field(
				'rvr_contact_phone_field',
				esc_html__( 'Booking Phone Number', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_contact_phone_render' ),
				'rvr_settings_page',
				'rvr_settings_contact_section'
			);

			add_settings_field(
				'rvr_contact_page_field',
				esc_html__( 'Contact Page', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_contact_page_render' ),
				'rvr_settings_page',
				'rvr_settings_contact_section'
			);

			add_settings_field(
				'rvr_notification_email_field',
				esc_html__( 'Booking Email', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_notification_email_render' ),
				'rvr_settings_page',
				'rvr_settings_contact_section'
			);

			add_settings_field(
				'rvr_owner_notification_button',
				esc_html__( 'Booking Request Notification for Owner', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_owner_notification_render' ),
				'rvr_settings_page',
				'rvr_settings_contact_section'
			);

			add_settings_field(
				'rvr_terms_button',
				esc_html__( 'Terms & Conditions Info', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_terms_button_render' ),
				'rvr_settings_page',
				'rvr_settings_terms_section'
			);

			add_settings_field(
				'rvr_terms_anchor_text_field',
				esc_html__( 'Terms & Conditions Text', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_terms_anchor_text_render' ),
				'rvr_settings_page',
				'rvr_settings_terms_section'
			);

			add_settings_field(
				'rvr_outdoor_features_label',
				esc_html__( 'Label for Outdoor Features', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_outdoor_features_label_render' ),
				'rvr_settings_page',
				'rvr_settings_labels_section'
			);

			add_settings_field(
				'rvr_optional_services_label',
				esc_html__( 'Labels for Optional Services', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_optional_services_label_render' ),
				'rvr_settings_page',
				'rvr_settings_labels_section'
			);

			add_settings_field(
				'rvr_property_policies_label',
				esc_html__( 'Label for Property Policies', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_property_policies_label_render' ),
				'rvr_settings_page',
				'rvr_settings_labels_section'
			);

			add_settings_field(
				'rvr_surroundings_label',
				esc_html__( 'Label for Surroundings', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_surroundings_label_render' ),
				'rvr_settings_page',
				'rvr_settings_labels_section'
			);

			add_settings_field(
				'rvr_date_format_method',
				esc_html__( 'Date Format', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_date_format_method_render' ),
				'rvr_settings_page',
				'rvr_settings_date_format_section'
			);

			add_settings_field(
				'rvr_date_format_custom',
				esc_html__( 'Custom Date Format', 'realhomes-vacation-rentals' ),
				array( __CLASS__, 'rvr_custom_date_format_render' ),
				'rvr_settings_page',
				'rvr_settings_date_format_section'
			);

			// Add payment and instant booking options if "WooCommerce" and "RealHomes WooCommerce Payments Addon" plugins are active.
			if ( class_exists( 'WooCommerce' ) && class_exists( 'Realhomes_WC_Payments_Addon' ) ) {
				add_settings_field(
					'rvr_booking_payment_button',
					esc_html__( 'Enable WooCommerce Payments for Bookings', 'realhomes-vacation-rentals' ),
					array( __CLASS__, 'rvr_payment_button_render' ),
					'rvr_settings_page',
					'rvr_settings_payment_section'
				);

				add_settings_field(
					'rvr_booking_mode',
					esc_html__( 'Booking Mode', 'realhomes-vacation-rentals' ),
					array( __CLASS__, 'rvr_booking_mode_render' ),
					'rvr_settings_page',
					'rvr_settings_payment_section'
				);

				add_settings_field(
					'rvr_instant_booking_scope',
					esc_html__( 'Booking Scope', 'realhomes-vacation-rentals' ),
					array( __CLASS__, 'rvr_payment_scope_render' ),
					'rvr_settings_page',
					'rvr_settings_payment_section'
				);

				add_settings_field(
					'rvr_instant_booking_button_label',
					esc_html__( 'Instant Booking Button Label', 'realhomes-vacation-rentals' ),
					array( __CLASS__, 'rvr_instant_booking_button_label' ),
					'rvr_settings_page',
					'rvr_settings_payment_section'
				);
			}
		}

		public static function rvr_activation_button_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_activation_enable">
                <input type='radio' id="rvr_activation_enable" name='rvr_settings[rvr_activation]' <?php checked( $options['rvr_activation'], 1 ); ?> value='1'>
				<?php esc_html_e( 'Enable', 'realhomes-vacation-rentals' ); ?>
            </label><br><label for="rvr_activation_disable">
                <input type='radio' id="rvr_activation_disable" name='rvr_settings[rvr_activation]' <?php checked( $options['rvr_activation'], 0 ); ?> value='0'>
				<?php esc_html_e( 'Disable', 'realhomes-vacation-rentals' ); ?>
            </label><p class="description"><?php esc_html_e( 'Enabling this will replace real estate functionality with vacation rentals functionality.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_contact_phone_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type='text' name='rvr_settings[rvr_contact_phone]' value='<?php echo esc_attr( $options['rvr_contact_phone'] ); ?>'><p class="description"><?php esc_html_e( 'To receive booking calls as it will be displayed on front-end booking widget.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_contact_page_render() {
			$options = get_option( 'rvr_settings' );
			wp_dropdown_pages(
				array(
					'name'             => 'rvr_settings[rvr_contact_page]',
					'selected'         => $options['rvr_contact_page'],
					'show_option_none' => esc_html__( 'None', 'realhomes-vacation-rentals' ),
				)
			);
			?>
            <p class="description"><?php esc_html_e( 'Select a page where users can ask Pre-Booking questions (e.g: contact page).', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_notification_email_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type='email' name='rvr_settings[rvr_notification_email]' value='<?php echo sanitize_email( $options['rvr_notification_email'] ); ?>'>
            <p class="description"><?php esc_html_e( 'Provide an email address to receive notifications for new booking requests. If no email is provided, the admin email will be used to receive notifications.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function max_guests_render() {
			$rvr_settings               = get_option( 'rvr_settings' );
			$rvr_settings['max_guests'] = isset( $rvr_settings['max_guests'] ) ? $rvr_settings['max_guests'] : 10; // On fresh installation set 10 as default.
			?>
            <select name="rvr_settings[max_guests]">
                <option value="10"<?php selected( $rvr_settings['max_guests'], '10' ); ?>><?php esc_html_e( '10', 'realhomes-vacation-rentals' ); ?></option>
                <option value="15"<?php selected( $rvr_settings['max_guests'], '15' ); ?>><?php esc_html_e( '15', 'realhomes-vacation-rentals' ); ?></option>
                <option value="20"<?php selected( $rvr_settings['max_guests'], '20' ); ?>><?php esc_html_e( '20', 'realhomes-vacation-rentals' ); ?></option>
                <option value="25"<?php selected( $rvr_settings['max_guests'], '25' ); ?>><?php esc_html_e( '25', 'realhomes-vacation-rentals' ); ?></option>
                <option value="30"<?php selected( $rvr_settings['max_guests'], '30' ); ?>><?php esc_html_e( '30', 'realhomes-vacation-rentals' ); ?></option>
            </select><p class="description"><?php esc_html_e( 'Select number of maximum guests, that a renter can choose in search and booking forms.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_owner_notification_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_owner_notification_enable">
                <input type='radio' id="rvr_owner_notification_enable" name='rvr_settings[rvr_owner_notification]' <?php checked( $options['rvr_owner_notification'], 1 ); ?> value='1'>
				<?php esc_html_e( 'Enable', 'realhomes-vacation-rentals' ); ?>
            </label><br><label for="rvr_owner_notification_disable">
                <input type='radio' id="rvr_owner_notification_disable" name='rvr_settings[rvr_owner_notification]' <?php checked( $options['rvr_owner_notification'], 0 ); ?> value='0'>
				<?php esc_html_e( 'Disable', 'realhomes-vacation-rentals' ); ?>
            </label>
			<?php
		}

		/**
		 * @since 4.1.1
		 * @return void
		 */
		public static function rvr_booking_type_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_booking_type_full_day">
                <input type='radio' id="rvr_booking_type_full_day" name='rvr_settings[rvr_booking_type]' <?php checked( $options['rvr_booking_type'], 'full_day' ); ?> value='full_day'>
				<?php esc_html_e( 'Full Day', 'realhomes-vacation-rentals' ); ?>
            </label><br><label for="rvr_booking_type_split_day">
                <input type='radio' id="rvr_booking_type_split_day" name='rvr_settings[rvr_booking_type]' <?php checked( $options['rvr_booking_type'], 'split_day' ); ?> value='split_day'>
				<?php esc_html_e( 'Split Day', 'realhomes-vacation-rentals' ); ?>
            </label>
            <p class="description"><?php esc_html_e( 'Choose your preferred booking type. If you select the Split Day option, the calendar will not show reserved dates on the booking form, allowing more flexibility in selecting check-in and check-out dates.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_terms_button_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_terms_info_enable">
                <input type='radio' id="rvr_terms_info_enable" name='rvr_settings[rvr_terms_info]' <?php checked( $options['rvr_terms_info'], 1 ); ?> value='1'>
				<?php esc_html_e( 'Show', 'realhomes-vacation-rentals' ); ?>
            </label><br><label for="rvr_terms_info_disable">
                <input type='radio' id="rvr_terms_info_disable" name='rvr_settings[rvr_terms_info]' <?php checked( $options['rvr_terms_info'], 0 ); ?> value='0'>
				<?php esc_html_e( 'Hide', 'realhomes-vacation-rentals' ); ?>
            </label><p class="description"><?php esc_html_e( 'If shown, user must accept it for booking form submission.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_terms_anchor_text_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <textarea name='rvr_settings[rvr_terms_anchor_text]' cols="50" rows="5"><?php echo esc_html( $options['rvr_terms_anchor_text'] ); ?></textarea>
            <p class="description"><?php echo sprintf( esc_html__( 'Provide Terms and Conditions option label text that will be displayed on booking form. You may use %s tag in your text to link separate Terms and Conditions page.', 'realhomes-vacation-rentals' ), '<a href="https://www.w3schools.com/tags/tag_a.asp" target="_blank">' . htmlspecialchars( '<a>' ) . '</a>' ); ?></p>
			<?php
		}

		public static function rvr_outdoor_features_label_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type='text' name='rvr_settings[rvr_outdoor_features_label]' value='<?php echo esc_attr( $options['rvr_outdoor_features_label'] ); ?>'>
			<?php
		}

		public static function rvr_optional_services_label_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <p class="description">
                <input type='text' name='rvr_settings[rvr_optional_services_label]' value='<?php echo esc_attr( $options['rvr_optional_services_label'] ); ?>'>
				<?php esc_html_e( 'Provide "Optional Services" section label.', 'realhomes-vacation-rentals' ); ?>
            </p><br><p class="description">
                <input type='text' name='rvr_settings[rvr_optional_services_inc_label]' value='<?php echo esc_attr( $options['rvr_optional_services_inc_label'] ); ?>'>
				<?php esc_html_e( 'Provide "Included" sub-section label.', 'realhomes-vacation-rentals' ); ?>
            </p><br><p class="description">
                <input type='text' name='rvr_settings[rvr_optional_services_not_inc_label]' value='<?php echo esc_attr( $options['rvr_optional_services_not_inc_label'] ); ?>'>
				<?php esc_html_e( 'Provide "Not Included" sub-section label.', 'realhomes-vacation-rentals' ); ?>
            </p>
			<?php
		}

		public static function rvr_property_policies_label_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type='text' name='rvr_settings[rvr_property_policies_label]' value='<?php echo esc_attr( $options['rvr_property_policies_label'] ); ?>'>
			<?php
		}

		public static function rvr_surroundings_label_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type="text" name="rvr_settings[rvr_surroundings_label]" value="<?php echo esc_attr( $options['rvr_surroundings_label'] ); ?>">
			<?php
		}

		public static function rvr_payment_button_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_wc_payments_enable">
                <input type='radio' id="rvr_wc_payments_enable" name='rvr_settings[rvr_wc_payments]' <?php checked( $options['rvr_wc_payments'], 1 ); ?> value='1'>
				<?php esc_html_e( 'Enable', 'realhomes-vacation-rentals' ); ?>
            </label>
            <br>
            <label for="rvr_wc_payments_disable">
                <input type='radio' id="rvr_wc_payments_disable" name='rvr_settings[rvr_wc_payments]' <?php checked( $options['rvr_wc_payments'], 0 ); ?> value='0'>
				<?php esc_html_e( 'Disable', 'realhomes-vacation-rentals' ); ?>
            </label>
            <p class="description"><?php esc_html_e( 'Enable WooCommerce payments for the bookings on your website.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_booking_mode_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_booking_mode_instant">
                <input type='radio' id="rvr_booking_mode_instant" name='rvr_settings[rvr_booking_mode]' <?php checked( $options['rvr_booking_mode'], 'instant' ); ?> value='instant'>
				<?php esc_html_e( 'Instant Payment', 'realhomes-vacation-rentals' ); ?>
            </label>
            <p class="description"><?php esc_html_e( 'Payment is made immediately, and booking is confirmed automatically.', 'realhomes-vacation-rentals' ); ?></p><br>
            <label for="rvr_booking_mode_deferred">
                <input type='radio' id="rvr_booking_mode_deferred" name='rvr_settings[rvr_booking_mode]' <?php checked( $options['rvr_booking_mode'], 'deferred' ); ?> value='deferred'>
				<?php esc_html_e( 'Deferred Payment', 'realhomes-vacation-rentals' ); ?>
            </label>
            <p class="description"><?php esc_html_e( 'Booking is made, and the renter can only pay to confirm it after the property owner issues the invoice.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_payment_scope_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_wc_payments_scope_global">
                <input type='radio' id="rvr_wc_payments_scope_global" name='rvr_settings[rvr_wc_payments_scope]' <?php checked( $options['rvr_wc_payments_scope'], 'global' ); ?> value='global'>
				<?php esc_html_e( 'For All Properties', 'realhomes-vacation-rentals' ); ?>
            </label>
            <br>
            <label for="rvr_wc_payments_scope_individual">
                <input type='radio' id="rvr_wc_payments_scope_individual" name='rvr_settings[rvr_wc_payments_scope]' <?php checked( $options['rvr_wc_payments_scope'], 'property' ); ?> value='property'>
				<?php esc_html_e( 'For Individual Property', 'realhomes-vacation-rentals' ); ?>
            </label>
            <p class="description"><?php esc_html_e( 'If you choose "For Individual Property" option then you would need to choose the "Booking Mode" for properties individually from their add/edit page.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_instant_booking_button_label() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type='text' name='rvr_settings[rvr_instant_booking_button_label]' value='<?php echo esc_attr( $options['rvr_instant_booking_button_label'] ); ?>'><p class="description"><?php esc_html_e( 'This booking button label will be display for only instant booking enabled properties.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_date_format_method_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <label for="rvr_date_format_default">
                <input type='radio' id="rvr_date_format_default" name='rvr_settings[rvr_date_format_method]' <?php checked( $options['rvr_date_format_method'], 'default' ); ?> value='default'>
				<?php esc_html_e( 'Default', 'realhomes-vacation-rentals' ); ?>
            </label><br><label for="rvr_date_format_wordpress">
                <input type='radio' id="rvr_date_format_wordpress" name='rvr_settings[rvr_date_format_method]' <?php checked( $options['rvr_date_format_method'], 'wordpress' ); ?> value='wordpress'>
				<?php esc_html_e( 'WordPress', 'realhomes-vacation-rentals' ); ?>
            </label><br><label for="rvr_date_format_custom">
                <input type='radio' id="rvr_date_format_custom" name='rvr_settings[rvr_date_format_method]' <?php checked( $options['rvr_date_format_method'], 'custom' ); ?> value='custom'>
				<?php esc_html_e( 'Custom', 'realhomes-vacation-rentals' ); ?>
            </label><p class="description"><?php esc_html_e( 'Select the date format for different sections of RealHomes Vacation Rentals.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		public static function rvr_custom_date_format_render() {
			$options = get_option( 'rvr_settings' );
			?>
            <input type="text" name="rvr_settings[rvr_date_format_custom]" value="<?php echo esc_attr( $options['rvr_date_format_custom'] ); ?>"><p class="description"><?php esc_html_e( 'Please only use DD, MM, YYYY for DD = day, MM = month, YYYY = year.', 'realhomes-vacation-rentals' ); ?></p>
            <p class="description"><?php esc_html_e( 'The Date Format must be selected to Custom for this option to work.', 'realhomes-vacation-rentals' ); ?></p>
			<?php
		}

		// Settings tabs
		public function tabs() {
			$tabs = array(
				'general'        => esc_html__( 'General', 'realhomes-vacation-rentals' ),
				'contact_notify' => esc_html__( 'Contact & Notifications', 'realhomes-vacation-rentals' ),
				'ts_cs'          => esc_html__( 'Ts & Cs', 'realhomes-vacation-rentals' ),
				'labels'         => esc_html__( 'Labels', 'realhomes-vacation-rentals' ),
				'date_format'    => esc_html__( 'Date Format', 'realhomes-vacation-rentals' ),
			);

			if ( class_exists( 'WooCommerce' ) && class_exists( 'Realhomes_WC_Payments_Addon' ) ) {
				$tabs['booking_payments'] = esc_html__( 'Booking Payments', 'realhomes-vacation-rentals' );
			}

			// Filter to add the New Settings tabs
			return apply_filters( 'rvr_settings_tabs', $tabs );
		}

		// Generate tabs navigation
		public function tabs_nav( $current_tab ) {
			$tabs = $this->tabs();
			?>
            <div class="nav-tab-wrapper">
				<?php
				if ( ! empty( $tabs ) && is_array( $tabs ) ) {
					foreach ( $tabs as $slug => $title ) {
						$active_tab_class = ( $current_tab === $slug ) ? 'nav-tab-active' : '';
						$admin_url        = ( $current_tab === $slug ) ? '#' : admin_url( 'admin.php?page=rvr-settings&tab=' . $slug );
						echo '<a class="nav-tab ' . $active_tab_class . '" href="' . esc_url_raw( $admin_url ) . '" data-tab="' . esc_attr( $slug ) . '">' . esc_html( $title ) . '</a>';
					}
				}
				?>
            </div>
			<?php
		}
	}
}
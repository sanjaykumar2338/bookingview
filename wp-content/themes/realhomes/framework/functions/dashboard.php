<?php
if ( ! function_exists( 'realhomes_count_posts' ) ) {
	/**
	 * Count number of posts of a post type for user.
	 *
	 * @since 3.12
	 *
	 * @param string $type Optional. Post type to retrieve count. Default 'property'.
	 *
	 * @return object Number of posts for each status.
	 * @global wpdb  $wpdb WordPress database abstraction object.
	 *
	 */
	function realhomes_count_posts( $type = 'property' ) {
		global $wpdb;

		if ( ! post_type_exists( $type ) ) {
			return new stdClass;
		}

		$query = "SELECT post_status, COUNT( * ) AS num_posts FROM {$wpdb->posts} WHERE post_type = %s";
		$query .= $wpdb->prepare( " AND post_author = %d", get_current_user_id() );
		$query .= ' GROUP BY post_status';

		$results = (array)$wpdb->get_results( $wpdb->prepare( $query, $type ), ARRAY_A );
		$counts  = array_fill_keys( get_post_stati(), 0 );

		foreach ( $results as $row ) {
			$counts[ $row['post_status'] ] = $row['num_posts'];
		}

		return (object)$counts;
	}
}

if ( ! function_exists( 'realhomes_count_featured_properties' ) ) {
	/**
	 * Counts number of featured properties for current user.
	 *
	 * @since 3.12
	 *
	 * @return string
	 */
	function realhomes_count_featured_properties() {
		global $wpdb;

		$query = " 
		SELECT COUNT( * ) AS featured_properties_count 
		FROM {$wpdb->posts}, {$wpdb->postmeta} 
		WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
		AND $wpdb->posts.post_status = 'publish'
		AND $wpdb->posts.post_author = %d
		AND $wpdb->posts.post_type = %s
		AND $wpdb->postmeta.meta_key = %s 
		AND $wpdb->postmeta.meta_value = %s";

		$results = $wpdb->get_results( $wpdb->prepare( $query, get_current_user_id(), 'property', 'REAL_HOMES_featured', '1' ) );

		return $results[0]->featured_properties_count;
	}
}

if ( ! function_exists( 'realhomes_get_favorite_pro_ids' ) ) {
	/**
	 * Returns the favorite properties ids.
	 *
	 * @since 3.12
	 *
	 * @return array|bool
	 */
	function realhomes_get_favorite_pro_ids() {

		if ( is_user_logged_in() ) {
			$user_id    = wp_get_current_user()->ID;
			$properties = get_user_meta( $user_id, 'favorite_properties' );

			if ( ! empty( $properties ) && is_array( $properties ) ) {

				$favorite_properties = array();
				// Build a list of favorite properties excluding trashed/deleted properties.
				foreach ( $properties as $property_id ) {

					if ( 'publish' === get_post_status( $property_id ) ) {
						$favorite_properties[] = $property_id;
					}
				}

				if ( ! empty( $favorite_properties ) ) {
					return $favorite_properties;
				}
			}
		}

		return false;
	}
}

if ( ! function_exists( 'realhomes_get_template_permalink' ) ) {
	/**
	 * Retrieves the template permalink.
	 *
	 * @since 3.12
	 *
	 * @param $template
	 *
	 * @return string
	 */
	function realhomes_get_template_permalink( $template ) {

		$pages = get_pages( array(
			'meta_key'   => '_wp_page_template',
			'meta_value' => $template
		) );

		if ( $pages ) {
			return get_permalink( $pages[0]->ID );
		}

		return home_url( '/' );
	}

}

if ( ! function_exists( 'realhomes_get_dashboard_page_url' ) ) {
	/**
	 * Returns the dashboard page url.
	 *
	 * @since 3.12
	 *
	 * @param string $module
	 * @param array  $args
	 *
	 * @return string
	 */
	function realhomes_get_dashboard_page_url( $module = '', $args = array() ) {

		$dashboard_page_id = get_option( 'inspiry_dashboard_page' );

		if ( ! empty( $dashboard_page_id ) ) {

			/* WPML filter to get translated page id if translation exists otherwise default id */
			$dashboard_page_id = apply_filters( 'wpml_object_id', $dashboard_page_id, 'page', true );

			$dashboard_url = get_permalink( $dashboard_page_id );

			if ( ! empty( $module ) ) {
				$dashboard_url = add_query_arg( array( 'module' => $module ), $dashboard_url );

				if ( ! empty( $args ) && is_array( $args ) ) {
					$dashboard_url = add_query_arg( $args, $dashboard_url );
				}
			}

			return $dashboard_url;
		}

		return false;
	}
}

if ( ! function_exists( 'realhomes_dashboard_menu_items_default_labels' ) ) {
	/**
	 * Provides the front-end dashboard menu default labels.
	 *
	 * @since 4.1.0
	 *
	 * @return array
	 */
	function realhomes_dashboard_menu_items_default_labels() {

		$menu_default_labels = array();

		$menu_default_labels['dashboard'] = array(
			esc_html__( 'Dashboard', 'framework' ),
			esc_html__( 'Dashboard', 'framework' ),
		);

		if ( 'show' === get_option( 'realhomes_dashboard_analytics_module', 'show' ) ) {
			$menu_default_labels['analytics'] = array(
				esc_html__( 'Analytics', 'framework' ),
				esc_html__( 'Analytics', 'framework' ),
			);
		}

		$menu_default_labels['submit-property'] = array(
			esc_html__( 'Submit Property', 'framework' ),
			esc_html__( 'Submit Property', 'framework' ),
		);

		$menu_default_labels['properties'] = array(
			esc_html__( 'My Properties', 'framework' ),
			esc_html__( 'My Properties', 'framework' ),
		);

		$menu_default_labels['favorites'] = array(
			esc_html__( 'My Favorites', 'framework' ),
			esc_html__( 'My Favorites', 'framework' ),
		);

		$menu_default_labels['bookings'] = array(
			esc_html__( 'My Bookings', 'framework' ),
			esc_html__( 'My Bookings', 'framework' ),
		);

		$menu_default_labels['reservations'] = array(
			esc_html__( 'My Reservations', 'framework' ),
			esc_html__( 'My Reservations', 'framework' ),
		);

		$menu_default_labels['invoices'] = array(
			esc_html__( 'Invoices', 'framework' ),
			esc_html__( 'Invoices', 'framework' ),
		);

		$menu_default_labels['agents'] = array(
			esc_html__( 'Agents', 'framework' ),
			esc_html__( 'Agents', 'framework' ),
		);

		$menu_default_labels['agencies'] = array(
			esc_html__( 'Agencies', 'framework' ),
			esc_html__( 'Agencies', 'framework' ),
		);

		$menu_default_labels['profile'] = array(
			esc_html__( 'My Profile', 'framework' ),
			esc_html__( 'My Profile', 'framework' ),
		);

		$menu_default_labels['saved-searches'] = array(
			esc_html__( 'My Saved Searches', 'framework' ),
			esc_html__( 'My Saved Searches', 'framework' ),
		);

		$menu_default_labels['membership'] = array(
			esc_html__( 'My Membership', 'framework' ),
			esc_html__( 'My Membership Package', 'framework' ),
		);

		$menu_default_labels['logout'] = array(
			esc_html__( 'Logout', 'framework' ),
		);

		return $menu_default_labels;
	}
}

if ( ! function_exists( 'realhomes_dashboard_menu_item_label' ) ) {
	/**
	 * Provides the custom label for the menu item if it exists; otherwise, default label.
	 *
	 * @since 4.1.0
	 *
	 * @param string $menu_item
	 * @param bool   $page_label
	 *
	 * @return string
	 */
	function realhomes_dashboard_menu_item_label( $menu_item, $page_label = false ) {

		$menu_default_labels = realhomes_dashboard_menu_items_default_labels();
		if ( ! is_array( $menu_default_labels ) && empty( $menu_default_labels ) ) {
			return '';
		}

		$item_default_label = '';
		$menu_option_id     = 'realhomes_dashboard_' . $menu_item;

		if ( $page_label ) {
			$item_default_label = $menu_default_labels[ $menu_item ]['1'];
			$menu_option_id     .= '_page_label';
		} else {
			$item_default_label = $menu_default_labels[ $menu_item ]['0'];
			$menu_option_id     .= '_menu_label';
		}

		$item_custom_label = get_option( $menu_option_id, $item_default_label );

		if ( ! empty( $item_custom_label ) ) {
			return $item_custom_label;
		}

		return $item_default_label;
	}
}

if ( ! function_exists( 'realhomes_dashboard_menus' ) ) {
	/**
	 * Dashboard sidebar main and submenus.
	 *
	 * @since 3.12
	 *
	 * @return array
	 */
	function realhomes_dashboard_menus() {
		/**
		 * The elements in the array are :
		 * 0: Menu Title
		 * 1: Page Title
		 * 2: Icon for top level menu
		 * 3: Show in menu (bool)
		 */
		$menu = array();

		/**
		 * The elements in the array are :
		 * 0: Menu Title
		 * 1: Page Title
		 * 2: Query parameters
		 * 3: Show in menu (bool)
		 */
		$submenu = array();

		if ( is_user_logged_in() ) {
			$menu['dashboard'] = array(
				realhomes_dashboard_menu_item_label( 'dashboard' ),
				realhomes_dashboard_menu_item_label( 'dashboard', true ),
				'fas fa-tachometer-alt',
				realhomes_dashboard_module_enabled( 'inspiry_dashboard_page_display' )
			);

			if ( 'show' === get_option( 'realhomes_dashboard_analytics_module', 'show' ) && inspiry_is_property_analytics_enabled() && realhomes_get_current_user_role_option( 'property_analytics' ) ) {
				$menu['analytics'] = array(
					realhomes_dashboard_menu_item_label( 'analytics' ),
					realhomes_dashboard_menu_item_label( 'analytics', true ),
					'fas fa-chart-line'
				);
			}
		}

		if ( ( ! is_user_logged_in() && inspiry_guest_submission_enabled() ) || (
				is_user_logged_in()
				&& realhomes_dashboard_module_enabled( 'inspiry_submit_property_module_display' )
				&& ! realhomes_dashboard_module_enabled( 'inspiry_properties_module_display' )
				&& realhomes_get_current_user_role_option( 'property_submit' )
			) ) {
			$menu['submit-property'] = array(
				realhomes_dashboard_menu_item_label( 'submit-property' ),
				realhomes_dashboard_menu_item_label( 'submit-property', true ),
				'fas fa-home'
			);
		}

		if ( is_user_logged_in() && realhomes_dashboard_module_enabled( 'inspiry_properties_module_display' ) ) {

			if ( inspiry_no_membership_disable_stuff() && ( realhomes_get_current_user_role_option( 'property_submit' ) || realhomes_get_current_user_role_option( 'manage_listings' ) ) ) {

				$menu['properties'] = array(
					realhomes_dashboard_menu_item_label( 'properties' ),
					realhomes_dashboard_menu_item_label( 'properties', true ),
					'fas fa-home'
				);

				if ( realhomes_dashboard_module_enabled( 'inspiry_submit_property_module_display' ) && realhomes_get_current_user_role_option( 'property_submit' ) ) {
					$submenu['properties']['submit-property'] = array(
						esc_html__( 'Add Property', 'framework' ),
						esc_html__( 'Add New Property', 'framework' ),
						array( 'submodule' => 'submit-property' )
					);
				}

				if ( realhomes_get_current_user_role_option( 'manage_listings' ) ) {
					$submenu['properties']['publish'] = array(
						esc_html__( 'Published', 'framework' ),
						esc_html__( 'Published Properties', 'framework' ),
						array( 'status' => 'publish' )
					);

					$submenu['properties']['pending'] = array(
						esc_html__( 'Pending Review', 'framework' ),
						esc_html__( 'Pending Review', 'framework' ),
						array( 'status' => 'pending' )
					);
				}
			}
		}

		if ( realhomes_dashboard_module_enabled( 'inspiry_favorites_module_display' ) ) {
			$favorites_after_login = get_option( 'inspiry_login_on_fav', 'no' );
			if ( ( realhomes_get_current_user_role_option( 'manage_favorites' ) && 'yes' === $favorites_after_login ) || 'no' === $favorites_after_login ) {
				$menu['favorites'] = array(
					realhomes_dashboard_menu_item_label( 'favorites' ),
					realhomes_dashboard_menu_item_label( 'favorites', true ),
					'fas fa-heart'
				);
			}
		}

		if ( is_user_logged_in() ) {

			if ( function_exists( 'rvr_is_enabled' ) && rvr_is_enabled() && realhomes_get_current_user_role_option( 'check_invoices' ) ) {

				if ( realhomes_dashboard_module_enabled( 'realhomes_bookings_module_display', 'true' ) && realhomes_dashboard_module_enabled( 'inspiry_submit_property_module_display' ) && realhomes_get_current_user_role_option( 'property_submit' ) ) {
					$menu['bookings'] = array(
						realhomes_dashboard_menu_item_label( 'bookings' ),
						realhomes_dashboard_menu_item_label( 'bookings', true ),
						'fas fa-calendar-alt'
					);
				}

				if ( realhomes_dashboard_module_enabled( 'realhomes_reservations_module_display', 'true' ) ) {
					$menu['reservations'] = array(
						realhomes_dashboard_menu_item_label( 'reservations' ),
						realhomes_dashboard_menu_item_label( 'reservations', true ),
						'fas fa-calendar-check'
					);
				}

				if ( realhomes_dashboard_module_enabled( 'realhomes_invoices_module_display', 'true' ) && class_exists( 'woocommerce' ) ) {
					$menu['invoices'] = array(
						realhomes_dashboard_menu_item_label( 'invoices' ),
						realhomes_dashboard_menu_item_label( 'invoices', true ),
						'fas fa-envelope-open-text'
					);
				}
			}

			if ( realhomes_dashboard_module_enabled( 'realhomes_agents_module_display', 'false' ) && realhomes_get_current_user_role_option( 'manage_agents' ) ) {
				$menu['agents'] = array(
					realhomes_dashboard_menu_item_label( 'agents' ),
					realhomes_dashboard_menu_item_label( 'agents', true ),
					'fas fa-users'
				);

				if ( realhomes_dashboard_module_enabled( 'realhomes_add_agent_module_display', false ) ) {
					$submenu['agents']['submit-agent'] = array(
						esc_html__( 'Add Agent', 'framework' ),
						esc_html__( 'Add New Agent', 'framework' ),
						array( 'submodule' => 'submit-agent' )
					);
				}
			}

			if ( realhomes_dashboard_module_enabled( 'realhomes_agencies_module_display', 'false' ) && realhomes_get_current_user_role_option( 'manage_agencies' ) ) {
				$menu['agencies'] = array(
					realhomes_dashboard_menu_item_label( 'agencies' ),
					realhomes_dashboard_menu_item_label( 'agencies', true ),
					'fas fa-user-tie'
				);

				if ( realhomes_dashboard_module_enabled( 'realhomes_add_agency_module_display', false ) ) {
					$submenu['agencies']['submit-agency'] = array(
						esc_html__( 'Add Agency', 'framework' ),
						esc_html__( 'Add New Agency', 'framework' ),
						array( 'submodule' => 'submit-agency' )
					);
				}
			}

			if ( realhomes_dashboard_module_enabled( 'inspiry_profile_module_display' ) && realhomes_get_current_user_role_option( 'manage_profile' ) ) {
				$menu['profile'] = array(
					realhomes_dashboard_menu_item_label( 'profile' ),
					realhomes_dashboard_menu_item_label( 'profile', true ),
					'fas fa-user-alt'
				);
			}

			if ( inspiry_is_save_search_enabled() && realhomes_get_current_user_role_option( 'manage_searches' ) ) {
				$menu['saved-searches'] = array(
					realhomes_dashboard_menu_item_label( 'saved-searches' ),
					realhomes_dashboard_menu_item_label( 'saved-searches', true ),
					'fas fa-bell',
				);
			}

			if ( current_user_can( 'administrator' ) ) {
				$realhomes_saved_searches_all_users_label = get_option( 'realhomes_saved_searches_all_users_label', esc_html__( 'All Users Saved Searches', 'framework' ) );
				if ( inspiry_is_save_search_enabled() && ! empty( $realhomes_saved_searches_all_users_label ) ) {
					$submenu['saved-searches']['saved-searches-all'] = array(
						esc_html__( 'All Users Saved Searches', 'framework' ),
						esc_html__( 'All Users Saved Searches', 'framework' ),
						array( 'submodule' => 'saved-searches-all' )
					);
				}
			}

			if ( class_exists( 'IMS_Helper_Functions' ) && realhomes_get_current_user_role_option( 'memberships' ) ) {
				if ( ! empty( IMS_Helper_Functions::is_memberships() ) ) {
					$menu['membership'] = array(
						realhomes_dashboard_menu_item_label( 'membership' ),
						realhomes_dashboard_menu_item_label( 'membership', true ),
						'fas fa-clipboard-list'
					);

					$submenu['membership']['packages'] = array(
						esc_html__( 'Packages', 'framework' ),
						esc_html__( 'Packages', 'framework' ),
						array( 'submodule' => 'packages' ),
						false
					);

					$submenu['membership']['checkout'] = array(
						esc_html__( 'Checkout', 'framework' ),
						esc_html__( 'Checkout', 'framework' ),
						array( 'submodule' => 'checkout' ),
						false
					);

					$submenu['membership']['order'] = array(
						esc_html__( 'Order', 'framework' ),
						esc_html__( 'Order', 'framework' ),
						array( 'submodule' => 'order' ),
						false
					);
				}
			}
		}

		/**
		 * Filters the dashboard parent menu.
		 *
		 * @since 3.12
		 *
		 * @param array $menu The parent menu.
		 */
		$menu = apply_filters( 'realhomes_dashboard_menu', $menu );

		/**
		 * Filters the dashboard submenu.
		 *
		 * @since 3.12
		 *
		 * @param array $submenu The submenu.
		 * @param array $menu    The parent menu.
		 */
		$submenu = apply_filters( 'realhomes_dashboard_submenu', $submenu, $menu );

		if ( is_user_logged_in() ) {
			$menu['logout'] = array(
				realhomes_dashboard_menu_item_label( 'logout' ),
				false,
				'fas fa-sign-out-alt'
			);
		}

		return array(
			'menu'    => $menu,
			'submenu' => $submenu
		);
	}
}

if ( ! function_exists( 'realhomes_dashboard_submenu_validation' ) ) {
	/**
	 * Validates the submenu items.
	 *
	 * @since 3.12
	 *
	 * @return array|bool
	 */
	function realhomes_dashboard_submenu_validation() {

		$submenu = realhomes_dashboard_menus()['submenu'];
		if ( ! is_array( $submenu ) ) {
			return false;
		}

		$keys   = array();
		$values = array();

		foreach ( $submenu as $submenu_items ) {
			if ( is_array( $submenu_items ) ) {
				foreach ( $submenu_items as $submenu_item ) {
					if ( isset( $submenu_item[2] ) ) {
						$keys[]   = array_keys( $submenu_item[2] )[0];
						$values[] = array_values( $submenu_item[2] )[0];
					}
				}
			}
		}

		return array(
			array_unique( $keys ),
			array_unique( $values )
		);
	}
}

if ( ! function_exists( 'realhomes_dashboard_globals' ) ) {
	/**
	 * Prepares mostly used data for dashboard page template.
	 *
	 * @since 3.12
	 *
	 * @return array
	 */
	function realhomes_dashboard_globals() {

		// Variable to hold dashboard global data.
		$global = array(
			'dashboard_url'  => esc_url( realhomes_get_dashboard_page_url() ),
			'page_title'     => '',
			'current_module' => '',
			'submenu'        => false
		);

		// Set first menu item as default dashboard module if menu is not empty.
		$menu = realhomes_dashboard_menus()['menu'];
		if ( ! empty( $menu ) && is_array( $menu ) ) {
			$module                   = array_keys( $menu )[0];
			$global['page_title']     = $menu[ $module ][1];
			$global['current_module'] = $module;
		}

		if ( isset( $_GET['module'] ) && ! empty( $_GET['module'] ) ) {

			// Validate the current module and update global data on success.
			$module = sanitize_text_field( $_GET['module'] );
			if ( in_array( $module, array_keys( $menu ) ) ) {
				$global['page_title']     = $menu[ $module ][1];
				$global['current_module'] = $module;

				if ( isset( $_GET['status'] ) || isset( $_GET['submodule'] ) ) {

					$global['submenu'] = true;

					$submenu        = realhomes_dashboard_menus()['submenu'];
					$submenu_values = realhomes_dashboard_submenu_validation()[1];

					if ( ! empty( $_GET['status'] ) ) {
						// Check for valid post status and update global data on success.
						$status = sanitize_text_field( $_GET['status'] );
						if ( in_array( $status, $submenu_values ) ) {
							$global['submenu_page_title'] = $submenu[ $module ][ $status ][1];
						}

					} else if ( ! empty( $_GET['submodule'] ) ) {
						// Validate the sub-module and update global data on success.
						$submodule = sanitize_text_field( $_GET['submodule'] );
						if ( in_array( $submodule, $submenu_values ) ) {

							$global['submodule'] = $submodule;

							if ( isset( $_GET['id'] ) ) {
								if ( 'submit-property' === $submodule ) {
									$global['submenu_page_title'] = esc_html__( 'Edit Property', 'framework' );
								} else if ( 'submit-agent' === $submodule ) {
									$global['submenu_page_title'] = esc_html__( 'Edit Agent', 'framework' );
								} else if ( 'submit-agency' === $submodule ) {
									$global['submenu_page_title'] = esc_html__( 'Edit Agency', 'framework' );
								}
							} else {
								$global['submenu_page_title'] = $submenu[ $module ][ $submodule ][1];
							}
						}
					}
				}
			}
		}

		return $global;
	}
}

if ( ! function_exists( 'realhomes_dashboard_module_enabled' ) ) {
	/**
	 * Determines whether the given dashboard module is enabled or not.
	 *
	 * @since 3.12
	 *
	 * @param string $option_id
	 * @param string $default
	 *
	 * @return bool
	 */
	function realhomes_dashboard_module_enabled( $option_id, $default = 'true' ) {

		if ( 'true' === get_option( $option_id, $default ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'realhomes_dashboard_header_menu' ) ) {
	/**
	 * Renders the dashboard pages link in header as dropdown menu.
	 *
	 * @since 3.12
	 */
	function realhomes_dashboard_header_menu() {

		if ( realhomes_get_current_user_role_option( 'property_analytics' ) ) {

			if ( realhomes_get_dashboard_page_url() && 'show' === get_option( 'realhomes_dashboard_analytics_module', 'show' ) && inspiry_is_property_analytics_enabled() ) {
				$analytics_url = realhomes_get_dashboard_page_url( 'analytics' );

				if ( ! empty( $analytics_url ) ) {
					?>
                    <a href="<?php echo esc_url( $analytics_url ); ?>">
                        <i class="fas fa-chart-line"></i>
                        <span><?php echo realhomes_dashboard_menu_item_label( 'analytics' ); ?></span>
                    </a>
					<?php
				}
			}
		}

		if ( realhomes_get_current_user_role_option( 'manage_profile' ) ) {
			$profile_url = '';
			if ( realhomes_get_dashboard_page_url() && realhomes_dashboard_module_enabled( 'inspiry_profile_module_display' ) ) {
				$profile_url = realhomes_get_dashboard_page_url( 'profile' );
			}

			if ( ! empty( $profile_url ) ) {
				?>
                <a href="<?php echo esc_url( $profile_url ); ?>">
					<?php inspiry_safe_include_svg( 'images/icon-dash-profile.svg', '/common/' ); ?>
                    <span><?php echo realhomes_dashboard_menu_item_label( 'profile' ); ?></span>
                </a>
				<?php
			}
		}

		if ( realhomes_get_current_user_role_option( 'manage_listings' ) ) {
			$my_properties_url = '';
			if ( realhomes_get_dashboard_page_url() && realhomes_dashboard_module_enabled( 'inspiry_properties_module_display' ) ) {
				$my_properties_url = realhomes_get_dashboard_page_url( 'properties' );
			}

			if ( ! empty( $my_properties_url ) && inspiry_no_membership_disable_stuff() ) {
				?>
                <a href="<?php echo esc_url( $my_properties_url ); ?>">
					<?php inspiry_safe_include_svg( 'images/icon-dash-my-properties.svg', '/common/' ); ?>
                    <span><?php echo realhomes_dashboard_menu_item_label( 'properties' ); ?></span>
                </a>
				<?php
			}
		}

		if ( realhomes_get_current_user_role_option( 'manage_favorites' ) ) {
			$favorites_url = '';
			if ( realhomes_get_dashboard_page_url() && realhomes_dashboard_module_enabled( 'inspiry_favorites_module_display' ) ) {
				$favorites_url = realhomes_get_dashboard_page_url( 'favorites' );
			}

			if ( ! empty( $favorites_url ) ) {
				?>
                <a href="<?php echo esc_url( $favorites_url ); ?>">
					<?php inspiry_safe_include_svg( 'images/icon-dash-favorite.svg', '/common/' ); ?>
                    <span><?php echo realhomes_dashboard_menu_item_label( 'favorites' ); ?></span>
                </a>
				<?php
			}
		}

		if ( realhomes_get_current_user_role_option( 'manage_searches' ) && inspiry_is_save_search_enabled() ) {
			$saved_searches_url = '';
			if ( realhomes_get_dashboard_page_url() ) {
				$saved_searches_url = realhomes_get_dashboard_page_url( 'saved-searches' );
			}

			if ( ! empty( $saved_searches_url ) ) :
				?>
                <a href="<?php echo esc_url( $saved_searches_url ); ?>">
					<?php inspiry_safe_include_svg( 'images/icon-dash-alert.svg', '/common/' ); ?>
                    <span><?php echo realhomes_dashboard_menu_item_label( 'saved-searches' ); ?></span>
                </a>
			<?php
			endif;
		}

		if ( function_exists( 'IMS_Helper_Functions' ) && realhomes_get_current_user_role_option( 'memberships' ) ) {
			$ims_helper_functions  = IMS_Helper_Functions();
			$is_memberships_enable = $ims_helper_functions::is_memberships();

			$membership_url = '';
			if ( realhomes_get_dashboard_page_url() ) {
				$membership_url = realhomes_get_dashboard_page_url( 'membership' );
			}

			if ( ! empty( $is_memberships_enable ) && ! empty( $membership_url ) ) {
				?>
                <a href="<?php echo esc_url( $membership_url ); ?>">
					<?php inspiry_safe_include_svg( 'images/icon-membership.svg', '/common/' ); ?>
                    <span><?php echo realhomes_dashboard_menu_item_label( 'membership' ); ?></span>
                </a>
				<?php
			}
		}
		?>
        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
			<?php inspiry_safe_include_svg( 'images/icon-dash-logout.svg', '/common/' ); ?>
            <span><?php echo realhomes_dashboard_menu_item_label( 'logout' ); ?></span>
        </a>
		<?php
	}
}

if ( ! function_exists( 'realhomes_dashboard_posts_per_page_list' ) ) {
	/**
	 * Provides the posts per page list for dashboard posts list pages.
	 *
	 * @since 3.12
	 *
	 * @return array
	 */
	function realhomes_dashboard_posts_per_page_list() {

		$list = array(
			'-1'  => esc_html__( 'All', 'framework' ),
			'5'   => esc_html__( '5', 'framework' ),
			'10'  => esc_html__( '10', 'framework' ),
			'15'  => esc_html__( '15', 'framework' ),
			'20'  => esc_html__( '20', 'framework' ),
			'25'  => esc_html__( '25', 'framework' ),
			'50'  => esc_html__( '50', 'framework' ),
			'75'  => esc_html__( '75', 'framework' ),
			'100' => esc_html__( '100', 'framework' ),
		);

		return apply_filters( 'realhomes_dashboard_posts_per_page_list', $list );
	}
}

if ( ! function_exists( 'realhomes_dashboard_posts_per_page' ) ) {
	/**
	 * Provides the post per page current value.
	 *
	 * @since 3.12
	 *
	 * @return int
	 */
	function realhomes_dashboard_posts_per_page() {

		$posts_per_page = get_option( 'inspiry_dashboard_posts_per_page', '10' );

		if ( isset( $_GET['posts_per_page'] ) && ! empty( $_GET['posts_per_page'] ) ) {
			if ( in_array( $_GET['posts_per_page'], array_keys( realhomes_dashboard_posts_per_page_list() ) ) ) {
				$posts_per_page = $_GET['posts_per_page'];
			}
		}

		return intval( $posts_per_page );
	}
}

if ( ! function_exists( 'realhomes_dashboard_properties_status_filter' ) ) {
	/**
	 * Filters the properties as per selected property status value.
	 *
	 * @since 3.12
	 *
	 * @return string
	 */
	function realhomes_dashboard_properties_status_filter() {

		$property_status_filter = '-1';

		if ( isset( $_GET['property_status_filter'] ) && ! empty( $_GET['property_status_filter'] ) ) {
			$property_status_filter = sanitize_text_field( $_GET['property_status_filter'] );
		}

		return $property_status_filter;
	}
}

if ( ! function_exists( 'realhomes_dashboard_no_items' ) ) {
	/**
	 * Shows no property found message for dashboard posts list pages.
	 *
	 * @since 3.12
	 *
	 * @param string $message
	 */
	function realhomes_dashboard_no_items( $title = '', $message = '', $icon = '' ) {

		if ( empty( $title ) ) {
			$title = esc_html__( 'No Property Found!', 'framework' );
		}

		if ( empty( $message ) ) {
			$message = esc_html__( 'There are no properties falling under this criteria.', 'framework' );
		}

		if ( empty( $icon ) ) {
			$icon = 'not-found.svg';
		}

		?>
        <div class="dashboard-no-items">
            <div class="icon-wrap">
				<?php inspiry_safe_include_svg( $icon, '/common/images/icons/' ); ?>
            </div>
            <h3><?php echo esc_html( $title ); ?></h3>
            <p><?php echo esc_html( $message ); ?></p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'realhomes_dashboard_notice' ) ) {
	/**
	 * Displays dashboard notices.
	 *
	 * @since 3.12
	 *
	 * @param        $message
	 * @param string $type
	 * @param bool   $dismissible
	 */
	function realhomes_dashboard_notice( $message, $type = 'info', $dismissible = false ) {

		$allowed_html = array(
			'button' => array(
				'type'  => array(),
				'class' => array(),
			),
			'a'      => array(
				'href'   => array(),
				'class'  => array(),
				'target' => array(),
			),
			'i'      => array(
				'class' => array(),
			),
			'br'     => array(),
			'strong' => array(),
			'em'     => array(),
			'h5'     => array(),
			'p'      => array(),
		);

		if ( is_array( $message ) && ! empty( $message ) ) {
			$count = count( $message );
			if ( $count === 2 ) {
				$output = sprintf( '<h5>%s</h5><p>%s</p>', $message[0], $message[1] );
			} else {
				$output = sprintf( '<p>%s</p>', $message[0] );
			}
		} else {
			$output = sprintf( '<p>%s</p>', $message );
		}

		if ( $dismissible ) {
			$output .= '<button type="button" class="dashboard-notice-dismiss-button"><i class="fas fa-times"></i></button>';
			printf( '<div class="dashboard-notice %s is-dismissible">%s</div>', esc_attr( $type ), wp_kses( $output, $allowed_html ) );
		} else {
			printf( '<div class="dashboard-notice %s">%s</div>', esc_attr( $type ), wp_kses( $output, $allowed_html ) );
		}
	}
}

if ( ! function_exists( 'realhomes_dashboard_edit_property' ) ) {
	/**
	 * Checks for dashboard edit property.
	 *
	 * @since 3.12
	 *
	 * @return bool
	 */
	function realhomes_dashboard_edit_property() {
		global $target_property;

		if ( ! empty( $target_property ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'realhomes_dashboard_submit_property' ) ) {
	/**
	 * Provides the functionality to add or update property.
	 *
	 * @since 3.12
	 */
	function realhomes_dashboard_submit_property() {
		global $submitted_successfully, $updated_successfully;

		$response               = array();
		$submitted_successfully = false;
		$updated_successfully   = false;
		$submit_form_fields     = inspiry_get_submit_fields();

		// Verify nonce.
		if ( ! wp_verify_nonce( $_POST['property_nonce'], 'submit_property' ) ) {
			$response['message'] = esc_html__( 'Security check failed!', 'framework' );
			wp_send_json_error( $response );
		}

		// Verifies the google recaptcha challenge.
		if ( ! is_user_logged_in() && inspiry_guest_submission_enabled() && class_exists( 'Easy_Real_Estate' ) ) {
			ere_verify_google_recaptcha();
		}

		/* Start with basic array */
		$new_property = array( 'post_type' => 'property' );

		/* Title */
		if ( isset( $_POST['inspiry_property_title'] ) && ! empty( $_POST['inspiry_property_title'] ) ) {
			$new_property['post_title'] = sanitize_text_field( $_POST['inspiry_property_title'] );
		}

		/* Description */
		if ( isset( $_POST['description'] ) ) {
			$new_property['post_content'] = wp_kses_post( $_POST['description'] );
		}

		/* Get current user */
		$current_user                = wp_get_current_user();
		$new_property['post_author'] = $current_user->ID;

		/* Check the type of action */
		$action      = $_POST['action'];
		$property_id = 0;

		/* Parent Property ID */
		if ( isset( $_POST['property_parent_id'] ) && ! empty( $_POST['property_parent_id'] ) ) {
			$new_property['post_parent'] = $_POST['property_parent_id'];
		} else {
			$new_property['post_parent'] = 0;
		}

		/* Add or Update Property */
		if ( 'add_property' == $action ) {

			$submitted_property_status = get_option( 'theme_submitted_status' );
			if ( ! empty( $submitted_property_status ) ) {
				$new_property['post_status'] = $submitted_property_status;
			} else {
				$new_property['post_status'] = 'pending';
			}

			/* This filter is used to filter the submission arguments of property before inserting it. */
			$new_property = apply_filters( 'inspiry_before_property_submit', $new_property );

			// Insert Property and get Property ID.
			$property_id = wp_insert_post( $new_property );
			if ( $property_id > 0 ) {
				$submitted_successfully = true;
			}
		} else if ( 'update_property' == $action ) {

			// If individual payments are enabled then set the property status accordingly.
			$isp_settings   = get_option( 'isp_settings' ); // Stripe settings.
			$rpp_settings   = get_option( 'rpp_settings' ); // PayPal settings.
			$rhwpa_settings = get_option( 'rhwpa_property_payment_settings' ); // Property WooCommerce payments settings.

			// Check if PayPal or Stripe payment is enabled.
			if ( ! empty( $isp_settings['enable_stripe'] ) || ! empty( $rpp_settings['enable_paypal'] ) || ! empty( $rhwpa_settings['enable_wc_payments'] ) ) {
				if ( 'Completed' === get_post_meta( $_POST['property_id'], 'payment_status', true ) ) {
					$new_property['post_status'] = get_option( 'inspiry_updated_property_status', 'publish' );
				} else {
					$new_property['post_status'] = 'pending';
				}
			} else {
				$new_property['post_status'] = get_option( 'inspiry_updated_property_status', 'publish' );
			}

			$new_property['ID'] = intval( $_POST['property_id'] );

			/* This filter is used to filter the submission arguments of property before update */
			$new_property = apply_filters( 'inspiry_before_property_update', $new_property );

			// Update Property and get Property ID.
			$property_id = wp_update_post( $new_property );
			if ( $property_id > 0 ) {
				$updated_successfully = true;
			}
		}

		// If property is added or updated successfully then move ahead
		if ( $property_id > 0 ) {

			/* Attach Property Type(s) with Newly Created Property */
			if ( isset( $_POST['type'] ) ) {
				if ( ! empty( $_POST['type'] ) && is_array( $_POST['type'] ) ) {
					$property_types = array();
					foreach ( $_POST['type'] as $property_type_id ) {
						$property_types[] = intval( $property_type_id );
					}
					wp_set_object_terms( $property_id, $property_types, 'property-type' );
				}
			}

			/* Attach Property Location with Newly Created Property */
			$location_select_names = inspiry_get_location_select_names();
			$selected_locations    = array();
			foreach ( $location_select_names as $current_location ) {
				if ( isset( $_POST[ $current_location ] ) && $_POST[ $current_location ] !== 'any' && ! empty( $_POST[ $current_location ] ) ) {
					$selected_locations[] = $_POST[ $current_location ];
				}
			}
			wp_set_object_terms( $property_id, $selected_locations, 'property-city' );

			/* Attach Property Status with Newly Created Property */
			if ( isset( $_POST['status'] ) && ( '-1' != $_POST['status'] ) ) {
				wp_set_object_terms( $property_id, intval( $_POST['status'] ), 'property-status' );
			}

			/* Attach Property Features with Newly Created Property */
			if ( isset( $_POST['features'] ) ) {
				if ( ! empty( $_POST['features'] ) && is_array( $_POST['features'] ) ) {
					$property_features = array();
					foreach ( $_POST['features'] as $property_feature_id ) {
						$property_features[] = intval( $property_feature_id );
					}
					wp_set_object_terms( $property_id, $property_features, 'property-feature' );
				}
			} else {
				wp_delete_object_term_relationships( $property_id, 'property-feature' );
			}

			/* Attach Price Post Meta */
			if ( isset( $_POST['price'] ) && ! empty( $_POST['price'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_price', sanitize_text_field( $_POST['price'] ) );

				if ( isset( $_POST['price-postfix'] ) && ! empty( $_POST['price-postfix'] ) ) {
					update_post_meta( $property_id, 'REAL_HOMES_property_price_postfix', sanitize_text_field( $_POST['price-postfix'] ) );
				}
			}

			if ( in_array( 'price', $submit_form_fields, true ) && isset( $_POST['price'] ) && empty( $_POST['price'] ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_price' );
			}

			/* Attach Old Price Post Meta */
			if ( isset( $_POST['old-price'] ) && ! empty( $_POST['old-price'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_old_price', sanitize_text_field( $_POST['old-price'] ) );
			} else if ( in_array( 'price', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_old_price' );
			}

			if ( in_array( 'price-postfix', $submit_form_fields, true ) && isset( $_POST['price-postfix'] ) && empty( $_POST['price-postfix'] ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_price_postfix' );
			}

			if ( isset( $_POST['price-prefix'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_price_prefix', sanitize_text_field( $_POST['price-prefix'] ) );
			}

			/* Attach Size Post Meta */
			if ( isset( $_POST['size'] ) && ! empty( $_POST['size'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_size', sanitize_text_field( $_POST['size'] ) );

				if ( isset( $_POST['area-postfix'] ) && ! empty( $_POST['area-postfix'] ) ) {
					update_post_meta( $property_id, 'REAL_HOMES_property_size_postfix', sanitize_text_field( $_POST['area-postfix'] ) );
				}
			} else if ( in_array( 'area', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_size' );
			}

			if ( in_array( 'area-postfix', $submit_form_fields, true ) && isset( $_POST['area-postfix'] ) && empty( $_POST['area-postfix'] ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_size_postfix' );
			}

			/* Attach Lot Size Post Meta */
			if ( isset( $_POST['lot-size'] ) && ! empty( $_POST['lot-size'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_lot_size', sanitize_text_field( $_POST['lot-size'] ) );

				if ( isset( $_POST['lot-size-postfix'] ) && ! empty( $_POST['lot-size-postfix'] ) ) {
					update_post_meta( $property_id, 'REAL_HOMES_property_lot_size_postfix', sanitize_text_field( $_POST['lot-size-postfix'] ) );
				}
			} else if ( in_array( 'lot-size', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_lot_size' );
			}

			if ( in_array( 'lot-size-postfix', $submit_form_fields, true ) && isset( $_POST['lot-size-postfix'] ) && empty( $_POST['lot-size-postfix'] ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_lot_size_postfix' );
			}

			/* Attach Bedrooms Post Meta */
			if ( isset( $_POST['bedrooms'] ) && ! empty( $_POST['bedrooms'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_bedrooms', floatval( $_POST['bedrooms'] ) );
			} else if ( in_array( 'bedrooms', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_bedrooms' );
			}

			/* Attach Bathrooms Post Meta */
			if ( isset( $_POST['bathrooms'] ) && ! empty( $_POST['bathrooms'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_bathrooms', floatval( $_POST['bathrooms'] ) );
			} else if ( in_array( 'bathrooms', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_bathrooms' );
			}

			/* Attach Garages Post Meta */
			if ( isset( $_POST['garages'] ) && ! empty( $_POST['garages'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_garage', floatval( $_POST['garages'] ) );
			} else if ( in_array( 'garages', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_garage' );
			}

			/* Attach Year Built Post Meta */
			if ( isset( $_POST['year-built'] ) && ! empty( $_POST['year-built'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_year_built', floatval( $_POST['year-built'] ) );
			}

			/* Attach Energy Performance Certificate Meta */
			if ( isset( $_POST['energy-class'] ) && ! empty( $_POST['energy-class'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_energy_class', sanitize_text_field( $_POST['energy-class'] ) );
			}

			if ( isset( $_POST['energy-performance'] ) && ! empty( $_POST['energy-performance'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_energy_performance', sanitize_text_field( $_POST['energy-performance'] ) );
			}

			if ( isset( $_POST['epc-current-rating'] ) && ! empty( $_POST['epc-current-rating'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_epc_current_rating', sanitize_text_field( $_POST['epc-current-rating'] ) );
			}

			if ( isset( $_POST['epc-potential-rating'] ) && ! empty( $_POST['epc-potential-rating'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_epc_potential_rating', sanitize_text_field( $_POST['epc-potential-rating'] ) );
			}

			/* Attach Address Post Meta */
			if ( isset( $_POST['address'] ) && ! empty( $_POST['address'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_address', sanitize_text_field( $_POST['address'] ) );
			}

			/**
			 * RVR meta fields
			 * @since 3.13.0
			 */
			if ( inspiry_is_rvr_enabled() ) {
				if ( isset( $_POST['rvr_guests_capacity'] ) ) {
					update_post_meta( $property_id, 'rvr_guests_capacity', sanitize_text_field( $_POST['rvr_guests_capacity'] ) );
				}

				if ( isset( $_POST['rvr_min_stay'] ) ) {
					update_post_meta( $property_id, 'rvr_min_stay', sanitize_text_field( $_POST['rvr_min_stay'] ) );
				}

				if ( isset( $_POST['rvr_govt_tax'] ) ) {
					update_post_meta( $property_id, 'rvr_govt_tax', sanitize_text_field( $_POST['rvr_govt_tax'] ) );
				}

				if ( isset( $_POST['rvr_service_charges'] ) ) {
					update_post_meta( $property_id, 'rvr_service_charges', sanitize_text_field( $_POST['rvr_service_charges'] ) );
				}

				if ( isset( $_POST['rvr_property_owner'] ) ) {
					update_post_meta( $property_id, 'rvr_property_owner', sanitize_text_field( $_POST['rvr_property_owner'] ) );
				}

				if ( ! empty( $_POST['rvr_booking_mode'] ) ) {
					update_post_meta( $property_id, 'rvr_booking_mode', sanitize_text_field( $_POST['rvr_booking_mode'] ) );
				}

				if ( isset( $_POST['rvr_custom_reserved_dates'] ) && ! empty( $_POST['rvr_custom_reserved_dates'] ) ) {
					$rvr_reserved_booking_dates = $_POST['rvr_custom_reserved_dates'];
					if ( is_array( $rvr_reserved_booking_dates ) ) {
						foreach ( $rvr_reserved_booking_dates as $rvr_reserved_date ) {
							if ( is_array( $rvr_reserved_date ) ) {
								foreach ( $rvr_reserved_date as $key => $value ) {
									$rvr_reserved_date[ $key ] = sanitize_text_field( $value );
								}
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_custom_reserved_dates', $rvr_reserved_booking_dates );

				if ( isset( $_POST['rvr_custom_seasonal_prices'] ) && ! empty( $_POST['rvr_custom_seasonal_prices'] ) ) {
					$rvr_seasonal_prices = $_POST['rvr_custom_seasonal_prices'];
					if ( is_array( $rvr_seasonal_prices ) ) {
						foreach ( $rvr_seasonal_prices as $rvr_seasonal_price ) {
							if ( is_array( $rvr_seasonal_price ) ) {
								foreach ( $rvr_seasonal_price as $key => $value ) {
									$rvr_seasonal_price[ $key ] = sanitize_text_field( $value );
								}
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_seasonal_pricing', $rvr_seasonal_prices );

				$rvr_outdoor_features_sanitized = array();
				if ( isset( $_POST['rvr_outdoor_features'] ) && ! empty( $_POST['rvr_outdoor_features'] ) ) {
					$rvr_outdoor_features = $_POST['rvr_outdoor_features'];
					if ( is_array( $rvr_outdoor_features ) ) {
						foreach ( $rvr_outdoor_features as $rvr_outdoor_feature ) {
							if ( ! empty( $rvr_outdoor_feature ) ) {
								$rvr_outdoor_features_sanitized[] = sanitize_text_field( $rvr_outdoor_feature );
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_outdoor_features', $rvr_outdoor_features_sanitized );

				$rvr_included_sanitized = array();
				if ( isset( $_POST['rvr_included'] ) && ! empty( $_POST['rvr_included'] ) ) {
					$rvr_included = $_POST['rvr_included'];
					if ( is_array( $rvr_included ) ) {
						foreach ( $rvr_included as $rvr_included_field ) {
							if ( ! empty( $rvr_included_field ) ) {
								$rvr_included_sanitized[] = sanitize_text_field( $rvr_included_field );
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_included', $rvr_included_sanitized );

				$rvr_not_included_sanitized = array();
				if ( isset( $_POST['rvr_not_included'] ) && ! empty( $_POST['rvr_not_included'] ) ) {
					$rvr_not_included = $_POST['rvr_not_included'];
					if ( is_array( $rvr_not_included ) ) {
						foreach ( $rvr_not_included as $rvr_not_included_field ) {
							if ( ! empty( $rvr_not_included_field ) ) {
								$rvr_not_included_sanitized[] = sanitize_text_field( $rvr_not_included_field );
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_not_included', $rvr_not_included_sanitized );

				$rvr_surroundings_sanitized = array();
				if ( isset( $_POST['rvr_surroundings'] ) && ! empty( $_POST['rvr_surroundings'] ) ) {
					$rvr_surroundings = $_POST['rvr_surroundings'];
					if ( is_array( $rvr_surroundings ) ) {
						foreach ( $rvr_surroundings as $k => $v ) {
							if ( ! empty( $v['rvr_surrounding_point'] ) || ! empty( $v['rvr_surrounding_point_distance'] ) ) {
								$rvr_surroundings_sanitized[ $k ] = array(
									'rvr_surrounding_point'          => sanitize_text_field( $v['rvr_surrounding_point'] ),
									'rvr_surrounding_point_distance' => sanitize_text_field( $v['rvr_surrounding_point_distance'] )
								);
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_surroundings', $rvr_surroundings_sanitized );

				$rvr_policies_sanitized = array();
				if ( isset( $_POST['rvr_policies'] ) && ! empty( $_POST['rvr_policies'] ) ) {
					$rvr_policies = $_POST['rvr_policies'];
					if ( is_array( $rvr_policies ) ) {
						foreach ( $rvr_policies as $k => $v ) {
							if ( ! empty( $v['rvr_policy_detail'] ) || ! empty( $v['rvr_policy_icon'] ) ) {
								$rvr_policies_sanitized[ $k ] = array(
									'rvr_policy_detail' => sanitize_text_field( $v['rvr_policy_detail'] ),
									'rvr_policy_icon'   => sanitize_text_field( $v['rvr_policy_icon'] )
								);
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_policies', $rvr_policies_sanitized );

				// Update iCalendar import fields data
				$icalendar_imports_sanitized = array();
				if ( isset( $_POST['rvr_import_icalendar_feed_list'] ) && ! empty( $_POST['rvr_import_icalendar_feed_list'] ) ) {
					$icalendar_fields = $_POST['rvr_import_icalendar_feed_list'];
					if ( is_array( $icalendar_fields ) ) {
						foreach ( $icalendar_fields as $key => $icalendar_field ) {
							if ( ! empty( $icalendar_field['feed_name'] ) || ! empty( $icalendar_field['feed_url'] ) ) {
								$icalendar_imports_sanitized[ $key ] = array(
									'feed_name' => sanitize_text_field( $icalendar_field['feed_name'] ),
									'feed_url'  => sanitize_text_field( $icalendar_field['feed_url'] )
								);
							}
						}
					}
				}
				update_post_meta( $property_id, 'rvr_import_icalendar_feed_list', $icalendar_imports_sanitized );
			}

			/* Attach Address Post Meta */
			if ( isset( $_POST['coordinates'] ) && ! empty( $_POST['coordinates'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_location', $_POST['coordinates'] );
			}

			/* Agent Display Option */
			if ( in_array( 'agent', $submit_form_fields, true ) ) {
				if ( isset( $_POST['agent_display_option'] ) && ! empty( $_POST['agent_display_option'] ) ) {
					update_post_meta( $property_id, 'REAL_HOMES_agent_display_option', $_POST['agent_display_option'] );
					if ( ( $_POST['agent_display_option'] == 'agent_info' ) && isset( $_POST['agent_id'] ) ) {
						delete_post_meta( $property_id, 'REAL_HOMES_agents' );
						foreach ( $_POST['agent_id'] as $agent_id ) {
							add_post_meta( $property_id, 'REAL_HOMES_agents', $agent_id );
						}
					} else {
						if ( in_array( 'agent', $submit_form_fields, true ) ) {
							delete_post_meta( $property_id, 'REAL_HOMES_agents' );
						}
					}
				}
			} else {
				$auto_user_agent_assignment = get_option( 'realhomes_auto_user_agent_assignment' );
				if ( is_user_logged_in() ) {
					$current_user_id = get_current_user_id();
					switch ( $auto_user_agent_assignment ) {
						case 'agent':
							update_post_meta( $property_id, 'REAL_HOMES_agent_display_option', 'agent_info' );
							$user_agent_id = intval( get_the_author_meta( 'inspiry_role_post_id', $current_user_id ) );
							if ( 0 < $user_agent_id ) {
								delete_post_meta( $property_id, 'REAL_HOMES_agents' );
								add_post_meta( $property_id, 'REAL_HOMES_agents', $user_agent_id );
							}
							break;

						case 'user':
							update_post_meta( $property_id, 'REAL_HOMES_agent_display_option', 'my_profile_info' );
							break;

						default:
							update_post_meta( $property_id, 'REAL_HOMES_agent_display_option', 'none' );
					}
				}
			}

			/* Attach Property ID Post Meta */
			if ( isset( $_POST['property-id'] ) && ! empty( $_POST['property-id'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_property_id', sanitize_text_field( $_POST['property-id'] ) );
			} else {
				$auto_property_id    = get_option( 'inspiry_auto_property_id_check' );
				$property_id_pattern = get_option( 'inspiry_auto_property_id_pattern' );
				if ( ! empty( $auto_property_id ) && ( 'true' === $auto_property_id ) && ! empty( $property_id_pattern ) ) {
					$property_id_value = preg_replace( '/{ID}/', $property_id, $property_id_pattern );
					update_post_meta( $property_id, 'REAL_HOMES_property_id', sanitize_text_field( $property_id_value ) );
				}
			}

			if ( in_array( 'video', $submit_form_fields, true ) ) {
				$inspiry_video_group_sanitized = array();
				if ( isset( $_POST['inspiry_video_group'] ) && ! empty( $_POST['inspiry_video_group'] ) ) {
					$inspiry_video_group = $_POST['inspiry_video_group'];
					if ( is_array( $inspiry_video_group ) ) {
						foreach ( $inspiry_video_group as $k => $v ) {
							if ( isset( $v['inspiry_video_group_url'] ) && ! empty( $v['inspiry_video_group_url'] ) ) {
								$inspiry_video_group_sanitized[ $k ]['inspiry_video_group_url'] = esc_url_raw( $v['inspiry_video_group_url'] );

								if ( isset( $v['inspiry_video_group_title'] ) ) {
									$inspiry_video_group_sanitized[ $k ]['inspiry_video_group_title'] = sanitize_text_field( $v['inspiry_video_group_title'] );
								}

								if ( isset( $v['inspiry_video_group_image'] ) ) {
									$inspiry_video_group_sanitized[ $k ]['inspiry_video_group_image'] = sanitize_text_field( $v['inspiry_video_group_image'] );
								}
							}
						}
					}
				}
				update_post_meta( $property_id, 'inspiry_video_group', $inspiry_video_group_sanitized );
			}

			/* Attach 360 Virtual Tour Post Meta */
			if ( isset( $_POST['REAL_HOMES_360_virtual_tour'] ) ) {
				update_post_meta( $property_id, 'REAL_HOMES_360_virtual_tour', wp_kses( $_POST['REAL_HOMES_360_virtual_tour'], inspiry_embed_code_allowed_html() ) );
			}

			/* Attach Message to Reviewer */
			if ( isset( $_POST['message_to_reviewer'] ) && ! empty( $_POST['message_to_reviewer'] ) ) {
				update_post_meta( $property_id, 'inspiry_message_to_reviewer', esc_textarea( $_POST['message_to_reviewer'] ) );
			}

			/* Attach floor plans details with property */
			if ( isset( $_POST['inspiry_floor_plans'] ) && ! empty( $_POST['inspiry_floor_plans'] ) ) {
				inspiry_submit_floor_plans( $property_id, $_POST['inspiry_floor_plans'] );
			} else {
				if ( in_array( 'floor-plans', $submit_form_fields, true ) ) {
					delete_post_meta( $property_id, 'inspiry_floor_plans' );
				}
			}

			/* Attach additional details with property */
			if ( isset( $_POST['detail-titles'] ) && isset( $_POST['detail-values'] ) ) {

				$additional_details_titles = $_POST['detail-titles'];
				$additional_details_values = $_POST['detail-values'];

				$titles_count = count( $additional_details_titles );
				$values_count = count( $additional_details_values );

				// to skip empty values on submission
				if ( $titles_count == 1 && $values_count == 1 && empty( $additional_details_titles[0] ) && empty( $additional_details_values[0] ) ) {
					// do nothing and let it go
				} else {

					if ( ! empty( $additional_details_titles ) && ! empty( $additional_details_values ) ) {
						$additional_details = array_combine( $additional_details_titles, $additional_details_values );

						// Remove empty values before adding to database
						$additional_details = array_filter( $additional_details, 'strlen' );

						$additional_details_meta = array();
						foreach ( $additional_details as $title => $value ) {
							$additional_details_meta[] = array( $title, $value );
						}

						update_post_meta( $property_id, 'REAL_HOMES_additional_details_list', $additional_details_meta );
					}
				}
			} else {
				if ( in_array( 'additional-details', $submit_form_fields, true ) ) {
					delete_post_meta( $property_id, 'REAL_HOMES_additional_details_list' );
				}
			}

			/* Attach Property as Featured Post Meta */
			$featured = ( isset( $_POST['featured'] ) && ! empty( $_POST['featured'] ) ) ? 1 : 0;
			update_post_meta( $property_id, 'REAL_HOMES_featured', $featured );

			/* Property Submission Terms & Conditions */
			$terms = ( isset( $_POST['terms'] ) && ! empty( $_POST['terms'] ) ) ? 1 : 0;
			update_post_meta( $property_id, 'REAL_HOMES_terms_conditions', $terms );

			/* Property Attachments */
			if ( isset( $_POST['property_attachment_ids'] ) && ! empty( $_POST['property_attachment_ids'] ) ) {
				if ( is_array( $_POST['property_attachment_ids'] ) ) {
					foreach ( $_POST['property_attachment_ids'] as $property_attachment_id ) {
						add_post_meta( $property_id, 'REAL_HOMES_attachments', $property_attachment_id );
					}
				}
			} else {
				if ( in_array( 'attachments', $submit_form_fields, true ) ) {
					delete_post_meta( $property_id, 'REAL_HOMES_attachments' );
				}
			}

			/* If property is being updated, clean up the old meta information related to images */
			if ( $action == 'update_property' && in_array( 'images', $submit_form_fields, true ) ) {
				delete_post_meta( $property_id, 'REAL_HOMES_property_images' );
				delete_post_meta( $property_id, '_thumbnail_id' );
			}

			/* Attach gallery images with newly created property */
			if ( isset( $_POST['gallery_image_ids'] ) ) {
				if ( ! empty( $_POST['gallery_image_ids'] ) && is_array( $_POST['gallery_image_ids'] ) ) {
					$gallery_image_ids = array();
					foreach ( $_POST['gallery_image_ids'] as $gallery_image_id ) {
						$gallery_image_ids[] = intval( $gallery_image_id );
						add_post_meta( $property_id, 'REAL_HOMES_property_images', $gallery_image_id );
						/* Creating image association with the property */
						realhomes_assign_image_to_property( $property_id, $gallery_image_id );
					}
					if ( isset( $_POST['featured_image_id'] ) ) {
						$featured_image_id = intval( $_POST['featured_image_id'] );
						if ( in_array( $featured_image_id, $gallery_image_ids ) ) {     // validate featured image id
							update_post_meta( $property_id, '_thumbnail_id', $featured_image_id );

							/* if video url is provided but there is no video image then use featured image as video image */
							if ( empty( $tour_video_image ) && ! empty( $_POST['video-url'] ) ) {
								update_post_meta( $property_id, 'REAL_HOMES_tour_video_image', $featured_image_id );
							}
						}
					} else if ( ! empty( $gallery_image_ids ) ) {
						update_post_meta( $property_id, '_thumbnail_id', $gallery_image_ids[0] );

						/* if video url is provided but there is no video image then use featured image as video image */
						if ( empty( $tour_video_image ) && ! empty( $_POST['video-url'] ) ) {
							update_post_meta( $property_id, 'REAL_HOMES_tour_video_image', $gallery_image_ids[0] );
						}
					}
				}
			}

			// Property Single Gallery Type
			$change_gallery_slider_type = ( isset( $_POST['REAL_HOMES_change_gallery_slider_type'] ) && ! empty( $_POST['REAL_HOMES_change_gallery_slider_type'] ) ) ? 1 : 0;
			update_post_meta( $property_id, 'REAL_HOMES_change_gallery_slider_type', $change_gallery_slider_type );
			if ( $change_gallery_slider_type ) {
				if ( isset( $_POST['REAL_HOMES_gallery_slider_type'] ) && in_array( $_POST['REAL_HOMES_gallery_slider_type'], array(
						'thumb-on-right',
						'thumb-on-bottom',
						'img-pagination',
						'masonry-style',
						'carousel-style',
						'fw-carousel-style',
					) ) ) {
					update_post_meta( $property_id, 'REAL_HOMES_gallery_slider_type', sanitize_text_field( $_POST['REAL_HOMES_gallery_slider_type'] ) );
				}
			}

			// Property Homepage Slider Image
			$add_in_slider = ( isset( $_POST['REAL_HOMES_add_in_slider'] ) && ! empty( $_POST['REAL_HOMES_add_in_slider'] ) ) ? 'yes' : 'no';
			update_post_meta( $property_id, 'REAL_HOMES_add_in_slider', $add_in_slider );
			if ( 'yes' === $add_in_slider ) {
				if ( ! empty( $_POST['slider_image_id'] ) ) {
					update_post_meta( $property_id, 'REAL_HOMES_slider_image', intval( $_POST['slider_image_id'] ) );
				} else {
					// If no image is uploaded then there is no need to set this to yes
					update_post_meta( $property_id, 'REAL_HOMES_add_in_slider', 'no' );
					delete_post_meta( $property_id, 'REAL_HOMES_slider_image' );
				}
			} else {
				// If the option is set to no, and there is an image attached (if any)
				delete_post_meta( $property_id, 'REAL_HOMES_slider_image' );
			}

			// Property Tax ( Mortgage Calculator )
			if ( isset( $_POST['inspiry_property_tax'] ) ) {
				update_post_meta( $property_id, 'inspiry_property_tax', sanitize_text_field( $_POST['inspiry_property_tax'] ) );
			}

			// Additional Fee ( Mortgage Calculator )
			if ( isset( $_POST['inspiry_additional_fee'] ) ) {
				update_post_meta( $property_id, 'inspiry_additional_fee', sanitize_text_field( $_POST['inspiry_additional_fee'] ) );
			}

			// Property Label
			if ( isset( $_POST['inspiry_property_label'] ) ) {
				update_post_meta( $property_id, 'inspiry_property_label', sanitize_text_field( $_POST['inspiry_property_label'] ) );
			}

			// Property Label Background Color
			if ( isset( $_POST['inspiry_property_label_color'] ) ) {
				update_post_meta( $property_id, 'inspiry_property_label_color', sanitize_text_field( $_POST['inspiry_property_label_color'] ) );
			}

			// Property Owner Name
			if ( isset( $_POST['inspiry_property_owner_name'] ) ) {
				update_post_meta( $property_id, 'inspiry_property_owner_name', sanitize_text_field( $_POST['inspiry_property_owner_name'] ) );
			}

			// Property Owner Contact
			if ( isset( $_POST['inspiry_property_owner_contact'] ) ) {
				update_post_meta( $property_id, 'inspiry_property_owner_contact', sanitize_text_field( $_POST['inspiry_property_owner_contact'] ) );
			}

			// Property Owner Address
			if ( isset( $_POST['inspiry_property_owner_address'] ) ) {
				update_post_meta( $property_id, 'inspiry_property_owner_address', sanitize_text_field( $_POST['inspiry_property_owner_address'] ) );
			}

			if ( 'add_property' == $_POST['action'] ) {
				/**
				 * ere_submit_notice function in /plugins/easy-real-estate/includes/functions/property-submit.php is hooked with this hook.
				 */
				do_action( 'inspiry_after_property_submit', $property_id, $response );

				// Send success response with guest submission on.
				if ( ! is_user_logged_in() && inspiry_guest_submission_enabled() ) {
					$response['guest_submission'] = true;
					wp_send_json_success( $response );
				}

			} else if ( 'update_property' == $_POST['action'] ) {
				/**
				 * No default theme function is hooked with this hook.
				 */
				do_action( 'inspiry_after_property_update', $property_id, $response );
			}

			// Send success response with redirect url.
			$response['redirect_url'] = inspiry_property_submit_redirect( $updated_successfully, true, $property_id );
			wp_send_json_success( $response );
		}
	}

	add_action( 'wp_ajax_add_property', 'realhomes_dashboard_submit_property' );
	add_action( 'wp_ajax_update_property', 'realhomes_dashboard_submit_property' );

	// Adds action when guest submission is enabled.
	if ( ! is_user_logged_in() && inspiry_guest_submission_enabled() ) {
		add_action( 'wp_ajax_nopriv_add_property', 'realhomes_dashboard_submit_property' );
	}
}

if ( ! function_exists( 'realhomes_dashboard_submit_agent_agency' ) ) {
	/**
	 * Handles adding or updating the agent or agency post type in the RealHomes dashboard.
	 *
	 * Intended for handling the submission of agent or agency data.
	 *
	 * @since 4.3
	 */
	function realhomes_dashboard_submit_agent_agency() {
		$response = array();

		// Verify nonce.
		if ( ! wp_verify_nonce( $_POST['submit_post_nonce'], 'submit_agent_agency' ) ) {
			$response['message'] = esc_html__( 'Security check failed!', 'framework' );
			wp_send_json_error( $response );
		}

		$updated       = false;
		$post_id       = 0;
		$action_type   = isset( $_POST['action_type'] ) ? sanitize_text_field( $_POST['action_type'] ) : 'add_post';
		$post_type     = isset( $_POST['post_type'] ) ? sanitize_text_field( $_POST['post_type'] ) : 'agent';
		$is_agent_post = ( 'agent' === $post_type );
		$post_data     = array( 'post_type' => $post_type );

		// Author
		$current_user             = wp_get_current_user();
		$post_data['post_author'] = $current_user->ID;

		// Title
		if ( isset( $_POST['post_title'] ) && ! empty( $_POST['post_title'] ) ) {
			$post_data['post_title'] = sanitize_text_field( $_POST['post_title'] );
		}

		// Description
		if ( isset( $_POST['description'] ) ) {
			$post_data['post_content'] = wp_kses_post( $_POST['description'] );
		}

		// Add or Update Post
		if ( 'add_post' == $action_type ) {

			$submitted_post_status = get_option( "realhomes_submitted_{$post_type}_status", 'pending' );
			if ( ! empty( $submitted_post_status ) ) {
				$post_data['post_status'] = $submitted_post_status;
			} else {
				$post_data['post_status'] = 'pending';
			}

			// This filter is used to filter the submission arguments of post before inserting it.
			$post_data = apply_filters( "realhomes_before_{$post_type}_submit", $post_data );

			// Insert post and get its ID.
			$post_id = wp_insert_post( $post_data );

		} else if ( 'update_post' == $action_type ) {

			$post_data['post_status'] = get_option( "realhomes_updated_{$post_type}_status", 'publish' );

			$post_data['ID'] = intval( $_POST["{$post_type}_id"] );

			// This filter is used to filter the submission arguments of post before update
			$post_data = apply_filters( "realhomes_before_{$post_type}_update", $post_data );

			// Update post and get its ID.
			$post_id = wp_update_post( $post_data );
			if ( $post_id > 0 ) {
				$updated = true;
			}
		}

		// If post is added or updated successfully then move ahead
		if ( $post_id > 0 ) {

			$contact_fields = array();
			$social_fields  = array();

			// Post thumbnail
			if ( ! empty( $_POST['profile-image-id'] ) ) {
				update_post_meta( $post_id, '_thumbnail_id', intval( $_POST['profile-image-id'] ) );
			} else {
				delete_post_meta( $post_id, '_thumbnail_id' );
			}

			// Email field
			$email = ! empty( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
			update_post_meta( $post_id, "REAL_HOMES_{$post_type}_email", $email );

			// Address field
			if ( isset( $_POST['address'] ) ) {
				update_post_meta( $post_id, 'REAL_HOMES_address', sanitize_textarea_field( $_POST['address'] ) );
			}

			// Verification status field
			$verification_status = ! empty( $_POST['verification_status'] ) ? 1 : 0;
			update_post_meta( $post_id, 'ere_agent_verification_status', $verification_status );

			// Agent agency field
			if ( $is_agent_post ) {
				$agent_agency = ! empty( $_POST['agent_agency'] ) ? $_POST['agent_agency'] : 0;
				update_post_meta( $post_id, 'REAL_HOMES_agency', intval( $agent_agency ) );
			}

			// Contact fields
			if ( $is_agent_post ) {
				$contact_fields['REAL_HOMES_license_number'] = ! empty( $_POST['license-number'] ) ? $_POST['license-number'] : '';
			}

			$contact_fields['REAL_HOMES_whatsapp_number'] = ! empty( $_POST['whatsapp-number'] ) ? $_POST['whatsapp-number'] : '';
			$contact_fields['REAL_HOMES_mobile_number']   = ! empty( $_POST['mobile-number'] ) ? $_POST['mobile-number'] : '';
			$contact_fields['REAL_HOMES_office_number']   = ! empty( $_POST['office-number'] ) ? $_POST['office-number'] : '';
			$contact_fields['REAL_HOMES_fax_number']      = ! empty( $_POST['fax-number'] ) ? $_POST['fax-number'] : '';
			foreach ( $contact_fields as $contact_meta_key => $contact_field ) {
				update_post_meta( $post_id, $contact_meta_key, sanitize_text_field( $contact_field ) );
			}

			// Social links & website URL field
			$social_fields['REAL_HOMES_website']       = ! empty( $_POST['website_url'] ) ? $_POST['website_url'] : '';
			$social_fields['REAL_HOMES_facebook_url']  = ! empty( $_POST['facebook_url'] ) ? $_POST['facebook_url'] : '';
			$social_fields['REAL_HOMES_twitter_url']   = ! empty( $_POST['twitter_url'] ) ? $_POST['twitter_url'] : '';
			$social_fields['REAL_HOMES_linked_in_url'] = ! empty( $_POST['linkedin_url'] ) ? $_POST['linkedin_url'] : '';
			$social_fields['inspiry_instagram_url']    = ! empty( $_POST['instagram_url'] ) ? $_POST['instagram_url'] : '';
			$social_fields['inspiry_youtube_url']      = ! empty( $_POST['youtube_url'] ) ? $_POST['youtube_url'] : '';
			$social_fields['inspiry_pinterest_url']    = ! empty( $_POST['pinterest_url'] ) ? $_POST['pinterest_url'] : '';
			foreach ( $social_fields as $meta_key => $social_field ) {
				update_post_meta( $post_id, $meta_key, esc_url_raw( $social_field ) );
			}

			$hook_name = "realhomes_after_{$post_type}_submit";
			if ( 'update_post' == $action_type ) {
				$hook_name = "realhomes_after_{$post_type}_update";
			}
			do_action( $hook_name, $post_id, $response );

			// Default page to redirect.
			$default_module_to_redirect = ( $is_agent_post ) ? 'agents' : 'agencies';
			$redirect_page_url          = realhomes_get_dashboard_page_url( $default_module_to_redirect );

			// Custom page to redirect to.
			$redirect_page_id = get_option( "realhomes_after_{$post_type}_submit_redirect_page" );
			if ( ! empty( $redirect_page_id ) ) {
				$redirect_page_url = get_permalink( $redirect_page_id );
			}

			$redirect_url = '';
			if ( $redirect_page_url ) {
				$key = "{$post_type}-added";
				if ( ! empty( $updated ) ) {
					$key = "{$post_type}-updated";
				}
				if ( $post_id ) {
					$redirect_url = add_query_arg( array(
						$key  => 'true',
						'pid' => $post_id,
					), $redirect_page_url );
				} else {
					$redirect_url = add_query_arg( $key, 'true', $redirect_page_url );
				}
			}

			$response['redirect_url'] = $redirect_url;
			wp_send_json_success( $response );
		}
	}

	add_action( 'wp_ajax_submit_agent_agency', 'realhomes_dashboard_submit_agent_agency' );
}

if ( ! function_exists( 'realhomes_dashboard_trash_post' ) ) {
	/**
	 * Handles the removal of property, agent and agency posts from the related post table when the delete action is triggered in the dashboard.
	 *
	 * @since 4.3
	 */
	function realhomes_dashboard_trash_post() {

		if ( ! isset( $_POST['post_id'] ) ) {
			echo json_encode( array(
					'success' => false,
					'message' => esc_html__( "Invalid Parameters!", 'framework' )
				)
			);

			wp_die();
		}

		if ( ! is_user_logged_in() ) {
			echo json_encode( array(
					'success' => false,
					'message' => esc_html__( "This action required login to delete .", 'framework' )
				)
			);

			wp_die();
		}

		$post_type = 'property';
		if ( isset( $_POST['post_type'] ) && $post_type !== $_POST['post_type'] ) {
			$post_type = sanitize_text_field( $_POST['post_type'] );

			// If post type is invoice then delete invoice ID from related booking post
			if ( 'invoice' === $_POST['post_type'] ) {
				$booking_id = get_post_meta( intval( $_POST['post_id'] ), 'booking_id', true );
				delete_post_meta( $booking_id, 'rvr_invoice_id' );
			}
		}

		$post_id      = intval( $_POST['post_id'] );
		$trashed_post = wp_trash_post( $post_id );

		if ( $trashed_post ) {

			// Check if WPML is active
			if ( class_exists( 'SitePress' ) ) {
				// Get active languages configured in WPML - https://wpml.org/wpml-hook/wpml_active_languages/
				$active_languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
				if ( ! empty( $active_languages ) ) {
					foreach ( $active_languages as $language ) {
						// get translated post ID if any for current language in loop - https://wpml.org/wpml-hook/wpml_object_id/
						$translated_post_id = apply_filters( 'wpml_object_id', $post_id, $post_type, false, $language['language_code'] );
						if ( $translated_post_id && ( $translated_post_id != $post_id ) ) {
							wp_trash_post( $translated_post_id );
						}
					}
				}
			}

			echo json_encode( array(
					'success' => true,
					'message' => sprintf( esc_html__( "%s Removed Successfully!", 'framework' ), ucfirst( $post_type ) )
				)
			);

		} else {
			echo json_encode( array(
					'success' => false,
					'message' => sprintf( esc_html__( "Failed to Remove %s!", 'framework' ), ucfirst( $post_type ) )
				)
			);
		}

		wp_die();
	}

	add_action( 'wp_ajax_dashboard_trash_post', 'realhomes_dashboard_trash_post' );
}

if ( ! function_exists( 'realhomes_dashboard_js_templates' ) ) {
	/**
	 * Adds the js templates in the footer related to dashboard template.
	 *
	 * @since 3.12
	 */
	function realhomes_dashboard_js_templates() {
		if ( ! is_page_template( array( 'templates/dashboard.php' ) ) ) {
			return;
		}
		?>
        <script id="tmpl-floor-plan-clone" type="text/template">
            <div class="inspiry-clone inspiry-group-clone" data-floor-plan="{{data}}">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="inspiry-field">
                            <label for="inspiry_floor_plan_name_{{data}}"><?php esc_html_e( 'Floor Name', 'framework' ); ?></label>
                            <input type="text" id="inspiry_floor_plan_name_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_name]" value="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="inspiry-field inspiry-file-input-wrapper">
                            <label><?php esc_html_e( 'Floor Plan Image', 'framework' ); ?>
                                <span><?php esc_html_e( '* Minimum width is 770px and height is flexible.', 'framework' ); ?></span></label>
                            <div class="inspiry-btn-group clearfix">
                                <input type="text" class="inspiry-file-input" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_image]" value="">
                                <button id="inspiry-file-select-{{data}}" class="inspiry-file-select real-btn btn btn-primary"><?php esc_html_e( 'Select Image', 'framework' ); ?></button>
                                <button id="inspiry-file-remove-{{data}}" class="inspiry-file-remove real-btn btn btn-secondary hidden"><?php esc_html_e( 'Remove', 'framework' ); ?></button>
                            </div>
                        </div>
                        <div class="errors-log"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="inspiry-field">
                            <label for="inspiry_floor_plan_descr_{{data}}"><?php esc_html_e( 'Description', 'framework' ); ?></label>
                            <textarea id="inspiry_floor_plan_descr_{{data}}" class="inspiry-textarea" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_descr]"></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="inspiry-field">
                                    <label for="inspiry_floor_plan_price_{{data}}"><?php esc_html_e( 'Floor Price', 'framework' ); ?>
                                        <span><?php esc_html_e( '( Only digits )', 'framework' ); ?></span></label>
                                    <input type="text" id="inspiry_floor_plan_price_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_price]" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="inspiry-field">
                                    <label for="inspiry_floor_plan_price_postfix_{{data}}"><?php esc_html_e( 'Price Postfix', 'framework' ); ?></label>
                                    <input type="text" id="inspiry_floor_plan_price_postfix_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_price_postfix]" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="inspiry-field">
                                    <label for="inspiry_floor_plan_size_{{data}}"><?php esc_html_e( 'Floor Size', 'framework' ); ?>
                                        <span><?php esc_html_e( '( Only digits )', 'framework' ); ?></span></label>
                                    <input type="text" id="inspiry_floor_plan_size_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_size]" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="inspiry-field">
                                    <label for="inspiry_floor_plan_size_postfix_{{data}}"><?php esc_html_e( 'Size Postfix', 'framework' ); ?></label>
                                    <input type="text" id="inspiry_floor_plan_size_postfix_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_size_postfix]" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="inspiry-field">
                                    <label for="inspiry_floor_plan_bedrooms_{{data}}"><?php esc_html_e( 'Bedrooms', 'framework' ); ?></label>
                                    <input type="text" id="inspiry_floor_plan_bedrooms_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_bedrooms]" value="">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="inspiry-field">
                                    <label for="inspiry_floor_plan_bathrooms_{{data}}"><?php esc_html_e( 'Bathrooms', 'framework' ); ?></label>
                                    <input type="text" id="inspiry_floor_plan_bathrooms_{{data}}" name="inspiry_floor_plans[{{data}}][inspiry_floor_plan_bathrooms]" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="inspiry-remove-clone"><i class="fas fa-minus"></i></button>
            </div>
        </script>
        <script id="tmpl-additional-details" type="text/template">
            <div class="inspiry-detail">
                <div class="inspiry-detail-sort-handle"><i class="fas fa-grip-horizontal"></i></div>
                <div class="inspiry-detail-title">
                    <label class="inspiry-repeater-field-label"><?php esc_attr_e( 'Title', 'framework' ); ?></label>
                    <input type="text" name="detail-titles[]" />
                </div>
                <div class="inspiry-detail-value">
                    <label class="inspiry-repeater-field-label"><?php esc_attr_e( 'Value', 'framework' ); ?></label>
                    <input type="text" name="detail-values[]" />
                </div>
                <div class="inspiry-detail-remove-detail">
                    <button class="remove-detail btn btn-primary"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>
        </script>
        <script id="tmpl-dashboard-notice" type="text/template">
            <div class="dashboard-notice {{ data.type }} is-dismissible">
                <p>{{ data.message }}</p>
                <button type="button" class="dashboard-notice-dismiss-button"><i class="fas fa-times"></i></button>
            </div>
        </script>
        <script id="tmpl-video-group" type="text/template">
			<?php
			$inspiry_video_group_fields = array(
				array( 'name' => 'inspiry_video_group[{{data}}][inspiry_video_group_title]' ),
				array( 'name' => 'inspiry_video_group[{{data}}][inspiry_video_group_url]' )
			);
			inspiry_repeater_group( $inspiry_video_group_fields, true );
			?>
        </script>
		<?php
		if ( inspiry_is_rvr_enabled() ) :
			/**
			 * Js templates for RVR repeater fields.
			 *
			 * @since   3.13.0
			 * @updated 4.1.0
			 */
			?>
            <script id="tmpl-rvr-reserve-booking-dates" type="text/template">
				<?php
				$rvr_reserve_booking_dates_fields = array(
					array( 'name' => 'rvr_custom_reserved_dates[{{data}}][rvr_reserve_note]', 'class' => 'rvr_reserve_note', 'label' => esc_html__( 'Reservation Note', 'framework' ) ),
					array( 'name' => 'rvr_custom_reserved_dates[{{data}}][rvr_reserve_start_date]', 'class' => 'rvr_reserve_start_date', 'label' => esc_html__( 'Start Date', 'framework' ) ),
					array( 'name' => 'rvr_custom_reserved_dates[{{data}}][rvr_reserve_end_date]', 'class' => 'rvr_reserve_end_date', 'label' => esc_html__( 'End Date', 'framework' ) )
				);
				inspiry_repeater_group( $rvr_reserve_booking_dates_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-seasonal-prices" type="text/template">
				<?php
				$rvr_custom_seasonal_prices_fields = array(
					array( 'name' => 'rvr_custom_seasonal_prices[{{data}}][rvr_price_start_date]', 'class' => 'rvr_seasonal_start_date' ),
					array( 'name' => 'rvr_custom_seasonal_prices[{{data}}][rvr_price_end_date]', 'class' => 'rvr_seasonal_end_date' ),
					array( 'name' => 'rvr_custom_seasonal_prices[{{data}}][rvr_price_amount]', 'class' => 'rvr_seasonal_price' )
				);
				inspiry_repeater_group( $rvr_custom_seasonal_prices_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-outdoor-features" type="text/template">
				<?php
				$rvr_outdoor_features_fields = array( array( 'name' => 'rvr_outdoor_features[]' ) );
				inspiry_repeater_group( $rvr_outdoor_features_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-included" type="text/template">
				<?php
				$rvr_included_fields = array( array( 'name' => 'rvr_included[]' ) );
				inspiry_repeater_group( $rvr_included_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-not-included" type="text/template">
				<?php
				$rvr_not_included_fields = array( array( 'name' => 'rvr_not_included[]' ) );
				inspiry_repeater_group( $rvr_not_included_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-surroundings" type="text/template">
				<?php
				$rvr_surroundings_fields = array(
					array( 'name' => 'rvr_surroundings[{{data}}][rvr_surrounding_point]', 'label' => esc_html__( 'Point of Interest', 'framework' ) ),
					array( 'name' => 'rvr_surroundings[{{data}}][rvr_surrounding_point_distance]', 'label' => esc_html__( 'Distance or How to approach', 'framework' ) )
				);
				inspiry_repeater_group( $rvr_surroundings_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-policies" type="text/template">
				<?php
				$rvr_policies_fields = array(
					array( 'name' => 'rvr_policies[{{data}}][rvr_policy_detail]', 'label' => esc_html__( 'Policy Text', 'framework' ) ),
					array( 'name' => 'rvr_policies[{{data}}][rvr_policy_icon]', 'label' => esc_html__( 'Font Awesome Icon (i.e far fa-star)', 'framework' ) )
				);
				inspiry_repeater_group( $rvr_policies_fields, true );
				?>
            </script>
            <script id="tmpl-rvr-icalendar" type="text/template">
				<?php
				$rvr_icalendar_fields = array(
					array( 'name' => 'rvr_import_icalendar_feed_list[{{data}}][feed_name]', 'label' => esc_html__( 'Feed Name', 'framework' ) ),
					array( 'name' => 'rvr_import_icalendar_feed_list[{{data}}][feed_url]', 'label' => esc_html__( 'Feed URL', 'framework' ) )
				);
				inspiry_repeater_group( $rvr_icalendar_fields, true );
				?>
            </script>
		<?php
		endif;
	}

	add_action( "wp_footer", "realhomes_dashboard_js_templates" );
}

if ( ! function_exists( 'realhomes_dashboard_assets' ) ) {
	/**
	 * Provides dashboard assets.
	 *
	 * @since 3.12
	 */
	function realhomes_dashboard_assets() {

		if ( ! is_page_template( 'templates/dashboard.php' ) ) {
			return;
		}

		// Bootstrap Select
		wp_enqueue_style(
			'vendors-css',
			get_theme_file_uri( 'common/js/vendors/bootstrap-select/bootstrap-select.min.css' ),
			array(),
			INSPIRY_THEME_VERSION,
			'all'
		);

		// Google Fonts
		wp_enqueue_style(
			'dashboard-font',
			inspiry_google_fonts(),
			array(),
			INSPIRY_THEME_VERSION
		);

		// FontAwesome 5
		wp_enqueue_style( 'font-awesome-5-all',
			get_theme_file_uri( 'common/font-awesome/css/all.min.css' ),
			array(),
			'5.13.1',
			'all'
		);

		// Dashboard Styles
		wp_enqueue_style(
			'dashboard-styles',
			get_theme_file_uri( 'common/css/dashboard.min.css' ),
			array(),
			INSPIRY_THEME_VERSION,
			'all'
		);

		// Adds inline dashboard styles
		wp_add_inline_style( 'dashboard-styles', apply_filters( 'realhomes_dashboard_custom_css', '' ) );

		// Remove ERE plugin script
		wp_dequeue_script( 'ere-frontend' );

		// Bootstrap plugin script
		wp_enqueue_script(
			'bootstrap-min',
			get_theme_file_uri( 'common/js/vendors/bootstrap-select/bootstrap.min.js' ),
			array( 'jquery' ),
			INSPIRY_THEME_VERSION,
			true
		);

		wp_enqueue_script(
			'bootstrap-select-min',
			get_theme_file_uri( 'common/js/vendors/bootstrap-select/bootstrap-select.min.js' ),
			array( 'jquery' ),
			INSPIRY_THEME_VERSION,
			true
		);

		// Login Script
		if ( ! is_user_logged_in() ) {
			wp_enqueue_script(
				'inspiry-login',
				get_theme_file_uri( 'common/js/inspiry-login.js' ),
				array( 'jquery' ),
				INSPIRY_THEME_VERSION,
				true
			);
		}

		// jQuery validate
		wp_enqueue_script(
			'jquery-validate',
			get_theme_file_uri( 'common/js/vendors/jquery.validate.min.js' ),
			array( 'jquery', 'jquery-form' ),
			INSPIRY_THEME_VERSION,
			true
		);

		/**
		 * Maps Script
		 */
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		$map_type = inspiry_get_maps_type();

		if ( 'google-maps' == $map_type ) {
			inspiry_enqueue_google_maps();
		} else if ( 'mapbox' == $map_type ) {
			realhomes_enqueue_mapbox();
		} else {
			inspiry_enqueue_open_street_map();
		}

		// Google reCaptcha
		if ( class_exists( 'Easy_Real_Estate' ) && ere_is_reCAPTCHA_configured() ) {
			$reCPATCHA_type = get_option( 'inspiry_reCAPTCHA_type', 'v2' );

			if ( 'v3' === $reCPATCHA_type ) {
				$render = get_option( 'theme_recaptcha_public_key' );
			} else {
				$render = 'explicit';
			}

			$recaptcha_src = esc_url_raw( add_query_arg( array(
				'render' => $render,
				'onload' => 'loadInspiryReCAPTCHA',
			), '//www.google.com/recaptcha/api.js' ) );

			// Enqueue google reCAPTCHA API.
			wp_enqueue_script( 'rh-google-recaptcha', $recaptcha_src, array(), INSPIRY_THEME_VERSION, true );
		}

		// WP Picker
		if ( in_array( 'label-and-color', inspiry_get_submit_fields(), true ) ) {

			wp_enqueue_style( 'wp-color-picker' );

			wp_enqueue_script(
				'iris',
				admin_url( 'js/iris.min.js' ),
				array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
				false,
				1
			);

			wp_enqueue_script(
				'wp-color-picker',
				admin_url( 'js/color-picker.min.js' ),
				array( 'iris' ),
				false,
				1
			);
		}

		// locations related script
		wp_enqueue_script(
			'realhomes-locations',
			get_theme_file_uri( 'common/js/locations.js' ),
			array( 'jquery' ),
			INSPIRY_THEME_VERSION,
			true
		);

		// Dashboard Scrips
		wp_enqueue_script(
			'dashboard-js',
			get_theme_file_uri( 'common/js/dashboard.js' ),
			array( 'jquery', 'wp-util', 'jquery-ui-sortable', 'plupload' ),
			INSPIRY_THEME_VERSION,
			true
		);

		// Dashboard js data.
		$dashboard_data = array(
			'url'               => esc_url( realhomes_get_dashboard_page_url() ),
			'ajaxURL'           => admin_url( 'admin-ajax.php' ),
			'uploadNonce'       => wp_create_nonce( 'inspiry_allow_upload' ),
			'fileTypeTitle'     => esc_html__( 'Valid file formats', 'framework' ),
			'cancel_membership' => esc_html__( 'Cancel Membership', 'framework' ),
			'clear'             => esc_html__( 'Clear', 'framework' ),
			'pick'              => esc_html__( 'Select Color', 'framework' ),
			'select_noResult'   => get_option( 'inspiry_select2_no_result_string', esc_html__( 'No Results Found!', 'framework' ) ),
			'searching_string'  => esc_html__( 'Searching...', 'framework' ),
			'loadingMore'       => esc_html__( 'Loading more results...', 'framework' ),
			'returnValue'       => esc_html__( 'Are you sure you want to exit?', 'framework' ),
			'local'             => get_option( 'ere_price_number_format_language', 'en-US' ),
		);

		// Adds inline dashboard script
		wp_localize_script( 'dashboard-js', 'dashboardData', $dashboard_data );

		if ( isset( $_GET['ask-for-login'] ) && ! empty( $_GET['ask-for-login'] && $_GET['ask-for-login'] === 'true' ) ) {
			wp_add_inline_script( 'dashboard-js', "jQuery(document).ready(function(){ jQuery('body .ask-for-login').trigger('click'); });" );
		}
	}

	add_action( 'wp_enqueue_scripts', 'realhomes_dashboard_assets' );
}

if ( ! function_exists( 'realhomes_dashboard_color_settings' ) ) {
	/**
	 * Provides dashboard color settings list.
	 *
	 * @since 3.12
	 *
	 * @return array
	 */
	function realhomes_dashboard_color_settings() {

		$color_settings = array(
			array(
				'id'      => 'primary_color',
				'label'   => esc_html__( 'Primary Color', 'framework' ),
				'default' => '#1ea69a'
			),
			array(
				'id'      => 'secondary_color',
				'label'   => esc_html__( 'Secondary Color', 'framework' ),
				'default' => '#ea723d'
			),
			array(
				'id'      => 'body_color',
				'label'   => esc_html__( 'Body Text Color', 'framework' ),
				'default' => '#808080'
			),
			array(
				'id'      => 'heading_color',
				'label'   => esc_html__( 'Heading Color', 'framework' ),
				'default' => '#333333'
			),
			array(
				'id'      => 'link_color',
				'label'   => esc_html__( 'Link Color', 'framework' ),
				'default' => '#333333'
			),
			array(
				'id'      => 'link_hover_color',
				'label'   => esc_html__( 'Link Hover Color', 'framework' ),
				'default' => '#e86126'
			),
			array(
				'id'      => 'logo_container_bg_color',
				'label'   => esc_html__( 'Logo Background Color', 'framework' ),
				'default' => '#2f3534'
			),
			array(
				'id'      => 'logo_color',
				'label'   => esc_html__( 'Logo Color', 'framework' ),
				'default' => '#fff'
			),
			array(
				'id'      => 'logo_hover_color',
				'label'   => esc_html__( 'Logo Hover Color', 'framework' ),
				'default' => '#fff'
			),
			array(
				'id'      => 'sidebar_bg_color',
				'label'   => esc_html__( 'Sidebar Background Color', 'framework' ),
				'default' => '#1e2323'
			),
			array(
				'id'      => 'sidebar_menu_color',
				'label'   => esc_html__( 'Sidebar Menu Text Color', 'framework' ),
				'default' => '#91afad'
			),
			array(
				'id'      => 'sidebar_menu_bg_color',
				'label'   => esc_html__( 'Sidebar Menu Background Color', 'framework' ),
				'default' => '#1e2323'
			),
			array(
				'id'      => 'sidebar_menu_hover_color',
				'label'   => esc_html__( 'Sidebar Menu Text Hover Color', 'framework' ),
				'default' => '#ffffff'
			),
			array(
				'id'      => 'sidebar_menu_hover_bg_color',
				'label'   => esc_html__( 'Sidebar Menu Hover Background Color', 'framework' ),
				'default' => '#1e3331'
			),
			array(
				'id'      => 'sidebar_current_submenu_color',
				'label'   => esc_html__( 'Sidebar Active Submenu Text Color', 'framework' ),
				'default' => '#ffffff'
			),
			array(
				'id'      => 'sidebar_current_submenu_bg_color',
				'label'   => esc_html__( 'Sidebar Active Submenu Background Color', 'framework' ),
				'default' => '#171b1b'
			),
			array(
				'id'      => 'bookings_invoices_price_color',
				'label'   => esc_html__( 'Properties, Bookings & Invoices Price Color', 'framework' ),
				'default' => '#20b759'
			)
		);

		/**
		 * Membership package color settings.
		 * @since  3.12
		 */
		if ( class_exists( 'IMS_Helper_Functions' ) ) {

			$color_settings[] = array(
				'id'      => 'package_background_color',
				'label'   => esc_html__( 'Membership Package Background Color', 'framework' ),
				'default' => '#d5f0eb'
			);

			$color_settings[] = array(
				'id'      => 'popular_package_background_color',
				'label'   => esc_html__( 'Popular Membership Package Background Color', 'framework' ),
				'default' => '#f7daca'
			);
		}

		return $color_settings;
	}
}

if ( ! function_exists( 'realhomes_dashboard_dynamic_css' ) ) {
	/**
	 * Adds dashboard dynamic css.
	 *
	 * @since 3.12
	 *
	 * @param $custom_css
	 *
	 * @return string
	 */
	function realhomes_dashboard_dynamic_css( $custom_css ) {

		$output = '';

		if ( 'default' === get_option( 'realhomes_dashboard_color_scheme', 'default' ) ) {
			$color_settings = realhomes_dashboard_get_color_scheme( 'default' );
			if ( is_array( $color_settings ) && ! empty( $color_settings ) ) {

				// Remove label key from array
				unset( $color_settings['label'] );

				foreach ( $color_settings as $key => $value ) {
					$output .= sprintf( '--dashboard-%s-color: %s;', str_replace( '_', '-', esc_html( $key ) ), sanitize_hex_color( $value ) );
				}

				$primary_color = sanitize_hex_color( $color_settings['primary'] );
				$output        .= sprintf( '--dashboard-primary-rgb-color: %s;', inspiry_hex_to_rgba( $primary_color, false, true ) );
				$output        .= sprintf( '--dashboard-primary-hover-color: %s;', inspiry_hex_darken( $primary_color, 5 ) );
				$output        .= sprintf( '--dashboard-secondary-hover-color: %s;', inspiry_hex_darken( sanitize_hex_color( $color_settings['secondary'] ), 5 ) );
			}

		} else {
			$color_settings = realhomes_dashboard_color_settings();
			if ( is_array( $color_settings ) && ! empty( $color_settings ) ) {
				foreach ( $color_settings as $setting ) {
					$output .= sprintf( '--dashboard-%s: %s;', str_replace( '_', '-', esc_html( $setting['id'] ) ), get_option( 'inspiry_dashboard_' . $setting['id'], $setting['default'] ) );
				}
			}

			$primary_color = get_option( 'inspiry_dashboard_primary_color', '#1ea69a' );
			$output        .= sprintf( '--dashboard-primary-rgb-color: %s;', inspiry_hex_to_rgba( $primary_color, false, true ) );
			$output        .= sprintf( '--dashboard-primary-hover-color: %s;', inspiry_hex_darken( $primary_color, 5 ) );
			$output        .= sprintf( '--dashboard-secondary-hover-color: %s;', inspiry_hex_darken( get_option( 'inspiry_dashboard_secondary_color', '#ea723d' ), 5 ) );
		}

		$custom_css .= ":root {";
		$custom_css .= $output;
		$custom_css .= "}";

		return $custom_css;
	}

	add_filter( 'realhomes_dashboard_custom_css', 'realhomes_dashboard_dynamic_css' );
}

if ( ! function_exists( 'realhomes_dashboard_analytics_process' ) ) {
	/**
	 * This function process and return the analytics information to ajax request
	 * for admin dashboard
	 */
	function realhomes_dashboard_analytics_process() {

		if ( ! wp_verify_nonce( $_POST['nonce'], "dashboard_analytics_nonce" ) ) {

			echo json_encode( array(
				'type'    => 'error',
				'message' => esc_html__( "No naughty business please", 'framework' )
			) );

			die();
		}

		$post_id      = ( isset( $_POST['post_id'] ) && 0 < intval( $_POST['post_id'] ) ) ? $_POST['post_id'] : 0;
		$request_type = isset( $_POST['request_type'] ) ? $_POST['request_type'] : '';
		$views        = array();

		// Default colors
		$charts_default_colors = [
			'#c5c9f3',
			'#fff2cc',
			'#cfe2f3',
			'#eae9d1',
			'#ead1dc',
			'#8b87ff',
			'#f58495',
			'#423dbf',
			'#c90c0c'
		];

		// Default Labels
		$default_chart_values = array(
			0 => array(
				'label'           => esc_html__( 'Visits', 'framework' ),
				'borderColor'     => $charts_default_colors[5],
				'backgroundColor' => $charts_default_colors[5] . '33' // Adding opacity level
			),
			1 => array(
				'label'           => esc_html__( 'Unique Visits', 'framework' ),
				'borderColor'     => $charts_default_colors[6],
				'backgroundColor' => $charts_default_colors[6] . '33' // Adding opacity level
			)
		);

		if ( $request_type === 'top_views' ) {

			$views_type = isset( $_POST['views_type'] ) ? esc_html( $_POST['views_type'] ) : 'today';

			// Setting default colors
			$views['charts']['colors'] = array( $charts_default_colors[5], $charts_default_colors[6] );

			// Values in sets for related charts
			$views['charts']['labels'] = array(
				esc_html__( 'Views', 'framework' ),
				esc_html__( 'Unique', 'framework' )
			);

			if ( $views_type === 'today_views' ) {

				// Getting today's total views from query
				$today_views = ere_get_properties_by_time_period( array(
					'time_start' => strtotime( "Today" ),
					'post_id'    => $post_id
				) );

				// Getting today's unique views from query
				$today_unique = ere_get_properties_by_time_period( array(
					'time_start' => strtotime( "Today" ),
					'post_id'    => $post_id,
					'unique'     => true
				) );

				$views['total_views']      = $today_views[0]->PID;
				$views['unique_views']     = $today_unique[0]->PID;
				$views['charts']['values'] = array( $views['total_views'], $views['unique_views'] );

				// Confirming the success of this request
				$views['success'] = true;

				// Encoding for json response
				echo json_encode( $views );

				// Ending the game for this request here
				die();

			} else if ( $views_type === 'this_week_views' ) {

				// Getting this week monday date
				$currentDayOfWeek = date( 'w' ); // Getting current day of the week
				$days_to_monday   = ( $currentDayOfWeek == 0 ) ? 6 : ( $currentDayOfWeek - 1 ); // Calculating the difference till monday
				$week_start_date  = date( 'Y-m-d', strtotime( "-$days_to_monday days" ) );

				$this_week_views = ere_get_properties_by_time_period( array(
					'time_start' => strtotime( $week_start_date ),
					'post_id'    => $post_id
				) );

				$this_week_unique = ere_get_properties_by_time_period( array(
					'time_start' => strtotime( $week_start_date ),
					'post_id'    => $post_id,
					'unique'     => true
				) );

				$views['total_views']      = $this_week_views[0]->PID;
				$views['unique_views']     = $this_week_unique[0]->PID;
				$views['charts']['values'] = array( $views['total_views'], $views['unique_views'] );

				// Confirming the success of this request
				$views['success'] = true;

				// Encoding for json response
				echo json_encode( $views );

				// Ending the game for this request here
				die();

			} else if ( $views_type === 'this_month_views' ) {

				// Getting this month first date
				$current_date       = new DateTime(); // Getting current date
				$first_day_array    = new DateTime( 'first day of ' . $current_date->format( 'F Y' ) ); // Getting the first day of month
				$first_day_of_month = $first_day_array->format( 'Y-m-d' );

				$this_month_views = ere_get_properties_by_time_period( array(
					'time_start' => strtotime( $first_day_of_month ),
					'post_id'    => $post_id
				) );

				$this_month_unique = ere_get_properties_by_time_period( array(
					'time_start' => strtotime( $first_day_of_month ),
					'post_id'    => $post_id,
					'unique'     => true
				) );

				$views['total_views']      = $this_month_views[0]->PID;
				$views['unique_views']     = $this_month_unique[0]->PID;
				$views['charts']['values'] = array( $views['total_views'], $views['unique_views'] );

				// Confirming the success of this request
				$views['success'] = true;

				// Encoding for json response
				echo json_encode( $views );

				// Ending the game for this request here
				die();

			} else {

				if ( ! $post_id ) {
					$views['total_views'] = ere_get_properties_all_time_views();
				} else {
					$all_time_views       = ere_get_properties_by_time_period( array( 'post_id' => $post_id ) );
					$views['total_views'] = $all_time_views[0]->PID;
				}

				$all_time_unique           = ere_get_properties_by_time_period( array( 'post_id' => $post_id, 'unique' => true ) );
				$views['unique_views']     = $all_time_unique[0]->PID;
				$views['charts']['values'] = array( $views['total_views'], $views['unique_views'] );

				// Confirming the success of this request
				$views['success'] = true;

				// Encoding for json response
				echo json_encode( $views );

				// Ending the game for this request here
				die();
			}

		} else if ( $request_type === 'visits_line_graph' ) {

			// Visits Graph Work
			$day_times = 24;
			$this_time = 0;

			// Adding initial static day times values
			$day_times_array           = array();
			$day_times_array['values'] = $default_chart_values;
			while ( $this_time < $day_times ) {
				$this_time                   = $this_time < 10 ? '0' . $this_time : $this_time;
				$time_start_string           = $this_time . ':00:00';
				$time_end_string             = $this_time . ':59:59';
				$day_time_start_string       = date( 'Y-m-d ' . $time_start_string );
				$day_time_end_string         = date( 'Y-m-d ' . $time_end_string );
				$day_times_array['labels'][] = $this_time . ':00';

				$day_times_args['unique']     = false;
				$day_times_args['time_start'] = strtotime( $day_time_start_string );
				$day_times_args['time_end']   = strtotime( $day_time_end_string );
				if ( 0 < $post_id ) {
					$day_times_args['post_id'] = $post_id;
				}

				$day_times_array['times']['starting'][]     = $day_time_start_string;
				$day_times_array['times']['ending'][]       = $day_time_end_string;
				$day_times_array['timestamp']['starting'][] = strtotime( $day_time_start_string );
				$day_times_array['timestamp']['ending'][]   = strtotime( $day_time_end_string );
				$today_views                                = ere_get_properties_by_time_period( $day_times_args );
				$day_times_array['values'][0]['data'][]     = $today_views[0]->PID;
				$day_times_args['unique']                   = true;
				$today_unique_views                         = ere_get_properties_by_time_period( $day_times_args );
				$day_times_array['values'][1]['data'][]     = count( $today_unique_views );

				$this_time++;
			}

			$visits['today_times_chart'] = $day_times_array;

			// This week, day by day data processing
			$week_days_array['days'] = array(
				esc_html__( 'Monday', 'framework' ),
				esc_html__( 'Tuesday', 'framework' ),
				esc_html__( 'Wednesday', 'framework' ),
				esc_html__( 'Thursday', 'framework' ),
				esc_html__( 'Friday', 'framework' ),
				esc_html__( 'Saturday', 'framework' ),
				esc_html__( 'Sunday', 'framework' )
			);

			// Adding initial static week values
			$week_days_array['values'] = $default_chart_values;

			$currentDay = date( 'N' ) - 1;
			$day_count  = 1;
			while ( $day_count <= 7 ) {
				$week_day              = strtotime( '-' . ( $currentDay ) . ' day' );
				$week_day_start_string = date( 'Y-m-d 00:00:00', $week_day );
				$week_day_end_string   = date( 'Y-m-d 23:59:59', $week_day );

				$week_days_args['unique']     = false;
				$week_days_args['time_start'] = strtotime( $week_day_start_string );
				$week_days_args['time_end']   = strtotime( $week_day_end_string );
				if ( 0 < $post_id ) {
					$week_days_args['post_id'] = $post_id;
				}
				$week_views                             = ere_get_properties_by_time_period( $week_days_args );
				$week_days_array['values'][0]['data'][] = $week_views[0]->PID;
				$week_days_args['unique']               = true;
				$week_unique_views                      = ere_get_properties_by_time_period( $week_days_args );
				$week_days_array['values'][1]['data'][] = $week_unique_views[0]->PID ?? 0;
				$day_count++;
				$currentDay--;
			}

			$visits['this_week_chart'] = $week_days_array;


			// This month day by day data processing
			$current_month = date( 'm' );
			$current_year  = date( 'y' );
			$days_in_month = date( 't' );
			$this_day      = 1;

			// Adding initial static month values
			$month_days_array['values'] = $default_chart_values;

			while ( $this_day <= $days_in_month ) {
				$dt_start                    = $current_year . '-' . $current_month . '-' . $this_day . ' 00:00:01';
				$dt_end                      = $current_year . '-' . $current_month . '-' . $this_day . ' 23:59:59';
				$day_time_args['unique']     = false;
				$day_time_args['time_start'] = strtotime( $dt_start );
				$day_time_args['time_end']   = strtotime( $dt_end );
				if ( 0 < $post_id ) {
					$day_time_args['post_id'] = $post_id;
				}
				$month_days_array['days'][]              = $this_day;
				$month_views                             = ere_get_properties_by_time_period( $day_time_args );
				$month_days_array['values'][0]['data'][] = $month_views[0]->PID ?? 0;
				$day_time_args['unique']                 = true;
				$month_unique_views                      = ere_get_properties_by_time_period( $day_time_args );
				$month_days_array['values'][1]['data'][] = $month_unique_views[0]->PID ?? 0;
				$this_day++;
			}

			$visits['this_month_chart'] = $month_days_array;

			// Confirming the success of this request
			$visits['success'] = true;

			// Encoding for json response
			echo json_encode( $visits );

			// Ending the game for this request here
			die();

		} else if ( $request_type === 'taxonomy_pie_charts' ) {

			// Famous locations bar chart
			$taxonomy_args = array(
				'taxonomy' => 'property-city',
				'orderby'  => 'count',
				'order'    => 'DESC',
				'number'   => 4
			);

			$terms               = get_terms( $taxonomy_args );
			$color_counter       = 0;
			$color_opacity       = '33'; // the last opacity value for color hax code
			$taxonomy_chart_data = array();

			foreach ( $terms as $term ) {
				$taxonomy_chart_data['labels'][]   = $term->name;
				$taxonomy_chart_data['values'][]   = $term->count;
				$taxonomy_chart_data['colors'][]   = $charts_default_colors[ $color_counter ];
				$taxonomy_chart_data['bgcolors'][] = $charts_default_colors[ $color_counter++ ] . $color_opacity;
			}

			// Confirming the success of this request
			$taxonomy_chart_data['success'] = true;

			// Encoding for json response
			echo json_encode( $taxonomy_chart_data );

			// Ending the game for this request here
			die();

		} else if ( $request_type === 'doughnut_charts' ) {

			// Getting data for devices chart
			$devices_args = array( 'column' => 'Device', 'limit' => 4 );
			if ( $post_id ) {
				$devices_args['post_id'] = $post_id;
			}
			$devices_data       = ere_get_analytics_views_sorted_data( $devices_args );
			$devices_chart_data = array();

			if ( is_array( $devices_data ) && 0 < count( $devices_data ) ) {
				$color_counter = 3;
				foreach ( $devices_data as $country ) {
					// Older views with no device added will be skipped
					if ( null !== $country->Device ) {
						$devices_chart_data['labels'][] = $country->Device;
						$devices_chart_data['values'][] = $country->ValueCount;
						$devices_chart_data['colors'][] = $charts_default_colors[ $color_counter++ ];
					}
				}
				$doughnut_charts['devices_chart'] = $devices_chart_data;
			}

			// Getting data for platforms chart
			$platform_args = array( 'column' => 'OS', 'others' => true, 'limit' => 4 );
			if ( $post_id ) {
				$platform_args['post_id'] = $post_id;
			}
			$platform_data       = ere_get_analytics_views_sorted_data( $platform_args );
			$platform_chart_data = array();

			if ( is_array( $platform_data ) && 0 < count( $platform_data ) ) {
				$color_counter = 3;
				foreach ( $platform_data as $platform ) {
					$platform_chart_data['labels'][] = $platform->OS;
					$platform_chart_data['values'][] = $platform->ValueCount;
					$platform_chart_data['colors'][] = $charts_default_colors[ $color_counter++ ];
				}
				$doughnut_charts['platform_chart'] = $platform_chart_data;
			}

			// Getting data for browsers chart
			$browser_args = array( 'column' => 'Browser', 'others' => true, 'limit' => 3 );
			if ( $post_id ) {
				$browser_args['post_id'] = $post_id;
			}
			$browser_data       = ere_get_analytics_views_sorted_data( $browser_args );
			$browser_chart_data = array();

			if ( is_array( $browser_data ) && 0 < count( $browser_data ) ) {
				$color_counter = 3;
				foreach ( $browser_data as $browser ) {
					$browser_chart_data['labels'][] = $browser->Browser;
					$browser_chart_data['values'][] = $browser->ValueCount;
					$browser_chart_data['colors'][] = $charts_default_colors[ $color_counter++ ];
				}
				$doughnut_charts['browser_chart'] = $browser_chart_data;
			}

			// Confirming the success of this request
			$doughnut_charts['success'] = true;

			// Encoding for json response
			echo json_encode( $doughnut_charts );

			// Ending the game for this request here
			die();

		} else {

			// Getting data for countries list
			$countries_args = array( 'others' => true, 'limit' => 10 );
			if ( $post_id ) {
				$countries_args['post_id'] = $post_id;
			}
			$countries_data         = ere_get_analytics_views_sorted_data( $countries_args );
			$countries_list['data'] = $countries_data;

			// Confirming the success of this request
			$countries_list['success'] = true;

			// Encoding for json response
			echo json_encode( $countries_list );

			// Ending the game for this request here
			die();
		}

	}

	add_action( 'wp_ajax_dashboard_analytics_process', 'realhomes_dashboard_analytics_process' );
}

if ( ! function_exists( 'realhomes_dashboard_sidebar_display_control' ) ) {
	/**
	 * Managing show/hide option update with ajax request for dashboard sidebar display
	 *
	 * @since 4.0.2
	 *
	 * @return bool
	 */
	function realhomes_dashboard_sidebar_display_control() {

		// Making sure that request is coming from the proper place
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'dashboard_sidebar_display_nonce' ) ) {
			echo esc_html__( 'Security check failed!', 'framework' );

			return false;
		}

		// Setting the selected option directly after checking the request
		if ( isset( $_POST['displayOption'] ) ) {

			update_option( 'realhomes_dashboard_sidebar_display', esc_html( $_POST['displayOption'] ) );

			return true;
		}

		return false;
	}

	add_action( 'wp_ajax_realhomes_dashboard_sidebar_display_control', 'realhomes_dashboard_sidebar_display_control' );
	add_action( 'wp_ajax_nopriv_realhomes_dashboard_sidebar_display_control', 'realhomes_dashboard_sidebar_display_control' );
}

if ( ! function_exists( 'realhomes_is_dashboard_page' ) ) {
	/**
	 * Checks whether the current dashboard page matches the specified module and optionally submodule.
	 *
	 * This function determines if the user is currently viewing a specific module, or optionally, a submodule
	 * within the RealHomes dashboard. It utilizes the global dashboard settings to check the current module
	 * and submodule.
	 *
	 * @since 4.3.2
	 *
	 * @param string $module The module to check against the current dashboard page.
	 * @param string|bool $submodule Optional. The submodule to check against the current dashboard page. Default false.
	 * @return bool True if the current dashboard page matches the specified module and optionally submodule, false otherwise.
	 */
	function realhomes_is_dashboard_page( $module, $submodule = false ) {
		$field             = 'current_module';
		$dashboard_globals = realhomes_dashboard_globals();

		if ( $submodule ) {
			$field = 'submodule';
		}

		if ( isset( $dashboard_globals[ $field ] ) && ( $module === $dashboard_globals[ $field ] ) ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'realhomes_dashboard_is_submit_property_page' ) ) {
	/**
	 * Checks whether the current dashboard page is the "Submit Property" page.
	 *
	 * This function determines if the user is currently viewing the "Submit Property"
	 * submodule within the RealHomes dashboard. It utilizes the global dashboard
	 * settings to check the current submodule.
	 *
	 * @since 4.3.2
	 *
	 * @return bool True if the current dashboard page is the "Submit Property" page, false otherwise.
	 */
	function realhomes_dashboard_is_submit_property_page() {
		return realhomes_is_dashboard_page( 'submit-property', true );
	}
}

if ( ! function_exists( 'realhomes_generate_thumbnails_on_upload' ) ) {
	/**
	 * Generates 150x150 thumbnails for previews in gallery area of submit property page of
	 * Dashboard if default media sizes are disabled.
	 *
	 * @param array $metadata      Metadata of the uploaded image.
	 * @param int   $attachment_id ID of the uploaded attachment.
	 *
	 * @return array Modified metadata with manually generated thumbnails.
	 */
	function realhomes_generate_thumbnails_on_upload( $metadata, $attachment_id ) {
		// Get the current thumbnail dimensions from the media settings
		$thumbnail_width  = intval( get_option( 'thumbnail_size_w' ) );
		$thumbnail_height = intval( get_option( 'thumbnail_size_h' ) );

		// Only proceed if thumbnail generation is disabled in settings
		if ( 0 === $thumbnail_width || 0 === $thumbnail_height ) {
			$file = get_attached_file( $attachment_id );

			// Check if the file exists before proceeding
			if ( ! file_exists( $file ) ) {
				inspiry_log( 'File not found: ' . $file ); // Log error if the file is missing
				return $metadata; // Early return to avoid further processing
			}

			// Define the custom sizes for the thumbnail ( we only need 150x150 )
			$custom_sizes = array(
				'thumbnail' => array( 'width' => 150, 'height' => 150, 'crop' => true ),
			);

			// Loop through the sizes and create the thumbnails
			foreach ( $custom_sizes as $size_name => $dimensions ) {
				$resized_image = image_make_intermediate_size( $file, $dimensions['width'], $dimensions['height'], $dimensions['crop'] );

				// Check if the resizing was successful
				if ( $resized_image ) {
					$metadata['sizes'][ $size_name ] = $resized_image; // Add the new size to the metadata
				} else {
					inspiry_log( "Failed to generate $size_name for attachment ID: $attachment_id" ); // Log the error if resizing fails
				}
			}

			// Update the metadata with the newly generated sizes
			wp_update_attachment_metadata( $attachment_id, $metadata );
		}

		return $metadata;
	}

	add_filter( 'wp_generate_attachment_metadata', 'realhomes_generate_thumbnails_on_upload', 10, 2 );
}
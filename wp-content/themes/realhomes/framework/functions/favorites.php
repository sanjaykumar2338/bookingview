<?php
/**
 *  This file contains functions related to add to favorites feature
 */


if ( ! function_exists( 'add_to_favorite' ) ) {
	/**
	 * Add a property to favorites
	 */
	function add_to_favorite() {

		/* if user is logged in then store in meta data */
		if ( isset( $_POST['property_id'] ) && is_user_logged_in() ) {
			$property_id = intval( $_POST['property_id'] );
			$user_id     = get_current_user_id();
			if ( ( $property_id > 0 ) && ( $user_id > 0 ) ) {
				if ( add_user_meta( $user_id, 'favorite_properties', $property_id ) ) {
					esc_html_e( 'Added to Favorites', 'framework' );
				} else {
					esc_html_e( 'Failed!', 'framework' );
				}
			}
		} else {
			echo 'false';
		}

		die;
	}

	add_action( 'wp_ajax_add_to_favorite', 'add_to_favorite' );
	add_action( 'wp_ajax_nopriv_add_to_favorite', 'add_to_favorite' );
}

if ( ! function_exists( 'display_favorite_properties' ) ) {
	/**
	 * Display favorite properties on the favorite page (only when user is not logged in).
	 */
	function display_favorite_properties() {

		// Switch the content language to the current WPML language.
		if ( isset( $_GET['wpml_lang'] ) ) {
			do_action( 'wpml_switch_language', sanitize_text_field( wp_unslash( $_GET['wpml_lang'] ) ) );
		}

		$design       = sanitize_text_field( $_POST['design_variation'] );
		$property_ids = $_POST['prop_ids'];

		if ( ! empty( $design ) && is_array( $_POST['prop_ids'] ) ) {

			$count = count( $property_ids );

			// My properties arguments.
			$favorites_properties_args = array(
				'post_type'      => 'property',
				'posts_per_page' => $count,
				'post__in'       => $property_ids,
				'orderby'        => 'post__in',
			);

			$favorites_query = new WP_Query( $favorites_properties_args );

			if ( $favorites_query->have_posts() ) {

				if ( 'dashboard' === $design ) :
					global $dashboard_globals;
					$dashboard_globals['current_module'] = 'favorites';
					?>
                    <div class="dashboard-posts-list">
					<?php get_template_part( 'common/dashboard/property-columns' ); ?>
                    <div class="dashboard-posts-list-body">
				<?php
				endif;

				while ( $favorites_query->have_posts() ) :
					$favorites_query->the_post();
					if ( 'dashboard' === $design ) {
						get_template_part( 'common/dashboard/property-card' );
					} else {
						get_template_part( 'assets/' . $design . '/partials/properties/favorite-card' );
					}
				endwhile;
				wp_reset_postdata();

				if ( 'dashboard' === $design ) :
					?>
                    </div></div>
				<?php
				endif;

			} else {
				if ( 'modern' === $_POST['design_variation'] ) {
					realhomes_print_no_result();
				} else if ( 'classic' === $_POST['design_variation'] ) {
					realhomes_print_no_result( '', array( 'container_class' => 'alert-wrapper' ) );
				}
			}
		}

		die;
	}

	add_action( 'wp_ajax_display_favorite_properties', 'display_favorite_properties' );
	add_action( 'wp_ajax_nopriv_display_favorite_properties', 'display_favorite_properties' );
}


if ( ! function_exists( 'is_added_to_favorite' ) ) {
	/**
	 * Check if a property is already added to favorites
	 *
	 * @param $property_id
	 * @param $user_id
	 *
	 * @return bool
	 */
	function is_added_to_favorite( $property_id, $user_id = 0 ) {

		if ( $property_id > 0 ) {

			/* if user id is not provided then try to get current user id */
			if ( ! $user_id ) {
				$user_id = get_current_user_id();
			}

			if ( $user_id > 0 ) {
				/* if logged in check in database */
				global $wpdb;
				$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->usermeta} WHERE meta_key= %s AND meta_value= %s AND user_id= %s", 'favorite_properties', $property_id, $user_id ) );
				if ( isset( $results[0]->meta_value ) && ( $results[0]->meta_value == $property_id ) ) {
					return true;
				}
			}
		}

		return false;
	}
}


if ( ! function_exists( 'remove_from_favorites' ) ) {
	/**
	 * Remove from favorites
	 */
	function remove_from_favorites() {
		if ( isset( $_POST['property_id'] ) ) {
			$property_id = intval( $_POST['property_id'] );
			if ( $property_id > 0 ) {

				if ( is_user_logged_in() ) {
					$user_id = get_current_user_id();

					if ( delete_user_meta( $user_id, 'favorite_properties', $property_id ) ) {
						echo json_encode( array(
								'success' => true,
								'message' => esc_html__( "Property removed form favorites successfully!", 'framework' )
							)
						);
						die;
					} else {
						echo json_encode( array(
								'success' => false,
								'message' => esc_html__( "Failed to remove property form favorites!", 'framework' )
							)
						);
						die;
					}
				}
			}
		}

		echo json_encode( array(
				'success' => false,
				'message' => esc_html__( "Invalid Parameters!", 'framework' )
			)
		);

		die;

	}

	add_action( 'wp_ajax_remove_from_favorites', 'remove_from_favorites' );
	add_action( 'wp_ajax_nopriv_remove_from_favorites', 'remove_from_favorites' );
}

if ( ! function_exists( 'inspiry_favorite_prop_migration' ) ) {
	/**
	 * Migrate local favorited properties to server side.
	 */
	function inspiry_favorite_prop_migration() {

		/* Ensure user login and data intigrity */
		if ( isset( $_POST['prop_ids'] ) && is_array( $_POST['prop_ids'] ) && is_user_logged_in() ) {
			$prop_ids = $_POST['prop_ids'];

			foreach ( $prop_ids as $property_id ) {
				$user_id = get_current_user_id();
				if ( ( $property_id > 0 ) && ( $user_id > 0 ) && ! is_added_to_favorite( $property_id, $user_id ) ) {
					add_user_meta( $user_id, 'favorite_properties', $property_id );
				}
			}
			echo 'true';
		} else {
			echo 'false';
		}

		die();
	}

	add_action( 'wp_ajax_inspiry_favorite_prop_migration', 'inspiry_favorite_prop_migration' );
	add_action( 'wp_ajax_nopriv_inspiry_favorite_prop_migration', 'inspiry_favorite_prop_migration' );
}

if ( ! function_exists( 'inspiry_safe_include_favorite_svg_icon' ) ) {
	/**
	 * Include SVG icons
	 *
	 * @param string $icon_path svg icon path i.e /common/images/icons/filename.svg
	 */

	function inspiry_include_favorite_svg_icon( $icon_path = '' ) {
		if ( ! empty( $icon_path ) ) {
			inspiry_safe_include_svg( $icon_path, '' );
		} else {
			inspiry_safe_include_svg( '/images/icons/icon-favorite.svg' );
		}
	}

}

if ( ! function_exists( 'inspiry_favorite_button' ) ) {
	/**
	 * Display 'Add to Favorite' button
	 *
	 * @param null   $property_id     Property ID.
	 * @param string $ele_add_label   Elementor Label Option Add To Favourite.
	 * @param string $ele_added_label Elementor Label Option Added To Favourite.
	 * @param string $icon_path       svg icon path i.e /common/images/icons/filename.svg
	 * @param string $design_variation RealHomes design variation name i.e classic,modern,ultra
	 */
	function inspiry_favorite_button( $property_id = null, $ele_add_label = '', $ele_added_label = '', $icon_path = '', $design_variation = '' ) {

		$fav_button = get_option( 'theme_enable_fav_button' );

		if ( 'true' === $fav_button ) {

			$require_login                       = get_option( 'inspiry_login_on_fav', 'no' );
			$inspiry_add_to_fav_property_label   = get_option( 'inspiry_add_to_fav_property_label' );
			$inspiry_added_to_fav_property_label = get_option( 'inspiry_added_to_fav_property_label' );

			if ( ! empty( $ele_add_label ) ) {
				$add_label = $ele_add_label;
			} else if ( $inspiry_add_to_fav_property_label ) {
				$add_label = $inspiry_add_to_fav_property_label;
			} else {
				$add_label = esc_html__( 'Add to favorites', 'framework' );
			}

			if ( ! empty( $ele_added_label ) ) {
				$added_label = $ele_added_label;
			} else if ( $inspiry_added_to_fav_property_label ) {
				$added_label = $inspiry_added_to_fav_property_label;
			} else {
				$added_label = esc_html__( 'Added to favorites', 'framework' );
			}

			$tooltip_class            = '';
			$added_tooltip_attributes = ' data-tooltip="' . esc_attr( $added_label ) . '" ';
			$add_tooltip_attributes   = ' data-tooltip="' . esc_attr( $add_label ) . '" ';
			if ( 'ultra' === $design_variation ) {
				$tooltip_class            = ' rh-ui-tooltip ';
				$added_tooltip_attributes = ' title="' . esc_attr( $added_label ) . '" ';
				$add_tooltip_attributes   = ' title="' . esc_attr( $add_label ) . '" ';
			}

			if ( ( is_user_logged_in() && 'yes' === $require_login ) || ( 'yes' !== $require_login ) ) {

				if ( null === $property_id ) {
					$property_id = get_the_ID();
				}

				$user_status = 'user_not_logged_in';
				if ( is_user_logged_in() ) {
					$user_status = 'user_logged_in';
				}

				if ( is_added_to_favorite( $property_id ) ) {
					?>
                    <span class="favorite-btn-wrap favorite-btn-<?php echo esc_attr( $property_id ); ?>">
							<span class="favorite-placeholder highlight__red <?php echo esc_attr( $user_status . $tooltip_class ); ?>" data-propertyid="<?php echo esc_attr( $property_id ); ?>" <?php echo $added_tooltip_attributes ?>>
								<?php inspiry_include_favorite_svg_icon( $icon_path ); ?>
							</span>
							<a href="#" class="favorite add-to-favorite hide <?php echo esc_attr( $user_status . $tooltip_class ); ?>" data-propertyid="<?php echo esc_attr( $property_id ); ?>" <?php echo $add_tooltip_attributes ?>>
								<?php inspiry_include_favorite_svg_icon( $icon_path ); ?>
							</a>
						</span>
					<?php
				} else {
					?>
                    <span class="favorite-btn-wrap favorite-btn-<?php echo esc_attr( $property_id ); ?>">
							<span class="favorite-placeholder highlight__red hide <?php echo esc_attr( $user_status . $tooltip_class ); ?>" data-propertyid="<?php echo esc_attr( $property_id ); ?>" <?php echo $added_tooltip_attributes ?>>
								<?php inspiry_include_favorite_svg_icon( $icon_path ); ?>
							</span>
							<a href="#" class="favorite add-to-favorite <?php echo esc_attr( $user_status . $tooltip_class ); ?>" data-propertyid="<?php echo esc_attr( $property_id ); ?>" <?php echo $add_tooltip_attributes ?>>
								<?php inspiry_include_favorite_svg_icon( $icon_path ); ?>
							</a>
						</span>
					<?php
				}
			} else {
				?>
                <a href="#" class="<?php echo esc_attr( $tooltip_class ) ?> favorite add-to-favorite require-login" data-login="<?php echo esc_url( inspiry_get_login_register_url() ); ?>" <?php echo $add_tooltip_attributes ?>>
					<?php inspiry_include_favorite_svg_icon( $icon_path ); ?>
                </a>
				<?php
			}
		}
	}
}

if ( ! function_exists( 'realhomes_share_favorites_by_email' ) ) {
	/**
	 * Process favorites list email request
	 *
	 * @since 4.3.3
	 */
	function realhomes_share_favorites_by_email() {

		/* Ensure that the request is valid */
		if ( ! wp_verify_nonce( $_POST['fav_nonce'], 'fav_share_nonce' ) ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Unverified Nonce!', 'framework' ),
				)
			);
			wp_die();
		}

		/* Ensure that the request is valid */
		if ( ! is_user_logged_in() ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'You must login to use this functionality!', 'framework' ),
				)
			);
			wp_die();
		}

		if ( empty( $_POST['prop_ids'] ) ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Wrong Information Provided!', 'framework' )
				)
			);
			wp_die();
		}

		// Validate multiple emails
		if ( empty( $_POST['target_email'] ) || ! realhomes_are_emails( $_POST['target_email'] ) ) {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Wrong Email Provided!', 'framework' )
				)
			);
			wp_die();
		}

        // Sender's information gathering
		$current_user = wp_get_current_user();
		if ( ! empty( $current_user->display_name ) ) {
            $sender_name = $current_user->display_name;
		} else {
			$sender_name = $current_user->user_login;
        }

		$user_email = $_POST['target_email'];
		$headers    = array();
		$headers[]  = 'Content-Type: text/html; charset=UTF-8';
		$headers    = apply_filters( 'realhomes_share_favorites_mail_header', $headers );

		// Build email subject.
		$default_subject = esc_html__( 'Check out some favorite properties sent by ', 'framework' ) . esc_html( $sender_name );
		$subject         = get_option( 'realhomes_share_favorites_email_subject', $default_subject );
		$subject         = empty( $subject ) ? $default_subject : $subject;
		$subject         = $subject . ' - ' . get_bloginfo( 'name' );

		// Build email contents
		$mail_content[] = '<h3>' . esc_html__( 'Here are my favorite properties - ', 'framework' ) . esc_html( $sender_name ) .'</h3>';

		$mail_content[] = array(
			'name'  => '',
			'value' => '<h3>' . esc_html__( 'Here are my favorite properties - ', 'framework' ) . esc_html( $sender_name ) .'</h3>',
		);
		$prop_ids       = $_POST['prop_ids'];

		if ( is_array( $prop_ids ) && 0 < count( $prop_ids ) ) {
			$prop_list = '<ul>';
			foreach ( $prop_ids as $id ) {
				$prop_list .= '<li><a style="text-decoration: none;" href="' . get_permalink( $id ) . '">' . get_the_title( $id ) . '</a></li>';
			}
			$prop_list .= '</ul>';

			$mail_content[] = array(
				'name'  => '',
				'value' => $prop_list,
			);
		}

		$mail_content = ere_email_template( $mail_content, 'realhomes_share_favorite_list' );

		if ( wp_mail( $user_email, $subject, $mail_content, $headers ) ) {
			echo wp_json_encode(
				array(
					'success' => true,
					'message' => esc_html__( 'Email sent successfully.', 'framework' ),
				)
			);
			wp_die();
		} else {
			echo wp_json_encode(
				array(
					'success' => false,
					'message' => esc_html__( 'Something went wrong with email sending process.', 'framework' ),
				)
			);
			wp_die();
		}
	}

	add_action( 'wp_ajax_realhomes_share_favorites_by_email', 'realhomes_share_favorites_by_email' );
	add_action( 'wp_ajax_nopriv_realhomes_share_favorites_by_email', 'realhomes_share_favorites_by_email' );
}
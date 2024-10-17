<?php
/**
 * Property Rating System
 *
 * Functions file for property ratings system.
 *
 * @package realhomes
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! function_exists( 'inspiry_rating_average' ) ) {
	/**
	 * Display rating average based on approved comments with rating
	 *
	 * @param array $settings   (arguments)
	 *                          bool rating_string,
	 *                          bool simplified
	 */
	function inspiry_rating_average( $settings = array() ) {

		$post_id = get_the_ID();

		if ( ! intval( $post_id ) ) {
			return;
		}

		// Setting defaults
		$simplified    = isset( $settings['simplified'] ) ? $settings['simplified'] : true;
		$rating_string = isset( $settings['rating_string'] ) ? $settings['rating_string'] : true;

		$args = array(
			'post_id' => $post_id,
			'status'  => 'approve',
		);

		$comments = get_comments( $args );
		$ratings  = array();
		$count    = 0;

		foreach ( $comments as $comment ) {

			$rating = get_comment_meta( $comment->comment_ID, 'inspiry_rating', true );

			if ( ! empty( $rating ) ) {
				$ratings[] = absint( $rating );
				$count++;
			}
		}

		if ( 0 !== count( $ratings ) ) {

			$allowed_html = array(
				'span' => array(
					'class' => array(),
				),
				'i'    => array(
					'class' => array(),
				),
			);

			$values_count   = ( array_count_values( $ratings ) );
			$average_rating = get_post_meta( $post_id, 'realhomes_post_average_rating', true );
			if ( empty( $average_rating ) ) {
				$average_rating = realhomes_set_post_average_rating( $post_id );
			}
			?>

            <div class="stars-avg-rating inspiry_stars_avg_rating">
				<?php
				echo wp_kses( inspiry_rating_stars( $average_rating ), $allowed_html );

				if ( $rating_string ) {
					?>
                    <span class="rating-span">
                        <?php echo esc_html( $average_rating ); ?>
                        <i class="rvr_rating_down fas fa-angle-down"></i>
                        <?php printf( _nx( 'Review %s', 'Reviews %s', $count, 'Rating Reviews', 'framework' ), number_format_i18n( $count ) ) ?>
                    </span>
					<?php
				}

				if ( $simplified ) {
					?>
                    <div class="inspiry_wrapper_rating_info">
						<?php
						$i = 5;
						while ( $i > 0 ) {
							?>
                            <p class="inspiry_rating_percentage">
                                <span class="inspiry_rating_sorting_label">
                                    <?php
                                    printf( _nx( '%s Star', '%s Stars', $i, 'Rating Stars', 'framework' ), number_format_i18n( $i ) );
                                    ?>
                                </span>
								<?php
								if ( isset( $values_count[ $i ] ) && ! empty( $values_count[ $i ] ) ) {
									$stars = round( ( $values_count[ $i ] / ( count( $ratings ) ) ) * 100 );
								} else {
									$stars = 0;
								}
								?>
                                <span class="inspiry_rating_line">
                                    <span class="inspiry_rating_line_inner" style="width: <?php echo esc_attr( $stars ); ?>%"></span>
                                </span>
                                <span class="inspiry_rating_text">
                                    <span class="inspiry_rating_text_inner">
                                        <?php echo esc_html( $stars ) . '%' ?>
                                    </span>
                                </span>
                            </p>
							<?php
							$i--;
						}
						?>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'inspiry_rating_stars' ) ) {
	/**
	 * Display rated stars based on given number of rating
	 *
	 * @param int $rating - Average rating.
	 *
	 * @return string
	 */
	function inspiry_rating_stars( $rating ) {

		$output = '';

		if ( ! empty( $rating ) ) {

			$whole    = floor( $rating );
			$fraction = $rating - $whole;

			$round = round( $fraction, 2 );

			$output = '<span class="rating-stars">';

			for ( $count = 1; $count <= $whole; $count++ ) {
				$output .= '<i class="fas fa-star rated"></i>'; //3
			}
			$half = 0;
			if ( $round <= .24 ) {
				$half = 0;
			} else if ( $round >= .25 && $round <= .74 ) {
				$half   = 1;
				$output .= '<i class="fas fa-star-half-alt"></i>';
			} else if ( $round >= .75 ) {
				$half   = 1;
				$output .= '<i class="fas fa-star rated"></i>';
			}

			$unrated = 5 - ( $whole + $half );
			for ( $count = 1; $count <= $unrated; $count++ ) {
				$output .= '<i class="far fa-star"></i>';
			}

			$output .= '</span>';
		}

		return $output;
	}
}


if ( ! function_exists( 'inspiry_rating_form_field' ) ) {
	/**
	 * Add fields after default fields above the comment box, always visible
	 */
	function inspiry_rating_form_field() {

		// Display rating field only, if current user didn't rate already
		$rated_already = inspiry_user_rated_already();
		if ( $rated_already ) {
			return;
		}

		if ( realhomes_get_rating_status() ) {
			?>
            <div class="stars-comment-rating">
                <div class="rating-box">
                    <select id="rate-it" name="inspiry_rating">
						<?php
						for ( $i = 1; $i <= 5; $i++ ) {
							$selected = ( $i == 5 ) ? 'selected' : '';
							echo '<option value="' . esc_attr( $i ) . '" ' . $selected . '>' . esc_html( $i ) . '</option>';
						}
						?>
                    </select>
                </div>
            </div>
			<?php
		}
	}
}


if ( ! function_exists( 'inspiry_verify_comment_rating' ) ) {
	/**
	 * Add the filter to check whether the comment rating has been set
	 *
	 * @param array $comment_data - Comment Data.
	 *
	 * @return mixed $comment_data
	 */
	function inspiry_verify_comment_rating( $comment_data ) {

		// Filter $_POST array for security.
		$post_array = filter_input_array( INPUT_POST );

		if ( ( isset( $post_array['inspiry_rating'] ) ) && ( empty( $post_array['inspiry_rating'] ) ) ) {

			wp_die( esc_html__( 'Error: You did not add a rating. Hit the Back button on your Web browser and resubmit your comment with a rating.', 'framework' ) );
		}

		return $comment_data;
	}
}


if ( ! function_exists( 'inspiry_save_comment_rating' ) ) {
	/**
	 * Save the comment rating along with comment
	 *
	 * @param int $comment_id - Comment ID.
	 */
	function inspiry_save_comment_rating( $comment_id ) {

		// Filter $_POST array for security.
		$post_array = filter_input_array( INPUT_POST );

		if ( ( isset( $post_array['inspiry_rating'] ) ) && ( ! empty( $post_array['inspiry_rating'] ) ) ) {
			$rating = wp_filter_nohtml_kses( $post_array['inspiry_rating'] );
			if ( $rating ) {
				add_comment_meta( $comment_id, 'inspiry_rating', $rating );

				$post_id = realhomes_get_post_id_from_comment_id( $comment_id );

				// Setting average rating meta for the post
				realhomes_set_post_average_rating( $post_id );
			}
		}

	}
}


if ( ! function_exists( 'inspiry_modify_rating_comment' ) ) {
	/**
	 * Add the comment rating (saved earlier) to the comment text
	 * You can also output the comment rating values directly to the comments template
	 *
	 * @param string $comment - Comment text.
	 *
	 * @return string
	 */
	function inspiry_modify_rating_comment( $comment ) {

		$rating = get_comment_meta( get_comment_ID(), 'inspiry_rating', true );

		if ( $rating && realhomes_get_rating_status() ) {

			$rating = '<p>' . inspiry_rating_stars( $rating ) . '</p>';

			return $comment . $rating;
		} else {
			return $comment;
		}
	}
}

if ( ! function_exists( 'inspiry_user_rated_already' ) ) {
	/**
	 * Check if current user rated current property already
	 * @return bool
	 */
	function inspiry_user_rated_already() {

		if ( function_exists( 'ere_get_current_user_ip' ) ) {
			$post_id      = get_the_ID();
			$reviewed_ips = array();

			$reviews = get_comments( array(
				'post_id' => $post_id
			) );

			foreach ( $reviews as $review ) {
				$reviewed_ips[] = get_comment_author_IP( $review->comment_ID );
			}

			$current_ip = ere_get_current_user_ip();

			if ( in_array( $current_ip, $reviewed_ips ) ) {

				return true;
			}
		}

		return false;
	}
}


if ( ! function_exists( 'realhomes_get_rating_status' ) ) {
	/**
	 * This function returns if rating is enabled in general
	 *
	 * @since 4.3.0
	 *
	 * @return bool
	 */
	function realhomes_get_rating_status() {

		// If comments are not open `
		if ( ! comments_open() && ! get_comments_number() ) {
			return false;
		}

		$post_types        = array( 'property', 'agent', 'agency' );
		$current_post_type = get_post_type();

		if ( $current_post_type ) {
			if ( $current_post_type === 'property' ) {
				$option_key = 'inspiry_property_ratings';
			} else {
				$option_key = 'realhomes_' . $current_post_type . '_ratings';
			}

			if ( in_array( $current_post_type, $post_types ) && ( 'true' === get_option( $option_key, 'false' ) ) ) {
				return true;
			}
		}

		return false;
	}
}


if ( ! function_exists( 'inspiry_add_property_ratings_hooks' ) ) {
	/**
	 * Function to add hooks for property|agent|agency ratings.
	 *
	 * @since 3.3.1
	 */
	function inspiry_add_property_ratings_hooks() {

		// Add ratings field.
		add_action( 'comment_form_logged_in_before', 'inspiry_rating_form_field' );
		add_action( 'comment_form_top', 'inspiry_rating_form_field' );

		// Make it required.
		add_filter( 'preprocess_comment', 'inspiry_verify_comment_rating' );

		// Save ratings.
		add_action( 'comment_post', 'inspiry_save_comment_rating' );

		if ( 'ultra' !== INSPIRY_DESIGN_VARIATION ) {

			// Add ratings to comments text.
			add_filter( 'comment_text', 'inspiry_modify_rating_comment' );
		}

	}

	add_action( 'init', 'inspiry_add_property_ratings_hooks' );
}


if ( ! function_exists( 'realhomes_get_post_by_rating' ) ) {
	/**
	 * Display rating average based on approved comments with rating
	 *
	 * @since  4.3.2
	 *
	 * @param int    $rating
	 * @param string $post_type
	 *
	 * @return $post_ids
	 */
	function realhomes_get_posts_by_rating( $rating, $post_type = 'agent' ) {

		// Ensure rating is an integer between 1 and 5
		$rating = intval( $rating );
		if ( $rating < 1 || $rating > 5 ) {
			return [];
		}

		// Array to hold the matching post IDs
		$matching_post_ids = [];

		// Get all posts of the specified post type
		$args = array(
			'post_type'      => $post_type,
			'posts_per_page' => 3000, // limiting number for better performance
			'post_status'    => 'publish', // Only published posts
		);

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();

				// Get all comments for this post
				$comments = get_comments( array(
					'post_id' => $post_id,
					'status'  => 'approve', // Only approved comments
				) );

				// Initialize variables to calculate the average rating
				$total_rating = 0;
				$rating_count = 0;
				if ( ! is_wp_error( $comments ) && is_array( $comments ) && ! empty( $comments ) ) {

					foreach ( $comments as $comment ) {
						$comment_rating = get_comment_meta( $comment->comment_ID, 'inspiry_rating', true );
						if ( $comment_rating ) {
							$total_rating += floatval( $comment_rating );
							$rating_count++;
						}
					}
				}

				// Calculate the average rating if there are ratings
				if ( $rating_count > 0 ) {
					$average_rating         = $total_rating / $rating_count;
					$rounded_average_rating = round( $average_rating );

					// Check if the rounded average rating matches the specified rating
					if ( $rounded_average_rating == $rating ) {
						$matching_post_ids[] = $post_id;
					}
				}
			}
			wp_reset_postdata();
		}

		return $matching_post_ids;
	}
}


if ( ! function_exists( 'realhomes_set_post_average_rating' ) ) {
	/**
	 * Set rating average in meta based on approved comments with rating for current post
	 *
	 * @since  4.3.2
	 *
	 * @return float|int|void
	 */
	function realhomes_set_post_average_rating( $post_id ) {

		if ( ! intval( $post_id ) ) {
			return;
		}

		$args = array(
			'post_id' => $post_id,
			'status'  => 'approve',
		);

		$comments       = get_comments( $args );
		$ratings        = array();
		$average_rating = 0;

		foreach ( $comments as $comment ) {

			$rating = get_comment_meta( $comment->comment_ID, 'inspiry_rating', true );

			if ( ! empty( $rating ) ) {
				$ratings[] = absint( $rating );
			}
		}

		if ( 0 !== count( $ratings ) ) {
			$average_rating = round( array_sum( $ratings ) / count( $ratings ), 2 );
		}

		update_post_meta( $post_id, 'realhomes_post_average_rating', $average_rating );

		return $average_rating;

	}
}


if ( ! function_exists( 'realhomes_get_post_id_from_comment_id' ) ) {
	/**
	 * Returns post ID from given comment ID
	 *
	 * @since  4.3.2
	 *
	 * @param int $comment_id
	 *
	 * @return mixed
	 */
	function realhomes_get_post_id_from_comment_id( $comment_id ) {
		$comment = get_comment( $comment_id );
		if ( $comment ) {
			return $comment->comment_post_ID;
		}

		return false; // or any other value to indicate the comment was not found
	}
}
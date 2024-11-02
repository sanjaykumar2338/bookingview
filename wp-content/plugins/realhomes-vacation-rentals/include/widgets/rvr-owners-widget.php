<?php
/**
 * Widget: Owners List
 *
 * @since   1.4.0
 * @package realhomes_vacation_rentals
 */


if ( ! class_exists( 'RVR_Owners_Widget' ) ) {

	/**
	 * Class: Widget class for Owners List
	 */
	class RVR_Owners_Widget extends WP_Widget {

		/**
		 * Method: Constructor
		 */
		function __construct() {
			$widget_ops = array(
				'classname'                   => 'rvr_owners_widget',
				'description'                 => esc_html__( 'This widget displays owners list.', 'realhomes-vacation-rentals' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'RVR_Owners_Widget', esc_html__( 'RealHomes VR - Owners List', 'realhomes-vacation-rentals' ), $widget_ops );
		}

		/**
		 * Method: Widget's Display
		 */
		function widget( $args, $instance ) {

			echo $args['before_widget'];
			echo '<div class="agents-list-widget">';

			$rvr_settings = get_option( 'rvr_settings' );
			$rvr_enabled  = $rvr_settings['rvr_activation'];

			if ( ! $rvr_enabled ) {
				echo '<p class="rvr-widget-warning-message"><strong>' . esc_html__( 'Note: ', 'realhomes-vacation-rentals' ) . '</strong>' . esc_html__( 'Please activate the RVR from its settings to display Owners list.', 'realhomes-vacation-rentals' ) . '</p>';
			} else {
				$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Owners', 'realhomes-vacation-rentals' );

				/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
				$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

				$owners_args = array(
					'post_type'      => 'owner',
					'posts_per_page' => ! empty( $instance['count'] ) ? intval( $instance['count'] ) : 3
				);

				$owners_query = new WP_Query( apply_filters( 'rvr_owners_widget', $owners_args ) );

				if ( $title ) {
					echo $args['before_title'] . $title . $args['after_title'];
				}

				if ( $owners_query->have_posts() ) { ?>

					<?php
					while ( $owners_query->have_posts() ) : $owners_query->the_post(); ?>
                        <article class="agent-list-item">
							<?php
							if ( ! empty( get_the_post_thumbnail() ) ) {
								?>
                                <figure class="agent-thumbnail">
                                    <a title="<?php the_title(); ?>">
										<?php the_post_thumbnail( 'agent-image' ); ?>
                                    </a>
                                </figure>
								<?php
							} else if ( 'ultra' === INSPIRY_DESIGN_VARIATION ) {
								?>
                                <figure class="agent-thumbnail agent-thumb-placeholder">
                                    <a title="<?php the_title(); ?>">
                                        <i class="fas fa-user-tie"></i>
                                    </a>
                                </figure>
								<?php
							}
							?>
                            <div class="agent-widget-content <?php echo has_post_thumbnail() ? '' : esc_attr( 'no-agent-thumbnail' ); ?>">
                                <h4 class="agent-name"><?php the_title(); ?></h4>
								<?php
								$owner_id             = get_the_ID();
								$owner_contact_number = null;
								$owner_email          = get_post_meta( $owner_id, 'rvr_owner_email', true );
								$owner_mobile_number  = get_post_meta( $owner_id, 'rvr_owner_mobile', true );
								$owner_office_number  = get_post_meta( $owner_id, 'rvr_owner_office_phone', true );

								if ( ! empty( $owner_mobile_number ) ) {
									$owner_contact_number = $owner_mobile_number;
								} else if ( ! empty( $owner_office_number ) ) {
									$owner_contact_number = $owner_office_number;
								}

								if ( is_email( $owner_email ) ) {
									$owner_email_link = sprintf( '<a class="agent-contact-email" href="mailto:%1$s">%1$s</a>', antispambot( $owner_email ) );
									if ( 'ultra' === INSPIRY_DESIGN_VARIATION ) {
										?>
                                        <div class="rh-widget-agent-contact-item">
											<?php
											ere_safe_include_svg( '/images/icons/ultra/email.svg' );
											echo $owner_email_link;
											?>
                                        </div>
										<?php
									} else {
										echo $owner_email_link;
									}
								}

								if ( ! empty( $owner_contact_number ) ) {
									$owner_number_link = sprintf( '<a href="tel:%1$s">%1$s</a>', esc_html( $owner_contact_number ) );
									if ( 'ultra' === INSPIRY_DESIGN_VARIATION ) {
										?>
                                        <div class="rh-widget-agent-contact-item">
											<?php
											ere_safe_include_svg( '/images/icons/ultra/phone.svg' );
											echo $owner_number_link;
											?>
                                        </div>
										<?php
									} else {
										?>
                                        <div class="agent-contact-number">
											<?php echo $owner_number_link; ?>
                                        </div>
										<?php
									}
								}
								?>
                            </div>
                        </article>
					<?php endwhile;
					wp_reset_postdata();
				} else {
					if ( function_exists( 'realhomes_widget_no_items' ) ) {
						realhomes_widget_no_items( esc_html__( 'No Owner Found!', 'realhomes-vacation-rentals' ) );
					}
				}
			}

			echo '</div>'; // .agents-list-widget
			echo $args['after_widget'];
		}

		/**
		 * Method: Update Widget Options
		 */
		function form( $instance ) {
			$instance = wp_parse_args(
				(array)$instance, array(
					'title' => esc_html__( 'Owners', 'realhomes-vacation-rentals' ),
					'count' => 3
				)
			);

			$title = sanitize_text_field( $instance['title'] );
			$count = $instance['count'];
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title', 'realhomes-vacation-rentals' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e( 'Number of Owners', 'realhomes-vacation-rentals' ); ?></label>
                <input id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" size="3" />
            </p>
			<?php
		}

		/**
		 * Method: Update Widget Options
		 */
		function update( $new_instance, $old_instance ) {
			$instance          = $old_instance;
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
			$instance['count'] = $new_instance['count'];

			return $instance;
		}
	}
}

if ( ! function_exists( 'register_rvr_owners_widget' ) ) {
	function register_rvr_owners_widget() {
		register_widget( 'RVR_Owners_Widget' );
	}

	add_action( 'widgets_init', 'register_rvr_owners_widget' );
}
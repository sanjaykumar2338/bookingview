<?php
/**
 * Displays agent info for agent detail page.
 *
 * @package    realhomes
 * @subpackage modern
 */
global $post;
$agent_id = get_the_ID();

/* Agent Contact Info */
$agent_mobile       = get_post_meta( $agent_id, 'REAL_HOMES_mobile_number', true );
$agent_whatsapp     = get_post_meta( $agent_id, 'REAL_HOMES_whatsapp_number', true );
$agent_office_phone = get_post_meta( $agent_id, 'REAL_HOMES_office_number', true );
$agent_office_fax   = get_post_meta( $agent_id, 'REAL_HOMES_fax_number', true );
$agent_email        = get_post_meta( $agent_id, 'REAL_HOMES_agent_email', true );
$agent_address      = get_post_meta( $agent_id, 'REAL_HOMES_address', true );
$agent_license_no   = get_post_meta( $agent_id, 'REAL_HOMES_license_number', true );

$listed_properties = 0;
if ( function_exists( 'ere_get_agent_properties_count' ) ) {
	$listed_properties = ere_get_agent_properties_count( $agent_id );
}
?>
<div class="rh_agent_profile">
    <article class="rh_agent_card">
        <div class="rh_agent_card__wrap">
            <div class="rh_agent_card__head">
				<?php if ( has_post_thumbnail( $agent_id ) ) : ?>
                    <figure class="rh_agent_card__dp">
                        <a title="<?php the_title_attribute(); ?>" href="<?php echo get_permalink( $agent_id ); ?>">
							<?php echo get_the_post_thumbnail( $agent_id, 'agent-image' ); ?>
                        </a>
                    </figure>
				<?php endif; ?>
                <div class="rh_agent_card__name">
                    <h4 class="name">
	                    <?php
	                    echo get_the_title( $agent_id );
	                    if ( 0 < intval( $agent_id ) ) {
		                    realhomes_verification_badge( 'agent', $agent_id );
	                    }
	                    ?>
                    </h4><!-- /.name -->
					<?php
					$rh_agent_properties_count = get_option( 'inspiry_agent_properties_count', 'show' );
					if ( 'show' === $rh_agent_properties_count ) {
                        ?>
                        <div class="rh_agent_card__listings rh_agent_card__listings-inline">
                            <span class="count"><?php echo ( ! empty( $listed_properties ) ) ? esc_html( $listed_properties ) : 0; ?></span>
                            <span class="head"><?php ( 1 === $listed_properties ) ? esc_html_e( 'Listed Property', 'framework' ) : esc_html_e( 'Listed Properties', 'framework' ); ?></span>
                        </div>
					<?php
                    }

                    if ( realhomes_get_rating_status() ) {
                        inspiry_rating_average();
                    }
                    ?>
                </div>
                <div class="social single-agent-profile-social social-networks-brand-color">
					<?php
					$facebook_url = get_post_meta( $agent_id, 'REAL_HOMES_facebook_url', true );
					if ( ! empty( $facebook_url ) ) {
						?>
                        <a class="facebook" target="_blank" href="<?php echo esc_url( $facebook_url ); ?>">
                            <i class="fab fa-facebook fa-lg"></i>
                        </a>
						<?php
					}

					$twitter_url = get_post_meta( $agent_id, 'REAL_HOMES_twitter_url', true );
					if ( ! empty( $twitter_url ) ) {
						?>
                        <a class="twitter" target="_blank" href="<?php echo esc_url( $twitter_url ); ?>">
                            <i class="fab fa-twitter fa-lg"></i>
                        </a>
						<?php
					}

					$linked_in_url = get_post_meta( $agent_id, 'REAL_HOMES_linked_in_url', true );
					if ( ! empty( $linked_in_url ) ) {
						?>
                        <a class="linkedin" target="_blank" href="<?php echo esc_url( $linked_in_url ); ?>">
                            <i class="fab fa-linkedin fa-lg"></i>
                        </a>
						<?php
					}

					$instagram_url = get_post_meta( $agent_id, 'inspiry_instagram_url', true );
					if ( ! empty( $instagram_url ) ) {
						?>
                        <a class="instagram" target="_blank" href="<?php echo esc_url( $instagram_url ); ?>">
                            <i class="fab fa-instagram fa-lg"></i>
                        </a>
						<?php
					}

					$agent_website = get_post_meta( $agent_id, 'REAL_HOMES_website', true );
					if ( ! empty( $agent_website ) ) {
						?>
                        <a class="website-icon" target="_blank" href="<?php echo esc_url( $agent_website ); ?>">
                            <i class="fas fa-globe fa-lg"></i>
                        </a>
						<?php
					}

					$youtube_url = get_post_meta( $agent_id, 'inspiry_youtube_url', true );
					if ( ! empty( $youtube_url ) ) {
						?>
                        <a class="youtube" target="_blank" href="<?php echo esc_url( $youtube_url ); ?>">
                            <i class="fab fa-youtube-square fa-lg"></i>
                        </a>
						<?php
					}

					$tiktok_url = get_post_meta( $agent_id, 'realhomes_tiktok_url', true );
					if ( ! empty( $tiktok_url ) ) {
						?>
                        <a class="tiktok" target="_blank" href="<?php echo esc_url( $tiktok_url ); ?>">
                            <i class="fab fa-tiktok fa-lg"></i>
                        </a>
						<?php
					}

					$pinterest_url = get_post_meta( $agent_id, 'inspiry_pinterest_url', true );
					if ( ! empty( $pinterest_url ) ) {
						?>
                        <a class="pinterest" target="_blank" href="<?php echo esc_url( $pinterest_url ); ?>">
                            <i class="fab fa-pinterest fa-lg"></i>
                        </a>
						<?php
					}
					?>
                </div>
            </div>

            <div class="rh_content rh_agent_profile__excerpt">
				<?php the_content(); ?>
            </div>

            <div class="rh_agent_card__details">
                <div class="rh_agent_card__contact">
                    <div class="rh_agent_card__contact_wrap">
						<?php
						if ( ! empty( $agent_license_no ) ) {
							?>
                            <p class="contact office">
								<?php
								esc_html_e( 'License No', 'framework' );
								?> : <?php echo esc_html( $agent_license_no ); ?>
                            </p>
							<?php
						}
						if ( ! empty( $agent_office_phone ) ) {
							?>
                            <p class="contact office">
								<?php
								esc_html_e( 'Office', 'framework' );
								?> :
                                <a href="tel:<?php echo esc_attr( $agent_office_phone ); ?>"><?php echo esc_html( $agent_office_phone ); ?></a>
                            </p>
							<?php
						}
						if ( ! empty( $agent_mobile ) ) {
							?>
                            <p class="contact mobile">
								<?php esc_html_e( 'Mobile', 'framework' );
								?> :
                                <a href="tel:<?php echo esc_attr( $agent_mobile ); ?>"><?php echo esc_html( $agent_mobile ); ?></a>
                            </p>
							<?php
						}
						if ( ! empty( $agent_office_fax ) ) {
							?>
                            <p class="contact fax">
								<?php
								esc_html_e( 'Fax', 'framework' );
								?> :
                                <a href="tel:<?php echo esc_attr( $agent_office_fax ); ?>"><?php echo esc_html( $agent_office_fax ); ?></a>
                            </p>
							<?php
						}
						if ( ! empty( $agent_whatsapp ) ) {
							?>
                            <p class="contact whatsapp">
								<?php esc_html_e( 'WhatsApp', 'framework' );
								?> :
                                <a href="https://wa.me/<?php echo esc_attr( $agent_whatsapp ); ?>"><?php echo esc_html( $agent_whatsapp ); ?></a>
                            </p>
							<?php
						}
						if ( ! empty( $agent_email ) ) {
							?>
                            <p class="contact email">
								<?php esc_html_e( 'Email', 'framework' );
								?> :
                                <a href="mailto:<?php echo esc_attr( antispambot( $agent_email ) ); ?>"><?php echo esc_html( antispambot( $agent_email ) ); ?></a>
                            </p>
							<?php
						}
						if ( ! empty( $agent_address ) ) {
							?>
                            <p class="contact address"><?php esc_html_e( 'Address', 'framework' );
								?> :
                                <span><?php echo esc_html( $agent_address ); ?></span>
                            </p>
							<?php
						}
						?>
                    </div>
                </div>
            </div>

            <?php
            if ( function_exists( 'realhomes_generate_properties_stats_chart' ) && 'show' === get_option( 'realhomes_agent_single_stats_charts', 'show' ) ) {
                ?>
                <div class="stats-charts-wrap">
                    <h3><?php echo esc_html( get_option( 'realhomes_agent_stats_section_title', esc_html__( 'Progress & Stats', 'framework' ) ) ); ?></h3>
                    <div class="stats-wrapper">
                        <div class="tax-stats property-city">
                            <h3><?php echo preg_replace( '/^(\w+)/isux', '<span>$1</span>', esc_html__( 'Property Location', 'framework' ) ); ?></h3>
				            <?php realhomes_generate_properties_stats_chart( $agent_id ); ?>
                        </div>
                        <div class="tax-stats property-type">
                            <h3><?php echo preg_replace( '/^(\w+)/isux', '<span>$1</span>', esc_html__( 'Property Type', 'framework' ) ); ?></h3>
				            <?php realhomes_generate_properties_stats_chart( $agent_id, 'property-type' ); ?>
                        </div>
                        <div class="tax-stats property-status">
                            <h3><?php echo preg_replace( '/^(\w+)/isux', '<span>$1</span>', esc_html__( 'Property Status', 'framework' ) ); ?></h3>
				            <?php realhomes_generate_properties_stats_chart( $agent_id, 'property-status' ); ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>

            <div class="horizontal-border"></div>

            <div class="rh_agent_profile__contact_form">
				<?php get_template_part( 'assets/modern/partials/agent/single/contact-form' ); ?>
            </div>
        </div>
    </article>
</div>
<?php
if ( 'true' === get_option( 'theme_display_video' ) ) {
	$inspiry_video_group = get_post_meta( get_the_ID(), 'inspiry_video_group', true );
	if ( ! empty( $inspiry_video_group ) ) {
		?>
        <div class="video-content-wrapper single-property-section">
            <div class="container">
                <div class="rh_property__video rh_full_width_video_slider">
					<?php
					$property_video_title = get_option( 'theme_property_video_title' );
					if ( ! empty( $property_video_title ) ) {
						?><h4 class="rh_property__heading"><?php echo esc_html( $property_video_title ); ?></h4><?php
					}
					?>
                    <div class="rh_wrapper_property_videos_slider flexslider">
                        <ul class="slides">
							<?php
							foreach ( $inspiry_video_group as $videos ) {
								$group_video_url = $videos['inspiry_video_group_url'];
								if ( ! empty( $group_video_url ) ) {
									?>
                                    <li>
                                        <div class="rh_property_video">
                                            <div class="rh_property_video_inner">
												<?php
												if ( isset( $videos['inspiry_video_group_title'] ) && ! empty( $videos['inspiry_video_group_title'] ) ) {
													?>
                                                    <h5 class="rh_video_title"><?php echo esc_html( $videos['inspiry_video_group_title'] ); ?></h5>
													<?php
												}
												?>
                                                <a data-fancybox href="<?php echo esc_url( $group_video_url ); ?>" class="inspiry-lightbox-item" data-autoplay="true" data-vbtype="video">
                                                    <div class="play-btn"></div>
													<?php
													$group_video_id  = isset( $videos['inspiry_video_group_image'] ) ? $videos['inspiry_video_group_image'][0] : '';
													$group_video_src = wp_get_attachment_image_src( $group_video_id, 'property-detail-video-image' );
													if ( $group_video_src ) {
														echo '<img src="' . esc_url( $group_video_src[0] ) . '" alt="' . esc_attr( get_the_title( get_the_ID() ) ) . '">';
													} else if ( has_post_thumbnail( get_the_ID() ) ) {
														the_post_thumbnail( 'property-detail-video-image' );
													} else {
														inspiry_image_placeholder( 'property-detail-video-image' );
													}
													?>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
							}
							?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}

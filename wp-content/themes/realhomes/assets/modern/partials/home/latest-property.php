<?php
	/**
	 * Property card for latest properties on homepage.
	 *
	 * @package    realhomes
	 * @subpackage modern
	 */

	global $post;
	$property_size      = get_post_meta( get_the_ID(), 'REAL_HOMES_property_size', true );
	$size_postfix       = realhomes_get_area_unit( get_the_ID() );
	$property_bedrooms  = get_post_meta( get_the_ID(), 'REAL_HOMES_property_bedrooms', true );
	$property_bathrooms = get_post_meta( get_the_ID(), 'REAL_HOMES_property_bathrooms', true );
	$property_address   = get_post_meta( get_the_ID(), 'REAL_HOMES_property_address', true );
	$is_featured        = get_post_meta( get_the_ID(), 'REAL_HOMES_featured', true );
	$property_id        = get_the_ID();

?>

<article class="rh_prop_card">

	<div class="rh_prop_card__wrap">

		<?php if ( $is_featured ) { ?>
			<div class="rh_label rh_label__property">
				<div class="rh_label__wrap">
					<?php realhomes_featured_label(); ?>
					<span></span>
				</div>
			</div>            <!-- /.rh_label -->
		<?php } ?>

		<figure class="rh_prop_card__thumbnail">
            <div class="rh_figure_property_one">
			<a href="<?php the_permalink(); ?>">
				<?php
					if ( has_post_thumbnail( get_the_ID() ) ) {
						the_post_thumbnail( 'modern-property-child-slider' );
					} else {
						inspiry_image_placeholder( 'modern-property-child-slider' );
					}
				?>
			</a>

			<div class="rh_overlay"></div>
			<div class="rh_overlay__contents rh_overlay__fadeIn-bottom">
				<a href="<?php the_permalink(); ?>"><?php inspiry_property_detail_page_link_text(); ?></a>
			</div>
			<!-- /.rh_overlay__contents -->

			<?php inspiry_display_property_label( $property_id ); ?>
            </div>

            <div class="rh_prop_card__btns">
				<?php
				inspiry_favorite_button(); // Display add to favorite button.
				inspiry_add_to_compare_button(); // Display add to compare button.
				?>
            </div>
			<!-- /.rh_prop_card__btns -->
		</figure>
		<!-- /.rh_prop_card__thumbnail -->

		<div class="rh_prop_card__details">

			<h3>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			<p class="rh_prop_card__excerpt"><?php framework_excerpt( 10 ); ?></p>
			<!-- /.rh_prop_card__excerpt -->

			<div class="rh_prop_card__meta_wrap">

				<?php if ( ! empty( $property_bedrooms ) ) { ?>
					<div class="rh_prop_card__meta">
						<span class="rh_meta_titles">
							<?php
								$bedrooms_label = get_option( 'inspiry_bedrooms_field_label' );
								echo ( empty ( $bedrooms_label ) ) ? esc_html__( 'Bedrooms', 'framework' ) : esc_html( $bedrooms_label );
							?>
						</span>
						<div>
							<?php realhomes_property_meta_icon( 'REAL_HOMES_property_bedrooms', '/images/icons/icon-bed.svg' ); ?>
							<span class="figure"><?php echo esc_html( $property_bedrooms ); ?></span>
						</div>
					</div>                    <!-- /.rh_prop_card__meta -->
				<?php } ?>

				<?php if ( ! empty( $property_bathrooms ) ) { ?>
					<div class="rh_prop_card__meta">
						<span class="rh_meta_titles">
							<?php
								$bathrooms_label = get_option( 'inspiry_bathrooms_field_label' );
								echo ( empty ( $bathrooms_label ) ) ? esc_html__( 'Bathrooms', 'framework' ) : esc_html( $bathrooms_label );
							?>
						</span>
						<div>
							<?php realhomes_property_meta_icon( 'REAL_HOMES_property_bathrooms',  '/images/icons/icon-shower.svg' ); ?>
							<span class="figure"><?php echo esc_html( $property_bathrooms ); ?></span>
						</div>
					</div>                    <!-- /.rh_prop_card__meta -->
				<?php }

				if ( ! empty( $property_size ) ) { ?>
					<div class="rh_prop_card__meta">
						<span class="rh_meta_titles">
							<?php
								$area_label = get_option( 'inspiry_area_field_label' );
								echo ( empty ( $area_label ) ) ? esc_html__( 'Area', 'framework' ) : esc_html( $area_label );
							?>
						</span>
						<div>
							<?php realhomes_property_meta_icon( 'REAL_HOMES_property_size',  '/images/icons/icon-area.svg' ); ?>
							<span class="figure">
							<?php echo esc_html( $property_size ); ?>
							</span>
							<?php if ( ! empty( $size_postfix ) ) { ?>
								<span class="label">
								<?php echo esc_html( $size_postfix ); ?>
								</span>
							<?php } ?>
						</div>
					</div>                    <!-- /.rh_prop_card__meta -->
				<?php } ?>

			</div>
			<!-- /.rh_prop_card__meta_wrap -->

			<div class="rh_prop_card__priceLabel">

				<span class="rh_prop_card__status">
					<?php echo esc_html( display_property_status( get_the_ID() ) ); ?>
				</span>
				<!-- /.rh_prop_card__type -->
				<p class="rh_prop_card__price">
					<?php
					if ( function_exists( 'ere_property_price' ) ) {
						ere_property_price( '', true );
					}
                    ?>
				</p>
				<!-- /.rh_prop_card__price -->

			</div>
			<!-- /.rh_prop_card__priceLabel -->

		</div>
		<!-- /.rh_prop_card__details -->

	</div>
	<!-- /.rh_prop_card__wrap -->

</article><!-- /.rh_prop_card -->
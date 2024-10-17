<?php
/**
 * Property features to be displayed in lightbox
 *
 * Partial for
 * * elementor/widgets/properties-widget/lightbox-partials/lightbox.php
 *
 * @version 2.3.2
 */
global $post_id;
/* Property Features */
$features_terms = get_the_terms( $post_id, 'property-feature' );
if ( ! empty( $features_terms ) ) {
	?>
    <div class="rh_property__features_wrap margin-bottom-40px <?php realhomes_printable_section( 'features' ); ?>">
        <h4 class="rh_property__heading"><?php echo esc_html_e( 'Features', 'realhomes-elementor-addon' ); ?></h4>
		<?php
		$rh_property_features_display = get_option( 'inspiry_property_features_display', 'link' );
		?>
        <ul class="rh_property__features arrow-bullet-list">
			<?php
			foreach ( $features_terms as $feature_term ) {
				$feature = $feature_term->name;
				echo '<li class="rh_property__feature" id="rh_property__feature_' . esc_attr( $feature_term->term_id ) . '">';

				$feature_icon_id = get_term_meta( $feature_term->term_id, 'inspiry_property_feature_icon', true );
				$feature_icon    = ! empty( $feature_icon_id ) ? wp_get_attachment_url( $feature_icon_id ) : false;
				if ( $feature_icon ) {
					if ( preg_match( '/\.svg$/', $feature_icon ) === 1 ) {
						// Download SVG content.
						$svg_file = wp_remote_get( $feature_icon );
						if ( is_array( $svg_file ) && ! is_wp_error( $svg_file ) ) {
							$svg_content = wp_remote_retrieve_body( $svg_file );

							$svg_class = 'property-feature-icon property-feature-icon-svg';
							if ( preg_match( '/<svg[^>]*\bclass\s*=\s*["\'](.*?)["\'][^>]*>/', $svg_content ) ) {
								$svg_content = str_replace( '<svg class="', '<svg class="' . $svg_class . ' ', $svg_content );
							} else {
								$svg_content = str_replace( '<svg', '<svg class="' . $svg_class . '"', $svg_content );
							}

							$sanitized_svg = ( new RealHomes_Sanitize_Svg() )->sanitize( $svg_content );
							if ( false !== $sanitized_svg ) {
								echo $sanitized_svg;
							} else {
								inspiry_safe_include_svg( '/ultra/icons/done.svg', '/assets/' );
							}
						}

					} else {
						echo '<img class="property-feature-icon property-feature-icon-image" src="' . esc_url( $feature_icon ) . '" alt="' . esc_attr( $feature ) . '">';
					}

				} else {
					inspiry_safe_include_svg( '/ultra/icons/done.svg', '/assets/' );
				}

				if ( 'link' === $rh_property_features_display ) {
					echo '<a href="' . esc_url( get_term_link( $feature_term->slug, 'property-feature' ) ) . '">' . esc_html( $feature ) . '</a>';
				} else {
					echo esc_html( $feature );
				}
				echo '</li>';
			}
			?>
        </ul>
    </div>
	<?php
} else {
	rhea_print_no_result_for_editor();
}
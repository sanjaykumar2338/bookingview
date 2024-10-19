<?php
/**
 * Breadcrumbs for property detail page.
 *
 * @package    realhomes
 * @subpackage classic
 */

global $post;

$possible_taxonomies  = array( 'property-city', 'property-type', 'property-status' );
$breadcrumbs_taxonomy = get_option( 'theme_breadcrumbs_taxonomy' );
if ( $breadcrumbs_taxonomy && in_array( $breadcrumbs_taxonomy, $possible_taxonomies ) ) {

	$inspiry_breadcrumbs_items = inspiry_get_breadcrumbs_items( get_the_ID(), $breadcrumbs_taxonomy, false );
	$breadcrumbs_count         = count( $inspiry_breadcrumbs_items );

	if ( is_array( $inspiry_breadcrumbs_items ) && ( 0 < $breadcrumbs_count ) ) {

		$bread_crumbs_modern = '';
		if ( 'modern' === INSPIRY_DESIGN_VARIATION ) {
			$bread_crumbs_modern = ' page-breadcrumbs-modern';
		}
		?>
		<div class="page-breadcrumbs <?php echo esc_attr( $bread_crumbs_modern ); ?>">
			<nav class="property-breadcrumbs">
				<ul>
					<?php
					// Get the current URL
					$current_url = $_SERVER['REQUEST_URI'];

					// Explode the URL to get its parts
					$url_parts = explode('/', trim($current_url, '/'));

					// Check if "ab" (province) exists in the URL
					if (in_array('ab', $url_parts)) {
						// Extract relevant parts from the URL for custom breadcrumb
						$province = 'Province';
						$city = isset($url_parts[3]) ? ucfirst(str_replace('-', ' ', $url_parts[3])) : 'Unknown City';
						$city_region = isset($url_parts[4]) ? ucfirst(str_replace('-', ' ', $url_parts[4])) : 'Unknown Region';
						$property_type = isset($url_parts[5]) ? ucfirst(str_replace('-', ' ', $url_parts[5])) : 'Unknown Type';
						$address = isset($url_parts[6]) ? ucfirst(str_replace('-', ' ', implode('-', array_slice($url_parts, 6)))) : 'Unknown Address';

						// Breadcrumb items for custom logic
						$breadcrumbs_items = [
							['name' => $province, 'url' => '/ab'],
							['name' => $city, 'url' => "/ab/{$url_parts[3]}"],
							['name' => $city_region, 'url' => "/ab/{$url_parts[3]}/{$url_parts[4]}"],
							['name' => $property_type, 'url' => "/ab/{$url_parts[3]}/{$url_parts[4]}/{$url_parts[5]}"],
							['name' => $address, 'url' => '']
						];

						$breadcrumbs_item_index = 1;
						$breadcrumbs_count = count($breadcrumbs_items);

						foreach ( $breadcrumbs_items as $item ) {
							// Only show breadcrumb if the name doesn't contain "Unknown"
							if (strpos($item['name'], 'Unknown') === false) {
								echo '<li>';

								if ( isset( $item['url'] ) && ! empty( $item['url'] ) ) {
									?>
									<a href="<?php esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['name'] ); ?></a><?php
								} else {
									echo esc_html( $item['name'] );
								}

								$breadcrumbs_item_index++;
								if ( $breadcrumbs_item_index <= $breadcrumbs_count ) {
									if ( is_rtl() ) {
										?><i class="breadcrumbs-separator fas fa-angle-left"></i><?php
									} else {
										?><i class="breadcrumbs-separator fas fa-angle-right"></i><?php
									}
								}

								echo '</li>';
							}
						}
					} else {
						// Use the default breadcrumb logic if "ab" is not found in the URL
						foreach ( $inspiry_breadcrumbs_items as $item ) {
							// Only show breadcrumb if the name doesn't contain "Unknown"
							if (strpos($item['name'], 'Unknown') === false) {
								echo '<li>';

								if ( isset( $item['url'] ) && ! empty( $item['url'] ) ) {
									?>
									<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['name'] ); ?></a><?php
								} else {
									echo esc_html( $item['name'] );
								}

								$breadcrumbs_item_index++;
								if ( $breadcrumbs_item_index <= $breadcrumbs_count ) {
									if ( is_rtl() ) {
										?><i class="breadcrumbs-separator fas fa-angle-left"></i><?php
									} else {
										?><i class="breadcrumbs-separator fas fa-angle-right"></i><?php
									}
								}

								echo '</li>';
							}
						}
					}
					?>
				</ul>
			</nav>
		</div>
	<?php
	}
}

<?php
global $paged, $posts_per_page, $property_status_filter, $dashboard_posts_query;

$favorite_properties_count = 0;
$favorite_pro_ids     = realhomes_get_favorite_pro_ids();
if ( ! empty( $favorite_pro_ids ) ) {
	$favorite_properties_count = count( $favorite_pro_ids );
}

$posts_per_page = realhomes_dashboard_posts_per_page();

$favorites_properties_args = array(
	'post_type'      => 'property',
	'posts_per_page' => $posts_per_page,
	'paged'          => $paged,
	'post__in'       => $favorite_pro_ids,
	'orderby'        => 'post__in',
);

$property_status_filter = realhomes_dashboard_properties_status_filter();
if ( '-1' !== $property_status_filter ) {
	$favorites_properties_args['tax_query'] = array(
		array(
			'taxonomy' => 'property-status',
			'field'    => 'slug',
			'terms'    => $property_status_filter
		)
	);
}

// Add searched parameter
if ( isset( $_GET['posts_search'] ) && 'show' == get_option( 'inspiry_my_properties_search', 'show' ) ) {
	$favorites_properties_args['s'] = sanitize_text_field( $_GET['posts_search'] );
	printf( '<div class="dashboard-notice"><p>%s <strong>%s</strong></p></div>', esc_html__( 'Search results for: ', 'framework' ), esc_html( $_GET['posts_search'] ) );
}

$dashboard_posts_query = new WP_Query( $favorites_properties_args );
$favorites_after_login = get_option( 'inspiry_login_on_fav', 'no' );


// To fix styles for no items container
$dashboard_inner_class = '';
if ('yes' === $favorites_after_login) {
    $dashboard_inner_class = 'dashboard-content-inner';
}

$allow_fav_share = get_option( 'realhomes_dashboard_allow_favorites_list_share', 'true' );
$fav_share_list = array();
?>
<div id="property-message"></div>
    <div id="dashboard-favorites" class="dashboard-favorites <?php echo $dashboard_inner_class; ?>">
    <?php
    if ( $favorite_properties_count > 0 ) {
        ?>
            <?php
            get_template_part( 'common/dashboard/top-nav' );

            if ( $dashboard_posts_query->have_posts() ) {
                ?>
                <div class="dashboard-posts-list">
                    <?php get_template_part( 'common/dashboard/property-columns' ); ?>
                    <div class="dashboard-posts-list-body">
                        <?php
                        while ( $dashboard_posts_query->have_posts() ) {
                            $dashboard_posts_query->the_post();
                            global $post;

                            if ( $allow_fav_share === 'true' ) {
	                            // Managing list for share functionality
	                            $fav_share_list[$post->ID] = get_the_title( $post->ID );
                            }

                            get_template_part( 'common/dashboard/property-card' );
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
                <?php
            }

            get_template_part( 'common/dashboard/bottom-nav' );
            ?>
        <?php
    } else {
        realhomes_dashboard_no_items(
            esc_html__( 'No favorite properties!', 'framework' ),
            esc_html__( 'You have no property in your favorites.', 'framework' ),
            'no-favorite.svg'
        );
    }
    ?>
    </div><!-- #dashboard-favorites -->

<?php
if ( $allow_fav_share === 'true' && ! empty( $fav_share_list ) ) {
    ?>
    <div class="fav-share-lightbox">
        <div class="fav-share-wrap">
            <h3><?php esc_html_e( 'Share Favorite Properties', 'framework' ); ?></h3>
            <div class="fav-list-wrap">
                <p class="description"><?php esc_html_e( 'Choose the properties you want to share via email.', 'framework' ); ?></p>

                <div class="heading">
                    <span class="prop-title"><?php esc_html_e( 'Properties List', 'framework' ); ?></span>
                </div>
                <div class="prop-list">
                    <ul>
		                <?php
		                foreach ( $fav_share_list as $id => $title ) {
			                ?>
                            <li class="active" data-prop-id="<?php echo esc_attr( $id ); ?>">
                                <span class="check"></span>
                                <span class="prop-title"><a href="<?php echo get_permalink( $id ); ?>" target="_blank"><?php echo esc_html( $title ); ?></a></span>
                            </li>
			                <?php
		                }
		                ?>
                    </ul>
                </div>

                <div class="buttons-wrap">
                    <span class="btn btn-primary next-btn"><?php esc_html_e( 'Next', 'framework' ); ?></span>
                </div>
            </div>

            <div class="share-form-wrap">
                <label for="fav-share-email"><?php esc_html_e( 'Email', 'framework' ); ?></label>
                <p class="desc"><?php esc_html_e( 'Enter email to share your properties with', 'framework' ); ?></p>
                <input type="email" id="fav-share-email" required name="fav-share-email">
                <input type="hidden" id="fav-share-nonce" name="fav-share-nonce" value="<?php echo wp_create_nonce( 'fav_share_nonce' ); ?>">
                <input type="hidden" name="action" value="send-message">
                <div class="buttons-wrap">
                    <span class="btn btn-primary back-btn"><?php esc_html_e( 'Back', 'framework' ); ?></span>
                    <input type="submit" class="btn btn-primary send-btn" value="<?php esc_html_e( 'Send', 'framework' ); ?>">
                </div>
            </div>

            <div class="fav-share-progress">
                <p class="loader">
                    <br>
                    <?php inspiry_safe_include_svg( 'loader.svg', 'common/images/' ); ?>
                    <br><br>
                    <span><?php esc_html_e( 'Working on it...', 'framework' ); ?></span>
                </p>
                <p class="message"></p>
            </div>

            <span class="close fas fa-times"></span>
        </div>
    </div>
    <?php
}
?>
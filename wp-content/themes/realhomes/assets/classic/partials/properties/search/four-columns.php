<section class="listing-layout property-grid property-grid-four-column">
	<div class="list-container inner-wrapper clearfix">
		<?php
			global $search_query;
			if ( $search_query->have_posts() ) :

				$counter = 1;
				while ( $search_query->have_posts() ) :
					$search_query->the_post();

					// properties grid card.
					get_template_part( 'assets/classic/partials/properties/grid-card' );

					if ( $counter % 2 == 0 ) { ?>
                        <div class="clearfix rh-visible-xs"></div>
                        <?php
					}

					if ( $counter % 3 == 0 ) { ?>
                        <div class="clearfix rh-visible-sm"></div>
                        <?php
					}

					if ( $counter % 4 == 0 ) { ?>
                        <div class="clearfix rh-visible-md rh-visible-lg"></div>
                        <?php
					}
					$counter ++;
				endwhile;
				wp_reset_postdata();
			else :
				realhomes_print_no_result( get_option( 'inspiry_search_template_no_result_text' ), array( 'container_class' => 'alert-wrapper' ) );
			endif;
		?>
	</div>
</section>
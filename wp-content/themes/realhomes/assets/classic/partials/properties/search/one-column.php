<section class="listing-layout">
	<div class="list-container inner-wrapper clearfix">
		<?php
			global $search_query;
			if ( $search_query->have_posts() ) :
				while ( $search_query->have_posts() ) :
					$search_query->the_post();

					get_template_part( 'assets/classic/partials/properties/list-card' );

				endwhile;
				wp_reset_postdata();
			else :
				realhomes_print_no_result( get_option( 'inspiry_search_template_no_result_text' ), array( 'container_class' => 'alert-wrapper' ) );
			endif;
		?>
	</div>
</section>
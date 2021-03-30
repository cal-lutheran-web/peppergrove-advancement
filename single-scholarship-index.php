<?php


if(isset($_GET['metadata'])){

	// just page meta data to be used in HTML head

	echo '<title>'.get_the_title().' | Cal Lutheran</title>';

} else {

	// page content


	$img = wp_get_attachment_url(get_post_thumbnail_id($post->ID));

	echo '<div class="row"><div class="col-sm-9">';
		
		// main content
		
		$tagline = get_field('tagline') ? '<h2>'.get_field('tagline').'</h2>' : '';

		echo '
			<div class="detail-hero" style="background-image: url('.$img.');">
				<div class="detail-hero-content">
					<h1>'.get_the_title().'</h1>
					'.$tagline.'
				</div>
			</div>
		';

		echo '<p class="intro">'.get_field('summary', false, false).'</p>';
		
		the_content();

	echo '</div><div class="col-sm-3">';

		// sidebar
		
		echo '
			<p><a href="'.$donation_url.'?fund='.get_field('fund_id').'" class="btn btn-large btn-arrow-right block green">Support this Scholarship</a></p>
			<p><strong><a href="index.html" class="icon-line-arrow-left">Find Other Scholarships</a></strong></p>
		';




		//for use in the loop, list 5 post titles related to first tag on current post
		$taxonomies = get_object_taxonomies($post);

		$tax_query = array();

		foreach($taxonomies as $tax){
			$tax_terms = get_the_terms($post, $tax);

			$post_terms = array();

			if($tax_terms){
				foreach($tax_terms as $t){
					$post_terms[] = $t->slug;
				}

				if(!empty($post_terms)){
					$this_tax_query['taxonomy'] = $tax;
					$this_tax_query['field'] = 'slug';
					$this_tax_query['terms'] = $post_terms;

					$tax_query[] = $this_tax_query;
				}
			}
		}

		$related_data = new WP_Query(array(
			'post_type' => 'scholarship-index',
			'orderby' => 'title',
			'order' => 'asc',
			'tax_query' => array(
				'relation' => 'AND',
				$tax_query
			)
		));
		
		if($related_data->have_posts()){

			echo '
			<div class="bg-box bg-silver">
				<h5>Related Scholarships</h5>
				<ul>';

				while($related_data->have_posts()) {
					$related_data->the_post();

					echo '<li><a href="'.get_detail_url($post).'">'.get_the_title().'</a></li>';

				}

			echo '</ul></div>';


		} else {
			wp_reset_query();
		}

		

		

	echo '</div></div>';

}



?>


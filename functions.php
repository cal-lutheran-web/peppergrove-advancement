<?php



	function donation_url(){ return '/giving/donate/special-gifts.html'; }

	
	function get_data_url(){
		return 'https://www.callutheran.edu/_resources/php/wp-templates/template-parts/advancement/scholarships/scholarships-data.php';
	}
	
	function get_detail_url($post){

		$link = (get_the_content() !== '') ? 'detail.html?id='.$post->post_name : donation_url().'?fund='.get_field('fund_id');
		
		return $link;
	}
	
	function scholarship_item($post=null, $class=null){

		
		$title = ($post !== null) ? $post->post_title : '';

		$subtitle = (get_field('summary')) ? '<p class="tile-description">'.strip_tags(get_field('summary')).'</p>' : '';
		
		$css = ($class !== null) ? $class : '';
		
		$img_data = wp_get_attachment_url(get_post_thumbnail_id($post->ID));

		$image = ($post !== null) ? 'style="background-image: url('.$img_data.');"' : '';
		
		
		$link = ($post !== null) ? '<a href="'.get_detail_url($post).'" class="tile-link">'.get_the_title().'</a>' : '';

		
		$html = '
			<div class="tile scholarship-item '.$css.'" '.$image.'>
				<div class="tile-info scholarship-item-title">
					<header class="tile-title">'.$link.'</header>
					'.$subtitle.'
				</div>
			</div>
		';
		
		return $html;
	}


?>
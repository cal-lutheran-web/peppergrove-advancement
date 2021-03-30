<?php

// get default data

$default_data = new WP_Query(array(
	'post_type' => array( 'scholarship-index' ),
	'numberposts' => -1,
	'meta_query' => array(
		array(
			'key'     => 'featured',
			'value'   => '1',
			'compare' => '=',
		)
	)
));







// get list of all scholarship taxonomies and data

$taxonomies = get_object_taxonomies('scholarship-index', 'objects');

$all_tabs = array();

foreach($taxonomies as $taxonomy){

	$tax_slug = $taxonomy->name;
	$tax_name = $taxonomy->label;
	
	$tab_content = '';
	
	$terms = get_terms(array(
		'taxonomy' => $tax_slug,
		'hide_empty' => false
	));


	foreach( $terms as $term ){
		$tab_content .= '
		<li>
			<button class="btn-borderless btn-sans-font scholarships-category-button" @click="getSearchResults(\''.$tax_slug.'='.$term->term_id.'\', true)">'.$term->name.'</button>
		</li>';
	}
	
	$tab_content_html = '<ul class="col-list scholarships-list">'.$tab_content.'</ul>';
	
	
	$tab_item = array(
		'title' => $tax_name,
		'content' => $tab_content_html
	);
	
	
	$all_tabs[] = $tab_item;
	
}




?>



<div class="scholarships-search-wrapper" x-data="search()" x-init="init()">
	
	
	<div class="row scholarships-search-area">
		<div class="col-sm-12 col-md-offset-3 col-md-6 centered">
			<label for="scholarship-search">Search Scholarships</label>
			<input class="scholarship-search-input" id="scholarship-search" x-ref="scholarshipSearch" name="scholarship-search" type="search" @keyUp.debounce="keywordSearch($event)" />
		</div>
		<div class="col-md-3">
			<button class="btn-borderless icon-close-it btn-no-style scholarship-search-clear" x-show="searchMade" @click.prevent="clearSearch">Clear Search</button>
		</div>
	</div>
	
	<?php echo tabs($all_tabs,'scholarships-filter-tabs tabs-hide tabs-no-hash'); ?>
	
	<header class="section-title" x-ref="resultsTitle"><?php echo '' ?></header>
	
	<div class="scholarship-search-loading" :class="{ \'show\': loadingMessage !== \'\' }">
		<div class="scholarship-search-loading-message" x-text="loadingMessage"></div>
	</div>
	
	
	
	<div id="scholarships-results" class="tile-grid-container" x-ref="resultsList">

		
		<?php
		
			// The Loop
			if($default_data->have_posts()){
				while($default_data->have_posts()) {
					$default_data->the_post();

					echo scholarship_item($post);
				}
			}
			
			// Restore original Post Data
			wp_reset_postdata();
			
		?>
		
		
	</div>
	
	
	<template x-ref="loadingItems">
		
	</template>
	
	
</div>
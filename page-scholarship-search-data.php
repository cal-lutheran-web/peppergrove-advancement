<?php


$args = array();
$taxonomy_query = array();

$taxonomies = get_object_taxonomies('scholarship-index');

// check if URL query has a taxonomy
if(!empty($_GET)){
	foreach($_GET as $param=>$value){
		if(in_array($param, $taxonomies)){
			$taxonomy_query[$param] = $value;
		}
	}
}


// if search is set
if(isset($_GET['search'])){

	$title = 'Search Results for <strong>'.$_GET['search'].'</strong>';

	$args = array(
		's' => $_GET['search']
	);

}
// taxonomy search
else if(!empty($taxonomy_query)){

	$key = array_keys($taxonomy_query)[0];
	$value = array_values($taxonomy_query)[0];

	$title = get_term_by('id', $value, $key)->name;


	$args = array(
		'tax_query' => array(
			array(
				'taxonomy' => $key,
				'field'    => 'term_id',
				'terms'    => $value,
			)
		)
	);
	

}
// default data view
else {

	$title = 'Featured Scholarships';

	$args = array(
		'meta_query' => array(
			array(
				'key'     => 'featured',
				'value'   => '1',
				'compare' => '=',
			)
		)
	);

}

$wp_query_args = array_merge(
	array(
		'post_type' => array( 'scholarship-index' ),
		'numberposts' => -1
	),
	$args
);

$data = new WP_Query($wp_query_args);

?>



<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title><?php echo $title; ?></title>
</head>
<body>


<?php

if($data->have_posts()){
	while($data->have_posts()) {
		$data->the_post();
		
		echo scholarship_item($post);
	}
}

// Restore original Post Data
wp_reset_postdata();

?>



</body>
</html>
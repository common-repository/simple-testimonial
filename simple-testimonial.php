<?php
/*
Plugin Name: Simple Testimonial
Plugin URI: http://nitinmaurya.com/
Description: A brief description of the Plugin.
Version: 1.0
Author: Nitin Maurya
Author URI: http://nitinmaurya.com/
License: A "Slug" license name e.g. GPL2
*/
register_activation_hook(__FILE__,'stestimonial_install');
function stestimonial_install(){
	global $wp_version;
	if(version_compare($wp_version, "3.2.1", "<")) {
		deactivate_plugins(basename(__FILE__));
		wp_die("This plugin requires WordPress version 3.2.1 or higher.");
	}
}
/* Add Testimonial Menu */
function stestimonial_menu(){

	$labels = array(
    'name'               => 'Testimonial',
    'singular_name'      => 'Testimonial',
    'add_new'            => 'Add New',
    'add_new_item'       => 'Add New Testimonial',
    'edit_item'          => 'Edit Testimonial',
    'new_item'           => 'New Testimonial',
    'all_items'          => 'All Testimonial',
    'view_item'          => 'View Testimonial',
    'search_items'       => 'Search Testimonial',
    'not_found'          => 'No books found',
    'not_found_in_trash' => 'No books found in Trash',
    'parent_item_colon'  => '',
    'menu_name'          => 'Testimonial'
  );

  $args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array( 'slug' => 'testimonial' ),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'supports'           => array( 'title', 'editor', 'thumbnail' )
  );

  register_post_type( 'testimonial', $args );
}

function show_testimonial_action($arg){
	//print_r($arg);
	$before_title="<h1>";
	$after_title="</h1>";
	$title="";
	$before_image="";
	$after_image="";
	$limit=(isset($arg['limit'])?$arg['limit']:10);
	$order=(isset($arg['order'])?$arg['order']:'DESC');
	
	if(isset($arg['title'])){$title=$before_title.$arg['title'].$after_title;}
	
	if(isset($arg['testimonial_id'])){
		$testiargs = array( 'post_type' => 'testimonial', 'posts_per_page' => $limit, 'p' =>$arg['testimonial_id'],'order'=>$order   );
	}else{
		$testiargs = array( 'post_type' => 'testimonial', 'posts_per_page' => $limit, $passing_arg,'order'=>$order  );
	}
	$loop = new WP_Query( $testiargs );
	while ( $loop->have_posts() ) : $loop->the_post();
		
		
		if ( has_post_thumbnail() ) :
			$size=array(150,150);
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size, 'post-header-thumb' ); 	
			$show_image=$before_image.'<img src="'.$image[0].'" class="alignleft"/>'.$after_image;
		endif;
		
		echo '<div class="entry-content">'.$show_image;
		the_content();
		echo '</div>';
		
		echo '<div style="text-align:right; font-size:18px; font-weight:bold;">-';
		the_title();
		echo '</div>';
		
	endwhile;
}


add_action( 'init', 'stestimonial_menu' );
add_shortcode('show_testimonial', 'show_testimonial_action');

?>
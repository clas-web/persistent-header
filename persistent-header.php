<?php
/* 
Plugin Name: Persistent Header
Plugin URI: https://github.com/clas-web/persistent-header
Description: This plugin keeps the header images set as you change themes.
Version: 1.0.0
Author: Aaron Forsyth
Author URI: https://www.linkedin.com/in/aaron-forsyth-4a5634122/
GitHub Plugin URI: https://github.com/clas-web/persistent-header
*/


add_action('pre_update_option_stylesheet', 'ph_pre_update_option_stylesheet');
add_action('switch_theme', 'ph_switch_theme');

function ph_pre_update_option_stylesheet($stylesheet, $old_stylesheet){
	//Set temporary option for header_image value from old theme
    update_option('previous_header_image_saved', get_theme_mod('header_image'));
	
	//Update uploaded images to make them available in the new theme
	$header_images = get_uploaded_header_images();
	foreach ($header_images as $header_image) {
		$image_post = $header_image['attachment_id'];
		update_post_meta($image_post, '_wp_attachment_is_custom_header', $stylesheet, $old_stylesheet); 
	}
    return $stylesheet;
}

function ph_switch_theme(){
	//Use temporary option to set current theme's header_image
	if (get_option('previous_header_image_saved')) {
		set_theme_mod('header_image', get_option('previous_header_image_saved'));
		delete_option('previous_header_image_saved');
	}
}
?>


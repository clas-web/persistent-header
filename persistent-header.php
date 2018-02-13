<?php
/* 
Plugin Name: Persistent Header
Plugin URI: https://github.com/clas-web/persistent-header
Description: This plugin keeps the header images set as you change themes.
Version: 1.0.1
Author: Aaron Forsyth
Author URI: https://www.linkedin.com/in/aaron-forsyth-4a5634122/
GitHub Plugin URI: https://github.com/clas-web/persistent-header
*/


$persist_options = array(
							'header_type',
							'header_constrain_width',
							'header_image',
							'header_textcolor',
							'header_textbgcolor',
							'header-title-hide',
							'header-title-position',
							'background_color', 
							'background_image',
							'background_preset',
							'background_position_x',
							'background_position_y',
							'background_size',
							'background_repeat',
							'background_attachment',
							'custom_css_post_id',
							'vtt-variation',
							'vtt-variation-choices'
							);
							
add_action('pre_update_option_stylesheet', 'ph_pre_update_option_stylesheet', 10, 2);
add_action('switch_theme', 'ph_switch_theme');

function ph_pre_update_option_stylesheet($stylesheet, $old_stylesheet){
	//Set temporary option for header_image value from old theme
	global $persist_options;
	foreach ($persist_options as $option){
		if(get_theme_mod($option)) update_option('previous'.$option,get_theme_mod($option));
	}
	//Update custom css if it exists
	if(get_theme_mod('custom_css_post_id')){
	 $my_post = array(
					'ID'           => get_theme_mod('custom_css_post_id'),
					'post_title'   => $stylesheet,
					'post_name'   => $stylesheet					
		);

		wp_update_post($my_post);
	}
	//Update uploaded images to make them available in the new theme
	$header_images = get_uploaded_header_images();
	//Need to modify the header image filepath to match the new theme
	foreach ($header_images as $header_image) {
		$image_post = $header_image['attachment_id'];
		update_post_meta($image_post, '_wp_attachment_is_custom_header', $stylesheet, $old_stylesheet);
	}
		
    return $stylesheet;
}

function ph_switch_theme(){
	global $persist_options;
	//Use temporary option to set current theme's header_image
	foreach ($persist_options as $option){
		if(get_option('previous'.$option)){
			set_theme_mod($option, get_option('previous'.$option));
			delete_option('previous'.$option);
		}
	}
}
?>


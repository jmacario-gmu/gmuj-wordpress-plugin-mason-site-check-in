<?php

/**
 * Summary: php file which implements the plugin custom API
 */

add_action('rest_api_init', 'gmuj_sci_register_routes');

function gmuj_sci_register_routes() {

	// Most recent modifications
	register_rest_route('gmuj-sci', 'most-recent-modifications', array(
		'methods' => 'GET',
		'callback' => function(){
			return array(
				'last_modified' => get_most_recent_touch_date(),
				'last_modified_post' => gmuj_sci_get_most_recent_post_date(),
				'last_modified_page' => gmuj_sci_get_most_recent_page_date(),
				'last_modified_attachment' => gmuj_sci_get_most_recent_attachment_date(),
				'last_modified_user' => gmuj_sci_get_most_recent_user_date(),
				'last_login' => gmuj_sci_get_most_recent_login_timestamp(),
				'last_login_user' => gmuj_sci_get_most_recent_login_username(),
			);
		}
	));

	// Theme info
	register_rest_route('gmuj-sci', 'theme-info', array(
		'methods' => 'GET',
		'callback' => function(){

		    // Get current theme
		    $active_theme = wp_get_theme();

		    // Return data
		    return array(
				'theme' => $active_theme->Name,
				'theme_version' => $active_theme->Version,
			);

		}
	));

}

<?php

/**
 * Summary: php file which implements the plugin custom API
 */

add_action('rest_api_init', 'gmuj_sci_register_routes');

function gmuj_sci_register_routes() {

	// Most recent touch
	register_rest_route('gmuj-sci', 'most-recent-touch', array(
			'methods' => 'GET',
			'callback' => function(){return array('last_modified' => get_most_recent_touch_date());}
		)
	);
	
	// Most recent post
	register_rest_route('gmuj-sci', 'most-recent-post', array(
			'methods' => 'GET',
			'callback' => 'gmuj_sci_get_most_recent_post_date',
		)
	);

	// Most recent page
	register_rest_route('gmuj-sci', 'most-recent-page', array(
			'methods' => 'GET',
			'callback' => 'gmuj_sci_get_most_recent_page_date',
		)
	);

	// Most recent attachment
	register_rest_route('gmuj-sci', 'most-recent-attachment', array(
			'methods' => 'GET',
			'callback' => 'gmuj_sci_get_most_recent_attachment_date',
		)
	);

	// Most recent user
	register_rest_route('gmuj-sci', 'most-recent-user', array(
			'methods' => 'GET',
			'callback' => 'gmuj_sci_get_most_recent_user_date',
		)
	);

	// Most recent login
	register_rest_route('gmuj-sci', 'most-recent-login', array(
			'methods' => 'GET',
			'callback' => 'gmuj_sci_get_most_recent_login_timestamp',
		)
	);

	// Most recent login user
	register_rest_route('gmuj-sci', 'most-recent-login-user', array(
			'methods' => 'GET',
			'callback' => 'gmuj_sci_get_most_recent_login_username',
		)
	);


}

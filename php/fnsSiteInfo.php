<?php

/**
 * Summary: php file which contains functions related to site info
 */

function gmuj_sci_get_most_recent_post_date(){

	// Get answer from generic function
		return gmuj_sci_get_modified_date_of_most_recent_post_record_of_type('post');  	

}

function gmuj_sci_get_most_recent_page_date(){

	// Get answer from generic function
		return gmuj_sci_get_modified_date_of_most_recent_post_record_of_type('page');  	  	

}

function gmuj_sci_get_most_recent_attachment_date(){

	// Get answer from generic function
		return gmuj_sci_get_modified_date_of_most_recent_post_record_of_type('attachment'); 	

}

function gmuj_sci_get_modified_date_of_most_recent_post_record_of_type($post_type){

	// Get globals
		global $wpdb; // WordPress wpdb class instance, used for database access

	// Setup return variable
		$return_string="";

	// Get record representing most recently modified record of the type specified, while ignoring auto-drafts 
		$record = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type='$post_type' AND post_status<>'auto-draft' ORDER BY post_modified DESC LIMIT 1");

	// if we have a record, store the post_modified value as the return value
		if ($record) { $return_string=$record->post_modified; }

	// Return value
		return $return_string;  

}

function gmuj_sci_get_most_recent_user_date(){

	// Setup return variable
		$return_string="";

	// Get array of WP_User objects
		$wp_users = get_users(array(
	    'number' => 1, // get only a single user
		'orderby' => 'user_registered', // order by the date the user was registered
		'order' => 'DESC', // order descending
	));

	// Loop through array of WP_User objects
		foreach ($wp_users as $wp_user) {
	    	$return_string.=esc_html($wp_user->user_registered);
		}

	// Return value
		return $return_string;    	

}

function gmuj_sci_get_most_recent_login_timestamp(){

	// Get answer from generic function
		return gmuj_sci_get_most_recent_login_info('timestamp'); 	

}

function gmuj_sci_get_most_recent_login_username(){

	// Get answer from generic function
		return gmuj_sci_get_most_recent_login_info('username'); 	
			
}

function gmuj_sci_get_most_recent_login_info($output_data){

	// Setup return variable
		$return_string="";

	// Get array of WP_User objects
		$wp_users = get_users(array(
	    'number' => -1, // get all users
	));

	// Variables to store highest login time and user
		$most_recent_login_time = 0;
		$most_recent_login_user = '';

	// Loop through array of WP_User objects
		foreach ($wp_users as $wp_user) {
	    	// Get user ID
	    		$user_id=$wp_user->ID;
	    		//$return_string.='User: '.$wp_user->display_name.PHP_EOL;
	    	// Get user session tokens
	    		$user_session_tokens=get_user_meta($user_id,'session_tokens');
	    	// Loop through user session tokens
	    		foreach ($user_session_tokens as $user_session_token) {
	    			foreach ($user_session_token as $user_session_token_item) {
	    				foreach ($user_session_token_item as $key => $value) {
	    					if ($key=='login') {
	    						if ($value > $most_recent_login_time) {
	    							$most_recent_login_time=$value;
	    							$most_recent_login_user=$wp_user->display_name;
	    						}
	    						//$return_string.='     login time: '.date("Y-m-d\TH:i:s\Z", $value).PHP_EOL;
	    					}
	    				}
	    			}
	    		}
		}

	// Output requested info
		if ($output_data=='timestamp') {
			$return_string.=date("Y-m-d H:i:s", $most_recent_login_time);
		}
		if ($output_data=='username') {
			$return_string.=$most_recent_login_user;
		}

	// Return value
		return $return_string;    	

}

function gmuj_sci_get_summary_info() {

	// Setup return variable
		$return_string="";

	// Display days since last modification date and last modification date
		$return_string .= "<strong>".get_days_since_most_recent_touch_date()." days since most recent modification</strong> (". get_most_recent_touch_date() .")<br />". PHP_EOL;

	// Return value
		return $return_string;    	

}

function gmuj_sci_get_detail_info() {

	// Setup return variable
		$return_string="";

	// Most recent post date
		$return_string .= "Most recently modified post: " . gmuj_sci_get_most_recent_post_date() . "<br />". PHP_EOL;
	// Most recent page date
		$return_string .= "Most recently modified page: " . gmuj_sci_get_most_recent_page_date() . "<br />". PHP_EOL;
	// Most recent attachment date
		$return_string .= "Most recently modified attachment: " . gmuj_sci_get_most_recent_attachment_date() . "<br />". PHP_EOL;
	// Most recent user date
		$return_string .= "Most recently modified user: " . gmuj_sci_get_most_recent_user_date() . "<br />". PHP_EOL;
	// Most recent login
		$return_string .= "Most recent login: " . gmuj_sci_get_most_recent_login_timestamp() . "<br />". PHP_EOL;

	// Return value
		return $return_string;    	

}

function get_most_recent_touch_date(){

	// Get array of most recent touch dates
		$touch_dates = array(gmuj_sci_get_most_recent_post_date(), gmuj_sci_get_most_recent_page_date(), gmuj_sci_get_most_recent_user_date(), gmuj_sci_get_most_recent_attachment_date());
	// Determine which touch date is most recent
		$most_recent_touch_date = max(array_map('strtotime', $touch_dates));
	// Format most recent touch date
		$return_string=date('Y-m-d H:i:s', $most_recent_touch_date);
	// Return that date
		return $return_string;
}


function get_days_since_most_recent_touch_date(){

	// Get most recent touch date
		$most_recent_touch_date=get_most_recent_touch_date();
	// Convert most recent touch date to unix time
		$most_recent_touch_date_time=strtotime ($most_recent_touch_date);
	// Get current time
		$now = time();
	// Calculate time difference
		$datediff = $now - $most_recent_touch_date_time;
	// Convert to days
		$days_since_most_recent_touch_date = round($datediff / (60 * 60 * 24));
	// Return value
		return $days_since_most_recent_touch_date;

}
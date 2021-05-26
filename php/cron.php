<?php

/**
 * Summary: php file which implements the wp cron tasks
 */


// WP hooks

  // Cron hook to execute auto-refresh
  add_action('gmuj_sci_cron_hook', 'gmuj_sci_run_check_in_process');

// Setup WP cron jobs
  if (!wp_next_scheduled('gmuj_sci_cron_hook')) {
      wp_schedule_event(time(), 'hourly', 'gmuj_sci_cron_hook');
  }

function gmuj_sci_run_check_in_process(){

	// Get globals
	global $days_per_checkpoint;

	// Is the site 'stale'? Do we need to run the check-in process?
	if (gmuj_sci_site_needs_check_in()) {

		// Update checkpoint
			gmuj_sci_update_checkpoint();
					
		// Send check-in email
			gmuj_sci_send_email();
	}

}

function gmuj_sci_site_needs_check_in(){

	// Set default return value
	$return_value=false;

	// Get globals
	global $days_per_checkpoint;
	
	// Figure out if the site needs to check-in
		// Get last checkpoint
			if (!empty(get_option('gmuj_sci_last_checkpoint'))) {
				$last_checkpoint_days = get_option('gmuj_sci_last_checkpoint');
			} else {
				$last_checkpoint_days = 0;
			}
		// Get days since last touch
			$days_since_most_recent_touch=get_days_since_most_recent_touch_date();
		// Has it been as long as a check-in period or more since the last touch?
			if ($days_since_most_recent_touch>=$last_checkpoint_days+$days_per_checkpoint) {
				$return_value=true;
			}

	return $return_value;

}

function gmuj_sci_update_checkpoint(){

	// Get globals
	global $days_per_checkpoint;

	// Update checkpoint
		// Get new checkpoint
			$new_checkpoint = intdiv(get_days_since_most_recent_touch_date(), $days_per_checkpoint) * $days_per_checkpoint;
		// Update checkpoint option with new checkpoint
			update_option('gmuj_sci_last_checkpoint', $new_checkpoint);

}

function gmuj_sci_send_email(){

	// Get plugin options
		$gmuj_sci_options = get_option('gmuj_sci_options');

	// Get website name
		$website_name = get_bloginfo('name');

	// Set the number of hours to subtract to correct for timezone
	  $hoursToSubtract = 4;

	// Output the current time to a string, while subtracting 4 hours so the time is correct for our time zone
		//$date_string = date("F j, Y, g:i a", time()->sub(new DateInterval("PT{$hoursToSubtract}H")));
	  	$date_string = date("F j, Y, g:i a", time());
	
	// Build email subject
		$email_subject='';
		$email_subject.='Mason Site Check-In ('.get_days_since_most_recent_touch_date().' days'.'): '.$website_name.' ('.$_SERVER['HTTP_HOST'].')';
	
	// Build email body
		$email_body='';
		$email_body.=gmuj_sci_get_summary_info();	
		$email_body.=gmuj_sci_get_detail_info();	

	// Switch email to text/html - https://developer.wordpress.org/reference/hooks/wp_mail_content_type/#comment-777
		add_filter('wp_mail_content_type', 'gmuj_sci_set_html_mail_content_type');

	// Send email
		// Send to webmaster
			wp_mail('webmaster@gmu.edu', $email_subject, $email_body);
		// Loop through other email addresses
			if (!empty($gmuj_sci_options['gmuj_sci_settings_email'])) {
				// Separate addresses
					$addresses = explode(" ", $gmuj_sci_options['gmuj_sci_settings_email']);
				// Loop through email addresses
					foreach ($addresses as $address){
						// Send notification email
						wp_mail($address, $email_subject, $email_body);
					}
			}	

	// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
		remove_filter('wp_mail_content_type', 'gmuj_sci_set_html_mail_content_type');

}
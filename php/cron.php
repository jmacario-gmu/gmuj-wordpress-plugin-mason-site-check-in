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

		// Send check in notification email
			gmuj_send_check_in_email();

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

function gmuj_send_check_in_email() {

		// Set email subject and body
			$email_subject='Mason Site Check-In ('.get_days_since_most_recent_touch_date().' days'.'): '.get_bloginfo('name').' ('.$_SERVER['HTTP_HOST'].')';
			$email_body=gmuj_sci_get_summary_info().gmuj_sci_get_detail_info();

		// Send check-in email
			gmuj_sci_send_email($email_subject,$email_body,"all");

}

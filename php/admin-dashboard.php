<?php

/**
 * Summary: php file which implements the plugin WP admin menu changes
 */

/**
 * Adds meta boxes to WordPress admin dashboard
 * 
 * The meta box content comes from the functions listed in the add_meta_box function calls and are below.
 */
add_action('wp_dashboard_setup', 'gmuj_sci_custom_dashboard_meta_boxes');
function gmuj_sci_custom_dashboard_meta_boxes() {

  // Declare global variables
  global $wp_meta_boxes;

  // Adds custom WordPress dashboard content boxes

   /* Add informational meta box */
  add_meta_box("gmuj_sci_custom_dashboard_meta_box_summary", "Mason Site Check-In", "gmuj_sci_custom_dashboard_meta_box_summary", "dashboard","normal");

}

/**
 * Provides content for the Mason Site Check-In 'summary' meta box
 */
function gmuj_sci_custom_dashboard_meta_box_summary() {

	// Output meta box info
		// Summary info
			echo '<h4><strong>Summary</strong></h4>';
			echo '<p>'.gmuj_sci_get_summary_info().'</p>';
		// Detail and secure info
			echo '<h4><strong>Details</strong></h4>';
			echo '<p>';
			echo gmuj_sci_get_detail_info();
			echo gmuj_sci_get_secure_info();
			echo '</p>';

}
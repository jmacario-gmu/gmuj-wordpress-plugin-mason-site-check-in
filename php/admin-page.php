<?php

/**
 * Summary: php file which implements the plugin WP admin page interface
 */

/**
 * Generates the plugin settings page
 */
function gmuj_sci_display_settings_page() {
	
	// Only continue if this user has the 'manage options' capability
	if (!current_user_can('manage_options')) return;

	// Get globals
	global $days_per_checkpoint;

	// Get plugin options
	$gmuj_sci_options = get_option('gmuj_sci_options');

	// email test button action
		// Check whether the button has been pressed AND also check the nonce value
			if (isset($_POST['email_button']) && check_admin_referer('email_button_clicked')) {
		// We're good; run the action.
			email_button_action();
		}

	// Begin HTML output
	echo "<div class='wrap'>";

	// Page title
	echo "<h1>" . esc_html(get_admin_page_title()) . "</h1>";

	// Output basic plugin info
	echo "<p>This plugin helps to identify when your website content was last updated.</p>";

	// Output check-in info
	echo '<p>Check-in period (days): '.$days_per_checkpoint.'</p>';
	echo '<p>Last checkpoint: ';
	$last_checkpoint = get_option('gmuj_sci_last_checkpoint');
	echo !empty($last_checkpoint) ? $last_checkpoint : 0;
	echo '</p>';

	// Display summary info
	echo '<h2>Summary</h2>';
	echo '<p>' . gmuj_sci_get_summary_info() . '</p>';

	// Display detailed and secure info
	echo '<h2>Details</h2>';
	echo '<p>';
	echo gmuj_sci_get_detail_info();
	echo gmuj_sci_get_secure_info();
	echo '</p>';

	
	// Display email info
	echo '<h2>Email Information</h2>';

    // Display email recipient addresses
	if (!empty($gmuj_sci_options['gmuj_sci_settings_email'])) {
		// Display intro
			echo '<h3>Email Notification Recipients</h3>';
			echo '<p>Notifications will be sent to the following email addresses (specified in the Email Settings section below):</p>';
		// Separate addresses
			$addresses = explode(" ", $gmuj_sci_options['gmuj_sci_settings_email']);
		// Loop through email addresses
			foreach ($addresses as $index=>$address){
				$index_for_display=$index+1;
				echo 'Recipient '.$index_for_display.': '.$address.'<br />';
			}
	} else {
		echo '<p>Please enter one or more space-separated email addresses in the box below.</p>';
	}

    // Display email button
	if (!empty($gmuj_sci_options['gmuj_sci_settings_email'])) {
		// Heading
		echo '<h3>Test Emails</h3>';
		echo '<p>Use the button below to test sending an email to the specified email recipients.</p>';
		// Start form
		echo '<form action="admin.php?page=gmuj_sci" method="post">';
		// Add nonce
		wp_nonce_field('email_button_clicked');
		// Add fields
		echo '<input type="hidden" value="true" name="email_button" />';
		// Add submit button
		submit_button('Test Email Notifications');
		// End form
		echo '</form>';
	}

	// Begin form
	echo "<form action='options.php' method='post'>";

	// output settings fields - outputs required security fields - parameter specifes name of settings group
	settings_fields('gmuj_sci_options');

	// output setting sections - parameter specifies name of menu slug
	do_settings_sections('gmuj_sci');

	// submit button
	submit_button();

	// Close form
	echo "</form>";

	// Finish HTML output
	echo "</div>";
	
}

function email_button_action(){

  // Output message to page
    echo '<div id="message" class="updated fade"><p>' . 'Sending email notification...' . '</p></div>';

  // refresh database
    gmuj_sci_send_email();
}

/**
 * Generates content for general settings section
 */
function gmuj_sci_callback_section_settings_general() {

	echo '<p>Set the Mason Site Check-In general settings.</p>';

}

/**
 * Generates content for email settings section
 */
function gmuj_sci_callback_section_settings_email() {

	//echo '<p>Set the Mason Site Check-In email settings.</p>';

}

/**
 * Generates text field for plugin settings option
 */
function gmuj_sci_callback_field_text($args) {
	
	//Get array of options. If the specified option does not exist, get default options from a function
	$options = get_option('gmuj_sci_options', gmuj_sci_options_default());
	
	//Extract field id and label from arguments array
	$id    = isset($args['id'])    ? $args['id']    : '';
	$label = isset($args['label']) ? $args['label'] : '';
	
	//Get setting value
	$value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';
	
	//Output field markup
	echo '<input id="gmuj_sci_options_'. $id .'" name="gmuj_sci_options['. $id .']" type="text" size="40" value="'. $value .'">';
	echo "<br />";
	echo '<label for="gmuj_sci_options_'. $id .'">'. $label .'</label>';
	
}

/**
 * Sets default plugin options
 */
function gmuj_sci_options_default() {

	return array(
		//'gmuj_sci_settings_field_01'   => 'default value',
		//'gmuj_sci_settings_email'   => 'webmaster@gmu.edu',
	);

}

/**
 * Validate plugin options
 */
function gmuj_sci_callback_validate_options($input) {
	
	// Example field
	if (isset($input['gmuj_sci_settings_field_01'])) {
		$input['gmuj_sci_settings_field_01'] = sanitize_text_field($input['gmuj_sci_settings_field_01']);
	}

	// Email field
	if (isset($input['gmuj_sci_settings_email'])) {
		$input['gmuj_sci_settings_email'] = sanitize_text_field($input['gmuj_sci_settings_email']);
	}

	return $input;
	
}

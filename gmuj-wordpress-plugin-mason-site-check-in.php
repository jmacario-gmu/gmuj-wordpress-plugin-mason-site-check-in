<?php

/**
 * Main plugin file for the Mason WordPress: Mason Site Check-In plugin
 */

/**
 * Plugin Name:       Mason WordPress: Mason Site Check-In
 * Author:            Jan Macario
 * Plugin URI:        https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-site-check-in
 * Description:       Mason WordPress plugin which provides infomation about when a site was last edited
 * Version:           1.0
 */


// Exit if this file is not called directly.
	if (!defined('WPINC')) {
		die;
	}

// Set up auto-updates
	require 'plugin-update-checker/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-site-check-in/',
	__FILE__,
	'gmuj-wordpress-plugin-mason-site-check-in'
	);

// Set global variables
	$days_per_checkpoint=30;

// Include files
	// Functions
		// Site info
			include('php/fnsSiteInfo.php');
	// Cron
		include('php/cron.php');
	// Admin menu
		include('php/admin-menu.php');
	// Admin page
		include('php/admin-page.php');
	// Dashboard info
		include('php/admin-dashboard.php');
	// Output
		include('php/output.php');
	// API
		include('php/api.php');



/**
 * Filter the mail content type.
 */
function gmuj_sci_set_html_mail_content_type() {
    return 'text/html';
}


/**
 * Register plugin settings
 */
add_action('admin_init', 'gmuj_sci_register_settings');
function gmuj_sci_register_settings() {
	
	/*
	Code reference:

	register_setting( 
		string   $option_group, // name of option group - should match the parameter used in the settings_fields function in the display_settings_page function
		string   $option_name, // name of the particular option
		callable $sanitize_callback = '' // function used to validate settings
	);

	add_settings_section( 
		string   $id, // section id
		string   $title, // title/heading of section
		callable $callback, // function that displays section
		string   $page // admin page (slug) on which this section should be displayed
	);

	add_settings_field(
    	string   $id, // setting id
		string   $title, // title of setting
		callable $callback, // outputs markup required to display the setting
		string   $page, // page on which setting should be displayed, same as menu slug of the menu item
		string   $section = 'default', // section id in which this setting is placed
		array    $args = [] // array the contains data to be passed to the callback function. by convention I pass back the setting id and label to make things easier
	);
	*/

	// Register serialized options setting to store this plugin's options
	register_setting(
		'gmuj_sci_options',
		'gmuj_sci_options',
		'gmuj_sci_callback_validate_options'
	);

	/*
	// Add section: general settings
	add_settings_section(
		'gmuj_sci_section_settings_general',
		'General Settings',
		'gmuj_sci_callback_section_settings_general',
		'gmuj_sci'
	);

	// Add field: example 01
	add_settings_field(
		'gmuj_sci_settings_field_01',
		'GMUJ ASC Settings Field 01',
		'gmuj_sci_callback_field_text',
		'gmuj_sci',
		'gmuj_sci_section_settings_general',
		['id' => 'gmuj_sci_settings_field_01', 'label' => 'GMUJ ASC Settings Field 01']
	);
	*/
	
	// Add section: email settings
	add_settings_section(
		'gmuj_sci_section_settings_email',
		'Email Settings',
		'gmuj_sci_callback_section_settings_email',
		'gmuj_sci'
	);

	// Add field: example 01
	add_settings_field(
		'gmuj_sci_settings_email',
		'Email Recipients',
		'gmuj_sci_callback_field_text',
		'gmuj_sci',
		'gmuj_sci_section_settings_email',
		['id' => 'gmuj_sci_settings_email', 'label' => 'space-separated list of email addresses']
	);

} 

<?php

/**
 * Main plugin file for the Mason WordPress: Mason Site Check-In plugin
 */

/**
 * Plugin Name:       Mason WordPress: Mason Site Check-In
 * Author:            Jan Macario
 * Plugin URI:        https://github.com/jmacario-gmu/gmuj-wordpress-plugin-mason-site-check-in
 * Description:       Mason WordPress plugin which provides information about this website.
 * Version:           1.2.2
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
	// Branding
		include('php/fnsBranding.php');
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
	// Plugin settings
		include('php/settings.php');
	// Email functions
		include('php/fnsEmail.php');
	// Action hook detection
		include('php/detect.php');

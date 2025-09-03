<?php

/**
 * Summary: php file which sets up action hooks to detect certain actions and send notification emails
 */


//check for plugin activation and send notification email
add_action( 'activated_plugin', 'gmuj_sci_detect_plugin_activation', 10, 2 );
function gmuj_sci_detect_plugin_activation($plugin, $network_activation) {

	//get site domain
	$site_domain=preg_replace('/^https?:\/\//i','',site_url());

	//set email subject
	$email_subject='Mason Site Check-In: Plugin Activated: '.$site_domain;

	//set email body
	$email_body='Status: Plugin Activated<br />';
	$email_body.='Website: '.get_bloginfo('name').' ('.$site_domain.')<br />';
	$email_body.='Plugin: '.$plugin.'<br />';

	//send email
	gmuj_sci_send_email($email_subject,$email_body);

}

//check for plugin deactivation and send notification email
add_action( 'deactivated_plugin', 'gmuj_sci_detect_plugin_deactivation', 10, 2 );
function gmuj_sci_detect_plugin_deactivation($plugin, $network_activation) {

	//get site domain
	$site_domain=preg_replace('/^https?:\/\//i','',site_url());

	//set email subject
	$email_subject='Mason Site Check-In: Plugin Deactivated: '.$site_domain;

	//set email body
	$email_body='Status: Plugin Deactivated<br />';
	$email_body.='Website: '.get_bloginfo('name').' ('.$site_domain.')<br />';
	$email_body.='Plugin: '.$plugin.'<br />';

	//send email
	gmuj_sci_send_email($email_subject,$email_body);

}

//check for theme switch and send notification email
add_action( 'switch_theme', 'gmuj_sci_detect_switch_theme', 10, 1 );
function gmuj_sci_detect_switch_theme($theme) {

    //set email subject and body
		$email_subject='Mason Site Check-In: Theme Switched: '.$_SERVER['HTTP_HOST'];
		$email_body.='Status: Theme Switched<br />';
		$email_body.='Website: '.get_bloginfo('name').' ('.$_SERVER['HTTP_HOST'].')<br />';
		$email_body.='Theme: '.$theme.'<br />';
    
    //send email
    	gmuj_sci_send_email($email_subject,$email_body);

}

<?php

/**
 * Summary: php file which contains email-related functions
 */


/**
 * Filter the mail content type.
 */
function gmuj_sci_set_html_mail_content_type() {
    return 'text/html';
}

function gmuj_sci_send_email($email_subject, $email_body, $recipients=''){

	// Switch email to text/html - https://developer.wordpress.org/reference/hooks/wp_mail_content_type/#comment-777
		add_filter('wp_mail_content_type', 'gmuj_sci_set_html_mail_content_type');

	// Send email

		// Send to webmaster
			wp_mail('webmaster@gmu.edu', $email_subject, $email_body);

		// Should we send to all recipients listed in plugin settings?
			if ($recipients=='all') {
				// Get plugin options
					$gmuj_sci_options = get_option('gmuj_sci_options');				
				// Do we have any other email addresses specified in the plugin settings?
					if (!empty($gmuj_sci_options['gmuj_sci_settings_email'])) {
						// Separate addresses
							$addresses = explode(" ", $gmuj_sci_options['gmuj_sci_settings_email']);
						// Loop through email addresses
							foreach ($addresses as $address){
								// Send notification email
								wp_mail($address, $email_subject, $email_body);
							}
					}				
			}

	// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
		remove_filter('wp_mail_content_type', 'gmuj_sci_set_html_mail_content_type');

}

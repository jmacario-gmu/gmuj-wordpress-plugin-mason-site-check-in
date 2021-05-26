<?php

/**
 * Summary: php file which implements the HTML output
 */

/**
 * Outputs meta tags to web page HTML head section
 */
add_action('wp_head', 'gmuj_sci_html_output', 100); // Giving it a priority of 100 means it is typically called last in the wp_head action, so this output appears right before the closing head tag

function gmuj_sci_html_output() {

	// Prepare return variable
		$return_value='';

	// Begin output
		$return_value.=PHP_EOL;
		$return_value.="<!-- Mason Site Check-In Plugin Output -->".PHP_EOL;
		$return_value.="<!--".PHP_EOL;

	// Get summary info content
		$summary_info=gmuj_sci_get_summary_info();
	// fix summary info content for HTML output
		// Remove line breaks
			$summary_info=str_replace('<br />','',$summary_info);
		// Remove strong tags
			$summary_info=str_replace('<strong>','',$summary_info);
			$summary_info=str_replace('</strong>','',$summary_info);
	// Add summary info content to return value
		$return_value.=$summary_info;

	// Get detail info content
		$detail_info=gmuj_sci_get_detail_info();
	// fix summary info content for HTML output
		// Remove line breaks
			$detail_info=str_replace('<br />','',$detail_info);
		// Remove strong tags
			$detail_info=str_replace('<strong>','',$detail_info);
			$detail_info=str_replace('</strong>','',$detail_info);
	// Add summary info content to return value
		$return_value.=$detail_info;

	// Finish output
		$return_value.="-->".PHP_EOL;
		$return_value.="<!-- End Mason Site Check-In Plugin Output -->".PHP_EOL;
		$return_value.=PHP_EOL;

	// Echo return value
		echo $return_value;

}
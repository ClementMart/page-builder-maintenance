<?php
/*
Plugin Name: Page Builder Maintenance
Description: Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi etc)
Author: Clément MARTINEZ
Author URI: https://clementmartinez.fr/
Version: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
*** On WP init
**/
function cm_page_builder_maintenance_init() {
    if( !is_front_page() ):
	    if( !is_user_logged_in() ):
			if( $GLOBALS['pagenow'] != 'wp-login.php'  ):
		        wp_redirect(get_site_url(), 302);
				exit;
		    endif;
	    endif;
    endif;
}
add_action('template_redirect', 'cm_page_builder_maintenance_init');
<?php
/*
Plugin Name: Page Builder Maintenance
Description: Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi, Gutenberg etc)
Author: Clément MARTINEZ
Author URI: https://clementmartinez.fr/
Version: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
defined( 'ABSPATH' ) or die( '!' );

define( 'PAGE_BUILDER_MAINTENANCE_VERSION', '0.2' );

function cm_page_builder_maintenance_init() {
    if ( is_front_page() ) return;
    if ( is_user_logged_in() ) return;
    if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php' ) return;

    status_header( 503 );
    header( 'Retry-After: 3600' );

    wp_safe_redirect( get_site_url(), 302 );
    exit;
}
add_action( 'template_redirect', 'cm_page_builder_maintenance_init' );
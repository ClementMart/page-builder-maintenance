<?php
/*
Plugin Name: CM Page Builder Maintenance
Description: Maintenance plugin, but redirect to homepage, so page builders are compatible (Elementor, Beaver Builder, Divi, Gutenberg etc)
Author: Clément MARTINEZ
Author URI: https://clementmartinez.fr/
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
defined( 'ABSPATH' ) or die( '!' );

define( 'PAGE_BUILDER_MAINTENANCE_VERSION', '1.0' );

define( 'CM_PBM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

function cm_pbm_lang() {
    load_plugin_textdomain( 'cm-page-builder-maintenance', false, basename( CM_PBM_PLUGIN_DIR ) . '/languages' );
}
add_action( 'init', 'cm_pbm_lang' );

class CMPageBuilderMaintenanceSettings
{
    private $options;

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function add_plugin_page()
    {
        add_options_page(
            __( 'Page Builder Maintenance', 'cm-page-builder-maintenance' ),
            __( 'Page Builder Maintenance', 'cm-page-builder-maintenance' ),
            'manage_options',
            'cm-page-builder-maintenance',
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page()
    {
        $this->options = get_option( 'cm_pbm_option' );
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                settings_fields( 'cm_pbm_option_group' );
                do_settings_sections( 'cm-page-builder-maintenance' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {
        register_setting( 'cm_pbm_option_group', 'cm_pbm_option', array( $this, 'sanitize' ) );

        add_settings_section(
            'cm_pbm_setting_section',
            __( 'Maintenance Settings', 'cm-page-builder-maintenance' ),
            '__return_false',
            'cm-page-builder-maintenance'
        );

        add_settings_field(
            'cm_pbm_enabled',
            __( 'Maintenance mode', 'cm-page-builder-maintenance' ),
            array( $this, 'enabled_callback' ),
            'cm-page-builder-maintenance',
            'cm_pbm_setting_section'
        );

        add_settings_field(
            'cm_pbm_role',
            __( 'Minimum role to bypass', 'cm-page-builder-maintenance' ),
            array( $this, 'role_callback' ),
            'cm-page-builder-maintenance',
            'cm_pbm_setting_section'
        );

        add_settings_field(
            'cm_pbm_whitelist',
            __( 'Whitelisted URLs', 'cm-page-builder-maintenance' ),
            array( $this, 'whitelist_callback' ),
            'cm-page-builder-maintenance',
            'cm_pbm_setting_section'
        );

    }

    public function enabled_callback()
    {
        $checked = isset( $this->options['cm_pbm_enabled'] ) ? (bool) $this->options['cm_pbm_enabled'] : false;
        printf(
            '<input type="checkbox" id="cm_pbm_enabled" name="cm_pbm_option[cm_pbm_enabled]" value="1" %s /> <label for="cm_pbm_enabled">%s</label>',
            checked( $checked, true, false ),
            __( 'Enable maintenance mode', 'cm-page-builder-maintenance' )
        );
    }

    public function role_callback()
    {
        $selected = isset( $this->options['cm_pbm_role'] ) ? $this->options['cm_pbm_role'] : 'subscriber';
        $roles = array(
            'subscriber'    => __( 'Subscriber and above', 'cm-page-builder-maintenance' ),
            'contributor'   => __( 'Contributor and above', 'cm-page-builder-maintenance' ),
            'author'        => __( 'Author and above', 'cm-page-builder-maintenance' ),
            'editor'        => __( 'Editor and above', 'cm-page-builder-maintenance' ),
            'administrator' => __( 'Administrator only', 'cm-page-builder-maintenance' ),
        );
        foreach ( $roles as $value => $label ) {
            printf(
                '<input type="radio" name="cm_pbm_option[cm_pbm_role]" id="role_%1$s" value="%1$s" %2$s> <label for="role_%1$s">%3$s</label><br/>',
                esc_attr( $value ),
                checked( $selected, $value, false ),
                esc_html( $label )
            );
        }
    }

    public function whitelist_callback()
    {
        $value = isset( $this->options['cm_pbm_whitelist'] ) ? esc_textarea( $this->options['cm_pbm_whitelist'] ) : '';
        printf(
            '<textarea id="cm_pbm_whitelist" name="cm_pbm_option[cm_pbm_whitelist]" rows="5" class="large-text">%s</textarea>
            <p class="description">%s</p>',
            $value,
            __( 'One URL path per line. Example: /contact', 'cm-page-builder-maintenance' )
        );
    }

    public function sanitize( $input )
    {
        $new_input = array();
        $new_input['cm_pbm_enabled'] = isset( $input['cm_pbm_enabled'] ) ? '1' : '0';
        $allowed_roles = array( 'subscriber', 'contributor', 'author', 'editor', 'administrator' );
        $new_input['cm_pbm_role'] = isset( $input['cm_pbm_role'] ) && in_array( $input['cm_pbm_role'], $allowed_roles )
            ? $input['cm_pbm_role']
            : 'subscriber';
        $new_input['cm_pbm_whitelist'] = isset( $input['cm_pbm_whitelist'] )
            ? sanitize_textarea_field( $input['cm_pbm_whitelist'] )
            : '';
        return $new_input;
    }
}

if ( is_admin() ) {
    new CMPageBuilderMaintenanceSettings();
}


function cm_pbm_action_links( $links ) {
    $links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/options-general.php?page=cm-page-builder-maintenance' ) ) . '">' . __( 'Settings', 'cm-page-builder-maintenance' ) . '</a>'
    ), $links );
    return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cm_pbm_action_links' );


function cm_page_builder_maintenance_init() {

    $options = get_option( 'cm_pbm_option' );
    if ( empty( $options['cm_pbm_enabled'] ) ) return;

    if ( is_front_page() ) return;

    $whitelist = isset( $options['cm_pbm_whitelist'] ) ? $options['cm_pbm_whitelist'] : '';
    if ( ! empty( $whitelist ) ) {
        $paths = array_filter( array_map( 'trim', explode( "\n", $whitelist ) ) );
        $current_path = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
        foreach ( $paths as $path ) {
            if ( $current_path === $path ) return;
        }
    }
    
    if ( is_user_logged_in() ) {
        $role = isset( $options['cm_pbm_role'] ) ? $options['cm_pbm_role'] : 'subscriber';
        $roles_hierarchy = array( 'subscriber', 'contributor', 'author', 'editor', 'administrator' );
        $min_index = array_search( $role, $roles_hierarchy );
        foreach ( array_slice( $roles_hierarchy, $min_index ) as $r ) {
            if ( current_user_can( $r ) ) return;
        }
    }

    if ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php' ) return;

    status_header( 503 );
    header( 'Retry-After: 3600' );

    wp_safe_redirect( get_site_url(), 302 );
    exit;
}
add_action( 'template_redirect', 'cm_page_builder_maintenance_init' );
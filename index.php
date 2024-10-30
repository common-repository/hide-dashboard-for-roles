<?php
/*
    Plugin Name: Hide Dashboard for Roles
    Plugin URI: https://sagrev.solutions/product/dashboard-disable/
    Author: Sagrev Solutions
    Author URI: https://sagrev.solutions/
    Description: Disable access to the Admin Dashboard for certain roles
    Version: 1.0
    License: GPL2
    License URI: https://www.gnu.org/licenses/gpl-2.0.html
   */

add_action( 'admin_menu', 'sagrev_admin_hide_dashboard_roles_menu' );
function sagrev_admin_hide_dashboard_roles_menu() {
    if( current_user_can('administrator')) {
        add_menu_page('Hide Dashboard for Roles', 'Hide Dashboard', 'manage_options', 'sagrev-hide-dashboard-roles.php', 'sagrev_admin_hide_dashboard_roles', 'dashicons-admin-network', 6);
    }
}

function sagrev_admin_hide_dashboard_roles(){
    if( current_user_can('administrator')) {
        require 'admin/settings.php';
    }
}

add_action( 'plugin_action_links', 'sagrev_admin_hide_dashboard_roles_add_settings_link', 10, 2 );
function sagrev_admin_hide_dashboard_roles_add_settings_link( $links, $file ) {
    $settings_link = '<a href="/wp-admin/admin.php?page=sagrev-hide-dashboard-roles.php">Settings</a>';
    if ( plugin_basename( __FILE__ ) == $file ) {
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_action('admin_init', 'sagrev_admin_hide_dashboard_roles_disable_dashboard');
function sagrev_admin_hide_dashboard_roles_disable_dashboard() {
    $disabled_roles = get_option( 'sagrev_sol-roles_hidden_dashboard' );
    if (!is_user_logged_in()) {
        return null;
    }

    $user_meta = get_userdata(get_current_user_id());
    $user_roles = $user_meta->roles;
    $user_hide_dashboard = array_intersect($user_roles, $disabled_roles);

    if (count($user_hide_dashboard) > 0) {
        if (is_admin()) {
            wp_redirect(home_url());
            exit;
        }
    }

}

<?php
/**
 * Plugin Name:       Subscriptions Renewal Reminders 
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Renewal Reminders for Subscriptions automatically send your subscribers a courtesy reminder via email X days before their subscription renews. Shortcodes to be used for updating the subscriber's First and Last Names are {first_name} and {last_name} respectively.
 * Version:           1.1.7
 * Author:            StorePro
 * Author URI:        https://storepro.io/
 * Text Domain:       subscriptions-renewal-reminders
 */

/**
 * @Package  RenewalReminders
 */

/*
    Renewal Reminders for Subscriptions, automatically send your subscribers a courtesy reminder via email X days before their subscription renews.
    Copyright (C) 2022  StorePro  

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

// If this file is called firectly, abort!!!
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );

/**
 * Text domain key definition
 */
if (!defined('TEXT_DOMAIN_NAME')){
    define('TEXT_DOMAIN_NAME', "subscriptions-renewal-reminders");
}


/**
 * Check if WooCommerce is active. if it isn't, disable Renewal Reminders.
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if( !is_plugin_active( 'woocommerce/woocommerce.php') ){
    function sprr_is_woo_plugin_active() {
        ?>
        <div class="error notice">
            <p><?php esc_html_e( 'Subscriptions Renewal Reminders is inactive.WooCommerce plugin must be active for Renewal Reminders to work. Please install & activate WooCommerce »', 'renewal-reminders-sp' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'sprr_is_woo_plugin_active' );
    deactivate_plugins(plugin_basename(__FILE__));
    unset($_GET['activate']);
    return;
}

/**
 * Check if WooCommerce Subscriptions plugin is active. if it isn't, disable Renewal Reminders.
 */
if( !is_plugin_active( 'woocommerce-subscriptions/woocommerce-subscriptions.php') ){
    function sprr_is_subscription_plugin_active() {
        ?>
        <div class="error notice">
            <p><?php esc_html_e( 'Subscriptions Renewal Reminders is inactive. WooCommerce Subscriptions plugin must be active for Renewal Reminders to work. Please install & activate WooCommerce Subscriptions »', 'renewal-reminders-sp' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'sprr_is_subscription_plugin_active' );
    deactivate_plugins(plugin_basename(__FILE__));
    unset($_GET['activate']);
    return;
}

/**
 * Check if Renewal Reminders Pro plugin is active. if it isn't, enable Renewal Reminders.
 */

 if( is_plugin_active( 'subscriptions-renewal-reminders-premium/subscriptions-renewal-reminders.php') ){
    function sprr_is_pro_plugin_active() {
        ?>
        <div class="error notice">
            <p><?php esc_html_e( 'Subscriptions Renewal Reminders Pro is active. Please deactivate the Pro Plugin to activate the free version »', 'renewal-reminders-sp' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'sprr_is_pro_plugin_active' );
    deactivate_plugins(plugin_basename(__FILE__));
    unset($_GET['activate']);
    return;
}

 
/**
 * Woocommerce Version Check
 */
if(!function_exists('sprr_wc_version_check')){
    function sprr_wc_version_check( $version = '3.0' ) {
        if ( class_exists( 'WooCommerce' ) ) {
            global $woocommerce;
            if ( version_compare( $woocommerce->version, $version, ">=" ) ) {
                return true;
            }
        }
        return false;
    }
}


/**
 * Define plugin directory path
 */
if (!defined('SPRR_PLUGIN_DIR'))
define('SPRR_PLUGIN_DIR', plugin_dir_path( __FILE__ ));


/*
 * The code that runs during plugin activation
 */
function sprr_free_activate_storepro_plugin() {
	require_once SPRR_PLUGIN_DIR . 'inc/base/renewal-reminders-activate.php';
	SPRRActivate::sprr_activate();
    $time_value = stripslashes_deep(esc_attr( get_option( 'email_time' ) ));
    
        if ( ! wp_next_scheduled( 'renewal_reminders' ) ) {
        wp_schedule_event( strtotime($time_value), 'daily', 'renewal_reminders' );
    }else {
        wp_clear_scheduled_hook( 'renewal_reminders' );
        wp_schedule_event( strtotime($time_value), 'daily', 'renewal_reminders' );
    }
}
register_activation_hook( __FILE__, 'sprr_free_activate_storepro_plugin' );


/**
 * The code that runs during plugin deactivation
 */
function sprr_free_deactivate_storepro_plugin() {
	require_once SPRR_PLUGIN_DIR . 'inc/base/renewal-reminders-deactivate.php';
	SPRRDeactivate::sprr_deactivate();
    
	wp_clear_scheduled_hook( 'renewal_reminders' );
}
register_deactivation_hook( __FILE__, 'sprr_free_deactivate_storepro_plugin' );


/**
 * Initialize all the core classes of the plugin
*/
require_once SPRR_PLUGIN_DIR . '/inc/init.php';
if ( class_exists( 'SPRRInit' ) ) 
{
	SPRRInit::sprr_get_services();
}

/**
 * function to load data into database
 */

function renew_get_data_test() {

    require_once SPRR_PLUGIN_DIR . 'inc/base/renewal-reminders-table-operations.php';
   SPRRTableOperations::sprr_active_subscription_list();
   
}

add_action( 'wp_ajax_renew_get_data_test', 'renew_get_data_test' );
/**
 * function to update the database on change of subscription status
 */

function renew_sunscription_change_db_update() {

    require_once SPRR_PLUGIN_DIR . 'inc/base/renewal-reminders-table-operations.php';
    SPRRTableOperations::sprr_active_subscription_list();
}
add_action('woocommerce_subscription_status_updated','renew_sunscription_change_db_update');

/**
 * function to update scheduled event time
 */

function do_after_update_email_time() {

    $time_value = stripslashes_deep(esc_attr( get_option( 'email_time' )) );
   

    if ( ! wp_next_scheduled( 'renewal_reminders' ) ) {
        wp_schedule_event( strtotime($time_value), 'daily', 'renewal_reminders' );
    }else {
        wp_clear_scheduled_hook( 'renewal_reminders' );
        wp_schedule_event( strtotime($time_value), 'daily', 'renewal_reminders' );
    }
 
  }
add_action('update_option_email_time','do_after_update_email_time', 10, 2);

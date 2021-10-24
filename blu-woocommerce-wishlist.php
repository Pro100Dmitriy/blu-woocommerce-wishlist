<?php
/*
Plugin Name: Blueins Wishlist for Woocommerce
Plugin URI: https://blueins.by
Description: Плагин создания списка желаний.
Version: 1.0
Author: Dmitry Shestak
Author URI: #
License: GPLv2 or later
Text Domain: blu-woocommerce-wishlist
*/





/**
 * ************* Wordpress is worked! *************
 */

if( !defined('ABSPATH') ) die;

define("PLUGIN_NAME", 'blu-woocommerce-wishlist');





/********************  Register Activation Hook  **************************/
if( !function_exists('Blueins_Woo_Wishlist_Install') ){
    require_once 'blu-woo-register-plugin.php';
}

register_activation_hook( __FILE__, array( Blueins_Woo_Wishlist_Install(), 'init' ) );





/********************  Add JS and CSS files  **************************/

// For Admin Panel
function blu_woo_wishlist_scripts_admin(){
    // Register Scripts
    wp_register_script( 'blu_woo_wishlist_admin_script', plugins_url( '/assets/admin/blu_woo_wishlist_admin_script.js', __FILE__ ) );

    // Activation Scripts
    wp_enqueue_script( 'blu_woo_wishlist_admin_script' );
}
add_action( 'wp_footer', 'blu_woo_wishlist_scripts_admin' );



// For Single Page
function blu_woo_wishlist_scripts_public($arg){
    // Register Scripts
    wp_register_script( 'blu_woo_wishlist_public_single', plugins_url( '/assets/public/js/blu_woo_wishlist_public_single.js', __FILE__ ) );

    // Activation Scripts
    wp_enqueue_script( 'blu_woo_wishlist_public_single' );
    
    // Localizetion Scripts
    wp_localize_script( 'blu_woo_wishlist_public_single', 'type_page', array(
        'type' => $arg
    ) );
}



// For General Page
function blu_woo_wishlist_publick_add(){
    // Register Styles
    wp_register_style( 'blu_woo_wishlist_public_style', plugins_url( '/assets/public/css/public.css', __FILE__ ) );

    // Activation Styles
    wp_enqueue_style( 'blu_woo_wishlist_public_style' );

    //-----------------------------------------------------------------------------------------------------------------------------

    // Register Scripts
    wp_register_script( 'blu_woo_wishlist_function', plugins_url( '/assets/public/js/blu_woo_functions.js', __FILE__ ) );
    wp_register_script( 'blu_woo_wishlist_public_general', plugins_url( '/assets/public/js/blu_woo_wishlist_public_general.js', __FILE__ ) );
    wp_register_script( 'blu_woo_wishlist_page', plugins_url( '/assets/public/js/blu_woo_wishlist_page.js', __FILE__ ) );
    wp_register_script( 'blu_woo_notification', plugins_url( '/assets/public/js/blu_woo_notification.js', __FILE__ ) );

    // Activation Scripts
    wp_enqueue_script( 'blu_woo_notification' );
    wp_enqueue_script( 'blu_woo_wishlist_function' );
    wp_enqueue_script( 'blu_woo_wishlist_public_general' );
    wp_enqueue_script( 'blu_woo_wishlist_page' );
}
add_action( 'wp_footer', 'blu_woo_wishlist_publick_add' );





/********************  Create Menu Page (Admin Panel)  **************************/
add_action( 'admin_menu', 'blu_woo_option_page' );
add_action( 'admin_init', 'blu_woo_register_option' );

require_once 'blu-woo-plugin-admin-template.php';





/********************  Register Plugin Shortcode  **************************/
require_once 'blu-woo-wishlist-shortcodes.php';





/********************  Filters  **************************/
add_filter( 'the_content', 'blu_woo_wishlist_content' );

function blu_woo_wishlist_content($content){

    if( is_product() ){
        blu_woo_wishlist_scripts_public('product');
    }

    return $content;
}





/********************  Template For Wishlist Page  **************************/
add_filter( 'template_include', 'template_wishlist_page' );

function template_wishlist_page( $template ){
    if( is_page('wishlist') ){
        $theme_files = ['page-wishlist.php','bluwishlist/page-wishlist.php'];
        $exist = locate_template($theme_files, false);
        if( $exist != '' ){
            return $exist;
        } else {
            return wp_normalize_path( WP_PLUGIN_DIR ) . '/' . PLUGIN_NAME . '/template/page-wishlist.php';
        }
    }
    return $template;
}





/********************  Ajax  **************************/
require_once 'blu-woo-plugin-ajax.php';





/********************  Plugin Blueins Subscribers  **************************/
require_once 'blusubscrube/blusubscrube.php';





/********************  Plugin Blueins Fast View  **************************/
require_once 'blu_fastview/blu-fast-view.php';





/********************  Plugin Blueins Canvas Product  **************************/
require_once 'blu_canvasproduct/blu-canvas-product.php';





/********************  Customizer  **************************/
require_once 'blu-woo-customizer.php';
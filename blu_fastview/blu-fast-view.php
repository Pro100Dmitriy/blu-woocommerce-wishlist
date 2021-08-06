<?php



/********************  Register Widget  **************************/
add_action( 'widgets_init', 'blu_fast_view_register' );

function blu_fast_view_register(){
    //register_widget( 'Blu_Widget_Fast_View' );
}





/********************  Add JS and CSS files  **************************/

// For General Page
function blu_add_scripts_FV(){
    // Register Styles
    wp_register_style( 'blu_woo_fastview_style', plugins_url( 'assets/public/css/blu_woo_fastview.css', __FILE__ ) );

    // Activation Styles
    wp_enqueue_style( 'blu_woo_fastview_style' );

    //-----------------------------------------------------------------------------------------------------------------------------

    // Register Scripts
    wp_register_script( 'blu_woo_fastview', plugins_url( 'assets/public/js/blu_woo_fastview.js', __FILE__ ) );

    // Activation Scripts
    wp_enqueue_script( 'blu_woo_fastview' );
}
add_action( 'wp_footer', 'blu_add_scripts_FV' );





/********************  Ajax  **************************/
function blu_ajax_fastview(){

    $data = $_GET;

    $product_id = $data['product_id'];

    $product = wc_get_product( $product_id );

    if( $product->is_type('variable') ){
        $woo_product_variation = $product->get_available_variations();
    }

    print_r( $product );
    print_r( $woo_product_variation );

    $product_data = array(
        'title' => '',
        'price' => '',
        'description' => '',

    );

    die();
}
add_action( 'wp_ajax_blu_fastview', 'blu_ajax_fastview' );
add_action( 'wp_ajax_nopriv_blu_fastview', 'blu_ajax_fastview' );





/********************  WP Admin Menu Function  **************************/
add_action( 'admin_menu', 'blu_wp_admin_menu_FV' );

function blu_wp_admin_menu_FV(){
    add_submenu_page( 'blu_woocommerce_wishlist_options', 'Blueins Быстрый Просмотр', 'Быстрый просмотр', 'manage_options', 'blu_fastview', 'blu_fastview_page' );
    add_action( 'admin_enqueue_scripts', 'blu_admin_scripts_FV' );
}

function blu_admin_scripts_FV(){

}

function blu_fastview_page(){
    ?>
    <div class="wpar">
        <h2>Быстрый Просмотр Blueins</h2>
    </div>
   <?php 
}
<?php

/********************  Ajax View Fastview  **************************/
function blu_ajax_fastview(){

    $data = $_GET;
    print_r( $data );

    $product_id = $data['product_id'];

    $product = wc_get_product( $product_id );

    if( $product->is_type('variable') ){
        $woo_product_variation = $product->get_available_variations();
    }

    print_r( $product );
    print_r( $woo_product_variation );

    // $product_data = array(
    //     'title' => '',
    //     'price' => '',
    //     'description' => '',

    // );

    die();
}
add_action( 'wp_ajax_blu_fastview', 'blu_ajax_fastview' );
add_action( 'wp_ajax_nopriv_blu_fastview', 'blu_ajax_fastview' );
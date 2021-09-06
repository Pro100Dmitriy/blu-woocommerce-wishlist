<?php

if( ! class_exists( 'BluFastView' ) ){

    class BluFastView{

        function __construct(){
            // Register Widget
            add_action( 'widgets_init', array( $this, 'BFV_fast_view_register' ) );

            // Add JS and CSS files
            add_action( 'wp_footer', array( $this, 'BFV_add_scripts_FV' ) );

            // WP Admin Menu Function
            add_action( 'admin_menu', array( $this, 'BFV_wp_admin_menu_FV' ) );

            // Ajax View Fastview
            add_action( 'wp_ajax_BFV_fastview', array( $this, 'BFV_ajax_fastview' ) );
            add_action( 'wp_ajax_nopriv_BFV_fastview', array( $this, 'BFV_ajax_fastview' ) );
            
            // Summery
            add_action( 'bfv_product_summery', 'woocommerce_template_single_title', 5 );
            //add_action( 'bfv_product_summery', 'woocommerce_template_single_rating', 10 );
            add_action( 'bfv_product_summery', 'woocommerce_template_single_price', 15 );
            //add_action( 'bfv_product_summery', 'woocommerce_template_single_excerpt', 20 );
            add_action( 'bfv_product_summery', 'woocommerce_template_single_meta', 25 );
            add_action( 'bfv_product_summery', 'woocommerce_template_single_sharing', 30 );
            add_action( 'bfv_product_summery', 'woocommerce_template_single_add_to_cart', 35 );

        }

        /********************  Register Widget  **************************/
        function BFV_fast_view_register(){
            //register_widget( 'Blu_Widget_Fast_View' );
        }

        /********************  Add JS and CSS files  **************************/
        function BFV_add_scripts_FV(){
            // Register Styles
            wp_register_style( 'blu_woo_fastview_style', plugins_url( 'assets/public/css/blu_woo_fastview.css', __FILE__ ) );
        
            // Activation Styles
            wp_enqueue_style( 'blu_woo_fastview_style' );
        
            //-----------------------------------------------------------------------------------------------------------------------------
        
            // Register Scripts
            wp_register_script( 'blu_woo_fastview_functions', plugins_url( 'assets/public/js/blu_woo_fastview_functions.js', __FILE__ ) );
            wp_register_script( 'blueins-variation-class', plugins_url( 'assets/public/js/blueins-variation-class.js', __FILE__ ) );
            wp_register_script( 'blu_woo_fastview', plugins_url( 'assets/public/js/blu_woo_fastview.js', __FILE__ ) );
        
            // Activation Scripts
            wp_enqueue_script( 'blu_woo_fastview_functions' );
            wp_enqueue_script( 'blueins-variation-class' );
            wp_enqueue_script( 'blu_woo_fastview' );
        }

        /********************  WP Admin Menu Function  **************************/
        function BFV_wp_admin_menu_FV(){
            add_submenu_page( 'blu_woocommerce_wishlist_options', 'Blueins Быстрый Просмотр', 'Быстрый просмотр', 'manage_options', 'blu_fastview', array( $this, 'BFV_fastview_page') );
            add_action( 'admin_enqueue_scripts', array( $this, 'BFV_admin_scripts_FV') );
        }
        
        function BFV_admin_scripts_FV(){
        }
        
        function BFV_fastview_page(){
            ?>
            <div class="wpar">
                <h2>Быстрый Просмотр Blueins</h2>
            </div>
           <?php 
        }

        /********************  Ajax View Fastview  **************************/
        function BFV_ajax_fastview(){
            $data = $_GET;
            global $post, $product;
            $product_id = $data['product_id'];
            $product = wc_get_product( $product_id );

            $thumb_ids = array();

            if ( $product->get_gallery_image_ids() ) {
                $thumb_ids = $product->get_gallery_image_ids();
            }

            if(  $product->is_type( 'variable' ) ){
                $variations = $product->get_available_variations();
                $variations_IMG = array();
            
                foreach($variations as $variant){
                    $var = array(
                        'id' => $variant['attributes']['attribute_czvet'] ?? $variant['attributes']['attribute_pa_czvet'],
                        'src' => $variant['image']['src'],
                        'data-src' => $variant['image']['url'],
                        'data-large_image' => $variant['image']['full_src'],
                        'srcset' => $variant['image']['srcset'],
                        'price_html' => $variant['price_html'],
                        'availability_html' => $variant['availability_html'],
                        'variaction_id' => $variant['variation_id'],
                        //'data-thumb' => $variant['image']['gallery_thumbnail_src'],
                    );
                    array_push( $variations_IMG, $var );
                }

                //print_r( $variations[0] );

            }

            if( $product ){
                $post = get_post( $product_id );
                setup_postdata( $post );
                ?>
                    <div class="fastview__container__slider">
                        <div class="fastview__src_variation">
                            <?php
                            foreach( $variations_IMG as $data ){
                                ?>
                                <span   data-id="<?php echo $data['id']; ?>" 
                                        src="<?php echo $data['src'] ?>" 
                                        data-src="<?php echo $data['data-src'] ?>" 
                                        data-large_image="<?php echo $data['data-large_image'] ?>" 
                                        srcset="<?php echo $data['srcset'] ?>"
                                        price_html='<?php echo $data['price_html'] ?>'
                                        availability_html='<?php echo $data['availability_html'] ?>'
                                        variaction_id='<?php echo $data['variaction_id'] ?>'></span>
                                <?php
                            }
                            ?>
                        </div>
                        <div class="fastview-slick-list">
                            <?php

                                if ( ! empty( $thumb_ids ) ) {
                                    foreach ( $thumb_ids as $thumb_id ) {
                                        echo '<div class="slick-item">' . wp_get_attachment_image( $thumb_id, 'full' ) . '</div>';
                                    }
                                }

                            ?>
                        </div>
                    </div>
                    <div class="fastview__container__content">
                        <div class="close-fastview-button-cover">
                            <button id="close-fastview-menu-button" class="close-button">Close Fullscreen Menu</button>
                        </div>
                        <?php do_action( 'bfv_product_summery', $product ) ?>
                    </div>
                <?php
                wp_reset_postdata();
            }
        
            die();
        }
        
    }

    new BluFastView();

}
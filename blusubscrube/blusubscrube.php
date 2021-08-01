<?php

require_once dirname(__FILE__) . '/blu_widget_class.php';
require_once dirname(__FILE__) . '/blu_helpers.php';






/********************  Register Widget  **************************/
add_action( 'widgets_init', 'blu_register_widget' );

function blu_register_widget(){
    register_widget( 'Blu_Widget_Subscribers' );
}





/********************  Add JS and CSS files  **************************/
add_action('wp_footer', 'blu_add_scripts');

function blu_add_scripts(){
    wp_register_script('blu_common_script', plugins_url( 'assets/common/blu_common.js', __FILE__ ));
    wp_register_script( 'blu_subscribe_script', plugins_url('/assets/public/blu_subscribe.js', __FILE__) );

    wp_register_style( 'blu_subscribe_style',plugins_url('/assets/public/blu_subscribe.css', __FILE__) );

    wp_enqueue_script('blu_common_script');
    wp_enqueue_script('blu_subscribe_script');
    wp_enqueue_style('blu_subscribe_style');

    wp_localize_script( 'blu_subscribe_script', 'bluajax', array( 'url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce('bluajax') ) );
}





/********************  Register Shortcode  **************************/
add_shortcode( 'blueins_subscribe_form', 'fun_blueins_subscribe_form' );

function fun_blueins_subscribe_form($atts){
    if( empty($atts) ){
        $text = 'Email';
        $button = 'Кнопка';
    }else{
        extract($atts);
    }
    ?>
        <form id="blu_widget__subscribeForm" class="form" action="" method="POST">
            <p class="form__input-item el-input">
                <label id="blu_widget-email-label" class="el-input__label" for="bluemail"><?php echo $text; ?></label>
                <input id="blu_widget-email" class="el-input__field" type="text" placeholder="" name="bluemail" required>
            </p>
            <button type="submit" class="form__button"><?php echo $button; ?></button>
        </form>
        <div id="blu_widget_messege" class="form__messege_text"></div>
    <?php
}





/********************  Ajax Function  **************************/
add_action( 'wp_ajax_blu_subscriber', 'blu_ajax_subscriber' );
add_action( 'wp_ajax_nopriv_blu_subscriber', 'blu_ajax_subscriber' );

function blu_ajax_subscriber(){
    if( !wp_verify_nonce($_GET['security'], 'bluajax') ){
        die( 'Error security!' );
    }
    $request_data = $_GET;

    if( empty($request_data['formData']) ){
        exit('Заполните поля');
    }

    if( !is_email($request_data['formData']) ){
        exit('It\'s not email!');
    }

    global $wpdb;
    if( $wpdb->get_var( $wpdb->prepare("SELECT subscriber_id FROM blu_subsciption_email WHERE subscriber_email = %s", $request_data['formData']) ) ){
        echo '<p class="blu-form-red">Вы уже подписаны</p>';
    }else{
        if($wpdb->query( $wpdb->prepare("INSERT INTO blu_subsciption_email (subscriber_email) VALUES (%s)", $request_data['formData']) )){
            echo '<p class="blu-form-green">Подписка оформлена</p>';
        }else{
            echo '<p class="blu-form-yellow">Произошла ошибка, попробуйте позже</p>';
        }
    }

    die();
}





/********************  WP Admin Menu Function  **************************/
add_action( 'admin_menu', 'blu_wp_admin_menu' );

function blu_wp_admin_menu(){
    //add_options_page( 'Подписчики', 'Подписчики', 'manage_options', 'blu_subscriber', 'blu_subscriber_page' );
    add_submenu_page( 'blu_woocommerce_wishlist_options', 'Blueins подписчики', 'Подписчики', 'manage_options', 'blu_subscriber', 'blu_subscriber_page' );
    add_action( 'admin_enqueue_scripts', 'blu_admin_scripts' );
}

function blu_admin_scripts($hook){
    if( !$hook = 'settings_page_blu_subscriber' ) return;
    wp_enqueue_style('blu_admin_style', plugins_url( 'assets/admin/blu_admin_subscribe.css', __FILE__ ));
    wp_enqueue_script('blu_common_script', plugins_url( 'assets/common/blu_common.js', __FILE__ ), array(), NULL, true);
    wp_enqueue_script('blu_admin_script', plugins_url( 'assets/admin/blu_admin_subscribe.js', __FILE__ ), array(), NULL, true);
}

function blu_subscriber_page(){
   ?>
    <div class="wpar">
        <h2>Подписчики Blueins</h2>
        <?php $paginations_params = pagination_params(); // Exported function from --> "blu_helpers.php"?> 
        <?php $subscribers = get_subscribers(); // Exported function from --> "blu_helpers.php"?> 
        <?php if($subscribers) : ?>
            <p>Кол-во подписчиков <?php echo $paginations_params['count'] ?>:</p>

            <table class="wp_list_table widefat fixed posts">
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Email</td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($subscribers as $subscriber): ?>
                    <tr>
                        <td><?php echo $subscriber['subscriber_id']; ?></td>
                        <td><?php echo $subscriber['subscriber_email']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if( $paginations_params['count_pages'] > 1 ) : ?>
                <div class="pagination">
                    <?php echo pagination($paginations_params['page'], $paginations_params['count_pages']); ?>
                </div>
            <?php endif; ?>
            <!-- Pagination -->

            <p>
                <label for="blu_text">Текст рассылки (для email используйте %email%)</label>
                <textarea name="blu_text" id="blu_text" cols="30" rows="10" class="widefat blu_text"></textarea>
            </p>
            <div id="messege_div">
                
            </div>
            <button class="btn" id="blueins_button_admin">Сделать рассылку</button>
        <?php else : ?>
            <p>Подписчиков нет :(</p>
        <?php endif; ?>
    </div>
   <?php 
}





/********************  WP Admin Menu Function  **************************/
add_action( 'wp_ajax_blu_subscriber_admin', 'blu_ajax_subscriber_admin' );

function blu_ajax_subscriber_admin(){
    if( empty($_GET['formData']) ){
        echo "Заполните письио";
    }
    $subscribers = get_subscribers(true);
    $i = 0;
    foreach($subscribers as $person){
        $data = nl2br( $_GET['formData'] );
        echo $person['subscriber_email'];
        if( wp_mail( $person['subscriber_email'], 'Новые продукты', $data ) ){
            $i++;
        }
    }
    die('Разослано' . $i);
}
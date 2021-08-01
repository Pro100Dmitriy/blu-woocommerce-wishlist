<?php

//delete_option();

function blu_woo_option_page(){
    add_menu_page( 'Настройки списка желаний', 'Blueins', 'manage_options', 'blu_woocommerce_wishlist_options', 'blu_woo_wishlist_page', plugins_url('icon.png', __FILE__), 58 );
}


function blu_woo_register_option(){
    register_setting( 'blu_theme_options_group', 'blu_theme_options', 'blu_theme_options_sanitize' );

    // First Section
    add_settings_section( 'blu_theme_options_notifications', 'Настройка уведомлений.', '', 'blu_woocommerce_wishlist_options' );

    add_settings_field( 'blu_theme_options_notifications__seved', 'Успешное сохранение', 'blu_notification_seved_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_notifications', array('label_for' => 'blu_theme_options_notifications__seccess') );
    add_settings_field( 'blu_theme_options_notifications__remove', 'Успешное удаление', 'blu_notification_remove_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_notifications', array('label_for' => 'blu_theme_options_notifications__remove') );
    add_settings_field( 'blu_theme_options_notifications__add_to_cart', 'Успешное добавление в корзину', 'blu_notification_add_to_cart_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_notifications', array('label_for' => 'blu_theme_options_notifications__add_to_cart') );
    add_settings_field( 'blu_theme_options_notifications__err_seved', 'Ошибка при сохранении', 'blu_notification_err_seved_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_notifications', array('label_for' => 'blu_theme_options_notifications__err_seved') );
    add_settings_field( 'blu_theme_options_notifications__err_remove', 'Ошибка при удалении', 'blu_notification_err_remove_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_notifications', array('label_for' => 'blu_theme_options_notifications__err_remove') );
    add_settings_field( 'blu_theme_options_notifications__err_add_to_cart', 'Ошибка при добавлении в корзину', 'blu_notification_err_add_to_cart_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_notifications', array('label_for' => 'blu_theme_options_notifications__err_add_to_cart') );

    // Second Section
    add_settings_section( 'blu_theme_options_inputs', 'Настройки текстовых полей', '', 'blu_woocommerce_wishlist_options' );

    add_settings_field( 'blu_theme_options_body', 'Цвет фона', 'blu_tneme_body_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_inputs', array('label_for' => 'blu_theme_options_body') );
    add_settings_field( 'blu_theme_options_header', 'Цвет header', 'blu_tneme_header_html', 'blu_woocommerce_wishlist_options', 'blu_theme_options_inputs', array('label_for' => 'blu_theme_options_header') );
}


function blu_notification_seved_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_notifications__seved"></label>
            <input type="text" name="blu_theme_options[blu_theme_options_notifications__seved]" id="blu_theme_options_notifications__seved" value="<?php echo esc_attr( $options['blu_theme_options_notifications__seved'] ); ?>" class="regular-text">
        </p>
    <?php
}
function blu_notification_remove_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_notifications__remove"></label>
            <input type="text" name="blu_theme_options[blu_theme_options_notifications__remove]" id="blu_theme_options_notifications__remove" value="<?php echo esc_attr( $options['blu_theme_options_notifications__remove'] ); ?>" class="regular-text">
        </p>
    <?php
}
function blu_notification_add_to_cart_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_notifications__add_to_cart"></label>
            <input type="text" name="blu_theme_options[blu_theme_options_notifications__add_to_cart]" id="blu_theme_options_notifications__add_to_cart" value="<?php echo esc_attr( $options['blu_theme_options_notifications__add_to_cart'] ); ?>" class="regular-text">
        </p>
    <?php
}
function blu_notification_err_seved_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_notifications__err_seved"></label>
            <input type="text" name="blu_theme_options[blu_theme_options_notifications__err_seved]" id="blu_theme_options_notifications__err_seved" value="<?php echo esc_attr( $options['blu_theme_options_notifications__err_seved'] ); ?>" class="regular-text">
        </p>
    <?php
}
function blu_notification_err_remove_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_notifications__err_remove"></label>
            <input type="text" name="blu_theme_options[blu_theme_options_notifications__err_remove]" id="blu_theme_options_notifications__err_remove" value="<?php echo esc_attr( $options['blu_theme_options_notifications__err_remove'] ); ?>" class="regular-text">
        </p>
    <?php
}
function blu_notification_err_add_to_cart_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_notifications__err_add_to_cart"></label>
            <input type="text" name="blu_theme_options[blu_theme_options_notifications__err_add_to_cart]" id="blu_theme_options_notifications__err_add_to_cart" value="<?php echo esc_attr( $options['blu_theme_options_notifications__err_add_to_cart'] ); ?>" class="regular-text">
        </p>
    <?php
}


function blu_tneme_body_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_body">Тестовая настройка123123</label>
            <input type="text" name="blu_theme_options[blu_theme_options_body]" id="blu_theme_options_body" value="<?php echo esc_attr( $options['blu_theme_options_body'] ); ?>" class="regular-text">
        </p>
    <?php
}


function blu_tneme_header_html(){
    $options = get_option( 'blu_theme_options' )
    ?>
        <p>
            <label for="blu_theme_options_header">Тестовая настройка2123123</label>
            <input type="text" name="blu_theme_options[blu_theme_options_header]" id="blu_theme_options_header" value="<?php echo esc_attr( $options['blu_theme_options_header'] ); ?>" class="regular-text">
        </p>
    <?php
}


function blu_theme_options_sanitize( $options ){
    $clean_options = array();
    foreach( $options as $key => $value ){
        $clean_options[$key] = strip_tags($value);
    }
    return $clean_options;
}


function blu_woo_wishlist_page(){
    $options = get_option( 'blu_theme_options' );
    ?>
    <div class="wrap">
        <h2>Настройки для интернет магазина Blueins</h2>
        <form action="options.php" method="post">
            <?php settings_fields( 'blu_theme_options_group' ); ?>
            <?php //wp_nonce_field( 'blu_woocommerce_wishlist_options' ); ?>
            <?php do_settings_sections( 'blu_woocommerce_wishlist_options' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


function blu_woocommerce_wishlist_suscribers(){
    
}
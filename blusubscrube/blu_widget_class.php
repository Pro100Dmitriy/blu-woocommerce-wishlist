<?php

class Blu_Widget_Subscribers extends WP_Widget{

    function __construct(){
        $args = array(
            'name' => 'Виджет подписки',
            'description' => 'Сохраняет email подписчиков',
            'classname' => 'blu-subscriber'
        );

        parent::__construct( 'blu_subscriber', '', $args );
    }

    // View in user site
    function widget($args, $instance){
        //add_action('wp_footer', 'blu_add_scripts');
        extract($args);
        extract($instance);
        $title = apply_filters('widget_title', $title);

        echo $before_widget;
        echo $before_title . $title . $after_title;
        ?>
        <form id="blu_widget__subscribeForm" class="form" action="" method="POST">
            <div class="form__input-item">
                <label id="blu_widget-email-label" class="form__input-item__label" for="bluemail">Email</label>
                <input id="blu_widget-email" class="form__input-item__email" type="text" placeholder="" required name="bluemail">
            </div>
            <button type="submit" class="form__button">Подписаться</button>
        </form>
        <?php
    }

    // View in admin panel
    function form($instance){
        extract($instance);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Заголовок</label>
            <input id="<?php echo $this->get_field_id('title'); ?>" class="widefat" value="<?php if( isset($title) ) echo esc_attr($title); ?>" name="<?php echo $this->get_field_name('title'); ?>">
        </p>
        <?php
    }

}
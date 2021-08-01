<?php

if( !class_exists('Blueins_Wishlist_Shortcodes') ){

class Blueins_Wishlist_Shortcodes{

    public $data;
    
    protected static $instance;

    public static function get_instance($obj){
        if( is_null( self::$instance ) ){
            self::$instance = new self($obj);
        }
        return self::$instance;
    }

    public function __construct($obj){
        if( !is_null( $obj ) ){
            $this->data = $obj;
        }
        add_shortcode( 'blu_woo_wishlist', array($this,'single_add_wishlist') );
        add_shortcode( 'blu_woo_wishlist_public', array($this,'public_add_wishlist') );
        add_shortcode( 'blu_woo_wishlist_page', array($this,'wishlist_page_link') );
        add_shortcode( 'blu_woo_get_wishlist', array($this, 'views_wishlist') );
        add_shortcode( 'blu_woo_get_notification', array($this, 'notification_wishlist') );
    }

    public function single_add_wishlist($atts){
        global $product;
        $id = $product->id;
        ?>
        <span class="details-like-button blu-add-ajax-wishlist" data-product-id="<?php echo $id; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33"><defs><style>.a{fill:none;}</style></defs><g transform="translate(-1347 -6)"><g transform="translate(1355.105 14.684)"><path d="M8.323,43.324c-.351-.3-.529-.476-2.4-2.07C2.355,38.223,0,36.221,0,33.3a4.537,4.537,0,0,1,4.421-4.763,4.4,4.4,0,0,1,3.9,2.634,4.4,4.4,0,0,1,3.9-2.634A4.537,4.537,0,0,1,16.645,33.3c0,2.923-2.355,4.926-5.919,7.957C8.843,42.855,8.689,43.007,8.323,43.324Zm-3.9-13.815A3.549,3.549,0,0,0,.975,33.3c0,2.473,2.218,4.359,5.576,7.214.569.484,1.154.982,1.772,1.518.617-.536,1.2-1.034,1.771-1.518,3.358-2.855,5.576-4.742,5.576-7.214a3.549,3.549,0,0,0-3.446-3.788c-1.774,0-3.024,1.59-3.431,3.056v0l-.939,0h0C7.493,31.271,6.326,29.509,4.421,29.509Z" transform="translate(0 -28.534)"/></g><rect class="a" width="33" height="33" transform="translate(1347 6)"/></g></svg>
        </span>
        <?php
    }

    public function public_add_wishlist($atts){
        ?>
        <button class="<?php echo $atts['class'] ?> blu__like-icon blu-add-public-ajax-wishlist" data-product-id="<?php echo $atts['id'] ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33"><g transform="translate(8.105 8.684)"><path d="M8.323,43.324a23.174,23.174,0,0,1-2.4-2.07C2.355,38.223,0,36.221,0,33.3a4.537,4.537,0,0,1,4.421-4.763c2.815,0,3.722,2.634,3.9,2.634s1.007-2.634,3.9-2.634A4.537,4.537,0,0,1,16.645,33.3c0,2.923-2.355,4.926-5.919,7.957A23.087,23.087,0,0,1,8.323,43.324Zm-3.9-13.815A3.549,3.549,0,0,0,.975,33.3c0,2.473,2.218,4.359,5.576,7.214C7.12,41,8.1,42.029,8.323,42.029s1.2-1.034,1.771-1.518c3.358-2.855,5.576-4.742,5.576-7.214a3.549,3.549,0,0,0-3.446-3.788c-1.774,0-3.024,1.59-3.431,3.056v0l-.939,0h0C7.493,31.271,6.326,29.509,4.421,29.509Z" transform="translate(0 -28.534)" fill="#fff"/></g><rect width="33" height="33" fill="none"/></svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 33 33"><defs><style>.cls-1{fill:#fff;}.cls-2{fill:none;}</style></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M16.43,23.47A21.47,21.47,0,0,1,14,21.4c-3.57-3-5.93-5-5.93-7.95a4.55,4.55,0,0,1,4.31-4.76h.12c2.81,0,3.72,2.63,3.9,2.63s1-2.63,3.9-2.63a4.53,4.53,0,0,1,4.42,4.64v.12c0,2.92-2.35,4.93-5.92,8A22.47,22.47,0,0,1,16.43,23.47Z"/><rect class="cls-2" width="33" height="33"/></g></g></svg>
        </button>
        <?php
    }

    public function wishlist_page_link($atts){
        $page_url = get_page_by_path('wishlist');
        ?>
        <a href="<?php echo $page_url->guid; ?>" class="like-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" viewBox="0 0 45 45"><g transform="translate(11 -16.534)"><g transform="translate(0 28.534)"><path d="M11.295,48.606c-.476-.412-.718-.646-3.262-2.809C3.2,41.683,0,38.966,0,35a6.158,6.158,0,0,1,6-6.464,5.975,5.975,0,0,1,5.294,3.574,5.975,5.975,0,0,1,5.294-3.574,6.158,6.158,0,0,1,6,6.464c0,3.967-3.2,6.685-8.033,10.8C12,47.97,11.792,48.175,11.295,48.606ZM6,29.858c-2.666,0-4.677,2.21-4.677,5.141,0,3.356,3.01,5.915,7.567,9.79.772.657,1.567,1.332,2.4,2.06.837-.728,1.632-1.4,2.4-2.06,4.557-3.875,7.567-6.435,7.567-9.79,0-2.931-2.011-5.141-4.677-5.141-2.407,0-4.1,2.158-4.657,4.147v0l-1.275,0h0C10.169,32.248,8.585,29.858,6,29.858Z" transform="translate(0 -28.534)" fill="#fff"/></g></g><rect width="45" height="45" fill="none"/></svg>
        </a>
        <?php
    }

    public function views_wishlist($atts){
        global $wpdb;
        $current_user = get_current_user_id();
        $likes = $wpdb->get_results("SELECT * FROM `blu_woocommerce_wishlist` WHERE `user_id` = {$current_user}");
        $likes_array = unserialize( $likes[0]->product );
        //print_r($likes);

        if( !empty($likes_array) ):
        ?>
            <ul class="like__products__list">
                <?php
                foreach( $likes_array as $like_key => $like_item ):
                    $product = wc_get_product($like_item['identical']['product_id']);
                    $product_id = $like_item['identical']['product_id'];
                    preg_match("/.+\s/", $like_item['identical']['variation']['attribute_czvet'], $color);
                    preg_match("/.+\s/", $like_item['identical']['variation']['attribute_razmer'], $razmer);
                ?>
                <li class="like__products__list__item">
                    <div class="like__products__list__item__img">
                        <button data-arr-id="<?php echo $like_key; ?>" class="close-button delete_from_wishlist"></button>
                        <div class="like__products__img__cover">
                            <img src="<?php echo $like_item['deferent']['img_url']; ?>" all="Product Alt">
                        </div>
                        <div class="like__products__title">
                            <p class="like-title"><?php echo $product->name; ?></p>
                            <?php if( isset($color[0]) ): ?><p class="like-color">Цвет:<span><?php echo $color[0]; ?></span></p><?php endif; ?>
                            <?php if( isset($razmer[0]) ): ?><p class="like-color">Размер:<span><?php echo $razmer[0]; ?></span></p><?php endif; ?>
                        </div>
                    </div>
                    <div class="like__products__list__item__price">
                        <p class="like-price"><?php echo $like_item['deferent']['currency'] . ' ' . $like_item['deferent']['price']; ?></p>
                    </div>
                    <div class="like__products__list__item__add-to-cart">
                        <?php
                            date_default_timezone_set('Europe/Minsk');
                        ?>
                        <p class="like-date">Добавлен: <?php echo date("F j, Y", $like_item['deferent']['dataadded'] ); ?></p>
                        <!--button data-arr-id="<?php //echo $like_key; ?>" class="el-form__button add_to_cart_from_wishlist"><span>Добавить</span> <i>в</i> корзину</button-->
                        <a href="<?php echo $like_item['deferent']['page_url'] ?>" class="el-form__button"><span>Добавить</span> <i>в</i> корзину</a>
                        <a href="#" data-arr-id="<?php echo $like_key; ?>" class="stroke-button-dark delete-but delete_from_wishlist_big">Удалить</a>
                    </div>
                </li>
                <?php

                endforeach;
                ?>
            </ul>
        <?php
        else:
        ?>
            <div class="empty-likes">
                <img src="<?php echo get_theme_mod('wishlist-empty-start-img-upload'); ?>">
            </div>
        <?php
        endif;
    }

    public function notification_wishlist($atts){
        ?>
        <div id="blueins__notification-block" class="blueins-notification-block">
            <ul class="blueins-notification-block__list">
                
            </ul>
        </div>
        <?php
    }

}

function Blueins_Wishlist_Shortcodes($obj = NULL){
    return Blueins_Wishlist_Shortcodes::get_instance($obj);
}

Blueins_Wishlist_Shortcodes();

}
<?php
/**
 * 
 * Class "Blueins_Woo_Wishlist_Install" for register plugin, when plugin activating.
 * 
 */


$page_id = 0;

if( !class_exists('Blueins_Woo_Wishlist_Install') ){
// ******************************** "Blueins_Woo_Wishlist_Install" class not exists

class Blueins_Woo_Wishlist_Install{

    protected static $instance;

    public static function get_instance(){
        if( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct(){
    }

    public function init(){
        $this->create_wishlist_db();
        $this->create_wishlist_page();
        $this->create_subscribe_db();
    }

    private function create_wishlist_db(){
        global $wpdb;

        // $sql = "CREATE TABLE IF NOT EXISTS `blu_woocommerce_wishlist` ( 
        //     `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
        //     `prod_id` BIGINT(20) NOT NULL, 
        //     `quantity` INT(11) NOT NULL, 
        //     `user_id` BIGINT(20) NULL DEFAULT NULL,
        //     `img_url` TEXT(100) NOT NULL,
        //     `cvet`  VARCHAR(20) NULL DEFAULT NULL,
        //     `razmer` VARCHAR(20) NULL DEFAULT NULL,
        //     `url_page` TEXT(20) NULL DEFAULT NULL,
        //     `original_price` DECIMAL(9,3) NULL DEFAULT NULL, 
        //     `original_currency` CHAR(3) NULL DEFAULT NULL, 
        //     `dateadded` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
        //     `on_sale` TINYINT NOT NULL,
        //     PRIMARY KEY (`ID`)
        // )ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $sql = "CREATE TABLE IF NOT EXISTS `blu_woocommerce_wishlist` ( 
            `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
            `user_id` BIGINT(20) NULL DEFAULT NULL,
            `product` TEXT(2000) NOT NULL,
            PRIMARY KEY (`ID`)
        )ENGINE = InnoDB DEFAULT CHARSET=utf8;";

        $wpdb->query( $sql );
    }

    private function create_wishlist_page(){
        wc_create_page( 'wishlist', 'blueins_wishlist_page_id', 'Список желаний', 'Страница используется плагином!!' );
    }

    private function create_subscribe_db(){
        global $wpdb;
        $sql = "CREATE TABLE IF NOT EXISTS `blu_subsciption_email` (
            `subscriber_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
            `subscriber_email` varchar(50) NOT NULL,
            PRIMARY KEY (`subscriber_id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
        $wpdb->query( $sql );
    }
}

function Blueins_Woo_Wishlist_Install(){
    return Blueins_Woo_Wishlist_Install::get_instance();
}

// ******************************** "Blueins_Woo_Wishlist_Install" class not exists
}


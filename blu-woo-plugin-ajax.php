<?php

/********************  Ajax Add To Wishlist  **************************/
function blu_woo_add_to_wishlist(){

    // Cheak User Authorization
    $user_id = get_current_user_id();                                                                               // User ID
    if( $user_id == 0 ){ 
        ?>
            Пожалуйста <a href="<?php echo get_page_link(23); ?>">войдите</a> или <a href="<?php echo get_page_link(23); ?>">зарегестрируйтесь!</a>
        <?php 
        die; 
    }

    $data = $_GET;

    $product_id = $data['product_id'];                                                                              // Product ID
    $variation_id = $data['variation_id'];                                                                          // Variation ID
    $quantity = $data['quanity'];                                                                                   // Quantity
    $variation = array(
		'attribute_czvet' => $data['color'] ? str_replace( ' ', ' #', $data['color'] ) : false,                     // Color
		'attribute_razmer' => $data['razmer'] ? str_replace( ' ', ' #', $data['razmer'] ) : false                   // Razmer
	);
    $page_url = get_permalink( $product_id );                                                                       // Page URL
    $currency = get_option('woocommerce_currency');                                                                 // Currency


    // Get Array Active Variation
    $product = wc_get_product($product_id);

    if( $product->is_type('variable') )
    {
        $woo_product_variation = $product->get_available_variations();
        $active_variation = false;
        foreach( $woo_product_variation as $variant ){
            if( $variant['attributes']['attribute_czvet'] == $variation['attribute_czvet'] ){
                $active_variation = $variant;
            }
        }
    }
    else
    {

    }
    // Output $active_variation <--- Current variation


    $price = $active_variation != false ? $active_variation['display_price'] : $product->price;                     // Price        
    $img_url =  $active_variation != false ? 
                $active_variation['image']['gallery_thumbnail_src'] :
                wp_get_attachment_image_url( $product->get_image_id(), 'full' );                                    // Producy URL


    $product_to_add = array(
        'identical'         => array(
            'product_id'        => $product_id,
            'variation_id'      => $variation_id,
            'variation'         => $variation
        ),
        'deferent'          => array(
            'quantity'          => $quantity,
            'price'             => $price,
            'currency'          => $currency,
            'dataadded'         => time(),
            'page_url'          => $page_url,
			'img_url'           => $img_url
        )
    );

    /*********** Add To Database **********/
    global $wpdb;

    $db_likes = $wpdb->get_results("SELECT * FROM `blu_woocommerce_wishlist` WHERE `user_id` = {$user_id}");
    if( $db_likes ){
        // Get Product From DataBase
        $products_from_db = unserialize( $db_likes[0]->product );
        $counter = 0;

        foreach( $products_from_db as &$product ){
            if( $product['identical'] === $product_to_add['identical'] ){
                $product['deferent']['quantity'] = $product['deferent']['quantity'] + $product_to_add['deferent']['quantity'];
                $counter++;
            }
        }

        if( $counter == 0 ){
            array_push($products_from_db, $product_to_add);
        }

        array_unique( $products_from_db );

        $result = $wpdb->update('blu_woocommerce_wishlist', [ 'product' => serialize($products_from_db) ], ['user_id' => $user_id] );
    }else{
        $result = $wpdb->insert( 'blu_woocommerce_wishlist', array(
            'user_id'   => $user_id,
            'product'   => serialize( array($product_to_add) )
        ) );
    }


    // $db_likes = $wpdb->get_results("SELECT * FROM `blu_woocommerce_wishlist` WHERE `prod_id` = {$product_id} AND `user_id` = {$user_id} AND `cvet` = '{$color}' AND `razmer` = '{$razmer}'");

    // if( $db_likes ){
    //     $quant = $db_likes[0]->quantity + $quantity;
    //     $result = $wpdb->update('blu_woocommerce_wishlist', [ 'quantity' => $quant ], ['prod_id' => $product_id, 'user_id' => $user_id, 'cvet' => $color, 'razmer' => $razmer] );
    // }else {
    //     $result = $wpdb->insert( 'blu_woocommerce_wishlist', array(
    //         'prod_id' => $product_id,
    //         'quantity' => $quantity,
    //         'user_id' => $user_id,
    //         'img_url' => $img_url,
    //         'cvet' => $color,
    //         'razmer' => $razmer,
    //         'url_page' => $url_woo_product,
    //         'original_price' => $original_price,
    //         'original_currency' => $original_currency
    //     ) );
    // }
    
	$options = get_option( 'blu_theme_options' );
    if( $result ){
        ?>
        <p><?php echo $options['blu_theme_options_notifications__seved'] ?><p>
        <?php
    }else{
        ?>
        <p><?php echo $options['blu_theme_options_notifications__err_seved'] ?><p>
        <?php
    }

    die();

}
add_action('wp_ajax_wishlist', 'blu_woo_add_to_wishlist');
add_action('wp_ajax_nopriv_wishlist', 'blu_woo_add_to_wishlist');





/********************  Ajax Delete From Wishlist  **************************/
function blu_woo_remove_from_wishlist(){
    
    // Cheak User Authorization
    $user_id = get_current_user_id();                                                                           // User ID
    if( $user_id == 0 ){
        ?>
            <p>Пожалуйста <a href="#">войдите</a> или <a href="#">зарегестрируйтесь!</a><p>
        <?php
        die();
    }

    // Prepare Data
    $data = $_GET;
    
    $wishlist_id = $data['wishlist_id'];																		// Wishilst ID

	/*********** Update And Delete Element in DB ***********/
	global $wpdb;

	$db_likes = $wpdb->get_results("SELECT * FROM `blu_woocommerce_wishlist` WHERE `user_id` = {$user_id}");
	if( $db_likes ){
		// Get Product From DataBase
		$products_from_db = unserialize( $db_likes[0]->product );

		unset( $products_from_db[$wishlist_id] );

		$result = $wpdb->update( 'blu_woocommerce_wishlist', [ 'product' => serialize($products_from_db) ], ['user_id' => $user_id] );
	}

	// Return Result Function
	$options = get_option( 'blu_theme_options' );
    if( $result ){
        do_shortcode('[blu_woo_get_wishlist]');
		?>
        <messege><p><?php echo $options['blu_theme_options_notifications__remove'] ?><p></messege>
        <?php
    }else{
		do_shortcode('[blu_woo_get_wishlist]');
        ?>
        <messege><p><?php echo $options['blu_theme_options_notifications__err_remove'] ?><p></messege>
        <?php
    }

	die();
}
add_action('wp_ajax_wishlist_remove', 'blu_woo_remove_from_wishlist');
add_action('wp_ajax_nopriv_wishlist_remove', 'blu_woo_remove_from_wishlist');





/********************  Ajax Add To Cart From Wishlist  **************************/
function blu_woo_add_to_cart_from_wishlist(){

    $user_id = get_current_user_id();																										// User ID
    if( $user_id == 0 ){
        ?>
            <p>Пожалуйста <a href="#">войдите</a> или <a href="#">зарегестрируйтесь!</a><p>
        <?php
        die();
    }

    
	global $wpdb;
    $data = $_GET;

    $wishlist_id = $data['wishlist_id'];

	if( $wishlist_id == null ) die();

    $db_likes = $wpdb->get_results("SELECT * FROM `blu_woocommerce_wishlist` WHERE `user_id` = {$user_id}");
	$products_to_cart = unserialize( $db_likes[0]->product )[$wishlist_id];

	// Prepare Data
    $product_id = $products_to_cart['identical']['product_id'];																							// Product ID
	$variation_id = $products_to_cart['identical']['variation_id'];																						// Variation ID
	$quantity = $products_to_cart['deferent']['quantity'];																								// Quantity
	$variation = $products_to_cart['identical']['variation'];

    // Other Cheaks
	$passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
	$product_status = get_post_status($product_id);

	$product = wc_get_product($product_id);

	if( $product->is_type('variable') ){
		
		$product_variation = $product->get_available_variations();

		foreach($product_variation as $var){
			if($var['variation_id'] == $variation_id){
				$max_qty = $var['max_qty'] ? $var['max_qty'] : 1;
			}
		}

	}else{

		if( $product->stock_quantity ){
			$max_qty = $product->stock_quantity;
		}else{
			$max_qty = 1;
		}

	}

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ){
		if( $cart_item['product_id'] == $product_id ){
			if( $cart_item['variation_id'] == $variation_id){
				$cart_qty = $cart_item['quantity'];
			}
		}else{
			$cart_qty = 0;
		}
	}

	// Add To Cart
	$result = false;
	$removed = false;

	if ($passed_validation && 'publish' === $product_status) {
		if( $quantity + $cart_qty <= $max_qty){
			WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
			$result = true;
		}
		else if( $quantity > $max_qty && $cart_qty == 0 ){
			WC()->cart->add_to_cart($product_id, $max_qty, $variation_id, $variation);
			$result = true;
		}
		else if( $quantity + $cart_qty > $max_qty && $cart_qty != 0 ){
			$quantity_last = $max_qty - $cart_qty;
			WC()->cart->add_to_cart($product_id, $quantity_last, $variation_id, $variation); 
			$result = true;
		}
	}

	if( $result ){
		// Get Product From DataBase
		$products_from_db = unserialize( $db_likes[0]->product );

		unset( $products_from_db[$wishlist_id] );

		$removed = $wpdb->update( 'blu_woocommerce_wishlist', [ 'product' => serialize($products_from_db) ], ['user_id' => $user_id] );
	}

	$options = get_option( 'blu_theme_options' );
	if( $removed ){
		?>
		<wishlist><?php do_shortcode('[blu_woo_get_wishlist]'); ?></wishlist>
        <messege><p><?php echo $options['blu_theme_options_notifications__add_to_cart'] ?><p></messege>
        <?php
	}

    // Return Cart
	if ( ! WC()->cart->is_empty() ) : ?>

		<cart>

		<div class="cart__center">
			<ul class="cart__center__list">
			<!-- List -->

			<?php

			if( $quantity + $cart_qty > $max_qty){
				?>
					<div id="tomatch-container" class="cart__center__tomatch-container">
						<div class="cart__center__tomatch-content">
							<h4 class="h4-style">Корзина</h4>
							<p class="regular-fiveteen">Вы добавили максимальное количесвто товара!</p>
						</div>
					</div>
				<?php
			}
			
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
	
				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) :
					$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
					$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					
			?>

				<li class="cart-list-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="item__img-cover">
						<?php if ( empty( $product_permalink ) ) : ?>
							<?php echo $thumbnail . $product_name;?>
						<?php else : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<?php echo $thumbnail . $product_name;?>
							</a>
						<?php endif; ?>
					</div>
					<div class="item__content">
						<span class="item__content__title">
							<?php 
								$hashIndex = strripos( $product_name, '#' );
								if( $hashIndex ){
									$name = substr($product_name, 0, $hashIndex);
								}else{
									$name = $product_name;
								}
							?>
							<a class="medium-fiveteen" href="<?php echo $product_permalink ?>"><?php echo $name ?></a>
							<a  href="#" 
								class="close-button blueins_remove_cart_button"
								aria-label="Remove this item" 
								data-product_id="<?php echo $product_id; ?>" 
								data-cart_item_key="<?php echo $cart_item_key; ?>" 
								data-product_sku="<?php echo $_product->get_sku(); ?>">&times;
							</a>
							<?php
								// echo apply_filters(
								// 	'woocommerce_cart_item_remove_link',
								// 	sprintf(
								// 		'<a href="%s" class="close-button remove remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
								// 		esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								// 		esc_attr__( 'Remove this item', 'woocommerce' ),
								// 		esc_attr( $product_id ),
								// 		esc_attr( $cart_item_key ),
								// 		esc_attr( $_product->get_sku() )
								// 	),
								// 	$cart_item_key
								// );
							?>
						</span>
						<div class="product-quantity-price">
							<div class="item-quantity">
							</div>
							<div class="item-price">
								<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
								<!--span class="price lights-fiveteen">BYN 60.00</span-->
							</div>
						</div>
					</div>
				</li>
			
			<?php 
				endif;
			endforeach;
			?>

			<!-- List -->
			</ul>
		</div>
		<div class="cart__bottom">

			<div class="summ-for-price woocommerce-mini-cart__total total">
				<span class="summ-for-price__text medium-fiveteen">К оплате:</span>
				<span class="summ-for-price__price lights-fiveteen"><?php wc_cart_totals_subtotal_html(); ?></span>
			</div>

			<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

			<div class="woocommerce-mini-cart__buttons buttons">
				<?php 
				do_action( 'woocommerce_widget_shopping_cart_buttons' ); 
				?>
			</div>

			<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

		</div>

		</cart>
	<?php elseif($quantity > $max_qty): ?>
		<cart>

		<div class="widget_shopping_cart_content">
			<div class="woocommerce-mini-cart__empty-message__cover">
				<p class="woocommerce-mini-cart__empty-message">Это слишком много.</p>
			</div>
		</div>

		</cart>
	<?php endif;
	die();
    
}

add_action('wp_ajax_wishlist_add_to_cart', 'blu_woo_add_to_cart_from_wishlist');
add_action('wp_ajax_nopriv_wishlist_add_to_cart', 'blu_woo_add_to_cart_from_wishlist');
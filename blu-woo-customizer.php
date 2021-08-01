<?php
/**
 * Adds options to the customizer for Blueins.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Blueins_Shop_Customizer class.
 */
class Blueins_Shop_Customizer {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'add_sections' ) );
	}

	/**
	 * Add settings to the customizer.
	 */
	public function add_sections( $wp_customize ) {
		$wp_customize->add_panel(
			'Blueins',
			array(
				'priority'       => 200,
				'capability'     => 'manage_options',
				'theme_supports' => '',
				'title'          => 'Blueins',
			)
		);

		$this->add_product_images_section( $wp_customize );
	}

	/**
	 * Wishlist section.
	 */
	private function add_product_images_section( $wp_customize ) {

		if( $section = 'blu-wishlist-customize' ){

            $wp_customize->add_section( $section, [
                'title' => 'Blueins сохранения',
                'priority' => 110,
                'description' => 'Настройка элементов страницы понравившиеся.'
            ]);

            $setting = 'wishlist-empty-start-img-upload';

            $wp_customize->add_setting( $setting, array(
                'default' => '', 
                'sanitize_callback' => 'esc_url_raw'
            ));
        
            $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'diwp_logo_control6', array(
                'label' => 'Загрузить изображение пустых сохранёнок.',
                'priority' => 20,
                'section' => $section,
                'settings' => $setting,
                'button_labels' => array(// All These labels are optional
                            'select' => 'Выбрать изображение',
                            'remove' => 'Удалить изображение',
                            'change' => 'Изменить изображение',
                            )
            )));
            

        }


	}


}

new Blueins_Shop_Customizer();

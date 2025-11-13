<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialifnRegisterAssets {
    
    // Registering all assets
    public static function registerAssets() {

        // Register Notiflix
        wp_register_style('jialifn-notiflix', JIALIFN_ASSETS_URI . '/plugins/notiflix/notiflix.min.css' , array(), '3.2.8', 'all');
        wp_register_script('jialifn-notiflix', JIALIFN_ASSETS_URI . '/plugins/notiflix/notiflix.min.js' , array(), '3.2.8', true);
        wp_register_script('jialifn-notiflix-custom', JIALIFN_ASSETS_URI . '/plugins/notiflix/notiflix-custom.js' , array(), '3.2.8', true);

        // Register Swiper
        wp_register_style('jialifn-swiper', JIALIFN_ASSETS_URI . '/plugins/swiper/swiper.min.css' , array(), '12.0.3', 'all');
        wp_register_script('jialifn-swiper', JIALIFN_ASSETS_URI . '/plugins/swiper/swiper.min.js' , array(), '12.0.3', true);
        wp_register_script('jialifn-swiper-custom', JIALIFN_ASSETS_URI . '/plugins/swiper/swiper-custom.js' , array(), '12.0.3', true);

        // Register styles
        wp_register_style('jialifn-styles', JIALIFN_ASSETS_URI . '/css/styles.css' , array(), '1.0.0', 'all');
        // Register scripts
        wp_register_script('jialifn-script', JIALIFN_ASSETS_URI . '/js/main.js', array('jquery'), '1.0.0', true);

        // Localize script
        wp_localize_script( 'jialifn-script', 'jialifn_ajax', 
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('jialifn-nonce')
            )
        );
    } 

    public static function adminRegisterAssets() {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('jialifn-color-picker', esc_url(JIALIFN_ASSETS_URI . '/js/admin-color-picker.js'), ['wp-color-picker'], '1.0.0', true);
        // It is neede for this project
        self::registerAssets();
    }
    
}

?>
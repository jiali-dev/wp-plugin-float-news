<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialifnRegisterAssets {
    
    // Registering all assets
    public static function registerAssets() {

        // Register Slider Assets
        wp_register_style('jialifn-slider', JIALIFN_ASSETS_URI . '/plugins/slider/slider.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-slider', JIALIFN_ASSETS_URI . '/plugins/slider/slider.js' , array(), '1.0.0', true);

        // Register Toast Assets
        wp_register_style('jialifn-toast', JIALIFN_ASSETS_URI . '/plugins/toast/toast.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-toast', JIALIFN_ASSETS_URI . '/plugins/toast/toast.js' , array(), '1.0.0', true);

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
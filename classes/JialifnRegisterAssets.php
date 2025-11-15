<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialifnRegisterAssets {
    
    // Registering all assets
    public static function registerAssets() {

        // Admin Assets
        
        // Frontend Assets
        wp_register_style('jialifn-slider', JIALIFN_ASSETS_URI . '/frontend/css/slider.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-slider', JIALIFN_ASSETS_URI . '/frontend/js/slider.js' , array(), '1.0.0', true);
        wp_register_style('jialifn-toast', JIALIFN_ASSETS_URI . '/frontend/css/toast.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-toast', JIALIFN_ASSETS_URI . '/frontend/js/toast.js' , array(), '1.0.0', true);
        
        // Common Assets
        wp_register_style('jialifn-shared-style', JIALIFN_ASSETS_URI . '/common/css/shared.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-shared-script', JIALIFN_ASSETS_URI . '/common/js/shared.js', array('jquery'), '1.0.0', true);


        // Localize script
        wp_localize_script( 'jialifn-shared-script', 'jialifn_ajax', 
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('jialifn-nonce')
            )
        );
    } 

    public static function adminRegisterAssets() {

        // Selct2 Assets
        wp_register_style('jialifn-select2', JIALIFN_ASSETS_URI . '/vendor/select2/select2.min.css' , array(), '4.1.0', 'all');
        wp_register_script('jialifn-select2', JIALIFN_ASSETS_URI . '/vendor/select2/select2.min.js' , array(), '4.1.0', true);

        // Color Picker Assets
        wp_enqueue_style('wp-color-picker');
        wp_register_script('jialifn-color-picker', esc_url(JIALIFN_ASSETS_URI . '/admin/js/admin-color-picker.js'), ['wp-color-picker'], '1.0.0', true);

    }
    
}

?>
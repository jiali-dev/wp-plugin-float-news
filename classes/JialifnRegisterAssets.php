<?php 

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialifnRegisterAssets {
    
    // Registering all assets
    public static function registerAssets() {

        // Frontend Assets
        wp_register_style('jialifn-slider', JIALIFN_ASSETS_URI . '/frontend/css/slider.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-slider', JIALIFN_ASSETS_URI . '/frontend/js/slider.js' , array(), '1.0.0', true);
        wp_register_style('jialifn-toast', JIALIFN_ASSETS_URI . '/frontend/css/toast.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-toast', JIALIFN_ASSETS_URI . '/frontend/js/toast.js' , array(), '1.0.0', true);
        
        // Common Assets
        wp_register_style('jialifn-shared', JIALIFN_ASSETS_URI . '/common/css/shared.css' , array(), '1.0.0', 'all');
        wp_register_script('jialifn-shared', JIALIFN_ASSETS_URI . '/common/js/shared.js', array('jquery'), '1.0.0', true);

        // Localize script
        wp_localize_script( 'jialifn-shared', 'jialifn_ajax', 
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('jialifn-nonce')
            )
        );
    } 

    public static function adminRegisterAssets($hook_suffix) {

        wp_enqueue_script('jialifn-shared', JIALIFN_ASSETS_URI . '/common/js/shared.js', array('jquery'), '1.0.0', true);

        // Localize script
        wp_localize_script( 'jialifn-shared', 'jialifn_ajax', 
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('jialifn-nonce')
            )
        );
        
        if ($hook_suffix === 'toplevel_page_jialifn-settings' ||
        $hook_suffix === 'float-news_page_jialifn-settings') {
            
            // Selct2 Assets
            wp_enqueue_style('jialifn-select2', JIALIFN_ASSETS_URI . '/vendor/select2/select2.min.css' , array(), '4.1.0', 'all');
            wp_enqueue_script('jialifn-select2', JIALIFN_ASSETS_URI . '/vendor/select2/select2.min.js' , array(), '4.1.0', true);
            
            // Register settings script
            wp_enqueue_script('jialifn-settings-query', JIALIFN_ASSETS_URI . '/admin/js/settings-query.js' , ['jquery', 'jialifn-select2'], '1.0.0', true);

        }

        // Color Picker Assets
        wp_enqueue_style('wp-color-picker');
        wp_register_script('jialifn-color-picker', esc_url(JIALIFN_ASSETS_URI . '/admin/js/admin-color-picker.js'), ['wp-color-picker'], '1.0.0', true);

    }
    
}

?>
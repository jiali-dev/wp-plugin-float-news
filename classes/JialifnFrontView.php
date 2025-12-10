<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JialifnFrontView {

    private static $instance = null;

    public static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Hook into content
        add_action( 'wp_footer', [ $this, 'render' ] );
    }

    /**
     * Enqueue required assets
     */
    private function enqueueAssets( ) {
        wp_enqueue_style('jialifn-styles');
        wp_enqueue_script('jialifn-script');
        wp_enqueue_style('jialifn-toast');
        wp_enqueue_script('jialifn-toast');
        wp_enqueue_style('jialifn-slider');
        wp_enqueue_script('jialifn-slider');
    }

    /**
     * Render
     */
    public function render( ) {
        $this->enqueueAssets();

        ob_start(); ?>
        <div class="jialifn-toast">
            <div class="jialifn-toast-content">
                
            </div>
        </div>
        
        <?php
        echo ob_get_clean();
    }

}
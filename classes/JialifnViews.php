<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class JialifnViews {

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
        wp_enqueue_style('jialifn-slider');
        wp_enqueue_script('jialifn-slider');
        wp_enqueue_style('jialifn-toast');
        wp_enqueue_script('jialifn-toast');
    }

    /**
     * Bookmark button HTML
     */
    public function render( ) {
        $this->enqueueAssets();

        ob_start(); ?>
        <div class="jialifn-toast">
            <div class="jialifn-toast-content">
                <div class="jialifn-slider">
                    <div class="jialifn-slides active">
                        <img src="https://picsum.photos/id/1018/800/400" alt="Slide 1">
                    </div>
                    <div class="jialifn-slides">
                        <img src="https://picsum.photos/id/1025/800/400" alt="Slide 2">
                    </div>
                    <div class="jialifn-slides">
                        <img src="https://picsum.photos/id/1039/800/400" alt="Slide 3">
                    </div>
        
                    <div class="jialifn-progress">
                        <div class="jialifn-progress-bar"></div>
                    </div>
                </div>
            </div>
        </div>

        
        <?php
        echo ob_get_clean();
    }

}
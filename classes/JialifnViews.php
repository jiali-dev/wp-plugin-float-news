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
        add_filter( 'wp_footer', [ $this, 'render' ] );
    }

    /**
     * Enqueue required assets
     */
    private function enqueueAssets( ) {
        wp_enqueue_style('jialifn-swiper');
        wp_enqueue_script('jialifn-swiper');
        wp_enqueue_script('jialifn-swiper-custom');
        wp_enqueue_style('jialifn-notiflix');
        wp_enqueue_script('jialifn-notiflix');
        wp_enqueue_script('jialifn-notiflix-custom');
        wp_enqueue_style('jialifn-styles');
        wp_enqueue_script('jialifn-script');
    }

    /**
     * Bookmark button HTML
     */
    public function render( ) {
        $this->enqueueAssets();

        ob_start(); ?>
        
        <div class="">
            <h1>Test</h1>
        </div>
        <?php
        return ob_get_clean();
    }

}
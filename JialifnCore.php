<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialifnCore {

    private static $instance = null;

    private function __construct() {
        $this->defineConstants();
        $this->registerAutoload();
        $this->init();
    }

    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function registerAutoload() {
        spl_autoload_register(function ($class_name) {
            // Only autoload classes starting with "Ab"
            if (strpos($class_name, 'Jialifn') === 0) {
                $file = JIALIFN_CLASSES_PATH . $class_name . '.php';
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        });
    }

    private function defineConstants() {
        define('JIALIFN_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('JIALIFN_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('JIALIFN_CLASSES_PATH', JIALIFN_PLUGIN_PATH . 'classes/');
        define('JIALIFN_ASSETS_URI', JIALIFN_PLUGIN_URL . 'assets');
    }

    private function init() {
        // Hook into WordPress
        add_action('init', [$this, 'startOutputBuffers']);
   
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
        add_action('admin_enqueue_scripts', [$this, 'adminRegisterAssets']); // For this project
        
        // include_once(JIALIFN_PLUGIN_PATH.'inc/functions.php');
        JialifnRestApi::getInstance();
        JialifnFrontView::getInstance();
        JialifnAdminMenu::getInstance();
    }

    // Start output buffering
    public static function startOutputBuffers() {
        ob_start();
    }

    // Activate plugin
    public static function registerActivation() {}

    // Registering all assets
    public function registerAssets() {
        JialifnRegisterAssets::registerAssets();
    }

    public function adminRegisterAssets() {
        JialifnRegisterAssets::adminRegisterAssets();
    }

    public static function uninstallation() {
		// Deactivation logic here
	}
}
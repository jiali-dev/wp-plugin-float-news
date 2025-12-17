<?php

if ( ! defined('ABSPATH') ) exit;

class JialifnAdminMenu {

    private static $instance = null;

    public static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_menu', [ $this, 'registerMenu' ]);
    }

    public function registerMenu() {

        // MAIN MENU (also acts as Query Settings page)
        add_menu_page(
            esc_html__('Floating News Settings', 'jiali-floating-news'),
            esc_html__('Floating News', 'jiali-floating-news'),
            'manage_options',
            'jialifn-settings',
            [ $this, 'renderQuerySettings' ],
            'dashicons-megaphone',
            70
        );

        // SUBMENU → Query Settings (same page as main)
        add_submenu_page(
            'jialifn-settings',
            esc_html__('Query Settings', 'jiali-floating-news'),
            esc_html__('Query Settings', 'jiali-floating-news'),
            'manage_options',
            'jialifn-settings',
            [ $this, 'renderQuerySettings' ]
        );

        // SUBMENU → Style Settings (different page)
        add_submenu_page(
            'jialifn-settings',
            esc_html__('Style Settings', 'jiali-floating-news'),
            esc_html__('Style Settings', 'jiali-floating-news'),
            'manage_options',
            'jialifn-style-settings',
            [ $this, 'renderStyleSettings' ]
        );
    }

    public function renderQuerySettings() {
        JialifnSettingsQuery::getInstance()->renderPage();
    }

    public function renderStyleSettings() {
        JialifnSettingsStyle::getInstance()->renderPage();
    }
}
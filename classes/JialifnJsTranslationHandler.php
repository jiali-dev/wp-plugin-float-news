<?php

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

class JialifnJsTranslationHandler {

    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'registerJsTranslation']);
        add_action('admin_enqueue_scripts', [$this, 'registerJsTranslation']);
    }

    public function registerJsTranslation() {
        // Ensure script is already enqueued
        wp_enqueue_script('jialifn-shared');

        wp_localize_script(
            'jialifn-shared',
            'jialifn_translate_handler', 
            [
                'select' => __('Select ...', 'jiali-floating-news'),
                'enter_characters' => __('Please enter 1 or more characters', 'jiali-floating-news'),
            ]
        );
    }
}
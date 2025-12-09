<?php

if (!defined('ABSPATH')) exit;

class JialifnCacheManager {

    private static $instance = null;
    private $prefix = 'jialifn_';

    private function __construct() {
        // Auto-clear cache when posts change
        add_action('save_post',    [$this, 'clearCache']);
        add_action('deleted_post', [$this, 'clearCache']);
        add_action('trashed_post', [$this, 'clearCache']);
    }

    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Generate a unique cache key based on prefix + hash of data.
     */
    private function generateKey($data) {
        return $this->prefix . md5(wp_json_encode($data));
    }

    /**
     * Get cached value
     */
    public function getCache($data) {
        $key = $this->generateKey($data);
        return get_transient($key);
    }

    /**
     * Set cached value
     */
    public function setCache($data, $value, $expiration = 600) {
        $key = $this->generateKey($data);
        set_transient($key, $value, $expiration);
    }

    /**
     * Remove ALL plugin transients
     */
    public function clearCache() {
        global $wpdb;

        $pattern1 = esc_sql("_transient_{$this->prefix}%");
        $pattern2 = esc_sql("_transient_timeout_{$this->prefix}%");

        $transients = $wpdb->get_col("
            SELECT option_name 
            FROM $wpdb->options
            WHERE option_name LIKE '{$pattern1}'
               OR option_name LIKE '{$pattern2}'
        ");

        if (!$transients) return;

        foreach ($transients as $option_name) {
            // Remove "_transient_" prefix from option key
            $key = str_replace('_transient_', '', $option_name);
            delete_transient($key);
        }
    }
}
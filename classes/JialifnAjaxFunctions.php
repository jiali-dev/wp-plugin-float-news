<?php
if (!defined('ABSPATH')) exit;

class JialifnAjaxFunctions {
    
    private static $instance = null;

    private function __construct() {
        // Get terms
        add_action('wp_ajax_jialifn_get_terms', [$this, 'jialifnGetTerms']);
        add_action('wp_ajax_nopriv_jialifn_get_terms', [$this, 'jialifnGetTerms']);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function verifyNonce() {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
        if (empty($nonce) || !wp_verify_nonce($nonce, 'jialifn-nonce')) {
            throw new Exception(esc_html__('Security error!', 'jiali-float-news'), 403);
        }
    }

    public function jialifnGetTerms() {

        // Verify AJAX nonce
        $this->verifyNonce();

        if (!is_user_logged_in()) {
            throw new Exception(
                esc_html__('You must be logged in to perform this action!', 'jiali-float-news'),
                403
            );
        }

        // Sanitize inputs
        $search     = sanitize_text_field($_POST['search'] ?? '');
        $post_type  = sanitize_key($_POST['post_type'] ?? 'post');

        // Get all taxonomies linked to this post type
        $taxonomies = get_object_taxonomies($post_type);

        if (empty($taxonomies)) {
            wp_send_json_error([
                'message' => esc_html__('No taxonomies found for this post type.', 'jiali-float-news')
            ]);
        }

        $results = [];

        // Loop through all taxonomies and collect terms
        foreach ($taxonomies as $taxonomy) {

            $terms = get_terms([
                'taxonomy'   => $taxonomy,
                'search'     => $search,
                'hide_empty' => false,
                'number'     => 20,
            ]);

            if (!is_wp_error($terms) && !empty($terms)) {
                foreach ($terms as $term) {
                    $results[] = [
                        'id'   => $term->term_id,
                        'text' => $term->name . ' (' . $taxonomy . ')'
                    ];
                }
            }
        }

        wp_send_json($results);
    }

}

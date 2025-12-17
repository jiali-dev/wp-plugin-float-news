<?php
if (!defined('ABSPATH')) exit;

class JialifnAjaxFunctions {
    
    private static $instance = null;

    private function __construct() {
        // Get terms
        add_action('wp_ajax_jialifn_get_terms', [$this, 'jialifnGetTerms']);

        // Get authors
        add_action('wp_ajax_jialifn_get_authors', [$this, 'jialifnGetAuthors']);

        // Get manual sources
        add_action('wp_ajax_jialifn_get_manual_sources', [$this, 'jialifnGetManualSources']);

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
            throw new Exception(esc_html__('Security error!', 'jiali-floating-news'), 403);
        }
    }

    // Get terms
    public function jialifnGetTerms() {

        // Verify AJAX nonce
        $this->verifyNonce();

        if (!is_user_logged_in()) {
            throw new Exception(
                esc_html__('You must be logged in to perform this action!', 'jiali-floating-news'),
                403
            );
        }

        // Sanitize inputs
        $search = sanitize_text_field(wp_unslash($_POST['search']) ?? '');
        $post_type = sanitize_key(wp_unslash($_POST['post_type'] ?? 'post'));

        // Get all taxonomies linked to this post type
        $taxonomies = get_object_taxonomies($post_type);

        if (empty($taxonomies)) {
            wp_send_json_error([
                'message' => esc_html__('No taxonomies found for this post type.', 'jiali-floating-news')
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

    // Get authors
    public function jialifnGetAuthors() {

        // Verify AJAX nonce
        $this->verifyNonce();

        if (!is_user_logged_in()) {
            throw new Exception(
                esc_html__('You must be logged in to perform this action!', 'jiali-floating-news'),
                403
            );
        }

        // Sanitize search query
        $search = sanitize_text_field($_POST['search'] ?? '');

        // Query authors
        $authors = get_users([
            'search'         => '*'.$search.'*',
            'search_columns' => ['display_name', 'user_nicename'],
            'number'         => 20,
            'orderby'        => 'display_name',
            'order'          => 'ASC',
            // Optional: Limit only to authors/editors/admins
            'role__in'       => ['author', 'editor', 'administrator'],
        ]);

        if (empty($authors)) {
            wp_send_json_error([
                'message' => esc_html__('No authors found!', 'jiali-floating-news')
            ]);
        }

        $results = [];

        foreach ($authors as $author) {
            $results[] = [
                'id'   => $author->ID,
                'text' => $author->display_name . ' (' . $author->user_nicename . ')'
            ];
        }

        wp_send_json($results);
        
    }

    // Get manual sources
    public function jialifnGetManualSources() {

        // Verify nonce
        $this->verifyNonce();

        // User must be logged in
        if (!is_user_logged_in()) {
            wp_send_json_error([
                'message' => esc_html__('You must be logged in to perform this action.', 'jiali-floating-news')
            ], 403);
        }

        // Sanitize inputs
        $search     = sanitize_text_field(wp_unslash($_POST['search'] ?? ''));

        // Query posts
        $posts = get_posts([
            's'              => $search,      // WordPress search
            'post_type' => 'any',
            'posts_per_page' => 20,
            'post_status'    => 'publish',
        ]);

        $results = [];
        foreach ($posts as $post) {
            $results[] = [
                'id'   => $post->ID,
                'text' => $post->post_title
            ];
        }

        wp_send_json($results);
    }

}

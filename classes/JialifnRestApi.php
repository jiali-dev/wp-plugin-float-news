<?php
if (!defined('ABSPATH')) exit;

class JialifnRestApi {
  
    private static $instance = null;

    /**
     * Constructor: hook into rest_api_init
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'registerRoutes' ] );
    }

     private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Register REST API routes
     */
    public function registerRoutes() {
        register_rest_route( 'jiali-float-news/v1', '/posts', [
            'methods'  => 'GET',
            'callback' => [ $this, 'getPosts' ],
            'permission_callback' => '__return_true',
            'args' => [
                'count' => [
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                ],
                'orderby' => [
                    'default' => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'order' => [
                    'default' => 'DESC',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'category' => [
                    'sanitize_callback' => 'absint',
                ],
                'tag' => [
                    'sanitize_callback' => 'absint',
                ],
                'random' => [
                    'default' => false,
                    'sanitize_callback' => 'rest_sanitize_boolean',
                ],
            ],
        ] );
    }

    /**
     * REST API Callback — Fetch and cache posts
     */
    public function getPosts( WP_REST_Request $request ) {

        $args = [
            'posts_per_page' => $request['count'],
            'post_status'    => 'publish',
            'orderby'        => $request['random'] ? 'rand' : $request['orderby'],
            'order'          => $request['order'],
        ];

        if ( $request['category'] ) {
            $args['cat'] = $request['category'];
        }

        if ( $request['tag'] ) {
            $args['tag_id'] = $request['tag'];
        }

        // Generate unique cache key based on args
        $cache_key = 'jiali_float_news_' . md5( wp_json_encode( $args ) );
        $posts = get_transient( $cache_key );

        if ( false === $posts ) {
            $query = new WP_Query( $args );
            $posts = [];

            while ( $query->have_posts() ) {
                $query->the_post();
                $posts[] = [
                    'id'    => get_the_ID(),
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                    'image' => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
                ];
            }
            wp_reset_postdata();

            // Cache for 10 minutes
            set_transient( $cache_key, $posts, 10 * MINUTE_IN_SECONDS );
        }

        return rest_ensure_response( $posts );
    }
}

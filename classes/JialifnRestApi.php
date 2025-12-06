<?php
if (!defined('ABSPATH')) exit;

class JialifnRestApi {
  
    private static $instance = null;

    /**
     * Constructor: hook into rest_api_init
     */
    private function __construct() {
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
                'source' => [
                    'default' => null,
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'manual_sources' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('absint', (array)$v);
                    }
                ],
                'include_by' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('sanitize_text_field', (array)$v);
                    }
                ],
                'included_terms' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('absint', (array)$v);
                    }
                ],
                'included_authors' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('absint', (array)$v);
                    }
                ],
                'exclude_by' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('sanitize_text_field', (array)$v);
                    }
                ],
                'excluded_terms' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('absint', (array)$v);
                    }
                ],
                'excluded_authors' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('absint', (array)$v);
                    }
                ],
                'manual_excluded_sources' => [
                    'default' => [],
                    'sanitize_callback' => function($v){
                        return array_map('absint', (array)$v);
                    }
                ],
                'date_range' => [
                    'default' => 'all',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'date_after' => [
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'date_before' => [
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'orderby' => [
                    'default' => 'date',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'order' => [
                    'default' => 'DESC',
                    'sanitize_callback' => 'sanitize_text_field',
                ],
                'count' => [
                    'default' => 10,
                    'sanitize_callback' => 'absint',
                ],

            ]
        ] );
    }

    /**
     * REST API Callback — Fetch and cache posts
     */
    public function getPosts( WP_REST_Request $request ) {

        // Base args
        $orderby = $request->get_param('orderby') ?: 'date';
        $order = $request->get_param('order') ?: 'DESC';
        $count = $request->get_param('count') ?: 10;
        
        $args = [
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => $orderby,
            'order'          => $order,
        ];

        // Get post type from settings
        $source = $request->get_param('source') ?: 'post';
        
        // ---------------------------
        // MANUAL SOURCES MODE
        // ---------------------------        
        if( $source === 'manual_sources') {
            
            $manual_sources   = $request->get_param('manual_sources') ?: [];

            if( !empty( $manual_sources ) )
                $args['post__in'] = array_map('intval', $manual_sources);

        } else {
            
            // NORMAL MODE → apply all filters
            $args['post_type'] = $source;

            // =====================================================
            // INCLUDE / EXCLUDE
            // =====================================================
            $include_by = (array) ($request->get_param('include_by') ?: []);
            $exclude_by = (array) ($request->get_param('exclude_by') ?: []);

            // =====================================================
            // TERM FILTERS
            // =====================================================
            $included_terms = array_map('intval', (array) $request->get_param('included_terms'));
            $excluded_terms = array_map('intval', (array) $request->get_param('excluded_terms'));

            $tax_query = [
                'relation' => 'AND',
            ];

            // ----------------------------------------------
            // INCLUDED TERMS  →  OR relation
            // ----------------------------------------------
            if ( !empty($included_terms) && in_array('term', $include_by)) {

                $included_rows = [];

                foreach ($included_terms as $term_id) {
                    $term = get_term($term_id);
                    if (!$term || is_wp_error($term)) continue;

                    $included_rows[] = [
                        'taxonomy' => $term->taxonomy,
                        'field'    => 'term_id',
                        'terms'    => [$term_id],
                        'operator' => 'IN',
                    ];
                }

                if (!empty($included_rows)) {
                    $tax_query[] = array_merge(['relation' => 'OR'], $included_rows);
                }
            }

            // ----------------------------------------------
            // EXCLUDED TERMS  →  AND relation
            // ----------------------------------------------
            if (!empty($excluded_terms) && in_array('term', $exclude_by)) {

                $excluded_rows = [];

                foreach ($excluded_terms as $term_id) {
                    $term = get_term($term_id);
                    if (!$term || is_wp_error($term)) continue;

                    $excluded_rows[] = [
                        'taxonomy' => $term->taxonomy,
                        'field'    => 'term_id',
                        'terms'    => [$term_id],
                        'operator' => 'NOT IN',
                    ];
                }

                if (!empty($excluded_rows)) {
                    $tax_query[] = array_merge(['relation' => 'AND'], $excluded_rows);
                }
            }

            if (!empty($tax_query)) {
                $args['tax_query'] = $tax_query;
            }

            // wp_die(jve_pretty_var_dump($args)); // For debugging purposes

            // ====================================================
            // AUTHOR FILTERS
            // =====================================================

            $included_authors = array_map('intval', (array) $request->get_param('included_authors'));
            $excluded_authors = array_map('intval', (array) $request->get_param('excluded_authors'));

            if (in_array('author', $include_by) && !empty($included_authors)) {
                $args['author__in'] = $included_authors;
            }

            if (in_array('author', $exclude_by) && !empty($excluded_authors)) {
                $args['author__not_in'] = $excluded_authors;
            }

            // =====================================================
            // MANUAL EXCLUDED POSTS
            // =====================================================
            if (in_array('manual_source', $exclude_by)) {
                $manual_excluded = array_map('intval', (array) $request->get_param('manual_excluded_sources'));
                if (!empty($manual_excluded)) {
                    $args['post__not_in'] = $manual_excluded;
                }
            }

            $date_range = $request->get_param('date_range') ?: 'all';

            if( $date_range !== 'all' ) {
                $date_query = [];

                switch( $date_range ) {
                    case 'past_day':
                        $date_query[] = [ 'after' => '1 day ago', 'inclusive' => true ];
                        break;

                    case 'past_week':
                        $date_query[] = [ 'after' => '1 week ago', 'inclusive' => true ];
                        break;

                    case 'past_month':
                        $date_query[] = [ 'after' => '1 month ago', 'inclusive' => true ];
                        break;

                    case 'past_year':
                        $date_query[] = [ 'after' => '1 year ago', 'inclusive' => true ];
                        break;

                    case 'custom':
                        $range = [];

                        $date_after = $request->get_param('date_after') ?: '';
                        $date_before = $request->get_param('date_before') ?: '';

                        if( !empty( $date_after ) ) {
                            $range['after'] = $date_after;
                        }

                        if( !empty( $date_before ) ) {
                            $range['before'] = $date_before;
                        }

                        if( !empty( $range ) ) {
                            $range['inclusive'] = true;
                            $date_query[] = $range;
                        }
                        break;
                }

                if( !empty( $date_query ) ) {
                    $args['date_query'] = $date_query;
                }
            }
        }

        // ---------------------------
        // CACHING (BASED ON WP_QUERY ARGS)
        // ---------------------------
        $cache_key = 'jialifn_' . md5(json_encode($args));
        $posts = get_transient($cache_key);

        if ($posts !== false) {
            return $posts;
        }

        // ---------------------------
        // RUN QUERY
        // ---------------------------
        $query = new WP_Query($args);
        $posts = [];

        while ($query->have_posts()) {
            $query->the_post();
            $posts[] = [
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'link'  => get_permalink(),
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'),
            ];
        }

        wp_reset_postdata();

        // Cache 10 minutes
        set_transient($cache_key, $posts, 10 * MINUTE_IN_SECONDS);

        return $posts;
    }

}
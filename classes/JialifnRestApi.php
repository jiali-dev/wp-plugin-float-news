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

        // Get post type from settings (validate against public post types)
        $raw_source = trim( (string) $request->get_param('source') );
        $public_pts = array_keys( get_post_types( [ 'public' => true ] ) );

        // Accept 'manual' or 'manual_sources' as manual selection mode
        $is_manual_mode = in_array( $raw_source, [ 'manual', 'manual_sources' ], true );

        // Normalize source to a valid post type when not manual
        $source = $is_manual_mode ? 'post' : ( in_array( $raw_source, $public_pts, true ) ? $raw_source : 'post' );
        wp_die(jve_pretty_var_dump($source)); // For debugging purposes

        // ---------------------------
        // MANUAL SOURCES MODE
        // ---------------------------
        if ( $is_manual_mode ) {
            $manual_sources = array_map( 'intval', (array) $request->get_param('manual_sources') );

            if ( ! empty( $manual_sources ) ) {
                $args['post__in'] = $manual_sources;
            }

        } else {
            // NORMAL MODE → apply all filters
            $args['post_type'] = $source;

            // =====================================================
            // INCLUDE / EXCLUDE
            // =====================================================
            $include_by = (array) ( $request->get_param('include_by') ?: [] );
            $exclude_by = (array) ( $request->get_param('exclude_by') ?: [] );

            // =====================================================
            // TERM FILTERS — build grouped tax_query only if needed
            // =====================================================
            $included_terms = array_filter( array_map( 'intval', (array) $request->get_param( 'included_terms' ) ) );
            $excluded_terms = array_filter( array_map( 'intval', (array) $request->get_param( 'excluded_terms' ) ) );

            $tax_groups = [];

            // Included terms: create an OR group where each term is its own clause (matches any)
            if ( ! empty( $included_terms ) && in_array( 'term', $include_by, true ) ) {
                $included_rows = [];
                foreach ( $included_terms as $term_id ) {
                    $term = get_term( $term_id );
                    if ( ! $term || is_wp_error( $term ) ) {
                        continue;
                    }
                    $included_rows[] = [
                        'taxonomy' => $term->taxonomy,
                        'field'    => 'term_id',
                        'terms'    => [ $term_id ],
                        'operator' => 'IN',
                    ];
                }
                if ( ! empty( $included_rows ) ) {
                    // group with OR relation
                    $tax_groups[] = array_merge( [ 'relation' => 'OR' ], $included_rows );
                }
            }

            // Excluded terms: create an AND group with NOT IN per term
            if ( ! empty( $excluded_terms ) && in_array( 'term', $exclude_by, true ) ) {
                $excluded_rows = [];
                foreach ( $excluded_terms as $term_id ) {
                    $term = get_term( $term_id );
                    if ( ! $term || is_wp_error( $term ) ) {
                        continue;
                    }
                    $excluded_rows[] = [
                        'taxonomy' => $term->taxonomy,
                        'field'    => 'term_id',
                        'terms'    => [ $term_id ],
                        'operator' => 'NOT IN',
                    ];
                }
                if ( ! empty( $excluded_rows ) ) {
                    $tax_groups[] = array_merge( [ 'relation' => 'AND' ], $excluded_rows );
                }
            }

            // Attach tax_query only if we have at least one group
            if ( ! empty( $tax_groups ) ) {
                // If multiple groups exist, combine them with a top-level AND relation
                if ( count( $tax_groups ) > 1 ) {
                    $args['tax_query'] = array_merge( [ 'relation' => 'AND' ], $tax_groups );
                } else {
                    // single group — use it directly
                    $args['tax_query'] = reset( $tax_groups );
                }
            }

            // ====================================================
            // AUTHOR FILTERS
            // =====================================================
            $included_authors = array_map('intval', (array) $request->get_param('included_authors'));
            $excluded_authors = array_map('intval', (array) $request->get_param('excluded_authors'));

            $has_include_filter = in_array('author', $include_by);
            $has_exclude_filter = in_array('author', $exclude_by);

            // Step 1: If include filter is enabled, include list defines the universe
            if ($has_include_filter) {

                // Remove excluded from included
                if (!empty($excluded_authors)) {
                    $included_authors = array_diff($included_authors, $excluded_authors);
                }

                // Step 2: If no authors remain → return nothing
                if (!empty($included_authors)) {

                    // Step 3: Use remaining authors as the only valid ones
                    $args['author__in'] = $included_authors;

                } else {
                    // No authors remain → return nothing
                    $args['post__in'] = [0];
                }

            } else {

                // No include filter → exclude normally
                if ($has_exclude_filter && !empty($excluded_authors)) {
                    $args['author__not_in'] = $excluded_authors;
                }
            }

            // =====================================================
            // MANUAL EXCLUDED POSTS
            // =====================================================
            // support both 'manual_source' and 'manual_sources' keys from different UI variants
            if ( in_array( 'manual_source', $exclude_by, true ) || in_array( 'manual_sources', $exclude_by, true ) ) {
                $manual_excluded = array_map( 'intval', (array) $request->get_param( 'manual_excluded_sources' ) );
                if ( ! empty( $manual_excluded ) ) {
                    $args['post__not_in'] = $manual_excluded;
                }
            }

            // Date range
            $date_range = $request->get_param('date_range') ?: 'all';

            // choose which column to filter: 'post_date' or 'post_modified'
            $date_column = 'post_modified';

            if ( $date_range !== 'all' ) {
                $date_query = [];

                switch ( $date_range ) {
                    case 'past_day':
                        $date_query[] = [
                            'column'    => $date_column,
                            'after'     => date_i18n( 'Y-m-d H:i:s', strtotime( '-1 day' ) ),
                            'inclusive' => true,
                        ];
                        break;

                    case 'past_week':
                        $date_query[] = [
                            'column'    => $date_column,
                            'after'     => date_i18n( 'Y-m-d H:i:s', strtotime( '-1 week' ) ),
                            'inclusive' => true,
                        ];
                        break;

                    case 'past_month':
                        $date_query[] = [
                            'column'    => $date_column,
                            'after'     => date_i18n( 'Y-m-d H:i:s', strtotime( '-1 month' ) ),
                            'inclusive' => true,
                        ];
                        break;

                    case 'past_year':
                        $date_query[] = [
                            'column'    => $date_column,
                            'after'     => date_i18n( 'Y-m-d H:i:s', strtotime( '-1 year' ) ),
                            'inclusive' => true,
                        ];
                        break;

                    case 'custom':
                        $range = [];

                        $raw_after  = trim( $request->get_param('date_after') );
                        $raw_before = trim( $request->get_param('date_before') );

                        // Automatically append seconds if missing (HH:MM → HH:MM:00)
                        if ( preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $raw_after ) ) {
                            $raw_after .= ':00';
                        }
                        if ( preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $raw_before ) ) {
                            $raw_before .= ':00';
                        }

                        // validate after
                        if ( !empty($raw_after) ) {
                            $ts = strtotime($raw_after);
                            if ( $ts !== false ) {
                                $range['after'] = date('Y-m-d H:i:s', $ts);
                            }
                        }

                        // validate before
                        if ( !empty($raw_before) ) {
                            $ts = strtotime($raw_before);
                            if ( $ts !== false ) {
                                $range['before'] = date('Y-m-d H:i:s', $ts);
                            }
                        }

                        if ( ! empty( $range ) ) {
                            $range['column']    = $date_column; // post_date OR post_modified
                            $range['inclusive'] = true;
                            $date_query[]       = $range;
                        }
                        break;

                }

                if ( ! empty( $date_query ) ) {
                    // if you want to combine multiple date clauses, you can set relation.
                    // usually there is only one clause, so this is fine:
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
                'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail')
            ];
        }

        wp_reset_postdata();

        // Cache 10 minutes
        set_transient($cache_key, $posts, 10 * MINUTE_IN_SECONDS);

        return $posts;
    }

}
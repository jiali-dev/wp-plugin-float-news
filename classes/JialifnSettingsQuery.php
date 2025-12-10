<?php

class JialifnSettingsQuery {

    private static $instance = null;

    public static function getInstance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('admin_init', [ $this, 'registerSettings' ]);
    }

    public function registerSettings() {

        register_setting('jialifn_query_group', 'jialifn_query_options');

        // --- Source Section ---
        add_settings_section(
            'jialifn_query_source_section',
            esc_html__('Query source configuration', 'jiali-float-news'),
            '__return_false',
            'jialifn-settings',
            [
                'before_section' => '<div class="jialifn-query-source-section-wrapper">',
                'after_section'  => '</div>',
            ]
        );

        // SOURCE
        add_settings_field(
            'source',
            esc_html__('Source', 'jiali-float-news'),
            [ $this, 'fieldSource' ],
            'jialifn-settings',
            'jialifn_query_source_section'
        );

        // MANUAL SOURCE
        add_settings_field(
            'manual-sources',
            esc_html__('Manual sources', 'jiali-float-news'),
            [ $this, 'fieldManualSources' ],
            'jialifn-settings',
            'jialifn_query_source_section',
            [
                'class' => 'jialifn-manual-sources-wrapper'
            ]
        );

        // --- Exception Section ---
        add_settings_section(
            'jialifn_query_exception_section',
            esc_html__('Query exception settings', 'jiali-float-news'),
            '__return_false',
            'jialifn-settings',
            [
                'before_section' => '<div class="jialifn-query-exception-section-wrapper">',
                'after_section'  => '</div>',
            ]
        );

        // INCLUDE TERMS + AUTHORS
        add_settings_field(
            'include-by',
            esc_html__('Include by', 'jiali-float-news'),
            [ $this, 'fieldIncludeBy' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-include-by-wrapper'
            ]
        );

        // INCLUDED TERMS
        add_settings_field(
            'included-terms',
            esc_html__('Included terms', 'jiali-float-news'),
            [ $this, 'fieldIncludedTerms' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-included-terms-wrapper'
            ]
        );

        // INCLUDED AUTHORS
        add_settings_field(
            'included-authors',
            esc_html__('Included authors', 'jiali-float-news'),
            [ $this, 'fieldIncludedAuthors' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-included-authors-wrapper'
            ]
        );

        // EXCLUDE TERMS + AUTHORS
        add_settings_field(
            'exclude-by',
            esc_html__('Exclude by', 'jiali-float-news'),
            [ $this, 'fieldExcludeBy' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-exclude-by-wrapper'
            ]
        );

        // EXCLUDED TERMS
        add_settings_field(
            'excluded-terms',
            esc_html__('Excluded terms', 'jiali-float-news'),
            [ $this, 'fieldExcludedTerms' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-excluded-terms-wrapper'
            ]
        );

        // EXCLUDED AUTHORS
        add_settings_field(
            'excluded-authors',
            esc_html__('Excluded authors', 'jiali-float-news'),
            [ $this, 'fieldExcludedAuthors' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-excluded-authors-wrapper'
            ]
        );

        // MANUAL EXCLUDED SOURCES
        add_settings_field(
            'manual-excluded-sources',
            esc_html__('Manual excluded sources', 'jiali-float-news'),
            [ $this, 'fieldManualExcludedSources' ],
            'jialifn-settings',
            'jialifn_query_exception_section',
            [
                'class' => 'jialifn-manual-excluded-sources-wrapper'
            ]
        );

        // --- Date Section ---
        add_settings_section(
            'jialifn_query_date_section',
            esc_html__('Query date settings', 'jiali-float-news'),
            '__return_false',
            'jialifn-settings',
            [
                'before_section' => '<div class="jialifn-query-date-section-wrapper">',
                'after_section'  => '</div>',
            ]
        );

        // DATE FILTERS
        add_settings_field(
            'date_range',
            esc_html__('Date range', 'jiali-float-news'),
            [ $this, 'fieldDateRange' ],
            'jialifn-settings',
            'jialifn_query_date_section'
        );

        // DATE AFTER
        add_settings_field(
            'date_after',
            esc_html__('Date after', 'jiali-float-news'),
            [ $this, 'fieldDateAfter' ],
            'jialifn-settings',
            'jialifn_query_date_section',
            [
                'class' => 'jialifn-date-after-wrapper'
            ]
        );

        // DATE BEFORE
        add_settings_field(
            'date_before',
            esc_html__('Date before', 'jiali-float-news'),
            [ $this, 'fieldDateBefore' ],
            'jialifn-settings',
            'jialifn_query_date_section',
            [
                'class' => 'jialifn-date-before-wrapper'
            ]
        );

        // --- ORDER Section ---
        add_settings_section(
            'jialifn_query_order_section',
            esc_html__('Query order settings', 'jiali-float-news'),
            '__return_false',
            'jialifn-settings',
            [
                'before_section' => '<div class="jialifn-query-order-section-wrapper">',
                'after_section'  => '</div>',
            ]
        );

        // ORDER
        add_settings_field(
            'orderby',
            esc_html__('Order by', 'jiali-float-news'),
            [ $this, 'fieldOrderBy' ],
            'jialifn-settings',
            'jialifn_query_order_section'
        );

        add_settings_field(
            'order',
            esc_html__('Order', 'jiali-float-news'),
            [ $this, 'fieldOrder' ],
            'jialifn-settings',
            'jialifn_query_order_section'
        );

        // --- ORDER Section ---
        add_settings_section(
            'jialifn_query_extra_section',
            esc_html__('Query extra settings', 'jiali-float-news'),
            '__return_false',
            'jialifn-settings',
            [
                'before_section' => '<div class="jialifn-query-extra-section-wrapper">',
                'after_section'  => '</div>',
            ]
        );

        add_settings_field(
            'count',
            esc_html__('Count', 'jiali-float-news'),
            [ $this, 'fieldCount' ],
            'jialifn-settings',
            'jialifn_query_extra_section'
        );
    }


    /* ==== FIELD RENDERERS ===== */
    public function fieldSource() {
        $opts = get_option('jialifn_query_options');
        $value = $opts['source'] ?? '';

        $post_types = get_post_types([ 'public' => true ], 'objects');

        $exclude = ['attachment', 'e-floating-buttons', 'elementor_library'];

        echo '<select class="jialifn-source" name="jialifn_query_options[source]">';

        foreach ($post_types as $key => $pt) {
            if (in_array($key, $exclude, true)) {
                continue;
            }
            echo "<option value='{$key}' " . selected($value, $key, false) . ">{$pt->label}</option>";
        }
        echo '<option value="manual_sources" ' . selected($value, 'manual_sources', false) . '>' . esc_html__('Manual sources', 'jiali-float-news') . '</option>';

        echo '</select>';
    }

    // MANUAL SOURCES
    public function fieldManualSources() {
        
        // Get saved values (array because multiple select)
        $options = get_option('jialifn_query_options');
        $selected_values = $options['manual_sources'] ?? [];

        echo '<select class="jialifn-manual-sources" 
                 name="jialifn_query_options[manual_sources][]" 
                 multiple>';

        // Preload saved posts
        if (!empty($selected_values) && is_array($selected_values)) {

            foreach ($selected_values as $post_id) {

                $post = get_post($post_id);

                if ($post && !is_wp_error($post)) {

                    // Label example: "Post Title (post_type)"
                    $label = $post->post_title;

                    echo '<option value="' . esc_attr($post->ID) . '" selected>'
                        . esc_html($label) .
                        '</option>';
                }
            }
        }

        echo '</select>';
    }

    public function fieldIncludeBy() {
        // Get saved values (array because multiple select)
        $options = get_option('jialifn_query_options');
        $selected_values = $options['include_by'] ?? [];

        echo '<select class="jialifn-include-by" name="jialifn_query_options[include_by][]" multiple>';
        echo '<option value="term" ' . selected(in_array('term', $selected_values), true, false) . '>'
                . esc_html__('Term', 'jiali-float-news') .
            '</option>';
        echo '<option value="author" ' . selected(in_array('author', $selected_values), true, false) . '>'
                . esc_html__('Author', 'jiali-float-news') .
            '</option>';
        echo '</select>';
    }

    public function fieldIncludedTerms() {
        // Get saved values (array of term IDs)
        $options = get_option('jialifn_query_options');
        $saved_ids = $options['included_terms'] ?? [];

        echo '<select class="jialifn-included-terms" name="jialifn_query_options[included_terms][]" multiple>';

        // If saved terms exist → preload them in <option selected>
        if (!empty($saved_ids) && is_array($saved_ids)) {
            foreach ($saved_ids as $term_id) {

                $term = get_term($term_id);

                // Validate term exists
                if ($term && !is_wp_error($term)) {
                    echo '<option value="' . esc_attr($term->term_id) . '" selected>'
                            . esc_html($term->name . ' (' . $term->taxonomy . ')') .
                        '</option>';
                }
            }
        }

        echo '</select>';
    }

    // INCLUDED AUTHORS
    public function fieldIncludedAuthors() {
        // Get saved options
        $options = get_option('jialifn_query_options', []);
        $saved_ids = $options['included_authors'] ?? []; // array of author IDs

        echo '<select class="jialifn-included-authors" name="jialifn_query_options[included_authors][]" multiple>';

        if (!empty($saved_ids) && is_array($saved_ids)) {

            foreach ($saved_ids as $user_id) {
                $user = get_userdata($user_id);

                if ($user) {
                    $label = $user->display_name . ' (' . $user->user_login . ')';
                    echo '<option value="' . esc_attr($user->ID) . '" selected>'
                        . esc_html($label) .
                        '</option>';
                }
            }
        }

        echo '</select>';
    }

    // EXCLUDE BY FIELD
    public function fieldExcludeBy() {
        // Get saved values (array because multiple select)
        $options = get_option('jialifn_query_options');
        $selected_values = $options['exclude_by'] ?? [];

        echo '<select class="jialifn-exclude-by" name="jialifn_query_options[exclude_by][]" multiple>';
        echo '<option value="term" ' . selected(in_array('term', $selected_values), true, false) . '>'
                . esc_html__('Term', 'jiali-float-news') .
            '</option>';
        echo '<option value="author" ' . selected(in_array('author', $selected_values), true, false) . '>'
                . esc_html__('Author', 'jiali-float-news') .
            '</option>';
        echo '<option value="manual_sources" ' . selected(in_array('manual_sources', $selected_values), true, false) . '>'
                . esc_html__('Manual sources', 'jiali-float-news') .
            '</option>';
        echo '</select>';
    }

    // EXCLUDED TERMS
    public function fieldExcludedTerms() {
        
        // Get saved values (array of term IDs)
        $options = get_option('jialifn_query_options');
        $saved_ids = $options['excluded_terms'] ?? [];

        echo '<select class="jialifn-excluded-terms" name="jialifn_query_options[excluded_terms][]" multiple>';

        // If saved terms exist → preload them in <option selected>
        if (!empty($saved_ids) && is_array($saved_ids)) {
            foreach ($saved_ids as $term_id) {

                $term = get_term($term_id);

                // Validate term exists
                if ($term && !is_wp_error($term)) {
                    echo '<option value="' . esc_attr($term->term_id) . '" selected>'
                            . esc_html($term->name . ' (' . $term->taxonomy . ')') .
                        '</option>';
                }
            }
        }

        echo '</select>';
    }

    // EXCLUDED AUTHORS
    public function fieldExcludedAuthors() {
        // Get saved options
        $options = get_option('jialifn_query_options', []);
        $saved_ids = $options['excluded_authors'] ?? []; // array of author IDs

        echo '<select class="jialifn-excluded-authors" name="jialifn_query_options[excluded_authors][]" multiple>';

        if (!empty($saved_ids) && is_array($saved_ids)) {

            foreach ($saved_ids as $user_id) {
                $user = get_userdata($user_id);

                if ($user) {
                    $label = $user->display_name . ' (' . $user->user_login . ')';
                    echo '<option value="' . esc_attr($user->ID) . '" selected>'
                        . esc_html($label) .
                        '</option>';
                }
            }
        }

        echo '</select>';

    }

    // MANUAL EXCLUDED SOURCES
    public function fieldManualExcludedSources() {
        // Get saved values (array because multiple select)
        $options = get_option('jialifn_query_options');
        $selected_values = $options['manual_excluded_sources'] ?? [];

        echo '<select class="jialifn-manual-excluded-sources" name="jialifn_query_options[manual_excluded_sources][]" multiple>';

        // Preload saved posts
        if (!empty($selected_values) && is_array($selected_values)) {

            foreach ($selected_values as $post_id) {

                $post = get_post($post_id);

                if ($post && !is_wp_error($post)) {

                    // Label example: "Post Title (post_type)"
                    $label = $post->post_title;

                    echo '<option value="' . esc_attr($post->ID) . '" selected>'
                        . esc_html($label) .
                        '</option>';
                }
            }
        }

        echo '</select>';
    }

    // DATE RANGE
    public function fieldDateRange() {
        // Get saved values (array because multiple select)
        $opts = get_option('jialifn_query_options');
        $value = $opts['date_range'] ?? '';

        echo '<select class="jialifn-date-range" name="jialifn_query_options[date_range]">
            <option value="all" ' . selected($value, 'all', false) . '>'.esc_html__('All', 'jiali-float-news').'</option>
            <option value="past_day"' . selected($value, 'past_day', false) . '>'.esc_html__('Past day', 'jiali-float-news').'</option>
            <option value="past_week"' . selected($value, 'past_week', false) . '>'.esc_html__('Past week', 'jiali-float-news').'</option>
            <option value="past_month"' . selected($value, 'past_month', false) . '>'.esc_html__('Past month', 'jiali-float-news').'</option>
            <option value="past_quarter"' . selected($value, 'past_quarter', false) . '>'.esc_html__('Past quarter', 'jiali-float-news').'</option>
            <option value="past_year"' . selected($value, 'past_year', false) . '>'.esc_html__('Past year', 'jiali-float-news').'</option>
            <option value="custom"' . selected($value, 'custom', false) . '>'.esc_html__('Custom', 'jiali-float-news').'</option>
        </select>';
    }

    // DATE BEFORE
    public function fieldDateBefore() {
        $options = get_option('jialifn_query_options', []);
        $value = $options['date_before'] ?? '';

        echo '<input type="text" 
            class="jialifn-date-before" 
            name="jialifn_query_options[date_before]" 
            placeholder="' . esc_html__('Select ...', 'jiali-float-news') . '" 
            value="' . esc_attr($value) . '" />';
    }

    // DATE AFTER
    public function fieldDateAfter() {
        $options = get_option('jialifn_query_options', []);
        $value = $options['date_after'] ?? '';

        echo '<input type="text" 
            class="jialifn-date-after" 
            name="jialifn_query_options[date_after]" 
            placeholder="' . esc_html__('Select ...', 'jiali-float-news') . '" 
            value="' . esc_attr($value) . '" />';
    }

    // ORDER BY
    public function fieldOrderBy() {
        $options = get_option('jialifn_query_options', []);
        $value = $options['order_by'] ?? '';

        echo '<select name="jialifn_query_options[order_by]">
                <option value="date"' . selected($value, 'date', false) . '>' . esc_html__('Date', 'jiali-float-news') . '</option>
                <option value="title"' . selected($value, 'title', false) . '>' . esc_html__('Title', 'jiali-float-news') . '</option>
                <option value="modified"' . selected($value, 'modified', false) . '>' . esc_html__('Last modified', 'jiali-float-news') . '</option>
                <option value="comment_count"' . selected($value, 'comment_count', false) . '>' . esc_html__('Comment count', 'jiali-float-news') . '</option>
                <option value="rand"' . selected($value, 'rand', false) . '>' . esc_html__('Random', 'jiali-float-news') . '</option>
            </select>';
    }

    // ORDER
    public function fieldOrder() {
        $options = get_option('jialifn_query_options', []);
        $value = $options['order'] ?? '';

        echo '<select name="jialifn_query_options[order]">
                <option value="DESC"' . selected($value, 'DESC', false) . '>' . esc_html__('DESC', 'jiali-float-news') . '</option>
                <option value="ASC"' . selected($value, 'ASC', false) . '>' . esc_html__('ASC', 'jiali-float-news') . '</option>
            </select>';
    }

    // COUNT
    public function fieldCount() {
        $options = get_option('jialifn_query_options', []);
        $value = $options['count'] ?? '';

        echo '<select name="jialifn_query_options[count]">';
        for ($i = 1; $i <= 10; $i++) {
            echo '<option value="' . $i . '" ' . selected($value, $i, false) . '>' . $i . '</option>';
        }
        echo '</select>';
    }

    public function renderPage() {
        ?>
        <div class="wrap jialifn-admin-area">
            <h1>
                <?php echo esc_html__('Float News', 'jiali-float-news') . ' - ' . esc_html__('Query Settings', 'jiali-float-news'); ?>
            </h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('jialifn_query_group');
                do_settings_sections('jialifn-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
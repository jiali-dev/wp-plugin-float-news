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

        add_settings_section(
            'jialifn_query_section',
            'Query Configuration',
            '__return_false',
            'jialifn-settings'
        );

        // SOURCE
        add_settings_field(
            'source',
            'Source',
            [ $this, 'fieldSource' ],
            'jialifn-settings',
            'jialifn_query_section'
        );

        // MANUAL SOURCE
        add_settings_field(
            'manual-sources',
            'Manual sources',
            [ $this, 'fieldManualSources' ],
            'jialifn-settings',
            'jialifn_query_section'
        );

        // INCLUDE TERMS + AUTHORS
        add_settings_field(
            'include-by',
            'Include by',
            [ $this, 'fieldIncludeBy' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-includeby-wrapper'
            ]
        );

        // INCLUDED TERMS
        add_settings_field(
            'included-terms',
            'Included terms',
            [ $this, 'fieldIncludedTerms' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-included-terms-wrapper'
            ]
        );

        // INCLUDED AUTHORS
        add_settings_field(
            'included-authors',
            'Included authors',
            [ $this, 'fieldIncludedAuthors' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-included-authors-wrapper'
            ]
        );

        // EXCLUDE TERMS + AUTHORS
        add_settings_field(
            'exclude-by',
            'Exclude by',
            [ $this, 'fieldExcludeBy' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-excludeby-wrapper'
            ]
        );

        // EXCLUDED TERMS
        add_settings_field(
            'excluded-terms',
            'Excluded terms',
            [ $this, 'fieldExcludedTerms' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-excluded-terms-wrapper'
            ]
        );

        // EXCLUDED AUTHORS
        add_settings_field(
            'excluded-authors',
            'Excluded authors',
            [ $this, 'fieldExcludedAuthors' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-excluded-authors-wrapper'
            ]
        );

        // MANUAL EXCLUDED SOURCES
        add_settings_field(
            'manual-excluded-sources',
            'Manual excluded sources',
            [ $this, 'fieldManualExcludedSources' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-manual-excluded-sources-wrapper'
            ]
        );

        // DATE FILTERS
        add_settings_field(
            'date_range',
            'Date Range',
            [ $this, 'fieldDateRange' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-date-range-wrapper'
            ]
        );

        // DATE BEFORE
        add_settings_field(
            'date_before',
            'Date Before',
            [ $this, 'fieldDateBefore' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-date-before-wrapper'
            ]
        );

        // DATE AFTER
        add_settings_field(
            'date_after',
            'Date After',
            [ $this, 'fieldDateAfter' ],
            'jialifn-settings',
            'jialifn_query_section',
            [
                'class' => 'jialifn-date-after-wrapper'
            ]
        );

        // ORDER
        add_settings_field(
            'orderby',
            'Order By',
            [ $this, 'fieldOrderBy' ],
            'jialifn-settings',
            'jialifn_query_section'
        );

        add_settings_field(
            'order',
            'Order',
            [ $this, 'fieldOrder' ],
            'jialifn-settings',
            'jialifn_query_section'
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
        echo '<option value="' . esc_attr__('manual_selection', 'jiali-float-news') . '" ' . selected($value, 'manual_selection', false) . '>' . esc_html__('Manual Selection', 'jiali-float-news') . '</option>';

        echo '</select>';
    }

    public function fieldManualSources() {
        echo '<select class="jialifn-manual-sources" name="jialifn_query_options[manual_sources][]" multiple= style="width: 100%;">
        </select>';
    }

    public function fieldIncludeBy() {
        echo '<select class="jialifn-include-by" name="jialifn_query_options[include_by][]" multiple= style="width: 100%;">
            <option value="term">'.esc_html__('Term', 'jiali-float-news').'</option>
            <option value="author">'.esc_html__('Author', 'jiali-float-news').'</option>
        </select>';
    }

    public function fieldIncludedTerms() {
        echo '<select class="jialifn-included-terms" name="jialifn_query_options[included_terms][]" multiple= style="width: 100%;">
        </select>';
    }

    public function fieldIncludedAuthors() {
        echo '<select class="jialifn-included-authors" name="jialifn_query_options[included_authors][]" multiple= style="width: 100%;">
        </select>';
    }

    public function fieldExcludeBy() {
        echo '<select class="jialifn-exclude-by" name="jialifn_query_options[exclude_by][]" multiple= style="width: 100%;">
            <option value="term">'.esc_html__('Term', 'jiali-float-news').'</option>
            <option value="author">'.esc_html__('Author', 'jiali-float-news').'</option>
            <option value="author">'.esc_html__('Manual selection', 'jiali-float-news').'</option>
        </select>';
    }

    public function fieldExcludedTerms() {
        echo '<select class="jialifn-excluded-terms" name="jialifn_query_options[excluded_terms][]" multiple= style="width: 100%;">
        </select>';
    }

    public function fieldExcludedAuthors() {
        echo '<select class="jialifn-excluded-authors" name="jialifn_query_options[excluded_authors][]" multiple= style="width: 100%;">
        </select>';
    }

    public function fieldManualExcludedSources() {
        echo '<select class="jialifn-manual-excluded-sources" name="jialifn_query_options[manual_excluded_sources][]" multiple= style="width: 100%;">
        </select>';
    }

    public function fieldDateRange() {
        echo '<select name="jialifn_query_options[date_range]">
            <option value="anytime">All</option>
            <option value="today">Past day</option>
            <option value="week">Past week</option>
            <option value="month">Past month</option>
            <option value="quarter">Past quarter</option>
            <option value="year">Past year</option>
            <option value="custom">Custom</option>
        </select>';
    }

    public function fieldDateBefore() {
        echo '<input type="text" class="jialifn-date-before" name="jialifn_query_options[date_before]" />';
    }

    public function fieldDateAfter() {
        echo '<input type="text" class="jialifn-date-after" name="jialifn_query_options[date_after]" />';
    }

    public function fieldOrderBy() {
        echo '<select name="jialifn_query_options[order_by]">
                <option value="date">Date</option>
                <option value="title">Title</option>
                <option value="modified">Last Modified</option>
                <option value="comment_count">Comment Count</option>
                <option value="rand">Random</option>
            </select>';
    }

    public function fieldOrder() {
        echo '<select name="jialifn_query_options[order]">
                <option value="DESC">DESC</option>
                <option value="ASC">ASC</option>
            </select>';
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
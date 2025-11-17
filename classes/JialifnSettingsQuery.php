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
        echo '<option value="' . esc_attr__('manual_ids', 'jiali-float-news') . '" ' . selected($value, 'manual_ids', false) . '>' . esc_html__('Manual Selection', 'jiali-float-news') . '</option>';

        echo '</select>';
    }

    public function fieldIncludeBy() {
        echo '<select class="jialifn-includeby" name="jialifn_query_options[includeby][]" multiple= style="width: 100%;">
            <option value="term">'.esc_html__('Term', 'jiali-float-news').'</option>
            <option value="author">'.esc_html__('Author', 'jiali-float-news').'</option>
        </select>';
    }

    public function fieldIncludedTerms() {
        echo '<select class="jialifn-includedterms" name="jialifn_query_options[includedterms][]" multiple= style="width: 100%;">
            <option value="term">Term</option>
            <option value="author">Author</option>
        </select>';
    }

    public function fieldIncludedAuthors() {
        echo '<select class="jialifn-includedauthors" name="jialifn_query_options[includedauthors][]" multiple= style="width: 100%;">
            <option value="term">Term</option>
            <option value="author">Author</option>
        </select>';
    }

    public function fieldExcludeBy() {
        echo '<p>Exclude authors / terms form inputs here</p>';
    }

    public function fieldDateRange() {
        echo '<p>Date range fields here</p>';
    }

    public function fieldOrderBy() {
        echo '<select name="jialifn_query_options[orderby]">
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
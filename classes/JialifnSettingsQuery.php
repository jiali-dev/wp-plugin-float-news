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
            'include',
            'Include',
            [ $this, 'fieldInclude' ],
            'jialifn-settings',
            'jialifn_query_section'
        );

        // EXCLUDE TERMS + AUTHORS
        add_settings_field(
            'exclude',
            'Exclude',
            [ $this, 'fieldExclude' ],
            'jialifn-settings',
            'jialifn_query_section'
        );

        // DATE FILTERS
        add_settings_field(
            'date_range',
            'Date Range',
            [ $this, 'fieldDateRange' ],
            'jialifn-settings',
            'jialifn_query_section'
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

        echo '<select name="jialifn_query_options[source]">';
        echo '<option value="latest" ' . selected($value, 'latest', false) . '>Latest Posts</option>';
        echo '<option value="manual" ' . selected($value, 'manual', false) . '>Manual IDs</option>';

        foreach ($post_types as $key => $pt) {
            echo "<option value='{$key}' " . selected($value, $key, false) . ">{$pt->label}</option>";
        }
        echo '</select>';
    }

    public function fieldInclude() {
        echo '<p>Include authors / terms form inputs here</p>';
    }

    public function fieldExclude() {
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
        <div class="wrap">
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
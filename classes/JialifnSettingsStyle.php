<?php

class JialifnSettingsStyle {

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

        register_setting('jialifn_style_group', 'jialifn_style_options');

        add_settings_section(
            'jialifn_style_section',
            'Style Configuration',
            '__return_false',
            'jialifn-style-settings'
        );

        add_settings_field(
            'theme',
            'Theme',
            [ $this, 'fieldTheme' ],
            'jialifn-style-settings',
            'jialifn_style_section'
        );
    }

    public function fieldTheme() {
        echo '<select name="jialifn_style_options[theme]">
                <option value="light">Light</option>
                <option value="dark">Dark</option>
              </select>';
    }

    public function renderPage() {
        ?>
        <div class="wrap">
            <h1>Float News – Style Settings</h1>

            <form action="options.php" method="post">
                <?php
                settings_fields('jialifn_style_group');
                do_settings_sections('jialifn-style-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
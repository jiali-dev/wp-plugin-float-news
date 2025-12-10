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
        add_action('wp_enqueue_scripts', [$this, 'enqueueStyles']);
    }

    public function registerSettings() {

        register_setting('jialifn_style_group', 'jialifn_style_options');

        add_settings_section(
            'jialifn_style_section',
            esc_html__('Style configuration', 'jiali-float-news'),
            '__return_false',
            'jialifn-style-settings'
        );

        add_settings_field(
            'toast-background-color',
            esc_html__('Toast background color', 'jiali-float-news'),
            [ $this, 'fieldToastBackgroundColor' ],
            'jialifn-style-settings',
            'jialifn_style_section'
        );

        add_settings_field(
            'progress-bar-color',
            esc_html__('Progress bar color', 'jiali-float-news'),
            [ $this, 'fieldProgressBarColor' ],
            'jialifn-style-settings',
            'jialifn_style_section'
        );

        add_settings_field(
            'title-color',
            esc_html__('Title color', 'jiali-float-news'),
            [ $this, 'fieldTitleColor' ],
            'jialifn-style-settings',
            'jialifn_style_section'
        );

        add_settings_field(
            'title-size',
            esc_html__('Title size (px)', 'jiali-float-news'),
            [ $this, 'fieldTitleSize' ],
            'jialifn-style-settings',
            'jialifn_style_section'
        );

        add_settings_field(
            'slider-duration',
            esc_html__('Slider duration (ms)', 'jiali-float-news'),
            [ $this, 'fieldSliderDuration' ],
            'jialifn-style-settings',
            'jialifn_style_section'
        );
    }

    public function fieldToastBackgroundColor() {
        // Get saved values (array of term IDs)
        $styles = get_option('jialifn_style_options');
        $color = $styles['toast_background_color'] ?? '#333';

        echo '<input type="text" class="jialifn-color-field jialifn-toast-background-color" name="jialifn_style_options[toast_background_color]" value="' . esc_attr($color) . '">';
    }

    public function fieldProgressBarColor() {
        // Get saved values (array of term IDs)
        $styles = get_option('jialifn_style_options');
        $color = $styles['progress_bar_color'] ?? '#00bfff';

        echo '<input type="text" class="jialifn-color-field jialifn-progress-bar-color" name="jialifn_style_options[progress_bar_color]" value="' . esc_attr($color) . '">';
    }

    public function fieldSliderDuration() {
        // Get saved values (array of term IDs)
        $styles = get_option('jialifn_style_options');
        $color = $styles['slider_duration'] ?? '4000';

        echo '<input type="number" class="jialifn-slider-duration" name="jialifn_style_options[slider_duration]" value="' . esc_attr($color) . '">';
    }

    public function fieldTitleColor() {
        // Get saved values (array of term IDs)
        $styles = get_option('jialifn_style_options');
        $color = $styles['title_color'] ?? '#ffffff';

        echo '<input type="text" class="jialifn-color-field jialifn-title-color" name="jialifn_style_options[title_color]" value="' . esc_attr($color) . '">';
    }

    public function fieldTitleSize() {
        // Get saved values (array of term IDs)
        $styles = get_option('jialifn_style_options');
        $color = $styles['title_size'] ?? 'inherit';

        echo '<input type="number" class="jialifn-title-size" name="jialifn_style_options[title_size]" value="' . esc_attr($color) . '">';
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

    /**
     * Enqueue dynamic custom styles
     */
    public function enqueueStyles() {
        $styles = get_option('jialifn_style_options');
        $toast_background_color = esc_attr($styles['toast_background_color']) ?: '#333';
        $progress_bar_color = esc_attr($styles['progress_bar_color']) ?: '#00bfff';
        $slider_duration = esc_attr($styles['slider_duration']) ?: '4000';
        $title_color = esc_attr($styles['title_color']) ?: '#ffffff';
        $title_size = esc_attr($styles['title_size']) ?: 'inherit';
        
        $toastCss = ".jialifn-toast { background: {$toast_background_color} !important; }";
        wp_add_inline_style('jialifn-toast', $toastCss);

        $sliderCss = "
            .jialifn-progress-bar { background: {$progress_bar_color} !important; }
            .jialifn-slides__title { 
                font-size: {$title_size}px !important;
                color: {$title_color} !important;
            }
        ";
        wp_add_inline_style('jialifn-slider', $sliderCss);

        wp_localize_script(
            'jialifn-slider',
            'jialifn_style_settings',
            ['slideDuration' => $slider_duration]
        );
    }
}
<?php

/**
 * Plugin Name: Jiali Float News
 * Description: Display up to 10 recent or random posts in a floating, auto-sliding notification bar. Fully customizable by category, tag, and style.
 * Version: 1.0.1
 * Author: Mahyar Rad
 * Author URI: https://mahyarerad.com/
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: jiali-float-news
 * Domain Path: /languages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) exit;

// Include Core class
require_once plugin_dir_path(__FILE__) . 'JialifnCore.php';

// Start plugin
JialifnCore::getInstance();

// Activation hook
register_activation_hook(__FILE__, ['JialifnCore', 'registerActivation']);

// Uninstallation hook
register_uninstall_hook(__FILE__, ['JialifnCore', 'uninstallation'] );

/*
Prefix Guidance for Aabgine POS Plugin

Constants:      JIALIFN_      (e.g. JIALIFN_PLUGIN_URL)
Class Names:    Jialifn       (e.g. JialifnCore )
DB Tables:      jialifn_         (e.g. jialifn_orders, jialifn_customers)
Functions:      jialifn_         (e.g.  jialifn_add_bookmark())
Text Domain:    jiali-float-news       (for translations)

Always use these prefixes to avoid conflicts and keep code organized.
*/

?>

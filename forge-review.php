<?php

/**
 * Plugin Name: Forge Review
 * Plugin URI: https://github.com/MahimZaman/forge-review
 * Description: Review anything 
 * Version: 1.0.0
 * Author: Mahim Zaman (Condixia)
 * Author URI: https://condixia.com
 * Text Domain: fr-text
 */

defined('ABSPATH') || exit;

define('FR_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('FR_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('FR_DIR', __FILE__);
define('FR_VER', time());

require_once FR_PATH . 'includes/class-loader.php';

add_action('plugins_loaded', function () {
    FR_Loader::init();
});

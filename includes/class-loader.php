<?php

defined('ABSPATH') || exit;

class FR_Loader
{
    public static function init()
    {
        self::dependencies();
        self::instances();
        self::hooks();
    }

    private static function dependencies()
    {
        require_once FR_PATH . 'includes/class-cpt.php';
        require_once FR_PATH . 'includes/class-assets.php';
        require_once FR_PATH . 'includes/class-shortcodes.php';
        require_once FR_PATH . 'includes/class-ajax.php';
        require_once FR_PATH . 'includes/class-metaboxes.php';
        require_once FR_PATH . 'includes/class-functions.php';
    }

    private static function instances()
    {
        new FR_CPT();
        new FR_Assets();
        new FR_Shortcodes();
        new FR_Ajax();
        new FR_Meta();
    }

    private static function hooks() {}
}

<?php

defined('ABSPATH') || exit;

class FR_Assets
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'public_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    public function public_scripts()
    {
        wp_enqueue_style('remixicon', 'https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css', [], FR_VER, 'all');
        wp_enqueue_style('fr_universal', FR_URL . 'assets/css/universal.css', [], FR_VER, 'all');
        wp_enqueue_script('fr_universal', FR_URL . 'assets/js/universal.js', [], FR_VER, true);
        wp_enqueue_style('frontend', FR_URL . 'assets/css/frontend.css', [], FR_VER, 'all');
        wp_enqueue_script('frontend', FR_URL . 'assets/js/frontend.js', [], FR_VER, true);
        wp_localize_script('frontend', 'frLocal', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }

    public function admin_scripts()
    {
        wp_enqueue_style('remixicon', 'https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css', [], FR_VER, 'all');
        wp_enqueue_style('admin', FR_URL . 'assets/css/admin.css', [], FR_VER, 'all');
        wp_enqueue_script('admin', FR_URL . 'assets/js/admin.js', [], FR_VER, true);
    }
}

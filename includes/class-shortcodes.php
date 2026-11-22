<?php

defined('ABSPATH') || exit;

class FR_Shortcodes
{
    public function __construct()
    {
        add_shortcode('fr_review_form', [$this, 'fr_review_form']);
        add_shortcode('fr_reviews', [$this, 'fr_reviews']);
        add_shortcode('fr_review_summary', [$this, 'fr_review_summary']);
    }

    public function fr_review_summary()
    {
        ob_start();
        require FR_PATH . 'templates/template-review_summary.php';
        return ob_get_clean();
    }

    public function fr_review_form()
    {
        ob_start();
        require_once FR_PATH . 'templates/template-review_form.php';
        return ob_get_clean();
    }

    public function fr_reviews()
    {
        ob_start();
        require_once FR_PATH . 'templates/template-reviews.php';
        return ob_get_clean();
    }
}

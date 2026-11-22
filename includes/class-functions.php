<?php

defined('ABSPATH') || exit;

class FR_Functions
{
    public static function render_rating($rating)
    {
        $html = '<div class="fr-stars">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '<span class="fr-star filled"><i class="ri-star-fill"></i></span>';
            } else {
                $html .= '<span class="fr-star"><i class="ri-star-fill"></i></span>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}

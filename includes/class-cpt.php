<?php

defined('ABSPATH') || exit;

class FR_CPT
{
    public function __construct()
    {
        add_action('init', [$this, 'register_post_type']);
        add_action('admin_menu', [$this, 'remove_add_new']);
        add_action('admin_head', [$this, 'hide_add_new_button']);

        add_filter('manage_fr_reviews_posts_columns', [$this, 'add_columns']);
        add_action('manage_fr_reviews_posts_custom_column', [$this, 'render_columns'], 10, 2);
        add_filter('manage_edit-fr_reviews_sortable_columns', [$this, 'sortable_columns']);
    }

    public function add_columns($columns)
    {

        return [
            'cb'        => $columns['cb'],
            'title'     => 'Title',
            'fr_rating' => 'Rating',
            'fr_name'   => 'Name',
            'fr_email'  => 'Email',
            'fr_post'   => 'Post',
            'date'      => 'Date',
        ];
    }

    public function render_columns($column, $post_id)
    {

        switch ($column) {

            case 'fr_rating':
                $rating = get_post_meta($post_id, '_fr_rating', true);
                echo $this->render_stars($rating);
                break;

            case 'fr_name':
                echo esc_html(get_post_meta($post_id, '_fr_name', true));
                break;

            case 'fr_email':
                $email = get_post_meta($post_id, '_fr_email', true);
                echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
                break;

            case 'fr_post':
                $related_post = get_post_meta($post_id, '_fr_post_id', true);

                if ($related_post) {
                    $title = get_the_title($related_post);
                    $link  = get_edit_post_link($related_post);

                    echo '<a href="' . esc_url($link) . '">' . esc_html($title) . '</a>';
                } else {
                    echo '—';
                }
                break;
        }
    }

    public function sortable_columns($columns)
    {
        $columns['fr_rating'] = 'fr_rating';
        return $columns;
    }

    private function render_stars($rating)
    {

        $rating = intval($rating);
        $html = '<div class="fr-admin-stars">';

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '<span class="star filled">★</span>';
            } else {
                $html .= '<span class="star">★</span>';
            }
        }

        $html .= '</div>';
        return $html;
    }

    public function register_post_type()
    {

        register_post_type('fr_reviews', [
            'labels' => [
                'name' => 'Reviews',
                'singular_name' => 'Review',
            ],
            'public' => false,
            'show_ui' => true,
            'menu_icon' => 'dashicons-star-filled',

            'supports' => ['title'], // ONLY TITLE

            'capability_type' => 'post',
            'map_meta_cap' => true,

            // Disable create capability
            'capabilities' => [
                'create_posts' => 'do_not_allow',
            ],

            'show_in_menu' => true,
        ]);
    }

    public function remove_add_new()
    {
        remove_submenu_page(
            'edit.php?post_type=fr_reviews',
            'post-new.php?post_type=fr_reviews'
        );
    }

    public function hide_add_new_button()
    {
        global $typenow;

        if ($typenow === 'fr_reviews') {
            echo '<style>
                .page-title-action { display: none !important; }
            </style>';
        }
    }
}

<?php

if (!defined('ABSPATH')) exit;

class FR_Meta
{

    public function __construct()
    {
        add_action('add_meta_boxes', [$this, 'register_metabox']);
    }

    public function register_metabox()
    {
        add_meta_box(
            'fr_review_details',
            'Review Details',
            [$this, 'render_metabox'],
            'fr_reviews',
            'normal',
            'high'
        );
    }

    public function render_metabox($post)
    {

        $name     = get_post_meta($post->ID, '_fr_name', true);
        $email    = get_post_meta($post->ID, '_fr_email', true);
        $rating   = get_post_meta($post->ID, '_fr_rating', true);
        $feedback = get_post_meta($post->ID, '_fr_feedback', true);
        $post_id  = get_post_meta($post->ID, '_fr_post_id', true);

?>
        <div class="fr-meta-box">

            <div class="fr-meta-row">
                <label>Name</label>
                <div class="fr-meta-value"><?php echo esc_html($name); ?></div>
            </div>

            <div class="fr-meta-row">
                <label>Email</label>
                <div class="fr-meta-value"><?php echo esc_html($email); ?></div>
            </div>

            <div class="fr-meta-row">
                <label>Rating</label>
                <div class="fr-meta-value fr-rating">
                    <?php echo $this->render_stars($rating); ?>
                </div>
            </div>

            <div class="fr-meta-row">
                <label>Feedback</label>
                <div class="fr-meta-value fr-feedback">
                    <?php echo esc_html($feedback); ?>
                </div>
            </div>

            <div class="fr-meta-row">
                <label>Review for</label>
                <div class="fr-meta-value">
                    <a target="_blank" href="<?php echo get_permalink($post_id) ?>"><?php echo get_the_title($post_id); ?></a>
                </div>
            </div>

        </div>
<?php
    }

    private function render_stars($rating)
    {
        $html = '<div class="fr-stars">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '<span class="fr-star filled">★</span>';
            } else {
                $html .= '<span class="fr-star">★</span>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}

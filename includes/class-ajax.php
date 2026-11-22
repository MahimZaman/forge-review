<?php

defined('ABSPATH') || exit;

class FR_Ajax
{
    public function __construct()
    {
        $handlers = [
            'fr_submit_review',
            'fr_get_review_summary',
            'fr_get_reviews'
        ];

        if (!empty($handlers)) {
            foreach ($handlers as $handler) {
                add_action("wp_ajax_{$handler}", [$this, $handler]);
                add_action("wp_ajax_nopriv_{$handler}", [$this, $handler]);
            }
        }
    }

    public function fr_get_review_summary()
    {

        global $wpdb;

        $post_id = intval($_POST['post_id']);

        $meta_key_rating = '_fr_rating';
        $meta_key_post   = '_fr_post_id';

        // Direct DB query (fast 🔥)
        $results = $wpdb->get_row($wpdb->prepare("
        SELECT 
            AVG(CAST(r.meta_value AS DECIMAL(10,2))) as avg_rating,
            COUNT(*) as total_reviews
        FROM {$wpdb->postmeta} r
        INNER JOIN {$wpdb->postmeta} p 
            ON r.post_id = p.post_id
        WHERE r.meta_key = %s
        AND p.meta_key = %s
        AND p.meta_value = %d
    ", $meta_key_rating, $meta_key_post, $post_id));

        $avg   = round($results->avg_rating, 2);
        $count = intval($results->total_reviews);

        wp_send_json_success([
            'avg'   => $avg,
            'count' => $count
        ]);
    }

    public function fr_get_reviews()
    {
        $post_id = sanitize_text_field($_POST['post_id']);
        $sort = sanitize_text_field($_POST['sort']);
        $page = $_POST['page'] ? sanitize_text_field($_POST['page']) : 1;

        $args = [
            'post_type' => 'fr_reviews',
            'posts_per_page' => 5,
            'paged' => $page,
            'meta_query' => [
                [
                    'key' => '_fr_post_id',
                    'value' => $post_id,
                    'compare' => '='
                ]
            ]
        ];

        if ($sort == 'newest') {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        } elseif ($sort == 'oldest') {
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
        } elseif ($sort == 'top-rated') {
            $args['meta_key'] = '_fr_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
        } elseif ($sort == 'low-rated') {
            $args['meta_key'] = '_fr_rating';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'ASC';
        }

        $reviews = new WP_Query($args);

        ob_start();
        if ($reviews->have_posts()): ?>
            <?php while ($reviews->have_posts()):
                $reviews->the_post();
                $review_post = get_post_meta(get_the_ID(), '_fr_post_id', true);
                $post_title = get_the_title($review_post);
                $rating = (int) get_post_meta(get_the_ID(), '_fr_rating', true);
                $feedback = get_post_meta(get_the_ID(), '_fr_feedback', true);
            ?>
                <div class="fr-review-item">
                    <div class="fr-user">
                        <?php echo get_post_meta(get_the_ID(), '_fr_name', true); ?>
                    </div>
                    <div class="fr-review-detail">
                        <?php echo FR_Functions::render_rating($rating); ?>
                        <h3 class="fr-post-title"><?php echo $post_title; ?></h3>
                        <p class="fr-feedback"><?php echo $feedback; ?></p>
                        <span class="fr-review-date"><?php echo get_the_date(); ?></span>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        <?php endif;
        $review_items = ob_get_clean();

        ob_start();
        $total   = $reviews->max_num_pages;

        if ($total > 1): ?>

            <div class="fr-pagination">
                <?php for ($i = 1; $i <= $total; $i++): ?>
                    <span class="fr-page <?php echo $i == $page ? 'active' : '' ?>"><?php echo $i; ?></span>
                <?php endfor; ?>
            </div>

<?php endif;
        $pagination = ob_get_clean();

        wp_send_json_success([
            'reviews' => $review_items,
            'pages' => $pagination
        ]);
    }

    public function fr_submit_review()
    {
        $rating = sanitize_text_field($_POST['rating']);
        $feedback = sanitize_text_field($_POST['feedback']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_text_field($_POST['email']);
        $post_id = sanitize_text_field($_POST['post_id']);

        $review_id = wp_insert_post([
            'post_type' => 'fr_reviews',
            'post_title' => $feedback,
            'post_status' => 'publish'
        ]);

        if (!$review_id) {
            wp_send_json_error(__('Something went wrong. Try again.', 'fr-text'));
        }

        update_post_meta($review_id, '_fr_name', $name);
        update_post_meta($review_id, '_fr_rating', $rating);
        update_post_meta($review_id, '_fr_feedback', $feedback);
        update_post_meta($review_id, '_fr_email', $email);
        update_post_meta($review_id, '_fr_post_id', $post_id);

        wp_send_json_success(__('Review posted successfull!', 'fr-text'));
    }
}

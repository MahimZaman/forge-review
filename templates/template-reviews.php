<?php

defined('ABSPATH') || exit;

global $post;

$post_id = $post->ID;

$args = [
    'post_type' => 'fr_reviews',
    'posts_per_page' => 5,
    'paged' => 1,
    'meta_query' => [
        [
            'key' => '_fr_post_id',
            'value' => $post_id,
            'compare' => '='
        ]
    ]
];

$reviews = new WP_Query($args); ?>

<div class="fr-reviews-container">
    <div class="fr-reviews-header">
        <select id="sort-review">
            <option value=""><?php _e('Sort', 'fr-text'); ?></option>
            <option value="newest"><?php _e('Newest', 'fr-text'); ?></option>
            <option value="oldest"><?php _e('Oldest', 'fr-text'); ?></option>
            <option value="top-rated"><?php _e('Top rated', 'fr-text'); ?></option>
            <option value="low-rated"><?php _e('Low rated', 'fr-text'); ?></option>
        </select>
        <input type="hidden" value="<?php echo $post_id; ?>" class="fr-post_id">
    </div>
    <?php if ($reviews->have_posts()): ?>
        <div class="fr-reviews">
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
        </div>
    <?php else: ?>
        <p class="fr_noposts"><?php _e('No reviews yet', 'fr-text'); ?></p>
    <?php
    endif;
    ?>
    <div class="fr-reviews-footer">
        <?php
        $current = max(1, get_query_var('paged'));
        $total   = $reviews->max_num_pages;

        if ($total > 1): ?>

            <div class="fr-pagination">
                <?php for ($i = 1; $i <= $total; $i++): ?>
                    <span class="fr-page <?php echo $i == 1 ? 'active' : '' ?>"><?php echo $i; ?></span>
                <?php endfor; ?>
            </div>

        <?php endif; ?>
    </div>
</div>
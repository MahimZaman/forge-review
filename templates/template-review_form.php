<?php

defined('ABSPATH') || exit;
global $post;
?>

<div class="fr-review-form">
    <button type="button" id="show-review"><?php _e('Write a review', 'fr-text'); ?></button>
    <form action="" method="post">
        <input type="hidden" name="post_id" id="fr-post_id" value="<?php echo $post->ID; ?>">
        <div class="fr-form-group fr-form-full">
            <label for="fr-rating" class="fr-form-label"><?php _e('What would you rate this product? (required)', 'fr-text'); ?></label>
            <select name="rating" id="fr-rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>
        <div class="fr-form-group fr-form-full">
            <label for="fr-feedback" class="fr-form-label"><?php _e('Tell us your feedback about the product (required)', 'fr-text'); ?></label>
            <textarea name="feedback" id="fr-feedback" required></textarea>
        </div>
        <div class="fr-form-group fr-form-half">
            <label for="fr-rating" class="fr-form-label"><?php _e('Your name (required)', 'fr-text'); ?></label>
            <input type="text" name="name" id="fr-name" required>
        </div>
        <div class="fr-form-group fr-form-half">
            <label for="fr-rating" class="fr-form-label"><?php _e('Your email (required)', 'fr-text'); ?></label>
            <input type="email" name="email" id="fr-email" required>
        </div>
        <div class="fr-form-action">
            <button type="button" id="cancel-review"><?php _e('Cancel', 'fr-text'); ?></button>
            <button type="submit" id="submit-review">
                <span class="fr-btn-text"><?php _e('Submit', 'fr-text'); ?></span>
                <span class="fr-loader"></span>
            </button>
        </div>
    </form>
</div>
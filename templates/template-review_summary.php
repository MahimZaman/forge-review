<?php
defined('ABSPATH') || exit;

global $post;

$atts = shortcode_atts([
    'post_id' => $post->ID,
], $atts);
?>

<div class="fr-summary" data-post-id="<?php echo esc_attr($atts['post_id']); ?>">

    <!-- Skeleton Loader -->
    <div class="fr-skeleton">
        <div class="fr-skel-rating"></div>
        <div class="fr-skel-stars"></div>
        <div class="fr-skel-text"></div>
    </div>

    <!-- Actual Content -->
    <div class="fr-summary-content" style="display:none;"></div>

</div>
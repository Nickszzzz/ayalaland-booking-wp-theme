<?php

/**
 * Image and Icon Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'image-icon-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'image-icon';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

$image = get_field('image');
$icon = get_field('icon');
$color = get_field('color');
if( !empty($color) ) {
    $className .= ' ' . $color;
}
?>
<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <?php if (is_user_logged_in() && !$image && !$icon) echo 'Pls Add Image and Icon'; ?>
    <figure class="image-icon-figure">
        <?php if ($image) echo wp_get_attachment_image( $image, 'full' ); ?>
        <div class="icon">
            <?php if ($icon) echo wp_get_attachment_image( $icon, 'full' ); ?>
        </div>
    </figure>
</div>
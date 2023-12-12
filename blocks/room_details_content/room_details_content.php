<?php 
    $id = get_the_ID();

?>

<h2 class="wp-block-heading has-display-lg-font-size"
    style="margin-bottom:var(--wp--preset--spacing--10);font-style:normal;font-weight:600">Property Details</h2>



<div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
    style="margin-bottom:var(--wp--preset--spacing--20)">
    <p
        class="has-contrast-3-color has-text-color has-link-color has-text-md-font-size wp-elements-7f3f3d019a4063672b44e23fc688109b">
        <?php echo strip_tags(get_the_content()); ?></p>

</div>



<h3 class="wp-block-heading has-display-xs-font-size"
    style="margin-top:0px;margin-bottom:var(--wp--preset--spacing--10);font-style:normal;font-weight:600">Property
    Details</h3>



<figure class="wp-block-table product-single-page__property-details has-text-md-font-size"
    style="margin-bottom:var(--wp--preset--spacing--20)">
    <table class="has-base-2-background-color has-background has-fixed-layout">
        <tbody>
            <tr>
                <td>GLA:</td>
                <td><?php echo get_field('gla', $id); ?></td>
            </tr>
            <tr>
                <td>No. of Floors</td>
                <td><?php echo get_field('no_of_floors', $id); ?></td>
            </tr>
            <tr>
                <td>Floor Plate:</td>
                <td><?php echo get_field('floor_plate', $id); ?></td>
            </tr>
            <tr>
                <td>Density:</td>
                <td><?php echo get_field('density', $id); ?></td>
            </tr>
        </tbody>
    </table>
</figure>

<?php
// Get the product gallery attachment IDs
$product_gallery_ids = get_post_meta($id, '_product_image_gallery', true);

// Convert the comma-separated list of IDs into an array
$product_gallery_ids = explode(',', $product_gallery_ids);

// Get the gallery images
$gallery_images = array();

foreach ($product_gallery_ids as $attachment_id) {
    $image_url = wp_get_attachment_url($attachment_id);
    $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

    // Add image data to the array
    $gallery_images[] = array(
        'url' => $image_url,
        'alt' => $image_alt,
    );
}

?>

<figure
    class="wp-block-gallery has-nested-images columns-default is-cropped product-single-page__gallery wp-block-gallery-3 is-layout-flex wp-block-gallery-is-layout-flex"
    style="margin-bottom:var(--wp--preset--spacing--20)">
    <?php
    if (!empty($gallery_images)) {
        foreach ($gallery_images as $image) {
            $image_id = attachment_url_to_postid($image['url']);
        ?>
    <figure class="wp-block-image size-large"><img width="1024" height="576"
            src="<?php echo esc_url($image['url']); ?>"
            alt="<?php echo esc_attr($image['alt']); ?>" class="wp-image-<?php echo $image_id; ?>"
            srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($image_id)); ?>"
            sizes="(max-width: 1024px) 100vw, 1024px"></figure>
    <?php
        }
    }
    ?>
</figure>

<?php
// Get the video thumbnail from ACF
$video_thumbnail = get_field('video_information_thumbnail', $id);
// Check if the video thumbnail exists
if ($video_thumbnail) {
    ?>
    <figure class="wp-block-image size-large product-single-page__video">
        <img width="1024" height="576" src="<?php echo esc_url($video_thumbnail['url']); ?>" alt="<?php echo esc_attr($video_thumbnail['alt']); ?>" class="wp-image-<?php echo esc_attr($video_thumbnail['ID']); ?>"
            srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($video_thumbnail['ID'])); ?>"
            sizes="(max-width: 1024px) 100vw, 1024px">
            <a href="<?php echo get_field('video_information_link', $id)['url']; ?>" class="popup-youtube"></a>
    </figure>
    <?php
}

?>


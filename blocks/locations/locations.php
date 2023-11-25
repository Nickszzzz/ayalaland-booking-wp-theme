<?php

// Get the current post ID on a single post page
$location_id = get_the_ID();
$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,  // Set to -1 to retrieve all posts
    'meta_query' => array(
        array(
            'key' => 'location',  // Replace 'location' with the actual ACF field key
            'value' => $location_id,  // Replace 'desired_location' with the location you want to filter
            'compare' => '=',  // Use '=' to match exactly
        ),
    ),
);

$query = new WP_Query($args);

?>
<div
    class="wp-block-group offices has-global-padding is-layout-constrained wp-container-core-group-layout-6 wp-block-group-is-layout-constrained">
<?php

if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

       ?>
    <div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile office__item">
        <div class="wp-block-media-text__content">
            <h2 class="wp-block-heading has-display-md-font-size" style="font-style:normal;font-weight:600"><?php the_title(); ?></h2>



            <ul
                class="is-style-hourly-rate has-accent-color has-text-color has-link-color wp-elements-483757c2cb61131d0b24ce02e01252c1">
                <li>Hourly Rate: ₱ 650.00</li>



                <li>Whole Day Rate: ₱ 10,000.00</li>
            </ul>



            <p class="has-contrast-3-color has-text-color has-link-color wp-elements-ebe791791671ecdd2011a3ff2dae1553"><?php the_excerpt(); ?></p>



            <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                <div class="wp-block-button is-style-button-arrow"><a href="<?php the_permalink(); ?>"
                        class="wp-block-button__link has-accent-color has-text-color has-link-color wp-element-button">More
                        Details</a></div>
            </div>
        </div>
        <figure class="wp-block-media-text__media"><?php the_post_thumbnail(); ?></figure>
    </div>



    <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>



       <?php

    endwhile;
    wp_reset_postdata();  // Reset the post data
else :
    echo 'No products found with the specified location.';
endif;
?>

<div
        class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-core-buttons-layout-6 wp-block-buttons-is-layout-flex">
        <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Load More</a></div>
    </div>
</div>
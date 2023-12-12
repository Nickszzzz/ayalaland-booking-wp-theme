<?php

// Get the current post ID on a single post page
$location_id = get_the_ID();

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,  // Set to -1 to retrieve all posts
    'meta_query' => array(
        array(
            'key' => 'room_description_location',  // Replace 'location' with the actual ACF field key
            'value' => $location_id,  // Replace 'desired_location' with the location you want to filter
            'compare' => '=',  // Use '=' to match exactly
        ),
    ),
);

$query = new WP_Query($args);
?>

<?php

if ($query->have_posts()) :
    $count = 1;

    ?>
<div
    class="wp-block-group offices has-global-padding is-layout-constrained wp-container-core-group-layout-6 wp-block-group-is-layout-constrained">
    <?php
    while ($query->have_posts()) : $query->the_post();
            // Get the post ID
$id = get_the_ID();

       ?>
    <div class="wp-block-media-text has-media-on-the-right is-stacked-on-mobile office__item" style="<?php echo ($count % 2 == 0) ? 'flex-direction: row-reverse;' : ''; ?>margin-bottom: 100px;">
        <div class="wp-block-media-text__content">
            <h2 class="wp-block-heading has-display-md-font-size" style="font-style:normal;font-weight:600"><?php the_title(); ?></h2>



            <ul
                class="is-style-hourly-rate has-accent-color has-text-color has-link-color wp-elements-483757c2cb61131d0b24ce02e01252c1">
                <li>Hourly Rate: Php <?php echo number_format(get_field('rates_hourly_rate', $id), 2, '.', ','); ?></li>


                <?php 
                    $daily_rate = get_field('rates_daily_rate', $id);
                        if(!empty($daily_rate)) {?>
                            <li>Whole Day Rate: Php <?php echo number_format(get_field('rates_daily_rate', $id), 2, '.', ','); ?></li>
                <?php
                        }
                ?>
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
       <?php
        $count++;
    endwhile;
    wp_reset_postdata();  // Reset the post data
    ?>
    </div>
    <?php
else :
    echo 'No rooms found with the specified location.';
endif;
?>

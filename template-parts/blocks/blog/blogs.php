<?php

/**
 * Testimonial Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
// $args = array(
//     'post_type' => 'post',
//     'post_status' => 'publish',
//     'posts_per_page' => $post_per_page,
//     'orderby' => $post_order_by,
//     'order' => $sort_by,
//     'paged' => $paged
// );
// $loop = new WP_Query($args);
// while ($loop->have_posts()) {
//     $loop->the_post();
//     $id = get_the_ID();
// }

// Create a new WP_Query object
// $sort = the_field('sort');
$query = new WP_Query(
    array(
        'post_type' => 'post',
        // Specify the post type you want to query
        'posts_per_page' => get_field('show_all') == 'yes' ? -1 : 3,
        'order_by' => get_field('sort') ,
        // Retrieve all posts
        'post_status' => 'publish' // Retrieve only published posts
    )
);

// Check if the query has any posts
if ($query->have_posts()) {
    // Count the number of available posts?>
    <div
        class="wp-block-group alignwide animated has-global-padding is-layout-constrained o-anim-ready fadeInUp delay-100ms slow">
        <?php
        $post_count = $query->found_posts;

        ?>

        <div class="wp-block-group alignwide has-global-padding is-layout-constrained">
            <?php

            if ($post_count == 1) {
                while ( $query->have_posts() ) : $query->the_post();

                ?>
<div style="height:110px" aria-hidden="true" class="wp-block-spacer"></div>
                <div class="wp-block-group alignwide blogs-card-wrapper-not-3-grid has-global-padding is-layout-constrained">
                    <div class="wp-block-media-text alignwide is-stacked-on-mobile blog-1">
                        <figure class="wp-block-media-text__media"><?php the_post_thumbnail(); ?></figure>
                        <div class="wp-block-media-text__content">
                            <h4 class="wp-block-heading has-text-align-left"
                                style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px"><strong><?php the_title(); ?></strong></h4>



                            <p class="has-text-align-left"><?php the_excerpt(); ?></p>



                            <div class="wp-block-buttons is-layout-flex">
                                <div class="wp-block-button is-style-secondary-button"><a href="<?php the_permalink(); ?>"
                                        class="wp-block-button__link wp-element-button">Read more</a></div>
                            </div>
                        </div>
                    </div>
                </div>
<div style="height:110px" aria-hidden="true" class="wp-block-spacer"></div>
                <?php
           endwhile; 
        }
            ?>

            <?php

            if ($post_count == 2) {
                ?>
<div style="height:110px" aria-hidden="true" class="wp-block-spacer"></div>
                <div class="wp-block-group alignwide blogs-card-wrapper-not-3-grid is-nowrap blog-2 is-layout-flex wp-container-26">
                    <?php
                    while ( $query->have_posts() ) : $query->the_post();
                    ?>
                    <div class="wp-block-media-text alignwide is-stacked-on-mobile ">
                        <figure class="wp-block-media-text__media"><?php the_post_thumbnail() ?></figure>
                        <div class="wp-block-media-text__content">
                            <h4 class="wp-block-heading has-text-align-left"
                                style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px"><strong><?php the_title(); ?></strong></h4>



                            <p class="has-text-align-left"><?php the_excerpt(); ?></p>



                            <div class="wp-block-buttons is-layout-flex">
                                <div class="wp-block-button is-style-secondary-button"><a href="<?php the_permalink(); ?>"
                                        class="wp-block-button__link wp-element-button">Read more</a></div>
                            </div>
                        </div>
                    </div>

                    <?php
endwhile; 
                    ?>
                </div>
            </div>
<div style="height:110px" aria-hidden="true" class="wp-block-spacer"></div>
            <?php
            }

            if ($post_count > 2) {

                ?>




            <div class="wp-block-group alignwide <?php echo get_field('show_all') == 'yes' ? 'show-all-blogs' : ''; ?>  blogs-cards-wrapper has-global-padding is-layout-constrained">
            <?php
                    while ( $query->have_posts() ) : $query->the_post();
                    ?>
                <div
                    class="wp-block-media-text alignwide is-stacked-on-mobile is-style-show-media-on-top animated has-base-background-color has-background o-anim-ready fadeIn slow">
                    <figure class="wp-block-media-text__media"><?php the_post_thumbnail() ?></figure>
                    <div class="wp-block-media-text__content">
                        <h6 class="wp-block-heading has-text-align-left"
                            style="margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px"><strong><?php the_title(); ?></strong></h6>



                        <p class="has-text-align-left"><?php the_excerpt(); ?></p>



                        <div class="wp-block-buttons is-layout-flex">
                            <div class="wp-block-button is-style-secondary-button"><a href="<?php the_permalink(); ?>"
                                    class="wp-block-button__link wp-element-button">Read more</a></div>
                        </div>
                    </div>
                </div>
                <?php
endwhile; 
    if(get_field('show_all') == 'no') {
                    ?>
                    <div class="wp-block-buttons is-content-justification-center is-layout-flex wp-container-32"
                    style="margin-top:var(--wp--preset--spacing--40);text-transform:uppercase">
                    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo home_url(); ?>/blog">View all</a></div>
                </div>
            </div>

        </div>
        <?php
    }
            }
}

// Reset the post data
wp_reset_postdata();
?>
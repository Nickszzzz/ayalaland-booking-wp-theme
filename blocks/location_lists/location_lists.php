<?php 

$query_args = array(
    'post_type'      => 'location',
    'posts_per_page' => -1,
    'order'          => 'DESC',
    'post_status'    => 'publish',
    'meta_query'     => array(
        array(
            'key'     => 'featured_location',
            'value'   => true, // Set to true to only show posts where 'featured_location' is true
            'compare' => '=',  // Use '=' for true/false fields
            'type'    => 'BOOLEAN', // Set the type to BOOLEAN for true/false fields
        ),
    ),
);

$query = new WP_Query($query_args);


?>
    <?php
if ($query->have_posts()) {

?>

<figure
    class="wp-block-gallery alignwide has-nested-images columns-default is-cropped locations__grid wp-block-gallery-3 is-layout-flex wp-block-gallery-is-layout-flex">
    <?php 
			while ( $query->have_posts() ) : $query->the_post();

		?>
    <figure class="wp-block-image size-large">
    <?php the_post_thumbnail(); ?>
        <figcaption class="wp-element-caption"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></figcaption>
    </figure>
    <?php
			endwhile; 
			wp_reset_postdata(); // Reset post data after the custom query loop
		?>
</figure>

<?php
}
?>
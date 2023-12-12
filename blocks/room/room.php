<?php
$room = get_field('room_name');
?>

<figure class="wp-block-image size-full has-custom-border swiper-slide swiper-slide-active"
    style="height: calc(50% - 12px); width: 256px; margin-right: 24px;" role="group" aria-label="1 / 9"><?php echo get_the_post_thumbnail( $room->ID ); ?>
    <a class="wp-element-caption" href="<?php the_permalink($room->ID); ?>"><?php echo $room->post_title; ?><span>Location</span></a>
</figure>
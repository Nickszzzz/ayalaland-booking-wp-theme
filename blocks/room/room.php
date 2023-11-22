<?php
$room = get_field('room_name');
?>

<figure class="wp-block-image size-full has-custom-border swiper-slide swiper-slide-active"
    style="height: calc(50% - 12px); width: 256px; margin-right: 24px;" role="group" aria-label="1 / 9"><?php echo get_the_post_thumbnail( $room->ID ); ?>
    <figcaption class="wp-element-caption"><?php echo $room->post_title; ?><span><a href="<?php echo home_url().'/meeting-rooms/?location='.$room->ID.'&number_of_seats=&checkin=&checkout=' ?>">Location</a></span></figcaption>
</figure>
<?php 
    // Get the current post ID on a single post page
$location_id = get_the_ID();
                    

?>

<div class="wp-block-columns are-vertically-aligned-bottom product-single-page__banner has-background is-layout-flex wp-container-core-columns-layout-1 wp-block-columns-is-layout-flex"
    style="background-color:#01120a;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px">
    <div class="wp-block-column is-vertically-aligned-bottom is-layout-flow wp-block-column-is-layout-flow">
        <div class="wp-block-cover is-light has-custom-content-position is-position-bottom-center product-single-page__cover"
            style="padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><span aria-hidden="true"
                class="wp-block-cover__background has-background-dim"></span><?php the_post_thumbnail(); ?>
            <div
                class="wp-block-cover__inner-container has-global-padding is-layout-constrained wp-block-cover-is-layout-constrained">
                <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>



                <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>






                <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>
            </div>
        </div>
    </div>



    <div class="wp-block-column is-vertically-aligned-bottom is-layout-flow wp-block-column-is-layout-flow"
        style="flex-basis:451px">
        <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>



        <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>



        <div class="wp-block-group product-single-page__contact-info has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
            style="padding-right:0px;padding-left:0px">

        </div>



        <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>
    </div>
    <div
        class="wp-block-group alignwide is-content-justification-space-between is-layout-flex wp-container-core-group-layout-3 wp-block-group-is-layout-flex header-content">
        <div class="wp-block-group">
            <h2 style="padding-right:0px;padding-left:0px; font-style:normal;font-weight:700;"
                class="has-link-color has-text-color has-base-2-color wp-block-post-title has-display-xl-font-size wp-elements-54e2d517ab49abfd9a9b1df2430c5a73">
                <?php echo get_the_title($location_id); ?></h2>


            <ul
                class="is-style-office-amenities has-base-2-color has-text-color has-link-color wp-elements-215aefc385c3b5171d59acfc21738802">

               
                <li><?php echo get_field('room_description_location', $location_id)->post_title; ?></li>

                <li><?php echo get_field('amenity', $location_id); ?></li>


            </ul>
            <ul
                class="is-style-office-amenities has-base-2-color has-text-color has-link-color wp-elements-215aefc385c3b5171d59acfc21738802">

                <li><?php echo get_field('complete_address', $location_id); ?></li>


            </ul>
        </div>
        <div class="wp-block-group">
            <p class="has-base-2-color has-text-color has-link-color has-display-xs-font-size wp-elements-986039443a6925485bab9d9da11e66b3"
                style="font-style:normal;font-weight:600">Contact Information</p>



            <ul class="has-base-2-color has-text-color has-link-color wp-elements-11bca9c9f120966458c69c22419d442e">
                <li><a
                        href="mailto:yupangco.rick@ayalaland.com.ph"><?php echo get_field('cta_email_address', $location_id); ?></a>
                </li>


                <?php 
                $contact_numbers = get_field('cta_contact_number', $location_id);

                    foreach($contact_numbers as $contact_number) {
                        ?>
                <li><a
                        href="tel: <?php echo $contact_number['phone_number']; ?>"><?php echo $contact_number['phone_number']; ?></a>
                </li>
                <?php
                    }
                ?>
            </ul>
        </div>
    </div>
</div>
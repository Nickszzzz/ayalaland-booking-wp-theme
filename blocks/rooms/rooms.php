<?php 
function get_completed_rooms_by_orders($product_id) {
    global $wpdb;

    $query   = new WC_Order_Query( array(
        'limit'      => -1,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'post_status' => 'wc-ayala_approved',
        'return'     => 'ids',
    ) );

    $orders  = $query->get_orders();

    $completed_dates = array();
    foreach ( $orders as $order_id ) {
        $order  = wc_get_order( $order_id );
        $checkin = get_post_meta($order_id, 'checkin', true);
        $order_product_id = intval(get_post_meta($order_id, 'product_id', false)[0]);

        if($order_product_id === $product_id) {
            $order_date = date('m/d/Y', strtotime($checkin));
            $completed_dates[] = $order_date;
        }

    }
    return $completed_dates;
}

function isDateRangeValid($checkin, $checkout, $allowedDayStart, $allowedDayEnd) {
    // If both $checkin and $checkout are empty, consider it a valid date range
    if (empty($checkin) && empty($checkout)) {
        return true;
    }

    // Convert string dates to DateTime objects if they are not empty
    if (!empty($checkin)) {
        $checkinDate = new DateTime($checkin);
    }

    if (!empty($checkout)) {
        $checkoutDate = new DateTime($checkout);
    }

    // Define the allowed days range
    $allowedDays = generateDaysInRange($allowedDayStart, $allowedDayEnd);
	$dayMapping = [
        'Mon' => 'MO',
        'Tue' => 'TU',
        'Wed' => 'WE',
        'Thu' => 'TH',
        'Fri' => 'FR',
        'Sat' => 'SA',
        'Sun' => 'SU',
    ];
    // Check if both check-in and check-out days are within the allowed range
    if (!empty($checkinDate)) {
        $startDay = $checkinDate->format('D');
        $convertedStartDay = $dayMapping[$startDay];
        if (in_array($convertedStartDay, $allowedDays)) {
            return true;
        }
    }

    if (!empty($checkoutDate)) {
        $endDay = $checkoutDate->format('D');
        $convertedEndDay = $dayMapping[$endDay];
        if (in_array($convertedEndDay, $allowedDays)) {
            return true;
        }
    }

    return false;
}

function generateDaysInRange($start, $end) {
    $days = ['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU']; // All days of the week

    // Find the index of the start day
    $startIndex = array_search($start, $days);

    // Find the index of the end day
    $endIndex = array_search($end, $days);

    // Return the subset of days within the range
    return array_values(array_slice($days, $startIndex, $endIndex - $startIndex + 1));
}


    // Check if the parameters are set in the URL
if (isset($_GET['room_location']) || isset($_GET['number_of_seats']) || isset($_GET['checkin']) || isset($_GET['checkout']) || isset($_GET['orderby'])) {
    // Initialize an empty array to store meta queries
    $meta_queries = array();

    // Sanitize and retrieve the values from the URL
    $room_location = isset($_GET['room_location']) ? sanitize_text_field($_GET['room_location']) : '';
    $number_of_seats = isset($_GET['number_of_seats']) ? absint($_GET['number_of_seats']) : '';
    $checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
    $checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';

    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'date'; // Default to 'date' if not set

    // Validate and add meta queries for the provided parameters
    if (!empty($room_location)) {
        $meta_queries[] = array(
            'key' => 'room_description_location',
            'value' => $room_location,
            'compare' => '=',
        );
    }

    if (!empty($number_of_seats)) {
        $meta_queries[] = array(
            'key' => 'room_description_maximum_number_of_seats',
            'value' => $number_of_seats,
            'compare' => '>=',  
            'type' => 'NUMERIC', // Specify the type to ensure numeric comparison
        );
    }
    // Build the query arguments dynamically
    $args = array(
        'post_type' => 'product', // Assuming 'product' is your custom post type
        'posts_per_page' => -1,   // Retrieve all matching posts
        'meta_query' => $meta_queries,
        'post_status' => 'publish',
    );

    // Set orderby based on the URL parameter
    if ($orderby === 'price') {
        $args['meta_key'] = 'rates_hourly_rate';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'ASC';

    } elseif ($orderby === 'price-desc') {
        $args['meta_key'] = 'rates_hourly_rate';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ($orderby === 'popularity') {
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } 
    else {
        $args['orderby'] = $orderby; // Use the provided orderby value from the URL
    }


    // Create a new WP_Query instance
    $query = new WP_Query($args);

    $count = 0;
    $num_results = $query->found_posts;
    if($num_results > 0) {
        ?>

<?php
}
?>
<div style="display: flex; flex-direction: column;">
<div class="wp-block-query alignwide is-layout-flow wp-block-query-is-layout-flow" style="order: 2;">
    <ul
        class="columns-3 products-block-post-template wp-block-post-template is-layout-grid wp-container-core-post-template-layout-1 wp-block-post-template-is-layout-grid">
        <?php
    if ($query->have_posts()) {
        if (!empty($checkin) || !empty($checkout)) {
            while ($query->have_posts()) {
                $query->the_post();
                $id = get_the_ID();
                $start = get_field('operating_days_starts', $id);
                $end = get_field('operating_days_ends', $id);
                $booked_date = get_completed_rooms_by_orders($id);
                $is_booked = in_array($checkin, $booked_date);
                    if(isDateRangeValid($checkin, $checkout, $start, $end) && !$is_booked) {
                        $count++;

                ?>
        <li
            class="wp-block-post post-159 product type-product status-publish has-post-thumbnail product_cat-uncategorized first instock virtual purchasable product-type-simple">
            <div data-block-name="woocommerce/product-image" data-is-descendent-of-query-loop="true"
                data-is-inherited="1" class="wc-block-components-product-image wc-block-grid__product-image " style="">
                <a href="<?php the_permalink(); ?>" style=""> <?php the_post_thumbnail(); ?></a>
            </div>


            <div
                class="wp-block-group office-card__details has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
                <h2 style="font-style:normal;font-weight:600;"
                    class="has-text-align-left wp-block-post-title has-display-xs-font-size"><a
                        href="<?php the_permalink(); ?>" target="_self"><?php the_title(); ?></a></h2>


                <ul
                    class="is-style-hourly-rate has-accent-color has-text-color has-link-color wp-elements-7d4e976a87a56046e8880b9a798b2f32">
                    <li>Hourly Rate: Php <?php echo number_format(get_field('rates_hourly_rate', $id), 2, '.', ','); ?>
                    </li>



                    <?php 
                        $daily_rate = get_field('rates_daily_rate', $id);
                            if(!empty($daily_rate)) {
                                ?>
                    <li>Whole Day Rate: Php <?php 
                                    echo number_format(get_field('rates_daily_rate', $id), 2, '.', ','); 
                                ?>
                    </li>
                    <?php
                            }
                        ?>

                </ul>
                <p
                    class="has-contrast-3-color has-text-color has-link-color wp-elements-e6163b8573891fa29a48f85a126e09a9">
                    <?php the_excerpt(); ?></p>



                <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button"><a href="<?php the_permalink(); ?>"
                            class="wp-block-button__link wp-element-button">View More
                            Details</a></div>
                </div>
            </div>

        </li>
        <?php
                    }

            }
        }
        else {

        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();
            $start = get_field('operating_days_starts', $id);
            $end = get_field('operating_days_ends', $id);
            $count++;
            // print_r(isDateRangeValid($checkin, $checkout, $start, $end));
            ?>
        <li
            class="wp-block-post post-159 product type-product status-publish has-post-thumbnail product_cat-uncategorized first instock virtual purchasable product-type-simple">
            <div data-block-name="woocommerce/product-image" data-is-descendent-of-query-loop="true"
                data-is-inherited="1" class="wc-block-components-product-image wc-block-grid__product-image " style="">
                <a href="<?php the_permalink(); ?>" style=""> <?php the_post_thumbnail(); ?></a>
            </div>


            <div
                class="wp-block-group office-card__details has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
                <h2 style="font-style:normal;font-weight:600;"
                    class="has-text-align-left wp-block-post-title has-display-xs-font-size"><a
                        href="<?php the_permalink(); ?>" target="_self"><?php the_title(); ?></a></h2>


                <ul
                    class="is-style-hourly-rate has-accent-color has-text-color has-link-color wp-elements-7d4e976a87a56046e8880b9a798b2f32">
                    <li>Hourly Rate: Php <?php echo number_format(get_field('rates_hourly_rate', $id), 2, '.', ','); ?>
                    </li>



                    <?php 
                    $daily_rate = get_field('rates_daily_rate', $id);
                        if(!empty($daily_rate)) {
                            ?>
                    <li>Whole Day Rate: Php <?php 
                                echo number_format(get_field('rates_daily_rate', $id), 2, '.', ','); 
                            ?>
                    </li>
                    <?php
                        }
                    ?>

                </ul>
                <p
                    class="has-contrast-3-color has-text-color has-link-color wp-elements-e6163b8573891fa29a48f85a126e09a9">
                    <?php the_excerpt(); ?></p>



                <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button"><a href="<?php the_permalink(); ?>"
                            class="wp-block-button__link wp-element-button">View More
                            Details</a></div>
                </div>
            </div>

        </li>
        <?php
        }
    }

        wp_reset_postdata(); // Reset the post data to the main query
    } else {
        echo 'No matching rooms found.';
    }

    ?>

    </ul>

</div>
<div class="wp-block-group rooms-query-count-filter-results alignwide has-display-xs-font-size is-content-justification-space-between is-nowrap is-layout-flex wp-container-core-group-layout-5 wp-block-group-is-layout-flex"
    style="margin-top:32px;margin-bottom:32px;font-style:normal;font-weight:600">
    <div data-block-name="woocommerce/product-results-count" data-font-size="display-xs"
        class="woocommerce wc-block-product-results-count wp-block-woocommerce-product-results-count has-font-size has-display-xs-font-size "
        style="">
        <?php
    // Get the number of posts found by the query

    // Display the result count
    echo '<p class="woocommerce-result-count">';
    if ($count == 1) {
        echo 'Showing 1 result';
    } else {
        echo 'Showing all ' . $count . ' results';
    }

    echo '</p>';    
        ?>
    </div>

    <div data-block-name="woocommerce/catalog-sorting" data-font-size="text-md"
        class="woocommerce wc-block-catalog-sorting has-font-size has-text-md-font-size " style="">
        <form class="woocommerce-ordering" method="get">
            <select name="orderby" class="orderby" aria-label="Shop order">
                <option value="menu_order"
                    <?php echo isset($_GET['orderby']) ? $orderby == '' ? 'selected="selected"' : '' : 'selected="seleted"'; ?>>
                    Default sorting</option>
                <option value="popularity"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'popularity' ? 'selected="selected"' : '' : ''; ?>>
                    Sort by popularity</option>
                <option value="date"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'date' ? 'selected="selected"' : '' : ''; ?>>Sort
                    by latest</option>
                <option value="price"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'price' ? 'selected="selected"' : '' : ''; ?>>Sort
                    by price: low to high</option>
                <option value="price-desc"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'price-desc' ? 'selected="selected"' : '' : ''; ?>>
                    Sort by price: high to low</option>
            </select>
            <input type="hidden" name="paged" value="1">
        </form>
    </div>
</div>
</div>
<?php

} else {
    // If no parameters are provided, retrieve all products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    // Create a new WP_Query instance
    $query = new WP_Query($args);


    $num_results = $query->found_posts;
    if($num_results > 0) {
        ?>
<div class="wp-block-group rooms-query-count-filter-results alignwide has-display-xs-font-size is-content-justification-space-between is-nowrap is-layout-flex wp-container-core-group-layout-5 wp-block-group-is-layout-flex"
    style="margin-top:32px;margin-bottom:32px;font-style:normal;font-weight:600">
    <div data-block-name="woocommerce/product-results-count" data-font-size="display-xs"
        class="woocommerce wc-block-product-results-count wp-block-woocommerce-product-results-count has-font-size has-display-xs-font-size "
        style="">
        <?php
    // Get the number of posts found by the query

    // Display the result count
    echo '<p class="woocommerce-result-count">';

    if ($num_results == 1) {
        echo 'Showing 1 result';
    } else {
        echo 'Showing all ' . $num_results . ' results';
    }

    echo '</p>';    
        ?>
    </div>

    <div data-block-name="woocommerce/catalog-sorting" data-font-size="text-md"
        class="woocommerce wc-block-catalog-sorting has-font-size has-text-md-font-size " style="">
        <form class="woocommerce-ordering" method="get">
            <select name="orderby" class="orderby" aria-label="Shop order">
                <option value="menu_order"
                    <?php echo isset($_GET['orderby']) ? $orderby == '' ? 'selected="selected"' : '' : 'selected="seleted"'; ?>>
                    Default sorting</option>
                <option value="popularity"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'popularity' ? 'selected="selected"' : '' : ''; ?>>
                    Sort by popularity</option>
                <option value="date"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'date' ? 'selected="selected"' : '' : ''; ?>>Sort
                    by latest</option>
                <option value="price"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'price' ? 'selected="selected"' : '' : ''; ?>>Sort
                    by price: low to high</option>
                <option value="price-desc"
                    <?php echo isset($_GET['orderby']) ? $orderby == 'price-desc' ? 'selected="selected"' : '' : ''; ?>>
                    Sort by price: high to low</option>
            </select>
            <input type="hidden" name="paged" value="1">
        </form>
    </div>
</div>
<?php
}
?>
<div class="wp-block-query alignwide is-layout-flow wp-block-query-is-layout-flow">
    <ul
        class="columns-3 products-block-post-template wp-block-post-template is-layout-grid wp-container-core-post-template-layout-1 wp-block-post-template-is-layout-grid">
        <?php
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            ?>
        <li
            class="wp-block-post post-159 product type-product status-publish has-post-thumbnail product_cat-uncategorized first instock virtual purchasable product-type-simple">
            <div data-block-name="woocommerce/product-image" data-is-descendent-of-query-loop="true"
                data-is-inherited="1" class="wc-block-components-product-image wc-block-grid__product-image " style="">
                <a href="<?php the_permalink(); ?>" style=""> <?php the_post_thumbnail(); ?></a>
            </div>


            <div
                class="wp-block-group office-card__details has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
                <h2 style="font-style:normal;font-weight:600;"
                    class="has-text-align-left wp-block-post-title has-display-xs-font-size"><a
                        href="<?php the_permalink(); ?>" target="_self"><?php the_title(); ?></a></h2>


                <ul
                    class="is-style-hourly-rate has-accent-color has-text-color has-link-color wp-elements-7d4e976a87a56046e8880b9a798b2f32">
                    <li>Hourly Rate: Php <?php echo number_format(get_field('rates_hourly_rate', $id), 2, '.', ','); ?>
                    </li>



                    <?php 
                    $daily_rate = get_field('rates_daily_rate', $id);
                        if(!empty($daily_rate)) {
                            ?>
                    <li>Whole Day Rate: Php <?php 
                                echo number_format(get_field('rates_daily_rate', $id), 2, '.', ','); 
                            ?>
                    </li>
                    <?php
                        }
                    ?>

                </ul>



                <p
                    class="has-contrast-3-color has-text-color has-link-color wp-elements-e6163b8573891fa29a48f85a126e09a9">
                    <?php the_excerpt(); ?></p>



                <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button"><a href="<?php the_permalink(); ?>"
                            class="wp-block-button__link wp-element-button">View More
                            Details</a></div>
                </div>
            </div>

        </li>
        <?php
        }
        wp_reset_postdata(); // Reset the post data to the main query
    } else {
        echo 'No matching rooms found.';
    }

    ?>

    </ul>

</div>
<?php
}
?>
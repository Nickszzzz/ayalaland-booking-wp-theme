<?php 

// Get checkin and checkout values from parameters
$checkinParam = $_GET['checkin'] ?? null;
$checkoutParam = $_GET['checkout'] ?? null;


// Validate the parameters
if ($checkinParam && $checkoutParam) {

    // Convert checkin and checkout to DateTime objects
    $checkinDate = DateTime::createFromFormat('m/d/Y', $checkinParam);
    $checkoutDate = DateTime::createFromFormat('m/d/Y', $checkoutParam);

// Check if the parameters are set in the URL
if (isset($_GET['room_location']) || isset($_GET['number_of_seats'])) {
    // Initialize an empty array to store meta queries
    $meta_queries = array();

    // Sanitize and retrieve the values from the URL
    $room_location = isset($_GET['room_location']) ? sanitize_text_field($_GET['room_location']) : '';
    $number_of_seats = isset($_GET['number_of_seats']) ? absint($_GET['number_of_seats']) : '';

    

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
            'compare' => '<=',
            'type' => 'NUMERIC', // Specify the type to ensure numeric comparison
        );
    }

    // Build the query arguments dynamically
    $args = array(
        'post_type' => 'product', // Assuming 'product' is your custom post type
        'posts_per_page' => -1,   // Retrieve all matching posts
        'meta_query' => $meta_queries,
        'order' => 'DESC',
        'post_status' => 'publish',
    );

} else {
    // If no parameters are provided, retrieve all products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'order'          => 'DESC',
    'post_status'    => 'publish',
    );
}

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
                <option value="menu_order" selected="selected">Default sorting</option>
                <option value="popularity">Sort by popularity</option>
                <option value="date">Sort by latest</option>
                <option value="price">Sort by price: low to high</option>
                <option value="price-desc">Sort by price: high to low</option>
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
            // Get operating days from post custom fields
            $operatingDaysStart = get_field('operating_days_starts', $id);
            $operatingDaysEnd = get_field('operating_days_ends', $id);

            // Validate the retrieved values
            if ($operatingDaysStart && $operatingDaysEnd) {
                // Convert operating days to an array of days
                $operatingDays = getDaysInRange(strtolower($operatingDaysStart), strtolower($operatingDaysEnd));

                // Check if check-in and check-out are within operating days
                if (isWithinOperatingDays($checkinDate, $checkoutDate, $operatingDays)) {
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



                    <li>Whole Day Rate: Php <?php echo number_format(get_field('rates_daily_rate', $id), 2, '.', ','); ?>
                    </li>
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

        wp_reset_postdata(); // Reset the post data to the main query
    } else {
        echo 'No matching rooms found.';
    }

    ?>

    </ul>

</div>
<?php 
}else {
    // Check if the parameters are set in the URL
if (isset($_GET['room_location']) || isset($_GET['number_of_seats'])) {
    // Initialize an empty array to store meta queries
    $meta_queries = array();

    // Sanitize and retrieve the values from the URL
    $room_location = isset($_GET['room_location']) ? sanitize_text_field($_GET['room_location']) : '';
    $number_of_seats = isset($_GET['number_of_seats']) ? absint($_GET['number_of_seats']) : '';

    

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
            'compare' => '<=',
            'type' => 'NUMERIC', // Specify the type to ensure numeric comparison
        );
    }

    // Build the query arguments dynamically
    $args = array(
        'post_type' => 'product', // Assuming 'product' is your custom post type
        'posts_per_page' => -1,   // Retrieve all matching posts
        'meta_query' => $meta_queries,
        'order' => 'DESC',
        'post_status' => 'publish',
    );

} else {
    // If no parameters are provided, retrieve all products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'order'          => 'DESC',
    'post_status'    => 'publish',
    );
}

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
                <option value="menu_order" selected="selected">Default sorting</option>
                <option value="popularity">Sort by popularity</option>
                <option value="date">Sort by latest</option>
                <option value="price">Sort by price: low to high</option>
                <option value="price-desc">Sort by price: high to low</option>
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
// Function to get days between start and end
function getDaysInRange($startDay, $endDay) {
    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

    $startIndex = array_search($startDay, $days);
    $endIndex = array_search($endDay, $days);

    return array_slice($days, $startIndex, $endIndex - $startIndex + 1);
}

// Function to check if check-in and check-out are within operating days
function isWithinOperatingDays($checkinDate, $checkoutDate, $operatingDays) {
    $current = clone $checkinDate;
    while ($current <= $checkoutDate) {
        if (!in_array(strtolower($current->format('l')), $operatingDays)) {
            return false;
        }
        $current->modify('+1 day');
    }
    return true;
}
?>
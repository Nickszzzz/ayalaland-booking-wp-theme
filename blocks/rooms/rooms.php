<?php 

// Check if the parameters are set in the URL
if (isset($_GET['room_location']) || isset($_GET['number_of_seats']) || isset($_GET['checkin']) || isset($_GET['checkout'])) {
    // Initialize an empty array to store meta queries
    $meta_queries = array();

    // Sanitize and retrieve the values from the URL
    $room_location = isset($_GET['room_location']) ? sanitize_text_field($_GET['room_location']) : '';
    $number_of_seats = isset($_GET['number_of_seats']) ? absint($_GET['number_of_seats']) : '';
    $checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
    $checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';

    // Validate and add meta queries for the provided parameters
    if (!empty($room_location)) {
        $meta_queries[] = array(
            'key' => 'location', // Replace with the actual meta key
            'value' => $room_location,
            'compare' => '='
        );
    }

    if (!empty($number_of_seats)) {
        $meta_queries[] = array(
            'key' => 'number_of_seats', // Replace with the actual meta key
            'value' => $number_of_seats,
            'compare' => '='
        );
    }

    // Add more validation and meta queries for other parameters as needed

    // Build the query arguments dynamically
    $args = array(
        'post_type' => 'product', // Assuming 'product' is your custom post type
        'posts_per_page' => -1,   // Retrieve all matching posts
        'meta_query' => $meta_queries,
    );
} else {
    // If no parameters are provided, retrieve all products
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
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

            ?>
        <li
            class="wp-block-post post-159 product type-product status-publish has-post-thumbnail product_cat-uncategorized first instock virtual purchasable product-type-simple">
            <div data-block-name="woocommerce/product-image" data-is-descendent-of-query-loop="true"
                data-is-inherited="1" class="wc-block-components-product-image wc-block-grid__product-image " style="">
                <a href="http://ayalaland-booking.local/product/alabang-town-center-corporate-center/" style=""> <img
                        width="600" height="338"
                        src="http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6-600x338.png"
                        class="attachment-woocommerce_single size-woocommerce_single"
                        alt="Alabang Town Center Corporate Center" data-testid="product-image"
                        style="max-width:none;object-fit:cover;" decoding="async" fetchpriority="high"
                        srcset="http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6-600x338.png 600w, http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6-300x169.png 300w, http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6-1024x576.png 1024w, http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6-768x432.png 768w, http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6-1536x864.png 1536w, http://ayalaland-booking.local/wp-content/uploads/2023/11/Featured-Location-6.png 1920w"
                        sizes="(max-width: 600px) 100vw, 600px"></a>
            </div>


            <div
                class="wp-block-group office-card__details has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">
                <h2 style="font-style:normal;font-weight:600;"
                    class="has-text-align-left wp-block-post-title has-display-xs-font-size"><a
                        href="http://ayalaland-booking.local/product/alabang-town-center-corporate-center/"
                        target="_self">Alabang Town Center Corporate Center</a></h2>


                <ul
                    class="is-style-hourly-rate has-accent-color has-text-color has-link-color wp-elements-7d4e976a87a56046e8880b9a798b2f32">
                    <li>Hourly Rate: ₱ 650.00</li>



                    <li>Whole Day Rate: ₱ 10,000.00</li>
                </ul>



                <p
                    class="has-contrast-3-color has-text-color has-link-color wp-elements-e6163b8573891fa29a48f85a126e09a9">
                    Set in the urban area of Madrigal Avenue in Alabang, the Alabang Town Center Corporate Center is an
                    office complex built for new and established companies.</p>



                <div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
                    <div class="wp-block-button"><a class="wp-block-button__link wp-element-button">View More
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
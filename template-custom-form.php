<?php
/*
Template Name: Custom Form Template
*/

// Get values from parameters


$current_user_id = get_current_user_id();
$user_info = get_userdata( $current_user_id );

// Retrieve user meta fields and default to empty string if no value
$billing_first_name = get_user_meta( $current_user_id, 'first_name', true ) ?: '';
$billing_last_name = get_user_meta( $current_user_id, 'last_name', true ) ?: '';
$billing_company = get_user_meta( $current_user_id, 'company', true ) ?: '';
$country = get_user_meta( $current_user_id, 'country', true ) ?: '';
$billing_phone = get_user_meta( $current_user_id, 'phone', true ) ?: '';
$billing_tin_number = get_user_meta( $current_user_id, 'tin_number', true ) ?: '';
$billing_email = $user_info->user_email ?: '';



$booking_notes = isset($_GET['booking_notes']) ? sanitize_text_field($_GET['booking_notes']) : '';
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0; // Assuming it's an integer
$checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
$number_of_hours = isset($_GET['number_of_hours']) ? intval($_GET['number_of_hours']) : 0; // Assuming it's an integer
$number_of_seats = isset($_GET['number_of_seats']) ? intval($_GET['number_of_seats']) : 0; // Assuming it's an integer
$overall_total = isset($_GET['overall_total']) ? floatval($_GET['overall_total']) : 0.0; // Assuming it's a float
$formtoken = isset($_GET['formtoken']) ? $_GET['formtoken'] : ''; // Assuming it's a float


$product_id = isset($_GET['add-to-cart']) ? $_GET['add-to-cart'] : '';
$checkin = isset($_GET['checkin']) ? urldecode($_GET['checkin']) : '';
$checkin = strtotime($checkin);
$checkin = date('Y-m-d H:i:s', $checkin);
$checkout = isset($_GET['checkout']) ? urldecode($_GET['checkout']) : '';
$checkout = strtotime($checkout);
$checkout = date('Y-m-d H:i:s', $checkout);


$rate = isset($_GET['rate']) ? urldecode($_GET['rate']) : 0;
$operating_hours_start = get_field('operating_hours_start', $product_id);
$operating_hours_end = get_field('operating_hours_end', $product_id);
$operating_days_starts = get_field('operating_days_starts', $product_id);
$operating_days_ends = get_field('operating_days_ends', $product_id);

$startDateTime = DateTime::createFromFormat('M d, Y, g:i:s A', $_GET['checkin']);
$endDateTime = DateTime::createFromFormat('M d, Y, g:i:s A', $_GET['checkout']);

function DateCounter($checkinDate, $checkoutDate, $operatingDaysStarts, $operatingDaysEnds) {

    // Convert operatingDaysStarts and operatingDaysEnds to uppercase
    $startDay = strtoupper($operatingDaysStarts);
    $endDay = strtoupper($operatingDaysEnds);

    // Define day indices
    $daysOfWeek = ["SU", "MO", "TU", "WE", "TH", "FR", "SA"];
    $startDayIndex = array_search($startDay, $daysOfWeek);
    $endDayIndex = array_search($endDay, $daysOfWeek);

    $currentDate = clone $checkinDate; // Start with checkinDate
    $daysDifference = 0;

    // Loop through each day between checkinDate and checkoutDate
    while ($currentDate < $checkoutDate || $currentDate->format('Y-m-d') <= $checkoutDate->format('Y-m-d')) {
        $currentDayIndex = (int)$currentDate->format('w'); // 0 (Sunday) to 6 (Saturday)

        // Check if current day is within operating days range
        if (
            ($startDayIndex <= $endDayIndex && $currentDayIndex >= $startDayIndex && $currentDayIndex <= $endDayIndex) ||
            ($startDayIndex > $endDayIndex && ($currentDayIndex >= $startDayIndex || $currentDayIndex <= $endDayIndex))
        ) {
            $daysDifference++;
        }

        $currentDate->modify('+1 day'); // Move to the next day
    }

    return $daysDifference;
}



if (strpos($rate, 'Hourly') !== false) {
    $number_of_hours = compute_total_working_hours(
        $product_id, 
        $startDateTime,
        $endDateTime,
        $operating_hours_start, 
        $operating_hours_end,
        $operating_days_starts,
        $operating_days_ends
    );
} else {
    $number_of_hours = DateCounter(
        $startDateTime,
        $endDateTime,
        $operating_days_starts,
        $operating_days_ends
    );
} 

global $woocommerce;

$address = array(
    'first_name' => $billing_first_name,
    'last_name'  => $billing_last_name,
    'company'    => $billing_company,
    'email'      => $billing_email,
    'phone'      => $billing_phone,
    'country'    => $country
);


// Now we create the order
$order = wc_create_order();

// The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
$order->set_address($address, 'billing');

// Get the product
$product = wc_get_product($product_id);
$author_id = get_post_field('post_author', $product_id);

// Set the custom cost
$product->set_price(0);

// Add the product to the order
$order->add_product($product, 1);

// Calculate totals and update order status
$order->calculate_totals();

// Add post meta to the order
$order_id = $order->get_id();
update_post_meta($order_id, 'billing_tin_number', $billing_tin_number);
update_post_meta($order_id, 'checkin', $checkin);
update_post_meta($order_id, 'checkout', $checkout);
update_post_meta($order_id, 'number_of_hours', $number_of_hours);
update_post_meta($order_id, 'booking_notes', $booking_notes);
update_post_meta($order_id, 'number_of_seats', $number_of_seats);
update_post_meta($order_id, 'author_id', $author_id);
update_post_meta($order_id, 'vat', 0);
update_post_meta($order_id, 'booking_type', $rate);
update_post_meta($order_id, 'overall_total', 0);
update_post_meta($order_id, 'payment_method', "");
// Create a new post
$new_post = array(
    'post_title'    => '#'.$order_id,
    'post_content'  => 'This is the content of the sample post.',
    'post_status'   => 'publish',
    'post_author'   => $author_id, // Use the user ID of the post author
    'post_type'     => 'booking', // Post type (e.g., 'post', 'page', etc.)
);

// Insert the post into the database
$new_post_id = wp_insert_post($new_post);
update_field('order_id', $order_id, $new_post_id);
update_field('product_id', $product_id, $new_post_id);
update_field('checkin', strtotime($checkin), $new_post_id);
update_field('checkout', strtotime($checkout), $new_post_id);
$order_status_slug = $order->get_status();
// Get the human-readable order status name
$order_status_name = wc_get_order_status_name($order_status_slug);
update_field('order_status', $order_status_name, $new_post_id);
update_field('quantity', 1, $new_post_id);
update_field('booking_room', get_the_title($product_id), $new_post_id);
update_field('customer', $billing_first_name .' '. $billing_last_name, $new_post_id);
update_post_meta($order_id, 'post_id', $new_post_id);
update_field('vat', 0, $new_post_id);
update_field('booking_type', $rate, $new_post_id);
update_field('overall_total', 0, $new_post_id);
update_field('payment_method', "", $new_post_id);
// Set the order status to "completed" to indicate it's paid
$order->update_status('ayala_approved', 'Order paid via Backend', TRUE);

wp_redirect(home_url().'/thank-you/?formtoken='.$formtoken);
exit; // Ensure that no further code is executed after the redirect
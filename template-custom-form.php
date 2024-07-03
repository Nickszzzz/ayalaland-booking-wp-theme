<?php
/*
Template Name: Custom Form Template
*/

// Get values from parameters
$billing_first_name = isset($_GET['billing_first_name']) ? sanitize_text_field($_GET['billing_first_name']) : '';
$billing_last_name = isset($_GET['billing_last_name']) ? sanitize_text_field($_GET['billing_last_name']) : '';
$billing_company = isset($_GET['billing_company']) ? sanitize_text_field($_GET['billing_company']) : '';
$country = isset($_GET['country']) ? sanitize_text_field($_GET['country']) : '';
$billing_email = isset($_GET['billing_email']) ? sanitize_email($_GET['billing_email']) : '';
$billing_phone = isset($_GET['billing_phone']) ? sanitize_text_field($_GET['billing_phone']) : '';
$billing_tin_number = isset($_GET['billing_tin_number']) ? sanitize_text_field($_GET['billing_tin_number']) : '';
$booking_notes = isset($_GET['booking_notes']) ? sanitize_text_field($_GET['booking_notes']) : '';
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0; // Assuming it's an integer
$checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
$number_of_hours = isset($_GET['number_of_hours']) ? intval($_GET['number_of_hours']) : 0; // Assuming it's an integer
$number_of_seats = isset($_GET['number_of_seats']) ? intval($_GET['number_of_seats']) : 0; // Assuming it's an integer
$overall_total = isset($_GET['overall_total']) ? floatval($_GET['overall_total']) : 0.0; // Assuming it's a float
$formtoken = isset($_GET['formtoken']) ? $_GET['formtoken'] : ''; // Assuming it's a float
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
$product->set_price($overall_total);

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

$order->update_status("wc-ayala_new_order", 'Imported order', TRUE);

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


wp_redirect(home_url().'/thank-you/?formtoken='.$formtoken);
exit; // Ensure that no further code is executed after the redirect
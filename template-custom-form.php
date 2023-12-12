<?php
/*
Template Name: Custom Form Template
*/

// Get values from parameters
$billing_first_name = isset($_GET['billing_first_name']) ? sanitize_text_field($_GET['billing_first_name']) : '';
$billing_last_name = isset($_GET['billing_last_name']) ? sanitize_text_field($_GET['billing_last_name']) : '';
$billing_company = isset($_GET['billing_company']) ? sanitize_text_field($_GET['billing_company']) : '';
$billing_country = isset($_GET['billing_country']) ? sanitize_text_field($_GET['billing_country']) : '';
$billing_email = isset($_GET['billing_email']) ? sanitize_email($_GET['billing_email']) : '';
$billing_phone = isset($_GET['billing_phone']) ? sanitize_text_field($_GET['billing_phone']) : '';
$billing_tin_number = isset($_GET['billing_tin_number']) ? sanitize_text_field($_GET['billing_tin_number']) : '';
$booking_notes = isset($_GET['booking_notes']) ? sanitize_text_field($_GET['booking_notes']) : '';
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0; // Assuming it's an integer
$checkin = isset($_GET['checkin']) ? sanitize_text_field($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? sanitize_text_field($_GET['checkout']) : '';
$number_of_hours = isset($_GET['number_of_hours']) ? intval($_GET['number_of_hours']) : 0; // Assuming it's an integer
$overall_total = isset($_GET['overall_total']) ? floatval($_GET['overall_total']) : 0.0; // Assuming it's a float
$formtoken = isset($_GET['formtoken']) ? $_GET['formtoken'] : ''; // Assuming it's a float
global $woocommerce;

$address = array(
    'first_name' => $billing_first_name,
    'last_name'  => $billing_last_name,
    'company'    => $billing_company,
    'email'      => $billing_email,
    'phone'      => $billing_phone,
    'country'    => $billing_country
);


// Now we create the order
$order = wc_create_order();

// The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
$order->set_address($address, 'billing');

// Get the product
$product = wc_get_product($product_id);

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

$order->update_status("wc-processing", 'Imported order', TRUE);


wp_redirect(home_url().'/thank-you/?formtoken='.$formtoken);
exit; // Ensure that no further code is executed after the redirect
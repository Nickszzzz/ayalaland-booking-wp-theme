<?php
/**
 * Customer invoice email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-invoice.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$id = $order->get_id();
$orders = wc_get_order($id);
$first_item = current($orders->get_items());
$product_id = $first_item->get_product_id();
$location_id = get_field('room_description_location', $product_id)->ID;
$location = get_field('room_description_location', $product_id)->post_title;
$booking_notes = get_post_meta($order->get_id(), 'booking_notes', true);
$checkin = get_post_meta($order->get_id(), 'checkin', true);
$checkout = get_post_meta($order->get_id(), 'checkout', true);
$number_of_seats = get_post_meta($order->get_id(), 'number_of_seats', true);
$billing_email = get_post_meta($id, '_billing_email', true);
// Convert the date and time string to a DateTime object
$date_time_object_checkin = DateTime::createFromFormat('M j, Y, g:i:s A', $checkin);

// Format the date as "December 4, 2023"
$formatted_date= $date_time_object_checkin ? $date_time_object_checkin->format('F j, Y') : 'Invalid date';

// Parse the date and time using DateTime
$checkinDateTime = new DateTime($checkin);
$checkoutDateTime = new DateTime($checkout);

// Format the time as HH:mm:ss A
$checkin_time = $checkinDateTime->format('g:i:s A');
$checkout_time = $checkoutDateTime->format('g:i:s A');

// Get all post meta for the order
$all_meta = get_post_meta($id);

// Get the post author (user ID) of the location post
$author_id = get_post_field('post_author', $location_id);

$user_data = get_userdata($author_id);

// Get user email
$user_email = $user_data->user_email;
$display_name = $user_data->display_name;

// Get user roles (user type)
$user_roles = $user_data->roles;

// Assuming a user may have multiple roles, use the first role if available
$user_type = !empty($user_roles) ? $user_roles[0] : 'No Role';


?>

<div class='custom-email'>
<?php

echo '
<p style="margin: 0;">Dear '.get_post_meta($id, '_billing_first_name', true).' '.get_post_meta($id, '_billing_last_name', true).',</p>
<br>
<p style="margin: 0;">I hope this message finds you well. We appreciate your continued use of our facilities.</p>
<br>
<p style="margin: 0;">We would like to inform you that a meeting room booking request has been submitted for '.$formatted_date.' '.$checkin_time.' to '.$checkout_time.'. <br>The details are as follows:</p>
<br>
<ul style="text-align: left;">
	<li><b>Meeting Title:</b> '.$first_item->get_name().'</li>
	<li><b>Date:</b> '.$formatted_date.'</li>
	<li><b>Time:</b> '.$checkin_time.' to '.$checkout_time.'</li>
	<li><b>Number of Attendees:</b> '.$number_of_seats.'</li>
</ul>
<br>
<p style="margin: 0;">If you have any specific preferences or additional requirements, please reply to this email at your <br>earliest convenience. The Admin team will be coordinating the reservation, and we will do our <br>best to accommodate your needs.</p>
<br>
<p style="margin: 0;">Thank you for your cooperation, and we look forward to assisting you with a successful meeting.</p>
<br>
<br>
<p style="margin: 0;">Best regards,</p>
<p style="margin: 0;">Ayala Land Offices</p>';

?>
</div>
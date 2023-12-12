<?php
/**
 * Admin new order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/admin-new-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails\HTML
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
// add_action('woocommerce_email_header', 'custom_new_order_email_header', 10, 2);

function custom_new_order_email_header($email_heading, $email) {
    // Output your custom content here
    echo '<p>This is a custom content added to the new order email header.</p>';
}

	$id = $order->get_id();
    $orders = wc_get_order($order->get_id());
    $first_item = current($orders->get_items());
    $product_id = $first_item->get_product_id();
    $location_id = get_field('room_description_location', $product_id)->ID;
	$location = get_field('room_description_location', $product_id)->post_title;
	$booking_notes = get_post_meta($order->get_id(), 'booking_notes', true);
	$checkin = get_post_meta($order->get_id(), 'checkin', true);
	$checkout = get_post_meta($order->get_id(), 'checkout', true);
	
	// Convert the date and time string to a DateTime object
	$date_time_object_checkin = DateTime::createFromFormat('M j, Y, g:i:s A', $checkin);
	
	// Format the date as "December 4, 2023"
	$formatted_date= $date_time_object_checkin ? $date_time_object_checkin->format('F j, Y') : 'Invalid date';
	
	if (!function_exists('formatTime')) {
		function formatTime($date_time_string, $output_format = 'g:i A') {
			// Convert the date and time string to a DateTime object
			$date_time_object = DateTime::createFromFormat('M j, Y, g:i:s A', $date_time_string);
	
			if ($date_time_object) {
				// Format the time as needed
				return $date_time_object->format($output_format);
			} else {
				return 'Invalid date';
			}
		}
	}
	
	$checkin_time = formatTime($checkin);
	$checkout_time = formatTime($checkout);
    // Get all post meta for the order
    $all_meta = get_post_meta($id);

    // Get the post author (user ID) of the location post
    $author_id = get_post_field('post_author', $location_id);

    $user_data = get_userdata($author_id);

    if ($user_data) {
        // Get user email
        $user_email = $user_data->user_email;
    
        // Get user roles (user type)
        $user_roles = $user_data->roles;
    
        // Assuming a user may have multiple roles, use the first role if available
        $user_type = !empty($user_roles) ? $user_roles[0] : 'No Role';
    
        // Now, $user_email contains the email, and $user_type contains the user type (role)
        
        // Example: Output the email and user type
        // echo 'User Email: ' . $user_email . '<br>';
        // echo 'User Type: ' . ucwords(str_replace('_', ' ', $user_type));
    }

?>

<div class='custom-email'>
	<p>Admin,</p>
	<br>
	<p>A new room has been submitted through the website. Below are the details:</p>
	<br>
	<p>Meeting Room: <?php echo $first_item->get_name(); ?></p>
	<p>Location: <?php echo $location; ?></p>
	<br>
	<p>Booking Details:</p>
	<p>Order #<?php echo $order->get_id(); ?> details </p>
	<p><?php echo $formatted_date; ?> </p>
	<p><?php echo $checkin; ?> to <?php echo $checkout; ?> </p>
	<br>
	<p>Contact Information: </p>
	<p><?php echo $order->get_billing_first_name().' '.$order->get_billing_last_name() ?></p>
	<p><?php echo $order->get_billing_email(); ?> </p>
	<p><?php echo $order->get_billing_phone(); ?> </p>
	<br>
	<p>Additional Notes: </p>
	<p><?php echo $booking_notes; ?> </p>
</div>

<?php

$subject = 'New Room Booking | '.$first_item->get_name();
$email_content = '
    <p style="margin: 0;">'.ucwords(str_replace('_', ' ', $user_type)).',</p>
    <br>
    <p style="margin: 0;">A new room has been submitted through the website. Below are the details:</p>
    <br>
    <p style="margin: 0;">Meeting Room: '.$first_item->get_name().'</p>
    <p style="margin: 0;">Location: '.$location.'</p>
    <br>
    <p style="margin: 0;">Booking Details:</p>
    <p style="margin: 0;">Order #'.$order->get_id().' details </p>
    <p style="margin: 0;">'.$formatted_date.' </p>
    <p style="margin: 0;">'.$checkin.' to '.$checkout.' </p>
    <br>
    <p style="margin: 0;">Contact Information: </p>
    <p style="margin: 0;">'.$order->get_billing_first_name().' '.$order->get_billing_last_name().'</p>
    <p style="margin: 0;">'.$order->get_billing_email().' </p>
    <p style="margin: 0;">'.$order->get_billing_phone().' </p>
    <br>
    <p style="margin: 0;">Additional Notes: </p>
    <p style="margin: 0;">'.$booking_notes.' </p>';

$headers = array('Content-Type: text/html; charset=UTF-8');

// Send the email
wp_mail($user_email, $subject, $email_content, $headers);

?>
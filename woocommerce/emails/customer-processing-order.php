<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<?php /* translators: %s: Order number */ ?>
<p><?php printf( esc_html__( 'Just to let you know &mdash; we\'ve received your order #%s, and it is now being processed:', 'woocommerce' ), esc_html( $order->get_order_number() ) ); ?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

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

<div class="custom-email-processing">
<p>Location: <?php echo $location; ?></p>
<br>
<p>Booking Details:</p>
<p><?php echo $checkin; ?> to <?php echo $checkout; ?> </p>
</div>

<?php

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );

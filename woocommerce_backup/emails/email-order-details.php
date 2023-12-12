<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
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

defined( 'ABSPATH' ) || exit;

$orders = wc_get_order($order->get_id());
$first_item = current($orders->get_items());
$product_id = $first_item->get_product_id();
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


?>

<p>Meeting Room: <?php echo $first_item->get_name(); ?></p>
<p>Location: <?php echo $location; ?> </p>
<br>
<p>Booking Details: </p>
<p>Order #<?php echo $order->get_id(); ?> details </p>
<p><?php echo $formatted_date; ?> </p>
<p><?php echo $checkin; ?> to <?php echo $checkout; ?> </p>
<br>


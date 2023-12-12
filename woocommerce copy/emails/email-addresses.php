<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 5.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$booking_notes = get_post_meta($order->get_id(), 'booking_notes', true);
?>

<p>Contact Information: </p>
<p><?php echo $order->get_billing_first_name().' '.$order->get_billing_last_name() ?></p>
<p><?php echo $order->get_billing_email(); ?> </p>
<p><?php echo $order->get_billing_phone(); ?> </p>
<br>
<p>Additional Notes: </p>
<p><?php echo $booking_notes; ?> </p>
<br>
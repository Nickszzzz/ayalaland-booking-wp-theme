<?php

/**
 * @snippet       Send Formatted Email @ WooCommerce Custom Order Status
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// Uses 'woocommerce_order_status_' hook
  
include_once get_stylesheet_directory() . '/woocommerce_customs/order-product-information.php';

add_action( 'woocommerce_order_status_pending_order', 'bbloomer_status_custom_notification_pending_order', 20, 2 );
  
function bbloomer_status_custom_notification_pending_order( $order_id, $order ) {
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $order_product_info = new OrderProductInformation();
    $email_from = $order_product_info->get_from_name();
    $billing_email = $order_product_info->get_billing_email($order_id);
    $billing_firstname = $order_product_info->get_billing_first_name($order_id);
    $billing_lastname = $order_product_info->get_billing_last_name($order_id);
    $checkin = $order_product_info->get_checkin($order_id);
    $checkout = $order_product_info->get_checkout($order_id);
    $number_of_seats = $order_product_info->get_number_of_seats($order_id);
    $order_name = $order_product_info->get_order_title($order_id);
    $author_id = $order_product_info->get_author_id_of_order_product($order_id);
    $position =  get_field('position', 'user_' . $author_id);
    $phone_number =  get_field('phone_number', 'user_' . $author_id);
    $firstname = get_user_meta($author_id, 'first_name', true);
    $lastname = get_user_meta($author_id, 'last_name', true);
	$product_id = get_post_meta($order_id, 'product_id', true);
    $payment_gateway = get_field('payment_gateway', $product_id);
    $location_id = get_field('room_description_location', $product_id)->ID;
    $booking_notes = get_field( 'booking_notes', $order_id );
    $ad_ons = get_field('ad_ons', $order_id);
    
    // Get the tags for the post
    $post_tags = wp_get_post_tags($location_id);
    
    $email_info = array(
        array(
            "name" => $billing_firstname.' '.$billing_lastname,
            "email" => $billing_email
            // "email" => "jaymark@syntacticsinc.com"

        ),
    );

    
    $items = $order->get_items();
    $product_name = '';
    // Check if there are items in the order
    if ($items) {
        foreach ($items as $item_id => $item) {
            // Get the product name for each item
            $product_name = $item->get_name();
        }
    
    }
    $payment_email = 'ortiz.cathie@ayalalandoffices.com.ph';
    if(strtolower($post_tags[0]->name) !== 'makati') {
        $payment_email = $author_email;
    }

    foreach($email_info as $info) {
        // Your email template
        // Your email template
        $message = "
            <p>Dear ".$info['name'].",</p>

            <p>We would like to inform you that your meeting room booking request for ".getDateFromDateTimeString($checkin).", ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)." is currently pending confirmation. <br>We are in the process of finalizing the details and will update you shortly.</p>

            <p><strong>Booking Details:</strong></p>
            <ul>
                <li><strong>Meeting Title:</strong> ".$order_name."</li>
                <li><strong>Location:</strong> ".$post_tags[0]->name."</li>
                <li><strong>Meeting Room:</strong> ".$product_name."</li>
                <li><strong>Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                <li><strong>Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                <li><strong>Add-Ons:</strong> ".$ad_ons."</li>
                 <li><strong>Number of Attendees:</strong> ".$number_of_seats."</li>
            </ul>


            <p>If you have any urgent inquiries or need immediate assistance, please feel free to contact us at ".$phone_number.".</p>

            <p>Thank you for your patience, and we look forward to confirming your booking soon.</p>

            <p>Best regards,<br>Ayala Land Offices</p>
        ";


        // Send the email
        wp_mail($info['email'], $email_from.' | Pending Room Booking', $message, $headers);

    }

    $post_id = get_post_meta($order_id, 'post_id', true);
    $order_status_slug = $order->get_status();
    // Get the human-readable order status name
    $order_status_name = wc_get_order_status_name($order_status_slug);
    update_field('order_status', $order_status_name, $post_id);
}
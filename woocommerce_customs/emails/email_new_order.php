<?php

/**
 * @snippet       Send Formatted Email @ WooCommerce Custom Order Status
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// import class
include_once get_stylesheet_directory() . '/woocommerce_customs/order-product-information.php';

function getDateFromDateTimeString($dateTimeString) {
    // Convert the datetime string to a timestamp
    $date = new DateTime($dateTimeString);
    $formattedDate = $date->format('M d, Y');

    return $formattedDate;
}

function getTimeFromDateTimeString($dateTimeString) {
    // Convert the datetime string to a timestamp
    $dateTime = new DateTime($dateTimeString);
    $formattedTime = $dateTime->format('h:i A');

    return $formattedTime;
}

add_action( 'woocommerce_order_status_ayala_new_order', 'bbloomer_status_custom_notification_ayala_new_order', 20, 2 );
  
function bbloomer_status_custom_notification_ayala_new_order( $order_id, $order ) {

    
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $order_product_info = new OrderProductInformation();
    $email_sender = $order_product_info->get_email_sender();
    $email_from = $order_product_info->get_from_name();
    $author_email = $order_product_info->get_author_of_order_product($order_id);
    $author_name = $order_product_info->get_author_name_of_order_product($order_id);
    $billing_email = $order_product_info->get_billing_email($order_id);
    $billing_firstname = $order_product_info->get_billing_first_name($order_id);
    $billing_lastname = $order_product_info->get_billing_last_name($order_id);
    $checkin = $order_product_info->get_checkin($order_id);
    $checkout = $order_product_info->get_checkout($order_id);
    $number_of_seats = $order_product_info->get_number_of_seats($order_id);
    $order_name = $order_product_info->get_order_title($order_id);
	$product_id = get_post_meta($order_id, 'product_id', true);
    $payment_gateway = get_field('payment_gateway', $product_id);
    $location_id = get_field('room_description_location', $product_id)->ID;
    $booking_notes = get_field( 'booking_notes', $order_id );
    $ad_ons = get_field('ad_ons', $order_id);
    
    // Get the tags for the post
    $post_tags = wp_get_post_tags($location_id);
    $message1 = "
            <p>Dear ".$info['name'].",</p>

            <p>I hope this message finds you well. We appreciate your continued use of our facilities.</p>

            <p>We would like to inform you that a meeting room booking request has been submitted. The details are as follows:</p>

            <ul>
                <li><strong>Booking ID:</strong> ALO".padNumber($order_id, 6)."</li>
                <li><strong>Location:</strong> ".$post_tags[0]->name."</li>
                <li><strong>Meeting Room:</strong> ".$product_name."</li>
                <li><strong>Booked Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                <li><strong>Booked Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                <li><strong>Add-ons:</strong> ".$ad_ons."</li>
            </ul>


            <p>If you have any specific preferences or additional requirements, please reply to this email at your earliest convenience. <br/>The Admin team will be coordinating the reservation, and we will do our best to accommodate your needs.</p>

            <p>Thank you for your cooperation, and we look forward to assisting you with a successful meeting.</p>

            <p>Best regards,<br>Ayala Land Offices</p>
        ";
        $message2 = "
            <p>Dear ".$info['name'].",</p>

            <p>I hope this message finds you well. We appreciate your continued use of our facilities.</p>

            <p>We would like to inform you that a meeting room booking request has been submitted. The details are as follows:</p>

            <ul>
                <li><strong>Booking ID:</strong> ALO".padNumber($order_id, 6)."</li>
                <li><strong>Location:</strong> ".$post_tags[0]->name."</li>
                <li><strong>Meeting Room:</strong> ".$product_name."</li>
                <li><strong>Booked Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                <li><strong>Booked Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                <li><strong>Add-ons:</strong> ".$ad_ons."</li>
            </ul>


            <p>If you have any specific preferences or additional requirements, please reply to this email at your earliest convenience. <br/>The Admin team will be coordinating the reservation, and we will do our best to accommodate your needs.</p>

            <p>Thank you for your cooperation, and we look forward to assisting you with a successful meeting.</p>

            <p>Best regards,<br>Ayala Land Offices</p>
        ";

        $message3 = "
            <p>Dear ".$info['name'].",</p>

            <p>I hope this message finds you well. We appreciate your continued use of our facilities.</p>

            <p>We would like to inform you that a meeting room booking request has been submitted. The details are as follows:</p>

            <ul>
                <li><strong>Booking ID:</strong> ALO".padNumber($order_id, 6)."</li>
                <li><strong>Location:</strong> ".$post_tags[0]->name."</li>
                <li><strong>Meeting Room:</strong> ".$product_name."</li>
                <li><strong>Booked Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                <li><strong>Booked Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                <li><strong>Add-ons:</strong> ".$ad_ons."</li>
            </ul>


            <p>If you have any specific preferences or additional requirements, please reply to this email at your earliest convenience. <br/>The Admin team will be coordinating the reservation, and we will do our best to accommodate your needs.</p>

            <p>Thank you for your cooperation, and we look forward to assisting you with a successful meeting.</p>

            <p>Best regards,<br>Ayala Land Offices</p>
        ";
    $email_info = array(
        array(
            "name" => "Admin",
            "email" => $email_sender
        ),
        array(
            "name" => $author_name,
            "email" => $author_email

        ),
        array(
            "name" => $billing_firstname.' '.$billing_lastname,
            "email" => $billing_email
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
        
        // Send the email
        wp_mail($info['email'], $email_from.' | New Room Booking', $message, $headers);

    }

    $post_id = get_post_meta($order_id, 'post_id', true);
    $order_status_slug = $order->get_status();
    // Get the human-readable order status name
    $order_status_name = wc_get_order_status_name($order_status_slug);
    update_field('order_status', $order_status_name, $post_id);

}
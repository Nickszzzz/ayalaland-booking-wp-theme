<?php

/**
 * @snippet       Send Formatted Email @ WooCommerce Custom Order Status
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// Targets custom order status "refused"
// Uses 'woocommerce_order_status_' hook
  
include_once get_stylesheet_directory() . '/woocommerce_customs/order-product-information.php';

$order_product_info = new OrderProductInformation();

$author_id = $order_product_info->get_author_id_of_order_product(1142);
add_action( 'woocommerce_order_status_approved_request', 'bbloomer_status_custom_notification_approved_request', 20, 2 );
  
function bbloomer_status_custom_notification_approved_request( $order_id, $order ) {
    

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $order_product_info = new OrderProductInformation();
    $email_sender = $order_product_info->get_email_sender();
    $author_email = $order_product_info->get_author_of_order_product($order_id);
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
    $location_id = get_field('room_description_location', $product_id)->ID;
    $payment_gateway = get_field('payment_gateway', $product_id);
    $booking_notes = get_field( 'booking_notes', $order_id );
    $ad_ons = get_field('ad_ons', $order_id);
    // Get the tags for the post
    $post_tags = wp_get_post_tags($location_id);

    $items = $order->get_items();
    $product_name = '';
    $product_id = '';
    // Check if there are items in the order
    if ($items) {
        foreach ($items as $item_id => $item) {
            // Get the product name for each item
            $product_name = $item->get_name();
            $product_id = $item->get_product_id();
        }
    
    }
    $location_id = get_field('room_description_location', $product_id)->ID;

    $payment_email = 'ortiz.cathie@ayalalandoffices.com.ph';
    if(strtolower($post_tags[0]->name) !== 'makati') {
        $payment_email = $author_email;
    }


    $email_info = array(
        array(
            "name" => "Admin",
            "email" => $email_sender,
            "message" => "
                <p>Admin,</p>

                <p>A cancellation request for the meeting room booking on ".getDateFromDateTimeString($checkin).", ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)." has been approved by the center admin.</p>

                <p><strong>Booking Details </strong></p>
                 <ul>
                    <li><strong>Booking ID:</strong> ALO".padNumber($order_id, 6)."</li>
                    <li><strong>Location:</strong> ".html_entity_decode(get_the_title($location_id), ENT_QUOTES, 'UTF-8')."</li>
                    <li><strong>Meeting Room:</strong> ".$product_name."</li>
                    <li><strong>Booked Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                    <li><strong>Booked Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                    <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                    <li><strong>Add-ons:</strong> ".$ad_ons."</li>
                </ul>

                <p>Reason for Cancellation: ".get_field('reason', $product_id)."</p>

                <p>Thank you.</p>

                <p>Best regards,<br>Ayala Land Offices</p>
            ",
            "subject" => $email_from.' | Approved Booking Cancellation Request'
        ),
        array(
            "name" => $author_name,
            "email" => $author_email,
            "message" => "
                <pCenter Admin,</p>

                <p>A cancellation request for the meeting room booking on ".getDateFromDateTimeString($checkin).", ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)." has been approved by the center admin.</p>

                <p><strong>Booking Details </strong></p>
                 <ul>
                    <li><strong>Booking ID:</strong> ALO".padNumber($order_id, 6)."</li>
                    <li><strong>Location:</strong> ".html_entity_decode(get_the_title($location_id), ENT_QUOTES, 'UTF-8')."</li>
                    <li><strong>Meeting Room:</strong> ".$product_name."</li>
                    <li><strong>Booked Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                    <li><strong>Booked Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                    <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                    <li><strong>Add-ons:</strong> ".$ad_ons."</li>
                </ul>
                
                <p>Reason for Cancellation: ".get_field('reason', $product_id)."</p>

                <p>Thank you.</p>

                <p>Best regards,<br>Ayala Land Offices</p>
            ",
            "subject" => $email_from.' | Approved Booking Cancellation Request '

        ),
        array(
            "name" => $billing_firstname.' '.$billing_lastname,
            "email" => $billing_email,
            "message" => "
                <p>Dear ".$billing_firstname.' '.$billing_lastname.",</p>

                <p>We are pleased to inform you that your cancellation request for the meeting room booking on ".getDateFromDateTimeString($checkin).", ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)." has been approved by the center admin.</p>

                <p><strong>Booking Details </strong></p>
                 <ul>
                    <li><strong>Booking ID:</strong> ALO".padNumber($order_id, 6)."</li>
                    <li><strong>Location:</strong> ".html_entity_decode(get_the_title($location_id), ENT_QUOTES, 'UTF-8')."</li>
                    <li><strong>Meeting Room:</strong> ".$product_name."</li>
                    <li><strong>Booked Date:</strong> ".getDateFromDateTimeString($checkin)."</li>
                    <li><strong>Booked Time:</strong> ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)."</li>
                    <li><strong>Booking Notes:</strong>  ".$booking_notes."</li>
                    <li><strong>Add-ons:</strong> ".$ad_ons."</li>
                </ul>
                
                <p>Our center admin will reach out to you shortly to assist with the refund process.</p>
                <p>If you have any questions or need further assistance, please do not hesitate to contact us.</p>
                <p>Thank you.</p>

                <p>Best regards,<br>Ayala Land Offices</p>
            ",
             "subject" => $email_from.' | Approved Booking Cancellation Request'
        ),
    );
    foreach($email_info as $info) {
        // Send the email
        wp_mail($info['email'], $info['subject'], $info['message'], $headers);

    }

    $post_id = get_post_meta($order_id, 'post_id', true);
    $order_status_slug = $order->get_status();
    // Get the human-readable order status name
    $order_status_name = wc_get_order_status_name($order_status_slug);
    update_field('order_status', $order_status_name, $post_id);
}
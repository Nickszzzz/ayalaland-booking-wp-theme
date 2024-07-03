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

add_action( 'woocommerce_order_status_ayala_denied', 'bbloomer_status_custom_notification_ayala_denied', 20, 2 );
  
function bbloomer_status_custom_notification_ayala_denied( $order_id, $order ) {
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $order_product_info = new OrderProductInformation();
    $email_from = $order_product_info->get_from_name();
    $billing_email = $order_product_info->get_billing_email($order_id);
    $billing_firstname = $order_product_info->get_billing_first_name($order_id);
    $billing_lastname = $order_product_info->get_billing_last_name($order_id);
    $checkin = $order_product_info->get_checkin($order_id);
    $checkout = $order_product_info->get_checkout($order_id);
    $order_name = $order_product_info->get_order_title($order_id);
    $author_id = $order_product_info->get_author_id_of_order_product($order_id);
    $position =  get_field('position', 'user_' . $author_id);
    $phone_number =  get_field('phone_number', 'user_' . $author_id);
    $firstname = get_user_meta($author_id, 'first_name', true);
    $lastname = get_user_meta($author_id, 'last_name', true);
    $reason = get_post_meta($order_id, '_reason', true);
    $booking_notes = get_field( 'booking_notes', $order_id );
    $ad_ons = get_field('ad_ons', $order_id);

    $email_info = array(
        array(
            "name" => $billing_firstname.' '.$billing_lastname,
            "email" => $billing_email
            // "email" => "jaymark@syntacticsinc.com"
        ),
    );

    


    foreach($email_info as $info) {
        // Your email template
        $message = "
            <p>Dear ".$info['name'].",</p>

            <p>We regret to inform you that your meeting room booking request for ".getDateFromDateTimeString($checkin).", ".getTimeFromDateTimeString($checkin)." - ".getTimeFromDateTimeString($checkout)." has been denied due to ".$reason.".</p>

            <p>We understand that this may cause inconvenience, and we apologize for any disruption to your plans. <br>If you have any questions or would like to discuss alternative options, please feel free to contact us at ".$phone_number.".</p>

            <p>Thank you for your understanding.</p>

            <p>Best regards,<br>Ayala Land Offices</p>
        ";

        // Send the email
        wp_mail($info['email'], $email_from.' | Order Denied', $message, $headers);

    }

    $post_id = get_post_meta($order_id, 'post_id', true);
    $order_status_slug = $order->get_status();
    // Get the human-readable order status name
    $order_status_name = wc_get_order_status_name($order_status_slug);
    update_field('order_status', $order_status_name, $post_id);
}
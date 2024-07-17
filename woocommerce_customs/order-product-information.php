<?php 

class OrderProductInformation {

    public static function get_author_of_order_product($order_id) {
        if (empty($order_id)) {
            return 'Order ID not provided';
        }
    
        $order = wc_get_order($order_id);
    
        if (!$order) {
            return 'Order not found';
        }
    
        $items = $order->get_items();
    
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);
    
            if ($product) {
                $author_id = $product->get_post_data()->post_author;
                $author_email = get_the_author_meta('user_email', $author_id);
    
                if ($author_email) {
                    return $author_email;
                }
            }
        }
    
        return 'Author email not found';
    }

    public static function get_author_name_of_order_product($order_id) {
        if (empty($order_id)) {
            return 'Order ID not provided';
        }
    
        $order = wc_get_order($order_id);
    
        if (!$order) {
            return 'Order not found';
        }
    
        $items = $order->get_items();
    
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);
    
            if ($product) {
                $author_id = $product->get_post_data()->post_author;
                $author_name = get_the_author_meta('display_name', $author_id);
    
                if ($author_name) {
                    return $author_name;
                }
            }
        }
    
        return 'Author name not found';
    }
    


    public static function get_author_id_of_order_product($order_id) {
        if (empty($order_id)) {
            return 'Order ID not provided';
        }
    
        $order = wc_get_order($order_id);
    
        return $order_id;
        if (!$order) {
            return 'Order not found';
        }
    
        $items = $order->get_items();
    
        foreach ($items as $item) {
            $product_id = $item->get_product_id();
            $product = wc_get_product($product_id);
    
            if ($product) {
                $author_id = $product->get_post_data()->post_author;
    
                if ($author_id) {
                    return $author_id;
                }
            }
        }
    
        return 'Author ID not found';
    }
    


    public static function get_email_sender() {
       
       $wc_emails =  WC()->mailer()->get_emails();
        // Output the email senders
        foreach ($wc_emails as $email_class) {
            return $email_class->get_from_address();
        }
    }

    public static function get_from_name() {
        $wc_emails =  WC()->mailer()->get_emails();
        // Output the email senders
        foreach ($wc_emails as $email_class) {
            return $email_class->get_from_name();
        }
    }

    public static function get_order_title($order_id) {

       return "Order #".$order_id;
    }

    public static function get_billing_first_name($order_id) {
        $billing_first_name = get_post_meta($order_id, '_billing_first_name', true);
        return $billing_first_name;
    }

    public static function get_billing_last_name($order_id) {
        $billing_last_name = get_post_meta($order_id, '_billing_last_name', true);
        return $billing_last_name;
    }

    public static function get_billing_company($order_id) {
        $billing_company = get_post_meta($order_id, '_billing_company', true);
        return $billing_company;
    }

    public static function get_billing_email($order_id) {
        $billing_email = get_post_meta($order_id, '_billing_email', true);
        return $billing_email;
    }

    public static function get_billing_phone($order_id) {
        $billing_phone = get_post_meta($order_id, '_billing_phone', true);
        return $billing_phone;
    }

    public static function get_billing_country($order_id) {
        $billing_country = get_post_meta($order_id, '_billing_country', true);
        return $billing_country;
    }

    public static function get_tin_number($order_id) {
        $tin_number = get_post_meta($order_id, 'billing_tin_number', true);
        return $tin_number;
    }

    public static function get_checkin($order_id) {
        $checkin = get_post_meta($order_id, 'checkin', true);
        return $checkin;
    }

    public static function get_checkout($order_id) {
        $checkout = get_post_meta($order_id, 'checkout', true);
        return $checkout;
    }

    public static function get_number_of_hours($order_id) {
        $number_of_hours = get_post_meta($order_id, 'number_of_hours', true);
        return $number_of_hours;
    }

    public static function get_booking_notes($order_id) {
        $booking_notes = get_post_meta($order_id, 'booking_notes', true);
        return $booking_notes;
    }

    public static function get_number_of_seats($order_id) {
        $number_of_seats = get_post_meta($order_id, 'number_of_seats', true);
        return $number_of_seats;
    }
    


}
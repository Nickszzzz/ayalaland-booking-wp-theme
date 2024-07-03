<?php


// Add the metabox
add_action('add_meta_boxes', 'add_reason_metabox', 10,3);

function add_reason_metabox() {
    add_meta_box(
        'reason_metabox',
        'Reason for Denial',
        'display_reason_metabox',
        'woocommerce_page_wc-orders',
        'advanced',
        'high'
    );
}

// Display the metabox content for "Reason for Denial"
function display_reason_metabox($order) {
    $reason = get_post_meta($order->get_id(), '_reason', true);
    ?>
    <div>
        <label for="reason"><?php _e('Reason for Denial:', 'textdomain'); ?></label>
        <input type="text" id="reason" name="reason" value="<?php echo esc_attr($reason); ?>" style="width: 100%;" />
    </div>
    <div style="margin-top: 1rem;">

        <button type="button" class="button button-primary" id="save_reason">Save Reason</button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var button = document.getElementById('save_reason');
            if (button) {
                button.addEventListener('click', function () {
                    button.classList.add('loading-button');
                    var denialReasonValue = document.getElementById('reason').value;
                    // Send an AJAX request to save the value
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', ajaxurl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Optionally, you can handle the response here
                            button.classList.remove('loading-button');
                            button.style.borderColor = '';
                        }
                    };
                    xhr.send('action=save_reason&order_id=<?php echo esc_js($order->get_id()); ?>&reason=' + encodeURIComponent(denialReasonValue));
                });
            }
        });
    </script>
    <?php
}

// Save the custom field when the button is clicked
add_action('wp_ajax_save_reason', 'ajax_save_reason');

function ajax_save_reason() {
    $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
    $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';

    if ($order_id) {
        $order = wc_get_order($order_id);
        update_post_meta($order_id, '_reason', $reason);
        update_field('cancel_reason', $reason, $order_id);

        $checkinDateTime = new DateTime(get_field( 'checkin', $order_id ));
        $checkoutDateTime = new DateTime(get_field( 'checkout', $order_id ));
        $user_id = get_field( 'user_id', $order_id );
    
        // Format the dates to the desired format
        $checkinFormatted = $checkinDateTime->format('F j, Y, g:i A');
        $checkoutFormatted = $checkoutDateTime->format('g:i A');
    
        create_notification_post_with_acf(
            "Booking Cancelled", 
            "Your meeting room booking request for [$checkinFormatted - $checkoutFormatted] has been cancelled due to [$reason].", 
            $user_id, 
            false
        );
    
    
        if ( $order ) {
            // Initialize a variable to store the first product ID
            $product_id = null;
            
            // Loop through the order items and get the first product ID
            foreach ( $order->get_items() as $item_id => $item ) {
                // Get the product ID from the order item
                $product_id = $item->get_product_id();
                
                // Break the loop after getting the first product ID
                break;
            }
     
        }
    
        $author_id = get_post_field('post_author', $product_id);
        $product = wc_get_product( $product_id );
        $product_name = $product->get_name();
    
        create_notification_post_with_acf(
            "Cancelled Room Booking", 
            "A meeting room cancellation for a booking on [$checkinFormatted - $checkoutFormatted] has been requested by a customer. Requesting for your decision on the cancellation request.", 
            $author_id, 
            false
        );
    }

    wp_die();
}
// Save the custom field when the order is saved or updated
add_action('woocommerce_process_shop_order_meta', 'save_reason');

function save_reason($order_id) {
    $reason = isset($_POST['reason']) ? sanitize_text_field($_POST['reason']) : '';

    update_post_meta($order_id, '_reason', $reason);
    update_field('cancel_reason', $reason, $order_id);
}



// add_action( 'add_meta_boxes', 'bbloomer_order_meta_box', 10, 2  );
 
function bbloomer_order_meta_box($order) {
    add_meta_box( 'additional_information', 'Additional Informations', 'bbloomer_single_order_meta_box', 'woocommerce_page_wc-orders', 'advanced', 'high' );
}
 
function bbloomer_single_order_meta_box($order) {
    // global $post; // OPTIONALLY USE TO ACCESS ORDER POST

    $id = $order->get_id();
    $orders = wc_get_order($order->get_id());
    $first_item = current($orders->get_items());
    $product_id = $first_item->get_product_id();
    $location_id = get_field('room_description_location', $product_id);

    // Get all post meta for the order
    $all_meta = get_post_meta($id);
    $billing_email = get_post_meta($id, '_billing_email', true);
    // // Assuming $location_id is the ID of the location post
    // $location_id = 845; // Replace with the actual ID

    // // Get the post author (user ID) of the location post
    $author_id = get_post_field('post_author', $location_id);
update_post_meta($id, 'product_id', $product_id);
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

    }
    $user_email = '	totestertester@gmail.com';
    $user = get_user_by('email', $user_email);
    if ($user) {
        // Get the user's first name
        $first_name = get_user_meta($user->ID, 'first_name', true);
    
        // Display the user's first name
    }

    $checkin = get_post_meta($order->get_id(), 'checkin', true);
	$checkout = get_post_meta($order->get_id(), 'checkout', true);
    // Parse the date and time using DateTime
    $checkinDateTime = new DateTime($checkin);
    $checkoutDateTime = new DateTime($checkout);

    // Format the time as HH:mm:ss A
    $checkin_time = $checkinDateTime->format('g:i:s A');
    $checkout_time = $checkoutDateTime->format('g:i:s A');

?>
<table>
    <thead>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($all_meta as $field => $values): ?>
        <tr>
            <td style="width: 50%;"><?php echo htmlspecialchars($field); ?></td>
            <td><?php echo htmlspecialchars($values[0]); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
    // echo 'Whatever HTML content'.$order;
}

// Add the metabox
//add_action('add_meta_boxes', 'add_payment_gateway_link_metabox', 100);

function add_payment_gateway_link_metabox() {
    add_meta_box(
        'payment_gateway_link_metabox',
        'Payment Gateway Link',
        'display_payment_gateway_link_metabox',
        'woocommerce_page_wc-orders', 'advanced', 'high'
    );
}

// Display the metabox content for "Payment Gateway Link"
function display_payment_gateway_link_metabox($order) {
    $payment_gateway_link = get_post_meta($order->get_id(), '_payment_gateway_link', true);
    ?>
    <div>
        <label for="payment_gateway_link"><?php _e('Payment Gateway Link:', 'textdomain'); ?></label>
        <input type="text" id="payment_gateway_link" name="payment_gateway_link" value="<?php echo esc_attr($payment_gateway_link); ?>" style="width: 100%;" />
    </div>
    <div style="margin-top: 1rem;">
        <button type="button" class="button button-primary" id="save_payment_gateway_link">Save Link</button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var button = document.getElementById('save_payment_gateway_link');
            if (button) {
                button.addEventListener('click', function () {
                    button.classList.add('loading-button');
                    var paymentGatewayLinkValue = document.getElementById('payment_gateway_link').value;
                    var orderId = <?php echo json_encode($order->get_id()); ?>;
                    // Send an AJAX request to save the value
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', ajaxurl, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Optionally, you can handle the response here
                            button.classList.remove('loading-button');
                            button.style.borderColor = '';
                        }
                    };
                    xhr.send('action=save_payment_gateway_link&order_id=' + orderId + '&payment_gateway_link=' + encodeURIComponent(paymentGatewayLinkValue));
                });
            }
        });
    </script>
    <?php
}

// Save the custom field when the button is clicked
//add_action('wp_ajax_save_payment_gateway_link', 'ajax_save_payment_gateway_link');

function ajax_save_payment_gateway_link() {
    $order_id = isset($_POST['order_id']) ? absint($_POST['order_id']) : 0;
    $payment_gateway_link = isset($_POST['payment_gateway_link']) ? esc_url_raw($_POST['payment_gateway_link']) : '';

    if ($order_id) {
        update_post_meta($order_id, '_payment_gateway_link', $payment_gateway_link);
    }

    wp_die();
}
<?php 

// Add action for logged in users
add_action('wp_ajax_my_ajax_action', 'my_ajax_callback');
// Add action for non-logged-in users
add_action('wp_ajax_nopriv_my_ajax_action', 'my_ajax_callback');

function my_ajax_callback() {
    // Check if the term parameter is set
    if (isset($_GET['q'])) {
        $search_term = sanitize_text_field($_GET['q']);

        // Query product categories using the REST API
        $url = home_url().'/wp-json/wp/v2/locations?search=' . urlencode($search_term);

        $response = wp_remote_get($url);

        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);

            // Exclude categories with specific names
            $exclude_categories = array('uncategorized', 'booking');
            $filtered_data = array_filter($data, function ($category) use ($exclude_categories) {
                return !in_array(strtolower($category['title']['rendered']), $exclude_categories);
            });

            // Format the filtered data as needed for Select2
            $results = array();
            foreach ($filtered_data as $category) {
                $results[] = array(
                    'id'   => $category['id'],
                    'text' => $category['title']['rendered'],
                );
            }

            // Sort the results alphabetically based on the 'text' field
            usort($results, function ($a, $b) {
                return strcmp($a['text'], $b['text']);
            });

            wp_send_json($results);
        }
    }
    else {
        // Query product categories using the REST API
        $url = home_url().'/wp-json/wp/v2/locations';

        $response = wp_remote_get($url);

        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);

            // Exclude categories with specific names
            $exclude_categories = array('uncategorized', 'booking');
            $filtered_data = array_filter($data, function ($category) use ($exclude_categories) {
                return !in_array(strtolower($category['title']['rendered']), $exclude_categories);
            });

            // Format the filtered data as needed for Select2
            $results = array();
            foreach ($filtered_data as $category) {
                $results[] = array(
                    'id'   => $category['id'],
                    'text' => $category['title']['rendered'],
                );
            }

            // Sort the results alphabetically based on the 'text' field
            usort($results, function ($a, $b) {
                return strcmp($a['text'], $b['text']);
            });

            wp_send_json($results);
        }
    }

    wp_send_json([]);
}

function get_disabled_dates_by_product_id_callback($data) {
    $product_id = isset($data['id']) ? $data['id'] : 0;
    $current_date = isset($data['current_date']) ? $data['current_date'] : date('D M d Y H:i:s T');
    $current_date_value = str_replace(' GMT 0800 (Philippine Standard Time)', '', $current_date);
    $current_date_format = date('Y-m-d', strtotime($current_date_value));

    global $wpdb;

    // Prepare and execute the SQL query
    $query = $wpdb->prepare("
    SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
    FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
    INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
    LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
    LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
    WHERE lookup.product_id = %d
    AND orders.status != 'trash'
", $product_id);

    $orders = $wpdb->get_results($query);

    $disabled_dates = array();

    foreach ($orders as $order) {
        $checkin_time_24hr = date('H:i', strtotime($order->checkin_value));
        $checkout_time_24hr = date('H:i', strtotime($order->checkout_value));
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        if($order_date == $current_date_format) {
            $disabled_dates[] = [
                "start" => $checkin_time_24hr, 
                "end" => $checkout_time_24hr
            ];
        }
    }
    return $disabled_dates;
}

function register_disabled_dates_endpoint() {
    register_rest_route('custom/v1', '/disabled-dates/', array(
        'methods' => 'GET',
        'callback' => 'get_disabled_dates_by_product_id_callback',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
            'current_date' => array(
                'validate_callback' => function($param, $request, $key) {
                    // Add your validation logic for the current date parameter if needed
                    return true;
                }
            ),
        ),
    ));
}

add_action('rest_api_init', 'register_disabled_dates_endpoint');


// Register a custom REST API endpoint
function custom_api_route() {
    register_rest_route('custom/v1', '/completed-orders/', array(
        'methods' => 'GET',
        'callback' => 'get_completed_orders',
    ));
}
add_action('rest_api_init', 'custom_api_route');

// Callback function to handle the API request
function get_completed_orders($data) {
    global $wpdb;

    $product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;


    $query   = new WC_Order_Query( array(
        'limit'      => -1,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'return'     => 'ids',
    ) );
    $orders  = $query->get_orders();

    $completed_dates = array();
    foreach ( $orders as $order_id ) {
        $order  = wc_get_order( $order_id );
        // $completed_dates[ $order_id ]    = $order->get_date_completed();
        $first_item = current($order->get_items());
        $order_product_id = $first_item->get_product_id();
        $all_meta = get_post_meta($order_id);
        $order_date = date('Y-m-d', strtotime($all_meta['checkin'][0]));

        if($order_product_id == $product_id) {
            $date_completed = $order->get_date_completed();
            if ($date_completed) {
                $completed_dates[] = array(
                    "date" => $order_date,
                    "background" => "#F84F6E",
                );
            }
        }
    }
    return $completed_dates;

}

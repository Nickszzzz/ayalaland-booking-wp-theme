<?php 

// Add action for logged in users
add_action('wp_ajax_my_ajax_action', 'my_ajax_callback');
// Add action for non-logged-in users
add_action('wp_ajax_nopriv_my_ajax_action', 'my_ajax_callback');

function my_ajax_callback() {
    $cloudfront_domain = 'http://ayalaland.local';
    // Check if the term parameter is set
    if (isset($_GET['q'])) {
        $search_term = sanitize_text_field($_GET['q']);
    
        // Construct the REST API URL with the search parameter for the title
        $url = $cloudfront_domain . '/ayala/wp-json/wp/v2/locations?search=' . urlencode($search_term);
        $response = wp_remote_get($url);

        $tags_url = $cloudfront_domain . '/ayala/wp-json/wp/v2/tags?search=' . urlencode($search_term);
        $tags_response = wp_remote_get($tags_url);
        $final_data = array();
        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);
            
            // Exclude categories with specific names
            $exclude_categories = array('uncategorized', 'booking');
            $filtered_data = array_filter($data, function ($location) use ($exclude_categories, $search_term) {
                $title = strtolower($location['title']['rendered']);
                return !in_array($title, $exclude_categories) && strpos($title, strtolower($search_term)) !== false;
            });

    
            // Format the filtered data as needed for Select2
            foreach ($filtered_data as $location) {
            array_push($final_data, array(
                'id'   => $location['id'],
                'text' => $location['title']['rendered'],
            ));

            }
    
            // Sort the results alphabetically based on the 'text' field
            usort($final_data, function ($a, $b) {
                return strcmp($a['text'], $b['text']);
            });

            // echo count($results);

        }

        if (!is_wp_error($tags_response) && $tags_response['response']['code'] === 200) {
            $data = json_decode($tags_response['body'], true);
            $tag_ids = array();


            foreach($data as $tag_data) {
                array_push($tag_ids, $tag_data['id']);
            }

            // Set up the query arguments
            $args = array(
                'post_type'      => 'location',  // Replace 'location' with your actual post type
                'posts_per_page' => -1,           // Retrieve all posts
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'post_tag',  // Replace 'post_tag' with your actual taxonomy
                        'field'    => 'id',
                        'terms'    => $tag_ids,
                    ),
                ),
            );

            // Perform the query
            $query = new WP_Query($args);

            // Check if there are posts
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();

                    // Output the location information
                    $location_id = get_the_ID();
                    $location_title = get_the_title();
                    array_push($final_data, array(
                        'id'   => $location_id,
                        'text' => $location_title,
                    ));
                }

                // Restore original post data
                wp_reset_postdata();
            }

            // Sort the results alphabetically based on the 'text' field
            usort($final_data, function ($a, $b) {
                return strcmp($a['text'], $b['text']);
            });
        }

            // Initialize an empty array to store unique values
            $uniqueArray = [];

            // Iterate through the input array
            foreach ($final_data as $item) {
                // Use the combination of 'id' and 'text' as a key
                $key = $item['id'] . '|' . $item['text'];

                // Check if the key already exists in the unique array
                if (!isset($uniqueArray[$key])) {
                    // If not, add the item to the unique array
                    $uniqueArray[$key] = $item;
                }
            }

            // Convert the unique array values back to indexed array
            $uniqueArray = array_values($uniqueArray);

            wp_send_json($uniqueArray);

    }
    
    // if (isset($_GET['q'])) {
    //     $search_term = sanitize_text_field($_GET['q']);
    
    //     // Query product categories and post tags using the REST API
    //     $url = $cloudfront_domain . '/ayala/wp-json/wp/v2/locations?search=' . urlencode($search_term);
    //     $url .= '&type[]=category&type[]=post_tag'; // Include both categories and tags in the search
    
    //     $response = wp_remote_get($url);
    
    //     if (!is_wp_error($response) && $response['response']['code'] === 200) {
    //         $data = json_decode($response['body'], true);

    //         // Exclude categories with specific names
    //         $exclude_categories = array('uncategorized', 'booking');
    
    //         // Filter the data based on search term
    //         $filtered_data = array_filter($data, function ($item) use ($exclude_categories, $search_term) {
    //             $category_title = strtolower($item['title']['rendered']);
    //             $is_category_valid = !in_array($category_title, $exclude_categories) && strpos($category_title, strtolower($search_term)) !== false;
    
    //             // Fetch and filter tags
    //             $tag_ids = $item['tags']; // Assuming 'tags' contains an array of tag IDs
    //             // $tag_names = $this->getTagNames($tag_ids); 
    //             $tag_ids_in_response = array();
    //             foreach ($tag_ids as $tag_id) {
    //                 $tag_url = $cloudfront_domain . "/ayala/wp-json/wp/v2/tags?search={$search_term}";
    //                 $tag_response = wp_remote_get($tag_url);
            
    //                 if (!is_wp_error($tag_response) && $tag_response['response']['code'] === 200) {
    //                     $tag_data = json_decode($tag_response['body'], true);
    //                     foreach ($tag_data as $tag_item) {
    //                         $tag_ids_in_response[] = $tag_item['id'];
    //                     }

    //                 }

    //             }
    //             // wp_send_json(!empty(array_intersect($tag_ids, $tag_ids_in_response)));
    //             wp_send_json($tag_ids_in_response);

    //             // $is_tag_valid = !empty(array_intersect($tag_ids, $tag_ids_in_response));

    //             // $is_tag_valid = in_array(strtolower($search_term), $tag_names);
    
    //             // return $is_category_valid || $is_tag_valid;
    //             return $is_category_valid;
    //         });
            
    
    //         // Format the filtered data as needed for Select2
    //         $results = array();
    //         foreach ($filtered_data as $item) {
    //             $results[] = array(
    //                 'id'   => $item['id'],
    //                 'text' => $item['title']['rendered'],
    //             );
    //         }
    
    //         // Sort the results alphabetically based on the 'text' field
    //         usort($results, function ($a, $b) {
    //             return strcmp($a['text'], $b['text']);
    //         });
    
    //         wp_send_json($results);
    //     }
    // }
    

    
    else {
        // Query product categories using the REST API
        $url = $cloudfront_domain.'/ayala/wp-json/wp/v2/locations';

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
    global $wpdb;
	
    $product_id = isset($data['id']) ? $data['id'] : 0;
	
    $current_date = isset($data['current_date']) ? $data['current_date'] : date('D M d Y H:i:s T');
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
	// Convert the original date string to a Unix timestamp using strtotime
	$timestamp = strtotime($current_date);

	// Format the timestamp into the desired format
	$formattedDate = date('Y-m-d', $timestamp);

    foreach ($orders as $order) {
        $checkin_time_24hr = date('H:i', strtotime($order->checkin_value));
        $checkout_time_24hr = date('H:i', strtotime($order->checkout_value));
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        if($order_date == $formattedDate) {
            $disabled_dates[] = [
                "start" => $checkin_time_24hr, 
                "end" => $checkout_time_24hr,
            ];
        }
    }
    return $disabled_dates;
}

function register_disabled_dates_endpoint() {
    register_rest_route('custom/v1', '/disabled-dates/', array(
        'methods' => 'GET',
        'callback' => 'get_disabled_dates_by_product_id_callback',
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
        'post_status' => 'wc-ayala_approved',
        'return'     => 'ids',
    ) );

    $orders  = $query->get_orders();
    $completed_dates = array();
    foreach ( $orders as $order_id ) {
        $order  = wc_get_order( $order_id );
        $checkin = get_post_meta($order_id, 'checkin', true);
        $order_product_id = intval(get_post_meta($order_id, 'product_id', false)[0]);

        if($order_product_id === $product_id) {
            $order_date = date('Y-m-d', strtotime($checkin));
            $completed_dates[] = array(
                "date" => $order_date,
                "background" => "#F84F6E",
            );
        }

    }
    return $completed_dates;

}

    /**
     * Helper function to get tag names from tag IDs.
     *
     * @param array $tag_ids Array of tag IDs.
     * @return array Array of tag names.
     */
    function getTagNames($tag_ids) {
        $tag_names = array();
        foreach ($tag_ids as $tag_id) {
            $tag_url = $cloudfront_domain . "/ayala/wp-json/wp/v2/tags/{$tag_id}";
            $tag_response = wp_remote_get($tag_url);
    
            if (!is_wp_error($tag_response) && $tag_response['response']['code'] === 200) {
                // $tag_data = json_decode($tag_response['body'], true);
                // $tag_names[] = strtolower($tag_data['name']);
                $tag_names[] = $tag_url;
            }
        }
    
        return $tag_names;
    }
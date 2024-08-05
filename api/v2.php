<?php 


// Register a custom REST API endpoint for featured locations
add_action('rest_api_init', 'register_featured_locations_endpoint');
function register_featured_locations_endpoint() {
    register_rest_route('v2', '/featured-locations', array(
        'methods' => 'GET',
        'callback' => 'get_featured_locations',
    ));
}

// Callback function to retrieve featured locations
function get_featured_locations($request) {
    $query_args = array(
        'post_type'      => 'location',
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'post_status'    => 'publish',
        'meta_query'     => array(
            array(
                'key'     => 'featured_location',
                'value'   => true, // Set to true to only show posts where 'featured_location' is true
                'compare' => '=',  // Use '=' for true/false fields
                'type'    => 'BOOLEAN', // Set the type to BOOLEAN for true/false fields
            ),
        ),
    );

    $featured_locations = get_posts($query_args);

    // Check if locations exist
     if ($featured_locations) {
        $formatted_locations = array_map('format_location_data', $featured_locations);
        return rest_ensure_response($formatted_locations);
    } else {
        return new WP_Error('no_featured_locations', 'No featured locations found', array('status' => 404));
    }
}

// Register a custom REST API endpoint for all locations
add_action('rest_api_init', 'register_all_locations_endpoint');
function register_all_locations_endpoint() {
    register_rest_route('v2', '/all-locations', array(
        'methods' => 'GET',
        'callback' => 'get_all_locations',
    ));
}

// Callback function to retrieve featured locations
function get_all_locations($request) {
    $query_args = array(
        'post_type'      => 'location',
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'post_status'    => 'publish',
    );

    $featured_locations = get_posts($query_args);

    // Check if locations exist
     if ($featured_locations) {
        $formatted_locations = array_map('format_location_data', $featured_locations);
        return rest_ensure_response($formatted_locations);
    } else {
        return new WP_Error('no_featured_locations', 'No featured locations found', array('status' => 404));
    }
}

// Function to format location data
function format_location_data($location) {
    $formatted_location = array(
        'id' => $location->ID,
        'title' => $location->post_title,
        'content' => wp_strip_all_tags($location->post_content),
        'link' => "/locations/".$location->ID, // Get the permalink for the location
        'image' => get_the_post_thumbnail_url($location->ID), // Get the URL of the featured image (thumbnail)
    );

    return $formatted_location;
}

add_action('rest_api_init', 'register_locations_endpoint');
function register_locations_endpoint() {
    register_rest_route('v2', '/admin-locations/(?P<user_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_locations_by_user_role',
        'permission_callback' => '__return_true', // Allow all requests for now, handle permissions in callback
    ));
}

function get_locations_by_user_role($request) {
    $user_id = $request->get_param('user_id');

    // Check if user exists
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return new WP_Error('invalid_user', 'Invalid user ID', array('status' => 404));
    }

    // Check user role
    if (in_array('administrator', $user->roles)) {
        // If user is admin, return all locations
        $args = array(
            'post_type' => 'location',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        $locations = get_posts($args);
        $formatted_locations = array_map('format_location_data', $locations);
        return rest_ensure_response($formatted_locations);
    } elseif (in_array('shop_manager', $user->roles)) {
        // If user is shop manager, get location ID from ACF field
        $location_id = get_field('location', 'user_' . $user_id);
        if ($location_id) {
            $location = get_post($location_id);
            if ($location && $location->post_type === 'location') {
                return rest_ensure_response(array(format_location_data($location)));
            } else {
                return new WP_Error('invalid_location', 'Invalid location ID', array('status' => 404));
            }
        } else {
            return new WP_Error('no_location_assigned', 'No location assigned to this shop manager', array('status' => 404));
        }
    } else {
        return new WP_Error('forbidden', 'You do not have permission to view locations', array('status' => 403));
    }
}

add_action('rest_api_init', 'register_admin_rooms_endpoint');
function register_admin_rooms_endpoint() {
    register_rest_route('v2', '/admin-rooms/(?P<user_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_rooms_by_user_role',
        'permission_callback' => '__return_true', // Allow all requests for now, handle permissions in callback
    ));
}

function get_rooms_by_user_role($request) {
    $user_id = $request->get_param('user_id');

    // Check if user exists
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return new WP_Error('invalid_user', 'Invalid user ID', array('status' => 404));
    }

    // Check user role
    if (in_array('administrator', $user->roles)) {
        // If user is admin, return all rooms
        $args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        );
        $rooms_query = new WP_Query($args);
        $rooms = array();

        if ($rooms_query->have_posts()) {
            while ($rooms_query->have_posts()) {
                $rooms_query->the_post();

                $room_id = get_the_ID();
                $hourly_rate = get_field('rates_hourly_rate', $room_id);
                $daily_rate = get_field('rates_daily_rate', $room_id);

                $rooms[] = array(
                    'ID'             => $room_id,
                    'title'          => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                    'description'    => get_the_excerpt(),
                    'hourly_rate'    => $hourly_rate,
                    'daily_rate'     => $daily_rate,
                    'featured_image' => get_the_post_thumbnail_url($room_id, 'full'),
                    'permalink'      => get_the_permalink(),
                );
            }
            wp_reset_postdata(); // Reset post data after query
        }

        return rest_ensure_response($rooms);

    } elseif (in_array('shop_manager', $user->roles)) {
        // If user is shop manager, get rooms ID from ACF field
        $location_id = get_field('location', 'user_' . $user_id);
        if ($location_id) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => -1,
                'meta_query'     => array(
                    array(
                        'key'     => 'room_description_location',
                        'value'   => $location_id,
                        'compare' => '=',
                    ),
                ),
            );

            $rooms_query = new WP_Query($args);
            $rooms = array();

            if ($rooms_query->have_posts()) {
                while ($rooms_query->have_posts()) {
                    $rooms_query->the_post();

                    $room_id = get_the_ID();
                    $hourly_rate = get_field('rates_hourly_rate', $room_id);
                    $daily_rate = get_field('rates_daily_rate', $room_id);

                    $rooms[] = array(
                        'ID'             => $room_id,
                        'title'          => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                        'description'    => get_the_excerpt(),
                        'hourly_rate'    => $hourly_rate,
                        'daily_rate'     => $daily_rate,
                        'featured_image' => get_the_post_thumbnail_url($room_id, 'full'),
                        'permalink'      => get_the_permalink(),
                    );
                }
                wp_reset_postdata(); // Reset post data after query
            }

            return rest_ensure_response($rooms);

        } else {
            return new WP_Error('no_rooms_assigned', 'No rooms assigned to this shop manager', array('status' => 404));
        }
    } else {
        return new WP_Error('forbidden', 'You do not have permission to view rooms', array('status' => 403));
    }
}




// Register a custom REST API endpoint for single location
add_action('rest_api_init', 'register_single_location_endpoint');
function register_single_location_endpoint() {
    register_rest_route('v2', '/location/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_single_location',
    ));
}

// Callback function to retrieve a single location by ID
function get_single_location($request) {
    $location_id = $request->get_param('id');

    $location = get_post($location_id);

    // Check if location exists and it's of the 'location' post type
    if ($location && $location->post_type === 'location') {
        $formatted_location = format_location_data($location);
        return rest_ensure_response($formatted_location);
    } else {
        return new WP_Error('invalid_location', 'Invalid location ID', array('status' => 404));
    }
}

// Register a custom REST API endpoint for fetching all WooCommerce products
add_action('rest_api_init', 'register_woocommerce_products_endpoint');
function register_woocommerce_products_endpoint() {
    register_rest_route('v2', '/featured-rooms', array(
        'methods' => 'GET',
        'callback' => 'get_all_rooms',
    ));
}

// Callback function to retrieve all WooCommerce products
function get_all_rooms($request) {
    $query_args = array(
        'post_type'      => 'product', // WooCommerce product post type
        'posts_per_page' => -1,
        'order'          => 'DESC',
        'post_status'    => 'publish',
        // 'meta_query'     => array(
        //     array(
        //         'key'     => 'featured_room',
        //         'value'   => true, // Set to true to only show posts where 'featured_location' is true
        //         'compare' => '=',  // Use '=' for true/false fields
        //         'type'    => 'BOOLEAN', // Set the type to BOOLEAN for true/false fields
        //     ),
        // ),
    );

    $products = get_posts($query_args);

    // Check if products exist
    if ($products) {
        $formatted_products = array_map('format_product_data', $products);
        return rest_ensure_response($formatted_products);
    } else {
        return new WP_Error('no_products', 'No products found', array('status' => 404));
    }
}

// Function to format product data
function format_product_data($product) {
  $room_location = get_field('room_description_location', $product->ID);

    $formatted_product = array(
        'id' => $product->ID,
        'name' => html_entity_decode($product->post_title, ENT_QUOTES, 'UTF-8'),
        'content' => wp_strip_all_tags($product->post_content), // Convert post content into raw text
        'link' => '/meeting-rooms/'.$product->ID, // Get the permalink for the product
        'image' => get_the_post_thumbnail_url($product->ID), // Get the URL of the featured image (thumbnail)
         'room_location' => $room_location ? $room_location->post_title : null,
        // You can add more fields here as needed
    );

    return $formatted_product;
}

// Add this code to your theme's `functions.php` file or a custom plugin.

// Hook to initialize the REST API endpoint
add_action('rest_api_init', 'register_custom_rooms_api');

function register_custom_rooms_api() {
    register_rest_route('v2', '/rooms/', array(
        'methods' => 'GET', // Allows GET requests
        'callback' => 'get_rooms_data', // Function to handle the request
    ));
}

// Function to retrieve rooms/products based on query parameters
function get_rooms_data(WP_REST_Request $request) {
    $meta_queries = array(); // Meta queries for filtering
    $args = array(
        'post_type' => 'product', // Assuming 'product' is your custom post type for rooms
        'posts_per_page' => -1,   // Retrieve all matching posts
        'post_status' => 'publish', // Only retrieve published posts
    );

    // Retrieve query parameters
    $room_location = sanitize_text_field($request->get_param('room_location'));
    $number_of_seats = absint($request->get_param('number_of_seats'));
    $checkin = empty($checkin = sanitize_text_field($request->get_param('checkin'))) ? null : $checkin;
    $checkout = empty($checkout = sanitize_text_field($request->get_param('checkout'))) ? null : $checkout;
    $orderby = sanitize_text_field($request->get_param('orderby'));

    // Add meta queries based on provided parameters
    if (!empty($room_location)) {
        $meta_queries[] = array(
            'key' => 'room_description_location',
            'value' => $room_location,
            'compare' => '=',
        );
    }

    if ($number_of_seats > 0) {
        $meta_queries[] = array(
            'key' => 'room_description_maximum_number_of_seats',
            'value' => $number_of_seats,
            'compare' => '>=',  
            'type' => 'NUMERIC', 
        );
    }

    if (!empty($meta_queries)) {
        $args['meta_query'] = $meta_queries; // Include meta queries in the WP_Query arguments
    }

    // Set the sorting order
    if ($orderby === 'price') {
        $args['meta_key'] = 'rates_hourly_rate';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'ASC';
    } elseif ($orderby === 'price-desc') {
        $args['meta_key'] = 'rates_hourly_rate';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } elseif ($orderby === 'popularity') {
        $args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
    } else {
        $args['orderby'] = $orderby ?? 'date'; // Default to 'date' if no other order is specified
    }

    // Query the rooms/products based on the arguments
    $query = new WP_Query($args);

    // Define a function to generate an array of dates between a start date and an end date (which could be null)
    function generateDateRange($start_date = null, $end_date = null) {
        $dates = [];
        if ($start_date !== null && $end_date === null) {
            $dates[] = DateTime::createFromFormat('m-d-Y', $start_date)->format('m/d/Y'); // Add start date if end date is null
            return $dates;
        }
        if ($start_date === null && $end_date !== null) {
            $dates[] = DateTime::createFromFormat('m-d-Y', $end_date)->format('m/d/Y'); // Add end date if start date is null
            return $dates;
        }

        $start_date = ($start_date !== null) ? DateTime::createFromFormat('m-d-Y', $start_date) : new DateTime(); // If start_date is null, use current date
        $end_date = ($end_date !== null) ? DateTime::createFromFormat('m-d-Y', $end_date) : new DateTime(); // If end_date is null, use current date

        while ($start_date <= $end_date) {
            $dates[] = $start_date->format('m/d/Y');
            $start_date->modify('+1 day');
        }

        return $dates;
    }
    

    $dates = generateDateRange($checkin, $checkout);

    if ($query->have_posts()) {
        $rooms = array(); // Array to store the results

        // Loop through query results and extract data
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $booked_dates = [];

            foreach($dates as $date) {
                $booked_dates[] = format_booked_slots_by_id(get_field('operating_hours_start', $id), get_field('operating_hours_end', $id), $id, $date);
            }

            // Extract data for each product/room
            $room = array(
                'ID' => $id,
                'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                'description' => get_the_excerpt(),
                'hourly_rate' => get_field('rates_hourly_rate', $id),
                'daily_rate' => get_field('rates_daily_rate', $id),
                'permalink' => get_permalink($id),
                'featured_image' => get_the_post_thumbnail_url($id), // Get the featured image URL,
                'number_of_seats' => get_field('room_description_maximum_number_of_seats', $id)
            );
            $room['booked_slots'] =  $booked_dates;
            $rooms[] = $room;
        }

        wp_reset_postdata(); // Reset the post data after querying

        return new WP_REST_Response($rooms, 200); // Return the list of rooms
    } else {
        return new WP_REST_Response(array('message' => 'No rooms found.'), 404); // Return 404 if no rooms are found
    }
}

function get_order_booked_slots_custom($product_id, $date) {
    global $wpdb;
	
    $current_date = date('D M d Y H:i:s T', strtotime($date));

    // $current_date = isset($data['current_date']) ? $data['current_date'] : date('D M d Y H:i:s T');

    // Prepare and execute the SQL query
    $query = $wpdb->prepare("
        SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
        FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
        INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
        LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
        LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
        WHERE lookup.product_id = %d
        AND orders.status IN ('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request')
        AND orders.status NOT IN ('trash', 'deleted')
    ", $product_id);
        $orders = $wpdb->get_results($query);


    $start_time_str = get_field('operating_hours_start', $product_id);
    $end_time_str = get_field('operating_hours_end', $product_id);
    
    // return array(
    //     $orders[0],
    //     "start" => $hours_start,
    //     "end" => $hours_end,
    // );

    $disabled_dates = array();
	// Convert the original date string to a Unix timestamp using strtotime
	$timestamp = strtotime($current_date);
    $booked_slots = array();


	// Format the timestamp into the desired format
	$formattedDate = date('Y-m-d', $timestamp);

    foreach ($orders as $order) {
        
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        if(!isValidDateFormat($order->checkin_value) && !isValidDateFormat($order->checkout_value)) continue;

        // Define input data
        $checkin_value = new DateTime($order->checkin_value);
        $checkout_value = new DateTime($order->checkout_value);

        

        // Convert start and end times to DateTime objects
        $start_time = DateTime::createFromFormat('h:i A', $start_time_str);
        $end_time = DateTime::createFromFormat('h:i A', $end_time_str);

        // Iterate through each day in the interval
        $current_date = clone $checkin_value;
        $current_date->setTime(0, 0, 0); // Set to midnight to start the day comparison

        $interval_end = clone $checkout_value;
        $interval_end->setTime(0, 0, 0); // Set to midnight to end the day comparison

        while ($current_date <= $interval_end) {
            // Calculate the working hours for the current date
            if ($current_date == $checkin_value->format('Y-m-d')) {
                $working_hours_start = $checkin_value;
            } else {
                $working_hours_start = clone $current_date;
                $working_hours_start->setTime((int)$start_time->format('H'), (int)$start_time->format('i'));
            }

            if ($current_date == $checkout_value->format('Y-m-d')) {
                $working_hours_end = $checkout_value;
            } else {
                $working_hours_end = clone $current_date;
                $working_hours_end->setTime((int)$end_time->format('H'), (int)$end_time->format('i'));
            }

            // Ensure the working hours are within the checkin and checkout bounds
            if ($working_hours_start < $checkin_value) {
                $working_hours_start = $checkin_value;
            }
            if ($working_hours_end > $checkout_value) {
                $working_hours_end = $checkout_value;
            }

            // Format the working hours for output
            $working_hours_start_formatted = $working_hours_start->format('h:i A');
            $working_hours_end_formatted = $working_hours_end->format('h:i A');

            // Output the result for the current date
            // echo $working_hours_start_formatted . " to " . $working_hours_end_formatted . "<br>";
            $checkin_time_24hr = date('H:i', strtotime($working_hours_start_formatted));
            $checkout_time_24hr = date('H:i', strtotime($working_hours_end_formatted));

            if($formattedDate === $current_date->format('Y-m-d')) {
                $booked_slots[] = [
                    "start" => $working_hours_start_formatted, 
                    "end" => $working_hours_end_formatted,
                    "date" => $current_date->format('Y-m-d'),
                    "product_id" => $product_id,
                    "product_id" => $product_id,
                ];
            }
            

            // Move to the next day
            $current_date->modify('+1 day');
        }
    }

        // Convert the booked slots into the desired format
    // $formatted_slots = array();
    // foreach ($booked_slots as $slot) {
    //     $formatted_slots[] = array($slot['start'], $slot['end'] );
    // }
    
    return $booked_slots;
}

function isValidDateFormat($date) {
    // Define the regular expression pattern
    $pattern = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/';

    // Check if the date matches the pattern
    if (preg_match($pattern, $date)) {
        // Validate the date components
        $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date);
        if ($dateTime && $dateTime->format('Y-m-d H:i:s') === $date) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// Hook to initialize the REST API endpoint
add_action('rest_api_init', 'register_suggested_rooms_endpoint');

function register_suggested_rooms_endpoint() {
    register_rest_route('v2', '/suggested-rooms', array(
        'methods' => 'GET', // Allows GET requests
        'callback' => 'get_suggested_rooms', // Function to handle the request
        'permission_callback' => '__return_true', // Allow public access to the endpoint
    ));
}

// Function to retrieve rooms/products based on query parameters
function get_suggested_rooms(WP_REST_Request $request) {
    $meta_queries = array(); // Meta queries for filtering
     // Default WP_Query arguments for suggested rooms
     $args = array(
        'post_type' => 'product', // Assuming 'room' is your custom post type
        'post_status' => 'publish', // Only published rooms
        'posts_per_page' => 3, // Fetch 5 rooms by default
        'orderby' => 'rand', // Random order for variety
    );

    // Retrieve query parameters
    $checkin = empty($checkin = sanitize_text_field($request->get_param('checkin'))) ? null : $checkin;
    $checkout = empty($checkout = sanitize_text_field($request->get_param('checkout'))) ? null : $checkout;

    // Convert check-in and check-out dates to valid date strings
    if (!empty($checkin)) {
        $checkin_date = date('Y-m-d', strtotime($checkin));
    } else {
        $checkin_date = date('Y-m-d'); // Default to today's date if check-in date is not provided
    }

    if (!empty($checkout)) {
        $checkout_date = date('Y-m-d', strtotime($checkout));
    } else {
        $checkout_date = $checkin_date; // Default to check-in date if check-out date is not provided
    }

    // Query the rooms/products based on the arguments
    $query = new WP_Query($args);

    // Define a function to generate an array of dates between a start date and an end date (which could be null)
    function generateDateRange($start_date = null, $end_date = null) {
        $dates = [];
        if ($start_date !== null && $end_date === null) {
            $dates[] = DateTime::createFromFormat('m-d-Y', $start_date)->format('m/d/Y'); // Add start date if end date is null
            return $dates;
        }
        if ($start_date === null && $end_date !== null) {
            $dates[] = DateTime::createFromFormat('m-d-Y', $end_date)->format('m/d/Y'); // Add end date if start date is null
            return $dates;
        }

        $start_date = ($start_date !== null) ? DateTime::createFromFormat('m-d-Y', $start_date) : new DateTime(); // If start_date is null, use current date
        $end_date = ($end_date !== null) ? DateTime::createFromFormat('m-d-Y', $end_date) : new DateTime(); // If end_date is null, use current date

        while ($start_date <= $end_date) {
            $dates[] = $start_date->format('m/d/Y');
            $start_date->modify('+1 day');
        }

        return $dates;
    }
    

    $dates = generateDateRange($checkin, $checkout);

    
    if ($query->have_posts()) {
        $rooms = array(); // Array to store the results

        // Loop through query results and extract data
        while ($query->have_posts()) {
            $query->the_post();
            $id = get_the_ID();

            $booked_dates = [];

            foreach($dates as $date) {
                $booked_dates[] = format_booked_slots_by_id(get_field('operating_hours_start', $id), get_field('operating_hours_end', $id), $id, $date);
            }

            // Extract data for each product/room
            $room = array(
                'ID' => $id,
                'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                'description' => get_the_excerpt(),
                'hourly_rate' => get_field('rates_hourly_rate', $id),
                'daily_rate' => get_field('rates_daily_rate', $id),
                'permalink' => get_permalink($id),
                'featured_image' => get_the_post_thumbnail_url($id), // Get the featured image URL
                'number_of_seats' => get_field('room_description_maximum_number_of_seats', $id)
            );
            $room['booked_slots'] =  $booked_dates;
            $rooms[] = $room;
        }

        wp_reset_postdata(); // Reset the post data after querying

        return new WP_REST_Response($rooms, 200); // Return the list of rooms
    } else {
        return new WP_REST_Response(array('message' => 'No rooms found.'), 404); // Return 404 if no rooms are found
    }
}

// Add the following code to your theme's functions.php or a custom plugin

// Hook to register a custom REST endpoint
add_action('rest_api_init', function () {
    register_rest_route('v2', '/meeting-rooms-by-location/(?P<location_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_meeting_rooms_by_location',
        'permission_callback' => '__return_true', // Allow public access to the endpoint
    ));
});

/**
 * Callback function to fetch products based on the given location ID
 */
function get_meeting_rooms_by_location($request) {
    $location_id = (int) $request['location_id'];

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'room_description_location',
                'value' => $location_id,
                'compare' => '=',
            ),
        ),
    );

    $query = new WP_Query($args);
    $products = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $product_id = get_the_ID();

            $hourly_rate = get_field('rates_hourly_rate', $product_id);
            $daily_rate = get_field('rates_daily_rate', $product_id);

            $products[] = array(
                'ID' => $product_id,
                'title' => html_entity_decode(get_the_title(), ENT_QUOTES, 'UTF-8'),
                'description' => get_the_excerpt(),
                'hourly_rate' => $hourly_rate,
                'daily_rate' => $daily_rate,
                'featured_image' => get_the_post_thumbnail_url($product_id, 'full'),
                'permalink' => get_the_permalink(),
                'booked_slots' => format_booked_slots(get_field('operating_hours_start', $product_id), get_field('operating_hours_end', $product_id), $product_id),
                'number_of_seats' => get_field('room_description_maximum_number_of_seats', $product_id)
            );

        }

        wp_reset_postdata(); // Reset post data after query
    } else {
        return new WP_Error('no_products_found', 'No products found for the given location.', array('status' => 404));
    }

    return new WP_REST_Response($products, 200);
}


// Register the REST API endpoint
add_action('rest_api_init', function() {
    register_rest_route('v2', '/meeting-room-header-details/(?P<room_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_room_header',
        'permission_callback' => '__return_true', // Adjust permissions if needed
    ));
});

// Callback function for the REST API endpoint
function get_room_header($request) {
    $room_id = (int) $request['room_id'];

    if (get_post_type($room_id) !== 'product') {
        return new WP_Error('invalid_post_type', 'The specified ID does not belong to a product.', array('status' => 400));
    }

    $author_id = get_post_field('post_author', $room_id);
    $author_info = get_userdata($author_id);
    $author_email = $author_info ? $author_info->user_email : null;

    // Retrieve various fields
    $title = get_the_title($room_id);
    $featured_image = get_the_post_thumbnail_url($room_id, 'full');
    $room_location = get_field('room_description_location', $room_id);
    $amenity = get_field('amenity', $room_id);
    $complete_address = get_field('complete_address', $room_id);
    $contact_email = get_field('cta_email_address', $room_id);
    $contact_numbers = get_field('cta_contact_number', $room_id);

    // Prepare the response data
    $response_data = array(
        'title' => html_entity_decode($title, ENT_QUOTES, 'UTF-8'),
        'featured_image' => $featured_image,
        'room_location' => $room_location ? $room_location->post_title : null,
        'amenity' => $amenity,
        'complete_address' => $complete_address,
        'contact_email' => $contact_email,
        'contact_numbers' => array(),
    );

    // Collect contact numbers
    if ($contact_numbers) {
        foreach ($contact_numbers as $number) {
            if (isset($number['phone_number'])) {
                $response_data['contact_numbers'][] = array(
                    'phone_number' => $number['phone_number'],
                );
            }
        }
    }

    return new WP_REST_Response($response_data, 200);
}


// Register a new REST API endpoint
add_action('rest_api_init', function() {
    register_rest_route('v2', '/meeting-room-content-details/(?P<room_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_room_details',
        'permission_callback' => '__return_true', // Adjust permission if needed
    ));
});

// Callback function for the REST API endpoint
function get_room_details($request) {
    $room_id = (int) $request['room_id'];

    // Ensure the room_id corresponds to a valid post of the desired type (e.g., 'room', 'product', etc.)
    if (get_post_status($room_id) !== 'publish') {
        return new WP_Error('invalid_post', 'The specified room ID does not refer to a valid post.', array('status' => 404));
    }

    // General information
    $title = get_the_title($room_id);
    $content = strip_tags(get_the_content(null, false, $room_id)); // Remove HTML tags
    $featured_image = get_the_post_thumbnail_url($room_id, 'full'); // Full-size featured image

    // Room attributes from custom fields
    $gla = get_field('gla', $room_id);
    $no_of_floors = get_field('no_of_floors', $room_id);
    $floor_plate = get_field('floor_plate', $room_id);
    $density = get_field('density', $room_id);
    $hourly_rate = get_field('rates_hourly_rate', $room_id);
    $daily_rate = get_field('rates_daily_rate', $room_id);
    // Image gallery
    $product_gallery_ids = explode(',', get_post_meta($room_id, '_product_image_gallery', true));
    $gallery_images = array();

    foreach ($product_gallery_ids as $attachment_id) {
        if ($attachment_id) {
            $image_url = wp_get_attachment_url($attachment_id, "full");
            $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            $gallery_images[] = array(
                'url' => $image_url,
                'alt' => $image_alt,
                'id' => $attachment_id,
            );
        }
    }

    // Video information
    $video_thumbnail = get_field('video_information_thumbnail', $room_id);
    $video_link = get_field('video_information_link', $room_id);

    $video_info = null;
    if ($video_thumbnail && $video_link) {
        $video_info = array(
            'thumbnail' => array(
                'url' => wp_get_attachment_url($video_thumbnail['id'], "full"),
                'alt' => $video_thumbnail['alt'],
            ),
            'link' => $video_link['url'],
        );
    }

    // Construct the response data
    $response_data = array(
        'title' => html_entity_decode($title),
        'content' => $content,
        'featured_image' => $featured_image,
        'room_attributes' => array(
            'gla' => $gla,
            'no_of_floors' => $no_of_floors,
            'floor_plate' => $floor_plate,
            'density' => $density,
        ),
        'gallery_images' => $gallery_images,
        'video_info' => $video_info,
        'hourly_rate' => $hourly_rate,
        'daily_rate' => $daily_rate,
    );

    return new WP_REST_Response($response_data, 200);
}

// Register a custom REST API endpoint for user registration
add_action('rest_api_init', function () {
    register_rest_route('v2', '/register', [
        'methods' => 'POST',
        'callback' => 'handle_user_registration',
        'permission_callback' => '__return_true', // Allow anyone to register
    ]);
});

// Callback function to handle user registration
function handle_user_registration($request) {

    $username = sanitize_text_field($request['username']);
    $password = $request['password'];
    $email = sanitize_email($request['email']);
    $first_name = sanitize_text_field($request['first_name']);
    $last_name = sanitize_text_field($request['last_name']);
    $contact_number = sanitize_text_field($request['contact_number']);

    if (empty($username) || empty($password) || empty($email)) {
        return new WP_Error('missing_fields', 'Missing required fields', ['status' => 400]);
    }

    if (username_exists($username) || email_exists($email)) {
        return new WP_Error('user_exists', 'Username or email already exists', ['status' => 400]);
    }

    // Create the user
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        return new WP_Error('registration_failed', 'User registration failed', ['status' => 500]);
    }

    // Update user meta information
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
    update_user_meta($user_id, 'contact_number', $contact_number);

    // Update ACF field (phone_number)
    update_field('phone_number', $contact_number, 'user_' . $user_id);

     // Set user nickname and display name to full name
     $full_name = $first_name . ' ' . $last_name;

    wp_update_user([
        'ID'           => $user_id,
        'nickname'     => $full_name, // Set nickname to full name
        'display_name' => $full_name, // Set display name to full name
    ]);


    // Set user role to customer
    $user = new WP_User($user_id);
    $user->set_role('customer');

    // Create notification or any additional actions
    create_notification_post_with_acf(
        "Welcome to Clock In!", 
        "Hi there! Welcome to our community! We're thrilled to have you with us. Do you want to book a meeting room? We got you!", 
        $user_id, 
        false
    );

    return [
        'message' => 'User registered successfully',
        'user_id' => $user_id,
        'status' => 200
    ];
}




// Register the custom API endpoint
function register_custom_booking_api() {
    register_rest_route('v2', '/booking-details/(?P<room_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'custom_booking_details_callback',
        'permission_callback' => '__return_true', // adjust permissions as needed
    ));
}

// Callback function to handle the API request
function custom_booking_details_callback(WP_REST_Request $request) {
    $room_id = $request->get_param('room_id');
    if (!$room_id) {
        return new WP_Error('no_product_id', 'Product ID is required', array('status' => 400));
    }

    // Retrieve product data based on the product ID
    $product = get_post($room_id);
    if (!$product) {
        return new WP_Error('invalid_product_id', 'Invalid Product ID', array('status' => 404));
    }

    // Get custom field data
    $room_name = get_the_title($room_id);
    $max_seats = get_field('room_description_maximum_number_of_seats', $room_id);
    $cta_map_link = get_field('cta_map_link', $room_id);
    $hourly_rate = get_field('rates_hourly_rate', $room_id);
    $daily_rate = get_field('rates_daily_rate', $room_id);
    $operating_hours_start = get_field('operating_hours_start', $room_id);
    $operating_hours_end = get_field('operating_hours_end', $room_id);
    $operating_days_starts = get_field('operating_days_starts', $room_id);
    $operating_days_ends = get_field('operating_days_ends', $room_id);
    $room_location = get_field('room_description_location', $room_id);


    // Construct the response data
    $data = array(
        'id' => $room_id,
        'room_name' => html_entity_decode($room_name),
        'max_seats' => $max_seats,
        'cta_map_link' => $cta_map_link,
        'hourly_rate' => $hourly_rate,
        'daily_rate' => $daily_rate,
        'operating_hours_start' => $operating_hours_start,
        'operating_hours_end' => $operating_hours_end,
        'operating_days_starts' => $operating_days_starts,
        'operating_days_ends' => $operating_days_ends,
        'room_location_id' => $room_location->ID,
    );

    return new WP_REST_Response($data, 200);
}

// Hook into WordPress REST API
add_action('rest_api_init', 'register_custom_booking_api');


add_action('rest_api_init', function () {
    register_rest_route('v2', '/sso-login', [
        'methods' => 'POST',
        'callback' => 'sso_login_or_create_user',
        'permission_callback' => '__return_true', // Properly secure this with authorization
    ]);
});

function sso_login_or_create_user($request) {

    $email = sanitize_email($request->get_param("email")); // Sanitize email
    $name = sanitize_text_field($request->get_param("name")); // Sanitize name
    $nameParts = explode(" ", $name);
    // Assuming you want to combine all remaining parts as last name
    $firstName = $nameParts[0]; // Get the first element (first name)

    if (count($nameParts) > 1) {
        $lastName = implode(" ", array_slice($nameParts, 1));
    } else {
        $lastName = ""; // No last name if only one part in $nameParts
    }
    if (!$email || !is_email($email)) {
        return new WP_Error('invalid_data', 'Invalid or missing email', ['status' => 400]);
    }

    if (!$name) {
        return new WP_Error('invalid_data', 'Missing name', ['status' => 400]);
    }

    // Check if user exists
    $user = get_user_by('email', $email);

    if (!$user) {
        // Create a new user if they don't exist
        $user_id = wp_insert_user([
            'user_email' => $email,
            'user_login' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'role' => 'customer', // Set the default role to 'customer'
        ]);

        if (is_wp_error($user_id)) {
            return new WP_Error('user_creation_failed', 'Error creating WordPress user', ['status' => 500]);
        }

        

        $user = get_user_by('ID', $user_id);
        
        // Set 'password_set' meta to false for new users
        update_user_meta($user_id, 'password_set', false);
        update_user_meta($user_id, 'sso_login', true);

        create_notification_post_with_acf(
            "Welcome to Clock In!", 
            "Hi there! Welcome to our community! We're thrilled to have you with us. Do you want to book a meeting room? We got you!", 
            $user->ID, 
            false
        );
        
        // New user created, set initial last login time
        $last_login_time = current_time('mysql');
        update_user_meta($user_id, 'last_login_time', $last_login_time);
    }
    update_user_meta($user->ID, 'sso_login', true);
    $last_login_time = current_time('mysql');
    update_user_meta($user->ID, 'last_login_time', $last_login_time);

    return rest_ensure_response([
        'name' => $user->display_name,
        'email' => $user->user_email,
        'sub' => (string) $user->ID,
        'id' => $user->ID,
        'role' => $user->roles[0], // Default role
        'last_login_time' => $last_login_time,
    ]);
}

// Hook into REST API initialization
add_action('rest_api_init', function() {
    // Register a custom REST API endpoint
    register_rest_route(
        'v2', // Namespace
        '/user/(?P<id>\d+)', // Endpoint pattern with a named parameter (user ID)
        array(
            'methods' => 'GET', // HTTP method
            'callback' => 'get_user_information', // Callback function
            'permission_callback' => '__return_true', // Permission check (for simplicity, allows all access)
        )
    );
});

// Function to get user information
function get_user_information($request) {
    $user_id = (int) $request['id']; // Get user ID from request parameters

    if ($user_id <= 0) {
        return new WP_Error('invalid_user_id', 'Invalid user ID', array('status' => 400));
    }

    $user = get_user_by('id', $user_id);

    if (!$user) {
        return new WP_Error('user_not_found', 'User not found', array('status' => 404));
    }

    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);


    // Retrieve standard WordPress user fields
    $response = array(
        'ID' => $user->ID,
        'username' => $user->user_login,
        'email' => $user->user_email,
        'user_nicename' => $user->user_nicename,
        'user_registered' => $user->user_registered,
        'display_name' => $user->display_name,
        'firstname' => $first_name,
        'lastname' => $last_name,
        'roles' => $user->roles[0],
        'password_set' => get_user_meta($user->ID, 'password_set', true) !== '',
        'sso_login' => get_user_meta($user->ID, 'sso_login', true) !== '',
    );

    // Retrieve ACF fields for the user
    $acf_fields = array(
        'company_name' => get_field('company_name', 'user_' . $user_id), // ACF field
        'tin_number' => get_field('tin_number', 'user_' . $user_id), // ACF field
        'phone_number' => get_field('phone_number', 'user_' . $user_id), // ACF field
    );

    // Merge ACF fields with the main response
    $response = array_merge($response, $acf_fields);

    return $response;
}

// Hook into REST API initialization
add_action('rest_api_init', function() {
    // Register a custom REST API endpoint for updating user information
    register_rest_route(
        'v2', // Namespace
        '/user/(?P<id>\d+)', // Endpoint pattern with a named parameter (user ID)
        array(
            'methods' => 'POST', // HTTP method
            'callback' => 'update_user_information', // Callback function
            'permission_callback' => '__return_true', // Permission check (for simplicity, allows only logged-in users)
        )
    );
});

// Function to update user information
function update_user_information($request) {
    $user_id = (int) $request['id']; // Get user ID from request parameters

    // Get the user object
    $user = get_user_by('id', $user_id);

    if (!$user) {
        return new WP_Error('user_not_found', 'User not found', array('status' => 404));
    }

    // Extract updated user data from the request
    $updated_data = $request->get_params();
    $userdata = array('ID' => $user_id);

    // Update user data
    if (isset($updated_data['firstname'])) {
        $userdata['first_name'] = sanitize_text_field($updated_data['firstname']);
    }
    if (isset($updated_data['lastname'])) {
        $userdata['last_name'] = sanitize_text_field($updated_data['lastname']);
    }
    if (isset($updated_data['email'])) {
        $new_email = sanitize_email($updated_data['email']);
        
        // Check if the new email is already in use
        $user_by_email = get_user_by('email', $new_email);
        if ($user_by_email && $user_by_email->ID !== $user_id) {
            // Email is already used by another user
            return new WP_Error('email_already_exists', __('Email is already in use by another user.'), array('status' => 400));
        }
        
        $userdata['user_email'] = $new_email;
    }
    if (isset($updated_data['fullname'])) {
        $userdata['display_name'] = sanitize_text_field($updated_data['fullname']);
        $userdata['nickname'] = sanitize_text_field($updated_data['fullname']); // Update nickname to match display name
    }

    if (isset($updated_data['center_name'])) {

        $userdata['display_name'] = sanitize_text_field($updated_data['center_name']);
        $userdata['nickname'] = sanitize_text_field($updated_data['center_name']); // Update nickname to match display name
    }


    // Perform the update
    $result = wp_update_user($userdata);


    if (is_wp_error($result)) {
        return new WP_Error('update_failed', $result , array('status' => 500));
    }

    // Update ACF fields (example)
    if (function_exists('update_field')) {
        if (isset($updated_data['company_name'])) {
            update_field('company_name', sanitize_text_field($updated_data['company_name']), 'user_' . $user_id);
        }
        if (isset($updated_data['tin_number'])) {
            update_field('tin_number', sanitize_text_field($updated_data['tin_number']), 'user_' . $user_id);
        }
        if (isset($updated_data['phone_number'])) {
            update_field('phone_number', sanitize_text_field($updated_data['phone_number']), 'user_' . $user_id);
        }
        // Add similar blocks for other ACF fields as needed
    }

    // Return success response
    return array('message' => 'User information updated successfully');
}



// Hook into REST API initialization
add_action('rest_api_init', function() {
    // Register a custom REST API endpoint for updating user password
    register_rest_route(
        'v2', // Namespace
        '/user/change-password/(?P<id>\d+)', // Endpoint pattern with a named parameter (user ID)
        array(
            'methods' => 'POST', // HTTP method
            'callback' => 'change_user_password', // Callback function
            'permission_callback' => '__return_true', // Permission check (for simplicity, allows only logged-in users)
        )
    );

    register_rest_route(
        'v2', // Namespace
        '/user/set-password/(?P<id>\d+)', // Endpoint pattern with a named parameter (user ID)
        array(
            'methods' => 'POST', // HTTP method
            'callback' => 'set_user_password', // Callback function
            'permission_callback' => '__return_true', // Permission check (for simplicity, allows only logged-in users)
        )
    );
});

// Function to change user password
function change_user_password($request) {
    $user_id = (int) $request['id']; // Get user ID from request parameters
    $current_password = $request['current_password'];
    $new_password = $request['new_password'];

    if ($user_id <= 0) {
        return new WP_Error('invalid_user_id', 'Invalid user ID', array('status' => 400));
    }

    // Validate required parameters
    if (empty($current_password) || empty($new_password)) {
        return new WP_Error('missing_parameters', 'Current password or new password is missing', array('status' => 400));
    }

    // Check if the current password matches
    $user = get_user_by('id', $user_id);
    if (!wp_check_password($current_password, $user->user_pass, $user_id)) {
        return new WP_Error('wrong_password', 'Incorrect Password!', array('status' => 401));
    }

    // Check if the new password is different from the current password
    if ($current_password === $new_password) {
        return new WP_Error('same_password', 'New password must be different from the current password', array('status' => 400));
    }

    // Update user password
    wp_set_password($new_password, $user_id);

    // Return success response
    return array('message' => 'Password changed successfully');
}

function set_user_password($request) {
    $user_id = (int) $request['id']; // Get user ID from request parameters
    $new_password = $request['new_password'];

    if ($user_id <= 0) {
        return new WP_Error('invalid_user_id', 'Invalid user ID', array('status' => 400));
    }

    // Update user password
    wp_set_password($new_password, $user_id);
    // Set 'password_set' meta to false for new users
    update_user_meta($user_id, 'password_set', true);

    // Return success response
    return array('message' => 'Password changed successfully');
}

// Add this code to your theme's functions.php or a custom plugin

// Register custom REST API endpoint
function register_custom_api_endpoint() {
    register_rest_route('v2', '/customer-transactions/(?P<user_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_customer_transactions',
    ));
}
add_action('rest_api_init', 'register_custom_api_endpoint');

// Callback function to get customer transactions
function get_customer_transactions($request) {
    $user_id = $request['user_id'];

    
    // Query customer orders based on user ID
    $customer_orders = wc_get_orders(array(
        'customer' => $user_id,
    ));

    return $customer_orders;

    $transactions = array();
    
    foreach ($customer_orders as $order) {
        $order_data = array(
            'order_id' => $order->get_id(),
            'order_total' => $order->get_total(),
            'order_date' => $order->get_date_created()->format('Y-m-d H:i:s'),
            // Add more order data as needed
        );
        $transactions[] = $order_data;
    }

    return $transactions;
}

// Add custom REST API endpoint for fetching products by author
function custom_get_products_by_author_endpoint() {
    register_rest_route( 'v2', '/products/(?P<author_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_products_by_author',
    ) );
}
add_action( 'rest_api_init', 'custom_get_products_by_author_endpoint' );

// Callback function to fetch products by author
function get_products_by_author( $data ) {
    $author_id = $data['author_id'];

    $location_id = get_field('location', 'user_' . $author_id);


    // Query products by author
    // $args = array(
    //     'post_type' => 'product',
    //     'author' => $author_id,
    //     'posts_per_page' => -1, // Retrieve all products by the author
    // );

    $args = array(
        'post_type' => 'product',
        'meta_query' => array(
            array(
                'key' => 'room_description_location', // ACF field name
                'value' => $location_id,
                'compare' => 'LIKE' // Use LIKE to match serialized array format
            )
        ),
        'posts_per_page' => -1, // Retrieve all products by the author
    );



    $products = new WP_Query( $args );

    $formatted_products = array();

    // Format retrieved products data
    if ( $products->have_posts() ) {
        while ( $products->have_posts() ) {
            $products->the_post();
            $product_id = get_the_ID();
            $product_name = get_the_title();
            $product_thumbnail = get_the_post_thumbnail_url($product_id, 'thumbnail');
            
            // Add product data to formatted array
            $formatted_products[] = array(
                'id' => $product_id,
                'room_name' => html_entity_decode($product_name, ENT_QUOTES, 'UTF-8'),
                'thumbnail' => $product_thumbnail,
            );
        }

        // Reset post data
        wp_reset_postdata();

        // Return products data
        return $formatted_products;
    }
    else {
        // No products found, return error response
        return new WP_Error( 'no_products_found', 'No products found for the specified author.', array( 'status' => 404 ) );
    }

    
}


// Add custom REST API endpoint for fetching payments by author
function custom_get_payments_by_author_endpoint() {
    register_rest_route( 'v2', '/payments/(?P<author_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_payments_by_author',
    ) );
}
add_action( 'rest_api_init', 'custom_get_payments_by_author_endpoint' );

function get_payments_by_author( $data ) {
    $author_id = $data['author_id'];
    $location_id = get_field('location', 'user_' . $author_id);
    
    $orders = wc_get_orders( array(
        'limit' => -1, // Retrieve all orders, can be adjusted
    ) );

    if ( empty( $orders ) ) {
        return new WP_REST_Response( array( 'message' => 'No payments found' ), 404 );
    }

    $response = array();

    // Custom statuses to filter
    $custom_statuses = array(
        'ayala_cancelled',
        'ayala_approved',
        'cancel_request',
        'denied_request',
        'approved_request',
    );

    foreach ( $orders as $order ) {
        $order_data = $order->get_data();
        $total_payment_amount = $order->get_total();
        $payment_method = $order->get_payment_method();
        $transaction_id = $order->get_transaction_id();
        $order_status = $order_data['status']; // Get the order status
        $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
        $booking_type = get_field( 'booking_type', $order->get_id() );
        $total_hours = get_field( 'number_of_hours', $order->get_id() );


        // Extract amount from booking type using regex
        preg_match('/₱ ([\d,]+\.\d{2})/', $booking_type, $matches);

        $amount = floatval(str_replace(',', '', $matches[1]));
        $totalAmount = $amount * $total_hours;
        $taxAmountAdd = ($totalAmount * 12) / 100;
        $total = $totalAmount + $taxAmountAdd;


        // Check if the order status is in the custom statuses array
        if ( $order->get_status() !== 'trash' && in_array( $order_status, $custom_statuses ) ) {
            $order_items = $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $product_id = $item->get_product_id();
                // $product_author_id = get_post_field( 'post_author', $product_id );
                // Get the 'initial' custom field value for the author using ACF
                $author_initial = get_field('initial', 'user_' . $product_author_id);
                $product_location_id = get_field('room_description_location', $product_id);


                if ( $product_location_id->ID == $location_id ) {
                    $response[] = array(
                        'transaction_id'       => 'ALO'.padNumber($order_data['id'], 9),
                        'room_id'     => 'ALO'.padNumber($order_data['id'], 9),
                        'payment_amount' => $total,
                        'order_status'   => $order_status,
                        'initial' => $author_initial,
                    );
                    break; // Exit the loop after the first match to avoid duplicating payments for the same order
                }
            }
        }
    }

    if ( empty( $response ) ) {
        return new WP_REST_Response( array( 'message' => 'No payments found for this author' ), 404 );
    }

    return new WP_REST_Response( $response, 200 );
}

// Add custom REST API endpoint for fetching payments by author
function custom_get_payments_by_admin_author_endpoint() {
    register_rest_route( 'v2', '/admin-payments/(?P<author_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_admin_payments_by_author',
    ) );
}
add_action( 'rest_api_init', 'custom_get_payments_by_admin_author_endpoint' );


function get_admin_payments_by_author( $data ) {
    $author_id = $data['author_id'];
    $current_user = get_userdata( $author_id );

    if ( in_array( 'administrator', $current_user->roles ) ) {


        // Admin user - retrieve all payments
        $orders = wc_get_orders( array(
            'limit' => -1, // Retrieve all orders
        ) );
    } else {

        // Non-admin user - filter payments by author
        $location_id = get_field('location', 'user_' . $author_id);

        $orders = wc_get_orders( array(
            'limit' => -1, // Retrieve all orders
        ) );

        if ( empty( $orders ) ) {
            return new WP_REST_Response( array( 'message' => 'No payments found' ), 404 );
        }

        $response = array();

        // Custom statuses to filter
        $custom_statuses = array(
            'ayala_cancelled',
            'ayala_approved',
            'cancel_request',
            'denied_request',
            'approved_request',
        );

        foreach ( $orders as $order ) {
            $order_data = $order->get_data();
            $total_payment_amount = $order->get_total();
            $payment_method = $order->get_payment_method();
            $transaction_id = $order->get_transaction_id();
            $order_status = $order_data['status']; // Get the order status
            $order_date = $order->get_date_created();
            $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
            
            
            $customer_id = $order->get_customer_id(); // Get customer ID
            $customer_information = get_userdata($customer_id);
            if($customer_information) {
                $customer = esc_html($customer_information->first_name) . ' ' . esc_html($customer_information->last_name); // Get customer name
            }else {
                $customer = $order->get_meta('_billing_first_name') . ' ' . $order->get_meta('_billing_last_name'); // Get customer name
            }

            

            $booking_type = get_field( 'booking_type', $order->get_id() );
            $total_hours = get_field( 'number_of_hours', $order->get_id() );


            // Extract amount from booking type using regex
            preg_match('/₱ ([\d,]+\.\d{2})/', $booking_type, $matches);

            $amount = floatval(str_replace(',', '', $matches[1]));
            $totalAmount = $amount * $total_hours;
            $taxAmountAdd = ($totalAmount * 12) / 100;
            $total = $totalAmount + $taxAmountAdd;

            // Format the date if necessary, for example, to 'Y-m-d H:i:s'
            $formatted_order_date = $order_date ? $order_date->date('m-d-Y h:i a') : '';
            // Check if the order status is in the custom statuses array
            if ( $order->get_status() !== 'trash' && in_array( $order_status, $custom_statuses ) ) {
                $order_items = $order->get_items();
                foreach ( $order_items as $item_id => $item ) {
                    $product_id = $item->get_product_id();
                    // Get the 'initial' custom field value for the author using ACF
                    $product_author_id = get_post_field( 'post_author', $product_id );
                    $author_initial = get_field('initial', 'user_' . $product_author_id);
                    $product_location_id = get_field('room_description_location', $product_id);
                    
                    if ( $product_location_id->ID == $location_id ) {
                        $response[] = array(
                            'transaction_id'       => 'ALO'.padNumber($order_data['id'], 9),
                            'payment_date'       => $formatted_order_date,
                            'room_id'     =>  'ALO'.padNumber($order_data['id'], 9),
                            'payment_amount' => $total,
                            'order_status'   => $order_status,
                            "location_name" => $product_location_id->post_title,
                            "customer_name" => $customer,
                            "customer_id" => $customer_id,
                        );
                        break; // Exit the loop after the first match to avoid duplicating payments for the same order
                    }
                }
            }
        }

        if ( empty( $response ) ) {
            return new WP_REST_Response( array( 'message' => 'No payments found for this author' ), 404 );
        }

        return new WP_REST_Response( $response, 200 );
    }
    
    // Admin user - retrieve all payments
    $response = array();

    // Custom statuses to filter
    $custom_statuses = array(
        'ayala_cancelled',
        'ayala_approved',
        'cancel_request',
        'denied_request',
        'approved_request',
    );

    foreach ( $orders as $order ) {
        $order_data = $order->get_data();
        // Get the order date (created date)
       
        $total_payment_amount = $order->get_total();
        $payment_method = $order->get_payment_method();
        $transaction_id = $order->get_transaction_id();
        $order_status = $order_data['status']; // Get the order status
        $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
        
        
        $customer_id = $order->get_customer_id(); // Get customer ID
        $customer_information = get_userdata($customer_id);
        if($customer_information) {
            $customer = esc_html($customer_information->first_name) . ' ' . esc_html($customer_information->last_name); // Get customer name
        }else {
            $customer = $order->get_meta('_billing_first_name') . ' ' . $order->get_meta('_billing_last_name'); // Get customer name
        }

        
        
        $booking_type = get_field( 'booking_type', $order->get_id() );
        $total_hours = get_field( 'number_of_hours', $order->get_id() );


        // Extract amount from booking type using regex
        preg_match('/₱ ([\d,]+\.\d{2})/', $booking_type, $matches);

        $amount = floatval(str_replace(',', '', $matches[1]));
        $totalAmount = $amount * $total_hours;
        $taxAmountAdd = ($totalAmount * 12) / 100;
        $total = $totalAmount + $taxAmountAdd;

        $order_date = $order->get_date_created();
        // Format the date if necessary, for example, to 'Y-m-d H:i:s'
        $formatted_order_date = $order_date ? $order_date->date('m-d-Y h:i a') : '';
        
        // Check if the order status is in the custom statuses array
        if ( $order->get_status() !== 'trash' && in_array( $order_status, $custom_statuses ) ) {
            $order_items = $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $product_id = $item->get_product_id();
                // Get the 'initial' custom field value for the author using ACF
                $product_author_id = get_post_field( 'post_author', $product_id );
                $author_initial = get_field('initial', 'user_' . $product_author_id);
                $product_location_id = get_field('room_description_location', $product_id);

                $response[] = array(
                    'transaction_id'       => 'ALO'.padNumber($order_data['id'], 9),
                    'payment_date'       => $formatted_order_date,
                    'room_id'     =>  'ALO'.padNumber($order_data['id'], 9),
                    'payment_amount' => $total,
                    'order_status'   => $order_status,
                    "location_name" => $product_location_id->post_title,
                    "customer_name" => $customer,
                    "customer_id" => $customer_id,
                );
            }
        }
    }

    if ( empty( $response ) ) {
        return new WP_REST_Response( array( 'message' => 'No payments found' ), 404 );
    }

    return new WP_REST_Response( $response, 200 );
}

// Add custom REST API endpoint for fetching payments by author
function custom_get_orders_by_admin_author_endpoint() {
    register_rest_route( 'v2', '/admin-orders/(?P<author_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_admin_orders_by_author',
    ) );
}
add_action( 'rest_api_init', 'custom_get_orders_by_admin_author_endpoint' );


function get_admin_orders_by_author( $data ) {
    $author_id = $data['author_id'];
    $current_user = get_userdata( $author_id );

    if ( in_array( 'administrator', $current_user->roles ) ) {


        // Admin user - retrieve all payments
        $orders = wc_get_orders( array(
            'limit' => -1, // Retrieve all orders
        ) );
    } else {

        // Non-admin user - filter payments by author
        $location_id = get_field('location', 'user_' . $author_id);

        $orders = wc_get_orders( array(
            'limit' => -1, // Retrieve all orders
        ) );

        if ( empty( $orders ) ) {
            return new WP_REST_Response( array( 'message' => 'No payments found' ), 404 );
        }

        $response = array();

        // Custom statuses to filter
        $custom_statuses = array(
            'ayala_cancelled',
            'ayala_approved',
            'cancel_request',
            'denied_request',
            'approved_request',
        );

        foreach ( $orders as $order ) {
            $order_data = $order->get_data();
            $total_payment_amount = $order->get_total();
            $payment_method = $order->get_payment_method();
            $transaction_id = $order->get_transaction_id();
            $order_status = $order_data['status']; // Get the order status
            $order_date = $order->get_date_created();
            $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field

            $customer_id = $order->get_customer_id(); // Get customer ID
            $customer_information = get_userdata($customer_id);
            if($customer_information) {
                $customer = esc_html($customer_information->first_name) . ' ' . esc_html($customer_information->last_name); // Get customer name
            }else {
                $customer = $order->get_meta('_billing_first_name') . ' ' . $order->get_meta('_billing_last_name'); // Get customer name
            }

            // Format the date if necessary, for example, to 'Y-m-d H:i:s'
            $formatted_order_date = $order_date ? $order_date->date('m-d-Y H:i A') : '';
            // Check if the order status is in the custom statuses array
            if ($order->get_status() !== 'trash' &&  in_array( $order_status, $custom_statuses ) ) {
                $order_data = $order->get_data();
                $total_payment_amount = $order->get_total();
                $checkin = get_field( 'checkin', $order->get_id() ); // Get the checkin custom field
                $checkout = get_field( 'checkout', $order->get_id() ); // Get the checkout custom field
                $booking_notes = get_field( 'booking_notes', $order->get_id() ); // Get the checkout custom field
                $ad_ons = get_field( 'ad_ons', $order->get_id() ); // Get the checkout custom field
                $billing_country = get_field( '_billing_country', $order->get_id() ); // Get the checkout custom field
                $billing_tin_number = get_field( 'billing_tin_number', $order->get_id() ); // Get the checkout custom field
                $number_of_hours = get_field( 'number_of_hours', $order->get_id() ); // Get the checkout custom field
                $number_of_seats = get_field( 'number_of_seats', $order->get_id() ); // Get the checkout custom field
                $vat = get_field( 'vat', $order->get_id() ); // Get the checkout custom field
                $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
                $reason = get_field( 'reason', $order->get_id() ); // Get the checkout custom field
                $cancel_reason = get_field( 'cancel_reason', $order->get_id() ); // Get the checkout custom field
                $author_id_meta = get_field( 'author_id', $order->get_id() ); // Get the checkout custom field
                $booking_type = get_field( 'booking_type', $order->get_id() );
                // Additional order meta
                $billing_first_name = $order->get_meta('_billing_first_name');
                $billing_last_name = $order->get_meta('_billing_last_name');
                $billing_company = $order->get_meta('_billing_company');
                $billing_email = $order->get_meta('_billing_email');
                $billing_phone = $order->get_meta('_billing_phone');
                $product_id = $order->get_meta('product_id');
                $payment_method = get_field( 'payment_method', $order->get_id() );
                $total_hours = get_field( 'number_of_hours', $order->get_id() );
    
                $order_items = $order->get_items();
                foreach ( $order_items as $item_id => $item ) {
                    $product_id = $item->get_product_id();
                    $product_location_id = get_field('room_description_location', $product_id);
                    // return $product_location_id->ID;
                    // $product_author_id = get_post_field( 'post_author', $product_id );
    
    
                    $room_location = get_field('room_description_location', $product_id);
                   
                    if ( $product_location_id->ID== $location_id ) {
                        $product_name = $item->get_name();
                        $product_price = $item->get_subtotal() / $item->get_quantity(); // Single item price
                        $booking_date = new DateTime($order_data['date_created']->date( 'Y-m-d H:i:s' ));
                        $booking_date = $booking_date->format('M-d-Y h:i A');
        
                        $order_checkin_date = new DateTime($checkin);
                        $order_checkin_date = $order_checkin_date->format('M-d-Y h:i A');
        
                        $order_checkout_date = new DateTime($checkout);
                        $order_checkout_date = $order_checkout_date->format('M-d-Y h:i A');
    
                        $response[] = array(
                            'id'            => 'ALO'.padNumber($order_data['id'], 9),
                            'post_id'            => $order_data['id'],
                            'booking_date'    => $booking_date,
                            'room_name'  => $product_name,
                            'price'         => $product_price,
                            'payment_amount'=> $total_payment_amount,
                            'order_status' => $order_status === 'cancel_request' ? 'Cancellation Request': (($order_status === 'approved_request' || $order_status === 'ayala_cancelled')? 'Canceled' : 'Fully Paid'),
                            'booked_slot'    => $order_checkin_date.' - ' .$order_checkout_date,
                            'location'       => $room_location ? $room_location->post_title : null,
                            // Additional order meta
                            'first_name' => $billing_first_name,
                            'booking_type'   => $booking_type,
                            'last_name' => $billing_last_name,
                            'company' => $billing_company,
                            'email' => $billing_email,
                            'phone' => $billing_phone,
                            'country' => $billing_country,
                            'tin_number' => $billing_tin_number,
                            'number_of_hours' => $number_of_hours,
                            'booking_notes' => $booking_notes,
                            'add_ons' => $ad_ons,
                            'number_of_seats' => $number_of_seats,
                            'overall_total' => $overall_total,
                            'author_id' => $order->get_customer_id(),
                            'product_id' => $product_id,
                            'reason' => $reason,
                            'cancel_reason' => $cancel_reason,
                            'payment_method' => $payment_method,
                            'vat' => $vat,
                            'total_hours'    => $total_hours,
                            'customer_name' => $customer,
                            "location_name" => $product_location_id->post_title,
                        );
                    }
                }
            }
        }

        if ( empty( $response ) ) {
            return new WP_REST_Response( array( 'message' => 'No payments found for this author' ), 404 );
        }

        return new WP_REST_Response( $response, 200 );
    }
    
    // Admin user - retrieve all payments
    $response = array();

    // Custom statuses to filter
    $custom_statuses = array(
        'ayala_cancelled',
        'ayala_approved',
        'cancel_request',
        'denied_request',
        'approved_request',
    );

    foreach ( $orders as $order ) {
        $order_data = $order->get_data();
        // Get the order date (created date)
        $order_date = $order->get_date_created();
        // Format the date if necessary, for example, to 'Y-m-d H:i:s'
        $formatted_order_date = $order_date ? $order_date->date('m-d-Y H:i A') : '';
        $total_payment_amount = $order->get_total();
        $payment_method = $order->get_payment_method();
        $transaction_id = $order->get_transaction_id();
        $order_status = $order_data['status']; // Get the order status
        $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
        
        
        $customer_id = $order->get_customer_id(); // Get customer ID
        $customer_information = get_userdata($customer_id);
        if($customer_information) {
            $customer = esc_html($customer_information->first_name) . ' ' . esc_html($customer_information->last_name); // Get customer name
        }else {
            $customer = $order->get_meta('_billing_first_name') . ' ' . $order->get_meta('_billing_last_name'); // Get customer name
        }

        


        if ($order->get_status() !== 'trash' &&  in_array( $order_status, $custom_statuses ) ) {
            $order_data = $order->get_data();
            $total_payment_amount = $order->get_total();
            $checkin = get_field( 'checkin', $order->get_id() ); // Get the checkin custom field
            $checkout = get_field( 'checkout', $order->get_id() ); // Get the checkout custom field
            $booking_notes = get_field( 'booking_notes', $order->get_id() ); // Get the checkout custom field
            $ad_ons = get_field( 'ad_ons', $order->get_id() ); // Get the checkout custom field
            $billing_country = get_field( '_billing_country', $order->get_id() ); // Get the checkout custom field
            $billing_tin_number = get_field( 'billing_tin_number', $order->get_id() ); // Get the checkout custom field
            $number_of_hours = get_field( 'number_of_hours', $order->get_id() ); // Get the checkout custom field
            $number_of_seats = get_field( 'number_of_seats', $order->get_id() ); // Get the checkout custom field
            $vat = get_field( 'vat', $order->get_id() ); // Get the checkout custom field
            $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
            $reason = get_field( 'reason', $order->get_id() ); // Get the checkout custom field
            $cancel_reason = get_field( 'cancel_reason', $order->get_id() ); // Get the checkout custom field
            $author_id_meta = get_field( 'author_id', $order->get_id() ); // Get the checkout custom field
            $booking_type = get_field( 'booking_type', $order->get_id() );
            // Additional order meta
            $billing_first_name = $order->get_meta('_billing_first_name');
            $billing_last_name = $order->get_meta('_billing_last_name');
            $billing_company = $order->get_meta('_billing_company');
            $billing_email = $order->get_meta('_billing_email');
            $billing_phone = $order->get_meta('_billing_phone');
            $product_id = $order->get_meta('product_id');
            $payment_method = get_field( 'payment_method', $order->get_id() );
            $total_hours = get_field( 'number_of_hours', $order->get_id() );

            $order_items = $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $product_id = $item->get_product_id();
                $product_location_id = get_field('room_description_location', $product_id);
                // return $product_location_id->ID;
                // $product_author_id = get_post_field( 'post_author', $product_id );


                $room_location = get_field('room_description_location', $product_id);
               
                    $product_name = $item->get_name();
                    $product_price = $item->get_subtotal() / $item->get_quantity(); // Single item price
                    $booking_date = new DateTime($order_data['date_created']->date( 'Y-m-d H:i:s' ));
                    $booking_date = $booking_date->format('M-d-Y h:i A');
    
                    $order_checkin_date = new DateTime($checkin);
                    $order_checkin_date = $order_checkin_date->format('M-d-Y h:i A');
    
                    $order_checkout_date = new DateTime($checkout);
                    $order_checkout_date = $order_checkout_date->format('M-d-Y h:i A');

                    $response[] = array(
                        'id'            => 'ALO'.padNumber($order_data['id'], 9),
                        'post_id'            => $order_data['id'],
                        'booking_date'    => $booking_date,
                        'room_name'  => $product_name,
                        'price'         => $product_price,
                        'payment_amount'=> $total_payment_amount,
                        'order_status' => $order_status === 'cancel_request' ? 'Cancellation Request': (($order_status === 'approved_request' || $order_status === 'ayala_cancelled')? 'Canceled' : 'Fully Paid'),
                        'booked_slot'    => $order_checkin_date.' - ' .$order_checkout_date,
                        'location'       => $room_location ? $room_location->post_title : null,
                        // Additional order meta
                        'first_name' => $billing_first_name,
                        'booking_type'   => $booking_type,
                        'last_name' => $billing_last_name,
                        'company' => $billing_company,
                        'email' => $billing_email,
                        'phone' => $billing_phone,
                        'country' => $billing_country,
                        'tin_number' => $billing_tin_number,
                        'number_of_hours' => $number_of_hours,
                        'booking_notes' => $booking_notes,
                        'add_ons' => $ad_ons,
                        'number_of_seats' => $number_of_seats,
                        'overall_total' => $overall_total,
                        'author_id' => $order->get_customer_id(),
                        'product_id' => $product_id,
                        'reason' => $reason,
                        'cancel_reason' => $cancel_reason,
                        'payment_method' => $payment_method,
                        'vat' => $vat,
                        'total_hours'    => $total_hours,
                        'customer_name' => $customer,
                        "location_name" => $product_location_id->post_title,
                    );
            }
        }
    }

    if ( empty( $response ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found' ), 404 );
    }

    return new WP_REST_Response( $response, 200 );
}


// Register the custom REST API endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'v2', '/orders/(?P<author_id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'get_custom_woocommerce_orders',
    ) );
} );

function padNumber($number, $length) {
    return str_pad($number, $length, '0', STR_PAD_LEFT);
}

function get_custom_woocommerce_orders( $data ) {
    $author_id = $data['author_id'];

    $orders = wc_get_orders( array(
        'limit' => -1, // Retrieve all orders, can be adjusted
    ) );

    if ( empty( $orders ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found' ), 404 );
    }

    $response = array();

    // Custom statuses to filter
    $custom_statuses = array(
        'ayala_cancelled',
        'ayala_approved',
        'cancel_request',
        'denied_request',
        'approved_request',
    );

    

    foreach ( $orders as $order ) {
        $order_status = $order->get_status(); // Get the order status

        // Check if the order status is in the custom statuses array
        if ($order->get_status() !== 'trash' &&  in_array( $order_status, $custom_statuses ) ) {
            $order_data = $order->get_data();
            $total_payment_amount = $order->get_total();
            $checkin = get_field( 'checkin', $order->get_id() ); // Get the checkin custom field
            $checkout = get_field( 'checkout', $order->get_id() ); // Get the checkout custom field
            $booking_notes = get_field( 'booking_notes', $order->get_id() ); // Get the checkout custom field
            $ad_ons = get_field( 'ad_ons', $order->get_id() ); // Get the checkout custom field
            $billing_country = get_field( '_billing_country', $order->get_id() ); // Get the checkout custom field
            $billing_tin_number = get_field( 'billing_tin_number', $order->get_id() ); // Get the checkout custom field
            $number_of_hours = get_field( 'number_of_hours', $order->get_id() ); // Get the checkout custom field
            $number_of_seats = get_field( 'number_of_seats', $order->get_id() ); // Get the checkout custom field
            $vat = get_field( 'vat', $order->get_id() ); // Get the checkout custom field
            $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
            $reason = get_field( 'reason', $order->get_id() ); // Get the checkout custom field
            $cancel_reason = get_field( 'cancel_reason', $order->get_id() ); // Get the checkout custom field
            $author_id_meta = get_field( 'author_id', $order->get_id() ); // Get the checkout custom field
            $booking_type = get_field( 'booking_type', $order->get_id() );
            // Additional order meta
            $billing_first_name = $order->get_meta('_billing_first_name');
            $billing_last_name = $order->get_meta('_billing_last_name');
            $billing_company = $order->get_meta('_billing_company');
            $billing_email = $order->get_meta('_billing_email');
            $billing_phone = $order->get_meta('_billing_phone');
            $product_id = $order->get_meta('product_id');
            $payment_method = get_field( 'payment_method', $order->get_id() );
            $total_hours = get_field( 'number_of_hours', $order->get_id() );

            $order_items = $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $product_id = $item->get_product_id();
                $product_author_id = get_post_field( 'post_author', $product_id );


                $room_location = get_field('room_description_location', $product_id);
               
                if ( $product_author_id == $author_id ) {
                    $product_name = $item->get_name();
                    $product_price = $item->get_subtotal() / $item->get_quantity(); // Single item price
                    $booking_date = new DateTime($order_data['date_created']->date( 'Y-m-d H:i:s' ));
                    $booking_date = $booking_date->format('M-d-Y h:i A');
    
                    $order_checkin_date = new DateTime($checkin);
                    $order_checkin_date = $order_checkin_date->format('M-d-Y h:i A');
    
                    $order_checkout_date = new DateTime($checkout);
                    $order_checkout_date = $order_checkout_date->format('M-d-Y h:i A');

                    
                    $response[] = array(
                        'id'            => 'ALO'.padNumber($order_data['id'], 9),
                        'booking_date'    => $booking_date,
                        'room_name'  => $product_name,
                        'price'         => $product_price,
                        'payment_amount'=> $total_payment_amount,
                        'order_status' => $order_status === 'cancel_request' ? 'Cancellation Request': (($order_status === 'approved_request' || $order_status === 'ayala_cancelled')? 'Canceled' : 'Fully Paid'),
                        'booked_slot'    => $order_checkin_date.' - ' .$order_checkout_date,
                        'location'       => $room_location ? $room_location->post_title : null,
                        // Additional order meta
                        'first_name' => $billing_first_name,
                        'booking_type'   => $booking_type,
                        'last_name' => $billing_last_name,
                        'company' => $billing_company,
                        'email' => $billing_email,
                        'phone' => $billing_phone,
                        'country' => $billing_country,
                        'tin_number' => $billing_tin_number,
                        'number_of_hours' => $number_of_hours,
                        'booking_notes' => $booking_notes,
                        'add_ons' => $booking_notes,
                        'number_of_seats' => $number_of_seats,
                        'overall_total' => $overall_total,
                        'author_id' => $order->get_customer_id(),
                        'product_id' => $product_id,
                        'reason' => $reason,
                        'cancel_reason' => $cancel_reason,
                        'payment_method' => $payment_method,
                        'vat' => $vat,
                        'total_hours'    => $total_hours,
                    );
                }
            }
        }
    }

    if ( empty( $response ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found for this author' ), 404 );
    }

    return new WP_REST_Response( $response, 200 );
}

// Register the custom REST API endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'v2', '/center-admin-orders/(?P<author_id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'get_center_admin_woocommerce_orders',
    ) );
} );

function get_center_admin_woocommerce_orders( $data ) {
    $author_id = $data['author_id'];
    $location_id = get_field('location', 'user_' . $author_id);
    

    $orders = wc_get_orders( array(
        'limit' => -1, // Retrieve all orders, can be adjusted
    ) );

    if ( empty( $orders ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found' ), 404 );
    }

    $response = array();

    // Custom statuses to filter
    $custom_statuses = array(
        'ayala_cancelled',
        'ayala_approved',
        'cancel_request',
        'denied_request',
        'approved_request',
    );

    

    foreach ( $orders as $order ) {
        $order_status = $order->get_status(); // Get the order status

        // Check if the order status is in the custom statuses array
        if ($order->get_status() !== 'trash' &&  in_array( $order_status, $custom_statuses ) ) {
            $order_data = $order->get_data();
            $total_payment_amount = $order->get_total();
            $checkin = get_field( 'checkin', $order->get_id() ); // Get the checkin custom field
            $checkout = get_field( 'checkout', $order->get_id() ); // Get the checkout custom field
            $booking_notes = get_field( 'booking_notes', $order->get_id() ); // Get the checkout custom field
            $ad_ons = get_field( 'ad_ons', $order->get_id() ); // Get the checkout custom field
            $billing_country = get_field( '_billing_country', $order->get_id() ); // Get the checkout custom field
            $billing_tin_number = get_field( 'billing_tin_number', $order->get_id() ); // Get the checkout custom field
            $number_of_hours = get_field( 'number_of_hours', $order->get_id() ); // Get the checkout custom field
            $number_of_seats = get_field( 'number_of_seats', $order->get_id() ); // Get the checkout custom field
            $vat = get_field( 'vat', $order->get_id() ); // Get the checkout custom field
            $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field
            $reason = get_field( 'reason', $order->get_id() ); // Get the checkout custom field
            $cancel_reason = get_field( 'cancel_reason', $order->get_id() ); // Get the checkout custom field
            $author_id_meta = get_field( 'author_id', $order->get_id() ); // Get the checkout custom field
            $booking_type = get_field( 'booking_type', $order->get_id() );
            // Additional order meta
            $billing_first_name = $order->get_meta('_billing_first_name');
            $billing_last_name = $order->get_meta('_billing_last_name');
            $billing_company = $order->get_meta('_billing_company');
            $billing_email = $order->get_meta('_billing_email');
            $billing_phone = $order->get_meta('_billing_phone');
            $product_id = $order->get_meta('product_id');
            $payment_method = get_field( 'payment_method', $order->get_id() );
            $total_hours = get_field( 'number_of_hours', $order->get_id() );

            $order_items = $order->get_items();
            foreach ( $order_items as $item_id => $item ) {
                $product_id = $item->get_product_id();
                $product_location_id = get_field('room_description_location', $product_id);
                // return $product_location_id->ID;
                // $product_author_id = get_post_field( 'post_author', $product_id );


                $room_location = get_field('room_description_location', $product_id);
               
                if ( $product_location_id->ID== $location_id ) {
                    $product_name = $item->get_name();
                    $product_price = $item->get_subtotal() / $item->get_quantity(); // Single item price
                    $booking_date = new DateTime($order_data['date_created']->date( 'Y-m-d H:i:s' ));
                    $booking_date = $booking_date->format('M-d-Y h:i A');
    
                    $order_checkin_date = new DateTime($checkin);
                    $order_checkin_date = $order_checkin_date->format('M-d-Y h:i A');
    
                    $order_checkout_date = new DateTime($checkout);
                    $order_checkout_date = $order_checkout_date->format('M-d-Y h:i A');

                    $response[] = array(
                        'id'            => 'ALO'.padNumber($order_data['id'], 9),
                        'booking_date'    => $booking_date,
                        'room_name'  => $product_name,
                        'price'         => $product_price,
                        'payment_amount'=> $total_payment_amount,
                        'order_status' => $order_status === 'cancel_request' ? 'Cancellation Request': (($order_status === 'approved_request' || $order_status === 'ayala_cancelled')? 'Canceled' : 'Fully Paid'),
                        'booked_slot'    => $order_checkin_date.' - ' .$order_checkout_date,
                        'location'       => $room_location ? $room_location->post_title : null,
                        // Additional order meta
                        'first_name' => $billing_first_name,
                        'booking_type'   => $booking_type,
                        'last_name' => $billing_last_name,
                        'company' => $billing_company,
                        'email' => $billing_email,
                        'phone' => $billing_phone,
                        'country' => $billing_country,
                        'tin_number' => $billing_tin_number,
                        'number_of_hours' => $number_of_hours,
                        'booking_notes' => $booking_notes,
                        'add_ons' => $ad_ons,
                        'number_of_seats' => $number_of_seats,
                        'overall_total' => $overall_total,
                        'author_id' => $order->get_customer_id(),
                        'product_id' => $product_id,
                        'reason' => $reason,
                        'cancel_reason' => $cancel_reason,
                        'payment_method' => $payment_method,
                        'vat' => $vat,
                        'total_hours'    => $total_hours,
                    );
                }
            }
        }
    }

    if ( empty( $response ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found for this author' ), 404 );
    }

    return new WP_REST_Response( $response, 200 );
}



// Register the REST API endpoint for updating meeting room content details
add_action('rest_api_init', function() {
    register_rest_route('v2', '/update-meeting-room-content/(?P<room_id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'update_meeting_room_content',
        'permission_callback' => '__return_true', // Adjust permissions if needed
    ));
});

// Callback function for the REST API endpoint to update meeting room content details
function update_meeting_room_content($request) {
    $room_id = (int) $request['room_id'];

    // Ensure the room_id corresponds to a valid post of the desired type (e.g., 'room', 'product', etc.)
    if (get_post_status($room_id) !== 'publish') {
        return new WP_Error('invalid_post', 'The specified room ID does not refer to a valid post.', array('status' => 404));
    }

    // Retrieve and sanitize update fields from the request
    $update_fields = $request->get_params();

    // Update meeting room content details
    $updated = array();
    foreach ($update_fields as $field => $value) {
        switch ($field) {
            case 'name':
                $updated['name'] = wp_update_post(array('ID' => $room_id, 'post_title' => sanitize_text_field($value)));
                break;
            case 'description':
                $updated['description'] = wp_update_post(array('ID' => $room_id, 'post_content' => wp_kses_post($value)));
                break;
            case 'image':
                $updated['image'] = update_post_meta($room_id, '_thumbnail_id', attachment_url_to_postid($value));
                break;
            case 'gla':
                $updated['gla'] = update_field('gla', $value, $room_id);
                break;
            case 'number_of_floors':
                // Assuming the key in the payload is 'number_of_floors', not 'no_of_floors'
                $updated['number_of_floors'] = update_field('no_of_floors', $value, $room_id);
                break;
            case 'floor_plate':
                $updated['floor_plate'] = update_field('floor_plate', $value, $room_id);
                break;
            case 'density':
                $updated['density'] = update_field('density', $value, $room_id);
                break;
            case 'video_url':
                $updated['video_url'] = update_field('video_information_link', array('url' => $value), $room_id);
                break;
            case 'hourly_rate':
                $updated['hourly_rate'] = update_field('rates_hourly_rate', $value, $room_id);
                break;
            case 'daily_rate':
                $updated['daily_rate'] = update_field('rates_daily_rate', $value, $room_id);
                break;
                
        }
    }

    return new WP_REST_Response($updated, 200);
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/cancel-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'cancel_order',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            )
        ),
    ));
});

function cancel_order(WP_REST_Request $request) {
    $order_id = $request->get_param('id');
    $reason = $request->get_param('reason');

    $order = wc_get_order($order_id);

    if (!$order) {
        return new WP_Error('invalid_order_id', 'Invalid order ID', array('status' => 404));
    }

    // Check if order status is eligible for cancellation
    $current_status = $order->get_status();

    $allowed_statuses = array('ayala_approved', 'denied_request', 'cancel_request');
    if (!in_array($current_status, $allowed_statuses)) {
        return new WP_Error('order_not_eligible', 'Booking is not eligible for cancellation.', array('status' => 400));
    }


    update_field('cancel_reason', $reason, $order_id);


    $order->save();
    $order->update_status('wc-ayala_cancelled');

    $checkinDateTime = new DateTime(get_field( 'checkin', $order_id ));
    $checkoutDateTime = new DateTime(get_field( 'checkout', $order_id ));
    $user_id = get_field( 'user_id', $order_id );

    // Format the dates to the desired format
    $checkinFormatted = $checkinDateTime->format('F j, Y, g:i A');
    $checkoutFormatted = $checkoutDateTime->format('g:i A');

    create_notification_post_with_acf(
        "Cancelled Room Booking", 
        "A meeting room booking request for [$checkinFormatted - $checkoutFormatted] has been cancelled by Center Admin due to [$reason].", 
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

    $room_location = get_field('room_description_location', $product_id);

    // Define the query arguments
    $args = array(
        'meta_key' => 'location', // The meta key for the ACF field
        'meta_value' => $room_location->ID, // The value you want to match
        'meta_compare' => '=', // Comparison operator
    );

    // Perform the user query
    $user_query = new WP_User_Query($args);

    // Get the results
    $users = $user_query->get_results();

    // Check for results
    if (!empty($users)) {
        // Loop through each user
        foreach ($users as $user) {
            create_notification_post_with_acf(
                "Cancelled Room Booking", 
                "A meeting room cancellation for a booking on [$checkinFormatted - $checkoutFormatted] has been requested by a customer. Requesting for your decision on the cancellation request.", 
                $user->ID, 
                false
            );
        }
    }

    return array('success' => true, 'message' => 'Order cancelled successfully');
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/approve-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'approve_order',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            )
        ),
    ));
});

function approve_order(WP_REST_Request $request) {
    $order_id = $request->get_param('id');
    $reason = $request->get_param('reason');

    $order = wc_get_order($order_id);

    if (!$order) {
        return new WP_Error('invalid_order_id', 'Invalid order ID', array('status' => 404));
    }

    // Check if order status is eligible for cancellation
    $current_status = $order->get_status();

    $allowed_statuses = array('denied_request', 'ayala_approved');
    if (!in_array($current_status, $allowed_statuses)) {
        return new WP_Error('order_not_eligible', 'Booking is not eligible for approve order.', array('status' => 400));
    }

    // Update order status to cancelled
    $order->update_status('wc-ayala_approved');

    update_field('reason', $reason, $order_id);


    $order->save();



    return array('success' => true, 'message' => 'Order cancelled successfully');
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/decline-cancel-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'decline_cancel_request',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            )
        ),
    ));
});

function decline_cancel_request(WP_REST_Request $request) {
    $order_id = $request->get_param('id');
    $reason = $request->get_param('reason');
    $author_id = $request->get_param('author_id');
    $room_name = $request->get_param('room_name');

    if (empty($reason)) {
        return new WP_Error('empty_reason', 'Reason for denied cancellation request is required', array('status' => 400));
    }

    $order = wc_get_order($order_id);


    if (!$order) {
        return new WP_Error('invalid_order_id', 'Invalid order ID', array('status' => 404));
    }

   

    // Check if order status is eligible for cancellation
    $current_status = $order->get_status();

    $allowed_statuses = array('cancel_request');
    if (!in_array($current_status, $allowed_statuses)) {
        return new WP_Error('order_not_eligible', 'Booking is not eligible for decline cancellation.', array('status' => 400));
    }

    update_post_meta($order_id, 'reason_for_denied_cancellation', $reason);

    update_field('cancel_reason', $reason, $order_id);

    $order->save();
    
    // Update order status to cancelled
    $order->update_status('wc-denied_request');


    $checkinDateTime = new DateTime(get_field( 'checkin', $order_id ));
    $checkoutDateTime = new DateTime(get_field( 'checkout', $order_id ));
    $user_id = get_field( 'user_id', $order_id );

    // Format the dates to the desired format
    $checkinFormatted = $checkinDateTime->format('F j, Y, g:i A');
    $checkoutFormatted = $checkoutDateTime->format('g:i A');

    create_notification_post_with_acf(
        "Declined Cancellation Request", 
        "Your cancellation request for the meeting room booking on [$checkinFormatted - $checkoutFormatted] has been declined by the center admin.", 
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

    $room_location = get_field('room_description_location', $product_id);

    // Define the query arguments
    $args = array(
        'meta_key' => 'location', // The meta key for the ACF field
        'meta_value' => $room_location->ID, // The value you want to match
        'meta_compare' => '=', // Comparison operator
    );

    // Perform the user query
    $user_query = new WP_User_Query($args);

    // Get the results
    $users = $user_query->get_results();

    // Check for results
    if (!empty($users)) {
        // Loop through each user
        foreach ($users as $user) {
            create_notification_post_with_acf(
                "Declined Booking Cancellation Request", 
                "A cancellation request for the meeting room booking on [$checkinFormatted - $checkoutFormatted] has been declined by the center admin.", 
                $user->ID, 
                false
            );
        }
    }

    return array('success' => true, 'message' => 'Order cancelled successfully');
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/trash-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'trash_order',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            )
        ),
    ));
});

function trash_order(WP_REST_Request $request) {
    $order_id = $request->get_param('id');
    
    if (empty($order_id) || !is_numeric($order_id)) {
        return new WP_Error('invalid_order_id', 'Invalid order ID', array('status' => 400));
    }

    // Load the order
    $order = wc_get_order($order_id);

    if (!$order) {
        return new WP_Error('order_not_found', 'Order not found', array('status' => 404));
    }

    // Trash the order
    wp_trash_post($order_id);
    $order->delete(true); // Set to true if you want to move it to trash, false to force delete

    return new WP_REST_Response('Order trashed successfully', 200);
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/approved-cancel-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'approved_cancel_request',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            )
        ),
    ));
});

function approved_cancel_request(WP_REST_Request $request) {
    $order_id = $request->get_param('id');

    $order = wc_get_order($order_id);


    if (!$order) {
        return new WP_Error('invalid_order_id', 'Invalid order ID', array('status' => 404));
    }

    // Check if order status is eligible for cancellation
    $current_status = $order->get_status();

    $allowed_statuses = array('cancel_request');
    if (!in_array($current_status, $allowed_statuses)) {
        return new WP_Error('order_not_eligible', 'Booking is not eligible for approve cancellation request.', array('status' => 400));
    }

   

    $order->save();

     // Update order status to cancelled
     $order->update_status('wc-approved_request');
     
    $checkinDateTime = new DateTime(get_field( 'checkin', $order_id ));
    $checkoutDateTime = new DateTime(get_field( 'checkout', $order_id ));
    $user_id = get_field( 'user_id', $order_id );

    // Format the dates to the desired format
    $checkinFormatted = $checkinDateTime->format('F j, Y, g:i A');
    $checkoutFormatted = $checkoutDateTime->format('g:i A');


    create_notification_post_with_acf(
        "Approved Cancellation Request", 
        "Your cancellation request for the meeting room booking on [$checkinFormatted - $checkoutFormatted] has been approved by the center admin.", 
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

    $room_location = get_field('room_description_location', $product_id);

    // Define the query arguments
    $args = array(
        'meta_key' => 'location', // The meta key for the ACF field
        'meta_value' => $room_location->ID, // The value you want to match
        'meta_compare' => '=', // Comparison operator
    );

    // Perform the user query
    $user_query = new WP_User_Query($args);

    // Get the results
    $users = $user_query->get_results();

    // Check for results
    if (!empty($users)) {
        // Loop through each user
        foreach ($users as $user) {
            create_notification_post_with_acf(
                "Approved Booking Cancellation Request ", 
                "A cancellation request for the meeting room booking on [$checkinFormatted - $checkoutFormatted] has been approved by the center admin.", 
                $user->ID, 
                false
            );
        }
    }

    return array('success' => true, 'message' => 'Order cancelled successfully');
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/cancellation-request-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'cancellation_request_order',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
            'reason' => array(
                'required' => true,
                'validate_callback' => function($param, $request, $key) {
                    return !empty($param);
                },
                'sanitize_callback' => 'sanitize_text_field'
            ),
        ),
    ));
});

function cancellation_request_order(WP_REST_Request $request) {
    $order_id = $request->get_param('id');
    $reason = $request->get_param('reason');

    if (empty($reason)) {
        return new WP_Error('empty_reason', 'Reason for cancellation is required', array('status' => 400));
    }

    $order = wc_get_order($order_id);

    // Check if order status is eligible for cancellation
    $current_status = $order->get_status();
    $allowed_statuses = array('ayala_approved');
    if (!in_array($current_status, $allowed_statuses)) {
        return new WP_Error('order_not_eligible', 'Booking is not eligible for cancellatio request.', array('status' => 400));
    }

  

    // Add reason for cancellation as order meta
    // update_post_meta($order_id, 'reason', $reason);
    update_field('reason', $reason, $order_id);
    $order->save();

    // Update order status to cancelled
    $order->update_status('wc-cancel_request');

    $checkinDateTime = new DateTime(get_field( 'checkin', $order_id ));
    $checkoutDateTime = new DateTime(get_field( 'checkout', $order_id ));
    $user_id = get_field( 'user_id', $order_id );

    // Format the dates to the desired format
    $checkinFormatted = $checkinDateTime->format('F j, Y, g:i A');
    $checkoutFormatted = $checkoutDateTime->format('g:i A');

    create_notification_post_with_acf(
        "Cancellation Request", 
        "Your cancellation request for the meeting room booking on [$checkinFormatted - $checkoutFormatted] has been forwarded to the center admin for approval. Kindly await the confirmation in a few days.", 
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

    $room_location = get_field('room_description_location', $product_id);

    // Define the query arguments
    $args = array(
        'meta_key' => 'location', // The meta key for the ACF field
        'meta_value' => $room_location->ID, // The value you want to match
        'meta_compare' => '=', // Comparison operator
    );

    // Perform the user query
    $user_query = new WP_User_Query($args);

    // Get the results
    $users = $user_query->get_results();

    // Check for results
    if (!empty($users)) {
        // Loop through each user
        foreach ($users as $user) {
            create_notification_post_with_acf(
                "Booking Cancellation Request", 
                "A meeting room cancellation for a booking on [$checkinFormatted - $checkoutFormatted] has been requested by a customer. Requesting for your decision on the cancellation request.", 
                $user->ID, 
                false
            );
        }
    }

    return rest_ensure_response(array(
        'success' => true,
        'order_id' => $order_id,
        'post_id' => $new_post_id,
        'redirect_url' => home_url('/thank-you')
    ));

    return array('success' => true, 'message' => 'Order cancellation request successfully');
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/paid-order/(?P<id>\d+)', array(
        'methods' => 'POST',
        'callback' => 'paid_order',
        'args' => array(
            'id' => array(
                'validate_callback' => function($param, $request, $key) {
                    return is_numeric($param);
                }
            ),
            'reason' => array(
                'required' => true,
                'validate_callback' => function($param, $request, $key) {
                    return !empty($param);
                },
                'sanitize_callback' => 'sanitize_text_field'
            ),
        ),
    ));
});

function paid_order(WP_REST_Request $request) {
    $order_id = $request->get_param('id');
    $reason = $request->get_param('reason');

    if (empty($reason)) {
        return new WP_Error('empty_reason', 'Reason for cancellation is required', array('status' => 400));
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        return new WP_Error('invalid_order_id', 'Invalid order ID', array('status' => 404));
    }

    // Check if order status is eligible for cancellation
    $current_status = $order->get_status();
    $allowed_statuses = array('ayala_cancelled');
    if (!in_array($current_status, $allowed_statuses)) {
        return new WP_Error('order_not_eligible', 'Booking is not eligible for cancellation.', array('status' => 400));
    }

    // Update order status to cancelled
    $order->update_status('wc-ayala_paid');

    // Add reason for cancellation as order meta
    update_post_meta($order_id, 'reason', $reason);
    $order->save();

    return array('success' => true, 'message' => 'Order cancelled successfully');
}


// Add custom API endpoint for removing item from product gallery
function remove_gallery_item_endpoint() {
    register_rest_route( 'v2', '/remove-gallery-item/', array(
        'methods' => 'POST',
        'callback' => 'remove_gallery_item_callback',
    ));
}
add_action( 'rest_api_init', 'remove_gallery_item_endpoint' );

// Callback function to handle removing item from product gallery
function remove_gallery_item_callback( $request ) {
    $product_id = $request->get_param( 'product_id' );
    $image_id = $request->get_param( 'image_id' );

    // Check if product ID and image ID are provided
    if ( empty( $product_id ) || empty( $image_id ) ) {
        return new WP_Error( 'invalid_params', 'Product ID and image ID are required.', array( 'status' => 400 ) );
    }

    // Check if product exists
    if ( ! wc_get_product( $product_id ) ) {
        return new WP_Error( 'invalid_product', 'Invalid product ID.', array( 'status' => 404 ) );
    }

    // Remove image from product gallery
    $attachment_ids = get_post_meta( $product_id, '_product_image_gallery', true );
    if ( $attachment_ids ) {
        $attachment_ids = explode( ',', $attachment_ids );
        $attachment_ids = array_diff( $attachment_ids, array( $image_id ) );
        update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );
    }

    return array( 'success' => true );
}


// Add custom API endpoint for adding item to product gallery
function add_gallery_item_endpoint() {
    register_rest_route( 'v2', '/add-gallery-item/', array(
        'methods' => 'POST',
        'callback' => 'add_gallery_item_callback',
    ));
}
add_action( 'rest_api_init', 'add_gallery_item_endpoint' );

// Callback function to handle adding item to product gallery
function add_gallery_item_callback( $request ) {
    $product_id = $request->get_param( 'product_id' );
    $image_id = $request->get_param( 'image_id' );

    // Check if product ID and image ID are provided
    if ( empty( $product_id ) || empty( $image_id ) ) {
        return new WP_Error( 'invalid_params', 'Product ID and image ID are required.', array( 'status' => 400 ) );
    }

    // Check if product exists
    if ( ! wc_get_product( $product_id ) ) {
        return new WP_Error( 'invalid_product', 'Invalid product ID.', array( 'status' => 404 ) );
    }

    // Add image to product gallery
    $attachment_ids = get_post_meta( $product_id, '_product_image_gallery', true );
    $attachment_ids = explode( ',', $attachment_ids );
    $attachment_ids[] = $image_id;
    update_post_meta( $product_id, '_product_image_gallery', implode( ',', $attachment_ids ) );

    return array( 'success' => true );
}


// Register the custom REST API endpoint
add_action( 'rest_api_init', function () {
    register_rest_route( 'v2', '/customer-orders/(?P<customer_id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'get_customer_orders',
    ) );
} );

function get_customer_orders( $data ) {
    $customer_id = $data['customer_id'];

    $orders = wc_get_orders( array(
        'limit' => -1, // Retrieve all orders
        'customer' => $customer_id,
    ) );

    if ( empty( $orders ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found for this customer' ), 404 );
    }

    $response = array();
    // Custom statuses to filter
    $custom_statuses = array(
        'ayala_cancelled',
        'ayala_approved',
        'cancel_request',
        'denied_request',
        'approved_request',
    );

    foreach ($orders as $order) {
        $order_status = $order->get_status();


        if ( $order_status !== 'trash' && in_array( $order_status, $custom_statuses ) ) {
            $order_data = $order->get_data();
            $total_payment_amount = $order->get_total();
            $checkin = get_field( 'checkin', $order->get_id() ); // Get the checkin custom field
            $checkout = get_field( 'checkout', $order->get_id() ); // Get the checkout custom field
            $booking_type = get_field( 'booking_type', $order->get_id() ); // Get the checkout custom field
            $billing_tin_number = get_field( 'billing_tin_number', $order->get_id() ); // Get the checkout custom field
            $total_hours = get_field( 'number_of_hours', $order->get_id() ); // Get the checkout custom field
            $payment_method = get_field( 'payment_method', $order->get_id() ); // Get the checkout custom field
            $vat = get_field( 'vat', $order->get_id() ); // Get the checkout custom field
            $overall_total = get_field( 'overall_total', $order->get_id() ); // Get the checkout custom field

            $booking_notes = get_field( 'booking_notes', $order->get_id() ); // Get the checkout custom field
            $ad_ons = get_field( 'ad_ons', $order->get_id() ); // Get the checkout custom field
            $billing_country = get_field( '_billing_country', $order->get_id() ); // Get the checkout custom field
            $billing_tin_number = get_field( 'billing_tin_number', $order->get_id() ); // Get the checkout custom field
            $number_of_hours = get_field( 'number_of_hours', $order->get_id() ); // Get the checkout custom field
            $number_of_seats = get_field( 'number_of_seats', $order->get_id() ); // Get the checkout custom field
            $reason = get_field( 'reason', $order->get_id() ); // Get the checkout custom field

            // Additional order meta
            $billing_first_name = $order->get_meta('_billing_first_name');
            $billing_last_name = $order->get_meta('_billing_last_name');
            $billing_company = $order->get_meta('_billing_company');
            $billing_email = $order->get_meta('_billing_email');
            $billing_phone = $order->get_meta('_billing_phone');
            $billing_country = $order->get_meta('_billing_country');
            $user_id = $order->get_meta('user_id');
            $post_id = $order->get_meta('post_id');
            $product_id = $order->get_meta('product_id');
            $cancel_reason = get_field( 'cancel_reason', $order->get_id() );
            $order_items = $order->get_items();
            
            foreach ( $order_items as $item ) {
                $product_id = $item->get_product_id();
                $product_author_id = get_post_field( 'post_author', $product_id );
                $room_location = get_field('room_description_location', $product_id);
               
                $product_name = $item->get_name();
                $product_price = $item->get_subtotal() / $item->get_quantity(); // Single item price
                $booking_date = new DateTime($order_data['date_created']->date( 'Y-m-d H:i:s' ));
                $booking_date = $booking_date->format('M-d-Y h:i A');

                $order_checkin_date = new DateTime($checkin);
                $order_checkin_date = $order_checkin_date->format('M-d-Y h:i A');

                $order_checkout_date = new DateTime($checkout);
                $order_checkout_date = $order_checkout_date->format('M-d-Y h:i A');

                $response[] = array(
                    'id'            => 'ALO'.padNumber($order_data['id'], 9),
                    'booking_date'   => $booking_date,
                    'room_name'      => $product_name,
                    'price'          => $product_price,
                    'payment_amount' => $total_payment_amount,
                    'order_status' => $order_status === 'cancel_request' ? 'Cancellation Request': (($order_status === 'approved_request' || $order_status === 'ayala_cancelled')? 'Canceled' : 'Fully Paid'),
                    'booked_slot'    => $order_checkin_date.' - ' .$order_checkout_date,
                    // Additional order meta
                    'first_name'     => $billing_first_name,
                    'last_name'      => $billing_last_name,
                    'company'        => $billing_company,
                    'email'          => $billing_email,
                    'phone'          => $billing_phone,
                    'country'        => $billing_country,
                    'location'       => $room_location ? $room_location->post_title : null,
                    'tin_number'     => $billing_tin_number,
                    'booking_type'   => $booking_type,
                    'total_hours'    => $total_hours,
                    'ad_ons'         => $ad_ons,
                    'payment_method' => $payment_method,
                    'vat'            => $vat,
                    'overall_total'  => $overall_total,
                    'user_id'        => $user_id,
                    'post_id'        => $post_id,
                    'product_id'     => $product_id,
                    'reason'         => $reason,
                    'booking_notes'  => $booking_notes,
                    'cancel_reason'  => $cancel_reason
                );
            }
        }
    }

    if ( empty( $response ) ) {
        return new WP_REST_Response( array( 'message' => 'No orders found for this customer' ), 404 );
    }

    return rest_ensure_response($response);
}


function register_custom_order_endpoint() {
    register_rest_route('v2', '/create-order', array(
        'methods' => 'POST',
        'callback' => 'handle_custom_order_creation',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('v2', '/create-orders', array(
        'methods' => 'POST',
        'callback' => 'handle_custom_orders_creation',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'register_custom_order_endpoint');

function handle_custom_order_creation(WP_REST_Request $request) {
    global $woocommerce;

    $user_id = intval($request->get_param('user_id'));
    $user_info = get_userdata($user_id);

    // Get values from parameters
    $billing_first_name =$user_info->first_name;
    $billing_last_name = $user_info->last_name;
    $billing_company = get_field('company_name', 'user_' . $user_id) ?: "";
    $country = sanitize_text_field($request->get_param('country_region'));
    $billing_email = $user_info->user_email;
    $billing_phone = get_field('phone_number', 'user_' . $user_id);
    $billing_tin_number = get_field('tin_number', 'user_' . $user_id) ?: "";
    $vat = sanitize_text_field($request->get_param('VAT'));
    $payment_method = sanitize_text_field($request->get_param('payment_method'));
    $booking_notes = $request->get_param('booking_notes');
    $ad_ons = $request->get_param('ad_ons');

    $total_hours = sanitize_text_field($request->get_param('Time (no. of hrs)'));
    $booking_type = sanitize_text_field($request->get_param('Booking Type'));

    $product_id = intval($request->get_param('product_id'));
    // $number_of_hours = intval($request->get_param('Time (no. of hrs)'));
    $overall_total = floatval(str_replace(['₱', ','], '', $request->get_param('Overall Total')));

    
    // Parse booking date
    $booking_date = sanitize_text_field($request->get_param('Booking Date'));
    $checkin_date = date_create_from_format('d-m-y H:i A', explode(" - ", $booking_date)[0]) ;
    $checkout_date = date_create_from_format('d-m-y H:i A', explode(" - ", $booking_date)[1]) ;


    // return new WP_Error('no_notifications', $checkin_checkout, array('status' => 404));
    $checkin = $checkin_date->format('Y-m-d H:i:s');


    $checkinFormatted = $checkin_date->format('F j, Y, g:i A');
    $checkoutFormatted = $checkout_date->format('g:i A');

    $checkout = $checkout_date->format('Y-m-d H:i:s');

    $validate_booking_times = validate_booking_times($checkin, $checkout, $product_id);
    if($validate_booking_times["is_booked"]) {
        return new WP_Error('booking_conflict', 'The '.$validate_booking_times["product_name"].' is already booked for the selected date and time.');
    }

    $address = array(
        'first_name' => $billing_first_name,
        'last_name'  => $billing_last_name,
        'company'    => $billing_company,
        'email'      => $billing_email,
        'phone'      => $billing_phone,
        'country'    => $country
    );

    // Now we create the order
    $order = wc_create_order(array('customer_id' => $user_id));

    // The add_product() function below is located in /plugins/woocommerce/includes/abstracts/abstract_wc_order.php
    $order->set_address($address, 'billing');

    // Get the product
    $product = wc_get_product($product_id);

    // Set the custom cost
    $product->set_price($overall_total);

    // Add the product to the order
    $order->add_product($product, 1);

    // Calculate totals and update order status
    $order->calculate_totals();

   

    // Add post meta to the order
    $order_id = $order->get_id();
    update_post_meta($order_id, 'billing_tin_number', $billing_tin_number);
    update_post_meta($order_id, 'checkin', $checkin);
    update_post_meta($order_id, 'checkout', $checkout);
    update_post_meta($order_id, 'number_of_hours', $number_of_hours);
    update_post_meta($order_id, 'booking_notes', $booking_notes);
    update_field('booking_notes', $booking_notes, $new_post_id);
    update_field('ad_ons', $ad_ons, $new_post_id);
    update_post_meta($order_id, 'ad_ons', $ad_ons);
    update_post_meta($order_id, 'booking_type', $booking_type);
    update_post_meta($order_id, 'number_of_hours', $total_hours);
    update_post_meta($order_id, 'payment_method', $payment_method);
    update_post_meta($order_id, 'vat', $vat);
    update_post_meta($order_id, 'overall_total', $overall_total);
    update_post_meta($order_id, 'user_id', $user_id);
    $author_id = get_post_field('post_author', $product_id);
    // Create a new post
    $new_post = array(
        'post_title'    => '#' . $order_id,
        'post_content'  => 'This is the content of the sample post.',
        'post_status'   => 'publish',
        'post_author'   => $author_id,
        'post_type'     => 'booking',
    );

    // Insert the post into the database
    $new_post_id = wp_insert_post($new_post);
    update_field('order_id', $order_id, $new_post_id);
    update_field('product_id', $product_id, $new_post_id);
    update_field('checkin', strtotime($checkin), $new_post_id);
    update_field('checkout', strtotime($checkout), $new_post_id);
    update_field('number_of_hours', $number_of_hours, $new_post_id);
    update_field('booking_type', $booking_type, $new_post_id);
    update_field('number_of_hours', $total_hours, $new_post_id);
    update_field('payment_method', $payment_method, $new_post_id);
    update_field('vat', $vat, $new_post_id);
    update_field('overall_total', $overall_total, $new_post_id);
    $order_status_slug = $order->get_status();
    // Get the human-readable order status name
    $order_status_name = wc_get_order_status_name($order_status_slug);
    update_field('order_status', $order_status_name, $new_post_id);
    update_field('quantity', 1, $new_post_id);
    update_field('booking_room', sanitize_text_field($request->get_param('Meeting Room Name')), $new_post_id);
    update_field('customer', $billing_first_name . ' ' . $billing_last_name, $new_post_id);
    update_post_meta($order_id, 'post_id', $new_post_id);


    create_notification_post_with_acf(
        "Booking Confirmed", 
        "Your meeting room booking request for [$checkinFormatted - $checkoutFormatted] has been confirmed.", 
        $user_id, 
        false
    );

    $product_id = get_field('product_id', $new_post_id);
    $product = wc_get_product( $product_id );
    $product_name = $product->get_name();

    $room_location = get_field('room_description_location', $product_id);

    // Define the query arguments
    $args = array(
        'meta_key' => 'location', // The meta key for the ACF field
        'meta_value' => $room_location->ID, // The value you want to match
        'meta_compare' => '=', // Comparison operator
    );

    // Perform the user query
    $user_query = new WP_User_Query($args);

    // Get the results
    $users = $user_query->get_results();

    // Check for results
    if (!empty($users)) {
        // Loop through each user
        foreach ($users as $user) {
            create_notification_post_with_acf(
                "New Confirmed Room Booking", 
                "A new meeting room booking request for [$checkinFormatted - $checkoutFormatted] on [$product_name] has been booked.", 
                $user->ID, 
                false
            );
        }
    }

     // Set the order status to "completed" to indicate it's paid
     $order->update_status('ayala_approved', 'Order paid via API', TRUE);

    return rest_ensure_response(array(
        'success' => true,
        'order_id' => $order_id,
        'post_id' => $new_post_id,
        'redirect_url' => home_url('/thank-you')
    ));
}

function handle_custom_orders_creation(WP_REST_Request $request) {
    global $woocommerce;

    // Get and validate common parameters
    $user_id = intval($request->get_param('user_id'));
    // Initialize first_name and last_name variables
    $billing_first_name = '';
    $billing_last_name = '';
    $billing_full_name = sanitize_text_field($request->get_param('fullname'));

    // Check if fullname is empty or not
    if ( empty( $billing_full_name ) ) {
        $billing_first_name = sanitize_text_field($request->get_param('firstname'));
        $billing_last_name = sanitize_text_field($request->get_param('lastname'));
    } else {
        // If fullname is not empty, set first_name to fullname and leave last_name empty
        $billing_first_name = $billing_full_name;
    }

    $billing_company = sanitize_text_field($request->get_param('company_name'));
    $country = sanitize_text_field($request->get_param('country_region'));
    $billing_email = sanitize_email($request->get_param('email_address'));
    $billing_phone = sanitize_text_field($request->get_param('contact_number'));
    $billing_tin_number = sanitize_text_field($request->get_param('tin'));
    $vat = sanitize_text_field($request->get_param('VAT'));
    $payment_method = sanitize_text_field($request->get_param('payment_method'));
    $overall_total = floatval(str_replace(['₱', ','], '', $request->get_param('Overall Total')));

    $booking_notes = $request->get_param('booking_notes');
    $ad_ons = $request->get_param('ad_ons');

    $orders_data = $request->get_param('orders');
    if (!is_array($orders_data) || empty($orders_data)) {
        return rest_ensure_response(array(
            'success' => false,
            'message' => 'Invalid or empty orders data provided.',
        ));
    }

    $validate_booking_times = validate_booking_times_for_multiple_rooms($orders_data);

    if($validate_booking_times["is_booked"]) {
        return new WP_Error('booking_conflict', 'The '.$validate_booking_times["product_name"].' is already booked for the selected date and time.');
    }

    $address = array(
        'first_name' => $billing_first_name,
        'last_name'  => $billing_last_name,
        'company'    => $billing_company,
        'email'      => $billing_email,
        'phone'      => $billing_phone,
        'country'    => $country
    );

    $results = [];
    foreach ($orders_data as $order_data) {
        // Sanitize and validate individual order data
        $product_id = intval($order_data['product_id']);
        $booking_type = sanitize_text_field($order_data['Booking Type']);
        $time_no_of_hrs = sanitize_text_field($order_data['Time (no. of hrs)']);
        $booking_date = sanitize_text_field($order_data['Booking Date']);

        // Parse checkin and checkout dates
        $format = 'd-m-y H:i A';
        $dates = explode(' - ', $booking_date);
        $checkin = DateTime::createFromFormat($format, trim($dates[0]));
        $checkout = DateTime::createFromFormat($format, trim($dates[1]));
        if ($checkin === false || $checkout === false) {
            $results[] = array(
                'success' => false,
                'message' => 'Failed to parse booking dates for product ID: ' . $product_id,
            );
            continue;
        }

        // Calculate total hours
        $time_parts = explode(' ', $time_no_of_hrs);
        $hours = intval($time_parts[0]);
        $minutes = isset($time_parts[2]) ? intval($time_parts[2]) : 0;
        $total_hours = $hours + ($minutes / 60);

        // Extract hourly rate from booking type
        preg_match('/₱ ([\d,.]+) \/ hr/', $booking_type, $matches);
        $hourly_rate = isset($matches[1]) ? floatval(str_replace(',', '', $matches[1])) : 0;

        // Calculate overall total based on hourly rate and total hours
        // $calculated_total = $hourly_rate * $total_hours;

        // Create the order
        $order = wc_create_order(array('customer_id' => $user_id));
        if (is_wp_error($order)) {
            $results[] = array(
                'success' => false,
                'message' => $order->get_error_message(),
            );
            continue;
        }

        $address = array(
            'first_name' => $billing_first_name,
            'last_name'  => $billing_last_name,
            'company'    => $billing_company,
            'email'      => $billing_email,
            'phone'      => $billing_phone,
            'country'    => $country
        );
    
        // Set the order address
        $order->set_address($address, 'billing');

        // Get the product
        $product = wc_get_product($product_id);
        if (!$product) {
            $results[] = array(
                'success' => false,
                'message' => 'Invalid product ID: ' . $product_id,
            );
            continue;
        }

        // Set custom cost and add the product to the order
        $product->set_price($calculated_total);
        $order->add_product($product, 1);

        // Calculate totals and update order status
        $order->calculate_totals();
        

        // Add post meta to the order
        $order_id = $order->get_id();
        update_post_meta($order_id, 'billing_tin_number', $billing_tin_number);
        update_post_meta($order_id, 'checkin', $checkin->format('Y-m-d H:i:s'));
        update_post_meta($order_id, 'checkout', $checkout->format('Y-m-d H:i:s'));
        update_post_meta($order_id, 'number_of_hours', $total_hours);
        update_post_meta($order_id, 'booking_type', $booking_type);
        update_post_meta($order_id, 'payment_method', $payment_method);
        update_post_meta($order_id, 'vat', $vat);
        update_post_meta($order_id, 'overall_total', $overall_total);
        update_post_meta($order_id, 'user_id', $user_id);

        // Create booking post
        $author_id = get_post_field('post_author', $product_id);
        $new_post = array(
            'post_title'    => '#' . $order_id,
            'post_content'  => 'This is the content of the sample post.',
            'post_status'   => 'publish',
            'post_author'   => $author_id,
            'post_type'     => 'booking',
        );
        $new_post_id = wp_insert_post($new_post);
        if (is_wp_error($new_post_id)) {
            $results[] = array(
                'success' => false,
                'message' => $new_post_id->get_error_message(),
            );
            continue;
        }

        update_post_meta($order_id, 'booking_notes', $booking_notes);
        update_field('booking_notes', $booking_notes, $new_post_id);
        update_field('ad_ons', $ad_ons, $new_post_id);
        update_post_meta($order_id, 'ad_ons', $ad_ons);
        // Update custom fields for the booking post
        update_field('order_id', $order_id, $new_post_id);
        update_field('product_id', $product_id, $new_post_id);
        update_field('checkin', $checkin->getTimestamp(), $new_post_id);
        update_field('checkout', $checkout->getTimestamp(), $new_post_id);
        update_field('number_of_hours', $total_hours, $new_post_id);
        update_field('booking_type', $booking_type, $new_post_id);
        update_field('payment_method', $payment_method, $new_post_id);
        update_field('vat', $vat, $new_post_id);
        update_field('overall_total', $overall_total, $new_post_id);
        $order_status_slug = $order->get_status();
        $order_status_name = wc_get_order_status_name($order_status_slug);
        update_field('order_status', $order_status_name, $new_post_id);
        update_field('quantity', 1, $new_post_id);
        update_field('booking_room', $product->get_name(), $new_post_id);
        update_field('customer', $billing_first_name . ' ' . $billing_last_name, $new_post_id);
        update_post_meta($order_id, 'post_id', $new_post_id);

        // Format the dates to the desired format
        $checkinFormatted = $checkin->format('F j, Y, g:i A');
        $checkoutFormatted = $checkout->format('g:i A');

        create_notification_post_with_acf(
            "Booking Confirmed", 
            "Your meeting room booking request for [$checkinFormatted - $checkoutFormatted] has been confirmed.", 
            $user_id, 
            false
        );
    
        $product_id = get_field('product_id', $new_post_id);
        $product = wc_get_product( $product_id );
        $product_name = $product->get_name();
    
        $room_location = get_field('room_description_location', $product_id);

        // Define the query arguments
        $args = array(
            'meta_key' => 'location', // The meta key for the ACF field
            'meta_value' => $room_location->ID, // The value you want to match
            'meta_compare' => '=', // Comparison operator
        );

        // Perform the user query
        $user_query = new WP_User_Query($args);

        // Get the results
        $users = $user_query->get_results();

        // Check for results
        if (!empty($users)) {
            // Loop through each user
            foreach ($users as $user) {
                create_notification_post_with_acf(
                    "New Confirmed Room Booking", 
                    "A new meeting room booking request for [$checkinFormatted - $checkoutFormatted] on [$product_name] has been booked.", 
                    $user->ID, 
                    false
                );
            }
        }

        
        $order->update_status('ayala_approved', 'Order paid via API', TRUE);
        $results[] = array(
            'success' => true,
            'order_id' => $order_id,
            'post_id' => $new_post_id,
        );
    }

    return rest_ensure_response(array(
        'success' => true,
        'results' => $results,
        'redirect_url' => home_url('/thank-you')
    ));
}

// Register a custom REST API endpoint
function custom_api_complete_order_route() {
    register_rest_route('v2', '/completed-orders/(?P<product_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_completed_orders_v2',
    ));
}
add_action('rest_api_init', 'custom_api_complete_order_route');

// Callback function to handle the API request
function get_completed_orders_v2($data) {
    global $wpdb;
    

    $product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
    $query = new WC_Order_Query( array(
        'limit'      => -1,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'post_status' => array('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request'), // Add more statuses as needed
        'return'     => 'ids',
    ) );
    $orders  = $query->get_orders();

   

    $completed_dates = array();

    foreach ( $orders as $order_id ) {
        $order  = wc_get_order( $order_id );
        $checkin = get_post_meta($order_id, 'checkin', true);
        $checkout = get_post_meta($order_id, 'checkout', true);
        $items = $order->get_items();
        $hours_start = get_field('operating_hours_start', $product_id);
        $hours_end = get_field('operating_hours_end', $product_id);
        $trash = $order->get_status();

        $checkin = new DateTime($checkin);
        $checkout = new DateTime($checkout);

        // Calculate the total hours between checkin and checkout
        $interval = $checkin->diff($checkout);
        $totalHours = $interval->h + $interval->i / 60;


        $hours_start = new DateTime($hours_start);
        $hours_end = new DateTime($hours_end);
        // Calculate the total hours between hours_start and hours_end
        $start = DateTime::createFromFormat('h:i A', $hours_start->format('h:i A'));
        $end = DateTime::createFromFormat('h:i A', $hours_end->format('h:i A'));
        $intervalHours = $start->diff($end);
        $totalIntervalHours = $intervalHours->h + $intervalHours->i / 60;
        $product_ids = [];


         // Extract date parts from checkin and checkout
        $checkinDate = $checkin->format('Y-m-d');
        $checkoutDate = $checkout->format('Y-m-d');

        // Create DateTime objects for the date parts only
        $checkinStart = DateTime::createFromFormat('Y-m-d', $checkinDate);
        $checkoutStart = DateTime::createFromFormat('Y-m-d', $checkoutDate);
        

        foreach ( $items as $item ) {
            // Get the product ID of the item
            $product_ids[] = $item->get_product_id();
        }



        $current_date[] = $totalHours === $totalIntervalHours;
        if(in_array($product_id, $product_ids)) {

        if($totalHours === $totalIntervalHours) {
            // Clone checkin date to iterate
            $currentDate = clone $checkin;

            // Loop through each day from checkin to checkout
            while ($currentDate <= $checkout) {
                 $completed_dates[] = array(
                    "date" => $currentDate->format('Y-m-d'),
                    "product_id" => $product_id,
                    "totalHours" => $totalHours,
                    "totalIntervalHours" => $totalIntervalHours,
                    "isEqual" => $totalHours === $totalIntervalHours,
                    "trash" => $trash,
                    "order_id" => $order_id,
                );
                $currentDate->modify('+1 day'); // Move to next day
            }
        }
        else {
            $currentDate = $checkinStart;

            // Loop through each day from checkin to checkout
            while ($currentDate <= $checkoutStart) {
                $timeToCheck = $currentDate->format('Y-m-d') < $checkout->format('Y-m-d')  && $currentDate->format('Y-m-d') > $checkin->format('Y-m-d');

                if($timeToCheck ) {
                    $completed_dates[] = array(
                        "date" => $currentDate->format('Y-m-d'),
                        "product_id" => $product_id,
                        "totalHours" => $totalHours,
                        "totalIntervalHours" => $totalIntervalHours,
                        "timeToCheck" => $timeToCheck,
                        "isEqual" => $totalHours === $totalIntervalHours,
                        "trash" => $trash,
                        "order_id" => $order_id,
                    );
                }
                 
                $currentDate->modify('+1 day'); // Move to next day
            }
        }
    }

    }

    return $completed_dates;

}

function custom_api_partial_order_route() {
    register_rest_route('v2', '/partial-orders/(?P<product_id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_partial_orders_v2',
    ));
}
add_action('rest_api_init', 'custom_api_partial_order_route');

// Callback function to handle the API request
function get_partial_orders_v2($data) {
    global $wpdb;
    

    $product_id = isset($data['product_id']) ? intval($data['product_id']) : 0;
    $query = new WC_Order_Query( array(
        'limit'      => -1,
        'orderby'    => 'date',
        'order'      => 'DESC',
        'post_status' => array('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request'), // Add more statuses as needed
        'return'     => 'ids',
    ) );
    $orders  = $query->get_orders();


    $partial_dates = array();

    foreach ( $orders as $order_id ) {
        $order  = wc_get_order( $order_id );
        $checkin = get_post_meta($order_id, 'checkin', true);
        $checkout = get_post_meta($order_id, 'checkout', true);
        $items = $order->get_items();
        $hours_start = get_field('operating_hours_start', $product_id);
        $hours_end = get_field('operating_hours_end', $product_id);

        $checkin = new DateTime($checkin);
        $checkout = new DateTime($checkout);

        // Calculate the total hours between checkin and checkout
        $interval = $checkin->diff($checkout);
        $totalHours = $interval->h + $interval->i / 60;


        $hours_start = new DateTime($hours_start);
        $hours_end = new DateTime($hours_end);
        // Calculate the total hours between hours_start and hours_end
        $start = DateTime::createFromFormat('h:i A', $hours_start->format('h:i A'));
        $end = DateTime::createFromFormat('h:i A', $hours_end->format('h:i A'));
        $intervalHours = $start->diff($end);
        $totalIntervalHours = $intervalHours->h + $intervalHours->i / 60;

        // Extract date parts from checkin and checkout
        $checkinDate = $checkin->format('Y-m-d');
        $checkoutDate = $checkout->format('Y-m-d');

        // Create DateTime objects for the date parts only
        $checkinStart = DateTime::createFromFormat('Y-m-d', $checkinDate);
        $checkoutStart = DateTime::createFromFormat('Y-m-d', $checkoutDate);

        
        $product_ids = [];
        foreach ( $items as $item ) {
            // Get the product ID of the item
            $product_ids[] = $item->get_product_id();
        }

        if(in_array($product_id, $product_ids) && $totalHours !== $totalIntervalHours) {
            // $checkin = $checkin->format('Y-m-d');
            // $checkout = $checkout->format('Y-m-d');
            // Clone checkin date to iterate
            $currentDate = $checkinStart;

            // Loop through each day from checkin to checkout
            while ($currentDate <= $checkoutStart) {
                $timeToCheck = $currentDate->format('Y-m-d') < $checkout->format('Y-m-d')  && $currentDate->format('Y-m-d') > $checkin->format('Y-m-d');

                if(!$timeToCheck) {
                    $partial_dates[] = array(
                        "date" => $currentDate->format('Y-m-d'),
                        "product_id" => $product_id,
                        "order_id" => $order_id,
                        "checkin" =>  $checkin->format('Y-m-d'),
                        "checkout" => $checkoutStart->format('Y-m-d'),
                        "totalHours" => $totalHours,
                        "totalIntervalHours" => $totalIntervalHours,
                        "isEqual" => $totalHours === $totalIntervalHours,
                    );
                }
                 
                $currentDate->modify('+1 day'); // Move to next day
            }
        }
    }

    return $partial_dates;

}

function get_disabled_dates_by_product_id_callback_v2($data) {
    global $wpdb;
	
    $product_id = isset($data['id']) ? $data['id'] : 0;
	
    $current_date = isset($data['current_date']) ? $data['current_date'] : date('D M d Y H:i:s T');

    $query = $wpdb->prepare("
    SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
    FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
    INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
    LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
    LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
    WHERE lookup.product_id = %d
    AND orders.status IN ('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request')
    AND orders.status NOT IN ('trash', 'deleted')
    ", $product_id);


    $orders = $wpdb->get_results($query);

    $start_time_str = get_field('operating_hours_start', $product_id);
    $end_time_str = get_field('operating_hours_end', $product_id);
    
    // return array(
    //     $orders[0],
    //     "start" => $hours_start,
    //     "end" => $hours_end,
    // );
    $disabled_dates = array();
	// Convert the original date string to a Unix timestamp using strtotime
	$timestamp = strtotime($current_date);

    // return $orders;

	// Format the timestamp into the desired format
	$formattedDate = date('Y-m-d', $timestamp);

    foreach ($orders as $order) {
        
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        // if($order_date == $formattedDate) {
            // $disabled_dates[] = [
            //     "start" => $checkin_time_24hr, 
            //     "end" => $checkout_time_24hr,
            // ];
        // }

        // Define input data
        $checkin_value = new DateTime($order->checkin_value);
        $checkout_value = new DateTime($order->checkout_value);

        // Convert start and end times to DateTime objects
        $start_time = DateTime::createFromFormat('h:i A', $start_time_str);
        $end_time = DateTime::createFromFormat('h:i A', $end_time_str);

        // Iterate through each day in the interval
        $current_date = clone $checkin_value;
        $current_date->setTime(0, 0, 0); // Set to midnight to start the day comparison

        $interval_end = clone $checkout_value;
        $interval_end->setTime(0, 0, 0); // Set to midnight to end the day comparison

        while ($current_date <= $interval_end) {
            // Calculate the working hours for the current date
            if ($current_date == $checkin_value->format('Y-m-d')) {
                $working_hours_start = $checkin_value;
            } else {
                $working_hours_start = clone $current_date;
                $working_hours_start->setTime((int)$start_time->format('H'), (int)$start_time->format('i'));
            }

            if ($current_date == $checkout_value->format('Y-m-d')) {
                $working_hours_end = $checkout_value;
            } else {
                $working_hours_end = clone $current_date;
                $working_hours_end->setTime((int)$end_time->format('H'), (int)$end_time->format('i'));
            }

            // Ensure the working hours are within the checkin and checkout bounds
            if ($working_hours_start < $checkin_value) {
                $working_hours_start = $checkin_value;
            }
            if ($working_hours_end > $checkout_value) {
                $working_hours_end = $checkout_value;
            }

            // Format the working hours for output
            $working_hours_start_formatted = $working_hours_start->format('Y-m-d h:i A');
            $working_hours_end_formatted = $working_hours_end->format('Y-m-d h:i A');

            // Output the result for the current date
            // echo $working_hours_start_formatted . " to " . $working_hours_end_formatted . "<br>";
            $checkin_time_24hr = date('H:i', strtotime($working_hours_start_formatted));
            $checkout_time_24hr = date('H:i', strtotime($working_hours_end_formatted));

            if($formattedDate === $current_date->format('Y-m-d')) {
                $disabled_dates[] = [
                    "order" => $order, 
                    "start" => $checkin_time_24hr, 
                    "end" => $checkout_time_24hr,
                    // "start" => $checkin_value->format('Y-m-d'), 
                    // "end" => $checkout_value->format('Y-m-d'),
                    // "cond" => $formattedDate == $current_date->format('Y-m-d'),
                    // "order_date" => $formattedDate,
                    // "current_date" => $current_date->format('Y-m-d'),
                ];
            }
            

            // Move to the next day
            $current_date->modify('+1 day');
        }
    }
    return $disabled_dates;
}

function register_disabled_dates_endpoint_v2() {
    register_rest_route('v2', '/disabled-dates/', array(
        'methods' => 'GET',
        'callback' => 'get_disabled_dates_by_product_id_callback_v2',
    ));
}

add_action('rest_api_init', 'register_disabled_dates_endpoint_v2');


// Hook to initialize the REST API endpoint
add_action('rest_api_init', 'register_custom_rooms_availability_api');

function register_custom_rooms_availability_api() {
    register_rest_route('v2', '/rooms-availabilty/(?P<id>\d+)', array(
        'methods' => 'POST', // Allows GET requests
        'callback' => 'get_rooms_data_availability', // Function to handle the request
    ));
}

// Function to retrieve rooms/products based on query parameters
function get_rooms_data_availability(WP_REST_Request $request) {
    $id = sanitize_text_field($request->get_param('id'));
    $date = sanitize_text_field($request->get_param('date'));

    return format_booked_slots_by_id_availability($id, $date);
    // return format_booked_slots_by_id_availability(get_field('operating_hours_start', $id), get_field('operating_hours_end', $id), $id, $date);
}

function format_booked_slots_by_availability($product_id, $current_date) {
    global $wpdb;

    $query = $wpdb->prepare("
    SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
    FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
    INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
    LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
    LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
    WHERE lookup.product_id = %d
    AND orders.status IN ('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request')
    AND orders.status NOT IN ('trash', 'deleted')
    ", $product_id);


    $orders = $wpdb->get_results($query);

    $start_time_str = get_field('operating_hours_start', $product_id);
    $end_time_str = get_field('operating_hours_end', $product_id);
    
    // return array(
    //     $orders[0],
    //     "start" => $hours_start,
    //     "end" => $hours_end,
    // );
    $disabled_dates = array();
	// Convert the original date string to a Unix timestamp using strtotime
	$timestamp = strtotime($current_date);

    // return $orders;

	// Format the timestamp into the desired format
	$formattedDate = date('Y-m-d', $timestamp);

    foreach ($orders as $order) {
        
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        // if($order_date == $formattedDate) {
            // $disabled_dates[] = [
            //     "start" => $checkin_time_24hr, 
            //     "end" => $checkout_time_24hr,
            // ];
        // }

        // Define input data
        $checkin_value = new DateTime($order->checkin_value);
        $checkout_value = new DateTime($order->checkout_value);

        // Convert start and end times to DateTime objects
        $start_time = DateTime::createFromFormat('h:i A', $start_time_str);
        $end_time = DateTime::createFromFormat('h:i A', $end_time_str);

        // Iterate through each day in the interval
        $current_date = clone $checkin_value;
        $current_date->setTime(0, 0, 0); // Set to midnight to start the day comparison

        $interval_end = clone $checkout_value;
        $interval_end->setTime(0, 0, 0); // Set to midnight to end the day comparison

        while ($current_date <= $interval_end) {
            // Calculate the working hours for the current date
            if ($current_date == $checkin_value->format('Y-m-d')) {
                $working_hours_start = $checkin_value;
            } else {
                $working_hours_start = clone $current_date;
                $working_hours_start->setTime((int)$start_time->format('H'), (int)$start_time->format('i'));
            }

            if ($current_date == $checkout_value->format('Y-m-d')) {
                $working_hours_end = $checkout_value;
            } else {
                $working_hours_end = clone $current_date;
                $working_hours_end->setTime((int)$end_time->format('H'), (int)$end_time->format('i'));
            }

            // Ensure the working hours are within the checkin and checkout bounds
            if ($working_hours_start < $checkin_value) {
                $working_hours_start = $checkin_value;
            }
            if ($working_hours_end > $checkout_value) {
                $working_hours_end = $checkout_value;
            }

            // Format the working hours for output
            $working_hours_start_formatted = $working_hours_start->format('Y-m-d h:i A');
            $working_hours_end_formatted = $working_hours_end->format('Y-m-d h:i A');

            // Output the result for the current date
            // echo $working_hours_start_formatted . " to " . $working_hours_end_formatted . "<br>";
            $checkin_time_24hr = date('H:i', strtotime($working_hours_start_formatted));
            $checkout_time_24hr = date('H:i', strtotime($working_hours_end_formatted));

            if($formattedDate === $current_date->format('Y-m-d')) {
                $disabled_dates[] = [
                    // "order" => $order, 
                    "start" => $checkin_time_24hr, 
                    "end" => $checkout_time_24hr,
                    // "start" => $checkin_value->format('Y-m-d'), 
                    // "end" => $checkout_value->format('Y-m-d'),
                    // "cond" => $formattedDate == $current_date->format('Y-m-d'),
                    // "order_date" => $formattedDate,
                    // "current_date" => $current_date->format('Y-m-d'),
                ];
            }
            

            // Move to the next day
            $current_date->modify('+1 day');
        }
    }
    return $disabled_dates;
}

function format_booked_slots_by_id_availability($id, $date) {

    $slots = get_order_booked_slots_custom($id, $date);

    // Parse the date
    $dateObj = DateTime::createFromFormat('m/d/Y', $date);
    $dateStr = $dateObj->format('Y/m/d');

    // Initialize an array to hold the formatted date and time ranges
    $formattedRanges = [];

    // Convert each slot to the desired format
    foreach ($slots as $slot) {
        $startTime = DateTime::createFromFormat('H:i A', $slot['start']);
        $endTime = DateTime::createFromFormat('H:i A', $slot['end']);

        $startTimeStr = $startTime->format('h:i a');
        $endTimeStr = $endTime->format('h:i a');

        $formattedRanges[] = "{$dateStr} at {$startTimeStr} - {$dateStr} at {$endTimeStr}";
    }

    return $formattedRanges;
}



function format_booked_slots_by_id($operating_hours_start, $operating_hours_end, $id, $date) {

    $booked_slots = get_order_booked_slots_custom($id, $date);

    // Convert time strings to DateTime objects
    $start_time = DateTime::createFromFormat('h:i A', $operating_hours_start);
    $end_time = DateTime::createFromFormat('h:i A', $operating_hours_end);

    // Get the current date
    $date = new DateTime($date);
    $current_date = $date->format('m/d/Y');


    // Initialize an array to hold the formatted slots
    $formatted_slots = array();

    // Add the slot before the first booked slot if any
    if (!empty($booked_slots)) {

        $first_booked_start = DateTime::createFromFormat('h:i A', $booked_slots[0]['start']);

        if ($first_booked_start > $start_time) {
            $formatted_slots[] = $current_date . ' at ' . $start_time->format('h:i A') . ' - ' . $current_date . ' at ' . $first_booked_start->format('h:i A');
        }


        // Loop through booked slots and add available slots between them
        for ($i = 0; $i < count($booked_slots) - 1; $i++) {
            $booked_end = DateTime::createFromFormat('h:i A', $booked_slots[$i]["end"]);
            $next_booked_start = DateTime::createFromFormat('h:i A', $booked_slots[$i + 1]["start"]);

            if ($booked_end < $next_booked_start) {
                $formatted_slots[] = $current_date . ' at ' . $booked_end->format('h:i A') . ' - ' . $current_date . ' at ' . $next_booked_start->format('h:i A');
            }
        }

        // Add the slot after the last booked slot if any
        $last_booked_end = DateTime::createFromFormat('h:i A', end($booked_slots)["end"]);

        if ($last_booked_end < $end_time) {
            $formatted_slots[] = $current_date . ' at ' . $last_booked_end->format('h:i A') . ' - ' . $current_date . ' at ' . $end_time->format('h:i A');
        }

    } else {
        // If no booked slots, return the full operating hours as a single available slot
        $formatted_slots[] = $current_date . ' at ' . $start_time->format('h:i A') . ' - ' . $current_date . ' at ' . $end_time->format('h:i A');
    }

    return $formatted_slots;
}

function format_booked_slots($operating_hours_start, $operating_hours_end, $id) {

    $booked_slots = get_order_booked_slots($id);

    // Convert time strings to DateTime objects
    $start_time = DateTime::createFromFormat('h:i A', $operating_hours_start);
    $end_time = DateTime::createFromFormat('h:i A', $operating_hours_end);

    // Get the current date
    $current_date = date('m/d/Y');

    // Initialize an array to hold the formatted slots
    $formatted_slots = array();

    // Add the slot before the first booked slot if any
    if (!empty($booked_slots)) {
        $first_booked_start = DateTime::createFromFormat('h:i A', $booked_slots[0][0]);
        if ($first_booked_start > $start_time) {
            $formatted_slots[] = $current_date . ' at ' . $start_time->format('h:i A') . ' - ' . $first_booked_start->format('h:i A');
        }

        // Loop through booked slots and add available slots between them
        for ($i = 0; $i < count($booked_slots) - 1; $i++) {
            $booked_end = DateTime::createFromFormat('h:i A', $booked_slots[$i][1]);
            $next_booked_start = DateTime::createFromFormat('h:i A', $booked_slots[$i + 1][0]);

            if ($booked_end < $next_booked_start) {
                $formatted_slots[] = $current_date . ' at ' . $booked_end->format('h:i A') . ' - ' . $next_booked_start->format('h:i A');
            }
        }

        // Add the slot after the last booked slot if any
        $last_booked_end = DateTime::createFromFormat('h:i A', end($booked_slots)[1]);
        if ($last_booked_end < $end_time) {
            $formatted_slots[] = $current_date . ' at ' . $last_booked_end->format('h:i A') . ' - ' . $end_time->format('h:i A');
        }
    } else {
        // If no booked slots, return the full operating hours as a single available slot
        $formatted_slots[] = $current_date . ' at ' . $start_time->format('h:i A') . ' - ' . $end_time->format('h:i A');
    }

    return $formatted_slots;
}

function get_order_booked_slots($product_id) {
    global $wpdb;
	
    $current_date = date('D M d Y H:i:s T');
	
    // Prepare and execute the SQL query to retrieve booked slots
    $query = $wpdb->prepare("
    SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
    FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
    INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
    LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
    LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
    WHERE lookup.product_id = %d
    AND orders.status IN ('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request')
    AND orders.status NOT IN ('trash', 'deleted')
", $product_id);
    $orders = $wpdb->get_results($query);


    $booked_slots = array();
    $disabled_dates = array();
    // Convert the original date string to a Unix timestamp using strtotime
    $timestamp = strtotime($current_date);

    // Format the timestamp into the desired format
    $formattedDate = date('Y-m-d', $timestamp);

    foreach ($orders as $order) {
        $checkin_time_24hr = date('H:i A', strtotime($order->checkin_value));
        $checkout_time_24hr = date('H:i A', strtotime($order->checkout_value));
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        if ($order_date == "2024-05-22") {
            // if ($order_date == $formattedDate) {
            $booked_slots[] = [
                "start" => $checkin_time_24hr, 
                "end" => $checkout_time_24hr,
            ];
        }
    }

    // Convert the booked slots into the desired format
    $formatted_slots = array();
    foreach ($booked_slots as $slot) {
        $formatted_slots[] = array($slot['start'], $slot['end'] );
    }

    return $formatted_slots;
}

function create_notification_post_with_acf($title, $content, $user_id, $status) {
    // Define the post content
    $post_data = array(
        'post_title'    => $title,
        'post_content'  => $content,
        'post_status'   => 'publish',
        'post_type'     => 'notifications',
    );

    // Insert the post into the database
    $post_id = wp_insert_post( $post_data );
    update_field('user_id', $user_id, $post_id); // Set the user ID
    update_field('status', $status, $post_id); // Set the status

    $api_url = get_option('frontend_address_url');

    $data = array(
        'id'       => $post_id,
        'title'    => $title,
        'content'  => $content,
        'user_id'  => $user_id,
        'status'   =>  $status,
    );
    // Define the POST request arguments
    $args = array(
        'method'    => 'POST',
        'body'      => json_encode($data), // If you need to send data, add it here
        'headers'   => array(
            'Content-Type' => 'application/json',
        ),
    );

    wp_remote_post("$api_url/api/notification", $args);
    wp_remote_post("$api_url/api/load", $args);

}

function register_notifications_route() {
    register_rest_route('v2', '/notifications/(?P<id>\d+)', array(
        'methods'  => 'GET',
        'callback' => 'get_notifications_by_user_id',
    ));
}
add_action('rest_api_init', 'register_notifications_route');

function get_notifications_by_user_id(WP_REST_Request $request) {
    // Get the user_id parameter from the request
    $user_id = $request->get_param('id');
    $user_info = get_userdata($user_id);

   
           // Query for notifications
            $args = array(
                'post_type'         => 'notifications',
                'meta_key'          => 'user_id',
                'meta_value'        => $user_id,
                'posts_per_page'    => -1,
                'post_status'       => 'publish',
                'orderby'           => 'date',
                'order'             => 'DESC', // Use 'ASC' for ascending order
            );

            $query = new WP_Query($args);

            if (!$query->have_posts()) {
                return new WP_Error('no_notifications', 'No notifications found for this user', array('status' => 404));
            }

            $notifications = array();

            while ($query->have_posts()) {
                $query->the_post();
                $notifications[] = array(
                    'id' => get_the_id(),
                    'title' => get_the_title(),
                    'description' => get_the_content(),
                    'date' => get_the_date('Y-m-d H:i:s'),
                    'status' => get_field('status') ? 'read' : 'unread', // Convert boolean to 'read'/'unread'
                );
            }

            wp_reset_postdata();

            return new WP_REST_Response($notifications, 200);
    
}


function register_update_notification_status_route() {
    register_rest_route('v2', '/notifications/update', array(
        'methods'  => 'POST',
        'callback' => 'update_notification_status',
    ));
}
add_action('rest_api_init', 'register_update_notification_status_route');

function update_notification_status(WP_REST_Request $request) {
    $notification_id = $request->get_param('notification_id');

    if (!get_post($notification_id)) {
        return new WP_Error('invalid_notification', 'Notification not found', array('status' => 404));
    }

    update_field('status', true, $notification_id);
    return new WP_REST_Response(array('status' => 'success'), 200);
}


function register_forgot_password_route() {
    register_rest_route('v2', '/forgot-password', array(
        'methods' => 'POST',
        'callback' => 'handle_forgot_password',
    ));
}
add_action('rest_api_init', 'register_forgot_password_route');

function handle_forgot_password(WP_REST_Request $request) {
    $input = $request->get_param('input');
    $url = $request->get_param('url');
    
    if (is_email($input)) {
        $user = get_user_by('email', $input);
    } else {
        $user = get_user_by('login', $input);
    }

    if (!$user) {
        return new WP_Error('invalid_input', 'Username or email is not yet registered!', array('status' => 404));
    }

    $reset_key = get_password_reset_key($user);

    if (is_wp_error($reset_key)) {
        return new WP_Error('reset_key_error', 'Could not generate reset key', array('status' => 500));
    }

    $reset_url = add_query_arg(array(
        'action' => 'rp',
        'key' => $reset_key,
        'login' => rawurlencode($user->user_login)
    ), $url);

    $message = "Hello,\n\n";
    $message .= "You asked us to reset your password for your account using the email address {$user->user_email}.\n\n";
    $message .= "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.\n\n";
    $message .= "To reset your password, visit the following address:\n\n";
    $message .= "$reset_url\n\n";
    $message .= "Thanks!\n";

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    $mail_sent = wp_mail($user->user_email, 'Password Reset Request', $message, $headers);

    if (!$mail_sent) {
        return new WP_Error('email_error', 'Failed to send reset email', array('status' => 500));
    }

    return new WP_REST_Response(array('status' => 'success', 'message' => 'Password reset email sent'), 200);
}


// Register a custom REST API endpoint
add_action('rest_api_init', 'get_booking_cancellations_and_refunds_policy_endpoint');
function get_booking_cancellations_and_refunds_policy_endpoint() {
    register_rest_route('v2', '/get-booking-cancellations-and-refunds-policy-data', array(
        'methods' => 'GET',
        'callback' => 'get_booking_cancellations_and_refunds_policy_data',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_booking_cancellations_and_refunds_policy_data($request) {
    $content = get_field('booking_cancellations_and_refund_policy_content', 'option'); // Assuming the field is an options page field
    $banner_image = get_field('booking_cancellations_and_refund_policy_banner_image', 'option'); // Assuming the field is an options page field
    $title = get_field('booking_cancellations_and_refund_policy_title', 'option'); // Assuming the field is an options page field


    // Check if data exists
    if ($content) {
        $options = array(
            'content' => $content,
            'banner_image' => $banner_image,
            'title' => $title,
        );
        return rest_ensure_response($options);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}

add_action('rest_api_init', 'get_privacy_policy_endpoint');
function get_privacy_policy_endpoint() {
    register_rest_route('v2', '/get-privacy-policy-data', array(
        'methods' => 'GET',
        'callback' => 'get_privacy_policy_data',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_privacy_policy_data($request) {
    $content = get_field('privacy_policy_content', 'option'); // Assuming the field is an options page field
    $banner_image = get_field('privacy_policy_banner_image', 'option'); // Assuming the field is an options page field
    $title = get_field('privacy_policy_title', 'option'); // Assuming the field is an options page field


    // Check if data exists
    if ($content) {
        $options = array(
            'content' => $content,
            'banner_image' => $banner_image,
            'title' => $title,
        );
        return rest_ensure_response($options);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}

add_action('rest_api_init', 'get_terms_and_conditions_endpoint');
function get_terms_and_conditions_endpoint() {
    register_rest_route('v2', '/get-terms-and-conditions-data', array(
        'methods' => 'GET',
        'callback' => 'get_terms_and_conditions_data',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_terms_and_conditions_data($request) {
    $content = get_field('terms_and_conditions_content', 'option'); // Assuming the field is an options page field
    $banner_image = get_field('terms_and_conditions_banner_image', 'option'); // Assuming the field is an options page field
    $title = get_field('terms_and_conditions_title', 'option'); // Assuming the field is an options page field


    // Check if data exists
    if ($content) {
        $options = array(
            'content' => $content,
            'banner_image' => $banner_image,
            'title' => $title,
        );
        return rest_ensure_response($options);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}

add_action('rest_api_init', 'get_sitemap_endpoint');
function get_sitemap_endpoint() {
    register_rest_route('v2', '/get-sitemap-data', array(
        'methods' => 'GET',
        'callback' => 'get_sitemap_data',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_sitemap_data($request) {
    $sitemap_pages = get_field('sitemap_pages', 'option'); // Assuming the field is an options page field
    $banner_image = get_field('sitemap_banner_image', 'option'); // Assuming the field is an options page field
    $title = get_field('sitemap_title', 'option'); // Assuming the field is an options page field

    $pages = array();
    if ($sitemap_pages) {
        foreach ($sitemap_pages as $button) {
            $pages[] = array(
                'name' => $button['name'],
                'link' => $button['link']
            );
        }
    }

    // Check if data exists
    if ($pages) {
        $respone = array(
            'banner_image' => $banner_image,
            'title' => $title,
            'pages' => $pages,
        );
        return rest_ensure_response($respone);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}


add_action('rest_api_init', 'get_locations_endpoint');
function get_locations_endpoint() {
    register_rest_route('v2', '/get-locations-data', array(
        'methods' => 'GET',
        'callback' => 'get_locations_data',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_locations_data($request) {
    $title = get_field('locations_option_title', 'option'); // Assuming the field is an options page field
    $description = get_field('locations_option_description', 'option'); // Assuming the field is an options page field
    $banner_image = get_field('locations_option_banner_image', 'option'); // Assuming the field is an options page field


    // Check if data exists
    if ($title || $banner_image || $description) {
        $options = array(
            'title' => $title,
            'banner_image' => $banner_image,
            'description' => $description,
        );
        return rest_ensure_response($options);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}

add_action('rest_api_init', 'get_meeting_rooms_endpoint');
function get_meeting_rooms_endpoint() {
    register_rest_route('v2', '/get-meeting-rooms-data', array(
        'methods' => 'GET',
        'callback' => 'get_meeting_rooms_data',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_meeting_rooms_data($request) {
    $title = get_field('meeting_rooms_group_title', 'option'); // Assuming the field is an options page field
    $description = get_field('meeting_rooms_group_description', 'option'); // Assuming the field is an options page field
    $banner_image = get_field('meeting_rooms_group_banner_image', 'option'); // Assuming the field is an options page field

    // Check if data exists
    if ($title || $description) {
        $options = array(
            'title' => $title,
            'description' => $description,
            'banner_image' => $banner_image,
        );
        return rest_ensure_response($options);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}

// Register a custom REST API endpoint
add_action('rest_api_init', 'register_banner_slider_endpoint');
function register_banner_slider_endpoint() {
    register_rest_route('v2', '/homepage-options', array(
        'methods' => 'GET',
        'callback' => 'get_homepage_options',
    ));
}

// Callback function to retrieve data from ACF field "banner slider"
function get_homepage_options($request) {
    $banner_slider_data = get_field('banner_slider', 'option'); // Assuming the field is an options page field
    $banner_title_data = get_field('banner_title', 'option'); // Assuming the field is an options page field
    $featured_locations_title_data = get_field('featured_locations_title', 'option'); // Assuming the field is an options page field
    $featured_locations_description_data = get_field('featured_locations_description', 'option'); // Assuming the field is an options page field
    $meeting_rooms_title_data = get_field('meeting_rooms_title', 'option'); // Assuming the field is an options page field
    $meeting_rooms_description_data = get_field('meeting_rooms_description', 'option'); // Assuming the field is an options page field
    $meeting_rooms_buttons_data = get_field('meeting_rooms_buttons', 'option'); // Assuming the field is an options page field

    $buttons = array();
    if ($meeting_rooms_buttons_data) {
        foreach ($meeting_rooms_buttons_data as $button) {
            $buttons[] = array(
                'name' => $button['name'],
                'variant' => $button['variant'],
                'link' => $button['link']
            );
        }
    }

    // Check if data exists
    if ($banner_slider_data) {
        $options = array(
            'banner_sliders' => $banner_slider_data,
            'banner_title' => $banner_title_data,
            'featured_locations_title_data' => $featured_locations_title_data,
            'featured_locations_description_data' => $featured_locations_description_data,
            'meeting_rooms_title_data' => $meeting_rooms_title_data,
            'meeting_rooms_description_data' => $meeting_rooms_description_data,
            'meeting_room_buttons' => $buttons,
        );
        return rest_ensure_response($options);
    } else {
        return new WP_Error('no_data', 'No banner slider data found', array('status' => 404));
    }
}

add_action('rest_api_init', 'register_ayala_global_options_data_endpoint');
function register_ayala_global_options_data_endpoint() {
    register_rest_route('v2', '/ayala-global-options-data', array(
        'methods' => 'GET',
        'callback' => 'get_ayala_global_options_data',
    ));
}

function get_ayala_global_options_data($request) {
    // Retrieve data for booking cancellations and refunds policy
    $booking_cancellations_and_refunds_policy = array(
        'content' => get_field('booking_cancellations_and_refund_policy_content', 'option'),
        'banner_image' => get_field('booking_cancellations_and_refund_policy_banner_image', 'option'),
        'title' => get_field('booking_cancellations_and_refund_policy_title', 'option'),
    );

    // Retrieve data for privacy policy
    $privacy_policy = array(
        'content' => get_field('privacy_policy_content', 'option'),
        'banner_image' => get_field('privacy_policy_banner_image', 'option'),
        'title' => get_field('privacy_policy_title', 'option'),
    );

    // Retrieve data for terms and conditions
    $terms_and_conditions = array(
        'content' => get_field('terms_and_conditions_content', 'option'),
        'banner_image' => get_field('terms_and_conditions_banner_image', 'option'),
        'title' => get_field('terms_and_conditions_title', 'option'),
    );

    // Retrieve data for sitemap
    $sitemap_pages = get_field('sitemap_pages', 'option');
    $sitemap = array(
        'banner_image' => get_field('sitemap_banner_image', 'option'),
        'title' => get_field('sitemap_title', 'option'),
        'pages' => array_map(function($page) {
            return array(
                'name' => $page['name'],
                'link' => $page['link']
            );
        }, $sitemap_pages ?: array())
    );

    // Retrieve data for locations
    $locations = array(
        'title' => get_field('locations_option_title', 'option'),
        'description' => get_field('locations_option_description', 'option'),
        'banner_image' => get_field('locations_option_banner_image', 'option'),
    );

    // Retrieve data for meeting rooms
    $meeting_rooms = array(
        'title' => get_field('meeting_rooms_group_title', 'option'),
        'description' => get_field('meeting_rooms_group_description', 'option'),
        'banner_image' => get_field('meeting_rooms_group_banner_image', 'option'),
    );

    // Retrieve data for homepage options
    $homepage_options = array(
        'banner_sliders' => get_field('banner_slider', 'option'),
        'banner_title' => get_field('banner_title', 'option'),
        'featured_locations_title_data' => get_field('featured_locations_title', 'option'),
        'featured_locations_description_data' => get_field('featured_locations_description', 'option'),
        'meeting_rooms_title_data' => get_field('meeting_rooms_title', 'option'),
        'meeting_rooms_description_data' => get_field('meeting_rooms_description', 'option'),
        'meeting_room_buttons' => array_map(function($button) {
            return array(
                'name' => $button['name'],
                'variant' => $button['variant'],
                'link' => $button['link']
            );
        }, get_field('meeting_rooms_buttons', 'option') ?: array())
    );

    $header_options = array(
        'main_menus' => array_map(function($menu) {
            return array(
                'name' => $menu['name'],
                'is_center_admin' => $menu['is_center_admin'],
                'link' => $menu['link']
            );
        }, get_field('menus_main_menus', 'option') ?: array()),
        'account_menus' => array_map(function($menu) {
            return array(
                'name' => $menu['name'],
                'user_role' => $menu['user_role'],
                'link' => $menu['link']
            );
        }, get_field('menus_account_menus', 'option') ?: array())
    );

    $copyright_footer_options = array(
        'tagline' => get_field('copyright_footer_tagline', 'option'),
        'links' => array_map(function($menu) {
            return array(
                'name' => $menu['name'],
                'link' => $menu['link']
            );
        }, get_field('copyright_footer_links', 'option') ?: array())
    );

    $footer_quicklinks_options = array_map(function($menu) {
        return array(
            'name' => $menu['name'],
            'links' => $menu['links']
        );
    }, get_field('footer_right_quick_links', 'option') ?: array());


    // Combine all data
    $response_data = array(
        'booking_cancellations_and_refunds_policy' => $booking_cancellations_and_refunds_policy,
        'privacy_policy' => $privacy_policy,
        'terms_and_conditions' => $terms_and_conditions,
        'sitemap' => $sitemap,
        'locations' => $locations,
        'meeting_rooms' => $meeting_rooms,
        'homepage_options' => $homepage_options,
        'header_options' => $header_options,
        'logo' => get_field('logo', 'option'),
        'copyright_footer' => $copyright_footer_options,
        'footer_quicklinks' => $footer_quicklinks_options,
        'footer_left_content' => get_field('footer_left_content', 'option'),
    );

    return rest_ensure_response($response_data);
}

// Register custom REST route
function register_firebase_token_route() {
    register_rest_route(
        'v2',
        '/add-token',
        array(
            'methods'  => 'POST',
            'callback' => 'handle_add_firebase_token',
        )
    );
}
add_action('rest_api_init', 'register_firebase_token_route');

// Callback function for the REST route
function handle_add_firebase_token(WP_REST_Request $request) {
    // Get parameters from the request
    $token = $request->get_param('token');

    if (empty($token)) {
        return new WP_Error('no_token', 'No token provided', array('status' => 400));
    }

    // Check if a post with the same title already exists
    $existing_post = get_page_by_title($token, OBJECT, 'firebase_token');
    if ($existing_post instanceof WP_Post) {
        // Post with the same title already exists, return an error or handle it as needed
        return new WP_Error('existing_token', 'Token already exists', array('status' => 400));
    }

    // Create a new post of custom post type 'firebase_token'
    $post_id = wp_insert_post(array(
        'post_title'  => $token,
        'post_type'   => "firebase_token",
        'post_status' => 'publish',
    ));

    if (is_wp_error($post_id)) {
        return new WP_Error('post_creation_failed', 'Failed to create post', array('status' => 500));
    }

    // Save the token using ACF field
    update_field('firebase_token', $token, $post_id);

    // Return the new post ID in the response
    return array('post_id' => $post_id);
}



function register_get_firebase_tokens_route() {
    register_rest_route(
        'v2',
        '/get-tokens',
        array(
            'methods'  => 'GET',
            'callback' => 'handle_get_firebase_tokens',
        )
    );
}
add_action('rest_api_init', 'register_get_firebase_tokens_route');

function handle_get_firebase_tokens() {
    $args = array(
        'post_type'      => 'firebase_token',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $tokens = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $token = get_field('firebase_token', get_the_ID()); // Retrieve token using ACF field
            $tokens[] = $token;
        }
        wp_reset_postdata();
    }

    if (empty($tokens)) {
        return new WP_Error('no_tokens', 'No tokens found', array('status' => 404));
    }

    return rest_ensure_response($tokens);
}

add_action( 'rest_api_init', 'custom_login_api_endpoint' );

function custom_login_api_endpoint() {
    register_rest_route( 'v2', '/login', array(
        'methods' => 'POST',
        'callback' => 'custom_login_api_callback',
    ) );
}

function custom_login_api_callback( $request ) {
    // Retrieve username and password from request
    $username = $request->get_param( 'username' );
    $password = $request->get_param( 'password' );

    // Check if username is provided
    if ( empty( $username ) ) {
        return new WP_Error( 'missing_username', 'Username is missing.', array( 'status' => 400 ) );
    }

    // Check if password is provided
    if ( empty( $password ) ) {
        return new WP_Error( 'missing_password', 'Password is missing.', array( 'status' => 400 ) );
    }

    // Validate username and password
    $user = wp_authenticate( $username, $password );

    if ( is_wp_error( $user ) ) {
        // Check if it's a password error
        if ( $user->get_error_code() === 'incorrect_password' ) {
            return new WP_Error( 'incorrect_password', 'Incorrect password.', array( 'status' => 401 ) );
        }
        
        // Check if it's a username error
        if ( $user->get_error_code() === 'invalid_username' ) {
            return new WP_Error( 'invalid_username', 'Unknown username. Check again or try your email address.', array( 'status' => 401 ) );
        }

        // Otherwise, it's an invalid credentials error
        return new WP_Error( 'invalid_credentials', 'Invalid username or password.', array( 'status' => 401 ) );
    }

    update_user_meta($user->ID, 'sso_login', false);
    $last_login_time = current_time('mysql');
    update_user_meta($user->ID, 'last_login_time', $last_login_time);

    // Extract user data
    return array(
        'email' => $user->user_email,
        'name' => $user->display_name,
        'id' => $user->ID,
        'manual_login' => true,
        'role' => $user->roles[0], // Assuming user has only one role
        'last_login_time' => $last_login_time,
    );

}


function send_fcm_notification($token, $user_id, $firebaseToken, $title, $description ) {
    $payload = [
        'message' => [
            'token' => $token,
            'notification' => [
                'body' => $description,
                'title' => $title,
            ],
            'data' => [
                'type' => 'notification',
                'user_id' => $user_id,
                'token' => $token,
            ],
        ],
    ];

    $response = wp_remote_post('https://fcm.googleapis.com/v1/projects/ayalaland-booking/messages:send', [
        'headers' => [
            'Authorization' => 'Bearer ' . $firebaseToken,
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode($payload),
    ]);

    if (is_wp_error($response)) {
        return new WP_Error('fcm_error', 'Error sending message to token: ' . $token, ['status' => 500]);
    }

    $response_body = wp_remote_retrieve_body($response);
    $response_code = wp_remote_retrieve_response_code($response);

    return [
        'code' => $response_code,
        'response' => json_decode($response_body, true),
    ];
}


// Register the REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('v2', '/send-reset-link', array(
        'methods'  => 'POST',
        'callback' => 'custom_send_reset_link_handler',
        'permission_callback' => '__return_true',
    ));
});

/**
 * Handler for the custom send reset link endpoint
 */
function custom_send_reset_link_handler( $request ) {
    $parameters = $request->get_json_params();
    $user_login = $request->get_param( 'login' );
    $url = $request->get_param( 'url' );

    if ( empty( $user_login ) ) {
        return new WP_Error( 'invalid_request', 'User login is required.', array( 'status' => 400 ) );
    }

    // Get the user by login
    $user = get_user_by('login', $user_login);
    if ( ! $user ) {
        return new WP_Error( 'invalid_user', 'Invalid user login.', array( 'status' => 404 ) );
    }

    // Generate the password reset key
    $key = get_password_reset_key( $user );

    // Construct the reset link
    $reset_link = add_query_arg( array(
        'action' => 'rp',
        'key' => $key,
        'login' => $user->user_login,
    ), $url );

    // Send the reset link via email
    $to = $user->user_email;
    $subject = 'Password Reset Request';
    $message = "Hello,\n\nPlease click the following link to reset your password:\n\n$reset_link\n\nThank you.";
    $headers = array('Content-Type: text/plain; charset=UTF-8');

    wp_mail( $to, $subject, $message, $headers );

    return new WP_REST_Response( array(
        'message' => 'Password reset link sent successfully.',
    ), 200 );
}

// Register the REST API endpoint
add_action('rest_api_init', function () {
    register_rest_route('v2', '/reset-password', array(
        'methods'  => 'POST',
        'callback' => 'custom_reset_password_handler',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('v2', '/validate-reset-key', array(
        'methods'  => 'POST',
        'callback' => 'custom_validate_reset_key_handler',
        'permission_callback' => '__return_true',
    ));

});

/**
 * Handler for the custom reset password endpoint
 */
function custom_reset_password_handler( $request ) {
    $parameters = $request->get_json_params();
    $user_login = $request->get_param( 'login' );
    $new_password = $request->get_param( 'new_password' );
    $key = $request->get_param( 'key' );

    if ( empty( $user_login ) || empty( $new_password ) || empty( $key ) ) {
        return new WP_Error( 'invalid_request', 'Login, new password, and key are required.', array( 'status' => 400 ) );
    }

    // Validate the key and user login
    $user = check_password_reset_key( $key, $user_login );
    if ( is_wp_error( $user ) ) {
        return new WP_Error( 'invalid_key', 'Invalid key or login.', array( 'status' => 400 ) );
    }

    // Set the new password
    reset_password( $user, $new_password );

    return new WP_REST_Response( array(
        'message' => 'Password updated successfully.',
    ), 200 );
}

function custom_validate_reset_key_handler( $request ) {
    $parameters = $request->get_json_params();
    $user_login = $request->get_param( 'login' );
    $key = $request->get_param( 'key' );

    if ( empty( $user_login ) || empty( $key ) ) {
        return new WP_Error( 'invalid_request', 'User login and key are required.', array( 'status' => 400 ) );
    }

    // Validate the key and user login
    $user = check_password_reset_key( $key, $user_login );
    if ( is_wp_error( $user ) ) {
        return new WP_Error( 'invalid_key', 'Invalid key or login.', array( 'status' => 400 ) );
    }

    return new WP_REST_Response( array(
        'message' => 'Key is valid.',
    ), 200 );
}


function handle_increment_room_views( WP_REST_Request $request ) {
    $room_id = $request->get_param( 'room_id' );
    $count = get_field("count", $room_id);
  
    if(is_null($count)) {
        update_field("count", 1, $room_id);
        return get_field("count", $room_id);
    }
    else {
        update_field("count", $count+1, $room_id);
        return get_field("count", $room_id);
    }
}

function register_increment_count_viewer_endpoint() {
    register_rest_route('v2', '/increment-count-viewer/(?P<room_id>\d+)', array(
        'methods'  => 'POST',
        'callback' => 'handle_increment_room_views',
       
    ));
}
add_action('rest_api_init', 'register_increment_count_viewer_endpoint');

function handle_decrement_room_views( WP_REST_Request $request ) {
    $room_id = $request->get_param( 'room_id' );
    $count = get_field("count", $room_id);

    if(is_null($count) || $count <= 1) {
        update_field("count", 0, $room_id);
        return get_field("count", $room_id);
    }
    else {
        update_field("count", $count-1, $room_id);
        return get_field("count", $room_id);
    }
}

function register_decrement_count_viewer_endpoint() {
    register_rest_route('v2', '/decrement-count-viewer/(?P<room_id>\d+)', array(
        'methods'  => 'POST',
        'callback' => 'handle_decrement_room_views',
    ));
}
add_action('rest_api_init', 'register_decrement_count_viewer_endpoint');



add_action('rest_api_init', function () {
    register_rest_route('v2', '/sso-login-microsoft', [
        'methods' => 'POST',
        'callback' => 'sso_login_create_user_microsoft',
        'permission_callback' => '__return_true', // Properly secure this with authorization
    ]);
});

function sso_login_create_user_microsoft($request) {
    $email = sanitize_email($request->get_param("email")); // Sanitize email
    $firstName = sanitize_text_field($request->get_param("firstname")); // Sanitize firstname
    $lastName = sanitize_text_field($request->get_param("lastname")); // Sanitize lastname
    $role = sanitize_text_field($request->get_param("role")); // Sanitize role
    // Validate required fields
    if (!$email || !is_email($email)) {
        return new WP_Error('invalid_data', 'Invalid or missing email', ['status' => 400]);
    }

    if (!$firstName) {
        return new WP_Error('invalid_data', 'Missing firstname', ['status' => 400]);
    }

    if (!$lastName) {
        return new WP_Error('invalid_data', 'Missing lastname', ['status' => 400]);
    }

    if (!$role) {
        return new WP_Error('invalid_data', 'Missing role', ['status' => 400]);
    }

    // Check if user exists
    $user = get_user_by('email', $email);

    if ($user) {
        update_user_meta($user_id, 'sso_login', true);
        return new WP_Error('user_exists', 'User with this email already exists', ['status' => 400]);
    }

    // Create a new user
    $user_id = wp_insert_user([
        'user_email' => $email,
        'user_login' => $email, // Use email as username or customize as needed
        'first_name' => $firstName,
        'last_name' => $lastName,
        'role' => $role, // Set the user role based on the parameter
    ]);

    if (is_wp_error($user_id)) {
        return new WP_Error('user_creation_failed', 'Error creating WordPress user', ['status' => 500]);
    }

    $user = get_user_by('ID', $user_id);

    // Set 'password_set' meta to false for new users if needed
    update_user_meta($user_id, 'password_set', false);
    update_user_meta($user_id, 'sso_login', true);
    $last_login_time = current_time('mysql');
    update_user_meta($user_id, 'last_login_time', $last_login_time);

    // Return response
    return rest_ensure_response([
        'firstname' => $user->first_name,
        'lastname' => $user->last_name,
        'email' => $user->user_email,
        'sub' => (string) $user->ID,
        'id' => $user->ID,
        'role' => $role, // Return the assigned role
        'last_login_time' => $last_login_time,
    ]);
}

add_action('rest_api_init', function () {
    register_rest_route('v2', '/validate-booking', [
        'methods' => 'POST',
        'callback' => 'validate_booking_times_function',
        'permission_callback' => '__return_true', // Properly secure this with authorization
    ]);
});

function validate_booking_times_function($request) {
    $product_id = sanitize_text_field($request->get_param("product_id")); // Sanitize firstname
    $checkin = sanitize_text_field($request->get_param("checkin")); // Sanitize firstname
    $checkout = sanitize_text_field($request->get_param("checkout")); // Sanitize lastname

    // Convert strings to DateTime objects
    $checkinDateTime = new DateTime($checkin);
    $checkoutDateTime = new DateTime($checkout);

    // Format dates to 'Y-m-d H:i:s'
    $formattedCheckin = $checkinDateTime->format('Y-m-d H:i:s');
    $formattedCheckout = $checkoutDateTime->format('Y-m-d H:i:s');

    $validate_booking_times = validate_booking_times($checkin, $checkout, $product_id);
    if($validate_booking_times["is_booked"]) {
        return new WP_Error('booking_conflict', 'The '.$validate_booking_times["product_name"].' is already booked for the selected date and time.');
    }
    return true;

}

function validate_booking_times($checkin, $checkout, $product_id) {
    global $wpdb;

    $query = $wpdb->prepare("
    SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
    FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
    INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
    LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
    LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
    WHERE lookup.product_id = %d
    AND orders.status IN ('wc-ayala_approved', 'wc-denied_request', 'wc-cancel_request')
    AND orders.status NOT IN ('trash', 'deleted')
", $product_id);

    $orders = $wpdb->get_results($query);

    $product = wc_get_product($product_id);
    $product_name = $product->get_name();
    
    foreach ($orders as $booking) {

        $existing_checkin = $booking->checkin_value;
        $existing_checkout = $booking->checkout_value;
        $date_checkin = new DateTime($existing_checkin);
        $date_checkout = new DateTime($existing_checkout);
        

        // Check for overlap
        if (
            (strtotime($checkin) >= strtotime($existing_checkin) && strtotime($checkin) < strtotime($existing_checkout)) ||
            (strtotime($checkout) > strtotime($existing_checkin) && strtotime($checkout) <= strtotime($existing_checkout)) ||
            (strtotime($checkin) <= strtotime($existing_checkin) && strtotime($checkout) >= strtotime($existing_checkout))
        ) {
            return array(
                "is_booked" => true, 
                "checkin"   => $date_checkin->format('F j, Y \a\t h:i:s A'),
                "checkout"  => $date_checkout->format('F j, Y \a\t h:i:s A'),
                "product_name"  => $product_name,
            ); // No overlap found
        }
    }
    return array(
        "is_booked" => false
    ); // No overlap found
}

function validate_booking_times_for_multiple_rooms($orders_data) {
    foreach ($orders_data as $order_data) {
        // Sanitize and validate individual order data
        $product_id = intval($order_data['product_id']);
        $booking_type = sanitize_text_field($order_data['Booking Type']);
        $booking_date = sanitize_text_field($order_data['Booking Date']);

        // Parse checkin and checkout dates
        $format = 'd-m-y H:i A';
        $dates = explode(' - ', $booking_date);
        $checkin = DateTime::createFromFormat($format, trim($dates[0]));
        $checkout = DateTime::createFromFormat($format, trim($dates[1]));

        $validate_booking_times = validate_booking_times($checkin->format('Y-m-d H:i:s'), $checkout->format('Y-m-d H:i:s'), $product_id);
        if($validate_booking_times["is_booked"]) {
            return array(
                "is_booked"     => $validate_booking_times["is_booked"], 
                "checkin"       => $validate_booking_times["checkin"],
                "checkout"      => $validate_booking_times["checkout"],
                "product_name"  => $validate_booking_times["product_name"],
            ); // No overlap found
        }
       
    }
    return array(
        "is_booked" => false
    ); // No overlap found
}




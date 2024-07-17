<?php


function rename_shop_manager_role() {
    global $wp_roles;

    // Rename the 'Shop Manager' role to 'New Role Name'
    $wp_roles->roles['shop_manager']['name'] = 'Center Admin';
    $wp_roles->role_names['shop_manager'] = 'Center Admin';
}

add_action('init', 'rename_shop_manager_role');


// function restrict_shop_manager_locations($query) {
//     // Check if the user is a Shop Manager and if it is the main query
//     if(is_admin()) {
//         if (current_user_can('shop_manager') && $query->is_main_query() && isset($_GET['post_type']) & $_GET['post_type'] == 'location' ) {
//             // Get the current user ID
//             $current_user_id = get_current_user_id();
    
//             // Modify the query to show only locations created by the current user
//             $query->set('author', $current_user_id);
//         }
//     }
    
// }

// add_action('pre_get_posts', 'restrict_shop_manager_locations');

function restrict_shop_manager_locations($query) {
    // Check if we're in the admin area, the user is a Shop Manager, and it's the main query
    if (is_admin() && $query->is_main_query() && current_user_can('shop_manager') && isset($_GET['post_type']) && $_GET['post_type'] == 'location') {
        // Get the current user ID
        $current_user_id = get_current_user_id();

        // Fetch the ACF field value for the user (assuming the field name is 'location')
        $location_id = get_field('location', 'user_' . $current_user_id);

        // Check if a location ID is set for the user
        if ($location_id) {
            // Modify the query to show only the location matching the user's ACF field value
            $query->set('p', $location_id);
        } else {
            // If no location is set, modify the query to show no results
            $query->set('p', 0);
        }
    }
}

add_action('pre_get_posts', 'restrict_shop_manager_locations');


function restrict_shop_manager_bookings($query) {
    // Check if the user is a Shop Manager and if it is the main query
    if(is_admin()) {
        if (current_user_can('shop_manager') && $query->is_main_query() && isset($_GET['post_type']) & $_GET['post_type'] == 'booking' ) {
            // Get the current user ID
            $current_user_id = get_current_user_id();
            $location_id = get_field('location', 'user_' . $current_user_id);
            // Modify the query to show only bookings created by the current user
            // $query->set('author', $current_user_id);

            // Query to get product IDs based on ACF field 'location'
            $args = array(
                'post_type' => 'product', // Adjust post type if needed
                'posts_per_page' => -1, // Retrieve all products
                'meta_query' => array(
                    array(
                        'key' => 'room_description_location', // ACF field key for location
                        'value' => $location_id, // Replace with your location ID
                        'compare' => '=', // Adjust comparison if needed
                    ),
                ),
                'fields' => 'ids', // Retrieve only IDs to optimize performance
            );
            $product_ids = get_posts($args);

            // Add a meta query to filter bookings based on product IDs
            if (!empty($product_ids)) {
                $meta_query = array(
                    'relation' => 'AND',
                    array(
                        'key' => 'product_id', // Assuming 'product_id' is the meta key for the product associated with the booking
                        'value' => $product_ids,
                        'compare' => 'IN',
                    ),
                );
                $query->set('meta_query', $meta_query);
            }
        }
    }
    
}

add_action('pre_get_posts', 'restrict_shop_manager_bookings');

// function restrict_shop_manager_products($query) {
//     // Check if the user is a Shop Manager and if it is the main query
//     if(is_admin()) {
//         if (current_user_can('shop_manager') && $query->is_main_query() && isset($_GET['post_type']) & $_GET['post_type'] == 'product' ) {
//             // Get the current user ID
//             $current_user_id = get_current_user_id();
    
//             // Modify the query to show only bookings created by the current user
//             $query->set('author', $current_user_id);
//         }
//     }
    
// }


function restrict_shop_manager_products($query) {
    // Check if the user is a Shop Manager and if it is the main query
    if(is_admin()) {
        if (current_user_can('shop_manager') && $query->is_main_query() && isset($_GET['post_type']) & $_GET['post_type'] == 'product' ) {
            // Get the current user ID
            $current_user_id = get_current_user_id();
            $location_id = get_field('location', 'user_' . $current_user_id);
            
            $meta_query = array(
                array(
                    'key' => 'room_description_location',
                    'value' => $location_id,
                    'compare' => '='
                )
            );
            $query->set('meta_query', $meta_query);
        }
    }
    
}

add_action('pre_get_posts', 'restrict_shop_manager_products');

// add_action( 'woocommerce_product_query', 'shapeSpace_set_only_author', 20, 2 );
// function shapeSpace_set_only_author ( $q, $query ) {
//     if( is_admin() ) return;

//     global $current_user;
//     $q->set('author', $current_user->ID);
// }
// 
add_action( 'pre_get_posts', 'modify_query_show_current_user_products' );

function modify_query_show_current_user_products( $query ) {
    global $current_user;

    if( ! is_admin() && $query->is_main_query() && isset( $query->query_vars['wc_query'] ) && $query->query_vars['wc_query'] == 'product_query' ) {
        $query->set('author', $current_user->ID);
    }
}


function custom_restrict_user_access($role, $pages_to_remove) {
    $user = wp_get_current_user();

    if (in_array($role, $user->roles)) {
        foreach ($pages_to_remove as $page) {
            remove_menu_page($page);
        }
    }
}

// Usage for removing access to Pages, Media, and Posts for Shop Managers
add_action('admin_menu', function () {
    $pages_to_remove_for_shop_manager = array(
        'edit.php?post_type=page', // Pages
        'upload.php', // Media
        'edit.php', // Posts
        'edit-comments.php',
        'themes.php',
        'users.php',
        'index.php',
        // Add more pages as needed
    );

    custom_restrict_user_access('shop_manager', $pages_to_remove_for_shop_manager);
});

function remove_dashboard_widgets() {
    if ( current_user_can( 'shop_manager' ) ) {
        // remove WooCommerce Dashboard Status
        remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
    }
}
add_action( 'wp_user_dashboard_setup', 'remove_dashboard_widgets', 20 );
add_action( 'wp_dashboard_setup', 'remove_dashboard_widgets', 20 );

/*
* Remove WooCommerce reports for shop manager
* Remove analytics for shop manager
*/
function zorem_remove_wc_reports() {
    if ( current_user_can( 'shop_manager' ) ) {
        remove_submenu_page( 'woocommerce', 'wc-reports' );
        remove_submenu_page( 'woocommerce', 'wc-settings' );
        remove_submenu_page( 'woocommerce', 'wc-status' );
        remove_submenu_page( 'woocommerce', 'wc-marketing' );
        remove_menu_page( 'wc-admin&path=/analytics/overview' );
        remove_menu_page( 'wc-admin&path=/marketing' );
        remove_submenu_page( 'woocommerce', 'wc-admin' );
    }
}
add_action('admin_menu', 'zorem_remove_wc_reports', 110);

// add_filter( 'woocommerce_admin_features', function( $features ) {
//     /**
//      * Filter list of features and remove those not needed     *
//      */

//      if ( current_user_can( 'shop_manager' ) ) {
//         return array_values(
//             array_filter( $features, function($feature) {
//                 return $feature !== 'marketing';
//             } ) 
//         );
//      }
    
// } );


function plt_hide_all_in_one_wp_migration_menus() {

    if ( current_user_can( 'shop_manager' ) ) {
        //Hide "All-in-One WP Migration".
        remove_menu_page('ai1wm_export');
        //Hide "All-in-One WP Migration → Export".
        remove_submenu_page('ai1wm_export', 'ai1wm_export');
        //Hide "All-in-One WP Migration → Import".
        remove_submenu_page('ai1wm_export', 'ai1wm_import');
        //Hide "All-in-One WP Migration → Backups 0".
        remove_submenu_page('ai1wm_export', 'ai1wm_backups');
        //Hide "All-in-One WP Migration → Schedules".
        remove_submenu_page('ai1wm_export', 'ai1wm_schedules');

        // Hide Notifications menu.
        remove_menu_page('edit.php?post_type=notifications');
        
        // Hide specific Clockin Options page.
        remove_menu_page('clockin-options');

        remove_menu_page( 'admin.php?page=clockin-options' );

        remove_menu_page( 'edit.php?post_type=firebase_token');

        remove_menu_page('locations-options');
    }

	
}

add_action('admin_menu', 'plt_hide_all_in_one_wp_migration_menus', 11);



function wpse287823_remove_menu_items() {

    
    remove_menu_page( 'admin.php?page=wc-admin' );
        //Hide "Payments".
	remove_menu_page('wc-admin&path=/wc-pay-welcome-page');
	//Hide "Tools → Scheduled Actions".
	remove_submenu_page('tools.php', 'action-scheduler');

	//Hide "WooCommerce".
	remove_menu_page('woocommerce');
	//Hide "WooCommerce → Home".
	remove_submenu_page('woocommerce', 'wc-admin');
	//Hide "WooCommerce → Orders".
	// remove_submenu_page('woocommerce', 'wc-orders');
	//Hide "WooCommerce → Customers".
	remove_submenu_page('woocommerce', 'wc-admin&path=/customers');
	//Hide "WooCommerce → Reports".
	remove_submenu_page('woocommerce', 'wc-reports');
	//Hide "WooCommerce → Settings".
	remove_submenu_page('woocommerce', 'wc-settings');
	//Hide "WooCommerce → Status".
	remove_submenu_page('woocommerce', 'wc-status');
	//Hide "WooCommerce → Extensions".
	remove_submenu_page('woocommerce', 'wc-admin&path=/extensions');
	//Hide "WooCommerce →".
	remove_submenu_page('woocommerce', 'wc-addons');

    



// 	//Hide "Analytics".
	remove_menu_page('wc-admin&path=/analytics/overview');
	//Hide "Analytics → Overview".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/overview');
	//Hide "Analytics → Products".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/products');
	//Hide "Analytics → Revenue".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/revenue');
	//Hide "Analytics → Orders".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/orders');
	//Hide "Analytics → Variations".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/variations');
	//Hide "Analytics → Categories".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/categories');
	//Hide "Analytics → Coupons".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/coupons');
	//Hide "Analytics → Taxes".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/taxes');
	//Hide "Analytics → Downloads".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/downloads');
	//Hide "Analytics → Stock".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/stock');
	//Hide "Analytics → Settings".
	remove_submenu_page('wc-admin&path=/analytics/overview', 'wc-admin&path=/analytics/settings');

	//Hide "Marketing".
	remove_menu_page('woocommerce-marketing');
	//Hide "Marketing → Overview".
	remove_submenu_page('woocommerce-marketing', 'admin.php?page=wc-admin&path=/marketing');
	//Hide "Marketing → Coupons".
	remove_submenu_page('woocommerce-marketing', 'edit.php?post_type=shop_coupon');
}
add_action( 'admin_menu', 'wpse287823_remove_menu_items' );

// Customize the edit post link and retrieve post data
function customize_post_edit_link($link, $post_id) {
    global $current_screen;

    if ( is_admin() && $current_screen && $current_screen->post_type === 'booking' ) {
        // Get the post object
        $post = get_post($post_id);

        // Check if the post object is valid
        if ($post instanceof WP_Post) {
            // Retrieve custom fields
            $order_id = get_field('order_id', $post_id);
            $product_id = get_field('product_id', $post_id);

            // Construct custom edit link
            if ($order_id) {
                $edit_url = 'admin.php?page=wc-orders&action=edit&id=' . $order_id;
                return $edit_url;
            }
        }
    }

    // If not 'booking' post type or other conditions not met, return the default link
    return $link;
}

// Hook into the get_edit_post_link filter
add_filter('get_edit_post_link', 'customize_post_edit_link', 10, 2);


function customize_post_row_actions($actions, $post) {
    // Check if the post type is 'booking' or 'shop_order' (WooCommerce order)
    if ($post->post_type == 'booking' || $post->post_type == 'shop_order') {
        // Ensure current user can delete the post
       
        if (current_user_can('delete_post', $post->ID)) {
            // Add 'Trash' action link for orders that are not already trashed
            if ($post->post_status !== 'trash') {
                $actions['trash'] = '<a href="' . wp_nonce_url(admin_url('admin.php?action=delete&booking_id=' . $post->ID.'&post=' . $post->post_name), 'delete-post_' . $post->post_name) . '" class="submitdelete" aria-label="' . esc_attr(sprintf(__('Move %s to the Trash'), $post->post_title)) . '">' . __('Trash') . '</a>';
            } else {
                // Replace existing 'Restore' action link with custom implementation
                $actions['restore'] = '<a href="' . wp_nonce_url(admin_url('post.php?action=custom_restore&post=' . $post->ID), 'custom-restore-order_' . $post->ID) . '">' . __('Restore') . '</a>';
            }
        }
    }
    return $actions;
}

// Hook into the post_row_actions filter to customize actions for 'booking' and 'shop_order' post types
add_filter('post_row_actions', 'customize_post_row_actions', 10, 2);

// Handle deletion of WooCommerce order
add_action('admin_action_delete', 'custom_delete_wc_order');
function custom_delete_wc_order() {
    // Check if it's a WooCommerce order being deleted
    if (isset($_GET['post']) && $_GET['action'] === 'delete' && get_post_type($_GET['post']) === 'shop_order_placehold' && wp_verify_nonce($_REQUEST['_wpnonce'], 'delete-post_' . $_GET['post'])) {

        // Check user capabilities
        if (!current_user_can('delete_post', $_GET['post'])) {
            wp_die(__('You are not allowed to delete this order.'));
        }

        $order = wc_get_order($_GET['post']);

         // Check if order object exists and delete it
         if ($order instanceof WC_Order) {
            wp_trash_post( $_GET['booking_id'] );
            // Delete the order
            $order->delete(false); // Set to true if you want to move it to trash, false to force delete
            wp_redirect(admin_url('edit.php?post_type=booking'));
            exit;
        } else {
            wp_die(__('Error occurred while deleting order.'));
        }
    }
}

// Handle bulk action to move orders to trash
add_action('admin_action_trash_bulk_orders', 'custom_trash_bulk_orders');
function custom_trash_bulk_orders() {

    // Get selected orders to delete
    $order_ids = isset($_REQUEST['post']) ? $_REQUEST['post'] : array();

    
    // Check user capabilities
    if (!current_user_can('delete_posts')) {
        wp_die(__('You are not allowed to delete orders.'));
    }

   

    // Move each order to trash
    foreach ($order_ids as $post_id) {
        if ($post_id && get_post_type($post_id) === 'booking') {
            $order_id = get_post($post_id)->post_name;
            $order = wc_get_order($order_id);

            // Check if order object exists and delete it
            if ($order instanceof WC_Order) {
                wp_trash_post( $post_id );
                // Delete the order
                $order->delete(false);
            }
        }
    }

    wp_redirect(admin_url('edit.php?post_type=booking'));
    exit;
}


// Hook into bulk action handler
add_action('load-edit.php', 'custom_handle_bulk_actions');
function custom_handle_bulk_actions() {
    // Check if current screen is the shop_order post type
    $screen = get_current_screen();
    if ($screen->post_type !== 'booking' || !isset($_REQUEST['action'])) {
        return;
    }

    // Check if the bulk action is trash
    if ($_REQUEST['action'] === 'trash' && isset($_REQUEST['post'])) {
        // Trigger bulk action to move orders to trash
        do_action('admin_action_trash_bulk_orders');
    }
}

// Handle custom restore action for WooCommerce orders
add_action('admin_action_custom_restore', 'custom_restore_order');
function custom_restore_order() {

    // Check user capabilities
    if (!current_user_can('delete_post', $_GET['post'])) {
        wp_die(__('You are not allowed to restore this order.'));
    }

    $order_id = filter_var(get_post($_GET['post'])->post_title, FILTER_SANITIZE_NUMBER_INT);
    $order = wc_get_order($order_id);
    if ($order) {
       
        $new_status = 'wc-ayala_approved';
        $order->update_status($new_status);
    }
    // Restore the order (customize as needed)
    $restored = wp_untrash_post($_GET['post']);

    // Change status to 'publish' if restore is successful
    if ($restored) {
        $order_data = array(
            'ID' => $_GET['post'],
            'post_status' => 'publish',
        );
        wp_update_post($order_data);
    }

    wp_redirect(admin_url('edit.php?post_type=booking'));
    exit;
}

// Hook into post actions to handle custom restore action
add_action('admin_action_custom_restore', 'custom_handle_custom_restore_action');
function custom_handle_custom_restore_action() {
    // Check if action is custom_restore and post is set
    if ($_GET['action'] === 'custom_restore' && isset($_GET['post'])) {
        // Trigger custom restore action for the order
        do_action('admin_action_custom_restore');
    }
}

// Add custom column to post list
function custom_post_columns($columns) {
    global $current_screen;
    if(is_admin()) {
        if ( $current_screen->post_type == 'booking' ) {
            $columns['order_status'] = 'Order Status'; // Add your custom column name
            $columns['date_range'] = 'Date Range'; // Add your custom column name
            $columns['room_name'] = 'Meeting Room'; // Add your custom column name
            $columns['customer'] = 'Customer'; // Add your custom column name
        }}
        return $columns;

}
add_filter('manage_posts_columns', 'custom_post_columns');

// Populate custom column with data
function custom_column_data($column, $post_id) {

    global $current_screen;
    if(is_admin()) {
        if ( $current_screen->post_type == 'booking' ) {
            $order_id = get_field('order_id', $post_id);
            $product_id = get_field('product_id', $post_id);
            $orders = wc_get_order($order_id);
        
            if ($column == 'order_status') {
                // Replace 'your_acf_field_key' with your actual ACF field key
                $order_status = get_field('order_status', $post_id);
        
                echo '<mark class="order-status status-ayala_new_order tips"><span>'.$order_status.'</span></mark>';
            }

            if ($column == 'date_range') {
                $checkin = get_field('checkin', $post_id);
                $checkout = get_field('checkout', $post_id);
        
                echo 'Booked<br />'.date('Y/m/d \a\t h:i a', $checkin). ' - ' . date('Y/m/d \a\t h:i a', $checkout);
            }

            if ($column == 'room_name') {
                $quantity = get_field('quantity', $post_id);
                $booking_room = get_field('booking_room', $post_id);
				echo $quantity .' × <a>'. $booking_room .'</a><br />';
            }

            if ($column == 'customer') {
                $customer = get_field('customer', $post_id);
        
                echo $customer;
            }
        }
    }
   
}
add_action('manage_posts_custom_column', 'custom_column_data', 10, 2);

// Customize the post title in the post list
function customize_post_title($title, $post_id) {
    global $current_screen;
    if(is_admin()) {
        if ( $current_screen->post_type == 'booking' ) {
            $order_id = get_field('order_id', $post_id);
            $orders = wc_get_order($order_id);
            // Access billing address
            $billing_email = get_post_meta($order_id, '_billing_email', true);
    
            // Access order items
            $order_items = $orders->get_items();
            $last_name = get_post_meta($order_id)['_billing_last_name'][0];
            $first_name = get_post_meta($order_id)['_billing_first_name'][0];
            return $title .' '. $first_name.' '.$last_name;
        }
        return $title;
    }
    
}

// Hook into the_title filter
//add_filter('the_title', 'customize_post_title', 10, 2);


// Remove "View" link from post list
function disable_view_post_link($actions, $post) {
    global $current_screen;
    if(is_admin()) {
        if ( $current_screen->post_type == 'booking' ) {
            unset($actions['view']);
            return $actions;
        }
        return $actions;
    }
    
}
add_filter('post_row_actions', 'disable_view_post_link', 10, 2);


function modify_booking_menu() {
    global $submenu;

    // Remove the "Add New" link from the submenu
    if (isset($submenu['edit.php?post_type=booking'][10])) {
        unset($submenu['edit.php?post_type=booking'][10]);
    }
}

add_action('admin_menu', 'modify_booking_menu');
add_filter( 'admin_url', 'wpse_271288_change_add_new_link_for_post_type', 10, 2 );
function wpse_271288_change_add_new_link_for_post_type( $url, $path ){
    if( $path === 'post-new.php?post_type=booking' ) {
        $url = home_url().'/meeting-rooms/';
    }
    return $url;
}


// Add custom filter to the admin list page for the "booking" post type based on custom meta
function booking_admin_meta_filter() {
    global $typenow;

    // Check if the current post type is "booking"
    if ($typenow == 'booking') {
        $status_key = 'order_status';
        $status_value = isset($_GET[$status_key]) ? sanitize_text_field($_GET[$status_key]) : '';

        $room_key = 'booking_room';
        $room_value = isset($_GET[$room_key]) ? sanitize_text_field($_GET[$room_key]) : '';

        $customer_key = 'customer';
        $customer_value = isset($_GET[$customer_key]) ? sanitize_text_field($_GET[$customer_key]) : '';

        $checkin_key = 'checkin';
        $checkin_value = isset($_GET[$checkin_key]) ? sanitize_text_field($_GET[$checkin_key]) : '';

        $checkout_key = 'checkout';
        $checkout_value = isset($_GET[$checkout_key]) ? sanitize_text_field($_GET[$checkout_key]) : '';

        echo '<select name="' . $status_key . '" class="postform">';
        echo '<option value="">' . esc_html__('Filter by Status', 'textdomain') . '</option>';

        // Replace 'your_custom_query' with your custom query to get distinct meta values
        $distinct_status_values = get_booking_meta_values($status_key);

        foreach ($distinct_status_values as $value) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($status_value, $value, false) . '>' . esc_html($value) . '</option>';
        }

        echo '</select>';

        echo '<select name="' . $room_key . '" class="postform">';
        echo '<option value="">' . esc_html__('Filter by Room', 'textdomain') . '</option>';

        // Replace 'your_custom_query' with your custom query to get distinct meta values
        $distinct_room_values = get_booking_meta_values($room_key);

        foreach ($distinct_room_values as $value) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($room_value, $value, false) . '>' . esc_html($value) . '</option>';
        }

        echo '</select>';

        echo '<select name="' . $customer_key . '" class="postform">';
        echo '<option value="">' . esc_html__('Filter by Customer', 'textdomain') . '</option>';

        // Replace 'your_custom_query' with your custom query to get distinct meta values
        $distinct_customer_values = get_booking_meta_values($customer_key);

        foreach ($distinct_customer_values as $value) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($customer_value, $value, false) . '>' . esc_html($value) . '</option>';
        }

        echo '</select>';

        echo '<label for="' . $checkin_key . '">Check-in Date:</label>';
        echo '<input type="date" name="' . $checkin_key . '" value="' . esc_attr($checkin_value) . '" />';

        echo '<label for="' . $checkout_key . '">Check-out Date:</label>';
        echo '<input type="date" name="' . $checkout_key . '" value="' . esc_attr($checkout_value) . '" />';
    }
}

// Hook into the restrict_manage_posts action
add_action('restrict_manage_posts', 'booking_admin_meta_filter');

// Modify the query based on the custom meta filters
function booking_filter_by_meta($query) {
    global $typenow;

    // Check if the current post type is "booking" and the custom fields are set
    if ($typenow == 'booking' && (isset($_GET['order_status']) || isset($_GET['booking_room']) || isset($_GET['customer']) || isset($_GET['checkin']) || isset($_GET['checkout']))) {
        $status_key = 'order_status';
        $status_value = sanitize_text_field($_GET[$status_key]);

        $room_key = 'booking_room';
        $room_value = sanitize_text_field($_GET[$room_key]);

        $customer_key = 'customer';
        $customer_value = sanitize_text_field($_GET[$customer_key]);

        $checkin_key = 'checkin';
        $checkin_value = isset($_GET[$checkin_key]) ? strtotime(sanitize_text_field($_GET[$checkin_key])) : '';

        $checkout_key = 'checkout';
        $checkout_value = isset($_GET[$checkout_key]) ? strtotime(sanitize_text_field($_GET[$checkout_key])) : '';

        if ($status_value) {
            $query->set('meta_key', $status_key);
            $query->set('meta_value', $status_value);
        }

        if ($room_value) {
            $query->set('meta_key', $room_key);
            $query->set('meta_value', $room_value);
        }

        if ($customer_value) {
            $query->set('meta_key', $customer_key);
            $query->set('meta_value', $customer_value);
        }

        if ($checkin_value) {
            $query->set('meta_query', array(
                array(
                    'key'     => $checkin_key,
                    'value'   => $checkin_value,
                    'compare' => '<=',
                    'type'    => 'NUMERIC',
                ),
            ));
        }

        if ($checkout_value) {
            $query->set('meta_query', array(
                array(
                    'key'     => $checkout_key,
                    'value'   => $checkout_value,
                    'compare' => '>=',
                    'type'    => 'NUMERIC',
                ),
            ));
        }

        // Combine check-in and check-out conditions using the relation 'AND'
        $query->set('meta_query', $query->get('meta_query'));
        $query->set('relation', 'AND');
    }
}

// Hook into the pre_get_posts action
add_action('pre_get_posts', 'booking_filter_by_meta');


// Hook into the pre_get_posts action
add_action('pre_get_posts', 'booking_filter_by_meta');

// Helper function to retrieve distinct meta values for the dropdown
function get_booking_meta_values($key) {
    global $wpdb;

    $query = $wpdb->prepare("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = %s", $key);
    $results = $wpdb->get_col($query);

    return $results;
}

function add_shop_manager_admin_styles() {
    // Check if the current user has the "shop_manager" role
    if (current_user_can('shop_manager')) {
        // Replace 'path/to/shop-manager-styles.css' with the actual path to your CSS file
        wp_enqueue_style('shop_manager_styles', get_stylesheet_directory_uri() . '/assets/css/shop-manager-styles.css');
    }
}

// Hook the function to the admin_enqueue_scripts action
add_action('admin_enqueue_scripts', 'add_shop_manager_admin_styles');


if (!function_exists('get_center_admin_emails')) {
    function get_center_admin_emails($order_id) {
        // Get an instance of the WC_Order object
        $order = wc_get_order( $order_id );
        // Array to store unique emails and display names
        $unique_emails = [];
        $seen_emails = [];
        if ( $order ) {
            // Loop through order items
            foreach ( $order->get_items() as $item_id => $item ) {
                // Get the product ID
                $product_id = $item->get_product_id();
                
                // Output the product ID
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
                            $email = $user->user_email;
                            $display_name = $user->display_name;
                
                            // Check if the email is not in the set of seen emails
                            if (!in_array($email, $seen_emails)) {
                                // Add the user details to the unique users array
                                $unique_emails[] = $email;
                                // Mark the email as seen
                                $seen_emails[] = $email;
                            }
                        }
                    }


                    $center_email_receiver = get_field('center_email_receiver',  $room_location->ID);

                    // Check for results
                    if (!empty($center_email_receiver)) {
                        // Loop through each user
                        foreach ($center_email_receiver as $center_email) {
                            $email = $center_email["email"];
                
                            // Check if the email is not in the set of seen emails
                            if (!in_array($email, $seen_emails)) {
                                // Add the user details to the unique users array
                                $unique_emails[] = $email;
                                // Mark the email as seen
                                $seen_emails[] = $email;
                            }
                        }
                    }

            }
        } 
        
        return $unique_emails;

    }
}

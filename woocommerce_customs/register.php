<?php 
//  include_once get_stylesheet_directory() . '/woocommerce_customs/register.php';

$sends_email_file = [
    'approved',
    'cancelled',
    'denied',
    'new_order',
    'paid',
    'pending_order',
    'pending_payment',
    'cancel_request',
    'denied_request',
    'approved_request',
];


foreach($sends_email_file as $file_name) {
    include_once get_stylesheet_directory() . '/woocommerce_customs/emails/email_'.$file_name.'.php';
}


function rename_submenu() {
    global $submenu;
    
    // Loop through each menu to find the one with the desired name
    foreach ($submenu as $parent_slug => $menu_items) {
        foreach ($menu_items as $index => $menu_item) {
            // Replace 'Menu Name' with the actual name of the submenu you want to rename
            if ($menu_item[0] === 'All Products') {
                $submenu[$parent_slug][$index][0] = 'All Meeting Rooms';
            }
        }
    }
}

add_action('admin_menu', 'rename_submenu');

function edit_page_title() {
    global $post, $title, $action, $current_screen;
    if( isset( $current_screen->post_type ) && $current_screen->post_type == 'product' ) {
        // / this is the new page title /
        $title = 'Meeting Rooms';     
		    
    } else if( isset( $current_screen->post_type ) && $current_screen->post_type == 'shop_order'){
        // $title = 'Bookings';  
    } else {
        $title = $title .' - ' .get_bloginfo('name');
    }
    return $title;  
}
add_filter( 'admin_title', 'edit_page_title' );

add_filter(  'gettext',  'dirty_translate'  );  
add_filter(  'ngettext',  'dirty_translate'  );
function dirty_translate( $translated ) {
	$words = array(
		// 'word to translate' => 'translation'
		'Products' => 'Meeting Rooms',
		// 'Orders' => 'Bookings',
		// 'WooCommerce' => 'Bookings',
		'Product name' => 'Meeting Room name',
		'Add new order' => 'Add new room booking',
	);
	$translated = str_ireplace(  array_keys($words),  $words,  $translated );
	return $translated;
}


add_action('init', 'wpse_74054_add_author_woocommerce', 999 );

function wpse_74054_add_author_woocommerce() {
    add_post_type_support( 'product', 'author' );
}


// remove order status except draft
function so_39252649_remove_processing_status( $statuses ){
    $statuses_to_remove = [
		'wc-processing', 
		'wc-on-hold',
		'wc-pending',
		'wc-completed',
		'wc-refunded',
		'wc-failed',
		'wc-cancelled',
	];

	foreach ($statuses_to_remove as $status) {
		if (isset($statuses[$status])) {
			unset($statuses[$status]);
		}
	}
    return $statuses;
}
add_filter( 'wc_order_statuses', 'so_39252649_remove_processing_status' );

// Prevent 'draft' status from being assigned to orders
function so_39252649_prevent_draft_status( $status, $order ) {
    if ( $status === 'wc-draft' ) {
        return 'wc-ayala_approved'; // or any other default status you prefer
    }
    return $status;
}
add_filter( 'woocommerce_order_status_changed', 'so_39252649_prevent_draft_status', 10, 2 );


// Remove the draft status from WooCommerce order statuses
function so_39252649_remove_draft_status( $statuses ){
    $status_to_remove = 'wc-draft';
    
    if (isset($statuses[$status_to_remove])) {
        unset($statuses[$status_to_remove]);
    }

    return $statuses;
}
add_filter( 'wc_order_statuses', 'so_39252649_remove_draft_status' );

// Add custom order statuses dynamically
function register_custom_order_statuses($statuses) {
    foreach ($statuses as $status => $label) {
        register_post_status($status, array(
            'label'                     => $label,
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop("$label <span class='count'>(%s)</span>", "$label <span class='count'>(%s)</span>")
        ));
    }
}

// Usage: Define your custom statuses and call the registration function
$custom_statuses = array(
    // 'wc-ayala_new_order'  => 'New Order',
    'wc-ayala_approved'   => 'User Booking Confirmation',
    // 'wc-ayala_denied'   => 'Denied',
    // 'wc-pending_payment'   => 'Pending Payment',
    // 'wc-ayala_paid'   => 'Paid',
    'wc-ayala_cancelled'   => 'Admin Cancelled Booking',
    'wc-cancel_request'   => 'User Cancellation Request',
    'wc-denied_request'   => 'Declined Cancellation Request',
    'wc-approved_request'   => 'Approved Cancellation Request',
    // 'wc-pending_order'   => 'Pending Order',
    // 'wc-ayala_refunded'   => 'Refund Order',
    // Add more statuses as needed
);

add_action('init', function () use ($custom_statuses) {
    register_custom_order_statuses($custom_statuses);
});

// Add custom order statuses to the list of order statuses
add_filter('wc_order_statuses', 'custom_order_status');
function custom_order_status($order_statuses) {
    // Merge the custom statuses with the existing order statuses
    global $custom_statuses;
    $order_statuses = array_merge($order_statuses, $custom_statuses);

    return $order_statuses;
}



// ROOM OWNERS TO GET ONLY ORDERS WITH THEIR PRODUCT
add_filter( 'manage_woocommerce_page_wc-orders_columns', 'order_admin_table_custom_column' );
function order_admin_table_custom_column( $columns ){
	$columns[ 'room_column' ] = 'Meeting Room';
	$columns[ 'author_column' ] = 'Location';
	return $columns;
}

add_action( 'manage_woocommerce_page_wc-orders_custom_column', 'misha_populate_orders_column', 25, 2 );
function misha_populate_orders_column( $column_name, $order ){ // WC_Order object is available as $order variable here
	
	if( 'room_column' === $column_name ) {
		$items = $order->get_items();
		if( ! is_wp_error( $items ) ) {
			foreach( $items as $item ) {
 				echo $item[ 'quantity' ] .' × <a href="' . get_edit_post_link( $item[ 'product_id' ] ) . '">'. $item[ 'name' ] .'</a><br />';
			}
		}
	}

    if( 'author_column' === $column_name ) {
		$items = $order->get_items();
		if( ! is_wp_error( $items ) ) {
			foreach( $items as $item ) {
				// $author_id = get_post_field('post_author', $item[ 'product_id' ]);
 				// echo get_the_author_meta('display_name', $author_id).'<br />';

                // PRINT AUTHOR ID of the Product from the order.
                echo get_post_meta( $order->get_id(), 'author_id', true );
			}
		}
	}
}

function filter_orders_by_user_id($query) {
    global $pagenow;

    // $user_id = 20000;
    // $query->set('meta_key', 'author_id');
    // $query->set('meta_value', $user_id);
    // $query->set('meta_compare', '=');
    // Check if we are in the admin and working with the 'edit.php' page for the 'shop_order' post type
    // if (is_admin() && $pagenow == 'edit.php' && isset($query->query['post_type']) && $query->query['post_type'] == 'shop_order') {

    //     // Change '123' to the ID of the specific user you want to filter orders for
    //     $user_id = 123;

    //     $query->set('meta_key', '_customer_user');
    //     $query->set('meta_value', $user_id);
    //     $query->set('meta_compare', '=');
    // }
}
// add_action('parse_query', 'filter_orders_by_user_id');

add_filter('gettext_with_context', 'rename_woocommerce_admin_text', 100, 4 );
function rename_woocommerce_admin_text( $translated, $text, $context, $domain ) {
    if( $domain == 'woocommerce' && $context == 'Admin menu name' && $translated == 'Orders' ) {
        // Here your custom text
        // $translated = 'Bookings';
    }
    return $translated;
}


add_action('before_delete_post', 'custom_delete_woocommerce_order_on_booking_trash');

function custom_delete_woocommerce_order_on_booking_trash($post_id) {
    // Check if the post being deleted is of the 'booking' custom post type
    if (get_post_type($post_id) == 'booking') {
        wp_die();
        // Retrieve the associated WooCommerce order ID from the post meta
        $order_id = get_post_meta($post_id, 'woocommerce_order_id', true);
        
        // Check if the order ID exists
        if ($order_id) {
            // Load the order object
            $order = wc_get_order($order_id);
            
            // Check if the order exists
            if ($order) {
                // Permanently delete the order
                $order->delete(true);
            }
        }
    }
}



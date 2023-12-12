<?php

// Add a custom user role (Center Admin)
function add_center_admin_role() {
    // Parameters for the new role
    $role_name = 'center_admin';
    $display_name = 'Center Admin';
    $capabilities = array(
        'read' => true,
        'edit_posts' => true,
        'delete_posts' => true,
        'read_location' => true,
        'read_private_locations' => true,
        'edit_location' => true,
        'edit_locations' => true,
        'edit_others_locations' => true,
        'publish_locations' => true,
        'read' => true,
        'delete_location' => true,
        'delete_locations' => true,
        'delete_others_locations' => true,
        'edit_published_locations' => true,
        'delete_published_locations' => true,
        'manage_woocommerce' => true,
        'view_woocommerce_reports' => true,
        'edit_products' => true,
        'read_shop_orders' => true,
        // Add other capabilities as needed
    );

    // Add the new role
    add_role($role_name, $display_name, get_role( 'admin' )->$capabilities);
}

// Hook the function to the 'init' action
add_action('init', 'add_center_admin_role');


// Add capabilities for the Center Admin role, including WooCommerce capabilities
function add_location_capabilities_to_center_admin() {
    $role = get_role('center_admin');

    // Add capabilities for the Location post type
    $location_capabilities = array(
        'read_location',
        'read_private_locations',
        'edit_location',
        'edit_locations',
        'edit_others_locations',
        'publish_locations',
        'read',
        'delete_location',
        'delete_locations',
        'delete_others_locations',
        'edit_published_locations',
        'delete_published_locations',
        // Add other capabilities specific to the Location post type
    );

    // Add capabilities for WooCommerce
    $woocommerce_capabilities = array(
        'manage_woocommerce',
        'view_woocommerce_reports',
        'edit_products',
        'read_shop_orders',
        // Add other WooCommerce capabilities as needed
    );

    // Merge the capabilities arrays
    $all_capabilities = array_merge($location_capabilities, $woocommerce_capabilities);

    // Add all capabilities to the 'center_admin' role
    foreach ($all_capabilities as $cap) {
        $role->add_cap($cap);
    }
}

// Hook the function to the 'admin_init' action
add_action('admin_init', 'add_location_capabilities_to_center_admin');



// Modify the query to show only locations added by the current Center Admin user
function filter_locations_by_center_admin($query) {
    // Check if the user is logged in and has the Center Admin role
    if (is_user_logged_in() && current_user_can('center_admin')) {
        $user_id = get_current_user_id();

        // Modify the query to show only locations added by the current user
        $query->set('author', $user_id);
    }
}

// Hook the function to the 'pre_get_posts' action
add_action('pre_get_posts', 'filter_locations_by_center_admin');


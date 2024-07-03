<?php
function create_resource_posts() {
    $labels = array(
        'name'               => _x( 'Locations', 'post type general name', 'location' ),
        'singular_name'      => _x( 'Location', 'post type singular name', 'location' ),
        'menu_name'          => _x( 'Locations', 'admin menu', 'location' ),
        'name_admin_bar'     => _x( 'Location', 'add new on admin bar', 'location' ),
        'add_new'            => _x( 'Add New', 'Location', 'location' ),
        'add_new_item'       => __( 'Add New Location', 'location' ),
        'new_item'           => __( 'New Location', 'location' ),
        'edit_item'          => __( 'Edit Location', 'location' ),
        'view_item'          => __( 'View Location', 'location' ),
        'all_items'          => __( 'All Locations', 'location' ),
        'search_items'       => __( 'Search Locations', 'location' ),
        'parent_item_colon'  => __( 'Parent Locations:', 'location' ),
        'not_found'          => __( 'No Locations found.', 'location' ),
        'not_found_in_trash' => __( 'No Locations found in Trash.', 'location' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'location' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-location-alt', // Icon name or URL
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author' ),
        'taxonomies'         => array( 'post_tag' ), // Add 'post_tag' taxonomy for tags
        'show_in_rest'       => true, // Enable REST API support
        'rest_base'          => 'locations', // Customize the REST API route
        'rest_controller_class' => 'WP_REST_Posts_Controller', // Use the default controller for posts
        'author'             => true, // Enable support for post authors
    );

    register_post_type( 'location', $args );
}
add_action( 'init', 'create_resource_posts' );


function create_booking_posts() {
    $labels = array(
        'name'               => _x( 'Bookings', 'post type general name', 'booking' ),
        'singular_name'      => _x( 'Booking', 'post type singular name', 'booking' ),
        'menu_name'          => _x( 'Bookings', 'admin menu', 'booking' ),
        'name_admin_bar'     => _x( 'Booking', 'add new on admin bar', 'booking' ),
        'add_new_item'       => __( 'Add New Booking', 'booking' ),
        'new_item'           => __( 'New Booking', 'booking' ),
        'edit_item'          => __( 'Edit Booking', 'booking' ),
        'view_item'          => __( 'View Booking', 'booking' ),
        'all_items'          => __( 'All Bookings', 'booking' ),
        'search_items'       => __( 'Search Bookings', 'booking' ),
        'parent_item_colon'  => __( 'Parent Bookings:', 'booking' ),
        'not_found'          => __( 'No Bookings found.', 'booking' ),
        'not_found_in_trash' => __( 'No Bookings found in Trash.', 'booking' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'bookings' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-tagcloud',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'delete_post' ),
        'show_in_rest'       => true,
        'rest_base'          => 'bookings',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'author'             => true,
    );

    register_post_type( 'booking', $args );
}
add_action( 'init', 'create_booking_posts' );


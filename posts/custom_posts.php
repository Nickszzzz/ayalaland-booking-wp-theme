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
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'taxonomies'         => array(), // Remove the 'category' taxonomy
        'show_in_rest'       => true, // Enable REST API support
        'rest_base'          => 'locations', // Customize the REST API route
        'rest_controller_class' => 'WP_REST_Posts_Controller', // Use the default controller for posts
    );

    register_post_type( 'location', $args );
}
add_action( 'init', 'create_resource_posts' );

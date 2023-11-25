<?php 

add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'room',
            'title'             => __('Room'),
            'description'       => __('A custom room block.'),
            'render_template'   => 'blocks/room/room.php',
            'category'          => 'formatting',
            'icon'              => 'schedule',
            'keywords'          => array( 'room', 'quote' ),
        ));
    }

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'featured_locations',
            'title'             => __('Featured Locations'),
            'description'       => __('A custom featured_locations block.'),
            'render_template'   => 'blocks/featured_locations/featured_locations.php',
            'category'          => 'formatting',
            'icon'              => 'location',
            'keywords'          => array( 'featured_locations', 'quote' ),
        ));
    }

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'rooms',
            'title'             => __('Rooms'),
            'description'       => __('A custom rooms block.'),
            'render_template'   => 'blocks/rooms/rooms.php',
            'category'          => 'formatting',
            'icon'              => 'schedule',
            'keywords'          => array( 'rooms', 'quote' ),
        ));
    }

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'locations',
            'title'             => __('Locations'),
            'description'       => __('A custom locations block.'),
            'render_template'   => 'blocks/locations/locations.php',
            'category'          => 'formatting',
            'icon'              => 'location-alt',
            'keywords'          => array( 'locations', 'quote' ),
        ));
    }

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'location_lists',
            'title'             => __('Location Lists'),
            'description'       => __('A custom location_lists block.'),
            'render_template'   => 'blocks/location_lists/location_lists.php',
            'category'          => 'formatting',
            'icon'              => 'location-alt',
            'keywords'          => array( 'location_lists', 'quote' ),
        ));
    }

}
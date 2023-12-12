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

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'room_details',
            'title'             => __('Room Details'),
            'description'       => __('A custom room details block.'),
            'render_template'   => 'blocks/room_details/room_details.php',
            'category'          => 'formatting',
            'icon'              => 'welcome-widgets-menus',
            'keywords'          => array( 'room_details', 'quote' ),
        ));
    }

     // Check function exists.
     if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'room_details_header',
            'title'             => __('Room Details Header'),
            'description'       => __('A custom room details header block.'),
            'render_template'   => 'blocks/room_details_header/room_details_header.php',
            'category'          => 'formatting',
            'icon'              => 'welcome-widgets-menus',
            'keywords'          => array( 'room_details_header', 'quote' ),
        ));
    }

     // Check function exists.
     if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'room_details_content',
            'title'             => __('Room Details Content'),
            'description'       => __('A custom room details content block.'),
            'render_template'   => 'blocks/room_details_content/room_details_content.php',
            'category'          => 'formatting',
            'icon'              => 'welcome-widgets-menus',
            'keywords'          => array( 'room_details_content', 'quote' ),
        ));
    }

     // Check function exists.
     if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'room_details_map',
            'title'             => __('Room Details Map'),
            'description'       => __('A custom room details map block.'),
            'render_template'   => 'blocks/room_details_map/room_details_map.php',
            'category'          => 'formatting',
            'icon'              => 'welcome-widgets-menus',
            'keywords'          => array( 'room_details_map', 'quote' ),
        ));
    }

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'mobi_calendar',
            'title'             => __('Room Calendar'),
            'description'       => __('A custom Room Calendar block.'),
            'render_template'   => 'blocks/mobi_calendar/mobi_calendar.php',
            'category'          => 'formatting',
            'icon'              => 'welcome-widgets-menus',
            'keywords'          => array( 'mobi_calendar', 'quote' ),
        ));
    }

     // Check function exists.
     if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'checkout',
            'title'             => __('Check Out'),
            'description'       => __('A custom Check Out block.'),
            'render_template'   => 'blocks/checkout/checkout.php',
            'category'          => 'formatting',
            'icon'              => 'welcome-widgets-menus',
            'keywords'          => array( 'checkout', 'quote' ),
        ));
    }

}
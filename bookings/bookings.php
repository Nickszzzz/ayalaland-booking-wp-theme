<?php


// Hook into the admin menu to add Payments options page
add_action('admin_menu', 'bookings_options_menu');

function bookings_options_menu() {
    // Add a top-level menu page under 'Settings'
    add_menu_page(
        'Bookings',    // Page title
        'Bookings',            // Menu title
        'edit_posts',      // Capability required to access menu
        'bookings_options',    // Menu slug
        'bookings_options_page', // Callback function to display page content
        'dashicons-calendar',     // Icon URL or Dashicon class
        20                     // Position
    );
}

function bookings_options_page() {
    $current_user_id = get_current_user_id();
    $location_id = get_field('location', 'user_' . $current_user_id);
    $site_url = get_site_url();

    // Output field
    echo '<input type="hidden" id="location_id" name="location_id" value="'.esc_html($location_id).'">';
    echo '<input type="hidden" id="site_url" name="site_url" value="'.esc_html($site_url).'">';
    echo '<input type="hidden" id="current_user_id" name="current_user_id" value="'.esc_html($current_user_id).'">';
    ?>
    <div id="root"></div>
    </div>
    <?php
}

function enqueue_custom_scripts_styles_bookings($hook_suffix) {
    if ($hook_suffix === 'toplevel_page_bookings_options') {
        // Enqueue your CSS file
        wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/bookings/index-D7Hq4R-6.css');
        // Enqueue your JavaScript file
        wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/bookings/index-C8DQaAUL.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts_styles_bookings');

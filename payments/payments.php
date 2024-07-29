<?php


// Hook into the admin menu to add Payments options page
add_action('admin_menu', 'payments_options_menu');

function payments_options_menu() {
    // Add a top-level menu page under 'Settings'
    add_menu_page(
        'Payments',    // Page title
        'Payments',            // Menu title
        'edit_posts',      // Capability required to access menu
        'payments_options',    // Menu slug
        'payments_options_page', // Callback function to display page content
        'dashicons-money-alt',     // Icon URL or Dashicon class
        20                     // Position
    );
}

function payments_options_page() {
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

function enqueue_custom_scripts_styles_payments($hook_suffix) {
    if ($hook_suffix === 'toplevel_page_payments_options') {
        // Enqueue your CSS file
        wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/payments/index-B4KolLmO.css');
        // Enqueue your JavaScript file
        wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/payments/index-BLrBcPxU.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts_styles_payments');

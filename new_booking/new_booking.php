<?php

// Add meta box callback function
function add_custom_meta_box() {
    add_meta_box(
        'custom_meta_box',          // Unique ID
        ' ',          // Box title
        'render_custom_meta_box',   // Callback function to render the meta box contents
        'booking',                  // Post type
        'normal',                   // Context (normal, side, advanced)
        'high'                      // Priority (high, default, low)
    );
}

// Function to render meta box contents
function render_custom_meta_box($post) {

    $current_user_id = get_current_user_id();
    $location_id = get_field('location', 'user_' . $current_user_id);
    $site_url = get_site_url();

    // Output field
    echo '<input type="hidden" id="location_id" name="location_id" value="'.esc_html($location_id).'">';
    echo '<input type="hidden" id="site_url" name="site_url" value="'.esc_html($site_url).'">';
    echo '<input type="hidden" id="current_user_id" name="current_user_id" value="'.esc_html($current_user_id).'">';
    echo '<div id="root"></div>';
}

// Hook into WordPress
add_action('add_meta_boxes', 'add_custom_meta_box');

function remove_publish_meta_box() {
    global $pagenow;
    
    // Check if we are on the correct screen
    if ($pagenow === 'post-new.php' || $pagenow === 'post.php') {
        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : (isset($_POST['post_type']) ? $_POST['post_type'] : '');
        
        if ('booking' == $post_type) {
            remove_meta_box('submitdiv', 'booking', 'side');
        }
    }
}
add_action('admin_head', 'remove_publish_meta_box');


function enqueue_custom_scripts_styles($hook) {
    // Check if we are on the add new booking page
    global $post_type;
    if ('booking' == $post_type && in_array($hook, array('post-new.php', 'post.php'))) {
        // Enqueue your CSS file
        wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/new_booking/index-DRf6RnTg.css');
        // Enqueue your JavaScript file
        wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/new_booking/index-73EmHj9Z.js', array(), null, true);
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_scripts_styles');

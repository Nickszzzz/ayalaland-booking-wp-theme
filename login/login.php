<?php


// Add custom styles and HTML to the login page
function custom_login_page_override() {
    // Output custom CSS to hide default login elements
    echo '<style>
        #login { display: none; }
    </style>';

    // Output custom HTML
    echo '<input type="hidden" id="site_url" name="site_url" value="'.home_url().'">';
    echo '<div id="root" ></div>';
}
add_action('login_head', 'custom_login_page_override');
add_action('login_footer', 'custom_login_page_override');

function enqueue_react_app_login_page() {
    // Enqueue your CSS file
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/login/index-DSDF9gH9.css');

    // Enqueue your JavaScript file
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/login/index-DI0vs6Fv.js', array(), null, true);
}
add_action('login_enqueue_scripts', 'enqueue_react_app_login_page');

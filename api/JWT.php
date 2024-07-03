<?php


// Add additional user data to the JWT response
add_filter('jwt_auth_token_before_dispatch', function ($data, $user) {
    // Include user roles in the token response
    $data['role'] = $user->roles[0]; // An array of roles
    $data['id'] = $user->ID; 
    $data['name'] = $user->display_name; 
    $data['email'] = $user->user_email; // Additional user data
    return $data;
}, 10, 2);

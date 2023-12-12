<?php
add_action( 'add_meta_boxes', 'bbloomer_order_meta_box', 10, 2  );
 
function bbloomer_order_meta_box($order) {
    add_meta_box( 'additional_information', 'Additional Informations', 'bbloomer_single_order_meta_box', 'woocommerce_page_wc-orders', 'advanced', 'high' );
}
 
function bbloomer_single_order_meta_box($order) {
    // global $post; // OPTIONALLY USE TO ACCESS ORDER POST

    $id = $order->get_id();
    $orders = wc_get_order($order->get_id());
    $first_item = current($orders->get_items());
    $product_id = $first_item->get_product_id();
    $location_id = get_field('room_description_location', $product_id)->ID;

    // Get all post meta for the order
    $all_meta = get_post_meta($id);

    // Assuming $location_id is the ID of the location post
    $location_id = 845; // Replace with the actual ID

    // Get the post author (user ID) of the location post
    $author_id = get_post_field('post_author', $location_id);

    $user_data = get_userdata($author_id);

    if ($user_data) {
        // Get user email
        $user_email = $user_data->user_email;
    
        // Get user roles (user type)
        $user_roles = $user_data->roles;
    
        // Assuming a user may have multiple roles, use the first role if available
        $user_type = !empty($user_roles) ? $user_roles[0] : 'No Role';
    
        // Now, $user_email contains the email, and $user_type contains the user type (role)
        
        // Example: Output the email and user type

    }

?>
<table>
    <thead>
        <tr>
            <th>Field</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($all_meta as $field => $values): ?>
        <tr>
            <td style="width: 50%;"><?php echo htmlspecialchars($field); ?></td>
            <td><?php echo htmlspecialchars($values[0]); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
    // echo 'Whatever HTML content'.$order;
}
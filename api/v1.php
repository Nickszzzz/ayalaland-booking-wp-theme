<?php 

// Add action for logged in users
add_action('wp_ajax_my_ajax_action', 'my_ajax_callback');
// Add action for non-logged-in users
add_action('wp_ajax_nopriv_my_ajax_action', 'my_ajax_callback');

function my_ajax_callback() {
    // Check if the term parameter is set
    if (isset($_GET['q'])) {
        $search_term = sanitize_text_field($_GET['q']);

        // Query product categories using the REST API
        $url = home_url().'/wp-json/wp/v2/locations?search=' . urlencode($search_term);

        $response = wp_remote_get($url);

        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);

            // Exclude categories with specific names
            $exclude_categories = array('uncategorized', 'booking');
            $filtered_data = array_filter($data, function ($category) use ($exclude_categories) {
                return !in_array(strtolower($category['title']['rendered']), $exclude_categories);
            });

            // Format the filtered data as needed for Select2
            $results = array();
            foreach ($filtered_data as $category) {
                $results[] = array(
                    'id'   => $category['id'],
                    'text' => $category['title']['rendered'],
                );
            }

            // Sort the results alphabetically based on the 'text' field
            usort($results, function ($a, $b) {
                return strcmp($a['text'], $b['text']);
            });

            wp_send_json($results);
        }
    }
    else {
        // Query product categories using the REST API
        $url = home_url().'/wp-json/wp/v2/locations';

        $response = wp_remote_get($url);

        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $data = json_decode($response['body'], true);

            // Exclude categories with specific names
            $exclude_categories = array('uncategorized', 'booking');
            $filtered_data = array_filter($data, function ($category) use ($exclude_categories) {
                return !in_array(strtolower($category['title']['rendered']), $exclude_categories);
            });

            // Format the filtered data as needed for Select2
            $results = array();
            foreach ($filtered_data as $category) {
                $results[] = array(
                    'id'   => $category['id'],
                    'text' => $category['title']['rendered'],
                );
            }

            // Sort the results alphabetically based on the 'text' field
            usort($results, function ($a, $b) {
                return strcmp($a['text'], $b['text']);
            });

            wp_send_json($results);
        }
    }

    wp_send_json([]);
}

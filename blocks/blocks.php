<?php 

add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'search filter',
            'title'             => __('Search Filter'),
            'description'       => __('A custom search filter block.'),
            'render_template'   => 'blocks/filter/search_filter.php',
            'category'          => 'formatting',
            'icon'              => 'search',
            'keywords'          => array( 'filter', 'quote' ),
        ));
    }
}
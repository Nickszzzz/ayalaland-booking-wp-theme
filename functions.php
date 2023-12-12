<?php
/**
 * WP Template 2024 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP Template 2024
 * @since WP Template 2024 1.0
 */

 // Add Custom ACF Blocks
include_once get_stylesheet_directory() . '/blocks/blocks.php';

 // Add Custom Metabox
 include_once get_stylesheet_directory() . '/metabox/orders.php';


// Add Shortcode
include_once get_stylesheet_directory() . '/posts/custom_posts.php';

 // Add API
 include_once get_stylesheet_directory() . '/api/v1.php';

 // Add Custom Logo
include_once get_stylesheet_directory() . '/theme_assets/login_logo.php';

 // Add Sitemap Shortcode
 include_once get_stylesheet_directory() . '/theme_assets/sitemap.php';


 add_action( 'after_setup_theme', 'twentytwentythree_support', 9999 );

 if ( ! function_exists( 'twentytwentythree_support' ) ) :
 
	 /**
	  * Sets up theme defaults and registers support for various WordPress features.
	  */
	 function twentytwentythree_support() {
 
		 // Add support for block styles.
		 add_theme_support( 'wp-block-styles' );
 
		 // Enqueue editor styles.
		 add_editor_style( 'critical.css' );
		 add_editor_style( 'style.css' );
	 }
 
 endif;

// Load First the Google Fonts
add_action('wp_head', 'theme_fonts', 1);
function theme_fonts() { ?>
	<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> 
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" fetchpriority="high" as="style"> -->

    <link rel="preload" href="<?php echo get_stylesheet_directory_uri(); ?>/critical.css" fetchpriority="high" as="style"/>
    <link id="child-theme-critical" rel="stylesheet" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/critical.css" fetchpriority="high"/>

<?php } 


// CHILD THEME LOAD SCRIPTS AND STYLES
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_scripts_styles' );

function child_theme_enqueue_scripts_styles() {

    $theme_version = wp_get_theme()->get( 'Version' );

    wp_enqueue_style( 'child-theme-style', get_stylesheet_directory_uri() . '/style.css', array(), $theme_version , 'all');
    wp_enqueue_style( 'child-theme-custom-woo-style', get_stylesheet_directory_uri() . '/assets/css/custom-woo-style.css', array(), $theme_version , 'all');
    wp_enqueue_style( 'child-theme-custom-forms-style', get_stylesheet_directory_uri() . '/assets/css/custom-forms-style.css', array(), $theme_version , 'all');
	wp_enqueue_style( 'child-theme-swiper-css',  'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array() , '11.0.4' , 'all');
	// Register and enqueue Magnific Popup CSS file
    wp_enqueue_style('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css', array(), '1.1.0', 'all');
	wp_enqueue_style('mobiscroll-style',  get_stylesheet_directory_uri() . '/mobiscroll/css/mobiscroll.jquery.min.css');


    wp_enqueue_script('child-theme-swiper-js',  'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.4' , true );
	wp_enqueue_script('ajax-script', get_stylesheet_directory_uri() . '/assets/js/ajax-script.js', array('jquery'), '1.0', true);
	// Register and enqueue Magnific Popup JS file
	wp_enqueue_script('magnific-popup', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true);
	// Localize the script with the ajaxurl
	wp_localize_script('ajax-script', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
	wp_localize_script('ajax-script', 'ajax_api_object', array('ajax_api_url' => home_url().'/wp-json'));


	// Enqueue Select2
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0-rc.0', true);
	wp_enqueue_script('mobiscroll-script', get_stylesheet_directory_uri() . '/mobiscroll/js/mobiscroll.jquery.min.js', array('jquery'), null, true);
    // Enqueue Select2 CSS
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
	wp_enqueue_script('child-theme-javascript', get_stylesheet_directory_uri() . '/assets/js/custom' . '.js', [ 'jquery' ] , $theme_version , true );

}

// CHILD THEME BLOCK VARIATIONS
add_action( 'enqueue_block_editor_assets', 'child_theme_block_variations' );

function child_theme_block_variations() {

    $theme_version = wp_get_theme()->get( 'Version' );

    /**
     * Create a custom class for different variants of a component.
     * eg. different types of buttons (Primary, Secondary).
     */
    wp_enqueue_script('child-theme-block-styles-variation', get_stylesheet_directory_uri() . '/assets/js/block-styles-variation' . '.js',  array(), $theme_version , true );
	// wp_enqueue_script('child-theme-block-variations', get_template_directory_uri() . '/assets/js/block-variation' . '.js', array() , $theme_version , true );
}


// CHILD THEME LOAD SCRIPTS AND STYLES
// add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_scripts_styles' );

// function child_theme_enqueue_scripts_styles() {

//     $theme_version = wp_get_theme()->get( 'Version' );

//     wp_enqueue_style( 'child-theme-style', get_template_directory_uri() . '/style.css', array(), $theme_version , 'all');

// 	// wp_enqueue_style( 'child-theme-swiper-css',  'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', array() , '8.0.0' , 'all');
//     // wp_enqueue_style( 'child-theme-magnific-popup-css',  'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css', array() , '1.1.0' , 'all');
// 	// wp_enqueue_style( 'child-theme-font-awesome-6',  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css', array() , 'all');

// 	wp_enqueue_script('child-theme-javascript', get_stylesheet_directory_uri() . '/assets/js/custom' . '.js', [ 'jquery' ] , $theme_version , true );
//     wp_enqueue_script('child-theme-jquery',  get_stylesheet_directory_uri() . '/jquery-ui/jquery-ui.min.js', array() , true );
//     // wp_enqueue_script('child-theme-swiper-js',  'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), '8.4.5' , true );
// 	// wp_enqueue_script('child-theme-magnific-popup-js',  'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js', array(), '1.1.0', true);
// }


// Shortcode [year]
add_shortcode('year', 'year_shortcode');
function year_shortcode($atts) {
	return date("Y");
}

/**
 * Register block styles.
 */

if ( ! function_exists( 'wptemplate2024_block_styles' ) ) :
	/**
	 * Register custom block styles
	 *
	 * @since WP Template 2024 1.0
	 * @return void
	 */
	function wptemplate2024_block_styles() {

		register_block_style(
			'core/details',
			array(
				'name'         => 'arrow-icon-details',
				'label'        => __( 'Arrow icon', 'wptemplate2024' ),
				/*
				 * Styles for the custom Arrow icon style of the Details block
				 */
				'inline_style' => '
				.is-style-arrow-icon-details {
					padding-top: var(--wp--preset--spacing--10);
					padding-bottom: var(--wp--preset--spacing--10);
				}

				.is-style-arrow-icon-details summary {
					list-style-type: "\2193\00a0\00a0\00a0";
				}

				.is-style-arrow-icon-details[open]>summary {
					list-style-type: "\2192\00a0\00a0\00a0";
				}',
			)
		);

		register_block_style(
			'core/post-terms',
			array(
				'name'         => 'pill',
				'label'        => __( 'Pill', 'wptemplate2024' ),
				/*
				 * Styles variation for post terms
				 * https://github.com/WordPress/gutenberg/issues/24956
				 */
				'inline_style' => '
				.is-style-pill a,
				.is-style-pill span:not([class], [data-rich-text-placeholder]) {
					display: inline-block;
					background-color: var(--wp--preset--color--base-2);
					padding: 0.375rem 0.875rem;
					border-radius: var(--wp--preset--spacing--20);
				}

				.is-style-pill a:hover {
					background-color: var(--wp--preset--color--contrast-3);
				}',
			)
		);
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'wptemplate2024' ),
				/*
				 * Styles for the custom checkmark list block style
				 * https://github.com/WordPress/gutenberg/issues/51480
				 */
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
		register_block_style(
			'core/navigation-link',
			array(
				'name'         => 'arrow-link',
				'label'        => __( 'With arrow', 'wptemplate2024' ),
				/*
				 * Styles for the custom arrow nav link block style
				 */
				'inline_style' => '
				.is-style-arrow-link .wp-block-navigation-item__label:after {
					content: "\2197";
					padding-inline-start: 0.25rem;
					vertical-align: middle;
					text-decoration: none;
					display: inline-block;
				}',
			)
		);
		register_block_style(
			'core/heading',
			array(
				'name'         => 'asterisk',
				'label'        => __( 'With asterisk', 'wptemplate2024' ),
				'inline_style' => "
				.is-style-asterisk:before {
					content: '';
					width: 1.5rem;
					height: 3rem;
					background: var(--wp--preset--color--contrast-2, currentColor);
					clip-path: path('M11.93.684v8.039l5.633-5.633 1.216 1.23-5.66 5.66h8.04v1.737H13.2l5.701 5.701-1.23 1.23-5.742-5.742V21h-1.737v-8.094l-5.77 5.77-1.23-1.217 5.743-5.742H.842V9.98h8.162l-5.701-5.7 1.23-1.231 5.66 5.66V.684h1.737Z');
					display: block;
				}

				/* Hide the asterisk if the heading has no content, to avoid using empty headings to display the asterisk only, which is an A11Y issue */
				.is-style-asterisk:empty:before {
					content: none;
				}

				.is-style-asterisk:-moz-only-whitespace:before {
					content: none;
				}

				.is-style-asterisk.has-text-align-center:before {
					margin: 0 auto;
				}

				.is-style-asterisk.has-text-align-right:before {
					margin-left: auto;
				}

				.rtl .is-style-asterisk.has-text-align-left:before {
					margin-right: auto;
				}",
			)
		);
	}
endif;

add_action( 'init', 'wptemplate2024_block_styles' );

/**
 * Enqueue block stylesheets.
 */

if ( ! function_exists( 'wptemplate2024_block_stylesheets' ) ) :
	/**
	 * Enqueue custom block stylesheets
	 *
	 * @since WP Template 2024 1.0
	 * @return void
	 */
	function wptemplate2024_block_stylesheets() {
		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */
		wp_enqueue_block_style(
			'core/button',
			array(
				'handle' => 'wptemplate2024-button-style-outline',
				'src'    => get_parent_theme_file_uri( 'assets/css/button-outline.css' ),
				'ver'    => wp_get_theme( get_template() )->get( 'Version' ),
				'path'   => get_parent_theme_file_path( 'assets/css/button-outline.css' ),
			)
		);
	}
endif;

add_action( 'init', 'wptemplate2024_block_stylesheets' );

/**
 * Register pattern categories.
 */

if ( ! function_exists( 'wptemplate2024_pattern_categories' ) ) :
	/**
	 * Register pattern categories
	 *
	 * @since WP Template 2024 1.0
	 * @return void
	 */
	function wptemplate2024_pattern_categories() {

		register_block_pattern_category(
			'page',
			array(
				'label'       => _x( 'Pages', 'Block pattern category' ),
				'description' => __( 'A collection of full page layouts.' ),
			)
		);
	}
endif;

add_action( 'init', 'wptemplate2024_pattern_categories' );


// WOOCOMMERCE
// Remove fields
add_filter( 'woocommerce_checkout_fields', 'bbloomer_remove_woo_checkout_fields' );
  
function bbloomer_remove_woo_checkout_fields( $fields ) {
    unset( $fields['billing']['billing_address_1'] ); 
    unset( $fields['billing']['billing_address_2'] ); 
    unset( $fields['billing']['billing_city'] ); 
    unset( $fields['billing']['billing_postcode'] ); 
    unset( $fields['billing']['billing_state'] ); 
    return $fields;
}

// Reoder
// add_filter( 'woocommerce_default_address_fields', 'bbloomer_reorder_checkout_fields' );
 
// function bbloomer_reorder_checkout_fields( $fields ) {
 
//    // default priorities:
//    // 'first_name' - 10
//    // 'last_name' - 20
//    // 'company' - 30
//    // 'country' - 40
//    // 'address_1' - 50
//    // 'address_2' - 60
//    // 'city' - 70
//    // 'state' - 80
//    // 'postcode' - 90
 
//   // e.g. move 'company' above 'first_name':
//   // just assign priority less than 10
//   $fields['billing_email']['priority'] = 90;
 
//   return $fields;
// }

add_filter( 'woocommerce_billing_fields', 'bbloomer_move_checkout_email_field' );
 
function bbloomer_move_checkout_email_field( $address_fields ) {
    $address_fields['billing_email']['priority'] = 90;
    return $address_fields;
}

// CHANGE LABEL
add_filter( 'woocommerce_checkout_fields', 'bbloomer_rename_woo_checkout_fields' );
  
function bbloomer_rename_woo_checkout_fields( $fields ) {
    $fields['billing']['billing_phone']['label'] = 'Contact Number';
    $fields['billing']['billing_email']['label'] = 'Email Address';
    return $fields;
}

add_filter( 'woocommerce_checkout_fields', 'bbloomer_required_woo_checkout_fields' );
  
function bbloomer_required_woo_checkout_fields( $fields ) {
    $fields['billing']['billing_first_name']['required'] = false;
    return $fields;
}

// ADD FIELD
add_filter( 'woocommerce_checkout_fields', 'bbloomer_shipping_phone_checkout' );
 
function bbloomer_shipping_phone_checkout( $fields ) {
   $fields['billing']['billing_tin_number'] = array(
      'label' => 'TIN Number',
      'type' => 'tel',
      'required' => false,
      'class' => array( 'form-row-wide' ),
      'validate' => array( 'phone' ),
      'autocomplete' => 'tel',
      'priority' => 100,
   );
   return $fields;
}

// Add Booking Notes field on the checkout page
add_filter('woocommerce_checkout_fields', 'add_booking_notes_field');
function add_booking_notes_field($fields) {
    $fields['billing']['booking_notes'] = array(
        'type'        => 'textarea',
        'class'       => array('form-row-wide'),
        'label'       => __('Booking Notes'),
        'placeholder' => __('Some random notes here......'),
        'required'    => false,
        'clear'       => true,
    );

    return $fields;
}

  
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'bbloomer_shipping_phone_checkout_display' );
 
function bbloomer_shipping_phone_checkout_display( $order ){
    echo '<p><b>Shipping Phone:</b> ' . get_post_meta( $order->get_id(), '_shipping_phone', true ) . '</p>';
}


function custom_excerpt_length($length) {
    return 20; // Change 20 to the number of words you want to show in the excerpt
}

function custom_excerpt_more($more) {
    return ''; // Remove the default "[...]"
}

add_filter('excerpt_length', 'custom_excerpt_length');
add_filter('excerpt_more', 'custom_excerpt_more');




function set_default_quantity_to_one( $cart ) {
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
        $cart->set_quantity( $cart_item_key, 1 );
    }
}
add_action( 'woocommerce_cart_loaded_from_session', 'set_default_quantity_to_one' );


function hide_save_order_page($query) {
    global $pagenow;

    // Check if it's the admin pages list
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'page') {
        $save_order_page_id = 680; // Replace with the actual page ID of your save-order page

        // Exclude the save-order page from the query
        $query->set('post__not_in', array($save_order_page_id));
    }
}
add_action('pre_get_posts', 'hide_save_order_page');







function add_query_vars_filter( $vars ){

    $vars[] = "formtoken";

    return $vars;

}

add_filter( 'query_vars', 'add_query_vars_filter' );

get_query_var('formtoken');



add_action('template_redirect', function(){



    /** You can change your token value here as you like. As long as it's long enough.  */

    $token = 'L1dsrqjQNca4Bado4M17I1iWPqZOLk69swvTxWkjN6tknx2C00JgJudIgb68Ul65c1eeO0Wmzoc6h7EdX2mdP';

    $tokenURL = get_query_var('formtoken');

    if ( ! is_page('thank-you') && ! is_page('check-out')) {
        return;
    }

    if ($token == $tokenURL) {

        return;

    }

    wp_redirect( get_home_url() );

    exit;

} );


// add_filter( 'woocommerce_email_subject_customer_processing_order', 'bbloomer_change_processing_email_subject', 10, 2 );
  
// function bbloomer_change_processing_email_subject( $subject, $order ) {
// 	$orders = wc_get_order($order->get_id());
//     $first_item = current($orders->get_items());

//    $subject = 'New Room Booking | '. $first_item->get_name();
//    return $subject;
// }

// Add a filter to modify the order email recipient
// add_filter('woocommerce_email_recipient_customer_processing_order', 'custom_order_email_recipient', 10, 2);

// function custom_order_email_recipient($recipient, $order) {

// 	// Get the customer email from the order object
//     $customer_email = $order->get_billing_email();
	
//     // Change the recipient to the custom email address
//     $recipient = 'jmnicolas4me@gmail.com';

//     // Uncomment the line below to keep the original recipient as well
//     $recipient .= ',' . $customer_email;

//     return $recipient;
// }

add_filter('woocommerce_email_subject_new_order', 'custom_new_order_email_subject', 10, 2);

function custom_new_order_email_subject($subject, $order) {
	$orders = wc_get_order($order->get_id());
	$first_item = current($orders->get_items());
	$product_id = $first_item->get_product_id();
    // Customize the subject as needed
    $custom_subject = $subject.' | '.$first_item->get_name();

    return $custom_subject;
}

// add_filter( 'woocommerce_email_recipient_new_order', 'bbloomer_dynamic_recipient', 9999, 2 );
  
// function bbloomer_dynamic_recipient( $recipient, $order ) {
//    $email_recipient = 'jmnicolas4me@gmail.com';
//    return $email_recipient;
// }


add_action('woocommerce_order_status_changed', 'send_custom_email_notifications', 10, 4 );
function send_custom_email_notifications( $order_id, $old_status, $new_status, $order ){
    if ( $new_status == 'cancelled' || $new_status == 'failed' ){
        $wc_emails = WC()->mailer()->get_emails(); // Get all WC_emails objects instances
        $customer_email = $order->get_billing_email(); // The customer email
    }

    if ( $new_status == 'cancelled' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Cancelled_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Cancelled_Order']->trigger( $order_id );
    } 
    elseif ( $new_status == 'failed' ) {
        // change the recipient of this instance
        $wc_emails['WC_Email_Failed_Order']->recipient = $customer_email;
        // Sending the email from this instance
        $wc_emails['WC_Email_Failed_Order']->trigger( $order_id );
    } 
}

add_shortcode('page_sitemap', 'page_sitemap');
function page_sitemap($atts = array()){
    $args = shortcode_atts(  array( 'exclude' => '' ), $atts);

    $atts = shortcode_atts(array(
        'id' => 'sitemap',
        'title' => false,
        'parent' => false, 
        'authors' => false,
        'depth' => false,
        'sort_solumn' => 'menu_order,post_title',
        'date_format' => 'j D Y',
        'show_date' => false,
        'exclude' => $args['exclude'],
        'link_before' => false,
        'link_after' => false,
        'poststatus' => false,
        'item_spacing' => false,
        'walker' => false,
        'list_style' => 'none',
    ), $atts);

    $parent = ($atts['parent'] !== false) ? $atts['parent'] : '0';
    $authors = ($atts['authors'] !== false) ? $atts['authors'] : '';
    $title = ($atts['title'] !== false) ? $atts['title'] : '';
    $depth = ($atts['depth'] !== false) ? $atts['depth'] : '0';
    $walker = ($atts['walker'] !== false) ? $atts['walker'] : '';
    $date = ($atts['show_date'] !== false) ? $atts['show_date'] : '';
    $exclude = ($atts['exclude'] !== false) ? $atts['exclude'] : '';
    $poststatus = ($atts['poststatus'] !== false) ? $atts['poststatus'] : 'publish';
    $spacing = ($atts['item_spacing'] === false) ? 'preserve' : 'discard';
    $link_after = ($atts['link_after'] !== false) ? $atts['link_after'] : '';
    $link_before = ($atts['link_before'] !== false) ? $atts['link_before'] : '';
    $sitemap = wp_list_pages('child_of=' . $parent . '&authors=' . $authors . '&title_li=' . $title . '&depth=' . $depth . '&sort_column=' . $atts['sort_solumn'] . '&walker=' . $walker . '&date=' . $date . '&exclude=' . $exclude . '&post_status=' . $poststatus . '&item_spacing=' . $spacing . '&link_after=' . $link_after . '&link_before=' . $link_before . '&echo=0');
    if ($sitemap != '') $sitemap = '<ul' . ($atts['id'] == '' ? '' : ' id="' . $atts['id'] . '"') . '>' . $sitemap . '</ul>';
    return '' . $sitemap . '';
}
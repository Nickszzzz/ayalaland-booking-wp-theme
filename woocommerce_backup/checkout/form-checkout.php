<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}


?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
    action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <?php if ( $checkout->get_checkout_fields() ) : ?>

    <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

    <div class="col2-set" id="customer_details">
        <div class="col-1">
            <?php do_action( 'woocommerce_checkout_billing' ); ?>
            <div class="accordion">
                <div class="accordion-item">
                    
                    <div class="accordion-header" for="paynamics"><input type="radio" name="accordion" id="paynamics"> Paynamics (Gcash)</div>
                    <div class="accordion-content" style="display: none;">
                        <p>Some notes for Paynamics (Gcash). Nunc vulputate libero et velit interdum, ac aliquet odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                    </div>
                </div>

                <div class="accordion-item">
                   
                    <div class="accordion-header" for="debitCard"> <input type="radio" name="accordion" id="debitCard"> Debit/Credit Card</div>
                    <div class="accordion-content" style="display: none;">
                        <p>Some notes for Debit/Credit Card. Nunc vulputate libero et velit interdum, ac aliquet odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                    </div>
                </div>

                <div class="accordion-item">
                    
                    <div class="accordion-header" for="bankTransfer"><input type="radio" name="accordion" id="bankTransfer"> Bank Transfer</div>
                    <div class="accordion-content" style="display: none;">
                        <p>Some notes for Bank Transfer. Nunc vulputate libero et velit interdum, ac aliquet odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-2">
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

            <h3 id="order_review_heading"><?php esc_html_e( 'Booking Summary', 'woocommerce' ); ?></h3>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
        </div>
    </div>

    <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <?php endif; ?>



</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
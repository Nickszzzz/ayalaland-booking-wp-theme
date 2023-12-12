<?php
/**
 * Add Fields Inside “Order Details” Table 
 *
 * @author Misha Rudrastyh
 * @link https://rudrastyh.com/woocommerce/add-info-view-order-thank-you-pages.html#example-1
 */
add_action( 'woocommerce_order_details_after_order_table_items', 'misha_order_details' );

function misha_order_details( $order ) {

	$contact_method = $order->get_meta( 'contactmethod' );
	$shippingdate = $order->get_meta( 'shippingdate' );

	if( $contact_method ) :
		?>
			<tr>
				<th scope="row">Preferable contact method:</th>
				<td><?php echo esc_html( $contact_method ) ?></td>
			</tr>
		<?php
	endif;

	if( $shippingdate ) :
		?>
			<tr>
				<th scope="row">Shipping date:</th>
				<td><?php echo date( 'F jS, Y', strtotime( $shippingdate ) ) ?></td>
			</tr>
		<?php
	endif;

}
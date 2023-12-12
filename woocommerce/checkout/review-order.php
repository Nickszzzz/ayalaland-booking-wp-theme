<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;

$product_id = isset($_GET['add-to-cart']) ? $_GET['add-to-cart'] : '';
$quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';
$room_name = isset($_GET['room_name']) ? urldecode($_GET['room_name']) : '';
$number_of_seats = isset($_GET['number_of_seats']) ? $_GET['number_of_seats'] : '';
$checkin = isset($_GET['checkin']) ? urldecode($_GET['checkin']) : '';
$checkout = isset($_GET['checkout']) ? urldecode($_GET['checkout']) : '';
$rate = isset($_GET['rate']) ? urldecode($_GET['rate']) : '';
function extractNumber($inputString)
{
    $pattern = '/Php([\d,]+\.\d+)/';
    preg_match($pattern, $inputString, $matches);

    return !empty($matches[1]) ? (float)str_replace(',', '', $matches[1]) : null;
}

$total_amount =  extractNumber($rate);
// Convert date strings to DateTime objects with the correct format
$date1 = DateTime::createFromFormat('y-m-d h:i a', $checkin);
$date2 = DateTime::createFromFormat('y-m-d h:i a', $checkout);


// Check if the DateTime objects were created successfully
if ($date1 && $date2) {
    // Calculate the time difference
    $timeDifference = $date2->diff($date1);

    // Access the hours directly
    $timeDifferenceHours = $timeDifference->h;

    // If there are any days, add them to the hours
    $timeDifferenceHours += $timeDifference->days * 24;

    // Now you can use $timeDifferenceHours as needed
} 
?>
<table class="">

	<tbody>
		<tr class="">
			<td class="product-name">
				Meeting Room Name
			</td>
			<td class="product-total">
				<?php echo esc_html($room_name); // Use esc_html to sanitize the output ?>
			</td>
		</tr>
		<tr class="">
			<td class="product-name">
			Booking Type
			</td>
			<td class="product-total">
			 <?php echo $rate; ?>
			</td>
		</tr>
		<tr class="">
			<td class="product-name">
			Booking Date
			</td>
			<td class="product-total">
			<?php echo $checkin; ?><br>
			<?php echo $checkout; ?>
			</td>
		</tr>
		<tr class="">
			<td class="product-name">
			Time (no. of hrs)
			</td>
			<td class="product-total">
				<?php echo floor($timeDifferenceHours).' hrs'; ?>
			</td>
		</tr>

	</tbody>
	
</table>
<table class="">
	<body>
	<tr class="">
			<td class="product-name">
			Total Booking Amount
			</td>
			<td class="product-total">
			Php <?php echo $total_amount; ?>
			</td>
		</tr>
		<tr class="">
			<td class="product-name">
			VAT
			</td>
			<td class="product-total">
			Php 50.00
			</td>
		</tr>
		<tr class="">
			<td class="product-name">
			Overall Total
			</td>
			<td class="product-total">
			Php <?php echo ($total_amount + 50); ?>
			</td>
		</tr>
	</body>
	
</table>
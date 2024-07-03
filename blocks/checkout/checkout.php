<div
    class="wp-block-group has-global-padding is-layout-constrained wp-container-core-group-layout-6 wp-block-group-is-layout-constrained">
    <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>


    <div class="woocommerce">
        <div class="woocommerce-notices-wrapper">
            <div class="wc-block-components-notice-banner is-success" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true"
                    focusable="false">
                    <path d="M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"></path>
                </svg>
                <p></p>
                <div class="wc-block-components-notice-banner__content">
                    <a href="<?php echo home_url().'/cart'; ?>" tabindex="1"
                        class="button wc-forward wp-element-button">View cart</a> “South Room A” has been added to your
                    cart. </div>
                <p></p>
            </div>
        </div>
        <div class="woocommerce-notices-wrapper"></div>
        <form name="checkout" method="get" class="checkout woocommerce-checkout" id="checkout-form"
            action="<?php echo home_url().'/save-order'; ?>" enctype="multipart/form-data" novalidate="novalidate">
            <p></p>
            <input type="hidden" name="formtoken" value="L1dsrqjQNca4Bado4M17I1iWPqZOLk69swvTxWkjN6tknx2C00JgJudIgb68Ul65c1eeO0Wmzoc6h7EdX2mdP">
            <div class="col2-set" id="customer_details">
                <div class="col-1">
                    <div class="woocommerce-billing-fields">
                        <h3>Personal Information</h3>
                        <div class="woocommerce-billing-fields__field-wrapper">
                            <p></p>
                            <p class="form-row form-row-first" id="billing_first_name_field" data-priority="10"><label
                                    for="billing_first_name" class="">First name&nbsp;<abbr class="required"
                                        title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input
                                        type="text" class="input-text " name="billing_first_name"
                                        id="billing_first_name" placeholder="" value=""
                                        autocomplete="given-name"></span></p>
                            <p class="form-row form-row-last validate-required" id="billing_last_name_field"
                                data-priority="20"><label for="billing_last_name" class="">Last name&nbsp;<abbr
                                        class="required" title="required">*</abbr></label><span
                                    class="woocommerce-input-wrapper"><input type="text" class="input-text "
                                        name="billing_last_name" id="billing_last_name" placeholder="" value=""
                                        autocomplete="family-name"></span></p>
                            <p class="form-row form-row-wide" id="billing_company_field" data-priority="30"><label
                                    for="billing_company" class="">Company name&nbsp;<abbr class="required"
                                        title="required">*</abbr></label><span class="woocommerce-input-wrapper"><input
                                        type="text" class="input-text " name="billing_company" id="billing_company"
                                        placeholder="" value="" autocomplete="organization"></span></p>
                            <p class="form-row form-row-wide address-field update_totals_on_change validate-required"
                                id="billing_country_field" data-priority="40">
                                <label for="billing_country" class="">Country / Region&nbsp;<abbr class="required"
                                        title="required">*</abbr></label>
                                <span class="woocommerce-input-wrapper">
                                    <select name="country" id="billing_country"
                                        class="country_to_state country_select select2-hidden-accessible"
                                        autocomplete="country" data-placeholder="Select a country / region…"
                                        data-label="Country / Region" tabindex="-1" aria-hidden="true">

                                    </select>
                                    <p class="form-row form-row-wide validate-required validate-email woocommerce-invalid woocommerce-invalid-email woocommerce-invalid-phone"
                                        id="billing_email_field" data-priority="90"><label for="billing_email"
                                            class="">Email
                                            Address&nbsp;<abbr class="required" title="required">*</abbr></label><span
                                            class="woocommerce-input-wrapper"><input type="email" class="input-text "
                                                name="billing_email" id="billing_email" placeholder="" value=""
                                                autocomplete="email username"></span></p>
                                    <p class="form-row form-row-wide validate-required validate-phone"
                                        id="billing_phone_field" data-priority="100"><label for="billing_phone"
                                            class="">Contact Number&nbsp;<abbr class="required"
                                                title="required">*</abbr></label><span
                                            class="woocommerce-input-wrapper"><input type="tel" class="input-text "
                                                name="billing_phone" id="billing_phone" placeholder="" value=""
                                                autocomplete="tel"></span></p>
                                    <p class="form-row form-row-wide validate-phone" id="billing_tin_number_field"
                                        data-priority="100"><label for="billing_tin_number" class="">TIN
                                            Number&nbsp;<span class="optional">(optional)</span></label><span
                                            class="woocommerce-input-wrapper"><input type="tel" class="input-text "
                                                name="billing_tin_number" id="billing_tin_number" placeholder=""
                                                value="" autocomplete="tel"></span></p>
                                    <p class="form-row form-row-wide" id="booking_notes_field" data-priority=""><label
                                            for="booking_notes" class="">Additional requests&nbsp;</label><span
                                            class="woocommerce-input-wrapper"><textarea name="booking_notes"
                                                class="input-text " id="booking_notes"
                                                placeholder="Notes" rows="2"
                                                cols="5"></textarea></span></p>
                                                 <p class="form-row form-row-wide" id="privacy_and_guidelines1" data-priority=""><label
                                for="privacy_and_guidelines1" class=""><input type="checkbox" />By checking this box, you agree to our <a href="<?php echo home_url(); ?>/terms-and-conditions/">Terms and Condition</a> and <a href="<?php echo home_url(); ?>/privacy-policy/">Privacy Policy</a>.</label><span
                                class="woocommerce-input-wrapper"></span></p>
                        </div>
                        <p></p>
                    </div>
                    <div class="accordion">
                        <div class="accordion-item">
                            <h3>Payment Method</h3>
                            <div class="accordion-header" for="paynamics"><input type="radio" name="accordion"
                                    id="paynamics"> Paynamics (Gcash)</div>
                            <div class="accordion-content" style="display: none;">
                                <p>Some notes for Paynamics (Gcash). Nunc vulputate libero et velit interdum, ac aliquet
                                    odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                                    inceptos himenaeos.</p>
                            </div>
                        </div>

                        <div class="accordion-item">

                            <div class="accordion-header" for="debitCard"> <input type="radio" name="accordion"
                                    id="debitCard"> Debit/Credit Card</div>
                            <div class="accordion-content" style="display: none;">
                                <p>Some notes for Debit/Credit Card. Nunc vulputate libero et velit interdum, ac aliquet
                                    odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                                    inceptos himenaeos.</p>
                            </div>
                        </div>

                        <div class="accordion-item">

                            <div class="accordion-header" for="bankTransfer"><input type="radio" name="accordion"
                                    id="bankTransfer"> Bank Transfer</div>
                            <div class="accordion-content" style="display: none;">
                                <p>Some notes for Bank Transfer. Nunc vulputate libero et velit interdum, ac aliquet
                                    odio mattis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                                    inceptos himenaeos.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <h3 id="order_review_heading">Booking Summary</h3>
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php 
                            $product_id = isset($_GET['add-to-cart']) ? $_GET['add-to-cart'] : '';
                            $quantity = isset($_GET['quantity']) ? $_GET['quantity'] : '';
                            $room_name = isset($_GET['room_name']) ? urldecode($_GET['room_name']) : '';
                            $number_of_seats = isset($_GET['number_of_seats']) ? $_GET['number_of_seats'] : '';
                            $checkin = isset($_GET['checkin']) ? urldecode($_GET['checkin']) : '';
                            $checkout = isset($_GET['checkout']) ? urldecode($_GET['checkout']) : '';
                            $rate = isset($_GET['rate']) ? urldecode($_GET['rate']) : '';
							echo strtotime(str_replace([',', ':'], [' ', ''], $checkin));
						
                            function extractNumber($inputString)
                            {
                                $pattern = '/Php([\d,]+\.\d+)/';
                                preg_match($pattern, $inputString, $matches);
                            
                                return !empty($matches[1]) ? (float)str_replace(',', '', $matches[1]) : null;
                            }

                            // function calculateHourDifference($checkin, $checkout) {
                            //     // Convert date strings to DateTime objects
                            //     $checkinDateTime = DateTime::createFromFormat('M j, Y, g:i:s A', $checkin);
                            //     $checkoutDateTime = DateTime::createFromFormat('M j, Y, g:i:s A', $checkout);
                            
                            //     // Calculate the difference between the two dates
                            //     $interval = $checkinDateTime->diff($checkoutDateTime);
                            
                            //     // Calculate the total hours difference
                            //     $hoursDifference = $interval->h + $interval->days * 24;
                            
                            //     // Return the formatted result
                            //     return $hoursDifference;
                            // }
                            
//                             function calculateHourDifference($checkin, $checkout) {
//                                 // Convert date strings to DateTime objects
//                                 // $checkinDateTime = DateTime::createFromFormat('M j, Y, g:i:s A', $checkin);
//                                 // $checkoutDateTime = DateTime::createFromFormat('M j, Y, g:i:s A', $checkout);

//                                 $checkinDateTime = new DateTime($checkin);
//                                 $checkoutDateTime = new DateTime($checkout);
                            
//                                 // Calculate the difference between the two dates
//                                 $interval = $checkinDateTime->diff($checkoutDateTime);
                            
//                                 // Calculate the total hours difference in decimal format
//                                 $hoursDifference = $interval->h + $interval->i / 60 + $interval->s / 3600 + $interval->days * 24;
                            
//                                 // Return the result as a decimal number
//                                 return $hoursDifference;
//                             }
							
						function calculateHourDifference($checkin, $checkout) {
                                	$firstTime=strtotime($checkin);
								   $lastTime=strtotime($checkout);
                            
                                	// Calculate the time difference in seconds
									$timeDifference = $lastTime - $firstTime;

									// Calculate the total hours difference in decimal format
									$hoursDifference = $timeDifference / 3600;

									// Return the result as a decimal number
									return $hoursDifference;
                            }

                           function convertDateFormat($inputDate) {
                                $timestamp = strtotime($inputDate);

								// Convert the timestamp to the desired date format
								$outputDate = date('m-d-y h:i a', $timestamp);

								// Return the result
								return $outputDate;
                            }

                            function calculateDailyCost($dailyRate, $numberOfHours) {
                                $hoursInDay = 24;
                                
                                if ($numberOfHours <= $hoursInDay) {
                                    $hourlyRate = $dailyRate / $hoursInDay;
                                    return $hourlyRate * $numberOfHours;
                                } else {
                                    return "Invalid number of hours. Please book up to 24 hours.";
                                }
                            }

                            
                            
                            $total_amount =  extractNumber($rate);
                            $hoursDifference = calculateHourDifference($checkin, $checkout);

                            function calculateAmountToPay($wholeDayRate, $hoursInWholeDay, $hoursOccupied) {
                                // Calculate hourly rate
                                $hourlyRate = $wholeDayRate / $hoursInWholeDay;
                            
                                // Calculate amount to pay
                                $amountToPay = $hourlyRate * $hoursOccupied;
                            
                                return $amountToPay;
                            }

                            

                ?>
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
                        <input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
                        <input type="hidden" name="number_or_hours" value="<?php echo $hoursDifference; ?>">
                        <input type="hidden" name="number_of_seats" value="<?php echo $number_of_seats; ?>">

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
                                        <?php echo convertDateFormat($checkin); ?><br>
                                        <?php echo convertDateFormat($checkout); ?>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="product-name">
                                        Time (no. of hrs)
                                    </td>
                                    <td class="product-total">
                                        <?php
                                           
                                             // Determine whether to use "hour" or "hours"
                                            $plural = $hoursDifference > 1 ? 's' : '';
                                           echo "{$hoursDifference} hour{$plural}";
                                        ?>
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
                                        Php <?php 
                                            $total_booking_amount = 0;
                                        if (stripos($rate, 'Hourly') !== false) {
                                            $total_booking_amount = $total_amount*$hoursDifference;
                                            echo number_format((float)$total_booking_amount, 2, '.', ',');
                                        } else {
                                            $total_booking_amount = calculateAmountToPay($total_amount, 8, $hoursDifference);
                                            echo number_format((float)$total_booking_amount, 2, '.', ',');
                                        }
                                            
                                         ?>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="product-name">
                                        VAT
                                    </td>
                                    <td class="product-total">
                                        
                                        Php <?php 
                                            function calculateTaxAmount($originalCost, $vatPercentage) {
                                                // Adding Tax
                                                $taxAmountAdd = ($originalCost * $vatPercentage) / 100;
                                            
                                                // Return tax amount
                                                return $taxAmountAdd;
                                            }
                
                                            $taxAmountAdd = calculateTaxAmount($total_booking_amount, 12);
                                            echo number_format($taxAmountAdd, 2, '.', ',')
                                        ?>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td class="product-name">
                                        Overall Total
                                    </td>
                                    <td class="product-total">
                                        Php <?php 
                                              echo number_format((float)$total_booking_amount+$taxAmountAdd, 2, '.', ',');?>
                                        <input type="hidden" name="overall_total"
                                            value="<?php echo $total_booking_amount+$taxAmountAdd; ?>">
                                    </td>
                                </tr>
                            </body>

                        </table>
                        <div id="payment" class="woocommerce-checkout-payment">
                            <div class="form-row place-order">
                                <noscript>
                                    Since your browser does not support JavaScript, or it is disabled, please ensure you
                                    click the <em>Update Totals</em> button before placing your order. You may be
                                    charged more than the amount stated above if you fail to do so. <br /><button
                                        type="submit" class="button alt wp-element-button"
                                        name="woocommerce_checkout_update_totals" value="Update totals">Update
                                        totals</button>
                                </noscript>

                                <div class="woocommerce-terms-and-conditions-wrapper">
                                    <div class="woocommerce-privacy-policy-text">
                                        <p>Your personal data will be used to process your order, support your
                                            experience throughout this website, and for other purposes described in our
                                            <a href="<?php echo home_url().'/?page_id=3'; ?>"
                                                class="woocommerce-privacy-policy-link" target="_blank">privacy
                                                policy</a>.</p>
                                    </div>
                                </div>


                                <button type="submit" class="button alt wp-element-button"
                                    name="woocommerce_checkout_place_order" id="place_order" value="Place order"
                                    data-value="Place order">Place order</button>

                                <input type="hidden" id="woocommerce-process-checkout-nonce"
                                    name="woocommerce-process-checkout-nonce" value="46eb455fc1"><input type="hidden"
                                    name="_wp_http_referer" value="/?wc-ajax=update_order_review">
                            </div>
                        </div>
                    </div>
                    <p></p>
                </div>
                <p></p>
            </div>
        </form>
    </div>



    <div style="height:100px" aria-hidden="true" class="wp-block-spacer is-style-spacer-128"></div>
</div>

<script>
    jQuery(document).ready(function ($) {
        /**
         $("#gform_submit_button_2").on('click', function (e) {

            if (!$("#input_2_7").val() || !$("#input_2_8").val() || !$("#input_2_9").val() || !$('input[name="rate"]:checked').length > 0) {
            e.preventDefault();

            if (!$("#input_2_7").val()) {
                $("#field_2_7 .ginput_container").css("border-color", "red");
            }

            if (!$("#input_2_8").val()) {
                $("#field_2_8 .ginput_container").css("border-color", "red");
            }


            if (!$("#input_2_9").val()) {
                $("#field_2_9 .ginput_container").css("border-color", "red");
            }

            if (!$('input[name="rate"]:checked').length > 0) {
                $("#input_2_12 .gchoice").css("border-color", "red");
            }
            } else {
            $("#gform_2").submit();
            }




        });

        $("#input_2_7").on('input', function (e) {
            if ($(this).val()) {
            $("#field_2_7 .ginput_container").css("border-color", "");
            } else {
            $("#field_2_7 .ginput_container").css("border-color", "red");
            }
        });

        $('input[name="rate"]').on("change", function () {
            $("#input_2_12 .gchoice").css("border-color", "");
        });
     
     */

        $("#place_order").on('click', function (e) {


            if (!$("#billing_first_name").val() || !$("#billing_last_name").val() || !$(
                    "#billing_company").val() || !$("#billing_email").val() || !$("#billing_phone")
                .val()) {
                e.preventDefault();

                if (!$("#billing_first_name").val()) {
                    $("#billing_first_name").css("border-color", "red");
                }

                if (!$("#billing_last_name").val()) {
                    $("#billing_last_name").css("border-color", "red");
                }

                if (!$("#billing_company").val()) {
                    $("#billing_company").css("border-color", "red");
                }

                if (!$("#billing_email").val()) {
                    $("#billing_email").css("border-color", "red");
                }

                if (!$("#billing_phone").val()) {
                    $("#billing_phone").css("border-color", "red");
                }
            } else {
                $("#checkout-form").submit();
            }

        });

        $("#billing_first_name").on('input', function (e) {
            if ($(this).val()) {
                $(this).css("border-color", "");
            } else {
                $(this).css("border-color", "red");
            }
        });

        $("#billing_last_name").on('input', function (e) {
            if ($(this).val()) {
                $(this).css("border-color", "");
            } else {
                $(this).css("border-color", "red");
            }
        });

        $("#billing_company").on('input', function (e) {
            if ($(this).val()) {
                $(this).css("border-color", "");
            } else {
                $(this).css("border-color", "red");
            }
        });

        $("#billing_email").on('input', function (e) {
            if ($(this).val()) {
                $(this).css("border-color", "");
            } else {
                $(this).css("border-color", "red");
            }
        });

        $("#billing_phone").on('input', function (e) {
            if ($(this).val()) {
                $(this).css("border-color", "");
            } else {
                $(this).css("border-color", "red");
            }
        });

    });

    (function($) {
        $(function() {
            var isoCountries = [
                { id: 'AF', text: 'Afghanistan'},
                { id: 'AX', text: 'Aland Islands'},
                { id: 'AL', text: 'Albania'},
                { id: 'DZ', text: 'Algeria'},
                { id: 'AS', text: 'American Samoa'},
                { id: 'AD', text: 'Andorra'},
                { id: 'AO', text: 'Angola'},
                { id: 'AI', text: 'Anguilla'},
                { id: 'AQ', text: 'Antarctica'},
                { id: 'AG', text: 'Antigua And Barbuda'},
                { id: 'AR', text: 'Argentina'},
                { id: 'AM', text: 'Armenia'},
                { id: 'AW', text: 'Aruba'},
                { id: 'AU', text: 'Australia'},
                { id: 'AT', text: 'Austria'},
                { id: 'AZ', text: 'Azerbaijan'},
                { id: 'BS', text: 'Bahamas'},
                { id: 'BH', text: 'Bahrain'},
                { id: 'BD', text: 'Bangladesh'},
                { id: 'BB', text: 'Barbados'},
                { id: 'BY', text: 'Belarus'},
                { id: 'BE', text: 'Belgium'},
                { id: 'BZ', text: 'Belize'},
                { id: 'BJ', text: 'Benin'},
                { id: 'BM', text: 'Bermuda'},
                { id: 'BT', text: 'Bhutan'},
                { id: 'BO', text: 'Bolivia'},
                { id: 'BA', text: 'Bosnia And Herzegovina'},
                { id: 'BW', text: 'Botswana'},
                { id: 'BV', text: 'Bouvet Island'},
                { id: 'BR', text: 'Brazil'},
                { id: 'IO', text: 'British Indian Ocean Territory'},
                { id: 'BN', text: 'Brunei Darussalam'},
                { id: 'BG', text: 'Bulgaria'},
                { id: 'BF', text: 'Burkina Faso'},
                { id: 'BI', text: 'Burundi'},
                { id: 'KH', text: 'Cambodia'},
                { id: 'CM', text: 'Cameroon'},
                { id: 'CA', text: 'Canada'},
                { id: 'CV', text: 'Cape Verde'},
                { id: 'KY', text: 'Cayman Islands'},
                { id: 'CF', text: 'Central African Republic'},
                { id: 'TD', text: 'Chad'},
                { id: 'CL', text: 'Chile'},
                { id: 'CN', text: 'China'},
                { id: 'CX', text: 'Christmas Island'},
                { id: 'CC', text: 'Cocos (Keeling) Islands'},
                { id: 'CO', text: 'Colombia'},
                { id: 'KM', text: 'Comoros'},
                { id: 'CG', text: 'Congo'},
                { id: 'CD', text: 'Congo}, Democratic Republic'},
                { id: 'CK', text: 'Cook Islands'},
                { id: 'CR', text: 'Costa Rica'},
                { id: 'CI', text: 'Cote D\'Ivoire'},
                { id: 'HR', text: 'Croatia'},
                { id: 'CU', text: 'Cuba'},
                { id: 'CY', text: 'Cyprus'},
                { id: 'CZ', text: 'Czech Republic'},
                { id: 'DK', text: 'Denmark'},
                { id: 'DJ', text: 'Djibouti'},
                { id: 'DM', text: 'Dominica'},
                { id: 'DO', text: 'Dominican Republic'},
                { id: 'EC', text: 'Ecuador'},
                { id: 'EG', text: 'Egypt'},
                { id: 'SV', text: 'El Salvador'},
                { id: 'GQ', text: 'Equatorial Guinea'},
                { id: 'ER', text: 'Eritrea'},
                { id: 'EE', text: 'Estonia'},
                { id: 'ET', text: 'Ethiopia'},
                { id: 'FK', text: 'Falkland Islands (Malvinas)'},
                { id: 'FO', text: 'Faroe Islands'},
                { id: 'FJ', text: 'Fiji'},
                { id: 'FI', text: 'Finland'},
                { id: 'FR', text: 'France'},
                { id: 'GF', text: 'French Guiana'},
                { id: 'PF', text: 'French Polynesia'},
                { id: 'TF', text: 'French Southern Territories'},
                { id: 'GA', text: 'Gabon'},
                { id: 'GM', text: 'Gambia'},
                { id: 'GE', text: 'Georgia'},
                { id: 'DE', text: 'Germany'},
                { id: 'GH', text: 'Ghana'},
                { id: 'GI', text: 'Gibraltar'},
                { id: 'GR', text: 'Greece'},
                { id: 'GL', text: 'Greenland'},
                { id: 'GD', text: 'Grenada'},
                { id: 'GP', text: 'Guadeloupe'},
                { id: 'GU', text: 'Guam'},
                { id: 'GT', text: 'Guatemala'},
                { id: 'GG', text: 'Guernsey'},
                { id: 'GN', text: 'Guinea'},
                { id: 'GW', text: 'Guinea-Bissau'},
                { id: 'GY', text: 'Guyana'},
                { id: 'HT', text: 'Haiti'},
                { id: 'HM', text: 'Heard Island & Mcdonald Islands'},
                { id: 'VA', text: 'Holy See (Vatican City State)'},
                { id: 'HN', text: 'Honduras'},
                { id: 'HK', text: 'Hong Kong'},
                { id: 'HU', text: 'Hungary'},
                { id: 'IS', text: 'Iceland'},
                { id: 'IN', text: 'India'},
                { id: 'ID', text: 'Indonesia'},
                { id: 'IR', text: 'Iran}, Islamic Republic Of'},
                { id: 'IQ', text: 'Iraq'},
                { id: 'IE', text: 'Ireland'},
                { id: 'IM', text: 'Isle Of Man'},
                { id: 'IL', text: 'Israel'},
                { id: 'IT', text: 'Italy'},
                { id: 'JM', text: 'Jamaica'},
                { id: 'JP', text: 'Japan'},
                { id: 'JE', text: 'Jersey'},
                { id: 'JO', text: 'Jordan'},
                { id: 'KZ', text: 'Kazakhstan'},
                { id: 'KE', text: 'Kenya'},
                { id: 'KI', text: 'Kiribati'},
                { id: 'KR', text: 'Korea'},
                { id: 'KW', text: 'Kuwait'},
                { id: 'KG', text: 'Kyrgyzstan'},
                { id: 'LA', text: 'Lao People\'s Democratic Republic'},
                { id: 'LV', text: 'Latvia'},
                { id: 'LB', text: 'Lebanon'},
                { id: 'LS', text: 'Lesotho'},
                { id: 'LR', text: 'Liberia'},
                { id: 'LY', text: 'Libyan Arab Jamahiriya'},
                { id: 'LI', text: 'Liechtenstein'},
                { id: 'LT', text: 'Lithuania'},
                { id: 'LU', text: 'Luxembourg'},
                { id: 'MO', text: 'Macao'},
                { id: 'MK', text: 'Macedonia'},
                { id: 'MG', text: 'Madagascar'},
                { id: 'MW', text: 'Malawi'},
                { id: 'MY', text: 'Malaysia'},
                { id: 'MV', text: 'Maldives'},
                { id: 'ML', text: 'Mali'},
                { id: 'MT', text: 'Malta'},
                { id: 'MH', text: 'Marshall Islands'},
                { id: 'MQ', text: 'Martinique'},
                { id: 'MR', text: 'Mauritania'},
                { id: 'MU', text: 'Mauritius'},
                { id: 'YT', text: 'Mayotte'},
                { id: 'MX', text: 'Mexico'},
                { id: 'FM', text: 'Micronesia}, Federated States Of'},
                { id: 'MD', text: 'Moldova'},
                { id: 'MC', text: 'Monaco'},
                { id: 'MN', text: 'Mongolia'},
                { id: 'ME', text: 'Montenegro'},
                { id: 'MS', text: 'Montserrat'},
                { id: 'MA', text: 'Morocco'},
                { id: 'MZ', text: 'Mozambique'},
                { id: 'MM', text: 'Myanmar'},
                { id: 'NA', text: 'Namibia'},
                { id: 'NR', text: 'Nauru'},
                { id: 'NP', text: 'Nepal'},
                { id: 'NL', text: 'Netherlands'},
                { id: 'AN', text: 'Netherlands Antilles'},
                { id: 'NC', text: 'New Caledonia'},
                { id: 'NZ', text: 'New Zealand'},
                { id: 'NI', text: 'Nicaragua'},
                { id: 'NE', text: 'Niger'},
                { id: 'NG', text: 'Nigeria'},
                { id: 'NU', text: 'Niue'},
                { id: 'NF', text: 'Norfolk Island'},
                { id: 'MP', text: 'Northern Mariana Islands'},
                { id: 'NO', text: 'Norway'},
                { id: 'OM', text: 'Oman'},
                { id: 'PK', text: 'Pakistan'},
                { id: 'PW', text: 'Palau'},
                { id: 'PS', text: 'Palestinian Territory}, Occupied'},
                { id: 'PA', text: 'Panama'},
                { id: 'PG', text: 'Papua New Guinea'},
                { id: 'PY', text: 'Paraguay'},
                { id: 'PE', text: 'Peru'},
                { id: 'PH', text: 'Philippines'},
                { id: 'PN', text: 'Pitcairn'},
                { id: 'PL', text: 'Poland'},
                { id: 'PT', text: 'Portugal'},
                { id: 'PR', text: 'Puerto Rico'},
                { id: 'QA', text: 'Qatar'},
                { id: 'RE', text: 'Reunion'},
                { id: 'RO', text: 'Romania'},
                { id: 'RU', text: 'Russian Federation'},
                { id: 'RW', text: 'Rwanda'},
                { id: 'BL', text: 'Saint Barthelemy'},
                { id: 'SH', text: 'Saint Helena'},
                { id: 'KN', text: 'Saint Kitts And Nevis'},
                { id: 'LC', text: 'Saint Lucia'},
                { id: 'MF', text: 'Saint Martin'},
                { id: 'PM', text: 'Saint Pierre And Miquelon'},
                { id: 'VC', text: 'Saint Vincent And Grenadines'},
                { id: 'WS', text: 'Samoa'},
                { id: 'SM', text: 'San Marino'},
                { id: 'ST', text: 'Sao Tome And Principe'},
                { id: 'SA', text: 'Saudi Arabia'},
                { id: 'SN', text: 'Senegal'},
                { id: 'RS', text: 'Serbia'},
                { id: 'SC', text: 'Seychelles'},
                { id: 'SL', text: 'Sierra Leone'},
                { id: 'SG', text: 'Singapore'},
                { id: 'SK', text: 'Slovakia'},
                { id: 'SI', text: 'Slovenia'},
                { id: 'SB', text: 'Solomon Islands'},
                { id: 'SO', text: 'Somalia'},
                { id: 'ZA', text: 'South Africa'},
                { id: 'GS', text: 'South Georgia And Sandwich Isl.'},
                { id: 'ES', text: 'Spain'},
                { id: 'LK', text: 'Sri Lanka'},
                { id: 'SD', text: 'Sudan'},
                { id: 'SR', text: 'Suriname'},
                { id: 'SJ', text: 'Svalbard And Jan Mayen'},
                { id: 'SZ', text: 'Swaziland'},
                { id: 'SE', text: 'Sweden'},
                { id: 'CH', text: 'Switzerland'},
                { id: 'SY', text: 'Syrian Arab Republic'},
                { id: 'TW', text: 'Taiwan'},
                { id: 'TJ', text: 'Tajikistan'},
                { id: 'TZ', text: 'Tanzania'},
                { id: 'TH', text: 'Thailand'},
                { id: 'TL', text: 'Timor-Leste'},
                { id: 'TG', text: 'Togo'},
                { id: 'TK', text: 'Tokelau'},
                { id: 'TO', text: 'Tonga'},
                { id: 'TT', text: 'Trinidad And Tobago'},
                { id: 'TN', text: 'Tunisia'},
                { id: 'TR', text: 'Turkey'},
                { id: 'TM', text: 'Turkmenistan'},
                { id: 'TC', text: 'Turks And Caicos Islands'},
                { id: 'TV', text: 'Tuvalu'},
                { id: 'UG', text: 'Uganda'},
                { id: 'UA', text: 'Ukraine'},
                { id: 'AE', text: 'United Arab Emirates'},
                { id: 'GB', text: 'United Kingdom'},
                { id: 'US', text: 'United States'},
                { id: 'UM', text: 'United States Outlying Islands'},
                { id: 'UY', text: 'Uruguay'},
                { id: 'UZ', text: 'Uzbekistan'},
                { id: 'VU', text: 'Vanuatu'},
                { id: 'VE', text: 'Venezuela'},
                { id: 'VN', text: 'Viet Nam'},
                { id: 'VG', text: 'Virgin Islands}, British'},
                { id: 'VI', text: 'Virgin Islands}, U.S.'},
                { id: 'WF', text: 'Wallis And Futuna'},
                { id: 'EH', text: 'Western Sahara'},
                { id: 'YE', text: 'Yemen'},
                { id: 'ZM', text: 'Zambia'},
                { id: 'ZW', text: 'Zimbabwe'}
            ];
            
            function formatCountry (country) {
              if (!country.id) { return country.text; }
              var $country = $(
                '<span class="flag-icon flag-icon-'+ country.id.toLowerCase() +' flag-icon-squared"></span>' +
                '<span class="flag-text">'+ country.text+"</span>"
              );
              return $country;
            };
            
            //Assuming you have a select element with name country
            // e.g. <select name="name"></select>
            
            $("[name='country']").select2({
                placeholder: "Select a country",
				templateResult: formatCountry,
                data: isoCountries
            });
            
            // Set default country to United States
            $("[name='country']").val('PH').trigger('change'); // 'US' is the ISO code for United States
        });
})(jQuery);            
</script>
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
                    <a href="https://dev.websiteprojectupdates.com/cart/" tabindex="1"
                        class="button wc-forward wp-element-button">View cart</a> “South Room A” has been added to your
                    cart. </div>
                <p></p>
            </div>
        </div>
        <div class="woocommerce-notices-wrapper"></div>
        <form name="checkout" method="get" class="checkout woocommerce-checkout" id="checkout-form"
            action="<?php echo home_url().'/save-order'; ?>" enctype="multipart/form-data" novalidate="novalidate">
            <p></p>
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
                                    <select name="billing_country" id="billing_country"
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
                                            for="booking_notes" class="">Booking Notes&nbsp;</label><span
                                            class="woocommerce-input-wrapper"><textarea name="booking_notes"
                                                class="input-text " id="booking_notes"
                                                placeholder="Some random notes here......" rows="2"
                                                cols="5"></textarea></span></p>
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
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="checkin" value="<?php echo $checkin; ?>">
                        <input type="hidden" name="checkout" value="<?php echo $checkout; ?>">
                        <input type="hidden" name="number_or_hours" value="<?php echo $timeDifferenceHours; ?>">
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
                                        <input type="hidden" name="overall_total"
                                            value="<?php echo ($total_amount + 50); ?>">
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
                                            <a href="https://dev.websiteprojectupdates.com/?page_id=3"
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
            }
            else {
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
</script>
<?php 
    $id = get_the_ID();
    $map = get_field('cta_map_link', $id);
?>
<div class="wp-block-group product-single-page__book-form has-base-2-background-color has-background has-global-padding is-layout-constrained wp-container-core-group-layout-6 wp-block-group-is-layout-constrained"
    style="border-radius:16px;padding-top:var(--wp--preset--spacing--10);padding-right:var(--wp--preset--spacing--10);padding-bottom:var(--wp--preset--spacing--10);padding-left:var(--wp--preset--spacing--10)">
    <h3 class="wp-block-heading has-display-xs-font-size" style="font-style:normal;font-weight:600">Book now</h3>

    <div class="gf_browser_chrome gform_wrapper gravity-theme gform-theme--no-framework book__now-form_wrapper"
        id="gform_wrapper_2">
        <form method="get" enctype="multipart/form-data" id="gform_2" class="book__now-form" action="/check-out/"
            data-formid="2" novalidate="">
            <input type="hidden" name="formtoken" value="L1dsrqjQNca4Bado4M17I1iWPqZOLk69swvTxWkjN6tknx2C00JgJudIgb68Ul65c1eeO0Wmzoc6h7EdX2mdP">
            <input type="hidden" name="add-to-cart" value="<?php echo $id; ?>">
            <input type="hidden" name="quantity" value="1">
            <div class="gform-body gform_body">
                <div id="gform_fields_2" class="gform_fields top_label form_sublabel_below description_below">
                    <div id="field_2_6"
                        class="gfield gfield--type-text gfield--width-full field_sublabel_below gfield--no-description field_description_below hidden_label gfield_visibility_visible"
                        data-js-reload="field_2_6">
                        <div class="ginput_container ginput_container_text"><input name="room_name" id="input_2_6"
                                type="text" value="<?php echo get_the_title(); ?>" class="large"
                                placeholder="Meeting Room Name" aria-invalid="false" readonly>
                        </div>
                    </div>
                    <div id="field_2_7"
                        class="gfield gfield--type-number gfield--width-full field_sublabel_below gfield--no-description field_description_below hidden_label gfield_visibility_visible"
                        data-js-reload="field_2_7">
                        <div class="ginput_container ginput_container_number"><input name="number_of_seats"
                                id="input_2_7" type="number" step="any" value="<?php echo get_field('room_description_maximum_number_of_seats', $id); ?>" class="large"
                                max="<?php echo get_field('room_description_maximum_number_of_seats', $id); ?>"
                                placeholder="Number of Seats" aria-invalid="false" readonly></div>
                    </div>
                    <div id="field_2_8"
                        class="gfield gfield--type-date gfield--input-type-datepicker gfield--datepicker-no-icon gfield--width-full field_sublabel_below gfield--no-description field_description_below hidden_label gfield_visibility_visible"
                        data-js-reload="field_2_8">
                        <div class="ginput_container ginput_container_date">
                            <input name="checkin" id="input_2_8" type="text" value=""
                                class="datepicker gform-datepicker mdy datepicker_no_icon gdatepicker-no-icon hasDatepicker initialized"
                                placeholder="Check-In" aria-describedby="input_2_8_date_format" aria-invalid="false"
                                readonly><br>
                        </div>

                    </div>
                    <div id="field_2_9"
                        class="gfield gfield--type-date gfield--input-type-datepicker gfield--datepicker-no-icon gfield--width-full field_sublabel_below gfield--no-description field_description_below hidden_label gfield_visibility_visible"
                        data-js-reload="field_2_9">
                        <div class="ginput_container ginput_container_date">
                            <input name="checkout" id="input_2_9" type="text" value=""
                                class="datepicker gform-datepicker mdy datepicker_no_icon gdatepicker-no-icon hasDatepicker initialized"
                                placeholder="Check-Out" aria-describedby="input_2_9_date_format" aria-invalid="false"
                                readonly><br>
                        </div>

                    </div>
                    <fieldset id="field_2_12"
                        class="gfield gfield--type-radio gfield--type-choice gfield--width-full field_sublabel_below gfield--no-description field_description_below gfield_visibility_visible"
                        data-js-reload="field_2_12">
                        <legend class="gfield_label gform-field-label">Booking Type</legend>
                        <div class="ginput_container ginput_container_radio">
                            <div class="gfield_radio" id="input_2_12">
                                <div class="gchoice gchoice_2_12_0">
                                    <input class="gfield-choice-input" name="rate" type="radio" value="Hourly Rate (<?php echo 'Php' . number_format(get_field('rates_hourly_rate', $id), 2, '.', ','); ?>
                                        / hr)" id="choice_2_12_0"><br>
                                    <label for="choice_2_12_0" id="label_2_12_0"
                                        class="gform-field-label gform-field-label--type-inline">Hourly Rate
                                        (<?php echo 'Php' . number_format(get_field('rates_hourly_rate', $id), 2, '.', ','); ?>
                                        / hr)</label>
                                </div>
                                <?php 
                                    $daily_rate = get_field('rates_daily_rate', $id);

                                    if(!empty($daily_rate)) {
                                        ?>
                                <div class="gchoice gchoice_2_12_1">
                                    <input class="gfield-choice-input" name="rate" type="radio"
                                        value="Whole Day Rate
                                        (<?php echo 'Php' . number_format(get_field('rates_daily_rate', $id), 2, '.', ','); ?>)" id="choice_2_12_1"><br>
                                    <label for="choice_2_12_1" id="label_2_12_1"
                                        class="gform-field-label gform-field-label--type-inline">Whole Day Rate
                                        (<?php echo 'Php' . number_format(get_field('rates_daily_rate', $id), 2, '.', ','); ?>)</label>
                                </div>
                                <?php
                                    }
                                ?>

                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
            <div class="gform_footer top_label"> <input type="submit" id="gform_submit_button_2"
                    class="gform_button button gform-button--width-full" value="Book Now"
                    onclick="if(window[&quot;gf_submitting_2&quot;]){return false;}  if( !jQuery(&quot;#gform_2&quot;)[0].checkValidity || jQuery(&quot;#gform_2&quot;)[0].checkValidity()){window[&quot;gf_submitting_2&quot;]=true;}  "
                    onkeypress="if( event.keyCode == 13 ){ if(window[&quot;gf_submitting_2&quot;]){return false;} if( !jQuery(&quot;#gform_2&quot;)[0].checkValidity || jQuery(&quot;#gform_2&quot;)[0].checkValidity()){window[&quot;gf_submitting_2&quot;]=true;}  jQuery(&quot;#gform_2&quot;).trigger(&quot;submit&quot;,[true]); }"><br>

            </div>
        </form>
    </div>
</div>
<div class="wp-block-group product-single-page__map has-base-2-background-color has-background has-global-padding is-layout-constrained wp-block-group-is-layout-constrained"
    style="border-radius:16px;min-height:400px;padding-top:0px;padding-bottom:0px">
    <iframe src="<?php echo $map; ?>" width="100%" height="auto" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
<?php
            // Set the timezone to Philippines
            date_default_timezone_set('Asia/Manila');

            // Assuming you have start and end times as strings in PHP
            $start = get_field('operating_hours_start', $id);
            $end = get_field('operating_hours_end', $id);


            // Convert start and end times to timestamps
            $startTimestamp = strtotime($start);

            // $startTimestamp = strtotime('-30 minutes', $startTimestamp);

            $endTimestamp = strtotime($end);


            // Calculate the difference in seconds
            $timeDifference = $endTimestamp - $startTimestamp;
            // Check if the difference is exactly 24 hours (86400 seconds)
            if ($timeDifference === 88200) {
                $mobiscrollFormat = null; // or $mobiscrollFormat = '';
            } else {
                // Convert start and end times to 24-hour format
                $start24Hour = date('H:i', $startTimestamp);
                $end24Hour = date('H:i', $endTimestamp);
                // Create the formatted string for Mobiscroll
                // $mobiscrollFormat = "[{ start: '00:00', end: '{$start24Hour}' }, { start: '{$end24Hour}', end: '23:30' }]";
                // echo $mobiscrollFormat;
            }

            $operating_day_starts = get_field('operating_days_starts', $id);
            $operating_day_ends = get_field('operating_days_ends', $id);
           
        
            function getInvalidWeekDays($start, $end) {
                $validDays = ['mo', 'tu', 'we', 'th', 'fr', 'sa', 'su'];
            
                $startIdx = array_search(strtolower($start), $validDays);
                $endIdx = array_search(strtolower($end), $validDays);
            
                $invalidDays = [];
            
                // Check if both start and end are valid days
                if ($startIdx === false || $endIdx === false) {
                    return 'Invalid start or end day';
                }
            
                // Check if the range is valid
                $range = array_slice($validDays, $startIdx, $endIdx - $startIdx + 1);
            
                // Build array of invalid days
                foreach ($validDays as $day) {
                    if (!in_array($day, $range)) {
                        $invalidDays[] = $day;
                    }
                }
            
                return implode(',', $invalidDays);
            }
            
            $invalidDaysString = getInvalidWeekDays($operating_day_starts, $operating_day_ends);

        ?>
<script>
	// setup Mobiscroll Moment plugin
    jQuery(document).ready(function ($) {
		var baseURL = window.location.protocol + "//" + window.location.host + "/";
        function showDatePicker(selectedDate, invalidDate) {
            function timeToMinutes(time) {
                var parts = time.split(':');
                return parseInt(parts[0]) * 60 + parseInt(parts[1]);
            }
            var minHour = '<?php echo $start24Hour; ?>'
            var maxHour = '<?php echo $end24Hour;  ?>';
            // Get the current date and time
            var currentDate = new Date();
            // Get the current hour (in 24-hour format) and format it as "00:00"
            var currentHour = currentDate.getHours().toString().padStart(2, '0');

            // Get the current minute and format it as "00"
            var currentMinute = currentDate.getMinutes().toString().padStart(2, '0');

            // Create a string in the "HH:mm" format
            var formattedTime = currentHour + ":" + currentMinute;

            var currentMinutes = timeToMinutes(formattedTime);
            var comparisonMinutesMin = timeToMinutes(minHour);
            var comparisonMinutesMax = timeToMinutes(maxHour);
            // Compare the current date and time with the future date and time
            if (currentDate > selectedDate) {
            

                    if (currentMinutes > comparisonMinutesMin  && currentMinutes < comparisonMinutesMax) {
                        minHour = formattedTime;
                    }
                    else {
                        minHour = '<?php echo $start24Hour; ?>'
                        invalidDate.push( {
                        end: maxHour,
                        start: minHour
                    });

                    }   
                } else {
                    minHour = '<?php echo $start24Hour; ?>'
                }
			
                return $('#demo-range').mobiscroll().datepicker({
                controls: ['timegrid'],
                select: 'range',
                min: minHour,
                max: maxHour,
                touchUi: true,
                display: 'anchored',
                inRangeInvalid: true,
                rangeEndInvalid: true,
                invalid: invalidDate,
                showOnClick: false,
                showOnFocus: false,
                onTempChange: function (event, inst) {
                    
                },
                onChange: function (event, inst) {
                    
                    event.value.map(i => {
                        if(i !== null) {
                            i.setDate(selectedDate.getDate());
                            i.setMonth(selectedDate.getMonth());
                            i.setFullYear(selectedDate.getFullYear());
                        }
                    });


                    function removeTimeZoneAndFormat12Hour(inputDateString) {
                        const inputDate = new Date(inputDateString);
                        const options = { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
                        const outputFormat = inputDate.toLocaleString('en-US', options);

                        return outputFormat;
                    }

                $("#input_2_8").val(removeTimeZoneAndFormat12Hour(event.value[0]));
                    $("#input_2_9").val(removeTimeZoneAndFormat12Hour(event.value[1]));
                    $("#field_2_9 .ginput_container").css("border-color", "");
                    $("#field_2_8 .ginput_container").css("border-color", "");
                },
            }).mobiscroll('getInst');
        }

        function showCalendar(colorData) {
            // Get the current date
        // Initialize the calendar
        var currentDate = new Date();
        var yesterday = new Date(currentDate);  
        yesterday.setDate(currentDate.getDate());

        var calendar = $('#demo-calendar').mobiscroll().datepicker({
            controls: ['calendar'],
            display: 'inline',
            inRangeInvalid: true,
            touchUi: true,
            min: currentDate,
            colors: colorData,
            invalid: [
                <?php 
                    if($invalidDaysString !== '') {
                    ?>
                    {recurring: {
                        repeat: 'weekly',
                        weekDays: '<?php echo $invalidDaysString ; ?>'
                    }}
                    <?php
                    }
                ?>
                
            ],
            onCellClick: function (event, inst) {
				// Define months array for formatting
				const months = [
				  'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
				  'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
				];

				// Get individual components of the date
				const month = months[event.date.getMonth()];
				const day = event.date.getDate();
				const year = event.date.getFullYear();

				// Format the date string
				const formattedDate = `${month} ${day}, ${year}`;

                $("#demo-calendar").addClass('loading');
                $.ajax({
                    url: baseURL+'/wp-json/custom/v1/disabled-dates/?id=<?php echo $id; ?>&current_date='+formattedDate,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Handle the response data
                        showDatePicker(event.date, data).open();
                        $("#demo-calendar").removeClass('loading');
                    },
                    error: function (error) {
                        // Handle errors
                        console.error('Error:', error);
                    }
                });

                        return false;
            },
            onActiveDateChange: function (event, inst) {
                // Your calendar change event logic here
                
            },
            onTempChange: function (event, inst) {
                // Your temp change logic here
            },
        });
        }
        
        

        $.ajax({
            url: baseURL+'/wp-json/custom/v1/completed-orders/?product_id=<?php echo $id; ?>',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                $("#demo-calendar-skeleton").hide();
                showCalendar(data);

            },
            error: function (error) {
                // Handle errors
                console.error('Error:', error);
            }
        });
        var count = 0;

        $('#input_2_7').on('input', function(e) {
            var max = parseFloat($(this).attr('max'));
            var value = parseFloat($(this).val());
            if (value > max) {
                var error_element = $('<span style="font-size: 12px; color: red;">The number of seats exceeds the room capacity of '+max+'!</span>');
                count++;
                if(count === 1) {
                    $("#field_2_7").css({
                        'border' : '1px solid red',
                        'border-radius':'0.5rem'
                    });
                    $(error_element).insertAfter("#field_2_7");
                }
                // Reset the input value to the maximum allowed value
                $(this).val(value);
            }else {
                $("#field_2_7").removeAttr('style');
                $(this).parent().val(value);
                var nextSibling = $("#field_2_7").next();

                if (nextSibling.is('span')) {
                    // Remove the next sibling (which is a span)
                    nextSibling.remove();
                }
                count = 0;
            }
        });

    });
</script>
<?php 
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

   // Assuming you have start and end times as strings in PHP
   $start = get_field('operating_hours_start', $id);
   $end = get_field('operating_hours_end', $id);


    // Convert start and end times to timestamps
    $startTimestamp = strtotime($start);
    $startTimestamp = strtotime('-30 minutes', $startTimestamp);

    $endTimestamp = strtotime($end);
    $endTimestamp = strtotime('+30 minutes', $endTimestamp);

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
        $mobiscrollFormat = "[{ start: '00:00', end: '{$start24Hour}' }, { start: '{$end24Hour}', end: '23:30' }]";
    }

?>
<script>
    jQuery(document).ready(function ($) {
        // Get the current date
        var currentDate = new Date();
        var yesterday = new Date(currentDate);
        yesterday.setDate(currentDate.getDate() - 1);
        var year = currentDate.getUTCFullYear();
        // Get today's month (zero-based)
        var month = currentDate.getUTCMonth();
        // Function to get the number of days in a month
        function daysInMonth(year, month) {
            return new Date(year, month + 1, 0).getDate();
        }

        // Create an array of objects representing each day in the month
        var highlightedDays = [];
        for (var day = 1; day <= daysInMonth(year, month); day++) {
            highlightedDays.push({
                date: new Date(year, month, day),
                background: '#EDF6F6' // Replace with the desired background color
            });
        }

        let invalidData = []; 
     
        <?php
        if (!empty($invalidDaysString)) {
            ?>
            invalidData.push({
                recurring: {
                    repeat: 'weekly',
                    weekDays: '<?php echo $invalidDaysString ; ?>'
                }
            }); <?php
        } ?>

        function handleData(data) {
            if (data.length > 0) {
                data.map(i => invalidData.push(i));

            }
            console.log(invalidData);
            // invalidData.push(data);
            // invalidData.push({
            //     recurring: {
            //         repeat: 'weekly',
            //         weekDays: 'SA,SU'
            //     }
            // });
            // Call the function to initialize the calendar after handling the data
            initializeCalendar();
        }


        $.ajax({
            url: 'http://ayalaland-booking.local/wp-json/custom/v1/disabled-dates/?id=<?php echo $id; ?>',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Handle the response data
                handleData(data);
            },
            error: function (error) {
                // Handle errors
                console.error('Error:', error);
            }
        });



        function initializeCalendar() {
            // Initialize the calendar
            var calendar = $('#demo-calendar').mobiscroll().datepicker({
                controls: ['calendar', 'timegrid'],
                display: 'inline',
                select: 'range',
                inRangeInvalid: true,
                rangeEndInvalid: true,
                invalid: invalidData,
                touchUi: true,
                colors: highlightedDays,
                min: yesterday, // Set the minimum date to yesterday
                onActiveDateChange: function (event, inst) {
                    // Your calendar change event logic here
                },
                onTempChange: function (event, inst) {
                    // Your temp change logic here
                    function formatCustomDate(date) {
                        // Get components from the date
                        var year = date.getFullYear().toString().slice(
                            2); // Get last two digits of the year
                        var month = ('0' + (date.getMonth() + 1)).slice(-2); // Month is zero-based
                        var day = ('0' + date.getDate()).slice(-2);
                        var hours = ('0' + date.getHours()).slice(-2);
                        var minutes = ('0' + date.getMinutes()).slice(-2);

                        // Determine if it's AM or PM
                        var ampm = hours >= 12 ? 'pm' : 'am';

                        // Convert 24-hour format to 12-hour format
                        hours = hours % 12;
                        hours = hours ? hours : 12; // The hour '0' should be '12'

                        // Create the formatted string
                        var formattedDate = year + '-' + month + '-' + day + ' ' + hours + ':' +
                            minutes + ' ' + ampm;

                        return formattedDate;
                    }
                    $("#input_2_8").val(formatCustomDate(event.value[0]));
                    $("#input_2_9").val(formatCustomDate(event.value[1]));
                    $("#field_2_9 .ginput_container").css("border-color", "");
                    $("#field_2_8 .ginput_container").css("border-color", "");
                }
            });
        }


    });
</script>


<?php
                if($mobiscrollFormat !== null) {
                    ?>
            invalid: <?php  echo $mobiscrollFormat; ?>,
                    <?php
                }
            ?>
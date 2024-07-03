<?php

// Register a custom REST API endpoint for calculating working hours
add_action('rest_api_init', function () {
    register_rest_route('v2', '/calculate-working-hours', array(
        'methods' => 'GET',
        'callback' => 'calculate_working_hours',
    ));
});

// Callback function to compute total working hours
function calculate_working_hours(WP_REST_Request $request) {
    $params = $request->get_params();

    // Extract parameters
    $room_id = $params['room_id'];
    $inputTimeFormat = "F j, Y \a\\t h:i:s A";

    // Parse the check-in and check-out times
    $startDateTime = DateTime::createFromFormat($inputTimeFormat, $params['checkin']);
    $endDateTime = DateTime::createFromFormat($inputTimeFormat, $params['checkout']);

    $operatingHoursStart = $params['operatingHoursStart'];
    $operatingHoursEnd = $params['operatingHoursEnd'];
    $operatingDaysStarts = $params['operatingDaysStarts'];
    $operatingDaysEnds = $params['operatingDaysEnds'];

    // Compute total working hours
    $totalWorkingHours = compute_total_working_hours(
        $room_id,
        $startDateTime,
        $endDateTime,
        $operatingHoursStart,
        $operatingHoursEnd,
        $operatingDaysStarts,
        $operatingDaysEnds
    );

    // Prepare response
    $response = array(
        'total_working_hours' => $totalWorkingHours,
    );

    return rest_ensure_response($response);
}

// Function to compute total working hours
function compute_total_working_hours(
    $room_id, 
    $startDateTime, 
    $endDateTime, 
    $operatingHoursStart, 
    $operatingHoursEnd, 
    $operatingDaysStarts, 
    $operatingDaysEnds
) {
    $daysMap = array(
        'MO' => 'Monday',
        'TU' => 'Tuesday',
        'WE' => 'Wednesday',
        'TH' => 'Thursday',
        'FR' => 'Friday',
        'SA' => 'Saturday',
        'SU' => 'Sunday'
    );

    $calculate_total_working_hours = function(
        $room_id, 
        $start, 
        $end, 
        $opHoursStart, 
        $opHoursEnd, 
        $opDaysStart, 
        $opDaysEnd
    ) use ($daysMap) {
        $convertTo24HourFormat = function($time) {
            list($hourMin, $period) = explode(' ', $time);
            list($hours, $minutes) = explode(':', $hourMin);
            $hours = (int) $hours;
            $minutes = (int) $minutes;
            if ($period === 'PM' && $hours !== 12) $hours += 12;
            if ($period === 'AM' && $hours === 12) $hours = 0;
            return $hours + ($minutes / 60);
        };

        $getOperatingDays = function($start, $end) {
            $days = array('MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU');
            $startIndex = array_search($start, $days);
            $endIndex = array_search($end, $days);
            return array_slice($days, $startIndex, $endIndex - $startIndex + 1);
        };

        $workHoursStart = $convertTo24HourFormat($opHoursStart);
        $workHoursEnd = $convertTo24HourFormat($opHoursEnd);
        $operatingDays = $getOperatingDays($opDaysStart, $opDaysEnd);

        $totalHours = 0;
        $current = clone $start;

        while ($current <= $end) {
            $currentDay = strtoupper(substr($current->format('D'), 0, 2));

            if (in_array($currentDay, $operatingDays)) {
                $dayStart = clone $current;
                $dayEnd = clone $current;
                $currentDate = clone $current;

                $dayStart->setTime(max($workHoursStart, $current->format('G')), $current->format('i'));
                $dayEnd->setTime(min($workHoursEnd, $end->format('G')), $end->format('i'));

                if ($currentDay === $operatingDays[0] && $current < $dayStart) {
                    $current = clone $dayStart;
                }

                if ($current->format('Y-m-d') !== $end->format('Y-m-d')) {
                    $dayEnd->setTime(max($workHoursEnd, $end->format('G')), 0, 0, 0);
                }
                

                
                if ($current <= $end) {
                    $hours = ($dayEnd->getTimestamp() - $current->getTimestamp()) / 3600;
                    $data = get_disabled_dates_by_product_id_callback_util($room_id, $currentDate);
                    $totalBookedHours = 0;
                    
                    if (count($data) > 0) {


                        foreach ($data as $interval) {
                            $startTime = explode(':', $interval['start']);
                            $endTime = explode(':', $interval['end']);
                            
                            $startHours = (int)$startTime[0];
                            $startMinutes = (int)$startTime[1];
                            $endHours = (int)$endTime[0];
                            $endMinutes = (int)$endTime[1];
                            
                            $startDate = mktime($startHours, $startMinutes);
                            $endDate = mktime($endHours, $endMinutes);
                            
                            $diff = $endDate - $startDate;
                            $disabledHours = $diff / (60 * 60); // Convert seconds to disabledHours
                            // You would need to adjust this condition based on your PHP environment
                            if (
                                $endHours <= $end->format('G') && $endMinutes <= $end->format('i') &&
                                $startHours >= $current->format('G') && $startMinutes >= $current->format('i')
                                ) {


                                $totalBookedHours += $disabledHours;
                            }
                        }
                        
                    }
                    $hours = $hours - $totalBookedHours;

                    if ($hours > 0) $totalHours += $hours;
                }
            }

            $current->modify('+1 day');
            $current->setTime($workHoursStart, 0, 0);
        }

        return $totalHours;
    };

    return $calculate_total_working_hours(
        $room_id, 
        $startDateTime, 
        $endDateTime, 
        $operatingHoursStart, 
        $operatingHoursEnd, 
        $operatingDaysStarts, 
        $operatingDaysEnds
    );
}


function get_disabled_dates_by_product_id_callback_util($product_id, $date) {
    global $wpdb;
	
    // $current_date = isset($date) ? $date : date('D M d Y H:i:s T');

    // Prepare and execute the SQL query
    $query = $wpdb->prepare("
    SELECT checkin.meta_value as checkin_value, checkout.meta_value as checkout_value
    FROM {$wpdb->prefix}wc_order_product_lookup AS lookup
    INNER JOIN {$wpdb->prefix}wc_orders AS orders ON lookup.order_id = orders.id
    LEFT JOIN {$wpdb->prefix}postmeta AS checkin ON orders.id = checkin.post_id AND checkin.meta_key = 'checkin'
    LEFT JOIN {$wpdb->prefix}postmeta AS checkout ON orders.id = checkout.post_id AND checkout.meta_key = 'checkout'
    WHERE lookup.product_id = %d
    AND orders.status != 'trash'
", $product_id);
    $orders = $wpdb->get_results($query);


    $disabled_dates = array();
	// Convert the original date string to a Unix timestamp using strtotime
	// $timestamp = strtotime($current_date);

	// Format the timestamp into the desired format
    $formattedDate = $date->format('Y-m-d');


    foreach ($orders as $order) {
        $checkin_time_24hr = date('H:i', strtotime($order->checkin_value));
        $checkout_time_24hr = date('H:i', strtotime($order->checkout_value));
        $order_date = date('Y-m-d', strtotime($order->checkin_value));
        
        if($order_date == $formattedDate) {
            $disabled_dates[] = [
                "start" => $checkin_time_24hr, 
                "end" => $checkout_time_24hr,
            ];
        }
    }
    return $disabled_dates;
}
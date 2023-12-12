jQuery(document).ready(function($) {

    // Initialize Select2
    $('#input_1_8').select2({
        placeholder: 'Search Location',
        ajax: {
            url: ajax_object.ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                console.log(params);
                return {
                    q: params.term,
                    action: 'my_ajax_action',
                };
            },
            processResults: function (data) {
                // Log the raw data to the console
                console.log('Raw AJAX Data:', data);

                return {
                    results: data
                };
            },
            cache: true
        }
    });

    // Event listener for the select2:select event
    $(document).on('select2:select', '#input_1_8', function (e) {
        var selectedData = e.params.data;
        var selectedValueElement = $('.select2-selection__placeholder');
        // Update the value of the 'selected-value' element
        selectedValueElement.text(selectedData.text);
    });

   // Function to update the value of a selected element based on a URL parameter
    function updateInputValue(paramName, targetElementId) {
        // Get the query string from the current URL
        var queryString = window.location.search;

        // Create a URLSearchParams object to parse the query string
        var urlParams = new URLSearchParams(queryString);

        // Get the value of the specified parameter
        var paramValue = urlParams.get(paramName);

        // Update the value of the specified input element
        $(targetElementId).val(paramValue);
    }

// Function to update the value of a selected element based on a URL parameter
function updateSelect2Value(paramName, targetElementId) {
    // Get the query string from the current URL
    var queryString = window.location.search;

    // Create a URLSearchParams object to parse the query string
    var urlParams = new URLSearchParams(queryString);

    // Get the value of the specified parameter
    var paramValue = urlParams.get(paramName);
    if(paramValue !== null) {
    // Make an AJAX request
    $(targetElementId+" .select2-selection__placeholder").text('');

    $.ajax({
        url: ajax_api_object.ajax_api_url+'/wp/v2/locations/'+paramValue,
        type: 'GET',
        success: function(data) {
            // Handle the data received from the API
            // Update the value of the specified input element
            $(targetElementId+" .select2-selection__placeholder").text(data.title.rendered);

        },
        error: function(error) {
            // Handle errors
            console.error('Error fetching data:', error);
        }
    });
    }

   

    
}

// Update the values for number_of_seats, checkin, and checkout
updateInputValue('number_of_seats', '#input_1_16');
updateInputValue('checkin', '#input_1_21');
updateInputValue('checkout', '#input_1_22');
updateInputValue('room_location', '#input_1_8');
updateSelect2Value('room_location', "#field_1_8");




  
});

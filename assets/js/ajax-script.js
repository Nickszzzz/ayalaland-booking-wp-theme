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
});

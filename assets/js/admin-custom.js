jQuery(document).ready(function ($) {

    $('#order .save_order').on('click', function (e) {

        var order_status = $('#select2-order_status-container').attr('title');
        if (order_status === 'Admin Cancelled Booking') {
            e.preventDefault();

            var cancel_reason = $("#cancel_reason");
            if (cancel_reason.val() === '') {
                var targetOffset = cancel_reason.offset().top;
                // Animate the scroll to the target element
                $('html, body').animate({
                    scrollTop: targetOffset - 300
                }, 1000); // You can adjust the duration (in milliseconds) as needed

                cancel_reason.css({
                    'border-color': 'red',
                    // Add more styles as needed
                });
            } else {
                $("#order").submit();
            }
        }
        //else if(order_status === 'Pending Payment') {
        //e.preventDefault();

        //   var payment_gateway_link = $("#payment_gateway_link");
        // if(payment_gateway_link.val() === '') {
        //    var targetOffset = payment_gateway_link.offset().top;
        // Animate the scroll to the target element
        //    $('html, body').animate({
        //        scrollTop: targetOffset-300
        //   }, 1000); // You can adjust the duration (in milliseconds) as needed

        //  payment_gateway_link.css({
        //       'border-color': 'red',
        // Add more styles as needed
        //   });
        // }else {
        //     $("#order").submit();
        // }

        // }
        else {
            $("#order").submit();
        }
    });


    $('#acf-group_667d112924318 input').prop('disabled', true);

});
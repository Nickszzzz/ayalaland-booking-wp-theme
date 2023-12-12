<?php

function custom_login_logo() {
    $logo_link = home_url();
    $logo_url = $logo_link.'/wp-content/uploads/2023/11/AyalaLand-Logo.png'; // Replace with the URL of your logo image
    echo '<style type="text/css">
        h1 a { background-image:url(' . $logo_url . ') !important; background-size: 100px 100px !important; width: 100px !important; height: 100px !important; margin-bottom: 0 !important;}
        h1 {
            background: #30704c;
            padding: 0.5rem !important;
        }
    </style>';
    echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            var loginLogoLink = document.querySelector(".login h1 a");
            loginLogoLink.href = "' . $logo_link . '";
        });
    </script>';
}
add_action('login_head', 'custom_login_logo');

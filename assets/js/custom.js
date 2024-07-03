jQuery(document).ready(function ($) {


  var scroll = $(window).scrollTop();

  if ($("header").length > 0) {
    if (scroll >= 200) {
      $("header").addClass("scrolled");
    }
    $(window).scroll(function () {
      var scroll = $(window).scrollTop();
      if (scroll >= 200) {
        $("header").addClass("scrolled");
      } else {
        $("header").removeClass("scrolled");
      }
    });
  }

  // if ($(".is-mobile-header").length > 0) {
  //     if (scroll >= 100) {
  //         $(".is-mobile-header").addClass("scrolled");
  //     }
  //     $(window).scroll(function () {
  //         var scroll = $(window).scrollTop();
  //         if (scroll >= 100) {
  //             $(".is-mobile-header").addClass("scrolled");
  //         } else {
  //             $(".is-mobile-header").removeClass("scrolled");
  //         }
  //     });
  // }

  // if ($(".bottom-header").length > 0) {
  //     if (scroll >= 100) {
  //         $(".bottom-header").addClass("scrolled");
  //     }
  //     $(window).scroll(function () {
  //         var scroll = $(window).scrollTop();
  //         if (scroll >= 100) {
  //             $(".bottom-header").addClass("scrolled");
  //         } else {
  //             $(".bottom-header").removeClass("scrolled");
  //         }
  //     });
  // }

  var swiper = new Swiper(".featured__meeting-rooms-slider", {
    slidesPerView: 2,
    grid: {
      rows: 2,
    },
    spaceBetween: 8,
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      601: {
        slidesPerView: 3,
        spaceBetween: 8,
      },
      769: {
        slidesPerView: 2,
        spaceBetween: 16,
      },
      1025: {
        slidesPerView: 3,
        spaceBetween: 24,
      },
      // 1440: {
      //     slidesPerView: 3,
      //     spaceBetween: 24,
      // },
    },
  });


  var homeswiper = new Swiper(".home-slider-wrapper", {
    slidesPerView: 1,
    spaceBetween: 0,
    pagination: {
      el: ".swiper-pagination",
      dynamicBullets: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    autoplay: {
      delay: 15000,
      disableOnInteraction: false,
    },
    loop: true, // Enable loop mode
  });

  $('.popup-youtube').magnificPopup({
    type: 'iframe'
  });


  // Get the current date
  var today = new Date();

  // Format the current date as YYYY-MM-DD (the format used by the datepicker)
  var formattedToday = today.toISOString().split('T')[0];

  // Add a class to the datepicker input
  $('.wp-block-calendar input').addClass('disable-past-dates');

  // Set the min attribute to the current date
  $('.disable-past-dates').attr('min', formattedToday);



  $('.accordion-header').on('click', function () {
    $(this).next('.accordion-content').slideToggle();
    console.log($(this).next('.accordion-content'));
    $('.accordion-content').not($(this).next('.accordion-content')).slideUp();
  });

});





jQuery(document).ready(function ($) {
  $("#gform_submit_button_2").on('click', function (e) {
    var max = parseFloat($("#input_2_7").attr('max'));
    var value = parseFloat($("#input_2_7").val());
    value = isNaN(value) ? 0 : value;
    if (!$("#input_2_7").val() || !$("#input_2_8").val() || !$("#input_2_9").val() || !$('input[name="rate"]:checked').length > 0 || value > max) {
      e.preventDefault();
      
      // console.log(value > max);
      console.log(value);
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

});


(function ($) {
  $(function () {
    var isoCountries = [{
        id: 'AF',
        text: 'Afghanistan'
      },
      {
        id: 'AX',
        text: 'Aland Islands'
      },
      {
        id: 'AL',
        text: 'Albania'
      },
      {
        id: 'DZ',
        text: 'Algeria'
      },
      {
        id: 'AS',
        text: 'American Samoa'
      },
      {
        id: 'AD',
        text: 'Andorra'
      },
      {
        id: 'AO',
        text: 'Angola'
      },
      {
        id: 'AI',
        text: 'Anguilla'
      },
      {
        id: 'AQ',
        text: 'Antarctica'
      },
      {
        id: 'AG',
        text: 'Antigua And Barbuda'
      },
      {
        id: 'AR',
        text: 'Argentina'
      },
      {
        id: 'AM',
        text: 'Armenia'
      },
      {
        id: 'AW',
        text: 'Aruba'
      },
      {
        id: 'AU',
        text: 'Australia'
      },
      {
        id: 'AT',
        text: 'Austria'
      },
      {
        id: 'AZ',
        text: 'Azerbaijan'
      },
      {
        id: 'BS',
        text: 'Bahamas'
      },
      {
        id: 'BH',
        text: 'Bahrain'
      },
      {
        id: 'BD',
        text: 'Bangladesh'
      },
      {
        id: 'BB',
        text: 'Barbados'
      },
      {
        id: 'BY',
        text: 'Belarus'
      },
      {
        id: 'BE',
        text: 'Belgium'
      },
      {
        id: 'BZ',
        text: 'Belize'
      },
      {
        id: 'BJ',
        text: 'Benin'
      },
      {
        id: 'BM',
        text: 'Bermuda'
      },
      {
        id: 'BT',
        text: 'Bhutan'
      },
      {
        id: 'BO',
        text: 'Bolivia'
      },
      {
        id: 'BA',
        text: 'Bosnia And Herzegovina'
      },
      {
        id: 'BW',
        text: 'Botswana'
      },
      {
        id: 'BV',
        text: 'Bouvet Island'
      },
      {
        id: 'BR',
        text: 'Brazil'
      },
      {
        id: 'IO',
        text: 'British Indian Ocean Territory'
      },
      {
        id: 'BN',
        text: 'Brunei Darussalam'
      },
      {
        id: 'BG',
        text: 'Bulgaria'
      },
      {
        id: 'BF',
        text: 'Burkina Faso'
      },
      {
        id: 'BI',
        text: 'Burundi'
      },
      {
        id: 'KH',
        text: 'Cambodia'
      },
      {
        id: 'CM',
        text: 'Cameroon'
      },
      {
        id: 'CA',
        text: 'Canada'
      },
      {
        id: 'CV',
        text: 'Cape Verde'
      },
      {
        id: 'KY',
        text: 'Cayman Islands'
      },
      {
        id: 'CF',
        text: 'Central African Republic'
      },
      {
        id: 'TD',
        text: 'Chad'
      },
      {
        id: 'CL',
        text: 'Chile'
      },
      {
        id: 'CN',
        text: 'China'
      },
      {
        id: 'CX',
        text: 'Christmas Island'
      },
      {
        id: 'CC',
        text: 'Cocos (Keeling) Islands'
      },
      {
        id: 'CO',
        text: 'Colombia'
      },
      {
        id: 'KM',
        text: 'Comoros'
      },
      {
        id: 'CG',
        text: 'Congo'
      },
      {
        id: 'CD',
        text: 'Congo}, Democratic Republic'
      },
      {
        id: 'CK',
        text: 'Cook Islands'
      },
      {
        id: 'CR',
        text: 'Costa Rica'
      },
      {
        id: 'CI',
        text: 'Cote D\'Ivoire'
      },
      {
        id: 'HR',
        text: 'Croatia'
      },
      {
        id: 'CU',
        text: 'Cuba'
      },
      {
        id: 'CY',
        text: 'Cyprus'
      },
      {
        id: 'CZ',
        text: 'Czech Republic'
      },
      {
        id: 'DK',
        text: 'Denmark'
      },
      {
        id: 'DJ',
        text: 'Djibouti'
      },
      {
        id: 'DM',
        text: 'Dominica'
      },
      {
        id: 'DO',
        text: 'Dominican Republic'
      },
      {
        id: 'EC',
        text: 'Ecuador'
      },
      {
        id: 'EG',
        text: 'Egypt'
      },
      {
        id: 'SV',
        text: 'El Salvador'
      },
      {
        id: 'GQ',
        text: 'Equatorial Guinea'
      },
      {
        id: 'ER',
        text: 'Eritrea'
      },
      {
        id: 'EE',
        text: 'Estonia'
      },
      {
        id: 'ET',
        text: 'Ethiopia'
      },
      {
        id: 'FK',
        text: 'Falkland Islands (Malvinas)'
      },
      {
        id: 'FO',
        text: 'Faroe Islands'
      },
      {
        id: 'FJ',
        text: 'Fiji'
      },
      {
        id: 'FI',
        text: 'Finland'
      },
      {
        id: 'FR',
        text: 'France'
      },
      {
        id: 'GF',
        text: 'French Guiana'
      },
      {
        id: 'PF',
        text: 'French Polynesia'
      },
      {
        id: 'TF',
        text: 'French Southern Territories'
      },
      {
        id: 'GA',
        text: 'Gabon'
      },
      {
        id: 'GM',
        text: 'Gambia'
      },
      {
        id: 'GE',
        text: 'Georgia'
      },
      {
        id: 'DE',
        text: 'Germany'
      },
      {
        id: 'GH',
        text: 'Ghana'
      },
      {
        id: 'GI',
        text: 'Gibraltar'
      },
      {
        id: 'GR',
        text: 'Greece'
      },
      {
        id: 'GL',
        text: 'Greenland'
      },
      {
        id: 'GD',
        text: 'Grenada'
      },
      {
        id: 'GP',
        text: 'Guadeloupe'
      },
      {
        id: 'GU',
        text: 'Guam'
      },
      {
        id: 'GT',
        text: 'Guatemala'
      },
      {
        id: 'GG',
        text: 'Guernsey'
      },
      {
        id: 'GN',
        text: 'Guinea'
      },
      {
        id: 'GW',
        text: 'Guinea-Bissau'
      },
      {
        id: 'GY',
        text: 'Guyana'
      },
      {
        id: 'HT',
        text: 'Haiti'
      },
      {
        id: 'HM',
        text: 'Heard Island & Mcdonald Islands'
      },
      {
        id: 'VA',
        text: 'Holy See (Vatican City State)'
      },
      {
        id: 'HN',
        text: 'Honduras'
      },
      {
        id: 'HK',
        text: 'Hong Kong'
      },
      {
        id: 'HU',
        text: 'Hungary'
      },
      {
        id: 'IS',
        text: 'Iceland'
      },
      {
        id: 'IN',
        text: 'India'
      },
      {
        id: 'ID',
        text: 'Indonesia'
      },
      {
        id: 'IR',
        text: 'Iran}, Islamic Republic Of'
      },
      {
        id: 'IQ',
        text: 'Iraq'
      },
      {
        id: 'IE',
        text: 'Ireland'
      },
      {
        id: 'IM',
        text: 'Isle Of Man'
      },
      {
        id: 'IL',
        text: 'Israel'
      },
      {
        id: 'IT',
        text: 'Italy'
      },
      {
        id: 'JM',
        text: 'Jamaica'
      },
      {
        id: 'JP',
        text: 'Japan'
      },
      {
        id: 'JE',
        text: 'Jersey'
      },
      {
        id: 'JO',
        text: 'Jordan'
      },
      {
        id: 'KZ',
        text: 'Kazakhstan'
      },
      {
        id: 'KE',
        text: 'Kenya'
      },
      {
        id: 'KI',
        text: 'Kiribati'
      },
      {
        id: 'KR',
        text: 'Korea'
      },
      {
        id: 'KW',
        text: 'Kuwait'
      },
      {
        id: 'KG',
        text: 'Kyrgyzstan'
      },
      {
        id: 'LA',
        text: 'Lao People\'s Democratic Republic'
      },
      {
        id: 'LV',
        text: 'Latvia'
      },
      {
        id: 'LB',
        text: 'Lebanon'
      },
      {
        id: 'LS',
        text: 'Lesotho'
      },
      {
        id: 'LR',
        text: 'Liberia'
      },
      {
        id: 'LY',
        text: 'Libyan Arab Jamahiriya'
      },
      {
        id: 'LI',
        text: 'Liechtenstein'
      },
      {
        id: 'LT',
        text: 'Lithuania'
      },
      {
        id: 'LU',
        text: 'Luxembourg'
      },
      {
        id: 'MO',
        text: 'Macao'
      },
      {
        id: 'MK',
        text: 'Macedonia'
      },
      {
        id: 'MG',
        text: 'Madagascar'
      },
      {
        id: 'MW',
        text: 'Malawi'
      },
      {
        id: 'MY',
        text: 'Malaysia'
      },
      {
        id: 'MV',
        text: 'Maldives'
      },
      {
        id: 'ML',
        text: 'Mali'
      },
      {
        id: 'MT',
        text: 'Malta'
      },
      {
        id: 'MH',
        text: 'Marshall Islands'
      },
      {
        id: 'MQ',
        text: 'Martinique'
      },
      {
        id: 'MR',
        text: 'Mauritania'
      },
      {
        id: 'MU',
        text: 'Mauritius'
      },
      {
        id: 'YT',
        text: 'Mayotte'
      },
      {
        id: 'MX',
        text: 'Mexico'
      },
      {
        id: 'FM',
        text: 'Micronesia}, Federated States Of'
      },
      {
        id: 'MD',
        text: 'Moldova'
      },
      {
        id: 'MC',
        text: 'Monaco'
      },
      {
        id: 'MN',
        text: 'Mongolia'
      },
      {
        id: 'ME',
        text: 'Montenegro'
      },
      {
        id: 'MS',
        text: 'Montserrat'
      },
      {
        id: 'MA',
        text: 'Morocco'
      },
      {
        id: 'MZ',
        text: 'Mozambique'
      },
      {
        id: 'MM',
        text: 'Myanmar'
      },
      {
        id: 'NA',
        text: 'Namibia'
      },
      {
        id: 'NR',
        text: 'Nauru'
      },
      {
        id: 'NP',
        text: 'Nepal'
      },
      {
        id: 'NL',
        text: 'Netherlands'
      },
      {
        id: 'AN',
        text: 'Netherlands Antilles'
      },
      {
        id: 'NC',
        text: 'New Caledonia'
      },
      {
        id: 'NZ',
        text: 'New Zealand'
      },
      {
        id: 'NI',
        text: 'Nicaragua'
      },
      {
        id: 'NE',
        text: 'Niger'
      },
      {
        id: 'NG',
        text: 'Nigeria'
      },
      {
        id: 'NU',
        text: 'Niue'
      },
      {
        id: 'NF',
        text: 'Norfolk Island'
      },
      {
        id: 'MP',
        text: 'Northern Mariana Islands'
      },
      {
        id: 'NO',
        text: 'Norway'
      },
      {
        id: 'OM',
        text: 'Oman'
      },
      {
        id: 'PK',
        text: 'Pakistan'
      },
      {
        id: 'PW',
        text: 'Palau'
      },
      {
        id: 'PS',
        text: 'Palestinian Territory}, Occupied'
      },
      {
        id: 'PA',
        text: 'Panama'
      },
      {
        id: 'PG',
        text: 'Papua New Guinea'
      },
      {
        id: 'PY',
        text: 'Paraguay'
      },
      {
        id: 'PE',
        text: 'Peru'
      },
      {
        id: 'PH',
        text: 'Philippines'
      },
      {
        id: 'PN',
        text: 'Pitcairn'
      },
      {
        id: 'PL',
        text: 'Poland'
      },
      {
        id: 'PT',
        text: 'Portugal'
      },
      {
        id: 'PR',
        text: 'Puerto Rico'
      },
      {
        id: 'QA',
        text: 'Qatar'
      },
      {
        id: 'RE',
        text: 'Reunion'
      },
      {
        id: 'RO',
        text: 'Romania'
      },
      {
        id: 'RU',
        text: 'Russian Federation'
      },
      {
        id: 'RW',
        text: 'Rwanda'
      },
      {
        id: 'BL',
        text: 'Saint Barthelemy'
      },
      {
        id: 'SH',
        text: 'Saint Helena'
      },
      {
        id: 'KN',
        text: 'Saint Kitts And Nevis'
      },
      {
        id: 'LC',
        text: 'Saint Lucia'
      },
      {
        id: 'MF',
        text: 'Saint Martin'
      },
      {
        id: 'PM',
        text: 'Saint Pierre And Miquelon'
      },
      {
        id: 'VC',
        text: 'Saint Vincent And Grenadines'
      },
      {
        id: 'WS',
        text: 'Samoa'
      },
      {
        id: 'SM',
        text: 'San Marino'
      },
      {
        id: 'ST',
        text: 'Sao Tome And Principe'
      },
      {
        id: 'SA',
        text: 'Saudi Arabia'
      },
      {
        id: 'SN',
        text: 'Senegal'
      },
      {
        id: 'RS',
        text: 'Serbia'
      },
      {
        id: 'SC',
        text: 'Seychelles'
      },
      {
        id: 'SL',
        text: 'Sierra Leone'
      },
      {
        id: 'SG',
        text: 'Singapore'
      },
      {
        id: 'SK',
        text: 'Slovakia'
      },
      {
        id: 'SI',
        text: 'Slovenia'
      },
      {
        id: 'SB',
        text: 'Solomon Islands'
      },
      {
        id: 'SO',
        text: 'Somalia'
      },
      {
        id: 'ZA',
        text: 'South Africa'
      },
      {
        id: 'GS',
        text: 'South Georgia And Sandwich Isl.'
      },
      {
        id: 'ES',
        text: 'Spain'
      },
      {
        id: 'LK',
        text: 'Sri Lanka'
      },
      {
        id: 'SD',
        text: 'Sudan'
      },
      {
        id: 'SR',
        text: 'Suriname'
      },
      {
        id: 'SJ',
        text: 'Svalbard And Jan Mayen'
      },
      {
        id: 'SZ',
        text: 'Swaziland'
      },
      {
        id: 'SE',
        text: 'Sweden'
      },
      {
        id: 'CH',
        text: 'Switzerland'
      },
      {
        id: 'SY',
        text: 'Syrian Arab Republic'
      },
      {
        id: 'TW',
        text: 'Taiwan'
      },
      {
        id: 'TJ',
        text: 'Tajikistan'
      },
      {
        id: 'TZ',
        text: 'Tanzania'
      },
      {
        id: 'TH',
        text: 'Thailand'
      },
      {
        id: 'TL',
        text: 'Timor-Leste'
      },
      {
        id: 'TG',
        text: 'Togo'
      },
      {
        id: 'TK',
        text: 'Tokelau'
      },
      {
        id: 'TO',
        text: 'Tonga'
      },
      {
        id: 'TT',
        text: 'Trinidad And Tobago'
      },
      {
        id: 'TN',
        text: 'Tunisia'
      },
      {
        id: 'TR',
        text: 'Turkey'
      },
      {
        id: 'TM',
        text: 'Turkmenistan'
      },
      {
        id: 'TC',
        text: 'Turks And Caicos Islands'
      },
      {
        id: 'TV',
        text: 'Tuvalu'
      },
      {
        id: 'UG',
        text: 'Uganda'
      },
      {
        id: 'UA',
        text: 'Ukraine'
      },
      {
        id: 'AE',
        text: 'United Arab Emirates'
      },
      {
        id: 'GB',
        text: 'United Kingdom'
      },
      {
        id: 'US',
        text: 'United States'
      },
      {
        id: 'UM',
        text: 'United States Outlying Islands'
      },
      {
        id: 'UY',
        text: 'Uruguay'
      },
      {
        id: 'UZ',
        text: 'Uzbekistan'
      },
      {
        id: 'VU',
        text: 'Vanuatu'
      },
      {
        id: 'VE',
        text: 'Venezuela'
      },
      {
        id: 'VN',
        text: 'Viet Nam'
      },
      {
        id: 'VG',
        text: 'Virgin Islands}, British'
      },
      {
        id: 'VI',
        text: 'Virgin Islands}, U.S.'
      },
      {
        id: 'WF',
        text: 'Wallis And Futuna'
      },
      {
        id: 'EH',
        text: 'Western Sahara'
      },
      {
        id: 'YE',
        text: 'Yemen'
      },
      {
        id: 'ZM',
        text: 'Zambia'
      },
      {
        id: 'ZW',
        text: 'Zimbabwe'
      }
    ];

    function formatCountry(country) {
      if (!country.id) {
        return country.text;
      }
      var $country = $(
        '<span class="flag-icon flag-icon-' + country.id.toLowerCase() + ' flag-icon-squared"></span>' +
        '<span class="flag-text">' + country.text + "</span>"
      );
      return $country;
    };

    //Assuming you have a select element with name country
    // e.g. <select name="name"></select>

    $("[name='billing_country']").select2({
      placeholder: "Select a country",
      templateResult: formatCountry,
      data: isoCountries
    });


  });

  $('#gform_submit_button_1').on('click', function(e) {

    if($("#input_1_8").val() || $("#input_1_16").val() || $("#input_1_21").val() || $("#input_1_22").val()) {
      $("#gform_1").submit();

    }else {
      e.preventDefault();
      var currentUrl = window.location.href+'meeting-rooms/';  
      // window.open(currentUrl, '_self');

      console.log(!$("#input_1_19").val());
    }
  });
})(jQuery);



mobiscroll.setOptions({
  theme: 'ios',
  themeVariant: 'light'
});

jQuery(document).ready(function ($) {

  $('#input_1_20').mobiscroll().datepicker({
    controls: ['time'],
    touchUi: true,
    display: 'anchored',
    showOnFocus: true
  });

  $('#input_1_19').mobiscroll().datepicker({
    controls: ['time'],
    touchUi: true,
    display: 'anchored',
    showOnFocus: true
  });


});


gform.addFilter( 'gform_datepicker_options_pre_init', function( optionsObj, formId, fieldId ) {
  // Apply to field 2 only 
  if ( fieldId == 21 || fieldId == 22 ) {
      optionsObj.minDate = 0;
  }
  return optionsObj;
});




jQuery(document).ready(function ($) {
  var baseUrl = window.location.protocol + '//' + window.location.host;
  var logoElement = '<div class="wp-block-site-logo"><a href="'+baseUrl+'" class="custom-logo-link" rel="home" aria-current="page"><img width="280" height="59" src="'+baseUrl+'/wp-content/uploads/2023/11/ayala-logo.png" class="custom-logo" alt="ayala-logo" decoding="async"></a></div>';

  // Append the elements to the specified element with ID #modal-2-content
  $('#modal-2-content').append(logoElement);



  
});


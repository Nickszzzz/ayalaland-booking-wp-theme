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
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      loop: true, // Enable loop mode
    });
    
});
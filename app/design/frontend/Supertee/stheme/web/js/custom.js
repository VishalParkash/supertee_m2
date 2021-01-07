$(document).on('ready', function () {
    // AOS.init();
    $(".productSlider ").slick({
        dots: false,
        // arrows: false,
        prevArrow: $(".sliderNav .prev "),
        nextArrow: $(".sliderNav .next "),
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 1,
        centerMode: true,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true
                }
            },
            {
                breakpoint: 812,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
        ]
    });
});

// mobileMenu
$('.navbar-toggler').on('click', function () {
    $('body').addClass('open-mobileMenu');
});

$('.mobileMenu .close').on('click', function () {
    $('body').removeClass('open-mobileMenu');
});

// SearchBar$('#notification').on('click', function () {
// $('.main').toggleClass('showPanel notification');
// });

$('.search a').on('click', function () {
    $(this).next('.searchInput').toggleClass('openSearch');
});



// FooterLinkAccordian
var windowWidth = $(window).width();
$(".footerLink h6").on("click", function () {
    var windowWidth = $(window).width();
    if (windowWidth < 768) {
        if ($(this).hasClass("footerCollapseClickOpen")) {
            $(this).removeClass("footerCollapseClickOpen");
            $(this).next(".links").removeClass("footerCollapseOpen");
        }
        else {
            $(".footerLink h6").removeClass("footerCollapseClickOpen");
            $(".links").removeClass("footerCollapseOpen");

            $(this).addClass("footerCollapseClickOpen");
            $(this).next(".links").addClass("footerCollapseOpen");
        }
    }
});

// FilterModal
$(window).resize(function () {
    console.log($(window).width());
    if ($(window).width() > 768) {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').addClass("d-none");
    }
    else {
        $('.modal-backdrop').removeClass("d-none");

    }
});

// FilterAccordian

$(".filterWrap .filterHead ").on("click", function () {
    //   if ($(this).hasClass("filterCollapseClickOpen")) {
    //      $(this).removeClass("filterCollapseClickOpen");
    //      $(this).next(".wrap").removeClass("filterCollapseOpen");
    //  }
    //  else {
    //      $(".filterWrap .filterHead ").removeClass("filterCollapseClickOpen");
    //      $(".wrap").removeClass("filterCollapseOpen");

    //      $(this).addClass("filterCollapseClickOpen");
    //      $(this).next(".wrap").addClass("filterCollapseOpen");
    //  }
    $(this).toggleClass("filterCollapseClickOpen");
    $(this).next(".wrap").toggleClass("filterCollapseOpen");

});


// FeatureSlider
$(document).on('ready', function () {
    $(".featureSlider").slick({
        dots: false,
        // arrows: false,
        prevArrow: $(".sliderNav .prev "),
        nextArrow: $(".sliderNav .next "),
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1080,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            }
        ]
    });

});



// OurClient
$(document).on('ready', function () {
    $(".clientSlider ").slick({
        dots: false,
        // arrows: false,
        prevArrow: $(".sliderNav .prev "),
        nextArrow: $(".sliderNav .next "),
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,

    });
});




// new js
$("[dataSidebarRight]").on('click', function () {
    var dataSidebarRightID = $(this).attr("dataSidebarRight");
    if ($("#" + dataSidebarRightID).hasClass("active")) {
        $(".rightPanel").removeClass("sidebarRightBody");
        setTimeout(function () {
            $("#" + dataSidebarRightID).removeClass("active");
            $("body").removeClass("sidebarRightBody");
        }, 300);
    }
    else {
        $(".rightSlide").removeClass("active");
        $("#" + dataSidebarRightID).addClass("active");
        $("body").addClass("sidebarRightBody");
        setTimeout(function () {
            $("#" + dataSidebarRightID).children(".rightPanel").addClass("active");
        }, 100);
    }
});
$(".rightSlideOver, .rightSlideCancel").on("click", function () {
    $(".rightPanel").removeClass("active");
    setTimeout(function () {
        $("body").removeClass("sidebarRightBody");
        $(".rightSlide").removeClass("active");
    }, 300);
});

$('#cart').on('click', function () {
    $('.main').toggleClass('showPanel showCart');
});

// CartPanel
$('#wishlist').on('click', function () {
    $('.main').toggleClass('showPanel showWishlist');
});
$(".shareProductBtn").on("click", function () {
    $(this).toggleClass("active");
    $(this).next(".shareProductIcons").toggleClass("active");
});
// product detail page 
$(".productImgSlider ").slick({
    dots: true,
    // arrows: false,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,

});
$('#productImageModal').on('shown.bs.modal', function () {
    //   $('#productImageModal').trigger('focus');
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '.slider-nav',
        arrows: false,
        infinite: false,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    arrows: false,
                }
            }
        ]
    });

    $('.slider-nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        focusOnSelect: true,
        arrows: false,
        //    centerMode: true,
        // variableWidth:true,
        vertical: true,
        infinite: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    vertical: false,
                }
            }
        ]
    });
});

// listing page
// Filter
$('.filter_box .filterHead').on('click', function () {
    console.log('click');
    $(this).parents('.filter_box').toggleClass('filterOpen');
});


// ListView
$('.btnWrap .listView').on('click', function () {
    $(this).toggleClass('selected');
    $('.btnWrap .gridView').removeClass('selected');
    $('.similarProduct').addClass('list_view');
});


// gridView
$('.btnWrap .gridView ').on('click', function () {
    $(this).addClass('selected');
    $('.btnWrap .listView').removeClass('selected');
    $('.similarProduct').removeClass('list_view');
});
// faq
$('.questionWrap').on('click', function () {
    if ($(this).hasClass('open')) {
        $(this).removeClass('open');
    } else {
        $('.questionWrap').removeClass('open');
    }
    $(this).addClass('open');
});

// OurClient
$(".newProdWrap ").slick({
    dots: false,
    // arrows: false,
    prevArrow: $(".slide-nav .prev "),
    nextArrow: $(".slide-nav .next "),
    infinite: true,
    centerMode: true,
    slidesToShow: 4,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 1,
            }
        },
    ]

});


// genricPageFunctionality
$('.linkWrap a').on('click', function () {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
    } else {
        $('.linkWrap a').removeClass('active');
    }
    $(this).addClass('active');
});

// home 
$(".couponSlider ").slick({
    dots: false,
    arrows: false,
    infinite: true,
    centerMode: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    variableWidth: true,
    swipe: false,
    responsive: [
        {
            breakpoint: 1199,
            settings: {
                swipe: true,
                autoplay: true,
                autoplaySpeed: 3000,
            }
        },
    ]
});
// home
$(".productShots ").slick({
    dots: false,
    // arrows: false,
    prevArrow: $(".sliderNav .prev "),
    nextArrow: $(".sliderNav .next "),
    infinite: true,
    centerMode: true,
    slidesToShow: 2,
    slidesToScroll: 1,
    // autoplay: true,
    autoplaySpeed: 2000,
    responsive: [
        {
            breakpoint: 991,
            settings: {
                slidesToShow: 1,
            }
        },
    ]

});


// menuMobile
$(".btnMenu, .btnMenuClose").on("click", function () {
    if ($(".menuMobile").hasClass("active")) {
        $(".menuMobileInner").removeClass("active");
        setTimeout(function () {
            $("body").removeClass("sidebarRightBody");
            $(".menuMobile").removeClass("active");
        }, 300);
    }
    else {
        $(".menuMobile").addClass("active");
        setTimeout(function () {
            $("body").addClass("sidebarRightBody");
            $(".menuMobileInner").addClass("active");
        }, 100);
    }
});
$(".dropDownItems > a").on("click", function () {
    if ($(this).parents(".dropDownItems").hasClass("active")) {
        $(this).parents(".dropDownItems").removeClass("active");
    }
    else {
        $(".dropDownItems").removeClass("active");
        $(this).parents(".dropDownItems").addClass("active");
    }
});

// $(".menuMobileInner a").on("click", function () {
//     if (!$(this).closest("li").hasClass("dropDownItems")) {
//         $(".btnMenu").trigger("click");
//     }
// });
$(".footerTopItemDrop > h6").on("click", function () {
    if ($(this).parents(".footerTopItemDrop").hasClass("active")) {
        $(this).parents(".footerTopItemDrop").removeClass("active");
    }
    else {
        $(".footerTopItemDrop").removeClass("active");
        $(this).parents(".footerTopItemDrop").addClass("active");
    }
});

// product page 
$(".productDetailImgSlider").slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    // fade: true,
    dots: false,
    asNavFor: '.productImgSliderNav',
    infinite: false,
    responsive: [
        {
            breakpoint: 992,
            settings: {
                dots: true,
            }
        },
    ]
});
$(".productImgSliderNav").slick({
    slidesToShow: 1,
    slidesToScroll: 4,
    asNavFor: '.productDetailImgSlider',
    dots: false,
    arrows: false,
    centerMode: true,
    focusOnSelect: true,
    adaptiveHeight: true,
    infinite: false,
});


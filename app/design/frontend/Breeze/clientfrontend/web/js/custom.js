require([ 'jquery', 'slick', 'bootstrap4'], function($,modal){ 
$(document).ready(function()
{
    // $('#notification').on('click', function () {
    //     $('.mainSite').toggleClass('showNotification');
    // });



    // $('.datePicker').datepicker({
    //     format: 'dd-mm-yyyy',
    //     autoclose: true,
    // });
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


    $(".openModal").on("click", function () {
        var modalId = $(this).attr("dataModal");
        $("#" + modalId).addClass("show");
    });
    $(".closeModal").on("click", function () {
        var modalId = $(this).attr("dataModal");
        $("#" + modalId).removeClass("show");
    });

    // $(".rangeSlider").slider();


    // new js
    $("[dataSidebarRight]").on('click', function () {
        console.log('clicked');
        
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

    // Panel
    $('#wishlist').on('click', function () {
        $('.main').toggleClass('showPanel showWishlist');
    });
    $(".shareProductBtn").on("click", function () {
        $(this).toggleClass("active");
        $(this).next(".shareProductIcons").toggleClass("active");
    });

    // show/hide view coupen
    $('.coupen').on('keyup','input', function(){
        var coupenValue = $(this).val();
        if(coupenValue === ""){
            $(this).next('span').show();
        }else{
            $(this).next('span').hide();
        }
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

});
});

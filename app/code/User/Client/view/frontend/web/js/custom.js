require([ 'jquery', 'slick', 'bootstrap4'], function($,modal){
$(document).ready(function () {
    $('#notification').on('click', function () {
        $('.mainSite').toggleClass('showNotification');
    });

    $('.datePicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });


    $(".openModal").on("click", function () {
        var modalId = $(this).attr("dataModal");
        $("#" + modalId).addClass("show");
    });
    $(".closeModal").on("click", function () {
        var modalId = $(this).attr("dataModal");
        $("#" + modalId).removeClass("show");
    });

    $(".rangeSlider").slider();

    $(".sidebarNavBtn").on("click", function () {
        $(".mainContainer").toggleClass("mainContainerNavClose");
    });
    // $(".sidebarNavLinksChild").on("click", function () {
    //     $(this).parent("li").toggleClass("active");
    //     // $(this).toggleClass("active");
    // });
    $(".dashboardItemTopBtn").on("click", function () {
        $(this).closest(".dashboardItem").children(".dashboardItemContent").toggleClass("d-none");
        $(this).toggleClass("active");
        // $(this).toggleClass("active");
    });
    $(".dropdownLevelItem .dropdownBtn").on("click", function () {
        $(this).toggleClass("active");
        $(this).next(".dropdownLevel").toggleClass("d-none");
    });

    // $('#storeProfilePreviewModal').modal("show");
    
    // sidebarLinkingDropdown
    $('.sidebarNavLinks ul > li a').on('click', function(){
        // $(this).addClass('active');
        if($(this).next('ul').hasClass('show')){
            $(this).next('ul').removeClass('show');
            $(this).parents('li').removeClass('active');

        }else{
            $('.sidebarNavLinks ul > li ul.show').removeClass('show');
            $('.sidebarNavLinks ul li').removeClass('active');
            $(this).next('ul').addClass('show');
            $(this).parent('li').addClass('active');
        }
    });

    $('.sidebarNavLinks ul > li > ul li').on('click', function(e){
        e.stopPropagation;
        console.log('nested');
        $(this).parent('ul').addClass('show');

        if($(this).find('ul').hasClass('show')){
            $(this).addClass('active');
        }
    });

    $(".btnAccordion").on("click", function () {
        if ($(this).hasClass("active") === true) {
            $(this).closest(".accordionCustomItem").removeClass("active");
            $(this).removeClass("active");
        }
        else {
            // $(this).closest(".accordionCustom").children(".accordionCustomItem").removeClass("active");
            // $(this).closest(".accordionCustomItem").addClass("active");
            // $(".btnAccordion").removeClass("active");
            // $(this).addClass("active");
            $(this).closest(".accordionCustomItem").addClass("active");
            $(this).addClass("active");
        }
    });
    $(".btnAccordionSub").on("click", function () {
        if ($(this).hasClass("active") === true) {
            $(this).closest(".accordionCustomItemSub").removeClass("active");
            $(this).removeClass("active");
        }
        else {
            $(this).closest(".accordionCustomItemSub").addClass("active");
            $(this).addClass("active");
        }
    });
});
});





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
    $(".sidebarNavLinksChild").on("click", function () {
        $(this).parent("li").toggleClass("active");
        // $(this).toggleClass("active");
    });
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
});
});





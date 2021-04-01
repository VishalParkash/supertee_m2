require([ 'jquery', 'bootstrapSlider'], function($,modal){ 
$(document).ready(function () {
    $(".rangeSlider").slider();

    
    // $('.datePicker').datepicker({
    //     format: 'dd-mm-yyyy',
    //     autoclose: true,
    // });
    
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
    // $('#markupModal').modal('show');
});
});

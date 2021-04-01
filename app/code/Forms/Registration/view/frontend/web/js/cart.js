define([
    'jquery',
    'Magento_Checkout/js/action/get-totals'
], function ($, getTotalsAction) {
    'use strict';
    
    // The cart page totals summary block update
    var deferred = $.Deferred();
    getTotalsAction([], deferred);
});
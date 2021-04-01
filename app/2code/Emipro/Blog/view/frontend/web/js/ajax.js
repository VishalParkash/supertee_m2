/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint browser:true jquery:true*/
define([
    'jquery',
    'ko',
    'mage/template',
    'text!./templates/ajaxresult.html',
    'jquery/ui'
], function ($, ko, mageTemplate, resultTmpl){
    "use strict";
    var isLoggedIn = ko.observable(window.custlogin);


    $.widget('mage.emiproajax', {
        options: {
            filterGroup: "[data-role='filter_group']",
            filter: "[data-role='filter']",
            filterField: "[data-role='filter-field']",
            filterValue: "[data-role='filter-value']",
            filterCondition: "[data-role='condition-type']",
            searchurl: '',
            ajaxformurl: '',
            resultContainer: "[data-role='ajaxresultdata']",
            messagesSelector: '[data-placeholder="messages"]',
            url: '',
            pageSize: 10,
            currentPage: 1,
            template: resultTmpl
        },

        /**
         * Bind handlers to events
         */
        _create: function () {
            this._on({'click [data-role="search_post"]': $.proxy(this._search, this)});
            this._on({'click [data-role="search_reset"]': $.proxy(this._search_reset, this)});
            this._on({'click [data-role="ajax_form"]': $.proxy(this._ajax_form, this)});
            var self = this;
            this.element.find(this.options.resultContainer).empty();
            if (isLoggedIn()) {
                var msg = $("<div/>").addClass("message-success success message").html('Welcome Come <b>'+isLoggedIn()+'</b> Thank You For Visit Our Blog.');
                $('body').find(self.options.messagesSelector).prepend(msg);
            }else{
                var msg = $("<div/>").addClass("message-notice notice message").html('Login Reuqire For Add New Post. So Please <a href="'+window.loginurl+'">Login Now</a> And Add New Post.');
                $('body').find(self.options.messagesSelector).prepend(msg);
            }
            $.ajax({
                url: self.options.url,
                dataType: 'json',
                context: $('body'),
                showLoader: true
            }).done(function (data) {
                $('#resetsearch').hide();
                $('#filter_value').val('');
                self._drawResultTable(data);
            }).fail(function (response) {
                var msg = $("<div/>").addClass("message notice").text(response.responseJSON.message);
                this.find(self.options.messagesSelector).prepend(msg);
            });
        },
        _ajax_form: function () {
            var self = this;
            $('body').find(self.options.messagesSelector).empty();
            if (isLoggedIn()) {
            this.element.find(this.options.resultContainer).empty();
            $.ajax({
                url: self.options.ajaxformurl,
                context: $('body'),
                showLoader: true
            }).done(function (data) {
                $('#resetsearch').show();
                $('#filter_value').val('');
                self.element.find(self.options.resultContainer).append($(data));                    
            }).fail(function (response) {
                var msg = $("<div/>").addClass("message notice").text(response.responseJSON.message);
                this.find(self.options.messagesSelector).prepend(msg);
            });
            }else{
                var msg = $("<div/>").addClass("message-error error message").html('Login Reuqire For Add New Post. So Please <a href="'+window.loginurl+'">Login Now</a> And Add New Post.');
                $('body').find(self.options.messagesSelector).prepend(msg);
            }
        },
        _search_reset: function() {
            var self = this;
            $('body').find(self.options.messagesSelector).empty();
            this.element.find(this.options.resultContainer).empty();
            $.ajax({
                url: self.options.url,
                dataType: 'json',
                context: $('body'),
                showLoader: true
            }).done(function (data) {
                $('#resetsearch').hide();
                $('#filter_value').val('');
                self._drawResultTable(data);
            }).fail(function (response) {
                var msg = $("<div/>").addClass("message notice").text(response.responseJSON.message);
                this.find(self.options.messagesSelector).prepend(msg);
            });
        },
        _search: function () {
            var self = this;
            $('body').find(self.options.messagesSelector).empty();
            var filterval = $('#filter_value').val();
            if(filterval == ''){
                var msg = $("<div/>").addClass("message-error error message").html('Please Enter Search Keyword <i class="fa fa-keyboard-o"></i>.');
                $('body').find(self.options.messagesSelector).prepend(msg);
            }else{
            this.element.find(this.options.resultContainer).empty();
            var params = {
                "searchCriteria": {
                    "filter_groups": [],
                    "current_page": self.options.currentPage,
                    "page_size": self.options.pageSize
                }
            };
            $(self.options.filterGroup).each(function () {
                var filters = [];
                $(this).children(self.options.filter).each(function () {
                    filters.push({
                        field: $(this).find(self.options.filterField).val(),
                        value: $(this).find(self.options.filterValue).val(),
                        condition_type: $(this).find(self.options.filterCondition).val()
                    });
                });
                params.searchCriteria.filter_groups.push({"filters": filters});
            });
           
            $.ajax({
                url: self.options.searchurl,
                dataType: 'json',
                data: params,
                context: $('body'),
                showLoader: true
            }).done(function (data) {
                $('#resetsearch').show();
                self._drawResultTable(data);
            }).fail(function (response) {
                var msg = $("<div/>").addClass("message notice").text(response.responseJSON.message);
                this.find(self.options.messagesSelector).prepend(msg);
            });
        }
        },
        _drawResultTable: function(data){
            var tmpl = mageTemplate(this.options.template);
            tmpl = tmpl({data: data});
            this.element.find(this.options.resultContainer).append($(tmpl));
        }
    })
    
    return $.mage.emiproajax;
});

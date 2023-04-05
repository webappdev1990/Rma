define([
    "jquery"
], function($) {
    "use strict";
    $.widget('magently.ajax', {
        options: {
            url: 'magerma/customer/orderinfo',
            method: 'post',
            triggerEvent: 'click'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            self.element.on(self.options.triggerEvent, function() {
                self._ajaxSubmit();
            });
        },

        _ajaxSubmit: function() {
            var self = this;
            var orderId = $('#order_id').val();
            if(orderId) { 
                $.ajax({
                    url: self.options.url,
                    type: self.options.method,
                    dataType: 'json',
                    data: { orderId : orderId },
                    beforeSend: function() {
                        $('body').trigger('processStart');
                    },
                    success: function(res) {
                        $("#additional-commant-box").show();
                        $(".file-uploader-summary").remove();
                        $("#comment").val('');
                        $(".buttons-set.actions-toolbar").show();
                        $('#orderInfo').html(res.orderdetails);
                        $('body').trigger('processStop');
                    },
                    error: function (error) {
                        //window.location.reload();
                    }
                });
            }
        },

    });

    return $.magently.ajax;
});
define([
    "jquery"
], function($) {
    "use strict";
    $.widget('rma.List', {
        options: {
            rmaApply: 'true',
            method: 'post',
            triggerEvent: 'click'
        },

        _create: function() {
            this._bind();
        },

        _bind: function() {
            var self = this;
            $('.rma-item-checkbox').on(self.options.triggerEvent, function() {
                //self._ajaxSubmit();
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
                        $(".upload-wrapper").show();
                        $(".buttons-set actions-toolbar").show();
                        $('#orderInfo').html(res.orderdetails).trigger('contentUpdated');
                        $('body').trigger('processStop');
                    },
                    error: function (error) {
                        //window.location.reload();
                    }
                });
            }
        },

    });

    return $.rma.List;
});
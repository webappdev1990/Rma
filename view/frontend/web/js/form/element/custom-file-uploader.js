define([
    'Magento_Ui/js/form/element/file-uploader'
], function (Element) {
    'use strict';

    return Element.extend({
        defaults: {
            fileInputName: ''
        },

        /**
         * Handler of the file upload complete event.
         *
         * @param {Event} e
         * @param {Object} data
         */
        onFileUploaded: function (e, data) {
            this._super(e, data);
           
            var response = data.result; // Here the response data are stored
            this.imagearray = response;
            //console.log(response);
        }
    });
});
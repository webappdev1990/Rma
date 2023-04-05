/*global $ */
require(['jquery',
    'jquery/ui',
    'mage/mage',
    'mage/translate'
], function ($) {
    jQuery = $;
    jQuery(document).ready(function () {
        var dataForm = jQuery('#edit_form_rma');
        dataForm.mage('validation');
        jQuery('.rmaedit-save-continue').click(function () {
            jQuery('#rma_back').val('1');
            saveForm(jQuery);
        });
        jQuery('.rmaedit-save').click(function () {
            jQuery('#rma_back').val('');
            saveForm(jQuery);
        });
        function saveForm(jQuery) {
            if (dataForm.valid()) {
                dataForm.submit();
            } else {
                jQuery('.mainroot').find('input.mage-error').each(function () {
                    var errorPlacement = jQuery(this);
                    var toggleObject = errorPlacement.parent().closest('li.dd-item').find('.expand.linktoggle');
                    toggleObject.hide();
                    toggleObject.siblings('.collapse').show();
                    toggleObject.siblings('.item-information').slideDown();
                    errorPlacement.parent().closest('li.dd-item').effect("shake");
                });
            }
        }
        /*save and validation  */
    });

});
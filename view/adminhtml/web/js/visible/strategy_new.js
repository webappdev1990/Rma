define(function () {
    'use strict';

    return {
        defaults: {
            valuesForOptions: [],
            imports: {
                toggleVisibility:
                    'magerma_customfield_form_new.magerma_customfield_form_new.general.frontend_input:value'
            },
            isShown: false,
            inverseVisibility: false
        },

        /**
         * Toggle visibility state.
         *
         * @param {Number} selected
         */
        toggleVisibility: function (selected) {
            this.isShown = selected in this.valuesForOptions;
            this.visible(this.inverseVisibility ? !this.isShown : this.isShown);
        }
    };
});

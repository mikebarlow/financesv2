module.exports = {
    data: function () {
        return {
            btnText: ''
        }
    },
    methods: {
        processing: function (btn, iconOnly) {
            this.btnText = btn.html();

            if (typeof iconOnly === 'undefined') {
                btn.html('<i class="fa fa-spinner fa-spin"></i> Processing');
            } else {
                btn.html('<i class="fa fa-spinner fa-spin"></i>');
            }
        },
        stopProcessing: function (btn) {
            btn.html(this.btnText);
        }
    }
};
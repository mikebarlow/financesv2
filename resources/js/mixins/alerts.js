var alertClose;

module.exports = {
    methods: {
        successAlert: function (msg, wrapper) {
            this.baseAlert('success', msg, wrapper);
        },
        dangerAlert: function (msg, wrapper) {
            this.baseAlert('danger', msg, wrapper);
        },
        infoAlert: function (msg, wrapper) {
            this.baseAlert('info', msg, wrapper);
        },
        warningAlert: function (msg, wrapper) {
            this.baseAlert('warning', msg, wrapper);
        },
        baseAlert: function(type, msg, wrapper) {
            var d = new Date();
            var id = 'alert-' + d.getTime();

            var alert = '<div id="' + id + '" class="fade show alert alert-' + type + '" role="alert">\
            ' + msg + '</div>';

            if (typeof wrapper === 'undefined') {
                var wrapper = $('.alert-wrapper');
            } else {
                var wrapper = $(wrapper);
            }

            if (wrapper.length > 0) {
                wrapper.append(alert);

                setTimeout(function() {
                    $('#' + id).alert('close');
                }, 5000);
            }
        }
    }
};
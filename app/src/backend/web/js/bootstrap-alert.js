window.BootstrapAlert = {
    types: ['success', 'info', 'warning', 'danger'],

    show: function(containerSelector, alertType, messageHtml) {
        if (this.types.indexOf(alertType) === -1) {
            return;
        }

        var $container = $(containerSelector);
        if ($container.size() === 0) {
            return;
        }

        var alertHtml = '<div class="alert-' + alertType + ' alert-dismissible alert fade in">'
            + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
            + messageHtml
            + '</div>';

        $(alertHtml).prependTo($container);
    },

    showAll: function (containerSelector, alerts) {
        for (var alertType in alerts) {
            var messages = alerts[alertType];
            if (!Array.isArray(messages)) {
                messages = [messages];
            }
            var self = this;
            messages.forEach(function (message) {
                self.show(containerSelector, alertType, message);
            });
        }
    },

    removeAll: function(containerSelector) {
        $(containerSelector).children('.alert').remove();
    }
};

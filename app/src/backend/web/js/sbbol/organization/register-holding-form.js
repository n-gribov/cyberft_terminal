var RegisterHoldingForm = {
    $modal: null,
    $form: null,
    $submitButton: null,
    login: null,
    password: null,
    sessionId: null,
    requestId: null,
    senderName: null,

    sendHoldingInfoRequest: function () {
        var self = this;
        var formData = self.getFormData();
        $.ajax({
            url: 'request-holding-info',
            type: 'POST',
            data: self.$form.serializeArray(),
            success: function (response) {
                if (!response) {
                    return;
                }
                if (response.success) {
                    self.sessionId = response.sessionId;
                    self.requestId = response.requestId;
                    self.password = response.password;
                    self.login = formData.login;
                    self.senderName = formData.senderName;
                    self.scheduleHoldingInfoRequestStatusCheck();
                } else {
                    self.toggleSubmitButtonState(false);
                    if (response.validationErrors) {
                        self.$form.yiiActiveForm('updateMessages', response.validationErrors, true);
                    }
                    if (response.errorMessage) {
                        self.showAlert('alert-danger', response.errorMessage);
                    }
                }
            },
            beforeSend: function () {
                self.removeAlerts();
                self.toggleSubmitButtonState(true);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status != 302) {
                    self.toggleSubmitButtonState(false);
                }
            }
        });
    },

    scheduleHoldingInfoRequestStatusCheck: function () {
        var self = this;
        setTimeout(function () {
            self.checkHoldingInfoRequestStatus();
        }, 1000);
    },

    checkHoldingInfoRequestStatus: function () {
        var self = this;
        $.ajax({
            url: 'check-holding-info-request-status',
            type: 'POST',
            data: {
                sessionId:  self.sessionId,
                requestId:  self.requestId,
                login:      self.login,
                password:   self.password,
                senderName: self.senderName
            },
            success: function (response) {
                if (!response) {
                    return;
                }
                if (response.errorMessage) {
                    self.showAlert('alert-danger', response.errorMessage);
                }
                if (response.isFinished) {
                    self.toggleSubmitButtonState(false);
                } else {
                    self.scheduleHoldingInfoRequestStatusCheck();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (jqXHR.status != 302) {
                    self.toggleSubmitButtonState(false);
                }
            }
        });
    },

    toggleSubmitButtonState: function (isLoading) {
        this.$submitButton.button(isLoading ? 'loading' : 'reset')
    },

    removeAlerts: function() {
        this.$modal.find('.modal-body').children('.alert').remove();
    },

    showAlert: function(alertClass, messageHtml) {
        this.removeAlerts();

        var $container = this.$modal.find('.modal-body');
        if ($container.size() === 0) {
            return;
        }

        var alertHtml = '<div class="' + alertClass + ' alert-dismissible alert fade in">'
            + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
            + messageHtml
            + '</div>';
        $(alertHtml).prependTo($container);
    },

    getFormData: function () {
        return {
            login:      this.getFormFieldValue('login'),
            password:   this.getFormFieldValue('password'),
            senderName: this.getFormFieldValue('senderName')
        };
    },

    getFormFieldValue: function (fieldName) {
        return this.$form.find('[name="RegisterHoldingForm[' + fieldName + ']"]').val();
    },

    initialize: function () {
        this.$modal = $('#register-holding-modal');
        this.$form = this.$modal.find('form');
        this.$submitButton = this.$modal.find('.submit-button');
        this.bindToView();
    },

    bindToView: function () {
        var self = this;

        this.$modal.on('hidden.bs.modal', function () {
            self.$form.trigger('reset');
            self.removeAlerts();
        });

        this.$form.on('beforeSubmit', function () {
            self.sendHoldingInfoRequest();
            return false;
        });

        this.$submitButton.click(function () {
            self.$form.trigger('submit');
        });
    }
};

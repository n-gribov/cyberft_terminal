var RegisterKeyForm = {
    $modal: null,
    requestId: null,
    keyId: null,

    showModal: function (url) {
        var self = this;
        $.get(url).done(function (response) {
            self.renderModal(response.body);
            self.$modal.modal();
        });
    },

    renderModal: function (modalBodyContent) {
        this.$modal.find('.modal-body').html(modalBodyContent);
    },

    showGenerateCertificateRequestParamsForm: function () {
        this.showModal('generate-certificate-request-params');
    },

    scheduleCertificateRequestStatusCheck: function () {
        var self = this;
        setTimeout(function () {
            self.checkCertificateRequestStatus();
        }, 1000);
    },

    checkCertificateRequestStatus: function () {
        var self = this;
        $.ajax({
            url: 'check-certificate-request-status',
            type: 'POST',
            data: {
                requestId: self.requestId,
                keyId: self.keyId
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
                    self.scheduleCertificateRequestStatusCheck();
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
        var $submitButton = this.$modal.find('button:submit');
        if (isLoading) {
            if ($submitButton.data('loading-text')) {
                $submitButton.button('loading');
            } else {
                $submitButton.prop('disabled', true);
            }
        } else {
            $submitButton.button('reset');
            $submitButton.prop('disabled', false);
        }
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

    initialize: function () {
        this.$modal = $('#register-key-modal');
        this.bindToView();
    },

    submitForm: function($form, onSuccess, onError, onComplete) {
        this.toggleSubmitButtonState(true);
        this.removeAlerts();

        var formData = new FormData($form.get(0));
        var action = $form.attr('action');

        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: onSuccess,
            error: onError,
            complete: onComplete
        });
    },

    bindToView: function () {
        var self = this;

        this.$modal.on('submit', '#generate-certificate-request-params-form', function () {
            self.submitForm(
                $(this),
                function (response) {
                    self.renderModal(response.body);
                }
            );
            return false;
        });

        this.$modal.on('submit', '#create-key-form', function () {
            self.submitForm(
                $(this),
                function (response) {
                    if (response.body) {
                        self.renderModal(response.body);
                    } else if (response.success) {
                        self.requestId = response.requestId;
                        self.keyId = response.keyId;
                        self.scheduleCertificateRequestStatusCheck();
                    } else {
                        self.toggleSubmitButtonState(false);
                        if (response.errorMessage) {
                            self.showAlert('alert-danger', response.errorMessage);
                        }
                    }
                },
                function () {
                    self.toggleSubmitButtonState(false);
                }
            );
            return false;
        });
    }
};

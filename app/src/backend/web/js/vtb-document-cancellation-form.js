function VtbDocumentCancellationForm(documentId) {
    this.documentId = documentId;
    this.$modal = $('#cancel-document-modal');
    this.$cancelDocumentButton = $('#show-cancel-document-modal-button');

    this.initialize = function () {
        var $form = this.$modal.find('form');

        var $button = this.$modal.find('button:submit');
        $button.click(function () {
            $form.trigger('submit');
        });

        var self = this;
        $form.on('beforeSubmit', function () {
            self.submitCancellationForm($form);
            return false;
        });

        this.$modal.on('hide.bs.modal', function () {
            $form.trigger('reset');
        });
    };

    this.submitCancellationForm = function ($form) {
        var formData = new FormData($form.get(0));
        var self = this;
        $.ajax({
            url: '/edm/vtb-document-cancellation/send-prepare-cancellation-request?id=' + this.documentId,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    self.startTrackingCancellationProgress(response.prepareCancellationRequestDocumentId);
                } else if (response.errorMessage) {
                    self.showCancellationError(response.errorMessage);
                }
            },
            beforeSend: function() {
                self.showCancellationError(null);
            }
        });
    };

    this.startTrackingCancellationProgress = function (requestDocumentId) {
        this.$cancelDocumentButton.addClass('hidden');
        this.$modal.modal('hide');
        this.showAlert('info', '<img src="/img/spinner-white.gif" style="width: 18px; height: 18px;"> Создается запрос на отзыв...');
        this.scheduleCancellationProgressUpdate(requestDocumentId);
    };

    this.scheduleCancellationProgressUpdate = function (requestDocumentId) {
        var self = this;
        setTimeout(function () {
            self.updateCancellationProgress(requestDocumentId);
        }, 3000);
    };

    this.updateCancellationProgress = function (requestDocumentId) {
        var self = this;
        $.ajax({
            url: '/edm/vtb-document-cancellation/proceed-cancellation',
            data: {
                prepareCancellationRequestDocumentId: requestDocumentId
            },
            method: 'get',
            success: function (response) {
                if (response.status === 'processed') {
                    self.showCancellationProgressRequestResults(true, 'Запрос на отзыв документа отправлен');
                } else if (response.status === 'rejected') {
                    self.$cancelDocumentButton.removeClass('hidden');
                    self.showCancellationProgressRequestResults(false, 'Не удалось отправить запрос на отзыв документа');
                } else {
                    self.scheduleCancellationProgressUpdate(requestDocumentId);
                }
            },
            cache: false
        });
    };

    this.showCancellationProgressRequestResults = function (isSuccess, message) {
        var type = isSuccess ? 'success' : 'danger';
        this.showAlert(type, message);
    };

    this.showAlert = function (type, message) {
        var containerSelector = '.well.well-content';
        BootstrapAlert.removeAll(containerSelector);
        BootstrapAlert.show(containerSelector, type, message);
    };

    this.showCancellationError = function (message) {
        this.$modal.find('.error-message').text(message || '');
    };
}

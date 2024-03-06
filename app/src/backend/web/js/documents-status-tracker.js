var DocumentsStatusTracker = {

    updateTimeout: 3000,

    finalStatuses: [
        'undelivered',
        'delivered',
        'rejected',
        'attachmentUndelivered',
        'executed',
        'forUserVerify',
        'userVerifiedError',
        'verificationFailed',
        'decompressionError',
        'decryptingError',
        'schemaError',
        'encryptingError',
        'controllerVerificationFail',
        'mainAutoSigningError',
        'autoSigningError',
        'registeredError',
        'processingError',
        'registeredError',
        'signingRejected',
        'notExported',
        'deleted',
        'correction',
        'exported',
        'creatingError',
        'forSigning'
    ],

    finalStatusesExceptions: {
        'BICDir': {
            'processed': true
        },
        'SBBOLClientTerminalSettings': {
            'processed': true
        }
    },

    tableRows: null,

    pendingDocumentsIds: [],

    initialize: function () {
        var $table = $('.documents-journal-grid table');
        if ($table.size() === 0) {
            return;
        }
        this.tableRows = $table.find('tbody tr');

        var self = this;
        this.tableRows.each(function (index, row) {
            var status = $(row).data('status');
            var id = $(row).data('key');
            var documentType = $(row).data('document-type');

            if (id != null && !self.isFinalStatus(status, documentType)) {
                self.pendingDocumentsIds.push(Number(id));
            }
        });

        this.showSpinners();
        this.scheduleUpdate();
    },

    isFinalStatus: function (status, documentType, statusAge) {
        var documentTypeExceptions = this.finalStatusesExceptions[documentType];
        if (documentTypeExceptions && (status in documentTypeExceptions)) {
            return documentTypeExceptions[status];
        }

        if (status === 'processed' && statusAge > 3 * 60) {
            return true;
        }

        return this.finalStatuses.indexOf(status) >= 0;
    },

    showSpinners: function () {
        var self = this;
        this.tableRows.each(function (index, row) {
            var id = Number($(row).data('key'));
            var isPending = self.pendingDocumentsIds.indexOf(id) >= 0;

            $(row).toggleClass('pending', isPending);
        });
    },
    
    scheduleUpdate: function () {
        if (this.pendingDocumentsIds.length === 0) {
            return;
        }
        setTimeout(
            this.update.bind(this),
            this.updateTimeout
        );
    },
    
    update: function () {
        $.ajax({
            url: '/document/get-statuses',
            method: 'POST',
            format: 'json',
            data: {
                ids: this.pendingDocumentsIds
            },
            success: this.processResponse,
            context: this,
            cache: false
        });
    },

    processResponse: function (documents) {
        this.pendingDocumentsIds = [];

        var self = this;
        $.each(documents, function (index, document) {
            var $row = self.tableRows.filter('[data-key=' + document.id + ']');

            $row.attr('data-status', document.status);
            $row.find('[data-attribute=status]').html(document.statusName);
            $row.find('[data-attribute=signaturesCount]').html(document.signaturesCount);

            for (var dataAttr in document.documentData) {
                $row.find('[data-attribute=' + dataAttr + ']').html(document.documentData[dataAttr]);
            }

            if (!self.isFinalStatus(document.status, document.type, document.statusAge)) {
                self.pendingDocumentsIds.push(document.id);
            }
        });

        this.showSpinners();
        this.scheduleUpdate();
    }
};

DocumentsStatusTracker.initialize();

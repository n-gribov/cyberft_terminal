function DocumentsSigner($button) {

    this.button = $button;

    this.documentsIds = [];

    this.params = {
        fetchSignedDataUrl: null,
        saveSignaturesUrl: null,
        getSigningStatusUrl: null,
        commonSignatureErrorMessage: null,
        commonMultipleSignatureErrorMessage: null,
        cyberSignServiceSetupErrorMessage: null,
        cyberSignServiceVersionErrorMessage: null,
        useVersionCheck: true,
        requiredVersion: null,
        successRedirectUrl: null,
        entriesSelectionCacheKey: null,
        hashAlgorithm: 'sha256',
        alertsContainerSelector: null,
        prepareDocumentsIdsCallback: null
    };

    this.setup = function () {
        var params = JSON.parse(this.button.attr('data-params'));
        for (var paramKey in this.params) {
            if (paramKey in params) {
                var value = params[paramKey];
                if (paramKey === 'prepareDocumentsIdsCallback') {
                    eval('var callback = ' + value);
                    value = callback;
                }
                this.params[paramKey] = value;
            }
        }
    };

    this.startSigning = function() {
        var clientIsInitialized = this.initializeClient();
        if (!clientIsInitialized) {
            return;
        }

        this.removeAlerts();
        this.button.button('loading');

        var self = this;
        this.params.prepareDocumentsIdsCallback(function (documentsIds) {
            self.documentsIds = documentsIds;
            self.fetchSignedData(documentsIds);
        });
    };

    this.initializeClient = function () {
        var clientIsReady = typeof CyberFTClient !== 'undefined' && CyberFTClient.isReady();
        if (clientIsReady) {
            if (this.params.useVersionCheck && CyberFTClient.Version < this.params.requiredVersion) {
                this.fail(this.params.cyberSignServiceVersionErrorMessage);
                clientIsReady = false;
            } else {
                CyberFTClient.initialize();
                CyberFTClient.setHash(this.params.hashAlgorithm);
            }
        } else {
            this.fail(this.params.cyberSignServiceSetupErrorMessage);
        }

        return clientIsReady;
    };

    this.fetchSignedData = function(documentsIds) {
        var self = this;
        $.ajax({
            url: this.params.fetchSignedDataUrl,
            method: 'POST',
            format: 'json',
            data: this.addCsrfTokenToData({ids: documentsIds}),
            success: function (response) {
                self.handleGetSignedDataResponse(documentsIds, response);
            },
            context: this,
            cache: false
        });
    };

    this.handleGetSignedDataResponse = function (documentsIds, response) {
        if (response.success) {
            this.sign(documentsIds, response.signedData);
        } else {
            this.fail(response.errorMessageHtml);
        }
    };

    this.sign = function (documentsIds, documentsSignatureParams) {
        var newDocumentsIds = [];
        var newDocumentsSignatureParams = [];

        for (var documentIdIndex = 0; documentIdIndex < documentsIds.length; documentIdIndex++) {
            var documentId = documentsIds[documentIdIndex];
            var signatureParams = documentsSignatureParams[documentIdIndex];
            var signedData = Array.isArray(signatureParams[0]) ? signatureParams[0] : [signatureParams[0]];


            var signMethod = signatureParams[1];
            var signedDataEncoding = signatureParams[2];
            var signerCertificate = signatureParams[3];
            console.log('signMethod: ' + signMethod);
            console.log('signedDataEncoding: ' + signedDataEncoding);
            console.log('signedData:');
            console.log(signedData);

            for (var signedDataPartIndex = 0; signedDataPartIndex < signedData.length; signedDataPartIndex++) {
                newDocumentsIds.push(documentId);
                newDocumentsSignatureParams.push([signedData[signedDataPartIndex], signMethod, signedDataEncoding, signerCertificate]);
            }
        }

        var self = this;
        CyberFTClient.sign(
            CyberFTClient.Method.ANY,
            newDocumentsSignatureParams,
            function (signatures, parameters) {
                self.saveSignatures(newDocumentsIds, signatures, parameters.cert_body);
            },
            function (message) {
                var errorMessage = documentsIds.length > 1
                    ? self.params.commonMultipleSignatureErrorMessage
                    : self.params.commonSignatureErrorMessage;
                self.fail(errorMessage);
            }
        );
    };

    this.saveSignatures = function (documentsIds, signatures, certificateBody) {
        console.log('signatures:');
        console.log(signatures);
        $.ajax({
            url: this.params.saveSignaturesUrl,
            method: 'POST',
            format: 'json',
            data: this.addCsrfTokenToData({
                ids: documentsIds,
                signatures: signatures,
                certificateBody: certificateBody
            }),
            success: this.handleSaveSignaturesResult,
            context: this,
            cache: false
        });
    };

    this.handleSaveSignaturesResult = function (response) {
        if (!response) {
            return;
        }
        var jobsIds = response.jobsIds;
        if (jobsIds == null) {
            jobsIds = [];
        }
        this.trackSigningStatus(jobsIds);
    };

    this.trackSigningStatus = function (jobsIds) {
        $.ajax({
            url: this.params.getSigningStatusUrl,
            method: 'POST',
            format: 'json',
            data: this.addCsrfTokenToData({
                jobsIds: jobsIds,
                documentsCount: this.documentsIds.length,
                successRedirectUrl: this.params.successRedirectUrl,
                entriesSelectionCacheKey: this.params.entriesSelectionCacheKey
            }),
            success: function (response) {
                if (!response) {
                    return;
                }
                var self = this;
                setTimeout(
                    function () {
                        self.trackSigningStatus(response.pendingJobsIds);
                    },
                    1000
                );
            },
            context: this,
            cache: false
        });
    };

    this.fail = function (errorMessageHtml) {
        this.showError(errorMessageHtml);
        this.button.button('reset');
    };

    this.showError = function (errorMessageHtml) {
        this.showAlert('alert-danger', errorMessageHtml);
    };

    this.addCsrfTokenToData = function (data) {
        data[yii.getCsrfParam()] = yii.getCsrfToken();
        return data;
    };

    this.removeAlerts = function () {
        $(this.params.alertsContainerSelector).children('.alert').remove();
    };

    this.showAlert = function (alertClass, messageHtml) {
        this.removeAlerts();

        var $contentBlock = $(this.params.alertsContainerSelector);
        if ($contentBlock.size() === 0) {
            return;
        }

        var alertHtml = '<div class="' + alertClass + ' alert-dismissible alert fade in">'
            + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'
            + messageHtml
            + '</div>';
        $(alertHtml).prependTo($contentBlock);
    };

    this.setup();

}

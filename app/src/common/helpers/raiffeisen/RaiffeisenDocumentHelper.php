<?php

namespace common\helpers\raiffeisen;

use common\models\raiffeisenxml\request\Request;

class RaiffeisenDocumentHelper
{
    const REQUEST_DOCUMENTS_TYPES = [
        'DocIds',
        'PayDocRu',
        'Ticket',
        'Incoming',
        'CryptoIncoming',
        'ActivateCert',
        'StmtReq',
        'StmtReqRaif',
        'PayDocRuGetSMS',
        'PayDocRuSMS',
        'SalaryDoc',
        'Accept',
        'LetterInBank',
        'CurrDealCertificate138I',
        'ConfDocCertificate138I',
        'ConfDocCertificate181I',
        'IcsRestruct181I',
        'DzoAccAttach',
        'DealPassICS',
        'DealPassCon138I',
        'DealPassCred138I',
        'DealPassRestruct',
        'DealPassClose',
        'CertifRequestQualified',
        'CertifRequest',
        'RevocationCertifRequest',
        'PayDocCur',
        'CurrBuy',
        'CurrSell',
        'MandatorySale',
        'RegOfCorpCards',
        'ApplForContract',
        'RegOfIssCards',
        'RegOfFiredEmployees',
        'Dict',
        'RzkDictUpdate',
        'PersonalInfo',
        'DocTypeConfigInfo',
        'ClientAppUpdateRequest',
        'PayRequest',
        'CreditOrder',
        'RevocationRequest',
        'RemainRequest',
        'FirmwareUpdateRequest',
        'CorrAdd',
        'SubstDocAdd',
        'CorrDel',
        'GenSMSSign',
        'VerifySMSSign',
        'BenefAdd',
        'BenefDel',
        'EnableUseSignCorrDict',
        'CorpCardExtIssueRequest',
        'UpdateListOfEmployees',
        'ExchangeMessagesWithBank',
        'CurrControlInfoRequest',
        'IskForUL',
        'IskForIP',
        'IncomingRequestISK',
        'DealPassFromAnotherBank',
        'ChatWithBank',
        'RequestChatFromBankMsgs',
        'ChatWithBankMsgStatus',
        'InquiryOrder',
        'AccountStatement',
        'RzkPayDocsRu',
        'CurrCourseEntry',
        'GozDocUpdate',
        'AdmDictUpdate',
        'SupplyDoc',
        'BigFilesUploadLinksRequest',
        'BigFilesStatusRequest',
        'IncomingDealConf',
        'DealAns',
        'SmsTimeouts',
        'PayCustDoc',
        'ContractAdd',
        'ActAdd',
        'IncomingRoles',
        'IncomingUserRoles',
        'IncomingOffers',
        'OfferResponses',
        'Crms',
        'CallBacks',
        'AdmPayTemplate',
        'AdmPayTemplateBlock',
        'AdmCashierDel',
        'AdmPayTemplateDel',
        'AdmCashierAdd',
        'AdmPayTemplateList',
        'AdmCashierList',
        'AdmOperationList',
        'AdmGenSMSPass',
        'AdmCashierGetLogin',
        'AuditLetter',
        'EarlyRecall',
        'ChangeAccDetails',
        'CardDeposits',
        'AppForDepositNew',
        'PermBalanceNew',
        'CardPermBalance',
        'GenAuthSMSSign',
        'VerifyAuthSMSSign',
        'Audit',
        'FeesRegistry',
        'FeesRegistryDownload',
        'FeesRegistryAccept',
        'EssenceLinks',
        'DebtRegistry',
        'CashOrder',
        'RecallCashOrder',
        'CurrDealCertificate181I',
        'IntCtrlStatementXML181I',
        'BigFilesDownloadPrintFormLink',
    ];

    /**
     * @param Request $document
     * @return string|null
     * @throws \Exception
     */
    public static function detectRequestDocumentType(Request $document)
    {
        $detectedType = null;
        foreach (static::REQUEST_DOCUMENTS_TYPES as $type) {
            $getter = "get$type";

            if (!method_exists($document, $getter)) {
                continue;
            }

            if (empty($document->$getter())) {
                continue;
            }

            if ($detectedType) {
                throw new \Exception("Multiple document types found: $detectedType and $type");
            }
            $detectedType = $type;
        }

        return $detectedType;
    }
}

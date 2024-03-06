<?php

namespace addons\edm\states\out;

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\models\DictCurrency;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationItem;
use addons\ISO20022\models\Auth024Type;
use common\document\Document;
use common\exceptions\InvalidImportedDocumentException;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\ModelHelper;
use common\helpers\StringHelper;
use common\models\ImportError;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use DomainException;
use Exception;
use Yii;

/** @property-read EdmOutState $state */
final class Auth024PrepareStep extends ISO20022DocumentPrepareStep
{
    public function run()
    {
        /** @var Auth024Type $model */
        $typeModel = $this->state->model;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->validateXml($typeModel);
            $this->ensureDocumentIsNotDuplicate($typeModel);
            $typeModel->sender = $this->getSenderTerminalAddress($typeModel);
            $typeModel->receiver = $this->getReceiverTerminalAddress($typeModel);
            $this->addZipContent($typeModel);

            $document = $this->createDocument($typeModel);
            $extModel = $this->createExtModel($typeModel, $document->id);
            $this->saveExtModel($extModel);
            // Отправить документ на обработку в транспортном уровне
            DocumentTransportHelper::processDocument($document, true);

            $transaction->commit();
        } catch (Exception $exception) {
            $transaction->rollBack();
            $this->handleException($exception);

            return false;
        }

        // Предотвращаем запуск DocumentOutState
        $this->state->stop();

        return true;
    }

    private function getSenderTerminalAddress(Auth024Type $typeModel): string
    {
        $payerAccount = $this->getEdmPayerAccount($typeModel);
        $address = $payerAccount && $payerAccount->edmDictOrganization && $payerAccount->edmDictOrganization->terminal
            ? $payerAccount->edmDictOrganization->terminal->terminalId
            : null;
        if (empty($address)) {
            throw new Exception("Cannot find terminal address for payer account {$payerAccount->terminalId}");
        }
        return $address;
    }

    private function getReceiverTerminalAddress(Auth024Type $typeModel): string
    {
        $payerAccount = $this->getEdmPayerAccount($typeModel);
        if (empty($payerAccount->terminalId)) {
            throw new DomainException(Yii::t('edm', 'Account does not have bank terminal id'));
        }
        return $payerAccount->terminalId;
    }

    private function getEdmPayerAccount(Auth024Type $typeModel): EdmPayerAccount
    {
        $accountNumber = $typeModel->getSenderAccountNumber();
        if (empty($accountNumber)) {
            throw new DomainException(Yii::t('edm', 'Payer account is not specified'));
        }
        $payerAccount = EdmPayerAccount::findOne(['number' => $accountNumber]);

        if ($payerAccount === null) {
            throw new DomainException(Yii::t('edm', 'Payer account {number} is not found', ['number' => $accountNumber]));
        }

        return $payerAccount;
    }

    private function createDocument(Auth024Type $typeModel): Document
    {
        $terminal = Terminal::findOne(['terminalId' => $typeModel->sender]);
        $documentContext = FCCHelper::createCyberXml(
            $typeModel,
            [
                'sender'     => $typeModel->sender,
                'receiver'   => $typeModel->receiver,
                'terminal'   => $terminal,
                'account'    => $this->getEdmPayerAccount($typeModel),
                'attachFile' => null
            ]
        );

        if ($documentContext === false) {
            throw new Exception('Failed to create document');
        }

        return $documentContext['document'];
    }

    private function ensureDocumentIsNotDuplicate(Auth024Type $typeModel): void
    {
        $documentExists = Document::find()
            ->innerJoin(
                'documentExtISO20022 isoext',
                'isoext.documentId = document.id'
            )
            ->where([
                'and',
                ['=', 'document.direction', Document::DIRECTION_OUT],
                ['not in', 'document.status', [Document::STATUS_REJECTED, Document::STATUS_DELETED]],
                ['=', 'document.type', Auth024Type::TYPE],
                ['=', 'isoext.msgId', $typeModel->msgId],
                [
                    'or',
                    ['!=', 'isoext.statusCode', 'RJCT'],
                    ['isoext.statusCode' => null],
                ],
            ])
            ->exists();

        if ($documentExists) {
            $message = Yii::t(
                'app/iso20022',
                'Document with message id {messageId} already exists',
                ['messageId' => $typeModel->msgId]
            );
            throw new DomainException($message);
        }
    }

    protected function handleException(Exception $exception): void
    {
        $this->log($exception);
        $errorMessage = $exception instanceof DomainException
            ? $exception->getMessage()
            : \Yii::t('app', 'Create document error');

        $this->saveImportError($errorMessage);
    }

    protected function saveImportError($errorMessage, $documentNumber = null): void
    {
        $importErrorMessage = Yii::t(
            'edm',
            'Failed to create document: {error}',
            ['error' => StringHelper::mb_lcfirst($errorMessage)]
        );
        ImportError::createError([
            'type' => ImportError::TYPE_EDM,
            'filename' => FileHelper::mb_basename($this->state->filePath),
            'errorDescriptionData' => ['text' => $importErrorMessage],
        ]);
    }

    private function createExtModel(Auth024Type $typeModel, int $documentId): ForeignCurrencyOperationInformationExt
    {
        function isEmpty($value): bool
        {
            return (string)$value === '' || $value === null;
        }

        function stringOrNull($value): ?string
        {
            return isEmpty($value) ? null : (string)$value;
        }

        function intOrNull($value): ?int
        {
            return isEmpty($value) ? null : (int)$value;
        }

        function floatOrNull($value): ?float
        {
            return isEmpty($value) ? null : (float)$value;
        }

        function dateOrNull($value, $format = 'd.m.Y'): ?string
        {
            if (isEmpty($value)) {
                return null;
            }
            $dateString = (string)$value;
            return date($format, strtotime($dateString));
        }

        $xml = $typeModel->getRawXml();
        $payerAccount = $this->getEdmPayerAccount($typeModel);
        $model = new ForeignCurrencyOperationInformationExt([
            'accountId'        => $payerAccount->id,
            'organizationId'   => $payerAccount->organizationId,
            'number'           => stringOrNull($xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->Cert->Id),
            'correctionNumber' => stringOrNull($xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->Amdmnt->CrrctnId),
            'date'             => dateOrNull($xml->PmtRgltryInfNtfctn->GrpHdr->CreDtTm),
            'countryCode'      => stringOrNull($xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->BkAcctDmcltnCtry),
            'documentId'       => $documentId,
        ]);

        $operations = [];

        foreach ($xml->PmtRgltryInfNtfctn->TxNtfctn->TxCert->CertRcrd as $certRcrd) {
            $operationCurrency = DictCurrency::findOne(['name' => (string)$certRcrd->Tx->Amt['Ccy']]);
            $operationUnitsCurrency = DictCurrency::findOne(['name' => (string)$certRcrd->Ctrct->TxAmtInCtrctCcy['Ccy']]);
            $operation = new ForeignCurrencyOperationInformationItem([
                'currencyId'        => $operationCurrency->id,
                'number'            => stringOrNull($certRcrd->Tx->RfrdDoc->Id->EndToEndId),
                'docDate'           => dateOrNull($certRcrd->Tx->RfrdDoc->Dt),
                'operationDate'     => dateOrNull($certRcrd->Tx->TxDt),
                'paymentType'       => intOrNull($certRcrd->Tx->TxTp),
                'codeFCO'           => stringOrNull($certRcrd->Tx->LclInstrm),
                'operationSum'      => (string) floatOrNull($certRcrd->Tx->Amt),
                'operationSumUnits' => (string) floatOrNull($certRcrd->Ctrct->TxAmtInCtrctCcy),
                'docRepresentation' => intOrNull($certRcrd->Attchmnt->SndrRcvrSeqId),
                'contractPassport'  => stringOrNull($certRcrd->Ctrct->CtrctRef->RegdCtrctId),
                'contractNumber'    => stringOrNull($certRcrd->Ctrct->CtrctRef->Ctrct->Id /*DtOfIsse*/),
                'contractDate'      => dateOrNull($certRcrd->Tx->RfrdDoc->Dt),
                'currencyUnitsId'   => $operationUnitsCurrency ? $operationUnitsCurrency->id : null,
                'expectedDate'      => dateOrNull($certRcrd->Ctrct->XpctdShipmntDt),
                'refundDate'        => dateOrNull($certRcrd->Ctrct->XpctdAdvncPmtRtrDt),
                'comment'           => stringOrNull($certRcrd->Ctrct->AddtlInf),
                'documentId'        => $documentId,
            ]);
            $operation->notRequiredSection1 = empty($operation->number) ? 1 : 0;
            $operation->notRequiredSection2 = empty($operation->contractPassport) && empty($operation->contractNumber) && empty($operation->contractDate)
                ? 1 : 0;

            if (!$operation->validate()) {
                throw new InvalidImportedDocumentException(ModelHelper::getErrorsSummary($operation, true), $operation->number);
            }

            $operations[] = $operation;
        }

        if (count($operations) === 0){
            throw new DomainException(Yii::t('edm', 'Document has no operations data'));
        }

        $model->loadOperations($operations);
        $model->number = DocumentHelper::getDayUniqueCount('fci');

        if (!$model->validate()) {
            throw new InvalidImportedDocumentException(ModelHelper::getErrorsSummary($model, true));
        }

        return $model;
    }

    private function saveExtModel(ForeignCurrencyOperationInformationExt $extModel): void
    {
        // Сохранить модель в БД
        $isSaved = $extModel->save();
        if (!$isSaved) {
            throw new Exception('Failed to save ' . get_class($extModel) . ' to database');
        }
    }

    private function addZipContent(Auth024Type $typeModel): void
    {
        if ($this->state->isImportingZipArchive) {
            // Использовать сжатие в zip
            $typeModel->useZipContent = true;
            $typeModel->zipContent = file_get_contents($this->state->filePath);
            $typeModel->zipFilename = basename($this->state->filePath);
        }
    }

    protected function getDocumentTypeGroupId(): ?string
    {
        return EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY;
    }

    protected function getDocumentCurrency(): ?string
    {
        try {
            $account = $this->getEdmPayerAccount($this->state->model);
            return $account->edmDictCurrencies
                ? $account->edmDictCurrencies->name
                : null;
        } catch (\Throwable $exception) {
            return null;
        }
    }
}

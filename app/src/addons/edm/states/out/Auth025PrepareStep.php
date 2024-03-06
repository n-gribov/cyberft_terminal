<?php

namespace addons\edm\states\out;

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationItem;
use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\ISO20022\models\Auth025Type;
use common\document\Document;
use common\helpers\FileHelper;
use common\helpers\ModelHelper;
use common\helpers\StringHelper;
use common\models\ImportError;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;

/** @property-read EdmOutState $state */
final class Auth025PrepareStep extends ISO20022DocumentPrepareStep
{
    public function run()
    {
        /** @var Auth025Type $model */
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
        } catch (\Exception $exception) {
            $transaction->rollBack();
            $this->handleException($exception);
            return false;
        }

        // Предотвращаем запуск DocumentOutState
        $this->state->stop();

        return true;
    }

    private function getSenderOrganization(Auth025Type $typeModel): DictOrganization
    {
        $inn = $typeModel->getSenderTxId();
        if (!$inn) {
            throw new \DomainException(Yii::t('edm', 'Cannot find sender INN in the document'));
        }
        $organization = DictOrganization::findOne(['inn' => $inn]);
        if ($organization === null) {
            throw new \DomainException(Yii::t('edm', 'Organization with INN {inn} is not found', ['inn' => $inn]));
        }
        return $organization;
    }

    private function getReceiverBank(Auth025Type $typeModel): DictBank
    {
        $bik = $typeModel->getReceiverRuCbc();
        if (!$bik) {
            throw new \DomainException(Yii::t('edm', 'Cannot find receiver bank BIK in the document'));
        }
        $bank = DictBank::findOne(['bik' => $bik]);
        if ($bank === null) {
            throw new \DomainException(Yii::t('edm', 'Bank with BIK {bik} is not found in the dictionary', ['bik' => $bik]));
        }
        return $bank;
    }

    private function getSenderTerminalAddress(Auth025Type $typeModel): string
    {
        return $this->getSenderOrganization($typeModel)->terminal->terminalId;
    }

    private function getReceiverTerminalAddress(Auth025Type $typeModel): string
    {
        $bank = $this->getReceiverBank($typeModel);
        if (empty($bank->terminalId)) {
            throw new \DomainException(
                Yii::t('edm', 'Bank with BIK {bik} does not have terminal id', ['bik' => $bank->bik])
            );
        }
        return $bank->terminalId;
    }

    private function createDocument(Auth025Type $typeModel): Document
    {
        $terminal = Terminal::findOne(['terminalId' => $typeModel->sender]);

        $documentContext = FCCHelper::createCyberXml(
            $typeModel,
            [
                'sender'     => $terminal->terminalId,
                'receiver'   => $typeModel->receiver,
                'terminal'   => $terminal,
                'account'    => null,
                'attachFile' => null,
            ]
        );

        if ($documentContext === false) {
            throw new \Exception('Failed to create document');
        }

        return $documentContext['document'];
    }

    private function ensureDocumentIsNotDuplicate(Auth025Type $typeModel): void
    {
        $documentExists = Document::find()
            ->innerJoin(
                'edm_confirmingDocumentInformationExt ext',
                'ext.documentId = document.id'
            )
            ->where([
                'and',
                ['=', 'document.direction', Document::DIRECTION_OUT],
                ['not in', 'document.status', [Document::STATUS_REJECTED, Document::STATUS_DELETED]],
                ['=', 'document.type', Auth025Type::TYPE],
                ['=', 'ext.uuid', $typeModel->msgId],
                [
                    'or',
                    ['!=', 'ext.businessStatus', 'RJCT'],
                    ['ext.businessStatus' => null],
                ],
            ])
            ->exists();

        if ($documentExists) {
            $message = Yii::t(
                'app/iso20022',
                'Document with message id {messageId} already exists',
                ['messageId' => $typeModel->msgId]
            );
            throw new \DomainException($message);
        }
    }

    protected function handleException(\Exception $exception): void
    {
        $this->log($exception);
        $errorMessage = $exception instanceof \DomainException
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

    private function createExtModel(Auth025Type $typeModel, int $documentId): ConfirmingDocumentInformationExt
    {
        function isEmpty($value): bool
        {
            return (string)$value === '' || $value === null;
        }

        function stringOrNull($value): ?string
        {
            return isEmpty($value) ? null : (string)$value;
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

        function getCurrencyId(?string $currencyCode): ?string
        {
            if (!$currencyCode) {
                return null;
            }
            $currency = DictCurrency::findOne(['name' => $currencyCode]);
            return $currency ? $currency->id : null;
        }

        $xml = $typeModel->getRawXml();
        $model = new ConfirmingDocumentInformationExt([
            'number'           => stringOrNull($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Cert->Id),
            'correctionNumber' => stringOrNull($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Amdmnt->CrrctnId),
            'organizationId'   => $this->getSenderOrganization($typeModel)->id,
            'date'             => dateOrNull($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Cert->DtOfIsse),
            'contractPassport' => stringOrNull($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->CtrctRef->RegdCtrctId),
            'person'           => stringOrNull($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->CtctDtls->Nm),
            'contactNumber'    => stringOrNull($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->AcctOwnr->CtctDtls->PhneNb),
            'bankBik'          => $typeModel->getReceiverRuCbc(),
            'uuid'             => $typeModel->msgId,
            'documentId'       => $documentId,
        ]);

        $items = [];
        foreach ($xml->CcyCtrlSpprtgDocDlvry->SpprtgDoc->Ntry as $ntry) {
            $number = stringOrNull($ntry->OrgnlDoc->Id);
            if (isEmpty($number)) {
                $number = 'БН';
            }
            $item = new ConfirmingDocumentInformationItem([
                'number'                 => $number,
                'date'                   => dateOrNull($ntry->OrgnlDoc->DtOfIsse),
                'notRequiredNumber'      => $number === 'БН' ? 1 : 0,
                'code'                   => stringOrNull($ntry->DocTp),
                'sumDocument'            => floatOrNull($ntry->TtlAmt),
                'sumContract'            => floatOrNull($ntry->TtlAmtInCtrctCcy),
                'currencyIdDocument'     => getCurrencyId(stringOrNull($ntry->TtlAmt['Ccy'])),
                'currencyIdContract'     => getCurrencyId(stringOrNull($ntry->TtlAmtInCtrctCcy['Ccy'])),
                'type'                   => stringOrNull($ntry->ShipmntAttrbts->Conds->Cd),
                'expectedDate'           => dateOrNull($ntry->ShipmntAttrbts->XpctdDt),
                'countryCode'            => stringOrNull($ntry->ShipmntAttrbts->CtryOfCntrPty),
                'comment'                => stringOrNull($ntry->AddtlInf),
                'originalDocumentDate'   => dateOrNull($ntry->Amdmnt->OrgnlDocDt),
                'originalDocumentNumber' => stringOrNull($ntry->Amdmnt->OrgnlDocId),
                'documentId'             => $documentId,
            ]);

            if (!$item->validate()) {
                throw new \DomainException(ModelHelper::getErrorsSummary($item, true));
            }

            $items[] = $item;
        }

        if (count($items) === 0){
            throw new \DomainException(Yii::t('edm', 'No confirming documents records found'));
        }

        $model->loadDocuments($items);
        if (!$model->validate()) {
            throw new \DomainException(ModelHelper::getErrorsSummary($model, true));
        }

        return $model;
    }

    private function saveExtModel(ConfirmingDocumentInformationExt $extModel): void
    {
        // Сохранить модель в БД
        $isSaved = $extModel->save();
        if (!$isSaved) {
            throw new \Exception('Failed to save ' . get_class($extModel) . ' to database');
        }
    }

    private function addZipContent(Auth025Type $typeModel): void
    {
        if ($this->state->isImportingZipArchive) {
            // Использовать сжатие в zip
            $typeModel->useZipContent = true;
            $typeModel->zipContent = file_get_contents($this->state->filePath);
            $typeModel->zipFilename = basename($this->state->filePath);
        }
    }
}

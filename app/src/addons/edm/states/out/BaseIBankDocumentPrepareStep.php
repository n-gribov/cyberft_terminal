<?php

namespace addons\edm\states\out;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\IBank\common\converter\IBankConverter;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\IBank\IBankDocumentsPack;
use common\base\BaseType;
use common\document\Document;
use common\exceptions\InvalidImportedDocumentException;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\models\ImportError;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;
use Yii;

/** @property EdmOutState $state */
abstract class BaseIBankDocumentPrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';

    public function run()
    {
        if (!$this->validateAttachments()) {
            return false;
        }

        /** @var IBankDocumentsPack $ibankDocumentsPack */
        $ibankDocumentsPack = $this->state->model;
        $documentsCount = $ibankDocumentsPack->count();
        foreach ($ibankDocumentsPack as $index => $ibankDocument) {
            $documentNumber = $documentsCount > 1 ? $index + 1 : null;
            $this->processDocument($ibankDocument, $documentNumber);
        }

        // Предотвращаем запуск DocumentOutState
        $this->state->stop();

        return true;
    }

    abstract protected function createConverter($ibankDocumentType, $recipientTerminalId): IBankConverter;
    abstract protected function getRecipientTerminalId(IBankDocument $ibankDocument, $senderTerminalId);

    protected function validateAttachments()
    {
        if (empty($this->state->attachmentsPaths)) {
            return true;
        }

        /** @var IBankDocumentsPack $ibankDocumentsPack */
        $ibankDocumentsPack = $this->state->model;
        if ($ibankDocumentsPack->count() === 1) {
            return true;
        }

        $this->saveImportError(Yii::t('edm', 'Attachments are not allowed when importing files with multiple documents'));

        return false;
    }

    protected function processDocument(IBankDocument $ibankDocument, $documentNumber)
    {
        try {
            $senderTerminalId = $this->state->sender;
            $recipientTerminalId = $this->getRecipientTerminalId($ibankDocument, $senderTerminalId);
            $converter = $this->createConverter($ibankDocument->getType(), $recipientTerminalId);

            // Создать тайп-модель
            $typeModel = $converter->createTypeModel(
                $ibankDocument,
                $senderTerminalId,
                $recipientTerminalId,
                $this->state->attachmentsPaths
            );
            // Атрибуты расширяющей модели
            $extModelAttributes = $converter->createExtModelAttributes($typeModel);

            $extModelClass = $converter->getExtModelClass();
            $this->ensureDocumentIsNotDuplicate($extModelClass, $extModelAttributes);

            $this->sendDocument(
                $typeModel,
                $senderTerminalId,
                $recipientTerminalId,
                $ibankDocument->getSenderAccountNumber(),
                $extModelAttributes
            );
        } catch (\Exception $exception) {
            $this->handleException($exception, $documentNumber, $ibankDocument);
        }
    }

    protected function handleException(\Exception $exception, $documentNumber, IBankDocument $iBankDocument)
    {
        $this->log($exception);
        $errorMessage = $exception instanceof \DomainException
            ? $exception->getMessage()
            : \Yii::t('app', 'Create document error');

        if ($documentNumber === null && $exception instanceof InvalidImportedDocumentException) {
            $documentNumber = $exception->getDocumentNumber();
        }

        $documentCurrency = null;
        try {
            $accountNumber = $iBankDocument->getSenderAccountNumber();
            if ($accountNumber) {
                $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
                $documentCurrency = $account->edmDictCurrencies->name;
            }
        } catch (\Throwable $exception) {
            $this->log("Failed to detect document currency, caused by $exception");
        }

        $this->saveImportError($errorMessage, $documentNumber, "iBank:{$iBankDocument->getType()}", $documentCurrency);
    }

    protected function saveImportError($errorMessage, $documentNumber = null, $documentType = null, $documentCurrency = null)
    {
        $importErrorMessage = Yii::t(
            'edm',
            'Failed to create document: {error}',
            ['error' => StringHelper::mb_lcfirst($errorMessage)]
        );
        if ($documentNumber !== null) {
            $importErrorMessage .= '. ' . Yii::t('edm', 'Document number') . ": $documentNumber";
        }
        ImportError::createError([
            'type'                  => ImportError::TYPE_EDM,
            'filename'              => FileHelper::mb_basename($this->state->filePath),
            'errorDescriptionData'  => ['text' => $importErrorMessage],
            'documentNumber'        => $documentNumber,
            'documentType'          => $documentType,
            'documentCurrency'      => $documentCurrency,
            'senderTerminalAddress' => $this->state->sender,
        ]);
    }

    protected function ensureDocumentIsNotDuplicate($extModelClass, $extModelAttributes)
    {
        // костыль по причине того, что разные экст-модели имеют разные атрибуты для номера.
        $numberAttribute = 'numberDocument';
        if (!array_key_exists($numberAttribute, $extModelAttributes)) {
            $numberAttribute = 'number';
        }

        $extTableName = $extModelClass::tableName();
        $query = Document::find()
            ->innerJoin(
                "$extTableName ext",
                'ext.documentId = document.id'
            )
            ->where([
                'and',
                ['=', 'document.direction', Document::DIRECTION_OUT],
                ['not in', 'document.status', [Document::STATUS_REJECTED, Document::STATUS_DELETED]],
                ['=', 'ext.date', $extModelAttributes['date'] . ' 00:00:00'],
                ['=', "ext.$numberAttribute", $extModelAttributes[$numberAttribute]],
            ]);

        $hasBusinessStatus = in_array('businessStatus', (new $extModelClass())->attributes());
        if ($hasBusinessStatus) {
            $query->andWhere([
                'or',
                ['!=', 'ext.businessStatus', 'RJCT'],
                ['ext.businessStatus' => null]
            ]);
        }

        if ($query->exists()) {
            $message = Yii::t(
                'edm',
                'Found duplicate document number {num} date {date}',
                [
                    'num' => $extModelAttributes[$numberAttribute],
                    'date' => $extModelAttributes['date']
                ]
            );
            throw new InvalidImportedDocumentException($message, $extModelAttributes[$numberAttribute]);
        }
    }

    protected function sendDocument(BaseType $typeModel, $senderTerminalId, $recipientTerminalId, $accountNumber, $extModelAttributes)
    {
        $terminalId = Terminal::getIdByAddress($senderTerminalId);
        // Атрибуты документа
        $docAttributes = [
            'sender'     => $senderTerminalId,
            'receiver'   => $recipientTerminalId,
            'type'       => $typeModel->getType(),
            'direction'  => Document::DIRECTION_OUT,
            'terminalId' => $terminalId,
            'origin'     => Document::ORIGIN_FILE,
        ];
        $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
        if ($account && $account->requireSignQty) {
            // Из персональных настроек счета
            $docAttributes['status'] = Document::STATUS_FORSIGNING;
            $docAttributes['signaturesRequired'] = $account->requireSignQty;
        }

        // Создать контекст документа
        $context = DocumentHelper::createDocumentContext($typeModel, $docAttributes, $extModelAttributes);

        if (!$context) {
            throw new \Exception(\Yii::t('app', 'Save document error'));
        }
        // Отправить документ на обработку в транспортном уровне
        DocumentTransportHelper::processDocument($context['document'], true);
    }

    protected function getTerminalIdByAccountNumber($accountNumber)
    {
        $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
        if ($account === null) {
            throw new \DomainException(Yii::t('edm', 'Account {accountNumber} is not found in payers accounts dictionary', ['accountNumber' => $accountNumber]));
        }

        $bankTerminalId = $account->bank->terminalId;
        if (empty($bankTerminalId)) {
            throw new \DomainException(Yii::t('edm', 'Bank terminal is not specified for BIK {bankBIK}', ['bankBIK' => $account->bankBik]));
        }

        return $bankTerminalId;
    }
}

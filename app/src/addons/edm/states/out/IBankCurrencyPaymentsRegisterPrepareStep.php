<?php

namespace addons\edm\states\out;

use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\IBank\IBankDocumentsPack;
use addons\edm\models\IBank\V1\IBankV1ConverterFactory;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use common\document\Document;
use common\exceptions\InvalidImportedDocumentException;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\StringHelper;
use common\models\ImportError;
use common\models\Terminal;
use common\models\vtbxml\documents\PayDocCur;
use common\modules\transport\helpers\DocumentTransportHelper;
use common\states\BaseDocumentStep;
use Yii;

/** @property EdmOutState $state */
class IBankCurrencyPaymentsRegisterPrepareStep extends BaseDocumentStep
{
    public $name = 'prepare';

    public function run()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->ensureThereIsNoAttachments();

            $vtbPayDocCurTypeModels = $this->createVTBPayDocCurTypeModels();

            $this->ensureDocumentsHaveSameAccount($vtbPayDocCurTypeModels);
            $this->ensureDocumentIsNotDuplicates($vtbPayDocCurTypeModels);

            $registerTypeModel = $this->createRegisterTypeModel($vtbPayDocCurTypeModels);
            $this->createAndSendDocument($registerTypeModel);

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

    private function createVTBPayDocCurTypeModels(): array
    {
        /** @var IBankDocumentsPack $ibankDocumentsPack */
        $ibankDocumentsPack = $this->state->model;
        $typeModels = [];
        foreach ($ibankDocumentsPack as $iBankDocument) {
            $receiver = $this->getTerminalIdByAccountNumber($iBankDocument->getSenderAccountNumber());
            $converter = IBankV1ConverterFactory::create($iBankDocument->getType(), $receiver);
            $typeModels[] = $converter->createTypeModel(
                $iBankDocument,
                $this->state->sender,
                $receiver,
                []
            );
        }

        return $typeModels;
    }

    /**
     * @param VTBPayDocCurType[] $vtbPayDocCurTypeModels
     * @return VTBRegisterCurType
     */
    private function createRegisterTypeModel(array $vtbPayDocCurTypeModels): VTBRegisterCurType
    {
        $registerTypeModel = new VTBRegisterCurType();
        foreach ($vtbPayDocCurTypeModels as $typeModel) {
            $registerTypeModel->paymentOrders[] = $typeModel;
        }

        return $registerTypeModel;
    }

    private function createAndSendDocument(VTBRegisterCurType $registerTypeModel)
    {
        $sender = $this->state->sender;
        $accountNumber = $registerTypeModel->paymentOrders[0]->document->PAYERACCOUNT;
        $receiver = $this->getTerminalIdByAccountNumber($accountNumber);

        $account = $this->getAccountByNumber($accountNumber);
        $currency = $this->getCurrencyNameByAccount($account);
        $terminalId = Terminal::getIdByAddress($sender);

        $docAttributes = [
            'sender' => $sender,
            'receiver' => $receiver,
            'terminalId' => $terminalId,
            'type' => $registerTypeModel->getType(),
            'direction' => Document::DIRECTION_OUT
        ];

        $extAttributes = [
            'date' => (new \DateTime('now'))->format('Y-m-d'),
            'paymentsCount' => count($registerTypeModel->paymentOrders),
            'debitAccount' => $accountNumber,
        ];

        $context = DocumentHelper::createDocumentContext($registerTypeModel, $docAttributes, $extAttributes);

        if (!$context) {
            throw new \Exception(\Yii::t('app', 'Save document error'));
        }

        $document = $context['document'];

        foreach ($registerTypeModel->paymentOrders as $typeModel) {
            $fcoExt = new ForeignCurrencyOperationDocumentExt(['documentId' => $document->id]);
            $fcoExt->loadContentModel($typeModel);
            $isSaved = $fcoExt->save();
            if (!$isSaved) {
                throw new \Exception('Failed to save payment order to database, errors: ' . var_export($fcoExt->getErrors(), true));
            }
        }

        DocumentTransportHelper::processDocument($context['document'], true);
    }

    private function getCurrencyNameByAccount($account)
    {
        $currency = $account->edmDictCurrencies;
        if ($currency === null) {
            return null;
        }

        return $currency->name;
    }

    private function getAccountByNumber($number): EdmPayerAccount
    {
        $account = EdmPayerAccount::findOne(['number' => $number]);
        if ($account === null) {
            $errorMessage = Yii::t(
                'edm',
                'Account {accountNumber} is not found in payers accounts dictionary',
                ['accountNumber' => $number]
            );
            throw new \DomainException($errorMessage);
        }

        return $account;
    }

    private function getTerminalIdByAccountNumber($accountNumber)
    {
        $account = $this->getAccountByNumber($accountNumber);

        $bankTerminalId = $account->bank->terminalId;

        if (empty($bankTerminalId)) {
            throw new \Exception(Yii::t('edm', 'Bank terminal is not specified for BIK {bankBIK}', ['bankBIK' => $account->bankBik]));
        }
        return $bankTerminalId;
    }

    private function ensureThereIsNoAttachments()
    {
        if (!empty($this->state->attachmentsPaths)) {
            throw new \DomainException(Yii::t('edm', 'Attachments are not allowed when importing files with multiple documents'));
        }
    }

    /**
     * @param VTBPayDocCurType[] $vtbPayDocCurTypeModels
     */
    private function ensureDocumentsHaveSameAccount(array $vtbPayDocCurTypeModels)
    {
        $accounts = array_map(
            function (VTBPayDocCurType $typeModel) {
                /** @var PayDocCur $document */
                $document = $typeModel->document;
                return $document->PAYERACCOUNT;
            },
            $vtbPayDocCurTypeModels
        );

        if (count(array_unique($accounts)) > 1) {
            throw new \DomainException(Yii::t('edm', 'All payment documents in register must have the same payer account'));
        }
    }

    /**
     * @param VTBPayDocCurType[] $vtbPayDocCurTypeModels
     */
    private function ensureDocumentIsNotDuplicates(array $vtbPayDocCurTypeModels)
    {
        $duplicate = $this->findFirstDuplicate($vtbPayDocCurTypeModels);
        if ($duplicate === null) {
            return;
        }

        $errorMessage = Yii::t(
            'edm',
            'Found duplicate document number {num} date {date}',
            ['num' => $duplicate['number'], 'date' => $duplicate['date']]
        );
        throw new InvalidImportedDocumentException($errorMessage, $duplicate['number']);
    }

    /**
     * @param VTBPayDocCurType[] $vtbPayDocCurTypeModels
     * @return array|null
     */
    private function findFirstDuplicate(array $vtbPayDocCurTypeModels)
    {
        $numbersDates = [];

        foreach ($vtbPayDocCurTypeModels as $typeModel) {
            $number = $typeModel->document->DOCUMENTNUMBER;
            $date = $typeModel->document->DOCUMENTDATE->format('Y-m-d');
            // проверка на возможный дубликат прямо в исходном документе
            if (isset($numbersDates[$number])) {
                return [
                    'number' => $number,
                    'date' => $date
                ];
            }
            $numbersDates[$number] = $date;
        }

        // минимизируя запросы к базе, ищем все документы, попадающие в набор номеров и дат
        $found = ForeignCurrencyOperationDocumentExt::find()
            ->alias('ext')
            ->leftJoin('document', 'ext.documentId = document.id')
            ->where([
                'and',
                ['=', 'document.direction', Document::DIRECTION_OUT],
                ['not in', 'document.status', [Document::STATUS_REJECTED, Document::STATUS_DELETED]],
                ['in', 'ext.numberDocument', array_keys($numbersDates)],
                ['in', 'ext.date', array_unique($numbersDates)],
                [
                    'or',
                    ['!=', 'ext.businessStatus', 'RJCT'],
                    ['ext.businessStatus' => null],
                ],
            ])
            ->all();

        // если нашлись совпадающие номера или даты, делаем детальную проверку по парам номер-дата
        foreach ($found as $model) {
            $date = Yii::$app->formatter->asDate($model->date, 'php:Y-m-d');
            if (isset($numbersDates[$model->numberDocument]) && $numbersDates[$model->numberDocument] == $date) {
                return [
                    'number' => $model->numberDocument,
                    'date' => $date,
                ];
            }
        }

        return null;
    }

    protected function handleException(\Exception $exception)
    {
        $this->log($exception);
        $errorMessage = $exception instanceof \DomainException
            ? $exception->getMessage()
            : \Yii::t('app', 'Create document error');

        $documentNumber = $exception instanceof InvalidImportedDocumentException
            ? $exception->getDocumentNumber()
            : null;

        $this->saveImportError($errorMessage, $documentNumber);
    }

    protected function saveImportError($errorMessage, $documentNumber = null)
    {
        $importErrorMessage = Yii::t(
            'edm',
            'Failed to create document: {error}',
            ['error' => StringHelper::mb_lcfirst($errorMessage)]
        );
        ImportError::createError([
            'type'                  => ImportError::TYPE_EDM,
            'filename'              => FileHelper::mb_basename($this->state->filePath),
            'errorDescriptionData'  => ['text' => $importErrorMessage],
            'documentType'          => 'iBankCurrencyPaymentsRegister',
            'documentCurrency'      => $this->getDocumentCurrency(),
            'documentNumber'        => $documentNumber,
            'senderTerminalAddress' => $this->state->sender,
        ]);

        $this->state->addApiImportError($importErrorMessage);
    }

    private function getDocumentCurrency(): ?string
    {
        try {
            /** @var IBankDocumentsPack $ibankDocumentsPack */
            $ibankDocumentsPack = $this->state->model;
            $account = $this->getAccountByNumber($ibankDocumentsPack[0]->getSenderAccountNumber());
            return $this->getCurrencyNameByAccount($account);
        } catch (\Throwable $exception) {
            $this->log("Failed to get document currency, caused by: $exception");
            return null;
        }
    }
}

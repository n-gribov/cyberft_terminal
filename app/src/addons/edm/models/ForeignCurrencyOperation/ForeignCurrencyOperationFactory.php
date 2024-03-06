<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Pain001Type;
use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use addons\swiftfin\models\SwiftFinDictBank;
use addons\swiftfin\models\SwiftFinType;
use addons\swiftfin\SwiftfinModule;
use common\document\Document;
use common\helpers\Address;
use common\helpers\DocumentHelper;
use common\helpers\FileHelper;
use common\helpers\Html;
use common\helpers\Uuid;
use common\helpers\WizardCacheHelper;
use common\models\Terminal;
use SimpleXMLElement;
use Yii;
use yii\base\InvalidValueException;

class ForeignCurrencyOperationFactory
{
    const OPERATION_PURCHASE = 'ForeignCurrencyPurchaseRequest';
    const OPERATION_SELL = 'ForeignCurrencySellRequest';
    const OPERATION_PAYMENT = 'ForeignCurrencyPayment';
    const OPERATION_SELL_TRANSIT_ACCOUNT = 'ForeignCurrencySellTransitAccount';
    const OPERATION_CONVERSION = 'ForeignCurrencyConversion';
    //
    const PROCESS_CREATE = 'createDocument';
    const PROCESS_UPDATE = 'updateDocument';

    /**
     * Список типов валютных операций
     * @return array
     */
    public static function getOperationTypes()
    {
        return [
            self::OPERATION_PURCHASE => Yii::t('edm', 'Purchase of currency'),
            self::OPERATION_SELL => Yii::t('edm', 'Sale of currency'),
            self::OPERATION_PAYMENT => Yii::t('edm', 'Foreign currency payment'),
            self::OPERATION_SELL_TRANSIT_ACCOUNT => Yii::t('edm', 'Sell of foreign currency from the transit account'),
            self::OPERATION_CONVERSION => Yii::t('edm', 'Currency conversion')
        ];
    }

    /**
     * Получение модели по полученному типу
     * @param $type
     * @return ForeignCurrencyOperationType|ForeignCurrencyPayment|null
     */
    public static function getModelByType($type)
    {
        if ($type == self::OPERATION_PAYMENT) {
            return new ForeignCurrencyPaymentType();
        } else if ($type == self::OPERATION_SELL || $type == self::OPERATION_PURCHASE) {
            return new ForeignCurrencyOperationType();
        } else if ($type == self::OPERATION_SELL_TRANSIT_ACCOUNT) {
            return new ForeignCurrencySellTransit();
        } else if ($type == self::OPERATION_CONVERSION) {
            return new ForeignCurrencyConversion();
        }

        return null;
    }

    /**
     * Дополнительные правила валидации для типов
     * @param $type
     * @param $model
     * @return array
     */
    public static function validateByType($type, $model)
    {
        if ($type == self::OPERATION_PAYMENT) {
            return self::validateFCP($model);
        } else if ($type == self::OPERATION_SELL_TRANSIT_ACCOUNT) {
            return self::validateFCST($model);
        } else if ($type == self::OPERATION_SELL || $type == self::OPERATION_PURCHASE) {
            return self::validateFCO($model);
        } else if ($type == self::OPERATION_CONVERSION) {
            return self::validateFCVN($model);
        }
    }

    /**
     * Дополнительная валидация документов продажи/покупки валюты
     * @param $typeModel
     * @return array
     */
    private static function validateFCO(& $typeModel)
    {
        // Терминал из списка терминалов текущего пользователя
        $typeModel->sender = Yii::$app->terminals->getPrimaryTerminal();

        $debitAccount = EdmPayerAccount::findOne(['number' => $typeModel->debitAccount->number]);

        if (is_null($debitAccount)) {
            $typeModel->addError('debitAccount.number', Yii::t('edm', 'Invalid checking account'));
            $debitBankBik = null;
        } else {
            $debitBankBik = $debitAccount->bank->bik;
            if (!is_null($debitAccount->edmDictOrganization->terminalId)) {
                $typeModel->sender = $debitAccount->edmDictOrganization->terminal->terminalId;
            }
            $typeModel->recipient = $debitAccount->bank->terminalId;

            $typeModel->debitAccount->setAttributes([
                'bankName' => $debitAccount->bank->name,
                'bankAccountNumber' => $debitAccount->bank->account,
                'bik' => $debitAccount->bank->bik
            ]);
            if ($typeModel->operationType == ForeignCurrencyOperationType::OPERATION_SELL) {
                $typeModel->paymentOrderCurrCode = $debitAccount->edmDictCurrencies->name;
            }
        }

        $creditAccount = EdmPayerAccount::findOne(['number' => $typeModel->creditAccount->number]);

        if (is_null($creditAccount)) {
            $typeModel->addError('creditAccount.number', Yii::t('edm', 'Invalid checking account'));
            $creditBankBik = null;
        } else {
            $creditBankBik = $creditAccount->bank->bik;
            $typeModel->creditAccount->setAttributes([
                'bankName' => $creditAccount->bank->name,
                'bankAccountNumber' => $creditAccount->bank->account,
                'bik' => $creditAccount->bank->bik
            ]);

            if ($typeModel->operationType == ForeignCurrencyOperationType::OPERATION_PURCHASE) {
                $typeModel->paymentOrderCurrCode = $creditAccount->edmDictCurrencies->name;
            }
        }

        $edmPayerAccount = EdmPayerAccount::findOne(['number' => $typeModel->commissionAccount->number]);

        if (is_null($edmPayerAccount)) {
            $typeModel->addError('commissionAccount.number', Yii::t('edm', 'Invalid checking account'));
            $commissionBankBik = null;
        } else {
            $commissionBankBik = $edmPayerAccount->bank->bik;
            $typeModel->commissionAccount->setAttributes([
                'bankName' => $edmPayerAccount->bank->name,
                'bankAccountNumber' => $edmPayerAccount->bank->account,
                'bik' => $edmPayerAccount->bank->bik
            ]);
        }

        if (
            $debitBankBik && $creditBankBik && $commissionBankBik
            && ($debitBankBik !== $creditBankBik || $creditBankBik !== $commissionBankBik)
        ) {
            $typeModel->addError('debitAccount.number', Yii::t('edm', 'The accounts belong to different banks'));
            $typeModel->addError('creditAccount.number', Yii::t('edm', 'The accounts belong to different banks'));
            $typeModel->addError('commissionAccount.number', Yii::t('edm', 'The accounts belong to different banks'));
        }

        $result = [];
        foreach ($typeModel->getErrors() as $attribute => $errors) {
            $result[Html::getInputId($typeModel, $attribute)] = $errors;
        }

        return $result;
    }

    private static function validateFCP($typeModel)
    {
        // если не заполнены данные поля, в дальнейших валидациях нет смысла
        if (empty($typeModel->payerAccount) || empty($typeModel->beneficiaryAccount)) {
            return [];
        }

        $account = EdmPayerAccount::findOne(['number' => $typeModel->payerAccount]);

        if (!$account) {
            $typeModel->addError('payerAccount', 'Не найдена информация по счету плательщика');

            return static::errorResult($typeModel);
        }

        $organization = $account->edmDictOrganization;

        if (!$organization) {
            $typeModel->addError('payerAccount', 'Не найдена информация по организации плательщика');

            return static::errorResult($typeModel);
        }

        $bank = $account->bank;

        if (!$bank) {
            $typeModel->addError('payerAccount', 'Не найдена информация по банку плательщика');

            return static::errorResult($typeModel);
        }

        // Терминал отправителя
        $senderTerminal = Terminal::findOne($organization->terminalId);

        // Терминал получателя
        $recipientTerminal = $bank->terminalId;

        // Формирование содержимого swift-документа
        $swiftContent = EdmHelper::createMt103FromForeignCurrencyPayment($typeModel);

        // Формирование swift-контейнера
        $swt = new SwtContainer();
        $swt->setRecipient($recipientTerminal);
        $swt->setSender($senderTerminal->terminalId);
        $swt->terminalCode = $senderTerminal->terminalId;
        $swt->setContentType('103');
        $swt->setContent($swiftContent);
        $swt->scenario = 'default';

        // Валидация swift
        if (!$swt->validate()) {
            $typeModel->addError('operationType', 'Ошибка валидации swift-документа' . $swt->getReadableErrors());
        }

        // Проверка на дублирование референса
        $operationReference = $swt->getOperationReference();

        $referenceDuplicate = SwiftfinHelper::checkOperationReferenceExisted($operationReference, $senderTerminal->terminalId);

        // Проверка наличия суммы комиссии, если комиссия BEN
//        if ($typeModel->commission == ForeignCurrencyPaymentType::COMMISSION_BEN && !$typeModel->commissionSum) {
//            $typeModel->addError('commissionSum', 'Необходимо заполнить «Расходы отправителя»');
//        }

        // Проверка на размеры суммы и комиссии
        if ($typeModel->commissionSum && $typeModel->commissionSum > $typeModel->sum) {
            $typeModel->addError('commissionSum', 'Расходы отправителя превышают сумму платежа');
        }

        if ($referenceDuplicate) {
            $typeModel->addError('number', Yii::t('app/swiftfin',
                'Reference {id} is already used in an another operation', [
                    'id' => $operationReference
            ]));
        }

        // Применяем перловый валидатор. Для этого создаем модель MtUniversalDocument
        // и наполняем ее из swt-контейнера
        /**
         * @var MtUniversalDocument $mt
         */
        $mt = SwiftfinModule::getInstance()->mtDispatcher->instantiateMt(
            $swt->getcontentType(),
            ['owner' => $swt]
        );

        $mt->setBody($swt->getContent());

        // сначала костыльно проверяем на наличие некорректных символов, т.к. модель сама делает транслитерацию
        $parsed = $mt->parseForTags();

        $testChars = "QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789/-?:().,' +\n\r";
        $testCharsReplace = str_repeat('-', strlen($testChars));

        foreach($parsed as $tag) {
            $tagName = $tag['name'];
            $tagValue = $tag['value'];
            if ($tagValue) {
                $length = strlen($tagValue);
                $result = strtr($tagValue, $testChars, $testCharsReplace);
                if ($result !== str_repeat('-', $length)) {
                    $mt->addError('body', 'Tag ' . $tagName . ' contains invalid characters');
                }
            }
        }

        if ($mt->hasErrors() || !$mt->validate()) {
            $typeModel->addError('operationType', 'Ошибка валидации swift-документа ' . $mt->getReadableErrors());
        }

        return static::errorResult($typeModel);
    }

    private static function errorResult($model)
    {
        $result = [];

        foreach ($model->getErrors() as $attribute => $errors) {
            $result[Html::getInputId($model, $attribute)] = $errors;
        }

        return $result;
    }

    private static function validateFCST(& $typeModel)
    {
        // Проверка, что валютный и транзитный счета относятся к одинаковым валютам
        if ($typeModel->transitAccount && $typeModel->foreignAccount) {
            $data = EdmPayerAccount::find()->select('currencyId')
                ->where(['number' => [$typeModel->transitAccount, $typeModel->foreignAccount]])
                ->distinct()
                ->asArray()->count();

            if ($data != 1) {
                $typeModel->addError('foreignAccount', 'Не соответствует валюте транзитного счета');
            }
        }

        // Проверка, что сумма значений сумм продажи и перевода не больше значения суммы зачисления
        if ((float)$typeModel->amountTransfer + (float)$typeModel->amountSell > $typeModel->amount) {
            $typeModel->addError('amount', 'Меньше суммы значений продажи и перевода');
        }

        $result = [];

        foreach ($typeModel->getErrors() as $attribute => $errors) {
            $result[Html::getInputId($typeModel, $attribute)] = $errors;
        }

        return $result;
    }

    private static function validateFCVN(& $typeModel)
    {
        $typeModel->isNew = false;

        return static::errorResult($typeModel);
    }

    /**
     * Получение наименования
     * шаблона для отображения
     * @param $type
     * @param $process
     * @return string
     * @throws Exception
     */
    public static function getOperationProcessTemplate($type, $process)
    {
        if ($type == self::OPERATION_PAYMENT) {
            if ($process == self::PROCESS_CREATE) {
                return 'fcp/_formModal';
            } else if ($process == self::PROCESS_UPDATE) {
                return 'fcp/_formModal';
            }
        } else if($type == self::OPERATION_SELL_TRANSIT_ACCOUNT) {
            return 'fcst/_formModal';
        } else if($type == self::OPERATION_CONVERSION) {
            return 'fcvn/_formModal';
        } else if ($type == self::OPERATION_SELL || $type == self::OPERATION_PURCHASE) {
            if ($process == self::PROCESS_CREATE) {
                return 'fco/_formCreateModal';
            } else if ($process == self::PROCESS_UPDATE) {
                return 'fco/_formUpdateModal';
            }
        }

        throw new \Exception('Unsupported type: ' . $type);
    }

    /**
     * Подготовка отображения
     * шаблона по типу документа
     * @param $type
     * @param $typeModel
     * @param $id
     * @return string
     * @throws Exception
     */
    public static function prepareRenderDataByType($type, & $typeModel, $id)
    {
        if ($type == self::OPERATION_PURCHASE || $type == self::OPERATION_SELL) {
            $process = $id ? self::PROCESS_UPDATE : self::PROCESS_CREATE;
            $view = self::getOperationProcessTemplate($type, $process);

            $typeModel->operationType = $type;
            if (Yii::$app->user->identity->terminalId) {
                $org = DictOrganization::findOne([
                    'terminalId' => Yii::$app->user->identity->terminalId
                ]);

                if ($org) {

                    $typeModel->applicant->name = $org->id;
                    $typeModel->applicant->inn = $org->inn;
                    $typeModel->applicant->address = $org->address;
                    $accounts = EdmPayerAccount::findAll(['organizationId' => $org->id]);
                    $rur_accounts = [];
                    $nonrur_accounts = [];

                    foreach ($accounts as $account) {
                        if (
                            $account->edmDictCurrencies->name == 'RUB'
                            || $account->edmDictCurrencies->name == 'RUR'
                        ) {
                            $rur_accounts[] = $account;
                        } else {
                            $nonrur_accounts[] = $account;
                        }
                    }

                    if (count($rur_accounts) == 1) {
                        $typeModel->debitAccount->setAttributes(
                            $rur_accounts[0]->attributes
                        );
                    }

                    if (count($nonrur_accounts) == 1) {
                        $typeModel->creditAccount->setAttributes(
                            $nonrur_accounts[0]->attributes
                        );
                    }
                }
            }
        } else if ($type == self::OPERATION_PAYMENT) {
            $process = $id ? self::PROCESS_UPDATE : self::PROCESS_CREATE;
            $view = self::getOperationProcessTemplate($type, $process);
        } else if ($type == self::OPERATION_SELL_TRANSIT_ACCOUNT) {
            $process = $id ? self::PROCESS_UPDATE : self::PROCESS_CREATE;
            $view = self::getOperationProcessTemplate($type, $process);
        } else if ($type == self::OPERATION_CONVERSION) {
            $process = $id ? self::PROCESS_UPDATE : self::PROCESS_CREATE;
            $view = self::getOperationProcessTemplate($type, $process);
        } else {
            $view = '_chooseTypeModal';
        }

        return $view;
    }

    public static function createDocument($typeModel)
    {
        if ($typeModel->operationType == self::OPERATION_PAYMENT) {
            return self::createDocumentFCP($typeModel);
        } else if (
            $typeModel->operationType == self::OPERATION_SELL
            || $typeModel->operationType == self::OPERATION_PURCHASE
        ) {
            return self::createDocumentFCO($typeModel);
        } else if ($typeModel->operationType == self::OPERATION_SELL_TRANSIT_ACCOUNT) {
            return self::createDocumentFCST($typeModel);
        } else if ($typeModel->operationType == self::OPERATION_CONVERSION) {
            return self::createDocumentFCVN($typeModel);
        }

        throw new \Exception('Unsupported operation type: ' . $typeModel->operationType);
    }

    /**
     * Создание документа покупки/продажи валюты
     * @param $typeModel
     * @return mixed
     * @throws Exception
     */
    private static function createDocumentFCO(ForeignCurrencyOperationType $typeModel)
    {
        $org = $typeModel->getOrganization();
        $terminal = Terminal::findOne($org->terminalId);

        $xml = ISO20022Helper::createPain001FromFCO($typeModel);
        $pain001Type = new Pain001FxType();
        $pain001Type->loadFromString($xml);
        $pain001Type->validateXSD();

        if ($pain001Type->errors) {
            Yii::info('Pain.001 validation against XSD-scheme failed');
            Yii::info($pain001Type->errors);

            return false;
        }

        $documentSettings =             [
            'type' => $pain001Type->getType(),
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'terminalId' => $terminal->id,
            'sender' => $typeModel->sender,
            'receiver' => $typeModel->recipient,
        ];

        // Получение количества подписей, требуемых по счету
        $account = EdmPayerAccount::findOne(['number' => $typeModel->debitAccount->number]);

        if ($account) {
            $requireSignatures = self::getRequireSignatures($account, $typeModel->sender);

            if ($requireSignatures > 0) {
                $documentSettings['signaturesRequired'] = $requireSignatures;
            }
        }

        $context = DocumentHelper::createDocumentContext(
            $pain001Type,
            $documentSettings,
            [
                'documentType' => $typeModel->getType(),
                'numberDocument' => $typeModel->numberDocument,
                'date' => date('Y-m-d', strtotime($typeModel->date)),
                'debitAccount' => $typeModel->debitAccount->number,
                'creditAccount' => $typeModel->creditAccount->number,
                'currency' => $typeModel->paymentOrderCurrCode,
                'currencySum' => $typeModel->paymentOrderCurrAmount,
                'sum' => $typeModel->paymentOrderAmount,
                'uuid' => $pain001Type->msgId
            ],
            $terminal->terminalId
        );

        if (!$context) {
            throw new \Exception(Yii::t('app', 'Save document error'));
        }

        $document = $context['document'];

        // Удаление кэша
        WizardCacheHelper::deleteFCOWizardCache($typeModel->getType());

        return $document;
    }

    /**
     * Создание swiftfin mt103 из
     * формы платежного документа
     * @param $typeModel
     * @return mixed
     * @throws \Exception
     */
    private static function createDocumentFCP($typeModel)
    {
        // Счет плательщика
        $account = EdmPayerAccount::findOne(['number' => $typeModel->payerAccount]);

        if (!$account) {
            throw new \Exception('Не найдена информация по счету плательщика');
        }

        $organization = $account->edmDictOrganization;

        if (!$organization) {
            throw new \Exception('Не найдена информация по организации плательщика');
        }

        $bank = $account->bank;

        if (!$bank) {
            throw new \Exception('Не найдена информация по банку плательщика');
        }

        // Терминал отправителя
        $senderTerminal = Terminal::findOne($organization->terminalId);

        // Терминал получателя
        $recipientTerminal = Address::fixAddress($bank->terminalId);

        // Формирование содержимого swift-документа
        $swiftContent = EdmHelper::createMt103FromForeignCurrencyPayment($typeModel);

        // Формирование swift-контейнера
        $swt = new SwtContainer();
        $swt->setRecipient($recipientTerminal);
        $swt->setSender($senderTerminal->terminalId);
        $swt->terminalCode = $senderTerminal->terminalId;
        $swt->setContentType('103');
        $swt->setContent($swiftContent);
        $swt->scenario = 'default';

        if (!$swt->validate()) {
            throw new \Exception('Ошибка валидации swift-документа');
        }

        $tempFile		 = Yii::getAlias('@temp/' . FileHelper::uniqueName() . '.swt');
        $swt->sourceFile = $tempFile;
        $swt->save();

        // Создание type-модели swift
        $swiftTypeModel = SwiftFinType::createFromFile($tempFile);

        if (!$swiftTypeModel) {
            throw new \Exception('Не удалось создать SwiftFin-документ');
        }

        $documentSettings = [
            'type' => $swiftTypeModel->getType(),
            'direction' => Document::DIRECTION_OUT,
            'typeGroup' => EdmModule::SERVICE_ID,
            'origin' => Document::ORIGIN_WEB,
            'terminalId' => $senderTerminal->id,
            'sender' => $swiftTypeModel->sender,
            'receiver' => $swiftTypeModel->recipient,
        ];

        // Получение количества подписей требуемых по счету
        $requireSignatures = self::getRequireSignatures($account, $senderTerminal->terminalId);

        if ($requireSignatures > 0) {
            $documentSettings['signaturesRequired'] = $requireSignatures;
        }

        // Создание нового получателя валютного платежа
        $typeModel->terminalId = $senderTerminal->id;

        if ($typeModel->saveTemplate) {
            $template = self::createForeignCurrencyPaymentTemplate($typeModel);

            if (!$template) {
                throw new \Exception('Foreign currency payment template creating is failed');
            }
        }

        $context = DocumentHelper::createDocumentContext(
            $swiftTypeModel,
            $documentSettings,
            [
                'numberDocument' => $typeModel->number,
                'date' => date('Y-m-d', strtotime($typeModel->date)),
                'debitAccount' => $typeModel->payerAccount,
                'creditAccount' => $typeModel->beneficiaryAccount,
                'currency' => $typeModel->currency,
                'currencySum' => $typeModel->sum,
                'beneficiary' => $typeModel->beneficiaryAccount.' '.$typeModel->beneficiary,
                'paymentPurpose' => $typeModel->information,
            ],
            $senderTerminal->terminalId
        );

        if (!$context) {
            throw new \Exception('Ошибка сохранения документа');
        }

        $document = $context['document'];

        EdmHelper::createForeignCurrencyPaymentBeneficiary($typeModel);

        // Удаление кэша
        WizardCacheHelper::deleteFCPWizardCache();

        return $document;
    }

    /**
     * Создание документа продажи валюты с транзитного счета
     * @param $typeModel
     */
    private static function createDocumentFCST($typeModel)
    {
        $organization = $typeModel->getOrganization();

        if (!$organization) {
            throw new \Exception('Не найдена информация по организации плательщика');
        }

        $bank = $typeModel->getBank();

        if (!$bank) {
            throw new \Exception('Не найдена информация по банку плательщика');
        }

        // Терминал отправителя
        $senderTerminal = Terminal::findOne($organization->terminalId);

        if (!$senderTerminal) {
            throw new \Exception('Не найден терминал отправителя');
        }

        // Терминал получателя
        $recipientTerminal = $bank->terminalId;

        if (!$recipientTerminal) {
            Yii::info($bank);
            Yii::info($bank->terminalId);
            throw new \Exception('Не найден терминал получателя');
        }

        $xml = ISO20022Helper::createPain001FromFCST($typeModel);

        $pain001RlsType = new Pain001RlsType();
        $pain001RlsType->loadFromString($xml);

        // Валидация по XSD-схеме
        //$pain001Type->validateXSD();

        if ($pain001RlsType->errors) {
            Yii::info('Pain.001 validation against XSD-scheme failed');
            Yii::info($pain001RlsType->errors);

            return false;
        }

        $uuid = Uuid::generate();

        $documentSettings = [
            'uuid' => $uuid,
            'type' => $pain001RlsType->getType(),
            'typeGroup' => EdmModule::SERVICE_ID,
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'terminalId' => $senderTerminal->id,
            'sender' => $senderTerminal->terminalId,
            'receiver' => $recipientTerminal,
        ];

        // Получение количества подписей, требуемых по счету
        $account = $typeModel->getAccount($typeModel->transitAccount);

        if ($account) {
            $requireSignatures = self::getRequireSignatures($account, $senderTerminal->terminalId);

            if ($requireSignatures > 0) {
                $documentSettings['signaturesRequired'] = $requireSignatures;
            }
        }

        $context = DocumentHelper::createDocumentContext(
            $pain001RlsType,
            $documentSettings,
            [
                'numberDocument' => $typeModel->number,
                'date' => date('Y-m-d', strtotime($typeModel->date)),
                'debitAccount' => $typeModel->transitAccount, //organizationAccount,
                'creditAccount' => $typeModel->account,
                'currency' => $typeModel->amountCurrency,
                'currencySum' => (int) $typeModel->amountSell + (int) $typeModel->amountTransfer,
                'documentType' => self::OPERATION_SELL_TRANSIT_ACCOUNT,
                'uuid' => $pain001RlsType->msgId
            ],
            $senderTerminal->terminalId
        );

        if (!$context) {
            throw new \Exception(Yii::t('app', 'Save document error'));
        }

        // Удаление кэша
        WizardCacheHelper::deleteFCSTWizardCache();

        return $context['document'];
    }

    /**
     * Создание документа поручения на конверсию валюты
     * @param $typeModel
     */
    private static function createDocumentFCVN($typeModel)
    {
        // Счет плательщика
        $account = EdmPayerAccount::findOne(['number' => $typeModel->debitAccount]);

        if (!$account) {
            throw new \Exception('Failed to get debit account info');
        }

        $organization = $typeModel->getOrganization();

        if (!$organization) {
            throw new \Exception('Failed to get organization info');
        }

        $bank = $account->bank;

        if (!$bank) {
            throw new \Exception('Failed to get bank info');
        }

        // Терминал отправителя
        $senderTerminal = Terminal::findOne($organization->terminalId);

        if (!$senderTerminal) {
            Yii::info($organization);
            Yii::info($organization->terminalId);
            throw new \Exception('Failed to get sender terminal');
        }

        // Терминал получателя
        $recipientTerminal = $bank->terminalId;

        if (!$recipientTerminal) {
            Yii::info($bank);
            Yii::info($bank->terminalId);
            throw new \Exception('Failed to get recipient terminal');
        }

        $xml = ISO20022Helper::createPain001FromFCVN($typeModel);

        $pain001Type = new Pain001Type();
        $pain001Type->loadFromString($xml);

        // Валидация по XSD-схеме
        //$pain001Type->validateXSD();

        if ($pain001Type->errors) {
            Yii::info('Auth.001 validation against XSD-scheme failed');
            Yii::info($pain001Type->errors);

            return false;
       }

        $documentSettings = [
            'type' => $pain001Type->getType(),
            'typeGroup' => EdmModule::SERVICE_ID,
            'direction' => Document::DIRECTION_OUT,
            'origin' => Document::ORIGIN_WEB,
            'terminalId' => $senderTerminal->id,
            'sender' => $senderTerminal->terminalId,
            'receiver' => $recipientTerminal,
        ];

        if ($account) {
            $requireSignatures = self::getRequireSignatures($account, $senderTerminal->terminalId);

            if ($requireSignatures > 0) {
                $documentSettings['signaturesRequired'] = $requireSignatures;
            }
        }

        if ($typeModel->debitAmount) {
            $debitAccount = EdmPayerAccount::findOne(['number' => $typeModel->debitAccount]);
            $debitAccountCurrencyName = $debitAccount->edmDictCurrencies->name;

            $amount = $typeModel->debitAmount;
            $currencyName = $debitAccountCurrencyName;
        } else {
            $creditAccount = EdmPayerAccount::findOne(['number' => $typeModel->creditAccount]);
            $creditAccountCurrencyName = $creditAccount->edmDictCurrencies->name;

            $amount = $typeModel->creditAmount;
            $currencyName = $creditAccountCurrencyName;
        }

        $context = DocumentHelper::createDocumentContext(
            $pain001Type,
            $documentSettings,
            [
                'numberDocument' => $typeModel->number,
                'date' => date('Y-m-d', strtotime($typeModel->date)),
                'debitAccount' => $typeModel->debitAccount,
                'creditAccount' => $typeModel->creditAccount,
                'currency' => $currencyName,
                'currencySum' => $amount,
                'documentType' => self::OPERATION_CONVERSION,
                'debitAmount' => $typeModel->debitAmount,
                'creditAmount' => $typeModel->creditAmount
            ],
            $senderTerminal->terminalId
        );

        if (!$context) {
            throw new \Exception(Yii::t('app', 'Save document error'));
        }

        // Удаление кэша
        WizardCacheHelper::deleteFCVNWizardCache();

        return $context['document'];
    }

    /**
     * @todo требуется рефакторинг views, которые получают имя и адрес банка.
     * необходимо убрать логику получения из views.
     * @param SwiftFinType $swiftFinType
     * @param type $fill - автозаполнять имя и адрес банка для views
     * @return ForeignCurrencyPaymentType
     */
    public static function constructFCPFromSwift(SwiftFinType $swiftFinType, $fill = false)
    {
        $swiftContainer = $swiftFinType->source;
        $fcpType = new ForeignCurrencyPaymentType();

        // Соответствие полей документа swift и полей валютного платежа
        $mapping = [
            '20' => 'number',
            '52a' => null,
            '56a' => null,
            '57a' => null,
            '70' => 'information',
            '72' => 'additionalInformation',
            '71A' => 'commission',
            '32A' => null,
            '33B' => null,
            '50a' => null,
            //'50F' => null,
            '59a' => null,
        ];

        // Заполнение полей валютного платежа
        $contentModel = $swiftContainer->getContentModel();

        foreach($mapping as $tag => $attribute) {
            $node = $contentModel->getNode($tag);
            $tag = strtoupper($tag);

            if ($node) {
                $value = trim($node->getValue());

                // Отдельная обработка составных полей
                if ($tag === '32A') {
                    // дата
                    $date = '20' . substr($value, 0, 6);
                    $date = strtotime($date);
                    $date = date('d.m.Y', $date);

                    // валюта
                    $currency = substr($value, 6, 3);

                    // сумма
                    //$sum = substr($value, 9);

                    $fcpType->date = $date;
                    $fcpType->currency = $currency;

                } else if ($tag === '33B') {

                    $sum = substr($value, 3);
                    $sum = floatval(str_replace(',', '.', $sum));

                    $fcpType->sum = $sum;
                } else if ($tag === '50A') {
                    // Счет плательщика
                    $arrValue = explode("\r\n", $value);

                    $fcpType->payerAccount = str_replace('/', '', array_shift($arrValue));
                    foreach ($arrValue as $string) {
                        if (preg_match('#^(\d)/(.+)$#', $string, $matches)) {
                            $prefix = $matches[1];
                            $value = $matches[2];
                            switch ($prefix) {
                                case '1':
                                    $fcpType->payerName = $value;
                                    break;
                                case '2':
                                    $fcpType->payerAddress = $value;
                                    break;
                                case '3':
                                    $fcpType->payerLocation = $value;
                                    break;
                            }
                        }
                    }

                    $account = EdmPayerAccount::findOne(['number' => $fcpType->payerAccount]);
                    if ($account) {
                        $org = $account->edmDictOrganization;
                        if ($org) {
                            $fcpType->payerInn = $org->inn;
                        }
                    }

                } else if ($tag === '52A') {
                    $arrValue = explode("\r\n", $value);

                    $fcpType->payerBank = $arrValue[0];

                    $swiftBank = SwiftFinDictBank::findByCode($fcpType->payerBank);

                    if ($swiftBank) {
                        $fcpType->payerBankName = $swiftBank->name;
                        $fcpType->payerBankAddress = $swiftBank->address;
                    }
                } else if ($tag === '56A') {

                    $arrValue = explode("\r\n", $value, 2);

                    if (count($arrValue) > 0 && preg_match('#^/.+$#', $arrValue[0])) {
                        $account = array_shift($arrValue);
                        $fcpType->intermediaryBankAccount = str_replace('/', '', $account);
                    }

                    if (count($arrValue) > 0) {
                        if ($node->choice === 'A') {
                            $swiftCode = $arrValue[0];
                            $fcpType->intermediaryBank = $swiftCode;
                            if ($fill) {
                                $swiftBank = SwiftFinDictBank::findOne(['fullCode' => $swiftCode]);
                                if ($swiftBank) {
                                    $fcpType->intermediaryBankNameAndAddress = $swiftBank->name;
                                    if ($swiftBank->address) {
                                        $fcpType->intermediaryBankNameAndAddress .= ', ' . $swiftBank->address;
                                    }
                                }
                            }
                        } else {
                            $fcpType->intermediaryBankNameAndAddress = implode("\r\n", $arrValue);
                        }
                    }
                } else if ($tag === '57A') {
                    $arrValue = explode("\r\n", $value, 2);

                    if (count($arrValue) > 0 && preg_match('#^/.+$#', $arrValue[0])) {
                        $account = array_shift($arrValue);
                        $fcpType->beneficiaryBankAccount = str_replace('/', '', $account);
                    }

                    if (count($arrValue) > 0) {
                        if ($node->choice === 'A') {
                            $swiftCode = $arrValue[0];
                            $fcpType->beneficiaryBank = $swiftCode;
                            if ($fill) {
                                $swiftBank = SwiftFinDictBank::findOne(['fullCode' => $swiftCode]);

                                if ($swiftBank) {
                                    $fcpType->beneficiaryBankNameAndAddress = $swiftBank->name;
                                    if ($swiftBank->address) {
                                        $fcpType->beneficiaryBankNameAndAddress .= ', ' . $swiftBank->address;
                                    }
                                }
                            }
                        } else {
                            $fcpType->beneficiaryBankNameAndAddress = implode("\r\n", $arrValue);
                        }
                    }
                } else if ($tag === '59A') {
                    // Счет получателя
                    $arrValue = explode("\r\n", $value, 2);

                    $fcpType->beneficiaryAccount = str_replace('/', '', $arrValue[0]);
                    $fcpType->beneficiary = $arrValue[1];
                } else if (!empty($attribute)) {
                    $fcpType->$attribute = $value;
                }
            }
        }

        // Получение поля 71F

        $content = $swiftContainer->getContent();

        $rows = preg_split('/[\\r\\n]+/', $content);

        foreach($rows as $item) {
            if (stristr($item, '71F')) {
                $commissionSum = substr($item, 8);
                $fcpType->commissionSum = floatval(str_replace(',', '.', $commissionSum));
            }
        }

        return $fcpType;
    }

    public static function constructFCSTFromPain001(Pain001Type $pain001Type, string $organizationAccountNumber)
    {
        $xml = $pain001Type->getRawXml();

        $fcst = new ForeignCurrencySellTransit();
        $fcst->number = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[0]->PmtId->EndToEndId;
        $date = (string) $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm;
        $fcst->date = date('d.m.Y', strtotime($date));
        $fcst->contactPersonName = (string) $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->Nm;
        $fcst->contactPersonPhone = (string) $xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->PhneNb;
        $fcst->currencyIncomingNumber = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[0]->RmtInf->Strd->RfrdDocInf->Nb;

        $currencyIncomingDate = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[0]->RmtInf->Strd->RfrdDocInf->RltdDt;
        $fcst->currencyIncomingDate = date('d.m.Y', strtotime($currencyIncomingDate));

        $fcst->transitAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id;
        $fcst->amount = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[0]->Amt->InstdAmt;

        if (isset($xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[2])) {
            $fcst->foreignAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->CdtrAcct->Id->Othr->Id;
            $fcst->amountTransfer = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->Amt->InstdAmt;

            $fcst->amountSell = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[2]->Amt->InstdAmt;
            $fcst->account = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[2]->CdtrAcct->Id->Othr->Id;
        } else if (isset($xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1])) {

            $type = $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->PmtTpInf->LclInstrm->Prtry;

            if ($type == 'TRF') {
                $fcst->foreignAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->CdtrAcct->Id->Othr->Id;
                $fcst->amountTransfer = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->Amt->InstdAmt;
            } else if ($type == 'FX') {
                $fcst->amountSell = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->Amt->InstdAmt;
                $fcst->account = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf[1]->CdtrAcct->Id->Othr->Id;
            }
        }

        $fcst->commissionAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->Id;
        $fcst->organizationAccount = $organizationAccountNumber;
        $account = EdmPayerAccount::findOne(['number' => $organizationAccountNumber]);
        if (!$account) {
            throw new InvalidValueException('Account not found for number: ' . $fcst->organizationAccount);
        }

        $fcst->organizationId = $account->organizationId;
        $fcst->bankBik = $account->bankBik;
        $fcst->sellOnMarket = !empty($fcst->amountSell);

        return $fcst;
    }

    public static function constructFCVNFromPain001(Pain001Type $pain001Type, ForeignCurrencyOperationDocumentExt $extModel)
    {
        $xml = $pain001Type->getRawXml();
        $date = (string) $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm;
        $debitAccount = EdmPayerAccount::findOne(['number' => $extModel->debitAccount]);

        $fcvn = new ForeignCurrencyConversion();
        $fcvn->number = $extModel->numberDocument;
        $fcvn->date = date('d.m.Y', strtotime($date));
        $fcvn->organizationId = $debitAccount ? $debitAccount->organizationId : null;
        $fcvn->contactPersonName = (string) $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->CtctDtls->Nm;
        $fcvn->contactPersonPhone = (string) $xml->CstmrCdtTrfInitn->GrpHdr->InitgPty->CtctDtls->PhneNb;
        $fcvn->debitAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id;
        $fcvn->creditAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAcct->Id->Othr->Id;
        $fcvn->debitAmount = $extModel->debitAmount;
        $fcvn->creditAmount = $extModel->creditAmount;
        $fcvn->commissionAccount = (string) $xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->Id;

        return $fcvn;
    }

    /**
     * @param Pain001Type $typeModel
     * @return ForeignCurrencyPaymentType[]
     * @throws \Exception
     */
    public static function constructForeignCurrencyPaymentsFromPain001(Pain001Type $typeModel): array
    {
        /** @var SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $paymentElements = $xml->xpath("/*[local-name()='Document']/*[local-name()='CstmrCdtTrfInitn']/*[local-name()='PmtInf']/*[local-name()='CdtTrfTxInf']");

        $organizationsCache = [];
        $getOrganizationByAccountNumber = function (?string $accountNumber) use ($organizationsCache): ?DictOrganization {
            if (empty($accountNumber)) {
                return null;
            }
            if (!array_key_exists($accountNumber, $organizationsCache)) {
                $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
                $organizationsCache[$accountNumber] = $account && $account->edmDictOrganization ? $account->edmDictOrganization : null;
            }
            return $organizationsCache[$accountNumber];
        };

        $swiftBanksCache = [];
        $getSwiftBank = function (?string $swiftCode) use ($swiftBanksCache): ?SwiftFinDictBank {
            if (empty($swiftCode)) {
                return null;
            }
            if (!array_key_exists($swiftCode, $swiftBanksCache)) {
                $swiftBanksCache[$swiftCode] = SwiftFinDictBank::findByCode($swiftCode);
            }
            return $swiftBanksCache[$swiftCode];
        };

        return array_map(
            function (SimpleXMLElement $paymentElement) use ($getSwiftBank, $getOrganizationByAccountNumber, $xml) {
                $date = (string)$paymentElement->RmtInf->Strd->RfrdDocInf->RltdDt;

                $payerAccount = (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id ?: null;
                $organization = $getOrganizationByAccountNumber($payerAccount);
                $payerInn = (string)@$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr->Id;
                if (empty($payerInn)) {
                    $payerInn = $organization ? $organization->inn : null;
                }

                if (isset($xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm)) {
                    $payerName = (string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm;
                } else {
                    $payerName = $organization ? $organization->nameLatin : null;
                }

                if (isset($xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->AdrLine)) {
                    $payerAddress = (string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->AdrLine[0];
                    $payerLocation = (string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->AdrLine[1];
                } else {
                    $payerAddress = $organization ? $organization->addressLatin : null;
                    $payerLocation = $organization ? $organization->locationLatin : null;
                }

                $payerBankSwiftCode = (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->BICFI ?: null;
                $payerBank = $getSwiftBank($payerBankSwiftCode);

                $intermediaryBankSwiftCode = (string)@$paymentElement->IntrmyAgt1->FinInstnId->BICFI ?: null;
                $intermediaryBank = $getSwiftBank($intermediaryBankSwiftCode);

                $beneficiaryBankSwiftCode = (string)@$paymentElement->CdtrAgt->FinInstnId->BICFI ?: null;
                $beneficiaryBank = $getSwiftBank($beneficiaryBankSwiftCode);

                $informationElements = $paymentElement->xpath("./*[local-name()='RmtInf']/*[local-name()='Ustrd']");
                $information = implode('', array_map('strval', $informationElements));

                $commissionAccount = (string)@$xml->CstmrCdtTrfInitn->PmtInf->ChrgsAcct->Id->Othr->Id;

                $intermediaryBankAccountAccount = (string)@$paymentElement->IntrmyAgt1Acct->Id->Othr->Id
                    ?: (string)@$paymentElement->IntrmyAgt1Acct->Id->IBAN
                        ?: null;

                return new ForeignCurrencyPaymentType([
                    'number'                         => (string)@$paymentElement->PmtId->EndToEndId,
                    'sum'                            => (string)@$paymentElement->Amt->InstdAmt,
                    'date'                           => $date ? (new \DateTime($date))->format('Y-m-d') : null,
                    'currency'                       => (string)@$paymentElement->Amt->InstdAmt['Ccy'],
                    'immediatePayment'               => (string)@$paymentElement->PmtTpInf->SvcLvl->Cd,
                    'payerAccount'                   => $payerAccount,
                    'payerInn'                       => $payerInn,
                    'payerName'                      => $payerName,
                    'payerAddress'                   => $payerAddress,
                    'payerLocation'                  => $payerLocation,
                    'payerBank'                      => $payerBankSwiftCode,
                    'payerBankName'                  => $payerBank ? $payerBank->name : null,
                    'payerBankAddress'               => $payerBank ? $payerBank->address : null,
                    'intermediaryBank'               => $intermediaryBankSwiftCode,
                    'intermediaryBankNameAndAddress' => $intermediaryBank ? "{$intermediaryBank->name}\n{$intermediaryBank->address}" : null,
                    'intermediaryBankAccount'        => $intermediaryBankAccountAccount,
                    'beneficiaryAccount'             => (string)@$paymentElement->CdtrAcct->Id->Othr->Id,
                    'beneficiary'                    => (string)@$paymentElement->Cdtr->Nm,
                    'beneficiaryAddress'             => (string)@$paymentElement->Cdtr->PstlAdr->AdrLine[0],
                    'beneficiaryLocation'            => (string)@$paymentElement->Cdtr->PstlAdr->AdrLine[1],
                    'beneficiaryBank'                => $beneficiaryBankSwiftCode,
                    'beneficiaryBankNameAndAddress'  => $beneficiaryBank ? "{$beneficiaryBank->name}\n{$beneficiaryBank->address}" : null,
                    'beneficiaryBankAccount'         => (string)@$xml->CstmrCdtTrfInitn->PmtInf->CdtrAgtAcct->Id->Othr->Id,
                    'information'                    => $information,
                    'commission'                     => (string)@$paymentElement->ChrgBr,
                    'commissionAccount'              => $commissionAccount,
                    'additionalInformation'          => (string)@$paymentElement->RmtInf->Strd->AddtlRmtInf,
                    'uuid'                           => (string)@$paymentElement->PmtId->InstrId,
                ]);
            },
            $paymentElements
        );
    }

    /**
     * Получение количества подписей, требуемых для подписания документа
     * По счету или из общих настроек
     */
    private static function getRequireSignatures(EdmPayerAccount $account, $senderTerminal)
    {
        $requireSignatures = 0;

        $edmModule = Yii::$app->addon->getModule('edm');

        if ($account->requireSignQty) {
            $requireSignatures = $account->requireSignQty;
        } else if ($edmModule->isSignatureRequired(Document::ORIGIN_WEB, $senderTerminal)) {
            $requireSignatures = $edmModule->getSignaturesNumber($senderTerminal);
        }

        return $requireSignatures;
    }

    /**
     * Создание шаблона валютного платежа
     * @param ForeignCurrencyPaymentType $typeModel
     * @return bool
     */
    private static function createForeignCurrencyPaymentTemplate(ForeignCurrencyPaymentType $typeModel)
    {
        $template = new ForeignCurrencyPaymentTemplate();

        foreach($typeModel->attributes as $key => $value) {
            if (!$template->hasAttribute($key)) {
                continue;
            }

            $template->$key = $value;
        }

        return $template->save();
    }

    public static function constructFCOFromPain001(Pain001Type $pain001TypeModel): ForeignCurrencyOperationType
    {
        $xml = $pain001TypeModel->getRawXml();

        $getByXpath = function ($xpath) use ($xml) {
            $nodes  = $xml->xpath($xpath);
            return $nodes != false ? (string)$nodes[0] : null;
        };

        $date = $xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm
            ? date('d.m.Y', strtotime((string)$xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm))
            : null;

        $debitAccountNumber = $getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:DbtrAcct/a:Id/a:Othr[a:SchmeNm/a:Cd/text()='BBAN']/a:Id");
        $debitAccount = EdmPayerAccount::findOne(['number' => $debitAccountNumber]);

        $creditAccountNumber = $getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:CdtTrfTxInf/a:CdtrAcct/a:Id/a:Othr[a:SchmeNm/a:Cd/text()='BBAN']/a:Id");
        $creditAccount = EdmPayerAccount::findOne(['number' => $creditAccountNumber]);

        $commissionAccountNumber = $getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:ChrgsAcct/a:Id/a:Othr[a:SchmeNm/a:Cd/text()='BBAN']/a:Id");
        $commissionAccount = EdmPayerAccount::findOne(['number' => $commissionAccountNumber]);

        $fco = new ForeignCurrencyOperationType([
            'uuid'                         => $pain001TypeModel->msgId,
            'numberDocument'               => (string)$xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->PmtId->EndToEndId,
            'date'                         => $date,
            'recipientBankBik'             => (string)$xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId,
            'paymentOrderCurrExchangeRate' => (float)$xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->XchgRateInf->XchgRate ?: null,
            'organizationName'             => (string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm ?: null,
            'signatures'                   => [],
        ]);

        $address = implode(
            '',
            (array)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->PstlAdr->AdrLine
        );
        $fco->applicant->setAttributes([
            'contactPerson' => (string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->Nm ?: null,
            'phone'         => (string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->CtctDtls->PhneNb ?: null,
            'name'          => $debitAccount->organizationId,
            'address'       => $address ?: null,
            'inn'           => $getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:Dbtr/a:Id/a:OrgId/a:Othr[a:SchmeNm/a:Cd/text()='TXID']/a:Id"),
        ]);

        $fco->debitAccount->setAttributes([
            'number'            => $debitAccountNumber,
            'bankName'          => (string)$xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->Nm ?: null,
            'bankAccountNumber' => $debitAccount->bank->account,
            'bik'               => $getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:DbtrAgt/a:FinInstnId/a:ClrSysMmbId[a:ClrSysId/a:Cd/text()='RUCBC']/a:MmbId"),
        ]);

        $fco->creditAccount->setAttributes([
            'number'            => $creditAccountNumber,
            'bankName'          => (string)$xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf->CdtrAgt->FinInstnId->Nm ?: null,
            'bankAccountNumber' => $creditAccount->bank->account,
            'bik'               => $getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:CdtTrfTxInf/a:CdtrAgt/a:FinInstnId/a:ClrSysMmbId[a:ClrSysId/a:Cd/text()='RUCBC']/a:MmbId"),
        ]);

        $fco->commissionAccount->setAttributes([
            'number'            => $commissionAccountNumber,
            'bankName'          => $commissionAccount->bank->name,
            'bankAccountNumber' => $commissionAccount->bank->account,
            'bik'               => $commissionAccount->bank->name,
        ]);

        $debitAccountCurrency = $fco->getDebitAccountCurrency()->name;
        $isPurchase = in_array($debitAccountCurrency, ['RUR', 'RUB']);
        if ($isPurchase) {
            $fco->setOperationType(self::OPERATION_PURCHASE);
            $fco->paymentOrderCurrCode = $fco->getCreditAccountCurrency()->name;
        } else {
            $fco->setOperationType(self::OPERATION_SELL);
            $fco->paymentOrderCurrCode = $debitAccountCurrency;
        }

        $fco->paymentOrderAmount = (float)$getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:CdtTrfTxInf/a:Amt/a:InstdAmt[@Ccy='RUR' or @Ccy='RUB']") ?: null;
        $fco->paymentOrderCurrAmount = (float)$getByXpath("/a:Document/a:CstmrCdtTrfInitn/a:PmtInf/a:CdtTrfTxInf/a:Amt/a:InstdAmt[@Ccy!='RUR' and @Ccy!='RUB']") ?: null;

        return $fco;
    }
}

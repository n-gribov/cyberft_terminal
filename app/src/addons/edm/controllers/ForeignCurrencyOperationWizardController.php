<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentTemplate;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Pain001Type;
use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\models\SwiftFinDictBank;
use addons\swiftfin\models\SwiftFinType;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\helpers\WizardCacheHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class ForeignCurrencyOperationWizardController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => [
                                EdmDocumentTypeGroup::CURRENCY_PURCHASE,
                                EdmDocumentTypeGroup::CURRENCY_SELL,
                                EdmDocumentTypeGroup::CURRENCY_PAYMENT,
                                EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
                                EdmDocumentTypeGroup::CURRENCY_CONVERSION,
                            ],
                        ],
                    ],
					[
						'allow'      => true,
                        'roles'      => [DocumentPermission::SIGN],
                        'actions'    => ['reject-signing'],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => [
                                EdmDocumentTypeGroup::CURRENCY_PURCHASE,
                                EdmDocumentTypeGroup::CURRENCY_SELL,
                                EdmDocumentTypeGroup::CURRENCY_PAYMENT,
                                EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
                                EdmDocumentTypeGroup::CURRENCY_CONVERSION,
                            ],
                        ],
					],
                ],
            ],
        ];
    }

    private function renderView($type, $typeModel, $id = null)
    {
        $view = ForeignCurrencyOperationFactory::prepareRenderDataByType($type, $typeModel, $id);

        return $this->renderAjax($view, ['id' => $id, 'model' => $typeModel]);
    }

    public function actionUpdate($id, $type = null)
    {
        if (Yii::$app->request->isPost && !Yii::$app->request->post('isRealSubmit')) {
            $typeModel = ForeignCurrencyOperationFactory::getModelByType($type);

            // Загрузить данные модели из формы в браузере
            $typeModel->load(Yii::$app->request->post());
            // Включить формат вывода JSON
            Yii::$app->response->format = Response::FORMAT_JSON;

            $result = array_merge(
                ForeignCurrencyOperationFactory::validateByType($type, $typeModel),
                ActiveForm::validate($typeModel)
            );

            return $result;
        }

        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
        $this->authorizeDocumentAccess($document);
        $extModel = $document->extModel;

        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        if ($typeModel instanceof SwiftFinType) {
            $typeModel = ForeignCurrencyOperationFactory::constructFCPFromSwift($typeModel);
        } else if ($extModel->documentType == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
            $typeModel = ForeignCurrencyOperationFactory::constructFCSTFromPain001($typeModel, $extModel->debitAccount);
        } else if ($extModel->documentType == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
            $typeModel = ForeignCurrencyOperationFactory::constructFCVNFromPain001($typeModel, $extModel);
        } else if (in_array($extModel->documentType, [ForeignCurrencyOperationFactory::OPERATION_PURCHASE, ForeignCurrencyOperationFactory::OPERATION_SELL])) {
            $typeModel = ForeignCurrencyOperationFactory::constructFCOFromPain001($typeModel);
        } else {
            $typeModel->operationType = $type;
        }

        if (!Yii::$app->request->isPost) {
            return $this->renderView($type, $typeModel, $id);
        }

        $cyxDoc->rejectSignatures();

        // Если данные модели успешно загружены из формы в браузере
        if ($typeModel->load(Yii::$app->request->post())) {
            ForeignCurrencyOperationFactory::validateByType($type, $typeModel);

            // Порядок проверки в данном блоке обязателен!
            // validate сбрасывает все добавленные ошибки!
            if (!$typeModel->hasErrors() && $typeModel->validate()) {

                if ($typeModel->type == ForeignCurrencyOperationFactory::OPERATION_PURCHASE ||
                    $typeModel->type == ForeignCurrencyOperationFactory::OPERATION_SELL) {
                    $xml = ISO20022Helper::createPain001FromFCO($typeModel);
                    $pain001Type = new Pain001FxType();
                    $pain001Type->loadFromString($xml);
                    $cyxDoc = $cyxDoc->replaceTypeModel($pain001Type);
                } else if ($typeModel->operationType == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
                    $xml = ISO20022Helper::createPain001FromFCST($typeModel);

                    $pain001Type = new Pain001Type();
                    $pain001Type->loadFromString($xml);

                    // Валидация по XSD-схеме
                    // CYB-4238 (Закомментировал, т.к. с ней не работает редактирование)
                    /*$pain001Type->validateXSD();

                    if ($pain001Type->errors) {
                        Yii::info('Pain.001 validation against XSD-scheme failed');
                        Yii::info($pain001Type->errors);

                        return false;
                    }*/

                    $cyxDoc = $cyxDoc->replaceTypeModel($pain001Type);

                    //$account = EdmPayerAccount::findOne(['number' => $typeModel->organizationAccount]);
                    //$organization = $account->edmDictOrganization;
                } else if ($typeModel->operationType == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
                    $xml = ISO20022Helper::createPain001FromFCVN($typeModel);

                    $pain001Type = new Pain001Type();
                    $pain001Type->loadFromString($xml);

                    // Валидация по XSD-схеме
                    // CYB-4238 (Закомментировал, т.к. с ней не работает редактирование)
                    /*$pain001Type->validateXSD();

                    if ($pain001Type->errors) {
                        Yii::info('Pain.001 validation against XSD-scheme failed');
                        Yii::info($pain001Type->errors);

                        return false;
                    }*/

                    $cyxDoc = $cyxDoc->replaceTypeModel($pain001Type);
                } else {
                    // Счет плательщика
                    $account = EdmPayerAccount::findOne(['number' => $typeModel->payerAccount]);
                    $organization = $account->edmDictOrganization;
                    $bank = $account->bank;

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

                    if (!$swt->validate()) {
                        throw new \Exception('Ошибка валидации swift-документа');
                    }

                    // Создание type-модели swift
                    $swiftTypeModel = SwiftFinType::createFromData($swt->getRawText());
                    $cyxDoc->setTypeModel($swiftTypeModel);
                }

                $storedFile = Yii::$app->storage->get($document->actualStoredFileId);
                $storedFile->updateData($cyxDoc->saveXML());

                // Обнуление количества требуемых подписей
                $documentModel = Document::findOne($id);

                if ($documentModel) {
                    $documentModel->signaturesCount = 0;
                    $documentModel->setSignData(null);
                    // Сохранить модель в БД
                    $documentModel->save();
                    // Отправить документ на обработку в транспортном уровне
                    DocumentTransportHelper::processDocument($documentModel, true);
                }

                // Обновление extModel
                $extModel = $document->extModel;

                if ($typeModel->type == ForeignCurrencyOperationFactory::OPERATION_PURCHASE ||
                    $typeModel->type == ForeignCurrencyOperationFactory::OPERATION_SELL) {

                    $extModel->numberDocument = $typeModel->numberDocument;
                    $extModel->date = date('Y-m-d', strtotime($typeModel->date));
                    $extModel->payer = $typeModel->applicant->name;
                    $extModel->debitAccount = $typeModel->debitAccount->number;
                    $extModel->creditAccount = $typeModel->creditAccount->number;
                    $extModel->currency = $typeModel->paymentOrderCurrCode;
                    $extModel->currencySum = $typeModel->paymentOrderCurrAmount;
                    $extModel->sum = $typeModel->paymentOrderAmount;
                } else if ($typeModel->operationType == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
                    $extModel->payer = (string) $typeModel->organizationId;
                    $extModel->numberDocument = $typeModel->number;
                    $extModel->date = date('Y-m-d', strtotime($typeModel->date));
                    $extModel->debitAccount = $typeModel->transitAccount; //organizationAccount;
                    $extModel->creditAccount = $typeModel->account;
                    $extModel->currency = $typeModel->amountCurrency;
                    $extModel->currencySum = $typeModel->amount;
                } else if ($typeModel->operationType == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
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

                    $extModel->numberDocument = $typeModel->number;
                    $extModel->payer = $typeModel->organizationId;
                    $extModel->date = date('Y-m-d', strtotime($typeModel->date));
                    $extModel->debitAccount = $typeModel->debitAccount;
                    $extModel->creditAccount = $typeModel->creditAccount;
                    $extModel->currency = $currencyName;
                    $extModel->currencySum = $amount;
                    $extModel->documentType = ForeignCurrencyOperationFactory::OPERATION_CONVERSION;
                    $extModel->debitAmount = $typeModel->debitAmount;
                    $extModel->creditAmount = $typeModel->creditAmount;
                } else {
                    $extModel->numberDocument = $typeModel->number;
                    $extModel->payer = (string) $organization->id;
                    $extModel->date = date('Y-m-d', strtotime($typeModel->date));
                    $extModel->debitAccount = $typeModel->payerAccount;
                    $extModel->creditAccount = $typeModel->beneficiaryAccount;
                    $extModel->currency = $typeModel->currency;
                    $extModel->currencySum = $typeModel->sum;
                    $extModel->paymentPurpose = $typeModel->information;
                }

                // Сохранить модель в БД
                $extModel->save();

                switch ($type) {
                    case ForeignCurrencyOperationFactory::OPERATION_PURCHASE:
                        $tabMode = 'tabPurchase';
                        break;
                    case ForeignCurrencyOperationFactory::OPERATION_SELL:
                        $tabMode = 'tabSell';
                        break;
                    case ForeignCurrencyOperationFactory::OPERATION_PAYMENT:
                        // Поместить в сессию id документа
                        Yii::$app->session->setFlash('fcpCU', $document->id);
                        // Перенаправить на страницу индекса
                        return $this->redirect(['/edm/currency-payment/payment-index']);
                    case ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT:
                        $tabMode = 'tabPain001';
                        break;
                    case ForeignCurrencyOperationFactory::OPERATION_CONVERSION:
                        $tabMode = 'tabCurrencyConversion';
                        break;
                }

                // Поместить в сессию id и тип документа
                Yii::$app->session->setFlash('fcoCU', json_encode(['id' => $document->id, 'type' => $type]));

                // Перенаправить на страницу индекса
                return $this->redirect(['/edm/documents/foreign-currency-operation-journal', 'tabMode' => $tabMode]);
            }

            $template = ForeignCurrencyOperationFactory::getOperationProcessTemplate($type, ForeignCurrencyOperationFactory::PROCESS_UPDATE);

            return $this->renderAjax($template, ['model' => $typeModel, 'id' => $id]);
        }
    }

    public function actionRejectSigning()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('document-id');
            $model = Document::findOne($id);
            $businessStatusComment = (string) Yii::$app->request->post('businessStatusComment');

            if (empty($businessStatusComment)) {
                // Поместить в сессию флаг сообщения об необходимости описать причину отказа
                Yii::$app->session->addFlash('warning', Yii::t('edm', 'Please provide reject reason'));

                // Перенаправить на страницу индекса
                return $this->redirect(['documents/foreign-currency-operation-journal']);
            }

            $model->status = Document::STATUS_SIGNING_REJECTED;

            // Сохранить модель в БД
            if ($model->save()) {
                // Зарегистрировать событие отказа в подписании реестра в модуле мониторинга
                Yii::$app->monitoring->log(
                    'edm:registerSigningRejected',
                    $model->type,
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'reason' => $businessStatusComment,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );

                // Зарегистрировать событие отмены подписания документа в модуле мониторинга
                Yii::$app->monitoring->extUserLog('RejectSigningDocument', ['documentId' => $id]);
            }

        }

        // Перенаправить на страницу индекса
        return $this->redirect(['documents/foreign-currency-operation-journal']);
    }

    public function actionCreateFromExistingDocument($type, $id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
        $this->authorizeDocumentAccess($document);
        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $extModel = $document->extModel;

        if ($typeModel instanceof SwiftFinType) {
            $type = ForeignCurrencyOperationFactory::OPERATION_PAYMENT;
            $typeModel = ForeignCurrencyOperationFactory::constructFCPFromSwift($typeModel);
            $typeModel->number = DocumentHelper::getDayUniqueCount('fco');
        } else if ($extModel->documentType == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
            $type = ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT;
            $typeModel = ForeignCurrencyOperationFactory::constructFCSTFromPain001($typeModel, $document->extModel->debitAccount);
            $typeModel->operationType = $type;
            $typeModel->number = DocumentHelper::getDayUniqueCount('fcst');
        } else if ($extModel->documentType == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
            $type = ForeignCurrencyOperationFactory::OPERATION_CONVERSION;
            $typeModel = ForeignCurrencyOperationFactory::constructFCVNFromPain001($typeModel, $document->extModel);
            $typeModel->operationType = $type;
            $typeModel->number = DocumentHelper::getDayUniqueCount('fcvn');
        } else if (in_array($extModel->documentType, [ForeignCurrencyOperationFactory::OPERATION_PURCHASE, ForeignCurrencyOperationFactory::OPERATION_SELL])) {
            $typeModel = ForeignCurrencyOperationFactory::constructFCOFromPain001($typeModel);
            $typeModel->date = date('d.m.Y');
            $typeModel->numberDocument = DocumentHelper::getDayUniqueCount('fco');
            $type = $typeModel->operationType;
        } else {
            $typeModel->operationType = $type;
            $typeModel->numberDocument = DocumentHelper::getDayUniqueCount('fco');
        }

        $typeModel->date = date('d.m.Y');

        return $this->renderView($type, $typeModel);
    }

    public function actionCreate($type = null, $templateId = null)
    {
        $typeModel = ForeignCurrencyOperationFactory::getModelByType($type);

        if (Yii::$app->request->isPost && !Yii::$app->request->post('isRealSubmit')) {
            // Включить формат вывода JSON
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Загрузить данные модели из формы в браузере
            $typeModel->load(Yii::$app->request->post());
            $result = array_merge(
                ForeignCurrencyOperationFactory::validateByType($type, $typeModel),
                ActiveForm::validate($typeModel)
            );

            return $result;
        }

        if ($typeModel) {
            $typeModel->date = date('d.m.Y');
        }

        $this->authorizeCreateAction($type);

        if (
            $type == ForeignCurrencyOperationFactory::OPERATION_PURCHASE
            || $type == ForeignCurrencyOperationFactory::OPERATION_SELL
        ) {
            $typeModel->operationType = $type;

            if (!$typeModel->numberDocument) {
                $typeModel->numberDocument = DocumentHelper::getDayUniqueCount('fco');
            }
            // Получение кэша, если он был
            if ($cachedData = WizardCacheHelper::getFCOWizardCache($type)) {
                $typeModel = $cachedData['model'];
            }
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_PAYMENT) {
            // Использование данных из шаблона
            if ($templateId) {
                $templateModel = ForeignCurrencyPaymentTemplate::findOne($templateId);
                if (!$templateModel) {
                    throw new NotFoundHttpException('Template not found');
                }

                foreach($templateModel->attributes as $key => $value) {
                    if (!$typeModel->hasProperty($key)) {
                        continue;
                    }

                    $typeModel->$key = $value;
                }
            } else if ($cachedData = WizardCacheHelper::getFCPWizardCache()) {
                $typeModel = $cachedData['model'];
            }

            if (!$typeModel->number) {
                $typeModel->number = DocumentHelper::getDayUniqueCount('fco');
            }

            // Получение кэша, если он был
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
            $typeModel->operationType = $type;

            if (!$typeModel->number) {
                $typeModel->number = DocumentHelper::getDayUniqueCount('fcst');
            }

            // Получение кэша, если он был
            if ($cachedData = WizardCacheHelper::getFCSTWizardCache()) {
                $typeModel = $cachedData['model'];
            }
        } else if ($type == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
            $typeModel->operationType = $type;

            if (!$typeModel->number) {
                $typeModel->number = DocumentHelper::getDayUniqueCount('fcvn');
            }

            // Получение кэша, если он был
            if ($cachedData = WizardCacheHelper::getFCVNWizardCache()) {
                $typeModel = $cachedData['model'];
            }

            $typeModel->isNew = true;
        }

        if (!Yii::$app->request->isPost) {
            return $this->renderView($type, $typeModel);
        }

        // Если данные модели успешно загружены из формы в браузере
        if ($typeModel->load(Yii::$app->request->post())) {

            ForeignCurrencyOperationFactory::validateByType($type, $typeModel);

            // Порядок проверки в данном блоке обязателен!
            // validate сбрасывает все добавленные ошибки!
            if (!$typeModel->hasErrors() && $typeModel->validate()) {
                $document = ForeignCurrencyOperationFactory::createDocument($typeModel);

                // Отправить документ на обработку в транспортном уровне
                DocumentTransportHelper::processDocument($document);

                switch ($type) {
                    case ForeignCurrencyOperationFactory::OPERATION_PURCHASE:
                        $tabMode = 'tabPurchase';
                        break;
                    case ForeignCurrencyOperationFactory::OPERATION_SELL:
                        $tabMode = 'tabSell';
                        break;
                    case ForeignCurrencyOperationFactory::OPERATION_PAYMENT:
                        // Поместить в сессию id документа
                        Yii::$app->session->setFlash('fcpCU', $document->id);
                        // Перенаправить на страницу индекса
                        return $this->redirect(['/edm/currency-payment/payment-index']);
                    case ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT:
                        $tabMode = 'tabPain001';
                        break;
                    case ForeignCurrencyOperationFactory::OPERATION_CONVERSION:
                        $tabMode = 'tabCurrencyConversion';
                        break;
                }

                // Поместить в сессию id и тип документа
                Yii::$app->session->setFlash('fcoCU', json_encode(['id' => $document->id, 'type' => $type]));

                // Перенаправить на страницу индекса
                return $this->redirect(['/edm/documents/foreign-currency-operation-journal', 'tabMode' => $tabMode]);
            }

            // Получение шаблона по типу операции и текущему процессу
            $template = ForeignCurrencyOperationFactory::getOperationProcessTemplate($type, ForeignCurrencyOperationFactory::PROCESS_CREATE);

            return $this->renderAjax($template, ['model' => $typeModel]);
        }
    }

    /**
     * Получение информации о статусе подписания документа
     */
    public function actionGetDocumentSignaturesCount($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];

        $document = Document::findOne($id);
        $this->authorizeDocumentAccess($document);

        if ($document) {
            $out['signaturesCount'] = $document->signaturesCount;
        } else {
            $out['signaturesCount'] = 0;
        }

        return $out;
    }

    /**
     * Получение разметки блока операции справки о валютных операциях
     */
    public function actionGetFcoiOperationTemplate()
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new \HttpException('404','Method not found');
        }

        return $this->renderAjax('fcoi/operationElement');
    }


    /**
     * Получение списка swift-банков через AJAX
     * @param null $q
     * @return array
     * @throws MethodNotAllowedHttpException
     */
    public function actionGetSwiftBanksList($q = null)
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        return SwiftfinHelper::getBanksList($q);
    }

    /**
     * Получение информации по swift-банку
     * @param $swiftInfo
     * @return string
     * @throws MethodNotAllowedHttpException
     */
    public function actionGetSwiftBankInfo($swiftInfo)
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        $bankInfo = null;
        if (strlen($swiftInfo) === 8) { // указат только код банка
            $bank = SwiftFinDictBank::find()
                ->where(['swiftCode' => $swiftInfo])
                ->limit(1)
                ->one();
            if ($bank) {
                $bankInfo = [
                    'name' => $bank->name,
                    'address' => $bank->address
                ];
            }
        } else if (strlen($swiftInfo) === 11) { // указан код банка + код филиала
            $bankInfo = SwiftfinHelper::getBankInfo($swiftInfo);
        }

        return $bankInfo ? json_encode($bankInfo) : null;
    }

    private function authorizeDocumentAccess(Document $document)
    {
        $this->authorizePermission(
            DocumentPermission::CREATE,
            [
                'serviceId' => EdmModule::SERVICE_ID,
                'document' => $document,
            ]
        );
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    private function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(ForeignCurrencyOperationSearch::className(), $id);
    }

    private function authorizeCreateAction(string $operationType)
    {
        $typeGroupsMap = [
            ForeignCurrencyOperationFactory::OPERATION_SELL => EdmDocumentTypeGroup::CURRENCY_SELL,
            ForeignCurrencyOperationFactory::OPERATION_PAYMENT => EdmDocumentTypeGroup::CURRENCY_PAYMENT,
            ForeignCurrencyOperationFactory::OPERATION_PURCHASE => EdmDocumentTypeGroup::CURRENCY_PURCHASE,
            ForeignCurrencyOperationFactory::OPERATION_CONVERSION => EdmDocumentTypeGroup::CURRENCY_CONVERSION,
            ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT => EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
        ];

        if (!array_key_exists($operationType, $typeGroupsMap)) {
            throw new \Exception("Unsupported operation: $operationType");
        }

        $this->authorizePermission(
            DocumentPermission::CREATE,
            [
                'serviceId' => EdmModule::SERVICE_ID,
                'documentTypeGroup' => $typeGroupsMap[$operationType],
            ]
        );
    }
}
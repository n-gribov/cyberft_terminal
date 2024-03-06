<?php

namespace addons\edm\controllers;

use addons\edm\controllers\helpers\FCCHelper;
use addons\edm\EdmModule;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequest;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestNonresident;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestPaymentSchedule;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestTranche;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Auth018Type;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\BaseServiceController;
use common\base\traits\AuthorizesDocumentPermission;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\helpers\WizardCacheHelper;
use common\models\Terminal;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ContractRegistrationRequestController extends BaseServiceController
{
    use AuthorizesDocumentPermission;

    // Форма создания/изменения нерезидента
    public $nonresidentFormView = '@addons/edm/views/contract-registration-request/nonresident';

    // Список добавленных нерезидентов
    public $nonresidentListView = '@addons/edm/views/contract-registration-request/_nonresidents';

    // Форма управления траншами
    public $trancheFormView = '@addons/edm/views/contract-registration-request/tranche';

    // Список добавленных траншей
    public $trancheListView = '@addons/edm/views/contract-registration-request/_tranches';

    // Форма управления графиком платежей
    public $paymentScheduleFormView = '@addons/edm/views/contract-registration-request/paymentSchedule';

    // Список добавленных элементов графика платежей
    public $paymentScheduleListView = '@addons/edm/views/contract-registration-request/_paymentSchedule';

    private $crrCache;

    // Ключ кэша данных списка нерезидентов
    public $nonresidentCacheKey = 'edm/crr/nonresident';

    // Ключ кэша данных списка траншей
    public $trancheCacheKey = 'edm/crr/tranche';

    // Ключ кэша данных графика платежей
    public $paymentScheduleCacheKey = 'edm/crr/payment-schedule';

    // Ключ кэша данных списка нерезидентов-кредиторов
    public $nonresidentCreditCacheKey = 'edm/crr/nonresident-credit';

    // Ссылка на журнал документоа
    public $crrJournal = '/edm/documents/foreign-currency-control-index?tabMode=tabCRR';

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->crrCache = new ControllerCache('contractRegistrationRequest');
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'print'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => [
                                EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                                EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                            ],
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'add-related-data', 'process-related-data-form', 'delete-related-data', 'update-related-data'],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => [
                                EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                                EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                            ],
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'delete-crr'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => [
                                EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                                EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                            ],
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['send', 'before-signing'],
                        'roles' => [DocumentPermission::CREATE, DocumentPermission::SIGN],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => [
                                EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                                EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                            ],
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'before-signing' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Страница создания документа
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new ContractRegistrationRequestExt();

        // При открытии страницы очищаем кэш данных
        if (Yii::$app->request->isGet) {
            // Очистка кэша создания документа и редирект
            if (Yii::$app->request->get('clearWizardCache')) {
                WizardCacheHelper::deleteCRRWizardCache();
                return $this->redirect('/edm/contract-registration-request/create');
            }

            // значение по-умолчанию для новых документов
            $model->passportType = ContractRegistrationRequestExt::PASSPORT_TYPE_TRADE;
            $model->reasonFillPaymentsSchedule = ContractRegistrationRequestExt::REASON_PAYMENTS_SCHEDULE_LOAN;
            $model->number = DocumentHelper::getDayUniqueCount('crr');
            $model->date = date('d.m.Y');

            $cacheData = [
                $this->nonresidentCacheKey,
                $this->trancheCacheKey,
                $this->paymentScheduleCacheKey,
                $this->nonresidentCreditCacheKey
            ];

            foreach($cacheData as $item) {
                FCCHelper::clearChildObjectCache($item);
            }

            if ($cachedData = WizardCacheHelper::getCRRWizardCache()) {
                if (isset($cachedData['model'])) {
                    $model = $cachedData['model'];
                }

                // Запись закэшированных Связанные данные
                $relatedData = [
                    'nonresidents' => $this->nonresidentCacheKey,
                    'tranches' => $this->trancheCacheKey,
                    'paymentSchedule' => $this->paymentScheduleCacheKey,
                    'nonresidentsCredit' => $this->nonresidentCreditCacheKey
                ];

                foreach($relatedData as $relatedId => $relatedItem) {
                    if (!isset($cachedData[$relatedId])) {
                        continue;
                    }

                    $newData = $cachedData[$relatedId];

                    // Запись кэша
                    FCCHelper::setChildObjectCache($relatedItem, $newData);

                    // Запись существующих данных в модель
                    $model->$relatedId = $newData;
                }
            } else if ($id = Yii::$app->request->get('id')) {
                // Если создание на основе имеющегося документа
                $document = $this->findDocument($id);
                $model = ContractRegistrationRequestExt::findOne(['documentId' => $document->id]);
                $this->fillRelatedData($model);
            }

            $model->number = DocumentHelper::getDayUniqueCount('crr');
            $model->date = date('d.m.Y');
        }

        return $this->processContractRegistrationRequest($model);
    }

    /**
     * Страница редактирования документа
     * @param $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        $document = $this->findDocument($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::CREATE, $document);

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Modify error. Contract registration request is already sent'));

            return $this->redirect([$this->crrJournal]);
        }

        $model = ContractRegistrationRequestExt::findOne(['documentId' => $document->id]);

        // При открытии страницы устанавливаем кэш данных
        if (Yii::$app->request->isGet) {
            // Связанные данные
            $this->fillRelatedData($model);
        }

        return $this->processContractRegistrationRequest($model);
    }

    private function fillRelatedData($model)
    {
        // Связанные данные
        $relatedData = [
            'nonresidents' => [
                'items' => $model->nonresidentsItems,
                'cacheKey' => $this->nonresidentCacheKey
            ],
            'tranches' => [
                'items' => $model->tranchesItems,
                'cacheKey' => $this->trancheCacheKey
            ],
            'paymentSchedule' => [
                'items' => $model->paymentScheduleItems,
                'cacheKey' => $this->paymentScheduleCacheKey
            ],
            'nonresidentsCredit' => [
                'items' => $model->nonresidentsCreditItems,
                'cacheKey' => $this->nonresidentCreditCacheKey
            ],
        ];

        foreach($relatedData as $relatedId => $relatedItem) {
            $newData = [];

            // Получение существующих данных
            foreach($relatedItem['items'] as $item) {
                $newData[Uuid::generate()] = $item;
            }

            // Запись кэша
            FCCHelper::setChildObjectCache($relatedItem['cacheKey'], $newData);

            // Запись существующих данных в модель
            $model->$relatedId = $newData;
        }
    }

    /**
     * Просмотр документа
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $document = $this->findDocument($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);
        $model = ContractRegistrationRequestExt::findOne(['documentId' => $document->id]);
        $backUrl = Yii::$app->request->get('backUrl');

        if (!$backUrl) {
            $backUrl = '/edm/documents/foreign-currency-control-index?tabMode=tabCRR';
        }

        return $this->render('view', compact('model', 'document', 'backUrl'));
    }

    /**
     * Печать документа
     * @param $id
     * @return string
     */
    public function actionPrint($id)
    {
        $this->layout = '/blank';

        $document = $this->findDocument($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);
        $model = ContractRegistrationRequestExt::findOne(['documentId' => $document->id]);

        // Шаблон в зависимости от типа документа
        if ($model->passportType == $model::PASSPORT_TYPE_TRADE) {
            // контракт
            return $this->render('printable/printTrade', ['model' => $model]);
        } else if ($model->passportType == $model::PASSPORT_TYPE_LOAN) {
            // кредитный договор
            return $this->render('printable/printLoan', ['model' => $model]);
        }
    }

    /**
     * Удаление документа
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $document = $this->findDocument($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::DELETE, $document);

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Modify error. Contract registration request is already sent'));
            return $this->redirect([$this->crrJournal]);
        }

        $model = ContractRegistrationRequestExt::findOne(['documentId' => $document->id]);

        if ($document->delete() && $model->delete()) {
            if ($document->extModel->storedFileId) {
                Yii::$app->storage->remove($document->extModel->storedFileId);
            }
            Yii::$app->session->setFlash('success', Yii::t('document', 'Document deleted'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('document', 'Failed to delete document'));
        }

        return $this->redirect([$this->crrJournal]);
    }

    /**
     * Отправка документа
     * @param $id
     * @return Response
     */
    public function actionSend($id)
    {
        $document = $this->findDocument($id);
        $this->authorizeDocumentPermission(
            EdmModule::SERVICE_ID,
            [DocumentPermission::CREATE, DocumentPermission::SIGN],
            $document
        );

        // если документ уже отправлен, с ним ничего нельзя делать
        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Modify error. Contract registration request is already sent'));

            return $this->redirect([$this->crrJournal]);
        }

        $signResult = $this->signCryptoPro($document);

        if (!$signResult) {
            return $this->redirect([$this->fccJournalUrl]);
        }

        DocumentTransportHelper::processDocument($document, true);

        Yii::$app->session->setFlash('success', 'Документ отправлен');

        return $this->redirect([$this->crrJournal]);
    }

    /**
     * Массовое удаление выбранных в журнале документов
     * @return Response
     */
    public function actionDeleteCrr()
    {
        $cached = $this->crrCache->get();
        $idList = array_keys($cached['entries']);

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();

        ISO20022DocumentExt::deleteAll(['documentId' => $idList]);
        ContractRegistrationRequestExt::deleteRelatedData($idList);
        ContractRegistrationRequestExt::deleteAll(['documentId' => $idList]);
        Document::deleteAll(['id' => $idList]);

        Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

        $this->crrCache->clear();

        Yii::$app->session->setFlash(
            'info',
            Yii::t('edm', 'Deleted {count} contract registration requests', ['count' => count($idList)])
        );

        return $this->redirect([$this->crrJournal]);
    }

    /**
     * Получение формы добавления связанных данных по типу
     * AJAX
     * @param $type
     * @return string
     * @throws MethodNotAllowedHttpException
     */
    public function actionAddRelatedData($type)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        // Типы связанных данных
        if ($type == 'nonresidents' || $type == 'nonresidentsCredit') {
            // Нерезиденты
            $model = new ContractRegistrationRequestNonresident();
            $model->isCredit = Yii::$app->request->get('credit');
            $formView = $this->nonresidentFormView;
        } else if ($type == 'tranches') {
            // Транши
            $model = new ContractRegistrationRequestTranche();
            $formView = $this->trancheFormView;
        } else if ($type == 'paymentSchedule') {
            $model = new ContractRegistrationRequestPaymentSchedule();
            $formView = $this->paymentScheduleFormView;
        } else {
            // Неизвестный тип
            throw new \Exception;
        }

        return $this->renderAjax($formView, ['model' => $model, 'uuid' => null]);
    }

    /**
     * Удаление связанных данных из списка по типу
     * AJAX
     * @param $type
     */
    public function actionDeleteRelatedData($type, $uuid)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $params = [];

        if ($type == 'nonresidents') {
            $cacheKey = $this->nonresidentCacheKey;
            $listView = $this->nonresidentListView;
            $params = ['credit' => false];
        } else if ($type == 'tranches') {
            $cacheKey = $this->trancheCacheKey;
            $listView = $this->trancheListView;
        } else if ($type == 'paymentSchedule') {
            $cacheKey = $this->paymentScheduleCacheKey;
            $listView = $this->paymentScheduleListView;
        } else if ($type == 'nonresidentsCredit') {
            $cacheKey = $this->nonresidentCreditCacheKey;
            $listView = $this->nonresidentListView;
            $params = ['credit' => true];
        } else {
            // Неизвестный тип
            throw new \Exception('Unknown type');
        }

        return FCCHelper::deleteChildObjectFromDocumentList(
            $this, $uuid,
            $cacheKey,
            $listView,
            $params
        );
    }

    /**
     * Получение шаблона формы управления связанными данными
     * AJAX
     * @param $type
     * @param $uuid
     * @return string
     * @throws \Exception
     */
    public function actionUpdateRelatedData($type, $uuid)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        if ($type == 'nonresidents') {
            $cacheKey = $this->nonresidentCacheKey;
            $formView = $this->nonresidentFormView;
        } else if ($type == 'tranches') {
            $cacheKey = $this->trancheCacheKey;
            $formView = $this->trancheFormView;
        } else if ($type == 'paymentSchedule') {
            $cacheKey = $this->paymentScheduleCacheKey;
            $formView = $this->paymentScheduleFormView;
        } else if ($type == 'nonresidentsCredit') {
            $cacheKey = $this->nonresidentCreditCacheKey;
            $formView = $this->nonresidentFormView;
        } else {
            // Неизвестный тип
            throw new \Exception('Unknown type');
        }

        return FCCHelper::updateChildObjectFromDocumentList(
            $this, $uuid,
            $cacheKey,
            $formView
        );
    }

    /**
     * Обработка формы управления связанными данными
     * AJAX
     * @param $type
     * @return array
     * @throws MethodNotAllowedHttpException
     * @throws \Exception
     */
    public function actionProcessRelatedDataForm($type)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $addParams = [];

        if ($type == 'nonresidents') {
            $model = new ContractRegistrationRequestNonresident();
            $cacheKey = $this->nonresidentCacheKey;
            $formView = $this->nonresidentFormView;
            $listView = $this->nonresidentListView;
            $addParams = [
                'credit' => Yii::$app->request->post('ContractRegistrationRequestNonresident')['isCredit']
            ];
        } else if ($type == 'tranches') {
            $model = new ContractRegistrationRequestTranche();
            $cacheKey = $this->trancheCacheKey;
            $formView = $this->trancheFormView;
            $listView = $this->trancheListView;
        } else if ($type == 'paymentSchedule') {
            $model = new ContractRegistrationRequestPaymentSchedule();
            $cacheKey = $this->paymentScheduleCacheKey;
            $formView = $this->paymentScheduleFormView;
            $listView = $this->paymentScheduleListView;
        } else if ($type == 'nonresidentsCredit') {
            $model = new ContractRegistrationRequestNonresident();
            $cacheKey = $this->nonresidentCreditCacheKey;
            $formView = $this->nonresidentFormView;
            $listView = $this->nonresidentListView;
            $addParams = [
                'credit' => Yii::$app->request->post('ContractRegistrationRequestNonresident')['isCredit']
            ];
        } else {
            // Неизвестный тип
            throw new \Exception();
        }

        $result = FCCHelper::processDocumentForm($this, $model, $formView, $listView, $cacheKey, $addParams);

        return $result;
    }

    /**
     * Загрузка в модель связанных данных из кэша
     * @param ContractRegistrationRequest $model
     */
    private function loadModelRelatedData(ContractRegistrationRequestExt $model)
    {
        $relatedData = [
            'nonresidents' => [
                'cacheKey' => $this->nonresidentCacheKey,
                'required' => true
            ],
            'tranches' => [
                'cacheKey' => $this->trancheCacheKey,
                'required' => false
            ],
            'paymentSchedule' => [
                'cacheKey' => $this->paymentScheduleCacheKey,
                'required' => false
            ],
            'nonresidentsCredit' => [
                'cacheKey' => $this->nonresidentCreditCacheKey,
                'required' => false
            ]
        ];

        foreach($relatedData as $id => $data) {
            $cacheData = FCCHelper::getChildObjectCache($data['cacheKey']);
            $model->loadRelativeData($id, $cacheData, $data['required']);
        }
    }

    /**
     * Очистка кэша связанных данных
     */
    private function clearRelatedDataCache()
    {
        $cacheData = [
            $this->trancheCacheKey,
            $this->paymentScheduleCacheKey,
            $this->nonresidentCreditCacheKey
        ];

        foreach($cacheData as $item) {
            FCCHelper::clearChildObjectCache($item);
        }
    }

    /**
     * Обработка создания/редактирования документа
     * @param ContractRegistrationRequest $extModel
     * @return string|Response
     */
    private function processContractRegistrationRequest(ContractRegistrationRequestExt $extModel)
    {
        if (!Yii::$app->request->isPost) {
            return $this->render('_form', ['model' => $extModel]);
        }

        $validation = $this->validateForm($extModel);

        if (!$validation) {
            Yii::$app->session->setFlash('error', 'Ошибка создания паспорта сделки');

            return $this->render('_form', ['model' => $extModel]);
        }

        $auth018Type = $this->createAuth018Type($extModel);

        if ($auth018Type->errors) {
            Yii::$app->session->setFlash('error', Yii::t('app/iso20022', 'Auth.018 validation against XSD-scheme failed'));
            Yii::info('Auth.018 validation against XSD-scheme failed');
            Yii::info($auth018Type->errors);

            return $this->render('_form', ['model' => $extModel]);
        }

        if (empty($extModel->documentId)) {
            $context = $this->createCyberXml($extModel, $auth018Type);

            if (!$context) {
                Yii::$app->session->setFlash('error', Yii::t('edm', 'Ошибка создания паспорта сделки'));

                return $this->render('_form', ['model' => $extModel]);
            }

            $document = $context['document'];
            // Модификация ext-модели
            $extModel->documentId = $document->id;
        } else {
            $document = $extModel->document;
            FCCHelper::updateCyberXml($document, $extModel, $auth018Type);
        }

        $extModel->save();
        DocumentTransportHelper::extractSignData($document);

        WizardCacheHelper::deleteCRRWizardCache();

        return $this->redirect(['view', 'id' => $extModel->documentId]);
    }

    private function validateForm($model)
    {
        $model->load(Yii::$app->request->post());

        // Модель модифицирована из формы
        $model->modifyFromForm = true;

        // если тип - 'Контракт', очищаем поля, которые характеры
        // для типа 'Кредитный договор' и очищаем кэш связанных данных
        if ($model->passportType == $model::PASSPORT_TYPE_TRADE) {
            $model->clearLoanAttributes();
            $this->clearRelatedDataCache();
        }

        // Загрузка связанных данных
        $this->loadModelRelatedData($model);
        $model->generatePassportNumber();

        return $model->validate();
    }

    private function createAuth018Type($model)
    {
        // Получение xml-содержимого auth.018 из паспорта сделки
        $content = ISO20022Helper::createAuth018FromCRR($model);

        // Создание модели auth.025 из xml-содержимого
        $type = new Auth018Type();
        $type->loadFromString($content);

        // Валидация по XSD-схеме
        $type->validateXSD();

        return $type;
    }

    private function createCyberXml(ContractRegistrationRequestExt $model, Auth018Type $typeModel)
    {
        // Получение организации
        $organization = $model->organization;

        // Получение первого счета организации
        try {
            $account = $organization->accounts[0];
        } catch (\Exception $ex) {
            throw new \Exception(
                "Failed to get organization's ($organization->id) accounts: " . $ex->getMessage()
            );
        }

        $terminal = Terminal::findOne($organization->terminalId);

        $params = [
            'sender' => $terminal->terminalId,
            'receiver' => $account->bank->terminalId,
            'terminal' => $terminal,
            'account' => $account,
//            'attachFile' => null
        ];

        return FCCHelper::createCyberXml($typeModel, $params);
    }

    public function actionBeforeSigning($id)
    {
        $document = $this->findDocument($id);
        $this->authorizeDocumentPermission(
            EdmModule::SERVICE_ID,
            [DocumentPermission::CREATE, DocumentPermission::SIGN],
            $document
        );

        if (!$document->isModifiable()) {
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Modify error. Contract registration request is already sent'));
            return $this->redirect([$this->crrJournal]);
        }

        if ($document->extModel->extStatus == ISO20022DocumentExt::STATUS_FOR_CRYPTOPRO_SIGNING) {
            if (!$this->signCryptoPro($document)) {
                return $this->redirect([$this->crrJournal]);
            }

            DocumentTransportHelper::extractSignData($document);
        }

        return true;
    }

    private function signCryptoPro($document)
    {
        $signResult = FCCHelper::signCryptoPro($document);

        if (!$signResult) {
            Yii::$app->session->setFlash('error', Yii::t('document', 'CryptoPro signing error'));

            Yii::$app->monitoring->log('document:CryptoProSigningError', 'document', $document->id, [
                'terminalId' => $document->terminalId
            ]);

            Yii::error('CryptoPro signing failed');

            return false;
        }

        return true;
    }

    private function findDocument($id)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        if ($document === null) {
            throw new NotFoundHttpException();
        }
        return $document;
    }
}
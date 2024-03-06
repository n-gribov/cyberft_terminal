<?php
namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Pain001Rub\Pain001RubType;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderSearch;
use addons\edm\models\PaymentRegister\PaymentRegisterSearch;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\PaymentRegister\PaymentRegisterWizardForm;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\models\Cert;
use common\modules\transport\helpers\DocumentTransportHelper;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class PaymentRegisterController extends BaseServiceController
{
    private $paymentOrderCache;
    private $paymentRegisterCache;

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->paymentOrderCache = new ControllerCache('paymentOrders');
        $this->paymentRegisterCache = new ControllerCache('paymentRegisters');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'payment-order', 'view', 'payment-order-view', 'download'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['payment-order-view'],
                        'roles' => ['documentControllerVerification']
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['select-payment-orders', 'select-payment-registers'],
                        'roles'   => ['@']
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['delete-payment-orders'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['delete-payment-registers'],
                        'roles' => [DocumentPermission::DELETE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                    [
                        'allow'      => true,
                        'actions'    => ['create', 'send'],
                        'roles'      => [DocumentPermission::CREATE, DocumentPermission::SIGN],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                    [
                        'allow'      => true,
                        'actions'    => ['reject-signing'],
                        'roles'      => [DocumentPermission::SIGN],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                    [
                        'allow'      => true,
                        'actions'    => ['import-payment-register'],
                        'roles'      => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'select-payment-orders' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['import-payment-register'] = 'addons\edm\controllers\actions\ImportPaymentRegisterAction';

        return $actions;
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $registerPagesUrlsRegex = '/\/edm\/payment-register(\/(index|view))?([\?#]|$)/';
        if (!preg_match($registerPagesUrlsRegex, $this->getPreviousPageUrl())) {
            $this->paymentOrderCache->clear();
        }

        $searchModel = new PaymentRegisterSearch();
        $cachedEntries = $this->paymentRegisterCache->get();

        $orgFilter = EdmHelper::getOrgFilter();
        $accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter);

        // Вывести страницу
        return $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'cachedEntries' => $cachedEntries,
            'listType' => 'edmPaymentRegister',
            'accounts' => $accountFilter,
            'payers' => $orgFilter,
        ]);
    }

    public function actionView($id)
    {
        // Получить из БД документ с указанным id
        $document = $this->findModel($id);

        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $extModel = $document->extModel;
        $signatures = is_null($extModel)
            ? $typeModel->getSignatures()
            : $document->getSignatures(Document::SIGNATURES_ALL, Cert::ROLE_SIGNER);

        $dataProvider = new ActiveDataProvider([
            'query' => PaymentRegisterPaymentOrder::find()->where(['registerId' => $id])
        ]);

        $statusEvent = null;

        if (Document::STATUS_SIGNING_REJECTED == $document->status) {
            $statusEvent = Yii::$app->monitoring->getLastEvent('edm:registerSigningRejected', ['entityId' => $id]);
        }

        $previousUrl = Url::previous();
        $currentUrl = Url::current();

        if (empty($previousUrl) || $previousUrl !== $currentUrl) {
            Url::remember();
        }

        if ($previousUrl !== $currentUrl) {
            // Зарегистрировать событие просмотра документа в модуле мониторинга
            Yii::$app->monitoring->log(
                'user:viewDocument',
                'document',
                $id,
                [
                    'userId' => Yii::$app->user->id,
                    'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                ]
            );
        }
        // Вывести страницу
        return $this->render('view', [
            'document' => $document,
            'extModel' => $extModel,
            'signatures' => $signatures,
            'dataProvider' => $dataProvider,
            'statusEvent' => $statusEvent,
        ]);
    }

    public function actionRejectSigning()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $id = Yii::$app->request->post('id');
            // Получить из БД документ с указанным id
            $model = $this->findModel($id);
            $statusComment = (string) Yii::$app->request->post('statusComment');

            if (empty($statusComment)) {
                // Поместить в сессию флаг сообщения об необходимости описать причину отказа
                Yii::$app->session->addFlash('warning', Yii::t('edm', 'Please provide reject reason'));

                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $id]);
            }

            if ($model->type == PaymentRegisterType::TYPE) {
                $extModel = PaymentRegisterDocumentExt::findOne(['documentId' => $model->id]);
                $extModel->statusComment = $statusComment;
                $extModel->save(false, ['statusComment']);
            }

            $model->status = Document::STATUS_SIGNING_REJECTED;

            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Зарегистрировать событие отказа в подписании реестра в модуле мониторинга
                Yii::$app->monitoring->log(
                    'edm:registerSigningRejected',
                    $model->type,
                    $id,
                    [
                        'userId' => Yii::$app->user->id,
                        'reason' => $statusComment,
                        'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                    ]
                );

                // Зарегистрировать событие отмены подписания документа в модуле мониторинга
                Yii::$app->monitoring->extUserLog('RejectSigningDocument', ['documentId' => $id]);
            }

        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionPaymentOrder()
    {
        $orderPagesUrlsRegex = '/\/edm\/payment-register\/(payment-order|payment-order-view)([\?#]|$)/';
        if (!preg_match($orderPagesUrlsRegex, $this->getPreviousPageUrl())) {
            $this->paymentOrderCache->clear();
        }

        $searchModel = new PaymentRegisterPaymentOrderSearch();

        $queryParams = Yii::$app->request->queryParams;
        if (array_key_exists($searchModel->formName(), $queryParams)) {
            $formParams = $queryParams;
        } else {
            $formParams = [$searchModel->formName() => []];
        }

        $params = & $formParams[$searchModel->formName()];

        $payerAccount = array_key_exists('payerAccount', $params) ? $params['payerAccount'] : null;

        if ($payerAccount != Yii::$app->session->get('previousPaymentOrderSearchPayerAccount')) {
            $this->paymentOrderCache->clear();
        }

        Yii::$app->session->set('previousPaymentOrderSearchPayerAccount', $payerAccount);
        $paymentOrders = $this->paymentOrderCache->get();

        /**
         * Current url is stored for further use in backlinks
         */
        Url::remember('', 'paymentOrderSearch');

        $orgFilter = EdmHelper::getOrgFilter();
        $accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter);

        // Вывести страницу
        return $this->render('paymentOrderLog', [
            'model' => $searchModel,
            'dataProvider' => $searchModel->search($formParams, !empty($params['payerAccount'])),
            'filterStatus' => !empty($queryParams),
            'paymentOrders' => $paymentOrders,
            'listType' => 'edmPaymentRegisterPaymentOrder',
            'accounts' => $accountFilter,
            'payers' => $orgFilter,
        ]);
    }

    public function actionDeletePaymentOrders()
    {
        $cached = $this->paymentOrderCache->get();

        $count = PaymentRegisterPaymentOrder::updateAll(
            ['status' => Document::STATUS_DELETED],
            [
                'and',
                ['id' => array_keys($cached['entries'])],
                ['registerId' => null]
            ]
        );

        $this->paymentOrderCache->clear();

        // Поместить в сессию флаг сообщения о количестве удалённых документов
        Yii::$app->session->setFlash('info', Yii::t('edm', 'Deleted {count} payment orders', ['count' => $count]));

        // Перенаправить на страницу индекса ПП
        return $this->redirect(['payment-order']);
    }

    public function actionDeletePaymentRegisters()
    {

        $cached = $this->paymentRegisterCache->get();
        $idList = array_keys($cached['entries']);

        $count = Document::updateAll(
            ['status' => Document::STATUS_DELETED],
            [
                'and',
                [
                    'type' => [
                        PaymentRegisterType::TYPE, VTBPayDocRuType::TYPE,
                        SBBOLPayDocRuType::TYPE, Pain001RubType::TYPE
                    ]
                ],
                ['id' => $idList],
                ['status' => [
                    Document::STATUS_FORSIGNING, Document::STATUS_SIGNED,
                    Document::STATUS_ACCEPTED, Document::STATUS_SIGNING_REJECTED,
                ]]
            ]
        );

        PaymentRegisterPaymentOrder::updateAll(
            ['registerId' => null],
            ['registerId' => $idList]
        );

        $this->paymentRegisterCache->clear();

        // Поместить в сессию флаг сообщения о количестве удалённых документов
        Yii::$app->session->setFlash('info', Yii::t('edm', 'Deleted {count} payment registers', ['count' => $count]));

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    public function actionSelectPaymentOrders()
    {
        $paymentOrders = $this->paymentOrderCache->get();
        $entries = Yii::$app->request->post('entries');

        foreach ($entries as $entry) {
            $id = $entry['id'];
            if ($entry['checked'] === 'true') {
                $paymentOrders['entries'][$id] = true;
            } else {
                unset($paymentOrders['entries'][$id]);
            }
        }
        $this->paymentOrderCache->set($paymentOrders);

        return json_encode(array_keys($paymentOrders['entries']));
    }

    public function actionSelectPaymentRegisters()
    {
        $paymentRegisters = $this->paymentRegisterCache->get();
        $entries = Yii::$app->request->post('entries');
        foreach ($entries as $entry) {
            $id = $entry['id'];
            if ($entry['checked'] === 'true') {
                $paymentRegisters['entries'][$id] = true;
            } else {
                unset($paymentRegisters['entries'][$id]);
            }
        }
        $this->paymentRegisterCache->set($paymentRegisters);

        return json_encode(array_keys($paymentRegisters['entries']));
    }

    public function actionPaymentOrderView($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        $model = Yii::$app->terminalAccess->findModel(PaymentRegisterPaymentOrder::className(), $id);

        $previousUrl = Url::previous();
        $currentUrl = Url::current();

        if (empty($previousUrl) || $previousUrl !== $currentUrl) {
            Url::remember();
        }

        if ($previousUrl !== $currentUrl) {
            // Зарегистрировать событие просмотра документа в модуле мониторинга
            Yii::$app->monitoring->log(
                'user:viewDocument',
                'PaymentRegister',
                $id,
                [
                    'userId' => Yii::$app->user->id,
                    'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                ]
            );
        }

        // Вывести страницу
        return $this->render('_viewPaymentOrder', [
            'model' => $model,
        ]);
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    private function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(Document::className(), $id);
    }

    public function actionCreate($removeOrdersOnFailure = null)
    {
        $paymentOrderIds = Yii::$app->request->post('paymentOrdersIds');
        if ($paymentOrderIds === null) {
            $selectedPaymentOrders = $this->paymentOrderCache->get();
            $paymentOrderIds = array_keys($selectedPaymentOrders['entries']);
        }

        if (empty($paymentOrderIds)) {
            // Поместить в сессию флаг сообщения об отсутствии помеченных документов
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Payment orders not selected'));

            // Перенаправить на страницу индекса ПП
            return $this->redirect(['payment-order']);
        }

        $paymentOrders = PaymentRegisterPaymentOrder::find()
            ->where(['id' => $paymentOrderIds])
            ->andWhere(['registerId' => null])
            ->all();

        if (!count($paymentOrders)) {
            // Поместить в сессию флаг сообщения об отсутствующих документах
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Payment orders not found'));

            // Перенаправить на страницу индекса ПП
            return $this->redirect(['payment-order']);
        }

        $form = new PaymentRegisterWizardForm();
        $form->sender = '';
        $form->recipient = '';
        $form->addPaymentOrders($paymentOrders);

        $this->paymentOrderCache->clear();

        $account = EdmPayerAccount::find()
            ->with(['bank'])
            ->where(['number' => $form->account])
            ->one();

        if ($account) {
            if (isset($account->bank) && !empty($account->bank->terminalId)) {
                $form->recipient = $account->bank->terminalId;
            } else {
                $form->addError('account', Yii::t('edm', 'Account does not have bank terminal id'));
            }
        } else {
            $form->addError('account', Yii::t('edm', 'Account not found'));
        }

        if (!$form->hasErrors() && !$form->validate()) {
            // Поместить в сессию флаг сообщения об ошибке создания документа
            Yii::$app->session->setFlash('error', Yii::t('edm', 'Error creating payment register. {error}', ['error' => $form->getErrorsSummary()]));

            if ($removeOrdersOnFailure) {
                PaymentRegisterPaymentOrder::deleteAll(['id' => $paymentOrderIds]);
            }

            // Перенаправить на страницу индекса ПП
            return $this->redirect(['payment-order']);
        }

        try {
            $document = EdmHelper::createPaymentRegister($form, ['origin' => Document::ORIGIN_WEB]);
            // Отправить документ на обработку в транспортном уровне
            DocumentTransportHelper::processDocument($document, true);
        } catch (Exception $ex) {
            Yii::$app->session->setFlash('error', $ex->getMessage());
            // Поместить в сессию флаг сообщения об ошибке создания документа
            Yii::info("Failed to create payment register, caused by: $ex");

            if ($removeOrdersOnFailure) {
                PaymentRegisterPaymentOrder::deleteAll(['id' => $paymentOrderIds]);
            }

            $document = false;
        }

        $from = Yii::$app->request->post('from');
        $redirectUrl = Yii::$app->request->get('redirectUrl');

        if (!empty($document) && $document->status == Document::STATUS_ACCEPTED) {
            /**
             * Документ со статусом ACCEPTED был автоматически отправлен
             */
            // Поместить в сессию флаг сообщения об успешной отправке документа
            Yii::$app->session->setFlash('success', Yii::t('edm', 'Payment order register is processing'));
        }

        if (!empty($document) && $document->status === Document::STATUS_SERVICE_PROCESSING) {
            DocumentHelper::waitForDocumentsToLeaveStatus([$document->id], Document::STATUS_SERVICE_PROCESSING);
            $document->refresh();
        }

        if ($document) {
            $redirectParams = [
                'view',
                'id' => $document->id,
                'from' => 'payment-order',
                'redirectUrl' => $redirectUrl
            ];
            if ($from == 'wizard' && $document->status == Document::STATUS_FORSIGNING) {
                $redirectParams['triggerSigning'] = 1;
            }
            // Перенаправить на страницу по параметру перенаправления
            return $this->redirect($redirectParams);
        } else {
            // Перенаправить на страницу индекса ПП
            return $this->redirect(['/edm/payment-register/payment-order']);
        }
    }

    public function actionDownload($id, $name = null)
    {
        if (is_null($id)) {
            throw new BadRequestHttpException(Yii::t('app', "Can't send file"));
        }

        // Получить из БД документ с указанным id
        $document = $this->findModel($id);
        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $typeModel = $cyxDoc->getContent()->getTypeModel();

        $fileName = "File$id.xml";

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_RAW;
        $response->sendContentAsFile(
            (string) $typeModel, $fileName, ['mimeType' => 'application/xml']
        );

        return;
    }

}

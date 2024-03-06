<?php
namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationSearch;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestSearch;
use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\EdmUserExt;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyControlSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\edm\models\PaymentOrder\PaymentOrderSearch;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterSearch;
use addons\edm\models\Statement\StatementSearch;
use addons\edm\models\Statement\StatementType;
use addons\edm\models\Statement\StatementTypeConverter;
use addons\edm\models\StatementRequest\StatementRequestSearch;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestSearch;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\edm\models\VTBCurrConversion\VTBCurrConversionType;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\models\Pain001Type;
use common\base\BaseServiceController;
use common\base\traits\AuthorizesDocumentPermission;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\ControllerCache;
use common\helpers\UserHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\form\DocumentCorrectionForm;
use common\models\User;
use common\modules\certManager\models\Cert;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DocumentsController extends BaseServiceController
{
    use AuthorizesDocumentPermission;

    private $currencyRequestCache;
    private $paymentRegisterCache;
    private $fccCache;
    private $fcoCache;
    private $cdiCache;
    private $crrCache;
    private $statementRequestCache;
    private $foreignCurrencyInformationCache;
    private $confirmingDocumentInformationCache;
    private $contractRegistrationRequestCache;
    private $vtbCancellationRequestCache;
    private $bankLetterSigningCache;
    private $bankLetterCache;
    private $currencyRegistersCache;
    private $currencyPaymentsCache;
    private $currencyPaymentsSigningCache;

    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->currencyRequestCache = new ControllerCache('currencyRequests');
        $this->paymentRegisterCache = new ControllerCache('paymentRegisters');
        $this->statementRequestCache = new ControllerCache('statementRequest');
        $this->foreignCurrencyInformationCache = new ControllerCache('foreignCurrencyInformation');
        $this->confirmingDocumentInformationCache = new ControllerCache('confirmingDocumentInformation');
        $this->contractRegistrationRequestCache = new ControllerCache('contractRegistrationRequest');
        $this->fcoCache = new ControllerCache('fcoForSigning');
        $this->fccCache = new ControllerCache('fccForSigning');
        $this->cdiCache = new ControllerCache('cdiForSigning');
        $this->crrCache = new ControllerCache('crrForSigning');
        $this->vtbCancellationRequestCache = new ControllerCache('vtbCancellationRequestCache');
        $this->bankLetterSigningCache = new ControllerCache('bankLettersSigning');
        $this->bankLetterCache = new ControllerCache('bankLetters');
        $this->currencyRegistersCache = new ControllerCache('currencyRegisters');
        $this->currencyPaymentsCache = new ControllerCache('currencyPayments');
        $this->currencyPaymentsSigningCache = new ControllerCache('currencyPaymentsSigning');
    }

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
				    [
                        'allow' => true,
                        'actions' => ['signing-index'],
                        'roles' => [DocumentPermission::SIGN],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => '*',
                        ],
                    ],
					[
						'allow' => true,
						'roles' => [DocumentPermission::VIEW],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => '*',
                        ],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
                    'select-currency-requests' => ['post'],
                    'hide-zero-turnover-statements' => ['post'],
				],
			],
		];
	}

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => EdmModule::SERVICE_ID,
        ];

        return $actions;
    }

    public function actionPaymentOrder()
    {
        $this->authorizePermission(
            DocumentPermission::VIEW,
            ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT]
        );

        $searchModel = new PaymentOrderSearch(['type' => 'PaymentOrder']);

        return $this->render('paymentOrder', [
            'model' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'urlParams' => $this->getSearchUrl('PaymentOrderSearch'),
            'colored' => true,
            'filterStatus' => !empty(Yii::$app->request->queryParams)
        ]);
    }

    public function actionStatement()
    {
        $this->authorizePermission(
            DocumentPermission::VIEW,
            [
                'serviceId' => EdmModule::SERVICE_ID,
                'documentTypeGroup' => EdmDocumentTypeGroup::STATEMENT,
            ]
        );

        $searchModel = new StatementSearch();

        // Получение информации по настройке отображения документов с нулевыми оборотами
        $extParams = null;
        $hideNullTurnovers = false;

        $edmExt = EdmUserExt::findOne(['userId' => Yii::$app->user->id]);
        if ($edmExt && $edmExt->hideZeroTurnoverStatements) {
            $extParams = ['hideNullTurnovers'];
            $hideNullTurnovers = true;
        }

        return $this->render('statement', [
            'model' => $searchModel,
            //'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'dataProvider' => $searchModel->searchIncomingDocuments(Yii::$app->request->queryParams, $extParams),
            'urlParams' => $this->getSearchUrl('StatementSearch'),
            'colored' => true,
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'edmStatements',
            'hideNullTurnovers' => $hideNullTurnovers
        ]);
    }

    public function actionStatementPaymentOrderView($id, $num, $mode = 'readable')
    {
        $model = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $model);

		$referencingDataProvider = new ActiveDataProvider([
			'query' => $model->findReferencingDocuments(),
			'pagination' => [
				'pageSize' => 20,
			],
		]);

		return $this->render('view', [
			'model' => $model,
            'num' => $num,
            'mode' => $mode,
			'urlParams' => $this->getSearchUrl('EdmSearch'),
			'referencingDataProvider' => $referencingDataProvider,
			'dataView' => '@addons/edm/views/documents/_viewStatementPaymentOrder'
		]);
    }

    /**
     * Метод для показа родных моделей по id
     * @param int    $id
     * @param string $mode
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id, $mode = 'readable')
    {
        $model = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $model);

        // Если выписка, отображаем только по доступным счетам

        if (
            in_array(
                $model->type,
                [StatementType::TYPE, Camt052Type::TYPE, Camt053Type::TYPE, Camt054Type::TYPE]
            )
        ) {
            $this->seeStatementsPermission($model);
        }

        // (CYB-4587) Временный костыль: при открытии camt отображаем вьюху для выписок, а не для ISO20022
        if (
            in_array(
                $model->type,
                [StatementType::TYPE, Camt052Type::TYPE, Camt053Type::TYPE, Camt054Type::TYPE]
            )
        ) {
            $dataView = Yii::$app->registry->getTypeRegisteredAttribute('Statement', 'transport', 'dataView');
            $actionView = Yii::$app->registry->getTypeRegisteredAttribute('Statement', 'transport', 'actionView');
        } else {
            $dataView = Yii::$app->registry->getTypeRegisteredAttribute($model->type, $model->typeGroup, 'dataView');
            $actionView = Yii::$app->registry->getTypeRegisteredAttribute($model->type, $model->typeGroup, 'actionView');
        }

        $referencingDataProvider = new ActiveDataProvider([
            'query' => $model->findReferencingDocuments(),
            'pagination' => ['pageSize' => 20],
        ]);

        $previousUrl = Url::previous();
        $currentUrl = Url::current();

        if (empty($previousUrl) || $previousUrl !== $currentUrl) {
            Url::remember();
        }

        if ($previousUrl !== $currentUrl) {

            if (!$model->viewed) {
                $model->viewed = 1;
                $model->save(false, ['viewed']);
            }

            // Регистрация события просмотра документа
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

		return $this->render('view', [
			'model' => $model,
			'mode' => $mode,
			'urlParams' => $this->getSearchUrl('StatementSearch'),
			'referencingDataProvider' => $referencingDataProvider,
			'dataView' => $dataView,
            'actionView' => $actionView
		]);
    }

	public function actionPrint($id, $flagPaymentRegisterPaymentOrder = null)
	{
		if (!is_null($flagPaymentRegisterPaymentOrder)) {
			$paymentRegisterPaymentOrder = Yii::$app->terminalAccess->findModel(
                    PaymentRegisterPaymentOrder::className(), $id
            );

			$this->authorizePermission(
			    DocumentPermission::VIEW,
                ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT]
            );

			$model = (new PaymentOrderType)->loadFromString($paymentRegisterPaymentOrder->body);

            // Даты обработки и списания не хранятся в теле платежного поручения
            // Их нужно передавать отдельно
            $model->dateProcessing = $paymentRegisterPaymentOrder->dateProcessing;
            $model->dateDue = $paymentRegisterPaymentOrder->dateDue;
            $model->okud = '0401060'; // Значение по умолчанию для платежных поручений

            // Дополнительная информация по платежному поручению - подписи и данные бизнес-статуса
            $data = [
                'paymentOrderModel' => $paymentRegisterPaymentOrder,
                'businessStatus' => [
                    'status' => $paymentRegisterPaymentOrder->businessStatus,
                    'statusTranslation' => $paymentRegisterPaymentOrder->businessStatusTranslation,
                    'description' => $paymentRegisterPaymentOrder->businessStatusDescription,
                    'reason' => $paymentRegisterPaymentOrder->businessStatusComment,
                ]
            ];


		} else {
			$model = $this->findModel($id);
            $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $model);
            $data = [];
		}

		$views = [
			'Statement' => 'printable/statement',
			'VTBStatementRu' => 'printable/statement',
			'SBBOLStatement' => 'printable/statement',
			'RaiffeisenStatement' => 'printable/statement',
			'camt.052' => 'printable/statement',
			'camt.053' => 'printable/statement',
			'camt.054' => 'printable/statement',
			'PaymentOrder' => 'printable/paymentOrder',
			'ProvCSV' => 'printable/provcsv'
		];

		if (isset($views[$model->type])) {
            // Для печати выписки другой базовый шаблон
            if ($model->type === StatementType::TYPE) {
                $this->layout = '/blank';
            } else {
                $this->layout = '/print';
            }

			return $this->render($views[$model->type], ['model' => $model, 'data' => $data]);
		}

		return $this->redirect(['view', 'id' => $id]);
	}

	public function actionPrintAll($id)
	{
        $model = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $model);

		$this->layout = '/print';

        return $this->render('printable/statementAll', ['model' => $model]);
	}

    public function actionPrintStatementPaymentOrder($id, $num)
	{
        $model = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $model);
        $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
        $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);

        $paymentOrder = $statementTypeModel->getPaymentOrder($num);

        if (!empty($paymentOrder)) {

            // Регистрация события печати документа
            Yii::$app->monitoring->log(
                'user:printDocument',
                'document',
                $id,
                [
                    'userId' => Yii::$app->user->id,
                    'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
                ]
            );

            $this->layout = '/print';
        	return $this->render('printable/paymentOrder', ['paymentOrder' => $paymentOrder, 'savePdf' => false]);
        }

		return $this->redirect(['view', 'id' => $id]);
	}

	public function actionSigningIndex($tabMode = null)
	{
        $substituteConfig = ['substituteServices' => ['edm' => 'ISO20022']];

        $tabsData = [
            'tabRegisters' => [
                'search' => new PaymentRegisterSearch(),
                'listType' => 'edmSigningPaymentRegister',
                'cache' => $this->paymentRegisterCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::RUBLE_PAYMENT],
            ],
            'tabCurrencyPayments' => [
                'search' => new CurrencyPaymentDocumentSearch($substituteConfig),
                'listType' => 'edmSigningCurrencyPayments',
                'cache' => $this->currencyPaymentsSigningCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::CURRENCY_PAYMENT],
            ],
            'tabFCOSigning' => [
                'search' => new ForeignCurrencyOperationSearch($substituteConfig),
                'listType' => 'edmSigningFCO',
                'cache' => $this->fcoCache,
                'documentTypeGroups' => [
                    EdmDocumentTypeGroup::CURRENCY_CONVERSION,
                    EdmDocumentTypeGroup::CURRENCY_PURCHASE,
                    EdmDocumentTypeGroup::CURRENCY_SELL,
                    EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
                ],
            ],
            'tabFCCSigning' => [
                'search' => new ForeignCurrencyControlSearch($substituteConfig),
                'listType' => 'edmSigningFCC',
                'cache' => $this->fccCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY],
            ],
            'tabCDISigning' => [
                'search' => new ConfirmingDocumentInformationSearch($substituteConfig),
                'listType' => 'edmSigningCDI',
                'cache' => $this->cdiCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY],
            ],
            'tabCRRSigning' => [
                'search' => new ContractRegistrationRequestSearch($substituteConfig),
                'listType' => 'edmSigningCRR',
                'cache' => $this->crrCache,
                'documentTypeGroups' => [
                    EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                    EdmDocumentTypeGroup::CONTRACT_REGISTRATION_REQUEST,
                    EdmDocumentTypeGroup::CONTRACT_CHANGE_REQUEST,
                    EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                ],
            ],
            'tabBankLetters' => [
                'search' => new BankLetterSearch($substituteConfig),
                'listType' => 'edmBankLetter',
                'cache' => $this->bankLetterSigningCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::BANK_LETTER],
            ],
            'tabStatementRequests' => [
                'search' => new StatementRequestSearch(),
                'listType' => 'edmSigningStatementRequest',
                'cache' => $this->statementRequestCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::STATEMENT],
            ],
            'tabVTBCancellationRequests' => [
                'search' => new VTBCancellationRequestSearch(),
                'listType' => 'edmVTBCancellationRequest',
                'cache' => $this->vtbCancellationRequestCache,
                'documentTypeGroups' => [EdmDocumentTypeGroup::CANCELLATION_REQUEST],
            ]
        ];

        $availableTabs = array_reduce(
            array_keys($tabsData),
            function ($carry, $tabId) use ($tabsData) {
                $userHasAccess = Yii::$app->user->can(
                    DocumentPermission::SIGN,
                    [
                        'serviceId' => EdmModule::SERVICE_ID,
                        'documentTypeGroup' => $tabsData[$tabId]['documentTypeGroups'],
                    ]
                );
                if ($userHasAccess) {
                    $carry[] = $tabId;
                }
                return $carry;
            },
            []
        );

        if (count($availableTabs) === 0) {
            throw new ForbiddenHttpException();
        }

        if (!isset($tabsData[$tabMode]) || !in_array($tabMode, $availableTabs)) {
            $tabMode = $availableTabs[0];
        }

        $params = Yii::$app->request->queryParams;

        $forSigningCount = [];
        foreach ($tabsData as $tab => $model) {
            $forSigningCount[$tab] = $model['search']->countForSigning($params);
        }

        $registerSigningPagesUrlsRegex = '/\/edm\/(documents\/signing-index|payment-register\/view)([\?#]|$)/';
        if (!preg_match($registerSigningPagesUrlsRegex, $this->getPreviousPageUrl())) {
            $this->paymentRegisterCache->clear();
            $this->currencyRequestCache->clear();
            $this->fccCache->clear();
            $this->fcoCache->clear();
            $this->cdiCache->clear();
            $this->crrCache->clear();
            $this->vtbCancellationRequestCache->clear();
            $this->bankLetterSigningCache->clear();
        }

        $orgFilter = EdmHelper::getOrgFilter();

        $searchModel = $tabsData[$tabMode]['search'];

        if (in_array($tabMode, ['tabRegisters', 'tabFCOSigning'])) {
            $accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter);
        } else {
            $accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter, true);
        }

        $cache = $tabsData[$tabMode]['cache'];
        $cachedEntries = $cache->get();

        return $this->render(
            'forSigning',
            [
                'tabMode'       => $tabMode,
                'model'         => $searchModel,
                'dataProvider'  => $searchModel->searchForSigning($params),
                'forSigningCount' => $forSigningCount,
                'urlParams'     => $this->getSearchUrl($searchModel->formName()),
                'colored'       => true,
                'cachedEntries' => $cachedEntries,
                'filterStatus'  => !empty(Yii::$app->request->queryParams),
                'listType'      => $tabsData[$tabMode]['listType'],
                'orgFilter'      => $orgFilter,
                'accountFilter' => $accountFilter,
                'controllerCacheKey' => $cache->getKey(),
                'availableTabs' => $availableTabs,
                'documentTypeGroups' => $tabsData[$tabMode]['documentTypeGroups']
            ]
        );
	}

    /**
     * Журнал "Валютный контроль"
     * Входят документы "Справка о валютных операциях" и "Справка о подтверждающих документах"
     * @param null $tabMode
     */
    public function actionForeignCurrencyControlIndex($tabMode = null)
    {
        $userHasAccess = function (array $documentTypeGroups) {
            return Yii::$app->user->can(
                DocumentPermission::VIEW,
                [
                    'serviceId' => EdmModule::SERVICE_ID,
                    'documentTypeGroup' => $documentTypeGroups,
                ]
            );
        };

        // Список данных для журналов
        $tabs = [
            'tabFCI' => [
                'searchModel' => new ForeignCurrencyControlSearch(),
                'listType' => 'foreignCurrencyControl',
                'userHasAccess' => $userHasAccess([EdmDocumentTypeGroup::CURRENCY_DEAL_INQUIRY]),
            ],
            'tabCDI' => [
                'searchModel' => new ConfirmingDocumentInformationSearch(),
                'listType' => 'confirmingDocumentInformation',
                'userHasAccess' => $userHasAccess([EdmDocumentTypeGroup::CONFIRMING_DOCUMENTS_INQUIRY]),
            ],
            'tabCRR' => [
                'searchModel' => new ContractRegistrationRequestSearch(),
                'listType' => 'contractRegistrationRequest',
                'userHasAccess' => $userHasAccess([
                    EdmDocumentTypeGroup::LOAN_AGREEMENT_REGISTRATION_REQUEST,
                    EdmDocumentTypeGroup::CONTRACT_REGISTRATION_REQUEST,
                    EdmDocumentTypeGroup::CONTRACT_CHANGE_REQUEST,
                    EdmDocumentTypeGroup::CONTRACT_UNREGISTRATION_REQUEST,
                ]),
            ]
        ];

        $availableTabs = array_reduce(
            array_keys($tabs),
            function ($carry, $tabId) use ($tabs) {
                if ($tabs[$tabId]['userHasAccess']) {
                    $carry[] = $tabId;
                }
                return $carry;
            },
            []
        );

        if (count($availableTabs) === 0) {
            throw new ForbiddenHttpException();
        }

        // Если вкладка не указана, по умолчанию выбирается справка о валютных операциях
        if (!isset($tabs[$tabMode]) || !in_array($tabMode, $availableTabs)) {
            $tabMode = $availableTabs[0];
        }

        // Получение кэша выбранных записей в журналах
        $cache = $this->getCacheByTabMode()[$tabMode];
        $cachedEntries = $cache->get();

        // Параметры запроса в журнале документов
        $params = Yii::$app->request->queryParams;

        // Определение текущей search-модели по текущей выбранной вкладке
        $searchModel = $tabs[$tabMode]['searchModel'];

        // Списки значений для фильтров в журналах
        $orgFilter = EdmHelper::getOrgFilter();
        $accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter, true);

        $searchModel->substituteServices = ['edm' => 'ISO20022'];

        return $this->render(
            'fcc',
            [
                'tabMode'       => $tabMode,
                'searchModel'   => $searchModel,
                'dataProvider'  => $searchModel->search($params),
                'listType'      => $tabs[$tabMode]['listType'],
                'orgFilter'     => $orgFilter,
                'accountFilter' => $accountFilter,
                'bankFilter'    => EdmHelper::getBankFilter('bik'),
                'bankNameFilter' => EdmHelper::getBankFilter('name'),
                'cachedEntries' => $cachedEntries,
                'availableTabs' => $availableTabs,
            ]
        );
    }

    public function actionSelectEntries($tabMode)
    {
        $cache = $this->getCacheByTabMode()[$tabMode];
        $cachedEntries = $cache->get();

        $entries = Yii::$app->request->post('entries', []);
        foreach ($entries as $entry) {
            $id = $entry['id'];
            if ($entry['checked'] === 'true') {
                $cachedEntries['entries'][$id] = true;
            } else {
                unset($cachedEntries['entries'][$id]);
            }
        }
        $cache->set($cachedEntries);

        return json_encode(array_keys($cachedEntries['entries']));
    }

    public function actionGetSelectedEntriesIds($tabMode)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $cache = $this->getCacheByTabMode()[$tabMode];
        $cachedEntries = $cache->get();
        return array_keys($cachedEntries['entries']);
    }

    /**
     * Correction log
     * @deprecated
     * @return mixed
     */
//	public function actionCorrectionIndex()
//	{
//		$filterModel	 = new EdmSearch();
//		$dataProvider	 = $filterModel->searchForCorrection(Yii::$app->request->queryParams);
//
//		Url::remember(Url::to());
//
//		return $this->render(
//            'correction',
//            [
//				'filterModel' => $filterModel,
//				'dataProvider' => $dataProvider,
//                'filterStatus' => !empty(Yii::$app->request->queryParams)
//            ]
//		);
//	}

    public function actionForeignCurrencyOperationPrint($id, $type)
    {
        // Печатная форма в зависимости от типа документа

        $document = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);
        $extModel = $document->extModel;

        $this->layout = '/print';

        // Получение параметров для события
        $documentType = $type;

        $eventOptions = [
            'userId' => Yii::$app->user->id,
            'initiatorType' => UserHelper::getEventInitiatorType(Yii::$app->user)
        ];

        if ($documentType) {
            $eventOptions['documentType'] = $documentType;
        }

        Yii::$app->monitoring->log(
            'user:printDocument',
            'document',
            $id,
            $eventOptions
        );

        if ($type == ForeignCurrencyOperationFactory::OPERATION_PURCHASE ||
            $type == ForeignCurrencyOperationFactory::OPERATION_SELL
        ) {
            $pain001TypeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
            $signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);
            return $this->render(
                'printable/foreignCurrencyOperation',
                [
                    'document' => $document,
                    'typeModel' => ForeignCurrencyOperationFactory::constructFCOFromPain001($pain001TypeModel),
                    'signatures' => $signatures
                ]
            );
        } else if ($type == 'MT103') {
            $this->layout = '/empty';

            $swiftTypeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);
            $fcp = ForeignCurrencyOperationFactory::constructFCPFromSwift($swiftTypeModel, true);
            $signatures = $document->getSignatures(Document::SIGNATURES_ALL, Cert::ROLE_SIGNER);

            return $this->render('printable/foreignCurrencyPayment', [
                'document' => $document, 'typeModel' => $fcp, 'signatures' => $signatures
            ]);
        } else if ($type == Pain001Type::TYPE || $type == Pain001RlsType::TYPE) {
            $this->layout = '/empty';

            $pain001TypeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

            if ($extModel->documentType == ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT) {
                $typeModel = ForeignCurrencyOperationFactory::constructFCSTFromPain001($pain001TypeModel, $document->extModel->debitAccount);
                $printView = 'printable/foreignCurrencySellTransit';
            } else if ($extModel->documentType == ForeignCurrencyOperationFactory::OPERATION_CONVERSION) {
                $typeModel = ForeignCurrencyOperationFactory::constructFCVNFromPain001($pain001TypeModel, $document->extModel);
                $printView = 'printable/foreignCurrencyConversion';
            }

            $signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

            return $this->render($printView, [
                'document' => $document, 'typeModel' => $typeModel, 'signatures' => $signatures
            ]);
        }
    }

    public function actionForeignCurrencyOperationView($id, $ajax = false)
    {
        $document = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);

        $cyxDoc = CyberXmlDocument::read($document->actualStoredFileId);
        $pain001TypeModel = $cyxDoc->getContent()->getTypeModel();

        $typeModel = ForeignCurrencyOperationFactory::constructFCOFromPain001($pain001TypeModel);

        $extModel = $document->extModel;

        $statusEvent = null;

        if (Document::STATUS_SIGNING_REJECTED == $document->status) {
            $statusEvent = Yii::$app->monitoring->getLastEvent('edm:registerSigningRejected', ['entityId' => $id]);
        }

        $view = 'foreignCurrencyOperationView';
        $params = [
            'document'  => $document,
            'typeModel' => $typeModel,
            'businessStatus' => $extModel->getBusinessStatusTranslation(),
            'businessStatusDescription' => $extModel->businessStatusDescription,
        ];
        return $ajax
            ? $this->renderAjax($view, $params)
            : $this->render($view, $params);
    }

    public function actionForeignCurrencySellTransitView($id, $ajax = false)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);
        $pain001TypeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

        $fcst = ForeignCurrencyOperationFactory::constructFCSTFromPain001($pain001TypeModel, $document->extModel->debitAccount);

        $extModel = $document->extModel;
        $params = [
            'model' => $fcst,
            'document' => $document,
            'businessStatus' => $extModel->getBusinessStatusTranslation(),
            'businessStatusDescription' => $extModel->businessStatusDescription,
            'signatureList' => $pain001TypeModel->getSignaturesList()
        ];
        if ($ajax) {
            return $this->renderAjax('foreignCurrencySellTransitView', $params);
        } else {
            return $this->render('foreignCurrencySellTransitView', $params);
        }
    }

    public function actionForeignCurrencyConversionView($id, $ajax = false)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);

        $pain001TypeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

        $fcvn = ForeignCurrencyOperationFactory::constructFCVNFromPain001($pain001TypeModel, $document->extModel);

        $extModel = $document->extModel;

        $view = 'foreignCurrencyConversionView';
        $data = [
            'model' => $fcvn,
            'document' => $document,
            'businessStatus' => $extModel->businessStatus,
            'businessStatusDescription' => $extModel->businessStatusDescription
        ];

        $method = $ajax ? 'renderAjax' : 'render';

        return $this->$method($view, $data);
    }

    /**
     * Correction action
     *
     * @return mixed
     */
    public function actionCorrection()
    {
        if (Yii::$app->request->isPost) {
            $model             = new DocumentCorrectionForm();
            $model->documentId = Yii::$app->request->post('documentId');

            $document = Document::findOne($model->documentId);
            $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::CREATE, $document);

            $model->load(Yii::$app->request->post());
            if ($model->validate() && $model->toCorrection(Yii::$app->user->id)) {
                Yii::$app->session->setFlash('success',
                    Yii::t('doc', 'The document was sent for correction'));
                return $this->redirect(['/edm/documents/index']);
            }
            Yii::$app->session->setFlash('error',
                Yii::t('doc', 'The document was not sent for correction'));
            return $this->redirect(['/edm/documents/view/', ['id' => $model->documentId]]);
        }

        $referer = Url::previous('edit');
        if (empty($referer)) {
            return $this->redirect(['/emd/documents/index']);
        }

        return $this->redirect([$referer]);
    }

    public function actionForeignCurrencyOperationJournal($view = null, $tabMode = null)
    {
        $fcoRegex = '/\/edm\/(documents\/foreign-currency-operation-journal)([\?#]|$)/';
        if (!preg_match($fcoRegex, $this->getPreviousPageUrl())) {
            $this->paymentRegisterCache->clear();
            $this->currencyRequestCache->clear();
        }

        $tabs = [
            'tabPurchase' => [
                'searchType' => [
                    Pain001FxType::TYPE,
                    VTBCurrBuyType::TYPE
                ],
                'extDocumentType' => ForeignCurrencyOperationFactory::OPERATION_PURCHASE,
                'wizardType' => ForeignCurrencyOperationFactory::OPERATION_PURCHASE,
                'listType' => 'edmForeignCurrencyPurchaseRequest',
                'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_PURCHASE,
            ],
            'tabSell' => [
                'searchType' => [
                    Pain001FxType::TYPE,
                    VTBCurrSellType::TYPE
                ],
                'extDocumentType' => ForeignCurrencyOperationFactory::OPERATION_SELL,
                'wizardType' => ForeignCurrencyOperationFactory::OPERATION_SELL,
                'listType' => 'edmForeignCurrencySellRequest',
                'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_SELL,
            ],
            'tabPain001' => [
                'searchType' => [
                    'pain.001',
                    'pain.001.RLS',
                    VTBTransitAccPayDocType::TYPE
                ],
                'extDocumentType' => ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT,
                'wizardType' => ForeignCurrencyOperationFactory::OPERATION_SELL_TRANSIT_ACCOUNT,
                'listType' => 'edmForeignCurrencySellTransitAccount',
                'documentTypeGroup' => EdmDocumentTypeGroup::TRANSIT_ACCOUNT_PAYMENT,
            ],
            'tabCurrencyConversion' => [
                'searchType' => [
                    'pain.001',
                    VTBCurrConversionType::TYPE,
                ],
                'extDocumentType' => ForeignCurrencyOperationFactory::OPERATION_CONVERSION,
                'wizardType' => ForeignCurrencyOperationFactory::OPERATION_CONVERSION,
                'listType' => 'edmForeignCurrencyConversion',
                'documentTypeGroup' => EdmDocumentTypeGroup::CURRENCY_CONVERSION,
            ],
        ];

        $userHasAccess = function (string $documentTypeGroup) {
            return Yii::$app->user->can(
                DocumentPermission::VIEW,
                [
                    'serviceId' => EdmModule::SERVICE_ID,
                    'documentTypeGroup' => $documentTypeGroup,
                ]
            );
        };

        $availableTabs = array_reduce(
            array_keys($tabs),
            function ($carry, $tabId) use ($tabs, $userHasAccess) {
                if ($userHasAccess($tabs[$tabId]['documentTypeGroup'])) {
                    $carry[] = $tabId;
                }
                return $carry;
            },
            []
        );

        if (count($availableTabs) === 0) {
            throw new ForbiddenHttpException();
        }

        $defaultTab = $availableTabs[0];

        if (empty($tabMode) || !in_array($tabMode, $availableTabs)) {
            $tabMode = $defaultTab;
        }

        $type = $tabs[$tabMode]['searchType'];

        $filterData = ['type' => $type];

        if (isset($tabs[$tabMode]['extDocumentType'])) {
            $filterData['extDocumentType'] = $tabs[$tabMode]['extDocumentType'];
        }
        $filterModel = new ForeignCurrencyOperationSearch($filterData);
        $dataProvider = $filterModel->search(Yii::$app->request->queryParams);
        // если прислали ид для просмотра, но тип документа нельзя однозначно определить в текущей вкладке
        // тип в свою очередь нужен для правильной реализации показа в модальном окошке (JS: fcoModalView)
        if ($view && is_array($type)) {
            $model = $this->findModel($view);
            $type = $model->type;
        }

        Url::remember(Url::to());

        $orgFilter = EdmHelper::getOrgFilter();
        $accountFilter = EdmHelper::getAccountFilter(Yii::$app->user->identity->id, $orgFilter);
        $banksFilter = EdmHelper::getBankFilter();

        return $this->render(
            'fcoJournalTabs',
            [
                'filterModel' => $filterModel,
                'dataProvider' => $dataProvider,
                'filterStatus' => !empty(Yii::$app->request->queryParams),
                'listType' => $tabs[$tabMode]['listType'],
                'cachedEntries' => $this->currencyRequestCache->get(),
                'accountFilter' => $accountFilter,
                'orgFilter' => $orgFilter,
                'banksFilter' => $banksFilter,
                'view' => $view ?: 0,
                'type' => is_array($type) ? '-' : $type,
                'wizardType' => $tabs[$tabMode]['wizardType'] ?? null,
                'documentTypeGroup' => $tabs[$tabMode]['documentTypeGroup'],
                'defaultTab' => $defaultTab,
                'availableTabs' => $availableTabs,
            ]
        );
    }

    /**
     * Удаляет записи по запросу либо из журнала (отмеченные чекбоксами) либо из просмотра (одна запись)
     * @param integer|array $id Для query может быть или числом, или массивом
     * @return response
     */
    private function _deleteCurrencyRequests($id)
    {
        $documents = Document::find()
            ->where(['id' => $id])
            ->all();
        foreach ($documents as $document) {
            $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::DELETE, $document);
        }

        $count = Document::updateAll(
            ['status' => Document::STATUS_DELETED],
            [
                'and',
                [
                    'type' => [
                        Pain001FxType::TYPE,
                        'MT103',
                        Pain001Type::TYPE,
                        VTBPayDocCurType::TYPE,
                        VTBCurrSellType::TYPE,
                        VTBCurrBuyType::TYPE,
                        VTBTransitAccPayDocType::TYPE,
                        VTBCurrConversionType::TYPE,
                        VTBRegisterCurType::TYPE
                    ]
                ],
                ['typeGroup' => EdmModule::SERVICE_ID],
                ['id' => $id],
                ['status' => Document::STATUS_FORSIGNING]
            ]
        );

        Yii::$app->session->setFlash(
            'info',
            Yii::t(
                'edm', 'Deleted {count} currency operation requests',
                ['count' => $count]
            )
        );

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionForeignCurrencyOperationDelete($id)
    {
        return $this->_deleteCurrencyRequests($id);
    }

    public function actionForeignCurrencyOperationsDelete()
    {
        $cached = $this->currencyRequestCache->get();
        $this->currencyRequestCache->clear();

        return $this->_deleteCurrencyRequests(array_keys($cached['entries']));
    }

    /**
     * @param int $id
     * @return Document
     * @throws NotFoundHttpException
     */
	private function findModel($id)
	{
        $model = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        if (!$model) {
            throw new NotFoundHttpException('Document ' . $model->id . ' not found');
        }

        /**
         * Временный фикс: документы edm могут передаваться через транспорт
         * и тем самым иметь typeGroup = 'transport'
         */
        if ($model->typeGroup !== $this->module->id
            && $model->typeGroup !== 'transport'
            && $model->typeGroup !== 'ISO20022'
        ) {
            throw new NotFoundHttpException('Document ' . $model->id . ' wrong type: ' . $model->typeGroup);
        }

        return $model;
	}

    public function actionHideZeroTurnoverStatements()
    {
        $status = (bool)Yii::$app->request->post('status');
        $redirect = Yii::$app->request->post('redirect');

        /** @var EdmUserExt $edmUserExt */
        $edmUserExt = $this->module->getUserExtModel(Yii::$app->user->id);
        if ($edmUserExt->isNewRecord) {
            $edmUserExt->userId = Yii::$app->user->id;
            $edmUserExt->canAccess = 0;
        }
        $edmUserExt->hideZeroTurnoverStatements = $status;
        $isSaved = $edmUserExt->save();
        if (!$isSaved) {
            Yii::info('Failed to save edm user ext model, errors: ' . var_export($edmUserExt->getErrors(), true));
        }

        if ($redirect) {
            return $this->redirect($redirect);
        }
    }

    /**
     * Получение представления содержимого платежки из выписки
     */
    public function actionGetStatementContentView()
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new HttpException('404', 'Method not found');
        }

        $id = Yii::$app->request->get('id');
        $uniqId = Yii::$app->request->get('uniqId');

        // Не указан id документа
        if (!$id) {
            return null;
        }

        // Не найден документ по id
        $document = $this->findModel($id);
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::VIEW, $document);

        if (!$document) {
            return null;
        }

        $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
        $statementTypeModel = StatementTypeConverter::convertFrom($typeModel);

        $transactionId = $statementTypeModel->getTransactionIdByUniqId($uniqId);

        if (is_null($transactionId)) {
            return null;
        }

        $paymentOrder = $statementTypeModel->getPaymentOrder($transactionId);

        $html = $this->renderAjax('readable/paymentOrder', [
                'documentId' => $document->id,
                'paymentOrder' => $paymentOrder,
                'num' => $transactionId
            ]
        );

        return $html;
    }

    public function actionCreateFccFromExistingDocument($type, $id)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);
        if ($document === null) {
            throw new NotFoundHttpException("Document $id is not found or is not available to current user");
        };
        $this->authorizeDocumentPermission(EdmModule::SERVICE_ID, DocumentPermission::CREATE, $document);

        if ($type == 'FCC') {
            return $this->redirect('/edm/foreign-currency-control/create?id=' . $id);
        } else if ($type == 'CDI') {
            return $this->redirect('/edm/confirming-document-information/create?id=' . $id);
        } else if ($type == 'CRR') {
            return $this->redirect('/edm/contract-registration-request/create?id=' . $id);
        } else {
            Yii::$app->session->setFlash('error', 'Неизвестный формат документа');

            return $this->redirect('/edm/documents/foreign-currency-control-index');
        }
    }

    /**
     * Проверка возможности отображения выписки с учетом доступных пользователю счетов
     * @param $model
     * @throws ForbiddenHttpException
     */
    private function seeStatementsPermission($model)
    {
        $typeModel = CyberXmlDocument::getTypeModel($model->actualStoredFileId);
        $accountNumber = $typeModel->statementAccountNumber;
        $currentUser = Yii::$app->user->identity;

        // C учетом доступных текущему пользователю счетов
        if ($currentUser->role == User::ROLE_USER) {
            $allowAccounts = EdmPayerAccountUser::getUserAllowAccountsNumbers($currentUser->id);

            if (in_array($accountNumber, $allowAccounts) === false) {
                throw new ForbiddenHttpException;
            }
        }
    }

    /**
     * Получение данных кэша по типу вкладки для журналов документов,
     * ожидающих подписания и валютного контроля
     */
    private function getCacheByTabMode()
    {
        return [
            'tabFCO' => $this->currencyRequestCache,
            'tabRegisters' => $this->paymentRegisterCache,
            'tabStatementRequests' => $this->statementRequestCache,
            'tabFCI' => $this->foreignCurrencyInformationCache,
            'tabCDI' => $this->confirmingDocumentInformationCache,
            'tabCRR' => $this->contractRegistrationRequestCache,
            'tabFCOSigning' => $this->fcoCache,
            'tabFCCSigning' => $this->fccCache,
            'tabCDISigning' => $this->cdiCache,
            'tabCRRSigning' => $this->crrCache,
            'tabVTBCancellationRequests' => $this->vtbCancellationRequestCache,
            'tabBankLetters' => $this->bankLetterSigningCache,
            'bankLetters' => $this->bankLetterCache,
            'currencyRegisters' => $this->currencyRegistersCache,
            'currencyPayments' => $this->currencyPaymentsCache,
            'tabCurrencyPayments' => $this->currencyPaymentsSigningCache,
        ];
    }

}


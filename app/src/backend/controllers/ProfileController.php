<?php

namespace backend\controllers;

use addons\edm\EdmModule;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderSearch;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\Statement\StatementType;
use addons\fileact\FileActModule;
use addons\finzip\FinZipModule;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\models\Pain001Type;
use addons\swiftfin\models\SwiftFinSearch;
use addons\swiftfin\SwiftfinModule;
use common\base\Controller;
use common\document\Document;
use common\document\DocumentPermission;
use common\document\DocumentSearch;
use common\models\CommonUserExt;
use common\models\ImportError;
use common\models\User;
use DateTime;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

class ProfileController extends Controller
{
	protected $_innerCache;

	public function behaviors()
    {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						// Accessible for authorized users only
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

    public function actionDashboard()
    {
        $role = Yii::$app->user->identity->role;

        if ($role === User::ROLE_ADMIN || $role === User::ROLE_ADDITIONAL_ADMIN) {
            return $this->dashboardAdmin();
        } else {
            return $this->dashboardUser();
        }
    }

    protected function dashboardAdmin()
    {
        $this->redirect(['/autobot/multiprocesses/index']);
    }

    protected function dashboardUser()
    {
        return $this->render(
            'dashboardUser',
            [
                'counters' => $this->getCounters(),
                'accounts' => $this->getAccounts(),
            ]
        );
    }

	/**
     * Получение показателей для
     * стартовой страницы пользователя
	 * @return array
	 */
	protected function getCounters()
    {
        // Массив с сформированными данными для статистики
        $statistics = [];

        // Только для авторизованных пользователей
        // и для пользователей, у которых есть терминалы
        if (Yii::$app->user && Yii::$app->user->identity->terminals) {

            // Список модулей
            $modules = [
                EdmModule::SERVICE_ID,
                ISO20022Module::SERVICE_ID,
                SwiftfinModule::SERVICE_ID,
                FileActModule::SERVICE_ID,
                FinZipModule::SERVICE_ID
            ];

            // Перебор всех модулей и вывод функций получения показателей
            foreach($modules as $module) {
                $addon = Yii::$app->getModule($module);
                $userExtModel = $addon->getUserExtmodel(Yii::$app->user->id);

                if ($userExtModel->canAccess) {
                    // Получение названия для вызова конкретной функции
                    $funcName = strtolower($module);
                    $funcName = ucwords($funcName);

                    $funcName = "get{$funcName}Counters";
                    $statistics = $this->$funcName($statistics);
                }
            }

            // Ошибочные документы
            $statistics = $this->getErrorsCounters($statistics);
        }

        return $statistics;
	}

    /**
     * Получение показателей для модуля EDM
     */
    private function getEdmCounters($statistics)
    {
        // Уровень подписания пользователя
        $userSignatureNumber = Yii::$app->user->identity->signatureNumber;

        // Сегодняший день
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Сегодняшний день в формате
        $todayFormat = $this->getTodayFormat();
        $from = $todayFormat['from'];
        $to = $todayFormat['to'];

        // EDM шаблоны
        $edmTemplates = $this->statisticEdmTemplates();

        if (count($edmTemplates) > 0) {
            $statistics['edmTemplates'] = $edmTemplates;
        }

        // EDM сегодня

        // Показатели EDM за сегодня
        $edmToday = [];

        // Документы ожидающие подписания
        $edmForSigning = $this->statisticEdmForSigning();

        if ($userSignatureNumber && \Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => EdmModule::SERVICE_ID])) {
            $edmToday['forSigning'] = [
                'title' => Yii::t('app/profile', 'Documents for signing {count}', [
                    'count' => $this->renderCountNumbers($edmForSigning)
                ]),
                'url' => Yii::$app->urlManager->createUrl(['/edm/documents/signing-index',
                    'PaymentRegisterSearch' => []
                ])
            ];
        }

        // Непрочитанные сообщения edm (finzip)
        $totalUnread = $this->statisticFinzip();

        $edmToday['unread'] = [
            'title' => Yii::t('app/profile', 'Unread Messages {count}', [
                'count' => $this->renderCountNumbers($totalUnread)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/finzip/default',
                'FinZipSearch' => [
                    'direction' => Document::DIRECTION_IN
                ]
            ]),
        ];

        // Непрочитанные сообщения
        $edmStatementsToday = $this->statisticStatements($from, $to, Document::DIRECTION_IN);

        // Выписки
        $edmToday['statements'] = [
            'title' => Yii::t('app/profile', 'Statements {count}', [
                'count' => $this->renderCountNumbers($edmStatementsToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/edm/documents/statement',
                'StatementSearch' => [
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                    'direction'        => Document::DIRECTION_IN
                ]
            ]),
        ];

        // Платежные поручения, подготовленные
        $edmPaymentOrdersNewToday = $this->statisticEdmPaymentOrdersNew($from, $to);

        $edmToday['edmPaymentOrdersNewToday'] = [
            'title' => Yii::t('app/profile', 'Prepared Payment Orders {count}', [
                'count' => $this->renderCountNumbers($edmPaymentOrdersNewToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/edm/payment-register/payment-order',

                PaymentRegisterPaymentOrderSearch::FORM_NAME => [
                    'startDate'   => $todayDate,
                    'endDate' => $todayDate,
                    'registerId' => -1
                ]
            ]),
        ];

        // Платежные поручения, исполненные
        $edmPaymentOrdersExecutedToday = $this->statisticEdmPaymentOrdersExecuted($from, $to);

        $edmToday['edmPaymentOrdersExecutedToday'] = [
            'title' => Yii::t('app/profile', 'Executed Payment Orders {count}', [
                'count' => $this->renderCountNumbers($edmPaymentOrdersExecutedToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/edm/payment-register/payment-order',
                'PaymentRegisterPaymentOrderSearch' => [
                    'businessStatus'   => 'ACSC',
                    'startDate'   => $todayDate,
                    'endDate' => $todayDate,
                ]
            ]),
        ];

        // Платежные поручения отклоненные
        $edmPaymentOrdersRejectedToday = $this->statisticEdmPaymentOrdersRejected($from, $to);

        $edmToday['edmPaymentOrdersRejectedToday'] = [
            'title' => Yii::t('app/profile', 'Rejected Payment Orders {count}', [
                'count' => $this->renderCountNumbers($edmPaymentOrdersRejectedToday, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/edm/payment-register/payment-order',
                'PaymentRegisterPaymentOrderSearch' => [
                    'businessStatus'   => 'RJCT',
                    'startDate'   => $todayDate,
                    'endDate' => $todayDate,
                ]
            ]),
        ];

        $statistics['edmToday'] = $edmToday;

        return $statistics;
    }

    /**
     * Получение показателей по ошибочным документам
     */
    private function getErrorsCounters($statistics)
    {
        // Сегодняший день
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Сегодняшний день в формате
        $todayFormat = $this->getTodayFormat();
        $from = $todayFormat['from'];
        $to = $todayFormat['to'];

        $errors = [];

        // Входящие ошибочные документы
        $incomingErrors = $this->statisticErrors($from, $to, Document::DIRECTION_IN);

        $errors['incoming'] = [
            'title' => Yii::t('app/profile', 'Incoming Documents {count}', [
                'count' => $this->renderCountNumbers($incomingErrors, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/document/errors',
                'DocumentSearch' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                ]
            ])
        ];

        // Исходящие ошибочные документы
        $outcomingErrors = $this->statisticErrors($from, $to, Document::DIRECTION_OUT);

        $errors['outgoing'] = [
            'title' => Yii::t('app/profile', 'Outgoing Documents {count}', [
                'count' => $this->renderCountNumbers($outcomingErrors, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/document/errors',
                'DocumentSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                ]
            ])
        ];

        // Ошибки импорта

        // Получение и вывод, только если текущему
        // пользователю доступен данный журнал
        $permissionImportErrors = CommonUserExt::findOne([
            'type' => CommonUserExt::IMPORT_ERRORS_JOURNAL,
            'userId' => Yii::$app->user->id,
            'canAccess' => 1
        ]);

        if ($permissionImportErrors) {
            $importErrors = $this->statisticImportErrors($from, $to);

            $errors['import'] = [
                'title' => Yii::t('app/profile', 'Import Documents {count}', [
                    'count' => $this->renderCountNumbers($importErrors, true)
                ]),
                'url' => Yii::$app->urlManager->createUrl([
                    '/document/import-errors',
                    'ImportErrorSearch' => [
                        'dateCreate'   => $todayDate,
                    ]
                ])
            ];
        }

        $statistics['errorsToday'] = $errors;

        return $statistics;
    }

    /**
     * Получение показателей по документам Swiftfin
     */
    private function getSwiftfinCounters($statistics)
    {
        // Сегодняший день
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Сегодняшний день в формате
        $todayFormat = $this->getTodayFormat();
        $from = $todayFormat['from'];
        $to = $todayFormat['to'];

        $swiftfinToday = [];

        // Ожидающие подписания
        $swiftfinForSigningToday = $this->statisticSwiftfinForSigning();

        $swiftfinToday['forSigning'] = [
            'title' => Yii::t('app/profile', 'Documents for signing {count}', [
                'count' => $this->renderCountNumbers($swiftfinForSigningToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/signing-index',
                'SwiftFinSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                    'status' => Document::STATUS_FORSIGNING
                ]
            ])
        ];


        // Ожидающие модификации
        $swiftfinForCorrectionToday = $this->statisticSwiftfinForCorrection();

        $swiftfinToday['swiftfinForCorrectionToday'] = [
            'title' => Yii::t('app/profile', 'Documents for correction {count}', [
                'count' => $this->renderCountNumbers($swiftfinForCorrectionToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/correction-index',
                'SwiftFinSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                ]
            ])
        ];

        // Ожидающие верификации
        $swiftfinForVerificationToday = $this->statisticSwiftfinForVerification();

        $swiftfinToday['swiftfinForVerificationToday'] = [
            'title' => Yii::t('app/profile', 'Documents for verification {count}', [
                'count' => $this->renderCountNumbers($swiftfinForVerificationToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/user-verification-index',
                'SwiftFinSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                ]
            ])
        ];

        // Ожидающие авторизации
        $swiftfinForAuthorization = $this->statisticSwiftfinForAuthorization();

        $swiftfinToday['swiftfinForAuthorization'] = [
            'title' => Yii::t('app/profile', 'Documents for authorization {count}', [
                'count' => $this->renderCountNumbers($swiftfinForAuthorization)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/authorization-index',
            ])
        ];

        // Исходящие документы
        $swiftfinOutcomingToday = $this->statisticDocumentByType(
            SwiftfinModule::SERVICE_ID, ['direction' => Document::DIRECTION_OUT], $from, $to);

        $swiftfinToday['swiftfinOutcomingToday'] = [
            'title' => Yii::t('app/profile', 'Outgoing Documents {count}', [
                'count' => $this->renderCountNumbers($swiftfinOutcomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/index',
                'SwiftFinSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Входящие документы
        $swiftfinIncomingToday = $this->statisticDocumentByType(
            SwiftfinModule::SERVICE_ID, ['direction' => Document::DIRECTION_IN], $from, $to);

        $swiftfinToday['swiftfinIncomingToday'] = [
            'title' => Yii::t('app/profile', 'Incoming Documents {count}', [
                'count' => $this->renderCountNumbers($swiftfinIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/index',
                'SwiftFinSearch' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Ошибочные документы

        $swiftfinErrorsToday = $this->statisticDocumentByType(
            SwiftfinModule::SERVICE_ID, ['status' => DocumentSearch::getErrorStatus()], $from, $to);

        $swiftfinToday['swiftfinErrorsToday'] = [
            'title' => Yii::t('app/profile', 'Invalid Documents {count}', [
                'count' => $this->renderCountNumbers($swiftfinErrorsToday, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/swiftfin/documents/errors',
                'SwiftFinSearch' => [
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                ]
            ])
        ];

        $statistics['swiftfinToday'] = $swiftfinToday;

        return $statistics;
    }

    /**
     * Получение показателей по документам ISO
     */
    private function getIso20022Counters($statistics)
    {
        // Сегодняший день
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Сегодняшний день в формате
        $todayFormat = $this->getTodayFormat();
        $from = $todayFormat['from'];
        $to = $todayFormat['to'];

        $isoToday = [];

        // Документы свободного формата
        $isoFreeFormatIncomingToday = $this->statisticIso($from, $to, [
            Auth026Type::TYPE, Auth024Type::TYPE]
        );

        $isoToday['freeFormat'] = [
            'title' => Yii::t('app/profile', 'Free Format Documents {count}', [
                'count' => $this->renderCountNumbers($isoFreeFormatIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/ISO20022/documents/free-format',
                'ISO20022Search' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreate'   => $todayDate,
                ]
            ])
        ];

        // Платежные документы
        $isoPaymentsIncomingToday = $this->statisticIso($from, $to, [Pain001Type::TYPE]);

        $isoToday['payments'] = [
            'title' => Yii::t('app/profile', 'Payment Documents {count}', [
                'count' => $this->renderCountNumbers($isoPaymentsIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/ISO20022/documents/payments',
                'ISO20022Search' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreate'   => $todayDate,
                ]
            ])
        ];

        // Выписки
        $isoStatementsIncomingToday = $this->statisticIso($from, $to, [
            Camt053Type::TYPE, Camt054Type::TYPE
        ]);

        $isoToday['statements'] = [
            'title' => Yii::t('app/profile', 'Statements {count}', [
                'count' => $this->renderCountNumbers($isoStatementsIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/ISO20022/documents/statements',
                'ISO20022Search' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreate'   => $today->format('Y-m-d'),
                ]
            ])
        ];


        // Исходящие документы
        $isoOutcomingToday = $this->statisticDocumentByType(
            ISO20022Module::SERVICE_ID, ['direction' => Document::DIRECTION_OUT], $from, $to);

        $isoToday['isoOutcomingToday'] = [
            'title' => Yii::t('app/profile', 'Outgoing Documents {count}', [
                'count' => $this->renderCountNumbers($isoOutcomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/ISO20022/documents',
                'ISO20022Search' => [
                    'direction' => Document::DIRECTION_OUT,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Входящие документы
        $isoIncomingToday = $this->statisticDocumentByType(
            ISO20022Module::SERVICE_ID, ['direction' => Document::DIRECTION_IN], $from, $to);

        $isoToday['isoIncomingToday'] = [
            'title' => Yii::t('app/profile', 'Incoming Documents {count}', [
                'count' => $this->renderCountNumbers($isoIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/ISO20022/documents',
                'ISO20022Search' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Ошибочные документы
        $isoErrorsToday = $this->statisticDocumentByType(
            ISO20022Module::SERVICE_ID, ['status' => DocumentSearch::getErrorStatus()], $from, $to);

        $isoToday['isoErrorsToday'] = [
            'title' => Yii::t('app/profile', 'Invalid Documents {count}', [
                'count' => $this->renderCountNumbers($isoErrorsToday, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/ISO20022/documents/errors',
                'ISO20022Search' => [
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                ]
            ])
        ];

        $statistics['isoToday'] = $isoToday;

        return $statistics;
    }

    private function getFileactCounters($statistics)
    {
        // Сегодняший день
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Сегодняшний день в формате
        $todayFormat = $this->getTodayFormat();
        $from = $todayFormat['from'];
        $to = $todayFormat['to'];

        $fileactToday = [];

        // Документы, ожидающие подписания
        $fileactForSigningToday = $this->statisticFileactForSigning();

        if (\Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => FileActModule::SERVICE_ID])) {
            $fileactToday['forSigning'] = [
                'title' => Yii::t('app/profile', 'Documents for signing {count}', [
                    'count' => $this->renderCountNumbers($fileactForSigningToday)
                ]),
                'url'   => Yii::$app->urlManager->createUrl([
                    '/fileact/documents/signing-index',
                ])
            ];
        }

        // Исходящие документы
        $fileactOutcomingToday = $this->statisticDocumentByType(
            FileActModule::SERVICE_ID, ['direction' => Document::DIRECTION_OUT], $from, $to);

        $fileactToday['isoOutcomingToday'] = [
            'title' => Yii::t('app/profile', 'Outgoing Documents {count}', [
                'count' => $this->renderCountNumbers($fileactOutcomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/fileact/default',
                'FileActSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Входящие документы
        $fileactIncomingToday = $this->statisticDocumentByType(
            FileActModule::SERVICE_ID, ['direction' => Document::DIRECTION_IN], $from, $to);

        $fileactToday['isoIncomingToday'] = [
            'title' => Yii::t('app/profile', 'Incoming Documents {count}', [
                'count' => $this->renderCountNumbers($fileactIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/fileact/default',
                'FileActSearch' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Ошибочные документы
        $fileactErrorsToday = $this->statisticDocumentByType(
            FileActModule::SERVICE_ID, ['status' => DocumentSearch::getErrorStatus()], $from, $to);

        $fileactToday['isoErrorsToday'] = [
            'title' => Yii::t('app/profile', 'Invalid Documents {count}', [
                'count' => $this->renderCountNumbers($fileactErrorsToday, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/document/errors',
                'DocumentSearch' => [
                    'type' => FileActModule::SERVICE_ID,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                ]
            ])
        ];

        $statistics['fileactToday'] = $fileactToday;

        return $statistics;
    }

    private function getFinzipCounters($statistics)
    {
        // Сегодняший день
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Сегодняшний день в формате
        $todayFormat = $this->getTodayFormat();
        $from = $todayFormat['from'];
        $to = $todayFormat['to'];

        $finzipToday = [];

        // Документы, ожидающие подписания
        $finzipForSigningToday = $this->statisticFinzipForSigning();

        if (\Yii::$app->user->can(DocumentPermission::SIGN, ['serviceId' => FinZipModule::SERVICE_ID])) {
            $finzipToday['forSigning'] = [
                'title' => Yii::t('app/profile', 'Documents for signing {count}', [
                    'count' => $this->renderCountNumbers($finzipForSigningToday)
                ]),
                'url'   => Yii::$app->urlManager->createUrl([
                    '/finzip/documents/signing-index',
                ])
            ];
        }

        // Исходящие документы
        $finzipOutcomingToday = $this->statisticDocumentByType(
            FinZipModule::SERVICE_ID, ['direction' => Document::DIRECTION_OUT], $from, $to);

        $finzipToday['finzipOutcomingToday'] = [
            'title' => Yii::t('app/profile', 'Outgoing Documents {count}', [
                'count' => $this->renderCountNumbers($finzipOutcomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/finzip/default',
                'FinZipSearch' => [
                    'direction' => Document::DIRECTION_OUT,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Входящие документы
        $finzipIncomingToday = $this->statisticDocumentByType(
            FinZipModule::SERVICE_ID, ['direction' => Document::DIRECTION_IN], $from, $to);

        $finzipToday['finzipIncomingToday'] = [
            'title' => Yii::t('app/profile', 'Incoming Documents {count}', [
                'count' => $this->renderCountNumbers($finzipIncomingToday)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/finzip/default',
                'FinZipSearch' => [
                    'direction' => Document::DIRECTION_IN,
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate
                ]
            ])
        ];

        // Ошибочные документы
        $finzipErrorsToday = $this->statisticDocumentByType(
            FinZipModule::SERVICE_ID, ['status' => DocumentSearch::getErrorStatus()], $from, $to);

        $finzipToday['finzipErrorsToday'] = [
            'title' => Yii::t('app/profile', 'Invalid Documents {count}', [
                'count' => $this->renderCountNumbers($finzipErrorsToday, true)
            ]),
            'url' => Yii::$app->urlManager->createUrl([
                '/document/errors',
                'DocumentSearch' => [
                    'type' => 'FINZIP',
                    'dateCreateFrom'   => $todayDate,
                    'dateCreateBefore' => $todayDate,
                ]
            ])
        ];

        $statistics['finzipToday'] = $finzipToday;

        return $statistics;
    }

    private function getAccounts(): array
    {
        $userHasEdmAccess = Yii::$app->user->can('accessService', ['serviceId' => 'edm']);
        if (!$userHasEdmAccess) {
            return [];
        }

        $queryOrganizations = Yii::$app->terminalAccess->query(DictOrganization::className());
        $queryOrganizations->select('id')->asArray();
        $organizations = $queryOrganizations->all();

        $query = EdmPayerAccount::find();
        $query->where(['organizationId' => ArrayHelper::getColumn($organizations, 'id')]);

        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, 'edmPayersAccounts.id');

        return $query
            ->with('balance')
            ->joinWith('edmDictOrganization as organization')
            ->joinWith('bank as bank')
            ->joinWith('edmDictCurrencies as currency')
            ->orderBy([
                'organization.name' => SORT_ASC,
                'bank.name' => SORT_ASC,
                new Expression("case currency.name when 'RUR' then 0 when 'RUB' then 0 else 1 end"),
                'currency.name' => SORT_ASC,
                'number' => SORT_ASC,
            ])
            ->all();
    }

    /**
     * Получение шаблонов EDM
     */
    private function statisticEdmTemplates()
    {
        $query = Yii::$app->terminalAccess->query(PaymentRegisterPaymentOrderTemplate::className());

        $arr = [];

        $templates = $query->all();

        foreach($templates as $template) {
            $arr[] = [
                'id' => $template->id,
                'name' => $template->name,
                'beneficiary' => $template->beneficiaryName,
                'paymentPurpose' => $template->paymentPurpose,
                'isOutdated' => $template->isOutdated,
                'url' => Yii::$app->urlManager->createUrl([
                    '/edm/payment-order-templates/create-payment-order', 'id' => $template->id
                ]),
            ];
        }

        return $arr;
    }

    /**
     * EDM, документы ожидающие подписания
     */
    private function statisticEdmForSigning($dateFrom = null, $dateTo = null)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'type' => PaymentRegisterType::TYPE,
                'status' => Document::STATUS_FORSIGNING,
                'signaturesCount' => Yii::$app->user->identity->signatureNumber - 1
            ]
        );

        if ($dateFrom && $dateTo) {
            $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        $query->andWhere('`signaturesRequired` > `signaturesCount`');

        $prForSigning = $query->count();

        $fcoModel = new ForeignCurrencyOperationSearch();
        $fcoForSigning = $fcoModel->countForSigning();

        $allCounts = $prForSigning + $fcoForSigning;

        return $allCounts;
    }

    /**
     * EDM, платежные поручения, подготовленные
     */
    private function statisticEdmPaymentOrdersNew($dateFrom, $dateTo)
    {
        $query = Yii::$app->terminalAccess->query(
            PaymentRegisterPaymentOrder::className(),
            [
                'registerId' => null
            ]
        );

        $query->andWhere(['between', 'date', $dateFrom, $dateTo]);
        $query->andWhere(['!=', 'status', Document::STATUS_DELETED]);

        return $query->count();
    }

    /**
     * EDM, платежные поручения, исполненные
     */
    private function statisticEdmPaymentOrdersExecuted($dateFrom, $dateTo)
    {
        $query = Yii::$app->terminalAccess->query(
            PaymentRegisterPaymentOrder::className(),
            [
                'businessStatus' => 'ACSC',
            ]
        );

        $query->andWhere(['between', 'dateDue', $dateFrom, $dateTo]);

        return $query->count();
    }

    /**
     * EDM, платежные поручения, отклоненные
     */
    private function statisticEdmPaymentOrdersRejected($dateFrom, $dateTo)
    {
        $query = Yii::$app->terminalAccess->query(
            PaymentRegisterPaymentOrder::className(),
            [
                'businessStatus' => 'RJCT',
            ]
        );

        $query->andWhere(['between', 'date', $dateFrom, $dateTo]);

        return $query->count();
    }

    /**
     * Swiftfin, ожидающие подписания
     */
    private function statisticSwiftfinForSigning($dateFrom = null, $dateTo = null)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => SwiftfinModule::SERVICE_ID,
                'document.status' => [Document::STATUS_FORSIGNING],
                'signaturesCount' => Yii::$app->user->identity->signatureNumber - 1,
                'direction' => Document::DIRECTION_OUT
            ]
        );

        $query->andWhere('`signaturesRequired` > `signaturesCount`');

        if ($dateFrom && $dateTo) {
        $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        return $query->count();
    }

    /**
     * Swiftfin, ожидающие модификации
     */
    private function statisticSwiftfinForCorrection($dateFrom = null, $dateTo = null)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => SwiftfinModule::SERVICE_ID,
                'document.status' => Document::STATUS_CORRECTION,
                'direction' => Document::DIRECTION_OUT
            ]
        );

        if ($dateFrom && $dateTo) {
            $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        return $query->count();
    }

    /**
     * Swiftfin, ожидающие верификации
     */
    private function statisticSwiftfinForVerification($dateFrom = null, $dateTo = null)
    {
        $module = Yii::$app->getModule('swiftfin');

        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => SwiftfinModule::SERVICE_ID,
                'document.status' => Document::getUserVerifiableStatus(),
                'document.type' => $module->getUserVerifyDocType(),
                'direction' => Document::DIRECTION_OUT
            ]
        );

        if ($dateFrom && $dateTo) {
            $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        return $query->count();
    }

    /**
     * Swiftfin, ожидающие авторизации
     */
    private function statisticSwiftfinForAuthorization()
    {
        return SwiftFinSearch::getForAuthorizationCount();
    }

    /**
     * ISO20022, входящие документы по типу
     */
    private function statisticIso($dateFrom, $dateTo, $format)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => ISO20022Module::SERVICE_ID,
                'direction' => Document::DIRECTION_IN,
                'type' => $format
            ]
        );

        $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);

        return $query->count();
    }

    /**
     * Fileact, ожидают подписания
     */
    private function statisticFileactForSigning($dateFrom = null, $dateTo = null)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => FileActModule::SERVICE_ID,
                'document.status' => [Document::STATUS_FORSIGNING, Document::STATUS_SERVICE_PROCESSING],
                'signaturesCount' => Yii::$app->user->identity->signatureNumber - 1,
                'direction' => Document::DIRECTION_OUT
            ]
        );

        $query->andWhere('`signaturesRequired` > `signaturesCount`');

        if ($dateFrom && $dateTo) {
            $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        return $query->count();
    }

    /**
     * Fileact, ожидают подписания
     */
    private function statisticFinzipForSigning($dateFrom = null, $dateTo = null)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'typeGroup' => FinZipModule::SERVICE_ID,
                'document.status' => [Document::STATUS_FORSIGNING, Document::STATUS_SERVICE_PROCESSING],
                'signaturesCount' => Yii::$app->user->identity->signatureNumber - 1,
                'direction' => Document::DIRECTION_OUT
            ]
        );

        $query->andWhere('`signaturesRequired` > `signaturesCount`');

        if ($dateFrom && $dateTo) {
            $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        return $query->count();
    }

    /**
     * Получение выписок
     */
    private function statisticStatements($dateFrom, $dateTo, $direction)
    {

        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'direction' => $direction,
                'type' => StatementType::TYPE
            ]
        );

        $statements = $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo])->all();

        $count = 0;

        // Доступные пользователю счета
        $allowedAccounts = EdmPayerAccountUser::getUserAllowAccountsNumbers(Yii::$app->user->id);

        // Проверка счетов на доступность пользователю
        foreach($statements as $statement) {
            if (!$statement->extModel) {
                continue;
            }

            $statementAccount = $statement->extModel->accountNumber;

            if (in_array($statementAccount, $allowedAccounts) !== false) {
                $count++;
            }
        }

        return $count;
    }

    private function statisticErrors($dateFrom, $dateTo, $direction)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'direction' => $direction,
            ]
        );

        $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        $query->andWhere(['in', 'status', DocumentSearch::getErrorStatus()]);
        $query->andWhere(['not in', 'document.type', DocumentSearch::$techMessageTypes]);

        // Типы документов, доступные пользователю
        $types = [];

        if (Yii::$app->user) {
            foreach (array_keys(Yii::$app->addon->getRegisteredAddons()) as $serviceId) {
                if ($module = Yii::$app->getModule($serviceId)) {
                    $userExtModel = $module->getUserExtmodel(Yii::$app->user->id);
                    if ($userExtModel->canAccess) {
                       $types[] = $serviceId;
                    }
                }
            }
        }

        if (count($types) == 0) {
            // Если пользователю не доступны документы
            $query->andWhere('0=1');
        } else {
            // Отбор только по доступным пользователю документам
            $query->andWhere(['in', 'document.typeGroup', $types]);
        }

        return $query->count();
    }

    private function statisticImportErrors($dateFrom, $dateTo)
    {
        $query = ImportError::find();
        $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        return $query->count();
    }

    /**
     * Получение Finzip
     */
    private function statisticFinzip($dateFrom = null, $dateTo = null)
    {
        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            [
                'direction' => Document::DIRECTION_IN,
                'typeGroup' => 'finzip',
                'viewed' => 0
            ]
        );

        if ($dateFrom && $dateTo) {
            $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);
        }

        return $query->count();
    }

    /**
     * Получение документа по типу и направлению
     * @param $typeGroup
     * @param $direction
     * @param $from
     * @param $to
     * @return mixed
     */

    private function statisticDocumentByType($typeGroup, $options, $dateFrom, $dateTo)
    {
        $queryOptions = [
            'typeGroup' => $typeGroup
        ];

        if (isset($options['direction'])) {
            $queryOptions['direction'] = $options['direction'];
        }

        if (isset($options['status'])) {
            $queryOptions['status'] = $options['status'];
        }

        $query = Yii::$app->terminalAccess->query(
            Document::className(),
            $queryOptions
        );

        $query->andWhere(['between', 'dateCreate', $dateFrom, $dateTo]);

        return $query->count();
    }

    /**
     * Получение текущей даты
     * в формате "начало дня" / "конец дня"
     * @return array
     */
    private function getTodayFormat()
    {
        // Входящие выписки, за текущий день
        $todayFrom = new DateTime();
        $todayFrom->setTime(0, 0, 0);

        $todayTo = new DateTime();
        $todayTo->setTime(23, 59, 59);

        return [
            'from' => $todayFrom->format('Y-m-d H:i:s'),
            'to' => $todayTo->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Выделение значения показателя
     */
    private function renderCountNumbers($number, $error = false)
    {
        $string = $number;

        if ($error) {
            return "<span class='count count-error'>({$string})</span>";
        } else {
            return "<span class='count count-success'>({$string})</span>";
        }
    }

    // AJAX-получение формы запроса выписки по счету
    public function actionRenderStatementRequest() {

        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new HttpException('404','Method not found');
        }

        // Получение номера счета из запроса
        $id = Yii::$app->request->get('accountId');

        // Поиск счета по номеру
        $account = EdmPayerAccount::findOne($id);

        $html = $this->renderAjax('@addons/edm/views/documents/_sendRequestForm',
            [
                'account' => $account,
                'hideToggleButton' => true
            ]
        );

        return $html;
    }
}

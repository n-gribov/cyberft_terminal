<?php

namespace backend\controllers;

use addons\ISO20022\models\ISO20022DocumentExt;
use common\document\Document;
use common\document\DocumentSearch;
use common\helpers\DocumentHelper;
use common\helpers\UserHelper;
use common\models\form\DocumentCorrectionForm;
use common\models\ImportError;
use common\models\ImportErrorSearch;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use common\modules\autobot\models\Autobot;
use common\base\BaseServiceController;
use common\models\UserTerminal;
use common\models\UserColumnsSettings;
use yii\web\Response;

class DocumentController extends BaseServiceController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'save-checked-documents'  => ['post'],
                    'verify'  => ['post'],
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['controller-verification', 'view', 'save-checked-documents', 'verify'],
                        'roles' => ['documentControllerVerification'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['documentImportErrors'],
                        'actions' => [
                            'import-errors'
                        ],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['commonDocument'],
                        'actions' => [
                            'errors',
                            'view',
                            'send',
                            'correction',
                            'resend',
                            'save-column-settings',
                            'get-signers-info',
                            'create-event-print',
                            'get-statuses',
                        ],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['commonAdmins'],
                        'actions' => [
                            'index',
                        ]
                    ],
                    [
                        'allow'   => true,
                        // access is checked in common\actions\documents\DeleteAction
                        'actions' => ['download-attachment', 'delete'] 
                    ]
                ],
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = 'common\actions\documents\DeleteAction';
        return $actions;
    }

    /**
     * Index action. Get list of documents
     *
     * @return mixed
     */
    public function actionIndex()
    {
        // Если журнал документов смотрит пользователь без доступа к сервисам и терминалам,
        // делаем переадресацию на страницу журнала с сортировкой по дате
        if (Yii::$app->user->identity && Yii::$app->user->identity->role !== User::ROLE_ADMIN) {
            // Список терминалов, доступных пользователю
            $terminalId = Yii::$app->user->identity->terminalId;
            if (empty($terminalId)) {
                $terminalId = UserTerminal::getUserTerminalIds(Yii::$app->user->id);
            }

            // Список аддонов, доступных пользователю
            $services = [];

            foreach (array_keys(Yii::$app->addon->getRegisteredAddons()) as $serviceId) {
                // Дополнительному администратору доступны все аддоны
                if (Yii::$app->user->identity->role == User::ROLE_ADDITIONAL_ADMIN) {
                    $services[] = $serviceId;

                    continue;
                }

                $model = Yii::$app->getModule($serviceId)->getUserExtModel(Yii::$app->user->identity->id);

                if (!$model) {
                    continue;
                }

                if ($model->isAllowedAccess()) {
                    $services[] = $serviceId;
                }
            }

            // Если пользователю недоступны терминалы и аддоны,
            // то добавляем сортировку журнала по текущей дате
            if ((empty($terminalId) || empty($services)) && empty(Yii::$app->request->queryParams)) {

                // Список параметров, с которыми необходимо вызвать журнал документов
                $params = [
                    'dateCreateFrom-documentsearch-datecreatefrom' => date('Y-m-d'),
                    'DocumentSearch[dateCreateFrom]' => date('Y-m-d'),
                    'DocumentSearch[dateCreateBefore]' => date('Y-m-d'),
                    'dateCreateBefore-documentsearch-datecreatebefore' => date('Y-m-d')
                ];

                $queryParams = http_build_query($params);
                // Перенаправить на страницу индекса
                $this->redirect(Url::toRoute(['/document/index?' . $queryParams]));
            }
        }

        $searchModel  = new DocumentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $nonEmptySearchParamsValues = array_filter([
            $searchModel->dateCreateFrom,
            $searchModel->dateCreateBefore,
            $searchModel->searchBody
        ]);

        $filterStatus = count($nonEmptySearchParamsValues) > 0;

        // Вывести страницу
        return $this->render(
            'index',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'filterStatus' => $filterStatus,
                'urlParams'    => $this->getSearchUrl('DocumentSearch'),
                'listType' => 'documentCommon'
            ]
        );
    }

    /**
     * Журнал документов с ошибочными статусами
     */
    public function actionErrors()
    {
        $searchModel  = new DocumentSearch();
        $dataProvider = $searchModel->searchForErrors(Yii::$app->request->queryParams);

        $nonEmptySearchParamsValues = array_filter([
            $searchModel->dateCreateFrom,
            $searchModel->dateCreateBefore,
            $searchModel->searchBody
        ]);

        $filterStatus = count($nonEmptySearchParamsValues) > 0;

        // Вывести страницу
        return $this->render(
            'errors',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'filterStatus' => $filterStatus,
                'urlParams'    => $this->getSearchUrl('DocumentSearch'),
                'listType' => 'documentError'
            ]
        );
    }

    public function actionView($id, $mode = '')
    {
        // Получить из БД документ с указанным id
        $model = $this->findModel($id);

        $referencingDataProvider = new ActiveDataProvider([
            'query' => $model->findReferencingDocuments(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // (CYB-4587) Временный костыль: при открытии camt отображаем вьюху для выписок, а не для ISO20022
        if (in_array($model->type, [
            \addons\ISO20022\models\Camt052Type::TYPE,
            \addons\ISO20022\models\Camt053Type::TYPE,
            \addons\ISO20022\models\Camt054Type::TYPE,
        ])) {
            $dataView = Yii::$app->registry->getTypeRegisteredAttribute('Statement', 'transport', 'dataView');
            $actionView = Yii::$app->registry->getTypeRegisteredAttribute('Statement', 'transport', 'actionView');
        } else {
            $dataView = Yii::$app->registry->getTypeRegisteredAttribute($model->type, $model->typeGroup, 'dataView');
            $actionView = Yii::$app->registry->getTypeRegisteredAttribute($model->type, $model->typeGroup, 'actionView');
        }

        $autobot = Autobot::find()
            ->joinWith('controller.terminal')
            ->where([
                'primary' => 1,
                'userId' => Yii::$app->user->id,
                'terminal.terminalId' => $model->sender,
                'controllerVerificationFlag' => 1
            ])
            ->one();

        // Зарегистрировать событие просмотра документа
        // только если это новый просмотр (т.е. не переход по вкладкам)

        if (empty($mode)) {

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
        }

        // Вывести страницу
        return $this->render('view', [
            'model' => $model,
            'mode' => $mode,
            'dataView' => $dataView,
            'actionView' => $actionView,
            'autobot' => $autobot,
            'urlParams' => $this->getSearchUrl('DocumentSearch'),
            'referencingDataProvider' => $referencingDataProvider
        ]);
    }

    /**
     * List of documents for sending
     *
     */
    public function actionControllerVerification()
    {
        $searchModel  = new DocumentSearch();
        $checkedDocuments = $this->getCachedCheckedDocuments();
        $page = Yii::$app->request->get('page', 1);
        $queryParams = Yii::$app->request->queryParams;

        $autobot = Autobot::findOne([
            'primary' => 1,
            'userId' => Yii::$app->user->id,
            'controllerVerificationFlag' => 1
        ]);

        if (!is_null($autobot)) {
            $queryParams['sender'] = $autobot->terminalId;
        }

        $dataProvider = $searchModel->searchForVerification($queryParams);

        // Вывести страницу
        return $this->render('forControllerVerification',
        [
            'searchModel'  => $searchModel,
            'page' => $page,
            'checkedDocuments' => $checkedDocuments,
            'dataProvider' => $dataProvider,
            'autobot'      => $autobot,
            'filterStatus' => !empty(Yii::$app->request->queryParams)
        ]);
    }

    public function actionVerify()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $action = Yii::$app->request->post('action', 'reject');
            $status = $action == 'reject' ? Document::STATUS_CONTROLLER_VERIFICATION_FAIL : Document::STATUS_FOR_MAIN_AUTOSIGNING;
            $checkedDocuments = Yii::$app->request->post('postDocIds', []);
            foreach ($this->getCachedCheckedDocuments() as $page => $ids) {
                if (is_array($ids)) {
                    $checkedDocuments = array_merge($checkedDocuments, $ids);
                }
            }

            $this->clearCheckedDocuments();

            if (empty($checkedDocuments)) {
                // Поместить в сессию флаг сообщения об отсутствии помеченных документов
                Yii::$app->session->setFlash('error', Yii::t('app', 'Documents not selected'));

                // Перенаправить на страницу индекса
                return $this->redirect(['controller-verification']);
            }

            $autobot = Autobot::findOne([
                'primary' => 1,
                'userId' => Yii::$app->user->id,
                'controllerVerificationFlag' => 1
            ]);

            if (is_null($autobot)) {
                // Перенаправить на страницу индекса
                return $this->redirect(['controller-verification']);
            }

            foreach ($checkedDocuments as $docId) {
                // Получить из БД документ с указанным id
                $doc = $this->findModel($docId);

                if ($doc->sender !== $autobot->terminalId) {
                    continue;
                }

                if ($doc->status === Document::STATUS_FOR_CONTROLLER_VERIFICATION) {
                    DocumentHelper::updateDocumentStatus($doc, $status);
                }
            }
        }

        // Перенаправить на страницу индекса
        return $this->redirect(['controller-verification']);
    }

    public function clearCheckedDocuments()
    {
        \Yii::$app->cache->set('checked-documents-forVerify-' . Yii::$app->session->id, []);
    }

    public function cacheCheckedDocuments($list)
    {
        \Yii::$app->cache->set('checked-documents-forVerify-' . Yii::$app->session->id, $list);
    }

    public function getCachedCheckedDocuments()
    {
        $checkboxes = \Yii::$app->cache->get('checked-documents-forVerify-' . Yii::$app->session->id);
        if (!$checkboxes) {
            $checkboxes = [];
        }

        return $checkboxes;
    }

    public function actionSaveCheckedDocuments()
    {
        $page = Yii::$app->request->post('pageId');
        $checkedDocuments = $this->getCachedCheckedDocuments();
        $checkedDocuments[$page] = Yii::$app->request->post('checkedDocuments', []);
        $this->cacheCheckedDocuments($checkedDocuments);

        return json_encode($this->getCachedCheckedDocuments());
    }

    /**
     * Correction action
     *
     * @return mixed
     */
    public function actionCorrection()
    {
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $model = new DocumentCorrectionForm();
            $model->documentId = \Yii::$app->request->post('documentId');
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->toCorrection(\Yii::$app->user->id)) {
                // Поместить в сессию флаг сообщения об успешной передаче документа на корректировку
                Yii::$app->session->setFlash('success', \Yii::t('doc', 'The document was sent for correction'));
                // Перенаправить на страницу индекса
                return $this->redirect('index');
            }

            // Поместить в сессию флаг сообщения об ошибке передачи документа на корректировку
            Yii::$app->session->setFlash('error', \Yii::t('doc', 'The document was not sent for correction'));

            // Перенаправить на страницу просмотра
            return $this->redirect(['view', ['id' => $model->documentId]]);
        }

        $referer = Url::previous('edit');

        if (empty($referer)) {
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        // Перенаправить на предыдущую страницу
        return $this->redirect([$referer]);
    }

    public function actionDownloadAttachment($id, $mode = 'ISO20022')
    {
        /*
         * Если мы пытаемся скачать вложение в ISO-шном документе из раздела "Документы",
         * нужно перенаправлять на соответствующий action
         */
        // Перенаправить на страницу скачивания файла
        return $this->redirect(['/'.$mode.'/documents/download-attachment', 'id' => $id]);
    }

    public function actionSaveColumnSettings()
    {
        $post = Yii::$app->request->post();

        $data = $post['data'];
        $settings = $post['settings'];

        $user = $data['user'];
        $type = $data['type'];

        // Изменяем настройки для пользователя
        $record = UserColumnsSettings::findOne(['userId' => $user, 'listType' => $type]);

        if ($record) {
            $record->settingsData = $settings;
        } else {
            $record = new UserColumnsSettings;
            $record->userId = $user;
            $record->listType = $type;
            $record->settingsData = $settings;
        }

        // Сохранить модель в БД
        $record->save();

        // Перенаправить на предыдущую страницу
        return $this->redirect(Yii::$app->request->referrer);
    }

    // Получение информации о подписантах документа
    public function actionGetSignersInfo()
    {
        // Если не ajax-запрос
        if (!Yii::$app->request->isAjax) {
            throw new \yii\web\HttpException('404','Method not found');
        }

        $id = Yii::$app->request->get('documentId');

        // Не указан id документа
        if (!$id) {
            return null;
        }

        // Не найден документ по id
        $document = Document::findOne($id);

        if (!$document) {
            return null;
        }

        // Представление в зависимости от направления документа
        $viewName = ($document->direction === Document::DIRECTION_OUT) ? 'signatures/_output' : 'signatures/_input';

        $html = $this->renderAjax($viewName, ['model' => $document]);

        return $html;
    }

    /**
     * Журнал ошибок импорта
     */
    public function actionImportErrors()
    {
        $model = new ImportError();
        $searchModel = new ImportErrorSearch();

        // Вывести страницу
        return $this->render('importErrors', [
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'searchModel' => $searchModel,
            'model' => $model,
            'listType' => 'importErrors'
        ]);
    }

    public function actionGetStatuses()
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $documentsIds = Yii::$app->request->post('ids', []);
        // Получить из БД список документов через компонент авторизации доступа к терминалам
        $documents = Yii::$app->terminalAccess
            ->query(Document::className(), ['id' => $documentsIds])
            ->all();

        return array_map(
            function (Document $document) {
                $documentData = [];

                if ($document->typeGroup === 'ISO20022') {
                    $extModel = $document->extModel;
                    if ($extModel instanceof ISO20022DocumentExt) {
                        $documentData = [
                            'subject'          => $extModel->subject,
                            'descr'            => $extModel->descr,
                            'typeCode'         => $extModel->typeCode,
                            'fileName'         => $extModel->fileName,
                            'originalFilename' => $extModel->originalFilename,
                            'count'            => $extModel->count,
                            'currency'         => $extModel->currency,
                            'sum'              => $extModel->sum ? \Yii::$app->formatter->asDecimal($extModel->sum, 2) : null,
                            'periodBegin'      => $extModel->periodBegin ? \Yii::$app->formatter->asDate($extModel->periodBegin) : null,
                            'periodEnd'        => $extModel->periodEnd ? \Yii::$app->formatter->asDate($extModel->periodEnd) : null,
                        ];
                    }
                }

                return [
                    'id'              => $document->id,
                    'type'            => $document->type,
                    'status'          => $document->status,
                    'statusName'      => $document->getStatusLabel(),
                    'signaturesCount' => $document->signaturesCount,
                    'documentData'    => (object)$documentData,
                    'statusAge'       => time() - (new \DateTime($document->statusDate))->getTimestamp(),
                ];
            },
            $documents
        );
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(Document::className(), $id);
    }

}

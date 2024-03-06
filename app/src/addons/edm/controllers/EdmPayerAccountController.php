<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountSearch;
use addons\edm\models\EdmScheduledRequestCurrent;
use addons\edm\models\EdmScheduledRequestPrevious;
use addons\edm\models\StatementRequest\StatementRequestType;
use addons\swiftfin\models\SwiftFinDictBank;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\Address;
use common\helpers\DocumentHelper;
use common\helpers\vtb\VTBHelper;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Контроллер для работы со счетами плательщиков
 * @package addons\edm\controllers
 */
class EdmPayerAccountController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'update',
                            'delete',
                            'create',
                            'view',
                            'send-request',
                            'list',
                            'simple-list',
                            'send-request-all-accounts',
                            'update-export-settings',
                        ],
                        'allow' => true,
                        'roles' => ['admin', 'additionalAdmin'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'view',
                            'list',
                            'simple-list',
                            'get-swiftbic-organization',
                            'get-account-data'
                        ],
                        'allow' => true,
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                    [
                        'actions' => [
                            'send-request',
                            'send-request-all-accounts',
                        ],
                        'allow' => true,
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => [
                            'serviceId' => EdmModule::SERVICE_ID,
                            'documentTypeGroup' => EdmDocumentTypeGroup::STATEMENT,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     * с журналом всех счетов плательщика
     */
    public function actionIndex()
    {
        $searchModel = new EdmPayerAccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($name = null)
    {
        $model = new EdmPayerAccount();

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Установить признак доступности счета пользователям
                EdmHelper::setAccountToUsers($model->id);

                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Вывести страницу
        return $this->render('create', [
            'model' => $model,
            'name' => $name
        ]);
    }

    /**
     * Редактирование существующего счета плательщика
     */
    public function actionUpdate($id)
    {
        // Получить из БД счёт с указанным id
        $model = $this->findModel($id);
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Если данные модели успешно загружены из формы в браузере и модель успешно сохранена в БД
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->addFlash('error', 'Form data is not valid');
            }
        }

        // Вывести страницу
        return $this->render('update', ['model' => $model]);
    }

    /**
     * Метод выводит страницу просмотра счёта
     */
    public function actionView($id)
    {
        // Получить из БД счёт с указанным id
        $model = $this->findModel($id);
        // (CYB-4583) Вводим модели для запросов выписок за прошлый день и за текущий
        $reqPrevDay = $this->findModelScheduledRequestPrevious($model->number);
        $reqCurrDay = $this->findModelScheduledRequestCurrent($model->number);
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            \Yii::info(Yii::$app->request->post());
            $post = Yii::$app->request->post();

            $reqPrevDay->lastTime = null;
            $reqCurrDay->lastTime = null;

            if (
                isset($post['previousDayCheckBox'])
                && isset($post['EdmScheduledRequestPrevious']['previousDaysSelect'])
            ) {
                $reqPrevDay->load($post);
                if (!empty($post['EdmScheduledRequestPrevious']['previousDaysSelect'])) {
                    $reqPrevDay->weekDays = implode(',', $post['EdmScheduledRequestPrevious']['previousDaysSelect']);
                } else {
                    $reqPrevDay->weekDays = null;
                }
                $reqPrevDay->currentDay = date('Y-m-d');
                // Сохранить модель в БД
                $reqPrevDay->save();
            } else {
                $reqPrevDay->startTime = null;
                $reqPrevDay->endTime = null;
                $reqPrevDay->currentDay = null;
                $reqPrevDay->interval = null;
                $reqPrevDay->weekDays = null;
                // Сохранить модель в БД
                $reqPrevDay->save();
            }

            if (isset($post['currentDayCheckBox']) && isset($post['EdmScheduledRequestCurrent']['currentDaysSelect'])) {
                $reqCurrDay->load($post);
                if (!empty($post['EdmScheduledRequestCurrent']['currentDaysSelect'])) {
                    $reqCurrDay->weekDays = implode(',', $post['EdmScheduledRequestCurrent']['currentDaysSelect']);
                } else {
                    $reqCurrDay->weekDays = null;
                }
                $reqCurrDay->currentDay = date('Y-m-d');
                // Сохранить модель в БД
                $reqCurrDay->save();
            } else {
                $reqCurrDay->startTime = null;
                $reqCurrDay->endTime = null;
                $reqCurrDay->currentDay = null;
                $reqCurrDay->interval = null;
                $reqCurrDay->weekDays = null;
                // Сохранить модель в БД
                $reqCurrDay->save();
            }
        }

        // (CYB-4583) Узнаем, принадлежит ли счет банку ВТБ, чтобы в таком случае показать настройки
        $account = EdmPayerAccount::findOne(['number' => $model->number]);
        $bank = DictBank::findOne(['bik' => $account->bankBik]);
        $isVTBTerminal = false;
        if (!empty($bank)) {
            if (!empty($bank->terminalId)) {
                if (VTBHelper::isGatewayTerminal($bank->terminalId)) {
                    $isVTBTerminal = true;
                }
            }
        }

        // Вывести страницу
        return $this->render('view', [
            // Получить из БД счёт с указанным id
            'model' => $this->findModel($id),
            'modelScheduledRequestPreviousDay' => $reqPrevDay,
            'modelScheduledRequestCurrentDay' => $reqCurrDay,
            'isVTBTerminal' => $isVTBTerminal
        ]);
    }

    /**
     * Метод удаляет счёт плательщика
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        // Удаление доступа к счету у удаляемого счета
        EdmHelper::deleteAccountFromUsers($id);

        // Получить из БД счёт с указанным id
        $model = $this->findModel($id);

        // Удалить счёт из БД
        $model->delete();

        // Поместить в сессию флаг сообщения об успешном удалении счёта плательщика
        Yii::$app->session->setFlash('success', Yii::t('edm', 'The payer account was successfully deleted'));

        // Взять из модели счёта id организации
        return $this->redirect(['dict-organization/view', 'id' => $model->organizationId]);
    }

    /**
     * Метод получает id валюты по её коду
     * @param $code
     */
    public function actionGetCurrency($code)
    {
        if (Yii::$app->request->isGet && Yii::$app->request->isAjax) {
            $currency = DictCurrency::findOne(['code' => $code]);

            // Возвращаем id валюты, если она найдена
            if ($currency) {
                return $currency->id;
            }
        }

        return null;
    }

    /**
     * Метод ищет модель счёта в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Сформировать запрос поиска организации в БД
        // через компонент авторизации доступа к терминалам
        $queryOrgs = Yii::$app->terminalAccess->query(DictOrganization::class);
        // Получить в запросе список id организаций
        $organizations = $queryOrgs->select('id')->asArray()->all();
        // Сформировать из результатов запроса массив id организаций 
        $organizationsIds = ArrayHelper::getColumn($organizations, 'id');

        // Сформировать запрос поиска счетов с фильтром по указанными id организаций
        $queryAccount = EdmPayerAccount::find()->where(['organizationId' => $organizationsIds]);

        // Получить роль пользователя из активной сессии
        $userRole = Yii::$app->user->identity->role;
        if (!in_array($userRole, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
            // Если пользователь не админ, добавить в запрос фильтр по id счёта
            // через компонент авторизации доступа к терминиалам
            $queryAccount = Yii::$app->edmAccountAccess->query($queryAccount, 'id');
        }
        // Добавить фильтр по id счёта
        $model = $queryAccount->andWhere(['id' => $id])->one();

        // Если в результате модель не получена, вывести в лог ошибку и бросить исключение
        if ($model === null) {
            Yii::info("Account $id is not found or not accessible by current user");
            throw new NotFoundHttpException();
        }

        return $model;
    }

    protected function findModelScheduledRequestPrevious($id)
    {
        $model = EdmScheduledRequestPrevious::findOne($id);
        if ($model === null) {
            $model = new EdmScheduledRequestPrevious();
    	    $model->accountNumber = $id;
            // Сохранить модель в БД
            $model->save();
        }

        return $model;
    }

    protected function findModelScheduledRequestCurrent($id)
    {
        $model = EdmScheduledRequestCurrent::findOne($id);
        if ($model === null) {
            $model = new EdmScheduledRequestCurrent();
            $model->accountNumber = $id;
            // Сохранить модель в БД
            $model->save();
        }

        return $model;
    }

    /** Получение списка счетов плательщика
     * @param null $q
     * @param null $id
     * @param null $currency
     * @param null $exceptCurrency
     * @return array
     */
    public function actionList(
        $q = null, $id = null, $currency = null, $exceptCurrency = null,
        $organizationId = null, $bankBik = null, $exceptNumber = null
    ) {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];

        if (is_null($id)) {

            // Получаем список организаций, доступных пользователю
            $query = Yii::$app->terminalAccess->query(DictOrganization::className());

            // С учетом отбора по организации
            $query->andFilterWhere(['id' => $organizationId]);

            $organizations = $query->select('id')->asArray()->all();

            // Приводим список организаций пользователя к массиву со списком id
            $organizations = ArrayHelper::getColumn($organizations, 'id');

            // Получаем список счетов плательщика по организации
            $query = EdmPayerAccount::find()->limit(20)->where(['organizationId' => $organizations]);

            // C учетом доступных текущему пользователю счетов
            $query = Yii::$app->edmAccountAccess->query($query, 'id');

            // С учётом исключения конкретного счета
            if (!empty($exceptNumber)) {
                $query->andWhere(['<>', 'number', $exceptNumber]);
            }

            // С учетом отбора по БИК банка
            $query->andFilterWhere(['bankBik' => $bankBik]);

            // Делаем отбор по валюте, если она присутствует
            if (!empty($currency)) {

                // Для валюты рубли включаем как RUB, так и RUR значения
                if ($currency == 1 || $currency == 2) {
                    $currency = [1, 2];
                }

                $query->andFilterWhere(['currencyId' => $currency]);
            }

            // Если есть исключающая валюта, то убираем её из результатов запроса
            if (!empty($exceptCurrency)) {

                // Для валюты рубли исключаем как RUB, так и RUR значения
                if ($exceptCurrency == 1 || $exceptCurrency == 2) {
                    $query->andFilterWhere(['<>', 'currencyId', 1]);
                    $query->andFilterWhere(['<>', 'currencyId', 2]);
                } else {
                    $query->andFilterWhere(['<>', 'currencyId', $exceptCurrency]);
                }
            }

            if (is_numeric($q)) {
                // Поиск по номеру счета
                $query->andfilterWhere(['like', 'number', $q]);
            } else {
                // Поиск по наименованию
                $query->andfilterWhere(['like', 'name', $q]);
            }

            $items = $query->all();

            $out = ['results' => []];
            foreach ($items as $i => $item) {
                $out['results'][$i] = array_merge(
                    $item->getAttributes(),
                    [
                        'bank' => $item->bank->getAttributes(),
                        'contractor' => $item->edmDictOrganization->getAttributes(),
                        'currencyInfo' => $item->edmDictCurrencies->getAttributes()
                    ]
                );
                /**
                 * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
                 */
                $out['results'][$i]['id'] = $out['results'][$i]['number'];
            }
        } else if ($id > 0) {
            $out['results'] = [['id' => $id, 'text' => EdmPayerAccount::findOne($id)->name]];
        }

        return $out;
    }

    public function actionSimpleList($q = null, $id = null)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];

        if (is_null($id)) {

            // Получить список организаций доступных пользователю
            $query = Yii::$app->terminalAccess->query(DictOrganization::className());
            $organizations = $query->select('id')->asArray()->all();

            // Привести список организаций пользователя к массиву со списком id
            $organizations = ArrayHelper::getColumn($organizations, 'id');

            // Получить список счетов плательщика по организации
            $query = EdmPayerAccount::find()->limit(20)->where(['organizationId' => $organizations]);

            // Получить модель пользователя из активной сессии
            $currentUser = Yii::$app->user->identity;

            // C учетом доступных текущему пользователю счетов
            $query = Yii::$app->edmAccountAccess->query($query, 'id');

            if (is_numeric($q)) {
                // Поиск по номеру счета
                $query->andFilterWhere(['like', 'number', $q]);
            } else {
                // Поиск по наименованию
                $query->andFilterWhere(['like', 'name', $q]);
            }

            $items = $query->all();

            $out = ['results' => []];
            foreach ($items as $i => $item) {
                $out['results'][$i] = array_merge(
                    $item->getAttributes(),
                    [
                        'bank' => $item->bank->getAttributes(),
                        'contractor' => $item->edmDictOrganization->getAttributes(),
                        'currencyInfo' => $item->edmDictCurrencies->getAttributes()
                    ]
                );
                /**
                 * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
                 */
                $out['results'][$i]['id'] = $out['results'][$i]['number'];
            }
        } else if ($id > 0) {
            $out['results'] = [['id' => $id, 'text' => EdmPayerAccount::findOne($id)->name]];
        }

        return $out;
    }

    /**
     * Запрос выписки по счету
     * @param null $fromUrl
     * @param null $id
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionSendRequest($fromUrl = null, $id = null, $statementParams = null)
    {
        if (is_null($fromUrl)) {
            $fromUrl = '/edm/documents/statement';
        }

        if (Yii::$app->request->isPost || !empty($statementParams)) {

            $model = new StatementRequestType();
            // Если отправлены POST-данные
            if (Yii::$app->request->isPost) {
                // Загрузить данные модели из формы в браузере
                $model->load(Yii::$app->request->post());
            } else {
                $model->load($statementParams);
            }

            try {
                $account = EdmPayerAccount::findOne(['number' => $model->accountNumber]);
                $model->BIK = $account->bankBik;

                if (!$model->validate()) {
                    throw new BadRequestHttpException($model->getErrorsSummary(true));
                }

                // Получить из БД организацию с указанным id через компонент авторизации доступа к терминалам
                $organization = Yii::$app->terminalAccess->findModel(
                    'addons\edm\models\DictOrganization', $account->organizationId
                );

                $document = EdmHelper::createStatementRequest($model, $organization->terminal);

                // Зарегистрировать событие запрос выписки по счету в модуле мониторинга
                Yii::$app->monitoring->extUserLog(
                    'RequestStatement',
                    ['accountNumber' => $model->accountNumber]
                );

                if ($document->status === Document::STATUS_SERVICE_PROCESSING) {
                    DocumentHelper::waitForDocumentsToLeaveStatus([$document->id], Document::STATUS_SERVICE_PROCESSING);
                    $document->refresh();
                }

                if ($document->status == Document::STATUS_FORSIGNING) {
                    // Перенаправить на страницу индекса
                    return $this->redirect(Url::to(['/edm/documents/signing-index?tabMode=tabStatementRequests']));
                }

                if ($document->status === Document::STATUS_PROCESSING_ERROR) {
                    // Поместить в сессию флаг сообщения об ошибке отправки запроса выписки
                    Yii::$app->session->setFlash('error', Yii::t('edm', 'Failed to send statement request'));
                } else {
                    // Поместить в сессию флаг сообщения об успешной отправке запроса выписки
                    Yii::$app->session->setFlash('info', Yii::t('edm', 'Statement request was sent'));
                }
            } catch (\Exception $ex) {
                Yii::info("Failed to send statement request, caused by: $ex");
                // Поместить в сессию флаг сообщения об ошибке отправки запроса выписки
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
        }

        if (!is_null($id)) {
            // Перенаправить на предыдущую страницу с передачей id
            return $this->redirect([$fromUrl, 'id' => $id]);
        } else {
            // Перенаправить на предыдущую страницу
            return $this->redirect([$fromUrl]);
        }
    }

    /**
     * Запрос по всем счетам, доступным текущему пользователю
     */
    public function actionSendRequestAllAccounts()
    {
        $queryOrganizations = Yii::$app->terminalAccess->query(DictOrganization::className());
        $queryOrganizations->select('id')->asArray();
        $organizations = $queryOrganizations->all();

        $query = EdmPayerAccount::find();
        $query->where(['organizationId' => ArrayHelper::getColumn($organizations, 'id')]);

        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, 'id');

        // Получение всех счетов, доступных пользователю
        $accounts = $query->all();

        /** @var Document[] $documents */
        $documents = [];
        foreach($accounts as $account) {
            // Формирование запроса выписки по каждому счету
            $model = new StatementRequestType();
            $model->startDate = date('Y-m-d');
            $model->endDate = date('Y-m-d');
            $model->accountNumber = $account->number;

            $document = null;
            try {
                $model->BIK = $account->bankBik;

                if (!$model->validate()) {
                    throw new BadRequestHttpException($model->getErrorsSummary(true));
                }

                // Получить из БД организацию с указанным id через компонент авторизации доступа к терминалам
                $organization = Yii::$app->terminalAccess->findModel(
                    'addons\edm\models\DictOrganization', $account->organizationId
                );

                $document = EdmHelper::createStatementRequest($model, $organization->terminal);

                // Зарегистрировать событие запрос выписки по счету в модуле мониторинга
                Yii::$app->monitoring->extUserLog(
                    'RequestStatement',
                    ['accountNumber' => $model->accountNumber]
                );

                // Поместить в сессию флаг сообщения об успешной отправке запроса выписки
                Yii::$app->session->setFlash('info', Yii::t('edm', 'Statement request was sent'));
            } catch (\Exception $ex) {
                $document = null;
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->setFlash('error', $ex->getMessage());
            }
            if (!empty($document)) {
                $documents[] = $document;
            }
        }

        DocumentHelper::waitForDocumentsToLeaveStatus([$document->id], Document::STATUS_SERVICE_PROCESSING);
        $documentsForSigningIds = [];
        foreach ($documents as $document) {
            $document->refresh();
            if ($document->status === Document::STATUS_FORSIGNING) {
                $documentsForSigningIds[] = $document->id;
            }
        }

        $redirectUrl = empty($documentsForSigningIds)
            ? Yii::$app->request->referrer
            : Url::to(['/edm/documents/signing-index?tabMode=tabStatementRequests']);

        // Перенаправить на страницу перенаправления
        return $this->redirect($redirectUrl);
    }

    /**
     * Получение данных swiftbank по банку счета
     */
    public function actionGetSwiftbicOrganization($accountNumber)
    {
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = [];

        // Поиск счета по номеру
        $account = EdmPayerAccount::findOne(['number' => $accountNumber]);

        if (!$account) {
            return $result;
        }

        // Получение банка, которому принадлежит счет
        $bank = $account->bank;

        if (!$bank) {
            return $result;
        }

        $terminalId = Address::truncateAddress($bank->terminalId);

        // Получение swift bic
        $swiftBic = SwiftFinDictBank::findOne(['fullCode' => $terminalId]);

        if (!$swiftBic) {
            return $result;
        }

        $result['bic'] = $swiftBic->swiftCode;
        $result['branchCode'] = $swiftBic->branchCode;
        $result['name'] = $swiftBic->name;
        $result['address'] = $swiftBic->address;

        return $result;
    }

    /**
     * Получение данных по отдельному счету
     * @param $number
     * @return array|null|ActiveRecord
     * @throws MethodNotAllowedHttpException
     */
    public function actionGetAccountData($number)
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = EdmPayerAccount::find()->andWhere(['number' => $number])->asArray();

        return $query->one();
    }

    public function actionUpdateExportSettings($id)
    {
        // Получить из БД счёт с указанным id
        $model = $this->findModel($id);
        $isUpdated = false;
        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Сохранить модель в БД
            $isUpdated = $model->save();
        }

        if ($isUpdated) {
            // Поместить в сессию флаг сообщения об успешном сохранении настроек
            Yii::$app->session->setFlash('success', Yii::t('app', 'Settings have been updated'));
        } else {
            Yii::info('Failed to save account export settings, errors: ' . var_export($model->getErrors(), true));
            // Поместить в сессию флаг сообщения об ошибке сохранения настроек
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to update settings'));
        }

        // Перенаправить на страницу просмотра
        $this->redirect(['view', 'id' => $model->id]);
    }

}

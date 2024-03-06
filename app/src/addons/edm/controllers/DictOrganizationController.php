<?php
namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictOrganizationSearch;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountSearch;
use addons\edm\models\EdmPayerAccountUser;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use common\models\Terminal;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\Response;

/**
 * Контроллер для работы со справочником организаций
 * Class DictBankController
 * @package addons\edm\controllers
 */
class DictOrganizationController extends BaseServiceController
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['get-organization-data', 'get-banks', 'get-accounts'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['commonSettings'],
                    ],
                ],
            ],
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ],
            ],
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     * с журналом всех организаций
     */
    public function actionIndex()
    {
        $searchModel = new DictOrganizationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Вывести страницу
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создание новой организации
     */
    public function actionCreate()
    {
        $model = new DictOrganization();
        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            // Обработка записи нового элемента справочника организаций
            // Если данные модели успешно загружены из формы в браузере
            if ($model->load(Yii::$app->request->post())) {
                // Если модель успешно сохранена в БД
                if ($model->save()) {
                    // Поместить в сессию флаг сообщения об успешном добавлении организации
                    Yii::$app->session->setFlash('success', Yii::t('edm', 'The new organization was successfully added'));
                    // Получить id созданной записи, чтобы посмотреть её через view
                    $modelView = DictOrganization::findOne(['terminalId' => $model->terminalId]);
                    // Перенаправить на страницу просмотра
                    return $this->redirect(['view', 'id' => $modelView->id]);
                }
            }
            // Поместить в сессию флаг сообщения об ошибке добавления организации
            Yii::$app->session->setFlash('error', Yii::t('edm', 'New organization adding failed'));
        }

        // Вывести страницу
        return $this->render('create', ['model' => $model]);
    }

    /**
     * Редактирование существующей организации
     */
    public function actionUpdate($id)
    {
        // Получить из БД запись организации с указанным id
        $model = $this->findModel($id);

        // Если данные модели успешно загружены из формы в браузере
        if ($model->load(Yii::$app->request->post())) {
            // Если модель успешно сохранена в БД
            if ($model->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении организации
                Yii::$app->session->setFlash('success', Yii::t('edm', 'The organization was successfully updated'));
                // Перенаправить на страницу просмотра
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        // Вывести страницу
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Просмотр организации
     */
    public function actionView($id)
    {
        $accountsModel = new EdmPayerAccountSearch();
        $accountsDataProvider = $accountsModel->search(
            Yii::$app->request->queryParams + ['organizationId' => $id]
        );

        // Вывести страницу
        return $this->render('view', [
            // Получить из БД запись организации с указанным id
            'model' => $this->findModel($id),
            'accountsModel' => $accountsModel,
            'accountsDataProvider' => $accountsDataProvider
        ]);
    }

    /**
     * Удаление организации
     * @param $id
     * @return Response
     */
    public function actionDelete($id)
    {
        // Получить из БД запись организации с указанным id и удалить её
        $this->findModel($id)->delete();
        // Поместить в сессию флаг сообщения об успешном удалении организации
        Yii::$app->session->setFlash('success', Yii::t('edm', 'The organization was successfully deleted'));
        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Метод для ajax-получения названия терминала по его id
     */
    public function actionGetTerminalTitle($id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException('404','Method not found');
        }

        $terminal = Terminal::findOne($id);

        if (!$terminal) {
            return '';
        }

        return $terminal->title ?: $terminal->terminalId;
    }

    // Метод для ajax-получения объекта организации по его id
    public function actionGetOrganizationData($id)
    {
        if (!Yii::$app->request->isAjax) {
            throw new HttpException('404','Method not found');
        }

        // Получить из БД запись организации с указанным id
        $model = $this->findModel($id);

        // Преобразвание объекта организации в массив
        // для более удобного формирования json ответа
        $modelArray = ArrayHelper::toArray($model);

        // Преобразование в json и возврат ответа
        return json_encode($modelArray);
    }

    /**
     * Метод ищет модель в БД по первичному ключу.
     * @return DictOrganization
     */
    protected function findModel($id)
    {
        // Получить из БД запись организации с указанным id
        // через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(DictOrganization::className(), $id);
    }

    /**
     * Получение banks по организации
     */
    public function actionGetBanks($orgId)
    {
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];
        $bankBiks = [];
        $accountId = null;
        $userAllowedAccounts = EdmPayerAccountUser::getUserAllowAccounts(Yii::$app->user->identity->id);

        try {
            if (strpos($orgId, '_') !== false) {
                list($orgId, $accountId) = explode('_', $orgId);
                $account = EdmPayerAccount::find()
                    ->where(['id' => $accountId])
                    ->andWhere(['id' => $userAllowedAccounts])
                    ->andWhere(['organizationId' => $orgId])
                    ->one();
                if ($account) {
                    // find other accounts with same payer name
                    $bankBiks[$account->bankBik] = true;
                    $accounts = EdmPayerAccount::find()
                        ->where(['payerName' => $account->payerName])
                        ->andWhere(['organizationId' => $orgId])
                        ->andWhere(['!=', 'id', $accountId])
                        ->andWhere(['id' => $userAllowedAccounts])
                        ->all();
                    foreach($accounts as $acc) {
                        $bankBiks[$acc->bankBik] = true;
                    }
                }
            }
            // Получить из БД модель организации с указанным id
            $organization = $this->findModel($orgId);
            $banks = $accountId
                ? $organization->getBanks(array_keys($bankBiks))
                : $organization->getBanksWithoutPayerName(array_keys($bankBiks));
            foreach($banks as $bank) {
                $out[$bank->bik] = $bank->name;
            }
        } catch(Exception $ex) {
            Yii::info('Exception while getting bank list for organization ' . $orgId
                    . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ':' . $ex->getLine());
        }

        return $out;
    }

    /**
     * Получение счетов по организации
     */
    public function actionGetAccounts($orgId, $bankBik = null)
    {
        if (!Yii::$app->request->isAjax) {
            throw new ForbiddenHttpException();
        }

        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;

        $out = [];
        $accountId = null;
        $payerName = null;
        $userAllowedAccounts = EdmPayerAccountUser::getUserAllowAccounts(Yii::$app->user->identity->id);

        try {
            if (strpos($orgId, '_') !== false) {
                list($orgId, $accountId) = explode('_', $orgId);
                $account = EdmPayerAccount::find()
                    ->where(['id' => $accountId])
                    ->andWhere(['id' => $userAllowedAccounts])
                    ->andWhere(['organizationId' => $orgId])
                    ->one();
                if ($account) {
                    $payerName = $account->payerName;
                }
            }
            // Получить из БД модель организации с указанным id
            $organization = $this->findModel($orgId);

            $accounts = $accountId
                ? $organization->getBankAccounts($bankBik, $payerName, $userAllowedAccounts)
                : $organization->getBankAccountsWithoutPayerName($bankBik, $userAllowedAccounts);

            foreach($accounts as $account) {
                $out[$account->id] = "{$account->name}, {$account->number}, {$account->edmDictCurrencies->name}";
            }
        } catch(Exception $ex) {
            Yii::info('Exception while getting account list for organization ' . $orgId
                    . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ':' . $ex->getLine());
        }

        return $out;
    }
}

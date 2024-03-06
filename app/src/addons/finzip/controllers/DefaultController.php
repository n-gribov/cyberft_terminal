<?php

namespace addons\finzip\controllers;

use addons\finzip\FinZipModule;
use addons\finzip\models\FinZipSearch;
use common\base\BaseServiceController;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\UserHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class DefaultController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => FinZipModule::SERVICE_ID],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        $searchModel = new FinZipSearch();
        $model       = new Document();
        Url::remember();

        // Вывести страницу
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'model'        => $model,
            'urlParams'    => $this->getSearchUrl('FinZipSearch'),
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'finzipIndex',
        ]);
    }

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => FinZipModule::SERVICE_ID,
        ];
        return $actions;
    }

    /**
     * Метод для показа родных моделей по id
     * @param int $id finzip id
     * @param string $mode render mode
     */
    public function actionView($id, $mode = '')
    {
        $model = $this->findModel($id);

        // Зарегистрировать событие просмотра документа
        // только если это новый просмотр (т.е., не переход по вкладкам)

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

        return $this->viewModel($model, $mode, $id);
    }

    protected function viewModel(FinZipSearch $model, $mode, $routeId)
    {
        $referencingDataProvider = new ActiveDataProvider([
            'query' => $model->findReferencingDocuments(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Вывести страницу
        return $this->render('view', [
            'model' => $model,
            'mode' => $mode,
            'referencingDataProvider' => $referencingDataProvider,
            'urlParams' => $this->getSearchUrl('FinZipSearch'),
        ]);
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(FinZipSearch::className(), $id);
    }

}

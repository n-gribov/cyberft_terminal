<?php

namespace addons\fileact\controllers;

use addons\fileact\FileActModule;
use addons\fileact\models\FileActSearch;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\UserHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\base\BaseServiceController;

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
                        'roleParams' => [
                            'serviceId' => FileActModule::SERVICE_ID,
                        ],
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

    public function actions()
    {
        $actions = parent::actions();
        $actions['delete'] = [
            'class' => 'common\actions\documents\DeleteAction',
            'serviceId' => FileActModule::SERVICE_ID,
        ];

        return $actions;
    }

    /**
     * Метод обрабатывает страницу индекса
     * со списком FileAct-документов
     */
    public function actionIndex()
    {
        $searchModel = new FileActSearch();
        $model       = new Document();

        // Вывести страницу
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'model'        => $model,
            'urlParams'    => $this->getSearchUrl('FileActSearch'),
            'dataProvider' => $searchModel->search(Yii::$app->request->queryParams),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'fileactIndex',
        ]);
    }

    /**
     * Метод для показа родных моделей по id
     * @param int $id fileact id
     * @param string $mode render mode
     */
    public function actionView($id, $mode = '')
    {
        // Получить из БД документ с указанным id
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

        return $this->viewModel($model, $mode);
    }

    protected function viewModel(Document $document, $mode)
    {
        $referencingDataProvider = new ActiveDataProvider([
            'query' => $document->findReferencingDocuments(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Вывести страницу
        return $this->render('view', [
            'model' => $document,
            'mode' => $mode,
            'referencingDataProvider' => $referencingDataProvider,
            'urlParams'    => $this->getSearchUrl('FileActSearch'),
        ]);
    }

    /**
     * Метод ищет модель документа в БД по первичному ключу.
     * Если модель не найдена, выбрасывается исключение HTTP 404
     */
    protected function findModel($id)
    {
        // Получить из БД документ с указанным id через компонент авторизации доступа к терминалам
        return Yii::$app->terminalAccess->findModel(FileActSearch::className(), $id);
    }
}

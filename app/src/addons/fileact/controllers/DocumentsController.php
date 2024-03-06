<?php

namespace addons\fileact\controllers;

use addons\fileact\FileActModule;
use addons\fileact\models\FileActSearch;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;

class DocumentsController extends BaseServiceController
{
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
                            'serviceId' => FileActModule::SERVICE_ID,
                        ],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['delete'] // access is checked in common\actions\documents\DeleteAction
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

    public function actionSigningIndex()
    {
        $filterModel	 = new FileActSearch();
        $dataProvider	 = $filterModel->searchForSigning(Yii::$app->request->queryParams);

        Url::remember(Url::to());

        // Вывести страницу
        return $this->render('forsigning', [
            'title' => Yii::t('app/menu', 'Documents for signing'),
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
            'urlParams'    => $this->getSearchUrl('FileActSearch'),
            'filterStatus' => !empty(Yii::$app->request->queryParams),
            'listType' => 'fileactSigning',
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

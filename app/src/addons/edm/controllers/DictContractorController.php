<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\models\DictContractor;
use addons\edm\models\DictContractorSearch;
use common\base\BaseServiceController;
use common\document\DocumentPermission;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Response;

class DictContractorController extends BaseServiceController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list','view'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => [DocumentPermission::CREATE],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ]
				],
			],
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
	}

	public function beforeAction($action)
	{
		if (Yii::$app->request->get('emptyLayout')) {
			$this->layout = '@backend/views/layouts/empty';
		}

		return parent::beforeAction($action);
	}

    public function actionIndex()
    {
        $searchModel = new DictContractorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
		if (Yii::$app->request->get('emptyLayout')) {
			return $this->render('_view', [
				'model' => $this->findModel($id),
			]);
		} else {
			return $this->render('view', [
				'model' => $this->findModel($id),
			]);
		}
    }

    public function actionCreate()
    {
        $model = new DictContractor();

        if ($model->load(Yii::$app->request->post())) {
            $model->terminalId = Yii::$app->terminals->defaultTerminal->id;
            if ($model->save()) {
                // Регистрация события добавления нового контрагента
                Yii::$app->monitoring->extUserLog('AddNewEdmContractor', ['id' => $model->id]);

                if (Yii::$app->request->getIsPjax()) {
                    return $this->renderPartial('_view', ['model' => $model]);
                } else if (Yii::$app->request->get('emptyLayout')) {
                    return $this->redirect(['view', 'id' => $model->id, 'emptyLayout' => 1]);
                } else {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        if (Yii::$app->request->getIsPjax()) {
            return $this->renderPartial('_form', ['model' => $model]);
        } else if (Yii::$app->request->get('emptyLayout')) {
            return $this->render('_form', ['model' => $model]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->terminalId = Yii::$app->terminals->defaultTerminal->id;
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

	public function actionList($q = null, $role = null, $id = null)
	{
		\Yii::$app->response->format = Response::FORMAT_JSON;

		if (is_null($id)) {
            $query = Yii::$app->terminalAccess->query(DictContractor::className())->limit(20);

			if (is_numeric($q)) {
				$query->filterWhere(['like', 'account', $q]);
			} else {
				$query->filterWhere(['like', 'name', $q]);
			}

			if ($role) {
				$query->andWhere(['role' => [$role, '']]);
			}

			/** @var DictContractor[] $items */
			$items = $query->all();

			$out = ['results' => []];
			foreach ($items as $i => $item) {
				$out['results'][$i] = array_merge(
					$item->getAttributes(), [
						'bank' => $item->bank->getAttributes()
					]
				);
				/**
				 * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
				 */
				$out['results'][$i]['id'] = $out['results'][$i]['account'];
			}
		} else if ($id > 0) {
			$out['results'] = [['id' => $id, 'text' => DictContractor::find($id)->name]];
		}

		return $out;
	}

    protected function findModel($id)
    {
        return Yii::$app->terminalAccess->findModel(DictContractor::className(), $id);
    }

}

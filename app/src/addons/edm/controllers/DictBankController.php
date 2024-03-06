<?php

namespace addons\edm\controllers;

use addons\edm\EdmModule;
use addons\edm\helpers\DictBankHelper;
use common\document\DocumentPermission;
use Yii;
use addons\edm\models\DictBank;
use addons\edm\models\DictBankSearch;
use yii\filters\AccessControl;
use common\base\BaseServiceController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use common\modules\certManager\models\Cert;

class DictBankController extends BaseServiceController
{
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'list', 'view'],
                        'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*'],
                    ],
					[
						'allow' => true,
						'roles' => ['admin'],
					],
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

    public function actionIndex()
    {
        $searchModel = new DictBankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	public function actionSyncFile()
    {
		if (!Yii::$app->request->getIsPost()) {
			return $this->redirect('index');
		}

		$errors = [];
		$log    = [];
		$file   = UploadedFile::getInstanceByName('file');

		if (!$file) {
			$errors['file'] = Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('app', 'File')]);
		} else if (!in_array($file->type, ['application/x-zip-compressed', 'application/zip', 'application/octet-stream'])) {
			$errors['file'] = Yii::t('app/error', 'Invalid source file format');
		} else {
			try {
				$log = DictBankHelper::syncFile($file->tempName);

                // Регистрация события загрузки справочника банков
                Yii::$app->monitoring->extUserLog('UploadEdmBanks');

			} catch (\Exception $e) {
				$errors['file'] = $e->getMessage();
			}
		}

		if (!empty($errors)) {
			$searchModel = new DictBankSearch();
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

			return $this->render('index', [
				'searchModel' => $searchModel,
				'dataProvider' => $dataProvider,
				'errors' => $errors,
			]);
		} else {
			return $this->render('sync', [
				'log' => $log,
			]);
		}
	}

	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		$certs = Cert::find()->with('participant')->where(['role' => Cert::ROLE_SIGNER_BOT])->all();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		} else {
			return $this->render('update', [
				'model' => $model,
				'certs' => $certs
			]);
		}
	}

	public function actionList($q = null, $id = null)
    {
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		$out = ['results' => [['id' => '', 'text' => '']]];
		if (!is_null($q)) {
			$query = DictBank::find()
				//->from(DictBank::tableName())
				->where(['like', 'bik', $q])
				->orWhere(['like', 'name', $q])
				->limit(20);

			/** @var DictBank[] $items */
			$items = $query->all();

			$out = ['results' => []];
			foreach ($items as $i => $item) {
				$out['results'][$i] = $item->getAttributes();
				/**
				 * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
				 */
				$out['results'][$i]['id'] = $out['results'][$i]['bik'];
			}
		} else if ($id > 0) {
			$out['results'] = ['id' => $id, 'text' => DictBank::find($id)->name];
		}

		return $out;
	}

    protected function findModel($id)
    {
        if (($model = DictBank::findOne(['bik' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

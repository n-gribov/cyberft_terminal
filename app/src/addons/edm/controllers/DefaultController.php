<?php
namespace addons\edm\controllers;

use addons\edm\EdmModule;
use common\document\DocumentPermission;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\base\BaseServiceController;

class DefaultController extends BaseServiceController
{
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => [DocumentPermission::VIEW],
                        'roleParams' => ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => '*']
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

	/**
	 * Lists all Edm models.
	 * @return mixed
	 */
	public function actionIndex()
	{
        return $this->render('index');
//        return $this->redirect(['/edm/documents']);
	}
}

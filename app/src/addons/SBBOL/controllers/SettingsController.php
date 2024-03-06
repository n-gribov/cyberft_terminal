<?php

namespace addons\SBBOL\controllers;

use common\base\BaseServiceController;
use Yii;
use yii\filters\AccessControl;

class SettingsController extends BaseServiceController
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
		];
	}

	public function actionIndex()
	{
        if (!empty(Yii::$app->request->isPost)) {
        	$formName = $this->module->settings->formName();

            $attributes = Yii::$app->request->post($formName);
            /**
             * Fix for autotest: при вызове из теста с выключенным чекбоксом в посте не приходит поле совсем,
             * поэтому в setAttributes оно не попадает и не обновляется
             */

            $this->module->settings->setAttributes($attributes);

            if ($this->module->settings->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
            }
        }

        return $this->render('index', [
			'settings' => $this->module->settings,
		]);
	}

}

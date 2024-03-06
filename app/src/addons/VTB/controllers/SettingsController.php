<?php

namespace addons\VTB\controllers;

use common\base\BaseServiceController;
use common\models\CryptoproCertSearch;
use common\models\CryptoproKeySearch;
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
						'roles' => ['documentManage'],
					],
				],
			],
		];
	}

	/**
	 * @return mixed
	 */

	public function actionIndex()
	{
        $certModel = CryptoproCertSearch::getInstance('VTB');
        $keysModel = new CryptoproKeySearch();

        if (Yii::$app->request->isPost) {
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

        // Запись ссылки настроек для верного
        // отображения кнопки назад на странице настроек сертификата КриптоПро
        Yii::$app->cache->set('crypto-pro-back-link' . Yii::$app->session->id, Yii::$app->request->url);

        return $this->render('index', [
            'defaultTab' => 'tabCommon',
            'settings' => $this->module->settings,
            'cryptoproKeys' => $keysModel->search(Yii::$app->request->post()),
            'cryptoproKeysSearch' => $keysModel,
            'cryptoproCert' => $certModel->search(Yii::$app->request->post()),
            'cryptoproCertSearch' => $certModel,
        ]);
	}
}

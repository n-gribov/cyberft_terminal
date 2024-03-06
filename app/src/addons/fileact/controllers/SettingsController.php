<?php

namespace addons\fileact\controllers;

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

	public function actionIndex()
	{
        $certModel = CryptoproCertSearch::getInstance('fileact');
        $keysModel = new CryptoproKeySearch();

        $settings = $this->module->settings;
        $enableCryptoProSign = $settings->enableCryptoProSign;
        $useSerial = $settings->useSerial;

        if (!empty(Yii::$app->request->isPost)) {
          	$formName = $this->module->settings->formName();
            $this->module->settings->setAttributes(Yii::$app->request->post($formName));

            if ($this->module->settings->save()) {

                if (!$enableCryptoProSign && $this->module->settings->enableCryptoProSign) {
                    // Регистрация события изменения настройки использования подписания CryptoPro
                    Yii::$app->monitoring->extUserLog('ActivateFileactCryptoProSigning');
                } elseif ($enableCryptoProSign && !$this->module->settings->enableCryptoProSign) {
                    // Регистрация события изменения настройки использования подписания CryptoPro
                    Yii::$app->monitoring->extUserLog('DeactivateFileactCryptoProSigning');
                }

                if (!$useSerial && $this->module->settings->useSerial) {
                    // Регистрация события изменения настройки использования серийного номера сертификата вместо отпечатка
                    Yii::$app->monitoring->extUserLog('ActivateFileactUseSerialNumberCert');
                } elseif ($useSerial && !$this->module->settings->useSerial) {
                    // Регистрация события изменения настройки использования серийного номера сертификата вместо отпечатка
                    Yii::$app->monitoring->extUserLog('DeactivateFileactUseSerialNumberCert');
                }

                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
            }
        }

        // Запись ссылки настроек для верного
        // отображения кнопки назад на странице настроек сертификата КриптоПро
        Yii::$app->cache->set('crypto-pro-back-link' . Yii::$app->session->id, Yii::$app->request->url);

        return $this->render('index', [
			'settings' => $this->module->settings,
            'cryptoproKeys' => $keysModel->search(Yii::$app->request->post()),
            'cryptoproKeysSearch' => $keysModel,
            'cryptoproCert' => $certModel->search(Yii::$app->request->post()),
            'cryptoproCertSearch' => $certModel,
		]);
	}

}

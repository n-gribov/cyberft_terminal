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

    /**
     * Метод обрабатывает страницу индекса
     */
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

            // Если модель успешно сохранена в БД
            if ($this->module->settings->save()) {
                if (!$enableCryptoProSign && $this->module->settings->enableCryptoProSign) {
                    // Зарегистрировать событие изменения настройки использования подписания CryptoPro в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('ActivateFileactCryptoProSigning');
                } else if ($enableCryptoProSign && !$this->module->settings->enableCryptoProSign) {
                    // Зарегистрировать событие изменения настройки использования подписания CryptoPro в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('DeactivateFileactCryptoProSigning');
                }

                if (!$useSerial && $this->module->settings->useSerial) {
                    // Зарегистрировать событие изменения настройки
                    // использования серийного номера сертификата вместо отпечатка в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('ActivateFileactUseSerialNumberCert');
                } else if ($useSerial && !$this->module->settings->useSerial) {
                    // Зарегистрировать событие изменения настройки
                    // использования серийного номера сертификата вместо отпечатка в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('DeactivateFileactUseSerialNumberCert');
                }

                // Поместить в сессию флаг сообщения об успешном сохранении настроек
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке сохранения настроек
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
            }
        }

        // Запись ссылки настроек для верного
        // отображения кнопки назад на странице настроек сертификата КриптоПро
        Yii::$app->cache->set('crypto-pro-back-link' . Yii::$app->session->id, Yii::$app->request->url);

        // Вывести страницу
        return $this->render('index', [
            'settings' => $this->module->settings,
            'cryptoproKeys' => $keysModel->search(Yii::$app->request->post()),
            'cryptoproKeysSearch' => $keysModel,
            'cryptoproCert' => $certModel->search(Yii::$app->request->post()),
            'cryptoproCertSearch' => $certModel,
        ]);
    }
}

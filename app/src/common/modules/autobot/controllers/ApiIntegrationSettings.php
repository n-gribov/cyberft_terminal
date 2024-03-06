<?php

namespace common\modules\autobot\controllers;

use common\models\Terminal;
use common\modules\api\models\AccessToken;
use common\settings\AppSettings;
use Yii;

trait ApiIntegrationSettings
{
    protected function apiIntegrationSettings(?Terminal $terminal = null): array
    {
        /** @var AppSettings $settings */
        $globalSettings = Yii::$app->settings->get('app');

        /** @var AppSettings $terminalSettings */
        $terminalSettings = $terminal
            ? Yii::$app->settings->get('app', $terminal->terminalId)
            : null;

        $settings = $terminal ? $terminalSettings : $globalSettings;

        // Если данные модели успешно загружены из формы в браузере
        if (Yii::$app->request->isPost && $settings->load(Yii::$app->request->post())) {
            if ((!$settings->encryptedApiSecret) || (Yii::$app->request->post('generate-new-token') === '1') ) {
                $accessToken = AccessToken::generate($terminal ? $terminal->terminalId : null);
                $settings->apiSecret = $accessToken->secret();
            }

            // Сохранить модель в БД
            $isSaved = $settings->save();
            if ($isSaved) {
                // Поместить в сессию флаг сообщения об успешном сохранении настроек
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings updated'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке сохранения настроек
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings update error'));
            }
        }

        return compact('terminalSettings', 'globalSettings');
    }
}

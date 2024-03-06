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

        if (Yii::$app->request->isPost && $settings->load(Yii::$app->request->post())) {
            if ((!$settings->encryptedApiSecret) || (Yii::$app->request->post('generate-new-token') === '1') ) {
                $accessToken = AccessToken::generate($terminal ? $terminal->terminalId : null);
                $settings->apiSecret = $accessToken->secret();
            }

            $isSaved = $settings->save();
            if ($isSaved) {
                Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings updated'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings update error'));
            }
        }

        return compact('terminalSettings', 'globalSettings');
    }
}

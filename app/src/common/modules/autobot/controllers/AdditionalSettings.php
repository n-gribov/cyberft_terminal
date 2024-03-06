<?php

namespace common\modules\autobot\controllers;

use Yii;
use common\models\UserTerminal;
use common\models\User;

trait AdditionalSettings
{
    /**
     * Дополнительные настройки терминалов
     */
    protected function additionalSettingsData($terminal = null)
    {
		$data = [
			'encodingList' => ['' => Yii::t('app/settings', 'default'), 'cp866' => 'dos', 'cp1251' => 'windows', 'utf8' => 'unicode']
		];

        $terminalList = Yii::$app->terminals->addresses;

        if ($terminal) {
            $terminalId = $terminal->terminalId;
        } else {
            $terminalId = Yii::$app->request->get('terminalId');
        }

        if (!in_array($terminalId, $terminalList)) {
            $terminalId = null;
        }

        if (Yii::$app->request->isPost) {
            $settings = [];
            $terminalId = Yii::$app->request->post('terminalId');
            if (!in_array($terminalId, $terminalList)) {
                $terminalId = null;
            }

            if ($terminalId) {
                $settings[$terminalId] = Yii::$app->settings->get('app', $terminalId);
            } else {
                $adminIdentity = Yii::$app->user->identity;
                $adminTerminals = UserTerminal::getUserTerminalIds($adminIdentity->id);

                foreach($terminalList as $termId) {
                    if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN &&
                        !in_array($termId, $adminTerminals)
                    ) {
                        continue;
                    }

                    $settings[$termId] = Yii::$app->settings->get('app', $termId);
                }
                /**
                 * Добавляем также settings с terminalId = null (дефолтный для всех терминалов)
                 */
                $settings['default'] = Yii::$app->settings->get('app');
            }

            $count = 0;
            foreach($settings as $termId => $model) {
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    $count++;
                    // Регистрация события изменения настроек безопасности
                    Yii::$app->monitoring->extUserLog('EditGeneralSettings', ['terminalId' => $termId]);
                }
            }

            if ($count > 0) {
                Yii::$app->session->setFlash('success', Yii::t('app/user', 'Settings updated'));
            }

            $data['model'] = $model;
            $data['terminalId'] = $terminalId;

            return $data;
        }

        $model = Yii::$app->settings->get('app', $terminalId);

        $data['model'] = $model;
        $data['terminalId'] = $terminalId;

        return $data;
    }

}
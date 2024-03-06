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

        $terminalList = Yii::$app->exchange->addresses;

        if ($terminal) {
            $terminalId = $terminal->terminalId;
        } else {
            $terminalId = Yii::$app->request->get('terminalId');
        }

        if (!in_array($terminalId, $terminalList)) {
            $terminalId = null;
        }

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            $settings = [];
            $terminalId = Yii::$app->request->post('terminalId');
            if (!in_array($terminalId, $terminalList)) {
                $terminalId = null;
            }

            if ($terminalId) {
                $settings[$terminalId] = Yii::$app->settings->get('app', $terminalId);
            } else {
                // Получить модель пользователя из активной сессии
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
                // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    $count++;
                    // Зарегистрировать событие изменения настроек безопасности в модуле мониторинга
                    Yii::$app->monitoring->extUserLog('EditGeneralSettings', ['terminalId' => $termId]);
                }
            }

            if ($count > 0) {
                // Поместить в сессию флаг сообщения об успешном сохранении настроек
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

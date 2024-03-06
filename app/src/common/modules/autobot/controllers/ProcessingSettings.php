<?php

namespace common\modules\autobot\controllers;

use common\models\Processing;
use common\modules\autobot\models\ProcessingSettingsForm;
use common\settings\AppSettings;
use Yii;

trait ProcessingSettings
{
    /**
     * Получение данных по настройкам соединения с процессингом
     * @return array
     */
    protected function processingSettingsData()
    {
        $settingsForm = new ProcessingSettingsForm();
        $terminals = Yii::$app->exchange;

        $runningTerminals = [];

        foreach ($terminals->addresses as $terminalId) {
            if ($terminals->isRunning($terminalId)) {
                $runningTerminals[] = $terminalId;
            }
        }

        $availableProcessings = Processing::find()->orderBy('id')->all();

        // Если отправлены POST-данные
        if (Yii::$app->request->isPost) {
            if (count($runningTerminals) > 0) {
                // Поместить в сессию флаг сообщения об ошибке сохранения настроек
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings update error'));
            } else {
                // Если данные модели успешно загружены из формы в браузере и модель сохранилась в БД
                if ($settingsForm->load(Yii::$app->request->post()) && $settingsForm->save()) {
                    // Поместить в сессию флаг сообщения об успешном сохранении настроек
                    Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings updated'));
                } else {
                    // Поместить в сессию флаг сообщения об ошибке сохранения настроек
                    Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings update error'));
                }
            }
        }

        return compact('settingsForm', 'runningTerminals', 'availableProcessings');
    }

    /**
     * Сброс настроек подключения к процессингу
     */
    public function actionProcessingSettingsReload()
    {
        $terminals = Yii::$app->exchange;
        foreach ($terminals->addresses as $terminalId) {
            if ($terminals->isRunning($terminalId)) {
                // Поместить в сессию флаг сообщения об ошибке сброса настроек
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings reset error'));
                // Перенаправить на страницу индекса
                return $this->redirect(['index', 'tabMode' => 'tabProcessingSettings']);
            }
        }

        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $appSettings->saveDefaultSettings();

        // Поместить в сессию флаг сообщения об успешном сбросе настроек
        Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings reset'));
        // Перенаправить на страницу индекса
        return $this->redirect(['index', 'tabMode' => 'tabProcessingSettings']);
    }
}
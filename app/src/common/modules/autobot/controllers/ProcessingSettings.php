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
        $terminals = Yii::$app->terminals;

        $runningTerminals = [];

        foreach ($terminals->addresses as $terminalId) {
            if ($terminals->isRunning($terminalId)) {
                $runningTerminals[] = $terminalId;
            }
        }

        $availableProcessings = Processing::find()->orderBy('id')->all();

        if (Yii::$app->request->isPost) {
            if (count($runningTerminals) > 0) {
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings update error'));
            } else {
                if ($settingsForm->load(Yii::$app->request->post()) && $settingsForm->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings updated'));
                } else {
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
        $terminals = Yii::$app->terminals;
        foreach ($terminals->addresses as $terminalId) {
            if ($terminals->isRunning($terminalId)) {
                Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Settings reset error'));
                return $this->redirect(['index', 'tabMode' => 'tabProcessingSettings']);
            }
        }

        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        $appSettings->saveDefaultSettings();

        Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Settings reset'));
        return $this->redirect(['index', 'tabMode' => 'tabProcessingSettings']);
    }
}
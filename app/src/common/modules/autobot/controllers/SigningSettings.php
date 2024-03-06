<?php

namespace common\modules\autobot\controllers;

use common\base\interfaces\SigningInterface;
use common\modules\autobot\models\ParticipantAutobots;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;

trait SigningSettings
{
    protected function signingData($terminal)
    {
        $data = [];

        $terminalId = $terminal->terminalId;

        $addons = Yii::$app->addon->getRegisteredAddons();
        $settingsList = [];

        foreach ($addons as $serviceId => $addon) {
            // изолировать подписание ISO20022, CYB-3739
            if ($addon instanceof SigningInterface && $serviceId != 'ISO20022') {
                $settingsList[$serviceId] = $addon->getSettings($terminalId);
            }
        }

        $terminalSettings = Yii::$app->settings->get('app', $terminalId);

        if (Yii::$app->request->isPost) {
            $signings = Yii::$app->request->post('qty');
            $autoSignings = Yii::$app->request->post('autoSigning', []);

            $usePersonalAddonsSigningSettings = Yii::$app->request->post('usePersonalAddonsSigningSettings');
            $commonQtySignings = Yii::$app->request->post('commonQtySignings', 0);
            $commonAutoSigning = (bool)Yii::$app->request->post('commonAutoSigning');

            foreach ($signings as $signingsModule => $qty) {
                $module = Yii::$app->addon->getModule($signingsModule);
                $settings = $module->getSettings($terminalId);

                // Установка количества подписей

                // Если нужно использовать персональные настройки модулей
                if ($usePersonalAddonsSigningSettings) {
                    $settings->signaturesNumber = $qty;
                    $settings->useAutosigning = in_array($signingsModule, $autoSignings);
                } else {
                    $settings->signaturesNumber = $commonQtySignings;
                    $settings->useAutosigning = $commonAutoSigning;
                }

                $settings->save();
            }

            // Запись общих настроек подписей терминала
            $terminalSettings->usePersonalAddonsSigningSettings = $usePersonalAddonsSigningSettings;
            $terminalSettings->qtySignings = $commonQtySignings;
            $terminalSettings->useAutosigning = $commonAutoSigning;
            $terminalSettings->save();

            $url = Url::to(['/autobot/terminals/index', 'id' => $terminal->id, 'tabMode' => 'tabSigning']);

            $this->redirect($url);
        }

        $dataProvider = $this->getSigningSettings($settingsList, $terminalId);
        $commonSigningSettings = $this->getCommonSigningSettings($terminalSettings, $terminalId);

        $data['addons'] = $settingsList;
        $data['dataProvider'] = $dataProvider;
        $data['terminalId'] = $terminalId;
        $data['commonSigningSettings'] = $commonSigningSettings;

        return $data;
    }

    protected function getSigningSettings($settingsList, $terminalId)
    {
        $settingsArray    = [];
        $addons = Yii::$app->addon->getRegisteredAddons();

        foreach ($addons as $serviceId => $addon) {

            // изолировать подписание ISO20022, CYB-3739
            if ($serviceId == 'ISO20022') {
                continue;
            }

            $module = Yii::$app->addon->getModule($serviceId);
            $settings = $module->getSettings($terminalId);
            $signQty = $settingsList[$serviceId]->signaturesNumber;
            $settingsArray[] = [
              'document' => $serviceId,
              'title' =>  $serviceId,
              'qty' => $signQty,
              'auto' => $settings->useAutosigning,
            ];
        }

        return new ArrayDataProvider([
            'allModels' => $settingsArray,
            'key' => 'document',
        ]);
    }

    /**
     * Общие настройки подписания всех модулей
     */
    protected function getCommonSigningSettings($terminalSettings, $terminalId)
    {
        $data = [];

        $data['usePersonalAddonsSigningSettings'] = $terminalSettings->usePersonalAddonsSigningSettings;

        $settingsArray[] = [
            'document' => 'all',
            'title' =>  'Все модули',
            'qty' => $terminalSettings->qtySignings,
            'auto' => $terminalSettings->useAutosigning
        ];

        $data['dataProvider'] = new ArrayDataProvider([
            'allModels' => $settingsArray,
            'key' => 'document',
        ]);

        return $data;
    }

    /**
     * Список получателей документов
     */
    public function getParticipantList($terminalId)
    {
        $participantAutobots = new ParticipantAutobots();
        $data				 = [];

        foreach (Yii::$app->getModule('certManager')->getParticipantsList() as $participant => $value) {
            $status			 = ($participantAutobots->getModifyStatus($value, $terminalId) ? null
                : ' ('.Yii::t('app/autobot', 'Signed by the primary key').')');
            $data[$value]	 = $value.$status;
        }

        return $data;
    }
}
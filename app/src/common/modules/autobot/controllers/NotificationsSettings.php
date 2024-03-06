<?php

namespace common\modules\autobot\controllers;

use common\models\Terminal;
use Yii;
use yii\data\ArrayDataProvider;
use common\helpers\MonitorLogHelper;

trait NotificationsSettings
{
    protected $commonCheckers = ['sftpOpenFailed', 'cryptoProCertExpired', 'changeCertStatus'];
    /**
     * Данные по настройкам оповещений
     */
    protected function notificationSettingsData($terminal)
    {
        $data = [];
        $checkers = [];

        $module = $this->getMonitorModule();

        foreach($module->checkers as $checkerCode) {
            if (in_array($checkerCode, $this->commonCheckers)) {
                continue;
            }

            $checker = $module->getChecker($checkerCode);
            $checker->loadData();

            $checkers[$checker->code] = $checker;
        }

        $data['checkerDataProvider'] =  new ArrayDataProvider(['allModels' => $checkers]);

        return $data;
    }

    /**
     * Действие обновления настроек оповещений
     */
    public function actionNotificationsUpdate($terminalId)
    {
        $post = Yii::$app->request->post();
        $terminal = Terminal::findOne($terminalId);

        if (!$terminal) {
            // если такой терминал не найден
            return $this->redirect(Yii::$app->request->referrer);
        }

        $selection = Yii::$app->request->post('selection', []);

        $module = $this->getMonitorModule();

        foreach($module->checkers as $checkerCode) {
            if (in_array($checkerCode, $this->commonCheckers)) {
                continue;
            }

            $checker = $module->getChecker($checkerCode);
            $checker->loadData();
            $checker->save();

            // Запись настроек чекера
            MonitorLogHelper::saveCheckerSettings($checker, $post, $terminal->id);
        }

        // Регистрация события изменения настроек оповещений
        Yii::$app->monitoring->extUserLog('EditNotifySettings');

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function getMonitorModule()
    {
        return Yii::$app->getModule('monitor');
    }
}
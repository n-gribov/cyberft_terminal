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
            // Перенаправить на предыдущую страницу
            return $this->redirect(Yii::$app->request->referrer);
        }

        $module = $this->getMonitorModule();

        foreach($module->checkers as $checkerCode) {
            if (in_array($checkerCode, $this->commonCheckers)) {
                continue;
            }

            $checker = $module->getChecker($checkerCode);
            $checker->loadData();
            // Сохранить модель в БД
            $checker->save();

            // Запись настроек чекера
            MonitorLogHelper::saveCheckerSettings($checker, $post, $terminal->id);
        }

        // Зарегистрировать событие изменения настроек оповещений в модуле мониторинга
        Yii::$app->monitoring->extUserLog('EditNotifySettings');

        // Перенаправить на предыдущую страницу
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function getMonitorModule()
    {
        return Yii::$app->getModule('monitor');
    }
}

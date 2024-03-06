<?php

use yii\db\Migration;
use common\settings\SettingsAR;
use yii\helpers\ArrayHelper;
use common\models\User;

class m170804_122938_modify_notifications_addresses extends Migration
{
    public function up()
    {
        /*
         * Преобразование хранения адресов
         * получателей уведомлений из старого формата в новый (свой список адресов для каждого события)
         */
        // Получение терминалов, для которых были указаны настройки оповещений
        $terminals = SettingsAR::find()
            ->where(['code' => 'monitor:Notification'])
            ->select('terminalId')
            ->asArray()
            ->all();

        $terminals = ArrayHelper::getColumn($terminals, 'terminalId');

        $monitorModule = Yii::$app->getModule('monitor');

        // Перебор настроек оповещений терминалов
        foreach($terminals as $terminal) {
            $settings = Yii::$app->settings->get('monitor:Notification', $terminal);

            // Текущие настройки
            $addressList = $settings->addressList;

            // если нет настроек адресов оповещение, пропуска
            if (empty($addressList)) {
                continue;
            }

            $newAddressList = [];

            // Не для всех чекеров нужны настройки
            $commonCheckers = ['sftpOpenFailed', 'CryptoProCertExpired', 'changeCertStatus'];

            // Получение чекеров и запись для них настроек
            foreach($monitorModule->checkers as $checkerCode) {
                if (in_array($checkerCode, $commonCheckers)) {
                    continue;
                }

                // Перебор текущих заданных адресов
                foreach($addressList as $id => $address) {
                    // Получаем информацию по пользователю
                    $user = User::findOne($id);

                    if ($user) {
                        $newAddressList[ucfirst($checkerCode)][$address] = $user->getName();
                    }
                }
            }

            if (count($newAddressList) > 0) {
                $settings->addressList = $newAddressList;
                $settings->save();
            }
        }
    }

    public function down()
    {
        return true;
    }
}

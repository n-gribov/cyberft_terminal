<?php

use yii\db\Migration;
use common\modules\certManager\models\Cert;
use common\modules\autobot\models\Autobot;

class m161024_075407_add_expiration_date_to_autobot extends Migration
{
    public function up()
    {
        // Миграция создает новое поле даты доступности сертификата контролера
        // CYB-3056
        $this->addColumn('autobot', 'expirationDate', $this->timestamp());

        // Ищем соответствия в таблице сертификатов cert,
        // пытаемся заполнить дату из этой таблицы
        $certs = Cert::find()->all();

        foreach($certs as $cert) {

            // Ищем среди сертификатов контролера совпадение с общим сертификатом
            $autobotCert = Autobot::findOne(['fingerprint' => $cert->fingerprint, 'terminalId' => $cert->terminalId]);

            // Если совпадение найдено, записываем дату в сертификат контролера
            if ($autobotCert) {
                $autobotCert->expirationDate = $cert->validBefore;
                $autobotCert->save(false);
            }
        }
    }

    public function down()
    {
        $this->dropColumn('autobot', 'expirationDate');
    }
}

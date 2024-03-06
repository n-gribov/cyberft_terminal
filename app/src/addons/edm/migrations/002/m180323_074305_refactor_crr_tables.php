<?php

use yii\db\Migration;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestNonresident;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestPaymentSchedule;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestTranche;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;

/**
 * Миграция старых данных в новые представления для ПС
 * @task CYB-4069
 */
class m180323_074305_refactor_crr_tables extends Migration
{
    public function up()
    {
        // Переименование таблицы
        $this->renameTable('contractRegistrationRequest', 'edm_contractRegistrationRequestExt');

        // Добавление documentId в подчиненные таблицы
        $this->addColumn('contractRegistrationRequestNonresident', 'documentId', $this->integer());
        $this->addColumn('contractRegistrationRequestPaymentSchedule', 'documentId', $this->integer());
        $this->addColumn('contractRegistrationRequestTranche', 'documentId', $this->integer());

        // Заполнение documentId в подчиненной таблице
        $rows = (new \yii\db\Query())
            ->select(['id', 'documentId'])
            ->from('edm_contractRegistrationRequestExt')->all();

        foreach($rows as $row) {
            if ($row['documentId']) {
                ContractRegistrationRequestNonresident::updateAll(['documentId' => $row['documentId']], ['requestId' => $row['id']]);
                ContractRegistrationRequestPaymentSchedule::updateAll(['documentId' => $row['documentId']], ['requestId' => $row['id']]);
                ContractRegistrationRequestTranche::updateAll(['documentId' => $row['documentId']], ['requestId' => $row['id']]);
            } else {
                ContractRegistrationRequestExt::deleteAll(['id' => $row['id']]);
                ContractRegistrationRequestNonresident::deleteAll(['requestId' => $row['id']]);
                ContractRegistrationRequestPaymentSchedule::deleteAll(['requestId' => $row['id']]);
                ContractRegistrationRequestTranche::deleteAll(['requestId' => $row['id']]);
            }
        }

        $this->dropColumn('edm_contractRegistrationRequestExt', 'terminalId');
        $this->dropColumn('contractRegistrationRequestNonresident', 'requestId');
        $this->dropColumn('contractRegistrationRequestPaymentSchedule', 'requestId');
        $this->dropColumn('contractRegistrationRequestTranche', 'requestId');

        return true;
    }

    public function down()
    {
        $this->renameTable('edm_contractRegistrationRequestExt', 'contractRegistrationRequest');

        $this->dropColumn('contractRegistrationRequestNonresident', 'documentId');
        $this->dropColumn('contractRegistrationRequestPaymentSchedule', 'documentId');
        $this->dropColumn('contractRegistrationRequestTranche', 'documentId');
        $this->addColumn('contractRegistrationRequest', 'terminalId', $this->integer());
        $this->addColumn('contractRegistrationRequestNonresident', 'requestId', $this->integer());
        $this->addColumn('contractRegistrationRequestPaymentSchedule', 'requestId', $this->integer());
        $this->addColumn('contractRegistrationRequestTranche', 'requestId', $this->integer());

        return true;
    }
}

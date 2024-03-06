<?php

use yii\db\Migration;

/**
 * Информация о графиках платежей для документа 'Паспортная сделка' типа 'Кредитный договор'
 * @task CYB-3857
 */
class m171012_094402_create_contractRegistrationRequestPaymentSchedule extends Migration
{
    public function up()
    {
        $this->createTable('contractRegistrationRequestPaymentSchedule', [
            'id' => $this->primaryKey(),
            'mainDeptDate' => $this->string(),
            'mainDeptAmount' => $this->float(2),
            'interestPaymentsDate' => $this->string(),
            'interestPaymentsAmount' => $this->float(2),
            'comment' => $this->text(),
            'requestId' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('contractRegistrationRequestPaymentSchedule');
        return true;
    }
}

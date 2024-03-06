<?php

use yii\db\Migration;

/**
 * Информация о траншах для документа 'Паспортная сделка' типа 'Кредитный договор'
 * @task CYB-3857
 */
class m171011_123207_create_contractRegistrationRequestTranche extends Migration
{
    public function up()
    {
        $this->createTable('contractRegistrationRequestTranche', [
            'id' => $this->primaryKey(),
            'amount' => $this->float(2),
            'termCode' => $this->integer(),
            'date' => $this->string(),
            'requestId' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('contractRegistrationRequestTranche');
        return true;
    }
}

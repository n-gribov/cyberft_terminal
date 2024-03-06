<?php

use yii\db\Migration;

/**
 * Добавление данных по нерезидентам для документа "Паспорт сделки"
 * @task CYB-3857
 */
class m171009_130119_create_ContractRegistrationRequestNonresident extends Migration
{
    public function up()
    {
        $this->createTable('contractRegistrationRequestNonresident', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'countryCode' => $this->string(10),
            'amount' => $this->float(2),
            'percent' => $this->float(2),
            'isCredit' => $this->boolean(),
            'requestId' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('contractRegistrationRequestNonresident');
        return true;
    }
}

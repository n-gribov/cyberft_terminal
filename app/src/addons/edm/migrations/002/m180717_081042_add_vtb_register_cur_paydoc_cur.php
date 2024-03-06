<?php

use yii\db\Migration;

/**
 * Таблица для хранения данных валютных платежей для связи с реестрами валютных платежей
 * @task CYB-4210
 */
class m180717_081042_add_vtb_register_cur_paydoc_cur extends Migration
{
    public function up()
    {
        $this->createTable('vtb_RegisterCurPayDocCur', [
            'id' => $this->primaryKey(),
            'documentId' => $this->bigInteger()->notNull(),
            'number' => $this->string(),
            'date' => $this->string(),
            'payer' => $this->string(),
            'payerAccount' => $this->string(),
            'beneficiar' => $this->string(),
            'beneficiarAccount' => $this->string(),
            'beneficiarBank' => $this->string(),
            'amount' => $this->float(),
            'currency' => $this->string(),
            'paymentPurpose' => $this->string(),
            'status' => $this->string(),
        ]);

        return true;
    }

    public function down()
    {
        $this->dropTable('vtb_RegisterCurPayDocCur');

        return true;
    }
}

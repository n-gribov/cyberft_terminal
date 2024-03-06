<?php

use yii\db\Migration;

class m161123_084355_create_edmDictBeneficiaryContractor extends Migration
{
    public function up()
    {
        $this->createTable('edmDictBeneficiaryContractor', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => "enum('IND','ENT') NOT NULL DEFAULT 'ENT'",
            'account' => $this->string(32)->notNull(),
            'bankBik' => $this->string(9)->notNull(),
            'kpp' => $this->string(9)->notNull(),
            'inn' => $this->string(12)->notNull(),
            'currencyId' => $this->integer(11)->notNull(),
            'terminalId' => $this->integer()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('edmDictBeneficiaryContractor');
        return true;
    }
}

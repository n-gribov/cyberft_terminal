<?php

use yii\db\Migration;

class m161118_082452_create_edmDictOrganization extends Migration
{
    public function up()
    {
        // Создание справочника организаций
        // CYB-3317
        $this->createTable('edmDictOrganization', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'type' => "enum('IND','ENT') NOT NULL DEFAULT 'ENT'",
            'kpp' => $this->string(9)->notNull(),
            'inn' => $this->string(12)->notNull(),
            'terminalId' => $this->integer()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('edmDictOrganization');
        return true;
    }
}

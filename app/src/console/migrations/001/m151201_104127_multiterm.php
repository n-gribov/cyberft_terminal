<?php

use yii\db\Migration;

class m151201_104127_multiterm extends Migration
{
    public function up()
    {
        $this->createTable('terminal', [
           'id' => yii\db\mysql\Schema::TYPE_PK,
           'terminalId' => yii\db\mysql\Schema::TYPE_STRING . '(12) NULL',
           'title' => yii\db\mysql\Schema::TYPE_STRING . '(255) NOT NULL default \'\'',
           'status' => yii\db\mysql\Schema::TYPE_STRING . '(30) NOT NULL default \'\'',
           'isDefault' => yii\db\mysql\Schema::TYPE_SMALLINT . '(1) NOT NULL default 0',
        ]);
        $this->createIndex('terminalId', 'terminal', ['terminalId']);
        $this->createIndex('isDefault', 'terminal', ['isDefault']);
    }

    public function down()
    {
        $this->dropIndex('terminalId', 'terminal');
        $this->dropIndex('isDefault', 'terminal');
        $this->dropTable('terminal');

        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}

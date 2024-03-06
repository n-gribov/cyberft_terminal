<?php

use yii\db\Schema;
use yii\db\Migration;

class m151210_081254_storage_defaults extends Migration
{
    public function up()
    {
        $this->alterColumn('storage', 'entity', 'VARCHAR(62) DEFAULT NULL');
    }

    public function down()
    {
        echo "m151210_081254_storage_defaults cannot be reverted.\n";

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

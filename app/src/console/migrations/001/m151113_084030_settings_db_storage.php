<?php

use yii\db\Migration;
use yii\db\mysql\Schema;

class m151113_084030_settings_db_storage extends Migration
{

    public function up()
    {
        $this->createTable('settings',
            [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(255)',
            'data' => Schema::TYPE_TEXT,
        ]);

        $this->createIndex('code', 'settings', 'code', true);
    }

    public function down()
    {
        $this->dropIndex('settings');
        $this->dropTable('settings');

        return true;
    }

}
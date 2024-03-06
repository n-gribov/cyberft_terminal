<?php

use yii\db\Migration;

class m170125_134320_create_user_columns_settings extends Migration
{
    public function up()
    {
        // @CYB-3377
        // Таблица для хранения настроек колонок для журналов

        $this->createTable('user_columns_settings', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'listType' => $this->string(100)->notNull(),
            'settings' => $this->text()->notNull()
        ]);
    }

    public function down()
    {
        $this->dropTable('user_columns_settings');
        return true;
    }
}

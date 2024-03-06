<?php

use yii\db\Migration;

class m170522_124418_add_ip_column_to_users extends Migration
{
    public function up()
    {
        // Добавление поля для хранения ip пользователя,
        // с которого он заходил последний раз
        // CYB-3711
        $this->addColumn('user', 'lastIp', $this->string());
    }

    public function down()
    {
        $this->dropColumn('user', 'lastIp');
    }
}

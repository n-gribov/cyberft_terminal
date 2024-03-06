<?php

use yii\db\Migration;

/**
 * Добавление признака зашифрованности для таблицы storage
 * @task CYB-4174
 */
class m180606_125002_add_encrypted_prop_to_storage extends Migration
{
    public function up()
    {
        $this->addColumn('storage', 'isEncrypted', $this->boolean() . ' default 0 ');
        return true;
    }

    public function down()
    {
        $this->dropColumn('storage', 'isEncrypted');
        return true;
    }
}

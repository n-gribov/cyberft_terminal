<?php

use yii\db\Migration;

/**
 * Добавление признака состояния шифрования данных документа (зашифирован или нет)
 * @task CYB-4183
 */
class m180614_092516_add_is_encrypted_prop_to_documents extends Migration
{
    public function up()
    {
        $this->addColumn('document', 'isEncrypted', 'tinyint(1) unsigned not null default 0');
        return true;
    }

    public function down()
    {
        $this->dropColumn('document', 'isEncrypted');
        return true;
    }
}

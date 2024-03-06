<?php

use yii\db\Migration;

class m200219_104827_add_uuid_to_edm_paymentOrder extends Migration
{
    private $tableName = '{{%edm_paymentOrder}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'uuid', $this->string());
        $this->createIndex('edm_paymentOrder_uuid', $this->tableName, 'uuid');
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'uuid');
    }
}

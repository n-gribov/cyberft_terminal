<?php

use yii\db\Migration;

class m200521_094503_add_signatureType_to_raiffeisen_customer extends Migration
{
    private $tableName = '{{%raiffeisen_customer}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'signatureType', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'signatureType');
    }
}

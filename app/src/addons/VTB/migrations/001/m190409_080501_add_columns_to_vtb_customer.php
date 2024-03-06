<?php

use yii\db\Migration;

class m190409_080501_add_columns_to_vtb_customer extends Migration
{
    private $tableName = '{{%vtb_customer}}';

    public function safeUp()
    {
        $this->addColumn($this->tableName, 'clientId', $this->bigInteger());
        $this->addColumn($this->tableName, 'addressZipCode', $this->string(50));
        $this->addColumn($this->tableName, 'internationalZipCode', $this->string(50));
        $this->addColumn($this->tableName, 'okato', $this->string(50));
        $this->addColumn($this->tableName, 'okpo', $this->string(50));
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'clientId');
        $this->dropColumn($this->tableName, 'addressZipCode');
        $this->dropColumn($this->tableName, 'internationalZipCode');
        $this->dropColumn($this->tableName, 'okato');
        $this->dropColumn($this->tableName, 'okpo');
    }
}

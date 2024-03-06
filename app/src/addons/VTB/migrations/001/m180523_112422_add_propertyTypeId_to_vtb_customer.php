<?php

use yii\db\Migration;

class m180523_112422_add_propertyTypeId_to_vtb_customer extends Migration
{
    private $tableName = '{{%vtb_customer}}';

    public function safeUp()
    {
        $this->addColumn(
            $this->tableName,
            'propertyTypeId',
            $this->integer()
        );
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'propertyTypeId');
    }
}

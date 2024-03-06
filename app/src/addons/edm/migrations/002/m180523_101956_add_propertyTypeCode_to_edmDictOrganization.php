<?php

use yii\db\Migration;

class m180523_101956_add_propertyTypeCode_to_edmDictOrganization extends Migration
{
    private $tableName = '{{%edmDictOrganization}}';

    public function safeUp()
    {
        $this->addColumn(
            $this->tableName,
            'propertyTypeCode',
            $this->string(50)
        );
    }

    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'propertyTypeCode');
    }
}

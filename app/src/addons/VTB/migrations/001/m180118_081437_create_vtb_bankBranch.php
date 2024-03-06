<?php

use yii\db\Migration;

class m180118_081437_create_vtb_bankBranch extends Migration
{
    private $tableName = '{{%vtb_bankBranch}}';

    public function up()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'                => $this->primaryKey(),
                'branchId'          => $this->integer()->notNull()->unique(),
                'bik'               => $this->string(20),
                'name'              => $this->string(),
                'fullName'          => $this->string(),
                'internationalName' => $this->string(),
            ]
        );
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}

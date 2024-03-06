<?php

use yii\db\Migration;

class m180119_120021_create_edmDictVTBBankBranch extends Migration
{
    private $tableName = '{{%edmDictVTBBankBranch}}';

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

<?php

use yii\db\Migration;

class m180125_142343_create_edmCryptoproSigningRequest extends Migration
{
    private $tableName = '{{%edmCryptoproSigningRequest}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'documentId' => $this->bigInteger(20)->notNull()->unique(),
            'status'     => $this->string(32)->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}

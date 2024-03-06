<?php

use yii\db\Migration;

class m171025_112149_create_sbbol_request extends Migration
{
    private $tableName = '{{%sbbol_request}}';

    public function up()
    {
        $this->createTable($this->tableName, [
            'id'                => $this->primaryKey(),
            'status'            => $this->string(64)->notNull(),
            'senderRequestId'   => $this->string(64),
            'receiverRequestId' => $this->string(64),
            'dateCreate'        => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'responseCheckDate' => 'TIMESTAMP NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}

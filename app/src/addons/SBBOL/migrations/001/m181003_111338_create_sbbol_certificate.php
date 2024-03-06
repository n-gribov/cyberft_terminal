<?php

use yii\db\Migration;

class m181003_111338_create_sbbol_certificate extends Migration
{
    private $tableName = '{{%sbbol_certificate}}';

    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'dateCreate'  => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'status'      => $this->string(100)->notNull(),
            'type'        => $this->string(100)->notNull(),
            'fingerprint' => $this->string()->unique()->notNull(),
            'serial'      => $this->string()->unique()->notNull(),
            'commonName'  => $this->string(),
            'body'        => $this->text()->notNull(),
            'validFrom'   => $this->dateTime(),
            'validTo'     => $this->dateTime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}

<?php

use yii\db\Migration;

class m180802_075220_create_sbbol_key extends Migration
{
    private $tableName = '{{%sbbol_key}}';

    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id'                     => $this->primaryKey(),
                'dateCreate'             => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
                'status'                 => $this->string(100),
                'keyOwnerId'             => $this->string(100)->notNull(),
                'bicryptId'              => $this->string(100),
                'certificateFingerprint' => $this->string(),
                'certificateSerial'      => $this->string(200),
                'certificateBody'        => $this->text(),
                'certificateRequest'     => $this->text(),
                'keyContainerName'       => $this->string(255),
                'keyPassword'            => $this->string(1000),
                'publicKey'              => $this->text(),
            ]
        );

        $this->addForeignKey(
            'fk_sbbol_key_keyOwnerId',
            $this->tableName,
            'keyOwnerId',
            '{{%sbbol_customerKeyOwner}}',
            'id',
            'cascade',
            'cascade'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}

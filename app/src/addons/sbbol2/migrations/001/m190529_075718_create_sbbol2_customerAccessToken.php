<?php

use yii\db\Migration;

class m190529_075718_create_sbbol2_customerAccessToken extends Migration
{
    private $tableName = '{{%sbbol2_customerAccessToken}}';
    
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => $this->primaryKey(),
                'customerId' => $this->integer()->notNull()->unique(),
                'accessToken' => $this->string()->notNull(),
                'accessTokenExpiryTime' => $this->timestamp()->defaultValue(null),
                'refreshToken' => $this->string()->notNull(),
                'isActive' => $this->boolean()->notNull()->defaultValue(0),
                'createDate' => $this->timestamp()->defaultValue(null),
                'updateDate' => $this->timestamp()->defaultValue(null),
            ]
        );

        $this->addForeignKey(
            'fk_sbbol2_customerAccessToken_customerId',
            $this->tableName,
            'customerId',
            '{{%sbbol2_customer}}',
            'id',
            'cascade'
        );
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}

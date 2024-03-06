<?php

use yii\db\Migration;

class m201126_112419_add_canSignDocuments_column_to_edmPayersAccountsUsers extends Migration
{
    private const TABLE_NAME = '{{%edmPayersAccountsUsers}}';

    public function safeUp()
    {
        $this->addColumn(
            self::TABLE_NAME,
            'canSignDocuments',
            $this->boolean()->notNull()->defaultValue(false)
        );
        $this->update(
            self::TABLE_NAME,
            ['canSignDocuments' => true]
        );
    }

    public function safeDown()
    {
        $this->dropColumn(
            self::TABLE_NAME,
            'canSignDocuments'
        );
    }
}

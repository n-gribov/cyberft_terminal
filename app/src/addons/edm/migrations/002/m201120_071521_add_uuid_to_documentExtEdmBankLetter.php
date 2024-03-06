<?php

use yii\db\Migration;

class m201120_071521_add_uuid_to_documentExtEdmBankLetter extends Migration
{
    private const TABLE_NAME = '{{%documentExtEdmBankLetter}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'uuid', $this->string()->unique());
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'uuid');
    }
}

<?php

use yii\db\Migration;

class m210415_111021_make_participant_BICDir_nullable extends Migration
{
    private const TABLE_NAME = '{{%participant_BICDir}}';

    public function safeUp()
    {
        $this->alterColumn(self::TABLE_NAME, 'id', $this->integer(10)->unsigned()->null());
    }

    public function safeDown()
    {
        $this->alterColumn(self::TABLE_NAME, 'id', $this->integer(10)->unsigned()->notNull());
    }
}

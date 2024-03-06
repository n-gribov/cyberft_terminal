<?php

use yii\db\Migration;

class m210414_081713_add_autoUpdate_column_to_cert extends Migration
{
    private const TABLE_NAME = '{{%cert}}';

    public function safeUp()
    {
        $this->addColumn(
            self::TABLE_NAME,
            'autoUpdate',
            $this->boolean()->notNull()->defaultValue(false)
        );
        $this->update(self::TABLE_NAME, ['autoUpdate' => false]);
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'autoUpdate');
    }
}

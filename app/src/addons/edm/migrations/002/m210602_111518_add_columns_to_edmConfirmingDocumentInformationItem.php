<?php

use yii\db\Migration;

class m210602_111518_add_columns_to_edmConfirmingDocumentInformationItem extends Migration
{
    private const TABLE_NAME = '{{%edmConfirmingDocumentInformationItem}}';

    public function safeUp()
    {
        $this->addColumn(
            self::TABLE_NAME,
            'originalDocumentDate',
            $this->string()
        );
        $this->addColumn(
            self::TABLE_NAME,
            'originalDocumentNumber',
            $this->string()
        );
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'originalDocumentDate');
        $this->dropColumn(self::TABLE_NAME, 'originalDocumentNumber');
    }
}

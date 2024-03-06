<?php

use yii\db\Migration;

class m210618_111256_add_uuid_to_edm_confirmingDocumentInformationExt extends Migration
{
    private const TABLE_NAME = '{{%edm_confirmingDocumentInformationExt}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'uuid', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'uuid');
    }
}

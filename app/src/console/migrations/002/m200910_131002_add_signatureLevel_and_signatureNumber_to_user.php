<?php

use yii\db\Migration;

class m200910_131002_add_signatureLevel_and_signatureNumber_to_user extends Migration
{
    private const TABLE_NAME = '{{%user}}';

    public function safeUp()
    {
        $this->addColumn(
            self::TABLE_NAME,
            'signatureLevel',
            $this->integer()
        );
        $this->addColumn(
            self::TABLE_NAME,
            'signatureNumber',
            $this->integer()
        );
        $this->execute('update ' . $this->db->quoteTableName(self::TABLE_NAME) . ' set signatureNumber = signerLevel');
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'signatureLevel');
        $this->dropColumn(self::TABLE_NAME, 'signatureNumber');
    }
}

<?php

use yii\db\Migration;

class m210728_054614_add_columns_to_edm_ForeignCurrencyPaymentTemplates extends Migration
{
    private const TABLE_NAME = '{{%edm_ForeignCurrencyPaymentTemplates}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE_NAME, 'immediatePayment', $this->string());
        $this->addColumn(self::TABLE_NAME, 'beneficiaryAddress', $this->string());
        $this->addColumn(self::TABLE_NAME, 'beneficiaryLocation', $this->string());
        $this->addColumn(self::TABLE_NAME, 'commissionAccount', $this->string());
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE_NAME, 'immediatePayment');
        $this->dropColumn(self::TABLE_NAME, 'beneficiaryAddress');
        $this->dropColumn(self::TABLE_NAME, 'beneficiaryLocation');
        $this->dropColumn(self::TABLE_NAME, 'commissionAccount');
    }
}

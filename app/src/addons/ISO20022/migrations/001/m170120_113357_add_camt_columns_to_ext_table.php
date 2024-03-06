<?php

use yii\db\Migration;

class m170120_113357_add_camt_columns_to_ext_table extends Migration
{
    public function up()
    {
        // @CYB-3462
        // Добавление нужных полей для документов camt.053, camt.054
        $this->addColumn('documentExtISO20022', 'account', $this->string());
        $this->addColumn('documentExtISO20022', 'periodBegin', $this->date());
        $this->addColumn('documentExtISO20022', 'periodEnd', $this->date());
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'account');
        $this->dropColumn('documentExtISO20022', 'periodBegin');
        $this->dropColumn('documentExtISO20022', 'periodEnd');
    }
}

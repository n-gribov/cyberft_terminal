<?php

use yii\db\Migration;

class m170830_154559_add_documentId_to_edmForeignCurrencyOperationInformation extends Migration
{
    public function up()
    {
        $this->addColumn('edmForeignCurrencyOperationInformation', 'documentId', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('edmForeignCurrencyOperationInformation', 'documentId');
        return true;
    }
}

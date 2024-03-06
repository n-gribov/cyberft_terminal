<?php

use yii\db\Migration;

class m170911_120103_add_storedFileId_to_edmForeignCurrencyOperationInformation extends Migration
{
    public function up()
    {
        $this->addColumn('edmForeignCurrencyOperationInformation', 'storedFileId', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('edmForeignCurrencyOperationInformation', 'storedFileId');
        return true;
    }
}

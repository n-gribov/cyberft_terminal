<?php

use yii\db\Migration;

class m180425_114825_add_business_status_to_edm_confirmingDocumentInformationExt extends Migration
{
    private $extTable = '{{%edm_confirmingDocumentInformationExt}}';

    public function safeUp()
    {
        $this->addColumn($this->extTable, 'businessStatus', $this->string(4));
        $this->addColumn($this->extTable, 'businessStatusDescription', $this->string());
        $this->addColumn($this->extTable, 'businessStatusComment', $this->text());

        return true;
    }

    public function safeDown()
    {
        $this->dropColumn($this->extTable, 'businessStatus');
        $this->dropColumn($this->extTable, 'businessStatusDescription');
        $this->dropColumn($this->extTable, 'businessStatusComment');

        return true;
    }
}

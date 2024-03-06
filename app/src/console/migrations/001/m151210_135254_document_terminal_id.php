<?php

use yii\db\Schema;
use yii\db\Migration;

class m151210_135254_document_terminal_id extends Migration
{
    public function up()
    {
        $this->addColumn('document', 'terminalId', Schema::TYPE_INTEGER);
    }

    public function down()
    {
        $this->dropColumn('document', 'terminalId');
        return true;
    }
}

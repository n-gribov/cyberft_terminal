<?php

use yii\db\Migration;

class m160726_133224_add_correctionReason_column_to_documentExtSwiftFin extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtSwiftFin', 'correctionReason', $this->text());
    }

    public function down()
    {
        $this->dropColumn('documentExtSwiftFin', 'correctionReason');
        return true;
    }
}

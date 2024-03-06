<?php

use yii\db\Schema;
use yii\db\Migration;

class m151216_105507_isoext_storedfileId extends Migration
{
    public function up()
    {
        $this->addColumn('documentExtISO20022', 'storedFileId', Schema::TYPE_BIGINT);
    }

    public function down()
    {
        $this->dropColumn('documentExtISO20022', 'storedFileId');

        return true;
    }

}

<?php

use yii\db\Migration;

class m170302_132717_modify_extModel_businessStatus extends Migration
{
    public function up()
    {
        // Добавление полей, связанных с бизнес-статусами
        // Нужны для обработки документов StatusReport
        // @CYB-3509
        $this->addColumn('documentExtFinZip', 'businessStatus', $this->string());
        $this->addColumn('documentExtFinZip', 'businessStatusDescription', $this->string());
    }

    public function down()
    {
        $this->dropColumn('documentExtFinZip', 'businessStatus');
        $this->dropColumn('documentExtFinZip', 'businessStatusDescription');
        return true;
    }
}
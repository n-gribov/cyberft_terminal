<?php

use yii\db\Migration;

class m161220_134723_iso20022_UserExt_permissions extends Migration
{
    public function up()
    {
        $this->addColumn('iso20022_UserExt', 'permissionsData', \yii\db\mysql\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('iso20022_UserExt', 'permissionsData');
        return true;
    }
}

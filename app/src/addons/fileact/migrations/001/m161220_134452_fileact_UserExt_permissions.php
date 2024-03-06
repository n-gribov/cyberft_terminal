<?php

use yii\db\Migration;

class m161220_134452_fileact_UserExt_permissions extends Migration
{
    public function up()
    {
        $this->addColumn('fileact_UserExt', 'permissionsData', \yii\db\mysql\Schema::TYPE_TEXT);
    }

    public function down()
    {
        $this->dropColumn('fileact_UserExt', 'permissionsData');
    }
}

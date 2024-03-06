<?php

use yii\db\Migration;

class m161010_094252_add_remoteSenderId_column_to_terminal extends Migration
{
    public function up()
    {
        $this->addColumn('terminal', 'remoteSenderId', $this->string());
    }

    public function down()
    {
        $this->dropColumn('terminal', 'remoteSenderId');

        return true;
    }
}

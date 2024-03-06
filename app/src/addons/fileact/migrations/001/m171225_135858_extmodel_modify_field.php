<?php

use yii\db\Migration;

class m171225_135858_extmodel_modify_field extends Migration
{
    public function up()
    {
        try {
            $this->execute('alter table `documentExtFileAct` drop key `senderReference`');
        } catch(Exception $ex) {
            echo $ex->getMessage() . "\n";
        }
    }

    public function down()
    {
        return true;
    }

}

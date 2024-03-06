<?php

use common\models\Terminal;
use common\models\User;
use yii\db\Migration;

class m160603_083134_user_terminal extends Migration
{
    public function up()
    {
        $this->execute("alter table `user` add column `terminalId` int unsigned null");
        User::updateAll(['terminalId' => Terminal::getIdByAddress(Yii::$app->exchange->address)]);
    }

    public function down()
    {
        $this->execute("alter table `user` drop column `terminalId`");

        return true;
    }

}

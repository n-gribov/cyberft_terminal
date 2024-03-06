<?php

use addons\edm\models\DictContractor;
use yii\db\Migration;

class m160620_155019_account_add_contractorId extends Migration
{
    public function up()
    {
        $this->execute("alter table `edm_account` add column `contractorId` int unsigned null");
        $this->execute("alter table `edmDictContractor` add column `terminalId` int unsigned null");

        $defaultTerminal = Yii::$app->terminals->defaultTerminal;

        if ($defaultTerminal) {
            DictContractor::updateAll(['terminalId' => $defaultTerminal->id]);
        }
        $contractor = DictContractor::find()->one();
        if ($contractor) {
            $this->execute("update `edm_account` set `contractorId` = " .  $contractor->id);
        }
    }

    public function down()
    {
        $this->execute("alter table `edm_account` drop column `contractorId`");
        $this->execute("alter table `edmDictContractor` drop column `terminalId`");

        return true;
    }

}

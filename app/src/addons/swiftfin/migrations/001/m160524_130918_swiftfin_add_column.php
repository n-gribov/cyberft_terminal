<?php

use yii\db\Migration;

class m160524_130918_swiftfin_add_column extends Migration
{
    public function up()
    {
        $this->addColumn('swiftfin_dictBank','fullCode',$this->string(16));

        $connection = Yii::$app->getDb();
        $command = $connection->createCommand('UPDATE swiftfin_dictBank SET fullCode = CONCAT(swiftCode,branchCode)');
        $result = $command->query();
    }

    public function down()
    {
        $this->dropColumn('swiftfin_dictBank','fullCode');
        return true;
    }
}

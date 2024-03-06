<?php

use yii\db\Migration;

class m160718_140801_modify_swiftfin_templates extends Migration
{
    public function up()
    {
        $this->addColumn('swiftfin_templates','sender',$this->string(12) . 'NOT NULL');
        $this->addColumn('swiftfin_templates','recipient',$this->string(12) . 'NOT NULL');
        $this->addColumn('swiftfin_templates','terminalCode',$this->string(1));
        $this->addColumn('swiftfin_templates','bankPriority',$this->string(4));
        $this->dropColumn('swiftfin_templates','comment');
    }

    public function down()
    {
        $this->dropColumn('swiftfin_templates','sender');
        $this->dropColumn('swiftfin_templates','recipient');
        $this->dropColumn('swiftfin_templates','terminalCode');
        $this->dropColumn('swiftfin_templates','bankPriority');
        $this->addColumn('swiftfin_templates','comment',$this->text());
        return true;
    }
}

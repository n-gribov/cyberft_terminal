<?php

use addons\swiftfin\models\SwiftfinTemplate;
use common\models\Terminal;
use yii\db\Migration;

class m161215_142655_swiftfin_template_terminalId extends Migration
{
    public function up()
    {
        $this->execute('alter table swiftfin_templates add column terminalId int unsigned null');

        $terminal = Terminal::find()
            ->where(['status' => Terminal::STATUS_ACTIVE, 'isDefault' => 1])
            ->select('id')
            ->one();

        if (!$terminal) {
            return;
        }

        SwiftfinTemplate::updateAll(['terminalId' => $terminal->id]);

    }

    public function down()
    {
        $this->execute('alter table swiftfin_templates drop column terminalId');

        return true;
    }

}

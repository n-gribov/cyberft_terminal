<?php

use yii\db\Migration;

class m170627_081253_add_user_id_to_swiftfin_ext extends Migration
{
    public function up()
    {
        // Поле для хранения пользователя, который создал swift-документ
        // @CYB-3785
        $this->addColumn('documentExtSwiftFin', 'userId', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('documentExtSwiftFin', 'userId');
    }
}

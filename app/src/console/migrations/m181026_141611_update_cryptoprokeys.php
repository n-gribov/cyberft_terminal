<?php

use yii\db\Migration;

class m181026_141611_update_cryptoprokeys extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->execute('alter table `cryptoproKeys` rename to `cryptoproKey`');
        $this->execute('alter table `cryptoproKey` add column algo varchar(64)');
    }

    public function down()
    {
        $this->execute('alter table `cryptoproKey` drop column algo');
        $this->execute('alter table `cryptoproKey` rename to `cryptoproKeys`');

        return true;
    }

}

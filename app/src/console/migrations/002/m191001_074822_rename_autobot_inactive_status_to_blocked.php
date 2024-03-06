<?php

use yii\db\Migration;

/**
 * Class m191001_074822_rename_autobot_inactive_status_to_blocked
 */
class m191001_074822_rename_autobot_inactive_status_to_blocked extends Migration
{
    public function safeUp()
    {
        $this->update(
            'autobot',
            ['status' => 'blocked'],
            ['status' => 'inactive']
        );
    }

    public function safeDown()
    {
        $this->update(
            'autobot',
            ['status' => 'inactive'],
            ['status' => 'blocked']
        );
    }
}

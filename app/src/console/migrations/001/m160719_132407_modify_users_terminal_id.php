<?php

use yii\db\Migration;
use common\models\Terminal;
use common\models\User;

class m160719_132407_modify_users_terminal_id extends Migration
{
    public function up()
    {
        $terminals = Terminal::getList('id', 'terminalId');

        /*
         * Если в системе больше 1 терминала, то для всех существующих пользователей
         * делаем возможность доступа ко всем терминалам
         */
        if (count($terminals) > 1) {
            User::updateAll(['terminalId' => null],['role' => User::ROLE_USER]);
        }
    }

    public function down()
    {
        return true;
    }

}

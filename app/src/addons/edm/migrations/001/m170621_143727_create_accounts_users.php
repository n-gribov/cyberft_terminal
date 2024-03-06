<?php

use yii\db\Migration;
use common\models\User;
use common\helpers\UserHelper;

class m170621_143727_create_accounts_users extends Migration
{
    public function up()
    {
        // создание таблицы для хранения
        // счетов доступных пользователям
        // CYB-3710
        $this->createTable('edmPayersAccountsUsers', [
            'id' => $this->primaryKey(),
            'accountId' => $this->integer(),
            'userId' => $this->integer()
        ]);

        // Всем существубщим пользователям
        // доступны все счета согласно их принадлежности к терминалу
        $users = User::find()
            ->select('id')
            ->where(['status' => User::STATUS_ACTIVE])
            ->asArray()->all();

        foreach($users as $user) {
            try {
                UserHelper::setNewUserAccounts($user['id']);
            } catch (\Exception $e) {
                continue;
            }
        }
    }

    public function down()
    {
        $this->dropTable('edmPayersAccountsUsers');
    }
}

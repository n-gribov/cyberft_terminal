<?php

use yii\db\Migration;
use common\models\User;

class m170421_074803_set_additional_administrators extends Migration
{
    public function up()
    {
        // Поле содержит id администратора,
        // который создал пользователя
        // CYB-3679
        $this->addColumn('user', 'ownerId', $this->integer(11));

        // Установка отдельной роли
        // для дополнительных администраторов
        // CYB-3679
        $users = User::findAll(['role' => User::ROLE_ADMIN]);

        foreach($users as $user) {
            if ($user->isMainAdmin()) {
                continue;
            }

            $user->role = User::ROLE_ADDITIONAL_ADMIN;
            $user->save();
        }

        // Все существующие пользователи принадлежат
        // главному администратору

        // Получаем главного администратора
        $mainAdmin = User::findOne(['role' => User::ROLE_ADMIN]);

        if ($mainAdmin) {
            // Записываем данные для всех пользователей
            User::updateAll([
                'ownerId' => $mainAdmin->id,
            ], "id != $mainAdmin->id");
        }
    }

    public function down()
    {
        return true;
    }
}

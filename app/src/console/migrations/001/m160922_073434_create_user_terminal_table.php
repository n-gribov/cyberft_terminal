<?php

use yii\db\Migration;
use common\models\UserTerminal;
use common\models\User;
use common\models\Terminal;

class m160922_073434_create_user_terminal_table extends Migration
{
    public function up()
    {
        $this->createTable('user_terminal', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(11)->unsigned()->notNull(),
            'terminalId' => $this->integer(11)->notNull()
        ]);

        $this->createIndex('idx_user_terminal', 'user_terminal', ['userId', 'terminalId'], true);

        $this->addForeignKey('fk_user_terminal_user', 'user_terminal', 'userId', 'user', 'id', 'CASCADE', "CASCADE");
        $this->addForeignKey('fk_user_terminal_terminal', 'user_terminal', 'terminalId', 'terminal', 'id', 'CASCADE', "CASCADE");

        // Запись информации о доступных терминалах существующих пользователей

        // Получаем список текущих активных терминалов
        $terminals = Terminal::find()->select('id')->where(['status' => Terminal::STATUS_ACTIVE])->asArray()->all();

        $terminalsArray = [];

        // Формируем из них односложный массив
        foreach($terminals as $terminal) {
            $terminalsArray[] = $terminal['id'];
        }

        // Получаем информацию о пользователях
        $users = User::find()->all();

        foreach($users as $user) {
            // Если terminalId пуст, то считаем, что пользователю доступны все терминалы
            if (!$user->terminalId) {

                // Записываем все активные терминалы в качестве доступных пользователю
                foreach($terminalsArray as $value) {
                    $userTerminal = new UserTerminal();
                    $userTerminal->userId = $user->id;
                    $userTerminal->terminalId = $value;
                    $userTerminal->save();
                }
            } else {
                // Иначе определяем доступ только к одному терминалу

                // Если терминал пользователя активен, добавляем его в список его терминалов
                if (in_array($user->terminalId, $terminalsArray)) {
                    $userTerminal = new UserTerminal();
                    $userTerminal->userId = $user->id;
                    $userTerminal->terminalId = $user->terminalId;
                    $userTerminal->save();
                }
            }
        }
    }

    public function down()
    {
        $this->dropForeignKey('fk_user_terminal_user', 'user_terminal');
        $this->dropForeignKey('fk_user_terminal_terminal', 'user_terminal');
        $this->dropTable('user_terminal');

        return true;
    }
}

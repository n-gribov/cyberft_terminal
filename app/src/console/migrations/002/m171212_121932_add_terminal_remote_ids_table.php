<?php

use yii\db\Migration;
use common\models\Terminal;
use common\models\TerminalRemoteId;

class m171212_121932_add_terminal_remote_ids_table extends Migration
{
    public function up()
    {
        $this->createTable('terminal_remote_ids', [
            'id' => $this->primaryKey(),
            'terminalId' => $this->integer(11),
            'terminalReceiver' => $this->string(),
            'remoteId' => $this->string()
        ]);

        // Перенос существующих настроек в новую таблицу
        // Получение списка терминалов с указанными remoteSenderId
        $terminals = Terminal::find()
            ->where(['not', ['remoteSenderId' => ""]])
            ->andWhere(['not', ['remoteSenderId' => null]])
            ->all();

        foreach($terminals as $terminal) {
            $terminalRemoteId = new TerminalRemoteId;
            $terminalRemoteId->terminalId = $terminal->id;
            $terminalRemoteId->remoteId = $terminal->remoteSenderId;
            $terminalRemoteId->save();
        }

        // Удаление поля из таблицы терминалов
        $this->dropColumn(Terminal::tableName(), 'remoteSenderId');
    }

    public function down()
    {
        $this->dropTable('terminal_remote_ids');
        $this->addColumn(Terminal::tableName(), 'remoteSenderId', $this->string());
        return true;
    }
}

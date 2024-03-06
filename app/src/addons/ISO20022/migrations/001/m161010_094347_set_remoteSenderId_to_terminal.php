<?php

use yii\db\Migration;
use common\modules\participant\models\BICDirParticipant;
use common\helpers\Address;
use common\models\Terminal;

class m161010_094347_set_remoteSenderId_to_terminal extends Migration
{
    public function up()
    {
        // Получение списка текущих кодов в системе получателя из таблицы участников
        $participants = BICDirParticipant::find()
            ->select(['participantBIC', 'remoteSenderId'])
            ->where(['not', ['remoteSenderId' => null]])->all();

        // Обход списка участников с заполненными кодами
        foreach($participants as $participant) {

            // Преобразование адреса из списка участников в 12-значный адрес
            $terminalAddress = Address::fixSender($participant->participantBIC);

            // Поиск терминала по полученному адресу и запись значения кода для него
            Terminal::updateAll(
                ['remoteSenderId' => $participant->remoteSenderId],
                ['terminalId' => $terminalAddress]
            );
        }

        // Удаление поля кода из таблицы участников
        $this->dropColumn('participant_BICDir', 'remoteSenderId');

        return true;
    }

    public function down()
    {
        return true;
    }
}

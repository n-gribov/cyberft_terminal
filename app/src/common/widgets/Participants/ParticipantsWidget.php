<?php

namespace common\widgets\Participants;

use Yii;
use yii\base\Widget;
use common\models\Terminal;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use common\models\UserTerminal;
use common\helpers\Address;

class ParticipantsWidget extends Widget
{
    public $form;
    public $model;

    public function run()
    {
        // Если пользователю доступны все терминалы, получаем список активных терминалов
        // Иначе список доступных ему терминалов будет содержать только одно значение
        $senderTerminalList = [];

        // Получить модель пользователя из активной сессии
        $userModel = Yii::$app->user->identity;
        $userId = $userModel->id;

        // Получаем список доступных пользователю терминалов
        $query = UserTerminal::find()->with('terminal')->where(['userId' => $userId]);

        /**
         * Если у юзера задан терминал, значит в данный момент он переключен на этот терминал и с другими не работает
         */
        if ($userModel->terminalId) {
            $query->andWhere(['terminalId' => $userModel->terminalId]);
        }

        $userTerminals = $query->all();
        foreach($userTerminals as $item) {

            // Неактивные терминалы, которые привязаны к пользователю, не выводим
            if ($item->terminal->status == Terminal::STATUS_INACTIVE) {
                continue;
            }

            $terminalId = $item->terminal->terminalId;
            $truncatedId = Address::truncateAddress($terminalId);
            $participantData = BICDirParticipant::find()->where(['participantBIC' => $truncatedId])->one();

            if ($participantData) {
                $senderTerminalList[$terminalId] = $participantData->name;
            } else {
                $senderTerminalList[$terminalId] = $terminalId;
            }
        }

        $certs = Cert::find()->all();

        $recepientsList = [];

        foreach ($certs as $cert) {
            $participantId = $cert->participantId;

            $participantData = BICDirParticipant::find()->where(['participantBIC' => $participantId])->one();

            // Если терминал отсутствует в списке участников, то наименование показывать не нужно
            if ($participantData) {
                $participantTitle = $participantData->name . " ($participantId)";
            } else {
                $participantTitle = $participantId;
            }

            $recepientsList[$participantId] = $participantTitle;
        }

        // Вывести страницу
        return $this->render('participants', [
            'form' => $this->form,
            'model' => $this->model,
            'senderTerminalList' => $senderTerminalList,
            'recepientsList' => $recepientsList
        ]);
    }
}

<?php

namespace common\models;

use yii\db\ActiveRecord;
use Yii;
use common\helpers\Address;
use common\modules\participant\models\BICDirParticipant;

/**
 * Информация о коде терминала в  системе получателя
 * Class TerminalRemoteId
 * @package common\models
 */
class TerminalRemoteId extends ActiveRecord
{
    public static function tableName()
    {
        return 'terminal_remote_ids';
    }

    public function rules()
    {
        return [
            [['terminalId', 'remoteId'], 'required'],
            ['terminalReceiver', 'safe']
        ];
    }

    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }

    public function attributeLabels()
    {
        return [
            'terminalId'        => Yii::t('app/terminal', 'Terminal ID'),
            'terminalReceiver'  => Yii::t('app/message', 'Recipient'),
            'remoteId'  => Yii::t('app/terminal', 'Remote sender id'),
        ];
    }

    /**
     * Получение адреса терминала получателя с
     * наименованием организации из справочника участников
     * @return mixed|string
     */
    public function getTerminalReceiverTitle()
    {
        $terminalReceiver = $this->terminalReceiver;

        $fixedAddress = Address::truncateAddress($terminalReceiver);

        $participant = BICDirParticipant::findOne(['participantBIC' => $fixedAddress]);

        if ($participant) {
            $terminalReceiverTitle = $terminalReceiver . ' (' . $participant->name . ')';
        } else {
            $terminalReceiverTitle = $terminalReceiver;
        }

        return $terminalReceiverTitle;
    }

    public static function getRemoteIdByTerminal($terminalId, $bankTerminalId = null)
    {
        $data = self::find()
                    ->where(['terminalId' => $terminalId])
                    ->andFilterWhere(['terminalReceiver' => $bankTerminalId])
                    ->one();

        return $data ? $data->remoteId : null;
    }
}
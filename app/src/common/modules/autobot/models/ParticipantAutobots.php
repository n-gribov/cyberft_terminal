<?php

namespace common\modules\autobot\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Participant autobots active record class
 *
 * @package modules
 * @subpackage autobot
 *
 * @property integer @id              Row ID
 * @property integer @terminal_id     Terminal ID
 * @property integer @participant_id  Participant ID
 * @property integer @autobot_id      Autobot ID
 */
class ParticipantAutobots extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'participant_autobots';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('app/autobot', 'ID'),
            'terminal_id'    => Yii::t('app/terminal', 'Terminal ID'),
            'participant_id' => Yii::t('app/autobot', 'Participant ID'),
            'autobot_id'     => Yii::t('app/autobot', 'Controller ID'),
        ];
    }

    /**
     * Get modify status
     *
     * @param integer $participantId Participant ID
     * @param integer $terminalId    Termainal ID
     * @return integer
     */
    public function getModifyStatus($participantId, $terminalId)
    {
        return (int) ParticipantAutobots::find()
                ->where([
                    'terminal_id' => $terminalId,
                    'participant_id' => $participantId
                ])
                ->count();
    }

    /**
     * Get list of autobot ID
     *
     * @param integer $participantId Participant ID
     * @param integer $terminalId    Terminal ID
     * @return ParticipantAutobots
     */
    public function getAutobotId($participantId, $terminalId)
    {
        $autobots = self::find()
            ->select(['autobot_id'])
            ->where([
                'terminal_id' => $terminalId,
                'participant_id' => $participantId
            ])
            ->all();

        return $autobots;
    }

    /**
     * Get list of autobots
     *
     * @param integer $participantId Participant ID
     * @param integer $terminalId    Terminal ID
     * @return Autobot
     */
    public function getAutobotInfo($participantId, $terminalId)
    {
        $autobots = self::find()
            ->select(['autobot_id'])
            ->where([
                'terminal_id' => $terminalId,
                'participant_id' => $participantId
            ])
            ->all();

        $autobotsId = ArrayHelper::getColumn($autobots, 'autobot_id');

        return Autobot::find()->where(['id' => $autobotsId])->all();
    }
}

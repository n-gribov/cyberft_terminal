<?php

namespace common\commands;

use common\commands\CommandAR;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Command accept active record model class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @property integer  $id            Command accept ID
 * @property integer  $userId        ID of user who accept command
 * @property integer  $commandId     ID of command
 * @property string   $acceptResult  Command accept result
 * @property string   $data          Command accept serialized data
 * @property string   $dateCreate    Command accept cretate date
 */
class CommandAcceptAR extends ActiveRecord
{
    const ACCEPT_RESULT_ACCEPTED = 'accepted';
    const ACCEPT_RESULT_REJECTED = 'rejected';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreate',
                'updatedAtAttribute' => 'dateCreate',
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%command_accept}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'commandId', 'acceptResult'], 'required'],
            ['acceptResult', 'in', 'range' => [self::ACCEPT_RESULT_ACCEPTED, self::ACCEPT_RESULT_REJECTED]],
        ];
    }

    /**
     * Get command for accept
     *
     * @return ActiveQuery
     */
    public function getCommand()
    {
        return $this->hasOne(CommandAR::className(), ['id' => 'commandId']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app', 'ID'),
            'userId'       => Yii::t('app', 'User ID'),
            'acceptResult' => Yii::t('app', 'Accept result'),
            'data'         => Yii::t('app', 'Data'),
            'dateCreate'   => Yii::t('app', 'Date of create'),
        ];
    }

    /**
     * Get list of accept result labels
     *
     * @return array
     */
    public function getAcceptResultLabels()
    {
        return [
            self::ACCEPT_RESULT_ACCEPTED => Yii::t('app', 'Accepted'),
            self::ACCEPT_RESULT_REJECTED => Yii::t('app', 'Rejected'),
        ];
    }

    /**
     * Get accept result label
     *
     * @return string
     */
    public function getAcceptResultLabel()
    {
        return !is_null($this->acceptResult) && array_key_exists($this->acceptResult,
                self::getAcceptResultLabels()) ? self::getAcceptResultLabels()[$this->acceptResult]
                : '';
    }

    /**
     * Set serialize data
     *
     * @param mixed $data Data to serialize
     */
    public function setDataSerialize($data)
    {
        $this->data = json_encode($data);
    }
}
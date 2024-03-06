<?php

namespace common\commands;

use common\commands\CommandAcceptAR;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Command active record model class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @property integer  $id            Command ID
 * @property string   $code          Command code
 * @property string   $entity        Command entity
 * @property integer  $entityId      Command entity ID
 * @property integer  $acceptsCount  Count of required accepts for command
 * @property string   $status        Command status
 * @property integer  $userId        ID of user who created command. If command was created by user
 * @property string   $args          Command arguments
 * @property string   $result        Result of command
 * @property string   $dateCreate    Command create date
 * @property string   $dateUpdate    Command update date
 */
class CommandAR extends ActiveRecord
{
    const STATUS_FOR_PERFORM    = 'forPerform';
    const STATUS_FOR_ACCEPTANCE = 'forAcceptance';
    const STATUS_NOT_ACCEPTED   = 'notAccepted';
    const STATUS_EXECUTING      = 'executing';
    const STATUS_EXECUTED       = 'executed';
    const STATUS_FAILED         = 'failed';
    const STATUS_CANCELLED      = 'cancelled';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreate',
                'updatedAtAttribute' => 'dateUpdate',
                'value'              => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%command}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'entity', 'entityId', 'acceptsCount', 'status'], 'required'],
            ['userId', 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app', 'ID'),
            'code'         => Yii::t('app', 'Command code'),
            'entity'       => Yii::t('app', 'Entity'),
            'entityId'     => Yii::t('app', 'Entity ID'),
            'acceptsCount' => Yii::t('app', 'Count of accepts'),
            'status'       => Yii::t('app', 'Status'),
            'userId'       => Yii::t('app', 'User ID'),
            'args'         => Yii::t('app', 'Arguments'),
            'result'       => Yii::t('app', 'Result of command'),
            'dateCreate'   => Yii::t('app', 'Date of create'),
            'dateUpdate'   => Yii::t('app', 'Date of update'),
        ];
    }

    /**
     * Get list of status labels
     *
     * @return array
     */
    public function getStatusLabels()
    {
        return [
            self::STATUS_FOR_PERFORM    => Yii::t('app', 'For perform'),
            self::STATUS_FOR_ACCEPTANCE => Yii::t('app', 'For acceptance'),
            self::STATUS_NOT_ACCEPTED   => Yii::t('app', 'Not accepted'),
            self::STATUS_EXECUTING      => Yii::t('app', 'Executing'),
            self::STATUS_EXECUTED       => Yii::t('app', 'Executed'),
            self::STATUS_FAILED         => Yii::t('app', 'Perform failed'),
            self::STATUS_CANCELLED      => Yii::t('app', 'Cancelled'),
        ];
    }

    /**
     * Get status label
     *
     * @return string
     */
    public function getStatusLabel()
    {
        return !is_null($this->status) && array_key_exists($this->status,
                self::getStatusLabels()) ? self::getStatusLabels()[$this->status]
                : '';
    }

    /**
     * Get accepts for command
     *
     * @return ActiveQuery
     */
    public function getAccepts()
    {
        return $this->hasMany(CommandAcceptAR::className(),
                ['commandId' => 'id']);
    }

    /**
     * Get unserialized command arguments
     *
     * @see http://php.net/manual/en/function.json-decode.php
     * @return mixed
     */
    public function getCommandArgs()
    {
        return json_decode($this->args, true);
    }

    /**
     * Set result data
     *
     * @param array $data Result data
     */
    public function setResultData($data)
    {
        $this->result = json_encode($data);
    }
}
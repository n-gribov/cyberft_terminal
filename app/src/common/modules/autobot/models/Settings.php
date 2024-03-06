<?php

namespace common\modules\autobot\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Settings extends Model
{
    public $terminal_id;
    public $participant_id;
    public $update;

    public function rules()
    {
        return [
            [['terminal_id', 'participant_id'], 'required'],
            [['update'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'terminal_id'       => Yii::t('app/terminal', 'Terminal ID'),
            'participant_id'    => Yii::t('app/autobot', 'Participant address')
        ]);
    }
}
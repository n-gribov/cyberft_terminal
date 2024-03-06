<?php

namespace common\modules\monitor\models;

use yii\db\ActiveRecord;

class CheckerSettingsAR extends ActiveRecord
{
    function behaviors()
    {
        return [
            [
                'class'=>  \common\behaviors\JsonArrayBehavior::className(),
                'attributes'=> [
                    'settingsData' => 'settings',
                    'opSettings' => 'opData'
                ]
            ],
        ];
    }

    public function rules()
    {
        return [
            [['checkerId', 'terminalId'], 'unique', 'targetAttribute' => ['checkerId', 'terminalId']],
            [['active', 'terminalId', 'settings', 'opData'], 'safe'],
            [['checkerId'], 'required']
        ];
    }

    public static function tableName()
    {
        return 'monitor_checker_settings';
    }
}
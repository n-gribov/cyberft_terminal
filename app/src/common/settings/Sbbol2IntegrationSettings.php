<?php

namespace common\settings;

use Yii;
use yii\helpers\ArrayHelper;

class Sbbol2IntegrationSettings extends BaseSettings
{
    public $gatewayUrl = false;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['gatewayUrl', 'string'],
                ['gatewayUrl', 'safe'],
            ]
        );
    }

    public function attributeLabels()
    {
        return [
            'gatewayUrl' => Yii::t('app/settings', 'Serbank gateway URL'),
        ];
    }
}

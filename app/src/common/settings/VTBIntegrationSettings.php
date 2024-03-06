<?php

namespace common\settings;

use Yii;
use yii\helpers\ArrayHelper;

class VTBIntegrationSettings extends BaseSettings
{
    public $enableCryptoProSign = false;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['enableCryptoProSign', 'boolean'],
                ['enableCryptoProSign', 'safe'],
            ]
        );
    }

    public function attributeLabels()
    {
        return [
            'enableCryptoProSign' => Yii::t('app/settings', 'Enable CryptoPro sign'),
        ];
    }
}

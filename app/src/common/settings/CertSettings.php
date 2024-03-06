<?php

namespace common\settings;

use Yii;
use yii\helpers\ArrayHelper;

class CertSettings extends BaseSettings
{
    public $countryName = 'RU';
    public $stateOrProvinceName = 'Moscow';
    public $localityName = 'Moscow';
    public $organizationName = 'CyberFT';

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
        [
            [
                ['countryName', 'stateOrProvinceName', 'localityName',
                    'organizationName', 'commonName'],
                'string',
                'max' => 64
            ],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'countryName'         => Yii::t('app/autobot', 'Country name'),
            'stateOrProvinceName' => Yii::t('app/autobot', 'State or province'),
            'localityName'        => Yii::t('app/autobot', 'Locality name'),
            'organizationName'    => Yii::t('app/autobot', 'Organization name'),
            'commonName'          => Yii::t('app/autobot', 'Common name'),
        ];
    }
}
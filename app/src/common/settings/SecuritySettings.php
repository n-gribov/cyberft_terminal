<?php

namespace common\settings;
use common\modules\certManager\models\Cert;
use yii\helpers\ArrayHelper;

class SecuritySettings extends \common\settings\BaseSettings
{
    public $maxLoginAttemptsCount = 5;
    public $passwordExpireDaysCount = 30;
    public $additionalEncryptCert;
    public $strongPasswordRequired = false;
    public $userPasswordHistoryLength = 0;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
        [
            [
                'maxLoginAttemptsCount', 'integer', 'min' => 3, 'max' => 10
            ],
            ['passwordExpireDaysCount', 'integer', 'min' => 0, 'max' => 365],
            ['additionalEncryptCert', 'integer'],
            [['strongPasswordRequired'], 'boolean'],
            ['userPasswordHistoryLength', 'integer', 'min' => 0],
            ['userPasswordHistoryLength', 'required']
        ]);
    }

    public function attributeLabels()
    {
        return [
            'maxLoginAttemptsCount' => \Yii::t('app/settings', 'Max user login attempts count'),
            'passwordExpireDaysCount' => \Yii::t('app/settings', 'Password expiration days count'),
            'additionalEncryptCert' => \Yii::t('app/settings', 'Additional encryption certificate'),
            'strongPasswordRequired' => \Yii::t('app/settings', 'Require strong password'),
            'userPasswordHistoryLength' => \Yii::t('app/settings', 'Number of previous passwords to check for uniqueness'),
        ];
    }

    public function getCertificateList()
    {
        $result = [];

        foreach(Cert::find()->all() as $cert) {
            $result[$cert->id] = $cert->certId;
        }

        return $result;
    }

    public function getSessionKey()
    {
        return '123qwe';
    }
}
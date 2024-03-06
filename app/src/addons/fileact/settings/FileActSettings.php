<?php
namespace addons\fileact\settings;

use common\settings\BaseSettings;
use yii\helpers\ArrayHelper;
use Yii;

class FileActSettings extends BaseSettings
{
    public $useAutosigning = false;
    public $signaturesNumber = 1;
    public $enableCryptoProSign = false;
    public $cryptoProSignKeys = [];
    public $useSerial = false;
    public $exportXml = false;
    //public $useCompatibleSigning = true;

    public function attributeLabels()
    {
        return [
            'enableCryptoProSign' => Yii::t('app/settings', 'Enable CryptoPro sign'),
            'cryptoProSignKeys'   => Yii::t('app/fileact', 'CryptoPro sign keys'),
            //'useCompatibleSigning' => Yii::t('app/settings', 'Use old signature template'),
            'useSerial' => Yii::t('app/fileact', 'Use certificate serial number instead of hash'),
            'exportXml' => Yii::t('app', 'Activate XML export')
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['enableCryptoProSign', 'useSerial', 'exportXml',
                 //'useCompatibleSigning'
            ], 'boolean'],
        ]);
    }

}
<?php
namespace addons\VTB\settings;

use common\settings\BaseSettings;
use Yii;
use yii\helpers\ArrayHelper;

class VTBSettings extends BaseSettings
{
    public $gatewayUrl = '';
    public $exportXml = false;
    public $signaturesNumber = 1;
    public $useAutosigning = false;
    public $clientCertificate = '';
    public $dontVerifyPeer = false;

    public function attributeLabels()
    {
        return [
            'gatewayUrl'        => Yii::t('app/vtb', 'VTB gateway URL'),
            'exportXml'         => Yii::t('app', 'Activate XML export'),
            'dontVerifyPeer'    => Yii::t('app/vtb', 'Don\'t verify gateway certificate'),
            'clientCertificate' => Yii::t('app/vtb', 'Client certificate'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
            [['exportXml', 'dontVerifyPeer'], 'boolean'],
            [['gatewayUrl', 'clientCertificate'], 'string'],
        ]);
    }
}

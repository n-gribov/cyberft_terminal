<?php
namespace addons\finzip\settings;

use common\settings\BaseSettings;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * @property string $emailImportServerPassword
 */
class FinZipSettings extends BaseSettings
{
    public $signaturesNumber = 1;
    public $useAutosigning = false;
    public $exportXml = false;
    public $enableImportFromEmailServer = false;
    public $emailImportSenderTerminalAddress;
    public $emailImportReceiverTerminalAddress;
    public $emailImportServerHost;
    public $emailImportServerPort;
    public $emailImportServerLogin;
    public $emailImportServerEncryptedPassword;

    public function attributeLabels()
    {
        return [
            'exportXml'                          => Yii::t('app', 'Activate XML export'),
            'enableImportFromEmailServer'        => Yii::t('app/finzip', 'Import documents from email server'),
            'emailImportSenderTerminalAddress'   => Yii::t('app/finzip', 'Sender terminal address'),
            'emailImportReceiverTerminalAddress' => Yii::t('app/finzip', 'Receiver terminal address'),
            'emailImportServerHost'              => Yii::t('app/finzip', 'Email server host'),
            'emailImportServerPort'              => Yii::t('app/finzip', 'Email server port'),
            'emailImportServerLogin'             => Yii::t('app/finzip', 'Email server login'),
            'emailImportServerPassword'          => Yii::t('app/finzip', 'Email server password'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['exportXml', 'enableImportFromEmailServer'], 'boolean'],
                [
                    ['emailImportSenderTerminalAddress', 'emailImportReceiverTerminalAddress', 'emailImportServerHost', 'emailImportServerPort', 'emailImportServerLogin', 'emailImportServerPassword'],
                    'required',
                    'when' => function ($model) { return $model->enableImportFromEmailServer; },
                    'whenClient' => "function (attribute, value) { return $('#finzipsettings-enableimportfromemailserver').is(':checked'); }"
                ],
                ['emailImportServerPort', 'number']
            ]
        );
    }

    public function getEmailImportServerPassword()
    {
        if (!$this->emailImportServerEncryptedPassword) {
            return null;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        return Yii::$app->security->decryptByKey(base64_decode($this->emailImportServerEncryptedPassword), $encryptionKey);
    }

    public function setEmailImportServerPassword($value)
    {
        if (!$value) {
            $this->emailImportServerEncryptedPassword = null;
        } else {
            $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
            $this->emailImportServerEncryptedPassword = base64_encode(Yii::$app->security->encryptByKey($value, $encryptionKey));
        }
    }
}

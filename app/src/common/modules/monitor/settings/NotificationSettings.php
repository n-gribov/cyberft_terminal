<?php
namespace common\modules\monitor\settings;

use common\settings\BaseSettings;
use Yii;

class NotificationSettings extends BaseSettings
{
    public $host = 'localhost';
    public $port = 25;
    public $login = '';
    public $password = '';
    public $encryption = '';

    public $addressList = [];

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['host', 'port'], 'required'],
            ['port', 'default', 'value' => 25],
            ['port', 'integer'],
            [['login', 'password'], 'string', 'max' => 64],
            [['encryption', 'addressList'], 'safe']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'host'       => Yii::t('monitor/mailer', 'SMTP host'),
            'port'       => Yii::t('monitor/mailer', 'SMTP port'),
            'login'      => Yii::t('monitor/mailer', 'Username'),
            'password'   => Yii::t('monitor/mailer', 'Password'),
            'encryption' => Yii::t('monitor/mailer', 'Encryption'),
            'addressList' => Yii::t('monitor/mailer', 'Address list'),
        ];
    }

    public function getEncryptionTypes()
    {
        return [
            ''    => Yii::t('monitor/mailer', 'None'),
            'tls' => 'TLS',
            'ssl' => 'SSL'
        ];
    }
}
<?php
namespace addons\sbbol2\settings;

use common\settings\BaseSettings;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * @property string|null $apiClientSecret
 * @property string|null $tlsKeyPassword
 * @property string $tlsClientCertificatePath
 * @property string $tlsCaBundlePath
 * @property string $tlsKeyPath
 */
class Sbbol2Settings extends BaseSettings
{
    public $apiUrl = null;
    public $apiClientId;
    public $encryptedApiClientSecret;
    public $authorizationUrl;
    public $authorizationApiUrl;
    public $authorizationPartnerScope;
    public $encryptedTlsKeyPassword;
    public $exportXml = false;
    public $signaturesNumber = 1;
    public $useAutosigning = false;

    public function attributeLabels()
    {
        return [
            'apiUrl' => Yii::t('app/sbbol2', 'Sberbank API URL'),
            'apiClientId' => Yii::t('app/sbbol2', 'Client ID'),
            'apiClientSecret' => Yii::t('app/sbbol2', 'Client secret'),
            'authorizationUrl' => Yii::t('app/sbbol2', 'User authorization URL'),
            'authorizationApiUrl' => Yii::t('app/sbbol2', 'Authorization API URL'),
            'authorizationPartnerScope' => Yii::t('app/sbbol2', 'Additional partner scope'),
            'exportXml' => Yii::t('app', 'Activate XML export'),
            'tlsKeyPassword' => Yii::t('app/sbbol2', 'TLS key password'),
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['exportXml'], 'boolean'],
                [['apiUrl', 'apiClientId', 'apiClientSecret', 'authorizationUrl', 'authorizationApiUrl', 'tlsKeyPassword', 'authorizationPartnerScope'], 'string'],
                [['apiUrl', 'apiClientId', 'apiClientSecret', 'authorizationUrl', 'authorizationApiUrl', 'tlsKeyPassword', 'authorizationPartnerScope'], 'trim'],
            ]
        );
    }

    public function getApiClientSecret()
    {
        return $this->decrypt($this->encryptedApiClientSecret);
    }

    public function setApiClientSecret($value)
    {
        $this->encryptedApiClientSecret = $this->encrypt($value);
    }

    public function getTlsKeyPassword()
    {
        return $this->decrypt($this->encryptedTlsKeyPassword);
    }

    public function setTlsKeyPassword($value)
    {
        $this->encryptedTlsKeyPassword = $this->encrypt($value);
    }

    public function getTlsClientCertificatePath(): string
    {
        return Yii::getAlias('@storage/sbbol2/tls/client.pem');
    }

    public function getTlsKeyPath(): string
    {
        return Yii::getAlias('@storage/sbbol2/tls/key.pem');
    }

    public function getTlsCaBundlePath(): string
    {
        return Yii::getAlias('@storage/sbbol2/tls/ca-bundle.pem');
    }

    private function encrypt($value)
    {
        if (!$value) {
            return null;
        }
        return base64_encode(
            Yii::$app->security->encryptByKey(
                $value,
                $this->getEncryptionKey()
            )
        );
    }

    private function decrypt($encryptedValue)
    {
        if (!$encryptedValue) {
            return null;
        }
        return Yii::$app->security->decryptByKey(
            base64_decode($encryptedValue),
            $this->getEncryptionKey()
        );
    }

    private function getEncryptionKey(): string
    {
        return getenv('COOKIE_VALIDATION_KEY');
    }
}

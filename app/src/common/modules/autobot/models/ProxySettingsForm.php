<?php

namespace common\modules\autobot\models;

use Yii;
use yii\base\Model;

class ProxySettingsForm extends Model
{
    public $protocol;
    public $host;
    public $port;
    public $login;
    public $password;

    public function rules()
    {
        return [
            [['protocol', 'host', 'login', 'password'], 'string'],
            ['port', 'integer', 'min' => '1'],
            ['protocol', 'in', 'range' => ['http', 'https']],
            ['host', 'validateHost']
        ];
    }

    public function attributeLabels()
    {
        return [
            'protocol' => Yii::t('app/settings', 'Protocol'),
            'host' => Yii::t('app/settings', 'Host'),
            'port' => Yii::t('app/settings', 'Port'),
            'login' => Yii::t('app/settings', 'Login'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    public static function getProtocolLabels()
    {
        return [
            'http' => 'HTTP',
            'https' => 'HTTPS',
        ];
    }

    public static function readFromEnvFile($envFilePath)
    {
        $envVars = static::readEnvFile($envFilePath);
        $httpProxyUrl = null;
        if (array_key_exists('http_proxy', $envVars)) {
            $httpProxyUrl = $envVars['http_proxy'];
        } else if (array_key_exists('https_proxy', $envVars)) {
            $httpProxyUrl = $envVars['https_proxy'];
        }

        return static::createFromUrl($httpProxyUrl);
    }

    private static function createFromUrl($url)
    {
        $form = new static();
        if (empty($url)) {
            return $form;
        }

        $urlParts = parse_url($url);
        if ($urlParts === null) {
            Yii::warning('Failed to parse proxy URL');
            return $form;
        }

        $form->protocol = $urlParts['scheme'] ?? 'http';
        $form->host = $urlParts['host'] ?? null;
        $form->port = $urlParts['port'] ?? null;
        $form->login = isset($urlParts['user']) ? urldecode($urlParts['user']) : null;
        $form->password = isset($urlParts['pass']) ? urldecode($urlParts['pass']) : null;

        return $form;
    }

    public function saveToEnv($envFilePath)
    {
        try {
            $envVars = static::readEnvFile($envFilePath);
            $httpProxyUrl = $this->createUrl();
            if (!empty($httpProxyUrl)) {
                $envVars['http_proxy'] = $httpProxyUrl;
                $envVars['https_proxy'] = $httpProxyUrl;
            } else {
                unset($envVars['http_proxy']);
                unset($envVars['https_proxy']);
            }
            Yii::info("Writing new env file to $envFilePath");
            static::writeEnvFile($envFilePath, $envVars);
        } catch (\Exception $exception) {
            Yii::warning("Failed to save settings to $envFilePath, caused by: $exception");
            return false;
        }

        return true;
    }

    private static function readEnvFile($envFilePath)
    {
        $lines = file($envFilePath);
        if ($lines === false) {
            throw new \Exception("Failed to read file $envFilePath");
        }
        $envVars = array_reduce(
            $lines,
            function ($carry, $line) {
                $parts = explode('=', trim($line), 2);
                if (count($parts) === 2) {
                    $carry[$parts[0]] = $parts[1];
                }
                return $carry;
            },
            []
        );
        return $envVars;
    }

    private static function writeEnvFile($envFilePath, $envVars)
    {
        copy($envFilePath, $envFilePath . date('.Y-m-d-H-i-s'));
        $content = array_reduce(
            array_keys($envVars),
            function ($carry, $key) use ($envVars) {
                $value = $envVars[$key];
                return $carry . "$key=$value\n";
            },
            ''
        );
        $bytesWritten = file_put_contents($envFilePath, $content);
        if ($bytesWritten === false) {
            throw new \Exception("Failed to write env variables to $envFilePath");
        }
    }

    private function createUrl()
    {
        if (empty($this->host) || empty($this->protocol)) {
            return null;
        }

        $credentials = null;
        if ($this->login) {
            $credentials = urlencode($this->login);
            if ($this->password) {
                $credentials .= ':' . urlencode($this->password);
            }
        }

        return "{$this->protocol}://"
            . ($credentials ? "$credentials@" : '')
            . $this->host
            . ($this->port ? ":{$this->port}" : '');
    }

    public function validateHost($attribute, $params = [])
    {
        if (static::isValidIp($this->$attribute) || static::isValidHostname($this->$attribute)) {
            return;
        }

        $this->addError(
            $attribute,
            Yii::t('yii', '{attribute} is invalid.', ['attribute' => $this->getAttributeLabel($attribute)])
        );
    }

    private static function isValidIp($value)
    {
        $ipRegex = '/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/';
        return (bool)preg_match($ipRegex, $value);

    }

    private static function isValidHostname($value)
    {
        $hostnameRegex = '/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/';
        return (bool)preg_match($hostnameRegex, $value);
    }
}

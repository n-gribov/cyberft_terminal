<?php

namespace addons\sbbol2\components;

use addons\sbbol2\apiClient\Configuration;
use addons\sbbol2\Sbbol2Module;
use addons\sbbol2\settings\Sbbol2Settings;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Yii;
use yii\base\Component;

class ApiFactory extends Component
{
    public function create(string $apiClass)
    {
        $settings = Yii::$app->getModule(Sbbol2Module::SERVICE_ID)->getSettings();
        return new $apiClass(
            $this->createClient($settings),
            $this->createConfiguration($settings)
        );
    }

    private function createClient(Sbbol2Settings $settings): ClientInterface
    {
        return new Client([
            'cert'    => $settings->tlsClientCertificatePath,
            'ssl_key' => [$settings->tlsKeyPath, $settings->tlsKeyPassword],
            'verify'  => $settings->tlsCaBundlePath,
            'timeout' => 30,
        ]);
    }

    private function createConfiguration(Sbbol2Settings $settings): Configuration
    {
        $configuration = (new Configuration());
        $configuration->setHost($settings->apiUrl);

        if (YII_DEBUG) {
            $configuration->setDebug(true);
            $configuration->setDebugFile(Yii::getAlias('@logs/fintech-api-debug.log'));
        }

        return $configuration;
    }
}

<?php

namespace common\components\processingApi;

use common\components\processingApi\api\BaseApi;
use common\models\Processing;
use GuzzleHttp\Client;
use Yii;
use yii\base\Component;

class ProcessingApiFactory extends Component
{
    public function create(string $apiClass): BaseApi
    {
        if (!is_subclass_of($apiClass, BaseApi::class)) {
            throw new \InvalidArgumentException("Invalid API class: $apiClass");
        }
        $client = new Client([
            'base_uri' => $this->getBaseUri(),
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
                'User-Agent'   => Yii::$app->name . '/' . Yii::$app->version,
            ],
        ]);
        return new $apiClass($client);
    }

    private function getBaseUri(): string
    {
        $processingAddress = Yii::$app->terminals->getProcessingAddress();
        $processing = Processing::findOne(['address' => $processingAddress]);
        return $processing->apiUrl . '/v1/';
    }
}

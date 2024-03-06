<?php

namespace common\components\processingApi\api;

use common\components\processingApi\models\Certificate;
use GuzzleHttp\Psr7\Request;

class ProcessingApi extends BaseApi
{
    public function getCertificate(): Certificate
    {
        $request = new Request(static::HTTP_GET, 'processing/certificate');
        $response = $this->sendRequest($request);
        return Certificate::fromJson($response->getBody()->getContents());
    }
}

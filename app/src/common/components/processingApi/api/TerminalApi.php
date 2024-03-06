<?php

namespace common\components\processingApi\api;

use common\components\processingApi\models\TerminalStompCredentials;
use common\components\processingApi\SignatureKey;
use GuzzleHttp\Psr7\Request;

class TerminalApi extends BaseApi
{
    public function updateStompCredentials(TerminalStompCredentials $credentials, SignatureKey $signatureKey): bool
    {
        $request = new Request(static::HTTP_POST, 'terminal/stomp-credentials', [], $credentials->toJson());
        $signedRequest = $this->signRequest($request, $signatureKey);
        $this->sendRequest($signedRequest);
        return true;
    }
}

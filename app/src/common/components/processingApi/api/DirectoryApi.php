<?php

namespace common\components\processingApi\api;

use common\components\processingApi\models\directory\Directory;
use common\components\processingApi\SignatureKey;
use GuzzleHttp\Psr7\Request;

class DirectoryApi extends BaseApi
{
    public function get(?string $lastVersion, SignatureKey $signatureKey): Directory
    {
        $headers = $lastVersion
            ? ['If-None-Match' => $lastVersion]
            : [];

        $request = new Request(static::HTTP_GET, 'directory', $headers);
        $signedRequest = $this->signRequest($request, $signatureKey);
        $response = $this->sendRequest($signedRequest);

        if ($response->getStatusCode() === 304) {
            return new Directory(false, $lastVersion);
        }

        $this->ensureResponseSignatureIsValid($response);

        return new Directory(
            true,
            $response->getHeader('ETag')[0],
            $response->getBody()->getContents()
        );
    }
}

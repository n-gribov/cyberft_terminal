<?php

namespace common\components\processingApi\api;

use common\components\processingApi\models\Certificate;
use common\components\processingApi\SignatureKey;
use GuzzleHttp\Psr7\Request;

class CertificateApi extends BaseApi
{
    public function get(string $terminalAddress, string $fingerprint, SignatureKey $signatureKey): Certificate
    {
        $certificateCode = "$terminalAddress-$fingerprint";
        $request = new Request(static::HTTP_GET, "certificate/$certificateCode");
        $signedRequest = $this->signRequest($request, $signatureKey, ['certificate' => $certificateCode]);
        $response = $this->sendRequest($signedRequest);

        return Certificate::fromJson($response->getBody()->getContents());
    }

    public function register(Certificate $certificate, SignatureKey $signatureKey): Certificate
    {
        $request = new Request(static::HTTP_POST, 'certificate', [], $certificate->toJson());
        $signedRequest = $this->signRequest($request, $signatureKey);
        $response = $this->sendRequest($signedRequest);

        return Certificate::fromJson($response->getBody()->getContents());
    }

    public function block(string $terminalAddress, string $fingerprint, SignatureKey $signatureKey): Certificate
    {
        $certificateCode = "$terminalAddress-$fingerprint";
        $request = new Request(static::HTTP_DELETE, "certificate/$certificateCode");
        $signedRequest = $this->signRequest($request, $signatureKey, ['certificate' => $certificateCode]);
        $response = $this->sendRequest($signedRequest);

        return Certificate::fromJson($response->getBody()->getContents());
    }
}

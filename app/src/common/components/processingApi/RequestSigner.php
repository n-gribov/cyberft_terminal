<?php

namespace common\components\processingApi;

use Psr\Http\Message\RequestInterface;

class RequestSigner
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var SignatureKey
     */
    private $signatureKey;

    /**
     * @var array|null
     */
    private $signedParams;

    public function __construct(RequestInterface $request, SignatureKey $signatureKey, ?array $signedParams)
    {
        $this->request = $request;
        $this->signedParams = $signedParams;
        $this->signatureKey = $signatureKey;
    }

    public function sign(): RequestInterface
    {
        $signedData = $this->createSignedData();
        return $this->request
            ->withAddedHeader(HttpSignatureHeaders::SIGNATURE, $this->doSign($signedData))
            ->withAddedHeader(HttpSignatureHeaders::KEY_CODE, $this->signatureKey->keyCode)
            ->withAddedHeader(HttpSignatureHeaders::ALGORITHM, 'sha256');
    }

    private function doSign(string $data): string
    {
        $keyResource = openssl_get_privatekey($this->signatureKey->body, $this->signatureKey->password);
        if ($keyResource === false) {
            throw new \Exception('Cannot use provided private key');
        }
        openssl_sign($data, $binarySignature, $keyResource, OPENSSL_ALGO_SHA256);
        openssl_free_key($keyResource);
        return base64_encode($binarySignature);
    }

    private function createSignedData(): string
    {
        return empty($this->signedParams)
            ? $this->request->getBody()->getContents()
            : $this->createSignedDataFromParams();
    }

    private function createSignedDataFromParams(): string
    {
        $sortedParams = $this->signedParams;
        ksort($sortedParams);
        $pairs = array_map(
            function ($key) use ($sortedParams) {
                return "$key={$sortedParams[$key]}";
            },
            array_keys($sortedParams)
        );
        return join('&', $pairs);
    }
}

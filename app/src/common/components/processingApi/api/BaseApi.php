<?php

namespace common\components\processingApi\api;

use common\components\processingApi\exceptions\ApiException;
use common\components\processingApi\HttpSignatureHeaders;
use common\components\processingApi\RequestSigner;
use common\components\processingApi\SignatureKey;
use common\modules\certManager\models\Cert;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class BaseApi
{
    public const HTTP_GET = 'GET';
    public const HTTP_POST = 'POST';
    public const HTTP_DELETE = 'DELETE';

    /**
     * @var ClientInterface
     */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        } catch (RequestException $exception) {
            $this->processRequestException($exception);
        }
    }

    private function processRequestException(RequestException $exception)
    {
        \Yii::$app->errorHandler->logException($exception);
        $statusCode = $exception->getCode();
        if ($statusCode < 200 || $statusCode > 299) {
            throw new ApiException($exception->getMessage(), $exception->getCode(), $exception);
        }
        throw $exception;
    }

    protected function signRequest(RequestInterface $request, SignatureKey $signatureKey, ?array $signedParams = null): RequestInterface
    {
        $signer = new RequestSigner($request, $signatureKey, $signedParams);
        return $signer->sign();
    }

    protected function ensureResponseSignatureIsValid(ResponseInterface $response): void
    {
        $keyCode = $response->getHeader(HttpSignatureHeaders::KEY_CODE)[0] ?? null;
        if (!$keyCode) {
            throw new \Exception('Missing signature key code in response');
        }
        list($terminalAddress, $fingerprint) = explode('-', $keyCode);
        $certificate = Cert::findByAddress($terminalAddress, $fingerprint);
        if (!$certificate) {
            throw new \Exception("Response is signed with unknown key: $keyCode");
        }

        $response->getBody()->rewind();
        $content = $response->getBody()->getContents();
        $response->getBody()->rewind();
        $verifyResult = openssl_verify(
            $content,
            base64_decode($response->getHeader(HttpSignatureHeaders::SIGNATURE)[0] ?? ''),
            $certificate->getCertificateContent(),
            OPENSSL_ALGO_SHA256
        );
        if ($verifyResult !== 1) {
            throw new \Exception('Got invalid response signature');
        }
    }
}

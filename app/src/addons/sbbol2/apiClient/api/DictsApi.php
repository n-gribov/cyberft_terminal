<?php
/**
 * DictsApi
 * PHP version 5
 *
 * @category Class
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */

/**
 * FINTECH
 *
 * Документация FINTECH REST API
 *
 * OpenAPI spec version: 1
 *
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 3.0.8
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace addons\sbbol2\apiClient\api;

use addons\sbbol2\apiClient\ApiException;
use addons\sbbol2\apiClient\Configuration;
use addons\sbbol2\apiClient\HeaderSelector;
use addons\sbbol2\apiClient\ObjectSerializer;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

/**
 * DictsApi Class Doc Comment
 *
 * @category Class
 *
 * @author   Swagger Codegen team
 *
 * @see     https://github.com/swagger-api/swagger-codegen
 */
class DictsApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var HeaderSelector
     */
    protected $headerSelector;

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation getDictUsingGET
     *
     * Получение справочника
     *
     * @param string $authorization Access token организации-клиента, полученный через SSO (required)
     * @param string $name          Идентификатор справочника (required)
     *
     * @throws \addons\sbbol2\apiClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     *
     * @return \common\models\sbbol2\DictArchive
     */
    public function getDictUsingGET($authorization, $name)
    {
        list($response) = $this->getDictUsingGETWithHttpInfo($authorization, $name);

        return $response;
    }

    /**
     * Operation getDictUsingGETWithHttpInfo
     *
     * Получение справочника
     *
     * @param string $authorization Access token организации-клиента, полученный через SSO (required)
     * @param string $name          Идентификатор справочника (required)
     *
     * @throws \addons\sbbol2\apiClient\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     *
     * @return array of \common\models\sbbol2\DictArchive, HTTP status code, HTTP response headers (array of strings)
     */
    public function getDictUsingGETWithHttpInfo($authorization, $name)
    {
        $returnType = '\common\models\sbbol2\DictArchive';
        $request = $this->getDictUsingGETRequest($authorization, $name);

        try {
            $options = $this->createHttpClientOption();

            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if (!in_array($returnType, ['string', 'integer', 'bool'])) {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders(),
            ];
        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\common\models\sbbol2\DictArchive',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);

                    break;
                case 400:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\common\models\sbbol2\ResourceFault',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);

                    break;
                case 401:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\common\models\sbbol2\Notice',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);

                    break;
                case 403:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\common\models\sbbol2\Notice',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);

                    break;
                case 500:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\common\models\sbbol2\Notice',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);

                    break;
            }

            throw $e;
        }
    }

    /**
     * Operation getDictUsingGETAsync
     *
     * Получение справочника
     *
     * @param string $authorization Access token организации-клиента, полученный через SSO (required)
     * @param string $name          Идентификатор справочника (required)
     *
     * @throws \InvalidArgumentException
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getDictUsingGETAsync($authorization, $name)
    {
        return $this->getDictUsingGETAsyncWithHttpInfo($authorization, $name)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation getDictUsingGETAsyncWithHttpInfo
     *
     * Получение справочника
     *
     * @param string $authorization Access token организации-клиента, полученный через SSO (required)
     * @param string $name          Идентификатор справочника (required)
     *
     * @throws \InvalidArgumentException
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getDictUsingGETAsyncWithHttpInfo($authorization, $name)
    {
        $returnType = '\common\models\sbbol2\DictArchive';
        $request = $this->getDictUsingGETRequest($authorization, $name);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders(),
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();

                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'getDictUsingGET'
     *
     * @param string $authorization Access token организации-клиента, полученный через SSO (required)
     * @param string $name          Идентификатор справочника (required)
     *
     * @throws \InvalidArgumentException
     *
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function getDictUsingGETRequest($authorization, $name)
    {
        // verify the required parameter 'authorization' is set
        if ($authorization === null || (is_array($authorization) && count($authorization) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $authorization when calling getDictUsingGET'
            );
        }
        // verify the required parameter 'name' is set
        if ($name === null || (is_array($name) && count($name) === 0)) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $name when calling getDictUsingGET'
            );
        }

        $resourcePath = '/v1/dicts';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // query params
        if ($name !== null) {
            $queryParams['name'] = ObjectSerializer::toQueryValue($name);
        }
        // header params
        if ($authorization !== null) {
            $headerParams['Authorization'] = ObjectSerializer::toHeaderValue($authorization);
        }

        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['*/*']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['*/*'],
                []
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue,
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);
            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);
            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);

        return new Request(
            'GET',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     *
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}

<?php
namespace addons\raiffeisen\components;

use addons\raiffeisen\components\RaiffeisenTransport\LoginResult;
use addons\raiffeisen\components\RaiffeisenTransport\SendAsyncResult;
use addons\raiffeisen\components\RaiffeisenTransport\SendRequestResult;
use addons\raiffeisen\helpers\SecureRemotePassword;
use addons\raiffeisen\models\ErrorRequestId;
use addons\raiffeisen\models\RaiffeisenCustomer;
use addons\raiffeisen\models\RaiffeisenRequest;
use addons\raiffeisen\models\soap\request\GetRequestStatusRequest;
use addons\raiffeisen\models\soap\request\LoginRequest;
use addons\raiffeisen\models\soap\request\PreLoginRequest;
use addons\raiffeisen\models\soap\request\SendRequestsRequest;
use addons\raiffeisen\models\soap\response\SendRequestsResponse;
use addons\raiffeisen\RaiffeisenModule;
use common\helpers\raiffeisen\RaiffeisenDocumentHelper;
use common\helpers\raiffeisen\RaiffeisenXmlSerializer;
use common\models\raiffeisenxml\request\Request;
use common\models\raiffeisenxml\response\Response;
use Exception;
use SoapClient;
use Yii;
use yii\base\Component;

class RaiffeisenTransport extends Component
{
    private const NONEXISTENT_SESSION_RESPONSE = '<!--NONEXISTENT SESSION-->';

    /** @var RaiffeisenModule */
    private $module;

    public function init()
    {
        parent::init();
        $this->module = Yii::$app->getModule(RaiffeisenModule::SERVICE_ID);
    }

    /**
     * Отправка запроса в УПШ через вызов sendRequests
     *
     * @param Request $requestDocument
     * @param int $holdingHeadCustomerId
     * @param string|null $digest
     * @param int $recreateSessionAttemptsCount
     * @return SendRequestResult
     * @throws Exception
     */
    public function sendRequest(
        Request $requestDocument,
        $holdingHeadCustomerId,
        $digest = null,
        int $recreateSessionAttemptsCount = 10
    ): SendRequestResult {
        $sessionId = $this->module->sessionManager->findOrCreateSession($holdingHeadCustomerId);
        $requestXmlContent = RaiffeisenXmlSerializer::serialize($requestDocument);
        $requestMessage = new SendRequestsRequest([
            'requests'  => RaiffeisenXmlSerializer::createCdataNode('ns1', 'requests', $requestXmlContent),
            'sessionId' => $sessionId,
        ]);

        /** @var SendRequestsResponse $responseMessage */
        $responseMessage = $this->send($requestMessage, $digest);

        if (is_array($responseMessage->return) && count($responseMessage->return) !== 1) {
            Yii::warning('Only one return element is expected in SendRequestsResponse');

            return new SendRequestResult(false, null, 'Invalid response');
        }

        $return = is_array($responseMessage->return) ? $responseMessage->return[0] : $responseMessage->return;

        /**
         * return field value may be:
         * 1. request id: XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
         * 2. special error request id: 00000000-0000-0000-0000-0000000000XX
         * 3. XML with error description wrapped in Response document
         * 4. <!--REQUESTID_DUBLIC -->
         */

        if (strpos($return, '<Response ') !== false) {
            /** @var Response $response */
            $response = RaiffeisenXmlSerializer::deserialize($return, Response::class);
            $errorMessage = !empty($response->getErrors()) ? $response->getErrors()[0]->getDesc() : 'Unknown error';
            Yii::warning("Got error response: $errorMessage");

            return new SendRequestResult(false, null, $errorMessage);
        }

        if ($return === self::NONEXISTENT_SESSION_RESPONSE) {
            if ($recreateSessionAttemptsCount === 0) {
                return new SendRequestResult(false, null, 'Invalid session');
            }
            Yii::info("Got session error, will try with new session, attempts left: $recreateSessionAttemptsCount");
            $this->module->sessionManager->deleteSession($holdingHeadCustomerId);
            usleep(rand(1000000, 5000000));
            return $this->sendRequest($requestDocument, $holdingHeadCustomerId, $digest, $recreateSessionAttemptsCount - 1);
        }

        if (!preg_match('/^[a-z\d]{8}-[a-z\d]{4}-[a-z\d]{4}-[a-z\d]{4}-[a-z\d]{12}$/', $return)) {
            Yii::warning("Got invalid response: $return");

            return new SendRequestResult(false, null, 'Invalid response');
        }

        $raiffeisenRequestId = $return;

        if (ErrorRequestId::isErrorRequestId($raiffeisenRequestId)) {
            $errorMessage = ErrorRequestId::getErrorDescription($raiffeisenRequestId);
            Yii::warning("Got error response: $errorMessage");

            return new SendRequestResult(false, null, $errorMessage);
        }

        return new SendRequestResult(true, $raiffeisenRequestId, null);
    }

    public function fetchRequestStatuses(array $requestsIds, int $holdingHeadCustomerId, int $recreateSessionAttemptsCount = 10): array
    {
        $requestMessage = new GetRequestStatusRequest([
            'sessionId' => $this->module->sessionManager->findOrCreateSession($holdingHeadCustomerId),
            'requests' => $requestsIds
        ]);
        $responseMessage = $this->send($requestMessage);
        $responsesBodies = $responseMessage->return;

        if ($responsesBodies === self::NONEXISTENT_SESSION_RESPONSE && $recreateSessionAttemptsCount > 0) {
            Yii::info("Got session error, will try with new session, attempts left: $recreateSessionAttemptsCount");
            $this->module->sessionManager->deleteSession($holdingHeadCustomerId);
            usleep(rand(1000000, 5000000));
            return $this->fetchRequestStatuses($requestsIds, $holdingHeadCustomerId, $recreateSessionAttemptsCount - 1);
        }

        if ($responsesBodies === null) {
            return [];
        } else if (is_array($responsesBodies)) {
            return $responsesBodies;
        } else {
            return [$responsesBodies];
        }
    }

    /**
     * Отправка запроса в УПШ через вызов sendRequests + его регистрация в очереди на отложенную обработку результата
     *
     * @param Request $requestDocument
     * @param integer $customerId
     * @param array|null $responseHandlerParams
     * @param string|null $digest
     * @param integer|null $incomingDocumentId
     * @param string|null $holdingHeadCustomerId
     * @return SendAsyncResult
     * @throws Exception
     */
    public function sendAsync(
        Request $requestDocument,
        $customerId,
        $responseHandlerParams = null,
        $digest = null,
        $incomingDocumentId = null,
        $holdingHeadCustomerId = null
    ): SendAsyncResult {
        $documentType = RaiffeisenDocumentHelper::detectRequestDocumentType($requestDocument);
        if (!$documentType) {
            throw new \Exception('Cannot detect document type');
        }

        if (empty($holdingHeadCustomerId)) {
            $customer = RaiffeisenCustomer::findOne($customerId);
            if ($customer === null) {
                throw new \Exception("Customer $customerId is not found");
            }
            $holdingHeadCustomerId = $customer->holdingHeadId;
        }

        $sendRequestResult = $this->sendRequest($requestDocument, $holdingHeadCustomerId, $digest);

        if (!$sendRequestResult->isSent()) {
            return new SendAsyncResult(false, null, $sendRequestResult->getErrorMessage());
        }

        $raiffeisenRequest = new RaiffeisenRequest([
            'customerId'            => $customerId,
            'status'                => RaiffeisenRequest::STATUS_SENT,
            'senderRequestId'       => $requestDocument->getRequestId(),
            'receiverRequestId'     => $sendRequestResult->getRequestId(),
            'documentType'          => $documentType,
            'responseHandlerParams' => $responseHandlerParams,
            'incomingDocumentId'    => $incomingDocumentId,
            'holdingHeadCustomerId' => $holdingHeadCustomerId,
        ]);

        // Сохранить модель в БД
        $isSaved = $raiffeisenRequest->save();
        if (!$isSaved) {
            throw new \Exception('Failed to save request to database, errors: ' . var_export($raiffeisenRequest->getErrors(), true));
        }

        return new SendAsyncResult(true, $raiffeisenRequest, null);
    }

    public function loginByCredentials(string $userLogin, string $password): LoginResult
    {
        Yii::info('Logging in to Raiffeisen by credentials');

        $preLoginResponse = $this->send(new PreLoginRequest(['userLogin' => $userLogin]));
        list($salt, $b) = $preLoginResponse->return;

        $secureRemotePassword = new SecureRemotePassword(
            $userLogin,
            $password,
            $salt,
            $b
        );

        $loginRequest = new LoginRequest([
            'userLogin' => $userLogin,
            'clientAuthData' => [
                $secureRemotePassword->getK(),
                $secureRemotePassword->getA(),
            ]
        ]);

        $loginResponse = $this->send($loginRequest);

        if ($loginResponse->return === 'BAD_CREDENTIALS') {
            Yii::info('Invalid credentials');
            return new LoginResult(false, null);
        }

        $sessionId = $loginResponse->return;
        return new LoginResult(true, $sessionId);
    }


    /**
     * Вызов метода УПШ через SOAP - общий случай
     *
     * @param $request
     * @param string|null $digest
     * @param bool $logRequest
     * @return object
     * @throws Exception
     */
    private function send($request, $digest = null, $logRequest = true)
    {
        $requestName = $this->detectRequestName($request);
        $client = $this->createClient();
        try {
            $response = $client->$requestName($request);
        } catch (\Exception $exception) {
            throw $exception;
        } finally {
            if ($logRequest) {
                $this->logRequest(
                    $requestName,
                    $client->__getLastRequest(),
                    $client->__getLastResponse(),
                    $digest
                );
            }
        }

        $responseClass = $this->getResponseClassForRequest(get_class($request));

        return $this->castToResponseClass($response, $responseClass);
    }

    private function createClient()
    {
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ]);

        $options = [
            'uri'            => 'http://upg.sbns.bssys.com/',
            'trace'          => 1,
            'stream_context' => $context
        ];

        $proxyUrlParseResult = parse_url(getenv('http_proxy'));
        if ($proxyUrlParseResult && isset($proxyUrlParseResult['host'])) {
            $options['proxy_host'] = $proxyUrlParseResult['host'];
            if (isset($proxyUrlParseResult['port'])) {
                $options['proxy_port'] = $proxyUrlParseResult['port'];
            }
        }

        $wsdlUrl = $this->module->settings->gatewayUrl;
        if (empty($wsdlUrl)) {
            throw new \Exception('Raiffeisen WSDL URL is not set');
        }

        return new SoapClient(
            $wsdlUrl,
            $options
        );
    }

    private function getResponseClassForRequest($requestClass)
    {
        return preg_replace(
            '/^(.*?)\\\\request\\\\(.*?)Request$/',
            '$1\\\\response\\\\$2Response',
            $requestClass
        );
    }

    private function castToResponseClass($response, $responseClass)
    {
        $attributes = get_object_vars($response);

        return new $responseClass($attributes);
    }

    private function logRequest($name, $body, $responseBody, $digest = null)
    {
        Yii::info("Request: $name, $body");
        Yii::info("Digest: $digest");
        Yii::info("Response: $responseBody");
    }

    private function detectRequestName($request)
    {
        $shortClassName = (new \ReflectionClass($request))->getShortName();
        return lcfirst(preg_replace('/Request$/', '', $shortClassName));
    }
}

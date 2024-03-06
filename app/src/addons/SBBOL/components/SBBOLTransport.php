<?php
namespace addons\SBBOL\components;

use addons\SBBOL\components\SBBOLTransport\LoginResult;
use addons\SBBOL\components\SBBOLTransport\SendAsyncResult;
use addons\SBBOL\components\SBBOLTransport\SendRequestResult;
use addons\SBBOL\helpers\SBBOLSignHelper;
use addons\SBBOL\helpers\SecureRemotePassword;
use addons\SBBOL\models\AuthReturnCode;
use addons\SBBOL\models\ErrorRequestId;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\models\SBBOLRequestLogRecord;
use addons\SBBOL\models\soap\request\LoginRequest;
use addons\SBBOL\models\soap\request\LoginSignRequest;
use addons\SBBOL\models\soap\request\PreLoginRequest;
use addons\SBBOL\models\soap\request\PreLoginSignRequest;
use addons\SBBOL\models\soap\request\SendRequestsSRPRequest;
use addons\SBBOL\models\soap\response\SendRequestsSRPResponse;
use addons\SBBOL\SBBOLModule;
use common\helpers\sbbol\SBBOLDocumentHelper;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\FraudParams;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\response\Response;
use Exception;
use SoapClient;
use Yii;
use yii\base\Component;

class SBBOLTransport extends Component
{
    /**
     * Вызов метода УПШ через SOAP - общий случай
     *
     * @param $request
     * @param string|null $digest
     * @return object
     */
    public function send($request, $digest = null, $log = true)
    {
        $requestName = $this->detectRequestName($request);
        $client = $this->createClient();
        $response =  $client->$requestName($request);

        if ($log) {
            $this->logRequest(
                $requestName,
                $client->__getLastRequest(),
                $client->__getLastResponse(),
                $digest
            );
        }

        $responseClass = $this->getResponseClassForRequest(get_class($request));

        return $this->castToResponseClass($response, $responseClass);
    }

    /**
     * Отправка запроса в УПШ через вызов sendRequestsSRP
     *
     * @param Request $requestDocument
     * @param $sessionId
     * @param string|null $digest
     * @return SendRequestResult
     */
    public function sendRequest(Request $requestDocument, $sessionId, $digest = null): SendRequestResult
    {
        $requestXmlContent = SBBOLXmlSerializer::serialize($requestDocument);
        $requestMessage = new SendRequestsSRPRequest([
            'sessionId' => $sessionId,
            'requests'  => SBBOLXmlSerializer::createCdataNode('ns1', 'requests', $requestXmlContent)
        ]);

        /** @var SendRequestsSRPResponse $responseMessage */
        $responseMessage = $this->send($requestMessage, $digest);

        if (is_array($responseMessage->return) && count($responseMessage->return) !== 1) {
            Yii::warning('Only one return element is expected in SendRequestsSRPResponse');

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
            $response = SBBOLXmlSerializer::deserialize($return, Response::class);
            $errorMessage = !empty($response->getErrors()) ? $response->getErrors()[0]->getDesc() : 'Unknown error';
            Yii::warning("Got error response: $errorMessage");

            return new SendRequestResult(false, null, $errorMessage);
        }

        if (!preg_match('/^[a-z\d]{8}\-[a-z\d]{4}\-[a-z\d]{4}\-[a-z\d]{4}\-[a-z\d]{12}$/', $return)) {
            Yii::warning("Got invalid response: $return");

            return new SendRequestResult(false, null, 'Invalid response');
        }

        $sbbolRequestId = $return;

        if (ErrorRequestId::isErrorRequestId($sbbolRequestId)) {
            $errorMessage = ErrorRequestId::getErrorDescription($sbbolRequestId);
            Yii::warning("Got error response: $errorMessage");

            if ($sbbolRequestId === '00000000-0000-0000-0000-000000000002') {
                /** @var SBBOLModule $module */
                $module = Yii::$app->getModule('SBBOL');
                $module->sessionManager->deleteSession($requestDocument->getOrgId());
            }

            return new SendRequestResult(false, null, $errorMessage);
        }

        return new SendRequestResult(true, $sbbolRequestId, null);
    }

    /**
     * Отправка запроса в УПШ через вызов sendRequestsSRP + его регистрация в очереди на отложенную обработку результата
     *
     * @param Request $requestDocument
     * @param string $sessionId
     * @param array|null $responseHandlerParams
     * @param string|null $digest
     * @param integer|null $incomingDocumentId
     * @param string|null $holdingHeadCustomerId
     * @return SendAsyncResult
     * @throws Exception
     */
    public function sendAsync(
        Request $requestDocument,
        $sessionId,
        $responseHandlerParams = null,
        $digest = null,
        $incomingDocumentId = null,
        $holdingHeadCustomerId = null
    ): SendAsyncResult {
        $documentType = SBBOLDocumentHelper::detectRequestDocumentType($requestDocument);
        if (!$documentType) {
            throw new \Exception('Cannot detect document type');
        }

        $sendRequestResult = $this->sendRequest($requestDocument, $sessionId, $digest);

        if (!$sendRequestResult->isSent()) {
            return new SendAsyncResult(false, null, $sendRequestResult->getErrorMessage());
        }

        $customerId = $requestDocument->getOrgId();

        if (empty($holdingHeadCustomerId)) {
            $customer = SBBOLCustomer::findOne($customerId);
            if ($customer === null) {
                throw new \Exception("Customer $customerId is not found");
            }
            $holdingHeadCustomerId = $customer->holdingHeadId;
        }

        $sbbolRequest = new SBBOLRequest([
            'customerId'            => $customerId,
            'status'                => SBBOLRequest::STATUS_SENT,
            'senderRequestId'       => $requestDocument->getRequestId(),
            'receiverRequestId'     => $sendRequestResult->getRequestId(),
            'documentType'          => $documentType,
            'responseHandlerParams' => $responseHandlerParams,
            'incomingDocumentId'    => $incomingDocumentId,
            'holdingHeadCustomerId' => $holdingHeadCustomerId,
        ]);

        // Сохранить модель в БД
        $isSaved = $sbbolRequest->save();
        if (!$isSaved) {
            throw new \Exception('Failed to save request to database, errors: ' . var_export($sbbolRequest->getErrors(), true));
        }

        return new SendAsyncResult(true, $sbbolRequest, null);
    }

    /**
     * @param $userLogin
     * @param $password
     * @return LoginResult
     */
    public function loginByCredentials($userLogin, $password)
    {
        Yii::info('Logging in to SBBOL by credentials');

        $preLoginResponse = $this->send(new PreLoginRequest(['userLogin' => $userLogin]));

        list($salt, $b, $sessionId, $preLoginReturnCodeBin) = $preLoginResponse->return;

        $preLoginReturnCode = new AuthReturnCode($preLoginReturnCodeBin);
        if ($preLoginReturnCode->getId() !== AuthReturnCode::SUCCESS) {
            Yii::info("PreLoginRequest failed, error: {$preLoginReturnCode->getId()}, {$preLoginReturnCode->getDescription()}");

            return new LoginResult(false, $preLoginReturnCode, null);
        }

        $secureRemotePassword = new SecureRemotePassword(
            $userLogin,
            $password,
            $salt,
            $b
        );

        $loginRequest = new LoginRequest([
            'sessionId' => $sessionId,
            'clientAuthData' => [
                $secureRemotePassword->getK(),
                $secureRemotePassword->getA(),
            ]
        ]);

        $loginResponse = $this->send($loginRequest);

        list($m2Bin, $returnCodeBin, $sessionId) = $loginResponse->return;

        $returnCode = new AuthReturnCode($returnCodeBin);
        $isLoggedIn = $returnCode->getId() === AuthReturnCode::SUCCESS;

        if (!$isLoggedIn) {
            Yii::info("LoginRequest failed, error: {$returnCode->getId()}, {$returnCode->getDescription()}");
        }

        return new LoginResult(
            $isLoggedIn,
            $returnCode,
            $isLoggedIn ? $sessionId : null
        );
    }

    /**
     * @param SBBOLKey $key
     * @return LoginResult
     */
    public function loginByKey(SBBOLKey $key)
    {
        $preLoginRequest = new PreLoginSignRequest([
            'serial' => $key->certificateSerial,
            'issue' => $key->certificateIssuer,
        ]);
        $preLoginResponse = $this->send($preLoginRequest);
        list($nothing, $salt, $sessionId, $preLoginReturnCodeBin) = $preLoginResponse->return;

        $preLoginReturnCode = new AuthReturnCode($preLoginReturnCodeBin);
        if ($preLoginReturnCode->getId() !== AuthReturnCode::SUCCESS) {
            Yii::info("PreLoginSignRequest failed, error: {$preLoginReturnCode->getId()}, {$preLoginReturnCode->getDescription()}");

            return new LoginResult(false, $preLoginReturnCode, null);
        }

        $signature = SBBOLSignHelper::sign($salt, $key->certificateFingerprint, $key->keyPassword);
        if ($signature === false) {
            Yii::info('Failed to sign login request');

            return new LoginResult(false, new AuthReturnCode(null), null);
        }

        $fraudParams = (new FraudParams())
            ->setDevicePrint('null')
            ->setChannelIndicator('SBB')
            ->setIpMACAddresses('null')
            ->setGeolocationInfo('null')
            ->setPcProp('null')
            ->setTokenInfo('null')
            ->setHttpAcceptLanguage('ru-RU');
        $fraudParamsXml = SBBOLXmlSerializer::serialize($fraudParams);

        $loginSignRequest = new LoginSignRequest([
            'sessionId' => $sessionId,
            'clientAuthData' => $signature,
            'fraudParams' => $fraudParamsXml,
        ]);

        $loginSignResponse = $this->send($loginSignRequest);
        list($nothing, $returnCodeBin, $sessionId) = $loginSignResponse->return;

        $returnCode = new AuthReturnCode($returnCodeBin);
        $isLoggedIn = $returnCode->getId() === AuthReturnCode::SUCCESS;

        if (!$isLoggedIn) {
            Yii::info("LoginSignRequest failed, error: {$returnCode->getId()}, {$returnCode->getDescription()}");
        }

        return new LoginResult(
            $isLoggedIn,
            $returnCode,
            $isLoggedIn ? $sessionId : null
        );
    }

    private function createClient()
    {
        $module = Yii::$app->getModule('SBBOL');

        $context = stream_context_create([
            'ssl' => [
                // set some SSL/TLS specific options
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        return new SoapClient(
            'https://localhost/SBBOL/wsdl-proxy',
            [
                'uri'   => 'http://upg.sbns.bssys.com/',
                'trace' => 1,
                'stream_context' => $context
            ]
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
        $logRecord = new SBBOLRequestLogRecord(compact('name', 'body', 'responseBody', 'digest'));

        try {
            // Сохранить модель в БД
            $isSaved = $logRecord->save();
            if (!$isSaved) {
                throw new Exception('save() failed, model errors: ' . var_export($logRecord->errors));
            }
        } catch (Exception $exception) {
            Yii::warning('Failed to log request, caused by: ' . $exception);
        }
    }

    private function detectRequestName($request)
    {
        $shortClassName = (new \ReflectionClass($request))->getShortName();

        return lcfirst(preg_replace('/Request$/', '', $shortClassName));
    }

}

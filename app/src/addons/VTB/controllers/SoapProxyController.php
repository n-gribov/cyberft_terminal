<?php

namespace addons\VTB\controllers;

use addons\VTB\utils\curl\Curl;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SoapProxyController extends Controller
{
    const CURL_EXECUTABLE = '/opt/cprocsp/bin/amd64/curl';
    const LOCALHOST = 'localhost';
    const LOCALHOST_IP = '127.0.0.1';

    public $enableCsrfValidation = false;
    public $layout = false;

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->checkAccess();
            return true;
        }

        return false;
    }

    /**
     * Метод обрабатывает запрос, направленный на страницу индекса
     */
    public function actionIndex()
    {
        try {
            $this->processRequest();
        } catch (\Exception $exception) {
            Yii::warning("Request failed, caused by $exception");
            Yii::$app->response->setStatusCode(500);
            return null;
        }
    }

    private function processRequest()
    {
        Yii::$app->response->format = Response::FORMAT_XML;

        $queryString = Yii::$app->request->queryString;
        $settings = $this->module->settings;
        $url = $settings->gatewayUrl . '?' . $queryString;

        $curl = (new Curl())
            ->executablePath(static::CURL_EXECUTABLE)
            ->url($url)
            ->headers($this->getRequestHeaders())
            ->cert($settings->clientCertificate)
            ->insecure($settings->dontVerifyPeer);

        $response = Yii::$app->request->isPost
            ? $curl->post(Yii::$app->request->getRawBody())
            : $curl->get();

        $responseBody = $response->getBody();
        if (empty($responseBody)) {
            Yii::warning("Soap proxy got empty response from $url");
            Yii::$app->response->content = '';
            return;
        }

        $isInvalidResponse = strpos($responseBody, '<?xml') !== 0 && strpos($responseBody, '<SOAP') !== 0;
        if ($isInvalidResponse) {
            $request = Yii::$app->request->isPost ? Yii::$app->request->getRawBody() : '';
            $requestForLogs = preg_replace('/^(.{1500}).*?(.{1500})$/s', '$1 ... $2', $request);
            Yii::warning(
                "Soap proxy got response from $url which does not look like SOAP/WSDL message: \""
                . mb_strimwidth($responseBody, 0, 1000, '...') . '"'
                . ' Request was: "'
                . $requestForLogs . '"'
            );
        }

        if ($this->isWsdlRequest($queryString)) {
            $responseBody = $this->replaceLocationInWsdl($responseBody);
        }

        Yii::$app->response->content = $responseBody;
    }

    private function isWsdlRequest($queryString)
    {
        return strpos($queryString, 'wsdl/') === 0;
    }

    private function replaceLocationInWsdl($wsdlBody)
    {
        $dom = simplexml_load_string($wsdlBody);
        $xpath = array_reduce(
            ['definitions', 'service', 'port', 'address'],
            function ($carry, $node) {
                return "$carry/*[local-name()='$node']";
            },
            ''
        );
        $addressNode = $dom->xpath($xpath)[0];
        $queryString = parse_url($addressNode->attributes()->location, PHP_URL_QUERY);
        $addressNode->attributes()->location = Url::to('/VTB/soap-proxy', true) . '?' . $queryString;
        return $dom->saveXML();
    }

    private function getRequestHeaders()
    {
        $safeHeadersKeys = ['user-agent', 'content-type', 'soapaction'];
        $headers = Yii::$app->request->getHeaders();
        return array_reduce(
            array_keys($headers->toArray()),
            function ($carry, $headerKey) use ($safeHeadersKeys, $headers) {
                if (in_array(strtolower($headerKey), $safeHeadersKeys)) {
                    $carry[$headerKey] = $headers->get($headerKey);
                }
                return $carry;
            },
            []
        );
    }

    private function checkAccess()
    {
        $hasAccess = $_SERVER['REMOTE_ADDR'] === static::LOCALHOST_IP
            && $_SERVER['SERVER_ADDR'] === static::LOCALHOST_IP
            && $_SERVER['HTTP_HOST'] === static::LOCALHOST;
        if (!$hasAccess) {
            Yii::info("Request is not allowed: REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}, SERVER_ADDR: {$_SERVER['SERVER_ADDR']}, HTTP_HOST: {$_SERVER['HTTP_HOST']}");
            throw new NotFoundHttpException();
        }
    }
}

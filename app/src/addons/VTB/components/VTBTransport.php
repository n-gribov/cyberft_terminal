<?php


namespace addons\VTB\components;


use addons\VTB\models\soap\messages\BaseMessage;
use SoapClient;
use Yii;
use yii\base\Component;

class VTBTransport extends Component
{
    const SOAP_URI = 'http://tempuri.org/';
    const SOAP_PROXY_BASE_URL = 'https://localhost/VTB/soap-proxy';

    public function send($serviceName, $requestName, BaseMessage $request)
    {
        $wsdlUrl = $this->buildWsdlUrl($serviceName);
        $client = $this->createClient($wsdlUrl);
        $response = call_user_func_array([$client, $requestName], $request->getPropertiesAsArray());

        $this->logRequest(
            $serviceName,
            $requestName,
            $client->__getLastRequest(),
            $client->__getLastResponse()
        );

        $responseClass = $this->getResponseClassForRequest(get_class($request));
        return $this->castToResponseClass($response, $responseClass);
    }

    private function createClient($wsdlUrl)
    {
        return new SoapClient(
            $wsdlUrl,
            [
                'soap_version' => SOAP_1_1,
                'uri' => static::SOAP_URI,
                'trace' => 1,
                'connection_timeout' => 300,
                'stream_context' => stream_context_create([
                    'ssl' => [
                        'verify_peer'       => false,
                        'verify_peer_name'  => false,
                        'allow_self_signed' => true
                    ]
                ])
            ]
        );
    }

    private function getResponseClassForRequest($requestClass)
    {
        return preg_replace(
            '/^(.*?)\\\\(.*?)Request$/',
            '$1\\\\$2Response',
            $requestClass
        );
    }

    private function castToResponseClass(array $responseProperties, $responseClass)
    {
        return new $responseClass($responseProperties);
    }

    private function logRequest($serviceName, $requestName, $request, $response)
    {
        Yii::info(
            "Request $serviceName/$requestName: "
            . mb_strimwidth($request, 0, 50000, '...')
        );
        Yii::info(
            "Response for $serviceName/$requestName: "
            . mb_strimwidth($response, 0, 50000, '...')
        );
    }

    private function buildWsdlUrl($serviceName)
    {
        return sprintf('%s?wsdl/%s', static::SOAP_PROXY_BASE_URL, $serviceName);
    }
}

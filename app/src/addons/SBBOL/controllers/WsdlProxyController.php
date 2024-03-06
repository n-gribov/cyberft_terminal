<?php

namespace addons\SBBOL\controllers;

use addons\SBBOL\settings\SBBOLSettings;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class WsdlProxyController extends Controller
{
    const LOCALHOST = 'localhost';
    const LOCALHOST_IP_V4 = '127.0.0.1';
    const LOCALHOST_IP_V6 = '::1';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $this->checkAccess();

            return true;
        }

        return false;
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        /** @var SBBOLSettings $settings */
        $settings = $this->module->settings;
        $gatewayUrl = $settings->gatewayUrl;
        if (empty($gatewayUrl)) {
            throw new \Exception('SBBOL gateway URL is not set');
        }

        $wsdlFilePath = Yii::getAlias('@addons/SBBOL/resources/schema/request/upg.wsdl');
        $wsdlContent = file_get_contents($wsdlFilePath);

        return str_replace('${upg.endpoint}', $gatewayUrl, $wsdlContent);
    }

    private function checkAccess()
    {
        $hasAccess = self::isLocalhostIp($_SERVER['REMOTE_ADDR'])
            && self::isLocalhostIp($_SERVER['SERVER_ADDR'])
            && $_SERVER['HTTP_HOST'] === static::LOCALHOST;
        if (!$hasAccess) {
            Yii::info("Request is not allowed: REMOTE_ADDR: {$_SERVER['REMOTE_ADDR']}, SERVER_ADDR: {$_SERVER['SERVER_ADDR']}, HTTP_HOST: {$_SERVER['HTTP_HOST']}");
            throw new NotFoundHttpException();
        }
    }

    private static function isLocalhostIp($ip)
    {
        return in_array($ip, [self::LOCALHOST_IP_V4, self::LOCALHOST_IP_V6]);
    }
}

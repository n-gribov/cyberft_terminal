<?php

namespace addons\SBBOL\helpers;

use addons\SBBOL\models\SBBOLKey;
use common\helpers\DsgostSigner;
use common\helpers\sbbol\SBBOLDocumentDigestBuilder;
use common\helpers\sbbol\SBBOLDocumentHelper;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\models\sbbolxml\request\DigitalSignType;
use common\models\sbbolxml\request\FraudType;
use common\models\sbbolxml\request\Request;
use common\modules\certManager\components\ssl\X509FileModel;
use Yii;

class SBBOLSignHelper
{
    const DSGOSTXML_COMMAND_BASE = 'LD_LIBRARY_PATH="$LD_LIBRARY_PATH:/opt/cprocsp/lib/amd64" /var/www/cyberft/app/src/bin/dsgostxml';

    public static function sign($data, $certId, $keyPassword)
    {
        $signer = new DsgostSigner(true);
        return $signer->signCMS($data, $certId, $keyPassword);
    }

    public static function verify($data, $signature, $certId)
    {
        $signer = new DsgostSigner(true);
        return $signer->verify($data, $signature, $certId);
    }

    /**
     * @param Request $requestDocument
     * @param string $customerId
     * @return array [$isSigned, $digest]
     */
    public static function signRequestDocument(Request $requestDocument, string $customerId): array
    {
        $key = SBBOLKey::findActiveByCustomer($customerId);
        $digest = null;

        try {
            if ($key === null) {
                throw new \Exception("Cannot find active key for customer $customerId");
            }

            $documentType = SBBOLDocumentHelper::detectRequestDocumentType($requestDocument);
            if ($documentType === null) {
                throw new \Exception("Cannot detect document type");
            }

            $xml = SBBOLXmlSerializer::serialize($requestDocument);
            $digest = SBBOLDocumentDigestBuilder::build($xml, $documentType);

            $signature = SBBOLSignHelper::sign($digest, $key->certificateFingerprint, $key->keyPassword);

            if ($signature === false) {
                throw new \Exception('Digest was not signed');
            }

            $certificateX509 = X509FileModel::loadData($key->certificateBody);
            $issuer = SBBOLHelper::createCertificateIssuerString($certificateX509);

            $fraud = (new FraudType())
                ->setLogin($key->keyOwner->customer->login)
                ->setTokenInfo('null')
                ->setHttpAcceptLanguage('ru-RU')
                ->setIpMACAddresses('null')
                ->setGeolocationInfo('null')
                ->setPcProp('null')
                ->setDevicePrint('null');

            $requestDocument->addToSign(
                (new DigitalSignType())
                    ->setSN($key->certificateSerial)
                    ->setIssuer($issuer)
                    ->setValue(base64_encode($signature))
                    ->setFraud($fraud)
            );
        } catch (\Exception $exception) {
            Yii::info("Request signing has failed, caused by: $exception");

            return [false, null];
        }

        return [true, $digest];
    }

}
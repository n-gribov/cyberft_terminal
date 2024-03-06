<?php

namespace addons\SBBOL\states\in\sbbolDocument\processOrganizationInfoSteps;

use addons\SBBOL\helpers\CryptoProHelper;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLKey;
use addons\SBBOL\models\SBBOLRequest;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\sbbolDocument\ProcessOrganizationInfoState;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\Uuid;
use common\models\sbbolxml\request\ActivateCertType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\response\CertificateType;
use common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType;
use common\models\sbbolxml\SBBOLTransportConfig;
use common\modules\certManager\components\ssl\X509FileModel;

/**
 * @property ProcessOrganizationInfoState $state
 */
class SaveNewPublishedCertificateStep extends BaseStep
{
    public function run()
    {
        $request = SBBOLRequest::findOne($this->state->requestId);
        $keyId = $request->getResponseHandlerParam('keyId');
        $key = SBBOLKey::findOne($keyId);
        $pubKey = $key->publicKey;
        $signDeviceId = $key->keyOwner->signDeviceId;

        /** @var SignDeviceAType $keyOwnerSignDevice */
        $keyOwnerSignDevice = static::findFirstInArray(
            $this->state->sbbolDocument->getSignDevices(),
            function (SignDeviceAType $signDevice) use ($signDeviceId) {
                return $signDevice->getSignDeviceId() === $signDeviceId
                        && $signDevice->getCryptoTypeName() == 'Инфокрипт';
            }
        );

        if ($keyOwnerSignDevice === null) {
            return $this->fail("Sign device $signDeviceId is not found in the response");
        }

        /** @var CertificateType $keyOwnerCertificate */
        $keyOwnerCertificate = static::findFirstInArray(
            $keyOwnerSignDevice->getCertificates(),
            function (CertificateType $cert) use ($pubKey) {
                if ($cert->getActive() || !$cert->getValid() || !$cert->getClient()) {
                    return false;
                }

                $certData = base64_decode($cert->value());
                $pub_key = openssl_pkey_get_public($certData);
                $keyData = openssl_pkey_get_details($pub_key);
                $certPubKey = bin2hex(base64_decode(trim(str_replace(
                    ['-----BEGIN PUBLIC KEY-----', '-----END PUBLIC KEY-----'],
                    '',
                    $keyData['key']
                ))));

                return substr($certPubKey, -strlen($pubKey)) == $pubKey;
            }
        );

        if ($keyOwnerCertificate === null) {
            return $this->fail("Inactive valid certificate for sign device $signDeviceId is not found in the response");
        }

        $certificateBody = base64_decode($keyOwnerCertificate->value());
        if (!X509FileModel::isCertificate($certificateBody)) {
            return $this->fail('Got invalid certificate body');
        }

        $certificateX509 = X509FileModel::loadData($certificateBody);

        $key->certificateFingerprint = $certificateX509->getFingerprint();
        $key->certificateSerial = $keyOwnerCertificate->getSN();
        $key->certificateBody = $certificateBody;
        $key->status = SBBOLKey::STATUS_CERTIFICATE_IS_RECEIVED;

        $isSaved = $key->save();
        if (!$isSaved) {
            return $this->fail('Failed to save key, errors: ' . var_export($key->getErrors(), true));
        }

        $certificateFilePath = tempnam('/tmp', '');
        try {
            file_put_contents($certificateFilePath, $certificateBody);
            CryptoProHelper::installCertificateIntoContainer(
                $certificateFilePath,
                $key->keyContainerName,
                $key->keyPassword
            );
        } catch (\Exception $exception) {
            return $this->fail("Certificate is not imported, caused by: $exception");
        } finally {
            unlink($certificateFilePath);
        }

        $requestResult = $this->sendActivateCertificateRequest($key->keyOwner->customer, $certificateX509, $key);
        if (!$requestResult->isSent()) {
            return $this->fail('Failed to send ActivateCert document');
        }

        return true;
    }

    private static function findFirstInArray(array $array, callable $callback)
    {
        foreach ($array as $item) {
            if ($callback($item)) {
                return $item;
            }
        }

        return null;
    }

    private static function findLastInArray(array $array, callable $callback)
    {
        $lastMatchingItem = null;
        foreach ($array as $item) {
            if ($callback($item)) {
                $lastMatchingItem = $item;
            }
        }

        return $lastMatchingItem;
    }

    private function fail($logMessage)
    {
        $this->log($logMessage);
        SBBOLRequest::updateStatus($this->state->requestId, SBBOLRequest::STATUS_RESPONSE_PROCESSING_ERROR);

        return false;
    }

    private function sendActivateCertificateRequest(SBBOLCustomer $customer, X509FileModel $certificateX509, SBBOLKey $key)
    {
        $activateCertDocument = (new ActivateCertType())
            ->setIssuer(SBBOLHelper::createCertificateIssuerString($certificateX509))
            ->setSN($key->certificateSerial);

        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setActivateCert($activateCertDocument);

        $sessionId = $this->state->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        return $this->state->module->transport->sendAsync(
            $requestDocument,
            $sessionId,
            ['keyId' => $key->id]
        );
    }
}

<?php

namespace addons\SBBOL\console;

use addons\SBBOL\helpers\CryptoProHelper;
use addons\SBBOL\helpers\PrintableCertificate;
use addons\SBBOL\models\SBBOLCustomer;
use addons\SBBOL\models\SBBOLCustomerKeyOwner;
use addons\SBBOL\models\SBBOLKey;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\helpers\Uuid;
use common\models\Country;
use common\models\sbbolxml\request\ActivateCertType;
use common\models\sbbolxml\request\CertifRequestType;
use common\models\sbbolxml\request\ParamType;
use common\models\sbbolxml\request\PersonalInfoType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\request\RequestType\CryptoIncomingAType;
use common\models\sbbolxml\response\CertificateType;
use common\models\sbbolxml\response\OrganizationInfoType\SignDevicesAType\SignDeviceAType;
use common\models\sbbolxml\response\Response;
use common\models\sbbolxml\SBBOLTransportConfig;
use common\modules\certManager\components\ssl\X509FileModel;
use yii\helpers\VarDumper;

class CertificateController extends BaseController
{
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/certificate']);
    }

    public function actionListKeyOwners($customerId)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer $customerId is not found\n";
            return;
        }

        $keyOwners = $customer->keyOwners;
        if (empty($keyOwners)) {
            echo "Customer $customerId has no keys owners\n";
            return;
        }

        foreach ($keyOwners as $i => $keyOwner) {
            $n = $i + 1;
            echo "$n. {$keyOwner->id} - {$keyOwner->fullName}\n";
        }
    }

    public function actionGenerateCertificateRequestData($keyOwnerId, $email)
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($keyOwnerId);
        if ($keyOwner === null) {
            echo "Key owner $keyOwnerId is not found\n";
            return;
        }

        $customer = $keyOwner->customer;
        $countryCode = Country::convertCodeToAlfa2($customer->countryCode);

        $subject = sprintf(
            'C=%s,L=%s,O=%s,OU=%s,E=%s,CN=%s,T=%s',
            $countryCode,
            $customer->addressSettlement,
            $customer->shortName,
            $customer->bankBranchSystemName,
            $email,
            $keyOwner->fullName,
            $keyOwner->position
        );

        echo "$subject\n";
        echo 'bicryptid=' . static::createBicryptId($keyOwner) . "\n";
        echo "exporteble=1\n";
    }

    public function actionCreatePrintableCertificate($keyOwnerId, $certificateRequestFilePath, $publicKeyFilePath)
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($keyOwnerId);
        if ($keyOwner === null) {
            echo "Key owner $keyOwnerId is not found\n";
            return;
        }

        try {
            $certificateRequestBody = static::readFile($certificateRequestFilePath);
            $publicKeyBody = static::readFile($publicKeyFilePath);
        } catch (\Exception $exception) {
            echo "{$exception->getMessage()}\n";
            return;
        }

        $bicryptId = static::createBicryptId($keyOwner);
        $documentContent = PrintableCertificate::generateForKeyOwner(
            $keyOwner,
            $bicryptId,
            $certificateRequestBody,
            $publicKeyBody
        );

        $fileName = "$bicryptId.docx";
        $saveFileResult = file_put_contents($fileName, $documentContent);
        if ($saveFileResult) {
            echo "Printable certificate is saved to $fileName\n";
        } else {
            echo "Failed to save printable certificate to $fileName\n";
        }
    }

    public function actionSendCertificateRequest($keyOwnerId, $certificateEmail, $certificateRequestFilePath)
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($keyOwnerId);
        if ($keyOwner === null) {
            echo "Key owner $keyOwnerId is not found\n";
            return;
        }

        try {
            $certificateRequestBody = static::readFile($certificateRequestFilePath);
        } catch (\Exception $exception) {
            echo "{$exception->getMessage()}\n";
            return;
        }

        $certRequestId = Uuid::generate(false)->toString();
        $documentExtId = Uuid::generate(false)->toString();
        $docNum = substr(time(), strlen(time()) - 6);

        $customer = $keyOwner->customer;
        $countryCode = Country::convertCodeToAlfa2($customer->countryCode);

        $certRequestDocument = (new CertifRequestType())
            ->setRequestId($certRequestId)
            ->setDocDate(new \DateTime())
            ->setDocNum($docNum)
            ->setIdCrypto($keyOwner->signDeviceId)
            ->setDocExtId($documentExtId)
            ->setCryptoAlgorithmStandard('GOST_2012')
            ->setCommonName($keyOwner->fullName)
            ->setOrganization($customer->shortName)
            ->setOrganizationUnit($customer->bankBranchSystemName)
            ->setLocality($customer->addressSettlement)
            ->setCountry($countryCode)
            ->setEmail($certificateEmail)
            ->setPosition($keyOwner->position);

        $bicryptId = static::createBicryptId($keyOwner);

        $certRequestDocument->setDocs([
            (new CertifRequestType\DocsAType\DocAType())
                ->setType('sign')
                ->setAttachment(
                    (new CertifRequestType\DocsAType\DocAType\AttachmentAType())
                        ->setAttachmentName(Uuid::generate(false)->toString() . '.p10')
                        ->setBody(base64_encode($certificateRequestBody))
                )
                ->addToParams(
                    (new ParamType())
                        ->setName('bicryptId')
                        ->setValue($bicryptId)
                )
        ]);

        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setCertifRequest($certRequestDocument);

        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);

        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($responseBody, Response::class);
        if (empty($responseDocument->getTickets())) {
            echo "Response has no status information:\n$responseBody\n";
        } else {
            echo "Request status: {$responseDocument->getTickets()[0]->getInfo()->getStatusStateCode()}\n";
            echo "Use command 'SBBOL/request/track-request-status {$customer->id} {$responseDocument->getRequestId()}' to track request status\n";
            echo "Use command 'SBBOL/request/track-document-status {$customer->id} {$responseDocument->getRequestId()}' to track document status\n";
        }
    }

    public function actionSendCertificateActivationRequest($keyOwnerId)
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($keyOwnerId);
        if ($keyOwner === null) {
            echo "Key owner $keyOwnerId is not found\n";
            return;
        }
        $customer = $keyOwner->customer;

        echo "Fetching new published certificate...\n";
        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        list($isFound, $certificateBody, $certificateSerial) = $this->getNewPublishedCertificate($keyOwner, $sessionId);
        if (empty($isFound)) {
            return;
        }

        $certificateX509 = X509FileModel::loadData($certificateBody);
        $activateCertDocument = (new ActivateCertType())
            ->setIssuer(SBBOLKey::createIssuerString($certificateX509))
            ->setSN($certificateSerial);

        $requestDocument = (new Request())
            ->setRequestId(Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setActivateCert($activateCertDocument);

        echo "Sending certificate activation request...\n";
        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($responseBody, Response::class);
        if (empty($responseDocument->getTickets())) {
            echo "Response has no status information:\n$responseBody\n";
        } else {
            echo "Request status: {$responseDocument->getTickets()[0]->getInfo()->getStatusStateCode()}\n";
            echo "Use command 'SBBOL/request/track-request-status {$customer->id} {$responseDocument->getRequestId()}' to track request status\n";
        }
    }

    public function actionGetCertificates($customerId)
    {
        $customer = SBBOLCustomer::findOne($customerId);
        if ($customer === null) {
            echo "Customer $customerId is not found\n";
            return;
        }

        $cryptoIncoming = (new CryptoIncomingAType())
            ->setCertificates(
                (new CryptoIncomingAType\CertificatesAType())
                    ->setBank(true)
                    ->setRoot(true)
                    ->setClient(true)
            );

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false)->toString())
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setCryptoIncoming($cryptoIncoming);

        echo "Sending certificates request...\n";
        $sessionId = $this->module->sessionManager->findOrCreateSession($customer->holdingHeadId);
        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($responseBody, Response::class);

        $getCertificateTypeName = function (CertificateType $certificate) {
            if ($certificate->getClient()) {
                return 'Client';
            } elseif ($certificate->getRoot()) {
                return 'Root';
            } elseif ($certificate->getBank()) {
                return 'Bank';
            }
            return 'Unknown type';
        };

        foreach ($responseDocument->getCertificates() as $certificate) {
            $type = $getCertificateTypeName($certificate);
            $serial = $certificate->getSN() ?: 'N/A';
            $body = base64_decode($certificate->value());
            echo "$type certificate, serial: $serial\n$body\n";
        }
    }

    public function actionInstallKey($keyOwnerId, $bicryptId, $certificateRequestFilePath, $certificateFilePath, $publicKeyFilePath, $containerZipPath, $containerPassword)
    {
        $keyOwner = SBBOLCustomerKeyOwner::findOne($keyOwnerId);
        if ($keyOwner === null) {
            echo "Key owner $keyOwnerId is not found\n";
            return;
        }

        try {
            $certificateRequestBody = static::readFile($certificateRequestFilePath);
            $publicKeyBody = static::readFile($publicKeyFilePath);
            $certificateBody = static::readFile($certificateFilePath);
        } catch (\Exception $exception) {
            echo "{$exception->getMessage()}\n";
            return;
        }

        try {
            $containerName = CryptoProHelper::importContainerFromZipFile($containerZipPath);
            echo "Container is imported to $containerName\n";
        } catch (\Exception $exception) {
            echo "Failed to import container\n";
            return;
        }

        try {
            CryptoProHelper::installCertificateIntoContainer($certificateFilePath, $containerName, $containerPassword);
        } catch (\Exception $exception) {
            echo "Failed to import certificate\n";
            return;
        }

        $certificateX509 = X509FileModel::loadData($certificateBody);

        $key = new SBBOLKey([
            'keyOwnerId'             => $keyOwnerId,
            'keyContainerName'       => $containerName,
            'keyPassword'            => $containerPassword,
            'publicKey'              => $publicKeyBody,
            'certificateBody'        => $certificateBody,
            'certificateRequest'     => $certificateRequestBody,
            'bicryptId'              => $bicryptId,
            'certificateSerial'      => $certificateX509->getRawData()['serialNumberHex'],
            'certificateFingerprint' => $certificateX509->getFingerprint(),
            'status'                 => SBBOLKey::STATUS_ACTIVE,
        ]);

        $isSaved = $key->save();
        if (!$isSaved) {
            echo 'Failed to save key to database, errors: ' . VarDumper::dumpAsString($key->errors) . "\n";
            return;
        }

        echo "Key is successfully installed\n";
    }

    private function getNewPublishedCertificate(SBBOLCustomerKeyOwner $keyOwner, $sessionId)
    {
        $customer = $keyOwner->customer;
        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false))
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setOrgId($customer->id)
            ->setSender($customer->senderName)
            ->setPersonalInfo(new PersonalInfoType());

        $responseBody = $this->sendRequestAndGetStatus($requestDocument, $sessionId);

        /** @var Response $responseDocument */
        $responseDocument = SBBOLXmlSerializer::deserialize($responseBody, Response::class);

        $signDeviceId = $keyOwner->signDeviceId;

        /** @var SignDeviceAType $keyOwnerSignDevice */
        $keyOwnerSignDevice = static::findFirstInArray(
            $responseDocument->getOrganizationsInfo()[0]->getSignDevices(),
            function (SignDeviceAType $signDevice) use ($signDeviceId) {
                return $signDevice->getSignDeviceId() === $signDeviceId;
            }
        );

        if ($keyOwnerSignDevice === null) {
            echo "Sign device $signDeviceId is not found in the response\n";
            return [false, null, null];
        }

        /** @var CertificateType $keyOwnerCertificate */
        $keyOwnerCertificate = static::findLastInArray(
            $keyOwnerSignDevice->getCertificates(),
            function (CertificateType $certificate) {
                return !$certificate->getActive() && $certificate->getValid() && $certificate->getClient();
            }
        );

        if ($keyOwnerCertificate === null) {
            echo "Inactive valid certificate for sign device $signDeviceId is not found in the response\n";
            return [false, null, null];
        }

        $certificateBody = base64_decode($keyOwnerCertificate->value());
        if (!X509FileModel::isCertificate($certificateBody)) {
            echo "Got invalid certificate body\n";
            return [false, null, null];
        }

        return [true, $certificateBody, $keyOwnerCertificate->getSN()];
    }

    private static function createBicryptId(SBBOLCustomerKeyOwner $keyOwner)
    {
        $customer = $keyOwner->customer;
        $certAuthId = $customer->certAuthId;
        $certNumberHex = strtoupper(dechex($customer->lastCertNumber + 1));

        $ownerNameParts = preg_split('/\s+/', $keyOwner->fullName);
        $ownerLastName = $ownerNameParts[0] ?? '';
        $ownerInitials = array_slice($ownerNameParts, 1);
        $ownerId = $ownerLastName
            . implode(
                '',
                array_map(
                    function ($item) { return mb_substr($item, 0, 1); },
                    $ownerInitials
                )
            );

        return $certAuthId
            . str_pad($certNumberHex, 8 - strlen($certAuthId), '0', STR_PAD_LEFT)
            . 's'
            . $ownerId;
    }

    private static function readFile($path) {
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \Exception("Failed to read file $path");
        }

        return $content;
    }

    private static function findFirstInArray(array $array, callable $callback)
    {
        foreach ($array as $item) {
            if ($callback($item)) {
                return $item;
            }
        };

        return null;
    }

    private static function findLastInArray(array $array, callable $callback)
    {
        $lastMatchingItem = null;
        foreach ($array as $item) {
            if ($callback($item)) {
                $lastMatchingItem = $item;
            }
        };

        return $lastMatchingItem;
    }
}

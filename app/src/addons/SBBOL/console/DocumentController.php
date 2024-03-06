<?php

namespace addons\SBBOL\console;

use addons\edm\models\BaseSBBOLDocument\BaseSBBOLRequestDocumentType;
use addons\SBBOL\helpers\SBBOLSignHelper;
use common\document\Document;
use common\helpers\sbbol\SBBOLDocumentDigestBuilder;
use common\helpers\sbbol\SBBOLDocumentHelper;
use common\helpers\sbbol\SBBOLXmlSerializer;
use common\jobs\DocumentContentSignJob;
use common\models\cyberxml\CyberXmlDocument;

class DocumentController extends BaseController
{
    public function actionIndex()
    {
        $this->run('/help', ['SBBOL/document']);
    }

    public function actionSignAndSend($documentId, $certificateFingerprint, $keyPassword)
    {
        $document = Document::findOne($documentId);
        if ($document === null) {
            echo "Document $documentId is not found\n";
            return;
        }

        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);

        /** @var BaseSBBOLRequestDocumentType $typeModel */
        $typeModel = $cyxDocument->getContent()->getTypeModel();

        $documentType = SBBOLDocumentHelper::detectRequestDocumentType($typeModel->request);
        if ($documentType === null) {
            echo "Cannot detect document type";
            return;
        }

        $xml = SBBOLXmlSerializer::serialize($typeModel->request);
        $digest = SBBOLDocumentDigestBuilder::build($xml, $documentType);

        $signature = SBBOLSignHelper::sign($digest, $certificateFingerprint, $keyPassword);
        if ($signature === false) {
            echo "Failed to sign document\n";
            return;
        }

        $certBody = $this->getCryptoProCertificateBody($certificateFingerprint);
        if (empty($certBody)) {
            return;
        }

        $job = new DocumentContentSignJob();
        $job->args = [
            'id' => $documentId,
            'signature' => base64_encode($signature),
            'certBody' => $certBody
        ];
        $job->setUp();
        $job->perform();
    }

    private static function getCryptoProCertificateBody($fingerprint)
    {
        $tmpFileName = tempnam('/tmp', '');
        try {
            $cmd = "/opt/cprocsp/bin/amd64/certmgr -export -cert -thumbprint $fingerprint -base64 -dest $tmpFileName";
            $output = shell_exec($cmd);
            if (strpos($output, '[ErrorCode: 0x00000000]') === false) {
                throw new \Exception("certmgr has returned error: $output");
            }

            $certificateBody = file_get_contents($tmpFileName);
            if (strpos($certificateBody, '-----BEGIN CERTIFICATE') !== 0) {
                $certificateBody = "-----BEGIN CERTIFICATE-----\n" . trim($certificateBody) . "\n-----END CERTIFICATE-----\n";
            }
            return $certificateBody;
        } catch (\Exception $exception) {
            echo "Cannot export certificate from CryptoPro, caused by: {$exception->getMessage()}\n";
            return null;
        } finally {
            if (file_exists($tmpFileName)) {
                unlink($tmpFileName);
            }
        }
    }
}

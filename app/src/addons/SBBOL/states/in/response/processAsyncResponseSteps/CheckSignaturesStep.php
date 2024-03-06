<?php

namespace addons\SBBOL\states\in\response\processAsyncResponseSteps;

use addons\SBBOL\helpers\ResponseDocumentsExtractor;
use addons\SBBOL\helpers\SBBOLSignHelper;
use addons\SBBOL\models\SBBOLCertificate;
use addons\SBBOL\states\BaseStep;
use addons\SBBOL\states\in\response\ProcessAsyncResponseState;
use common\helpers\sbbol\SBBOLDocumentDigestBuilder;
use common\models\sbbolxml\response\DigitalSignType;

/**
 * @property ProcessAsyncResponseState $state
 */
class CheckSignaturesStep extends BaseStep
{
    const SIGNABLE_DOCUMENTS_TYPES = [
        'OrganizationsInfo',
        'Statements',
    ];

    public function run()
    {
        try {
            return $this->checkSignatures();
        } catch (\Exception $exception) {
            $this->log("Failed to check signatures, caused by: $exception");

            return false;
        }
    }

    private function checkSignatures()
    {
        $documentsExtractor = new ResponseDocumentsExtractor($this->state->response);
        $documentsTypes = $documentsExtractor->getDocumentsTypes();

        foreach ($documentsTypes as $documentType) {
            if (!self::isSignableDocumentType($documentType)) {
                $this->log("Skipping signature check for document type $documentType");
                continue;
            }

            foreach ($documentsExtractor->getDocuments($documentType) as $i => $document) {
                $this->log("Checking signature: document type: $documentType, document index: $i");

                /** @var DigitalSignType $signatureInfo */
                $signatureInfo = $this->getSignatureInfo($document);
                if ($signatureInfo === null) {
                    $this->log('No signature found');
                    continue;
                }

                try {
                    $digest = SBBOLDocumentDigestBuilder::build($this->state->responseBody, $documentType, $i);
                } catch (\Exception $exception) {
                    $this->log("Failed to create document digest, caused by: $exception");
                    continue;
                }

                $isValidSignature = $this->isValidSignature($digest, $signatureInfo);
                $this->log(
                    $isValidSignature
                        ? 'Signature is valid'
                        : 'Signature validation failed'
                );
            }
        }

        // Ignoring signature validation error
        return true;
    }

    private function getSignatureInfo($document)
    {
        $accessor = 'getSign';
        if (!method_exists($document, $accessor)) {
            $className = get_class($document);
            throw new \Exception("Class $className has no method $accessor");
        }

        return $document->$accessor();
    }

    private function isValidSignature(string $digest, DigitalSignType $signatureInfo)
    {
        $certificates = SBBOLCertificate::find()
            ->where([
                'status' => SBBOLCertificate::STATUS_ACTIVE,
                'serial' => $signatureInfo->getSN(),
            ])
            ->all();
        if (count($certificates) === 0) {
            $this->log("Certificate with serial {$signatureInfo->getSN()} is not found in database");
            return false;
        }

        foreach ($certificates as $certificate) {
            $isValid = SBBOLSignHelper::verify($digest, base64_decode($signatureInfo->getValue()), $certificate->fingerprint);
            if ($isValid) {
                return true;
            }
        }

        return false;
    }

    private static function isSignableDocumentType($documentType)
    {
        return in_array($documentType, self::SIGNABLE_DOCUMENTS_TYPES);
    }
}

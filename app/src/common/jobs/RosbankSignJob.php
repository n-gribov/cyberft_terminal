<?php

namespace common\jobs;

use addons\ISO20022\models\RosbankEnvelope;
use common\base\interfaces\SignableType;
use common\document\Document;
use common\models\TerminalRemoteId;
use common\modules\certManager\components\ssl\X509FileModel;
use Exception;

class RosbankSignJob extends BaseDocumentSignJob
{
    protected function injectSignature()
    {
        /** @var SignableType $typeModel */
        $typeModel = $this->_cyxDoc->getContent()->getTypeModel();

        $isInjected = $this->signDocument(
            $this->_document,
            $typeModel,
            $this->_signature,
            $this->_certBody
        );

        if (!$isInjected) {
            $this->log('Failed to inject signature into type model');

            return false;
        }

        $this->_cyxDoc->getContent()->markDirty();

        return true;
    }

    public function signDocument(Document $document, $typeModel, $signature, $certBody)
    {
        if (!property_exists($typeModel, 'rosbankEnvelope')) {
            throw new Exception("Document {$document->type} does not support Rosbank envelope");
        }
        $x509 = X509FileModel::loadData($certBody);

        $documentBody = (string) $typeModel;
        $envelope = $typeModel->rosbankEnvelope;
        if (empty($envelope)) {
            $envelope = new RosbankEnvelope([
                'documentType' => $document->type,
                'documentBody' => $documentBody,
                'cryptoSystem' => $this->getCryptoSystem($x509),
                'clientCode'   => $this->getClientCode($document),
                'signatures'   => []
            ]);
        }
        $envelope->signatures[] = [
            'signature'                  => $signature,
            'signatureKind'              => $this->_signatureLevel,
            'signatureCertificateSerial' => $x509->getSerialNumber(),
            'commonName'                 => $x509->getSubjectCN(),
            'signingTime'                => date('Y-m-d H:i:s'),
            'signatureCertificateIssuer' => null,
        ];

        $typeModel->rosbankEnvelope = $envelope;

        return true;
    }

    /**
     * @param X509FileModel $x509
     * @return string
     */
    private function getCryptoSystem($x509): string
    {
        $algo = $x509->rawData['signatureTypeLN'];
        if (strpos($algo, '34.10-2012') !== false) {
            return 'h';
        } else if (strpos($algo, '34.10-2001') !== false) {
            return 'g';
        }

        throw new Exception("Key {$x509->fingerprint} has unsupported algorithm: $algo");
    }

    private function getClientCode(Document $document): string
    {
        $terminalRemoteId = TerminalRemoteId::find()
            ->where([
                'terminalReceiver' => $document->receiver,
                'terminalId'       => $document->terminalId,
            ])
            ->one();

        $clientCode = $terminalRemoteId !== null && $terminalRemoteId->remoteId !== null
            ? $terminalRemoteId->remoteId
            : null;

        if ($clientCode !== null) {
            return $clientCode;
        }

        throw new Exception("Cannot find remote client code for document {$document->id}");
    }
}

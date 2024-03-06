<?php

namespace addons\ISO20022\models\traits;

use addons\ISO20022\helpers\RosbankSignHelper;
use addons\ISO20022\ISO20022Module;
use addons\ISO20022\models\ISO20022Type;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\helpers\DsgostSigner;
use common\helpers\SigningHelper;
use common\modules\certManager\components\ssl\X509FileModel;
use DOMDocument;
use DOMNode;
use DOMText;
use DOMXPath;
use Exception;
use Yii;

trait WithSignature
{
    public function getSignedInfo(?string $signerCertificate = null)
    {
        $signatureNode = $this->createSignatureNode($signerCertificate);
        $domDocument = $signatureNode->ownerDocument;
        $signedInfoNode = $this->getNodeByXpath("./*[local-name()='SignedInfo']", $domDocument, $signatureNode);
        $canonicalizationMethod = $this->getCanonicalizationMethod($signedInfoNode);

        $signedInfo = XMLSecurityDSig::canonicalizeData($signedInfoNode, $canonicalizationMethod);

        return $signedInfo;
    }

    public function injectSignature($signatureValue, $signerCertificate)
    {
        $signatureNode = $this->createSignatureNode($signerCertificate);
        $domDocument = $signatureNode->ownerDocument;
        $signatureValueNode = $this->getNodeByXpath(
            "./*[local-name()='SignatureValue']",
            $domDocument,
            $signatureNode
        );
        $signatureValueNode->appendChild(new DOMText($signatureValue));

        $this->_xml = simplexml_load_string($signatureNode->ownerDocument->saveXML(), 'SimpleXMLElement', LIBXML_PARSEHUGE);

        return true;
    }

    public function getSignaturesList()
    {
        if (!$this->_xml) {
            return [];
        }

        if ($this->rosbankEnvelope) {
            return RosbankSignHelper::getSignaturesList($this->rosbankEnvelope);
        }

        return SigningHelper::getUserSignaturesList($this->_xml);
    }

    private function getCanonicalizationMethod(DOMNode $signedInfoNode): ?string
    {
        $domXPath = new DOMXPath($signedInfoNode->ownerDocument);
        $nodes = $domXPath->query("./*[local-name()='CanonicalizationMethod']", $signedInfoNode);
        if ($nodes->count() === 0) {
            return null;
        }

        return $nodes->item(0)->getAttribute('Algorithm') ?: null;
    }

    private function createSignatureNode(?string $signerCertificate = null): DOMNode
    {
        $algorithm = $signerCertificate ? $this->getSignatureAlgorithm($signerCertificate) : 'sha256';
        $keyName = $signerCertificate ? $this->getKeyName($signerCertificate) : null;

        // Делаем копию, т.к. getSignatureTemplate модифицирует документ
        /** @var ISO20022Type $copy */
        $copy = new static();
        $copy->loadFromString((string)$this);

        $uniqid = uniqid();
        $documentWithSignature = $copy->getSignatureTemplate($uniqid, $keyName, $algorithm, $signerCertificate);
        $domDocument = new DOMDocument();
        $domDocument->loadXML($documentWithSignature);

        $signatureNode = $this->getNodeByXpath(
            "//*[local-name()='SplmtryData']/*[local-name()='Envlp']/*[local-name()='SgntrSt']/*[local-name()='Signature'][last()]",
            $domDocument
        );
        $digestValueNode = $this->getNodeByXpath(
            "./*[local-name()='SignedInfo']/*[local-name()='Reference']/*[local-name()='DigestValue']",
            $domDocument,
            $signatureNode
        );
        $digestValue = $this->calculateDigest($domDocument, $algorithm);
        $digestValueNode->appendChild(new DOMText(base64_encode($digestValue)));

        return $signatureNode;
    }

    private function getNodeByXpath(string $xpath, DOMDocument $domDocument, ?DOMNode $contextNode = null): DOMNode
    {
        $domXPath = new DOMXPath($domDocument);
        $nodes = $domXPath->query($xpath, $contextNode);
        if ($nodes->count() === 0) {
            throw new Exception("Cannot find node by path $xpath");
        }
        return $nodes->item(0);
    }

    private function getSignatureAlgorithm(string $certificateBody): string
    {
        $x509 = X509FileModel::loadData($certificateBody);
        $signatureType = $x509->getRawData()['signatureTypeLN'];
        if (strpos($signatureType, 'sha256') === 0) {
            return 'sha256';
        } else if (strpos($signatureType, '34.10-2012') !== false && strpos($signatureType, '34.11-2012') !== false) {
            if (strpos($signatureType, '512') !== false) {
                return 'gostr2012-512';
            } elseif (strpos($signatureType, '256') !== false) {
                return 'gostr2012-256';
            }
        } else if (strpos($signatureType, '34.11-94') !== false && strpos($signatureType, '34.10-2001') !== false) {
            return 'gostr2001';
        }

        throw new Exception("Unsupported signature type: $signatureType");
    }

    private function calculateDigest(DOMDocument $domDocument, string $signatureAlgorithm): string
    {
        $xpath = new DOMXPath($domDocument);
        $refNode = $xpath->query("//*[local-name()='SignedInfo']/*[local-name()='Reference']")->item(0);

        $dsig = new XMLSecurityDSig();
        $canonicalData = $dsig->processTransforms($refNode, $domDocument);

        if ($signatureAlgorithm === 'sha256') {
            return hash('sha256', $canonicalData, true);
        } else {
            $dsgostAlgMap = [
                'gostr2001' => '2001',
                'gostr2012-512' => '2012_512',
                'gostr2012-256' => '2012_256',
            ];
            $dsgost = new DsgostSigner();
            return $dsgost->hash($canonicalData, $dsgostAlgMap[$signatureAlgorithm]);
        }
    }

    private function getKeyName(string $certificateBody): string
    {
        $x509 = X509FileModel::loadData($certificateBody);
        $module = Yii::$app->getModule(ISO20022Module::SERVICE_ID);
        return $module->settings->useSerial
            ? $x509->getSerialNumber()
            : $x509->getFingerprint(false);
    }

}

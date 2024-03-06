<?php

namespace addons\ISO20022\models;

use yii\base\BaseObject;

class RosbankEnvelope extends BaseObject
{
    private const FORMAT = 'ISO20022.RU 2015.01 RUS';
    private const NAMESPACE = 'urn:rosbank:hh';

    public $documentBody;
    public $documentType;
    public $clientCode;
    public $cryptoSystem;
    public $signatures = [];

    public function toXml(): string
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $envelope = $dom->createElementNS(self::NAMESPACE, 'Envelope');
        $envelope->setAttribute('clientCode', $this->clientCode);
        $envelope->setAttribute('crysys', $this->cryptoSystem);
        $envelope->setAttribute('format', self::FORMAT);
        $envelope->setAttribute('docType', $this->documentType);
        $dom->appendChild($envelope);

        $body = $envelope->appendChild($dom->createElement('body'));
        $body->appendChild($dom->createElement('doc', base64_encode($this->documentBody)));

        foreach ($this->signatures as $signature) {
            $signatureNode = $envelope->appendChild($dom->createElement('signature'));
            $signatureNode->setAttribute('signKind', $signature['signatureKind']);
            $signatureNode->setAttribute('cert', $signature['signatureCertificateSerial']);
            $signatureNode->setAttribute('commonName', $signature['commonName']);
            if (isset($signature['signingTime'])) {
                $signatureNode->setAttribute('signingTime', $signature['signingTime']);
            }
            if ($signature['signatureCertificateIssuer']) {
                $signatureNode->setAttribute('issuer', $signature['signatureCertificateIssuer']);
            }
            $signatureNode->appendChild($dom->createElement('SignatureValue', $signature['signature']));
        }

        return $dom->saveXML();
    }

    public static function fromXml(string $xml): self
    {
        $root = new \SimpleXMLElement($xml);
        $envelope = new self([
            'clientCode'                 => self::getXmlAttributeValue($root, 'clientCode'),
            'cryptoSystem'               => self::getXmlAttributeValue($root, 'crysys'),
            'documentType'               => self::getXmlAttributeValue($root, 'docType'),
            'documentBody'               => empty($root->body->doc) ? null : base64_decode((string)$root->body->doc),
        ]);

        foreach ($root->children() as $child) {
            if ($child->getName() !== 'signature') {
                continue;
            }

            $signature = [
                'signature'                  => empty($child->SignatureValue) ? null : (string)$child->SignatureValue,
                'signatureKind'              => self::getXmlAttributeValue($child, 'signKind'),
                'signatureCertificateSerial' => self::getXmlAttributeValue($child, 'cert'),
                'signatureCertificateIssuer' => self::getXmlAttributeValue($child, 'issuer'),
                'commonName'                 => self::getXmlAttributeValue($child, 'commonName'),
            ];

            $signingTime = self::getXmlAttributeValue($child, 'signingTime');

            if ($signingTime) {
                $signature['signingTime'] = $signingTime;
            }

            $envelope->signatures[] = $signature;
        }

        return $envelope;
    }

    private static function getXmlAttributeValue(\SimpleXMLElement $element, string $attributeName)
    {
        if (empty($element)) {
            return null;
        }
        return $element->attributes()->$attributeName === null
            ? null
            : (string) $element->attributes()->$attributeName;
    }
}

<?php

namespace common\models\vtbxml\service;

use common\models\vtbxml\VTBXmlDocument;

/**
 * @property string[] signedFields
 * @property SignInfoSignature[] signatures
 */
class SignInfo extends VTBXmlDocument
{
    public $signedFields = [];
    public $signatures = [];

    public function toXml($version = null)
    {
        $dom = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><SignInfo />');
        $dom->SignDCMFields = implode('|', $this->signedFields);

        foreach ($this->signatures as $signature) {
            $signature->appendToDom($dom);
        }

        $xml = $dom->asXML();
        return static::replaceEncoding($xml, 'windows-1251');
    }

    public static function fromXml($xml)
    {
        $signInfo = new SignInfo();
        $dom = new \SimpleXMLElement(static::replaceEncoding($xml, 'UTF-8'));
        $signInfo->signedFields = preg_split('/\|/', $dom->SignDCMFields, -1, PREG_SPLIT_NO_EMPTY);

        foreach ($dom->xpath('//SignInfo/Sign') as $signatureElement) {
            $signInfo->signatures[] = SignInfoSignature::extractFromDom($signatureElement);
        }

        return $signInfo;
    }
}

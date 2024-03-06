<?php

namespace common\models\vtbxml\service;

use common\models\vtbxml\VTBXmlDocument;

/**
 * @property FreeBankDocListItem[] documents
 */
class FreeBankDocList extends VTBXmlDocument
{
    public $documents = [];

    public function toXml($version = null)
    {
        $dom = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><FreeBankDoc />');

        foreach ($this->documents as $document) {
            $document->appendToDom($dom);
        }

        $xml = $dom->asXML();
        return static::replaceEncoding($xml, 'windows-1251');
    }

    public static function fromXml($xml)
    {
        $freeBankDocList = new FreeBankDocList();
        $dom = new \SimpleXMLElement(static::replaceEncoding($xml, 'utf-8'));

        foreach ($dom->xpath('//FreeBankDoc/Doc') as $documentElement) {
            $freeBankDocList->documents[] = FreeBankDocListItem::extractFromDom($documentElement);
        }

        return $freeBankDocList;
    }
}

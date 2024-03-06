<?php

namespace common\helpers\vtb;

use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\fields\Field;

class BSDocumentXmlSerializer
{
    /**
     * @param BSDocument $document
     * @param $version
     * @return string
     * @throws \Exception
     */
    public static function serialize(BSDocument $document, $version)
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $rootElement = $dom->createElement('BSDocument');
        $dom->appendChild($rootElement);
        static::appendBSDocumentFieldsToXml($document, $version, $rootElement);
        $xml = $dom->saveXML();
        return str_replace('encoding="utf-8"', 'encoding="windows-1251"', $xml);
    }

    /**
     * @param $xml
     * @param $documentType
     * @return BSDocument
     */
    public static function deserialize($xml, $documentType)
    {
        $xml = str_replace('encoding="windows-1251"', 'encoding="utf-8"', $xml);

        $dom = new \DOMDocument();
        $dom->loadXML($xml);

        $rootNode = $dom->firstChild;
        if ($rootNode === null) {
            return static::createBSDocumentInstance($documentType);
        }

        return static::createBSDocumentFromXml($documentType, $rootNode);
    }

    /**
     * @param BSDocument $document
     * @param $version
     * @param \DOMElement $element
     * @throws \Exception
     */
    public static function appendBSDocumentFieldsToXml(BSDocument $document, $version, \DOMElement $element)
    {
        if (!in_array($version, $document::VERSIONS)) {
            throw new \Exception(get_class($document) . " has no version $version");
        }

        /** @var Field $field */
        foreach ($document->getSerializableFields($version) as $fieldId => $field) {
            $value = $document->$fieldId;
            $field->appendToXml($element, $fieldId, $value, $version);
        }
    }

    /**
     * @param $documentType
     * @param \DOMNode $parentNode
     * @return BSDocument
     */
    public static function createBSDocumentFromXml($documentType, \DOMNode $parentNode)
    {
        $document = static::createBSDocumentInstance($documentType);

        $fields = $document->getFields();
        $childNodeList = $parentNode->childNodes;

        /** @var \DOMElement $childElement */
        foreach ($childNodeList as $childElement) {
            $fieldId = $childElement->tagName;
            if (!array_key_exists($fieldId, $fields)) {
                continue;
            }

            /** @var Field $field */
            $field = $fields[$fieldId];
            $document->$fieldId = $field->extractFromDomElement($childElement);
        }

        return $document;
    }

    /**
     * @param string $documentType
     * @return BSDocument
     */
    private static function createBSDocumentInstance($documentType)
    {
        $className = "common\\models\\vtbxml\\documents\\{$documentType}";
        return new $className();
    }
}

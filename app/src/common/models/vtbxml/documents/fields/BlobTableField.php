<?php

namespace common\models\vtbxml\documents\fields;

use common\helpers\vtb\BSDocumentSignedDataBuilder;
use common\helpers\vtb\BSDocumentXmlSerializer;
use common\models\vtbxml\documents\BSDocument;

class BlobTableField extends Field
{
    public $type = 'BLOBTABLE';
    public $recordType;

    public function __construct($properties)
    {
        parent::__construct($properties);
        $this->recordType = $properties['recordType'];
    }

    public function appendToXml(\DOMElement $parentElement, $tagName, $value, $documentTypeVersion = null)
    {
        $records = empty($value) ? [] : $value;
        $element = $parentElement->ownerDocument->createElement($tagName);
        $recordsElement = $parentElement->ownerDocument->createElement('Records');
        $parentElement->appendChild($element);
        $element->appendChild($recordsElement);

        foreach ($records as $record) {
            $recordElement = $parentElement->ownerDocument->createElement('Record');
            $recordsElement->appendChild($recordElement);
            BSDocumentXmlSerializer::appendBSDocumentFieldsToXml($record, $documentTypeVersion, $recordElement);
        }
    }

    protected function createSignedDataFieldTagBody($value, $fieldTagIndentationSize)
    {
        /** @var BSDocument[] $records */
        $records = empty($value) ? [] : $value;

        $recordsTagIndentation = str_repeat(' ', $fieldTagIndentationSize + 1);
        $recordTagIndentation = str_repeat(' ', $fieldTagIndentationSize + 2);
        $recordFieldTagIndentationSize = $fieldTagIndentationSize + 3;

        $tagBody = static::CRLF . $recordsTagIndentation . '<Records>' . static::CRLF;

        foreach ($records as $record) {
            $tagBody .= $recordTagIndentation . '<Record>' . static::CRLF;
            BSDocumentSignedDataBuilder::appendBSDocumentFieldsToSignedData(
                $record,
                array_keys($record->getFields()),
                $tagBody,
                $recordFieldTagIndentationSize
            );
            $tagBody .= $recordTagIndentation . '</Record>' . static::CRLF;
        }

        $tagBody .= $recordsTagIndentation . '</Records>' . static::CRLF;
        $tagBody .= str_repeat(' ', $fieldTagIndentationSize);
        return $tagBody;
    }

    public function extractFromDomElement(\DOMElement $domElement)
    {
        /** @var \DOMElement $recordsElement */
        $recordsElement = $domElement->getElementsByTagName('Records')[0];
        if ($recordsElement === null) {
            return [];
        }
        $recordsNodeList = $recordsElement->getElementsByTagName('Record');
        return array_map(
            function (\DOMElement $node) {
                return BSDocumentXmlSerializer::createBSDocumentFromXml($this->recordType, $node);
            },
            iterator_to_array($recordsNodeList)
        );
    }

    public function isNullSignedValue($value)
    {
        return false;
    }
}

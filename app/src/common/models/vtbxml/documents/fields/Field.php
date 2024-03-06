<?php

namespace common\models\vtbxml\documents\fields;

abstract class Field
{
    const CRLF = "\r\n";

    public $type;
    public $isRequired;
    public $isSigned;
    public $description;
    public $versions = [];

    public function __construct($properties)
    {
        foreach (['isRequired', 'isSigned', 'description', 'versions'] as $property) {
            $this->$property = $properties[$property];
        }
    }

    public function appendToXml(\DOMElement $parentElement, $tagName, $value, $documentTypeVersion = null)
    {
        $element = $parentElement->ownerDocument->createElement($tagName);
        $elementText = $parentElement->ownerDocument->createTextNode($this->encodeForXml($value));
        $element->appendChild($elementText);
        $parentElement->appendChild($element);
    }

    public function appendToSignedData(&$signedData, $fieldId, $value, $indentationSize)
    {
        $fieldSignedData = str_repeat(' ', $indentationSize);

        $isNull = $this->isNullSignedValue($value);
        $tagBody = $isNull ? null : $this->createSignedDataFieldTagBody($value, $indentationSize);

        $fieldSignedData .= $this->createSignedDataFieldTag($fieldId, $isNull, $tagBody);
        $signedData .= $fieldSignedData . static::CRLF;
    }

    protected function createSignedDataFieldTag($fieldId, $isNull, $tagBody)
    {
        $tag = '<Field FieldName="' . strtoupper($fieldId) . '" DataType="' . $this->type . '"';
        if ($isNull) {
            $tag .= ' NULL="true"/>';
        } else {
            $tag .= '>' . $tagBody . '</Field>';
        }
        return $tag;
    }

    protected function createSignedDataFieldTagBody($value, $fieldTagIndentationSize)
    {
        return $this->encodeForSignedData($value);
    }

    public function extractFromDomElement(\DOMElement $domElement)
    {
        return $this->decodeXmlValue($domElement->textContent);
    }

    public function encodeForXml($value)
    {
        return $value;
    }

    public function encodeForSignedData($value)
    {
        return $value;
    }

    public function decodeXmlValue($value)
    {
        return $value;
    }

    public function isNullSignedValue($value)
    {
        return $value === null || $value === '';
    }

}

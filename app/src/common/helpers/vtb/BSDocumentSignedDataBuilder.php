<?php

namespace common\helpers\vtb;

use common\models\vtbxml\documents\BSDocument;
use common\models\vtbxml\documents\fields\Field;

class BSDocumentSignedDataBuilder
{
    const CRLF = "\r\n";

    /**
     * @param BSDocument $document
     * @param $version
     * @return string
     */
    public static function build(BSDocument $document, $version)
    {
        return static::buildForFields($document, $document->getSignedFieldsIds($version));
    }

    /**
     * @param BSDocument $document
     * @param array $fieldsIds
     * @return string
     */
    public static function buildForFields(BSDocument $document, array $fieldsIds)
    {
        $signedData = '<?xml version="1.0" encoding="windows-1251"?>' . static::CRLF . '<Body>' . static::CRLF;
        static::appendBSDocumentFieldsToSignedData($document, $fieldsIds, $signedData, 1);
        $signedData .= '</Body>';
        return $signedData;
    }

    public static function appendBSDocumentFieldsToSignedData(BSDocument $document, array $fieldsIds, &$signedData, $fieldTagIndentationSize)
    {
        $fields = $document->getFields();
        foreach ($fieldsIds as $fieldId) {
            /** @var Field $field */
            $field =  $fields[$fieldId];
            $field->appendToSignedData($signedData, $fieldId, $document->$fieldId, $fieldTagIndentationSize);
        }
    }
}

<?php

namespace common\models\vtbxml\documents\fields;

use common\helpers\vtb\BSDocumentAttachmentSerializer;

class AttachmentField extends BinaryField
{
    public $type = 'STRING';

    public function encodeForXml($value)
    {
        if ($value === null) {
            return null;
        }
        $attachmentContent = BSDocumentAttachmentSerializer::serialize($value);
        return base64_encode($attachmentContent);
    }

    public function decodeXmlValue($value)
    {
        if ($value === null || $value === '') {
            return [];
        }
        return BSDocumentAttachmentSerializer::deserialize(base64_decode($value));
    }

    public function encodeForSignedData($value)
    {
        $attachmentContent = BSDocumentAttachmentSerializer::serialize($value);
        return parent::encodeForSignedData($attachmentContent);
    }

    public function isNullSignedValue($value)
    {
        return false;
    }
}

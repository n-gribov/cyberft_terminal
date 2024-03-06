<?php

namespace common\models\vtbxml\documents;

use common\helpers\vtb\BSDocumentXmlSerializer;
use common\models\vtbxml\documents\fields\Field;
use common\models\vtbxml\VTBXmlDocument;

abstract class BSDocument extends VTBXmlDocument
{
    const TYPE = null;
    const TYPE_ID = null;
    const VERSIONS = [];
    const PARENT_TYPE = null;
    const SIGNED_FIELDS_IDS_BY_VERSION = [];

    /**
     * @return array
     */
    public function getFields()
    {
        return [];
    }

    /**
     * @param integer $version
     * @return Field[]
     */
    public function getSerializableFields($version)
    {
        return array_filter(
            $this->getFields(),
            function (Field $field, $fieldId) use ($version) {
                return in_array($version, $field->versions);
            },
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * @param $version
     * @return array
     * @throws \Exception
     */
    public function getSignedFieldsIds($version)
    {
        if (!in_array($version, static::VERSIONS)) {
            throw new \Exception(get_class($this) . " has no version $version");
        }
        return static::SIGNED_FIELDS_IDS_BY_VERSION[$version];
    }

    public  function toXml($version)
    {
        return BSDocumentXmlSerializer::serialize($this, $version);
    }

    /**
     * @param string $xml
     * @return static
     */
    public static function fromXml($xml)
    {
        return BSDocumentXmlSerializer::deserialize($xml, static::TYPE);
    }
}

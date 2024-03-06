<?php
namespace addons\ISO20022\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\helpers\ZipHelper;

class Auth027CyberXmlContent extends ISO20022CyberXmlContent implements TypeCyberXmlContentInterface
{
    protected const TYPE_MODEL_CLASS = Auth027Type::class;

    public function getDocumentData()
    {
        $data = [];

        return $data;
    }

    protected function fetchRawDataFromZipContent(string $zippedRawData): void
    {
        $rawData = ZipHelper::unpackStringFromString($zippedRawData);
        $this->_typeModel->loadFromString($this->processFetchedData($rawData));
    }
}

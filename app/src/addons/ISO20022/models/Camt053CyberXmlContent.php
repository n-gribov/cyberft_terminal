<?php
namespace addons\ISO20022\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\helpers\ZipHelper;

class Camt053CyberXmlContent extends ISO20022CyberXmlContent implements TypeCyberXmlContentInterface
{
    protected const TYPE_MODEL_CLASS = Camt053Type::class;

    protected function fetchRawDataFromZipContent(string $zippedRawData): void
    {
        $rawData = ZipHelper::unpackStringFromString($zippedRawData);
        $this->_typeModel->loadFromString($this->processFetchedData($rawData));
    }
}

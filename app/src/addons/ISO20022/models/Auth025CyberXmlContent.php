<?php
namespace addons\ISO20022\models;

use addons\ISO20022\helpers\ISO20022Helper;
use common\base\interfaces\TypeCyberXmlContentInterface;
use common\helpers\StringHelper;

class Auth025CyberXmlContent extends ISO20022CyberXmlContent implements TypeCyberXmlContentInterface
{
    protected const TYPE_MODEL_CLASS = Auth025Type::class;

    protected function processFetchedData(string $rawData): string
    {
        return StringHelper::fixXmlHeader($rawData);
    }

    protected function fetchRawDataFromZipContent(string $zippedRawData): void
    {
        $this->_typeModel->sender = $this->_parent->senderId;
        ISO20022Helper::loadTypeModelFromZip($this->_typeModel, $zippedRawData);
        $this->_typeModel->zipFilename = $this->_rootElement->getAttribute('filename');
    }
}

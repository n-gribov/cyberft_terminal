<?php
namespace addons\ISO20022\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\helpers\StringHelper;
use common\helpers\ZipHelper;

class Pain001CyberXmlContent extends ISO20022CyberXmlContent implements TypeCyberXmlContentInterface
{
    protected const TYPE_MODEL_CLASS = Pain001Type::class;

    public function getDocumentData()
    {
        $data = [];

        if (method_exists($this->_typeModel, 'getPaymentRegisterInfo')) {
            $info = $this->_typeModel->getPaymentRegisterInfo();
            $data['count'] = $info['count'];
            $data['currency'] = $info['currency'];
        }

        return $data;
    }

    public function fetchRawData()
    {
        if ($this->_rootElement->tagName == 'RawData') {
            $rawData = base64_decode($this->_rootElement->nodeValue);

            $mimeType = $this->_rootElement->getAttribute('mimeType');
            if ($mimeType === 'application/zip') {
                $rawData = ZipHelper::unpackStringFromString($rawData);
            } else if ($mimeType === 'application/vnd.rosbank+xml') {
                $envelope = RosbankEnvelope::fromXml($rawData);
                $this->_typeModel->rosbankEnvelope = $envelope;
                $rawData = $envelope->documentBody;
            }
        } else {
            $rawData = $this->_rootElement->ownerDocument->saveXML($this->_rootElement);
        }

        $this->_typeModel->loadFromString(
            StringHelper::fixXmlHeader($rawData)
        );
    }

    protected function processFetchedData(string $rawData): string
    {
        return StringHelper::fixXmlHeader($rawData);
    }

    protected function fetchRawDataFromZipContent(string $zippedRawData): void
    {
        $rawData = ZipHelper::unpackStringFromString($zippedRawData);
        $this->_typeModel->loadFromString($this->processFetchedData($rawData));
    }
}

<?php
namespace addons\ISO20022\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

abstract class ISO20022CyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    protected const TYPE_MODEL_CLASS = null;

    protected const MIME_TYPE_ZIP = 'application/zip';
    protected const MIME_TYPE_ROSBANK_XML = 'application/vnd.rosbank+xml';
    protected const MIME_TYPE_XML = 'application/xml';

    protected $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $typeModelClass = static::TYPE_MODEL_CLASS;
            $this->_typeModel = new $typeModelClass();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);
        $this->_typeModel->sender = $this->_parent->senderId;
        $this->_typeModel->receiver = $this->_parent->receiverId;
        $this->_typeModel->type = $this->_parent->docType;

        if (property_exists($this->_typeModel, 'originalFilename')) {
            $this->_typeModel->originalFilename = $this->_parent->filename;
        }
        $this->fetchRawData();

        return $this->_typeModel;
    }

    public function isDirty()
    {
        return (bool)(string) $this->_typeModel;
    }

    public function pushRawData()
    {
        $fileName = null;
        if ($this->useZipContentForPush()) {
            $mimeType = self::MIME_TYPE_ZIP;
            $rawData = $this->_typeModel->zipContent;
            $fileName = $this->_typeModel->zipFilename;
        } else if ($this->useRosbankEnvelopeForPush()) {
            $mimeType = self::MIME_TYPE_ROSBANK_XML;
            $rawData = $this->_typeModel->rosbankEnvelope->toXml();
        } else {
            $mimeType = self::MIME_TYPE_XML;
            $rawData = (string)$this->_typeModel;
        }

        $this->_rootElement->setAttribute('mimeType', $mimeType);
        $this->_rootElement->nodeValue = base64_encode($rawData);
        $this->_rootElement->setAttribute('encoding', 'base64');
        if ($fileName !== null) {
            $this->_rootElement->setAttribute('filename', $this->_typeModel->zipFilename);
        }
        if ($this->_typeModel->getTransportType() !== $this->_typeModel->getType()) {
            $this->_rootElement->setAttribute('doctype', $this->_typeModel->getType());
        }
    }

    public function fetchRawData()
    {
        if ($this->_rootElement->tagName == 'RawData') {
            $rawData = base64_decode($this->_rootElement->nodeValue);

            $mimeType = $this->_rootElement->getAttribute('mimeType');
            if ($mimeType === self::MIME_TYPE_ZIP) {
                $this->fetchRawDataFromZipContent($rawData);

                return;
            } else if ($mimeType === self::MIME_TYPE_ROSBANK_XML) {
                $envelope = RosbankEnvelope::fromXml($rawData);
                $this->_typeModel->rosbankEnvelope = $envelope;
                $rawData = $envelope->documentBody;
            }
        } else {
            $rawData = $this->_rootElement->ownerDocument->saveXML($this->_rootElement);
        }

        $this->_typeModel->loadFromString(
            $this->processFetchedData($rawData)
        );
    }

    protected function useZipContentForPush(): bool
    {
        return property_exists($this->_typeModel, 'useZipContent') && $this->_typeModel->useZipContent;
    }

    protected function useRosbankEnvelopeForPush(): bool
    {
        return property_exists($this->_typeModel, 'rosbankEnvelope') && $this->_typeModel->rosbankEnvelope !== null;
    }

    protected function processFetchedData(string $rawData): string
    {
        return $rawData;
    }

    abstract protected function fetchRawDataFromZipContent(string $zippedRawData): void;
}
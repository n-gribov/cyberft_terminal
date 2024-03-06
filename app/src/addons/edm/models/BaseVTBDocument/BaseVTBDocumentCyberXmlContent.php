<?php

namespace addons\edm\models\BaseVTBDocument;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;
use common\models\vtbxml\service\SignInfo;
use common\models\vtbxml\VTBXmlDocument;
use Yii;

abstract class BaseVTBDocumentCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_NS_PREFIX = 'vtb';
    const ROOT_ELEMENT = null;
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/vtb.01';
    const TYPE_MODEL_CLASS = null;

    /** @var BaseVTBDocumentType */
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
        $this->setAttributes($this->_typeModel->attributes, false);
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);
        $this->_typeModel->setAttributes($this->attributes, false);
        return $this->_typeModel;
    }

    public function setTypeModel($typeModel)
    {
        $this->_typeModel = $typeModel;
    }

    public function boundAttributes()
    {
        $rootElement = static::ROOT_ELEMENT;
        return [
            'document'        => "//vtb:$rootElement/vtb:Document",
            'documentVersion' => "//vtb:$rootElement/vtb:DocumentVersion",
            'signatureInfo'   => "//vtb:$rootElement/vtb:SignatureInfo",
            'customerId'      => "//vtb:$rootElement/vtb:CustomerId",
        ];
    }

    public function pushDocument()
    {
        $this->pushVTBXmlDocument('document', $this->_typeModel->document, $this->_typeModel->documentVersion);
    }

    public function pushSignatureInfo()
    {
        $this->pushVTBXmlDocument('signatureInfo', $this->_typeModel->signatureInfo);
    }

    /**
     * @param string              $attribute
     * @param VTBXmlDocument|null $document
     * @param integer|null        $version
     */
    private function pushVTBXmlDocument($attribute, $document, $version = null)
    {
        if ($document === null) {
            return;
        }
        try {
            $rawXml = $document->toXml($version);
            $this->pushBoundNodeValue($attribute, $rawXml);
        } catch (\Exception $exception) {
            Yii::error($exception);
        }
    }

    public function fetchDocument()
    {
        $typeModel = $this->_typeModel;
        $this->fetchVTBXmlDocument('document', $typeModel::VTB_DOCUMENT_CLASS);
    }

    public function fetchSignatureInfo()
    {
        $this->fetchVTBXmlDocument('signatureInfo', SignInfo::class);
    }

    private function fetchVTBXmlDocument($attribute, $documentClass)
    {
        $rawXml = $this->getBoundNodeValue($attribute);
        if (!empty($rawXml)) {
            $document = $documentClass::fromXml($rawXml);
            $this->_attributes[$attribute] = $document;
        }
    }
}

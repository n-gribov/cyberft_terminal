<?php

namespace addons\edm\models\BaseRaiffeisenDocument;

use common\base\BaseType;
use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

abstract class BaseRaiffeisenDocumentCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_NS_PREFIX = 'raiffeisen';
    const ROOT_ELEMENT = null;
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/raiffeisen.01';
    const TYPE_MODEL_CLASS = null;

    /** @var BaseType */
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

    public function isDirty()
    {
        return (bool)(string)$this->_typeModel;
    }

    protected function pushAttribute($tagName, $value)
    {
        foreach ($this->_rootElement->childNodes as $child) {
            if ($child->tagName === $tagName) {
                $this->_rootElement->removeChild($child);
            }
        }

        if ($value !== null) {
            $node = $this->_dom->createElement($tagName);
            $node->appendChild($this->_dom->createCDATASection($value));
            $this->_rootElement->appendChild($node);
        }
    }

    protected function fetchAttribute($attribute)
    {
        $rawXml = $this->getBoundNodeValue($attribute);
        $this->_typeModel->loadFromString($rawXml);
        $this->_attributes[$attribute] = $this->_typeModel->$attribute;
    }
}

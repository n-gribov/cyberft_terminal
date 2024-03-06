<?php
namespace addons\edm\models\ForeignCurrencyOperation;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class ForeignCurrencyOperationCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const ROOT_ELEMENT = 'Body';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdoc.01';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new ForeignCurrencyOperationType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getDocumentData()
    {
        return [
            'mimeType'   => 'application/xml',
            'encoding' => '',
        ];
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params, false);

        // Один из вариантов добывания содержимого Body
        $this->_typeModel->loadFromString($this->_rootElement->ownerDocument->saveXML($this->_rootElement));

        return $this->_typeModel;
    }

    public function fetchRawData()
    {
        $this->_typeModel->loadFromString($this->_rootElement->ownerDocument->saveXML($this->_rootElement));
    }

    public function isDirty()
    {
        return (bool)(string) $this->_typeModel;
    }

    public function __toString()
    {
        return (string) $this->_typeModel;
    }

}
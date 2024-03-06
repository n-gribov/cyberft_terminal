<?php
namespace addons\edm\models\PaymentRegister;

use addons\edm\models\PaymentRegister\PaymentRegisterType;
use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class PaymentRegisterCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const ROOT_ELEMENT = 'Body';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new PaymentRegisterType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getDocumentData()
    {
        return [
            'mimeType'   => 'application/xml',
            'encoding' => null,
            'sum' => $this->_typeModel->getSum(),
            'count' => $this->_typeModel->getCount(),
            'currency' => 'RUB',
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
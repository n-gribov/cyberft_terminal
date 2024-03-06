<?php

namespace addons\edm\models\PaymentOrder;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class PaymentOrderCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new PaymentOrderType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getDocumentData()
    {
        $this->rawData = (string) $this->_typeModel;

        return [
            'mimeType' => 'application/text',
            'sum' => $this->_typeModel->sum,
            'currency' => $this->_typeModel->currency,
            'count' => 1,
        ];
    }

    public function pushRawData()
    {
        $this->_rootElement->nodeValue = base64_encode((string) $this->_typeModel);
    }

    public function fetchRawData()
    {
        $rawData = base64_decode($this->_rootElement->nodeValue);
        $this->_typeModel->loadFromString($rawData);
    }

    public function getTypeModel($params = [])
    {
       return $this->_typeModel->loadFromString($this->rawData);
    }

}
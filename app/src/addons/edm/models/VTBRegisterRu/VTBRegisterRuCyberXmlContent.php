<?php

namespace addons\edm\models\VTBRegisterRu;

use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;
use common\models\cyberxml\CyberXmlDocument;
use common\models\vtbxml\documents\PayDocRu;
use common\models\vtbxml\service\SignInfo;

class VTBRegisterRuCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new VTBRegisterRuType();
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

    public function pushRawData()
    {
        $data = (string) $this->_typeModel;
        $this->_rootElement->nodeValue = base64_encode($data);
        $this->_rootElement->setAttribute('encoding', 'base64');
        $this->_rootElement->setAttribute('mimeType', 'application/xml');
    }

    public function getTypeModel($params = [])
    {
        return $this->_typeModel;
    }

    public function fetchRawData()
    {
        $rawData = base64_decode($this->_rootElement->nodeValue);

        $xml = simplexml_load_string($rawData);

        foreach($xml->VTBPayDocRu as $payDocRu) {
            $vtbTypeModel = new VTBPayDocRuType();
            $vtbTypeModel->document = PayDocRu::fromXml($payDocRu->Document);
            $vtbTypeModel->signatureInfo = SignInfo::fromXml($payDocRu->SignatureInfo);
            $vtbTypeModel->documentVersion = (string) $payDocRu->DocumentVersion;
            $vtbTypeModel->customerId = (string) $payDocRu->CustomerId;

            $this->_typeModel->paymentOrders[] = $vtbTypeModel;
        }
    }
}
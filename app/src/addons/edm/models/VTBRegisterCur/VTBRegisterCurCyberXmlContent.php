<?php

namespace addons\edm\models\VTBRegisterCur;

use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;
use common\models\cyberxml\CyberXmlDocument;
use common\models\vtbxml\documents\PayDocCur;
use common\models\vtbxml\documents\PayDocRu;
use common\models\vtbxml\service\SignInfo;

class VTBRegisterCurCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new VTBRegisterCurType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getDocumentData()
    {
        return [
            'mimeType' => 'application/xml',
            'encoding' => '',
        ];
    }

    public function pushRawData()
    {
        $data = '<VTBRegisterCurr>';

        foreach ($this->_typeModel->paymentOrders as $po) {
            $cyxDoc = CyberXmlDocument::loadTypeModel($po);
            $data .= $cyxDoc->getContent()->saveXML();
        }

        $data .= '</VTBRegisterCurr>';

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

        foreach ($xml->VTBPayDocCur as $payDocCur) {
            $vtbTypeModel = new VTBPayDocCurType();
            $vtbTypeModel->document = PayDocCur::fromXml($payDocCur->Document);
            $vtbTypeModel->signatureInfo = SignInfo::fromXml($payDocCur->SignatureInfo);
            $vtbTypeModel->documentVersion = (string) $payDocCur->DocumentVersion;
            $vtbTypeModel->customerId = (string) $payDocCur->CustomerId;

            $this->_typeModel->paymentOrders[] = $vtbTypeModel;
        }
    }
}
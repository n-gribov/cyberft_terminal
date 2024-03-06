<?php

namespace addons\edm\models\PaymentStatusReport;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class PaymentStatusReportCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
	const ROOT_ELEMENT = 'PaymentStatusReport';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/edm.02';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new PaymentStatusReportType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }

    }

	public function getDocumentData()
	{
        $this->_rootElement = $this->_typeModel->buildXml();

        return [
            'encoding' => 'utf-8',
			'mimeType'	=> 'application/xml',
		];
	}

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);
        $this->_typeModel->loadFromString($this->_rootElement->ownerDocument->saveXML($this->_rootElement));

        return $this->_typeModel;
    }

    public function __toString()
    {
        return (string) $this->_typeModel;
    }
}

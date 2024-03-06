<?php

namespace addons\edm\models\StatementRequest;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class StatementRequestCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const ROOT_ELEMENT = 'StatementRequest';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/swiftfin.01';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new StatementRequestType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }

        $this->_rootElement = $this->_typeModel->buildXml();
    }

    public function getDocumentData()
    {
        $this->_rootElement = $this->_typeModel->buildXml();

        return [
            'encoding' => 'utf-8',
            'mimeType' => 'application/xml',
            'statementType' => 'extended',
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

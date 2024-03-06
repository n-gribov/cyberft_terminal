<?php

namespace addons\edm\models\BaseSbbol2Document;

use addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType;
use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class BaseSbbol2DocumentCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const ROOT_ELEMENT = 'Statement';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/sbbol02';
    const DEFAULT_NS_PREFIX = 'sbbol2';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new Sbbol2PayDocRuType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }

        // Сформировать XML
        $this->_rootElement = $this->_typeModel->buildXml();
    }

    public function getDocumentData()
    {
        $this->_rootElement = $this->_typeModel->buildXml();

        return [
            'mimeType' => 'application/xml',
        ];
    }

    public function boundAttributes()
    {
        return [
            'Sbbol2PayDocRu' => '//sbbol2:Sbbol2PayDocRu'
        ];
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);

        $this->_typeModel->sender    = $this->_parent->senderId;
        $this->_typeModel->recipient = $this->_parent->receiverId;
        //$this->_typeModel->xmlDom    = $this->_rootElement;
        $this->_typeModel->loadFromString($this->_rootElement->ownerDocument->saveXML($this->_rootElement));

        return $this->_typeModel;
    }

    function __toString() {

        $body = (string) $this->_typeModel;

        $body = str_replace("<?xml version=\"1.0\"?>\n", '', $body);

        return $body;
    }
}
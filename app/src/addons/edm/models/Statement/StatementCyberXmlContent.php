<?php

namespace addons\edm\models\Statement;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class StatementCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
	const ROOT_ELEMENT = 'Statement';
	const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/swiftfin.01';
	const DEFAULT_NS_PREFIX = 'st';

    protected $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            static::initTypeModel();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }

        $this->_rootElement = $this->_typeModel->buildXml();
    }

    protected function initTypeModel()
    {
        $typeModelClass = \Yii::$app->registry->getTypeModelClass($this->_parent->docType);
        $this->_typeModel = new $typeModelClass();
    }

	public function getDocumentData()
	{
        $this->_rootElement = $this->_typeModel->buildXml();

        return [
			'mimeType'	=> 'application/xml',
		];
	}

    public function boundAttributes()
    {
        return [
            static::ROOT_ELEMENT => '//' . static::DEFAULT_NS_PREFIX . ':' . static::ROOT_ELEMENT
        ];
    }

    public function getTypeModel($params = [])
    {
        // отключил, т.к.
        // 1) это никогда не происходит
        // 2) загрузка loadFromString все перетрет, поэтому логика тут не совсем понятна
        //
        // $this->_typeModel->setAttributes($params);

        $this->_typeModel->sender    = $this->_parent->senderId;
        $this->_typeModel->recipient = $this->_parent->receiverId;
        $xmlString = $this->_rootElement->ownerDocument->saveXML($this->_rootElement);
        $this->_typeModel->loadFromString($xmlString);

        return $this->_typeModel;
    }

    function __toString()
    {
        return $this->_typeModel->getModelDataAsString(true);
    }

}

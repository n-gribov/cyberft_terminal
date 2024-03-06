<?php

namespace addons\swiftfin\models;

use addons\swiftfin\helpers\SwiftfinHelper;
use common\models\cyberxml\CyberXmlContent;
use common\base\interfaces\TypeCyberXmlContentInterface;

class SwiftfinCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    private $_typeModel;

    public function init()
    {
        parent::init();

        $this->_prepareTypeModel();
    }

    private function _prepareTypeModel()
    {
        if (!is_null($this->_parent) && !is_null($this->_parent->_typeModel)) {
			$this->_typeModel = $this->_parent->_typeModel;
		} else {
			$this->_typeModel = new SwiftFinType();
        }
    }

    public function getDocumentData()
    {
        $this->_prepareTypeModel();

        $this->rawData = (string) $this->_typeModel;

        return [
//            'mimeType' => 'application/text',
//            'docId' => $this->_typeModel->cyberXmlDocId,
//            'docDate' => $this->_typeModel->cyberXmlDate,
//            'sum' => $this->_typeModel->sum,
//            'currency' => $this->_typeModel->currency,
            'senderId' => $this->_typeModel->sender,
            'receiverId' => $this->_typeModel->recipient,
            'count' => $this->_typeModel->nestedItemsCount,
        ];
    }

    public function pushRawData()
    {
        $this->_rootElement->nodeValue = base64_encode((string) $this->_typeModel);
    }

    public function getTypeModel($params = [])
    {
        if (in_array(SwiftfinHelper::determineStringFormat($this->rawData),
            [
                SwiftfinHelper::FILE_FORMAT_SWIFT,
                SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE
            ])
        ) {

            return SwiftFinType::createFromData($this->rawData);
        }

        return null;
    }
}
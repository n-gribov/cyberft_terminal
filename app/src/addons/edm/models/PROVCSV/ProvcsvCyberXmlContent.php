<?php

namespace addons\edm\models\PROVCSV;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class ProvcsvCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
	const ROOT_ELEMENT = 'RawData';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new ProvcsvType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getDocumentData()
    {
        $this->rawData = iconv('UTF-8', 'cp1251', (string) $this->_typeModel);

        return [
            'mimeType' => 'application/text',
        ];
    }

    public function getTypeModel($params = [])
    {
        return $this->_typeModel->loadFromString($this->rawData);
    }

	// ProvCSV использует тот же тэг RawData, что и CyberXmlContent,
	// методы есть у родителя

}

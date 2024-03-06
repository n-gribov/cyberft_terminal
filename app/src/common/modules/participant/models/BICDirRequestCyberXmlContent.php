<?php
namespace common\modules\participant\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

/**
 * @deprecated
 */
class BICDirRequestCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
	const XSD_SCHEMA = '@common/modules/transport/resources/xsd/CyberFT_SYS_v1.0.xsd';
	const ROOT_ELEMENT = 'BICDirectoryUpdateRequest';
	const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdata.01';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new BICDirRequestType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function boundAttributes()
    {
        if ($this->_typeModel) {
            $type = $this->_typeModel->requestType;
        } else {
            $type = BICDirRequestType::REQUEST_TYPE_FULL;
        }

        if ($type == BICDirRequestType::REQUEST_TYPE_FULL) {
            return [
                'contentFormat' => '//BICDirectoryUpdateRequest/FullLoadRequest/ContentFormat',
            ];
        } else {
            return [
                'contentFormat' => '//BICDirectoryUpdateRequest/IncrementLoadRequest/ContentFormat',
                'startDate'	=> '//BICDirectoryUpdateRequest/IncrementLoadRequest/StartDate',
            ];
        }
    }

    public function loadFromTypeModel(BICDirRequestType $model)
    {
        $this->_typeModel = $model;
        $this->setAttributes($model->attributes, false);

        if ($model->requestType == BICDirRequestType::REQUEST_TYPE_INCREMENT) {
            $this->startDate = $model->startDate;
        }
    }

	public function getDocumentData()
	{
        return [
			'mimeType' => 'application/xml',
			'encoding' => null,
		];
	}

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);

        $this->_typeModel->sender = $this->_parent->senderId;
        $this->_typeModel->recipient = $this->_parent->receiverId;

        $this->_typeModel->setAttributes($this->attributes, false);

        return $this->_typeModel;
    }

}
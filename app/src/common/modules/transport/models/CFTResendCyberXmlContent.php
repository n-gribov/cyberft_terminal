<?php
namespace common\modules\transport\models;

use common\models\cyberxml\CyberXmlContent;
use common\base\interfaces\TypeCyberXmlContentInterface;

class CFTResendCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
	const XSD_SCHEMA = '@common/modules/transport/resources/xsd/CyberFT_SYS_v1.0.xsd';
	const ROOT_ELEMENT = 'Resend';
	const DEFAULT_NS_PREFIX = 'chk';
	const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdata.01';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new CFTResendType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function boundAttributes()
    {
        return [
            'refDocId'	=> '//chk:Resend/chk:RefDocId',
            'refSenderId'	=> '//chk:Resend/chk:RefSenderId',
        ];
    }

    public function loadFromTypeModel(CFTResendType $model)
    {
        $this->_typeModel = $model;

        $this->setAttributes($model->attributes, false);
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
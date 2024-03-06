<?php

namespace addons\edm\models\VTBPrepareCancellationResponse;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class VTBPrepareCancellationResponseCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_NS_PREFIX = 'vtb';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/vtb.01';
    const ROOT_ELEMENT = 'VTBPrepareCancellationResponse';

    /** @var VTBPrepareCancellationResponseType */
    protected $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new VTBPrepareCancellationResponseType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
        $this->setAttributes($this->_typeModel->attributes, false);
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);
        $this->_typeModel->setAttributes($this->attributes, false);
        return $this->_typeModel;
    }

    public function boundAttributes()
    {
        return [
            'requestDocumentUuid' => '//vtb:VTBPrepareCancellationResponse/vtb:RequestDocumentUUID',
            'status'              => '//vtb:VTBPrepareCancellationResponse/vtb:Status',
            'documentInfo'        => '//vtb:VTBPrepareCancellationResponse/vtb:DocumentInfo',
            'vtbReferenceId'      => '//vtb:VTBPrepareCancellationResponse/vtb:VTBReferenceId',
        ];
    }
}

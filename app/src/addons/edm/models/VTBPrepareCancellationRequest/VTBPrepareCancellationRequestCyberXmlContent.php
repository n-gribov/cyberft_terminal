<?php

namespace addons\edm\models\VTBPrepareCancellationRequest;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class VTBPrepareCancellationRequestCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_NS_PREFIX = 'vtb';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/vtb.01';
    const ROOT_ELEMENT = 'VTBPrepareCancellationRequest';

    /** @var VTBPrepareCancellationRequestType */
    protected $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new VTBPrepareCancellationRequestType();
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
            'documentUuid'    => '//vtb:VTBPrepareCancellationRequest/vtb:DocumentUUID',
            'documentNumber'  => '//vtb:VTBPrepareCancellationRequest/vtb:DocumentNumber',
            'documentDate'    => '//vtb:VTBPrepareCancellationRequest/vtb:DocumentDate',
            'messageForBank'  => '//vtb:VTBPrepareCancellationRequest/vtb:MessageForBank',
            'vtbCustomerId'   => '//vtb:VTBPrepareCancellationRequest/vtb:VTBCustomerId',
            'vtbDocumentType' => '//vtb:VTBPrepareCancellationRequest/vtb:VTBDocumentType',
        ];
    }
}

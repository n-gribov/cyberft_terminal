<?php
namespace common\modules\transport\models;

use common\models\cyberxml\CyberXmlContent;
use common\base\interfaces\TypeCyberXmlContentInterface;

/**
 * StatusReport based upon CyberXmlContent
 */
class StatusReportCyberXmlContent extends CyberXmlContent  implements TypeCyberXmlContentInterface
{
    const XSD_SCHEMA = '@common/modules/transport/resources/xsd/CyberFT_SYS_v1.0.xsd';
    const ROOT_ELEMENT = 'StatusReport';
    const DEFAULT_NS_PREFIX = 'sr';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdata.01';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new StatusReportType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
        $this->setAttributes($this->_typeModel->attributes, false);
    }

    public function boundAttributes()
    {
        return [
            'refDocId'	=> '//sr:StatusReport/sr:RefDocId',
            'statusCode' => '//sr:StatusReport/sr:StatusCode',
            'errorCode' => '//sr:StatusReport/sr:ErrorCode',
            'errorDescription' => '//sr:StatusReport/sr:ErrorDescription',
        ];
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
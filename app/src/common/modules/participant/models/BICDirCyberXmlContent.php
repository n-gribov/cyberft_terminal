<?php
namespace common\modules\participant\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

/**
 * @deprecated
 */
class BICDirCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const ROOT_ELEMENT = 'BICDirectoryUpdate';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftsys.02';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new BICDirType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }

        $this->_typeModel->loadFromDom($this->_parent->_rootElement);
    }

    public function getDocumentData()
    {
        return [
            'mimeType'   => 'application/xml',
        ];
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);

        return $this->_typeModel;
    }

    public function isDirty()
    {
        return (bool)(string) $this->_typeModel;
    }

    public function __toString()
    {
        return '';
    }

}
<?php

namespace addons\finzip\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\models\cyberxml\CyberXmlContent;

class FinZipCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const DEFAULT_MIME_TYPE = 'application/zip';

    private $_typeModel;

    public function init()
    {
        parent::init();

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new FinZipType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }
    }

    public function getDocumentData()
    {
        $this->rawData = (string) $this->_typeModel;

        return [
//            'docId'      => $this->_typeModel->cyberXmlDocId,
//            'docDate'    => $this->_typeModel->cyberXmlDate,
            'senderId'   => $this->_typeModel->sender,
            'receiverId' => $this->_typeModel->recipient,
            'attachmentUUID' => $this->_typeModel->attachmentUUID,
        ];
    }

    public function getTypeModel($params = [])
    {
        if (isset($params['zipStoredFileId'])) {
            $this->_typeModel->loadFromFile($params['zipStoredFileId']);
        } else {
            $this->_typeModel->loadFromString($this->rawData);
        }

        $this->_typeModel->sender = $this->_parent->senderId;
        $this->_typeModel->recipient = $this->_parent->receiverId;
        $this->_typeModel->attachmentUUID = isset($params['attachmentUUID']) ? $params['attachmentUUID'] : null;

        return $this->_typeModel;
    }

}
<?php

namespace addons\fileact\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlContent;
use Yii;

class FileActCyberXmlContent extends CyberXmlContent implements TypeCyberXmlContentInterface
{
    const ROOT_ELEMENT = 'Body';
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdata.01';

    private $_typeModel;
    private $_module;
    public $rawData;
    public $binFileName;

    public function init()
    {
        parent::init();

        $this->_module = Yii::$app->getModule('fileact');

        if (is_null($this->_parent->_typeModel)) {
            $this->_typeModel = new FileActType();
        } else {
            $this->_typeModel = $this->_parent->_typeModel;
        }

        $xml = simplexml_import_dom($this->_parent->_rootElement);

        if (isset($xml->Body)) {
            if (isset($xml->Body->SignedData)) {
                $this->rawData = base64_decode($xml->Body->SignedData->Content->RawData);
                $this->binFileName = (string) $xml->Body->SignedData->Content->RawData['binFileName'];
            } else {
                $this->rawData = base64_decode($xml->Body->RawData);
                $this->binFileName = (string) $xml->Body->RawData['binFileName'];
            }
        }
    }

    public function getDocumentData()
    {
        return [
            'mimeType'   => ($this->_module->isCryptoProSignEnabled($this->_typeModel->sender) ? 'application/xml' : 'application/zip'),
//            'docId'      => $this->_typeModel->cyberXmlDocId,
//            'docDate'    => $this->_typeModel->cyberXmlDate,
            'senderId'   => $this->_typeModel->sender,
            'receiverId' => $this->_typeModel->recipient,
        ];
    }

    public function getTypeModel($params = [])
    {
        $this->_typeModel->setAttributes($params);
        $this->_typeModel->sender = $this->_parent->senderId;
        $this->_typeModel->recipient = $this->_parent->receiverId;

        return $this->_typeModel;
    }

    public function getIsSigned()
    {
        $xml = simplexml_import_dom($this->_parent->_rootElement);
        return isset($xml->Body->SignedData);
    }

    public function isDirty()
    {
        return (bool)(string) $this->_typeModel;
    }

    public function __toString()
    {
        $typeModelData = base64_encode((string) $this->_typeModel);

        if (!$this->_module->isCryptoProSignEnabled($this->_typeModel->sender)) {
            $bodyData = '<RawData xmlns="http://cyberft.ru/xsd/cftdata.01" binFileName="'.  $this->_typeModel->binFileName.'">' . $typeModelData . '</RawData>';
        } else {
            $bodyData = '<SignedData xmlns="http://cyberft.ru/xsd/cftdata.02" xmlns:cpsign="http://www.w3.org/2000/09/xmldsig#"><Content>
    <RawData Id="id_'. Uuid::generate() .'" mimeType="application/zip" encoding="base64" binFileName="'.  $this->_typeModel->binFileName.'">'. $typeModelData .'</RawData></Content>
  <Signatures></Signatures>
</SignedData>';
        }

        return $bodyData;
    }

}
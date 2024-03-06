<?php
namespace addons\ISO20022\models;

use common\base\interfaces\TypeCyberXmlContentInterface;
use common\helpers\ZipHelper;

class Pain002CyberXmlContent extends ISO20022CyberXmlContent implements TypeCyberXmlContentInterface
{
    protected const TYPE_MODEL_CLASS = Pain002Type::class;

    public function getDocumentData()
    {
        $data = [];

//        if ($this->_parent && $this->_parent->docId) {
//            $data['docId'] = $this->_parent->docId;
//        } else {
//            $data['docId'] = $this->_typeModel->cyberXmlDocId;
//        }

//        if ($this->_parent && $this->_parent->docDate) {
//            $data['docDate'] = $this->_parent->docDate;
//        } else {
//            $data['docDate'] = $this->_typeModel->cyberXmlDate;
//        }

        if (method_exists($this->_typeModel, 'getPaymentRegisterInfo')) {
            $info = $this->_typeModel->getPaymentRegisterInfo();
            $data['count'] = $info['count'];
            $data['currency'] = $info['currency'];
        }

        return $data;
    }

    protected function fetchRawDataFromZipContent(string $zippedRawData): void
    {
        $rawData = ZipHelper::unpackStringFromString($zippedRawData);
        $this->_typeModel->loadFromString($this->processFetchedData($rawData));
    }
}

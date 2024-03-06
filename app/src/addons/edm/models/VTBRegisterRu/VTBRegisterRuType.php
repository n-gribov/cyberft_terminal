<?php
namespace addons\edm\models\VTBRegisterRu;

use common\base\BaseType;
use common\models\cyberxml\CyberXmlDocument;
use Yii;

class VTBRegisterRuType extends BaseType
{
    const TYPE = 'VTBRegisterRu';

    public $paymentOrders = [];
    private $_sum;
    private $_currency;
    private $_count;

    public function getType()
    {
        return static::TYPE;
    }

    public function getSearchFields()
    {
        return false;
    }

    public function getSignaturesList()
    {
        return [];
    }

    public function getSum()
    {
        $sum = 0;

        foreach($this->paymentOrders as $paymentOrder) {
            $sum += $paymentOrder->document->AMOUNT;
        }

        return $sum;
    }

    public function getCurrency()
    {
        if (count($this->paymentOrders) > 0) {
            return $this->paymentOrders[0]->document->CURRCODE;
        } else {
            return null;
        }
    }

    public function getCount()
    {
        return count($this->paymentOrders);
    }

    public function getModelDataAsString()
    {
        $xmlString = '<VTBRegisterRu>';

        foreach($this->paymentOrders as $po) {
            $cyxDoc = CyberXmlDocument::loadTypeModel($po);
            $xmlString .= $cyxDoc->getContent()->saveXML();
        }

        $xmlString .= '</VTBRegisterRu>';

        return $xmlString;
    }

    public function getSignedInfo(?string $signerCertificate = null)
    {
        $signedInfo = [];

        foreach($this->paymentOrders as $paymentOrder) {
            $signedInfo[] = $paymentOrder->getSignedInfo();
        }

        return $signedInfo;
    }

}
<?php

namespace addons\edm\models\VTBRegisterCur;

use common\base\BaseType;

class VTBRegisterCurType extends BaseType
{
    const TYPE = 'VTBRegisterCur';

    public $paymentOrders = [];

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
            $sum += $paymentOrder->document->AMOUNT ?: $paymentOrder->document->AMOUNTTRANSFER;
        }

        return $sum;
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
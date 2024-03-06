<?php

namespace addons\edm\models\IBank\V1;

use addons\edm\models\IBank\common\IBankDocument;

class IBankV1Document extends IBankDocument
{
    public function getSenderAccountNumber()
    {
        switch ($this->type) {
            case 'PayDocCurr':
                return $this->data[5];
            case 'TrAccPayDoc':
                return $this->data[7];
            case 'CurrBuy':
                return $this->data[5];
            case 'CurrSell':
                return $this->data[5];
            case 'CurrDInq181i':
                return $this->data[6];
            default:
                return null;
        }
    }

    public function getRemoteClientId()
    {
        switch ($this->type) {
            case 'ConfDInq181i':
            case 'ContractReg181i':
            case 'CredReg181':
            case 'ContractChanges181i':
                return $this->data[1];
            default:
                return null;
        }
    }
}

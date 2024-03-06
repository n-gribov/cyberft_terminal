<?php

namespace addons\edm\models\IBank\V2;

use addons\edm\models\IBank\common\IBankDocument;

class IBankV2Document extends IBankDocument
{
    public function getSenderAccountNumber()
    {
        switch ($this->type) {
            case 'currency_convert':
                return $this->data['SALE_ACCOUNT'];
            default:
                return null;
        }
    }

    public function getRemoteClientId()
    {
        return null;
    }
}

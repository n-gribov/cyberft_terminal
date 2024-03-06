<?php

namespace addons\edm\models\IBank\V1;

use addons\edm\models\IBank\V1\converter\VTB\IBankV1ConfDInq181iToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1ContractChanges181iToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1ContractReg181iToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1CredReg181ToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1CurrBuyToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1CurrSellToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1CurrDInq181iToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1PayDocCurrToVTBConverter;
use addons\edm\models\IBank\V1\converter\VTB\IBankV1TrAccPayDocToVTBConverter;
use common\helpers\vtb\VTBHelper;

class IBankV1ConverterFactory
{
    public static function create($documentType, $recipientTerminalId)
    {
        if (VTBHelper::isGatewayTerminal($recipientTerminalId)) {
            switch ($documentType) {
                case 'PayDocCurr':
                    return new IBankV1PayDocCurrToVTBConverter();
                case 'TrAccPayDoc':
                    return new IBankV1TrAccPayDocToVTBConverter();
                case 'CurrBuy':
                    return new IBankV1CurrBuyToVTBConverter();
                case 'CurrSell':
                    return new IBankV1CurrSellToVTBConverter();
                case 'CurrDInq181i':
                    return new IBankV1CurrDInq181iToVTBConverter();
                case 'ConfDInq181i':
                    return new IBankV1ConfDInq181iToVTBConverter();
                case 'ContractReg181i':
                    return new IBankV1ContractReg181iToVTBConverter();
                case 'CredReg181':
                    return new IBankV1CredReg181ToVTBConverter();
                case 'ContractChanges181i':
                    return new IBankV1ContractChanges181iToVTBConverter();
                default:
                    throw new \Exception("Unsupported document type $documentType");
            }
        } else {
            throw new \Exception("Don't know how to create converters for $recipientTerminalId");
        }
    }
}

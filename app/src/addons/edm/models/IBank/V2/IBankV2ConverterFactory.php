<?php

namespace addons\edm\models\IBank\V2;

use addons\edm\models\IBank\V2\converter\VTB\IBankV2BargainPassportClosingToVTBConverter;
use addons\edm\models\IBank\V2\converter\VTB\IBankV2CurrencyConvertToVTBConverter;
use common\helpers\vtb\VTBHelper;

class IBankV2ConverterFactory
{
    public static function create($documentType, $recipientTerminalId)
    {
        if (VTBHelper::isGatewayTerminal($recipientTerminalId)) {
            switch ($documentType) {
                case 'currency_convert':
                    return new IBankV2CurrencyConvertToVTBConverter();
                case 'bargain_passport_closing':
                    return new IBankV2BargainPassportClosingToVTBConverter();
                default:
                    throw new \Exception("Unsupported document type $documentType");
            }
        } else {
            throw new \Exception("Don't know how to create converters for $recipientTerminalId");
        }
    }
}
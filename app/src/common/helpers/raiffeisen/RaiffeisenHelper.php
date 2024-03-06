<?php

namespace common\helpers\raiffeisen;

use common\document\DocumentFormatGroup;

class RaiffeisenHelper
{
    public static function isGatewayTerminal($terminalAddress)
    {
        $gatewayTerminalAddress = static::getGatewayTerminalAddress();
        return !empty($gatewayTerminalAddress) && $gatewayTerminalAddress === $terminalAddress;
    }

    public static function getGatewayTerminalAddress()
    {
        return DocumentFormatGroup::getTerminalAddressByGroup(DocumentFormatGroup::RAIFFEISEN);
    }
}

<?php

namespace addons\ISO20022\helpers;

use addons\ISO20022\models\Auth026Type;
use addons\ISO20022\models\ISO20022Type;
use addons\ISO20022\services\RosbankSignatureService;
use common\document\Document;
use common\document\DocumentFormatGroup;

class RosbankHelper
{
    public static function isGatewayTerminal(string $terminalAddress): bool
    {
        $gatewayTerminalAddress = static::getGatewayTerminalAddress();

        return !empty($gatewayTerminalAddress) && $gatewayTerminalAddress === $terminalAddress;
    }

    public static function getGatewayTerminalAddress()
    {
        return DocumentFormatGroup::getTerminalAddressByGroup(DocumentFormatGroup::ROSBANK_ISO20022);
    }

    public static function isTerminalUsingRosbankFormat(string $terminalAddress): bool
    {
        return DocumentFormatGroup::getGroupByTerminalAddress($terminalAddress) === DocumentFormatGroup::ROSBANK_ISO20022;
    }

    /**
     * @param Document $document
     * @param ISO20022Type|Auth026Type $typeModel
     * @return bool
     */
    public static function signDocument(Document $document, $typeModel): bool
    {
        $service = new RosbankSignatureService();
        try {
            $service->signDocument($document, $typeModel);
        } catch (\Exception $exception) {
            \Yii::info("Failed to sign Rosbank document {$document->id}, caused by: $exception");
            return false;
        }

        return true;
    }
}

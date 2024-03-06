<?php
namespace common\helpers\sbbol2;

use addons\edm\models\EdmSBBOLAccount;
use common\document\DocumentFormatGroup;
use common\settings\AppSettings;
use Yii;

class Sbbol2Helper
{
    public static function isGatewayTerminal($terminalAddress)
    {
        $sbbol2TerminalAddress = static::getGatewayTerminalAddress();

        return !empty($sbbol2TerminalAddress) && $sbbol2TerminalAddress === $terminalAddress;
    }

    public static function getGatewayTerminalAddress()
    {
        return DocumentFormatGroup::getTerminalAddressByGroup(DocumentFormatGroup::SBBOL2);
    }

    public static function getSbbol2CustomerIdByAccountNumber($accountNumber)
    {
        $sbbolAccount = EdmSBBOLAccount::findOne(['number' => $accountNumber]);
        return $sbbolAccount !== null ? $sbbolAccount->customerId : null;
    }

    public static function getSbbol2SenderName($terminalAddress)
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $terminalAddress);
        return $terminalSettings->sbbol2CustomerSenderName ?: null;
    }

}

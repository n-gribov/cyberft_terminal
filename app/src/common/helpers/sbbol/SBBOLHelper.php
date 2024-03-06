<?php

namespace common\helpers\sbbol;

use addons\edm\models\EdmSBBOLAccount;
use common\document\DocumentFormatGroup;
use common\modules\certManager\components\ssl\X509FileModel;
use common\settings\AppSettings;

class SBBOLHelper
{
    public static function isGatewayTerminal($terminalAddress)
    {
        $sbbolTerminalAddress = static::getGatewayTerminalAddress();
        return !empty($sbbolTerminalAddress) && $sbbolTerminalAddress === $terminalAddress;
    }

    public static function getGatewayTerminalAddress()
    {
        return DocumentFormatGroup::getTerminalAddressByGroup(DocumentFormatGroup::SBBOL);
    }

    public static function getSBBOLCustomerIdByAccountNumber($accountNumber)
    {
        $sbbolAccount = EdmSBBOLAccount::findOne(['number' => $accountNumber]);
        return $sbbolAccount !== null ? $sbbolAccount->customerId : null;
    }

    public static function getSBBOLSenderName($terminalAddress)
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = \Yii::$app->settings->get('app', $terminalAddress);
        return $terminalSettings->sbbolCustomerSenderName ?: null;
    }

    public static function createCertificateIssuerString(X509FileModel $certificateX509)
    {
        // E = casbrf@sberbank.ru,roleOccupant = Тестирующий Q,CN = ЛавринСВ-Тестовая печать-УЦ-9,OU = Удостоверяющий центр СБ РФ (Тестовый),O = ОАО "Сбербанк России",C = RU
        $attributesMap = [
            'E' => 'emailAddress',
            'roleOccupant' => 'roleOccupant',
            'CN' => 'CN',
            'OU' => 'OU',
            'O' => 'O',
            'C' => 'C',
        ];

        $issuerAttributes = $certificateX509->getIssuer() ?: [];
        $pairs = array_reduce(
            array_keys($attributesMap),
            function ($carry, $toAttribute) use ($attributesMap, $issuerAttributes) {
                $fromAttribute = $attributesMap[$toAttribute];
                if (array_key_exists($fromAttribute, $issuerAttributes)) {
                    $carry[] = "$toAttribute={$issuerAttributes[$fromAttribute]}";
                }
                return $carry;
            },
            []
        );

        return implode(', ', $pairs);
    }
}

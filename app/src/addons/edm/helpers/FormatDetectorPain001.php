<?php

namespace addons\edm\helpers;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\Pain001Fx\Pain001FxType;
use addons\edm\models\Pain001Rls\Pain001RlsType;
use addons\ISO20022\models\ISO20022Type;
use addons\ISO20022\models\Pain001Type;
use common\base\BaseType;
use common\base\interfaces\FormatDetectorInterface;
use Yii;

class FormatDetectorPain001 implements FormatDetectorInterface
{
    use DetectsXml;

    public static function detect($filePath, $options = [])
    {
        try {
            $body = file_get_contents($filePath);
            if (!self::isXml($body)) {
                return false;
            }

            $typeModel = ISO20022Type::getModelFromString($body);
        } catch (\Exception $ex) {
            Yii::error(__METHOD__ . ': ' . $ex->getMessage());
            return false;
        }

        if (!$typeModel || !self::isPain001($typeModel)) {
            return false;
        }

        $sender = static::getSenderTerminalAddress($typeModel);
        if (empty($sender)) {
            Yii::info('Cannot detect sender terminal address');
            return false;
        }

        $receiver = static::getReceiverTerminalAddress($typeModel);
        if (empty($receiver)) {
            Yii::info('Cannot detect receiver terminal address');
            return false;
        }

        if (self::isForeignCurrencyOperation($typeModel)) {
            $effectiveTypeModelClass = Pain001FxType::class;
        } else if (self::isForeignCurrencyTransitAccountTransfer($typeModel)) {
            $effectiveTypeModelClass = Pain001RlsType::class;
        } else if (static::isForeignCurrencyPaymentRegister($typeModel)) {
            $effectiveTypeModelClass = Pain001FcyType::class;
        } else {
            return false;
        }

        $effectiveTypeModel = new $effectiveTypeModelClass();
        $effectiveTypeModel->loadFromString($filePath, true);
        $effectiveTypeModel->sender = $sender;
        $effectiveTypeModel->receiver = $receiver;

        return $effectiveTypeModel;
    }

    private static function isPain001(BaseType $typeModel): bool
    {
        return $typeModel instanceof Pain001Type;
    }

    private static function isForeignCurrencyPaymentRegister(Pain001Type $typeModel): bool
    {
        /** @var \SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();

        // 1. В теге <Document><CstmrCdtTrfInitn><PmtInf><DbtrAcct><Id><Othr><Id> указан счет не в рублях (символы 6-8 не 810)
        $payerAccount = (string)@$xml->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id;
        if (substr($payerAccount, 5, 3) === '810') {
            return false;
        }

        // 2. В документе нет тега (или пустой) <Document><CstmrCdtTrfInitn><PmtInf><PmtTpInf><LclInstrm><Prtry>
        $localInstrument = (string)@$xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->LclInstrm->Prtry;
        return empty($localInstrument);
    }

    private static function isForeignCurrencyOperation(Pain001Type $typeModel): bool
    {
        return $typeModel->getPainDocumentType() === 'RU-FX';
    }

    private static function isForeignCurrencyTransitAccountTransfer(Pain001Type $typeModel): bool
    {
        return $typeModel->getPainDocumentType() === 'RU-FCYRLS';
    }

    private static function getEdmPayerAccount(Pain001Type $typeModel): ?EdmPayerAccount
    {
        $payerAccountNumber = $typeModel->getDebitAccountNumber();
        if (empty($payerAccountNumber)) {
            Yii::info('Cannot find payer account in document');
            return null;
        }
        $payerAccount = EdmPayerAccount::findOne(['number' => $payerAccountNumber]);

        if ($payerAccount === null) {
            Yii::info("Cannot find payer account $payerAccountNumber in database");
        }

        return $payerAccount;
    }

    private static function getSenderTerminalAddress(Pain001Type $typeModel): ?string
    {
        $payerAccount = static::getEdmPayerAccount($typeModel);
        return $payerAccount && $payerAccount->edmDictOrganization && $payerAccount->edmDictOrganization->terminal
            ? $payerAccount->edmDictOrganization->terminal->terminalId
            : null;
    }

    private static function getReceiverTerminalAddress(Pain001Type $typeModel): ?string
    {
        $payerAccount = static::getEdmPayerAccount($typeModel);
        return $payerAccount ? $payerAccount->terminalId : null;
    }
}

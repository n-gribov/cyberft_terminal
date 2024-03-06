<?php

namespace addons\edm\states\out;

use addons\edm\models\DictBank;
use addons\edm\models\IBank\common\converter\IBankConverter;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\IBank\V2\IBankV2ConverterFactory;
use Yii;

/** @property EdmOutState $state */
class IBankV2DocumentPrepareStep extends BaseIBankDocumentPrepareStep
{
    protected function createConverter($ibankDocumentType, $recipientTerminalId): IBankConverter
    {
        return IBankV2ConverterFactory::create($ibankDocumentType, $recipientTerminalId);
    }

    protected function getRecipientTerminalId(IBankDocument $ibankDocument, $senderTerminalId)
    {
        $recipientTerminalId = null;
        $accountNumber = $ibankDocument->getSenderAccountNumber();
        $bik = $ibankDocument->getSenderBik();

        if ($accountNumber) {
            $recipientTerminalId = $this->getTerminalIdByAccountNumber($accountNumber);
        } elseif ($bik) {
            $recipientTerminalId = $this->getTerminalIdByBik($bik);
        }

        if (empty($recipientTerminalId)) {
            throw new \DomainException(Yii::t('edm', 'Failed to identify recipient terminal'));
        }

        return $recipientTerminalId;
    }

    private function getTerminalIdByBik($bik)
    {
        $bank = DictBank::findOne(['bik' => $bik]);

        if (empty($bank) || empty($bank->terminalId)) {
            throw new \DomainException(Yii::t('edm', 'Bank terminal is not specified for BIK {bankBIK}', ['bankBIK' => $bik]));
        }

        return $bank->terminalId;
    }
}

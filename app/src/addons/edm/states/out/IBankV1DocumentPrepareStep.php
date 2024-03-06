<?php

namespace addons\edm\states\out;

use addons\edm\models\IBank\common\converter\IBankConverter;
use addons\edm\models\IBank\common\IBankDocument;
use addons\edm\models\IBank\V1\IBankV1ConverterFactory;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use Yii;

/** @property EdmOutState $state */
class IBankV1DocumentPrepareStep extends BaseIBankDocumentPrepareStep
{
    protected function createConverter($ibankDocumentType, $recipientTerminalId): IBankConverter
    {
        return IBankV1ConverterFactory::create($ibankDocumentType, $recipientTerminalId);
    }

    protected function getRecipientTerminalId(IBankDocument $ibankDocument, $senderTerminalId)
    {
        $recipientTerminalId = null;
        $accountNumber = $ibankDocument->getSenderAccountNumber();
        $remoteClientId = $ibankDocument->getRemoteClientId();

        if (!empty($accountNumber)) {
            $recipientTerminalId = $this->getTerminalIdByAccountNumber($accountNumber);
        } else if (!empty($remoteClientId)) {
            $recipientTerminalId = $this->getTerminalIdByRemoteClientId($remoteClientId, $senderTerminalId);
        }

        if (empty($recipientTerminalId)) {
            throw new \DomainException(Yii::t('edm', 'Failed to identify recipient terminal'));
        }

        return $recipientTerminalId;
    }

    private function getTerminalIdByRemoteClientId($remoteClientId, $senderTerminalId)
    {
        $senderTerminal = Terminal::findOne(['terminalId' => $senderTerminalId]);
        if ($senderTerminal === null) {
            $this->log("Cannot find local terminal $senderTerminalId");

            return null;
        }

        $remoteIds = TerminalRemoteId::find()
            ->where([
                'terminalId' => $senderTerminal->id,
                'remoteId' => $remoteClientId,
            ])
            ->all();
        if (empty($remoteIds) || count($remoteIds) > 1) {
            $this->log("Cannot find distinct recipient terminal by remote id $remoteClientId");

            return null;
        }

        return $remoteIds[0]->terminalReceiver;
    }
}

<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusUpdateHandler;

use addons\VTB\helpers\VTBModuleHelper;
use addons\VTB\models\VTBDocumentStatus;

class PaymentDocumentStatusUpdateHandler extends BaseStatusUpdateHandler
{
    protected function sendStatusReport()
    {
        if ($this->status->getCode() == $this->previousStatus->getCode()) {
            $this->log('Document status in VTB is not changed');
            return false;
        }

        $groupStatusCode = $this->getPaymentStatusReportGroupStatusCode($this->status);
        $transactionStatusCode = $this->getPaymentStatusReportTransactionStatusCode($this->status);

        if (empty($groupStatusCode) || empty($transactionStatusCode)) {
            $this->log("Cannot map VTB status {$this->status->getCode()} to PaymentStatusReportStatus");
            return false;
        }

        $this->log('Will send payment status report...');

        $statusComment = $this->documentInfo === null ? null : $this->documentInfo->NOTEFROMBANK;
        return VTBModuleHelper::sendPaymentStatusReport(
            $this->document,
            $groupStatusCode,
            $transactionStatusCode,
            $this->status->getDescription(),
            $statusComment
        );
    }

    private function getPaymentStatusReportGroupStatusCode(VTBDocumentStatus $vtbStatus)
    {
        switch ($vtbStatus->getCode()) {
            case '13023': // ЭП Не верна
            case '15013': // Не принят
            case '15033': // Ошибка реквизитов
            case '17023': // Ошибка реквизитов после АБС
            case '17063': // Отказан АБС
            case '17083': // Не принят банком
            case '19003': // Отозван
            case '19013': // Сторнирован
            case '16023': // Не принят РЦК
            case '16063': // Отказан РЦК
                return 'RJCT';
            case '15003': // Принят
            case '15063': // Ожидает визирования
            case '17183': // Принят ВК
            case '17013': // Принят Банком
            case '17033': // Отложен
            case '18013': // Обработан ???
            case '19023': // Картотека
            case '16013': // Доставлен в РЦК
            case '16033': // Обрабатывается РЦК
            case '16043': // Акцептован РЦК ???
                return 'ACTC';
            case '17043': // Исполнен
                return 'ACSC';
            default:
                return null;
        }
    }

    private function getPaymentStatusReportTransactionStatusCode(VTBDocumentStatus $vtbStatus)
    {
        switch ($vtbStatus->getCode()) {
            case '13023': // ЭП Не верна
            case '15013': // Не принят
            case '15033': // Ошибка реквизитов
            case '17023': // Ошибка реквизитов после АБС
            case '17063': // Отказан АБС
            case '17083': // Не принят банком
            case '19003': // Отозван
            case '19013': // Сторнирован
            case '16023': // Не принят РЦК
            case '16063': // Отказан РЦК
                return 'RJCT';
            case '15003': // Принят
            case '15063': // Ожидает визирования
            case '17183': // Принят ВК
            case '17013': // Принят Банком
            case '16013': // Доставлен в РЦК
            case '16033': // Обрабатывается РЦК
                return 'ACCP';
            case '17033': // Отложен
            case '19023': // Картотека
                return 'PDNG';
            case '16043': // Акцептован РЦК ???
            case '18013': // Обработан ???
                return 'ACSP';
            case '17043': // Исполнен
                return 'ACSC';
            default:
                return null;
        }
    }
}

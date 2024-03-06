<?php

namespace common\helpers\raiffeisen;

use common\models\raiffeisenxml\request\RequestType\IncomingAType;
use common\models\raiffeisenxml\request\StmtReqTypeRaifType;

class RaiffeisenDocumentDigestBuilder
{
    public static function build($document, $requestId): string
    {
        if ($document instanceof StmtReqTypeRaifType) {
            return static::buildForStmtReqTypeRaif($document);
        }

        if ($document instanceof IncomingAType) {
            return static::buildForIncoming($document, $requestId);
        }

        throw new \Exception('Unsupported document type: ' . get_class($document));
    }

    private static function buildForStmtReqTypeRaif(StmtReqTypeRaifType $stmtReq): string
    {
        $formatDate = function ($date) {
            return $date !== null ? $date->format('d.m.Y') : null;
        };

        $digest = "[Запрос на получение информации о движении денежных средств]\n"
            . "Номер документа={$stmtReq->getDocNumber()}\n"
            . "Дата документа={$formatDate($stmtReq->getDocDate())}\n"
            . "Дата выписки={$formatDate($stmtReq->getDate())}\n"
            . "Наименование организации автора документа={$stmtReq->getOrgName()}\n"
            . "ИНН организации автора документа={$stmtReq->getInn()}\n";

        foreach ($stmtReq->getAccounts() as $i => $account) {
            $n = $i + 1;
            $digest .= "[Счет $n]\n"
                . "Счет={$account->value()}\n"
                . "БИК банка, в котором обслуживается счет={$account->getBic()}\n"
                . "Наименование банка={$account->getName()}\n";
        }

        return $digest;
    }

    private static function buildForIncoming(IncomingAType $document, $requestId): string
    {
        return "Incoming\nATTRIBUTES\nRequestId=$requestId";
    }
}

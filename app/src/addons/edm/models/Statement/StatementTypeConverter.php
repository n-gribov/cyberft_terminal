<?php

namespace addons\edm\models\Statement;

use addons\edm\models\DictCurrency;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\Sbbol2Statement\Sbbol2StatementType;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\VTBStatementRu\VTBStatementRuType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\models\ISO20022Type;
use common\models\raiffeisenxml\response\TransInfoRaifType;
use common\models\sbbolxml\response\TransInfoType;
use common\models\vtbxml\documents\StatementRu;
use common\models\vtbxml\documents\StatementRuDocument;
use DateTime;
use Exception;

class StatementTypeConverter {

    /**
     * @param StatementType|VTBStatementRuType|SBBOLStatementType|RaiffeisenStatementType|ISO20022Type $typeModel
     * @return StatementType
     * @throws Exception
     */
    public static function convertFrom($typeModel)
    {
        switch ($typeModel->type)
        {
            case StatementType::TYPE:
            case Sbbol2StatementType::TYPE:
                return $typeModel;
            case VTBStatementRuType::TYPE:
                return self::createStatementFromVTBStatementRu($typeModel);
            case SBBOLStatementType::TYPE:
                return self::createStatementFromSBBOLStatement($typeModel);
            case RaiffeisenStatementType::TYPE:
                return self::createStatementFromRaiffeisenStatement($typeModel);
            case Camt052Type::TYPE:
            case Camt053Type::TYPE:
            case Camt054Type::TYPE:
                return self::createStatementFromISOStatement($typeModel);
            default:
                throw new Exception("{$typeModel->type} document cannot be converted to StatementType");
        }
    }

    private static function createStatementFromISOStatement(ISO20022Type $iso20022TypeModel)
    {
        $xml = $iso20022TypeModel->getRawXml();

        switch ($iso20022TypeModel->type)
        {
            case Camt052Type::TYPE:
                $document = $xml->BkToCstmrAcctRpt;
                $statement = $xml->BkToCstmrAcctRpt->Rpt;
                break;
            case Camt053Type::TYPE:
                $document = $xml->BkToCstmrStmt;
                $statement = $xml->BkToCstmrStmt->Stmt;
                break;
            case Camt054Type::TYPE:
                $document = $xml->BkToCstmrDbtCdtNtfctn;
                $statement = $xml->BkToCstmrDbtCdtNtfctn->Ntfctn;
                break;
        }

        if (isset($statement->Acct->Id->Othr->Id)) {
            $accountNumber = (string) $statement->Acct->Id->Othr->Id;
            $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
            $accountOwnerName = $account ? $account->edmDictOrganization->name : null;
        } else {
            $accountNumber = null;
            $accountOwnerName = null;
        }

        $statementArray = [
            'dateCreated' => isset($statement->CreDtTm) ? (string) $statement->CreDtTm : null, //??? Могут быть проблемы с форматом даты/времени, надо проследить.
            'statementAccountNumber' => $accountNumber,
            'statementPeriodStart' => isset($statement->FrToDt->FrDtTm) ? (string) date('d.m.Y', strtotime($statement->FrToDt->FrDtTm)) : null,
            'statementPeriodEnd' => isset($statement->FrToDt->ToDtTm) ? (string) date('d.m.Y', strtotime($statement->FrToDt->ToDtTm)) : null,
            'companyName' => isset($account) ? $account->payerName : null,
            'currency' => isset($account) ? $account->edmDictCurrencies->name : null,
        ];



        if (isset($statement->ElctrncSeqNb)) {
            $statementArray['statementNumber'] = (string) $statement->ElctrncSeqNb;
        }

        if (isset($statement->Acct->Svcr)) {
            // проверяем что тег BIC есть в выписке
            if (!$statement->Acct->Svcr->FinInstnId->BIC->count()) {
                // если тега BIC нет в выписке то находим бик по номеру счета
                $account = EdmPayerAccount::findOne(['number' => $statementArray['statementAccountNumber']]);
                if ($account) {
                    $statementArray['statementAccountBIK'] = (string)$account->bank->bik;
                }
            } else {
                // если тег BIC ecть в выписке то берем значение из него
                $statementArray['statementAccountBIK'] = (string) $statement->Acct->Svcr->FinInstnId->BIC;
            }
        }

        if (isset($statement->Bal)) {
            foreach ($statement->Bal as $bal) {
                if ((string) $bal->Tp->CdOrPrtry->Cd === 'OPBD') {
                    $statementArray['openingBalance'] = (string) $bal->Amt;
                }

                if ((string) $bal->Tp->CdOrPrtry->Cd === 'CLBD') {
                    $statementArray['closingBalance'] = (string) $bal->Amt;
                }
            }
        }

        if (isset($statement->TxsSummry)) {
            $statementArray['debitTurnover'] = (string) $statement->TxsSummry->TtlDbtNtries->Sum;
            $statementArray['creditTurnover'] = (string) $statement->TxsSummry->TtlCdtNtries->Sum;
        }

        $statementTypeModel = new StatementType($statementArray);

        $ntryXpath = [
            'camt.054' => '/a:Document/a:BkToCstmrDbtCdtNtfctn/a:Ntfctn/a:Ntry',
            'camt.053' => '/a:Document/a:BkToCstmrStmt/a:Stmt/a:Ntry',
            'camt.052' => '/a:Document/a:BkToCstmrAcctRpt/a:Rpt/a:Ntry'
        ];

        foreach ($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
            if (empty($strPrefix)) {
                $strPrefix = 'a'; //Assign an arbitrary namespace prefix.
            }
            $xml->registerXPathNamespace($strPrefix, $strNamespace);
        }
        $entries = $xml->xpath($ntryXpath[$iso20022TypeModel->type]);

        foreach ($entries as $entry) {
            $isCredit = ((string) $entry->CdtDbtInd === 'CRDT');

            if (isset($entry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Iban)) {
                $payerAccountNum = isset($entry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Iban) ? (string) $entry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Iban : null;
            } else if (isset($entry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Othr->Id)) {
                $payerAccountNum = isset($entry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Othr->Id) ? (string) $entry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Othr->Id : null;
            } else {
                $payerAccountNum = null;
            }

            $entryDate = isset($entry->BookgDt->Dt) ? (string) date('d.m.Y', strtotime($entry->BookgDt->Dt)) : null;
            $docDate = isset($entry->NtryDtls->TxDtls->RmtInf->Strd->RfrdDocInf->RltdDt) ? (string) date('d.m.Y', strtotime($entry->NtryDtls->TxDtls->RmtInf->Strd->RfrdDocInf->RltdDt)) : null;
            if (!$docDate) {
                $docDate = $entryDate;
            }

            $paymentPurpose = implode(
                '',
                (array)$entry->NtryDtls->TxDtls->RmtInf->Ustrd
            );

            $taxDocDate = null;
            if (strlen($entry->NtryDtls->TxDtls->Tax->Mtd ?? '') > 0) {
                $taxDocDate = $entry->NtryDtls->TxDtls->Tax->Mtd;
            } elseif (isset($entry->NtryDtls->TxDtls->Tax->Dt)) {
                $taxDocDate = date('d.m.Y', strtotime($entry->NtryDtls->TxDtls->Tax->Dt));
            }

            array_push($statementTypeModel->transactions, [
                'Amount' => (string)$entry->Amt ?: 0,
                'Credit' => $isCredit ? (string) $entry->Amt : 0,
                'Debit' => $isCredit ? 0 : (string) $entry->Amt,
                'PayerCurrency' => (string) $entry->Amt[0]['Ccy'],
                'Reference' => isset($entry->AcctSvcrRef) ? (string) $entry->AcctSvcrRef : null,
                'UniqId' => isset($entry->NtryRef) ? (string) $entry->NtryRef : null,
                'EntryDate' => $entryDate,
                'ValueDate' => isset($entry->ValDt->Dt) ? (string) date('d.m.Y', strtotime($entry->ValDt->Dt)) : null,
                'ReceiptDate' => isset($entry->NtryDtls->TxDtls->RltdDts->AccptncDtTm) ? date('c', strtotime($entry->NtryDtls->TxDtls->RltdDts->AccptncDtTm)) : null,
                'Number' => isset($entry->NtryDtls->TxDtls->Refs->EndToEndId) ? (string) $entry->NtryDtls->TxDtls->Refs->EndToEndId : null,
                'DocDate' => $docDate,
                'PaymentKind' => null,
                'Purpose' => $paymentPurpose !== '' ? $paymentPurpose : null,
                'Priority' => isset($entry->NtryDtls->TxDtls->Purp->Prtry) ? (string) $entry->NtryDtls->TxDtls->Purp->Prtry : null,
                'PayType' => null,
                'PayCode' => isset($entry->NtryDtls->TxDtls->RmtInf->Strd->CdtrRefInf->Ref) ? (string) $entry->NtryDtls->TxDtls->RmtInf->Strd->CdtrRefInf->Ref : null,
                'PayerName' => isset($entry->NtryDtls->TxDtls->RltdPties->Dbtr->Nm) ? (string) $entry->NtryDtls->TxDtls->RltdPties->Dbtr->Nm : null,
                'PayerAccountNum' => $payerAccountNum,
                'PayerBIK' => isset($entry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId) ? (string) $entry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId : null,
                'PayerBankName' => isset($entry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Nm) ? (string) $entry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Nm : null,
                'PayerBankAccountNum' => isset($entry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Othr->Id) ? (string) $entry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Othr->Id : null,
                'PayerINN' => isset($entry->NtryDtls->TxDtls->RltdPties->Dbtr->Id->OrgId->Othr->Id) ? (string) $entry->NtryDtls->TxDtls->RltdPties->Dbtr->Id->OrgId->Othr->Id : null,
                'PayerKPP' => isset($entry->NtryDtls->TxDtls->Tax->Dbtr->TaxTp) ? (string) $entry->NtryDtls->TxDtls->Tax->Dbtr->TaxTp : null,
                'PayeeName' => isset($entry->NtryDtls->TxDtls->RltdPties->Cdtr->Nm) ? (string) $entry->NtryDtls->TxDtls->RltdPties->Cdtr->Nm : null,
                'PayeeAccountNum' => isset($entry->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->Othr->Id) ? (string) $entry->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->Othr->Id : null,
                'PayeeBIK' => isset($entry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId) ? (string) $entry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId : null,
                'PayeeBankName' => isset($entry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Nm) ? (string) $entry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Nm : null,
                'PayeeBankAccountNum' => isset($entry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Othr->Id) ? (string) $entry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Othr->Id : null,
                'PayeeINN' => isset($entry->NtryDtls->TxDtls->RltdPties->Cdtr->Id->OrgId->Othr->Id) ? (string) $entry->NtryDtls->TxDtls->RltdPties->Cdtr->Id->OrgId->Othr->Id : null,
                'PayeeKPP' => isset($entry->NtryDtls->TxDtls->Tax->Cdtr->TaxTp) ? (string) $entry->NtryDtls->TxDtls->Tax->Cdtr->TaxTp : null,
                'DepInfStatus' => isset($entry->NtryDtls->TxDtls->Tax->Rcrd->DbtrSts) ? (string) $entry->NtryDtls->TxDtls->Tax->Rcrd->DbtrSts : null,
                'DepInfKBK' => isset($entry->NtryDtls->TxDtls->Tax->Rcrd->CtgyDtls) ? (string) $entry->NtryDtls->TxDtls->Tax->Rcrd->CtgyDtls : null,
                'DepInfOKTMO' => isset($entry->NtryDtls->TxDtls->Tax->AdmstnZn) ? (string) $entry->NtryDtls->TxDtls->Tax->AdmstnZn : null,
                'DepInfPayReason' => isset($entry->NtryDtls->TxDtls->Tax->Rcrd->Ctgy) ? (string) $entry->NtryDtls->TxDtls->Tax->Rcrd->Ctgy : null,
                'DepInfTaxPeriod' => ISO20022Helper::getBudgetPeriodFromXml(
                    strval($entry->NtryDtls->TxDtls->Tax->Rcrd->Prd->Yr ?? ''),
                    strval($entry->NtryDtls->TxDtls->Tax->Rcrd->Prd->Tp ?? '')
                ),
                'DepInfTaxDocNumber' => isset($entry->NtryDtls->TxDtls->Tax->RefNb) ? (string) $entry->NtryDtls->TxDtls->Tax->RefNb : null,
                'DepInfTaxDocDate' => $taxDocDate,
                'DepInfTaxType' => isset($entry->NtryDtls->TxDtls->Tax->Rcrd->Tp) ? (string) $entry->NtryDtls->TxDtls->Tax->Rcrd->Tp : null,
                'IncomeTypeCode' => isset($entry->NtryDtls->TxDtls->AmtDtls->PrtryAmt->Tp) ? (string)$entry->NtryDtls->TxDtls->AmtDtls->PrtryAmt->Tp : null,
            ]);
        }

        return $statementTypeModel;
    }

    private static function createStatementFromVTBStatementRu(VTBStatementRuType $vtbStatementRuTypeModel)
    {
        /** @var StatementRu $statementRu */
        $statementRu = $vtbStatementRuTypeModel->document;
        $statementAccount = EdmPayerAccount::findOne(['number' => $statementRu->ACCOUNT]);

        $formatDate = function ($date, $format) {
            if (!$date instanceof DateTime) {
                return null;
            }
            return $date->format($format);
        };

        $statementTypeModel = new StatementType([
            'statementNumber' => $statementRu->DOCUMENTNUMBER,
            'dateCreated' => $formatDate($statementRu->DATAACTUALITY, 'c'),
            'currency' => DictCurrency::findOne(['code' => $statementRu->CURRCODE])->name,
            'statementAccountNumber' => $statementRu->ACCOUNT,
            'statementAccountBIK' => $statementAccount !== null ? $statementAccount->bankBik : null,
            'statementPeriodStart' => $formatDate($statementRu->STATEMENTDATE, 'd.m.Y'),
            'statementPeriodEnd' => $formatDate($statementRu->STATEMENTDATE, 'd.m.Y'),
            'openingBalance' => $statementRu->OPENINGBALANCE,
            'closingBalance' => $statementRu->CLOSINGBALANCE,
            'debitTurnover' => $statementRu->DEBETTURNOVER,
            'creditTurnover' => $statementRu->CREDITTURNOVER,
            'companyName' => $statementAccount !== null ? $statementAccount->payerName : null,
        ]);

        $getAccountCurrencyName = function ($accountNumber) {
            $currencyNumericCode = substr($accountNumber, 5, 3);
            $currency = DictCurrency::findOne(['code' => $currencyNumericCode]);
            return $currency !== null ? $currency->name : null;
        };

        $createTransaction = function (StatementRuDocument $document, $isCredit) use ($statementRu, $getAccountCurrencyName, $formatDate) {
            $transaction = [
                'Amount' => $document->AMOUNT,
                'Credit' => $isCredit ? $document->AMOUNT : 0,
                'Debit' => $isCredit ? 0 : $document->AMOUNT,
                'PayerAmount' => $isCredit ? $document->AMOUNTNAT : $document->AMOUNT,
                'PayeeAmount' => $isCredit ? $document->AMOUNT : $document->AMOUNTNAT,
                'PayerCurrency' => $getAccountCurrencyName($document->PAYERACCOUNT),
                'PayeeCurrency' => $getAccountCurrencyName($document->RECEIVERACCOUNT),
                'EntryDate' => $formatDate($document->VALUEDATE, 'c'),
                'ValueDate' => $formatDate($document->VALUEDATE ?: $statementRu->STATEMENTDATE, 'c'),
                'Number' => $document->DOCUMENTNUMBER,
                'DocDate' => $formatDate($document->DOCUMENTDATE, 'Y-m-d'),
                'PaymentKind' => $document->SENDTYPE,
                'Purpose' => $document->GROUND,
                'Priority' => $document->PAYMENTURGENT,
                'PayType' => $document->OPERTYPE,
                'PayCode' => $document->CODEUIP,
                'PayerName' => $document->PAYER,
                'PayerAccountNum' => $document->PAYERACCOUNT,
                'PayerBIK' => $document->PAYERBIC,
                'PayerBankName' => $document->PAYERBANKNAME,
                'PayerBankAccountNum' => $document->PAYERCORRACCOUNT,
                'PayerINN' => $document->PAYERINN,
                'PayerKPP' => $document->PAYERKPP,
                'PayeeName' => $document->RECEIVER,
                'PayeeAccountNum' => $document->RECEIVERACCOUNT,
                'PayeeBIK' => $document->RECEIVERBIC,
                'PayeeBankName' => $document->RECEIVERBANKNAME,
                'PayeeBankAccountNum' => $document->RECEIVERCORRACCOUNT,
                'PayeeINN' => $document->RECEIVERINN,
                'PayeeKPP' => $document->RECEIVERKPP,
                'DepInfStatus' => $document->STAT1256,
                'DepInfKBK' => $document->CBCCODE,
                'DepInfOKTMO' => $document->OKATOCODE,
                'DepInfPayReason' => $document->PAYGRNDPARAM,
                'DepInfTaxPeriod' => $document->TAXPERIODPARAM,
                'DepInfTaxDocNumber' => 0,
                'DepInfTaxDocDate' => 0,
                'DepInfTaxType' => $document->PAYTYPEPARAM,
                'ReceiptDate' => '',
            ];

            $uniqId = $document->DOCREF ?: self::generateUniqId($transaction);
            $transaction['Reference'] = $uniqId;
            $transaction['UniqId'] = $uniqId;

            return $transaction;
        };

        $createDebitTransaction = function (StatementRuDocument $document) use ($createTransaction) {
            return $createTransaction($document, false);
        };

        $createCreditTransaction = function (StatementRuDocument $document) use ($createTransaction) {
            return $createTransaction($document, true);
        };

        $statementTypeModel->transactions = array_merge(
                array_map($createCreditTransaction, $statementRu->CREDITDOCUMENTS), array_map($createDebitTransaction, $statementRu->DEBETDOCUMENTS)
        );

        return $statementTypeModel;
    }

    private static function createStatementFromSBBOLStatement(SBBOLStatementType $typeModel)
    {
        $statement = $typeModel->response->getStatements()->getStatement()[0];
        $statementAccount = EdmPayerAccount::findOne(['number' => $statement->getAcc()]);

        $statementTypeModel = new StatementType([
            'statementNumber' => $statement->getDocNum(),
            'dateCreated' => $statement->getStmtDateTime() ? $statement->getStmtDateTime()->format('c') : null,
            'currency' => $statementAccount ? $statementAccount->edmDictCurrencies->name : null,
            'statementAccountNumber' => $statement->getAcc(),
            'statementAccountBIK' => $statement->getBic(),
            'statementAccountName' => $statement->getAccountName(),
            'statementPeriodStart' => $statement->getBeginDate() ? $statement->getBeginDate()->format('d.m.Y') : null,
            'statementPeriodEnd' => $statement->getEndDate() ? $statement->getEndDate()->format('d.m.Y') : null,
            'openingBalance' => $statement->getEnterBal(),
            'closingBalance' => $statement->getOutBal(),
            'debitTurnover' => $statement->getDebetSum(),
            'creditTurnover' => $statement->getCreditSum(),
            'companyName' => $statement->getOrgName(),
            'prevLastOperationDate' => $statement->getLastMovetDate() ? $statement->getLastMovetDate()->format('d.m.Y') : null,
        ]);

        $currencyNameCache = [];
        $getAccountCurrencyName = function ($accountNumber) use (&$currencyNameCache) {
            $currencyNumericCode = substr($accountNumber, 5, 3);
            if (empty($currencyNumericCode)) {
                return null;
            }

            if (!array_key_exists($currencyNumericCode, $currencyNameCache)) {
                $currency = DictCurrency::findOne(['code' => $currencyNumericCode]);
                $currencyNameCache[$currencyNumericCode] = $currency !== null ? $currency->name : null;
            }

            return $currencyNameCache[$currencyNumericCode];
        };

        $createTransaction = function (TransInfoType $document) use ($statement, $getAccountCurrencyName) {
            $departmentalInfo = $document->getDepartmentalInfo();
            $isCredit = $statement->getAcc() === $document->getPayeeAcc();

            $transaction = [
                'Amount' => $document->getDocSum(),
                'Credit' => $isCredit ? $document->getDocSum() : 0,
                'Debit' => $isCredit ? 0 : $document->getDocSum(),
                'PayerAmount' => $isCredit ? $document->getDocSumNat() : $document->getDocSum(),
                'PayeeAmount' => $isCredit ? $document->getDocSum() : $document->getDocSumNat(),
                'PayerCurrency' => $getAccountCurrencyName($document->getPayerAcc()),
                'PayeeCurrency' => $getAccountCurrencyName($document->getPayeeAcc()),
                'EntryDate' => $document->getCarryDate() ? $document->getCarryDate()->format('c') : null,
                'ValueDate' => $document->getChargeOffDate() ? $document->getChargeOffDate()->format('c') : null,
                'ReceiptDate' => $document->getReceiptDate() ? $document->getReceiptDate()->format('c') : null,
                'Number' => $document->getDocNum(),
                'DocDate' => $document->getDocDate() ? $document->getDocDate()->format('Y-m-d') : null,
                'PaymentKind' => $document->getPaytKind(),
                'Purpose' => $document->getPurpose(),
                'Priority' => $document->getPaymentOrder(),
                'PayType' => $document->getTransKind(),
                'PayCode' => $document->getUip(),
                'PayerName' => $document->getPayerName(),
                'PayerAccountNum' => $document->getPayerAcc(),
                'PayerBIK' => $document->getPayerBankBic(),
                'PayerBankName' => $document->getPayerBankName(),
                'PayerBankAccountNum' => $document->getPayerBankCorrAcc(),
                'PayerINN' => $document->getPayerINN(),
                'PayerKPP' => $document->getPayerKPP(),
                'PayeeName' => $document->getPayeeName(),
                'PayeeAccountNum' => $document->getPayeeAcc(),
                'PayeeBIK' => $document->getPayeeBankBic(),
                'PayeeBankName' => $document->getPayeeBankName(),
                'PayeeBankAccountNum' => $document->getPayeeBankCorrAcc(),
                'PayeeINN' => $document->getPayeeINN(),
                'PayeeKPP' => $document->getPayeeKPP(),
                'DepInfStatus' => $departmentalInfo ? $departmentalInfo->getDrawerStatus() : null,
                'DepInfKBK' => $departmentalInfo ? $departmentalInfo->getCbc() : null,
                'DepInfOKTMO' => $departmentalInfo ? $departmentalInfo->getOkato() : null,
                'DepInfPayReason' => $departmentalInfo ? $departmentalInfo->getPaytReason() : null,
                'DepInfTaxPeriod' => $departmentalInfo ? $departmentalInfo->getTaxPeriod() : null,
                'DepInfTaxDocNumber' => $departmentalInfo ? $departmentalInfo->getDocNo() : null,
                'DepInfTaxDocDate' => $departmentalInfo ? $departmentalInfo->getDocDate() : null,
                'DepInfTaxType' => $departmentalInfo ? $departmentalInfo->getTaxPaytKind() : null,
                'DepInfPayerKPP' => $departmentalInfo ? $departmentalInfo->getKpp102() : null,
                'DepInfPayeeKPP' => $departmentalInfo ? $departmentalInfo->getKpp103() : null,
            ];

            $uniqId = $document->getDocAbcId() ?: self::generateUniqId($transaction);
            $transaction['Reference'] = $uniqId;
            $transaction['UniqId'] = $uniqId;

            return $transaction;
        };

        $statementTypeModel->transactions = array_map(
                $createTransaction, $statement->getDocs()
        );

        return $statementTypeModel;
    }

    private static function createStatementFromRaiffeisenStatement(RaiffeisenStatementType $typeModel): StatementType
    {
        $statement = $typeModel->response->getStatementsRaif()[0];

        $dateCreated = new \DateTime($statement->getDocDate()->format('Y-m-d'));
        $dateCreated->setTime($statement->getDocTime()->format('H'), $statement->getDocTime()->format('i'), $statement->getDocTime()->format('s'));

        $account = EdmPayerAccount::findOne(['number' => $statement->getAcc()]);
        $accountOwnerName = $account ? $account->edmDictOrganization->name : null;

        $endDate = $statement->getEndDate() ?: $statement->getBeginDate();

        $statementTypeModel = new StatementType([
            'statementNumber' => $statement->getDocNum(),
            'dateCreated' => $dateCreated->format('c'),
            'currency' => DictCurrency::getAlphaCodeByNumericCode($statement->getCurrCode()),
            'statementAccountNumber' => $statement->getAcc(),
            'statementAccountBIK' => $statement->getBic(),
            'statementAccountName' => $accountOwnerName,
            'statementPeriodStart' => $statement->getBeginDate() ? $statement->getBeginDate()->format('d.m.Y') : null,
            'statementPeriodEnd' => $endDate ? $endDate->format('d.m.Y') : null,
            'openingBalance' => $statement->getEnterBal(),
            'closingBalance' => $statement->getOutBal(),
            'debitTurnover' => $statement->getDebetSum(),
            'creditTurnover' => $statement->getCreditSum(),
            'companyName' => $accountOwnerName,
            'prevLastOperationDate' => $statement->getLastMovetDate() ? $statement->getLastMovetDate()->format('d.m.Y') : null,
        ]);

        $currencyNameCache = [];
        $getAccountCurrencyName = function ($accountNumber) use (&$currencyNameCache) {
            $currencyNumericCode = substr($accountNumber, 5, 3);
            if (empty($currencyNumericCode)) {
                return null;
            }

            if (!array_key_exists($currencyNumericCode, $currencyNameCache)) {
                $currency = DictCurrency::findOne(['code' => $currencyNumericCode]);
                $currencyNameCache[$currencyNumericCode] = $currency !== null ? $currency->name : null;
            }

            return $currencyNameCache[$currencyNumericCode];
        };

        $createTransaction = function (TransInfoRaifType $document) use ($statement, $getAccountCurrencyName) {
            $departmentalInfo = $document->getDepartmentalInfo();
            $isCredit = $document->getDc() == 2;
            $isDebit = $document->getDc() == 1;
            $valueDate = $document->getValueDate() ?: $document->getWriteOffDate();

            $transaction = [
                'Amount' => $document->getDocSumNat() ?: $document->getDocSum(),
                'Credit' => $isCredit ? $document->getDocSum() : 0,
                'Debit' => $isDebit ? $document->getDocSum() : 0,
                'PayerAmount' => $document->getDocSum(),
                'PayeeAmount' => $document->getDocSum(),
                'PayerCurrency' => $getAccountCurrencyName($document->getPersonalAcc()),
                'PayeeCurrency' => $getAccountCurrencyName($document->getCorrAcc()),
                'EntryDate' => $document->getReceiptDate() ? $document->getReceiptDate()->format('c') : null,
                'ValueDate' => $valueDate ? $valueDate->format('c') : null,
                'ReceiptDate' => $document->getReceiptDate() ? $document->getReceiptDate()->format('c') : null,
                'Number' => $document->getDocNum(),
                'DocDate' => $document->getDocDate() ? $document->getDocDate()->format('Y-m-d') : null,
                'PaymentKind' => $document->getPaytKind(),
                'Purpose' => $document->getPurpose() ?: $document->getPayDtls(),
                'Priority' => $document->getPaymentOrder(),
                'PayType' => $document->getTransKind(),
                'PayCode' => $document->getUip(),
                'PayerName' => $document->getPersonalName(),
                'PayerAccountNum' => $document->getPersonalAcc(),
                'PayerBIK' => $document->getPayerBankBic(),
                'PayerBankName' => $document->getBank(),
                'PayerBankAccountNum' => null,
                'PayerINN' => $document->getPersonalINN(),
                'PayerKPP' => $document->getPersonalKPP(),
                'PayeeName' => $document->getReceiver() ?: $document->getReceiptName(),
                'PayeeAccountNum' => $document->getCorrAcc(),
                'PayeeBIK' => $document->getCorrBIC(),
                'PayeeBankName' => $document->getReceiverBankName(),
                'PayeeBankAccountNum' => $document->getReceiverBankCorrAccount(),
                'PayeeINN' => $document->getReceiverINN(),
                'PayeeKPP' => $document->getReceiverKPP(),
                'DepInfStatus' => $departmentalInfo ? $departmentalInfo->getDrawerStatus() : null,
                'DepInfKBK' => $departmentalInfo ? $departmentalInfo->getCbc() : null,
                'DepInfOKTMO' => $departmentalInfo ? $departmentalInfo->getOkato() : null,
                'DepInfPayReason' => $departmentalInfo ? $departmentalInfo->getPaytReason() : null,
                'DepInfTaxPeriod' => $departmentalInfo ? $departmentalInfo->getTaxPeriod() : null,
                'DepInfTaxDocNumber' => $departmentalInfo ? $departmentalInfo->getDocNo() : null,
                'DepInfTaxDocDate' => $departmentalInfo ? $departmentalInfo->getDocDate() : null,
                'DepInfTaxType' => $departmentalInfo ? $departmentalInfo->getTaxPaytKind() : null,
                'DepInfPayerKPP' => null,
                'DepInfPayeeKPP' => null,
            ];

            $uniqId = $document->getExtId() ?: self::generateUniqId($transaction);
            $transaction['Reference'] = $uniqId;
            $transaction['UniqId'] = $uniqId;

            return $transaction;
        };

        $statementTypeModel->transactions = array_map(
                $createTransaction, $statement->getDocs()
        );
        $statementTypeModel->buildXml();

        return $statementTypeModel;
    }

    private static function generateUniqId($transaction)
    {
        return md5($transaction['EntryDate'] . $transaction['PayerINN'] . $transaction['Number'] . $transaction['Amount']);
    }

}

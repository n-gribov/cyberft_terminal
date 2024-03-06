<?php

namespace addons\edm\helpers;

use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestExt;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyOperationInformationExt;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\Statement\StatementType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use common\helpers\DateHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\RowCellIterator;
use PhpOffice\PhpSpreadsheet\Worksheet\RowIterator;
use Yii;
use yii\helpers\ArrayHelper;

abstract class Converter {
    /**
     * @param StatementType $statement
     * @param string $mode
     * @return string
     * @throws \Exception
     */
	public static function statementTo1C(StatementType $statement, $mode = 'all')
	{
		$document = self::doc1CByStatement($statement);

		// костылек берем заготовку тела
		// @todo реализовать человеческий statement и конверт для 1С и конверить в него

		$layout = preg_replace('/(СекцияДокумент.*КонецДокумента)/ms','{placeholder}', $document);

		// и формируем участки документа
		$body = "\r\n";

		foreach ($statement->transactions as $v) {

            // Отображение транзаций в зависимости от режима
            if ($mode == 'debit' && empty($v['Debit'])) {
                continue;
            } elseif ($mode == 'credit' && empty($v['Credit'])) {
                continue;
            }

            // Определение типа платежки - поручение или требование
            if (!empty($v['OKUD'])) {
                if ($v['OKUD'] == '0401061') {
                    $transactionType = "PaymentRequest";
                } else {
                    $transactionType = "PaymentOrder";
                }
            } else {
                $transactionType = "PaymentOrder";
            }

            // Вывод заголовка секции в зависимости от типа платежки
            if ($transactionType == "PaymentRequest") {
                $body .= "СекцияДокумент=Платежное требование\r\n";
            } elseif ($transactionType == "PaymentOrder") {
                $body .= "СекцияДокумент=Платежное поручение\r\n";
            }

            if ($v['DocDate']) {
                $body .= "Дата=" . DateHelper::convert($v['DocDate'], 'date', 'php:d.m.Y') . "\r\n";
            }
			$body .= "Номер={$v['Number']}\r\n";
			$body .= "Сумма={$v['Amount']}\r\n";
			$body .= "ПлательщикСчет={$v['PayerAccountNum']}\r\n";
			$body .= "ПлательщикРасчСчет={$v['PayerAccountNum']}\r\n";
			$body .= !empty($v['PayerBIK']) ? "ПлательщикБИК={$v['PayerBIK']}\r\n" : null;

            if (!empty($v['PayerINN'])) {
                $body .= !empty($v['PayerName']) ? "Плательщик=ИНН {$v['PayerINN']} {$v['PayerName']} \r\n" : null;
            } else {
                $body .= !empty($v['PayerName']) ? "Плательщик={$v['PayerName']} \r\n" : null;
            }

			$body .= 'ПлательщикИНН=' . ($v['PayerINN'] ? $v['PayerINN'] : '0') . "\r\n";
			$body .= 'ПлательщикКПП=' . ($v['PayerKPP'] ? $v['PayerKPP'] : '0') . "\r\n";
            $body .= !empty($v['PayerName']) ? "Плательщик1={$v['PayerName']} \r\n" : null;
			$body .= !empty($v['PayerBankName']) ? "ПлательщикБанк1={$v['PayerBankName']}\r\n" : null;
			$body .= !empty($v['PayerBankAccountNum']) ? "ПлательщикКорсчет={$v['PayerBankAccountNum']}\r\n" : null;
            $body .= !empty($v['PayeeAccountNum']) ? "ПолучательСчет={$v['PayeeAccountNum']}\r\n" : null;

			//$body .= !empty($v['PayeeBankAccountNum']) ? "ПолучательРасчСчет={$v['PayeeBankAccountNum']}\r\n" : null;
            // CYB-2891
            $body .= !empty($v['PayeeAccountNum']) ? "ПолучательРасчСчет={$v['PayeeAccountNum']}\r\n" : null;

			$body .= !empty($v['PayeeBIK']) ? "ПолучательБИК={$v['PayeeBIK']}\r\n" : null;
			$body .= "НазначениеПлатежа={$v['Purpose']}\r\n";

            if (!empty($v['PayeeINN'])) {
                $body .= !empty($v['PayeeName']) ? "Получатель=ИНН {$v['PayeeINN']} {$v['PayeeName']} \r\n" : null;
            } else {
                $body .= !empty($v['PayeeName']) ? "Получатель=ИНН {$v['PayeeINN']} \r\n" : null;
            }

			$body .= 'ПолучательИНН=' . ($v['PayeeINN'] ? $v['PayeeINN'] : '0') . "\r\n";
			$body .= 'ПолучательКПП=' . ($v['PayeeKPP'] ? $v['PayeeKPP'] : '0') . "\r\n";
            $body .= !empty($v['PayeeName']) ? "Получатель1={$v['PayeeName']} \r\n" : null;
			$body .= !empty($v['PayeeBankName']) ? "ПолучательБанк1={$v['PayeeBankName']}\r\n" : null;
			$body .= !empty($v['PayeeBankAccountNum']) ? "ПолучательКорсчет={$v['PayeeBankAccountNum']}\r\n" : null;
			$body .= !empty($v['PayCode']) ? "Код={$v['PayCode']}\r\n" : null;
			$body .= !empty($v['ValueDate']) ? 'ДатаСписано=' . DateHelper::formatDate($v['ValueDate'], 'date') . "\r\n" : null;
			$body .= !empty($v['EntryDate']) ? 'ДатаПоступило=' . DateHelper::formatDate($v['EntryDate'], 'date') . "\r\n" : null;
            $body .= !empty($v['Priority']) ? "Очередность={$v['Priority']}\r\n" : null;
            $body .= "ВидПлатежа=Электронно\r\n";
            if ($v['IncomeTypeCode']) {
                $body .= "КодНазПлатежа={$v['IncomeTypeCode']}\r\n";
            }

            $deptInfoCheckList = [
                'СтатусСоставителя' => 'DepInfStatus',
                'ПоказательКБК' => 'DepInfKBK',
                'ОКАТО' => 'DepInfOKTMO',
                'ПоказательОснования' => 'DepInfPayReason',
                'ПоказательПериода' => 'DepInfTaxPeriod',
                'ПоказательНомера' => 'DepInfTaxDocNumber',
                'ПоказательДаты' => 'DepInfTaxDocDate',
                'ПоказательТипа' => 'DepInfTaxType',
            ];

            $isEmpty = true;
            foreach($deptInfoCheckList as $attribute) {
                if ($v[$attribute]) {
                    $isEmpty = false;
                    break;
                }
            }
            if (!$isEmpty) {
                foreach($deptInfoCheckList as $targetAttribute => $attribute) {
                    $body .= $targetAttribute . '=' . ($v[$attribute] ?: '0') . "\r\n";
                }
            }

            // Поля, специфичные для платежного требования
            if ($transactionType == 'PaymentRequest') {
                $body .= !empty($v['PaymentCondition1']) ? "УсловиеОплаты1={$v['PaymentCondition1']} \r\n" : null;
                $body .= !empty($v['AcceptPeriod']) ? "СрокАкцепта={$v['AcceptPeriod']} \r\n" : null;
            }

			$body .= "КонецДокумента\r\n\r\n";
		}

        if (strpos($layout, '{placeholder}') === false) {
            return $layout . $body;
        }

        // Выписка в формате 1С должна быть в кодировке cp1251
        return iconv('UTF-8', 'cp1251', str_replace('{placeholder}', $body, $layout));
	}

    /**
     * @param StatementType $statement
     * @param string $documentCreateDate
     * @param string $documentUuid
     * @param string $statementUuid
     * @param string $isoDocumentType ISO document type (camt.052, camt.053 or camt.054)
     * @return mixed
     * @throws \Exception
     */
    public static function statementToIsoCamtXml(StatementType $statement, $documentCreateDate, $documentUuid, $statementUuid, $isoDocumentType)
    {
        $msgUuid = str_replace('-', '', $documentUuid);
        if (!$statementUuid) {
            $statementUuid = $msgUuid;
        }

        switch ($isoDocumentType) {
            case Camt052Type::TYPE:
                $documentTagName = 'BkToCstmrAcctRpt';
                $statementTagName = 'Rpt';
                break;
            case Camt053Type::TYPE:
                $documentTagName = 'BkToCstmrStmt';
                $statementTagName = 'Stmt';
                break;
            case Camt054Type::TYPE:
                $documentTagName = 'BkToCstmrDbtCdtNtfctn';
                $statementTagName = 'Ntfctn';
                break;
            default:
                throw new \Exception("Unsupported ISO document type: $isoDocumentType");
        }

        $xml = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>'
            . "<Document xmlns=\"urn:iso:std:iso:20022:tech:xsd:{$isoDocumentType}.001.02\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">"
            . '</Document>'
        );
        $documentNode = $xml->addChild($documentTagName);

        $documentNode->GrpHdr->MsgId = $msgUuid;
        $documentNode->GrpHdr->CreDtTm = $documentCreateDate ? (new \DateTime($documentCreateDate))->format('c') : null;

        $statementNode = $documentNode->addChild($statementTagName);
        $statementNode->Id = $statementUuid;
        $statementNumber = $statement->statementNumber;
        if (!$statementNumber && $statement->dateCreated) {
            $statementNumber = (new \DateTime($statement->dateCreated))->format('His');
        }
        if ($statementNumber) {
            $statementNode->ElctrncSeqNb = $statementNumber;
        }
        $statementNode->CreDtTm = $statement->dateCreated;

        $startDate = new \DateTime($statement->statementPeriodStart . ' 00:00:00');
        $endDate = new \DateTime($statement->statementPeriodEnd . ' 23:59:59');
        $statementNode->FrToDt->FrDtTm = $startDate->format('c');
        $statementNode->FrToDt->ToDtTm = $endDate->format('c');

        $statementNode->Acct->Id->Othr->Id = $statement->statementAccountNumber;
        $statementNode->Acct->Id->Othr->SchmeNm->Cd = 'BBAN';

        // Если при экспорте из модели невозможно определить наименование организации владельца счета
        // для отражения в header выписки, то в ISO документе не создавать тег <Stmt><Acct><Ownr><Nm/>
        // т.к. он не может быть пустым.
        $nm = $statement->statementAccountName;
        $org = static::getOrganization($statement);
        if (!$nm && $org) {
            $nm = $org->name;
        }
        if ($nm) {
            $statementNode->Acct->Ownr->Nm = mb_substr($nm, 0, 140);
        }
        if ($org) {
            $statementNode->Acct->Ownr->Id->OrgId->Othr->Id = $org->inn;
            $statementNode->Acct->Ownr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID'; // Всегда TXID
        }

        $statementNode->Acct->Svcr->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
        $statementNode->Acct->Svcr->FinInstnId->ClrSysMmbId->MmbId = $statement->statementAccountBIK;

        if ($isoDocumentType !== Camt054Type::TYPE) {
            $statementNode->Bal[0]->Tp->CdOrPrtry->Cd = 'OPBD';
            $statementNode->Bal[0]->Amt = $statement->openingBalance ?: 0;
            $statementNode->Bal[0]->Amt->addAttribute('Ccy', $statement->currency);
            $statementNode->Bal[0]->CdtDbtInd = 'CRDT';
            $statementNode->Bal[0]->Dt->Dt = $startDate->format('Y-m-d');

            $statementNode->Bal[1]->Tp->CdOrPrtry->Cd = 'CLBD';
            $statementNode->Bal[1]->Amt = $statement->closingBalance ?: 0;
            $statementNode->Bal[1]->Amt->addAttribute('Ccy', $statement->currency);
            $statementNode->Bal[1]->CdtDbtInd = 'CRDT';
            $statementNode->Bal[1]->Dt->Dt = $endDate->format('Y-m-d');

            $statementNode->TxsSummry->TtlCdtNtries->Sum = $statement->debitTurnover;
            $statementNode->TxsSummry->TtlDbtNtries->Sum = $statement->creditTurnover;
        }

        $isEmpty = function ($value) {
            return $value === null || $value === '';
        };

        $removeDashesFromUuid = function ($value) {
            if (preg_match('/^[a-z0-9]{8}(-[a-z0-9]{4}){3}-[a-z0-9]{12}$/i', $value)) {
                return str_replace('-', '', $value);
            }

            return $value;
        };

        foreach ($statement->transactions as $i => $transaction) {
            $ntry = $statementNode->addChild('Ntry');

            $ntry->NtryRef = $removeDashesFromUuid($transaction['UniqId']);
            $ntry->Amt = $transaction['Amount'];
            $ntry->Amt->addAttribute('Ccy', $transaction['PayerCurrency']);
            $ntry->CdtDbtInd = $transaction['Debit'] > 0 ? 'DBIT' : 'CRDT';
            $ntry->Sts = 'BOOK';

            $valueDate = $transaction['ValueDate'];
            if ($valueDate) {
                $valueDateIso = (new \DateTime($valueDate))->format('Y-m-d');
                $ntry->BookgDt->Dt = $valueDateIso;
                $ntry->ValDt->Dt = $valueDateIso;
            }

            // Если при конвертации документа в ISO не можем определить
            // или в исходном документе не задан <AcctSvcrRef/>, то не создаем этот тег
            if (!empty($transaction['Reference'])) {
                $ntry->AcctSvcrRef = $removeDashesFromUuid($transaction['Reference']);
            }

            $ntry->BkTxCd = null;

            $ntry->NtryDtls->TxDtls->Refs->EndToEndId = $transaction['Number'];

            if (!$isEmpty($transaction['PayerName'])) {
                $ntry->NtryDtls->TxDtls->RltdPties->Dbtr->Nm = mb_substr($transaction['PayerName'], 0, 140);
                if (!$isEmpty($transaction['PayerINN'])) {
                    $ntry->NtryDtls->TxDtls->RltdPties->Dbtr->Id->OrgId->Othr->Id = $transaction['PayerINN'];
                    $ntry->NtryDtls->TxDtls->RltdPties->Dbtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
                }
            }
            if (!$isEmpty($transaction['PayerAccountNum'])) {
                $ntry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Othr->Id = $transaction['PayerAccountNum'];
                $ntry->NtryDtls->TxDtls->RltdPties->DbtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
            }
            if (!$isEmpty($transaction['PayerBIK']) && !$isEmpty($transaction['PayerBankName'])) {
                $ntry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
                $ntry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId = $transaction['PayerBIK'];
                $ntry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Nm = mb_substr($transaction['PayerBankName'], 0, 140);

                if (!$isEmpty($transaction['PayerBankAccountNum'])) {
                    $ntry->NtryDtls->TxDtls->RltdAgts->DbtrAgt->FinInstnId->Othr->Id = $transaction['PayerBankAccountNum'];
                }
            }

            if (!$isEmpty($transaction['PayeeName'])) {
                $ntry->NtryDtls->TxDtls->RltdPties->Cdtr->Nm = mb_substr($transaction['PayeeName'], 0, 140);
                if (!$isEmpty($transaction['PayeeINN'])) {
                    $ntry->NtryDtls->TxDtls->RltdPties->Cdtr->Id->OrgId->Othr->Id = $transaction['PayeeINN'];
                    $ntry->NtryDtls->TxDtls->RltdPties->Cdtr->Id->OrgId->Othr->SchmeNm->Cd = 'TXID';
                }
            }

            if (!$isEmpty($transaction['PayeeAccountNum'])) {
                $ntry->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->Othr->Id = $transaction['PayeeAccountNum'];
                $ntry->NtryDtls->TxDtls->RltdPties->CdtrAcct->Id->Othr->SchmeNm->Cd = 'BBAN';
            }

            if (!$isEmpty($transaction['PayeeBankName']) && !$isEmpty($transaction['PayeeBIK'])) {
                $ntry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->ClrSysMmbId->ClrSysId->Cd = 'RUCBC';
                $ntry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId = $transaction['PayeeBIK'];
                $ntry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Nm = mb_substr($transaction['PayeeBankName'], 0, 140);

                if (!$isEmpty($transaction['PayeeBankAccountNum'])) {
                    $ntry->NtryDtls->TxDtls->RltdAgts->CdtrAgt->FinInstnId->Othr->Id = $transaction['PayeeBankAccountNum'];
                }
            }

            if (!$isEmpty($transaction['Priority'])) {
                $ntry->NtryDtls->TxDtls->Purp->Prtry = $transaction['Priority'];
            }

            if (!$isEmpty($transaction['Purpose'])) {
                // Split purpose by 140 character chunks and put them into separate tags
                $purpose = $transaction['Purpose'];
                $length  = mb_strlen($purpose);
                $splitLength = 140;
                $chunks = [];
                for ($i = 0; $i < $length; $i += $splitLength) {
                    $chunks[] = mb_substr($purpose, $i, $splitLength);
                }
                $rmtInf = $ntry->NtryDtls->TxDtls->addChild('RmtInf');
                foreach ($chunks as $chunk) {
                    $rmtInf->addChild('Ustrd', htmlspecialchars($chunk));
                }
            }

            $ntry->NtryDtls->TxDtls->RmtInf->Strd->RfrdDocInf->Tp->CdOrPrtry->Prtry = 'POD';
            if ($transaction['DocDate']) {
                $ntry->NtryDtls->TxDtls->RmtInf->Strd->RfrdDocInf->RltdDt = (new \DateTime($transaction['DocDate']))->format('Y-m-d');
            }

            if (!$isEmpty($transaction['PayCode'])) {
                $ntry->NtryDtls->TxDtls->RmtInf->Strd->CdtrRefInf->Ref = $transaction['PayCode'];
            }

            if (!$isEmpty($transaction['ReceiptDate'])) {
                $ntry->NtryDtls->TxDtls->RltdDts->AccptncDtTm = $transaction['ReceiptDate'];
            }

            if (!$isEmpty($transaction['PayeeKPP'])) {
                $ntry->NtryDtls->TxDtls->Tax->Cdtr->TaxTp = $transaction['PayeeKPP'];
            }
            if (!$isEmpty($transaction['PayerKPP'])) {
                $ntry->NtryDtls->TxDtls->Tax->Dbtr->TaxTp = $transaction['PayerKPP'];
            }

            if (!$isEmpty($transaction['DepInfOKTMO'])) {
                $ntry->NtryDtls->TxDtls->Tax->AdmstnZn = $transaction['DepInfOKTMO'];
            }
            if (!$isEmpty($transaction['DepInfTaxDocNumber'])) {
                $ntry->NtryDtls->TxDtls->Tax->RefNb = $transaction['DepInfTaxDocNumber'];
            }

            $taxDocDateDate = $transaction['DepInfTaxDocDate'];
            if (in_array($taxDocDateDate, ['0', '00'], true)) {
                $ntry->NtryDtls->TxDtls->Tax->Mtd = $taxDocDateDate;
            } elseif ($taxDocDateDate) {
                $ntry->NtryDtls->TxDtls->Tax->Dt = (new \DateTime($taxDocDateDate))->format('Y-m-d');
            }

            if (!$isEmpty($transaction['DepInfTaxType'])) {
                $ntry->NtryDtls->TxDtls->Tax->Rcrd->Tp = $transaction['DepInfTaxType'];
            }
            if (!$isEmpty($transaction['DepInfPayReason'])) {
                $ntry->NtryDtls->TxDtls->Tax->Rcrd->Ctgy = $transaction['DepInfPayReason'];
            }
            if (!$isEmpty($transaction['DepInfKBK'])) {
                $ntry->NtryDtls->TxDtls->Tax->Rcrd->CtgyDtls = $transaction['DepInfKBK'];
            }
            if (!$isEmpty($transaction['DepInfStatus'])) {
                $ntry->NtryDtls->TxDtls->Tax->Rcrd->DbtrSts = $transaction['DepInfStatus'];
            }

            if (!$isEmpty($transaction['DepInfTaxPeriod'])) {
                if (preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $transaction['DepInfTaxPeriod'])) {
                    $date = (new \DateTime($transaction['DepInfTaxPeriod']))->format('Y-m-d');
                    $ntry->NtryDtls->TxDtls->Tax->Rcrd->Prd->FrToDt->FrDt = $date;
                    $ntry->NtryDtls->TxDtls->Tax->Rcrd->Prd->FrToDt->ToDt = $date;
                } else {
                    $taxPeriodParams = ISO20022Helper::getBudgetPeriodFromTxt($transaction['DepInfTaxPeriod']);
                    if ($taxPeriodParams['prdYr']) {
                        $ntry->NtryDtls->TxDtls->Tax->Rcrd->Prd->Yr = $taxPeriodParams['prdYr'];
                        if ($taxPeriodParams['prdTp']) {
                            $ntry->NtryDtls->TxDtls->Tax->Rcrd->Prd->Tp = $taxPeriodParams['prdTp'];
                        }
                    }
                }
            }

            if (array_key_exists('DepInfPayeeKPP', $transaction) && !$isEmpty($transaction['DepInfPayeeKPP'])) {
                $ntry->NtryDtls->TxDtls->Tax->Cdtr->TaxTp = $transaction['DepInfPayeeKPP'];
            }
            if (array_key_exists('DepInfPayerKPP', $transaction) && !$isEmpty($transaction['DepInfPayerKPP'])) {
                $ntry->NtryDtls->TxDtls->Tax->Dbtr->TaxTp = $transaction['DepInfPayerKPP'];
            }
        }

        return $xml->saveXML();
    }

    /**
	 * @param StatementType $statement
	 * @param string              $xlsView
	 * @return Spreadsheet
	 * @throws \Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public static function statementToXls(StatementType $statement, $xlsView)
	{
		$document = self::doc1CByStatement($statement);

        $statementTags = ['companyName', 'reservedAmount', 'prevLastOperationDate'];
        $formatNumberTags = [
            'ВсегоПоступило', 'ВсегоСписано', 'НачальныйОстаток', 'КонечныйОстаток'
        ];

		$xls = IOFactory::load($xlsView);
		$xls->garbageCollect();
		$xls->getProperties()->setCompany('Киберплат');
		$xls->getProperties()->setCreator('Киберплат');
		$xls->getProperties()->setLastModifiedBy('Киберплат');
		$xls->getProperties()->setModified(date('U'));

		/** @var RowIterator $rows */
		$sheet = $xls->getActiveSheet();
		$rows  = $sheet->getRowIterator(); // по строкам
		$sheet->insertNewRowBefore(1);
//		// @todo при повторном использовании сделать человеческий класс шаблонизатор|конвертер из 1C в xls
		$repeatRow = null;
		foreach ($rows as $row) {
			/** @var RowCellIterator $cells */
			$cells = $row->getCellIterator(); // по ячейкам
			foreach ($cells as $cell) {
				$value = $cell->getValue(); // собираем значения
				if ($value	&& preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $value, $matches)) {
					// находим переменные
					foreach ($matches['tag'] as $k => $tag) {
						if ($tag === 'repeat:row') {
							$repeatRow = $row;
							$cell->setValue(''); // сбрасываем тэг инструкции
							continue 2;
						}

						if ($document->hasTag($tag)) { // узнаем значения
                            $tagValue = $document->getTag($tag);

                            if (in_array($tag, $formatNumberTags)) {
                                $tagValue = Yii::$app->formatter->asDecimal($tagValue, 2);
                            }

							$value = str_replace($matches['placeholder'][$k], $tagValue, $value); // и делаем "красиво"
						} else if (in_array($tag, $statementTags)) {
                            $tagValue = $statement->$tag;

                            if (in_array($tag, $formatNumberTags)) {
                                $tagValue = Yii::$app->formatter->asDecimal($tagValue, 2);
                            }

                            $value = str_replace($matches['placeholder'][$k], $tagValue, $value);
                        }
                    }
				}
				$cell->setValue($value);
			}
		}

		if (!empty($repeatRow)) {
			$transactions = $statement->transactions;

            // Добавление поля с ValueDate в нужном формате
            $transactions = array_map(function ($transaction) {
                $transaction['ValueDateFormat'] = DateHelper::formatDate((string)$transaction['ValueDate'], 'date');
                return $transaction;
            }, $transactions);

			$transactionCount = count($transactions);

			// дублируем строку с повторяющейся информацией
			$rowIndex = $repeatRow->getRowIndex();
			$endRowIndex = $rowIndex + $transactionCount;

			// Если вставить 0 строк, то исчезают нижележащие строки!
			if ($transactionCount > 1) {
				$sheet->insertNewRowBefore($rowIndex + 1, $transactionCount - 1);
			}
			$sheet->duplicateStyle(
				$sheet->getStyle('A' . $rowIndex),
				'A' . $rowIndex . ':A' . ($rowIndex + $transactionCount - 1)
			);

			// читаем шаблон дублируемой строки и формируем карту шаблонов по колонкам
			$map = [];
			$cells = $repeatRow->getCellIterator();
			foreach ($cells as $cell) {
				if (($value = $cell->getValue())) {
					$map[$cell->getColumn()] = $value;
				}
                // чтобы при пустой выписке не остались тэги в ячейках
                $cell->setValue('');
			}
			unset($value);

			// проходимся построчно и наполняем данными
			for ($i = $rowIndex, $k = 0; $i < $endRowIndex; $i++, $k++) {
				foreach ($map as $col => $pattern) {
					$cell  = $sheet->getCell($col . $i);
					$cellValue = $pattern;
					$transaction = $transactions[$k];

					preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $pattern, $matches);

					foreach ($matches['tag'] as $tag) {
                        $value = ArrayHelper::getValue($transaction, $tag, '');

                        if ($tag == 'Debit' || $tag == 'Credit') {
                            $value = Yii::$app->formatter->asDecimal($value, 2);
                        }

						$cellValue = str_replace('{' . $tag . '}', $value, $cellValue);
					}
					$cell->setValue($cellValue);
				}
			}
		}

		return $xls;
	}

    public static function foreignCurrencyOperationInformationToXls(ForeignCurrencyOperationInformationExt $fci, $xlsView)
    {
        $xls = IOFactory::load($xlsView);
        $xls->garbageCollect();
        $xls->getProperties()->setCompany('Киберплат');
        $xls->getProperties()->setCreator('Киберплат');
        $xls->getProperties()->setLastModifiedBy('Киберплат');
        $xls->getProperties()->setModified(date('U'));

        $sheet = $xls->getActiveSheet();
        $sheet->insertNewRowBefore(1);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageMargins()->setTop(0.8);
        $sheet->getPageMargins()->setBottom(0.4);
        $sheet->getPageMargins()->setLeft(0.4);
        $sheet->getPageMargins()->setRight(0.4);
        $sheet->getPageSetup()->setPrintArea('A:M');
        $sheet->getHeaderFooter()->setEvenHeader('');
        $sheet->getHeaderFooter()->setOddHeader('');
        $sheet->getHeaderFooter()->setEvenFooter('');
        $sheet->getHeaderFooter()->setOddFooter('');

        $items = $fci->items;

        $rowsOptions = [
            [
                'row' => 'repeat:row',
                'skippedRows' => ['repeat:row2'],
                'mergedRows' => null
            ],
            [
                'row' => 'repeat:row2',
                'skippedRows' => ['repeat:row'],
                'mergedRows' => [['C', 'M']]
            ]
        ];

        foreach($rowsOptions as $option) {
            $rows  = $sheet->getRowIterator();

            $repeatRow = self::getRepeatRow($fci, $rows, $option['row'], $option['skippedRows']);

            if (!empty($repeatRow)) {
                self::getRepeatRowValues($repeatRow, $items, $sheet, $option['mergedRows']);
            }
        }

        return $xls;
    }

    public static function confirmingDocumentInformationToXls(ConfirmingDocumentInformationExt $cdi, string $xlsView, array $signatures)
    {
        $xls = IOFactory::load($xlsView);
        $xls->garbageCollect();
        $xls->getProperties()->setCompany('Киберплат');
        $xls->getProperties()->setCreator('Киберплат');
        $xls->getProperties()->setLastModifiedBy('Киберплат');
        $xls->getProperties()->setModified(date('U'));

        $sheet = $xls->getActiveSheet();

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageMargins()->setTop(0.8);
        $sheet->getPageMargins()->setBottom(0.4);
        $sheet->getPageMargins()->setLeft(0.4);
        $sheet->getPageMargins()->setRight(0.4);
        $sheet->getHeaderFooter()->setEvenHeader('');
        $sheet->getHeaderFooter()->setOddHeader('');
        $sheet->getHeaderFooter()->setEvenFooter('');
        $sheet->getHeaderFooter()->setOddFooter('');

        if (empty($signatures)) {
            $sheet->removeRow(20, 3);
        }

        $rowsOptions = [
            [
                'row'         => 'repeat:row',
                'skippedRows' => ['repeat:row2', 'repeat:row3'],
                'mergedRows'  => null,
                'items'       => $cdi->items,
            ],
            [
                'row'         => 'repeat:row2',
                'skippedRows' => ['repeat:row', 'repeat:row3'],
                'mergedRows'  => [['C', 'M']],
                'items'       => $cdi->items,
            ],
            [
                'row'         => 'repeat:row3',
                'skippedRows' => ['repeat:row', 'repeat:row2'],
                'mergedRows'  => [['B', 'F'], ['G', 'K'], ['L', 'M']],
                'items'       => $signatures,
            ],
        ];

        foreach ($rowsOptions as $option) {
            $rows  = $sheet->getRowIterator();

            $repeatRow = self::getRepeatRow($cdi, $rows, $option['row'], $option['skippedRows']);

            if (!empty($repeatRow)) {
                self::getRepeatRowValues($repeatRow, $option['items'], $sheet, $option['mergedRows']);
            }
        }

        $sheet->removeColumn('A');

        return $xls;
    }

    public static function contractRegistrationRequestTradeToXls(ContractRegistrationRequestExt $crr, $xlsView)
    {
        $xls = IOFactory::load($xlsView);
        $xls->garbageCollect();
        $xls->getProperties()->setCompany('Киберплат');
        $xls->getProperties()->setCreator('Киберплат');
        $xls->getProperties()->setLastModifiedBy('Киберплат');
        $xls->getProperties()->setModified(date('U'));

        $sheet = $xls->getActiveSheet();
        $sheet->insertNewRowBefore(1);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(0);
        $sheet->getPageMargins()->setTop(0.4);
        $sheet->getPageMargins()->setBottom(0.4);
        $sheet->getPageMargins()->setLeft(0.8);
        $sheet->getPageMargins()->setRight(0.4);
        $sheet->getHeaderFooter()->setEvenHeader('');
        $sheet->getHeaderFooter()->setOddHeader('');
        $sheet->getHeaderFooter()->setEvenFooter('');
        $sheet->getHeaderFooter()->setOddFooter('');

        $items = $crr->nonresidentsItems;

        $rowsOptions = [
            [
                'row' => 'repeat:row',
                'skippedRows' => [],
                'mergedRows' => [['B', 'J'], ['K', 'T'], ['U', 'AC']]
            ]
        ];

        foreach($rowsOptions as $option) {
            $rows  = $sheet->getRowIterator();

            $repeatRow = self::getRepeatRow($crr, $rows, $option['row'], $option['skippedRows']);

            if (!empty($repeatRow)) {
                self::getRepeatRowValues($repeatRow, $items, $sheet, $option['mergedRows']);
            }
        }

        return $xls;
    }

    public static function contractRegistrationRequestLoanToXls(ContractRegistrationRequestExt $crr, $xlsView)
    {
        $xls = IOFactory::load($xlsView);
        $xls->garbageCollect();
        $xls->getProperties()->setCompany('Киберплат');
        $xls->getProperties()->setCreator('Киберплат');
        $xls->getProperties()->setLastModifiedBy('Киберплат');
        $xls->getProperties()->setModified(date('U'));

        $sheet = $xls->getActiveSheet();
        $sheet->insertNewRowBefore(1);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setPrintArea('A:AN');
        $sheet->getPageMargins()->setTop(0.4);
        $sheet->getPageMargins()->setBottom(0.4);
        $sheet->getPageMargins()->setLeft(0.8);
        $sheet->getPageMargins()->setRight(0.4);
        $sheet->getHeaderFooter()->setEvenHeader('');
        $sheet->getHeaderFooter()->setOddHeader('');
        $sheet->getHeaderFooter()->setEvenFooter('');
        $sheet->getHeaderFooter()->setOddFooter('');

        $rowsOptions = [
            [
                'row' => 'repeat:row',
                'skippedRows' => ['repeat:row2', 'repeat:row3', 'repeat:row4'],
                'mergedRows' => [['B', 'U'], ['V', 'AF'], ['AG', 'AN']],
                'items' => $crr->nonresidentsItems
            ],
            [
                'row' => 'repeat:row2',
                'skippedRows' => ['repeat:row', 'repeat:row3', 'repeat:row4'],
                'mergedRows' => [['B', 'K'], ['L', 'O'], ['P', 'X'], ['Y', 'AG'], ['AH', 'AN']],
                'items' => $crr->tranchesItems
            ],
            [
                'row' => 'repeat:row3',
                'skippedRows' => ['repeat:row', 'repeat:row2', 'repeat:row4'],
                'mergedRows' => [['B', 'C'], ['D', 'H'], ['I', 'M'], ['N', 'U'], ['V', 'Y'], ['Z', 'AG'], ['AH', 'AN']],
                'items' => $crr->paymentScheduleItems
            ],
            [
                'row' => 'repeat:row4',
                'skippedRows' => ['repeat:row', 'repeat:row2', 'repeat:row3'],
                'mergedRows' => [['B', 'C'], ['D', 'O'], ['P', 'U'], ['V', 'AF'], ['AG', 'AN']],
                'items' => $crr->nonresidentsCreditItems
            ],
        ];

        foreach($rowsOptions as $option) {
            $rows  = $sheet->getRowIterator();

            $repeatRow = self::getRepeatRow($crr, $rows, $option['row'], $option['skippedRows']);

            if (!empty($repeatRow)) {
                self::getRepeatRowValues($repeatRow, $option['items'], $sheet, $option['mergedRows']);
            }
        }

        return $xls;
    }

    /**
     * Получение ряда из шаблона, который нужно повторять
     * @param $model
     * @param $rows
     * @param $targetRow
     * @param $skippedRows
     * @return null
     */
    protected static function getRepeatRow($model, $rows, $targetRow, $skippedRows)
    {
        $repeatRow = null;

        foreach ($rows as $row) {
            $cells = $row->getCellIterator(); // по ячейкам
            foreach ($cells as $cell) {
                $value = $cell->getValue(); // собираем значения
                if ($value	&& preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $value, $matches)) {
                    // находим переменные
                    foreach ($matches['tag'] as $k => $tag) {
                        if ($tag === $targetRow) {
                            $repeatRow = $row;
                            $cell->setValue(''); // сбрасываем тэг инструкции
                            continue 2;
                        }
                        if (in_array($tag, $skippedRows)) {
                            continue 2;
                        }
                        if (isset($model->$tag)) {
                            $value = str_replace($matches['placeholder'][$k], $model->$tag, $value);
                        }
                    }
                }
                $cell->setValue($value);
            }
        }

        return $repeatRow;
    }

    /**
     * Получение и запись значений повторяющихся строк из xls-шаблона
     */
    protected static function getRepeatRowValues($repeatRow, $items, $sheet, $mergeOptions = null)
    {
        $itemsCount = count($items);

        if ($itemsCount == 0) {
            $sheet->removeRow($repeatRow->getRowIndex());

            return false;
        }

        // дублируем строку с повторяющейся информацией
        $rowIndex = $repeatRow->getRowIndex();
        $endRowIndex = $rowIndex + $itemsCount;

        // Если вставить 0 строк, то исчезают нижележащие строки!
        if ($itemsCount > 1) {
            $sheet->insertNewRowBefore($rowIndex + 1, $itemsCount - 1);
        }

        if ($mergeOptions) {
            for ($i = $rowIndex; $i < $endRowIndex; $i++) {
                foreach($mergeOptions as $value) {
                    $str = $value['0'] . $i . ':' . $value[1] . $i;
                    $sheet->mergeCells($str);
                }
            }
        }

        $sheet->duplicateStyle(
            $sheet->getStyle('A' . $rowIndex),
            'A' . $rowIndex . ':A' . ($rowIndex + $itemsCount - 1)
        );

        // читаем шаблон дублируемой строки и формируем карту шаблонов по колонкам
        $map = [];
        $cells = $repeatRow->getCellIterator();
        foreach ($cells as $cell) {
            if (($value = $cell->getValue())) {
                $map[$cell->getColumn()] = $value;
            }

            // чтобы при пустой выписке не остались тэги в ячейках
            $cell->setValue('');
        }

        // проходимся построчно и наполняем данными
        for ($i = $rowIndex, $k = 0; $i < $endRowIndex; $i++, $k++) {
            foreach ($map as $col => $pattern) {
                $cell = $sheet->getCell($col . $i);
                $cellValue = $pattern;
                $item = $items[$k];

                preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $pattern, $matches);

                // Порядковый номер повторяющейся строки
                $number = $k + 1;

                foreach ($matches['tag'] as $n => $tag) {
                    // Запись разных значений в зависимости от типа
                    if ($tag === 'n') {
                        $value = $number;
                    } elseif (is_array($item)) {
                        $value = $item[$tag] ?? '';
                    } else {
                        $value = $item->$tag;
                    }

                    $cellValue = str_replace('{' . $tag . '}', $value, $cellValue);
                }

                $cell->setValue($cellValue);
            }
        }
    }
    /**
     * Convert statement to 1c document
     *
     * @param StatementType $statement Statement
     * @return PaymentOrderType
     */
	protected static function doc1CByStatement(StatementType $statement)
	{
        \Yii::info("=== DATE CREATED: $statement->dateCreated");

        $dateCreated = new \DateTime($statement->dateCreated);

        $document    = new PaymentOrderType();
        $document->version = '1.2';
        $document->receiver = 'CyberFT';
        $document->setDateFormat('php:d.m.Y');

        $document->number                      = $statement->statementNumber;
        $document->payerCheckingAccount        = $statement->statementAccountNumber;
        $document->organizationCheckingAccount = $statement->statementAccountNumber;
        $document->payerBik                    = $statement->statementAccountBIK;
        $document->documentDateFrom            = $statement->statementPeriodStart;
        $document->documentDateBefore          = $statement->statementPeriodEnd;
        $document->openingBalance              = $statement->openingBalance;
        $document->debitTurnover               = number_format($statement->debitTurnover,2,'.','');
        $document->creditTurnover              = number_format($statement->creditTurnover,2,'.','');
        $document->closingBalance              = number_format($statement->closingBalance,2,'.','');
        $document->dateCreated                 = $dateCreated->format('d.m.Y');
        $document->timeCreated                 = $dateCreated->format('H:i:s');

        return $document;
    }

	/**
	 * @param PaymentOrderType $po
	 * @param string              $xlsView
	 * @return Spreadsheet
	 * @throws \Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public static function paymentOrderToXls(PaymentOrderType $po, $xlsView)
	{
		$xls = IOFactory::load($xlsView);
		$xls->garbageCollect();
		$xls->getProperties()->setCompany('Киберплат');
		$xls->getProperties()->setCreator('Киберплат');
		$xls->getProperties()->setLastModifiedBy('Киберплат');
		$xls->getProperties()->setModified(date('U'));

		$sheet = $xls->getActiveSheet();
		$rows  = $sheet->getRowIterator(); // по строкам

		foreach ($rows as $row) {
			$cells = $row->getCellIterator();
			foreach ($cells as $cell) {
				$value = $cell->getValue();
				if ($value	&& preg_match_all('/(?P<placeholder>\{(?P<tag>.*)\})/U', $value, $matches)) {
					foreach ($matches['tag'] as $k => $tag) {
						if ($po->hasTag($tag)) {
							$value = str_replace($matches['placeholder'][$k], $po->getTag($tag), $value);
						}
					}
				}
				$cell->setValue($value);
			}
		}

		return $xls;
	}

    private static function getOrganization(StatementType $statementTypeModel): ?DictOrganization
    {
        $accountNumber = $statementTypeModel->statementAccountNumber;
        if (!$accountNumber) {
            return null;
        }
        $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
        if (!$account) {
            return null;
        }
        return DictOrganization::findOne(['id' => $account->organizationId]);
	}
}

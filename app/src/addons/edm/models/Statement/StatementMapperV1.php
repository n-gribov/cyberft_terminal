<?php
namespace addons\edm\models\Statement;

use common\helpers\DateHelper;
use common\helpers\Uuid;
use DOMDocument;
use SimpleXMLElement;
use Yii;

class StatementMapperV1
{
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/swiftfin.01';
    const XSD_SCHEME = '@common/resources/xsd/CyberFT_SWIFTFIN_v1.2.xsd';
    public $message;

    public function validateXSD($xml)
    {
        $this->message = null;
		libxml_use_internal_errors(true);

        /** @todo надо все перевести на одну библиотеку вместо этих плясок */
        $domElement = dom_import_simplexml($xml);
        $dom = new DOMDocument();
        $dom->appendChild($dom->importNode($domElement, true));

		if ($dom->schemaValidate(Yii::getAlias(self::XSD_SCHEME))) {

			return true; // Успешная валидация по XSD-схеме
		}

		$errors = libxml_get_errors();
		$messages = [];
		foreach ($errors as $error) {
			$messages[] = "[{$error->level}] {$error->message}";
		}

		$this->message = join(PHP_EOL, $messages);

		return false; // Ошибка валидации
	}

    public function buildXml($model)
    {
        $xml = new SimpleXMLElement('<Statement xmlns="'.self::DEFAULT_NS_URI.'"></Statement>');

        $xml->StatementNumber                 = $model->statementNumber;
        $xml->CreateDate                      = $model->dateCreated;
        $xml->OpeningBalance                  = $model->openingBalance;
        $xml->DebitTurnover                   = $model->debitTurnover;
        $xml->CreditTurnover                  = $model->creditTurnover;
        $xml->ClosingBalance                  = $model->closingBalance;
        $xml->StatementAccount->AccountNumber = $model->statementAccountNumber;
        $xml->StatementAccount->BIK           = $model->statementAccountBIK;
        $xml->StatementPeriod->StartDate      = $model->statementPeriodStart;
        $xml->StatementPeriod->EndDate        = $model->statementPeriodEnd;
        $xml->AddInfo->PrevLastOperationDate  = $model->prevLastOperationDate;
        $xml->AddInfo->Companyname            = $model->companyName;

        foreach ($model->transactions as $data) {
            $transaction                                          = $xml->addChild('Transaction');
            $transaction->Reference                               = $data['Number'];
            $transaction->EntryDate                               = $data['EntryDate'];
            $transaction->ValueDate                               = $data['ValueDate'];
            $transaction->DocDate                                 = $data['DocDate'];
            $transaction->Number                                  = $data['Number'];
            $transaction->Purpose                                 = $data['Purpose'];
            $transaction->Amount                                  = $data['Amount'];
            $transaction->DCMark                                  = $data['DCMark'];
            $transaction->Correspondent->Reference                = $data['CorrReference'];
            $transaction->Correspondent->Name                     = $data['CorrName'];
            $transaction->Correspondent->INN                      = $data['CorrINN'];
            $transaction->Correspondent->KPP                      = $data['CorrKPP'];
            $transaction->CorrespondentAccount->AccountNumber     = $data['CorrAccountNum'];
            $transaction->CorrespondentAccount->BIK               = $data['CorrBIK'];
            $transaction->CorrespondentAccount->BankName          = $data['CorrBankName'];
            $transaction->CorrespondentAccount->BankAccountNumber = $data['CorrBankAccountNum'];
        }

        return dom_import_simplexml($xml);
    }

    public function parseXml(StatementType $model)
    {
        $xmlDom = $model->getXmlDom();

        $model->statementNumber        = (string) $xmlDom->StatementNumber;
        $model->dateCreated            = (string) $xmlDom->CreateDate;
        $model->openingBalance         = (string) $xmlDom->OpeningBalance;
        $model->debitTurnover           = (string) $xmlDom->DebitTurnover;
        $model->creditTurnover          = (string) $xmlDom->CreditTurnover;
        $model->closingBalance         = (string) $xmlDom->ClosingBalance;
        $model->statementAccountNumber = (string) $xmlDom->StatementAccount->AccountNumber;
        $model->statementAccountBIK    = (string) $xmlDom->StatementAccount->BIK;
        $model->statementPeriodStart   = (string) date('d.m.Y', strtotime($xmlDom->StatementPeriod->StartDate));
        $model->statementPeriodEnd     = (string) date('d.m.Y', strtotime($xmlDom->StatementPeriod->EndDate));
        $model->prevLastOperationDate  = (string) $xmlDom->AddInfo->PrevLastOperationDate;
        $model->companyName            = (string) $xmlDom->AddInfo->Companyname;

        $model->transactions = $this->getTransactions($model);
    }

    public function getTransactions($model)
    {
        $xml = $model->getXmlDom();
        $outList = [];
        $transactionList = $xml->Transaction;

        foreach($transactionList as $transaction) {

            $uniqId = (string) $transaction->Reference;
            if (!$uniqId) {
                $uniqId = Uuid::generate();
            }

            $out = [
                'UniqId'             => $uniqId,
                'Reference'          => $uniqId,
                'EntryDate'          => (string) $transaction->EntryDate,
                'ValueDate'          => DateHelper::formatDate((string) $transaction->ValueDate),
                'DocDate'            => (string) $transaction->DocDate,
                'Number'             => (string) $transaction->Number,
                'Purpose'            => (string) $transaction->Purpose,
                'Amount'             => (string) $transaction->Amount,
                'DCMark'             => (string) $transaction->DCMark,
                'CorrReference'      => (string) $transaction->Correspondent->Reference,
                'PaymentKind'        => '',
                'PayType'            => '',
                'PayerCurrency'      => '',
                'Priority'           => '',
                'DepInfStatus'       => '',
                'DepInfKBK'          => '',
                'DepInfOKTMO'        => '',
                'DepInfPayReason'    => '',
                'DepInfTaxPeriod'    => '',
                'DepInfTaxDocNumber' => '',
                'DepInfTaxDocDate'   => '',
                'DepInfTaxType'      => '',
                'ReceiptDate'        => '',
            ];

            if ($transaction->DCMark == 'D') {
                $out['Debit']               = (float) $transaction->Amount;
                $out['Credit']              = 0;

                $out['PayerName']           = '';
                $out['PayerINN']            = '';
                $out['PayerKPP']            = '';
                $out['PayerAccountNum']     = $model->statementAccountNumber;
                $out['PayerBIK']            = $model->statementAccountBIK;
                $out['PayerBankName']       = '';
                $out['PayerBankAccountNum'] = $model->statementAccountNumber;

                $out['PayeeName']           = (string) $transaction->Correspondent->Name;
                $out['PayeeINN']            = (string) $transaction->Correspondent->INN;
                $out['PayeeKPP']            = (string) $transaction->Correspondent->KPP;
                $out['PayeeAccountNum']     = (string) $transaction->CorrespondentAccount->AccountNumber;
                $out['PayeeBIK']            = (string) $transaction->CorrespondentAccount->BIK;
                $out['PayeeBankName']       = (string) $transaction->CorrespondentAccount->BankName;
                $out['PayeeBankAccountNum'] = (string) $transaction->CorrespondentAccount->BankAccountNumber;
            } else {
                $out['Debit']               = 0;
                $out['Credit']              = (float) $transaction->Amount;

                $out['PayerName']           = (string) $transaction->Correspondent->Name;
                $out['PayerINN']            = (string) $transaction->Correspondent->INN;
                $out['PayerKPP']            = (string) $transaction->Correspondent->KPP;
                $out['PayerAccountNum']     = (string) $transaction->CorrespondentAccount->AccountNumber;
                $out['PayerBIK']            = (string) $transaction->CorrespondentAccount->BIK;
                $out['PayerBankName']       = (string) $transaction->CorrespondentAccount->BankName;
                $out['PayerBankAccountNum'] = (string) $transaction->CorrespondentAccount->BankAccountNumber;

                $out['PayeeName']           = '';
                $out['PayeeINN']            = '';
                $out['PayeeKPP']            = '';
                $out['PayeeAccountNum']     = $model->statementAccountNumber;
                $out['PayeeBIK']            = $model->statementAccountBIK;
                $out['PayeeBankName']       = '';
                $out['PayeeBankAccountNum'] = $model->statementAccountNumber;
            }

            $outList[] = $out;
        }

        return $outList;
    }


    public function loadFromDataObject($obj)
    {
        return false;
    }
}

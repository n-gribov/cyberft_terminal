<?php
namespace addons\edm\models\Statement;

use common\helpers\Uuid;
use DOMDocument;
use SimpleXMLElement;
use Yii;

class StatementMapperV2
{
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/edm.02';
    const XSD_SCHEME = '@addons/edm/resources/xsd/CyberFT_EDM_latest.xsd';

    public $message;

    private $_accountNumber;

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
        $xml = new SimpleXMLElement('<Statement xmlns="' . self::DEFAULT_NS_URI . '"></Statement>');

        $xml->StatementNumber                    = $model->statementNumber;
        $xml->CreateDate                         = $model->dateCreated;
        $xml->OpeningBalance                     = $model->openingBalance;
        $xml->DebitTurnover                      = $model->debitTurnover;
        $xml->CreditTurnover                     = $model->creditTurnover;
        $xml->ClosingBalance                     = $model->closingBalance;
        $xml->StatementAccount->AccountNumber    = $model->statementAccountNumber;
        $xml->StatementAccount->BIK              = $model->statementAccountBIK;
        $xml->StatementPeriod->StartDate         = $model->statementPeriodStart;
        $xml->StatementPeriod->EndDate           = $model->statementPeriodEnd;
        $xml->AddInfo->PrevLastOperationDate     = $model->prevLastOperationDate;
        $xml->AddInfo->Companyname               = $model->companyName;
        $xml->AddInfo->AccountType               = $model->accountType;
        $xml->AddInfo->ReservedAmount            = $model->reservedAmount;
        $xml->AddInfo->ConsolidatedAccountNumber = $model->consolidatedAccountNumber;

        return dom_import_simplexml($xml);
    }

    public function parseXml(StatementType $model)
    {
        $xmlDom = $model->getXmlDom();

        //Некоторые теги в разных XML находятся внутри разных тегов, для них нужен XPath
        $xmlDom->registerXPathNamespace('ns', self::DEFAULT_NS_URI);

        $model->statementNumber           = (string) $xmlDom->StatementNumber;
        $model->dateCreated               = (string) $xmlDom->CreateDate;
        $model->openingBalance            = (string) $xmlDom->OpeningBalance;
        $model->debitTurnover             = (string) $xmlDom->DebitTurnover;
        $model->creditTurnover            = (string) $xmlDom->CreditTurnover;
        $model->closingBalance            = (string) $xmlDom->ClosingBalance;
        $model->statementAccountNumber    = (string) $xmlDom->StatementAccount->AccountNumber;
        $model->statementAccountBIK       = (string) $xmlDom->StatementAccount->BIK;
        $model->currency                  = (string) $xmlDom->StatementAccount->Currency;
        $model->statementPeriodStart      = date('d.m.Y', strtotime($xmlDom->StatementPeriod->StartDate));
        $model->statementPeriodEnd        = date('d.m.Y', strtotime($xmlDom->StatementPeriod->EndDate));
        $model->prevLastOperationDate     = (string) $xmlDom->AddInfo->PrevLastOperationDate;
        $model->companyName               = (string) $xmlDom->AddInfo->Companyname;
        $model->accountType               = (string) $xmlDom->AddInfo->AccountType;
        $model->reservedAmount            = (string) $xmlDom->AddInfo->ReservedAmount;
        $model->consolidatedAccountNumber = (string) $xmlDom->AddInfo->ConsolidatedAccountNumber;

        if (!empty($xmlDom->xpath('//ns:SignatureCardExpirationDate'))) {
            $model->signatureCardExpirationDate = date('d.m.Y',
                strtotime($xmlDom->xpath('//ns:SignatureCardExpirationDate')[0]));
        } else {
            $model->signatureCardExpirationDate = '';
        }

        $this->_accountNumber = $model->statementAccountNumber;
        $model->transactions = $this->getTransactions($model);
        $model->accountRestrictions = $this->getAccountRestrictions($xmlDom);
    }

    private function getAccountNumber(& $xml)
    {
        $account = $xml->AccountNumber;
        if (empty($account)) {
            $account = $xml->IBAN;
        }

        return (string) $account;
    }

    private function getBIK(& $xml)
    {
        $bik = $xml->BIK;
        if (empty($bik)) {
                $bik = $xml->BICFI;
        }

        return (string) $bik;
    }

    public function getTransactions($model)
    {
        $xml = $model->getXmlDom();
        $outList = [];
        $payDocList = $xml->PayDoc;

        foreach($payDocList as $paydoc) {
            $transaction = $paydoc->Transaction;
            $payerAccountNum = $this->getAccountNumber($transaction->PayerAccount);
            $uniqId = (string) $transaction->TrnExtId;
            if (!$uniqId) {
                $uniqId = Uuid::generate();
            }

            $out = [
                'UniqId'             => $uniqId,
                'Reference'          => $uniqId,
                'EntryDate'          => (string) $transaction->EntryDate,
                'ValueDate'          => (string) $transaction->ValueDate,
                'Number'             => (string) $transaction->DocNumber,
                'DocDate'            => (string) $transaction->DocDate,
                'PaymentKind'        => (string) $transaction->PaymentKind,
                'Purpose'            => (string) $transaction->Purpose,
                'PayeeAmount'        => (string) $transaction->PayeeAmount,
                'PayerAmount'        => (string) $transaction->PayerAmount,
                'Priority'           => (string) $transaction->Priority,
                'PayType'            => (string) $transaction->PayType,
                'PayCode'            => (string)$transaction->PayCode,

                'PayerName'           => (string) $transaction->Payer->Name,
                'PayerAccountNum'     => $payerAccountNum,
                'PayerBIK'            => $this->getBIK($transaction->PayerAccount),
                'PayerBankName'       => (string) $transaction->PayerAccount->BankName,
                'PayerBankAccountNum' => (string) $transaction->PayerAccount->BankAccountNumber,
                'PayerINN'            => (string) $transaction->Payer->INN,
                'PayerKPP'            => (string) $transaction->Payer->KPP,
                'PayerCurrency'       => (string) $transaction->PayerAccount->Currency,

                'PayeeName'           => (string) $transaction->Payee->Name,
                'PayeeAccountNum'     => $this->getAccountNumber($transaction->PayeeAccount),
                'PayeeBIK'            => $this->getBIK($transaction->PayeeAccount),
                'PayeeBankName'       => (string) $transaction->PayeeAccount->BankName,
                'PayeeBankAccountNum' => (string) $transaction->PayeeAccount->BankAccountNumber,
                'PayeeINN'            => (string) $transaction->Payee->INN,
                'PayeeKPP'            => (string) $transaction->Payee->KPP,
                'PayeeCurrency'       => (string) $transaction->PayeeAccount->Currency,

                'DepInfStatus'        => (string) $transaction->DepartmentalInfo->Status,
                'DepInfKBK'           => (string) $transaction->DepartmentalInfo->KBK,
                'DepInfOKTMO'         => (string) $transaction->DepartmentalInfo->OKTMO,
                'DepInfPayReason'     => (string) $transaction->DepartmentalInfo->PayReason,
                'DepInfTaxPeriod'     => (string) $transaction->DepartmentalInfo->TaxPeriod,
                'DepInfTaxDocNumber'  => (string) $transaction->DepartmentalInfo->TaxDocNumber,
                'DepInfTaxDocDate'    => (string) $transaction->DepartmentalInfo->TaxDocDate,
                'DepInfTaxType'       => (string) $transaction->DepartmentalInfo->TaxType,
                'ReceiptDate'         => '',
            ];

            if (isset($transaction->AddInfo)) {
                $out['AcceptanceEndDate'] = (string) $transaction->AddInfo->PaymentRequestParams->AcceptanceEndDate;
                $out['PaymentCondition1'] = (string) $transaction->AddInfo->PaymentRequestParams->PaymentTerm;
                $out['AcceptPeriod'] = (string) $transaction->AddInfo->PaymentRequestParams->AcceptancePeriod;
                $out['SWIFTMessage'] = base64_decode((string)$transaction->AddInfo->SWIFT);
            }

            if (isset($transaction->OKUD)) {
                $out['OKUD'] = (string) $transaction->OKUD;
            }

            if ($payerAccountNum == $this->_accountNumber) {
                $amount = $out['PayerAmount'];
                $out['Credit'] = 0;
                $out['Debit'] = $amount;
            } else {
                $amount = $out['PayeeAmount'];
                $out['Credit'] = $amount;
                $out['Debit'] = 0;
            }

            $out['Amount'] = $amount;

            $outList[] = $out;

        }

        return $outList;
    }

    private function getAccountRestrictions($xml)
    {
        if (count($xml->xpath('//ns:AccountRestrictions')) === 0) {
            return null;
        }

        return array_map(
            function ($node) {
                return [
                    /** Ammount это не опечатка, см. SBBOL/resources/schema/response/response.xsd */
                    'Amount'    => (string) $node->Ammount,
                    'Descr'     => (string) $node->Description,
                    'RestrType' => (string) $node->RestrictionType,
                    'RestrDate' => (string) $node->RestrictionDate
                ];
            },

            $nodes = $xml->xpath('//ns:AccountRestrictions/ns:Restriction')
        );
    }

    public function loadFromDataObject($obj)
    {
        // not supported
        return false;
    }

}

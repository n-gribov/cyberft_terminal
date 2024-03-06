<?php

namespace addons\edm\models\Sbbol2Statement;

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Statement\StatementType;
use common\models\sbbol2\ObjectSerializer;
use common\models\sbbol2\StatementSummary;
use common\models\sbbol2\StatementTransactions;
use InvalidArgumentException;
use SimpleXMLElement;
use Yii;

class StatementMapperSbbol2
{
    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/sbbol2';
    const XSD_SCHEME = null;

    private $_sbbol2Summary;
    private $_sbbol2Transactions;

    public $message;

    public function validateXSD($xml)
    {
        // XSD для валидации пока нет
        return true;
	}

    public function buildXml($model)
    {
        $xml = new SimpleXMLElement('<Statement xmlns="' . static::DEFAULT_NS_URI . '"></Statement>');
        $xml->StatementPeriod->StartDate = $model->statementPeriodStart;
        $xml->StatementPeriod->EndDate = $model->statementPeriodEnd;
        $xml->Summary = (string) $this->_sbbol2Summary;
        $xml->Transactions = (string) $this->_sbbol2Transactions;

        $dom = dom_import_simplexml($xml);

        return $dom;
    }

    public function loadFromDataObject($model, $obj)
    {
        if ($obj instanceof StatementSummary) {
            $this->_sbbol2Summary = $obj;
            $this->loadSbbol2Summary($model, $obj);
        } else if ($obj instanceof StatementTransactions) {
            $this->_sbbol2Transactions = $obj;
            $model->transactions = $this->getTransactions($model);
            $this->setModelAttributesFromFirstTransaction($model, $obj);
        } else {
            throw new InvalidArgumentException('Object class is not recognized');
        }
    }

    public function loadSbbol2Summary(StatementType $model, StatementSummary $summary)
    {
        $modelAttributes = [
            'dateCreated' => date('c', $summary->getComposedDateTime()->getTimestamp()),
            'prevLastOperationDate' => date('c', $summary->getLastMovementDate()->getTimeStamp()),
            'closingBalance' => $summary->getClosingBalance()->getAmount(),
            'creditTurnover' => $summary->getCreditTurnover()->getAmount(),
            'debitTurnover'  => $summary->getDebitTurnover()->getAmount(),
            'openingBalance' => $summary->getOpeningBalance()->getAmount(),
            'currency'       => $summary->getClosingBalance()->getCurrencyName()
        ];

        $model->setAttributes($modelAttributes);

//        $model->statementNumber           = $xml->StatementNumber;
//        $model->statementAccountNumber    = (string) $xml->StatementAccount->AccountNumber;
//        $model->statementAccountBIK       = (string) $xml->StatementAccount->BIK;
//        $model->currency                  = (string) $xml->StatementAccount->Currency;
//        $model->companyName               = (string) $xml->AddInfo->Companyname;
//        $model->accountType               = (string) $xml->AddInfo->AccountType;
//        $model->reservedAmount            = (string) $xml->AddInfo->ReservedAmount;
//        $model->consolidatedAccountNumber = (string) $xml->AddInfo->ConsolidatedAccountNumber;
//
//        if (!empty($xml->xpath('//ns:SignatureCardExpirationDate'))) {
//            $model->signatureCardExpirationDate = date('d.m.Y',
//                strtotime($xml->xpath('//ns:SignatureCardExpirationDate')[0]));
//        } else {
//            $model->signatureCardExpirationDate = '';
//        }
//
//        $this->_accountNumber = $model->statementAccountNumber;
//        $model->transactions = $this->getTransactions($xml);
//        $model->accountRestrictions = $this->getAccountRestrictions($xml);
    }

    // Parse xml from CyberXML envelope
    public function parseXml(StatementType $model)
    {
        $xmlDom = $model->getXmlDom();

        $xmlDom->registerXPathNamespace('ns', self::DEFAULT_NS_URI);

        $model->statementPeriodStart = date('d.m.Y', strtotime($xmlDom->StatementPeriod->StartDate));
        $model->statementPeriodEnd = date('d.m.Y', strtotime($xmlDom->StatementPeriod->EndDate));

        $sbbol2Summary = ObjectSerializer::deserialize(
            json_decode((string) $xmlDom->Summary),
            StatementSummary::class
        );

        $this->loadFromDataObject($model, $sbbol2Summary);

        $sbbol2Transactions = ObjectSerializer::deserialize(
            json_decode((string) $xmlDom->Transactions),
            StatementTransactions::class
        );

        $this->loadFromDataObject($model, $sbbol2Transactions);

        $model->accountRestrictions = $this->getAccountRestrictions($xmlDom);
    }

    private function setModelAttributesFromFirstTransaction($model)
    {
        $transactions = $this->_sbbol2Transactions->getTransactions();
        $trn = $transactions[0];
        $rurTransfer = $trn->getRurTransfer();
        if ($rurTransfer) {
            $direction = $trn->getDirection();
            if ($direction == 'CREDIT') {
                $model->statementAccountNumber = $rurTransfer->getPayeeAccount();
                $model->statementAccountBIK = $rurTransfer->getPayeeBankBic();
            } else {
                $model->statementAccountNumber = $rurTransfer->getPayerAccount();
                $model->statementAccountBIK = $rurTransfer->getPayerBankBic();
            }
            $customerAccount = EdmPayerAccount::findOne(['number' => $model->statementAccountNumber]);
            if ($customerAccount) {
                $org = DictOrganization::findOne($customerAccount->organizationId);
                $model->companyName = $org->name;
                $model->statementAccountName = $customerAccount->payerName ?: $org->name;
            } else {
                Yii::info(__METHOD__ . ': error getting org name for account ' . $model->statementAccountNumber);
            }
        } else {
            $swiftTransfer = $trn->getSwiftTransfer();
            if ($swiftTransfer) {
                if ($trn->getDirection() == 'DEBIT') {
                    $model->statementAccountNumber = $swiftTransfer->getOrderingCustomerAccount();
                } else {
                    $model->statementAccountNumber = $swiftTransfer->getbeneficiaryCustomerAccount();
                }
            }
        }
    }

    public function getTransactions($model)
    {
        $transactions = $this->_sbbol2Transactions;
        $outList = [];

        foreach($transactions->getTransactions() as $trn) {
            $trnAmount = $trn->getAmountRub();
            $amount = $trnAmount->getAmount();

            $out = [
                'Amount'              => $amount,
                'UniqId'              => $trn->getUuid(),
                'Reference'           => null,
                'Number'              => $trn->getNumber(),
                'DocDate'             => date('c', $trn->getDocumentDate()->getTimestamp()),
                'Purpose'             => $trn->getPaymentPurpose(),
                'PayeeAmount'         => 0,
                'PayerAmount'         => 0,
                'Priority'            => $trn->getPriority(),
                'PayType'             => $trn->getOperationCode(),
            ];

            $rt = $trn->getRurTransfer();

            if ($rt) { // рублевая операция
                $deptInfo = $rt->getDepartmentalInfo();
                $outRub = [
                    'EntryDate'           => date('c', $rt->getReceiptDate()->getTimeStamp()),
                    'ValueDate'           => date('c', $rt->getValueDate()->getTimeStamp()),
                    'PaymentKind'         => $rt->getDeliveryKind(),
                    'PayerName'           => $rt->getPayerName(),
                    'PayerAccountNum'     => $rt->getPayerAccount(),
                    'PayerBIK'            => $rt->getPayerBankBic(),
                    'PayerBankName'       => $rt->getPayerBankName(),
                    'PayerBankAccountNum' => $rt->getPayerBankCorrAccount(),
                    'PayerINN'            => $rt->getPayerInn(),
                    'PayerKPP'            => $rt->getPayerKpp(),
                    'PayerCurrency'       => $trnAmount->getCurrencyName(),

                    'PayeeName'           => $rt->getPayeeName(),
                    'PayeeAccountNum'     => $rt->getPayeeAccount(),
                    'PayeeBIK'            => $rt->getPayeeBankBic(),
                    'PayeeBankName'       => $rt->getPayeeBankName(),
                    'PayeeBankAccountNum' => $rt->getPayeeBankCorrAccount(),
                    'PayeeINN'            => $rt->getPayeeInn(),
                    'PayeeKPP'            => $rt->getPayeeKpp(),
                    'PayeeCurrency'       => $trnAmount->getCurrencyName(),

                    'PayCode'             => $deptInfo->getUip(),
                    'DepInfStatus'        => $deptInfo->getDrawerStatus101(),
                    'DepInfKBK'           => $deptInfo->getKbk(),
                    'DepInfOKTMO'         => $deptInfo->getOktmo(),
                    'DepInfPayReason'     => $deptInfo->getReasonCode106(),
                    'DepInfTaxPeriod'     => $deptInfo->getTaxPeriod107(),
                    'DepInfTaxDocNumber'  => $deptInfo->getDocNumber108(),
                    'DepInfTaxDocDate'    => $deptInfo->getDocDate109(),
                    'DepInfTaxType'       => $deptInfo->getPaymentKind110(),
                ];

                $out = array_merge($out, $outRub);
            }

            if ($trn->getDirection() == 'DEBIT') {
                $out['Credit'] = 0;
                $out['Debit'] = $amount;
            } else {
                $out['Credit'] = $amount;
                $out['Debit'] = 0;
            }

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

}

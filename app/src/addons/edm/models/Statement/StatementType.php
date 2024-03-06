<?php
namespace addons\edm\models\Statement;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\Sbbol2Statement\StatementMapperSbbol2;
use common\base\BaseType;
use common\helpers\StringHelper;
use SimpleXMLElement;
use yii\helpers\ArrayHelper;

class StatementType extends BaseType
{
    const TYPE = 'Statement';
    const SBBOL2TYPE = 'Sbbol2Statement';

    const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/swiftfin.01';
    const NS_URI_V1 = 'http://cyberft.ru/xsd/swiftfin.01';
    //const NS_URI_V2 = 'http://cyberft.ru/xsd/edm.02';
    const NS_URI_SBBOL2 = 'http://cyberft.ru/xsd/sbbol2';

    protected $_mapper;
    protected $_xmlDom;

    public $sender;
    public $recipient;
    public $statementNumber;
    public $statementAccountNumber;
    public $statementAccountBIK;
    public $statementAccountName;
    public $currency;
    public $transactions   = [];
    public $openingBalance = 0;
    public $debitTurnover  = 0;
    public $creditTurnover = 0;
    public $closingBalance = 0;
    public $organizationCheckingAccount;
    public $payerBik;
    public $statementPeriodStart;
    public $statementPeriodEnd;
    public $dateCreated;
    public $prevLastOperationDate;
    public $companyName;
    public $accountType = 'CardAccount';
    public $reservedAmount;
    public $consolidatedAccountNumber;
    public $accountRestrictions;
    public $signatureCardExpirationDate;

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [
                [array_values($this->attributes()), 'safe'],
            ]);
    }

    public function loadFromDataObject($obj)
    {
        $this->getMapper()->loadFromDataObject($this, $obj);
    }

    public function loadFromString($xml, $isFile = false, $encoding = null)
    {
        if ($isFile) {
            $xml = file_get_contents($xml);
        }

        $this->_xmlDom = new SimpleXMLElement($xml);
        $this->_mapper = null; // null old mapper because we loaded new xml data
        $this->getMapper()->parseXml($this);

        return $this;
    }

    public function getModelDataAsString($removeXmlDeclaration = true)
    {
        if (!$this->_xmlDom) {
            $xml = $this->buildXml();
            $body = StringHelper::fixBOM($xml->ownerDocument->saveXML());
        } else {
            $body = StringHelper::fixBOM($this->_xmlDom->asXML());
        }

        if ($removeXmlDeclaration) {
            return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
        }

        return StringHelper::fixXmlHeader($body);
    }

    public function validateXSD()
    {
        return $this->getMapper()->validateXSD($this->_xmlDom);
    }

    public function getXmlDom()
    {
        return $this->_xmlDom;
    }

    public function buildXml()
    {
        return $this->getMapper()->buildXml($this);
    }

    /**
     * Get transactions
     *
     * @return array
     */
    public function getTransactions()
    {
        if (!empty($this->transactions)) {
            return $this->transactions;
        }

        return !empty($this->_mapper) ? $this->_mapper->getTransactions($this) : [];
    }

    public function getTransactionIdByUniqId($uniqId)
    {
        $transactions = $this->getTransactions();

        foreach ($transactions as $id => $transaction) {
            if ($transaction['UniqId'] == $uniqId) {
                return $id;
            }
        }

        return null;
    }

    public function getPaymentOrder($num)
    {
        $transactions = $this->getTransactions();

        if (is_array($transactions) && count($transactions) > $num) {
            $transaction = $transactions[$num];


            $paymentOrderTypeParams = [
                /** @todo unmapped fields from PaymentOrder */
//    public $documentSendDate;
//    public $openingBalance = 0;
//    public $debitTurnover = 0;
//    public $creditTurnover = 0;
//    public $closingBalance = 0;
//    public $documentDateFrom;
//    public $documentDateBefore;
//    public $organizationCheckingAccount;
//    public $acceptPeriod;
//    public $paymentOrderPaymentPurpose;
//    public $payerDateEnrollment;
//    public $beneficiaryDateDebiting;
//    public $code;

                //'' => $transaction['Reference'],
                //'' => $transaction['OKUD'],
                'dateProcessing' => $transaction['EntryDate'] ? $transaction['EntryDate'] : $transaction['ValueDate'],
                'dateDue' => $transaction['ValueDate'],

                'number' => $transaction['Number'],

                'date' => $transaction['DocDate'],
                'paymentType' => $transaction['PaymentKind'],
                'paymentPurpose' => $transaction['Purpose'],
                'sum' => $transaction['Amount'],
                //'' => $transaction['PayeeAmount'],
                //'' => $transaction['PayerAmount'],
                'priority' => $transaction['Priority'],
                'payType' => $transaction['PayType'],
                'code' => $transaction['PayCode'] ?? null,

                'payerName' => $transaction['PayerName'],
                'payerCheckingAccount' => $transaction['PayerAccountNum'],
                'payerBik' => $transaction['PayerBIK'],
                'payerBank1' => $transaction['PayerBankName'],
                'payerCorrespondentAccount' => $transaction['PayerBankAccountNum'],
                'payerInn' => $transaction['PayerINN'],
                'payerKpp' => $transaction['PayerKPP'],
                'currency' => $transaction['PayerCurrency'],

                'beneficiaryName' => $transaction['PayeeName'],
                'beneficiaryCheckingAccount' => $transaction['PayeeAccountNum'],
                'beneficiaryBik' => $transaction['PayeeBIK'],
                'beneficiaryBank1' => $transaction['PayeeBankName'],
                'beneficiaryCorrespondentAccount' => $transaction['PayeeBankAccountNum'],
                'beneficiaryInn' => $transaction['PayeeINN'],
                'beneficiaryKpp' => $transaction['PayeeKPP'],
                //'' => $transaction['PayeeCurrency'],

                'senderStatus' => $transaction['DepInfStatus'],
                'indicatorKbk' => $transaction['DepInfKBK'],
                'okato' => $transaction['DepInfOKTMO'],
                'indicatorReason' => $transaction['DepInfPayReason'],
                'indicatorPeriod' => $transaction['DepInfTaxPeriod'],
                'indicatorNumber' => $transaction['DepInfTaxDocNumber'],
                'indicatorDate' => $transaction['DepInfTaxDocDate'],
                'indicatorType' => $transaction['DepInfTaxType'],
                'paymentOrderPaymentPurpose' => $transaction['IncomeTypeCode'] ?? null,
            ];

            // Поля требуемые для платежного поручения
            $paymentOrderTypeParams['okud'] = isset($transaction['OKUD']) ? $transaction['OKUD'] : null;
            $paymentOrderTypeParams['acceptanceEndDate'] = isset($transaction['AcceptanceEndDate']) ? $transaction['AcceptanceEndDate'] : null;
            $paymentOrderTypeParams['paymentCondition1'] = isset($transaction['PaymentCondition1']) ? $transaction['PaymentCondition1'] : null;
            $paymentOrderTypeParams['acceptPeriod'] = isset($transaction['AcceptPeriod']) ? $transaction['AcceptPeriod'] : null;
            $paymentOrderTypeParams['swiftMessage'] = isset($transaction['SWIFTMessage']) ? $transaction['SWIFTMessage'] : null;

            return new PaymentOrderType($paymentOrderTypeParams);
        }

        return null;
    }

	public function getSearchFields()
	{
		return [
			'body' => $this->getModelDataAsString()
		];
	}

    public function isTodaysStatement(): bool
    {
        $today = (new \DateTime())->format('d.m.Y');
        return $this->statementPeriodStart === $today
            && $this->statementPeriodEnd === $today;
    }

    protected function getMapperByNs($xml)
    {
        $namespaces = $xml->getNamespaces();

        if (is_array($namespaces) && count($namespaces)) {
            $ns = current($namespaces);
        }

        if ($ns == self::NS_URI_V1) {
            return new StatementMapperV1();
        } else if ($ns == self::NS_URI_SBBOL2) {
            return new StatementMapperSbbol2();
        }

        return $this->getDefaultMapper();
    }

    protected function getDefaultMapper()
    {
        return new StatementMapperV2();
    }

    protected function getMapper()
    {
        if (empty($this->_mapper)) {
            if ($this->_xmlDom) {
                $this->_mapper = $this->getMapperByNs($this->_xmlDom);
            } else {
                $this->_mapper = $this->getDefaultMapper();
            }
        }

        return $this->_mapper;
    }

}

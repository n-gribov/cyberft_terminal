<?php

namespace addons\edm\models\Sbbol2PayDocRu;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\base\BaseType;
use common\helpers\Uuid;
use common\models\sbbol2\DepartmentalInfo;
use common\models\sbbol2\ObjectSerializer;
use common\models\sbbol2\Payment;
use SimpleXMLElement;
use function GuzzleHttp\json_decode;

class Sbbol2PayDocRuType extends BaseType
{
    const TYPE = 'Sbbol2PayDocRu';
    
    /** @var Payment */
    public $document;
    
    public $sender;
    public $recipient;
    
    /** @var SimpleXMLElement */
    protected $xmlDom;

    public function rules()
    {
        return [
            [['document'], 'required'],
        ];
    }
    
    public function getType()
    {
        return static::TYPE;
    }

    public static function createFromPaymentOrder(PaymentOrderType $paymentOrder, $customerId, $senderName): self
    {
        if (empty($paymentOrder->indicatorKbk) &&
            empty($paymentOrder->okato) &&
            empty($paymentOrder->indicatorReason) &&
            empty($paymentOrder->indicatorPeriod) &&
            empty($paymentOrder->indicatorNumber) &&
            empty($paymentOrder->indicatorDate) &&
            empty($paymentOrder->indicatorType) &&
            empty($paymentOrder->code)
        ) {
            $departmentalInfo = null;
        } else {
            $departmentalInfo = (new DepartmentalInfo())
                ->setKbk($paymentOrder->indicatorKbk)
                ->setOktmo($paymentOrder->okato)
                ->setDrawerStatus101($paymentOrder->senderStatus)
                ->setReasonCode106($paymentOrder->indicatorReason)
                ->setTaxPeriod107($paymentOrder->indicatorPeriod)
                ->setDocNumber108($paymentOrder->indicatorNumber)
                ->setDocDate109($paymentOrder->indicatorDate)
                ->setUip($paymentOrder->code);
        }

        if (!$paymentOrder->paymentPurpose){
            $paymentPurpose = $paymentOrder->paymentPurpose1.' '
                .$paymentOrder->paymentPurpose2.' '
                .$paymentOrder->paymentPurpose3;
        } else {
            $paymentPurpose = $paymentOrder->paymentPurpose;
        }
        
        $paymentDocument = (new Payment())
            ->setNumber($paymentOrder->number)
            ->setDate($paymentOrder->dateCreated)
            ->setAmount($paymentOrder->sum)
            ->setPayerName($paymentOrder->payerName)
            ->setPayerInn($paymentOrder->payerInn)
            ->setPayerKpp($paymentOrder->payerKpp)
            ->setPayerAccount($paymentOrder->payerCheckingAccount)
            ->setPayerBankCorrAccount($paymentOrder->payerCorrespondentAccount)
            ->setPayerBankBic($paymentOrder->payerBik)
            ->setPayeeName($paymentOrder->beneficiaryName)
            ->setPayeeBankBic($paymentOrder->beneficiaryBik)
            ->setPayeeAccount($paymentOrder->beneficiaryCheckingAccount)
            ->setPayeeBankCorrAccount($paymentOrder->beneficiaryCorrespondentAccount)
            ->setPayeeInn($paymentOrder->beneficiaryInn)
            ->setPayeeKpp($paymentOrder->beneficiaryKpp)
            ->setOperationCode($paymentOrder->payType)
            ->setPriority($paymentOrder->priority)
            ->setDepartmentalInfo($departmentalInfo)
            ->setPurpose($paymentPurpose)
            ->setExternalId($paymentOrder->documentExternalId ?: (string) Uuid::generate(false));
        
        return new self (['document' => $paymentDocument]);
    }
    
    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }
    
    public function buildXml()
    {
        $xml = new SimpleXMLElement('<Sbbol2PayDocRu xmlns="http://cyberft.ru/xsd/sbbol02"></Sbbol2PayDocRu>');
        $xml->Document = (string) $this->document;
        
        return $xml;
    }
    
    public function getmodelDataAsString()
    {
        // Сформировать XML
        $xml = $this->buildXml();
                
        return $xml->asXML();
    }
    
    public function loadFromString($string, $isFile = false, $encoding = null)
    {        
        $xml = new \SimpleXMLElement($string);        
        
        $this->document = ObjectSerializer::deserialize(
            json_decode((string) $xml->Document),
            Payment::class
        );

        return $this;
    }
    
    public function getSignaturesList()
    {
        return [];
    }
    
}

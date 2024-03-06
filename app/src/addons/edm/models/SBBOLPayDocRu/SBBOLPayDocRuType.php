<?php

namespace addons\edm\models\SBBOLPayDocRu;

use addons\edm\models\BaseSBBOLDocument\BaseSBBOLRequestDocumentType;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\helpers\Uuid;
use common\models\sbbolxml\request\AccDocType;
use common\models\sbbolxml\request\BankType;
use common\models\sbbolxml\request\BudgetDepartmentalInfoType;
use common\models\sbbolxml\request\ContragentType;
use common\models\sbbolxml\request\PayDocRuClientType;
use common\models\sbbolxml\request\PayDocRuType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\SBBOLTransportConfig;

class SBBOLPayDocRuType extends BaseSBBOLRequestDocumentType
{
    const TYPE = 'SBBOLPayDocRu';

    public static function createFromPaymentOrder(PaymentOrderType $paymentOrder, $customerId, $senderName): self
    {
        $valueOrNull = function ($value) {
            return $value === '' ? null : $value;
        };

        $accDoc = (new AccDocType())
            ->setPurpose($valueOrNull($paymentOrder->getPaymentPurpose()))
            ->setAccDocNo($paymentOrder->number)
            ->setDocSum($paymentOrder->sum)
            ->setTransKind($paymentOrder->payType)
            ->setPriority($paymentOrder->priority)
            ->setPaytKind($paymentOrder->paymentType ?: '0');

        $documentDate = null;
        if ($paymentOrder->dateCreated) {
            $documentDate = new \DateTime("{$paymentOrder->dateCreated} {$paymentOrder->timeCreated}");
        }
        if ($documentDate) {
            $accDoc->setDocDate($documentDate);
        }

        $payerBank = (new BankType())
            ->setName($paymentOrder->payerBank1)
            ->setBankCity($paymentOrder->payerBank2)
            ->setBic($paymentOrder->payerBik)
            ->setCorrespAcc($paymentOrder->payerCorrespondentAccount);

        $payer = (new PayDocRuClientType())
            ->setName($paymentOrder->payerName1)
            ->setInn($paymentOrder->payerInn)
            ->setKpp($paymentOrder->payerKpp)
            ->setPersonalAcc($paymentOrder->payerCheckingAccount)
            ->setBank($payerBank);

        $payeeBank = (new BankType())
            ->setName($paymentOrder->beneficiaryBank1)
            ->setBankCity($paymentOrder->beneficiaryBank2)
            ->setBic($paymentOrder->beneficiaryBik)
            ->setCorrespAcc($paymentOrder->beneficiaryCorrespondentAccount);

        $payee = (new ContragentType())
            ->setName($paymentOrder->beneficiaryName1)
            ->setInn($paymentOrder->beneficiaryInn)
            ->setKpp($paymentOrder->beneficiaryKpp)
            ->setPersonalAcc($paymentOrder->beneficiaryCheckingAccount)
            ->setBank($payeeBank);

        $departmentalInfo = (new BudgetDepartmentalInfoType())
            ->setDrawerStatus($valueOrNull($paymentOrder->senderStatus))
            ->setCbc($valueOrNull($paymentOrder->indicatorKbk))
            ->setOkato($valueOrNull($paymentOrder->okato))
            ->setPaytReason($valueOrNull($paymentOrder->indicatorReason))
            ->setTaxPeriod($valueOrNull($paymentOrder->indicatorPeriod))
            ->setDocNo($valueOrNull($paymentOrder->indicatorNumber))
            ->setDocDate($valueOrNull($paymentOrder->indicatorDate))
            ->setTaxPaytKind($valueOrNull($paymentOrder->indicatorType));

        $payDocRu = (new PayDocRuType())
            ->setDocExtId($paymentOrder->documentExternalId ?: (string)Uuid::generate(false))
            ->setAccDoc($accDoc)
            ->setPayer($payer)
            ->setPayee($payee)
            ->setDepartmentalInfo($departmentalInfo);

        $requestDocument = (new Request())
            ->setOrgId($customerId)
            ->setSender($senderName)
            ->setRequestId((string)Uuid::generate(false))
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setPayDocRu($payDocRu);

        return new self(['request' => $requestDocument]);
    }


}

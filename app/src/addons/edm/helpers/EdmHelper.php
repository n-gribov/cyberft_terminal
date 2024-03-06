<?php

namespace addons\edm\helpers;

use addons\edm\EdmModule;
use addons\edm\models\BankLetter\BankLetterSearch;
use addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationSearch;
use addons\edm\models\ContractRegistrationRequest\ContractRegistrationRequestSearch;
use addons\edm\models\CurrencyPayment\CurrencyPaymentDocumentSearch;
use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictForeignCurrencyPaymentBeneficiary;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyControl\ForeignCurrencyControlSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationSearch;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentType;
use addons\edm\models\Pain001Rub\Pain001RubType;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterSearch;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use addons\edm\models\PaymentRegister\PaymentRegisterWizardForm;
use addons\edm\models\Sbbol2PayDocRu\Sbbol2PayDocRuType;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\SBBOLStmtReq\SBBOLStmtReqType;
use addons\edm\models\StatementRequest\StatementRequestSearch;
use addons\edm\models\StatementRequest\StatementRequestType;
use addons\edm\models\VTBCancellationRequest\VTBCancellationRequestSearch;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use addons\edm\models\VTBRegisterRu\VTBRegisterRuType;
use addons\edm\models\VTBStatementQuery\VTBStatementQueryType;
use addons\ISO20022\helpers\ISO20022Helper;
use addons\ISO20022\helpers\RosbankHelper;
use addons\swiftfin\models\SwiftFinDictBank;
use common\document\Document;
use common\document\DocumentFormatGroup;
use common\helpers\Countries;
use common\helpers\DocumentHelper;
use common\helpers\sbbol2\Sbbol2Helper;
use common\helpers\sbbol\SBBOLHelper;
use common\helpers\StringHelper;
use common\helpers\Uuid;
use common\helpers\vtb\VTBHelper;
use common\models\sbbolxml\request\AccDocType;
use common\models\sbbolxml\request\AccType;
use common\models\sbbolxml\request\BankType;
use common\models\sbbolxml\request\BudgetDepartmentalInfoType;
use common\models\sbbolxml\request\ContragentType;
use common\models\sbbolxml\request\PayDocRuClientType;
use common\models\sbbolxml\request\PayDocRuType;
use common\models\sbbolxml\request\Request;
use common\models\sbbolxml\request\StmtReqType;
use common\models\sbbolxml\SBBOLTransportConfig;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\models\vtbxml\documents\PayDocRu;
use common\models\vtbxml\documents\StatementQuery;
use common\models\vtbxml\service\SignInfo;
use DateTime;
use Exception;
use RuntimeException;
use SimpleXMLElement;
use Yii;
use yii\helpers\ArrayHelper;

class EdmHelper
{
    public static function createStatementRequest(StatementRequestType $model, $senderTerminal)
    {
        $bank = DictBank::findOne(['bik' => $model->BIK]);

        if (empty($bank)) {
            throw new Exception(Yii::t('edm', 'Bank not found'));
        }

        if (empty($bank->terminalId)) {
            throw new Exception(Yii::t('edm',
                    'Bank terminal is not specified for BIK {bankBIK}',
                    ['bankBIK' => $model->BIK]));
        }

        $effectiveModel = $model;
        if (VTBHelper::isGatewayTerminal($bank->terminalId)) {
            $effectiveModel = static::createVTBStatementQueryFromStatementRequest($model, $senderTerminal->terminalId);
        } else if (SBBOLHelper::isGatewayTerminal($bank->terminalId)) {
            $effectiveModel = static::createSBBOLStmtReqFromStatementRequest($model, $senderTerminal->terminalId);
        }

        $document = DocumentHelper::reserveDocument(
            $effectiveModel->type, Document::DIRECTION_OUT, Document::ORIGIN_SERVICE,
            $senderTerminal->id
        );

        if (empty($document)) {
            throw new Exception(Yii::t('edm', 'Error while preparing document'));
        }

        DocumentHelper::createCyberXml(
            $document,
            $effectiveModel,
            [
                'sender' => $senderTerminal->terminalId,
                'receiver' => $bank->terminalId
            ]
        );

        return $document;
    }

    private static function createVTBStatementQueryFromStatementRequest(StatementRequestType $statementRequest, $senderTerminalId)
    {
        if ($statementRequest->startDate != $statementRequest->endDate) {
            throw new Exception(Yii::t('edm', 'VTB statement can be requested only for one day interval'));
        }

        $vtbCustomerId = VTBHelper::getVTBCustomerId($senderTerminalId);
        if (empty($vtbCustomerId)) {
            throw new Exception(Yii::t('edm', 'VTB customer id for terminal {terminalId} is unknown', ['terminalId' => $senderTerminalId]));
        }

        $vtbBranch = DictVTBBankBranch::findOne(['bik' => $statementRequest->BIK]);
        if ($vtbBranch === null) {
            throw new Exception(Yii::t('edm', 'VTB bank branch for {bik} is not found in dictionary', ['bik' => $statementRequest->BIK]));
        }

        $documentVersion = 3;

        $statementQuery = new StatementQuery([
            'DOCUMENTDATE'   => new \DateTime(),
            'CUSTID'         => $vtbCustomerId,
            'KBOPID'         => $vtbBranch->branchId,
            'DOCUMENTNUMBER' => VTBHelper::generateDocumentNumber(),
            'DATEFROM'       => \DateTime::createFromFormat('Y-m-d', $statementRequest->startDate),
            'DATETO'         => \DateTime::createFromFormat('Y-m-d', $statementRequest->endDate),
            'ACCOUNT'        => $statementRequest->accountNumber,
            'STATEMENTTYPE'  => 0, // 0 = выписка
        ]);

        return new VTBStatementQueryType([
            'customerId'      => $vtbCustomerId,
            'documentVersion' => $documentVersion,
            'document'        => $statementQuery,
            'signatureInfo'   => new SignInfo(['signedFields' => $statementQuery->getSignedFieldsIds($documentVersion)]),
        ]);
    }

    private static function createSBBOLStmtReqFromStatementRequest(StatementRequestType $statementRequest, $senderTerminalAddress)
    {
        $startDate = new \DateTime($statementRequest->startDate);
        $endDate = new \DateTime($statementRequest->endDate);
        $interval = $startDate->diff($endDate);
        $daysDelta = $interval->format('%r%a');
        if ($daysDelta >= 15) {
            throw new \Exception(Yii::t('edm', 'Sberbank statement query interval cannot exceed 15 days'));
        }

        $sbbolCustomerId = SBBOLHelper::getSBBOLCustomerIdByAccountNumber($statementRequest->accountNumber);
        $sbbolSenderName = SBBOLHelper::getSBBOLSenderName($senderTerminalAddress);
        if (empty($sbbolCustomerId) || empty($sbbolSenderName)) {
            $errorMessage = Yii::t(
                'edm',
                'Terminal {terminalAddress} is not set up to send documents to Sberbank',
                ['terminalAddress' => $senderTerminalAddress]
            );
            throw new \Exception($errorMessage);
        }

        $stmtReq = (new StmtReqType())
            ->setBeginDate($startDate)
            ->setEndDate($endDate)
            ->setStmtType('101')
            ->setCreateTime(new \DateTime())
            ->setDocExtId((string)Uuid::generate(false));

        $account = EdmPayerAccount::findOne(['number' => $statementRequest->accountNumber]);
        if ($account === null) {
            $errorMessage = Yii::t(
                'edm',
                'Payer account {number} is not found',
                ['number' => $statementRequest->accountNumber]
            );
            throw new \Exception($errorMessage);
        }

        $stmtReq->addToAccounts(
            (new AccType($account->number))
                ->setBic($account->bankBik)
                ->setDocNum(1)
        );

        $requestDocument = (new Request())
            ->setRequestId((string)Uuid::generate(false))
            ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
            ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
            ->setStmtReq($stmtReq);

        return new SBBOLStmtReqType(['request' => $requestDocument]);
    }

    public static function createPaymentRegister(PaymentRegisterWizardForm &$form, $docAttributes = [])
    {
        $account = EdmPayerAccount::findOne(['number' => $form->account]);

        if (!$account) {
            throw new Exception(Yii::t('edm', 'Account ' . $form->account . ' not found'));
        }

        $terminalId = Terminal::getIdByAddress($form->sender);
        if (!$terminalId) {
            throw new Exception('Terminal not found for sender ' . $form->sender);
        }

        if (isset($account->bank) && !empty($account->bank->terminalId)) {
            $recipient = $account->bank->terminalId;
        } else {
            throw new Exception('Recipient not determined for account ' . $account->number);
        }

        $isVtb = VTBHelper::isGatewayTerminal($form->recipient);
        $isSbbol = SBBOLHelper::isGatewayTerminal($form->recipient);
        $isSbbol2 = Sbbol2Helper::isGatewayTerminal($form->recipient);
        $isRosbank = RosbankHelper::isGatewayTerminal($form->recipient);
        $isIso = $isRosbank || DocumentFormatGroup::getGroupByTerminalAddress($form->recipient) === DocumentFormatGroup::ISO20022;
        $typeModel = null;
        if ($isVtb) {
            $typeModel = new VTBRegisterRuType();

            foreach ($form->getPaymentOrders() as $paymentOrderId) {
                $paymentOrderModel = PaymentRegisterPaymentOrder::findOne(['id' => $paymentOrderId]);
                $paymentOrder = new PaymentOrderType();
                $paymentOrder->loadFromString($paymentOrderModel->body);
                $vtbPayDocRu = static::createVTBPayDocRuFromPaymentOrder($paymentOrder, $form->sender);
                $typeModel->paymentOrders[] = $vtbPayDocRu;
            }
        } else if ($isSbbol) {
            if (count($form->getPaymentOrders()) > 1) {
                throw new Exception(Yii::t('edm', 'Sberbank payment register can include only one payment order'));
            }
            $paymentOrderId = $form->getPaymentOrders()[0];
            $paymentOrderModel = PaymentRegisterPaymentOrder::findOne(['id' => $paymentOrderId]);
            $paymentOrder = new PaymentOrderType();
            $paymentOrder->loadFromString($paymentOrderModel->body);
            $typeModel = static::createSBBOLPayDocRuFromPaymentOrder($paymentOrder);
        } else if ($isSbbol2) {
            if (count($form->getPaymentOrders()) > 1) {
                throw new Exception(Yii::t('edm', 'Sberbank payment register can include only one payment order'));
            }
            $paymentOrderId = $form->getPaymentOrders()[0];
            $paymentOrderModel = PaymentRegisterPaymentOrder::findOne(['id' => $paymentOrderId]);
            $paymentOrder = new PaymentOrderType();
            $paymentOrder->loadFromString($paymentOrderModel->body);
            $typeModel = static::createSbbol2PayDocRuFromPaymentOrder($paymentOrder);
        } else if ($isIso) {
            $registerTypeModel = self::createPaymentRegisterTypeModel($form);
            $xml = ISO20022Helper::createPain001XmlFromPaymentOrders($form->account, $registerTypeModel->getPaymentOrders());
            $typeModel = new Pain001RubType();
            $typeModel->loadFromString($xml);
            static::saveISO20022PaymentOrdersUuids($typeModel, $form->getPaymentOrders());
        } else {
            $typeModel = self::createPaymentRegisterTypeModel($form);
        }

        $docAttributes['sender'] = $form->sender;
        $docAttributes['receiver'] = $recipient;
        $docAttributes['type'] = $typeModel->getType();
        $docAttributes['direction'] = Document::DIRECTION_OUT;
        $docAttributes['terminalId'] = $terminalId;

        $org = DictOrganization::findOne(['id' => $account->organizationId]);
        if (!$org) {
            throw new Exception('Organization not found for account ' . $account->number);
        }

        $extAttributes = null;
        if ($isVtb) {
            $currency = DictCurrency::find()->where(['code' => $typeModel->currency])->one();
            $extAttributes = [
                'sum' => $typeModel->sum,
                'count' => $typeModel->count,
                'currency' => $currency ? $currency->name : null,
                'accountId' => $account->id,
                'accountNumber' => $account->number,
                'orgId' => $org->id,
                'date' => date('Y-m-d'),
            ];
        } elseif ($isSbbol) {
            $extAttributes = [
                'sum' => $paymentOrder->sum,
                'count' => 1,
                'currency' => $paymentOrder->currency,
                'accountId' => $account->id,
                'accountNumber' => $account->number,
                'orgId' => $org->id,
                'date' => date('Y-m-d'),
            ];
        } elseif ($isSbbol2) {
            $extAttributes = [
                'sum' => $paymentOrder->sum,
                'count' => 1,
                'currency' => $paymentOrder->currency,
                'accountId' => $account->id,
                'accountNumber' => $account->number,
                'orgId' => $org->id,
                'date' => date('Y-m-d'),
            ];
        } elseif ($isIso) {
            $extAttributes = [
                'sum' => $typeModel->getPaymentRegisterInfo()['sum'],
                'count' => $typeModel->getPaymentRegisterInfo()['count'],
                'currency' => $typeModel->getPaymentRegisterInfo()['currency'],
                'accountId' => $account->id,
                'accountNumber' => $account->number,
                'orgId' => $org->id,
                'date' => (new \DateTime($typeModel->getDate()))->format('Y-m-d'),
                'msgId' => $typeModel->msgId,
            ];
        } else {
            $extAttributes = [
                'sum' => $typeModel->sum,
                'count' => $typeModel->count,
                'currency' => $typeModel->currency,
                'accountId' => $account->id,
                'accountNumber' => $typeModel->getAccountNumber(),
                'orgId' => $org->id,
                'date' => date('Y-m-d', strtotime($typeModel->date)),
            ];
        }

        // Проверка количества необходимых подписей
        // Документ sbbol2 отправляем без подписывания
        if ($account && $account->requireSignQty && !$isSbbol2) {
            // Из персональных настроек счета
            $docAttributes['status'] = Document::STATUS_FORSIGNING;
            $docAttributes['signaturesRequired'] = $account->requireSignQty;
        }

        $context = DocumentHelper::createDocumentContext($typeModel, $docAttributes, $extAttributes);

        if (!$context) {
            throw new Exception(Yii::t('app', 'Save document error'));
        }

        $document = $context['document'];
        $form->docId = $document->id;

        PaymentRegisterPaymentOrder::updateAll(
            ['registerId' => $document->id],
            ['id' => $form->getPaymentOrders()]
        );

        return $document;
    }

    private static function createPaymentRegisterTypeModel(PaymentRegisterWizardForm $form): PaymentRegisterType
    {
        $typeModel = new PaymentRegisterType();
        $typeModel->comment = $form->comment;
        $typeModel->addPaymentOrders($form->getPaymentOrders());
        return $typeModel;
    }

    private static function createVTBPayDocRuFromPaymentOrder(PaymentOrderType $paymentOrder, $senderTerminalId)
    {
        $vtbCustomerId = VTBHelper::getVTBCustomerId($senderTerminalId);
        if (empty($vtbCustomerId)) {
            throw new Exception(Yii::t('edm', 'VTB customer id for terminal {terminalId} is unknown', ['terminalId' => $senderTerminalId]));
        }

        $vtbBranch = DictVTBBankBranch::findOne(['bik' => $paymentOrder->payerBik]);
        if ($vtbBranch === null) {
            throw new Exception(Yii::t('edm', 'VTB bank branch for {bik} is not found in dictionary', ['bik' => $paymentOrder->payerBik]));
        }

        $documentVersion = 7;
        $documentDate = $paymentOrder->getDate() ? DateTime::createFromFormat('d.m.Y', $paymentOrder->getDate()) : null;

        $payerAccount = EdmPayerAccount::findOne(['number' => $paymentOrder->getPayerAccount()]);
        $currencyCode = $payerAccount->edmDictCurrencies->code;

        $payer = empty($paymentOrder->payerName1) ? $paymentOrder->payerName : $paymentOrder->payerName1;
        $receiver = empty($paymentOrder->beneficiaryName1) ? $paymentOrder->beneficiaryName : $paymentOrder->beneficiaryName1;
        $receiverBankName = DictBank::findOne(['bik' => $paymentOrder->beneficiaryBik])->name;

        $vatAmount = $paymentOrder->vat ? round($paymentOrder->sum * $paymentOrder->vat / 100, 2) : null;
        $isUrgentPayment = strtolower(trim($paymentOrder->paymentType)) === 'срочно';
        $sendType = $isUrgentPayment ? $paymentOrder->paymentType : null;
        $sendTypeCode = $isUrgentPayment ? 5 : 4;

        preg_match('/^(\d{2})\.(\d{2})\.(\d{4})$/', $paymentOrder->indicatorDate, $docDateParts);

        $taxPeriodParts = [null, null, null];
        if (preg_match('/^[^\.]{2}\.\d{2}\.\d{4}$/u', $paymentOrder->indicatorPeriod)) {
            $taxPeriodParts = explode('.', $paymentOrder->indicatorPeriod);
        } else if (strlen($paymentOrder->indicatorPeriod) === 8) {
            $taxPeriodParts[0] = $paymentOrder->indicatorPeriod;
        }

        $beneficiaryKpp = $paymentOrder->beneficiaryKpp;
        if (empty($beneficiaryKpp)) {
            // нормализация значения
            $beneficiaryKpp = '';
        }

        $payDocRu = new PayDocRu([
            'DOCUMENTNUMBER'      => $paymentOrder->number,
            'DOCUMENTDATE'        => $documentDate,
            'CUSTID'              => $vtbCustomerId,
            'CURRCODE'            => $currencyCode,
            'PAYER'               => $payer,
            'PAYERINN'            => $paymentOrder->payerInn,
            'PAYERKPP'            => $paymentOrder->payerKpp,
            'PAYERACCOUNT'        => $paymentOrder->getPayerAccount(),
            'PAYERBIC'            => $paymentOrder->payerBik,
            'PAYERCORRACCOUNT'    => $paymentOrder->payerCorrespondentAccount,
            'PAYERBANKNAME'       => $paymentOrder->payerBank1,
            'PAYERPLACE'          => $paymentOrder->payerBank2,
            'RECEIVER'            => $receiver,
            'RECEIVERINN'         => $paymentOrder->beneficiaryInn,
            'RECEIVERACCOUNT'     => $paymentOrder->getBeneficiaryAccount(),
            'RECEIVERBIC'         => $paymentOrder->beneficiaryBik,
            'RECEIVERCORRACCOUNT' => $paymentOrder->beneficiaryCorrespondentAccount,
            'RECEIVERBANKNAME'    => $receiverBankName,
            'RECEIVERPLACE'       => $paymentOrder->beneficiaryBank2,
            'AMOUNT'              => $paymentOrder->sum,
            'NDS'                 => $vatAmount,
            'GROUND'              => $paymentOrder->getPaymentPurpose(),
            'PAYMENTURGENT'       => $paymentOrder->priority,
            'OPERTYPE'            => $paymentOrder->payType,
            'SENDTYPE'            => $sendType,
            'RECEIVERKPP'         => $beneficiaryKpp,
            'STAT1256'            => $paymentOrder->senderStatus,
            'CBCCODE'             => $paymentOrder->indicatorKbk,
            'OKATOCODE'           => $paymentOrder->okato,
            'PAYGRNDPARAM'        => $paymentOrder->indicatorReason,
            'TAXPERIODPARAM1'     => @$taxPeriodParts[0],
            'TAXPERIODPARAM2'     => @$taxPeriodParts[1],
            'TAXPERIODPARAM3'     => @$taxPeriodParts[2],
            'DOCNUMPARAM2'        => $paymentOrder->indicatorNumber,
            'DOCDATEPARAM1'       => @$docDateParts[1],
            'DOCDATEPARAM2'       => @$docDateParts[2],
            'DOCDATEPARAM3'       => @$docDateParts[3],
            'PAYTYPEPARAM'        => $paymentOrder->indicatorType,
            'KBOPID'              => $vtbBranch->branchId,
            'SENDTYPECODE'        => $sendTypeCode,
            'CODEUIP'             => $paymentOrder->code,
        ]);

        return new VTBPayDocRuType([
            'customerId'      => $vtbCustomerId,
            'documentVersion' => $documentVersion,
            'document'        => $payDocRu,
            'signatureInfo'   => new SignInfo(['signedFields' => $payDocRu->getSignedFieldsIds($documentVersion)]),
        ]);
    }

    /**
     * Метод получает массив подписей из документа
     */

    public static function getPaymentRegisterSignaturesList($id, $type = null, $filter = null)
    {
        $document = Yii::$app->terminalAccess->findModel(Document::className(), $id);

        return $document->getSignatures($type, $filter);
    }

    /**
     * Возвращает количество требуемых для подписания личных подписей
     * Из индивидуальных настроек
     * Или из глобальных настроек edm
     */
    public static function getPayerAccountSignaturesNumber(EdmPayerAccount $account)
    {
        // если указано в настройках счета
        if ($account->requireSignQty > 0) {
            return $account->requireSignQty;
        }

        // Получение организации счета и её терминала
        $organization = $account->edmDictOrganization;
        $terminal = $organization->terminal;

        // если не удалось определить терминал счета
        if (!isset($terminal->terminalId)) {
            return 0;
        }

        // Получение количества из глобальных настроек edm
        $module = Yii::$app->addon->getModule(EdmModule::SERVICE_ID);
        $settings = $module->getSettings($terminal->terminalId);

        return $settings->signaturesNumber;
    }

    /**
     * Создание получателя валютного
     * платежа по данным валютного платежа
     */
    public static function createForeignCurrencyPaymentBeneficiary(ForeignCurrencyPaymentType $foreignCurrencyPayment)
    {
        // Поиск получателя по счету
        $beneficiary = DictForeignCurrencyPaymentBeneficiary::findOne(['account' => $foreignCurrencyPayment->beneficiaryAccount]);

        if (!$beneficiary) {
            // если не найден, создаем нового
            $beneficiary = new DictForeignCurrencyPaymentBeneficiary();
            $beneficiary->account = $foreignCurrencyPayment->beneficiaryAccount;
            $beneficiary->terminalId = $foreignCurrencyPayment->terminalId;
        }

        $beneficiary->description = $foreignCurrencyPayment->beneficiary;
        $beneficiary->save();

        return $beneficiary;
    }

    public static function createMt103FromForeignCurrencyPayment(ForeignCurrencyPaymentType $fcp)
    {
        $date = date('ymd', strtotime($fcp->date));

        $sum = str_replace('.', ',', $fcp->sum);
        $commission = $fcp->commission;
        //
        $file = ":20:{$fcp->number}\r\n";
        //
        $file .= ":23B:CRED\r\n";
        //
        if ($fcp->commissionSum) {
            $sumDiff = $fcp->sum - $fcp->commissionSum;
            $sumDiff = number_format($sumDiff, 2);
            $sumDiff = str_replace('.', ',', $sumDiff);
            $file .= ":32A:{$date}{$fcp->currency}{$sumDiff}\r\n";
        } else {
            $file .= ":32A:{$date}{$fcp->currency}{$sum}\r\n";
        }

        $payerLocation = $fcp->payerLocation;
        if (!preg_match('#/^[A-Z]{2}/#', $payerLocation)) {
            $payerLocationParts = preg_split('/\s*\,\s*/', $payerLocation);
            if (count($payerLocationParts) === 2) {
                $city = $payerLocationParts[0];
                $countryName = $payerLocationParts[1];
                $countryCode = Countries::getIso3166_1_alfa_2CodeByName($countryName);
                if ($countryCode) {
                    $payerLocation = "$countryCode/$city";
                }
            }
        }

        $file .= ":33B:{$fcp->currency}{$sum}\r\n";
        //
        $file .= ":50F:/{$fcp->payerAccount}\r\n";
        $file .= "1/{$fcp->payerName}\r\n";
        $file .= "2/{$fcp->payerAddress}\r\n";
        $file .= "3/{$payerLocation}\r\n";
        //
        if ($fcp->payerBank) {
            $file .= ":52A:{$fcp->payerBank}\r\n";
        }
        // Определение данных банка посредника
        if ($fcp->intermediaryBank) {
            // Получение данных по swift bic
            $swiftBank = SwiftFinDictBank::findByCode($fcp->intermediaryBank);

            if ($swiftBank) {
                $file .= ':56A:';
                if ($fcp->intermediaryBankAccount) {
                    $file .= "/{$fcp->intermediaryBankAccount}\r\n";
                }
                $file .= "{$fcp->intermediaryBank}\r\n";
            }
        } else {
            // Получение данных из формы ввода
            if ($fcp->intermediaryBankNameAndAddress) {
                $file .= ':56D:';
                if ($fcp->intermediaryBankAccount) {
                    $file .= "/{$fcp->intermediaryBankAccount}";
                }
                $file .= "\r\n{$fcp->intermediaryBankNameAndAddress}\r\n";
            }
        }
        // Определение данных банка получателя
        if ($fcp->beneficiaryBank) {
            // Получние данных по swift bic
            $swiftBank = SwiftFinDictBank::findByCode($fcp->beneficiaryBank);

            if ($swiftBank) {
                $file .= ':57A:';
                if ($fcp->beneficiaryBankAccount) {
                    $file .= "/{$fcp->beneficiaryBankAccount}\r\n";
                }
                $file .= "{$fcp->beneficiaryBank}\r\n";
            }
        } else {
            // Получение данных из формы ввода
            $file .= ':57D:';
            if ($fcp->beneficiaryBankAccount) {
                $file .= "/{$fcp->beneficiaryBankAccount}";
            }
            $file .= "\r\n{$fcp->beneficiaryBankNameAndAddress}\r\n";
        }

        $file .= ":59:/{$fcp->beneficiaryAccount}\r\n";
        $file .= "{$fcp->beneficiary}\r\n";

        if ($fcp->information) {
            $file .= ":70:{$fcp->information}\r\n";
        }

        $file .= ":71A:{$commission}\r\n";

        if ($fcp->commissionSum) {
            $commissionSum = str_replace('.', ',', $fcp->commissionSum);
            $file .= ":71F:{$fcp->currency}{$commissionSum}\r\n";
        } else if ($fcp->commission == ForeignCurrencyPaymentType::COMMISSION_BEN) {
            // CYB-4195: если BEN, то вписать нулевую сумму для нормальной валидации
            $file .= ":71F:{$fcp->currency}0,00\r\n";
        }

        if ($fcp->additionalInformation) {
            $file .= ":72:{$fcp->additionalInformation}\r\n";
        }

        return $file;
    }

    /*
     * Возвращает общее количество документов модуля edm, ожидающих подписания
     */
    public static function getEdmForSigningCount()
    {
        $allCounts = 0;
        if ($module = Yii::$app->addon->getModule('edm')) {
            $substituteConfig = ['substituteServices' => ['edm' => 'ISO20022']];
            $allCounts
                = (new PaymentRegisterSearch())->countForSigning()
                + (new ForeignCurrencyOperationSearch($substituteConfig))->countForSigning()
                + (new ForeignCurrencyControlSearch($substituteConfig))->countForSigning()
                + (new ConfirmingDocumentInformationSearch($substituteConfig))->countForSigning()
                + (new ContractRegistrationRequestSearch($substituteConfig))->countForSigning()
                + (new StatementRequestSearch($substituteConfig))->countForSigning()
                + (new VTBCancellationRequestSearch())->countForSigning()
                + (new BankLetterSearch())->countForSigning()
                + (new CurrencyPaymentDocumentSearch())->countForSigning();
        }

        return $allCounts;
    }

    /**
     * Установка доступности счета всем пользователям,
     * к которым они относятся и к которым принадлежит терминал
     * @param $accountId
     */
    public static function setAccountToUsers($accountId)
    {
        $account = EdmPayerAccount::findOne($accountId);

        // Если счет не найден
        if (!$account) {
            throw new RuntimeException;
        }

        // Получем организацию, к которой относится счет
        $organization = $account->edmDictOrganization;

        // Если не найдена организация
        if (!$organization) {
            throw new RuntimeException;
        }

        // Терминал, к которому принадлежит организация
        $terminalId = $organization->terminalId;

        // Получение пользователей, которые принадлежат терминалу
        $users = UserTerminal::getUsers($terminalId);

        // Установка прав пользователям на счет
        foreach($users as $user) {
            EdmPayerAccountUser::createOrUpdate($user, $accountId, true);
        }
    }

    public static function deleteAccountFromUsers($accountId)
    {
        $account = EdmPayerAccount::findOne($accountId);

        // Если счет не найден
        if (!$account) {
            throw new RuntimeException;
        }

        // Получем организацию, к которой относится счет
        $organization = $account->edmDictOrganization;

        // Если не найдена организация
        if (!$organization) {
            throw new RuntimeException;
        }

        // Терминал, к которому принадлежит организация
        $terminalId = $organization->terminalId;

        // Получение пользователей, которые принадлежат терминалу
        $users = UserTerminal::getUsers($terminalId);

        // Установка прав пользователям на счет
        foreach($users as $user) {
            EdmPayerAccountUser::deleteAccountFromUser($user, $accountId);
        }
    }

    /**
     * По текстовому файлу проверяем - это единичная платежка или реестр платежных поручений
     */
    public static function checkPaymentOrderOrPaymentRegister($content, $encoding = null)
    {
        $rows = preg_split('/[\\r\\n]+/', StringHelper::utf8($content, $encoding));

        $search = 'СекцияДокумент=Платежное поручение';

        $keys = array_keys($rows, $search);

        if (count($keys) == 1) {
            return PaymentOrderType::TYPE;
        } else if (count($keys) > 1) {
            return PaymentRegisterType::TYPE;
        } else {
            return null;
        }
    }

    /**
     * Список кодов стран
     */
    public static function countryCodesList()
    {
        return [
            'AU' => 'AU - Австралия', 'AT' => 'AT - Австрия', 'AZ' => 'AZ - Азербайджан', 'AX' => 'AX - Аландские острова',
            'AL' => 'AL - Албания', 'DZ' => 'DZ - Алжир', 'VI' => 'VI - Виргинские Острова (США)', 'AS' => 'AS - Американское Самоа',
            'AI' => 'AI - Ангилья', 'AO' => 'AO - Ангола', 'AD' => 'AD - Андорра', 'AQ' => 'AQ - Антарктида',
            'AG' => 'AG - Антигуа и Барбуда', 'AR' => 'AR - Аргентина', 'AM' => 'AM - Армения', 'AW' => 'AW - Аруба',
            'AF' => 'AF - Афганистан', 'BS' => 'BS - Багамы', 'BD' => 'BD - Бангладеш', 'BB' => 'BB - Барбадос',
            'BH' => 'BH - Бахрейн', 'BZ' => 'BZ - Белиз', 'BY' => 'BY - Белоруссия', 'BE' => 'BE - Бельгия',
            'BJ' => 'BJ - Бенин', 'BM' => 'BM - Бермуды', 'BG' => 'BG - Болгария', 'BO' => 'BO - Боливия',
            'BQ' => 'BQ - Бонэйр, Синт-Эстатиус и Саба', 'BA' => 'BA - Босния и Герцеговина',
            'BW' => 'BW - Ботсвана', 'BR' => 'BR - Бразилия', 'IO' => 'IO - Британская территория в Индийском океане',
            'VG' => 'VG - Виргинские Острова (Великобритания)', 'BN' => 'BN - Бруней', 'BF' => 'BF - Буркина-Фасо',
            'BI' => 'BI - Бурунди', 'BT' => 'BT - Бутан', 'VU' => 'VU - Вануату', 'VA' => 'VA - Ватикан',
            'GB' => 'GB - Великобритания', 'HU' => 'HU - Венгрия', 'VE' => 'VE - Венесуэла',
            'UM' => 'UM - Внешние малые острова (США)', 'TL' => 'TL - Восточный Тимор', 'VN' => 'VN - Вьетнам',
            'GA' => 'GA - Габон', 'HT' => 'HT - Гаити', 'GY' => 'GY - Гайана', 'GM' => 'GM - Гамбия',
            'GH' => 'GH - Гана', 'GP' => 'GP - Гваделупа', 'GT' => 'GT - Гватемала', 'GF' => 'GF - Гвиана',
            'GN' => 'GN - Гвинея', 'GW' => 'GW - Гвинея-Бисау', 'DE' => 'DE - Германия', 'GG' => 'GG - Гернси',
            'GI' => 'GI - Гибралтар', 'HN' => 'HN - Гондурас', 'HK' => 'HK - Гонконг', 'GD' => 'GD - Гренада',
            'GL' => 'GL - Гренландия', 'GR' => 'GR - Греция', 'GE' => 'GE - Грузия', 'GU' => 'GU - Гуам',
            'DK' => 'DK - Дания', 'JE' => 'JE - Джерси', 'DJ' => 'DJ - Джибути', 'DM' => 'DM - Доминика',
            'DO' => 'DO - Доминиканская Республика', 'CD' => 'CD - Демократическая Республика Конго',
            'EU' => 'EU - Европейский союз', 'EG' => 'EG - Египет', 'ZM' => 'ZM - Замбия', 'EH' => 'EH - САДР',
            'ZW' => 'ZW - Зимбабве', 'IL' => 'IL - Израиль', 'IN' => 'IN - Индия', 'ID' => 'ID - Индонезия',
            'JO' => 'JO - Иордания', 'IQ' => 'IQ - Ирак', 'IR' => 'IR - Иран', 'IE' => 'IE - Ирландия',
            'IS' => 'IS - Исландия', 'ES' => 'ES - Испания', 'IT' => 'IT - Италия', 'YE' => 'YE - Йемен',
            'CV' => 'CV - Кабо-Верде', 'KZ' => 'KZ - Казахстан', 'KY' => 'KY - Острова Кайман',
            'KH' => 'KH - Камбоджа', 'CM' => 'CM - Камерун', 'CA' => 'CA - Канада', 'QA' => 'QA - Катар',
            'KE' => 'KE - Кения', 'CY' => 'CY - Кипр', 'KG' => 'KG - Киргизия', 'KI' => 'KI - Кирибати',
            'TW' => 'TW - Китайская Республика', 'KP' => 'KP - КНДР (Корейская Народно-Демократическая Республика)',
            'CN' => 'CN - КНР (Китайская Народная Республика)', 'CC' => 'CC - Кокосовые острова',
            'CO' => 'CO - Колумбия', 'KM' => 'KM - Коморы', 'CR' => 'CR - Коста-Рика', 'CI' => 'CI - Кот-д’Ивуар',
            'CU' => 'CU - Куба', 'KW' => 'KW - Кувейт', 'CW' => 'CW - Кюрасао', 'LA' => 'LA - Лаос',
            'LV' => 'LV - Латвия', 'LS' => 'LS - Лесото', 'LR' => 'LR - Либерия', 'LB' => 'LB - Ливан',
            'LY' => 'LY - Ливия', 'LT' => 'LT - Литва', 'LI' => 'LI - Лихтенштейн', 'LU' => 'LU - Люксембург',
            'MU' => 'MU - Маврикий', 'MR' => 'MR - Мавритания', 'MG' => 'MG - Мадагаскар', 'YT' => 'YT - Майотта',
            'MO' => 'MO - Макао', 'MK' => 'MK - Македония', 'MW' => 'MW - Малави', 'MY' => 'MY - Малайзия',
            'ML' => 'ML - Мали', 'MV' => 'MV - Мальдивы', 'MT' => 'MT - Мальта', 'MA' => 'MA - Марокко',
            'MQ' => 'MQ - Мартиника', 'MH' => 'MH - Маршалловы Острова', 'MX' => 'MX - Мексика',
            'FM' => 'FM - Микронезия', 'MZ' => 'MZ - Мозамбик', 'MD' => 'MD - Молдавия',
            'MC' => 'MC - Монако', 'MN' => 'MN - Монголия', 'MS' => 'MS - Монтсеррат', 'MM' => 'MM - Мьянма',
            'NA' => 'NA - Намибия', 'NR' => 'NR - Науру', 'NP' => 'NP - Непал', 'NE' => 'NE - Нигер',
            'NG' => 'NG - Нигерия', 'NL' => 'NL - Нидерланды', 'NI' => 'NI - Никарагуа',
            'NU' => 'NU - Ниуэ', 'NZ' => 'NZ - Новая Зеландия', 'NC' => 'NC - Новая Каледония',
            'NO' => 'NO - Норвегия', 'AE' => 'AE - ОАЭ', 'OM' => 'OM - Оман', 'BV' => 'BV - Остров Буве',
            'IM' => 'IM - Остров Мэн', 'CK' => 'CK - Острова Кука', 'NF' => 'NF - Остров Норфолк',
            'CX' => 'CX - Остров Рождества', 'PN' => 'PN - Острова Питкэрн',
            'SH' => 'SH - Острова Святой Елены, Вознесения и Тристан-да-Кунья',
            'PK' => 'PK - Пакистан', 'PW' => 'PW - Палау',
            'PS' => 'PS - Государство Палестина', 'PA' => 'PA - Панама', 'PG' => 'PG - Папуа — Новая Гвинея',
            'PY' => 'PY - Парагвай', 'PE' => 'PE - Перу', 'PL' => 'PL - Польша', 'PT' => 'PT - Португалия',
            'PR' => 'PR - Пуэрто-Рико', 'CG' => 'CG - Республика Конго', 'KR' => 'KR - Республика Корея',
            'RE' => 'RE - Реюньон', 'RU' => 'RU - Россия', 'RW' => 'RW - Руанда', 'RO' => 'RO - Румыния',
            'SV' => 'SV - Сальвадор', 'WS' => 'WS - Самоа', 'SM' => 'SM - Сан-Марино', 'ST' => 'ST - Сан-Томе и Принсипи',
            'SA' => 'SA - Саудовская Аравия', 'SZ' => 'SZ - Свазиленд', 'MP' => 'MP - Северные Марианские Острова',
            'SC' => 'SC - Сейшельские Острова', 'BL' => 'BL - Сен-Бартелеми', 'MF' => 'MF - Сен-Мартен',
            'PM' => 'PM - Сен-Пьер и Микелон', 'SN' => 'SN - Сенегал', 'VC' => 'VC - Сент-Винсент и Гренадины',
            'KN' => 'KN - Сент-Китс и Невис', 'LC' => 'LC - Сент-Люсия', 'RS' => 'RS - Сербия', 'SG' => 'SG - Сингапур',
            'SX' => 'SX - Синт-Мартен', 'SY' => 'SY - Сирия', 'SK' => 'SK - Словакия', 'SI' => 'SI - Словения',
            'SB' => 'SB - Соломоновы Острова', 'SO' => 'SO - Сомали', 'SD' => 'SD - Судан', 'SR' => 'SR - Суринам',
            'US' => 'US - США', 'SL' => 'SL - Сьерра-Леоне', 'TJ' => 'TJ - Таджикистан', 'TH' => 'TH - Таиланд',
            'TZ' => 'TZ - Танзания', 'TC' => 'TC - Тёркс и Кайкос', 'TG' => 'TG - Того', 'TK' => 'TK - Токелау',
            'TO' => 'TO - Тонга', 'TT' => 'TT - Тринидад и Тобаго', 'TV' => 'TV - Тувалу', 'TN' => 'TN - Тунис',
            'TM' => 'TM - Туркмения', 'TR' => 'TR - Турция', 'UG' => 'UG - Уганда', 'UZ' => 'UZ - Узбекистан',
            'UA' => 'UA - Украина', 'WF' => 'WF - Уоллис и Футуна', 'UY' => 'UY - Уругвай', 'FO' => 'FO - Фареры',
            'FJ' => 'FJ - Фиджи', 'PH' => 'PH - Филиппины', 'FI' => 'FI - Финляндия', 'FK' => 'FK - Фолклендские острова',
            'FR' => 'FR - Франция', 'PF' => 'PF - Французская Полинезия', 'TF' => 'TF - Французские Южные и Антарктические Территории',
            'HM' => 'HM - Херд и Макдональд', 'HR' => 'HR - Хорватия', 'CF' => 'CF - ЦАР', 'TD' => 'TD - Чад',
            'ME' => 'ME - Черногория', 'CZ' => 'CZ - Чехия', 'CL' => 'CL - Чили', 'CH' => 'CH - Швейцария',
            'SE' => 'SE - Швеция', 'SJ' => 'SJ - Шпицберген и Ян-Майен',
            'LK' => 'LK - Шри-Ланка', 'EC' => 'EC - Эквадор', 'GQ' => 'GQ - Экваториальная Гвинея',
            'ER' => 'ER - Эритрея', 'EE' => 'EE - Эстония', 'ET' => 'ET - Эфиопия', 'ZA' => 'ZA - ЮАР',
            'GS' => 'GS - Южная Георгия и Южные Сандвичевы Острова', 'SS' => 'SS - Южный Судан',
            'JM' => 'JM - Ямайка', 'JP' => 'JP - Япония'
        ];
    }

    /**
     * Список кодов видов валютных операций
     */
    public static function fcoCodesList()
    {
        return [
            '01010' => '01010 - Продажа резидентом иностранной валюты за валюту Российской Федерации',
            '01030' => '01030 - Покупка резидентом иностранной валюты за валюту Российской Федерации',
            '01040' => '01040 - Покупка (продажа) резидентом одной иностранной валюты за другую иностранную валюту',
            '02010' => '02010 - Покупка нерезидентом валюты Российской Федерации за иностранную валюту',
            '02020' => '02020 - Продажа нерезидентом валюты Российской Федерации за иностранную валюту',
            '10100' => '10100 - Расчеты нерезидента в виде предварительной оплаты резиденту товаров, вывозимых с
территории Российской Федерации, в том числе по договору комиссии (агентскому
договору, договору поручения) (авансовый платеж), за исключением расчетов,
указанных в группе 22 настоящего Перечня',
            '10200' => '10200 - Расчеты нерезидента при предоставлении резидентом отсрочки платежа за товары,
вывезенные с территории Российской Федерации, в том числе по договору комиссии
(агентскому договору, договору поручения (отсрочка платежа), за исключением
расчетов, указанных в группе 22 настоящего Перечня',
            '10800' => '10800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при вывозе товаров с территории Российской Федерации, за
исключением расчетов по коду 22800',
            '11100' => '11100 - Расчеты резидента в виде предварительной оплаты нерезиденту товаров, ввозимых на
территорию Российской Федерации, в том числе по договору комиссии (агентскому
договору, договору поручения) (авансовый платеж), за исключением расчетов,
указанных в группе 23 настоящего Перечня',
            '11200' => '11200 - Расчеты резидента при предоставлении нерезидентом отсрочки платежа за товары,
ввезенные на территорию Российской Федерации, в том числе по договору комиссии
(агентскому договору, договору поручения) (отсрочка платежа), за исключением
расчетов, указанных в группе 23 настоящего Перечня',
            '11900' => '11900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств при ввозе товаров на территорию Российской Федерации, за
исключением расчетов по коду 23900',
            '12050' => '12050 - Расчеты нерезидента в пользу резидента за товары, продаваемые за пределами
территории Российской Федерации без их ввоза на территорию Российской Федерации,
за исключением расчетов по кодам 22110, 22210, 22300',
            '12060' => '12060 - Расчеты резидента в пользу нерезидента за товары, продаваемые за пределами
территории Российской Федерации без их ввоза на территорию Российской Федерации,
за исключением расчетов по кодам 23110, 23210, 23300',
            '12800' => '12800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при продаже товаров за пределами территории Российской
Федерации, за исключением расчетов по коду 22800',
            '12900' => '12900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств при продаже товаров за пределами территории Российской
Федерации, за исключением расчетов по коду 23900',
            '13010' => '13010 - Расчеты нерезидента в пользу резидента за товары, продаваемые на территории
Российской Федерации, за исключением расчетов по кодам 22110, 22210, 22300',
            '13020' => '13020 - Расчеты резидента в пользу нерезидента за товары, продаваемые на территории
Российской Федерации, за исключением расчетов по кодам 23110, 23210, 23300',
            '13800' => '13800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при продаже товаров на территории Российской Федерации, за
исключением расчетов по коду 22800',
            '13900' => '13900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств при продаже товаров на территории Российской Федерации, за
исключением расчетов по коду 23900',
            '20100' => '20100 - Расчеты нерезидента в виде предварительной оплаты выполняемых резидентом
работ, оказываемых услуг, передаваемых информации и результатов
интеллектуальной деятельности, в том числе исключительных прав на них, включая
выполнение указанных обязательств по договору комиссии (агентскому договору,
договору поручения) (авансовый платеж), за исключением расчетов по коду 20400,
расчетов, указанных в группе 22 настоящего Перечня, и расчетов, связанных с
выплатой вознаграждения резиденту-брокеру по договору о брокерском обслуживании
(группа 58 настоящего Перечня)',
            '20200' => '20200 - Расчеты нерезидента за выполненные резидентом работы, оказанные услуги,
переданные информацию и результаты интеллектуальной деятельности, в том числе
исключительные права на них, включая выполнение указанных обязательств по
договору комиссии (агентскому договору, договору поручения) (отсрочка платежа), за
исключением расчетов по коду 20400, расчетов, указанных в группе 22 настоящего
Перечня, и расчетов, связанных с выплатой вознаграждения резиденту-брокеру по
договору о брокерском обслуживании (группа 58 настоящего Перечня)',
            '20300' => '20300 - Расчеты нерезидента в пользу резидента по договору аренды движимого и (или)
недвижимого имущества, за исключением расчетов по договору финансовой аренды
(лизинга)',
            '20400' => '20400 - Расчеты нерезидента-комитента (принципала, доверителя) в пользу
резидента-комиссионера (агента, поверенного) в связи с оплатой приобретения у
третьих лиц товаров, работ, услуг, информации и результатов интеллектуальной
деятельности, в том числе исключительных прав на них, для нерезидента в
соответствии с договором комиссии (агентским договором, договором поручения), за
исключением расчетов, указанных в группе 58 настоящего Перечня',
            '20500' => '20500 - Расчеты резидента-комиссионера (агента, поручителя) в пользу
нерезидента-комитента (принципала, доверителя) в связи с оказанием резидентом
услуг по реализации иным лицам товаров, работ, услуг, информации и результатов
интеллектуальной деятельности, в том числе исключительных прав на них,
нерезидента в соответствии с договором комиссии (агентским договором, договором
поручения), за исключением расчетов, указанных в группе 58 настоящего Перечня',
            '20800' => '20800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств за выполнение резидентом работ, оказание услуг, передачу
информации и результатов интеллектуальной деятельности, в том числе
исключительных прав на них, за исключением расчетов, указанных в группах 22 и 58
настоящего Перечня',
            '21100' => '21100 - Расчеты резидента в виде предварительной оплаты выполняемых нерезидентом
работ, оказываемых услуг, передаваемых информации и результатов
интеллектуальной деятельности, в том числе исключительных прав на них, включая
выполнение указанных обязательств по договору комиссии (агентскому договору,
договору поручения) (авансовый платеж), за исключением расчетов по коду 21400,
расчетов, указанных в группе 23 настоящего Перечня, и расчетов, связанных с
выплатой вознаграждения нерезиденту-брокеру по договору о брокерском
обслуживании (группа 58 настоящего Перечня)',
            '21200' => '21200 - Расчеты резидента за выполненные нерезидентом работы, оказанные услуги,
переданную информацию и результаты интеллектуальной деятельности, в том числе
исключительные права на них, включая выполнение указанных обязательств по
договору комиссии (агентскому договору, договору поручения) (отсрочка платежа), за
исключением расчетов по коду 21400, расчетов, указанных в группе 23 настоящего
Перечня, и расчетов, связанных с выплатой вознаграждения нерезиденту-брокеру по
договору о брокерском обслуживании (группа 58 настоящего Перечня)',
            '21300' => '21300 - Расчеты резидента в пользу нерезидента по договору аренды движимого и (или)
недвижимого имущества, за исключением расчетов по договору финансовой аренды
(лизинга)',
            '21400' => '21400 - Расчеты резидента-комитента (принципала, доверителя) в пользу
нерезидента-комиссионера (агента, поверенного) в связи с оплатой приобретения у
третьих лиц товаров, работ, услуг, информации и результатов интеллектуальной
деятельности, в том числе исключительных прав на них, для резидента в соответствии
с договором комиссии (агентским договором, договором поручения), за исключением
расчетов, указанных в группе 58 настоящего Перечня',
            '21500' => '21500 - Расчеты нерезидента-комиссионера (агента, поручителя) в пользу
резидента-комитента (принципала, доверителя) в связи с оказанием нерезидентом
услуг по реализации иным лицам товаров, работ, услуг, информации и результатов
интеллектуальной деятельности, в том числе исключительных прав на них, резидента в
соответствии с договором комиссии (агентским договором, договором поручения), за
исключением расчетов, указанных в группе 58 настоящего Перечня',
            '21900' => '21900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств за выполнение нерезидентом работ, оказание услуг, передачу
информации и результатов интеллектуальной деятельности, в том числе
исключительных прав на них, за исключением расчетов, указанных в группах 23 и 58
настоящего Перечня',
            '22100' => '22100 - Расчеты нерезидента-комитента (принципала, доверителя) в виде предварительной
оплаты резиденту-комиссионеру (агенту, поручителю) товаров, вывозимых с
территории Российской Федерации, выполняемых работ, оказываемых услуг,
передаваемых информации и результатов интеллектуальной деятельности, в том
числе исключительных прав на них (авансовый платеж), за исключением расчетов по
коду 22110 и расчетов, связанных с выплатой вознаграждения резиденту-брокеру по
договору о брокерском обслуживании (группа 58 настоящего Перечня)',
            '22110' => '22110 - Расчеты нерезидента в виде предварительной оплаты резиденту поставляемых
товаров, выполняемых работ, оказываемых услуг, передаваемых информации и
результатов интеллектуальной деятельности, в том числе исключительных прав на
них, по договорам (контрактам), указанным в подпункте 5.1.2 пункта 5.1 настоящей
Инструкции (авансовый платеж)',
            '22200' => '22200 - Расчеты нерезидента-комитента (принципала, доверителя) при предоставлении
резидентом-комиссионером (агентом, поручителем) отсрочки платежа за вывезенные с
территории Российской Федерации товары, выполненные работы, оказанные услуги,
переданные информацию и результаты интеллектуальной деятельности, в том числе
исключительные права на них (отсрочка платежа), за исключением расчетов по коду
22210 и расчетов, связанных с выплатой вознаграждения резиденту-брокеру по
договору о брокерском обслуживании (группа 58 настоящего Перечня)',
            '22210' => '22210 - Расчеты нерезидента при предоставлении резидентом отсрочки платежа за
поставленные товары, выполненные работы, оказанные услуги, переданные
информацию и результаты интеллектуальной деятельности, в том числе
исключительные права на них, по договорам (контрактам), указанным в подпункте 5.1.2
пункта 5.1 настоящей Инструкции (отсрочка платежа)',
            '22300' => '22300 - Расчеты нерезидента в пользу резидента по договору финансовой аренды (лизинга)',
            '22800' => '22800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств по договорам (контрактам) смешанного типа',
            '23100' => '23100 - Расчеты резидента-комитента (принципала, доверителя) в виде предварительной
оплаты нерезиденту-комиссионеру (агенту, поручителю) товаров, ввозимых на
территорию Российской Федерации, выполняемых работ, оказываемых услуг,
передаваемых информации и результатов интеллектуальной деятельности, в том
числе исключительных прав на них (авансовый платеж), за исключением расчетов по
коду 23110 и расчетов, связанных с выплатой вознаграждения нерезиденту-брокеру по
договору о брокерском обслуживании (группа 58 настоящего Перечня)',
            '23110' => '23110 - Расчеты резидента в виде предварительной оплаты нерезиденту поставляемых
товаров, выполняемых работ, оказываемых услуг, передаваемых информации и
результатов интеллектуальной деятельности, в том числе исключительных прав на
них, по договорам (контрактам), указанным в подпункте 5.1.2 пункта 5.1 настоящей
Инструкции (авансовый платеж)',
            '23200' => '23200 - Расчеты резидента-комитента (принципала, доверителя) при предоставлении
нерезидентом-комиссионером (агентом, поручителем) отсрочки платежа за ввезенные
на территорию Российской Федерации товары, выполненные работы, оказанные
услуги, переданные информацию и результаты интеллектуальной деятельности, в том
числе исключительные права на них (отсрочка платежа), за исключением расчетов по
коду 23210 и расчетов, связанных с выплатой вознаграждения нерезиденту-брокеру по
договору о брокерском обслуживании (группа 58 настоящего Перечня)',
            '23210' => '23210 - Расчеты резидента при предоставлении нерезидентом отсрочки платежа за
поставленные товары, выполненные работы, оказанные услуги, переданную
информацию и результаты интеллектуальной деятельности, в том числе
исключительные права на них, по договорам (контрактам), указанным в подпункте 5.1.2
пункта 5.1 настоящей Инструкции (отсрочка платежа)',
            '23300' => '23300 - Расчеты резидента в пользу нерезидента по договору финансовой аренды (лизинга)',
            '23900' => '23900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств по договорам (контрактам) смешанного типа',
            '30010' => '30010 - Расчеты нерезидента в пользу резидента за недвижимое имущество, приобретаемое
за пределами территории Российской Федерации, в том числе связанные с долевым
участием нерезидента в строительстве резидентом недвижимого имущества за
пределами территории Российской Федерации',
            '30020' => '30020 - Расчеты резидента в пользу нерезидента за недвижимое имущество, приобретаемое
за пределами территории Российской Федерации, в том числе связанные с долевым
участием резидента в строительстве нерезидентом недвижимого имущества за
пределами территории Российской Федерации',
            '30030' => '30030 - Расчеты нерезидента в пользу резидента за недвижимое имущество, приобретаемое
на территории Российской Федерации, в том числе связанные с долевым участием
нерезидента в строительстве резидентом недвижимого имущества на территории
Российской Федерации',
            '30040' => '30040 - Расчеты резидента в пользу нерезидента за недвижимое имущество, приобретаемое
на территории Российской Федерации, в том числе связанные с долевым участием
резидента в строительстве нерезидентом недвижимого имущества на территории
Российской Федерации',
            '30800' => '30800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств по операциям с недвижимым имуществом, в том числе связанным с
долевым участием в строительстве недвижимого имущества',
            '30900' => '30900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств по операциям с недвижимым имуществом, в том числе связанным с
долевым участием в строительстве недвижимого имущества',
            '32010' => '32010 - Расчеты нерезидента в пользу резидента за уступаемое резидентом требование
нерезиденту в соответствии с договором уступки требования',
            '32015' => '32015 - Расчеты резидента в пользу нерезидента за уступаемое нерезидентом требование
резиденту в соответствии с договором уступки требования',
            '32020' => '32020 - Расчеты нерезидента в пользу резидента за переводимый нерезидентом долг на
резидента в соответствии с договором перевода долга',
            '32025' => '32025 - Расчеты резидента в пользу нерезидента за переводимый резидентом долг на
нерезидента в соответствии с договором перевода долга',
            '35030' => '35030 - Расчеты нерезидента в пользу резидента по прочим операциям, связанным с
внешнеторговой деятельностью и прямо не указанным в группах 10 - 23 настоящего
Перечня',
            '35040' => '35040 - Расчеты резидента в пользу нерезидента по прочим операциям, связанным с
внешнеторговой деятельностью и прямо не указанным в группах 10 - 23 настоящего
Перечня',
            '40030' => '40030 - Расчеты резидента в пользу нерезидента при предоставлении денежных средств по
договору займа',
            '40900' => '40900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств при предоставлении резидентом нерезиденту займа по договору
займа',
            '41030' => '41030 - Расчеты нерезидента в пользу резидента при предоставлении денежных средств по
кредитному договору, договору займа',
            '41800' => '41800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при предоставлении нерезидентом резиденту кредита или займа по
кредитному договору, договору займа',
            '42015' => '42015 - Расчеты резидента в пользу нерезидента по возврату основного долга по кредитному
договору, договору займа',
            '42035' => '42035 - Расчеты резидента в пользу нерезидента по выплате процентов по кредитному
договору, договору займу',
            '42050' => '42050 - Прочие расчеты резидента в пользу нерезидента, связанные с уплатой премий
(комиссий) и иных денежных средств по привлеченному кредиту, займу',
            '42900' => '42900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств при погашении резидентом основного долга по кредитному
договору, договору займу',
            '42950' => '42950 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
процентов по кредитному договору, договору займу',
            '43015' => '43015 - Расчеты нерезидента в пользу резидента по возврату основного долга по договору
займа',
            '43035' => '43035 - Расчеты нерезидента в пользу резидента по выплате процентов по договору займу',
            '43050' => '43050 - Прочие расчеты нерезидента в пользу резидента, связанные с уплатой премий
(комиссий) и иных денежных средств по привлеченному займу',
            '43800' => '43800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при возврате основного долга нерезидентом по договору займу',
            '43850' => '43850 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при погашении процентов по договору займу',
            '50100' => '50100 - Расчеты резидента в пользу нерезидента по операциям с долями, вкладами, паями в
имуществе (уставном или складочном капитале, паевом фонде кооператива)
юридического лица, а также по договору простого товарищества',
            '50110' => '50110 - Расчеты резидента в пользу нерезидента при выплате дивидендов (доходов) от
инвестиций в форме капитальных вложений',
            '50200' => '50200 - Расчеты нерезидента в пользу резидента по операциям с долями, вкладами, паями в
имуществе (уставном или складочном капитале, паевом фонде кооператива)
юридического лица, а также по договору простого товарищества',
            '50210' => '50210 - Расчеты нерезидента в пользу резидента при выплате дивидендов (доходов) от
инвестиций в форме капитальных вложений',
            '50800' => '50800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств по операциям инвестирования в форме капитальных вложений',
            '50900' => '50900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств по операциям инвестирования в форме капитальных вложений',
            '51210' => '51210 - Расчеты нерезидента в пользу резидента за приобретаемые облигации, акции и иные
эмиссионные ценные бумаги резидентов',
            '51215' => '51215 - Расчеты нерезидента в пользу резидента за приобретаемые облигации, акции и иные
эмиссионные ценные бумаги нерезидентов',
            '51230' => '51230 - Расчеты нерезидента в пользу резидента за приобретаемые паи инвестиционных
фондов, учредителем которых является резидент',
            '51235' => '51235 - Расчеты нерезидента в пользу резидента за приобретаемые паи инвестиционных
фондов, учредителем которых является нерезидент',
            '51250' => '51250 - Расчеты нерезидента в пользу резидента за приобретаемые векселя и иные
неэмиссионные ценные бумаги, выданные резидентом',
            '51255' => '51255 - Расчеты нерезидента в пользу резидента за приобретаемые векселя и иные
неэмиссионные ценные бумаги, выданные нерезидентами',
            '51800' => '51800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств по операциям с ценными бумагами (правами, удостоверенными
ценными бумагам), а также денежных средств по таким неисполненным
обязательствам',
            '52210' => '52210 - Расчеты резидента в пользу нерезидента за приобретаемые облигации, акции и иные
эмиссионные ценные бумаги резидентов',
            '52215' => '52215 - Расчеты резидента в пользу нерезидента за приобретаемые облигации, акции и иные
эмиссионные ценные бумаги нерезидентов',
            '52230' => '52230 - Расчеты резидента в пользу нерезидента за приобретаемые паи инвестиционных
фондов, учредителем которых является нерезидент',
            '52235' => '52235 - Расчеты резидента в пользу нерезидента за приобретаемые паи инвестиционных
фондов, учредителем которых является резидент',
            '52250' => '52250 - Расчеты резидента в пользу нерезидента за приобретаемые векселя и иные
неэмиссионные ценные бумаги, выданные резидентами',
            '52255' => '52255 - Расчеты резидента в пользу нерезидента за приобретаемые векселя и иные
неэмиссионные ценные бумаги, выданные нерезидентами',
            '52900' => '52900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств по операциям с ценными бумагами (правами, удостоверенными
ценными бумагам), а также денежных средств по таким неисполненным
обязательствам',
            '55210' => '55210 - Расчеты резидента в пользу нерезидента при исполнении резидентом обязательств по
облигациям, акциям и иным эмиссионным ценным бумагам',
            '55230' => '55230 - Расчеты резидента в пользу нерезидента при выплате доходов по паям
инвестиционных фондов',
            '55250' => '55250 - Расчеты резидента в пользу нерезидента при исполнении резидентом обязательств по
векселями и иным неэмиссионным ценным бумагам',
            '55310' => '55310 - Расчеты нерезидента в пользу резидента при исполнении нерезидентом обязательств
по облигациям и иным эмиссионным ценным бумагам',
            '55330' => '55330 - Расчеты нерезидента в пользу резидента при выплате доходов по паям
инвестиционных фондов',
            '55350' => '55350 - Расчеты нерезидента в пользу резидента при исполнении нерезидентом обязательств
по векселям и иным неэмиссионным ценным бумагам',
            '55800' => '55800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств при исполнении обязательств по операциям с ценными бумагами, а
также денежных средств по неисполненным обязательствам',
            '55900' => '55900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств при исполнении обязательств по операциям с ценными бумагами, а
также денежных средств по неисполненным обязательствам',
            '56010' => '56010 - Расчеты нерезидента в пользу резидента по операциям со срочными и производными
финансовыми инструментами (премии, маржевые и гарантийные взносы и иные
денежные средства, перечисляемые в соответствии с условиями таких контрактов, за
исключением расчетов, связанных с поставкой базисного актива)',
            '56060' => '56060 - Расчеты резидента в пользу нерезидента по операциям со срочными и производными
финансовыми инструментами (премии, маржевые и гарантийные взносы и иные
денежные средства, перечисляемые в соответствии с условиями таких контрактов, за
исключением расчетов, связанных с поставкой базисного актива',
            '56800' => '56800 - Расчеты резидента, связанные с возвратом нерезиденту излишне перечисленных
денежных средств, а также денежных средств по неисполненным обязательствам,
указанным в настоящей группе',
            '56900' => '56900 - Расчеты нерезидента, связанные с возвратом резиденту излишне перечисленных
денежных средств, а также денежных средств по неисполненным обязательствам,
указанным в настоящей группе',
            '57010' => '57010 - Расчеты резидента - учредителя управления в пользу резидента - доверительного
управляющего в иностранной валюте',
            '57015' => '57015 - Расчеты резидента - доверительного управляющего в пользу резидента - учредителя
управления в иностранной валюте',
            '57020' => '57020 - Расчеты нерезидента - учредителя управления в пользу резидента - доверительного
управляющего в иностранной валюте и валюте Российской Федерации',
            '57025' => '57025 - Расчеты резидента - доверительного управляющего в пользу нерезидента - учредителя
управления в иностранной валюте и валюте Российской Федерации',
            '57030' => '57030 - Расчеты резидента - учредителя управления в пользу нерезидента - доверительного
управляющего',
            '57035' => '57035 - Расчеты нерезидента - доверительного управляющего в пользу резидента - учредителя
управления',
            '57800' => '57800 - Расчеты резидента, связанные с возвратом нерезиденту излишне перечисленных
денежных средств по договорам доверительного управления имуществом',
            '57900' => '57900 - Расчеты нерезидента, связанные с возвратом резиденту излишне перечисленных
денежных средств по договорам доверительного управления имуществом',
            '58010' => '58010 - Расчеты резидента в пользу брокера-нерезидента по договору о брокерском
обслуживании, включая выплаты вознаграждения брокера-нерезидента',
            '58015' => '58015 - Расчеты брокера-нерезидента в пользу резидента по договору о брокерском
обслуживании, за исключением расчетов по коду 58900',
            '58020' => '58020 - Расчеты нерезидента в пользу брокера-резидента по договору о брокерском
обслуживании, включая выплаты вознаграждения брокера-резидента',
            '58025' => '58025 - Расчеты брокера-резидента в пользу нерезидента по договору о брокерском
обслуживании, за исключением расчетов по коду 58800',
            '58030' => '58030 - Расчеты между резидентами в иностранной валюте по договору о брокерском
обслуживании, включая выплаты вознаграждения брокера',
            '58800' => '58800 - Расчеты резидента, связанные с возвратом нерезиденту излишне перечисленных
денежных средств по договору о брокерском обслуживании',
            '58900' => '58900 - Расчеты нерезидента, связанные с возвратом резиденту излишне перечисленных
денежных средств по договору о брокерском обслуживании',
            '60070' => '60070 - Переводы валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации на другой банковский счет (счет по депозиту) в валюте
Российской Федерации этого нерезидента, открытый в этом же уполномоченном банке,
со счета по депозиту нерезидента в валюте Российской Федерации на банковский счет
этого нерезидента, открытый в этом же уполномоченном банке',
            '60071' => '60071 - Переводы валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации, открытого в уполномоченном банке, на банковский счет (счет
по депозиту) в валюте Российской Федерации этого нерезидента, открытый в другом
уполномоченном банке, со счета по депозиту нерезидента в валюте Российской
Федерации на банковский счет этого нерезидента, открытый в другом уполномоченном
банке',
            '60075' => '60075 - Переводы валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации, открытого в уполномоченном банке, на банковский счет (счет
по депозиту) в валюте Российской Федерации этого нерезидента, открытый в банке-
нерезиденте',
            '60076'  => '60076 - Переводы валюты Российской Федерации с банковского счета (счета по депозиту)
нерезидента в валюте Российской Федерации, открытого в банке-нерезиденте, на
банковский счет в валюте Российской Федерации этого нерезидента, открытый в
уполномоченном банке',
            '60080' => '60080 - Переводы валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации на банковский счет (счет по депозиту) в валюте Российской
Федерации другого нерезидента, открытый в этом же уполномоченном банке',
            '60085' => '60085 - Переводы валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации, открытого в уполномоченном банке, на банковский счет (счет
по депозиту) в валюте Российской Федерации другого нерезидента, открытый в другом
уполномоченном банке',
            '60086' => '60086 - Переводы валюты Российской Федерации с банковского счета (счета по депозиту)
нерезидента в валюте Российской Федерации, открытого в банке-нерезиденте, на
банковский счет в валюте Российской Федерации другого нерезидента, открытый в
уполномоченном банке',
            '60090' => '60090 - Снятие наличной валюты Российской Федерации со счета нерезидента в валюте
Российской Федерации, открытого в уполномоченном банке',
            '60095' => '60095 - Зачисление наличной валюты Российской Федерации на счет нерезидента в валюте
Российской Федерации, открытый в уполномоченном банке',
            '60200' => '60200 - Списание валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации, открытого в уполномоченном банке, с использованием
банковской карты',
            '61070' => '61070 - Расчеты между резидентами в иностранной валюте по договорам транспортной
экспедиции, перевозки и фрахтования (чартера) при оказании экспедитором,
перевозчиком и фрахтовщиком услуг, связанных с перевозкой вывозимого из
Российской Федерации или ввозимого в Российскую Федерацию груза, транзитной
перевозкой груза по территории Российской Федерации, а также по договорам
страхования указанных грузов',
            '61100' => '61100 - Переводы иностранной валюты с транзитного валютного счета резидента на другой
транзитный валютный счет этого резидента или расчетный счет этого резидента в
иностранной валюте',
            '61115' => '61115 - Расчеты в иностранной валюте между резидентами, являющимися комиссионерами
(агентами, поверенными), и резидентами, являющимися комитентами (принципалами,
доверителями), при оказании комиссионерами (агентами, поверенными) услуг,
связанных с заключением и исполнением договоров с нерезидентами о передаче
товаров, выполнении работ, об оказании услуг, о передаче информации и результатов
интеллектуальной деятельности, в том числе исключительных прав на них, включая
операции по возврату комитентам (принципалам, доверителям) денежных средств, за
исключением платежей по кодам 57010, 58030 и 61162',
            '61130' => '61130 - Переводы иностранной валюты с расчетного счета резидента в иностранной валюте,
открытого в уполномоченном банке, на счет этого же резидента в иностранной валюте,
открытый в этом же уполномоченном банке, со счета резидента в иностранной валюте,
открытого в уполномоченном банке, на расчетный счет этого же резидента в
иностранной валюте, открытый в этом же уполномоченном банке',
            '61135' => '61135 - Переводы иностранной валюты с расчетного счета резидента в иностранной валюте,
открытого в уполномоченном банке, на счет этого резидента в иностранной валюте,
открытый в другом уполномоченном банке, со счета резидента в иностранной валюте,
открытого в уполномоченном банке, на расчетный счет этого резидента в иностранной
валюте, открытый в другом уполномоченном банке',
            '61140' => '61140 - Переводы иностранной валюты или валюты Российской Федерации со счета
резидента, открытого в банке-нерезиденте, на счет этого резидента, открытый в
уполномоченном банке',
            '61145' => '61145 - Переводы иностранной валюты со счета резидента, открытого в банке-нерезиденте, на
счет другого резидента, открытый в уполномоченном банке',
            '61150' => '61150 - Переводы иностранной валюты или валюты Российской Федерации с расчетного счета
резидента, открытого в уполномоченном банке, на счет этого же резидента, открытый в
банке-нерезиденте',
            '61155' => '61155 - Переводы иностранной валюты с расчетного счета резидента, открытого в
уполномоченном банке, на счет другого резидента, открытый в банке-нерезиденте',
            '61160' => '61160 - Расчеты между резидентами в иностранной валюте, связанные с внесением и
возвратом индивидуального и (или) коллективного клирингового обеспечения, в
соответствии с Федеральным законом от 7 февраля 2011 года N 7-ФЗ "О клиринге и
клиринговой деятельности" (Собрание законодательства Российской Федерации, 2011,
N 7, ст. 904; N 48, ст. 6728; N 49, ст. 7040, ст. 7061) (далее - Федеральный закон "О
клиринге и клиринговой деятельности")',
            '61161' => '61161 - Расчеты между резидентами в иностранной валюте по итогам клиринга,
осуществляемого в соответствии с Федеральным законом "О клиринге и клиринговой
деятельности"',
            '61162' => '61162 - Расчеты в иностранной валюте между резидентами, являющимися комиссионерами
(агентами, поверенными), и резидентами, являющимися комитентами (принципалами,
доверителями), при оказании комиссионерами (агентами, поверенными) услуг,
связанных с заключением и исполнением договоров, обязательства по которым
подлежат исполнению по итогам клиринга, осуществляемого в соответствии с
Федеральным законом "О клиринге и клиринговой деятельности", в том числе
возвратом комитентам (принципалам, доверителям) денежных средств',
            '61163' => '61163 - Расчеты в иностранной валюте между резидентами, связанные с исполнением и (или)
прекращением договора, являющегося производным финансовым инструментом',
            '61164' => '61164 - Переводы валюты Российской Федерации со счета резидента открытого в
банке-нерезиденте, на счет другого резидента, открытый в уполномоченном банке',
            '61165' => '61165 - Переводы валюты Российской Федерации со счета резидента открытого в
уполномоченном банке, на счет другого резидента, открытый в банке-нерезиденте',
            '61170' => '61170 - Снятие наличной иностранной валюты со счета резидента в иностранной валюте,
открытого в уполномоченном банке',
            '61175' => '61175 - Зачисление наличной иностранной валюты на счет резидента в иностранной валюте,
открытый в уполномоченном банке',
            '61200' => '61200 - Списание с расчетного счета резидента, открытого в уполномоченном банке, с
использованием банковской карты',
            '70010' => '70010 - Расчеты нерезидента в пользу резидента, связанные с уплатой налогов, пошлин и
иных сборов, за исключением расчетов по коду 70120',
            '70020' => '70020 - Расчеты резидента в пользу нерезидента, связанные с уплатой налогов, пошлин и
иных сборов, за исключением расчетов по коду 70125',
            '70030' => '70030 - Расчеты, связанные с выплатой нерезидентом резиденту пенсий, пособий и других
социальных выплат, за исключением расчетов по коду 70120',
            '70040' => '70040 - Расчеты, связанные с выплатой резидентом нерезиденту пенсий, пособий и других
социальных выплат, за исключением расчетов по коду 70125',
            '70050' => '70050 - Расчеты, связанные с выплатой нерезидентом резиденту заработной платы и других
видов оплаты труда, за исключением расчетов по коду 70120',
            '70060' => '70060 - Расчеты, связанные с выплатой резидентом нерезиденту заработной платы и других
видов оплаты труда, за исключением расчетов по коду 70125',
            '70090' => '70090 - Расчеты, связанные с оказанием нерезидентом резиденту безвозмездной финансовой
помощи, за исключением расчетов по коду 70100',
            '70095' => '70095 - Расчеты, связанные с оказанием резидентом нерезиденту безвозмездной финансовой
помощи, за исключением расчетов по коду 70105',
            '70100' => '70100 - Расчеты, связанные с оказанием нерезидентом резиденту благотворительной помощи,
сбором пожертвований, выплатой (получением) грантов и иных платежей на
безвозмездной основе',
            '70105' => '70105 - Расчеты, связанные с оказанием резидентом нерезиденту благотворительной помощи,
сбором пожертвований, выплатой (получением) грантов и иных платежей на
безвозмездной основе',
            '70110' => '70110 - Расчеты нерезидента в пользу резидента, связанные с выплатой страхового
возмещения по договору страхования или перестрахования',
            '70115' => '70115 - Расчеты резидента в пользу нерезидента, связанные с выплатой страхового
возмещения по договору страхования или перестрахования',
            '70120' => '70120 - Расчеты нерезидента в пользу резидента, связанные с исполнением решений
судебных органов',
            '70125' => '70125 - Расчеты резидента в пользу нерезидента, связанные с исполнением решений
судебных органов',
            '70200' => '70200 - Прочие расчеты нерезидента в пользу резидента по неторговым операциям, за
исключением расчетов по кодам 70010, 70030, 70050, 70090, 70100, 70110, 70120',
            '70205' => '70205 - Прочие расчеты резидента в пользу нерезидента по неторговым операциям, за
исключением расчетов по кодам 70020, 70040, 70060, 70095, 70105, 70115, 70125',
            '70800' => '70800 - Расчеты резидента в пользу нерезидента, связанные с возвратом излишне полученных
денежных средств по неторговым операциям',
            '70900' => '70900 - Расчеты нерезидента в пользу резидента, связанные с возвратом излишне полученных
денежных средств по неторговым операциям',
            '80010' => '80010 - Расчеты между нерезидентом и уполномоченным банком в валюте Российской
Федерации по кредитному договору',
            '80020' => '80020 - Списание валюты Российской Федерации с банковского счета нерезидента в валюте
Российской Федерации в связи с открытием аккредитива',
            '80021' => '80021 - Зачисление валюты Российской Федерации на банковский счет нерезидента в валюте
Российской Федерации в связи с закрытием аккредитива',
            '80050' => '80050 - Расчеты между нерезидентом и уполномоченным банком в валюте Российской
Федерации по иным операциям, за исключением расчетов, указанных в группах 02, 57 и
58 настоящего Перечня, и расчетов по кодам 80010, 80020, 80021',
            '80110' => '80110 - Расчеты между резидентом и уполномоченным банком в иностранной валюте по
кредитному договору',
            '80120' => '80120 - Списание иностранной валюты, валюты Российской Федерации с расчетного счета
резидента в уполномоченном банке в связи с открытием аккредитива в пользу
нерезидента',
            '80121' => '80121 - Зачисление иностранной валюты, валюты Российской Федерации на расчетный счет
резидента в уполномоченном банке в связи с закрытием аккредитива в пользу
нерезидента',
            '80150' => '80150 - Расчеты между резидентом и уполномоченным банком в иностранной валюте по иным
операциям, за исключением расчетов, указанных в группах 01, 57 и 58 настоящего
Перечня, и расчетов по кодам 80110, 80120, 80121',
            '99010' => '99010 - Возврат резиденту ошибочно списанных (зачисленных) денежных средств',
            '99020' => '99020 - Возврат нерезиденту ошибочно списанных (зачисленных) денежных средств',
            '99090' => '99090 - Расчеты по операциям, не указанным в группах 01 - 80 настоящего Перечня, а также за
исключением платежей по кодам 99010, 99020',
        ];
    }

    /**
     * Признак поставки для справки о подтверждающих документах
     * @return array
     */
    public static function getCdiTypes()
    {
        return [
            '1' => '1 - исполнение резидентом обязательств по контракту в счет ранее полученного аванса от нерезидента',
            '2' => '2 - предоставление резидентом коммерческого кредита нерезиденту в виде отсрочки оплаты',
            '3' => '3 - исполнение нерезидентом обязательств по контракту в счет ранее полученного аванса от резидента',
            '4' => '4 - предоставление нерезидентом коммерческого кредита резиденту в виде отсрочки оплаты'
        ];
    }

    /**
     * Код вида подтверждающего документа для справки о подверждающих документах
     * @return array
     */
    public static function getCdiCodes()
    {
        return [
            '01_3' => '01_3 - О вывозе с территории Российской Федерации товаров с оформлением декларации на товары или документов, указанных в подпункте 9.1.1 пункта 9.1 Инструкции от 04.06.2012 № 138-И (далее - Инструкция), за исключением документов с кодом 03_3',
            '01_4' => '01_4 - О ввозе на территорию Российской Федерации товаров с оформлением декларации на товары или документов, указанных в подпункте 9.1.1 пункта 9.1 Инструкции, за исключением документов с кодом 03_4',
            '02_3' => '02_3 - Об отгрузке (передаче покупателю, перевозчику) товаров при их вывозе с территории Российской Федерации без оформления декларации на товары или документов, указанных в подпункте 9.1.1 пункта 9.1 Инструкции, за исключением документов с кодом 03_3',
            '02_4' => '02_4 - О получении (передаче продавцом, перевозчиком) товаров при их ввозе на территорию Российской Федерации без оформления декларации на товары или документов, указанных в подпункте 9.1.1 пункта 9.1 Инструкции, за исключением документов с кодом 03_4',
            '03_3' => '03_3 - О передаче резидентом товаров и оказании услуг нерезиденту по контрактам, указанным в подпункте 5.1.2 пункта 5.1 Инструкции',
            '03_4' => '03_4 - О получении резидентом товаров и услуг от нерезидента по контрактам, указанным в подпункте 5.1.2 пункта 5.1 Инструкции',
            '04_3' => '04_3 - О выполненных резидентом работах, оказанных услугах, переданных информации и результатах интеллектуальной деятельности, в том числе исключительных прав на них,  о переданном резидентом в аренду движимом и (или) недвижимом имуществе, за исключением документов с кодами 03_3 и 15_3',
            '04_4' => '04_4 - О выполненных нерезидентом работах, оказанных услугах, переданных информации и результатах интеллектуальной деятельности, в том числе исключительных прав на них, о переданном нерезидентом в аренду движимом и (или) недвижимом имуществе, за исключением документов с кодами 03_4 и 15_4',
            '05_3' => '05_3 - О прощении резидентом долга (основной долг) нерезиденту по кредитному договору',
            '05_4' => '05_4 - О прощении нерезидентом долга (основной долг)  резиденту  по кредитному договору',
            '06_3' => '06_3 - О  зачете  встречных  однородных  требований,  при  котором обязательства нерезидента  по  возврату  основного  долга  по кредитному  договору  прекращаются  полностью  или  изменяются обязательства (снижается сумма основного долга)',
            '06_4' => '06_4 - О  зачете  встречных  однородных  требований,  при  котором обязательства резидента по возврату основного долга по кредитному договору прекращаются полностью или  изменяются  обязательства (снижается сумма основного долга)',
            '07_3' => '07_3 - Об уступке резидентом требования  к  должнику-нерезиденту по возврату основного долга по кредитному договору иному лицу - нерезиденту',
            '07_4' => '07_4 - Об уступке нерезидентом требования к должнику-резиденту по возврату основного долга по кредитному договору в пользу иного лица - резидента',
            '08_3' => '08_3 - О переводе нерезидентом своего долга по возврату основного долга по кредитному договору на иное лицо-резидента',
            '08_4' => '08_4 - О переводе резидентом своего долга по возврату основного долга по кредитному договору на иное лицо - нерезидента',
            '09_3' => '09_3 - О прекращении обязательств или об изменении (снижении суммы) обязательств нерезидента по кредитному договору в связи с новацией (заменой первоначального обязательства должника-нерезидента другим обязательством),  за  исключением  новации,  осуществляемой посредством передачи должником-нерезидентом резиденту векселя или иных ценных бумаг',
            '09_4' => '09_4 - О прекращении обязательств или об изменении (снижении суммы) обязательств резидента по кредитному договору в связи с новацией (заменой первоначального обязательства должника-резидента другим обязательством),  за  исключением  новации,  осуществляемой посредством передачи должником-резидентом нерезиденту векселя или иных ценных бумаг',
            '10_3' => '10_3 - О прекращении обязательств или об изменении (снижении суммы) обязательств нерезидента, связанных с оплатой товаров (работ, услуг, переданных информации  и  результатов  интеллектуальной деятельности, в том числе исключительных прав на них), с арендой движимого и (или) недвижимого имущества по контракту или  с возвратом нерезидентом основного долга по кредитному договору посредством передачи нерезидентом резиденту векселя или иных ценных бумаг',
            '10_4' => '10_4 - О прекращении обязательств или об изменении (снижении суммы) обязательств резидента, связанных с оплатой товаров (работ, услуг, переданной информации и результатов интеллектуальной деятельности, в том числе исключительных прав на них), с арендой движимого и (или) недвижимого имущества по контракту или с возвратом резидентом основного долга по кредитному договору  посредством  передачи резидентом нерезиденту векселя или иных ценных бумаг',
            '11_3' => '11_3 - О полном или частичном исполнении обязательств  по  возврату основного долга нерезидента по кредитному договору иным лицом - резидентом',
            '11_4' => '11_4 - О полном или частичном исполнении обязательств  по  возврату основного долга резидента по кредитному договору третьим лицом - нерезидентом',
            '12_3' => '12_3 - Об изменении обязательств (увеличении задолженности по основному долгу) резидента перед нерезидентом по кредитному договору',
            '12_4' => '12_4 - Об изменении обязательств (увеличении задолженности по основному долгу) нерезидента перед резидентом по кредитному договору',
            '13_3' => '13_3 - Об иных способах исполнения (изменения, прекращения) обязательств нерезидента перед резидентом по контракту (кредитному договору), включая возврат нерезидентом  ранее  полученных  товаров, за исключением иных кодов видов подтверждающих документов, указанных в настоящей таблице',
            '13_4' => '13_4 - Об иных способах исполнения (изменения, прекращения) обязательств резидента перед нерезидентом по контракту (кредитному договору), включая  возврат  резидентом  ранее  полученных  товаров, за исключением иных кодов видов подтверждающих документов, указанных в настоящей таблице',
            '15_3' => '15_3 - О переданном резидентом в финансовую аренду (лизинг) имуществе',
            '15_4' => '15_4 - О переданном нерезидентом в финансовую аренду (лизинг) имуществе',
            '16_3' => '16_3 - Об удержании банками банковских комиссий за перевод денежных средств, причитающихся резиденту',
        ];
    }

    /**
     * Список кодов вида контракта для паспорта сделки
     * @return array
     */
    public static function fccContractTypeCodes()
    {
        return [
            '1' => '1 - Вывоз товаров с таможенной территории Российской Федерации',
            '2' => '2 - Ввоз товаров на таможенную территорию Российской Федерации',
            '3' => '3 - Выполнение работ, оказание услуг, передача информации и результатов интеллектуальной деятельности,
            в том числе исключительных прав на них, резидентом',
            '4' => '4 - Выполнение работ, оказание услуг, передача информации и результатов интеллектуальной деятельности,
            в том числе исключительных прав на них, нерезидентом',
            '5' => '5 - Предоставление займа резидентом нерезиденту',
            '6' => '6 - Привлечение кредита (займа) резидентом от нерезидента',
            '9' => '9 - Смешанная сделка',
        ];
    }

    /**
     * Список кодов срока привлечения для паспорта сделки
     * @return array
     */
    public static function fccTermInvolvementCodes()
    {
        return [
            '0' => '0 - до 30 дней',
            '1' => '1 - от 31 до 90 дней',
            '2' => '2 - от 91 до 180 дней',
            '3' => '3 - от 181 дня до 1 года',
            '4' => '4 - от 1 года до 3 лет',
            '6' => '6 - до востребования',
            '7' => '7 - от 3 до 5 лет',
            '8' => '8 - от 5 до 10 лет',
        ];
    }

    /**
     * Список кодов ставки ЛИБОР
     * @return array
     */
    public static function fccLiborCodes()
    {
        return [
            'L01' => 'Л01 - месячная ставка ЛИБОР',
            'L02' => 'Л02 - 3-х месячная ставка ЛИБОР',
            'L06' => 'Л06 - 6-ти месячная ставка ЛИБОР',
            'L12' => 'Л12 - 12-ти месячная ставка ЛИБОР'
        ];
    }

    /**
     * Получение банка организации
     * (через первый счет организации)
     * @param DictOrganization $organization
     * @return mixed
     * @throws Exception
     */
    public static function getOrganizationBank(DictOrganization $organization)
    {
        // Получение первого счета организации
        try {
            $account = $organization->accounts[0];
        } catch (\Exception $e) {
            throw new \Exception("Failed to get organization's ($organization->id) accounts");
        }

        // Получение банка по счету организации
        try {
            $bank = $account->bank;
        } catch (\Exception $e) {
            throw new \Exception("Failed to get account's ($account->id) bank");
        }

        return $bank;
    }

    /**
     * Список субьектов РФ
     * @return array
     */
    public static function fccRegions()
    {
        return [
            'Алтайский край', 'Амурская область', 'Архангельская область', 'Астраханская область',
            'Белгородская область', 'Брянская область', 'Владимирская область', 'Волгоградская область',
            'Вологодская область', 'Воронежская область', 'Еврейская автономная область', 'Забайкальский край',
            'Ивановская область', 'Иркутская область', 'Кабардино-Балкарская Республика', 'Калининградская область',
            'Калужская область', 'Камчатский край', 'Карачаево-Черкесская Республика', 'Кемеровская область',
            'Кировская область', 'Костромская область', 'Краснодарский край', 'Красноярский край', 'Курганская область',
            'Курская область', 'Ленинградская область', 'Липецкая область', 'Магаданская область', 'Москва',
            'Московская область', 'Мурманская область', 'Ненецкий автономный округ', 'Нижегородская область',
            'Новгородская область', 'Новосибирская область', 'Омская область', 'Оренбургская область',
            'Орловская область', 'Пензенская область', 'Пермский край', 'Приморский край', 'Псковская область',
            'Республика Адыгея (Адыгея)', 'Республика Алтай', 'Республика Башкортостан', 'Республика Бурятия',
            'Республика Дагестан', 'Республика Ингушетия', 'Республика Калмыкия', 'Республика Карелия',
            'Республика Коми', 'Республика Крым', 'Республика Марий Эл', 'Республика Мордовия', 'Республика Саха (Якутия)',
            'Республика Северная Осетия - Алания', 'Республика Татарстан (Татарстан)', 'Республика Тыва',
            'Республика Хакасия', 'Ростовская область', 'Рязанская область', 'Самарская область',
            'Санкт-Петербург', 'Саратовская область', 'Сахалинская область', 'Севастополь',
            'Свердловская область', 'Смоленская область', 'Ставропольский край', 'Тамбовская область',
            'Тверская область', 'Томская область', 'Тульская область', 'Тюменская область', 'Удмуртская область',
            'Ульяновская область', 'Хабаровский край', 'Ханты-Мансийский автономный округ',
            'Челябинская область', 'Чеченская Республика', 'Чувашская Республика - Чувашия', 'Чукотский автономные округ',
            'Ямало-Ненецкий автономный округ', 'Ярославская область',
        ];
    }

    /**
     * Формирование массива данных из ISO20022 pain.001 для платежного поручения в формате Сбербанк
     * @param $xml
     * @return array
     * @throws Exception
     */
    public static function createSBBOLPayDocRuTypeModelsFromPain001Xml($xml)
    {
        $sbbolPayDocRuTypeModels = [];

        foreach($xml->CstmrCdtTrfInitn->PmtInf->CdtTrfTxInf as $po) {
            $data = [];

            try {
                $documentDate = new \DateTime((string)$po->RmtInf->Strd->RfrdDocInf->RltdDt) ?: null;
                $accDoc = (new AccDocType())
                    ->setUip((string)$po->RmtInf->Strd->CdtrRefInf->Ref)
                    ->setAccDocNo((string)$po->PmtId->EndToEndId)
                    ->setDocDate($documentDate)
                    ->setDocSum((string)$po->Amt->InstdAmt)
                    ->setTransKind('01')
                    ->setPriority(5)
                    ->setPurpose((string)$po->RmtInf->Ustrd);

                if (isset($po->RgltryRptg->Dtls->Cd)) {
                    $accDoc->setCodeVO((string)$po->RgltryRptg->Dtls->Cd);
                }

                $paytKind = 0;
                if (isset($xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->SvcLvl->Cd)) {
                    if((string)$xml->CstmrCdtTrfInitn->PmtInf->PmtTpInf->SvcLvl->Cd == 'URGP') {
                        $paytKind = 'срочно';
                    }
                }
                $accDoc->setPaytKind($paytKind);

                $payerBank = (new BankType())
                    ->setName((string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm)
                    ->setBic((string)$xml->CstmrCdtTrfInitn->PmtInf->DbtrAgt->FinInstnId->ClrSysMmbId->MmbId)
                    ->setCorrespAcc((string)$xml->CstmrCdtTrfInitn->PmtInf->DbtrAgtAcct->Id->Othr->Id);

                $payer = (new PayDocRuClientType())
                    ->setName((string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Nm)
                    ->setInn((string)$xml->CstmrCdtTrfInitn->PmtInf->Dbtr->Id->OrgId->Othr->Id)
                    ->setKpp((string)$po->Tax->Dbtr->TaxTp)
                    ->setPersonalAcc((string)$po->CstmrCdtTrfInitn->PmtInf->DbtrAcct->Id->Othr->Id)
                    ->setBank($payerBank);

                $payeeBank = (new BankType())
                    ->setName((string)$po->CdtrAgt->FinInstnId->Nm)
                    ->setBic((string)$po->CdtrAgt->FinInstnId->ClrSysMmbId->MmbId)
                    ->setCorrespAcc((string)$po->CdtrAgtAcct->Id->Othr->Id);

                $payee = (new ContragentType())
                    ->setName((string)$po->Cdtr->Nm)
                    ->setInn((string)$po->Cdtr->Id->OrgId->Othr->Id)
                    ->setKpp((string)$po->Tax->Cdtr->TaxTp)
                    ->setPersonalAcc((string)$po->CdtrAcct->Id->Othr->Id)
                    ->setBank($payeeBank);

                $departmentalInfo = new BudgetDepartmentalInfoType();
                $departmentalInfo->setOkato((string)$po->Tax->AdmstnZn);

                $isBudget = isset($po->Tax->AdmstnZn);

                if ($isBudget) {
                    $data['BudgetPayment'] = true;

                    $departmentalInfo
                        ->setDocDate(new \DateTime((string)$po->Tax->Dt) ?: null)
                        ->setDocNo((string)$po->Tax->RefNb);

                    if (isset($po->Tax->Rcrd->Ctgy)) {
                        $ctgy = (string) $po->Tax->Rcrd->Ctgy;
                        $departmentalInfo->setPaytReason($ctgy);

                        // Определение налогового периода
                        $taxPeriod = null;
                        if ($ctgy === 0) {
                            $taxPeriod = 0;
                        } elseif (in_array($ctgy, ['ТП', 'ЗД'])) {
                            $year = date('Y', strtotime((string) $po->Tax->Rcrd->Prd->Yr));

                            if (isset($po->Tax->Rcrd->Prd->Tp)) {
                                $tp = (string)$po->Tax->Rcrd->Prd->Tp;

                                if (strpos($tp, 'MM') !== false) {
                                    $month = substr($tp, 2, 2);
                                    $taxPeriod = "МС.{$month}.{$year}";
                                } elseif (strpos($tp, 'QTR') !== false) {
                                    $qtr = substr($tp, 3, 1);
                                    $taxPeriod = "КВ.0{$qtr}.{$year}";
                                } elseif (strpos($tp, 'HLF') !== false) {
                                    $hlf = substr($tp, 3, 1);
                                    $taxPeriod = "ПГ.0{$hlf}.{$year}";
                                }

                            } else {
                                $taxPeriod = "ГД.00.{$year}";
                            }
                        } elseif (in_array($ctgy, ['ТР', 'РС', 'ОТ', 'РТ', 'ПБ', 'ПР'])) {
                            $taxPeriod = date('d.m.Y', strtotime((string) $po->Tax->Rcrd->Prd->Yr));
                        } elseif (in_array($ctgy, ['АП', 'АР'])) {
                            $taxPeriod = 0;
                        } elseif (in_array($ctgy, ['00', 'ДЕ', 'ПО', 'КТ', 'ИД', 'ИП', 'ТУ', 'БД', 'КП'])) {
                            $taxPeriod = (string) $po->Tax->Cdtr->RegnId;
                        } elseif (in_array($ctgy, ['ИН'])) {
                            if (isset($po->Tax->Cdtr->RegnId)) {
                                $taxPeriod = (string) $po->Tax->Cdtr->RegnId;
                            } elseif(isset($po->Tax->Rcrd->Prd->Yr)) {
                                $taxPeriod = date('d.m.Y', strtotime((string) $po->Tax->Rcrd->Prd->Yr));
                            }
                        }

                        $departmentalInfo->setTaxPeriod($taxPeriod);
                    }

                    $departmentalInfo
                        ->setCbc((string)$po->Tax->Rcrd->CtgyDtls)
                        ->setDrawerStatus((string)$po->Tax->Rcrd->CtgyDtls)
                        ->setTaxPaytKind($po->Tax->Rcrd->Tp);
                }

                $payDocRu = (new PayDocRuType())
                    ->setAccDoc($accDoc)
                    ->setPayer($payer)
                    ->setPayee($payee)
                    ->setDepartmentalInfo($departmentalInfo);

                if (isset($po->PmtId->InstrId)) {
                    $payDocRu->setDocExtId((string)$po->PmtId->InstrId);
                } else {
                    $payDocRu->setDocExtId((string)Uuid::generate(false));
                }

            } catch (\Exception $e) {
                throw new Exception($e->getMessage()  . PHP_EOL . $e->getTraceAsString());
            }

            // Получение отправителя и получения документа
            $account = EdmPayerAccount::findOne(['number' => $payer->getPersonalAcc()]);
            if ($account === null) {
                throw new \Exception("Account {$payer->getPersonalAcc()} is not found in database");
            }

            $sender = $account->edmDictOrganization->terminal->terminalId;
            $sbbolCustomerId = SBBOLHelper::getSBBOLCustomerIdByAccountNumber($payer->getPersonalAcc());
            $sbbolSenderName = SBBOLHelper::getSBBOLSenderName($sender);

            $requestDocument = (new Request())
                ->setOrgId($sbbolCustomerId)
                ->setSender($sbbolSenderName)
                ->setRequestId((string)Uuid::generate(false))
                ->setVersion(SBBOLTransportConfig::EXCHANGE_FORMAT_VERSION)
                ->setProtocolVersion(SBBOLTransportConfig::PROTOCOL_VERSION)
                ->setPayDocRu($payDocRu);

            $sbbolPayDocRuTypeModels[] = new SBBOLPayDocRuType(['request' => $requestDocument]);
        }

        return $sbbolPayDocRuTypeModels;
    }

    public static function getAccountFilter($userId, $orgFilter, $valueAsId = false)
    {
        // Список  счетов, доступных текущему пользователю
        $allowedAccounts = EdmPayerAccountUser::getUserAllowAccounts($userId);

        // Получаем список счетов плательщика по организации
        $resultSet = EdmPayerAccount::find()
                    ->where(['id' => $allowedAccounts, 'organizationId' => array_keys($orgFilter)])
                    ->all();

        $accountFilter = [];
        foreach($resultSet as $row) {
            if ($valueAsId) {
                $accountFilter[$row->id] = $row->number;
            } else {
                $accountFilter[$row->number] = $row->number;
            }
        }

        return $accountFilter;
    }

    public static function getOrgFilter()
    {
        // Получаем список организаций доступных пользователю
        $query = Yii::$app->terminalAccess->query(DictOrganization::className());
        $organizations = $query->asArray()->all();
        $orgFilter = [];

        foreach($organizations as $org) {
            $orgFilter[$org['id'] . '_organization'] = $org['name'];
        }

        // Получаем список наименований плательщиков счетов,
        // доступных пользователю
        $accounts = EdmPayerAccount::find()->
                    select('payerName')->distinct()->
                    where(['organizationId' => ArrayHelper::getColumn($organizations, 'id')])->
                    andWhere(['not', ['payerName' => null]])->
                    andWhere(['not', ['payerName' => '']])->
                    asArray()->all();

        foreach($accounts as $account) {
            $orgFilter[$account['payerName'] . '_payerName'] = $account['payerName'];
        }

        return $orgFilter;
    }

    public static function getBankFilter($optionId = 'bik', $optionValue = 'name')
    {
        $query = DictBank::find()
                ->select('bik, name')
                ->where(['not', ['terminalId' => 'null']]);
        $banks = $query->asArray()->all();

        return ArrayHelper::map($banks, $optionId, $optionValue);
    }

    /**
     * @param User $user
     * @return DictBank[]
     */
    public static function getBanksAvailableToUser(User $user): array
    {
        $accountsIds = EdmPayerAccountUser::getUserAllowAccounts($user->id);
        $bikQuery = EdmPayerAccount::find()->where(['in', 'id', $accountsIds])->select('bankBik');

        return DictBank::find()
            ->where(['in', 'bik', $bikQuery])
            ->andWhere(['not', ['terminalId' => 'null']])
            ->all();
    }

    public static function isBankAvailableToUser(string $bik, User $user): bool
    {
        $banks = self::getBanksAvailableToUser($user);
        foreach ($banks as $bank) {
            if ($bank->bik === $bik) {
                return true;
            }
        }
        return false;
    }

    public static function getAvailableBanksByOrganization(User $user): array
    {
        $accountsIds = EdmPayerAccountUser::getUserAllowAccounts($user->id);
        $accounts = EdmPayerAccount::find()->where(['in', 'id', $accountsIds])->all();
        return array_reduce(
            $accounts,
            function (array $carry, EdmPayerAccount $account): array {
                $carry[$account->organizationId][$account->bankBik] = $account->bank->name;
                return $carry;
            },
            []
        );
    }

    /**
     * Проверяет наличие дубликатов ПП по счету, дате, номеру и сумме.
     * @param type $typeModel одна модель или массив моделей
     * @return массив дубликатов, если найдены дубликаты со статусом, отличным от "отклонено", или false
     */
    public static function checkPaymentOrderDuplicate($typeModel)
    {
        $testModels = [];
        if (is_array($typeModel)) {
            $dates = [];
            $numbers = [];
            $sums = [];
            $payerAccounts = [];

            foreach($typeModel as $model) {
                $date = date('Y-m-d', strtotime($model->date));
                $dates[$date] = true;
                $numbers[$model->number] = true;
                $sum = static::truncatePaymentOrderSum($model->sum);
                $sums[$sum] = true;
                $payerAccounts[$model->payerAccount] = true;
                $uniqueId = static::getPaymentOrderUniqueId($model);
                $testModels[$uniqueId] = $model;
            }

            $dates = array_keys($dates);
            $numbers = array_keys($numbers);
            $sums = array_keys($sums);
            $payerAccounts = array_keys($payerAccounts);
        } else {
            $dates = date('Y-m-d', strtotime($typeModel->date));
            $numbers = $typeModel->number;
            $sums = static::truncatePaymentOrderSum($typeModel->sum);
            $payerAccounts = $typeModel->payerAccount;
            $uniqueId = static::getPaymentOrderUniqueId($typeModel);
            $testModels[$uniqueId] = $typeModel;
        }

        $query = PaymentRegisterPaymentOrder::find()->where([
            'and',
            ['!=', 'status', Document::STATUS_DELETED],
            ['payerAccount' => $payerAccounts],
            ['date' => $dates],
            ['number' => $numbers],
            ['sum' => $sums]
        ]);

        $models = $query->all();
        $duplicates = [];
        foreach($models as $model) {
            $uniqueId = static::getPaymentOrderUniqueId($model);
            if (!array_key_exists($uniqueId, $testModels)) {
                continue;
            }

            if ($model->registerId) {
                $document = Document::findOne($model->registerId);
                if ($document !== null) {
                    $isSuccessfulDocument = !in_array($document->status, Document::getErrorStatus())
                        && $model->businessStatus !== PaymentRegisterDocumentExt::STATUS_REJECTED;
                    if (!$isSuccessfulDocument) {
                        continue;
                    }
                }
            }

            $duplicates[$uniqueId] = $testModels[$uniqueId];
        }

        return count($duplicates) > 0 ? $duplicates : false;
    }

    private static function createSBBOLPayDocRuFromPaymentOrder(PaymentOrderType $typeModel)
    {
        $sender = $typeModel->sender;

        $sbbolCustomerId = SBBOLHelper::getSBBOLCustomerIdByAccountNumber($typeModel->payerCheckingAccount);
        $sbbolSenderName = SBBOLHelper::getSBBOLSenderName($sender);
        $sbbolPayDocRu = SBBOLPayDocRuType::createFromPaymentOrder($typeModel, $sbbolCustomerId, $sbbolSenderName);

        if ($sbbolPayDocRu->errors) {
            throw new Exception($sbbolPayDocRu->getErrorsSummary());
        }

        return $sbbolPayDocRu;
    }

    private static function createSbbol2PayDocRuFromPaymentOrder(PaymentOrderType $typeModel)
    {
        $sender = $typeModel->sender;

        $sbbol2CustomerId = Sbbol2Helper::getSbbol2CustomerIdByAccountNumber($typeModel->payerCheckingAccount);
        $sbbol2SenderName = Sbbol2Helper::getSbbol2SenderName($sender);
        $sbbol2PayDocRu = Sbbol2PayDocRuType::createFromPaymentOrder($typeModel, $sbbol2CustomerId, $sbbol2SenderName);

        if ($sbbol2PayDocRu->errors) {
            throw new Exception($sbbol2PayDocRu->getErrorsSummary());
        }

        return $sbbol2PayDocRu;
    }

    /**
     * Получение юридического типа участника по номеру счёта
     * @param $number
     * @return string
     */
    public static function checkParticipantTypeByAccount($number)
    {
        // Переданная строка является счетом
        if (strlen($number) != 20) {
           return null;
        }

        // Переданная строка не содержит буквенных и специальных символов
        preg_match_all('/(\D)/', $number, $matches);

        if (count($matches[1]) > 0) {
            return null;
        }

        // Если первые цифры счета 40802, 40803, 40810, 40813, 40817, 40820, 423,
        // 426, 454, 455, 457, 45814, 45815, 45817, 45914, 45915, 45917
        // то плательщик/получатель не юридическое лицо.
        $firstFiveDigitsValues = [
            '40802', '40803', '40810', '40813', '40817', '40820',
        ];

        $firstThreeDigitsValues = [
            '423', '426',
        ];

        $firstFiveDigits = substr($number, 0, 5);
        $firstThreeDigits = substr($number, 0, 3);

        $isIndividual = in_array($firstFiveDigits, $firstFiveDigitsValues) ||
                in_array($firstThreeDigits, $firstThreeDigitsValues);

        return $isIndividual ? DictOrganization::TYPE_INDIVIDUAL : DictOrganization::TYPE_ENTITY;
    }

    public static function getPaymentOrderUniqueId($model)
    {
        return $model->payerAccount . '-' . date('Y-m-d', strtotime($model->date))
            . '-' . $model->number . '-' . static::truncatePaymentOrderSum($model->sum);
    }

    public static function truncatePaymentOrderSum($sum)
    {
        if (substr($sum, -3) === '.00') {
            $sum = (int) $sum;
        }

        return $sum;
    }

    private static function saveISO20022PaymentOrdersUuids(Pain001RubType $typeModel, array $paymentOrdersIds): void
    {
        $uuidByPaymentOrderNumber = static::getISO20022PaymentOrdersUuids($typeModel);

        foreach ($uuidByPaymentOrderNumber as $paymentOrderNumber => $uuid) {
            PaymentRegisterPaymentOrder::updateAll(
                ['uuid' => $uuid],
                [
                    'and',
                    ['number' => $paymentOrderNumber],
                    ['in', 'id', $paymentOrdersIds],
                ]
            );
        }
    }

    private static function getISO20022PaymentOrdersUuids(Pain001RubType $typeModel): array
    {
        /** @var SimpleXMLElement $xml */
        $xml = $typeModel->getRawXml();
        $paymentIdElements = $xml->xpath("//*[local-name()='CdtTrfTxInf']/*[local-name()='PmtId']");

        $uuidByPaymentOrderNumber = array_reduce(
            $paymentIdElements,
            function (array $carry, SimpleXMLElement $paymentIdElement) {
                $instrId = (string)$paymentIdElement->InstrId ?: null;
                $endToEndId = (string)$paymentIdElement->EndToEndId ?: null;
                if ($instrId && $endToEndId) {
                    $carry[$endToEndId] = $instrId;
                }
                return $carry;
            },
            []
        );

        return $uuidByPaymentOrderNumber;
    }

    public static function getAccountList($currency = null, $exceptCurrency = null, $organizationId = null)
    {
        $out = [];

        // Получаем список организаций, доступных пользователю
        $query = Yii::$app->terminalAccess->query(DictOrganization::className());

        // С учетом отбора по организации
        $query->andFilterWhere(['id' => $organizationId]);

        $organizations = $query->select('id')->asArray()->all();

        // Приводим список организаций пользователя к массиву со списком id
        $organizations = ArrayHelper::getColumn($organizations, 'id');

        // Получаем список счетов плательщика по организации
        $query = EdmPayerAccount::find()->where(['organizationId' => $organizations]);

        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, 'id');


        // С учетом отбора по БИК банка
        $query->andFilterWhere(['bankBik' => $bankBik]);

        // Делаем отбор по валюте, если она присутствует
        if (!empty($currency)) {
            // Для валюты рубли включаем как RUB, так и RUR значения
            if ($currency == 1 || $currency == 2) {
                $currency = [1, 2];
            }

            $query->andWhere(['currencyId' => $currency]);
        }

        // Если есть исключающая валюта, то убираем её из результатов запроса
        if (!empty($exceptCurrency)) {
            // Для валюты рубли исключаем как RUB, так и RUR значения
            if ($exceptCurrency == 1 || $exceptCurrency == 2) {
                $query->andWhere(['<>', 'currencyId', 1]);
                $query->andWhere(['<>', 'currencyId', 2]);
            } else {
                $query->andWhere(['<>', 'currencyId', $exceptCurrency]);
            }
        }

        $items = $query->all();

        return $items;

        $out = ['results' => []];
        foreach ($items as $i => $item) {
            $out['results'][$i] = array_merge(
                $item->getAttributes(),
                [
                    'bank' => $item->bank->getAttributes(),
                    'contractor' => $item->edmDictOrganization->getAttributes(),
                    'currencyInfo' => $item->edmDictCurrencies->getAttributes()
                ]
            );
            /**
             * @todo по сути костыль для работы Select2, не удалось результирующее значение через виджет переопределить
             */
            $out['results'][$i]['id'] = $out['results'][$i]['number'];
        }
        Yii::info(var_export($out, true));

        return $out;
    }

}

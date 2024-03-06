<?php

namespace addons\edm\models\LoanAgreementRegistrationRequest;

use addons\edm\EdmModule;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\DictVTBBankBranch;
use addons\edm\models\DictVTBPaymentScheduleReason;
use addons\edm\models\DictVTBRepaymentPeriod;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\AttachedFileSession;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\NonResident;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\PaymentScheduleItem;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\Receipt;
use addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm\Tranche;
use addons\edm\models\VTBContractRequest\VTBContractRequestExt;
use addons\edm\models\VTBCredReg\VTBCredRegType;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\helpers\Uuid;
use common\helpers\vtb\VTBHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use common\models\User;
use common\models\UserTerminal;
use common\models\vtbxml\documents\BSDocumentAttachment;
use common\models\vtbxml\documents\CredReg;
use common\models\vtbxml\documents\CredRegCredReceiptInfo;
use common\models\vtbxml\documents\CredRegCredTranche;
use common\models\vtbxml\documents\CredRegNonresidentInfo;
use common\models\vtbxml\documents\CredRegPaymentReturn;
use common\models\vtbxml\service\SignInfo;
use common\modules\transport\helpers\DocumentTransportHelper;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 *
 * @property DictBank[] $availableReceiverBanks
 * @property DictOrganization[] $availableOrganizations
 * @property string $loanAgreementCurrencyDescription
 * @property string $repaymentPeriodName
 * @property string $loanAgreementCurrencyName
 * @property string $fixedInterestRateLiborCodeName
 * @property string $paymentScheduleReasonName
 * @property DictBank $receiverBank
 * @property DictOrganization $organization
 */
class LoanAgreementRegistrationRequestForm extends Model
{
    /** @var User */
    public $user;

    public $documentNumber;
    public $documentDate;
    public $receiverBankBik;
    public $loanAgreementUniqueNumber1;
    public $loanAgreementUniqueNumber2;
    public $loanAgreementUniqueNumber3;
    public $loanAgreementUniqueNumber4;
    public $loanAgreementUniqueNumber5;
    public $loanAgreementUniqueNumberDate;
    public $organizationId;
    public $contactPerson;
    public $contactPhone;
    public $previousLoanAgreementUniqueNumber;
    public $loanAgreementNumber;
    public $noLoanAgreementNumber;
    public $loanAgreementDate;
    public $loanAgreementEndDate;
    public $loanAgreementAmount;
    public $noLoanAgreementAmount;
    public $loanAgreementCurrencyCode;
    public $foreignAccountsTransferAmount;
    public $currencyIncomeRepaymentAmount;
    public $repaymentPeriodCode;
    public $fixedInterestRatePercent;
    public $fixedInterestRateLiborCode;
    public $otherPercentRateCalculationMethod;
    public $increaseRatePercent;
    public $otherPayments;
    public $mainDebtAmount;
    public $paymentScheduleReason;
    public $isDirectInvesting;
    public $depositAmount;

    /** @var NonResident[] */
    public $nonResidents = [];

    /** @var Tranche[] */
    public $tranches = [];

    /** @var PaymentScheduleItem[] */
    public $paymentScheduleItems = [];

    /** @var Receipt[] */
    public $receipts = [];

    /** @var AttachedFileSession[] */
    public $attachedFiles = [];

    public $nonResidentsJson = '[]';
    public $tranchesJson = '[]';
    public $paymentScheduleItemsJson = '[]';
    public $receiptsJson = '[]';
    public $attachedFilesJson = '[]';

    public function rules()
    {
        return [
            [['documentNumber', 'nonResidentsJson', 'tranchesJson', 'paymentScheduleItemsJson', 'receiptsJson', 'attachedFilesJson'], 'string'],
            [
                [
                    'loanAgreementAmount',
                    'depositAmount',
                    'mainDebtAmount',
                    'currencyIncomeRepaymentAmount',
                    'foreignAccountsTransferAmount',
                    'increaseRatePercent',
                    'fixedInterestRatePercent',
                ],
                'default',
                'value' => null,
            ],
            [['documentDate'], 'date', 'format' => 'dd.MM.yyyy'],
            [
                [
                    'documentNumber',
                    'documentDate',
                    'receiverBankBik',
                    'loanAgreementUniqueNumber1',
                    'loanAgreementUniqueNumber2',
                    'loanAgreementUniqueNumber3',
                    'loanAgreementUniqueNumber4',
                    'loanAgreementUniqueNumber5',
                    'loanAgreementUniqueNumberDate',
                    'organizationId',
                    'contactPerson',
                    'contactPhone',
                    'previousLoanAgreementUniqueNumber',
                    'loanAgreementNumber',
                    'noLoanAgreementNumber',
                    'loanAgreementDate',
                    'loanAgreementEndDate',
                    'loanAgreementAmount',
                    'noLoanAgreementAmount',
                    'loanAgreementCurrencyCode',
                    'foreignAccountsTransferAmount',
                    'currencyIncomeRepaymentAmount',
                    'repaymentPeriodCode',
                    'fixedInterestRatePercent',
                    'fixedInterestRateLiborCode',
                    'otherPercentRateCalculationMethod',
                    'increaseRatePercent',
                    'otherPayments',
                    'mainDebtAmount',
                    'paymentScheduleReason',
                    'paymentScheduleReasonName',
                    'isDirectInvesting',
                    'depositAmount',
                    'nonResidentsJson',
                    'tranchesJson',
                    'paymentScheduleItemsJson',
                    'receiptsJson',
                    'attachedFilesJson',
                ],
                'safe'
            ],
            [
                [
                    'documentNumber',
                    'documentDate',
                    'receiverBankBik',
                    'organizationId',
                    'loanAgreementUniqueNumber4',
                    'noLoanAgreementNumber',
                    'loanAgreementCurrencyCode',
                    'noLoanAgreementAmount',
                ],
                'required'
            ],
            ['organizationId', 'validateOrganization'],
            ['receiverBankBik', 'validateReceiverBankBik'],
        ];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);

        $this->nonResidents = NonResident::createListFromJson($this->nonResidentsJson);
        $this->tranches = Tranche::createListFromJson($this->tranchesJson);
        $this->paymentScheduleItems = PaymentScheduleItem::createListFromJson($this->paymentScheduleItemsJson);
        $this->receipts = Receipt::createListFromJson($this->receiptsJson);
        $this->attachedFiles = AttachedFileSession::createListFromJson($this->attachedFilesJson);

        return $result;
    }

    public function attributeLabels()
    {
        return [
            'documentNumber'                    => Yii::t('edm', 'Document number'),
            'documentDate'                      => Yii::t('edm', 'Document date'),
            'receiverBankBik'                   => Yii::t('edm', 'Authorized bank'),
            'loanAgreementUniqueNumber1'        => Yii::t('edm', 'Loan agreement unique number part {n}', ['n' => 1]),
            'loanAgreementUniqueNumber2'        => Yii::t('edm', 'Loan agreement unique number part {n}', ['n' => 2]),
            'loanAgreementUniqueNumber3'        => Yii::t('edm', 'Loan agreement unique number part {n}', ['n' => 3]),
            'loanAgreementUniqueNumber4'        => Yii::t('edm', 'Loan agreement unique number part {n}', ['n' => 4]),
            'loanAgreementUniqueNumber5'        => Yii::t('edm', 'Loan agreement unique number part {n}', ['n' => 5]),
            'loanAgreementUniqueNumberDate'     => Yii::t('edm', 'Loan agreement unique number date'),
            'organizationId'                    => Yii::t('edm', 'Organization'),
            'contactPerson'                     => Yii::t('edm', 'Contact person'),
            'contactPhone'                      => Yii::t('edm', 'Contact phone number'),
            'previousLoanAgreementUniqueNumber' => Yii::t('edm', 'Previous loan agreement unique number'),
            'loanAgreementNumber'               => Yii::t('edm', 'Loan agreement number'),
            'loanAgreementDate'                 => Yii::t('edm', 'Loan agreement date'),
            'loanAgreementEndDate'              => Yii::t('edm', 'Obligations end date'),
            'loanAgreementAmount'               => Yii::t('edm', 'Loan agreement amount'),
            'loanAgreementCurrencyCode'         => Yii::t('edm', 'Loan agreement currency'),
            'foreignAccountsTransferAmount'     => Yii::t('edm', 'Amount to transfer to foreign accounts'),
            'currencyIncomeRepaymentAmount'     => Yii::t('edm', 'Amount to repay by currency income'),
            'repaymentPeriodCode'               => Yii::t('edm', 'Repayment period code'),
            'repaymentPeriodName'               => Yii::t('edm', 'Repayment period'),
            'fixedInterestRatePercent'          => Yii::t('edm', 'Fixed interest rate percent'),
            'fixedInterestRateLiborCode'        => Yii::t('edm', 'Fixed interest rate LIBOR code'),
            'fixedInterestRateLiborCodeName'    => Yii::t('edm', 'Fixed interest rate LIBOR code'),
            'otherPercentRateCalculationMethod' => Yii::t('edm', 'Other percent rate calculation method'),
            'increaseRatePercent'               => Yii::t('edm', 'Increase rate percent'),
            'otherPayments'                     => Yii::t('edm', 'Other agreement payments'),
            'mainDebtAmount'                    => Yii::t('edm', 'Main debt amount'),
            'paymentScheduleReason'             => Yii::t('edm', 'Payment schedule reason'),
            'paymentScheduleReasonName'         => Yii::t('edm', 'Payment schedule reason'),
            'isDirectInvesting'                 => Yii::t('edm', 'Has direct investing'),
            'depositAmount'                     => Yii::t('edm', 'Deposit amount'),
            'nonResidents'                      => Yii::t('edm', 'Non-residents info'),
            'tranches'                          => Yii::t('edm', 'Attracted tranches'),
            'paymentScheduleItems'              => Yii::t('edm', 'Payments schedule'),
            'receipts'                          => Yii::t('edm', 'Loan receipts'),
            'attachedFiles'                     => Yii::t('edm', 'Attached files'),
        ];
    }

    /**
     * @return DictOrganization[]
     */
    public function getAvailableOrganizations()
    {
        if ($this->user === null) {
            return [];
        }

        $terminalsIds = $this->user->disableTerminalSelect
            ? UserTerminal::getUserTerminalIndexes($this->user->id)
            : [$this->user->terminalId => $this->user->terminalId];

        $vtbCustomersTerminalsIds = TerminalRemoteId::find()
            ->where(['terminalReceiver' => VTBHelper::getGatewayTerminalAddress()])
            ->andWhere(['terminalId' => $terminalsIds])
            ->select('terminalId')
            ->column();
        
        return DictOrganization::findAll(['terminalId' => $vtbCustomersTerminalsIds]);
    }

    /**
     * @return DictBank[]
     */
    public function getAvailableReceiverBanks()
    {
        if ($this->user === null) {
            return [];
        }

        $accountsIds = EdmPayerAccountUser::getUserAllowAccounts($this->user->id);
        $bikQuery = EdmPayerAccount::find()->where(['in', 'id', $accountsIds])->select('bankBik');
        $vtbBikQuery = DictVTBBankBranch::find()->select('bik');
        
        return DictBank::find()
            ->where(['in', 'bik', $bikQuery])
            ->andWhere(['in', 'bik', $vtbBikQuery])
            ->andWhere(['not', ['terminalId' => null]])
            ->all();
    }

    public function createDocument()
    {
        $senderOrganization = DictOrganization::findOne($this->organizationId);
        $receiverBank = DictBank::findOne(['bik' => $this->receiverBankBik]);

        $typeModel = $this->createTypeModel($senderOrganization);
        $docAttributes = $this->createDocumentAttributes($senderOrganization, $receiverBank);
        $extModelAttributes = $this->createExtModelAttributes($typeModel);

        $context = DocumentHelper::createDocumentContext($typeModel, $docAttributes, $extModelAttributes);
        if (!$context) {
            throw new \Exception(\Yii::t('app', 'Save document error'));
        }

        $document = $context['document'];
        DocumentTransportHelper::processDocument($document, true);

        return $document;
    }

    public function updateDocument(Document $document)
    {
        $senderOrganization = DictOrganization::findOne($this->organizationId);
        $receiverBank = DictBank::findOne(['bik' => $this->receiverBankBik]);

        $typeModel = $this->createTypeModel($senderOrganization);
        $docAttributes = $this->createDocumentAttributes($senderOrganization, $receiverBank);
        $extModelAttributes = $this->createExtModelAttributes($typeModel);

        $cyxDocument = CyberXmlDocument::read($document->actualStoredFileId);
        $cyxDocument->setTypeModel($typeModel);
        $fileInfo = $cyxDocument->getStoredFile()->updateData($cyxDocument->saveXML());
        if ($fileInfo === null) {
            throw new \Exception("Failed to update stored file for document {$document->id}");
        }

        $document->setAttributes($docAttributes, false);
        $documentIsUpdated = $document->save(false);
        if (!$documentIsUpdated) {
            throw new \Exception("Failed to update document {$document->id}");
        }

        $extModel = $document->extModel;
        $extModel->setAttributes($extModelAttributes, false);
        $extModelIsUpdated = $extModel->save(false);
        if (!$extModelIsUpdated) {
            throw new \Exception("Failed to update ext model for document {$document->id}");
        }

        /** @var EdmModule $module */
        $module = Yii::$app->getModule('edm');
        $module->processDocument($document, $document->sender, $document->receiver);
        DocumentTransportHelper::processDocument($document, true);
    }

    private function createDocumentAttributes(DictOrganization $senderOrganization, DictBank $receiverBank)
    {
        /** @var Terminal $senderTerminal */
        $senderTerminal = $senderOrganization->terminal;
        $receiverTerminalAddress = $receiverBank->terminalId;

        return [
            'sender'             => $senderTerminal->terminalId,
            'receiver'           => $receiverTerminalAddress,
            'type'               => VTBCredRegType::TYPE,
            'direction'          => Document::DIRECTION_OUT,
            'origin'             => Document::ORIGIN_WEB,
            'terminalId'         => $senderTerminal->id,
            'status'             => Document::STATUS_CREATING,
            'signaturesRequired' => Yii::$app->getModule('edm')->getSignaturesNumber($senderTerminal->terminalId),
            'signaturesCount'    => 0,
        ];
    }

    private function createTypeModel(DictOrganization $organization)
    {
        $vtbDocumentVersion = 5;
        $vtbCustomerId = VTBHelper::getVTBCustomerId($organization->terminal->terminalId);
        $vtbBankBranch = DictVTBBankBranch::findOne(['bik' => $this->receiverBankBik]);

        $vtbDocument = new CredReg([
            'CUSTID'               => $vtbCustomerId,
            'KBOPID'               => $vtbBankBranch->branchId,
            'DOCUMENTNUMBER'       => $this->documentNumber,
            'DOCUMENTDATE'         => \DateTime::createFromFormat('!d.m.Y', $this->documentDate) ?: null,
            'SENDEROFFICIALS'      => $this->contactPerson,
            'CUSTOMERBANKBIC'      => $this->receiverBankBik,
            'CUSTOMERBANKNAME'     => $vtbBankBranch->name,
            'CUSTOMERPROPERTYTYPE' => $organization->propertyTypeCode,
            'CUSTOMERNAME'         => $organization->name,
            'CUSTOMEROGRN'         => $organization->ogrn,
            'CUSTOMERINN'          => $organization->inn,
            'CUSTOMERKPP'          => $organization->kpp,
            'LAWSTATE'             => $organization->state,
            'LAWDISTRICT'          => $organization->district,
            'LAWCITY'              => $organization->city,
            'LAWPLACE'             => $organization->city,
            'LAWSTREET'            => $organization->street,
            'LAWBUILDING'          => $organization->buildingNumber,
            'LAWBLOCK'             => $organization->building,
            'LAWOFFICE'            => $organization->apartment,
            'PHONEOFFICIALS'       => $this->contactPhone,
            'DPDATE'               => \DateTime::createFromFormat('!d.m.Y', $this->loanAgreementUniqueNumberDate) ?: null,
            'DPNUM1'               => $this->loanAgreementUniqueNumber1,
            'DPNUM2'               => $this->loanAgreementUniqueNumber2,
            'DPNUM3'               => $this->loanAgreementUniqueNumber3,
            'DPNUM4'               => $this->loanAgreementUniqueNumber4,
            'DPNUM5'               => $this->loanAgreementUniqueNumber5,
            'CONNUMBER'            => $this->noLoanAgreementNumber ? null : $this->loanAgreementNumber,
            'ISCONNUMBER'          => $this->noLoanAgreementNumber ? 0 : 1,
            'CONDATE'              => \DateTime::createFromFormat('!d.m.Y', $this->loanAgreementDate) ?: null,
            'CONCURRCODE'          => $this->loanAgreementCurrencyCode,
            'CONAMOUNT'            => $this->noLoanAgreementAmount ? null : $this->loanAgreementAmount,
            'ISCONAMOUNT'          => $this->noLoanAgreementAmount ? 0 : 1,
            'CONENDDATE'           => \DateTime::createFromFormat('!d.m.Y', $this->loanAgreementEndDate) ?: null,
            'CONAMOUNTTRANSFER'    => $this->foreignAccountsTransferAmount,
            'CREDAMOUNTCURR'       => $this->currencyIncomeRepaymentAmount,
            'CREDPAYPERIODCODE'    => $this->repaymentPeriodCode,
            'DPNUMBEROTHERBANK'    => $this->previousLoanAgreementUniqueNumber,
            'FIXRATEPERCENT'       => $this->fixedInterestRatePercent,
            'LIBORRATE'            => $this->fixedInterestRateLiborCode,
            'OTHERRATEMETHOD'      => $this->otherPercentRateCalculationMethod,
            'INCREASERATEPERCENT'  => $this->increaseRatePercent,
            'DEBTSAMOUNT'          => $this->mainDebtAmount,
            'ISDIRECTINVESTING'    => $this->isDirectInvesting,
            'DEPOSITAMOUNT'        => $this->depositAmount,
            'BANKVKFULLNAME'       => $vtbBankBranch->fullName,
            'OTHERPAYMENTS'        => $this->otherPayments,
            'FLAGPAYMENTRETURN'    => $this->paymentScheduleReason,
            'DOCATTACHMENT'        => $this->createVtbDocumentAttachments(),
            'DATEOGRN'             => \DateTime::createFromFormat('!d.m.Y', $organization->dateEgrul) ?: null,
        ]);

        $vtbDocument->NONRESIDENTINFO = array_map(
            function (NonResident $nonResident) {
                return new CredRegNonresidentInfo([
                    'NAME' => $nonResident->name,
                    'COUNTRY' => $nonResident->countryName,
                    'COUNTRYCODE' => $nonResident->countryCode,
                ]);
            },
            $this->nonResidents
        );

        $vtbDocument->CREDTRANCHEBLOB = array_map(
            function (Tranche $tranche) {
                return new CredRegCredTranche([
                    'TRANCHEAMOUNT' => $tranche->amount,
                    'RECEIPTDATE' => \DateTime::createFromFormat('!d.m.Y', $tranche->receiptDate) ?: null,
                    'TRANCHEPAYMENTPERIODCODE' => $tranche->paymentPeriodCode
                ]);
            },
            $this->tranches
        );

        $vtbDocument->CREDRECEIPTINFOBLOB = array_map(
            function (Receipt $receipt) {
                return new CredRegCredReceiptInfo([
                    'BENEFICIAR' => $receipt->beneficiaryName,
                    'BENEFICIARCOUNTRYCODE' => $receipt->beneficiaryCountryCode,
                    'CREDAMOUNT' => $receipt->amount,
                    'CREDPERCENT' => $receipt->shareOfLoanAmount,
                ]);
            },
            $this->receipts
        );

        $vtbDocument->PAYMENTRETURNBLOB = array_map(
            function (PaymentScheduleItem $item) {
                return new CredRegPaymentReturn([
                    'PAYMENTDEBTAMOUNT' => $item->debtAmount,
                    'PAYMENTDEBTDATE' => \DateTime::createFromFormat('!d.m.Y', $item->debtDate) ?: null,
                    'PAYMENTPERCENTAMOUNT' => $item->interestAmount,
                    'PAYMENTPERCENTDATE' => \DateTime::createFromFormat('!d.m.Y', $item->interestDate) ?: null,
                    'SPECIALCONDITIONS' => $item->specialConditions,
                ]);
            },
            $this->paymentScheduleItems
        );

        return new VTBCredRegType([
            'document' => $vtbDocument,
            'customerId' => $vtbCustomerId,
            'documentVersion' => $vtbDocumentVersion,
            'signatureInfo'  => new SignInfo([
                'signedFields' => $vtbDocument->getSignedFieldsIds($vtbDocumentVersion)
            ])
        ]);
    }

    public function validateOrganization($attribute, $params = [])
    {
        $availableOrganizationsIds = ArrayHelper::getColumn($this->getAvailableOrganizations(), 'id');

        if (!in_array($this->organizationId, $availableOrganizationsIds)) {
            $this->addError($attribute, Yii::t('edm', 'You cannot create document on behalf of selected organization'));
        }
    }

    public function validateReceiverBankBik($attribute, $params = [])
    {
        $availableBanksBiks = ArrayHelper::getColumn($this->getAvailableReceiverBanks(), 'bik');

        if (!in_array($this->receiverBankBik, $availableBanksBiks)) {
            $this->addError($attribute, Yii::t('edm', 'You cannot create document addressed to selected bank'));
        }
    }

    private function createExtModelAttributes(VTBCredRegType $typeModel)
    {
        $extModel = new VTBContractRequestExt();
        $extModel->loadContentModel($typeModel);
        return array_merge(
            $extModel->dirtyAttributes,
            ['contractsAttributes' => $extModel->contractsAttributes]
        );
    }

    public static function createFromDocument(Document $document, User $user, $extractAttachedFiles = false)
    {
        /** @var VTBCredRegType $typeModel */
        $typeModel = CyberXmlDocument::getTypeModel($document->actualStoredFileId);

        /** @var CredReg $vtbDocument */
        $vtbDocument = $typeModel->document;

        $organization = DictOrganization::findOne(['terminalId' => $document->terminalId]);

        $form = new LoanAgreementRegistrationRequestForm([
            'user' => $user,
            'organizationId' => $organization ? $organization->id : null,
            'documentNumber' => $vtbDocument->DOCUMENTNUMBER,
            'documentDate' => $vtbDocument->DOCUMENTDATE ? $vtbDocument->DOCUMENTDATE->format('d.m.Y') : null,
            'contactPerson'    => $vtbDocument->SENDEROFFICIALS,
            'receiverBankBik'    => $vtbDocument->CUSTOMERBANKBIC,
            'contactPhone'    => $vtbDocument->PHONEOFFICIALS,
            'loanAgreementUniqueNumber1' => $vtbDocument->DPNUM1,
            'loanAgreementUniqueNumber2' => $vtbDocument->DPNUM2,
            'loanAgreementUniqueNumber3' => $vtbDocument->DPNUM3,
            'loanAgreementUniqueNumber4' => $vtbDocument->DPNUM4,
            'loanAgreementUniqueNumber5' => $vtbDocument->DPNUM5,
            'loanAgreementUniqueNumberDate' => $vtbDocument->DPDATE ? $vtbDocument->DPDATE->format('d.m.Y') : null,
            'loanAgreementNumber' => $vtbDocument->CONNUMBER,
            'noLoanAgreementNumber'=> $vtbDocument->ISCONNUMBER ? 0 : 1,
            'loanAgreementDate' => $vtbDocument->CONDATE ? $vtbDocument->CONDATE->format('d.m.Y') : null,
            'loanAgreementCurrencyCode' => $vtbDocument->CONCURRCODE,
            'noLoanAgreementAmount' => $vtbDocument->ISCONAMOUNT ? 0 : 1,
            'loanAgreementAmount' => $vtbDocument->CONAMOUNT,
            'loanAgreementEndDate' => $vtbDocument->CONENDDATE ? $vtbDocument->CONENDDATE->format('d.m.Y') : null,
            'foreignAccountsTransferAmount' => $vtbDocument->CONAMOUNTTRANSFER,
            'currencyIncomeRepaymentAmount' => $vtbDocument->CREDAMOUNTCURR,
            'repaymentPeriodCode' => $vtbDocument->CREDPAYPERIODCODE,
            'previousLoanAgreementUniqueNumber' => $vtbDocument->DPNUMBEROTHERBANK,
            'fixedInterestRatePercent' => $vtbDocument->FIXRATEPERCENT,
            'fixedInterestRateLiborCode' => $vtbDocument->LIBORRATE,
            'otherPercentRateCalculationMethod' => $vtbDocument->OTHERRATEMETHOD,
            'increaseRatePercent' => $vtbDocument->INCREASERATEPERCENT,
            'mainDebtAmount' => $vtbDocument->DEBTSAMOUNT,
            'isDirectInvesting' => $vtbDocument->ISDIRECTINVESTING,
            'depositAmount' => $vtbDocument->DEPOSITAMOUNT,
            'otherPayments' => $vtbDocument->OTHERPAYMENTS,
            'paymentScheduleReason' => $vtbDocument->FLAGPAYMENTRETURN,
            'nonResidents' => array_map(
                function ($index) use ($vtbDocument) {
                    $nonResident = $vtbDocument->NONRESIDENTINFO[$index];
                    return new NonResident([
                        'id' => $index + 1,
                        'name' => $nonResident->NAME,
                        'countryCode' => $nonResident->COUNTRYCODE,
                    ]);
                },
                array_keys($vtbDocument->NONRESIDENTINFO)
            ),
            'tranches' => array_map(
                function ($index) use ($vtbDocument) {
                    $tranche = $vtbDocument->CREDTRANCHEBLOB[$index];
                    return new Tranche([
                        'id' => $index + 1,
                        'paymentPeriodCode' => $tranche->TRANCHEPAYMENTPERIODCODE,
                        'amount' => $tranche->TRANCHEAMOUNT,
                        'receiptDate' => $tranche->RECEIPTDATE ? $tranche->RECEIPTDATE->format('d.m.Y') : null,
                    ]);
                },
                array_keys($vtbDocument->CREDTRANCHEBLOB)
            ),
            'paymentScheduleItems' => array_map(
                function ($index) use ($vtbDocument) {
                    $item = $vtbDocument->PAYMENTRETURNBLOB[$index];
                    return new PaymentScheduleItem([
                        'id' => $index + 1,
                        'debtDate' => $item->PAYMENTDEBTDATE ? $item->PAYMENTDEBTDATE->format('d.m.Y') : null,
                        'debtAmount' => $item->PAYMENTDEBTAMOUNT,
                        'interestDate' => $item->PAYMENTPERCENTDATE ? $item->PAYMENTPERCENTDATE->format('d.m.Y') : null,
                        'interestAmount' => $item->PAYMENTPERCENTAMOUNT,
                        'specialConditions' => $item->SPECIALCONDITIONS,
                    ]);
                },
                array_keys($vtbDocument->PAYMENTRETURNBLOB)
            ),
            'receipts' => array_map(
                function ($index) use ($vtbDocument) {
                    $receipt = $vtbDocument->CREDRECEIPTINFOBLOB[$index];
                    return new Receipt([
                        'id' => $index + 1,
                        'amount' => $receipt->CREDAMOUNT,
                        'beneficiaryName' => $receipt->BENEFICIAR,
                        'beneficiaryCountryCode' => $receipt->BENEFICIARCOUNTRYCODE,
                        'shareOfLoanAmount' => $receipt->CREDPERCENT,
                    ]);
                },
                array_keys($vtbDocument->CREDRECEIPTINFOBLOB)
            ),
            'attachedFiles' => array_map(
                function ($index) use ($vtbDocument, $extractAttachedFiles) {
                    $attachment = $vtbDocument->DOCATTACHMENT[$index];
                    $attachedFile = new AttachedFileSession([
                        'id' => Uuid::generate(),
                        'name' => $attachment->fileName,
                    ]);

                    if ($extractAttachedFiles) {
                        $path = tempnam(sys_get_temp_dir(), '');
                        file_put_contents($path, $attachment->fileContent);
                        $attachedFile->save($path);
                        unlink($path);
                    }

                    return $attachedFile;
                },
                array_keys($vtbDocument->DOCATTACHMENT)
            ),
        ]);

        $form->nonResidentsJson = NonResident::listToJson($form->nonResidents);
        $form->tranchesJson = Tranche::listToJson($form->tranches);
        $form->paymentScheduleItemsJson = PaymentScheduleItem::listToJson($form->paymentScheduleItems);
        $form->receiptsJson = Receipt::listToJson($form->receipts);
        $form->attachedFilesJson = AttachedFileSession::listToJson($form->attachedFiles);

        return $form;
    }

    /**
     * @return DictOrganization|null
     */
    public function getOrganization()
    {
        return DictOrganization::findOne(['id' => $this->organizationId]);
    }

    public function getReceiverBank()
    {
        return DictBank::findOne(['bik' => $this->receiverBankBik]);
    }

    public function getRepaymentPeriodName()
    {
        $period = DictVTBRepaymentPeriod::findOneByCode($this->repaymentPeriodCode);
        return $period ? $period->name : null;
    }

    public function getLoanAgreementCurrencyName()
    {
        $currency = DictCurrency::findOne(['code' => $this->loanAgreementCurrencyCode]);
        return $currency !== null ? $currency->name : null;
    }

    public function getLoanAgreementCurrencyDescription()
    {
        $currency = DictCurrency::findOne(['code' => $this->loanAgreementCurrencyCode]);
        return $currency !== null ? $currency->description : null;
    }

    public function getFixedInterestRateLiborCodeName()
    {
        $codes = EdmHelper::fccLiborCodes();
        return $codes[$this->fixedInterestRateLiborCode] ?? null;
    }

    public function getPaymentScheduleReasonName()
    {
        $reason = DictVTBPaymentScheduleReason::findOneById($this->paymentScheduleReason);
        return $reason ? $reason->name : null;
    }

    private function createVtbDocumentAttachments()
    {
        if (count($this->attachedFiles) === 0) {
            return [];
        }

        $iconsPath = Yii::getAlias('@common/models/vtbxml/documents/resources/attachment');
        $icon16Content = file_get_contents("$iconsPath/icon16.ico");
        $icon32Content = file_get_contents("$iconsPath/icon32.ico");

        return array_map(
            function (AttachedFileSession $attachedFile) use ($icon32Content, $icon16Content) {
                $fileContent = file_get_contents($attachedFile->getPath());
                return new BSDocumentAttachment([
                    'fileName'      => $attachedFile->name,
                    'fileContent'   => $fileContent,
                    'icon16Content' => $icon16Content,
                    'icon32Content' => $icon32Content,
                ]);
            },
            $this->attachedFiles
        );
    }

}

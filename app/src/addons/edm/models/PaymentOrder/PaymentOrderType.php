<?php
namespace addons\edm\models\PaymentOrder;

use addons\edm\helpers\Dict;
use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use addons\edm\models\VTBPayDocRu\VTBPayDocRuType;
use addons\edm\validators\CbrKeyingValidator;
use addons\edm\validators\CorrespondentAccountValidator;
use common\base\BaseType;
use common\helpers\DateHelper;
use common\helpers\NumericHelper;
use common\helpers\StringHelper;
use common\models\vtbxml\documents\PayDocRu;
use Exception;
use Yii;

class PaymentOrderType extends BaseType
{
    const TYPE       = 'PaymentOrder';
    const TYPE_LABEL = 'Платежное поручение';
    const LABEL      = 'Платежное поручение';

    const SCENARIO_1C_IMPORT = '1c_import';

    const EXTERNAL_ENCODING = 'windows-1251';

    /**
     * данные установленные ранее через setBody
     * @var string
     */
    protected $_rawBody;

    /**
     * Параметры для временного хранения признаков необходимости сохранения шаблона
     */
    public $setTemplate = false;
    public $setTemplateName;

    /**
     * Карта тэг => аттрибут
     * @var array
     */
    private $_tagAttributes = [];

    private const PAYMENT_TYPE_URGENT = 'Срочно';

    /**
     * Указатель кодировки документа
     * @warning внутри системы сформированный документ всегда будет в utf8
     * но при экспорте необходимо менять кодировку на cp1251
     * @var string
     */
    public $version;
    public $encoding          = 'Windows';
    public $softNameSender    = 'CyberFT';
    public $softNameRecipient = 'Бухгалтерский учет, редакция 4.2';
    public $receiver;

    public $dateCreated;
    public $timeCreated;

    public $number;

    public $openingBalance = 0;
    public $debitTurnover = 0;
    public $creditTurnover = 0;
    public $closingBalance = 0;

    public $documentDateFrom;
    public $documentDateBefore;
    public $organizationCheckingAccount;

    public $currency = 'RUB';
    public $sum;
    public $vat; // НДС
    public $paymentType;
    public $paymentCondition1;
    public $paymentCondition2;
    public $paymentCondition3;
    public $acceptPeriod;
    public $payerName;
    public $payerName1;
    public $payerName2;
    public $payerName3;
    public $payerName4;
    public $payerCheckingAccount;
    public $payerCorrespondentAccount;
    public $payerDateEnrollment;
    public $payerBank1;
    public $payerBank2;
    public $payerBik;
    public $beneficiaryBank1;
    public $beneficiaryBank2;
    public $beneficiaryBik;
    public $beneficiaryCheckingAccount;
    public $beneficiaryCorrespondentAccount;
    public $beneficiaryDateDebiting;
    public $beneficiaryName;
    public $beneficiaryName1;
    public $beneficiaryName2;
    public $beneficiaryName3;
    public $beneficiaryName4;
    public $payType;
    public $priority;
    public $documentSendDate;
    public $senderStatus;
    public $payerInn;
    public $payerKpp;
    public $beneficiaryInn;
    public $beneficiaryKpp;
    public $indicatorKbk;
    public $okato;
    public $okud;
    public $indicatorReason = '';
    public $indicatorPeriod = '';
    public $indicatorNumber = '';
    public $indicatorDate = '';
    public $indicatorType = '';
    protected $_paymentPurpose;
    public $paymentPurposeNds;
    public $paymentPurpose1;
    public $paymentPurpose2;
    public $paymentPurpose3;
    public $code = '';
    public $maturity;
    public $paymentOrderPaymentPurpose;
    public $backingField;
    public $swiftMessage;
    public $paymentPeriod;
    public $documentExternalId;
    public $registerExternalId;

    protected $_sumInWords;

    public $dateProcessing;
    public $dateDue;

    protected $_dateProcessingFormatted;
    protected $_dateDueFormatted;
    protected $_acceptanceEndDateFormatted;
    protected $_documentTypeExt;

    private $_sender;
    private $_terminalId;
    // payment requirement
    public $acceptanceEndDate;

    private $_date;
    private $_dateFormat = 'php:d.m.Y';

    // Закэшированные тэги, приведенные в нижний регистр
    private $_cachedAttributesTags = [];
    private $_cachedAccount = null;

    public static $typeByOkud = [
        '0401060' => 'Платежное поручение',
        '0401061' => 'Платежное требование',
        '0401063' => 'Аккредитив',
        '0401065' => 'Банковский ордер',
        '0401066' => 'Платежный ордер'
    ];

    public function init()
    {
        parent::init();

        $this->dateCreated = date('d.m.Y');
        $this->timeCreated = date('H:i:s');

        if (empty($this->_date)) {
            $this->date = date('d.m.Y');
        }
    }

    public function rules()
    {
        /**
         * @todo Yii-шный модификатор when использовать нельзя, потому что closures не сериализуются!
         * либо надо порефакторить все модели так, чтобы они нигде никогда не сериализовывались!
         */
        return [
            [
                [
                    'sum', 'payerName', 'payerCheckingAccount', 'payerBank1',
                    'payerBik', 'payerCorrespondentAccount', 'beneficiaryBank1', 'beneficiaryBik',
                    'beneficiaryName', 'payType', 'number', 'date', 'payerInn'
                ], 'required'
            ],
            [['payerBik', 'beneficiaryBik'], 'checkDictBik'],
            [
                ['payerBank2', 'beneficiaryBank2'],
                'required',
                'on' => self::SCENARIO_1C_IMPORT
            ],
            [
                'recipient',
                'required',
                'message' => Yii::t('edm', 'Recipient terminal is not found in contractors dictionary')
            ],
            [['payerKpp', 'payerInn', 'beneficiaryKpp', 'beneficiaryInn'], 'default', 'value' => '0'],
            [
                'payerInn',
                'requiredWhen', 'params' => ['when' => 'payerIsEnt']
            ],
            [
                'beneficiaryInn',
                'requiredWhen', 'params' => ['when' => 'beneficiaryIsEnt'],
            ],
            [
                [
                    'paymentPurposeNds', 'paymentType', 'paymentOrderPaymentPurpose',
                    'payerName1', 'payerName2', 'payerBank2',
                    'beneficiaryName1', 'beneficiaryName2', 'beneficiaryBank2',
                    'paymentPeriod'
                ],
                'safe'
            ],
            [
                ['beneficiaryName1'], 'string', 'max' => 160
            ],
            ['beneficiaryCheckingAccount', 'beneficiaryCheckingAccountValidate'],
            ['recipient', 'validateRecipient'],
            ['number', 'integer', 'min' => 1, 'max' => 9999999999],
            [['documentSendDate', 'date'], 'date', 'format' => 'd.M.yyyy'],
            [['documentSendDate', 'date'], 'string', 'length' => [10, 10]],
            [
                'senderStatus', 'in', 'range' => [
                '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16',
                '17', '18', '19', '20', '21', '22', '23', '24', '25', '26'
            ]
            ],
            ['payerInn', 'validateCodeNumber',
                'params' => [
                    'options' => [10],
                    'nozero12' => true,
                    'when' => 'payerIsEnt'
                ],
            ],
            ['payerInn', 'validateCodeNumber',
                'params' => [
                    'default' => '0',
                    'options' => [12],
                    'nozero12' => true,
                    'when' => 'payerIsInd',
                ],
            ],
            ['beneficiaryInn', 'validateCodeNumber',
                'params' => [
                    'required' => true,
                    'when' => 'isBudgetPayment',
                ],
            ],
            ['beneficiaryInn', 'validateCodeNumber',
                'params' => [
                    'options' => [10],
                    'nozero12' => true,
                    'when' => 'beneficiaryIsEnt'
                ],
            ],
            ['beneficiaryInn', 'validateCodeNumber',
                'params' => [
                    'default' => '0',
                    'options' => [12],
                    'nozero12' => true,
                    'when' => 'beneficiaryIsInd'
                ],
            ],
            ['payerKpp', 'validateCodeNumber',
                'params' => [
                    'required' => true,
                    'when' => ['payerIsEnt', 'isBudgetPayment']
                ],
            ],
            ['payerKpp', 'validateCodeNumber',
                'params' => [
                    'default' => '0',
                    'onlyEmpty' => true,
                    'when' => 'payerIsInd'
                ],
            ],
            ['payerKpp', 'validateCodeNumber',
                'params' => [
                    'options' => [9],
                    'default' => '0',
                    'nozero12' => true,
                    'when' => 'payerIsEnt'
                ]
            ],
            ['beneficiaryKpp', 'validateCodeNumber',
                'params' => [
                    'required' => true,
                    'when' => ['beneficiaryIsEnt', 'isBudgetPayment']
                ]
            ],
            ['beneficiaryKpp', 'validateCodeNumber',
                'params' => [
                    'default' => '0',
                    'onlyEmpty' => true,
                    'when' => 'beneficiaryIsInd'
                ]
            ],
            ['beneficiaryKpp', 'validateCodeNumber',
                'params' => [
                    'options' => [9],
                    'default' => '0',
                    'nozero12' => true,
                    'when' => 'beneficiaryIsEnt'
                ]
            ],
            [
                [
                    'payerCheckingAccount', 'payerCorrespondentAccount', 'beneficiaryCheckingAccount',
                    'beneficiaryCorrespondentAccount'
                ],
                'string', 'length' => [20, 20]
            ],
            [
                [
                    'payerCheckingAccount', 'payerCorrespondentAccount', 'beneficiaryCheckingAccount',
                    'beneficiaryCorrespondentAccount'
                ],
                'match', 'pattern' => '/^[0-9]{20}$/'
            ],
            ['payerCheckingAccount', CbrKeyingValidator::className(), 'bikKey' => 'payerBik'],
            ['beneficiaryCheckingAccount', CbrKeyingValidator::className(), 'bikKey' => 'beneficiaryBik', 'skipZero20' => true],

            ['payerCorrespondentAccount', CorrespondentAccountValidator::className(), 'bikKey' => 'payerBik'],
            ['beneficiaryCorrespondentAccount', CorrespondentAccountValidator::className(), 'bikKey' => 'beneficiaryBik'],

            [['payType', 'priority'], 'in', 'range' => array_keys(Dict::priority())],

            ['indicatorKbk', 'string', 'length' => [1, 20]],
            ['indicatorKbk', 'match', 'pattern' => '/^[0-9 ]{1,20}$/'],
            ['okato', 'validateCodeNumber', 'params' => ['default' => '0', 'options' => [8, 11]]],
            ['okud', 'safe'],
            [['indicatorReason', 'indicatorType'], 'string', 'length' => [1, 2]],
            [['indicatorPeriod'], 'string', 'length' => [1, 10]],

            [
                'paymentPurpose', 'string', 'length' => [0, 210],
                'message' => 'Общая длина поля ' . $this->getAttributeLabel('paymentPurpose')
                                . ' не должно превышать {max} символов'
            ],

            [['acceptPeriod', 'vat'], 'number'],
            ['sum', 'double', 'min' => 0.01, 'max' => 99999999999999],
            [
                ['paymentPurposeNds', 'vat', 'payerDateEnrollment',
                'beneficiaryDateDebiting', 'acceptanceEndDate'], 'safe'],
            ['code', 'default', 'value' => '0'],
            ['code', 'string', 'length' => [1, 25]],
            [['setTemplate', 'setTemplateName', 'indicatorNumber', 'indicatorDate'], 'safe']
        ];
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getDocumentType()
    {
        return static::TYPE_LABEL;
    }

    public function setDocumentType($value)
    {
        // сделано только для возможности импорта из файла, дабы не лезла ошибка
    }

    public function getPaymentPurpose()
    {
        if (isset($this->paymentPurposeNds)) {
            return $this->_paymentPurpose.($this->_paymentPurpose ? ' ' : null) . $this->paymentPurposeNds;
        }

        return $this->_paymentPurpose;
    }

    public function getRawPaymentPurpose()
    {
        return $this->_paymentPurpose;
    }

    public function setPaymentPurpose($paymentPurpose)
    {
        $this->_paymentPurpose = $paymentPurpose;
    }

    public function getTerminalId()
    {
        if (!$this->_terminalId) {
            $this->getAccountData();
        }

        return $this->_terminalId;
    }

    public function getSender()
    {
        if (!$this->_sender) {
            $this->getAccountData();
        }

        return $this->_sender;
    }

    private function getAccountData()
    {
        // кеширование аккаунта и замена его пустышкой нужны, чтобы предотвратить многочисленные обращения к базе
        // в случае его некорректности, т.к. он всегда будет пустой, и обращения к sender/receiver
        // будут пытаться его повторно дергать из базы
        if (!$this->_cachedAccount) {
            $account = EdmPayerAccount::findOne(['number' => $this->payerCheckingAccount]);
            if ($account) {
                $this->_cachedAccount = $account;
                $organization = DictOrganization::findOne($account->organizationId);
                $this->_terminalId = $organization->terminalId;
                $this->_sender = $organization->terminal->terminalId;
            } else {
                $this->_cachedAccount = new EdmPayerAccount();
                Yii::info('PaymentOrder account for number ' . $this->payerCheckingAccount . ' not found');
            }
        }
    }

    /**
     * @return null|string
     */
    public function getRecipient()
    {
        if (!$this->payerCheckingAccount) {
            return null;
        }

        $bank = DictBank::findOne([
            'bik' => $this->payerBik
        ]);

        return (isset($bank->terminalId) ? $bank->terminalId : null);
    }

    public function validateRecipient($attribute = 'recipient', $params = [])
    {
        if (!$this->getRecipient()) {
            $this->addError('payerBik', Yii::t('edm', 'You must set terminal identifier in bank or contractor dictionary'));
        }
    }


    public function requiredWhen($attribute, $params = [])
    {
        if (isset($params['when'])) {
            $when = $params['when'];
            if (!self::$when($this)) {
                return;
            }
        }

        if (empty($this->$attribute)) {
            $this->addError($attribute,	Yii::t('edm', 'This is required field'));
        }
    }

    public function validateCodeNumber($attribute, $params = [])
    {
        if ($attribute == 'beneficiaryInn' || $attribute == 'beneficiaryKpp') {
            if ($this->beneficiaryCheckingAccount && $this->beneficiaryCheckingAccount == '40817810101109999999') {
                return;
            }

            $account = EdmPayerAccount::findOne(['number' => $this->payerCheckingAccount]);

            if (!$account) {
                Yii::info("Account {$this->payerCheckingAccount} is not found, skipping INN/KPP validation");
                return;
            }

            $organization = DictOrganization::findOne($account->organizationId);

            if (!$organization) {
                throw new Exception('Failed to get payment order organization');
            }

            if ($organization->disablePayeeDetailsValidation) {
                return;
            }
        }

        if (isset($params['when'])) {
            $whenList = $params['when'];
            if (!is_array($whenList)) {
                $whenList = [$whenList];
            }

            foreach($whenList as $when) {
                if (!$this->$when()) {
                    return;
                }
            }
        }

        $value = $this->$attribute;

        if (isset($params['required']) && empty($value)) {
            $this->addError($attribute, Yii::t('edm', 'This is required field'));

            return;
        }

        if (isset($params['default']) && $value === $params['default']) {
            return;
        }

        if ($value && !preg_match('/^[0-9]+$/', $value)) {
            $this->addError($attribute,	Yii::t('edm', 'Only digits are allowed'));

            return;
        }

        if (isset($params['onlyEmpty'])) {
            if (!empty($value)) {
                $this->addError($attribute,	Yii::t('edm', 'This field must be null for individual account'));
            }

            return;
        } else if (isset($params['options'])) {
            $options = $params['options'];
            $length = mb_strlen($value);
            if (!array_key_exists($length, array_flip($options))) {
                $this->addError($attribute,	Yii::t('edm', 'The length must be {options} digits',
                    ['options' => implode(Yii::t('edm', ' or '), $options)]));

                return;
            }
        }

        if (isset($params['nozero12'])) {
            if ($value{0} == '0' && $value{1} == '0') {
                $this->addError($attribute,	Yii::t('edm', 'The first and second digit cannot be zero at once'));

                return;
            }
        } else {
            if ($value !== '0' && trim($value, '0') == '') {
                $this->addError($attribute,	Yii::t('edm', 'All digits cannot be zero'));

                return;
            }
        }
    }

    public function getModelDataAsString()
    {
        $tags        = $this->attributeTags();
        $headerTags  = $this->headerAttributeTags();
        $accountTags = $this->checkingAccountTags();

        $str = "1CClientBankExchange\r\n";
        // служебные заголовки
        foreach ($headerTags as $k => $v) {
            if (isset($this->$k)) {

                if (empty($this->$k) && in_array($k, $this->attributeSkipTags())) {
                    continue;
                }

                $str .= $this->formatRow($k, $v, true);
            }
        }
        if (!empty($this->receiver)) {
            $str .= 'Получатель=' . $this->receiver . "\r\n";
        }

        $str .= "СекцияРасчСчет\r\n";
        foreach ($accountTags as $k => $v) {
            if (isset($this->$k)) {
                $str .= $this->formatRow($k, $v, true);
            } else if (array_key_exists($k, $this->attributeAlwaysPresentTags())) {
                $str .= "$v=" . $this->attributeAlwaysPresentTags()[$k] . "\r\n";
            }
        }

        $str .= "КонецРасчСчет\r\n";

        foreach ($tags as $attribute => $attribute1C) {
            if (isset($this->$attribute)) {
                $val = $this->$attribute;

                if (in_array($attribute, $this->attributeDeprecated1cTags())) {
                    continue;
                }

                if (empty($this->$attribute) && in_array($attribute, $this->attributeSkipTags())) {
                    continue;
                }

                if ($attribute == 'beneficiaryCorrespondentAccount') {
                    $setRow = true;
                } else {
                    if ($this->isBudgetPayment() && in_array($attribute, $this->attributeEmptyTags())) {
                        $setRow = true;
                    } else {
                        if (is_null($val) || $val === '') {
                            $setRow = false;
                        } else {
                            $setRow = true;
                        }
                    }
                }

                if ($setRow) {
                    $str .= $this->formatRow($attribute, $attribute1C, false);
                }
            }
        }

        $str .= "КонецДокумента\r\n";
        $str .= "КонецФайла\r\n";

        return $str;
    }

    public function getBodyReadable()
    {
        $labels = $this->attributeLabels();
        $str    = '';
        foreach ($labels as $k => $v) {
            if (isset($this->$k)) {
                $str .= ($v ? "$v: " : null) . "{$this->$k}\r\n";
            }
        }
        return $str;
    }

    public function getBodyPrintable()
    {
        return $this->getBodyReadable();
    }

    /**
     * @param string $value
     * @param string $encoding
     * @return $this
     */
    public function loadFromString($value, $isFile = false, $encoding = null)
    {
        if (!$encoding) {
            $encoding = static::EXTERNAL_ENCODING;
        }

        $this->setRawBody(StringHelper::utf8($value, $encoding));
        $this->parse($this->_rawBody);

        return $this;
    }

    public static function createFromSBBOLPayDocRu(SBBOLPayDocRuType $typeModel)
    {
        $paymentOrderType = new PaymentOrderType();
        $payDocRu = $typeModel->request->getPayDocRu();

        $accDoc = $payDocRu->getAccDoc();
        if ($accDoc) {
            $paymentOrderType->_paymentPurpose = $accDoc->getPurpose();
            $paymentOrderType->number = $accDoc->getAccDocNo();
            $paymentOrderType->dateCreated = $accDoc->getDocDate() ? $accDoc->getDocDate()->format('d.m.Y') : null;
            $paymentOrderType->timeCreated = $accDoc->getDocDate() ? $accDoc->getDocDate()->format('H:i:s') : null;
            $paymentOrderType->sum = $accDoc->getDocSum();
            $paymentOrderType->payType = $accDoc->getTransKind();
            $paymentOrderType->priority = $accDoc->getPriority();
            $paymentOrderType->paymentType = $accDoc->getPaytKind();
        }

        $payer = $payDocRu->getPayer();
        if ($payer) {
            $paymentOrderType->payerName = $payer->getName();
            $payerBank = $payer->getBank();
            if ($payerBank) {
                $paymentOrderType->payerBank1 = $payerBank->getName();
                $paymentOrderType->payerBank2 = $payerBank->getBankCity();
                $paymentOrderType->payerBik = $payerBank->getBic();
                $paymentOrderType->payerCorrespondentAccount = $payerBank->getCorrespAcc();
            }
            $paymentOrderType->payerInn = $payer->getInn();
            $paymentOrderType->payerKpp = $payer->getKpp();
            $paymentOrderType->payerCheckingAccount = $payer->getPersonalAcc();
        }

        $payee = $payDocRu->getPayee();
        if ($payee) {
            $paymentOrderType->beneficiaryName = $payee->getName();
            $payeeBank = $payee->getBank();
            if ($payeeBank) {
                $paymentOrderType->beneficiaryBank1 = $payeeBank->getName();
                $paymentOrderType->beneficiaryBank2 = $payeeBank->getBankCity();
                $paymentOrderType->beneficiaryBik = $payeeBank->getBic();
                $paymentOrderType->beneficiaryCorrespondentAccount = $payeeBank->getCorrespAcc();
            }
            $paymentOrderType->beneficiaryInn = $payee->getInn();
            $paymentOrderType->beneficiaryKpp = $payee->getKpp();
            $paymentOrderType->beneficiaryCheckingAccount = $payee->getPersonalAcc();
        }

        $departmentalInfo = $payDocRu->getDepartmentalInfo();
        if ($departmentalInfo) {
            $paymentOrderType->senderStatus = $departmentalInfo->getDrawerStatus();
            $paymentOrderType->indicatorKbk = $departmentalInfo->getCbc();
            $paymentOrderType->okato = $departmentalInfo->getOkato();
            $paymentOrderType->indicatorReason = $departmentalInfo->getPaytReason();
            $paymentOrderType->indicatorPeriod = $departmentalInfo->getTaxPeriod();
            $paymentOrderType->indicatorNumber = $departmentalInfo->getDocNo();
            $paymentOrderType->indicatorDate = $departmentalInfo->getDocDate();
            $paymentOrderType->indicatorType = $departmentalInfo->getTaxPaytKind();
        }

        return $paymentOrderType;
    }

    public static function createFromVTBPayDocRu(VTBPayDocRuType $typeModel)
    {
        $paymentOrderType = new PaymentOrderType();

        /** @var PayDocRu $payDocRu */
        $payDocRu = $typeModel->document;

        $paymentOrderType->_paymentPurpose = $payDocRu->GROUND;
        $paymentOrderType->number = $payDocRu->DOCUMENTNUMBER;
        $paymentOrderType->dateCreated = $payDocRu->DOCUMENTDATE ? $payDocRu->DOCUMENTDATE->format('d.m.Y') : null;
        $paymentOrderType->sum = $payDocRu->AMOUNT;
        $paymentOrderType->payType = $payDocRu->OPERTYPE;
        $paymentOrderType->priority = $payDocRu->PAYMENTURGENT;
        $paymentOrderType->paymentType = $payDocRu->SENDTYPECODE == 5 ? 'срочно' : null;
        $paymentOrderType->vat = $payDocRu->NDS;

        $paymentOrderType->payerName = $payDocRu->PAYER;
        $paymentOrderType->payerBank1 = $payDocRu->PAYERBANKNAME;
        $paymentOrderType->payerBank2 = $payDocRu->PAYERPLACE;
        $paymentOrderType->payerBik = $payDocRu->PAYERBIC;
        $paymentOrderType->payerCorrespondentAccount = $payDocRu->PAYERCORRACCOUNT;
        $paymentOrderType->payerInn = $payDocRu->PAYERINN;
        $paymentOrderType->payerKpp = $payDocRu->PAYERKPP;
        $paymentOrderType->payerCheckingAccount = $payDocRu->PAYERACCOUNT;

        $paymentOrderType->beneficiaryName = $payDocRu->RECEIVER;
        $paymentOrderType->beneficiaryBank1 = $payDocRu->RECEIVERBANKNAME;
        $paymentOrderType->beneficiaryBank2 = $payDocRu->RECEIVERPLACE;
        $paymentOrderType->beneficiaryBik = $payDocRu->RECEIVERBIC;
        $paymentOrderType->beneficiaryCorrespondentAccount = $payDocRu->RECEIVERCORRACCOUNT;
        $paymentOrderType->beneficiaryInn = $payDocRu->RECEIVERINN;
        $paymentOrderType->beneficiaryKpp = $payDocRu->RECEIVERKPP;
        $paymentOrderType->beneficiaryCheckingAccount = $payDocRu->RECEIVERACCOUNT;

        $paymentOrderType->senderStatus = $payDocRu->STAT1256;
        $paymentOrderType->indicatorKbk = $payDocRu->CBCCODE;
        $paymentOrderType->okato = $payDocRu->OKATOCODE;
        $paymentOrderType->indicatorReason = $payDocRu->PAYGRNDPARAM;
        $paymentOrderType->indicatorPeriod = implode('.', array_filter([$payDocRu->TAXPERIODPARAM1, $payDocRu->TAXPERIODPARAM2, $payDocRu->TAXPERIODPARAM3]));
        $paymentOrderType->indicatorNumber = $payDocRu->DOCNUMPARAM2;
        $paymentOrderType->indicatorDate = implode('.', array_filter([$payDocRu->DOCDATEPARAM1, $payDocRu->DOCDATEPARAM2, $payDocRu->DOCDATEPARAM3]));;
        $paymentOrderType->indicatorType = $payDocRu->PAYTYPEPARAM;

        return $paymentOrderType;
    }

    public function beforeValidate()
    {
        $this->documentSendDate = date('d.m.Y');

        return parent::beforeValidate();
    }

    /**
     * Validate okato and senderStatus properties.
     * Not empty if beneficiaryCheckingAccount is budget account
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function beneficiaryCheckingAccountValidate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $accountNumber = $this->beneficiaryCheckingAccount;

            if ($accountNumber == '00000000000000000000') {
                return true;
            }

            $isBudgetPayment = $this->isBudgetPayment();

            if (strlen($this->okato) == 0 && $isBudgetPayment) {
                $this->addError('okato', Yii::t('edm', 'Empty value'));
            }

            if ($isBudgetPayment && empty($this->senderStatus)) {
                $this->addError('senderStatus', Yii::t('edm', 'Empty value'));
            }
        }
    }

    public function checkDictBik($attribute)
    {
        if (!DictBank::findOne($this->$attribute)) {
            $this->addError($attribute, $this->$attribute . ' ' . Yii::t('edm', 'not found in the Bank Dictionary'));
        }
    }

    /**
     * Check budget payment
     *
     * @see http://confluence.cyberplat.com/pages/viewpage.action?pageId=1705640
     * @return boolean
     */
    public function isBudgetPayment()
    {
        if ($this->isTreasuryPayment()) {
            return true;
        }

        $accountNumber = $this->beneficiaryCheckingAccount;

        $length = strlen($accountNumber);

        if ($length < 14) {
            return false;
        }

        if (strlen($this->beneficiaryBik) > 6) {
            $testBik = substr($this->beneficiaryBik, 6, 3);
            if ($testBik !== '000' && $testBik !== '001' && $testBik !== '002') {
                return false;
            }
        }

        /**
         * Check first 5 digits
         */
        if ($length > 4 && in_array(substr($accountNumber, 0, 5), ['40101', '40302'])) {
            return true;
        }

        $n14digit = (int) substr($accountNumber, 13, 1);

        switch (substr($accountNumber, 0, 5)) {
            case '40501':
                return $n14digit === 2;

            case '40601':
            case '40701':
                return $n14digit === 1 || $n14digit === 3;

            case '40503':
            case '40603':
            case '40703':
                return $n14digit === 4;
        }

        return false;
    }

    /**
     * Служит для маппинга на тело документа в формате 1С
     */
    public function attributeTags()
    {
        return [
            'documentTypeExt'				  => 'СекцияДокумент',
            'number'                          => 'Номер',
            'date'                            => 'Дата',
            'sum'                             => 'Сумма',
            'senderStatus'                    => 'СтатусСоставителя',
            'paymentType'                     => 'ВидПлатежа',
            'paymentOrderPaymentPurpose'      => 'КодНазПлатежа',
            'payerName'                       => 'Плательщик', // наименование
            'payerName1'                      => 'Плательщик1',
            'payerName2'                      => 'Плательщик2',
            'payerName3'                      => 'Плательщик3',
            'payerName4'                      => 'Плательщик4',
            'payerInn'                        => 'ПлательщикИНН',
            'payerKpp'                        => 'ПлательщикКПП',
            'payerAccount'                    => 'ПлательщикСчет',
            'payerCheckingAccount'            => 'ПлательщикРасчСчет',
            'payerCorrespondentAccount'       => 'ПлательщикКорсчет',
            'dateDueFormatted'                => 'ДатаСписано',
            'payerBank1'                      => 'ПлательщикБанк1',
            'payerBank2'                      => 'ПлательщикБанк2',
            'payerBik'                        => 'ПлательщикБИК',
            'beneficiaryName'                 => 'Получатель', // наименование
            'beneficiaryName1'                => 'Получатель1',
            'beneficiaryName2'                => 'Получатель2',
            'beneficiaryName3'                => 'Получатель3',
            'beneficiaryName4'                => 'Получатель4',
            'beneficiaryBank1'                => 'ПолучательБанк1',
            'beneficiaryBank2'                => 'ПолучательБанк2',
            'beneficiaryBik'                  => 'ПолучательБИК',
            'beneficiaryAccount'              => 'ПолучательСчет',
            'beneficiaryCheckingAccount'      => 'ПолучательРасчСчет',
            'beneficiaryCorrespondentAccount' => 'ПолучательКорсчет',
            'dateProcessingFormatted'         => 'ДатаПоступило',
            'beneficiaryInn'                  => 'ПолучательИНН',
            'beneficiaryKpp'                  => 'ПолучательКПП',
            'payType'                         => 'ВидОплаты',
            'priority'                        => 'Очередность',
            'indicatorKbk'                    => 'ПоказательКБК',
            'okato'                           => 'ОКАТО',
            'okud'                            => 'ОКУД',
            'acceptanceEndDateFormatted'      => 'ОкончСрокаАкцепта',
            'sumInWords'                      => 'СуммаПрописью',
            'indicatorReason'                 => 'ПоказательОснования',
            'indicatorPeriod'                 => 'ПоказательПериода',
            'indicatorNumber'                 => 'ПоказательНомера',
            'indicatorDate'                   => 'ПоказательДаты',
            'indicatorType'                   => 'ПоказательТипа',
            'paymentPurpose'                  => 'НазначениеПлатежа',
            'paymentPurpose1'                 => 'НазначениеПлатежа1',
            'paymentPurpose2'                 => 'НазначениеПлатежа2',
            'paymentPurpose3'                 => 'НазначениеПлатежа3',
            'code'                            => 'Код',
            'paymentCondition1'               => 'УсловиеОплаты1',
            'paymentCondition2'               => 'УсловиеОплаты2',
            'paymentCondition3'               => 'УсловиеОплаты3',
            'acceptPeriod'                    => 'СрокАкцепта',
            'documentSendDate'                => 'ДатаОтсылкиДок',
            'paymentPeriod'                   => 'СрокПлатежа',
            'documentExternalId'              => 'ДокументКод',
        ];
    }

    /**
     * Список атрибутов, которые не должны участвовать в экспорте 1с
     */

    public function attributeDeprecated1cTags()
    {
        return [
            'okud','acceptanceEndDateFormatted', 'sumInWords'
        ];
    }

    /**
     * Список атрибутов, которые при пустом значении должны получать значение 0
     * @return array
     */
    public function attributeEmptyTags()
    {
        return [
            'payerKpp', 'beneficiaryKpp', 'indicatorKbk',
            'okato', 'indicatorReason', 'indicatorPeriod',
            'indicatorNumber', 'indicatorDate', 'openingBalance', 'closingBalance'
        ];
    }
    /**
     * Список атрибутов, которые при отсутствии в документе должны создаваться с указанным здесь значением
     * @return array
     */
    public function attributeAlwaysPresentTags()
    {
        return ['openingBalance' => '0.00', 'closingBalance' => '0.00'];
    }

    public function attributeLabels()
    {
        return [
            'type' => 'Тип',
            'body' => 'Тело',
            'sender' => 'Отправитель',
            'recipient' => 'Получатель',
            // Общие сведения
            'version'                         => 'Номер версии формата обмена',
            'encoding'                        => 'Кодировка файла',
            'softNameSender'                  => 'Программа-отправитель',
            'softNameRecipient'               => 'Программа-получатель',
            'dateCreated'                     => 'Дата формирования файла',
            'timeCreated'                     => 'Время формирования файла',

            // Сведения об условиях отбора передаваемых данных
            'documentDateFrom'                => 'Дата начала интервала',
            'documentDateBefore'              => 'Дата конца интервала',
            'organizationCheckingAccount'     => 'Расчетный счет организации',
            'documentType'                    => 'Вид документа',

            // Секция платежного документа
            'number'                          => 'Номер документа',
            'date'                            => 'Дата документа',
            'sum'                             => 'Сумма платежа',
            'vat'                             => 'Ставка НДС',
            'senderStatus'                    => 'Статус составителя расчетного документа',
            'paymentType'                     => 'Вид платежа',
            'payerName'                       => 'Плательщик', // наименование
            'payerName1'                      => 'Плательщик стр. 1',
            'payerName2'                      => 'Плательщик стр. 2',
            'payerName3'                      => 'Плательщик стр. 3',
            'payerName4'                      => 'Плательщик стр. 4',
            'payerInn'                        => 'ИНН плательщика',
            'payerKpp'                        => 'КПП плательщика',
            'payerAccount'                    => 'Расчетный счет плательщика',
            'payerCheckingAccount'            => 'Расчетный счет плательщика',
            'payerCorrespondentAccount'       => 'Корсчет банка плательщика',
            'payerDateEnrollment'             => 'Дата списания средств с р/с',
            'payerBank1'                      => 'Банк плательщика',
            'payerBank2'                      => 'Город банка плательщика',
            'payerBik'                        => 'БИК банка плательщика',
            'beneficiaryName'                 => 'Получатель', // наименование
            'beneficiaryName1'                => 'Получатель стр. 1',
            'beneficiaryName2'                => 'Получатель стр. 2',
            'beneficiaryName3'                => 'Получатель стр. 3',
            'beneficiaryName4'                => 'Получатель стр. 4',
            'beneficiaryInn'                  => 'ИНН получателя',
            'beneficiaryKpp'                  => 'КПП получателя',
            'beneficiaryAccount'              => 'Расчетный счет получателя',
            'beneficiaryCheckingAccount'      => 'Расчетный счет получателя',
            'beneficiaryCorrespondentAccount' => 'Корсчет банка получателя',
            'beneficiaryDateDebiting'         => 'Дата поступления средств на р/с',
            'beneficiaryBank1'                => 'Банк получателя',
            'beneficiaryBank2'                => 'Город банка получателя',
            'beneficiaryBik'                  => 'БИК банка получателя',
            'payType'                         => 'Вид операции',
            'priority'                        => 'Очередность платежа',
            'indicatorKbk'                    => 'Показатель кода бюджетной классификации',
            'okato'                           => 'Код ОКТМО территории, на которой мобилизуются денежные средства от уплаты налога, сбора и иного платежа',
            'indicatorReason'                 => 'Показатель основания налогового платежа',
            'indicatorPeriod'                 => 'Показатель налогового периода / Код таможенного органа',
            'indicatorNumber'                 => 'Показатель номера документа',
            'indicatorDate'                   => 'Показатель даты документа',
            'indicatorType'                   => 'Показатель типа платежа',
            'paymentPurpose'                  => 'Назначение платежа',
            'paymentPurposeNds'               => 'В т.ч. НДС',
            'paymentPurpose1'                 => 'Назначение платежа стр.1',
            'paymentPurpose2'                 => 'Назначение платежа стр.2',
            'paymentPurpose3'                 => 'Назначение платежа стр.3',
            'paymentPurpose4'                 => 'Назначение платежа стр.4',
            'paymentPurpose5'                 => 'Назначение платежа стр.5',
            'paymentPurpose6'                 => 'Назначение платежа стр.6',
            'code'                            => 'Уникальный идентификатор платежа',
            'paymentCondition1'               => 'Условие оплаты, стр. 1',
            'paymentCondition2'               => 'Условие оплаты, стр. 2',
            'paymentCondition3'               => 'Условие оплаты, стр. 3',
            'acceptPeriod'                    => 'Срок акцепта, количество дней',
            'documentSendDate'                => 'Дата отсылки документов',
            'maturity'                        => 'Срок. Пл.',
            'backingField'                    => 'Рез. поле',
            'paymentOrderPaymentPurpose'      => 'Наз. пл.',
            'paymentPeriod'                    => 'Срок платежа',
            'setTemplate'                       => Yii::t('edm', 'Save as template')
        ];
    }

    /**
     * Отдаем неизмененные данные установленные ранее через setBody
     */
    public function getRawBody()
    {
        return $this->_rawBody;
    }

    public function setRawBody($value)
    {
        $this->_rawBody = $value;

        return $this;
    }

    protected function parse($string)
    {
        $processedTags = [];
        $rows = preg_split('/[\\r\\n]+/', $string);
        $isHeader = true;

        foreach ($rows as $row) {
            $isNewSection = in_array(trim($row), ['СекцияРасчСчет', 'СекцияДокумент=Платежное поручение']);
            if ($isNewSection) {
                $processedTags = [];
                $isHeader = false;
            }

            $tagAndValuePair = explode('=', trim($row), 2);
            if (count($tagAndValuePair) === 1) {
                continue;
            } else {
                list($tag, $value) = $tagAndValuePair;
            }

            $allowDuplicate = $isHeader && in_array($tag, ['РасчСчет', 'Документ']);
            if (!$allowDuplicate && in_array($tag, $processedTags)) {
                // если найден повторяющийся тэг
                Yii::info('Duplicate tag found: ' . $row);

                continue;
            }

            // Ошибка, если свойство и значение содержат между собой пробелы
            if (preg_match('/^[^=]*( =|= )/', $row)) {
                $this->addError('body',
                    Yii::t('edm', 'Extra spaces between property and value in the field {field}', [
                        'field' => $tag
                    ])
                );

                continue;
            }

            if (($attribute = $this->getAttributeByTag($tag, true))) {
                $this->$attribute = $value;
            } else {
                // если тэг не найден, пропускаем его
                Yii::info('Unsupported tag: ' . $row);

                continue;
            }

            $processedTags[] = $tag;
        }
    }

    public function getBeneficiaryAccount()
    {
        return $this->beneficiaryCheckingAccount;
    }

    public function setBeneficiaryAccount($beneficiaryAccount)
    {
        if (!$this->beneficiaryCheckingAccount) {
            $this->beneficiaryCheckingAccount = $beneficiaryAccount;
        }

        return $this;
    }

    public function getPayerAccount()
    {
        return $this->payerCheckingAccount;
    }

    public function setPayerAccount($payerAccount)
    {
        if (!$this->payerCheckingAccount) {
            $this->payerCheckingAccount = $payerAccount;
        }

        return $this;
    }

    protected function getAttributeByTag($tag, $caseInsensitive = false)
    {
        $map = $this->getTagAttributes();

        // если не нужно учитывать регистр символов тэгов
        if ($caseInsensitive) {
            $tag = mb_strtolower($tag);

            if (!$this->_cachedAttributesTags) {
                $keys = array_keys($map);
                $values = array_values($map);

                $keys = array_map(
                    function($item) {
                        return mb_strtolower($item);
                    },
                    $keys
                );

                $map = array_combine($keys, $values);
                $this->_cachedAttributesTags = $map;
            } else {
                $map = $this->_cachedAttributesTags;
            }
        }

		return isset($map[$tag])?$map[$tag]:null;
    }

    protected function getTagAttributes()
    {
        if (!empty($this->_tagAttributes)) {
            return $this->_tagAttributes;
        }

        return $this->_tagAttributes = array_merge(
            array_flip($this->attributeTags()),
            array_flip($this->headerAttributeTags()),
            array_flip($this->checkingAccountTags())
        );
    }

    public function headerAttributeTags()
    {
        return [
            // Общие сведения
            'version'                     => 'ВерсияФормата',
            'encoding'                    => 'Кодировка',
            'softNameSender'              => 'Отправитель',
            'dateCreated'                 => 'ДатаСоздания',
            'timeCreated'                 => 'ВремяСоздания',
            'registerExternalId'          => 'ГруппКод',
            /**
             * @todo Этот Получатель (транспортный) мешается c Получателем из аккаунта (денежным)
             * необходим рефакторинг парсинга тегов по группам.
             * В текущем виде данный получатель вставляется напрямую в getModelDataAsString()
             */
            //'receiver'                    => 'Получатель',

            // Сведения об условиях отбора передаваемых данных
            'documentDateFrom'            => 'ДатаНачала',
            'documentDateBefore'          => 'ДатаКонца',
            'organizationCheckingAccount' => 'РасчСчет',
            'documentType'                => 'Документ',
        ];
    }

    public function checkingAccountTags()
    {
        return [
            'documentDateFrom'     => 'ДатаНачала',
            'documentDateBefore'   => 'ДатаКонца',
            'payerCheckingAccount' => 'РасчСчет',
            'openingBalance'       => 'НачальныйОстаток',
            'creditTurnover'       => 'ВсегоПоступило',
            'debitTurnover'        => 'ВсегоСписано',
            'closingBalance'       => 'КонечныйОстаток'
        ];
    }

    public function getCurrency()
    {
        return $this->currency; //'RUB';
    }

    public function getLabel()
    {
        return Yii::t('edm', static::LABEL);
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->_date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->_date = $date;
    }

    /**
     * Получение текущего счетчика документа заданного типа
     * @return mixed
     */
    public function getCounter()
    {
        return Yii::$app->redis->get($this->getCounterKey());
    }

    /**
     * Приращение текущего счетчика документа заданного типа
     * @return $this
     */
    public function counterIncrement()
    {
        $val = $this->getCounter();
        $val = ($val < 1000 ? 1000 : ($val + 1));
        $this->setCounter($val);

        return $this;
    }

    /**
     * Задать значение текущего счетчика документа заданного типа
     * @param $value
     */
    public function setCounter($value)
    {
        Yii::$app->redis->set($this->getCounterKey(), $value);
    }

    /**
     * Ключ текущего счетчика документа заданного типа
     * @return string
     */
    protected function getCounterKey()
    {
        return $this->getType() . 'Counter' . date('Y') . $this->getType();

        /**
         * @todo Надо сделать так:
         */
        /*
        return \common\helpers\RedisHelper::getKeyName(
            static::TYPE
            . 'Counter:'
            . date('Y')
            . $this->getType()
        );
        */
    }

    /**
     * @param $tag
     * @param $value
     */
    public function setTag($tag, $value)
    {
        $attribute = $this->getAttributeByTag($tag);

        if ($attribute) {
            $this->$attribute = $value;
        }
    }

    public function setTags($tags)
    {
        foreach ($tags as $tag => $value) {
            $this->setTag($tag, $value);
        }
    }

    /**
     * @param $tag
     * @return mixed|null
     */
    public function getTag($tag)
    {
        $attribute = $this->getAttributeByTag($tag);

        if (isset($this->$attribute)) {
            return $this->$attribute;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        $data = [];
        $tags = $this->attributeTags();

        foreach ($tags as $attribute => $tag) {
            $data[$tag] = $this->$attribute;
        }

        return $data;
    }

    /**
     * @param $tag
     * @return bool
     */
    public function hasTag($tag)
    {
        return (bool) $this->getAttributeByTag($tag);
    }

	public function getSearchFields()
	{
		return [
			'body' => $this->getModelDataAsString()
		];
	}

    public function setDateFormat($format)
    {
        $this->_dateFormat = $format;
    }

    /**
     * Format headers row
     *
     * @param string  $key        Tag key
     * @param string  $key1C      Tag value
     * @param boolean $typeHeader Is header type flag
     * @return string
     */
    private function formatRow($key, $key1C, $typeHeader = false)
    {
        if (!$typeHeader) {
            $dateArray = ['date', 'payerDateEnrollment', 'beneficiaryDateDebiting', 'indicatorDate', 'documentSendDate'];
        } else {
            $dateArray = ['dateCreated', 'documentDateFrom', 'documentDateBefore'];
        }

        $emptyValues = $this->attributeEmptyTags();

        $rowValue = $this->$key;
        if (in_array($key, $dateArray)) {
            try {
                if (!($key === 'indicatorDate' && $rowValue == 0)) {
                    $rowValue = DateHelper::convert($rowValue, 'date', $this->_dateFormat);
                }
            } catch (Exception $ex) {

            }
        } else if (in_array($key, $emptyValues) && empty($rowValue)) {
            $rowValue = 0;
        }

        return ($key1C ? "$key1C=" : null) . "$rowValue\r\n";
    }

    public function getAccountType($number)
    {
        return EdmHelper::checkParticipantTypeByAccount($number);
    }

    public function getSumInWords()
    {
        $value = NumericHelper::num2str($this->sum, $this->currency);
        // Сумма должна быть с большой буквы.
        // эмуляция mb_ucfirst(), т.к. просто ucfirst() не работает с utf-8
        return mb_strtoupper(mb_substr($value, 0, 1)) . mb_substr($value, 1);
    }

    public function setSumInWords($value)
    {
        return $this->_sumInWords = $value;
    }

    public function isRequirement()
    {
        return $this->okud == '0401061';
    }

    public function setDateProcessingFormatted($value)
    {
        $this->_dateProcessingFormatted = $value;
    }

    public function getDateProcessingFormatted()
    {
        if ($this->dateProcessing) {
            return DateHelper::formatDate($this->dateProcessing);
        } else {
            return null;
        }
    }

    public function setDateDueFormatted($value)
    {
        $this->_dateDueFormatted = $value;
    }

    public function getDateDueFormatted()
    {
        if ($this->dateDue) {
            return DateHelper::formatDate($this->dateDue);
        } else {
            return null;
        }
    }

    public function setAcceptanceEndDateFormatted($value)
    {
        $this->_acceptanceEndDateFormatted = $value;
    }

    public function getAcceptanceEndDateFormatted()
    {
        if ($this->acceptanceEndDate) {
            return DateHelper::formatDate($this->acceptanceEndDate);
        } else {
            return null;
        }
    }

    public function setDocumentTypeExt($value)
    {
        $this->_documentTypeExt = $value;
    }

    public function getDocumentTypeExt()
    {
        if (array_key_exists($this->okud, static::$typeByOkud)) {
            return static::$typeByOkud[$this->okud];
        }

        return static::TYPE_LABEL;
    }

    private function payerIsEnt()
    {
        return $this->getAccountType($this->payerCheckingAccount) == 'ENT';
    }

    private function payerIsInd()
    {
        return $this->getAccountType($this->payerCheckingAccount) == 'IND';
    }

    private function beneficiaryIsEnt()
    {
        return $this->getAccountType($this->beneficiaryCheckingAccount) == 'ENT';
    }

    private function beneficiaryIsInd()
    {
        return $this->getAccountType($this->beneficiaryCheckingAccount) == 'IND';
    }

    /**
     * Подготовка модели к экспорту
     */
    public function beforeExport1c()
    {
        // ВидПлатежа только Срочно
        if ($this->paymentType != 'срочно') {
            $this->paymentType = '';
        }
    }

    /**
     * Список тэгов, которые могут не выводится, если имеют пустое значение
     * @return array
     */
    private function attributeSkipTags()
    {
        return [
            'timeCreated',
            'beneficiaryCorrespondentAccount',
            'documentExternalId',
            'registerExternalId',
        ];
    }

    /** Переназначение наименований плательщика/получателя
     * для правильного отображения в тэгах 1с
     */
    public function setParticipantsNames()
    {
        $payerName = $this->payerName;

        if ($this->payerInn) {
            $this->payerName = "ИНН {$this->payerInn} {$payerName}";
        }

        $this->payerName1 = $payerName;

        $beneficiaryName = $this->beneficiaryName;

        if ($this->beneficiaryInn) {
            $this->beneficiaryName = "ИНН {$this->beneficiaryInn} {$beneficiaryName}";
        }

        $this->beneficiaryName1 = $beneficiaryName;
    }

    /**
     * Отображение наименований плательщика/получения без ИНН
     */
    public function unsetParticipantsNames()
    {
        if ($this->payerName1) {
            $this->payerName = $this->payerName1;
        }
        if ($this->beneficiaryName1) {
            $this->beneficiaryName = $this->beneficiaryName1;
        }
    }

    private function isTreasuryPayment(): bool
    {
        return !empty($this->beneficiaryCorrespondentAccount)
            && StringHelper::startsWith($this->beneficiaryCorrespondentAccount, '40102');
    }

    public function isUrgent(): bool
    {
        return mb_strtolower($this->paymentType) === mb_strtolower(self::PAYMENT_TYPE_URGENT);
    }
}
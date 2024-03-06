<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\ForeignCurrencyOperation\cyberxml\dataTypes\AccountDataType;
use addons\edm\models\ForeignCurrencyOperation\cyberxml\dataTypes\ApplicantDataType;
use common\base\BaseType;
use common\helpers\Currencies;
use InvalidArgumentException;
use Yii;

/**
 * @property-read ApplicantDataType $applicant
 * @property-read AccountDataType debitAccount
 * @property-read AccountDataType $creditAccount
 * @property-read AccountDataType $commissionAccount
 * @property string|null $operationType
 * @property string|null $organizationName
 */
class ForeignCurrencyOperationType extends BaseType
{
    const TYPE = 'ForeignCurrencyOperation';

    const OPERATION_PURCHASE = 'ForeignCurrencyPurchaseRequest';
    const OPERATION_SELL = 'ForeignCurrencySellRequest';

    public $sender;
    public $recipient;

    public $recipientBankBik;

    public $id;
    public $body;
    public $numberDocument;
    public $date;
    public $uuid;

    public $paymentOrderCurrCode;
    public $paymentOrderCurrAmount;
    public $paymentOrderAmount;
    public $paymentOrderCurrExchangeRate;

    private $_debitAccount;
    private $_creditAccount;
    private $_commissionAccount;
    private $_applicant;
    private $_organizationName;

    public $reference;
    public $signatures;

    private $_xml;
    private $_xmlString;
    private $_operationType;

    public function init()
    {
        $this->_applicant = new ApplicantDataType();
        $this->_creditAccount = new AccountDataType();
        $this->_debitAccount = new AccountDataType();
        $this->_commissionAccount = new AccountDataType();
    }

    public function rules()
    {
        return [
            [
                [
                    'applicant.name', 'paymentOrderCurrCode', 'commissionAccount.number',
                    'creditAccount.number', 'debitAccount.number', 'numberDocument',
                    'date', 'recipientBankBik',
                ],
                'required'
            ],
            ['applicant.phone', 'cleanupPhone'],
            [
                ['paymentOrderAmount'],
                'validateAmount', 'skipOnEmpty' => false, 'enableClientValidation' => false,
            ],

            ['operationType', 'in', 'range' => [self::OPERATION_PURCHASE, self::OPERATION_SELL]],
            [['numberDocument'], 'integer'],
            [['date'], 'string', 'length' => [10, 10]],
            [['date'], 'date', 'format' => 'd.M.yyyy'],

            [
                [
                    'paymentOrderCurrExchangeRate', 'paymentOrderCurrAmount', 'paymentOrderAmount',
                ],
                'number'
            ],
            [['paymentOrderAmount', 'paymentOrderCurrAmount'], 'filter', 'filter' => function ($value) {
                return floatval($value) === 0.0 ? null : $value;
            }],
            [['applicant.inn'], 'string', 'min' => 9, 'max' => 12],
            [
                ['applicant.inn'], 'match',
                'pattern' => '/^[0-9]{9,12}$/',
                'message' => Yii::t('edm', 'The {attribute} must contain only digits')
            ],
            [['paymentOrderCurrCode'], 'string', 'max' => 3],
            [['paymentOrderCurrCode'], 'in', 'range' => Currencies::getCodes()],

            [['sender', 'recipient', 'body'], 'safe'],
            [['date'], 'validateActuality', 'enableClientValidation' => false],
        ];
    }

    public function cleanupPhone($attribute, $params)
    {
        if (stristr($this->applicant->phone, '_')) {
            $this->applicant->phone = '';
        }
    }
     /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('edm', 'ID'),
            'applicant.name' => Yii::t('edm', 'Business name'),
            'applicant.inn' => Yii::t('edm', 'INN'),
            'applicant.address' => Yii::t('edm', 'Address'),
            'applicant.phone' => Yii::t('edm', 'Phone'),
            'applicant.contactPerson' => Yii::t('edm', 'Contact person'),
            'creditAccount.number' => Yii::t('edm', 'Checking account'),
            'creditAccount.bik' => Yii::t('edm', 'BIC'),
            'creditAccount.bankName' => Yii::t('edm', 'Bank name'),
            'creditAccount.bankAccountNumber' => Yii::t('edm', 'Bank account name'),
            'debitAccount.number' => Yii::t('edm', 'Checking account'),
            'debitAccount.bik' => Yii::t('edm', 'BIC'),
            'debitAccount.bankName' => Yii::t('edm', 'Bank name'),
            'debitAccount.bankAccountNumber' => Yii::t('edm', 'Bank account name'),
            'commissionAccount.number' => Yii::t('edm', 'Checking account'),
            'commissionAccount.bik' => Yii::t('edm', 'BIC'),
            'commissionAccount.bankName' => Yii::t('edm', 'Bank name'),
            'commissionAccount.bankAccountNumber' => Yii::t('edm', 'Bank account name'),
            'paymentOrderCurrCode' => Yii::t('edm', 'Currency code'),
            'paymentOrderCurrAmount' => Yii::t('edm', 'Currency amount'),
            'paymentOrderAmount' => Yii::t('edm', 'Ruble amount'),
            'paymentOrderCurrExchangeRate' => Yii::t('edm', 'Currency exchange rate'),
            'operationType' => Yii::t('edm', 'Operation type'),
            'body' => Yii::t('edm', 'Body'),
            'numberDocument' => Yii::t('edm', 'Number document'),
            'date' => Yii::t('edm', 'Date'),
            'payer' => Yii::t('edm', 'Payer'),
            'paymentAccount' => Yii::t('edm', 'Payment account'),
            'recipientBankBik' => Yii::t('edm', 'Bank'),
        ];
    }

    public function getType()
    {
        return $this->_operationType;
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    public function getOperationType()
    {
        return $this->_operationType;
    }

    public function setOperationType($value)
    {
        if ($value == self::OPERATION_PURCHASE || $value == self::OPERATION_SELL) {
            $this->_operationType = $value;
        } else {
            throw new InvalidArgumentException('Unknown currency operation type: ' . $value);
        }
    }

    public function getOperationTypes()
    {
        return [
            self::OPERATION_PURCHASE => Yii::t('edm', 'Purchase of currency'),
            self::OPERATION_SELL => Yii::t('edm', 'Sale of currency'),
        ];
    }

    public function getApplicant(): ?ApplicantDataType
    {
        return $this->_applicant;
    }

    public function getDebitAccount(): ?AccountDataType
    {
        return $this->_debitAccount;
    }

    public function getCreditAccount(): ?AccountDataType
    {
        return $this->_creditAccount;
    }

    public function getCommissionAccount(): ?AccountDataType
    {
        return $this->_commissionAccount;
    }

    public function getOrganization()
    {
        // Получение организации операции с валютой
        // applicant->name это не имя, а ид организации!
        // называется name, потому что используется в форме визарда как название организации
        return DictOrganization::findOne($this->_applicant->name);
    }

    public function validateAmount($attribute = 'paymentOrderAmount', $params = [])
    {
        if (empty(floatval($this->paymentOrderAmount)) && empty(floatval($this->paymentOrderCurrAmount))) {
            $this->addError('paymentOrderAmount',	Yii::t('edm', 'One of amounts must be set'));
        }
    }

    public function validateActuality($attribute = 'date', $params = [])
    {
        $time = strtotime($this->$attribute);
        $now = strtotime(date('d.m.Y'));
        if ($time < $now) {
            $this->addError($attribute,	'Дата должна быть не меньше текущей');
        }
    }

    /**
     * Cвязь счета дебета со справочником счетов
     */
    public function getDebitAccountOrganizationName()
    {
        if (empty($this->organization)) {
            return '';
        }

        $organizationName = $this->organization->name;

        if (empty($this->getDebitAccount())) {
            return $organizationName;
        }

        $account = EdmPayerAccount::findOne(['number' => $this->getDebitAccount()->number]);

        if (!$account) {
            return $organizationName;
        }

        $organizationName = $account->getPayerName();

        return $organizationName;
    }

    public function getDebitAccountCurrency($attr = false)
    {
        return $this->getAccountCurrency($this->_debitAccount, $attr);
    }

    public function getCreditAccountCurrency($attr = false)
    {
        return $this->getAccountCurrency($this->_creditAccount, $attr);
    }

    private function getAccountCurrency($account, $attr = false)
    {
        if (empty($account)) {
            return null;
        }

        $account = EdmPayerAccount::findOne(['number' => $account->number]);

        if (empty($account)) {
            return null;
        }

        $currency = $account->edmDictCurrencies;

        if (empty($currency)) {
            return null;
        }

        if ($attr && $currency->hasAttribute($attr)) {
            return $currency->$attr;
        } else {
            return $currency;
        }
    }

    public function getOrganizationName(): ?string
    {
        return $this->_organizationName ?: $this->getDebitAccountOrganizationName();
    }

    public function setOrganizationName(?string $value): void
    {
        $this->_organizationName = $value;
    }
}

<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use common\base\BaseType;
use common\helpers\NumericHelper;
use Yii;

class ForeignCurrencySellTransit extends BaseType
{
    public $operationType = 'ForeignCurrencySellTransitAccount';
    public $number;
    public $date;
    public $organizationId;
    public $contactPersonName;
    public $contactPersonPhone;
    public $currencyIncomingNumber;
    public $currencyIncomingDate;
    public $bankBik;
    public $transitAccount;
    public $amount;
    public $foreignAccount;
    public $amountTransfer;
    public $amountSell;
    public $account;
    public $terminalId;
    public $commissionAccount;
    public $organizationAccount;
    public $sellOnMarket = false;
    public $profitToAccount = false;
    public $uuid = '';

    public function attributeLabels()
    {
        return [
            'number' => Yii::t('edm', 'Document number'),
            'date' => Yii::t('edm', 'Date'),
            'organizationId' => Yii::t('edm', 'Organization'),
            'contactPersonName' => 'Контактное лицо',
            'contactPersonPhone' => 'Телефон',
            'currencyIncomingNumber' => 'Уведомление о зачислении валюты №',
            'currencyIncomingDate' => 'Дата зачисления',
            'bankBik' => 'Банк',
            'transitAccount' => 'Транзитный счет',
            'amount' => 'Сумма зачисления',
            'foreignAccount' => 'Валютный счет',
            'amountTransfer' => 'Сумма перевода',
            'amountSell' => 'Сумма продажи',
            'account' => 'Расчетный счет',
            'commissionAccount' => 'Счет списания комиссии и расходов',
            'organizationAccount' => 'Расчетный счет организации',
            'sellOnMarket' => 'Продать валюту с транзитного счета на валютном рынке на сумму',
            'profitToAccount' => 'Средства от продажи зачислить на расчетный счет'
        ];
    }

    public function rules() {
        return [
            [
                ['number', 'date', 'organizationId', 'contactPersonName',
                    'contactPersonPhone', 'currencyIncomingNumber', 'currencyIncomingDate',
                    'transitAccount', 'amount', 'commissionAccount'],
                'required'
            ],
            [
                ['sellOnMarket', 'profitToAccount', 'bankBik', 'foreignAccount',
                    'amountTransfer', 'amountSell', 'account', 'organizationAccount'],
                'safe',
            ],
            ['contactPersonPhone', 'validatePhone'],
            [['foreignAccount', 'amountTransfer'], 'required', 'when'  => function($model) {
                return (empty($model->amountSell) && empty($model->account)) ||
                ($this->foreignAccount || $this->amountTransfer);
            }],
            [['account', 'amountSell'], 'required', 'when'  => function($model) {
                return !empty($model->sellOnMarket);
//                return (empty($model->foreignAccount) && empty($model->amountTransfer)) ||
//                ($this->account || $this->amountSell);
            }]
        ];
    }

    public function getOrganization()
    {
        return DictOrganization::findOne($this->organizationId);
    }

    public function getBank()
    {
        return DictBank::findOne(['bik' => $this->bankBik]);
    }

    public function getOrganizationAccountBankName()
    {
        $account = $this->getAccount($this->organizationAccount);

        if ($account && $account->bank) {
            return $account->bank->name;
        }

        return '';
    }

    public function getOrganizationAccountBank()
    {
        $account = $this->getAccount($this->organizationAccount);

        if ($account && $account->bank) {
            return $account->bank;
        }

        return '';
    }

    public function getForeignAccountBankName()
    {
        $account = $this->getAccount($this->foreignAccount);

        if ($account && $account->bank) {
            return $account->bank->name;
        }

        return '';
    }

    public function getAmountCurrency()
    {
        $account = EdmPayerAccount::findOne(['number' => $this->transitAccount]);

        if ($account && $account->edmDictCurrencies) {
            return $account->edmDictCurrencies->name;
        }

        return '';
    }

    public function getAmountTransferCurrency()
    {
        $account = EdmPayerAccount::findOne(['number' => $this->foreignAccount]);

        if ($account && $account->edmDictCurrencies) {
            return $account->edmDictCurrencies->name;
        }

        return '';
    }

    public function getAmountSellCurrency()
    {
        $account = EdmPayerAccount::findOne(['number' => $this->transitAccount]);

        if ($account && $account->edmDictCurrencies) {
            return $account->edmDictCurrencies->name;
        }

        return '';
    }

    public function getAmountInWords()
    {
        return NumericHelper::num2str($this->amount, $this->getAmountCurrency());
    }

    public function getAmountTransferInWords()
    {
        return NumericHelper::num2str($this->amountTransfer, $this->getAmountTransferCurrency());
    }

    public function getAmountSellInWords()
    {
        return NumericHelper::num2str($this->amountSell, $this->getAmountSellCurrency());
    }

    public function getAccount($number)
    {
        $account = EdmPayerAccount::findOne(['number' => $number]);

        return $account;
    }

    public function validatePhone($attribute, $params)
    {
        if (stristr($this->$attribute, '_')) {
            $this->addError($attribute, 'Необходимо полностью заполнить номер телефона');
        }
    }

    public function getSearchFields() {}

}
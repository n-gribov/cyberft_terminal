<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use common\base\BaseType;
use Yii;

class ForeignCurrencyConversion extends BaseType
{
    public $operationType = 'ForeignCurrencyConversion';
    //
    public $number;
    public $date;
    public $organizationId;
    public $contactPersonName;
    public $contactPersonPhone;
    public $debitAccount;
    public $creditAccount;
    public $debitAmount;
    public $creditAmount;
    public $commissionAccount;
    public $isNew = false;

    public function attributeLabels()
    {
        return [
            'number' => Yii::t('edm', 'Document number'),
            'date' => Yii::t('edm', 'Date'),
            'organizationId' => Yii::t('edm', 'Organization'),
            'contactPersonName' => 'Контактное лицо',
            'contactPersonPhone' => 'Телефон',
            'debitAccount' => 'Счет списания',
            'creditAccount' => 'Счет зачисления',
            'debitAmount' => 'Сумма списания',
            'creditAmount' => 'Сумма зачисления',
            'commissionAccount' => 'Комиссию и расходы банка списать со счёта',
        ];
    }

    public function rules() {
        return [
            [
                [
                    'number', 'date', 'organizationId', 'contactPersonName',
                    'contactPersonPhone', 'debitAccount', 'creditAccount',
                    'commissionAccount'],
                'required'
            ],
            [
                ['debitAmount', 'creditAmount'], 'safe',
            ],
            ['contactPersonPhone', 'validatePhone'],
            ['debitAmount', 'required', 'when'  => function($model) {
                return empty($model->creditAmount);
            }],
            ['creditAmount', 'required', 'when'  => function($model) {
                return empty($model->debitAmount);
            }]
        ];
    }

    public function validatePhone($attribute, $params)
    {
        if (stristr($this->$attribute, '_')) {
            $this->addError($attribute, 'Необходимо полностью заполнить номер телефона');
        }
    }

    public function getOrganization()
    {
        return DictOrganization::findOne($this->organizationId);
    }

    public function getDebitAccountCurrencyName()
    {
        $account = $this->getAccount($this->debitAccount);

        if ($account && $account->edmDictCurrencies) {
            return $account->edmDictCurrencies->name;
        } else {
            return '';
        }
    }

    public function getDebitAccountBankName()
    {
        $account = $this->getAccount($this->debitAccount);

        if ($account && $account->bank) {
            return $account->bank->name;
        } else {
            return '';
        }
    }

    public function getDebitAccountBankBik()
    {
        $account = $this->getAccount($this->debitAccount);

        if ($account && $account->bank) {
            return $account->bank->bik;
        } else {
            return '';
        }
    }

    public function getCreditAccountCurrencyName()
    {
        $account = $this->getAccount($this->creditAccount);

        if ($account && $account->edmDictCurrencies) {
            return $account->edmDictCurrencies->name;
        } else {
            return '';
        }
    }

    private function getAccount($number)
    {
        $account = EdmPayerAccount::findOne(['number' => $number]);

        return $account;
    }

        /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

}
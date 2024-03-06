<?php

namespace addons\edm\models\ForeignCurrencyOperation;

use common\base\BaseType;
use Yii;

class ForeignCurrencyPaymentType extends BaseType
{
    public $operationType = 'ForeignCurrencyPayment';
    //
    public $number;
    public $sum;
    public $date;
    public $currency;
    public $immediatePayment;
    public $payerAccount;
    public $payerInn;
    public $payerName;
    public $payerAddress;
    public $payerLocation;
    public $payerBank;
    public $payerBankName;
    public $payerBankAddress;
    public $intermediaryBank;
    public $intermediaryBankNameAndAddress;
    public $intermediaryBankAccount;
    public $beneficiaryAccount;
    public $beneficiary;
    public $beneficiaryAddress;
    public $beneficiaryLocation;
    public $beneficiaryBank;
    public $beneficiaryBankNameAndAddress;
    public $beneficiaryBankAccount;
    public $information;
    public $commission;
    public $commissionSum;
    public $commissionAccount;
    public $additionalInformation;
    public $uuid;
    //
    public $payerAccountSelect;
    public $beneficiaryAccountSelect;
    public $templateSelect;
    //
    public $saveTemplate;
    public $templateName;

    public $terminalId;

    // Виды комиссий
    // MT103
    const COMMISSION_OUR = 'OUR';
    const COMMISSION_BEN = 'BEN';
    const COMMISSION_SHA = 'SHA';
    // pain.001
    const COMMISSION_DEBT = 'DEBT';
    const COMMISSION_CRED = 'CRED';
    const COMMISSION_SHAR = 'SHAR';

    // Наименования видов комиссий
    public static function commissionLabels($index = null)
    {
        $labels = [
            self::COMMISSION_OUR => 'Комиссию Банка и других банков списать с нашего счета (OUR)',
            self::COMMISSION_BEN => 'Комиссию Банка и других банков вычесть из суммы перевода (BEN)',
            self::COMMISSION_SHA => 'Комиссию Банка списать с нашего счета, других банков - вычесть из суммы перевода (SHA)',
            self::COMMISSION_DEBT => 'Комиссию Банка и других банков списать с нашего счета (OUR)',
            self::COMMISSION_CRED => 'Комиссию Банка и других банков вычесть из суммы перевода (BEN)',
            self::COMMISSION_SHAR => 'Комиссию Банка списать с нашего счета, других банков - вычесть из суммы перевода (SHA)',
        ];
        if (!$index) {
            return $labels;
        }

        if (isset($labels[$index])) {
            return $labels[$index];
        }

        return $index;
    }

    public static function tableName()
    {
        return 'edmForeignCurrencyPayment';
    }

    public function attributeLabels()
    {
        return [
            'number' => 'Номер документа',
            'date' => 'Дата',
            'immediatePayment' => 'Срочность платежа',
            'sum' => 'Сумма',
            'payerAccount' => 'Счет плательщика',
            'payerInn' => 'ИНН',
            'payerName' => 'Наименование плательщика',
            'payerAddress' => 'Адрес плательщика',
            'payerLocation' => 'Местоположение плательщика',
            'payerBankName' => 'Банк плательщика',
            'intermediaryBank' => 'Банк посредник',
            'intermediaryBankNameAndAddress' => 'Наименование и адрес банка посредника',
            'intermediaryBankAccount' => 'Номер счета банка посредника',
            'beneficiaryBankAccount' => 'Номер счета банка получателя',
            'beneficiaryAccount' => 'Счет получателя',
            'beneficiary' => 'Получатель',
            'beneficiaryBank' => 'Банк получателя',
            'beneficiaryBankNameAndAddress' => 'Наименование и адрес банка получателя',
            'information' => 'Информация',
            'commission' => 'Комиссия',
            'additionalInformation' => 'Дополнительная информация',
            'currency' => 'Валюта',
            'commissionSum' => 'Расходы отправителя',
            'saveTemplate' => 'Сохранить как шаблон',
            'templateName' => 'Имя шаблона'
        ];
    }

    public function rules()
    {
        return [
            [
                [
                    'number', 'sum', 'date',
                    'payerAccount', 'payerInn',
                    'beneficiaryAccount',
                    'beneficiary',
                    'commission'
                ], 'required'
            ],
            [
                'date', 'match', 'pattern' => '/^\d{2}\.\d{2}\.\d{4}$/i'
            ],
            [
                'sum', 'double', 'min' => 1.00
            ],
            ['immediatePayment', 'string', 'max' => 4],
            [
                [
                    'immediatePayment', 'information', 'currency',
                    'additionalInformation', 'payerAccountSelect',
                    'beneficiaryAccountSelect', 'payerBank',
                    'payerBankName', 'payerBankAddress',
                    'intermediaryBank', 'intermediaryBankNameAndAddress', 'intermediaryBankAccount',
                    'beneficiaryBank', 'beneficiaryBankNameAndAddress', 'beneficiaryBankAccount',
                    'payerName', 'payerAddress', 'payerLocation', 'saveTemplate', 'templateName',
                    'templateSelect'
                ], 'safe'
            ],
            [
                'beneficiaryBank', 'required', 'when' => function($model) {
                    // swiftbic получателя должен быть заполнен, если данные не заполнены вручную
                    return empty($model->beneficiaryBankNameAndAddress);
                }
            ],
            [
                'templateName', 'required', 'when' => function($model) {
                    // Имя шаблона требуется к заполнению, если указана необходимость сохранения шаблона
                    return $model->saveTemplate;
                }
            ],
            ['payerAccount', 'organizationDataValidation'],
            ['beneficiaryAccount', 'string', 'max' => '34'],
            [['payerAddress', 'payerName', 'payerLocation'], 'string', 'max' => 35],
            [['beneficiary', 'beneficiaryBankNameAndAddress', 'intermediaryBankNameAndAddress', 
                'information'], 'multiLineValidation'],
            ['additionalInformation', 'additionalInformationValidation'],        
            ['number', 'string', 'max' => 16],
            ['beneficiaryAccount', 'match', 'pattern' => '/^[a-zA-Z0-9\-\s]*$/i'],
            [
                'commissionSum', 'number', 'min' => 0
            ],
        ];
    }

    /**
     * Метод возвращает поля для поиска в ElasticSearch
     * @return bool
     */
    public function getSearchFields()
    {
        return false;
    }

    /*
     * Проверка заполнения многострочных полей со строкой в 35 символов
     */
    public function multiLineValidation($attribute, $params)
    {        
        $trimmedValue = trim(preg_replace('/\s\s+/', '', $this->getAttributes()[$attribute]));
        if (strlen($trimmedValue) > 140){
            $this->addError($attribute, 'Длина поля превышает 140 символов!');            
        }
    }
    
    public function additionalInformationValidation($attribute, $params)
    {        
        $trimmedValue = trim(preg_replace('/\s\s+/', '', $this->getAttributes()[$attribute]));
        if (strlen($trimmedValue) > 210){
            $this->addError($attribute, 'Длина поля превышает 210 символов!');            
        }
    }

    /**
     * Проверка заполнения полей на латинском у организации, к которой относится счет плательщика
     */
    public function organizationDataValidation($attribute, $params)
    {
        if (empty($this->payerName) || empty($this->payerAddress) || empty($this->payerLocation)) {
            $this->addError($attribute, Yii::t('edm', 'Organization data must be in latin letters'));
        }
    }
    
    public function getImmediatePaymentDescription()
    {
        return static::getImmediatePaymentLabel($this->immediatePayment);
    }

    public static function getImmediatePaymentLabel(?string $value): string
    {
        switch ($value) {
            case 'URGP':
                return 'Срочный платеж';
            case 'NURG':
                return 'Обычный платеж';
            default:
                return '-';
        }
    }
}

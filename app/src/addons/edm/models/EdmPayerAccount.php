<?php

namespace addons\edm\models;

use addons\edm\jobs\ExportJob\ExportFormat;
use addons\edm\validators\CbrKeyingValidator;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Модель для счетов плательщиков
 * @property integer $id
 * @property string $name
 * @property string $organizationId
 * @property string $number
 * @property string $currencyId
 * @property string $bankBik
 * @property string $payerName
 * @property string $todaysStatementsExportFormat
 * @property string $previousDaysStatementsExportFormat
 * @property boolean $useIncrementalExportForTodaysStatements
 * @property integer $requireSignQty
 * @property DictOrganization $edmDictOrganization
 * @property string|null $terminalId
 * @property DictCurrency $edmDictCurrencies
 * @property DictBank $bank
 * @property AccountBalance|null $balance
 */
class EdmPayerAccount extends ActiveRecord
{
    public static function tableName()
    {
        return 'edmPayersAccounts';
    }

    public static $signaturesNumberOptions = [
        0 => 'Применяются общие настройки подписания',
        1 => 'Одна подпись',
        2 => 'Две подписи',
        3 => 'Три подписи',
        4 => 'Четыре подписи',
        5 => 'Пять подписей',
        6 => 'Шесть подписей',
        7 => 'Семь подписей',
    ];

    public function rules()
    {
        return [
            ['number', CbrKeyingValidator::className(), 'bikKey' => 'bankBik'],
            [['name', 'organizationId', 'number', 'currencyId', 'bankBik'], 'required'],
            [['organizationId', 'currencyId'], 'integer'],
            ['bankBik', 'string', 'min' => 9, 'max' => 9],
            ['name', 'string', 'max' => '255'],
            ['useIncrementalExportForTodaysStatements', 'boolean'],
            [['todaysStatementsExportFormat', 'previousDaysStatementsExportFormat'], 'string'],
            [['todaysStatementsExportFormat', 'previousDaysStatementsExportFormat'], 'default', 'value' => null],
            [
                [
                    'requireSignQty', 'payerName', 'todaysStatementsExportFormat', 'previousDaysStatementsExportFormat',
                    'useIncrementalExportForTodaysStatements',
                ],
                'safe'
            ],
            [
                'bankBik',
                'match',
                'pattern' => '/^[0-9]{9}$/',
                'message' => Yii::t('app', 'The {attribute} must contain only digits')
            ],
            ['number', 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'               => Yii::t('edm', 'Account name'),
            'organizationId'    => Yii::t('edm', 'Account owner'),
            'number'             => Yii::t('edm', 'Payer account number'),
            'currencyId'         => Yii::t('edm', 'Account currency'),
            'bankBik'             => Yii::t('edm', 'Account bank'),
            'payerName'             => Yii::t('edm', 'Payer name'),
            'requireSignQty' => Yii::t('edm', 'The number of personal signatures to send the document'),
            'useIncrementalExportForTodaysStatements' => Yii::t('edm', 'Export only new operations from current period statement'),
        ];
    }

    public function attributeHints()
    {
        return [
            'useIncrementalExportForTodaysStatements' => Yii::t('edm', 'This setting allows to create statement containing only new operation from current period statement')
        ];
    }

    /**
     * Связь со справочником организаций
     */
    public function getEdmDictOrganization()
    {
        return $this->hasOne(DictOrganization::className(), ['id' => 'organizationId']);
    }

    /**
     * Связь со справочником валют
     */
    public function getEdmDictCurrencies()
    {
        return $this->hasOne(DictCurrency::className(), ['id' => 'currencyId']);
    }

    /**
     * Связь со справочником банков
     */
    public function getBank()
    {
        return $this->hasOne(DictBank::className(), ['bik' => 'bankBik']);
    }

    /**
     * Получение терминала, к которому относится счет
     */
    public function getTerminalId()
    {
        $bank = $this->bank;

        if ($bank && $bank->terminalId) {
            return $bank->terminalId;
        } else {
            return null;
        }
    }

    /**
     * Удаление точек из маски поля ввода номера счета
     * @return bool
     */
    public function beforeValidate()
    {
        parent::beforeValidate();
        $this->number = str_replace('.', '', $this->number);

        return true;
    }

    /**
     * Удаление точек из маски поля ввода номера счета
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);
        $this->number = str_replace('.', '', $this->number);

        return true;
    }

    /**
     * Получение наименования плательщика
     * Либо из свойств самого счета
     * Либо из наименования организации, к которой привязан счет
     */
    public function getPayerName() {
        // Если индивидуальное наименование плательщика не указано
        if (empty($this->payerName)) {
            if ($this->edmDictOrganization) {
                return $this->edmDictOrganization->name;
            } else {
                // исключительная ситуация, не удается получить организацию, к которой привязан счет
                throw new \RuntimeException(Yii::t('edm', 'Failed to get account organization'));
            }
        } else {
            return $this->payerName;
        }
    }

    /**
     * Получение номера счета по его id
     */
    public static function getNumberById($id)
    {
        $model = static::findOne($id);

        return empty($model) ? null : $model->number;
    }

    public static function getStatementsExportFormatOptions()
    {
        return [
            ''                     => Yii::t('edm', "Don't export statements"),
            ExportFormat::ISO20022 => Yii::t('edm', 'Export statements to ISO20022'),
            ExportFormat::ONE_C    => Yii::t('edm', 'Export statements to 1C'),
        ];
    }

    public function getBalance()
    {
        return $this->hasOne(AccountBalance::class, ['accountNumber' => 'number']);
    }

    public function getAccountUsers(): ActiveQuery
    {
        return $this->hasMany(EdmPayerAccountUser::class, ['accountId' => 'id']);
    }
}

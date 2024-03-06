<?php

namespace addons\edm\models\ForeignCurrencyControl;

use addons\edm\models\DictCurrency;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $paymentType
 * @property string|null $number
 * @property string $docDate
 * @property string $codeFCO
 * @property string $operationDate
 * @property float $operationSum
 * @property int $currencyId
 * @property string|null $contractPassport
 * @property float|null $operationSumUnits
 * @property int|null $currencyUnitsId
 * @property string|null $refundDate
 * @property string|null $expectedDate
 * @property string|null $comment
 * @property int|null $notRequiredSection1
 * @property int |null$notRequiredSection2
 * @property string|null $contractNumber
 * @property string|null $contractDate
 * @property int $documentId
 * @property-read string|null $paymentDocumentNumber
 * @property-read DictCurrency|null $currencyUnits
 * @property-read string $paymentTypeTitle
 * @property-read DictCurrency|null $currency
 * @property-read null|string $currencyTitle
 * @property-read string $currencyUnitsCode
 * @property-read string $currencyCode
 * @property-read ForeignCurrencyOperationInformationExt $items
 */
class ForeignCurrencyOperationInformationItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'edmForeignCurrencyOperationInformationItem';
    }

    /** @var AttachedFileSession[] */
    public $attachedFiles = [];
    public $attachedFilesJson = '[]';

    public function attributeLabels()
    {
        return [
            'paymentType' => 'Признак платежа',
            'number' => 'Номер документа',
            'docDate' => 'Дата документа',
            'codeFCO' => 'Код вида ВО',
            'operationDate' => 'Дата операции',
            'operationSum' => 'Сумма операции',
            'currencyId' => 'Валюта',
            'contractPassport' => 'Уникальный номер контракта (кредитного договора)', // 'Паспорт сделки'
            'operationSumUnits' => 'Сумма операции в единицах контракта',
            'currencyUnitsId' => 'Валюта',
            'refundDate' => 'Срок возврата аванса',
            'expectedDate' => 'Ожидаемый срок',
            'comment' => 'Примечание',
            'notRequiredSection1' => 'Без номера',
            'notRequiredSection2' => 'Без номера',
            'contractNumber' => 'Номер контракта (кредитного договора)', // 'Контракт'
            'contractDate' => 'Дата', // 'Дата контракта',
            'paymentTypeTitle' => 'Признак платежа',
            'currencyTitle' => 'Валюта',
            'paymentDocumentNumber' => 'УНК или номер/дата договора',
            'docRepresentation' => 'Признак представления документов, связанных с проведением операций',
        ];
    }

    public function rules()
    {
        return [
            ['attachedFilesJson', 'string'],
            ['attachedFilesJson', 'validateAttachmentSize'],
            [
                [
                    'paymentType', 'codeFCO', 'docDate',
                    'operationDate', 'operationSum', 'currencyId',// 'docRepresentation'
                ], 'required'
            ],
            [
                [
                    'contractPassport', 'contractNumber', 'operationSumUnits',
                    'currencyUnitsId', 'refundDate', 'expectedDate',
                    'comment', 'notRequiredSection1', 'notRequiredSection2',
                ], 'safe'
            ],
            ['number', 'validationNumber', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['comment', 'string', 'max' => '255'],
            ['contractDate', 'validationContractDate', 'skipOnEmpty' => false, 'skipOnError' => false],
            [['informationId', 'documentId'], 'safe'],
            ['docRepresentation', 'integer'],
            [['number', 'contractNumber', 'contractPassport'], 'string', 'max' => 35],
            [['operationSum', 'operationSumUnits'], 'string', 'max' => 18],
        ];
    }

    public function validateAttachmentSize($attribute, $params = [])
    {
        return;
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        $this->attachedFiles = AttachedFileSession::createListFromJson($this->attachedFilesJson);

        return $result;
    }


    public function getItems()
    {
        $this->hasOne(ForeignCurrencyOperationInformationExt::className(), ['documentId' => 'documentId']);
    }

    public function getCurrency()
    {
        return $this->hasOne(DictCurrency::className(), ['id' => 'currencyId']);
    }

    public function getCurrencyUnits()
    {
        return $this->hasOne(DictCurrency::className(), ['id' => 'currencyUnitsId']);
    }

    /**
     * Наименование валюты операции
     * @return null|string
     */
    public function getCurrencyTitle()
    {
        return DictCurrency::getNameById($this->currencyId);
    }

    public function getPaymentTypeTitle()
    {
        return $this->paymentType == 1 ? 'Зачисление' : 'Списание';
    }

    public function validationNumber($attribute, $params)
    {
        if (empty($this->notRequiredSection1) && empty($this->number)) {
            $this->addError($attribute, Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('number')]));
        }
    }

    public function getCurrencyCode()
    {
        return $this->currency ? $this->currency->code : '';
    }

    public function getCurrencyUnitsCode()
    {
        return $this->currencyUnits ? $this->currencyUnits->code : '';
    }

    public function getPaymentDocumentNumber()
    {
        $out = '';
        if ($this->notRequiredSection2) {
            $out = 'БН';
        } else if ($this->contractPassport) {
            $out = $this->contractPassport;
        } else {
            $out = $this->contractNumber ?: 'БН';
        }

        return $out . '/' . $this->contractDate;
    }

    public function validationContractDate($attribute, $params)
    {
        if (!empty($this->contractNumber) && empty($this->contractDate)) {
            $this->addError($attribute, Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('contractDate')]));
        }
    }

}
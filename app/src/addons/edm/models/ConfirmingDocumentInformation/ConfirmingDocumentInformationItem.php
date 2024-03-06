<?php

namespace addons\edm\models\ConfirmingDocumentInformation;

use addons\edm\helpers\EdmHelper;
use common\helpers\Countries;
use common\models\listitem\AttachedFile;
use common\models\listitem\NestedListItem;
use yii\db\ActiveRecord;
use Yii;
use addons\edm\models\DictCurrency;

/**
 * Модель для счетов плательщиков
 * @property integer $id
 * @property integer $informationId
 * @property string $number
 * @property string $date
 * @property string $originalDocumentDate
 * @property string $originalDocumentNumber
 * @property bool $notRequiredNumber
 * @property string $code
 * @property float $sumDocument
 * @property float $sumContract
 * @property integer $currencyIdDocument
 * @property integer $currencyIdContract
 * @property string $type
 * @property string $expectedDate
 * @property string $countryCode
 * @property string $comment
 * @property int $documentId
 * @property string|null $typeLabel
 * @property string|null $codeLabel
 */
class ConfirmingDocumentInformationItem extends ActiveRecord
{
    /** @var AttachedFileSession[] */
    public $attachedFiles = [];
    public $attachedFilesJson = '[]';

    public static function tableName()
    {
        return 'edmConfirmingDocumentInformationItem';
    }

    public function attributeLabels()
    {
        return [
            'number' => Yii::t('edm', 'Number document'),
            'date' => Yii::t('edm', 'Date'),
            'originalDocumentDate' => Yii::t('edm', 'Original document date'),
            'originalDocumentNumber' => Yii::t('edm', 'Original document number'),
            'notRequiredNumber' => Yii::t('edm', 'Number is not required'),
            'code' => Yii::t('edm', 'Code confirming document information'),
            'sumDocument' => Yii::t('edm', 'Document amount'),
            'sumContract' => Yii::t('edm', 'Contract amount'),
            'currencyIdDocument' => Yii::t('edm', 'Document currency'),
            'currencyIdContract' => Yii::t('edm', 'Contract currency'),
            'type' => Yii::t('edm', 'Type confirming document information'),
            'expectedDate' => Yii::t('edm', 'Expected date'),
            'countryCode' => Yii::t('edm', 'Country code'),
            'comment' => Yii::t('edm', 'Comment'),
        ];
    }

    public function rules()
    {
        return [
            [['sumDocument', 'currencyIdDocument', 'code'], 'required'],
            [['documentId', 'date', 'notRequiredNumber',
                'sumContract', 'currencyIdContract',
                'expectedDate', 'comment', 'originalDocumentDate',
                'originalDocumentNumber'], 'safe'],
            ['number', 'string'],
            ['number', 'validationNumber', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['countryCode', 'validationCountryCode', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['type', 'validationType', 'skipOnEmpty' => false, 'skipOnError' => false],
            ['comment', 'string', 'max' => 500],
            ['attachedFilesJson', 'string'],
        ];
    }

    public function load($data, $formName = null)
    {
        $result = parent::load($data, $formName);
        $this->attachedFiles = AttachedFileSession::createListFromJson($this->attachedFilesJson);
        return $result;
    }

    /**
     * Наименование валюты документа
     * @return null|string
     */
    public function getCurrencyDocumentTitle()
    {
        return DictCurrency::getNameById($this->currencyIdDocument);
    }

    /**
     * Наименование валюты контракта
     * @return null|string
     */
    public function getCurrencyContractTitle()
    {
        return DictCurrency::getNameById($this->currencyIdContract);
    }

    public function validationNumber($attribute, $params)
    {
        if (empty($this->notRequiredNumber) && empty($this->number)) {
            $this->addError($attribute, Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => $this->getAttributeLabel('number')]));
        }
    }

    public function getCurrencyDocument()
    {
        return $this->hasOne(DictCurrency::className(), ['id' => 'currencyIdDocument']);
    }

    public function getCurrencyContract()
    {
        return $this->hasOne(DictCurrency::className(), ['id' => 'currencyIdContract']);
    }

    public function getCurrencyDocumentCode()
    {
        if ($this->currencyDocument) {
            return $this->currencyDocument->code;
        }

        return '';
    }

    public function getCurrencyContractCode()
    {
        if ($this->currencyContract) {
            return $this->currencyContract->code;
        }

        return '';
    }

    public function getNumericCountryCode()
    {
        return Countries::getNumericCode($this->countryCode);
    }

    /**
     * Геттер для получения значения типа для экспорта документа в xls
     * @return string
     */
    public function getCdiType()
    {
        return $this->type;
    }

    public function getCdiNumber()
    {
        return $this->number;
    }

    public function getCdiDate()
    {
        return $this->date;
    }

    /**
     * Валидация поля "Код страны" в зависимости от значения поля "Код вида подтверждающего документа"
     */
    public function validationCountryCode()
    {
        if (in_array($this->code, ['02_3', '02_4']) && empty($this->countryCode)) {
            $this->addError('countryCode', Yii::t('yii', '{attribute} cannot be blank.',
                ['attribute' => $this->getAttributeLabel('countryCode')]
            ));
        }
    }

    /**
     * Валидация поля "Признак поставки" в зависимости от значения поля "Код вида подтверждающего документа"
     */
    public function validationType()
    {
        $codes = ['01_3', '01_4', '02_3', '02_4', '03_3', '03_4', '04_3', '04_4', '15_3', '15_4'];

        if (in_array($this->code, $codes) && empty($this->type)) {
            $this->addError('type', Yii::t('yii', '{attribute} cannot be blank.',
                ['attribute' => $this->getAttributeLabel('type')]
            ));
        }
    }

    public function getTypeLabel()
    {
        $types = EdmHelper::getCdiTypes();
        return array_key_exists($this->type, $types)
            ? $types[$this->type]
            : null;
    }

    public function getCodeLabel()
    {
        $codes = EdmHelper::getCdiCodes();
        return array_key_exists($this->code, $codes)
            ? $codes[$this->code]
            : null;
    }

    public function loadAttachedFiles(array $attachedFiles): void
    {
        $this->attachedFiles = array_map(
            function (AttachedFile $attachedFile) {
                return AttachedFileSession::createFromAttachedFile($attachedFile);
            },
            $attachedFiles
        );
        $this->attachedFilesJson = NestedListItem::listToJson($this->attachedFiles);
    }
}

<?php

namespace addons\edm\models\ContractRegistrationRequest;

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictCurrency;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccountUser;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\Uuid;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Документ "Паспорт сделки"
 * Class ContractRegistrationRequest
 * @package addons\edm\models\ContractRegistrationRequest
 *
 * @property integer $id
 * @property string  $number
 * @property string  $date
 * @property string  $passportNumber
 * @property string  $passportType
 * @property string  passportTypeNumber
 * @property integer $organizationId
 * @property integer $amount
 * @property integer $currencyId
 * @property integer $signingDate
 * @property integer $completionDate
 * @property string  $existedPassport
 * @property integer $documentId
 * @property string  $inn
 * @property string  $kpp
 * @property string  ogrn
 * @property string  dateEgrul
 * @property string  state
 * @property string  city
 * @property string  street
 * @property string  building
 * @property string  district
 * @property string  locality
 * @property string  buildingNumber
 * @property string  apartment
 * @property string  creditedAccountsAbroad
 * @property string  repaymentForeignCurrencyEarnings
 * @property integer codeTermInvolvement
 * @property float   fixedRate
 * @property string  codeLibor
 * @property string  otherMethodsDeterminingRate
 * @property float   bonusBaseRate
 * @property string  otherPaymentsLoanAgreement
 * @property float   amountMainDebt
 * @property integer contractCurrencyId
 * @property string  reasonFillPaymentsSchedule
 * @property boolean directInvestment
 * @property float   amountCollateral
 * @property integer contractTypeCode
 */
class ContractRegistrationRequestExt extends ActiveRecord implements DocumentExtInterface
{
    // Типы паспорта сделки
    const PASSPORT_TYPE_LOAN = 'loan';
    const PASSPORT_TYPE_TRADE = 'trade';

    // Виды оснований заполнения графика платежей
    const REASON_PAYMENTS_SCHEDULE_LOAN = 'loan';
    const REASON_PAYMENTS_SCHEDULE_ANALYTICS = 'analytics';

    // Виртуальные поля для хранения связанных данных
    // Нерезиденты
    public $nonresidents = [];

    // Транши
    public $tranches = [];

    // График платежей
    public $paymentSchedule = [];

    // Нерезиденты-кредиторы
    public $nonresidentsCredit = [];

    // Виртуальное поле для индикации того,
    // что редактирование модели было произведено из формы
    public $modifyFromForm = false;

    public $type = 'ContractRegistrationRequest';

    public static function tableName()
    {
        return 'edm_contractRegistrationRequestExt';
    }

    public function rules()
    {
        return [
            [
                ['number', 'date', 'passportType', 'organizationId',
                    'signingDate', 'completionDate', 'ogrn',
                    'dateEgrul', 'state', 'inn', 'nonresidents', 'contractTypeCode',
                    'locality', 'district'
                ], 'required'
            ],
            [['passportNumber', 'amount', 'currencyId', 'existedPassport',
                'documentId', 'tranches', 'paymentSchedule', 'passportTypeNumber'], 'safe'],
            ['passportType', 'in', 'range' => array_keys(self::passportTypeLabels())],
            ['currencyId', 'exist', 'targetClass' => 'addons\edm\models\DictCurrency', 'targetAttribute' => 'id'],
            ['organizationId', 'exist', 'targetClass' => 'addons\edm\models\DictOrganization', 'targetAttribute' => 'id'],
            [['kpp', 'city', 'street', 'building', 'buildingNumber', 'apartment'], 'safe'],
            ['ogrn', 'string', 'max' => 13],
            ['ogrn', 'string', 'min' => 13],
            ['inn', 'string', 'max' => 12],
            ['kpp', 'string', 'max' => 9],
            [['locality', 'district', 'state', 'city'], 'string', 'max' => 35],
            ['buildingNumber', 'string', 'max' => 16],
            [['building', 'apartment', 'street'], 'string', 'max' => 70],
            ['passportNumber', 'unique'],
            [
                [
                    'creditedAccountsAbroad', 'repaymentForeignCurrencyEarnings',
                    'fixedRate', 'codeLibor', 'otherMethodsDeterminingRate', 'bonusBaseRate',
                    'otherPaymentsLoanAgreement', 'amountMainDebt', 'contractCurrencyId',
                    'reasonFillPaymentsSchedule', 'directInvestment', 'amountCollateral',
                ], 'safe'
            ],
            [
                ['codeTermInvolvement'], 'required',
                'when' => function($model) {
                    return ($model->passportType == self::PASSPORT_TYPE_LOAN);
                },
                'whenClient' => "function (attribute, value) {
					return $('#contractregistrationrequest-passporttype input:checked').val() == '"
                        . self::PASSPORT_TYPE_LOAN . "';
				}"
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' => Yii::t('edm', 'Number document'),
            'date' => Yii::t('edm', 'Date'),
            'passportNumber' => Yii::t('edm', 'Loan agreement (contract) unique number'),
            'passportType' => Yii::t('edm', 'Contract type'),
            'organizationId' => Yii::t('edm', 'Organization'),
            'amount' => Yii::t('edm', 'Amount'),
            'currencyId' => Yii::t('edm', 'Currency'),
            'signingDate' => Yii::t('edm', 'Signing date'),
            'completionDate' => Yii::t('edm', 'Completion date'),
            'existedPassport' => Yii::t('edm', 'Existed passport information'),
            'inn' => Yii::t('edm', 'INN'),
            'kpp' => Yii::t('edm', 'KPP'),
            'ogrn' => Yii::t('edm', 'OGRN'),
            'dateEgrul' => Yii::t('edm', 'Date of entry to EGRUL'),
            'state' => Yii::t('edm', 'State'),
            'city' => Yii::t('edm', 'City'),
            'street' => Yii::t('edm', 'Street'),
            'building' => Yii::t('edm', 'Building'),
            'district' => Yii::t('edm', 'District'),
            'locality' => Yii::t('edm', 'Locality'),
            'buildingNumber' => Yii::t('edm', 'Building number'),
            'apartment' => Yii::t('edm', 'Apartment'),
            'passportTypeNumber' => Yii::t('edm', 'Number'),
            'nonresidents' => Yii::t('edm', 'Details of the nonresidents'),
            'creditedAccountsAbroad' => Yii::t('edm', 'Credited to accounts abroad'),
            'repaymentForeignCurrencyEarnings' => Yii::t('edm', 'Repayment of foreign currency earnings'),
            'codeTermInvolvement' => Yii::t('edm', 'Code term involvement in (submission)'),
            'fixedRate' => Yii::t('edm', 'Fixed rate (% per annum)'),
            'codeLibor' => Yii::t('edm', 'Code LIBOR'),
            'otherMethodsDeterminingRate' => Yii::t('edm', 'Other methods of determining % rate'),
            'bonusBaseRate' => Yii::t('edm', 'Bonus to base interest rate (% per annum)'),
            'otherPaymentsLoanAgreement' => Yii::t('edm', 'Other payments provided by the loan agreement'),
            'amountMainDebt' => Yii::t('edm', 'The amount of the main debt on the date preceding the date of registration of the CRR'),
            'contractCurrencyId' => Yii::t('edm', 'Contract currency code'),
            'reasonFillPaymentsSchedule' => Yii::t('edm', 'The reason of the fill schedule of payments'),
            'directInvestment' => Yii::t('edm', 'Mark there are relations of direct investment'),
            'amountCollateral' => Yii::t('edm', 'The amount of collateral or other support'),
            'contractTypeCode' => Yii::t('edm', 'Contract type code')
        ];
    }

    /**
     * Заголовки типов паспорта сделки
     * @return array
     */
    public static function passportTypeLabels()
    {
        return [
            self::PASSPORT_TYPE_TRADE => Yii::t('edm', 'Trading contract'),
            self::PASSPORT_TYPE_LOAN => Yii::t('edm', 'Loan agreement'),
        ];
    }

    /**
     * Заголовки оснований заполнения графика платежей
     * @return array
     */
    public static function reasonPaymentScheduleLabels()
    {
        return [
            self::REASON_PAYMENTS_SCHEDULE_LOAN => Yii::t('edm', 'Details from loan'),
            self::REASON_PAYMENTS_SCHEDULE_ANALYTICS => Yii::t('edm', 'Analytics details')
        ];
    }

    /**
     * Получение заголовка типа паспорта сделки
     * @return null
     */
    public static function getPassportTypeLabel($type)
    {
        $types = self::passportTypeLabels();

        if (isset($types[$type])) {
            return $types[$type];
        }

        return null;
    }

    /**
     * Загрузка списка связанных данных по типу
     * @param $type
     * @param $data
     * @param bool|false $required
     * @return bool
     */
    public function loadRelativeData($type, $data, $required = false)
    {
        // Данные для записи в модель
        $newData = [];

        // Если список обязателен для заполнения и он пустой
        if ($required && empty($data)) {
            $this->addError($type, null);
            return false;
        }

        // Загрузка данных списка в модель
        foreach($data as $item) {
            $item->validate();
            $newData[Uuid::generate()] = $item;
        }

        $this->$type = $newData;
    }

    /**
     * Получение объекта валюты документа
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(DictCurrency::className(), ['Id' => 'currencyId']);
    }

    /**
     * Получение объекта валюты контракта
     * @return \yii\db\ActiveQuery
     */
    public function getContractCurrency()
    {
        return $this->hasOne(DictCurrency::className(), ['Id' => 'contractCurrencyId']);
    }

    /**
     * Получение связанного документа
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['Id' => 'documentId']);
    }

    /**
     * Получение связанной организации
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(DictOrganization::className(), ['Id' => 'organizationId']);
    }

    /**
     * Получение списка нерезидентов
     * @return \yii\db\ActiveQuery
     */
    public function getNonresidentsItems()
    {
        return $this->hasMany(ContractRegistrationRequestNonresident::className(), ['documentId' => 'documentId'])->andWhere(['isCredit' => false]);
    }

    /**
     * Получение списка нерезидентов-кредиторов
     * @return \yii\db\ActiveQuery
     */
    public function getNonresidentsCreditItems()
    {
        return $this->hasMany(ContractRegistrationRequestNonresident::className(), ['documentId' => 'documentId'])->andWhere(['isCredit' => true]);
    }

    /**
     * Получение списка траншей
     * @return \yii\db\ActiveQuery
     */
    public function getTranchesItems()
    {
        return $this->hasMany(ContractRegistrationRequestTranche::className(), ['documentId' => 'documentId']);
    }

    /**
     * Получение графика платежей
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentScheduleItems()
    {
        return $this->hasMany(ContractRegistrationRequestPaymentSchedule::className(), ['documentId' => 'documentId']);
    }

    /**
     * Создание связанных с документом данных
     * @param bool $insert
     * @param array $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        // Удаляем и перезаписываем связанные данные,
        // только если они были подготовлены

        $relatedData = [
            'nonresidents' => [
                'class' => ContractRegistrationRequestNonresident::className(),
                'params' => [
                    'isCredit' => false
                ]
            ],
            'tranches' => [
                'class' => ContractRegistrationRequestTranche::className(),
                'params' => []
            ],
            'paymentSchedule' => [
                'class' => ContractRegistrationRequestPaymentSchedule::className(),
                'params' => []
            ],
            'nonresidentsCredit' => [
                'class' => ContractRegistrationRequestNonresident::className(),
                'params' => [
                    'isCredit' => true
                ]
            ],
        ];

        foreach($relatedData as $relatedDataId => $data) {
            // Если модель изменена не из формы,
            // то пропускаем процедуру записи связанных данных
            if ($this->modifyFromForm == false) {
                continue;
            }

            // Удаление связанных записей
            $conditionParams = array_merge(
                ['documentId' => $this->documentId],
                $data['params']
            );

            $data['class']::deleteAll($conditionParams);

            // Запись новых значений связанных данных
            foreach($this->$relatedDataId as $relatedDataItem) {
                $relatedDataItem->id = null;
                $relatedDataItem->isNewRecord = true;
                $relatedDataItem->documentId = $this->documentId;
                // Сохранить модель в БД
                $relatedDataItem->save();
            }
        }

        return true;
    }

    /**
     * Перед удалением паспорта сделки, удаляем привязанные к нему данные
     * @return bool
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        self::deleteRelatedData($this->documentId);

        return true;
    }

    /**
     * Удаление связанных с моделью данных
     * @param $id
     */
    public static function deleteRelatedData($id)
    {
        $relatedData = [
            ContractRegistrationRequestPaymentSchedule::className(),
            ContractRegistrationRequestNonresident::className(),
            ContractRegistrationRequestTranche::className()
        ];

        foreach($relatedData as $item) {
            $item::deleteAll(['documentId' => $id]);
        }
    }

    /**
     * Создание номера паспорта сделки
     * Только для новых документов, у которых номер еще не заполнен
     */
    public function generatePassportNumber()
    {
        if ($this->isNewRecord && empty($this->passportNumber)) {
            // Шаблон номера паспорта сделки
            $template = 'AABBCCCC/0077/0000/D/0';

            // Текущие месяц и год
            $curYear = date('y', strtotime($this->date));
            $curMonth = date('m', strtotime($this->date));

            $template = str_replace('AA', $curYear, $template);
            $template = str_replace('BB', $curMonth, $template);

            // Количество паспортов сделок за текущий месяц
            $count = self::find()
                ->where(['like', 'date', ".{$curMonth}."])
                ->andWhere(['contractTypeCode' => $this->contractTypeCode])
                ->count();

            // Новый номер
            $count++;

            // Дополняем нулями до вида XXXX
            while(strlen($count) < 4) {
                $count = "0" . $count;
            }

            $template = str_replace('CCCC', $count, $template);

            // Тип вида контракта
            $template = str_replace('D', $this->contractTypeCode, $template);

            $this->passportNumber = $template;
        }
    }

    /**
     * Очистка значений полей, характерных для типа 'Кредитный договор'
     */
    public function clearLoanAttributes()
    {
        $loanAttributes = [
            'creditedAccountsAbroad', 'repaymentForeignCurrencyEarnings',
            'codeTermInvolvement', 'fixedRate', 'codeLibor', 'otherMethodsDeterminingRate',
            'bonusBaseRate', 'otherPaymentsLoanAgreement', 'amountMainDebt',
            'contractCurrencyId', 'directInvestment', 'amountCollateral'
        ];

        foreach($loanAttributes as $attribute) {
            $this->$attribute = '';
        }
    }

    public function getOrganizationBankName()
    {
        $bank = EdmHelper::getOrganizationBank($this->organization);

        return $bank->name;
    }

    public function getOrganizationName()
    {
        return $this->organization->name;
    }

    /**
     * Обработка получения свойств
     * для удобного экспорта в 1с
     * @param string $name
     * @return mixed|void
     */
    public function __get($name)
    {
        if (strpos($name, 'passportNumberByIndex') !== false) {
            $index = str_replace('passportNumberByIndex_', '', $name);
            return $this->passportNumber[$index];
        } else if (strpos($name, 'existedPassportByIndex') !== false) {
            if ($this->existedPassport) {
                $index = str_replace('existedPassportByIndex_', '', $name);
                return $this->existedPassport[$index];
            } else {
                return '';
            }
        } else if (strpos($name, 'ogrnByIndex') !== false) {
            $index = str_replace('ogrnByIndex_', '', $name);
            return $this->ogrn[$index];
        } else if (strpos($name, 'egrulByIndex') !== false) {
            $index = str_replace('egrulByIndex_', '', $name);
            return $this->dateEgrul[$index];
        } else if (strpos($name, 'innByIndex') !== false) {
            $index = str_replace('innByIndex_', '', $name);
            return $this->inn[$index];
        } else if (strpos($name, 'kppByIndex') !== false) {
            $index = str_replace('kppByIndex_', '', $name);
            return $this->inn[$index];
        }

        return parent::__get($name);
    }

    public function getPassportTypeNumberPrintable()
    {
        return $this->passportTypeNumber ?: 'БН';
    }

    public function getAmountPrintable()
    {
        return $this->amount ?: 'БС';
    }

    public function getCurrencyDescription()
    {
        return $this->currency ? $this->currency->description : '';
    }

    public function getCurrencyCode()
    {
        return $this->currency ? $this->currency->code : '';
    }

    public function getCurrencyName()
    {
        return $this->currency ? $this->currency->name : '';
    }

    public function getContractCurrencyName()
    {
        return $this->contractCurrency ? $this->contractCurrency->name : '';
    }

    public function getCodeLiborPrintable()
    {
        if (isset(EdmHelper::fccLiborCodes()[$this->codeLibor])) {
            return EdmHelper::fccLiborCodes()[$this->codeLibor];
        } else {
            return $this->codeLibor;
        }
    }

    public function getReasonFillLoanPrintable()
    {
        if ($this->reasonFillPaymentsSchedule == self::REASON_PAYMENTS_SCHEDULE_LOAN) {
            return '+';
        } else {
            return '';
        }
    }

    public function getReasonFillAnalyticsPrintable()
    {
        if ($this->reasonFillPaymentsSchedule == self::REASON_PAYMENTS_SCHEDULE_ANALYTICS) {
            return '+';
        } else {
            return '';
        }
    }

    public function getDirectInvestmentPrintable()
    {
        return $this->directInvestment ? '+' : '';
    }

    public static function getRelatedPassportNumbers()
    {
        $terminalId = Yii::$app->exchange->getPrimaryTerminal()->id;

        $documents = Document::find()->select('id')->where(
            [
                'terminalId' => $terminalId,
                'type' => 'auth.018'
            ]
        )->asArray()->all();

        $documentsIds = ArrayHelper::getColumn($documents, 'id');

        return ContractRegistrationRequestExt::find()
            ->select('passportNumber')
            ->where(['documentId' => $documentsIds])
            ->asArray();
    }

    public function loadContentModel($model)
    {
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return EdmPayerAccountUser::userCanSingDocumentsForBankTerminal($user->id, $document->receiver);
    }
}

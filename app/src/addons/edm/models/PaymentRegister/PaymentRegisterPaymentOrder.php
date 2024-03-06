<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\base\BaseType;
use common\base\interfaces\AttrShortcutInterface;
use common\document\Document;
use common\helpers\DateHelper;
use common\helpers\DocumentHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "edm_paymentOrder".
 *
 * @property integer $id
 * @property integer $registerId
 * @property string $body
 * @property integer $number
 * @property string $date
 * @property double $sum
 * @property string $beneficiaryName
 * @property string $beneficiaryCheckingAccount
 * @property string $payerAccount
 * @property string $currency
 * @property string $payerName
 * @property string $paymentPurpose
 * @property string $dateProcessing
 * @property string $dateDue
 * @property string $status
 * @property integer $terminalId
 * @property string $businessStatus
 * @property string $businessStatusDescription
 * @property string $businessStatusComment
 * @property string $uuid
 * @property bool $updated
 * @property Document $paymentRegister
 */
class PaymentRegisterPaymentOrder extends ActiveRecord implements AttrShortcutInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edm_paymentOrder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['registerId', 'terminalId', 'number'], 'integer'],
            [['body'], 'string'],
            [['sum'], 'number'],
            [['beneficiaryName', 'payerName', 'paymentPurpose', 'uuid'], 'string', 'max' => 255],
            [['beneficiaryCheckingAccount'], 'string', 'max' => 30],
            [['currency'], 'string', 'max' => 4],
            [['date', 'dateProcessing', 'dateDue', 'payerAccount', 'businessStatus',
                'businessStatusDescription', 'businessStatusComment'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('edm', 'ID'),
            'registerId'      => Yii::t('edm', 'Register ID'),
            'body'            => Yii::t('edm', 'Body'),
            'number'          => Yii::t('edm', 'Document number'),
            'date'            => Yii::t('edm', 'Date'),
            'sum'             => Yii::t('edm', 'Sum'),
            'beneficiaryName' => Yii::t('edm', 'Beneficiary'),
            'beneficiaryCheckingAccount' => Yii::t('edm', 'Beneficiary Account'),
            'payerAccount'    => Yii::t('edm', 'Payer account'),
            'currency'        => Yii::t('edm', 'Currency'),
            'payerName'       => Yii::t('edm', 'Payer'),
            'paymentPurpose'  => Yii::t('edm', 'Payment Purpose'),
            'dateProcessing'  => Yii::t('doc', 'Date of receipt to the bank'),
            'dateDue'         => Yii::t('doc', 'Due date'),
            'businessStatus'  => Yii::t('doc', 'Execution status'),
            'businessStatusTranslation'  => Yii::t('edm', 'Business status'),
            'businessStatusDescription' => Yii::t('edm', 'Business status description'),
            'businessStatusComment' => Yii::t('edm', 'Business status comment'),
            'terminalId'  => Yii::t('app/terminal', 'Terminal ID'),
        ];
    }

    public function attributeLabelShortcuts()
    {
        return [
            'number'    => Yii::t('edm', 'DN'),
        ];
    }

    public function getBusinessStatusTranslation()
    {
        $labels = DocumentHelper::getBusinessStatusesList();

        return isset($labels[$this->businessStatus]) ? $labels[$this->businessStatus] : $this->businessStatus;
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (!empty($this->dateProcessing)) {
            $this->dateProcessing = DateHelper::convert($this->dateProcessing,
                'datetime');
        }

        if (!empty($this->dateDue)) {
            $this->dateDue = DateHelper::convert($this->dateDue, 'datetime');
        }

        /**
         * Из 1С может прийти сумма в кривом формате с запятой вместо точки, что нарушит валидацию
         */
        $this->sum = str_replace(',', '.', $this->sum);

        return parent::beforeValidate();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!empty($this->dateProcessing)) {
            $this->dateProcessing = DateHelper::convert($this->dateProcessing,
                'datetime');
        }

        if (!empty($this->dateDue)) {
            $this->dateDue = DateHelper::convert($this->dateDue, 'datetime');
        }

        return parent::beforeSave($insert);
    }

    /**
     * Load from type model
     *
     * @param BaseType $model Content model
     */
    public function loadFromTypeModel($model)
    {
        if ($model instanceof PaymentOrderType) {
            $modelDate = $model->date;

            /**
             * @todo выяснить нормальные форматы дат
             */
            $date = strtotime($modelDate);
            if (empty($date)) {
                // Возможно, дата была в формате YYYY.mm.dd, strtotime его не понимает
                $date = strtotime(str_replace('.', '-', $modelDate));
            }
            $this->number          = $model->number;
            $this->date            = date("Y-m-d", $date);
            $this->sum             = $model->sum;
            $this->beneficiaryCheckingAccount = $model->beneficiaryCheckingAccount;
            $this->payerAccount    = $model->payerCheckingAccount;
            $this->paymentPurpose  = $model->paymentPurpose;
            $this->currency        = $model->currency;
            $this->terminalId      = $model->terminalId;
            $this->uuid = $model->documentExternalId;
            $this->body = (string) $model;

            // Подстановка корректных наименований плательщика и получателя
            if ($model->payerName1) {
                $this->payerName = $model->payerName1;
            } else {
                $this->payerName = $model->payerName;
            }

            if ($model->beneficiaryName1) {
                $this->beneficiaryName = $model->beneficiaryName1;
            } else {
                $this->beneficiaryName = $model->beneficiaryName;
            }
        }
    }

    public function getPaymentRegister()
    {
        return $this->hasOne(Document::className(), ['id' => 'registerId']);
    }

    public static function updateBusinessStatusesByRegister($registerId, $statusCode, $statusDescription, $statusComment): int
    {
        $paymentOrders = PaymentRegisterPaymentOrder::findAll(['registerId' => $registerId]);
        foreach ($paymentOrders as $paymentOrder) {
            $paymentOrder->updateBusinessStatus($statusCode, $statusDescription, $statusComment);
        }
        return count($paymentOrders);
    }

    public function updateBusinessStatus($statusCode, $statusDescription, $statusComment): bool
    {
        $isNewStatus = $this->businessStatus != $statusCode
            || $this->businessStatusDescription != $statusDescription
            || $this->businessStatusComment != $statusComment;

        if (!$isNewStatus) {
            return false;
        }

        // если дата поступления в банк не заполнена,
        // указываем текущую дату
        if (empty($this->dateProcessing)) {
            $this->dateProcessing = date('Y-m-d H:i:s');
        }

        if ($statusCode === 'ACSC') {
            $this->dateDue = date('Y-m-d H:i:s');
        } else { //if ($status == 'RJCT') {
            // если документ пришел с RJCT, очищаем дату исполнения
            // очищаем вообще, если не ACSC (CYB-3602)
            $this->dateDue = null;
        }

        // Заполнение информации о бизнес-статусе
        $this->businessStatus = $statusCode;
        $this->businessStatusDescription = $statusDescription;
        $this->businessStatusComment = $statusComment;

        $isSaved = $this->save(false);
        if (!$isSaved) {
            Yii::info('Failed to update payment order business status, errors: ' . var_export($this->getErrors(), true));
        }
        return $isSaved;
    }
}


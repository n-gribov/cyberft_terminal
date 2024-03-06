<?php
namespace addons\edm\models\ForeignCurrencyOperation;

use addons\edm\models\DictCurrency;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBCurrBuy\VTBCurrBuyType;
use addons\edm\models\VTBCurrConversion\VTBCurrConversionType;
use addons\edm\models\VTBCurrSell\VTBCurrSellType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBTransitAccPayDoc\VTBTransitAccPayDocType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\User;
use common\models\vtbxml\documents\CurrBuy;
use common\models\vtbxml\documents\CurrConversion;
use common\models\vtbxml\documents\CurrSell;
use common\models\vtbxml\documents\PayDocCur;
use common\models\vtbxml\documents\TransitAccPayDoc;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documentExtEdmForeignCurrencyOperation".
 *
 * @property integer $id
 * @property integer $documentId
 * @property integer $numberDocument
 * @property string $date
 * @property string $debitAccount
 * @property string $creditAccount
 * @property string $currency
 * @property string $currencySum
 * @property string $extStatus
 * @property string $sum
 * @property string $businessStatus
 * @property string $businessStatusDescription
 * @property string $businessStatusComment
 * @property string $documentType
 * @property string $debitAmount
 * @property string $creditAmount
 * @property string $payer
 * @property string $dateProcessing
 * @property string $dateDue
 * @property string $uuid
 * @property string $paymentPurpose
 */
class ForeignCurrencyOperationDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtEdmForeignCurrencyOperation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numberDocument'], 'required'],
            [['sum', 'currencySum', 'numberDocument'], 'number'],
            [['currency'], 'string', 'max' => 3],
            [['extStatus', 'payer', 'dateDue', 'dateProcessing', 'uuid'], 'string'],
            [['debitAccount'], 'integer'],
            [['businessStatus', 'businessStatusDescription', 'businessStatusComment', 'beneficiary'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('edm', 'ID'),
            'numberDocument' => Yii::t('edm', 'Number document'),
            'date' => Yii::t('edm', 'Document date'),
            'payer' => Yii::t('edm', 'Business name'),
            'debitAccount' => Yii::t('edm', 'Debiting account'),
            'creditAccount' => Yii::t('edm', 'Crediting account'),
            'currency' => Yii::t('edm', 'Currency'),
            'sum' => Yii::t('edm', 'Rouble amount'),
            'currencySum' => Yii::t('edm', 'Currency amount'),
            'extStatus' => Yii::t('edm', 'Status'),
            'businessStatus' => Yii::t('edm', 'Business status'),
            'beneficiary' => Yii::t('edm', 'Beneficiary'),
            'dateDue' => Yii::t('document', 'Due date'),
            'dateProcessing'  => Yii::t('document', 'Date of receipt to the bank'),
        ];
    }

    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document', ['id' => 'documentId'])->one();
        }

        return $this->_document;
    }

    public function loadContentModel($model)
    {
        if ($model instanceof ForeignCurrencyOperationType) {
            $this->numberDocument = empty($model->numberDocument) ? '0' : $model->numberDocument;
            $this->date = date('Y-m-d', strtotime($model->date));
            $this->debitAccount = $model->debitAccount->number;
            $this->creditAccount = $model->creditAccount->number;
            $this->currency = $model->paymentOrderCurrCode;
            $this->sum = $model->paymentOrderAmount;
            $this->currencySum = $model->paymentOrderCurrAmount;
            //$this->beneficiary = $model->beneficiary;
        } else if ($model instanceof ForeignCurrencyPaymentType) {
            $this->numberDocument = empty($model->number) ? '0' : $model->number;
            $this->date = date('Y-m-d', strtotime($model->date));
            $this->debitAccount = $model->payerAccount;
            $this->creditAccount = $model->beneficiaryAccount;
            $this->currency = $model->currency;
            $this->sum = $model->sum;
            $this->currencySum = $model->sum;
            $this->beneficiary = $model->beneficiary;
            $this->paymentPurpose = $model->additionalInformation;
            $this->uuid = $model->uuid;
        } else if ($model instanceof VTBPayDocCurType) {
            /** @var PayDocCur $vtbDocument */
            $vtbDocument = $model->document;
            $currency = DictCurrency::findOne(['code' => $vtbDocument->CURRCODETRANSFER]);

            $this->numberDocument = $vtbDocument->DOCUMENTNUMBER ?: '0';
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;
            $this->debitAccount = $vtbDocument->PAYERACCOUNT;
            $this->creditAccount = $vtbDocument->BENEFICIARACCOUNT;
            $this->currency = $currency !== null ? $currency->name : null;
            $this->sum = $vtbDocument->AMOUNTTRANSFER;
            $this->currencySum = $vtbDocument->AMOUNTTRANSFER;
            $this->beneficiary = $vtbDocument->BENEFICIAR;
            $this->paymentPurpose = $vtbDocument->PAYMENTSDETAILS;
        } else if ($model instanceof VTBCurrSellType) {
            /** @var CurrSell $vtbDocument */
            $vtbDocument = $model->document;
            $currency = DictCurrency::findOne(['code' => $vtbDocument->CURRCODEDEBET]);

            $this->numberDocument = $vtbDocument->DOCUMENTNUMBER ?: '0';
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;
            $this->debitAccount = $vtbDocument->ACCOUNTDEBET;
            $this->creditAccount = $vtbDocument->ACCOUNTCREDIT;
            $this->currency = $currency !== null ? $currency->name : null;
            $this->sum = $vtbDocument->AMOUNTCREDIT;
            $this->currencySum = $vtbDocument->AMOUNTDEBET;
        } else if ($model instanceof VTBCurrBuyType) {
            /** @var CurrBuy $vtbDocument */
            $vtbDocument = $model->document;
            $currency = DictCurrency::findOne(['code' => $vtbDocument->CURRCODECREDIT]);

            $this->numberDocument = $vtbDocument->DOCUMENTNUMBER ?: '0';
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;
            $this->debitAccount = $vtbDocument->ACCOUNTDEBET;
            $this->creditAccount = $vtbDocument->ACCOUNTCREDIT;
            $this->currency = $currency !== null ? $currency->name : null;
            $this->sum = $vtbDocument->AMOUNTDEBET;
            $this->currencySum = $vtbDocument->AMOUNTCREDIT;
        } else if ($model instanceof VTBTransitAccPayDocType) {
            /** @var TransitAccPayDoc $vtbDocument */
            $vtbDocument = $model->document;
            $currency = DictCurrency::findOne(['code' => $vtbDocument->CURRCODE]);

            $this->numberDocument = $vtbDocument->DOCUMENTNUMBER ?: '0';
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;
            $this->debitAccount = $vtbDocument->ACCOUNTTRANSIT;
            $this->creditAccount = $vtbDocument->RECEIVERCURRACCOUNT;
            $this->currency = $currency !== null ? $currency->name : null;
            $this->sum = $vtbDocument->AMOUNTCREDIT;
            $this->currencySum = $vtbDocument->AMOUNTDEBET;
        } else if ($model instanceof VTBCurrConversionType) {
            /** @var CurrConversion $vtbDocument */
            $vtbDocument = $model->document;
            $currency = DictCurrency::findOne(['code' => $vtbDocument->CURRCODEDEBET]);

            $this->numberDocument = $vtbDocument->DOCUMENTNUMBER ?: '0';
            $this->date = $vtbDocument->DOCUMENTDATE !== null ? $vtbDocument->DOCUMENTDATE->format('Y-m-d') : null;
            $this->debitAccount = $vtbDocument->ACCOUNTDEBET;
            $this->creditAccount = $vtbDocument->ACCOUNTCREDIT;
            $this->currency = $currency !== null ? $currency->name : null;;
            $this->sum = $vtbDocument->AMOUNTCREDIT;
            $this->currencySum = $vtbDocument->AMOUNTDEBET;
        }
    }

    public function getStatusLabel()
    {
        return (!is_null($this->extStatus) && array_key_exists($this->extStatus, Document::getStatusLabels()))
            ? Document::getStatusLabels()[$this->extStatus]
            : $this->extStatus;
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function getBusinessStatusTranslation()
    {
        $labels = DocumentHelper::getBusinessStatusesList();

        return isset($labels[$this->businessStatus]) ? $labels[$this->businessStatus] : $this->businessStatus;
    }

    public static function updateBusinessStatusesByRegister($registerId, $statusCode, $statusDescription, $statusComment): int
    {
        $paymentOrders = static::findAll(['documentId' => $registerId]);
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

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->debitAccount]);
        return $account !== null && EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id);
    }
}

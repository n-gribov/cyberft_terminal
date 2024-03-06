<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\SBBOLPayDocRu\SBBOLPayDocRuType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $documentId
 * @property integer $registerId
 * @property integer $accountId
 * @property string $date
 * @property double $sum
 * @property integer $count
 * @property integer $orgId
 * @property string $accountNumber
 * @property string $comment
 * @property string $statusComment
 * @property string $businessStatus
 * @property string $msgId
 * @property string $businessStatusComment
 * @property string $businessStatusDescription
 * @property string $businessStatusTranslation
 * @property-read string|null $payerName
 * @property-read string|null $payerBankName
 */
class PaymentRegisterDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    const STATUS_RECEIVED = 'RCVD';
    const STATUS_COMPLETELY_ACCEPTED = 'ACTC';
    const STATUS_ACCEPTED_FOR_PROCESSING = 'ACCP';
    const STATUS_REJECTED = 'RJCT';
    const STATUS_ACCEPTED = 'ACSP';
    const STATUS_PROCESSED = 'ACSC';
    const STATUS_PENDING = 'PDNG';
    const STATUS_PARTIALLY = 'PART';
    const STATUS_PARTIALLY_REJECTED = 'PRJT';
    const STATUS_PARTIALLY_ACCEPTED = 'PACP';
    const STATUS_PARTIALLY_PENDING = 'PPNG';

    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtEdmPaymentRegister';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId', 'registerId', 'count', 'accountId'], 'integer'],
            [['date', 'msgId'], 'safe'],
            [['sum', 'orgId'], 'number'],
            [
                [
                    'comment', 'statusComment', 'businessStatus',
                    'businessStatusTranslation', 'businessStatusDescription',
                    'businessStatusComment', 'accountNumber',
                ],
                'string'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('edm', 'ID'),
            'accountId' => Yii::t('edm', 'Account ID'),
            'accountNumber' => Yii::t('edm', 'Account'),
            'documentId' => Yii::t('edm', 'Document ID'),
            'registerId' => Yii::t('edm', 'Register ID'),
            'date' => Yii::t('edm', 'Date'),
            'sum' => Yii::t('edm', 'Sum'),
            'currency' => Yii::t('edm', 'Currency'),
            'count' => Yii::t('edm', 'Payment orders in the registry'),
            'comment' => Yii::t('edm', 'Comment'),
            'statusComment' => Yii::t('edm', 'Cause of status change'),
            'businessStatus' => Yii::t('edm', 'Business status'),
            'businessStatusTranslation' => Yii::t('edm', 'Business status'),
            'businessStatusDescription' => Yii::t('edm', 'Business status description'),
            'businessStatusComment' => Yii::t('other', 'Error description'),
            'payerName' => Yii::t('edm', 'Payer'),
            'payerBankName' => Yii::t('edm', 'Bank'),
        ];
    }

    public function loadContentModel($typeModel)
    {
        if ($typeModel instanceof PaymentRegisterType) {
            $this->loadContentPaymentRegister($typeModel);
        } else if($typeModel instanceof SBBOLPayDocRuType) {
            $this->loadContentSBBOLPayDocRu($typeModel);
        }
    }

    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document', ['id' => 'documentId']);
        }

        return $this->_document;
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    private static function businessStatusLabels()
    {
        return [
            self::STATUS_RECEIVED => Yii::t('edm', 'Received'),
            self::STATUS_COMPLETELY_ACCEPTED => Yii::t('edm', 'Completely accepted'),
            self::STATUS_REJECTED => Yii::t('edm', 'Rejected'),
            self::STATUS_ACCEPTED => Yii::t('edm', 'Accepted'),
            self::STATUS_ACCEPTED_FOR_PROCESSING => Yii::t('edm', 'Accepted for processing'),
            self::STATUS_PROCESSED => Yii::t('edm', 'Processed'),
            self::STATUS_PENDING => Yii::t('edm', 'Pending'),
            self::STATUS_PARTIALLY => Yii::t('edm', 'Partially'),
            self::STATUS_PARTIALLY_REJECTED => Yii::t('edm', 'Partially rejected'),
            self::STATUS_PARTIALLY_ACCEPTED => Yii::t('edm', 'Partially accepted'),
            self::STATUS_PARTIALLY_PENDING => Yii::t('edm', 'Partially pending')
        ];
    }

    public static function getBusinessStatusLabels()
    {
        return static::businessStatusLabels();
    }

    public function getBusinessStatusTranslation()
    {
        return static::translateBusinessStatus($this);
    }

    public static function translateBusinessStatus($model)
    {
        $labels = static::businessStatusLabels();

        return isset($labels[$model->businessStatus]) ? $labels[$model->businessStatus] : $model->businessStatus;
    }

    public static function getBusinessStatusByPaymentOrder($poStatus, $registerStatus)
    {
        $exceptForPART = [self::STATUS_PARTIALLY_REJECTED];
        $exceptForPACP = [self::STATUS_PARTIALLY_REJECTED, self::STATUS_PARTIALLY];
        $exceptForPPNG = [self::STATUS_PARTIALLY, self::STATUS_PARTIALLY_REJECTED, self::STATUS_PARTIALLY];

        if ($poStatus == self::STATUS_REJECTED) {
            return self::STATUS_PARTIALLY_REJECTED;
        } else if($poStatus == self::STATUS_PROCESSED && !in_array($registerStatus, $exceptForPART)) {
            return self::STATUS_PARTIALLY;
        } else if($poStatus == self::STATUS_ACCEPTED && !in_array($registerStatus, $exceptForPACP)) {
            return self::STATUS_PARTIALLY_ACCEPTED;
        } else if($poStatus == self::STATUS_PENDING && !in_array($registerStatus, $exceptForPPNG)) {
            return self::STATUS_PARTIALLY_PENDING;
        }

        return null;
    }

    /**
     * Загрузка данных из платежного поручения в формате Сбербанка
     * @param $typeModel
     */
    private function loadContentSBBOLPayDocRu(SBBOLPayDocRuType $typeModel)
    {
        $payDocRu = $typeModel->request->getPayDocRu();
        $this->date = $payDocRu->getAccDoc()->getDocDate()->format('Y-m-d');
        $this->sum = $payDocRu->getAccDoc()->getDocSum();
        $this->count = 1;
        $this->accountNumber = $payDocRu->getPayer()->getPersonalAcc();
        $this->msgId = $payDocRu->getDocExtId();

        if ($this->accountNumber) {
            $account = EdmPayerAccount::findOne(['number' => $this->accountNumber]);
            if ($account) {
                $this->accountId = $account->id;
                $this->orgId = $account->organizationId;

                $currency = $account->edmDictCurrencies;

                if ($currency) {
                    $this->currency = $currency->name;
                }
            }
        }
    }

    /**
     * Загрузка данных из реестра платежных поручений
     * @param $typeModel
     */
    private function loadContentPaymentRegister($typeModel)
    {
        $this->date = date('Y-m-d', strtotime($typeModel->date));
        $this->sum = $typeModel->sum;
        $this->count = $typeModel->count;
        $this->comment = $typeModel->comment;
        $this->currency = $typeModel->currency;
        $this->accountNumber = $typeModel->getAccountNumber();

        if ($this->accountNumber) {
            $account = EdmPayerAccount::findOne(['number' => $this->accountNumber]);
            if ($account) {
                $this->accountId = $account->id;
                $this->orgId = $account->organizationId;
            }
        }

        if (!empty($typeModel->storedRegistryId)) {
            $this->registerId = $typeModel->storedRegistryId;
        }
    }

    public function getPayerName(): ?string
    {
        $paymentOrderTypeModel = $this->getFirstPaymentOrderTypeModel();
        return $paymentOrderTypeModel ? $paymentOrderTypeModel->payerName : null;
    }

    public function getPayerBankName(): ?string
    {
        $paymentOrderTypeModel = $this->getFirstPaymentOrderTypeModel();
        return $paymentOrderTypeModel
            ? trim("{$paymentOrderTypeModel->payerBank1} {$paymentOrderTypeModel->payerBank2}")
            : null ;
    }

    private function getFirstPaymentOrderTypeModel(): ?PaymentOrderType
    {
        if (!$this->documentId) {
            return null;
        }
        $paymentOrder = PaymentRegisterPaymentOrder::findOne(['registerId' => $this->documentId]);
        if (!$paymentOrder) {
            return null;
        }
        $paymentOrderTypeModel = new PaymentOrderType();
        $paymentOrderTypeModel->loadFromString($paymentOrder->body);
        return $paymentOrderTypeModel;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->accountNumber]);
        return $account !== null && EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id);
    }
}

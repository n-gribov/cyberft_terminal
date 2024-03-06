<?php

namespace addons\edm\models\PaymentOrder;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use common\base\BaseType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\helpers\DateHelper;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documentExtPaymentOrder"
 *
 * @package addons
 * @subpackage edm
 *
 * @property integer  $id                  Row ID
 * @property string   $documentId          Document ID
 * @property integer  $number              Document number
 * @property float    $sum                 Document sum
 * @property string   $date                Document date
 * @property string   $beneficiaryName     Document beneficiary name
 * @property string   $currency            Document currency
 * @property string   $payerAccount        Document payer account
 * @property string   $paymentPurpose      Payment purpose
 * @property string   $payerName           Payer name
 * @property integer  $dateDue             Due date
 * @property integer  $dateProcessing      Processing date
 */
class PaymentOrderDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    /**
     * @var Document $_document Document
     */
    private $_document;
    private $_rawData;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtEdmPaymentOrder';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId'], 'required'],
            [['number'], 'integer'],
            [['sum'], 'double'],
            [['payerAccount', 'paymentPurpose', 'beneficiaryName', 'currency', 'payerName'], 'string'],
            [['date'], 'safe'],
        ];
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('edm', 'ID'),
            'documentId'      => Yii::t('edm', 'Document ID'),
            'number'          => Yii::t('edm', 'Number'),
            'date'            => Yii::t('edm', 'Date'),
            'sum'             => Yii::t('edm', 'Sum'),
            'beneficiaryName' => Yii::t('edm', 'Beneficiary'),
            'currency'        => Yii::t('edm', 'Currency'),
            'payerAccount'    => Yii::t('edm', 'Payer account'),
            'payerName'       => Yii::t('edm', 'Payer'),
            'paymentPurpose'  => Yii::t('edm', 'Payment Purpose'),
            'dateDue'         => Yii::t('doc', 'Due date'),
            'dateProcessing'  => Yii::t('doc', 'Date of receipt to the bank'),
        ];
    }

    /**
     * Load content model
     *
     * @param BaseType $model Content model
     */
    public function loadContentModel($model)
    {
        if ($model instanceof BaseType) {
            $this->number          = $model->number;
            $this->date            = date("Y-m-d", strtotime($model->date));
            $this->sum             = $model->sum;
            $this->beneficiaryName = $model->beneficiaryName;
            $this->payerAccount    = $model->payerCheckingAccount;
            $this->payerName       = $model->payerName;
            $this->paymentPurpose  = $model->paymentPurpose;
            $this->currency        = $model->currency;

            //$this->_rawData = $model->getCyberXmlBody();
        }
    }

    /**
     * Get document
     *
     * @return Document
     */
    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document',
                ['id' => 'documentId']);
        }

        return $this->_document;
    }

    public function isDocumentDeletable(User $user = null)
    {
        return false;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->payerAccount]);
        return $account !== null && EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id);
    }
}

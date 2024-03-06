<?php
/**
 * @obsolete
 * Нигде не используется, кроме последней миграции. Класс можно будет удалить
 * после полного перехода на новую архитектуру.
 */
namespace addons\edm\models\PaymentRegister;

use addons\edm\EdmModule;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\EdmPayerAccount;
use common\document\Document;
use common\document\DocumentPermission;
use common\helpers\DocumentHelper;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "edm_paymentRegister".
 *
 * @property integer $id
 * @property string $accountId
 * @property string $attachmentStorageId
 * @property string $bodyStorageId
 * @property double $sum
 * @property string $currency
 * @property integer $count
 * @property string $sender
 * @property string $status
 * @property string $dateCreate
 * @property string $dateUpdate
 * @property int $signaturesRequired
 * @property int $signaturesCount
 * @property string $statusComment
 * @property string $businessStatus
 * @property string $businessStatusDescription
 * @property text $businessStatusComment
 * @property integer $terminalId
 */
class PaymentRegisterAR extends ActiveRecord
{
    public static function tableName()
    {
        return 'edm_paymentRegister';
    }

    public function rules()
    {
        return [
            [['accountId', 'attachmentStorageId', 'bodyStorageId', 'count', 'terminalId'], 'integer'],
            [['sum'], 'number'],
            [['dateCreate', 'dateUpdate', 'statusComment'], 'safe'],
            [['currency'], 'string', 'max' => 4],
            [['sender'], 'string', 'max' => 32],
            [['status'], 'string', 'max' => 100],
            [['signaturesRequired', 'signaturesCount'], 'default', 'value' => 0],
            [['businessStatus', 'businessStatusDescription', 'businessStatusComment'], 'safe']
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
            'account' => Yii::t('edm', 'Account'),
            'attachmentStorageId' => Yii::t('edm', 'Attachment Storage ID'),
            'bodyStorageId' => Yii::t('edm', 'Body Storage ID'),
            'sum' => Yii::t('edm', 'Sum'),
            'currency' => Yii::t('edm', 'Currency'),
            'count' => Yii::t('edm', 'Payment orders in the registry'),
            'sender' => Yii::t('edm', 'Sender'),
            'status' => Yii::t('edm', 'Status'),
            'statusLabel' => Yii::t('edm', 'Status'),
            'dateCreate' => Yii::t('edm', 'Date Created'),
            'dateUpdate' => Yii::t('edm', 'Date Updated'),
            'signaturesRequired' => Yii::t('doc', 'Signatures required'),
            'signaturesCount' => Yii::t('doc', 'Signatures count'),
            'statusComment' => Yii::t('edm', 'Cause of status change'),
            'businessStatus' => Yii::t('edm', 'Business status'),
            'businessStatusTranslation' => Yii::t('edm', 'Business status'),
            'businessStatusDescription' => Yii::t('edm', 'Business status description'),
            'businessStatusComment' => Yii::t('other', 'Error description'),
            'terminalId'  => Yii::t('app/terminal', 'Terminal ID'),
        ];
    }

    public function getBusinessStatusTranslation()
    {
        $labels = DocumentHelper::getBusinessStatusesList();

        return isset($labels[$this->businessStatus]) ? $labels[$this->businessStatus] : $this->businessStatus;
    }

    public function getStatusLabels()
    {
        return [
            Document::STATUS_DELETED => Yii::t('app/message', 'Deleted'),
            Document::STATUS_EXPORTED => Yii::t('doc', 'Exported'),
            Document::STATUS_FORSIGNING => Yii::t('app/message', 'Waiting for signing'),
            Document::STATUS_SIGNING => Yii::t('app/message', 'Signing'),
            Document::STATUS_SIGNED => Yii::t('doc', 'Signed'),
            Document::STATUS_ACCEPTED => Yii::t('doc', 'Accepted'),
            Document::STATUS_SENT => Yii::t('app/message', 'Sent'),
            Document::STATUS_REGISTERED => Yii::t('app/message', 'Registered'),
            Document::STATUS_DELIVERED => Yii::t('app/message', 'Delivered'),
            Document::STATUS_REJECTED => Yii::t('app/message', 'Rejected'),
            Document::STATUS_SIGNING_REJECTED => Yii::t('app/message', 'Signing rejected'),
        ];
    }

    public function getStatusLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, $this->getStatusLabels())) ? $this->getStatusLabels()[$this->status]
                : $this->status;
    }

    public function isSignableByUserLevel()
    {
        if ($this->status == Document::STATUS_FORSIGNING && $this->signaturesCount < $this->signaturesRequired && $this->signaturesCount
            + 1 == Yii::$app->user->identity->signatureNumber) {

            return Yii::$app->user->can(
                DocumentPermission::SIGN,
                [
                    'serviceId' => EdmModule::SERVICE_ID,
                    'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
                ]
            );
        }

        return false;
    }

    public function isSignable()
    {
        return (Document::STATUS_FORSIGNING == $this->status && $this->signaturesCount < $this->signaturesRequired);
    }

    public function isSendable()
    {
        return (Document::STATUS_SIGNED == $this->status || Document::STATUS_ACCEPTED == $this->status);
    }

    public function getBody()
    {
        if ($this->bodyStorageId) {
            $storedFile = Yii::$app->storage->get($this->bodyStorageId);

            if ($storedFile) {
                return $storedFile->data;
            }
        }

        return null;
    }

    public function getAccount()
    {
        return $this->hasOne(EdmPayerAccount::className(), ['id' => 'accountId']);
    }

    public function getRecipient()
    {
        if ($this->account && isset($this->account->bank) && !empty($this->account->bank->terminalId)) {
            return $this->account->bank->terminalId;
        }

        return null;
    }

    /**
     * Получение основного документа, к которому относится реестр
     */
    public function getPrimaryDocument()
    {
        $extModel = PaymentRegisterDocumentExt::findOne(['registerId' => $this->id]);

        return $extModel ? $extModel->document : null;
    }

}

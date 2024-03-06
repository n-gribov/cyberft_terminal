<?php

namespace addons\ISO20022\models;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\cyberxml\CyberXmlDocument;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $documentId
 * @property integer $storedFileId
 * @property string $msgId
 * @property string $mmbId
 * @property string $extStatus
 * @property string $subject
 * @property string $descr
 * @property string $fileName
 * @property string $typeCode
 * @property string $errorCode
 * @property string $errorDescription
 * @property string $statusCode
 * @property string $count
 * @property string $currency
 * @property string $sum
 * @property string $account
 * @property string $periodBegin
 * @property string $periodEnd
 * @property string $originalFilename
 */
class ISO20022DocumentExt extends ActiveRecord implements DocumentExtInterface
{
    const STATUS_AWAITING_ATTACHMENT            = 'awaitingAttachment';
    const STATUS_CRYPTOPRO_VERIFICATION         = 'cryptoproVerification';
    const STATUS_CRYPTOPRO_VERIFICATION_FAILED  = 'cryptoproVerificationFailed';
    const STATUS_CRYPTOPRO_VERIFICATION_SUCCESS = 'cryptoproVerificationSuccess';
    const STATUS_FOR_CRYPTOPRO_SIGNING          = 'forCryptoProSigning';
    const STATUS_CRYPTOPRO_SIGNING_ERROR        = 'cryptoProSigningError';
    const STATUS_CRYPTOPRO_SIGNED               = 'cryptoProSigned';
    const STATUS_CRYPTOPRO_SIGNING_SUCCESS      = 'cryptoProSignedSuccess';
    const STATUS_SFTP_ERROR                     = 'sftpError';

    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtISO20022';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['documentId', 'required'],
            [
                [
                    'subject', 'descr','typeCode', 'statusCode',
                    'errorCode', 'errorDescription',
                    'count', 'currency', 'sum',
                    'account', 'periodBegin', 'periodEnd',
                    'originalFilename'
                ]
                ,'safe'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('doc', 'ID'),
            'documentId' => Yii::t('doc', 'Document ID'),
            'subject'      => Yii::t('doc', 'Subject'),
            'descr'        => Yii::t('doc', 'Description'),
            'typeCode'     => Yii::t('app/iso20022', 'Document type code'),
            'currency' => Yii::t('app/iso20022', 'Currency'),
            'count' => Yii::t('app/iso20022', 'Quantity'),
            'sum' => Yii::t('app/iso20022', 'Amount'),
            'account' => Yii::t('app/iso20022', 'Account'),
            'periodBegin' => Yii::t('app/iso20022', 'Begin period'),
            'periodEnd' => Yii::t('app/iso20022', 'End period'),
            'fileName' => Yii::t('app/iso20022', 'Attachment'),
            'statusCode' => Yii::t('document', 'Processing status on the receiver system'),
            'originalFilename' => Yii::t('app/iso20022', 'Original filename'),
            'msgId' => Yii::t('app/iso20022', 'Document ID'),
            'mmbId' => Yii::t('app/iso20022', 'Remote sender ID'),
        ];
    }

    public function getStatusLabel($status = null)
    {
        if (is_null($status)) {
            $status = $this->extStatus;
        }

        return (!is_null($this->extStatus) && array_key_exists($this->extStatus, self::getStatusLabels()))
            ? self::getStatusLabels()[$status]
            : $this->extStatus;
    }

    public static function getStatusLabels()
    {
        return [
            self::STATUS_AWAITING_ATTACHMENT            => Yii::t('doc', 'Awaiting attachment'),
            self::STATUS_CRYPTOPRO_VERIFICATION         => Yii::t('doc', 'CryptoPro verification'),
            self::STATUS_CRYPTOPRO_VERIFICATION_FAILED  => Yii::t('doc', 'CryptoPro verification failed'),
            self::STATUS_CRYPTOPRO_VERIFICATION_SUCCESS => Yii::t('doc', 'CryptoPro verification success'),
            self::STATUS_FOR_CRYPTOPRO_SIGNING          => Yii::t('doc', 'CryptoPro signing'),
            self::STATUS_CRYPTOPRO_SIGNING_ERROR        => Yii::t('doc', 'CryptoPro signing error'),
            self::STATUS_CRYPTOPRO_SIGNED               => Yii::t('doc', 'CryptoPro signed'),
            self::STATUS_CRYPTOPRO_SIGNING_SUCCESS      => Yii::t('doc', 'CryptoPro signed success'),
            self::STATUS_SFTP_ERROR                     => Yii::t('app/iso20022', 'SFTP access error'),
        ];
    }

    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document', ['id' => 'documentId']);
        }

        return $this->_document;
    }

    public function loadContentModel($model)
    {
        $this->msgId = $model->msgId;
        
        switch ($model->type){
            case Auth018Type::TYPE:
            case Auth024Type::TYPE:
            case Auth025Type::TYPE:
                $this->mmbId = $model->mmbId;
                $this->fileName = $model->fileName; 
                break;
            case Auth026Type::TYPE:
                $this->mmbId = '';
                $this->fileName = reset($model->fileNames);
                break;
            default:
                $this->mmbId = '';
                $this->fileName = $model->fileName; 
                break;            
        }
        
        $this->subject = $model->subject;
        $this->descr = $model->descr;
        $this->originalFilename = $model->originalFilename;
        $this->typeCode = $model->typeCode;
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        $accountNumber = null;
        if ($document->type === Auth024Type::TYPE) {
            $typeModel = CyberXmlDocument::getTypeModel($document->getValidStoredFileId());
            if ($typeModel instanceof Auth024Type) {
                $accountNumber = $typeModel->getSenderAccountNumber();
            }
        }

        if ($accountNumber) {
            $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
            return $account !== null && EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id);
        } else {
            return EdmPayerAccountUser::userCanSingDocumentsForBankTerminal($user->id, $document->receiver);
        }
    }
}

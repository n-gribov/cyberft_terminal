<?php

namespace addons\edm\models\BankLetter;

use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\VTBFreeBankDoc\VTBFreeBankDocType;
use addons\edm\models\VTBFreeClientDoc\VTBFreeClientDocType;
use addons\ISO20022\models\ISO20022DocumentExt;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Exception;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class BankLetterDocumentExt
 * @package addons\edm\models\BankLetter
 * @property integer $documentId
 * @property string $subject
 * @property string $message
 * @property string $extStatus
 * @property integer $storedFileId
 * @property string $fileName
 * @property string $businessStatus
 * @property string $businessStatusComment
 * @property string $businessStatusDescription
 * @property string $businessStatusErrorCode
 * @property string $jsonData
 * @property string|null $uuid
 * @property string $bankBik
 */
class BankLetterDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    public static function tableName()
    {
        return 'documentExtEdmBankLetter';
    }

    public function rules()
    {
        return [
            ['documentId', 'integer'],
            [['subject', 'uuid', 'bankBik'], 'string', 'max' => 255],
            [['documentId', 'subject', 'bankBik'], 'required'],
            [['businessStatus', 'businessStatusComment', 'businessStatusDescription',
                'businessStatusErrorCode', 'jsonData'], 'string'],
            ['jsonData', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'documentId' => Yii::t('edm', 'Document'),
            'subject'    => Yii::t('edm', 'Subject'),
            'message'    => Yii::t('edm', 'Message text'),
        ];
    }

    public static function getStatusLabels()
    {
        return [
            ISO20022DocumentExt::STATUS_AWAITING_ATTACHMENT => Yii::t('doc', 'Awaiting attachment')
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

    /**
     * @param VTBFreeClientDocType|VTBFreeBankDocType $model
     * @throws Exception
     */
    public function loadContentModel($model)
    {
        if ($model instanceof VTBFreeClientDocType || $model instanceof VTBFreeBankDocType) {
            $this->subject = $model->document->DOCNAME;
            $this->message = $model->document->DOCTEXT;
        } else {
            throw new Exception("Unsupported model type: {$model->type}");
        }
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function getStoredFileList()
    {
        if (!empty($this->jsonData)) {
            $fileList = json_decode($this->jsonData, true);

            return $fileList;
        }

        return [$this->storedFileId => $this->fileName];
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return EdmPayerAccountUser::userCanSingDocumentsForBank($user->id, $this->bankBik);
    }

    public static function getDeletableStatuses(): array
    {
        return array_merge(
            Document::getDeletableStatus(),
            [Document::STATUS_CREATING]
        );
    }
}

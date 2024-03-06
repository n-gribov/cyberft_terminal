<?php

namespace addons\finzip\models;

use common\base\BaseType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * FinZip document ext Active Record model class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package addons
 * @subpackage finzip
 *
 * @property integer  $id               Row ID
 * @property integer  $documentId       Document ID
 * @property integer  $zipStoredFileId  Stored ID for zip file
 * @property integer  $fileCount        Count of files in ZIP
 * @property string   $subject          FinZip document subject
 * @property string   $descr            FinZip document descriptiom
 * @property string   $attachmentUUID   FinZip attachment UUID
 * @property integer $attachmentsCount
 */
class FinZipDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    /**
     * @var ActiveQuery $_document Document
     */
    private $_document;
    private $_cachedData = [];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtFinZip';
    }

    public static function businessStatusLabels()
    {
        return [
            'RCVD' => Yii::t('edm', 'Received'),
            'ACTC' => Yii::t('edm', 'Completely accepted'),
            'ACCP' => Yii::t('edm', 'Accepted for processing'),
            'RJCT' => Yii::t('edm', 'Rejected'),
            'ACSP' => Yii::t('edm', 'Accepted'),
            'ACSC' => Yii::t('edm', 'Processed'),
            'PDNG' => Yii::t('edm', 'Pending'),
            'PART' => Yii::t('edm', 'Partially')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId'], 'required'],
            [['fileCount', 'documentId'], 'integer'],
            ['subject', 'string', 'max' => 255],
            ['descr', 'string'],
            [['attachmentUUID', 'businessStatus', 'businessStatusDescription'], 'safe']
        ];
    }

    public static function getBusinessStatusTranslation($label)
    {
        $labels = static::businessStatusLabels();

        if ($label) {
            return isset($labels[$label]) ? $labels[$label] : $label;
        } else {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => Yii::t('doc', 'ID'),
            'documentId'      => Yii::t('doc', 'Document ID'),
            'zipStoredFileId' => Yii::t('doc', 'Stored ID for zip file'),
            'fileCount'       => Yii::t('doc', 'Files Attached'),
            'subject'         => Yii::t('doc', 'Subject'),
            'descr'           => Yii::t('doc', 'Description'),
            'attachmentUUID'  => Yii::t('doc', 'Attachment UUID'),
            'businessStatus'  => Yii::t('doc', 'Execution status'),
            'businessStatusDescription' => Yii::t('edm', 'Business status description')
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabelShortcuts()
    {
        return [
//            'fileCount' => Yii::t('doc', 'FC'),
        ];
    }

    public function getLabelShortcut($attr) {
        return array_key_exists($attr, $this->attributeLabelShortcuts()) ? $this->attributeLabelShortcuts()[$attr] : null;
    }

    /**
     * Load data from content model
     *
     * @param BaseType $model Content model
     */
    public function loadContentModel($model)
    {
        $this->zipStoredFileId = $model->zipStoredFileId;
        $this->fileCount       = $model->fileCount;
        $this->subject         = $model->subject;
        $this->descr           = $model->descr;
        $this->attachmentUUID  = $model->attachmentUUID;
    }

    /**
     * Get document
     *
     * @return ActiveQuery
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
        return true;
    }

    public function getAttachmentsCount()
    {
        return $this->fileCount > 0 ? $this->fileCount - 1 : 0;
    }

    public function getEncryptedSubject()
    {
        return $this->getEncryptedAttribute('subject');
    }

    public function getEncryptedDescr()
    {
        return $this->getEncryptedAttribute('descr');
    }

    private function getEncryptedAttribute($attribute)
    {
        if (isset($this->_cachedData[$this->documentId][$attribute])) {
            return $this->_cachedData[$this->documentId][$attribute];
        }

        if ($this->document->isEncrypted) {
            try {
                $value = Yii::$app->xmlsec->decryptData($this->$attribute, true);
            } catch(\Exception $e) {
                $value = $this->$attribute;
            }
        } else {
            $value = $this->$attribute;
        }

        $this->_cachedData[$this->documentId][$attribute] = $value;

        return $value;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return true;
    }
}
<?php

namespace addons\fileact\models;

use common\base\BaseType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * FileAct document ext Active Record model class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package addons
 * @subpackage fileact
 *
 * @property integer $documentId             Document ID
 * @property string  $extStatus              Extansion status
 * @property integer $pduStoredFileId        Stored ID for pdu file
 * @property integer $binStoredFileId        Stored ID for bun file
 * @property integer $zipStoredFileId        Stored ID for zip file
 * @property string  $cryptoproSigningLog    CryptoPro signing log
 * @property string  $senderReference        Sender reference tag from PDU
 * @property string  $binFileName            BIN file name
 */
class FileActDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    const STATUS_FOR_CRYPTOPRO_SIGNING         = 'forCryptoProSigning';
    const STATUS_CRYPTOPRO_SIGNING_ERROR       = 'cryptoProSigningError';
    const STATUS_CRYPTOPRO_SIGNED              = 'cryptoProSigned';
    const STATUS_CRYPTOPRO_VERIFIED            = 'cryptoProVerified';
    const STATUS_CRYPTOPRO_VERIFICATION_FAILED = 'cryptoProVerificationFailed';

    /**
     * @var ActiveQuery $_document Document
     */
    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtFileAct';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId'], 'required'],
            [['extStatus', 'cryptoproSigningLog'], 'safe'],
            [['extStatus', 'cryptoproSigningLog', 'senderReference', 'binFileName'], 'string'],
            [['pduStoredFileId', 'binStoredFileId', 'zipStoredFileId'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                     => Yii::t('doc', 'ID'),
            'documentId'             => Yii::t('doc', 'Document ID'),
            'pduStoredFileId'        => Yii::t('doc', 'Stored ID for pdu file'),
            'binStoredFileId'        => Yii::t('doc', 'Stored ID for bun file'),
            'zipStoredFileId'        => Yii::t('doc', 'Stored ID for zip file'),
            'cryptoproSigningLog'    => Yii::t('doc', 'Cryptopro signing registry'),
            'senderReference'        => Yii::t('app/fileact', 'Sender reference'),
            'binFileName'            => Yii::t('app/fileact', 'BIN file'),
        ];
    }

    /**
     * Load data from content model
     *
     * @param BaseType $model Content model
     */
    public function loadContentModel($model)
    {
        if ($model instanceof FileActType) {
            $this->pduStoredFileId        = $model->pduStoredFileId;
            $this->binStoredFileId        = $model->binStoredFileId;
            $this->zipStoredFileId        = $model->zipStoredFileId;
            $this->senderReference        = $model->senderReference;
            $this->binFileName            = $model->binFileName;
        }
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

    /**
     * Get status label
     *
     * @return string Return localization status label
     */
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
     * Get list of status labels
     *
     * @return array
     */
    public static function getStatusLabels()
    {
        return [
            self::STATUS_FOR_CRYPTOPRO_SIGNING => Yii::t('doc', 'CryptoPro signing'),
            self::STATUS_CRYPTOPRO_SIGNING_ERROR => Yii::t('doc', 'CryptoPro signing error'),
            self::STATUS_CRYPTOPRO_SIGNED   => Yii::t('doc', 'CryptoPro signed'),
            self::STATUS_CRYPTOPRO_VERIFIED  => Yii::t('doc', 'CryptoPro verified'),
            self::STATUS_CRYPTOPRO_VERIFICATION_FAILED => Yii::t('doc', 'CryptoPro verification failed'),
        ];
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return true;
    }
}
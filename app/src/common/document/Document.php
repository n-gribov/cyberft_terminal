<?php

namespace common\document;

use common\base\BaseType;
use common\base\interfaces\AttrShortcutInterface;
use common\base\interfaces\DocumentExtInterface;
use common\base\interfaces\ElasticSearchable;
use common\db\ActiveRecord;
use common\db\ActiveQuery;
use common\helpers\Address;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\participant\models\BICDirParticipant as Participants;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ArrayDataProvider;

/**
 * This is the model class for table "document".
 *
 * @package core
 * @subpackage model
 *
 * @property integer  $id                    Document ID
 * @property integer  $direction             Document direction
 * @property string   $sender                Document sender
 * @property string   $receiver              Document receiver
 * @property string   $senderParticipantId   Document sender
 * @property string   $receiverParticipantId Document receiver
 * @property string   $status                Document status
 * @property string   $type                  Document type
 * @property string   $typeGroup             Document type group
 * @property string   $origin                Document origin
 * @property integer  $actualStoredFileId    Document stored file ID
 * @property integer  $encryptedStoredFileId Document encrypted stored file ID
 * @property string   $uuid                  Document uuid
 * @property string   $uuidReference         Document reference uuid
 * @property string   $uuidRemote            Document remote uuid
 * @property integer  $signaturesRequired    Count of required signatures for document
 * @property integer  $signaturesCount       Count of signatures in document
 * @property integer  $attemptsCount         Count of attempts
 * @property string   $statusJob             Status job
 * @property string   $statusDate            Status date
 * @property string   $dateCreate            Date of create
 * @property string   $dateUpdate            Date of update
 * @property string   $fileId                Document file ID for cftcp
 * @property string   $correctionReason      Document correction reason
 * @property int      $terminalId            Terminal AR id
 * @property int      $viewed                viewed flag 0/1
 * @property int      $isEncrypted           encrypted flag 0/1*
 * @property-read DocumentExtInterface|null $extModel
 */
class Document extends ActiveRecord implements ElasticSearchable, AttrShortcutInterface
{
    const SIGNATURES_ENVELOPE           = 1;
    const SIGNATURES_TYPEMODEL          = 2;
    const SIGNATURES_ALL                = 255;

    const SCENARIO_RESERVE              = 'reserve';
    const DIRECTION_IN                  = 'IN';
    const DIRECTION_OUT                 = 'OUT';
    const TYPE_UNKNOWN                  = 'unknown';
    const TYPE_SERVICE_GROUP            = 'service';
    const STATUS_CREATING               = 'creating';
    const STATUS_CREATING_ERROR         = 'creatingError';
    const STATUS_EDIT_ERROR             = 'editError';
    const STATUS_FOR_ACCEPTANCE         = 'forAcceptance';
    const STATUS_ACCEPTED               = 'accepted';
    const STATUS_PENDING                = 'pending';
    const STATUS_EXPORTED               = 'exported';
    const STATUS_CORRECTION             = 'correction';
    const STATUS_DELETED                = 'deleted';
    const STATUS_NOT_EXPORTED           = 'notExported';
    const STATUS_EXPORT_RETRY           = 'exportRetry';
    const STATUS_ROUTED                 = 'routed';
	const STATUS_SERVICE_PROCESSING		= 'serviceProcessing';
    const STATUS_SIGNING_REJECTED      =  'signingRejected';
    // Unsupport type status
    const STATUS_UNSUPPORTED_TYPE       = 'unsupportedType';
    // Register and processed
    const STATUS_REGISTERED             = 'registered';
    const STATUS_REGISTERING_ERROR      = 'registeredError';
    const STATUS_FORPROCESSING          = 'forProcessing';
    const STATUS_PROCESSED              = 'processed';
    const STATUS_PROCESSING_ERROR       = 'processingError';
    // Sign statuses
    const STATUS_FORSIGNING             = 'forSigning';
    const STATUS_SIGNING                = 'signing';
    const STATUS_SIGNING_ERROR          = 'signingError';
    const STATUS_SIGNED                 = 'signed';
    // Autosign statuses
    const STATUS_AUTOSIGNING            = 'autoSigning';
    const STATUS_AUTOSIGNED             = 'autoSigned';
    const STATUS_AUTOSIGNING_ERROR      = 'autoSigningError';
    // Optimus autobot sign statuses
    const STATUS_FOR_MAIN_AUTOSIGNING   = 'forMainAutoSigning';
    const STATUS_MAIN_AUTOSIGNING       = 'mainAutoSigning';
    const STATUS_MAIN_AUTOSIGNING_ERROR = 'mainAutoSigningError';
    const STATUS_MAIN_AUTOSIGNED        = 'mainAutoSigned';
    // Compress
    const STATUS_COMPRESSING            = 'compressing';
    const STATUS_COMPRESSION_ERROR      = 'compressionError';
    const STATUS_COMPRESSED             = 'compressed';

    const STATUS_FOR_CONTROLLER_VERIFICATION = 'forControllerVerification';
    const STATUS_CONTROLLER_VERIFICATION_FAIL= 'controllerVerificationFail';
    // Crypt statuses
    const STATUS_ENCRYPTING             = 'encrypting';
    const STATUS_ENCRYPTING_ERROR       = 'encryptingError';
    const STATUS_ENCRYPTED              = 'encrypted';
    // File upload statuses
    const STATUS_UPLOADING              = 'uploading';
    const STATUS_UPLOAD_FAIL            = 'uploadFail';
    const STATUS_NOT_UPLOADED           = 'notUploaded';
    const STATUS_UPLOADED               = 'uploaded';
    const STATUS_FORUPLOADING           = 'forUploading';
    // Send request statuses
    const STATUS_REQUESTING             = 'requesting';
    const STATUS_REQUEST_FAIL           = 'requestFail';
    const STATUS_NOT_REQUESTED          = 'notRequested';
    const STATUS_REQUESTED              = 'requested';
    // Send statuses
    const STATUS_SENDING                = 'sending';
    const STATUS_SENDING_FAIL           = 'sendFail';
    const STATUS_NOT_SENT               = 'notSent';
    const STATUS_SENT                   = 'sent';
    const STATUS_FORSENDING             = 'forSending';
    // Downloads statuses, used by CFTCP
    const STATUS_DOWNLOADING            = 'downloading';
    const STATUS_DOWNLOAD_FAIL          = 'downloadFail';
    const STATUS_NOT_DOWNLOADED         = 'notDownloaded';
    const STATUS_DOWNLOADED             = 'downloaded';
    const STATUS_FORDOWNLOADING         = 'forDownloading';
    // Analyzing sttus
    const STATUS_ANALYZING              = 'analyzing';
    // Статусы цикла получения
    const STATUS_SCHEMA_ERROR           = 'schemaError';
    // Decrypt statuses
    const STATUS_DECRYPTING             = 'decrypting';
    const STATUS_DECRYPTING_ERROR       = 'decryptingError';
    const STATUS_DECRYPTED              = 'decrypted';
    // Compress
    const STATUS_DECOMPRESSING          = 'decompressing';
    const STATUS_DECOMPRESSION_ERROR    = 'decompressionError';
    const STATUS_DECOMPRESSED           = 'decompressed';
    // Verify statuses
    const STATUS_VERIFICATION           = 'verification';
    const STATUS_VERIFICATION_FAILED    = 'verificationFailed';
    const STATUS_VERIFIED               = 'verified';
    // Delivering status
    const STATUS_DELIVERING             = 'delivering';
    // Final statuses
    const STATUS_UNDELIVERED            = 'undelivered';
    const STATUS_DELIVERED              = 'delivered';
    const STATUS_REJECTED               = 'rejected';
    const STATUS_ATTACHMENT_UNDELIVERED = 'attachmentUndelivered';
    // Done by receiver
    const STATUS_EXECUTED               = 'executed';
    const STATUS_FOR_USER_VERIFICATION  = 'forUserVerify';
    const STATUS_USER_VERIFIED          = 'userVerified';
    const STATUS_USER_VERIFICATION_ERROR  = 'userVerifiedError';
    // Origin
    const ORIGIN_FILE                   = 'File';      // Файловый обмен c АБС
    const ORIGIN_XMLFILE                = 'XmlFile';   // Файловый обмен с 1С в формате XML CyberFT
    const ORIGIN_MQ                     = 'MQ';        // Обмен через MQ
    const ORIGIN_WEB                    = 'Web';       // Обмен через web-интерфейс Терминала
    const ORIGIN_WEB_FILE               = 'WebFile';   // Файл, загруженный через web-интерфейс Терминала (Документ из файла)
    const ORIGIN_SERVICE                = 'Service';   // Специальный канал для сервисных типов документов
    const ORIGIN_API                    = 'API';       // Для документов, отправляемых через API
    const ORIGIN_NOTAPPLICABLE          = 'Undefined'; // Не применимо, указывается для всех входящих документов

    /**
     * @var ActiveRecord $_extModel Extension model
     */
    private $_extModel;

    private $_cyberXml;

    public static $techMessageTypes = [
        'CFTStatusReport',
        'CFTAck',
        'CFTChkAck',
        'CFTResend',
        'StatusReport',
    ];

    private $_senderParticipantName;
    private $_receiverParticipantName;

    public static function tableName()
    {
        return 'document';
    }

    public function init() {
        parent::init();
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_RESERVE] = ['type', 'direction'];
        return $scenarios;
    }

    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'dateCreate',
                'updatedAtAttribute' => 'dateUpdate',
                'value'              => date('Y-m-d H:i:s'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['direction'], 'in', 'range' => [self::DIRECTION_IN, self::DIRECTION_OUT]],
            [['direction', 'type'], 'required', 'on' => [self::SCENARIO_RESERVE]],
            //[['sender', 'receiver', 'origin', 'type'], 'required', 'on' => [self::SCENARIO_DEFAULT]],
            [['dateCreate', 'dateUpdate', 'statusDate', 'senderParticipantName', 'receiverParticipantName'], 'safe'],
            [['sender', 'receiver', 'origin'], 'string', 'max' => 32],
            [['status', 'type', 'typeGroup', 'uuid', 'uuidReference', 'uuidRemote'], 'string', 'max' => 64],
            [['signaturesCount', 'signaturesRequired', 'actualStoredFileId', 'encryptedStoredFileId'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('app/message', 'ID'),
            'direction'             => Yii::t('app/message', 'Direction'),
            'directionLabel'        => Yii::t('app/message', 'Direction'),
            'sender'                => Yii::t('app/message', 'Sender'),
            'receiver'              => Yii::t('app/message', 'Recipient'),
            'senderParticipantName' => Yii::t('app/message', 'Sender'),
            'receiverParticipantName' => Yii::t('app/message', 'Recipient'),
            'senderParticipant'     => Yii::t('app/message', 'Sender'),
            'receiverParticipant'   => Yii::t('app/message', 'Receiver'),
            'status'                => Yii::t('app/message', 'Status'),
            'type'                  => Yii::t('app/message', 'Type'),
            'typeGroup'             => Yii::t('app/message', 'Type Group'),
            'origin'                => Yii::t('app/message', 'Origin'),
            'actualStoredFileId'    => Yii::t('app/message', 'Actual Stored File ID'),
            'encryptedStoredFileId' => Yii::t('app/message', 'Encrypted Stored File ID'),
            'uuid'                  => Yii::t('app/message', 'Uuid'),
            'uuidReference'         => Yii::t('app/message', 'Uuid reference'),
            'uuidRemote'            => Yii::t('app/message', 'Uuid remote'),
            'signaturesRequired'    => Yii::t('doc', 'Signatures required'),
            'signaturesCount'       => Yii::t('doc', 'Signatures count'),
            'attemptsCount'         => Yii::t('app/message', 'Count of attempts'),
            'statusLabel'           => Yii::t('app/message', 'Status'),
            'statusJob'             => Yii::t('app/message', 'Status job'),
            'statusDate'            => Yii::t('app/message', 'Status date'),
            'dateCreate'            => Yii::t('app/message', 'Registry Time'),
            'dateUpdate'            => Yii::t('app/message', 'Update Time'),
            'fileId'                => Yii::t('app/message', 'File ID'),
            'correctionReason'      => Yii::t('doc', 'Correction reason'),
            'signData'              => Yii::t('app/message', 'Document data for signature'),
        ];
    }

    public function beforeSave($insert)
    {
        $this->getParticipants();
        if (!$insert) {
            if ($this->oldAttributes['status'] == self::STATUS_DELETED) {
                return false;
            }
            if ($this->status == self::STATUS_DELETED) {
                if (!static::isDeletableState($this->oldAttributes['direction'], $this->oldAttributes['status'], $this->extModel)) {
                    return false;
                }
            }
        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->getParticipants();
    }

    protected function getParticipants()
    {
        if (empty($this->senderParticipantId)) {
            $this->senderParticipantId = Address::truncateAddress($this->sender);
        }

        if (empty($this->receiverParticipantId)) {
            $this->receiverParticipantId = Address::truncateAddress($this->receiver);
        }
    }

    public function attributeLabelShortcuts()
    {
            return [];
    }

    /**
     * Get list of error statuses
     *
     * @return array Return list of error statuses
     */
    public static function getErrorStatus()
    {
        return [
            self::STATUS_SIGNING_ERROR,
            self::STATUS_AUTOSIGNING_ERROR,
            self::STATUS_REJECTED,
            self::STATUS_UNDELIVERED,
            self::STATUS_REGISTERING_ERROR,
            self::STATUS_CREATING_ERROR,
            self::STATUS_EDIT_ERROR,
        ];
    }

    /**
     * Ошибочные статусы с заголовками
     * @return array
     */
    public static function getErrorStatusLabels()
    {
        $statuses = [];

        foreach (self::getErrorStatus() as $status) {
            $statuses[$status] = self::getStatusLabels()[$status];
        }

        return $statuses;
    }

    /**
     * Get list of successful statuses
     *
     * @return array Return list of successful statuses
     */
    public static function getSuccessStatus()
    {
        return [
            self::STATUS_REGISTERED,
            self::STATUS_DELIVERED,
            self::STATUS_EXPORTED,
            self::STATUS_EXECUTED,
        ];
    }

    /**
     * Get list of processing statuses
     *
     * @return array Return list of processing statuses
     */
    public static function getProcessingStatus()
    {
        return [
            self::STATUS_CREATING,
            self::STATUS_FORSIGNING,
            self::STATUS_SIGNED,
            self::STATUS_AUTOSIGNING,
            self::STATUS_AUTOSIGNED,
            self::STATUS_PENDING,
        ];
    }

    public static function getDeletableStatus()
    {
        return [
            self::STATUS_CREATING_ERROR,
            self::STATUS_FOR_ACCEPTANCE,
            self::STATUS_FORSIGNING,
            self::STATUS_FORSENDING,
            self::STATUS_CORRECTION,
            self::STATUS_SIGNING_ERROR,
            self::STATUS_SIGNING_REJECTED
        ];
    }

    public static function getUserVerifiableStatus()
    {
        return [
            self::STATUS_FOR_USER_VERIFICATION,
            self::STATUS_USER_VERIFICATION_ERROR,
        ];
    }

    public static function filterStatusLabels($filterKeys)
    {
        return array_intersect_key(static::getStatusLabels(), array_flip($filterKeys));
    }


    /**
     * Get status labels list
     *
     * @return array Return list of status labels
     */
    public static function getStatusLabels()
    {
        $statusLabels = [
            self::STATUS_UNSUPPORTED_TYPE       => Yii::t('app/message', 'Unsupported type'),
            self::STATUS_REGISTERED             => Yii::t('app/message', 'Registered'),
            self::STATUS_FORSIGNING             => Yii::t('app/message', 'Waiting for signing'),
            self::STATUS_SIGNING_REJECTED       => Yii::t('app/message', 'Signing rejected'),
            self::STATUS_SIGNING                => Yii::t('app/message', 'Signing'),
            self::STATUS_SIGNING_ERROR          => Yii::t('app/message', 'Signing error'),
            self::STATUS_SIGNED                 => Yii::t('app/message', 'Signed'),
            self::STATUS_ENCRYPTING             => Yii::t('app/message', 'Encrypting'),
            self::STATUS_ENCRYPTING_ERROR       => Yii::t('app/message', 'Encrypting error'),
            self::STATUS_ENCRYPTED              => Yii::t('app/message', 'Encrypted'),
            self::STATUS_ANALYZING              => Yii::t('app/message', 'Analyzing'),
            self::STATUS_UPLOADING              => Yii::t('app/message', 'Uploading'),
            self::STATUS_UPLOAD_FAIL            => Yii::t('app/message', 'Upload fail'),
            self::STATUS_NOT_UPLOADED           => Yii::t('app/message', 'Not uploaded'),
            self::STATUS_UPLOADED               => Yii::t('app/message', 'Uploaded'),
            self::STATUS_FORUPLOADING           => Yii::t('app/message', 'Waiting for uploading'),
            self::STATUS_DOWNLOADING            => Yii::t('app/message', 'Downloading'),
            self::STATUS_DOWNLOAD_FAIL          => Yii::t('app/message', 'Downloading fail'),
            self::STATUS_NOT_DOWNLOADED         => Yii::t('app/message', 'Not downloaded'),
            self::STATUS_DOWNLOADED             => Yii::t('app/message', 'Downloaded'),
            self::STATUS_FORDOWNLOADING         => Yii::t('app/message', 'Waiting for downloading'),
            self::STATUS_REQUESTING             => Yii::t('app/message', 'Requesting'),
            self::STATUS_REQUEST_FAIL           => Yii::t('app/message', 'Request fail'),
            self::STATUS_NOT_REQUESTED          => Yii::t('app/message', 'Not requested'),
            self::STATUS_REQUESTED              => Yii::t('app/message', 'Requested'),
            self::STATUS_SENDING                => Yii::t('app/message', 'Sending'),
            self::STATUS_SENDING_FAIL           => Yii::t('app/message', 'Sending fail'),
            self::STATUS_NOT_SENT               => Yii::t('app/message', 'Not sent'),
            self::STATUS_SENT                   => Yii::t('app/message', 'Sent'),
            self::STATUS_FORSENDING             => Yii::t('app/message', 'Waiting for sending'),
            self::STATUS_SCHEMA_ERROR           => Yii::t('app/message', 'Schema error'),
            self::STATUS_DECRYPTING             => Yii::t('app/message', 'Decrypting'),
            self::STATUS_DECRYPTING_ERROR       => Yii::t('app/message', 'Decrypting error'),
            self::STATUS_DECRYPTED              => Yii::t('app/message', 'Decrypted'),
            self::STATUS_VERIFICATION           => Yii::t('app/message', 'Verification'),
            self::STATUS_VERIFICATION_FAILED    => Yii::t('app/message', 'Verification failed'),
            self::STATUS_VERIFIED               => Yii::t('app/message', 'Verified'),
            self::STATUS_DELIVERING             => Yii::t('app/message', 'Delivering'),
            self::STATUS_UNDELIVERED            => Yii::t('app/message', 'Undelivered'),
            self::STATUS_DELIVERED              => Yii::t('app/message', 'Delivered'),
            self::STATUS_REJECTED               => Yii::t('app/message', 'Rejected'),
            self::STATUS_FORPROCESSING          => Yii::t('app/message', 'Processing'),
            self::STATUS_PROCESSED              => Yii::t('app/message', 'Processed'),
            self::STATUS_PROCESSING_ERROR       => Yii::t('app/message', 'Processing error'),
            self::STATUS_CREATING               => Yii::t('app/message', 'Creating'),
            self::STATUS_ACCEPTED               => Yii::t('app/message', 'Accepted'),
            self::STATUS_FOR_MAIN_AUTOSIGNING   => Yii::t('app/message', 'For signing by controller primary key'),
            self::STATUS_MAIN_AUTOSIGNING       => Yii::t('app/message', 'Signing by controller primary key'),
            self::STATUS_MAIN_AUTOSIGNING_ERROR => Yii::t('app/message', 'Signing error by controller primary key'),
            self::STATUS_MAIN_AUTOSIGNED        => Yii::t('app/message', 'Signed by controller primary key'),
            self::STATUS_AUTOSIGNING            => Yii::t('app/message', 'Signing by controller additional keys'),
            self::STATUS_AUTOSIGNED             => Yii::t('app/message', 'Signed by controller additional keys'),
            self::STATUS_AUTOSIGNING_ERROR      => Yii::t('app/message', 'Signing error by controller additional keys'),
            self::STATUS_EXECUTED               => Yii::t('doc', 'Processed by the recipient'),
            self::STATUS_REGISTERING_ERROR      => Yii::t('doc', 'Document registering error'),
            self::STATUS_CORRECTION             => Yii::t('doc', 'Modification'),
            self::STATUS_ROUTED                 => Yii::t('app/message', 'Route'),
            self::STATUS_CREATING_ERROR         => Yii::t('app/message', 'Creating error'),
            self::STATUS_EDIT_ERROR             => Yii::t('app/message', 'Edit error'),
            self::STATUS_SERVICE_PROCESSING     => Yii::t('doc', 'In-service processing'),
            self::STATUS_EXPORTED               => Yii::t('doc', 'Exported'),
            self::STATUS_NOT_EXPORTED           => Yii::t('doc', 'Not exported'),
            self::STATUS_EXPORT_RETRY           => Yii::t('doc', 'Export retry'),
            self::STATUS_FOR_USER_VERIFICATION  => Yii::t('doc', 'Requires user verification'),
            self::STATUS_USER_VERIFIED          => Yii::t('doc', 'User verified'),
            self::STATUS_USER_VERIFICATION_ERROR => Yii::t('doc', 'User verified error'),
            self::STATUS_DELETED                => Yii::t('app/message', 'Deleted'),
            self::STATUS_ATTACHMENT_UNDELIVERED => Yii::t('app/message', 'Attachment not delivered'),
            self::STATUS_FOR_CONTROLLER_VERIFICATION => Yii::t('doc', 'Controller verification'),
            self::STATUS_CONTROLLER_VERIFICATION_FAIL => Yii::t('doc', 'Controller rejected'),
            self::STATUS_PENDING                => Yii::t('app/message', 'Pending'),
        ];

        asort($statusLabels);

        return $statusLabels;
    }

    /**
     * Get status label
     *
     * @return string Return localization status label
     */
    public function getStatusLabel()
    {
        return (!is_null($this->status) && array_key_exists($this->status, self::getStatusLabels()))
            ? self::getStatusLabels()[$this->status]
            : $this->status;
    }

    /**
     * Get list of direction labels
     *
     * @return array Return list of direction labels
     */
    public static function getDirectionLabels()
    {
        return [
            self::DIRECTION_IN	 => Yii::t('app/message', 'IN'),
            self::DIRECTION_OUT	 => Yii::t('app/message', 'OUT'),
        ];
    }

    /**
     * Get direction label
     *
     * @return string Return localization direction label
     */
    public function getDirectionLabel()
    {
        return (!is_null($this->direction) && array_key_exists($this->direction,
                self::getDirectionLabels())) ? self::getDirectionLabels()[$this->direction] : '';
    }

    /**
     * Get direction label
     *
     * @param string $direction Direction
     * @return string
     */
    public static function directionLabel($direction)
    {
        return !is_null($direction) && array_key_exists($direction, self::getDirectionLabels())
                ? self::getDirectionLabels()[$direction]
                : $direction;
    }

    /**
     * Get extension model
     *
     * @return ActiveQuery
     */
    public function getExtModel()
    {
        if (is_null($this->_extModel)) {
            $className = $this->extModelClassName();

            if (!is_null($className)) {
                $this->_extModel = $this->hasOne($className, ['documentId' => 'id']);
            }
        }

        return $this->_extModel;
    }

    /**
     * Get extension class name
     *
     * @return string
     */
    public function extModelClassName()
    {
        return Yii::$app->registry->getTypeExtModelClass($this->type, $this->typeGroup);
    }

    /**
     * Get extension model class instance
     *
     * @param array $config Config
     * @return ActiveRecord
     */
    public function extModelCreateInstance($config = [])
    {
        $className = $this->extModelClassName();

        if (!is_null($className)) {
            return new $className($config);
        } else {
            return null;
        }
    }

    /**
     * Get content model
     *
     * @return BaseType
     */
    public function getContentModel()
    {
		$className = Yii::$app->registry->getTypeContentClass($this->type);

        return new $className();
    }


    public function isModifiable()
    {
        $deniedStatuses = [
            Document::STATUS_DELIVERED,
            Document::STATUS_REJECTED,
            Document::STATUS_SENT,
            Document::STATUS_SENDING,
            Document::STATUS_PROCESSING_ERROR
        ];

        return !in_array($this->status, $deniedStatuses);
    }

    /**
     * Check the possibility of sending document
     *
     * @return boolean
     */
    public function isSendable()
    {
        return $this->direction == self::DIRECTION_OUT
                && in_array($this->status, [self::STATUS_REGISTERED, self::STATUS_SIGNED]);
    }
    /**
     * Check the possibility of resending document
     *
     * @return boolean
     */
    public function isResendable()
    {
        return $this->direction == self::DIRECTION_OUT
                && $this->status == self::STATUS_SENDING_FAIL;
    }

    /**
     * Check the possibility of signing document
     *
     * @return boolean
     */
    public function isSignable($extModel = null)
    {
        if (!$extModel) {
            $signData = $this->getSignData();
            if (empty($signData)) {
                return false;
            }
            $signaturesCount = $this->signaturesCount;
            $signaturesRequired = $this->signaturesRequired;
            $extChecked = false;
        } else {
            $signaturesCount = $extModel->signaturesCount;
            $signaturesRequired = $extModel->signaturesRequired;
            $extChecked = $this->status == self::STATUS_SERVICE_PROCESSING && $extModel->extStatus == self::STATUS_FORSIGNING;
        }

        return (
            $this->direction == self::DIRECTION_OUT
            && ($this->status == self::STATUS_FORSIGNING || $extChecked)
            && $signaturesCount < $signaturesRequired
        );
    }

    /**
     * Check the possibility of signing document for user
     *
     * @return boolean
     */
    public function isSignableByUserLevel($serviceId)
    {
        if ($this->direction !== self::DIRECTION_OUT) {
            return false;
        }
        if ($this->status !== self::STATUS_FORSIGNING) {
            return false;
        }

        $signaturesCount = $this->signaturesCount;
        $signaturesRequired = $this->signaturesRequired;
        if ($signaturesCount >= $signaturesRequired) {
            return false;
        }
        if (Yii::$app->user->identity->signatureNumber != $signaturesCount + 1) {
            return false;
        }

        return Yii::$app->user->can(
            DocumentPermission::SIGN,
            [
                'serviceId' => $serviceId,
                'document' => $this
            ]
        );
    }

    /**
     * Checks is document can be deleted
     *
     * @return bool
     */
    public function isDeletable()
    {
        if ($this->getExtModel()) {
            return static::isDeletableState($this->direction, $this->status, $this->getExtModel()->one());
        } else {
            return false;
        }
    }

    private static function isDeletableState($direction, $status, $extModel)
    {
        $deletableStatuses = static::getDeletableStatus();
        if ($extModel) {
            $extModelClass = get_class($extModel);
            if (method_exists($extModelClass, 'getDeletableStatuses')) {
                $deletableStatuses = $extModelClass::getDeletableStatuses();
            }
        }

        return $extModel != null && $extModel->isDocumentDeletable()
            && $direction == self::DIRECTION_OUT
            && in_array($status, $deletableStatuses);
    }

	/**
	 * Get flat fingerprint list from signature list
	 * @return array of fingerprints
	 */
	public function getDocumentFingerprints()
	{
		$signatureList = $this->getSignatures(static::SIGNATURES_ENVELOPE);
		$fingerList = [];

		foreach($signatureList as $signature) {
			$fingerList[] = $signature['fingerprint'];
		}

		return $fingerList;
	}

    /**
     * Get signatures list from current document
     *
     * @return array Return empty array or array with data: ['fingerprint', 'name', 'email', 'phone', 'role']
     */
    public function getSignatures($type = self::SIGNATURES_ALL, $filter = null)
    {
        $signatures = [];

        if ($type == 0) {
            return $signatures;
        }

        $cyxDoc = $this->getCyberXml();
        if ($cyxDoc) {
            if ($type & self::SIGNATURES_ENVELOPE) {
                $docSignList = $cyxDoc->getSignatureList($filter);
                if ($docSignList !== false) {
                    $signatures = $docSignList;
                }
            }

            if ($type & self::SIGNATURES_TYPEMODEL) {
                // incoming finzip encrypted case:
                // document is stored in non-decrypted state,
                // so actual stored file id == encrypted stored file id
                if (!$this->actualStoredFileId
                    || ($this->actualStoredFileId == $this->encryptedStoredFileId)
                ) {
                    $cyxDoc->decrypt();
                }

                /** @var \common\base\Model $typeModel */
                $typeModel = $cyxDoc->getContent()->getTypeModel();

                if ($typeModel->hasErrors()) {
                    foreach($typeModel->getErrors() as $attribute => $error) {
                        $this->addError('typeModel.' . $attribute, implode(', ', $error));
                    }
                }
                // Проверяем метод получения подписей у typeModel
                if (method_exists($typeModel, 'getSignaturesList')) {
                    $signatures = array_merge($signatures, $typeModel->getSignaturesList());
                }
            }
        }

        return $signatures;
    }

    /**
     * Get signature data provider
     *
     * @return ArrayDataProvider
     */
    public function getSignatureDataProvider($type = null, $filter = null)
    {
        $signatures = $this->getSignatures($type, $filter);

        $provider = new ArrayDataProvider([
            'allModels' => $signatures,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $provider;
    }

    /**
     * Get manual signatures list
     *
     * @param array $signatures List of exist signatures in document
     * @return array Array of autobots ['name', 'type', 'nominative', 'fingerprint', 'isSign']
     */
    public function getManualSignaturesList($signatures)
    {
        $out = [];

        /**
         * Add all manual signatures from document
         */
        foreach ($signatures as $value) {
            $signatureData		 = [
                'name'			 => $value['name'],
                'type'			 => 'manual',
                'fingerprint'	 => $value['fingerprint'],
                'isSign'		 => 1,
            ];

            $out[]	 = $signatureData;
        }

        /**
         * Add empty signature if needed
         */
        $countSign = (($this->signaturesRequired - count($out)) > 0)
                ? ($this->signaturesRequired - count($out))
                : 0;

        if ($countSign > 0) {
            $out = array_merge($out, array_fill(0, $countSign, $this->getEmptyOutputValue()));
        }

        return $out;
    }

    /**
     * Get empty output value
     *
     * @return array Return ['name', 'type', 'nominative', 'fingerprint', 'isSign']
     */
    public function getEmptyOutputValue()
    {
        return [
            'name'        => NULL,
            'type'        => NULL,
            'fingerprint' => NULL,
            'isSign'      => NULL,
        ];
    }

    /**
     * Все имеющиеся источники получения исходящих документов Терминалом
     */
    public static function getOriginLabels()
    {
        return [
            static::ORIGIN_FILE			 => 'FILE',
            static::ORIGIN_XMLFILE		 => 'FILE/XML',
            static::ORIGIN_MQ			 => 'MQ',
            static::ORIGIN_WEB			 => 'WEB',
            static::ORIGIN_WEB_FILE		 => 'WEB/FILE',
            static::ORIGIN_SERVICE		 => 'SERVICE',
            static::ORIGIN_NOTAPPLICABLE => 'N/A',
        ];
    }

    /**
     * @return string
     */
    public function getOriginLabel()
    {
        return !is_null($this->origin) && array_key_exists($this->origin, self::getOriginLabels())
            ? self::getOriginLabels()[$this->origin]
            : '';
    }

	public static function referencingDocuments($uuid)
	{
		return static::find()
            	->where(['uuidReference' => $uuid])
                ->orderBy(['dateCreate' => SORT_ASC]);
	}

    public function findReferencingDocuments($uuid = null)
    {
        if (!$uuid) {
            $uuid = $this->direction == self::DIRECTION_OUT ? $this->uuid : $this->uuidRemote;
        }

        return static::referencingDocuments($uuid);
    }

	public static function findByRemoteUuid($uuid)
	{
		return self::findOne(['uuidRemote' => $uuid]);
	}

	public static function findByUuid($uuid)
	{
		return self::findOne(['uuid' => $uuid]);
	}

	/**
	 * Заготовка на будущее, возвращает ид, соответствующий актуальному для просмотра сторед файлу
	 * Подразумевает наличие логики, выбирающей из нескольких версий (пока все прямо)
	 * @return int
	 */
	public function getValidStoredFileId()
	{
		return $this->actualStoredFileId ?: null;
	}

    /**
     * Get search ID
     *
     * @return integer
     */
	public function getSearchId()
	{
		return $this->id;
	}

    /**
     * Get type group value
     *
     * @return string
     */
	public function getSearchType()
	{
		return $this->typeGroup;
	}

	public function getSearchfields()
	{
		$id = $this->getValidStoredFileId();

		if (!$id) {
			return false;
		}

        $typeModel = CyberXmlDocument::getTypeModel($id);

		return $typeModel->getSearchFields();
	}

    /**
     * Update status
     *
     * @param string $status Status
     * @return boolean
     *
     */
    public function updateStatus($status, $info = null)
    {
        return DocumentHelper::updateDocumentStatus($this, $status, $this->attemptsCount, null, $info);
    }

    public function isServiceType()
    {
        return $this->type == static::TYPE_SERVICE_GROUP || in_array($this->type, static::$techMessageTypes);
    }

    public function getSenderParticipantName()
    {
        return $this->_senderParticipantName ? $this->_senderParticipantName : $this->senderParticipantId;
    }

    public function setSenderParticipantName($value)
    {
        $this->_senderParticipantName = $value;
    }

    public function getReceiverParticipantName()
    {
        return $this->_receiverParticipantName ? $this->_receiverParticipantName : $this->receiverParticipantId;
    }

    public function setReceiverParticipantName($value)
    {
        $this->_receiverParticipantName = $value;
    }

    private function getParticipant($value)
    {

        $participant = Participants::findOne(['participantBIC' => $value]);

        if(!empty($participant)) {
            return $participant->name;
        } else {
            return $value;
        }
    }

    public function getSenderParticipant()
    {
        return $this->getParticipant($this->senderParticipantId);
    }

    public function getReceiverParticipant()
    {
        return $this->getParticipant($this->receiverParticipantId);
    }

    public function getOriginTerminalId()
    {
        if ($this->direction == Document::DIRECTION_IN) {
            $terminalId = $this->receiver;
        } else {
            $terminalId = $this->sender;
        }

        return $terminalId;
    }

    /**
     * Функция возвращает кешированный CyberXML, т.к. в моделях типа Auth.026
     * присутствует зип-контент, который требует распаковки при каждом создании CyberXML
     * Данная функция должна вызываться специфично для предотвращения повторных созданий CyberXML
     */
    public function getCyberXml()
    {
        if (!$this->_cyberXml) {
            if ($this->getValidStoredFileId()) {
                if ($this->isEncrypted) {
                    Yii::$app->terminals->setCurrentTerminalId($this->originTerminalId);
                    $data = Yii::$app->storage->decryptStoredFile($this->actualStoredFileId);
                    $this->_cyberXml = new CyberXmlDocument();
                    $this->_cyberXml->loadFromString($data);
                } else {
                    $this->_cyberXml = CyberXmlDocument::read($this->actualStoredFileId);
                }
            }
        }

        return $this->_cyberXml;
    }

    /**
	 * CFTStatusReport или CFTAck
	 * @return boolean
	 */
	public function isReport()
	{
		return in_array($this->type, ['CFTStatusReport', 'CFTAck', 'CFTChkAck', 'StatusReport']);
	}

    public function getSignData()
    {
        $model = SignData::findOne($this->id);
        if (!$model) {
            return null;
        }

        return $model->data;
    }

    public function setSignData($data)
    {
        $model = SignData::findOne($this->id);
        if (!$model) {
            $model = new SignData;
        }

        $model->documentId = $this->id;
        $model->data = $data;

        return $model->save();
    }

}

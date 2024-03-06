<?php

namespace addons\SBBOL\models;

use common\helpers\sbbol\SBBOLHelper;
use common\modules\certManager\components\ssl\X509FileModel;
use Yii;

/**
 * This is the model class for table "sbbol_key".
 *
 * @property integer $id
 * @property string $dateCreate
 * @property string $status
 * @property string $keyOwnerId
 * @property string $bicryptId
 * @property string $certificateFingerprint
 * @property string $certificateSerial
 * @property string $certificateBody
 * @property string $certificateRequest
 * @property string $keyContainerName
 * @property string $keyPassword
 * @property string $statusLabel
 * @property string $certificateIssuer
 * @property string $publicKey
 * @property SBBOLCustomerKeyOwner $keyOwner
 */
class SBBOLKey extends \yii\db\ActiveRecord
{
    const STATUS_CREATED = 'created';
    const STATUS_CERTIFICATE_REQUEST_IS_SENT = 'certificateRequestIsSent';
    const STATUS_CERTIFICATE_IS_PUBLISHED = 'certificateIsPublished';
    const STATUS_CERTIFICATE_IS_RECEIVED = 'statusCertificateIsReceived';
    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';

    private static $statusLabels;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sbbol_key';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dateCreate'], 'safe'],
            [['keyOwnerId', 'status'], 'required'],
            [['status', 'keyOwnerId', 'bicryptId'], 'string', 'max' => 100],
            [['certificateFingerprint', 'keyContainerName'], 'string', 'max' => 255],
            [['certificateRequest', 'publicKey'], 'string'],
            [['certificateSerial'], 'string', 'max' => 200],
            [['keyPassword'], 'string', 'max' => 1000],
            [['keyOwnerId'], 'exist', 'skipOnError' => true, 'targetClass' => SBBOLCustomerKeyOwner::className(), 'targetAttribute' => ['keyOwnerId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                     => Yii::t('app/sbbol', 'Key ID'),
            'dateCreate'             => Yii::t('app/sbbol', 'Create date'),
            'status'                 => Yii::t('app/sbbol', 'Status'),
            'statusLabel'            => Yii::t('app/sbbol', 'Status'),
            'keyOwnerId'             => Yii::t('app/sbbol', 'Key owner'),
            'certificateFingerprint' => Yii::t('app/sbbol', 'Certificate fingerprint'),
            'certificateBody'        => Yii::t('app/sbbol', 'Certificate'),
            'certificateRequest'     => Yii::t('app/sbbol', 'Certificate request'),
            'certificateSerial'      => Yii::t('app/sbbol', 'Certificate serial'),
            'keyPassword'            => Yii::t('app/sbbol', 'Password'),
            'bicryptId'              => Yii::t('app/sbbol', 'Bicrypt ID'),
            'publicKey'              => Yii::t('app/sbbol', 'Public key'),
        ];
    }

    public function getStatusLabel()
    {
        $statusLabels = static::getStatusLabels();
        return $statusLabels[$this->status] ?? $this->status;
    }

    public static function getStatusLabels()
    {
        if (static::$statusLabels === null) {
            static::$statusLabels = [
                self::STATUS_CREATED                     => Yii::t('app/sbbol', 'Created'),
                self::STATUS_CERTIFICATE_REQUEST_IS_SENT => Yii::t('app/sbbol', 'Certificate request is sent'),
                self::STATUS_CERTIFICATE_IS_PUBLISHED    => Yii::t('app/sbbol', 'Certificate is published'),
                self::STATUS_CERTIFICATE_IS_RECEIVED     => Yii::t('app/sbbol', 'Certificate is received'),
                self::STATUS_ACTIVE                      => Yii::t('app/sbbol', 'Active'),
                self::STATUS_BLOCKED                     => Yii::t('app/sbbol', 'Blocked'),
            ];
        }
        return static::$statusLabels;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getKeyOwner()
    {
        return $this->hasOne(SBBOLCustomerKeyOwner::className(), ['id' => 'keyOwnerId']);
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->decrypt('keyPassword');
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->encrypt('keyPassword');

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->decrypt('keyPassword');
    }

    private function decrypt($attribute)
    {
        if (!$this->$attribute) {
            return;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->$attribute = \Yii::$app->security->decryptByKey(base64_decode($this->$attribute), $encryptionKey);
    }

    private function encrypt($attribute)
    {
        if (!$this->$attribute) {
            return;
        }
        $encryptionKey = getenv('COOKIE_VALIDATION_KEY');
        $this->$attribute = base64_encode(\Yii::$app->security->encryptByKey($this->$attribute, $encryptionKey));
    }

    public function getCertificateIssuer()
    {
        if (empty($this->certificateBody)) {
            return null;
        }

        $certificateX509 = X509FileModel::loadData($this->certificateBody);

        return SBBOLHelper::createCertificateIssuerString($certificateX509);
    }

    public static function findActiveByCustomer($customerId)
    {
        return static::find()
            ->joinWith('keyOwner as keyOwner')
            ->where([
                'keyOwner.customerId' => $customerId,
                'status' => static::STATUS_ACTIVE
            ])
            ->one();
    }
}

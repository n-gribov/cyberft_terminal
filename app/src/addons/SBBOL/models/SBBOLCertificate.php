<?php

namespace addons\SBBOL\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "sbbol_certificate".
 *
 * @property integer $id
 * @property string $dateCreate
 * @property string $status
 * @property string $type
 * @property string $fingerprint
 * @property string $serial
 * @property string $commonName
 * @property string $body
 * @property string $validFrom
 * @property string $validTo
 * @property null|mixed $statusLabel
 * @property null|mixed $typeLabel
 */
class SBBOLCertificate extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'deleted';

    const TYPE_ROOT = 'root';
    const TYPE_BANK = 'bank';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sbbol_certificate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'type', 'fingerprint', 'serial', 'body'], 'required'],
            [['body'], 'string'],
            [['status', 'type'], 'string', 'max' => 100],
            [['fingerprint', 'serial', 'commonName'], 'string', 'max' => 255],
            [['validFrom', 'validTo'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['type', 'in', 'range' => [self::TYPE_ROOT, self::TYPE_BANK]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('app/sbbol', 'Certificate ID'),
            'dateCreate'  => Yii::t('app/sbbol', 'Create date'),
            'status'      => Yii::t('app/sbbol', 'Status'),
            'statusLabel' => Yii::t('app/sbbol', 'Status'),
            'type'        => Yii::t('app/sbbol', 'Type'),
            'typeLabel'   => Yii::t('app/sbbol', 'Type'),
            'fingerprint' => Yii::t('app/sbbol', 'Fingerprint'),
            'serial'      => Yii::t('app/sbbol', 'Serial'),
            'commonName'  => Yii::t('app/sbbol', 'Certificate owner'),
            'body'        => Yii::t('app/sbbol', 'Certificate'),
            'validFrom'   => Yii::t('app/sbbol', 'Valid from'),
            'validTo'     => Yii::t('app/sbbol', 'Valid before'),
        ];
    }

    public static function getTypeLabels()
    {
        return [
            static::TYPE_ROOT => Yii::t('app/sbbol', 'Root'),
            static::TYPE_BANK => Yii::t('app/sbbol', 'Bank'),
        ];
    }

    public static function getStatusLabels()
    {
        return [
            static::STATUS_ACTIVE  => Yii::t('app/sbbol', 'Active'),
            static::STATUS_DELETED => Yii::t('app/sbbol', 'Deleted'),
        ];
    }

    public function getTypeLabel()
    {
        return static::getTypeLabels()[$this->type] ?? null;
    }

    public function getStatusLabel()
    {
        return static::getStatusLabels()[$this->status] ?? null;
    }

    public static function refreshAll(array $certificatesAttributes): bool
    {
        $newCertificatesFingerprints = ArrayHelper::getColumn($certificatesAttributes, 'fingerprint');
        static::updateAll(
            ['status' => static::STATUS_DELETED],
            ['not in', 'fingerprint', $newCertificatesFingerprints]
        );

        $hasErrors = false;
        foreach ($certificatesAttributes as $certificateAttributes) {
            $certificate = self::findOne(['fingerprint' => $certificateAttributes['fingerprint']]);
            if ($certificate === null) {
                $certificate = new self();
            }
            $certificate->setAttributes($certificateAttributes, false);

            try {
                $isSaved = $certificate->save();
                if (!$isSaved) {
                    \Yii::info("Failed to save certificate {$certificate->fingerprint}, errors: " . VarDumper::dumpAsString($certificate->getErrors()));
                    $hasErrors = true;
                }
            } catch (\Exception $exception) {
                \Yii::info("Failed to save certificate {$certificate->fingerprint}, caused by: $exception");
                $hasErrors = true;
            }
        }

        return !$hasErrors;
    }
}

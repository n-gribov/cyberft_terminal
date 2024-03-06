<?php

namespace addons\edm\models;

use Yii;

/**
 * This is the model class for table "userAuthCertBeneficiary".
 *
 * @property int $keyId
 * @property string $terminalId
 */
class UserAuthCertBeneficiary extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userAuthCertBeneficiary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keyId', 'terminalId'], 'required'],
            [['keyId'], 'integer'],
            [['terminalId'], 'string', 'max' => 12],
            [['keyId', 'terminalId'], 'unique', 'targetAttribute' => ['keyId', 'terminalId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'keyId' => Yii::t('app', 'Key ID'),
            'terminalId' => Yii::t('app', 'Terminal ID'),
        ];
    }
}

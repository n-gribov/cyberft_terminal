<?php

namespace addons\SBBOL\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sbbol_customerKeyOwner".
 *
 * @property string $id
 * @property string $fullName
 * @property string $position
 * @property string $signDeviceId
 * @property string $customerId
 *
 * @property SBBOLCustomer $customer
 */
class SBBOLCustomerKeyOwner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sbbol_customerKeyOwner';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fullName', 'signDeviceId', 'customerId'], 'required'],
            [['id', 'signDeviceId', 'customerId'], 'string', 'max' => 100],
            [['fullName', 'position'], 'string', 'max' => 500],
            [['id'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => SBBOLCustomer::className(), 'targetAttribute' => ['customerId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app/sbbol', 'User ID'),
            'fullName'     => Yii::t('app/sbbol', 'User full name'),
            'position'     => Yii::t('app/sbbol', 'User position'),
            'signDeviceId' => Yii::t('app/sbbol', 'Crypto profile ID'),
            'customerId'   => Yii::t('app/sbbol', 'Organization ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(SBBOLCustomer::className(), ['id' => 'customerId']);
    }

    public static function refreshAll($customerId, array $keyOwnersAttributes)
    {
        $newKeyOwnersIds = ArrayHelper::getColumn($keyOwnersAttributes, 'id');
        static::deleteAll([
            'and',
            ['not in', 'id', $newKeyOwnersIds],
            ['customerId' => $customerId]
        ]);

        $hasErrors = false;
        foreach ($keyOwnersAttributes as $keyOwnerAttribute) {
            $keyOwner = self::findOne(['id' => $keyOwnerAttribute['id']]);
            if ($keyOwner === null) {
                $keyOwner = new self();
            }
            $keyOwner->setAttributes($keyOwnerAttribute, false);
            $keyOwner->customerId = $customerId;
            $isSaved = $keyOwner->save();
            if (!$isSaved) {
                \Yii::info("Failed to save key owner {$keyOwner->fullName}, errors: " . var_export($keyOwner->getErrors(), true));
                $hasErrors = true;
            }
        }

        return !$hasErrors;
    }
}

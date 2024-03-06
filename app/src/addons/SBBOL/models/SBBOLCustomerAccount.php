<?php

namespace addons\SBBOL\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sbbol_customerAccount".
 *
 * @property string $id
 * @property string $number
 * @property string $bankBik
 * @property string $currencyCode
 * @property string $customerId
 *
 * @property SBBOLCustomer $customer
 */
class SBBOLCustomerAccount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sbbol_customerAccount';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'number', 'bankBik', 'currencyCode', 'customerId'], 'required'],
            [['id', 'number', 'customerId'], 'string', 'max' => 100],
            [['bankBik'], 'string', 'max' => 20],
            [['currencyCode'], 'string', 'max' => 10],
            [['id'], 'unique'],
            [['number'], 'unique'],
            [['customerId'], 'exist', 'skipOnError' => true, 'targetClass' => SBBOLCustomer::className(), 'targetAttribute' => ['customerId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => Yii::t('app/sbbol', 'Account ID'),
            'number'       => Yii::t('app/sbbol', 'Account number'),
            'bankBik'      => Yii::t('app/sbbol', 'Bank BIK'),
            'currencyCode' => Yii::t('app/sbbol', 'Currency code'),
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

    public static function refreshAll($customerId, array $accountsAttributes)
    {
        $newAccountsIds = ArrayHelper::getColumn($accountsAttributes, 'id');
        static::deleteAll([
            'and',
            ['not in', 'id', $newAccountsIds],
            ['customerId' => $customerId]
        ]);

        $hasErrors = false;
        foreach ($accountsAttributes as $accountAttribute) {
            $account = self::findOne(['id' => $accountAttribute['id']]);
            if ($account === null) {
                $account = new self();
            }
            $account->setAttributes($accountAttribute, false);
            $account->customerId = $customerId;
            $isSaved = $account->save();
            if (!$isSaved) {
                \Yii::info("Failed to save account {$account->number}, errors: " . var_export($account->getErrors(), true));
                $hasErrors = true;
            }
        }

        return !$hasErrors;
    }
}

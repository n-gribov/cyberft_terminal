<?php

namespace addons\sbbol2\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sbbol2_customerAccount".
 *
 * @property string $id
 * @property string $number
 * @property string $bic
 * @property string $currencyCode
 * @property string $customerId
 * @property Sbbol2Customer $customer
 */
class Sbbol2CustomerAccount extends ActiveRecord
{
    public static function tableName()
    {
        return 'sbbol2_customerAccount';
    }

    public function rules()
    {
        return [
            ['number', 'string', 'max' => 100],
            ['bic', 'string', 'max' => 20],
            ['currencyCode', 'string', 'max' => 10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' => Yii::t('app/sbbol2', 'Number'),
            'bic' => Yii::t('app/sbbol2', 'BIC'),
            'currencyCode' => Yii::t('app/sbbol2', 'Currency code'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Sbbol2Customer::className(), ['id' => 'customerId']);
    }

    public static function refreshAll($customerId, array $accountsAttributes)
    {
        $newAccountsNumbers = ArrayHelper::getColumn($accountsAttributes, 'number');
        static::deleteAll([
            'and',
            ['not in', 'number', $newAccountsNumbers],
            ['customerId' => $customerId]
        ]);

        $hasErrors = false;
        foreach ($accountsAttributes as $accountAttribute) {
            $account = self::findOne([
                'number' => $accountAttribute['number'],
                'customerId' => $customerId,
            ]);
            if ($account === null) {
                $account = new self();
            }
            $account->setAttributes($accountAttribute, false);
            $account->customerId = $customerId;
            $isSaved = $account->save();
            if (!$isSaved) {
                Yii::info("Failed to save account {$account->number}, errors: " . var_export($account->getErrors(), true));
                $hasErrors = true;
            }
        }

        return !$hasErrors;
    }
}

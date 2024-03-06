<?php

namespace addons\VTB\models;

use common\validators\TerminalIdValidator;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class VTBCustomerAccount
 * @package addons\VTB\models
 * @property integer $id
 * @property integer $customerId
 * @property string  $number
 * @property string  $bankBik
 * @property string  $bankBranchName
 * @property integer $bankBranchId
 * @property VTBBankBranch $bankBranch
 */
class VTBCustomerAccount extends ActiveRecord
{
    public static function tableName()
    {
        return 'vtb_customerAccount';
    }

    public function rules()
    {
        return [
            [['customerId', 'bankBranchId'], 'integer'],
            [['number', 'bankBik', 'bankBranchName'], 'string'],
            [['customerId', 'bankBik', 'number'], 'required'],
            [['customerId', 'bankBik', 'number'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'customerId'     => Yii::t('app/vtb', 'Customer ID'),
            'number'         => Yii::t('app/vtb', 'Number'),
            'bankBik'        => Yii::t('app/vtb', 'Bank BIK'),
            'bankBranchId'   => Yii::t('app/vtb', 'Bank branch ID'),
            'bankBranchName' => Yii::t('app/vtb', 'Bank name'),
        ];
    }

    public function getBankBranch()
    {
        return $this->hasOne(VTBBankBranch::class, ['bik' => 'bankBik']);
    }

    public static function refreshAll($customerId, $data)
    {
        $toPreserveIds = [];

        foreach ($data as $attributes) {
            $account = self::findOneByCustomerIdAndNumber($attributes['customerId'], $attributes['number']);
            if ($account === null) {
                $account = new VTBCustomerAccount();
            }
            $account->setAttributes($attributes);
            // Сохранить модель в БД
            $account->save();
            $toPreserveIds[] = $account->id;
        }

        static::deleteAll([
            'and',
            ['not in', 'id', $toPreserveIds],
            ['customerId' => $customerId]
        ]);
    }

    public static function findOneByCustomerIdAndNumber($customerId, $number)
    {
        return static::findOne(['customerId' => $customerId, 'number' => $number]);
    }
}

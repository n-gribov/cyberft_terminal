<?php

namespace addons\edm\models\ContractRegistrationRequest;

use Yii;
use yii\db\ActiveRecord;

/**
 * Данные о графике платежей для документа 'Паспорт сделки' типа 'Кредитный договор'
 * Class ContractRegistrationRequestPaymentSchedule
 * @package addons\edm\models\ContractRegistrationRequest
 *
 * @property integer $id
 * @property string $mainDeptDate
 * @property float $mainDeptAmount
 * @property string $interestPaymentsDate
 * @property float $interestPaymentsAmount
 * @property string $comment
 *
 * @author n.poymanov
 */
class ContractRegistrationRequestPaymentSchedule extends ActiveRecord
{
    public static function tableName()
    {
        return 'contractRegistrationRequestPaymentSchedule';
    }

    public function rules()
    {
        return [
            [['mainDeptDate', 'mainDeptAmount'], 'required'],
            [['interestPaymentsDate', 'interestPaymentsAmount', 'comment' ,'documentId'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'mainDeptDate' => Yii::t('edm', "Date"),
            'mainDeptAmount' => Yii::t('edm', "Amount"),
            'interestPaymentsDate' => Yii::t('edm', 'Date'),
            'interestPaymentsAmount' => Yii::t('edm', 'Amount'),
            'comment' => Yii::t('edm', 'Special conditions'),
        ];
    }

    public function getRequest()
    {
        return $this->hasOne(ContractRegistrationRequest::className(), ['id' => 'requestId']);
    }

    public function getPaymentScheduleCurrencyName()
    {
        if ($request = $this->request) {
            return $request->currencyName;
        } else {
            return "";
        }
    }
}
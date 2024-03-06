<?php

namespace addons\edm\models\ContractRegistrationRequest;

use Yii;
use yii\db\ActiveRecord;

/**
 * Данные о траншах для документа 'Паспорт сделки' типа 'Кредитный договор'
 * Class ContractRegistrationRequestTranche
 * @package addons\edm\models\ContractRegistrationRequest
 *
 * @property integer $id
 * @property float $amount
 * @property integer $termCode
 * @property string $date
 *
 * @author n.poymanov
 */
class ContractRegistrationRequestTranche extends ActiveRecord
{
    public static function tableName()
    {
        return 'contractRegistrationRequestTranche';
    }

    public function rules()
    {
        return [
            [['amount', 'termCode', 'date'], 'required'],
            ['documentId', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => Yii::t('edm', "Tranche's amount"),
            'termCode' => Yii::t('edm', "Tranche's term code"),
            'date' => Yii::t('edm', 'The expected date of tranche'),
        ];
    }

    public function getRequest()
    {
        return $this->hasOne(ContractRegistrationRequest::className(), ['id' => 'requestId']);
    }

    public function getTrancheAmountPrintable()
    {
        return $this->amount;
    }

    public function getTermCodePrintable()
    {
        return $this->termCode;
    }

    public function getDatePrintable()
    {
        return $this->date;
    }

    public function getTrancheCurrencyDescription()
    {
        if ($request = $this->request) {
            return $request->currencyDescription;
        } else {
            return "";
        }
    }

    public function getTrancheCurrencyCode()
    {
        if ($request = $this->request) {
            return $request->currencyCode;
        } else {
            return "";
        }
    }
}
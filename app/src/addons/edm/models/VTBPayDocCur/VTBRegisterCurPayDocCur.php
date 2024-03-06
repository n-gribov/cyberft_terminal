<?php

namespace addons\edm\models\VTBPayDocCur;

use addons\edm\models\DictCurrency;
use yii\db\ActiveRecord;

class VTBRegisterCurPayDocCur extends ActiveRecord
{
    public static function tableName()
    {
        return 'vtb_RegisterCurPayDocCur';
    }

    public function rules()
    {
        return [
            [
                [
                    'documentId', 'number', 'date', 'payer',
                    'payerAccount', 'beneficiar', 'beneficiarAccount',
                    'beneficiarBank', 'amount', 'currency', 'paymentPurpose'
                ], 'required'
            ],
            ['status', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' => 'Номер документа',
            'date' => 'Дата',
            'payer' => 'Плательщик',
            'payerAccount' => 'Счет списания',
            'beneficiar' => 'Бенифициар',
            'beneficiarAccount' => 'Счет бенифициара',
            'beneficiarBank' => 'Банк бенифициара',
            'amount' => 'Сумма',
            'currency' => 'Валюта',
            'paymentPurpose' => 'Назначение платежа',
            'status' => 'Статус исполнения',
        ];
    }

    public function loadTypeModel(VTBPayDocCurType $typeModel)
    {
        $vtbDocument = $typeModel->document;
        $currency = DictCurrency::findOne(['code' => $vtbDocument->CURRCODETRANSFER]);

        $this->number = $vtbDocument->DOCUMENTNUMBER;
        $this->date = $vtbDocument->DOCUMENTDATE->format('d-m-Y');
        $this->payer = $vtbDocument->PAYER;
        $this->payerAccount = $vtbDocument->PAYERACCOUNT;
        $this->beneficiar = $vtbDocument->BENEFICIAR;
        $this->beneficiarAccount = $vtbDocument->BENEFICIARACCOUNT;
        $this->beneficiarBank = $vtbDocument->BENEFBANKNAME;
        $this->amount = $vtbDocument->AMOUNTTRANSFER;
        $this->currency = $currency !== null ? $currency->name : null;
        $this->paymentPurpose = $vtbDocument->PAYMENTSDETAILS;
    }

}
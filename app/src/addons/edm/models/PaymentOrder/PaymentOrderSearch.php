<?php

namespace addons\edm\models\PaymentOrder;

use common\document\DocumentSearch;
use yii\helpers\ArrayHelper;

class PaymentOrderSearch extends DocumentSearch
{
    public $number;
    public $payerAccount;
    public $payerName;
    public $paymentPurpose;
    public $beneficiaryName;
    public $currency;
    public $sum;
    public $date;

    private $_extModel;

    public function init()
    {
        parent::init();

        $this->_extModel = new PaymentOrderDocumentExt();
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [array_values($this->_extModel->attributes()), 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), $this->_extModel->attributeLabels());
    }

    public function getDocumentExtEdmPaymentOrder()
    {
        return $this->hasOne(get_class($this->_extModel), ['documentId' => 'id']);
    }

    public function applyExtFilters($query)
    {
        $extTableName = $this->_extModel->tableName();

        $query->joinWith([$extTableName]);

        $query
            ->andFilterWhere(["{$extTableName}.number" => $this->number])
            ->andFilterWhere(['like', "{$extTableName}.payerAccount", $this->payerAccount])
            ->andFilterWhere(['like', "{$extTableName}.payerName", $this->payerName])
            ->andFilterWhere(['like', "{$extTableName}.paymentPurpose", $this->paymentPurpose])
            ->andFilterWhere(['like', "{$extTableName}.sum", $this->sum])
            ->andFilterWhere(['like', "{$extTableName}.beneficiaryName", $this->beneficiaryName])
            ->andFilterWhere(['like', "{$extTableName}.currency", $this->currency]);
    }

}
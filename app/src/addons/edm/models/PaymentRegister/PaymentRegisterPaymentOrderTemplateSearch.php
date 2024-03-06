<?php

namespace addons\edm\models\PaymentRegister;

use common\helpers\NumericHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;

class PaymentRegisterPaymentOrderTemplateSearch extends PaymentRegisterPaymentOrderTemplate
{
    public $searchBody;
    protected $_highlights;
    public $startDate;
    public $endDate;
    public $payer;
    public $accountNumber;

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['startDate', 'endDate', 'payer', 'accountNumber'], 'safe'],
            ['showDeleted', 'boolean']
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(
            [
                'startDate' => Yii::t('edm', 'Period start date'),
                'endDate' => Yii::t('edm', 'Period end date'),
                'payer' => Yii::t('edm', 'Payer'),
                'accountNumber' => Yii::t('edm', 'Account number')
            ],
            parent::attributeLabels()
        );
    }

    public function search($params, $isReadyForPaymentRegister = null)
    {
        $query = Yii::$app->terminalAccess->query(PaymentRegisterPaymentOrderTemplateSearch::className());

        $this->applyQueryFilters($params, $query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => new Pagination([
                'pageSize' => 20,
            ])
        ]);

        if (!empty($this->searchBody)) {
            $models = $dataProvider->getModels();
            $idList = [];

            foreach($models as $model) {
                $idList[] = $model->id;
            }

            $result = Yii::$app->elasticsearch->search($this->typeGroup, '', $this->searchBody, $idList, true);

            if ($result !== false) {
                $this->_highlights = [];

                foreach($result['hits']['hits'] as $row) {
                    $allHighlights = '';

                    foreach($row['highlight'] as $key => $field) {
                        $allHighlights .= $key . ':' . $field[0] . '<br>';
                    }

                    $this->_highlights[$row['_id']] = $row['highlight'];
                }
            }
        }

        return $dataProvider;
    }

    public function applyQueryFilters($params, $query)
    {
        $this->load($params);

        $query->andFilterWhere(['like', 'payerAccount', $this->payerAccount])
            ->andFilterWhere(['like', 'payerName', $this->payerName])
            ->andFilterWhere(['like', 'paymentPurpose', $this->paymentPurpose])
            ->andFilterWhere(['like', 'beneficiaryName', $this->beneficiaryName])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['>=', 'date', $this->startDate])
            ->andFilterWhere(['<=', 'date', $this->endDate]);

        // C учетом доступных текущему пользователю счетов
        $query = Yii::$app->edmAccountAccess->query($query, 'payerAccount', true);

        if (!empty($this->sum)) {
            $query->andWhere(['sum' => NumericHelper::getFloatValue($this->sum)]);
        }
    }
}
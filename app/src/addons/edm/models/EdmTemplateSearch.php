<?php

namespace addons\edm\models;

use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyPaymentTemplate;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use Yii;

class EdmTemplateSearch extends Model
{
    public $payerAccount;
    
    public function search($params)
    {
        $templates = [];

        if (isset($params['type']) && $params['type']) {
            if ($params['type'] == 'paymentOrder') {
                // Шаблоны платежных поручений
                $paymentOrdersTemplates = $this->getPaymentOrderTemplates($params);
                $templates = array_merge($templates, $paymentOrdersTemplates);
            } else if ($params['type'] == 'foreignCurrencyPayment') {
                // Шаблоны валютных операций
                $fcpTemplates = $this->getFCPTemplates($params);
                $templates = array_merge($templates, $fcpTemplates);
            }
        } else {
            // Шаблоны платежных поручений
            $paymentOrdersTemplates = $this->getPaymentOrderTemplates($params);
            $templates = array_merge($templates, $paymentOrdersTemplates);

            // Шаблоны валютных операций
            $fcpTemplates = $this->getFCPTemplates($params);
            $templates = array_merge($templates, $fcpTemplates);
        }

        ksort($templates);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $templates
        ]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'type' => Yii::t('edm', 'Template type'),
            'name' => Yii::t('edm', 'Template name'),
            'payerName' => Yii::t('edm', 'Payer'),
            'payerAccount' => Yii::t('edm', 'Payer account'),
            'beneficiaryName' => Yii::t('edm', 'Beneficiary'),
            'sum' => Yii::t('edm', 'Sum'),
            'currency' => Yii::t('edm', 'Currency'),
            'paymentPurpose' => Yii::t('edm', 'Payment Purpose')
        ];
    }

    /**
     * Получение шаблонов платежных поручений
     * @return mixed
     */
    private function getPaymentOrderTemplates($params)
    {
        $map = [
            'name' => 'name',
            'payerName' => 'payerName',
            'payerAccount' => 'payerAccount',
            'paymentPurpose' => 'paymentPurpose',
            'beneficiaryName' => 'beneficiaryName',
            'currency' => 'currency',
            'sum' => 'sum',
        ];

        $query = Yii::$app->terminalAccess->query(PaymentRegisterPaymentOrderTemplate::className());
        $query = Yii::$app->edmAccountAccess->query($query, 'payerAccount', true);

        $this->buildSelect($query, $map, ['id', '("PaymentOrder") as type']);
        $this->buildLikeCondition($query, $map, $params);

        $query->asArray();

        return $query->all();
    }

    /**
     * Получение шаблонов валютных платежей
     * @param $params
     * @return mixed
     */
    private function getFCPTemplates($params)
    {
        $map = [
            'templateName' => 'name',
            'payerName' => 'payerName',
            'payerAccount' => 'payerAccount',
            'information' => 'paymentPurpose',
            'beneficiary' => 'beneficiaryName',
            'currency' => 'currency',
            'sum' => 'sum',
        ];

        $query = Yii::$app->terminalAccess->query(ForeignCurrencyPaymentTemplate::className());
        $query = Yii::$app->edmAccountAccess->query($query, 'payerAccount', true);

        $this->buildSelect($query, $map, ['id', '("ForeignCurrencyPayment") as type']);
        $this->buildLikeCondition($query, $map, $params);

        $query->asArray();

        return $query->all();
    }

    private function buildSelect($query, $map, $params = [])
    {
        $select = [];
        foreach ($map as $key => $value) {
            if ($key != $value) {
                $select[] = $key . ' as ' . $value;
            } else {
                $select[] = $key;
            }
        }

        $query->select(array_merge($params, $select));
    }

    private function buildLikeCondition($query, $map, $params)
    {
        foreach ($map as $field => $key) {
            if (isset($params[$key])) {
                $query->andFilterWhere(['like', $field, $params[$key]]);
            }
        }
    }

}
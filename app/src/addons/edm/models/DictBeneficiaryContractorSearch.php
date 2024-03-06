<?php

namespace addons\edm\models;

use addons\edm\models\DictBeneficiaryContractor;
use Yii;
use yii\data\ActiveDataProvider;

class DictBeneficiaryContractorSearch extends DictBeneficiaryContractor
{
    public $bankName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankName', 'inn', 'kpp', 'account', 'name', 'bankBik', 'type'], 'safe']
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Yii::$app->terminalAccess->query(DictBeneficiaryContractor::className());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $this->applyExtFilters($query);

        $tableName = parent::tableName();

        $query->andFilterWhere(['like', 'inn', $this->inn])
              ->andFilterWhere(['like', 'kpp', $this->kpp])
              ->andFilterWhere(['like', "{$tableName}.account", $this->account])
              ->andFilterWhere(['like', "{$tableName}.name", $this->name])
              ->andFilterWhere(['like', 'bankBik', $this->bankBik])
              ->andFilterWhere(['=', 'type', $this->type]);

        return $dataProvider;
    }

    /**
     * Фильтры со связями по другим таблицам
     * @param $query
     */
    public function applyExtFilters($query)
    {
        // Запрос по связанной таблице банков
        $bankTableName = DictBank::tableName();
        $query->joinWith(['bank']);

        $query->andFilterWhere(['like', "{$bankTableName}.name", $this->bankName]);
    }

}

<?php

namespace addons\edm\models;

use addons\edm\models\DictCurrency;
use Yii;
use yii\data\ActiveDataProvider;

class DictCurrencySearch extends DictCurrency
{
    public $terminalName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'name', 'description'], 'safe']
        ];
    }

    public function search($params)
    {
        $query = DictCurrency::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

}

<?php

namespace addons\swiftfin\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use addons\swiftfin\models\SwiftFinDictBank;

class SwiftFinDictBankSearch extends SwiftFinDictBank
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['swiftCode', 'branchCode', 'name'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = SwiftFinDictBank::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['swiftCode' => SORT_ASC]],
		]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query
			->andFilterWhere(['like', 'swiftCode', $this->swiftCode])
			->andFilterWhere(['like', 'branchCode', $this->branchCode])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}

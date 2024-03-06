<?php

namespace addons\edm\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use addons\edm\models\DictBank;

class DictBankSearch extends DictBank
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bik'], 'integer'],
            [['account', 'name', 'city'], 'safe'],
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
        $query = DictBank::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['terminalId' => SORT_DESC, 'bik' => SORT_ASC]],
		]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query
			->andFilterWhere(['like', 'bik', $this->bik])
			->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'city', $this->city]);

        return $dataProvider;
    }
}

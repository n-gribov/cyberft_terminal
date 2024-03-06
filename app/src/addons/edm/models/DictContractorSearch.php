<?php

namespace addons\edm\models;

use addons\edm\models\DictContractor;
use common\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DictContractorSearch extends DictContractor
{
	public $bank;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'bankBik', 'kpp'], 'integer'],
            [['inn', 'account', 'terminalId', 'currency', 'name', 'bank'], 'safe'],
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
        $query = Yii::$app->terminalAccess->query(DictContractor::className());

		//$query->joinWith(['bank']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'bankBik' => $this->bankBik,
            'kpp' => $this->kpp,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'inn', $this->inn])
            ->andFilterWhere(['like', 'account', $this->account])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

}

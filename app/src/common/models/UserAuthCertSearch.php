<?php

namespace common\models;

use yii\data\ActiveDataProvider;


class UserAuthCertSearch extends UserAuthCert
{

	public function rules()
	{
		return [
			[['expiryDate', 'fingerprint'], 'safe'],
		];
	}

    public function search($params, $userId)
    {
        $query = parent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['status' => SORT_ASC]
            ],
			'pagination' => [
				'pageSize' => 20,
			],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'userId' => $userId,
            'expiryDate' => $this->expiryDate
        ]);

        $query->andFilterWhere(['like', 'fingerprint', $this->fingerprint]);

        return $dataProvider;
    }
}

<?php

namespace addons\edm\models;

use addons\edm\models\DictPaymentPurpose;
use common\models\UserTerminal;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class DictPaymentPurposeSearch extends DictPaymentPurpose
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['value'], 'string', 'max' => 180],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = DictPaymentPurpose::find();

        $terminalId = Yii::$app->user->identity->terminalId;
        if (empty($terminalId)) {
            $terminalId = array_keys(UserTerminal::getUserTerminalIds(Yii::$app->user->id));
        }

        if ($terminalId) {
            $query->where(['terminalId' => $terminalId]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}

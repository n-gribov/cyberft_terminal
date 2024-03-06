<?php

namespace addons\edm\models;

use addons\edm\models\DictOrganization;
use common\models\Terminal;
use Yii;
use yii\data\ActiveDataProvider;

class DictOrganizationSearch extends DictOrganization
{
    public $terminalName;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inn', 'terminalName', 'name', 'kpp', 'type'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Yii::$app->terminalAccess->query(DictOrganization::className());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $this->applyExtFilters($query);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'inn', $this->inn])
              ->andFilterWhere(['like', 'kpp', $this->kpp])
              ->andFilterWhere(['=', 'type', $this->type]);

        return $dataProvider;
    }

    /**
     * Фильтры со связями по другим таблицам
     * @param $query
     */
    public function applyExtFilters($query)
    {
        // Запрос по связанной таблице пользователей
        $terminalTableName = Terminal::tableName();
        $query->joinWith([$terminalTableName]);

        $query->andFilterWhere(['=', "{$terminalTableName}.id", $this->terminalName]);
    }

}

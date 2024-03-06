<?php

namespace common\models;

use yii\data\ActiveDataProvider;
use Yii;

class ImportErrorSearch extends ImportError {

    public function rules()
    {
        return parent::rules();
    }

    public function search($params) {

        $query = ImportError::find()->with('senderTerminal');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['dateCreate' => SORT_DESC],
            ]
        ]);

        $this->load($params);

        $this->applyFilters($query);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    public function applyFilters($query)
    {
        // Фильтр по типу документа
        $query->andFilterWhere(['type' => $this->type]);

        // Фильтр по идентификатору
        if ($this->identity) {

            $string = strtolower(Yii::t('other', 'Identity error'));
            $search = strtolower($this->identity);

            // Проверка на запрос пустых значений
            if (stristr($string, $search) !== false) {
                $query->andWhere(['identity' => null]);
            } else {
                $query->andFilterWhere(['like', 'identity', $this->identity]);
            }
        }

        // Фильтр по имени файла
        $query->andFilterWhere(['like', 'filename', $this->filename]);

        // Фильтр по дате создания
        if ($this->dateCreate) {
            $dateCreate = \DateTime::createFromFormat('d.m.Y', $this->dateCreate);

            if ($dateCreate === false) {
                $dateCreate = new \DateTime();
            }

            $dateCreateFormat = $dateCreate->format('Y.m.d');
            $query->andWhere(['>=', 'dateCreate', $dateCreateFormat . ' 00:00:00']);
            $query->andWhere(['<=', 'dateCreate', $dateCreateFormat . ' 23:59:59']);
        }

        $query->andFilterWhere(['documentNumber' => $this->documentNumber]);
        $query->andFilterWhere(['documentCurrency' => $this->documentCurrency]);
        $query->andFilterWhere(['senderTerminalAddress' => $this->senderTerminalAddress]);
    }

}
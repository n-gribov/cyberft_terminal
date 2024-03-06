<?php

namespace common\modules\monitor\models;

use common\data\InfiniteActiveDataProvider;
use common\data\InfinitePagination;
use common\helpers\DocumentHelper;
use common\models\User;
use common\models\UserTerminal;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;

/**
 * Monitor log search model class
 *
 * @package modules
 * @subpackage monitor
 */
class MonitorLogSearch extends MonitorLogAR
{
    /**
     * Search log
     *
     * @param array $params Params
     * @return ActiveDataProvider
     */

    public function search($params)
    {
        $query = $this->find();
        $query = $this->filterByTerminalAccess($query);

		$this->applyQueryFilters($params, $query);

        $dataProvider = new InfiniteActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => new InfinitePagination([
                'pageSize' => 100,
            ])
        ]);

        return $dataProvider;
    }

    public function searchDocument($id, $params)
    {
        $query = $this->find()->where([
            'entity' => 'document',
            'entityId' => $id,
            'eventCode' => DocumentHelper::getDocumentEventNames()
        ])->orderBy(['id' => SORT_DESC]);

        $this->load($params);

        $query->andFilterWhere([
            'initiatorType' => $this->initiatorType,
        ])->andFilterWhere(['like', 'eventCode', $this->eventCode]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => new Pagination([
                'pageSize' => 20,
            ])
        ]);

        return $dataProvider;

    }

    public function applyQueryFilters($params, $query)
    {
        $this->load($params);

        $query->andFilterWhere([
            'logLevel'    => $this->logLevel,
            'componentId' => $this->componentId,
            'initiatorType' => $this->initiatorType,
        ])
        ->andFilterWhere(['=', 'eventCode', $this->eventCode])
        ->andFilterWhere(['=', 'ip', $this->ip]) // отбор по ip события
        ->andFilterWhere(['terminalId' => $this->terminalId]); // отбор по терминалу события

        $this->applyDateFilters($query); // Применяем фильтр по датам
    }

    public function applyDateFilters($query)
    {
        if ($this->dateCreated) {
            //прибавляем день к дате из формы
            $dPlusDay = strtotime($this->dateCreated) + 86400;
            $query->andFilterWhere(['>=', 'dateCreated', strtotime($this->dateCreated)]);
            $query->andFilterWhere(['<', 'dateCreated', $dPlusDay]);
        }
    }

    private function filterByTerminalAccess($query)
    {
        if (!empty(Yii::$app->user) && !empty(Yii::$app->user->identity) &&
            Yii::$app->user->identity->role != User::ROLE_ADMIN) {

            $user = Yii::$app->user->identity;

            $terminalId = $user->terminalId;

            if (empty($terminalId) && $user->disableTerminalSelect) {
                $terminalId = array_keys(UserTerminal::getUserTerminalIds($user->id));
            }

            if ($terminalId) {
                $where = ['terminalId' => $terminalId];
            } else {
                $where = '0=1';
            }

            $query->where($where);
        }

        return $query;
    }

}
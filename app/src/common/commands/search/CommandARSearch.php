<?php

namespace common\commands\search;

use common\commands\CommandAR;
use DateTimeZone;
use DateTime;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;

/**
 * Command active record model class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @preperty string $dateCreateFrom Create date from
 * @property string $dateCreateBefore Created date before
 */
class CommandARSearch extends CommandAR
{
    /**
     *
     * @var string $dateCreateFrom Create date from
     */
    public $dateCreateFrom;

    /**
     *
     * @var string $dateCreateBefore Created date before
     */
    public $dateCreateBefore;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'entity', 'entityId', 'userId', 'status'], 'string'],
            [['acceptsCount', 'userId'], 'integer'],
            [['dateCreateFrom', 'dateCreateBefore'], 'date', 'format' => 'yyyy-MM-dd'],
        ];
    }

    /**
     * Search approve
     *
     * @param array $params Query params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = CommandAR::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['dateCreate' => SORT_DESC]],
            'pagination' => new Pagination([
                'pageSize' => 20,
                ])
        ]);

        $this->applyQueryFilters($params, $query);

        // Page number must be set here to fix Pagination bug.
        $dataProvider->pagination->page = !empty($params['page']) ? ($params['page']
            - 1) : 0;

        return $dataProvider;
    }

    /**
     * Ищет все команды пользователя
     *
     * @param $userId
     * @param array $params Query params
     * @return ActiveDataProvider
     */
    public function searchUserCommands($userId, $params)
    {
        $query = CommandAR::find()->andWhere(['userId' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['dateCreate' => SORT_DESC]],
            'pagination' => new Pagination([
                'pageSize' => 20,
            ])
        ]);

        $this->applyQueryFilters($params, $query);

        // Page number must be set here to fix Pagination bug.
        $dataProvider->pagination->page = !empty($params['page']) ? ($params['page']
            - 1) : 0;

        return $dataProvider;
    }

    /**
     * Ищет все команды над пользователем
     *
     * @param $userId
     * @param array $params Query params
     * @return ActiveDataProvider
     */
    public function searchAccountOperation($userId, $params)
    {
        $query = CommandAR::find()->andWhere(['entityId' => $userId]);

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['dateCreate' => SORT_DESC]],
            'pagination' => new Pagination([
                'pageSize' => 20,
            ])
        ]);

        $this->applyQueryFilters($params, $query);

        // Page number must be set here to fix Pagination bug.
        $dataProvider->pagination->page = !empty($params['page']) ? ($params['page']
            - 1) : 0;

        return $dataProvider;
    }

    /**
     * Apply approve filters
     *
     * @param array        $params  Query params
     * @param ActiveQuery  $query   Query
     * @return ActiveQuery
     */
    protected function applyQueryFilters($params, $query)
    {
        $this->load($params);

        if (!$this->validate()) {
            return $query->andOnCondition('0=1');
        }

        if ($this->dateCreateFrom) {
            $dFrom = (new DateTime($this->dateCreateFrom,
                new DateTimeZone(Yii::$app->getTimeZone())))
                ->setTimezone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s');

            $query->andFilterWhere(['>=', 'dateCreate', $this->dateCreateFrom ? $dFrom
                        : null]);
        }

        if ($this->dateCreateBefore) {
            $dBefore = (new DateTime($this->dateCreateBefore,
                new DateTimeZone(Yii::$app->getTimeZone())))
                ->setTimezone(new DateTimeZone('UTC'))
                ->modify('+1 day')
                ->format('Y-m-d H:i:s');

            $query->andFilterWhere(['<=', 'dateCreate', $this->dateCreateBefore ? $dBefore
                        : null]);
        }

        $query->andFilterWhere([
            'code'         => $this->code,
            'status'       => $this->status,
            'entity'       => $this->entity,
            'entityId'     => $this->entityId,
            'acceptsCount' => $this->acceptsCount,
            'userId'       => $this->userId,
        ]);
    }
}
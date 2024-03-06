<?php

namespace common\commands\search;

use common\commands\CommandAcceptAR;
use DateTime;
use DateTimeZone;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;

/**
 * Command active record search model
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage command
 *
 * @preperty string $dateCreateFrom Create date from
 * @property string $dateCreateBefore Created date before
 */
class CommandAcceptARSearch extends CommandAcceptAR
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
            [['id', 'commandId'], 'integer'],
            ['acceptResult', 'in', 'range' => [self::ACCEPT_RESULT_ACCEPTED, self::ACCEPT_RESULT_REJECTED]],
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
        $query = CommandAcceptAR::find();

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'sort'       => ['defaultOrder' => ['dateCreate' => SORT_DESC]],
            'pagination' => new Pagination([
                'pageSize' => 20,
                ])
        ]);

        $this->applyQueryFilters($params, $query);

        // Page number must be set here to fix Pagination bug.
		$dataProvider->pagination->page = !empty($params['page']) ? ($params['page'] - 1) : 0;

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
            'userId'       => Yii::$app->user->id,
            'commandId'    => $this->commandId,
            'acceptResult' => $this->acceptResult,
        ]);
    }
}
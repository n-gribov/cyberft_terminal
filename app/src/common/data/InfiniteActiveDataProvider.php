<?php

namespace common\data;

use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\QueryInterface;

class InfiniteActiveDataProvider extends ActiveDataProvider
{
    protected function prepareModels()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }

        $pagination = $this->getPagination() ?: null;
        if ($pagination && !($pagination instanceof InfinitePagination)) {
            throw new InvalidConfigException('The "pagination" property must be an instance of a class common\data\InfinitePagination or its subclasses.');
        }

        $query = clone $this->query;

        if ($pagination) {
            $query->limit($pagination->getLimit() + 1)->offset($pagination->getOffset());
        }
        if (($sort = $this->getSort()) !== false) {
            $query->addOrderBy($sort->getOrders());
        }

        $models = $query->all($this->db);
        if ($pagination) {
            if (count($models) > $pagination->getLimit()) {
                array_splice($models, $pagination->getLimit());
                $pagination->hasMorePages = true;
            } else {
                $pagination->hasMorePages = false;
            }
        }

        return $models;
    }

    protected function prepareTotalCount()
    {
        throw new \Exception('Not applicable');
    }
}

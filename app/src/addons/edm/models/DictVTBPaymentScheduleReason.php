<?php

namespace addons\edm\models;

use yii\base\BaseObject;

class DictVTBPaymentScheduleReason extends BaseObject
{
    public $id;
    public $name;

    private static $periods = null;
    private static $data = [
        ['id' => 1, 'name' => 'сведения из кредитного договора'],
        ['id' => 2, 'name' => 'оценочные данные'],
    ];

    /**
     * @return DictVTBPaymentScheduleReason[]
     */
    public static function all() {
        if (static::$periods === null) {
            static::$periods = array_map(
                function ($attributes) {
                    return new static($attributes);
                },
                static::$data
            );
        }
        return static::$periods;
    }

    /**
     * @param $id
     * @return DictVTBPaymentScheduleReason|null
     */
    public static function findOneById($id)
    {
        foreach (static::all() as $period) {
            if ($period->id == $id) {
                return $period;
            }
        }
        return null;
    }
}

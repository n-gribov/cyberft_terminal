<?php

namespace addons\edm\models;

use yii\base\BaseObject;

class DictVTBRepaymentPeriod extends BaseObject
{
    public $code;
    public $name;

    private static $periods = null;
    private static $data = [
        ['code' => 0, 'name' => 'до 30 дней'],
        ['code' => 1, 'name' => 'от 31 до 90 дней'],
        ['code' => 2, 'name' => 'от 91 до 180 дней'],
        ['code' => 3, 'name' => 'от 181 дня до 1 года'],
        ['code' => 4, 'name' => 'от 1 года до 3 лет'],
        ['code' => 5, 'name' => 'свыше 3 лет'],
        ['code' => 6, 'name' => 'до востребования'],
    ];

    /**
     * @return DictVTBRepaymentPeriod[]
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
     * @param $code
     * @return DictVTBRepaymentPeriod|null
     */
    public static function findOneByCode($code)
    {
        foreach (static::all() as $period) {
            if ($period->code == $code) {
                return $period;
            }
        }
        return null;
    }
}

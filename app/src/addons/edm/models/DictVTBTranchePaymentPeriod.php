<?php

namespace addons\edm\models;

use yii\base\BaseObject;

class DictVTBTranchePaymentPeriod extends BaseObject
{
    public $id;
    public $code;
    public $name;

    private static $periods = null;
    private static $data = [
        ['code' => 0, 'name' => 'до 30 дней'],
        ['code' => 1, 'name' => 'от 31 до 90 дней'],
        ['code' => 2, 'name' => 'от 91 до 180 дней'],
        ['code' => 3, 'name' => 'от 181 дня до 1 года'],
        ['code' => 4, 'name' => 'от 1 года до 3 лет'],
        ['code' => 6, 'name' => 'до востребования'],
        ['code' => 7, 'name' => 'от 3 лет до 5 лет'],
        ['code' => 8, 'name' => 'от 5 лет до 10 лет'],
        ['code' => 9, 'name' => 'Свыше 10 лет'],
    ];

    /**
     * @return DictVTBTranchePaymentPeriod[]
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
     * @return DictVTBTranchePaymentPeriod|null
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

<?php

namespace addons\edm\models;

use yii\base\BaseObject;

class DictVTBGroundDocumentType extends BaseObject
{
    public $id;
    public $code;
    public $name;

    private static $types = null;
    private static $data = [
        ['id' => 1,  'name' => 'Паспорт сделки',                                   'code' => '1'],
        ['id' => 2,  'name' => 'Контракт',                                         'code' => '2'],
        ['id' => 3,  'name' => 'Договор',                                          'code' => '3'],
        ['id' => 6,  'name' => 'Декларация на товары',                             'code' => '6'],
        ['id' => 7,  'name' => 'Уникальный номер контракта (кредитного договора)', 'code' => 'UNC'],
        ['id' => 21, 'name' => 'Иной документ',                                    'code' => '21'],
    ];

    /**
     * @return DictVTBGroundDocumentType[]
     */
    public static function all() {
        if (static::$types === null) {
            static::$types = array_map(
                function ($attributes) {
                    return new static($attributes);
                },
                static::$data
            );
        }
        return static::$types;
    }

    /**
     * @param $code
     * @return DictVTBGroundDocumentType|null
     */
    public static function findOneByCode($code)
    {
        foreach (static::all() as $type) {
            if ($type->code == $code) {
                return $type;
            }
        }
        return null;
    }
}

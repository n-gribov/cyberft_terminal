<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class Processing
 * @package common\models
 * @property integer $id
 * @property string  $name
 * @property string  $address
 * @property string  $dsn
 * @property boolean $isDefault
 * @property string  $apiUrl
 */
class Processing extends ActiveRecord
{
    public static function tableName()
    {
        return 'processing';
    }

    public function rules()
    {
        return [
            [['name', 'address', 'isDefault'], 'required'],
            [['name', 'dsn'], 'string'],
            ['isDefault', 'boolean'],
            ['isDefault', 'default', 'value' => false],
            ['address', 'string', 'length' => 12],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->isDefault) {
            static::updateAll(
                ['isDefault' => false],
                [
                    'isDefault' => true,
                    ['not', ['id' => $this->id]],
                ]
            );
        }
        parent::afterSave($insert, $changedAttributes);
    }
}

<?php

namespace addons\edm\models;

use yii\db\ActiveRecord;

/**
 * Class DictPropertyType
 * @package addons\edm\models
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $vtbId
 */
 class DictPropertyType extends ActiveRecord
{
     public static function tableName()
     {
         return 'edmDictPropertyType';
     }
 }

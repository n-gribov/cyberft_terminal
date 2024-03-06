<?php
namespace common\db;

use yii\db\ActiveRecord as yiiActiveRecord;
use Yii;

class ActiveRecord extends yiiActiveRecord
{
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

}

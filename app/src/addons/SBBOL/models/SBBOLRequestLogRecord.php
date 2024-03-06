<?php

namespace addons\SBBOL\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $dateCreate
 * @property string $name
 * @property string $body
 * @property string $responseBody
 * @property string $digest
 */
class SBBOLRequestLogRecord extends ActiveRecord
{
    public static function tableName()
    {
        return 'sbbol_requestLog';
    }

    public function rules()
    {
        return [
            [['name', 'dateCreate', 'body', 'responseBody', 'digest'], 'string'],
            [['name', 'body'], 'required'],
        ];
    }

}

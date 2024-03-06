<?php

namespace common\document;

use yii\db\ActiveRecord;

/**
 * @property int      $documentId
 * @property string   $data
 */
class SignData extends ActiveRecord
{
    public static function tableName()
    {
        return 'signData';
    }

    public function rules()
    {
        return [
            [['documentId', 'data'], 'required'],
            ['documentId', 'number'],
            ['data', 'string'],
        ];
    }
}

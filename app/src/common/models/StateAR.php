<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * @property integer $documentId
 * @property string $terminalId
 * @property string $code
 * @property string $status
 * @property string $data
 */
class StateAR extends ActiveRecord
{
    public static function tableName()
    {
        return 'state';
    }

    public function rules()
    {
        return [
            [['status', 'code', 'documentId', 'terminalId'], 'required'],
            ['documentId', 'integer'],
            ['dateRetry', 'date', 'format' => 'php:Y-m-d H:i:s'],
            ['data', 'string'],
        ];
    }
}

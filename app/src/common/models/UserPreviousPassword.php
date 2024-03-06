<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * Class UserPreviousPassword
 * @package common\models
 * @property integer $id
 * @property integer $userId
 * @property string $createDate
 * @property string $passwordHash
 */
class UserPreviousPassword extends ActiveRecord
{
    public static function tableName()
    {
        return 'userPreviousPassword';
    }

    public function rules()
    {
        return [
            [['id', 'userId'], 'integer'],
            [['createDate', 'passwordHash'], 'string'],
            [['userId', 'passwordHash'], 'required'],
        ];
    }
}

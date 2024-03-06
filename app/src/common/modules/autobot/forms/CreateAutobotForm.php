<?php

namespace common\modules\autobot\forms;

use Yii;
use yii\base\Model;

class CreateAutobotForm extends Model
{
    public $password;
    public $passwordConfirmation;

    public function rules()
    {
        return [
            [['password', 'passwordConfirmation'], 'string'],
            [['password', 'passwordConfirmation'], 'required'],
            [['password', 'passwordConfirmation'], 'safe'],
            ['passwordConfirmation', 'compare', 'compareAttribute'=>'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'passwordConfirmation' => Yii::t('app', 'Confirm password'),
        ];
    }
}

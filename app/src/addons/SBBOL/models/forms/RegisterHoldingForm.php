<?php

namespace addons\SBBOL\models\forms;

use Yii;
use yii\base\Model;

class RegisterHoldingForm extends Model
{
    public $login;
    public $password;
    public $senderName;

    public function attributeLabels()
    {
        return [
            'login' => Yii::t('app/sbbol', 'Login'),
            'password' => Yii::t('app/sbbol', 'Password'),
            'senderName' => Yii::t('app/sbbol', 'Sender name'),
        ];
    }

    public function rules()
    {
        $allAttributes = ['login', 'password', 'senderName'];

        return [
            [$allAttributes, 'string'],
            [$allAttributes, 'safe'],
            [$allAttributes, 'required'],
        ];
    }
}

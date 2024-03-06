<?php
namespace common\models\form;

use Yii;
use yii\base\Model;

class KeyActivationForm extends Model
{
    public $key;

    public function rules()
    {
        return [
            [['key'], 'required'],
            ['key', 'validateActivateKey'],
        ];
    }

     public function attributeLabels()
    {
        return [
            'key' => Yii::t('app/user', 'Activation key'),
        ];
    }

    public function validateActivateKey($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;

            if (!$user || !$user->validateActivateKey($this->key)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect validation key.'));
            }
        }
    }
}

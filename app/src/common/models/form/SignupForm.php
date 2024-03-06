<?php

namespace common\models\form;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form model
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package \common\models\form
 *
 * @property string $username User name
 * @property string $email User Email
 * @property string $password User password
 *
 */
class SignupForm extends Model
{
    /**
     * @var string $username User name
     */
    public $username;

    /**
     * @var string $email User email
     */
    public $email;

    /**
     * @var string $password User password
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'User name is already in use.')],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => Yii::t('app', 'This email is already in use.')],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('app', 'User name'),
            'username' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user			 = new User();
            $user->username	 = $this->username;
            $user->email	 = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            // Сохранить модель в БД
            $user->save();
            return $user;
        }

        return null;
    }
}

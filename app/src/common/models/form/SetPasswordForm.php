<?php

namespace common\models\form;

use common\models\User;
use common\validators\NewUserPasswordValidator;
use Yii;

/**
 * Set password model
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package \common\models\form
 *
 * @property integer $userId User ID
 * @property string $passwordOld Old password
 * @property string $passwordNew New password
 * @property string $passwordConfigm Confrim password
 */
class SetPasswordForm extends UserPasswordForm
{
    /**
     * @var \common\models\User
     */
    private $_user;

    /**
     * @var string $userId user ID
     */
    public $userId;

    /**
     * @var string $passwordOld Password old
     */
    public $passwordOld;

    /**
     * @var string $passwordNew New Password
     */
    public $passwordNew;

    /**
     *
     * @var string $passwordConfigm Password confirm
     */
    public $passwordConfirm;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['passwordOld', 'passwordNew', 'passwordConfirm'], 'required'],
            ['passwordNew', NewUserPasswordValidator::class],
            ['passwordConfirm', 'compare', 'compareAttribute' => 'passwordNew'],
            ['passwordOld', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'passwordOld' => Yii::t('app', 'Old password'),
            'passwordNew' => Yii::t('app', 'New password'),
            'passwordConfirm' => Yii::t('app', 'Confirm password'),
        ];
    }

    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->userId);
        }

        return $this->_user;
    }

    /**
     * Set password
     *
     * @return boolean
     */
    public function setPassword()
    {
        $user = $this->getUser();
        if (is_null($user)) {
            return false;
        }

        $user->password	 = $this->passwordNew;
        $user->isReset	 = 0;
        $user->setScenario('setPassword');
        // Сохранить модель в БД и вернуть результат сохранения
        return $user->save();
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->passwordOld)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect current password'));
            }
        }
    }
}

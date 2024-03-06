<?php

namespace common\models\form;

use common\models\User;
use Exception;
use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * @property string $email User Email
 * @property string $password User password
 * @property boolean $rememberMe Remember status
 */
class LoginPasswordForm extends Model
{
	/**
	 * @var string $email User Email
	 */
	public $email;

	/**
	 * @var string $password User password
	 */
	public $password;

	/**
	 * @var boolean $rememberMe Remember status
	 */
	public $rememberMe = true;

	/**
	 * @var \common\models\User $_user User instance
	 */
	private $_user = false;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['email', 'password'], 'required'],
			// rememberMe must be a boolean value
			['rememberMe', 'boolean'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'email' => Yii::t('app', 'Email'),
			'password' => Yii::t('app', 'Password'),
			'rememberMe' => Yii::t('app', 'Remember me'),
		];
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
	    $defaultErrorFlashMessage = Yii::t(
	        'app',
            'Authorization has failed. After a number of unsuccessful authorization attempts account can be blocked. If you cannon remember your password please try to <a href="{resetUrl}">reset it</a>.',
            ['resetUrl' => Url::to(['/request-password-reset'])]
        );

        $errorFlashMessage = null;
        $passwordWasValidated = false;

        if (!$this->hasErrors()) {
            try {
                $user = $this->getUser();
                if (empty($user)) {
                    $errorFlashMessage = $defaultErrorFlashMessage;

                    throw new Exception(Yii::t('app', 'Incorrect user name or password.'));
                }

                if ((int)$user->authType !== User::AUTH_TYPE_PASSWORD) {
                    throw new Exception(\Yii::t('app', 'You can login only with key.'));
                }

                $passwordWasValidated = true;
                if (!$user->validatePassword($this->password)) {
                    $errorFlashMessage = $defaultErrorFlashMessage;

                    throw new Exception(Yii::t('app', 'Incorrect user name or password.'));
                }

                if ($user->status == User::STATUS_ACTIVATING || $user->status == User::STATUS_INACTIVE) {
                    $errorFlashMessage = Yii::t('app', 'Your account is blocked or not activated! Contact your administrator!');

                    throw new Exception($errorFlashMessage);
                }

            } catch (Exception $ex) {
                // Поместить в сессию флаг сообщения об ошибке
                Yii::$app->session->setFlash('error', $errorFlashMessage ?: $ex->getMessage());
                $this->addError($attribute, $ex->getMessage());
            }

            if (!$passwordWasValidated) {
                // Чтобы по времени ответа сервера нельзя было понять, существует ли аккаунт, делаем вид, что проверяем пароль
                $this->dummyValidatePassword();
            }
        }
    }

    private function dummyValidatePassword()
    {
        try {
            $hash = User::find()->limit(1)->one()->password_hash;
            Yii::$app->security->validatePassword('whatever', $hash);
        } catch (Exception $exception) {

        }
    }

	/**
	 * Logs in a user using the provided username and password.
	 *
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(),
					$this->rememberMe ? 3600 * 24 * 30 : 0);
		} else {
			return false;
		}
	}

	/**
	 * Finds user by [[username]]
	 *
	 * @return User2|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$this->_user = User::findByLogin($this->email);
		}

		return $this->_user;
	}

}

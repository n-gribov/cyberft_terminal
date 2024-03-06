<?php

namespace common\models\form;

use common\models\User;
use common\validators\NewUserPasswordValidator;
use Yii;
use yii\base\InvalidParamException;

/**
 * Password reset form model
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package \common\models\form
 *
 * @property string $password User password
 */
class ResetPasswordForm extends UserPasswordForm
{
	/**
	 * @var string $password User password
	 */
	public $password;

    /**
     * @var string $passwordConfirmation User password confirmation
     */
    public $passwordConfirmation;

	/**
	 * @var \common\models\User $_user User instance
	 */
	private $_user;

	/**
	 * Creates a form model given a token.
	 *
	 * @param  string                          $token
	 * @param  array                           $config name-value pairs that will be used to initialize the object properties
	 * @throws \yii\base\InvalidParamException if token is empty or not valid
	 */
	public function __construct($token, $config = [])
	{
		if (empty($token) || !is_string($token)) {
			throw new InvalidParamException(Yii::t('app',
				'Password reset token cannot be blank.'));
		}
		$this->_user = User::findByPasswordResetToken($token);
		if (!$this->_user) {
			throw new InvalidParamException(Yii::t('app', 'Wrong password reset token.'));
		}
		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['password', 'passwordConfirmation'], 'required'],
			['password', NewUserPasswordValidator::class],
            ['passwordConfirmation', 'compare', 'compareAttribute' => 'password'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'password' => Yii::t('app', 'Password'),
			'passwordConfirmation' => Yii::t('app', 'Confirm password'),
		];
	}

	/**
	 * Resets password.
	 *
	 * @return boolean if password was reset.
	 */
	public function resetPassword()
	{
		$user			 = $this->_user;
		$user->password	 = $this->password;
        $user->isReset	 = 0;
        $user->setScenario('setPassword');
		$user->removePasswordResetToken();
		$isSaved = $user->save();
		if ($isSaved) {
            Yii::$app->monitoring->extUserLog('PasswordReset', ['subjectUserId' => $user->id]);
        }
        return $isSaved;
	}

    public function getUser(): ?User
    {
        if ($this->_user === NULL) {
            $this->_user = User::findOne($this->userId);
        }

        return $this->_user;
    }
}

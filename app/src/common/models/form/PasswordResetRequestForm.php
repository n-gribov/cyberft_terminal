<?php

namespace common\models\form;

use common\models\User;
use Exception;
use Yii;
use yii\base\Model;
use yii\swiftmailer\Mailer;

/**
 * Password reset request form model
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package \common\models\form
 *
 * @property string $email User Email
 */
class PasswordResetRequestForm extends Model
{
	/**
	 * @var string $email User email
	 */
	public $email;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
		];
	}

	/**
	 * Sends an email with a link, for resetting the password.
	 *
	 * @return boolean whether the email was send
	 */
	public function sendEmail()
	{
		/* @var $user User */
		$user = User::findOne(['email' => $this->email]);

        if (!$user) {
            Yii::$app->monitoring->extUserLog(
                'NonExistingUserPasswordResetRequest',
                ['email' => $this->email]
            );
            return false;
        }

		if ($user->status == User::STATUS_ACTIVE) {
            if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
                $user->generatePasswordResetToken();
            }
            $isSaved = $user->save();
            if (!$isSaved) {
                Yii::warning("Failed to update password reset token for user {$user->id}");
                return false;
            }
        }

        Yii::$app->monitoring->extUserLog(
            'PasswordResetRequest',
            [
                'email' => $this->email,
                'subjectUserId' => $user->id
            ]
        );

        try {
            return $this
                ->createMailer()
                ->compose('@common/mail/passwordResetToken', ['user' => $user])
                ->setFrom(getenv('MAIL_FROM'))
                ->setTo($this->email)
                ->setSubject(Yii::t('app', 'Password reset request'))
                ->send();
        } catch (Exception $exception) {
            Yii::warning("Failed to send reset password email, caused by: $exception");
            return false;
        }
	}

    public function checkMailer()
    {
        try {
            $mailer = $this->createMailer();
            $transport = $mailer->swiftMailer->getTransport();
            $transport->start();
            return $transport->isStarted();
        } catch (Exception $exception) {
            Yii::info("Mailer transport check has failed, caused by: $exception");
            return false;
        }
    }

    private function createMailer()
    {
        $settings = Yii::$app->settings->get('monitor:Notification');
        return new Mailer([
            'transport' => [
                'class'      => 'Swift_SmtpTransport',
                'host'       => $settings->host,
                'username'   => $settings->login,
                'password'   => $settings->password,
                'port'       => $settings->port,
                'encryption' => $settings->encryption,
            ],
            'htmlLayout' => false
        ]);
    }
}

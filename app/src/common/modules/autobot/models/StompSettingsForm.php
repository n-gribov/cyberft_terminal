<?php

namespace common\modules\autobot\models;

use Yii;
use yii\base\Model;

/**
 * @property string $terminalId    Terminal ID
 * @property string $login         Stomp login
 * @property string $password      Stomp password
 * @property string $status        Stomp connection status
 */
class StompSettingsForm extends Model
{
    const STATUS_WAITING_TO_SEND = 'waitingToSend';
    const STATUS_PENDING_CONFIRMATION = 'pendingConfirmation';
    const STATUS_CONFIRMED = 'confirmed';

	public $terminalId;
	public $login;
	public $password;
    public $status;

	public function rules()
    {
        return [
            [['terminalId', 'password'], 'required'],
            ['login','safe'],
            ['password', 'validatePassword']
        ];
    }

	public function attributeLabels()
	{
		return [
			'terminalId'	 => Yii::t('app/terminal', 'Terminal ID'),
			'login'			 => Yii::t('app/autobot', 'Login'),
			'password'		 => Yii::t('app/autobot', 'Password'),
		];
	}

    private function statusesLabels()
    {
        return [
            self::STATUS_WAITING_TO_SEND	        => Yii::t('app/autobot', 'Waiting to send'),
            self::STATUS_PENDING_CONFIRMATION   => Yii::t('app/autobot', 'Pending confirmation'),
            self::STATUS_CONFIRMED		        => Yii::t('app/autobot', 'Confirmed'),
        ];
    }

    public function getStatusLabel()
    {
        if (empty($this->status)) {
            return '';
        }

        $statuses = $this->statusesLabels();

        if (isset($statuses[$this->status])) {
            return $statuses[$this->status];
        } else {
            return $statuses;
        }
    }

	public function save()
	{
        $appSettings = Yii::$app->settings->get('app', $this->terminalId);

		$appSettings->stomp[$this->terminalId] = [
			'login' => $this->login,
			'password' => $this->password,
            'status' => $this->status ?? self::STATUS_PENDING_CONFIRMATION
		];

        return $appSettings->save();
	}

	public function getPasswordHash()
	{
		return (!empty($this->password)) ? md5($this->password) : '';
	}

    public function validatePassword($attribute, $params)
    {

        if (strlen($this->$attribute) < 8) {
            $this->addError($attribute, Yii::t('app/error', 'The password must contain at least {number} characters', ['number' => '8']));
            return;
        }

        if (strtolower($this->$attribute) == strtolower($this->terminalId)) {
            $this->addError($attribute, Yii::t('app/error', 'The password must be different from the current terminal ID'));
            return;
        }

        if (preg_match("/[a-z]+/", $this->$attribute) === 0) {
            $this->addError($attribute, Yii::t('app/error', 'The password must contain at least one lowercase letter'));
            return;
        }

        if (preg_match("/[A-Z]+/", $this->$attribute) === 0) {
            $this->addError($attribute, Yii::t('app/error', 'The password must contain at least one uppercase letter'));
            return;
        }

        if (preg_match("/[0-9]+/", $this->$attribute) === 0) {
            $this->addError($attribute, Yii::t('app/error', 'The password must contain at least one digit'));
            return;
        }

        if (preg_match('/[\!\@\#\$\%\№\^\&\*\-\_\+\=\;\:\,\.\\' . "'" . '\~\"\[\]\{\}\(\)\?\/\|\\\\]+/', $this->$attribute) === 0) {
            $this->addError($attribute, Yii::t('app/error', 'The password must contain at least one special character'));
        }
    }

}
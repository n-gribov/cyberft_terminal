<?php

namespace common\components\terminal;

use common\db\RedisConnection;
use Yii;
use yii\base\Model;

/**
 * @todo Класс нуждается в рефакторинге!
 */
class Autobot extends Model
{
	const KEY_VARS = "terminal:autobot";

	/** @var RedisConnection */
	protected $redis;

	/** @var array Attributes data array */
	private $_vars;

	/** Configurations */
	private $_keyFilePrefix;
	private $_certData;

	/** Attributes */
	public $fingerprint;
	public $certificate;
	public $publicKey;
	public $privateKey;
	public $updatedAt;

	public $privatePassword;

	public function init()
	{
		$this->redis = Yii::$app->redis;
		$vars = $this->redis->hmget(self::KEY_VARS);

		$this->setAttributes($vars, false);
	}


	public function getCertData()
	{
		return array_merge(
			$this->_certData, [
				'commonName' => Yii::$app->terminals->defaultTerminalId
			]
		);
	}
	
	/**
	 * @todo Заглушка! Переписать с учетом адреса участника!
	 */
	public function getCertFingerprint()
	{
		if(!empty($this->certificate)) {
			return openssl_x509_fingerprint($this->certificate);
		}
		
		return false;
	}

	public function setCertData($value)
	{
		$this->_certData = $value;
	}
	
	public function attributeLabels()
	{
		return [
			'fingerprint' => Yii::t('app', 'Certificate fingerprint'),
			'certificate' => Yii::t('app', 'Certificate file path'),
			'publicKey' => Yii::t('app', 'Public key file path'),
			'privateKey' => Yii::t('app', 'Private key file path'),
			'privatePassword' => Yii::t('app', 'Private key password'),
			'isValidPassword' => Yii::t('app', 'Is valid password'),
			'updatedAt' => Yii::t('app', 'Configuration last update time'),
		];
	}

	public function setKeyFilePrefix($value)
	{
		$this->_keyFilePrefix = $value;
	}

	public function save()
	{
		$this->updatedAt = gmdate('Y-m-d H:i:s');
		return $this->redis->hmset(self::KEY_VARS, $this->attributes);
	}

	public function getIsValid()
	{
		if ($this->privateKey && $this->publicKey && $this->certificate) {

			return true;
		}

		return false;
	}

	public function getIsValidPassword()
	{
		/**
		 * @var $certManager \common\modules\certManager\Module
		 */
		$certManager = Yii::$app->getModule('certManager');
		return $certManager->isValidPassword($this->privateKey, $this->privatePassword);
	}

	/**
	 * @param array $options
	 * @return $this
	 */
	public function generate($options = [])
	{
		/**
		 * @var $certManager \common\modules\certManager\Module
		 */
		$certManager = Yii::$app->getModule('certManager');

		$privateKeyPassword = !empty($options['privateKeyPassword'])
			? $options['privateKeyPassword']
			: null;

		$keys = $certManager->generateKeys($this->_keyFilePrefix, $privateKeyPassword, $this->certData);
		if ($keys) {
			$this->setAttributes([
				'privateKey' => $keys['private'],
				'publicKey' => $keys['public'],
				'certificate' => $keys['cert'],
				'fingerprint' => $certManager->getCertFingerprint($keys['cert'])
			], false);
		}

		return $this;
	}

	/**
	 * @param $privateKey	path to private key
	 * @param $certificate	path to certificate
	 * @param $publicKey	path to public key
	 * @return $this
	 */
	public function registerKeyAttributes($privateKey, $certificate, $publicKey)
    {
		/**
		 * @var $certManager \common\modules\certManager\Module
		 */
		$certManager = Yii::$app->getModule('certManager');
		list ($privateKey, $publicKey, $certificate) =
			array_values(
				$certManager->registerKeys(
					$this->_keyFilePrefix,
					file_get_contents($privateKey),
					file_get_contents($certificate),
					file_get_contents($publicKey)
				)
			);

		$this->setAttributes([
			'privateKey'	=> $privateKey,
			'publicKey'		=> $publicKey,
			'certificate'	=> $certificate,
			'fingerprint'	=> $certManager->getCertFingerprint($certificate)
		], false);

		return $this;
	}

	public function getCertificateContent()
	{
		if (file_exists($this->certificate)) {
			return file_get_contents($this->certificate);
		}
		
		return false;
	}

}
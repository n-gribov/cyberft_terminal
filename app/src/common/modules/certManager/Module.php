<?php

namespace common\modules\certManager;

/**
 * @todo инкапсулировать логику вынесенную в код класса модуля внутрь компонентов, моделей, контроллеров модуля
 */
use common\models\UserTerminal;
use common\modules\certManager\components\ssl\OpenSSL;
use common\modules\certManager\models\Cert;
use yii\base\Module as BaseModule;
use yii\base\Exception;
use Yii;

/**
 * @todo переименовать символьный certId в fullCertId чтобы не путать с int certId модели Cert
 * @todo подумать над переносом сюда механизмов подписи
 * @todo Избавиться от зависимостей от SSLException вне модуля и от метода get(set)Error: наружу рейзить только yii\base\Exception
 *
 * @package common\modules\certManager
 */
class Module extends BaseModule
{
	protected $_keyLength;

    /**
     * @var Component
     */
	public $ssl;
    public $terminalId = 'testCertModule';
	public $defaultRoute = 'cert/index';

	protected $error;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->ssl = Yii::createObject([
			'class' => OpenSSL::className(),
			'defaultKeyType'	=> 'RSA',
			'defaultHashAlgo'	=> 'SHA256',
			'defaultKeyName'	=> 'autobot'
		]);
	}

	/**
     * Генерация идентификатора сертификата {terminalId}-{fingerprint}
	 * @param string $fingerprint
	 * @return string
	 */
	public static function getCertId($fingerprint)
    {
        return Yii::$app->terminals->defaultTerminalId . '-' . $fingerprint;
	}

	/**
	 * @return bool|string
	 */
	public static function getUserKeyStoragePath()
    {
		return Yii::getAlias('@userKeyStorage');
	}

	/**
	 * Задаем идентификатор терминала на котором работаем
	 * @param string $value
	 */
	public function setTerminalId($value)
    {
		$this->terminalId = $value;
	}

	public function getError()
	{
		return $this->error;
	}

	/**
	 * Возвращает данные сертификата
	 * @param $certId - идентификатор вида "{terminalId}-{fingerprint}" или id
	 * @return null|Cert
	 */
	public function getCertificate($certId)
	{
		return Cert::findByCertId($certId);
	}

	public function getParticipantsList()
	{
		$certs = Cert::find()->all();
		$result = [];
		foreach ($certs as $cert) {
			$participantId = $cert->participantId;
			$result[$participantId] = $participantId;
		}

		return $result;
	}

	public function getTerminalCodesByParticipant($participantId)
	{
		$result = [];
		$query = Cert::findByParticipant($participantId);
		if (is_null($query)) {
			return $result;
		}
		$certs = $query->all();
		foreach ($certs as $cert) {
			$terminalCode = $cert->terminalCode;
			$result[] = ['id' => $terminalCode, 'text' => $terminalCode];
		}

		return $result;
	}

	/**
	 * Поиск сертификата по любому адресу (terminalId, participantId, BIC) и фингерпринту
	 *
	 * @param $address
	 * @param $fingerprint
	 * @return static
	 * @throws \yii\base\ErrorException
	 */
	public function getCertificateByAddress($address, $fingerprint)
    {
		return Cert::findByAddress($address, $fingerprint);
	}

	/**
	 * Поиск сертификата автобота
	 *
	 * @param $address
	 * @return Cert
	 * @throws \yii\base\ErrorException
	 */
	public function getSignerBotCertificate($address)
    {
		return Cert::findByRole($address, Cert::ROLE_SIGNER_BOT, true);
	}

    /**
	 * Генерирует набор ключей для автобота и самоподписанный сертификат для него,
	 * и возвращает сгенерированные данные в виде массива с элементами:
	 * ['private', 'public', 'cert']. В случае ошибки возвращает false.
	 * @param string $privateKeyPassword
	 * @param array $certData
	 * @return array|boolean
	 */
    public function generateAutobotKeys($privateKeyPassword = null, $certData = [])
	{
		try {
			// Обращаемся к драйверу, обеспечивающему генерацию
            $driver = $this->ssl->getDriver(OpenSSL::DEFAULT_DRIVER);
			// Генерируем пару ключей - приватный/открытый
			$keys = $driver->generateKeys($privateKeyPassword, $this->getKeyLength());

			// Создаем запрос на сертификат
            $csr = $driver->generateCertificateRequest($certData, $keys['private'], $privateKeyPassword);
			// Генерируем сертификат
			$cert = $driver->signCertificateRequest($csr, null, $keys['private'], $privateKeyPassword);

			// Возвращаем все сгенерированные даные в виде массива
			return [
				'private' => $keys['private'],
				'public' => $keys['public'],
				'cert' => $cert,
			];
        } catch (\Exception $ex) {

            Yii::info($ex->getMessage());
            $this->setError($ex->getMessage(), $ex->getCode());

            return false;
        }
	}

    /**
	 * Генерирует набор файлов с ключами и самоподписным сертификатом в `keyStorage`
	 * @param string $filename
	 * @param string $privateKeyPassword
	 * @param array $certData
	 * @param string $driverName
	 * @return boolean
	 */
    public function generateKeys($filename, $privateKeyPassword = null, $certData = [], $driverName = null)
	{
		try {
            $driver = $this->ssl->getDriver($driverName ?: OpenSSL::DEFAULT_DRIVER);
			$keys = $driver->generateKeys($privateKeyPassword, $this->getKeyLength());
            $csr = $driver->generateCertificateRequest($certData, $keys['private'], $privateKeyPassword);
			$cert = $driver->signCertificateRequest($csr, null, $keys['private'], $privateKeyPassword);

            return $this->registerKeys($filename, $keys['private'], $cert, $keys['public']);
        } catch (\Exception $ex) {
            $this->setError($ex->getMessage(), $ex->getCode());

            return false;
        }
	}

    public function generateUserKeys($filename, $privateKeyPassword = null, $certData = [], $driverName = null)
	{
		try {
            $driver = $this->ssl->getDriver($driverName ?: OpenSSL::DEFAULT_DRIVER);
			$keys = $driver->generateKeys($privateKeyPassword, $this->getKeyLength());
            $csr = $driver->generateCertificateRequest($certData, $keys['private'], $privateKeyPassword);
			$cert = $driver->signCertificateRequest($csr, null, $keys['private'], $privateKeyPassword);

            return $this->saveUserKeys($filename, $keys['private'], $cert, $keys['public']);
		} catch (\Exception $ex) {
			$this->setError($ex->getMessage(), $ex->getCode());

            return false;
        }
	}

	/**
	 * Регистрация ключей в хранилище
	 *
	 * @param $filename
	 * @param $privateKey
	 * @param $certificate
	 * @param $publicKey
	 * @return array|bool
	 * @throws Exception
	 * @deprecated
	 */
	public function registerKeys($filename, $privateKey, $certificate, $publicKey)
    {
		try {
			return [
				'private'	=> $this->storeData(null, $privateKey, $filename, 'private'),
				'public'	=> $this->storeData(null, $publicKey, $filename, 'public'),
				'cert'		=> $this->storeData(null, $certificate, $filename, 'cert'),
			];
		} catch (\Exception $ex) {
			$this->setError($ex->getMessage(), $ex->getCode());

            return false;
		}
	}

	/**
	 * Сохранение сгенерированных ключей пользователя
	 * @param $filename
	 * @param $privateKey
	 * @param $certificate
	 * @param $publicKey
	 * @return array|bool
	 *
	 */
	public function saveUserKeys($filename, $privateKey, $certificate, $publicKey)
    {
		try {
            $path = $this->getUserKeyStoragePath();
			return [
				'private'	=> $this->storeData($path, $privateKey, $filename, 'private'),
				'public'	=> $this->storeData($path, $publicKey, $filename, 'public'),
				'cert'		=> $this->storeData($path, $certificate, $filename, 'cert'),
			];
		} catch (\Exception $ex) {
			$this->setError($ex->getMessage(), $ex->getCode());

            return false;
		}
	}

	/**
	 * @param $privateKey
	 * @param $password
	 * @return bool
	 */
	public function isValidPassword($privateKey, $password)
	{
		$driver = $this->ssl->getDriver();
		try
		{
			$driver->openPrivateKey($privateKey, $password);
		} catch (\Exception $ex) {
			return false;
		}

		return true;
	}

	/**
	 * Возвращает fingerprint для переданного сертификата
	 *
	 * @param type $cert
	 * @return type
	 */
	public function getCertFingerprint($cert)
	{
		$driver = $this->ssl->getDriver();

		return $driver->getCertificateFingerprint($cert);
	}

    protected function keyFileTypeExtension($format)
	{
        switch($format) {
            case 'private': return 'key';
            case 'public': return 'pub';
            case 'pkcs': return 'p12';
            case 'csr': return 'csr';
            case 'cert': return 'crt';
        }

        return false;
	}

	/**
	 * @param $storagePath
	 * @param $data
	 * @param $filename
	 * @param $typeName
	 * @return string
	 * @throws Exception
	 */
	protected function storeData($storagePath, $data, $filename, $typeName)
	{
		$type = $this->keyFileTypeExtension($typeName);

        if (!$type) {
			throw new Exception('Unknown file type');
		}

        $filePath = $storagePath . '/' . $filename . '.' . $type;
		if (!file_put_contents($filePath, $data)) {
			throw new Exception("Cannot write file {$filePath}");
		}

        return $filePath;
	}

    protected function setError($message, $code = 0)
    {
		$this->error = [
			'message'	=> $message,
			'code'		=> $code
		];
	}

	protected function getKeyLength()
    {
		return $this->_keyLength;
	}

	protected function setKeyLength($value)
    {
		if (!$this->_keyLength) {
			$this->_keyLength = (int)$value;
		} else {
			throw new Exception('Key length already set and can\'t be replaced');
		}
	}

    // Проверка возможности выгрузки сертификатов в ДБО (для терминала Платины)
    public static function checkCertsExports()
    {
        // Если пользователь не авторизован или не может управлять сертификатами
        if (!Yii::$app->user || !Yii::$app->user->can('commonCertificates')) {
            return false;
        }

        // Текущий авторизованный пользователь
        $user = Yii::$app->user->identity;

        // Получаем список терминалов пользователя, которые начинаются на PLATRUMM.
        $terminals = UserTerminal::getUserTerminalIds($user->id, 'PLATRUMM');

        return !empty($terminals);
    }

}
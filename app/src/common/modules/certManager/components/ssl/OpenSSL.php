<?php
namespace common\modules\certManager\components\ssl;

use common\helpers\FileHelper;
use common\modules\certManager\components\ssl\DriverOpenSSL;
use common\modules\certManager\models\Cert;
use Exception;
use yii\base\Component;

class OpenSSL extends Component
{
	const DEFAULT_DRIVER = 'OpenSSL';

	public $defaultKeyName;
	public $defaultKeyType;
	public $defaultHashAlgo;

	public $keyPrefix = '';

    protected $driverMap = [
        'OpenSSL'   => 'DriverOpenSSL',
        'CryptoPro' => 'DriverCryptoPro',
    ];

	/**
	 * @param string $driverName
	 * @param string $keyType
	 * @param string $hashAlgo
	 * @return mixed|DriverOpenSSL
	 */
    public function getDriver($driverName = self::DEFAULT_DRIVER, $keyType = '', $hashAlgo = '')
	{
		if (!$keyType) {
			$keyType = $this->defaultKeyType;
		}

		if (!$hashAlgo) {
			$hashAlgo = $this->defaultHashAlgo;
		}

		$className = __NAMESPACE__  . '\\' . $this->driverMap[$driverName];

		return new $className([
				'keyType'	=> $keyType,
				'hashAlgo'	=> $hashAlgo,
			]);
    }

	/**
	 * Подписать данные
	 * @param type $data
	 * @param type $privateKey
	 * @param type $password
     * @param string $driverName
	 * @return string подпись в PEM формате
	 */
	public function sign($data, $privateKey, $password = null, $driverName = self::DEFAULT_DRIVER)
	{
		$driver = $this->getDriver($driverName);
		$signature = $driver->sign($data, $privateKey, $password);

		return $signature;
	}

	/**
	 * Верификация подписи
	 *
	 * @param type $data - данные
	 * @param type $signature - цифровая подпись
	 * @param type $certFullId - полный идентификатор сертификата
	 * @return boolean true если подпись верна
	 */
	public function verify($data, $signature, $certFullId, $driver = self::DEFAULT_DRIVER)
	{
        $certContent = $this->getCertContent($certFullId);

		return $this->getDriver($driver)->verify($data, $signature, $certContent);
	}

	/**
	 *
	 * @param string $data - unsigned raw data
	 * @param string $signature - binary string with container
	 * @param string $certFullId - полный идентификатор сертификата
	 * @param type $isDetached
	 * @param type $driver
	 * @return boolean
	 */
	public function verifyPkcs7($data, $signature, $certFullId, $isDetached, $driver = self::DEFAULT_DRIVER)
	{
		// здесь вместо certPath нужно получить certBody и передать вместо файла,
		// соответственно меняется сигнатура метода verifyPkcs7 в драйвере
		// $certPath = $this->getCertPath($certFullId);
		$certBody = $this->getCert($certFullId)->getCertificateContent();

        return $this->getDriver($driver)->verifyPkcs7($data, $signature, $certBody, $isDetached);
	}


	public function generateKeyFilename()
	{
		return FileHelper::uniqueName();
	}

	protected function getKeyFilename()
	{

    }

    /**
     * Получение тела сертификата
     * @param string $certFullId
     * @return string
     * @throws Exception
     */
    protected function getCertContent($certFullId)
    {
        if (!($certContent = $this->getCert($certFullId)->getCertificateContent())) {
            throw new Exception('Can\'t get certificate file', Exception::ERROR_BROKEN_CERT_PATH);
        }

        return $certContent;
    }

    /**
     * Получение тела сертификата
     * @param string $certFullId
     * @return string
     * @throws Exception
     */
    protected function getCert($certFullId)
    {
		$cert = Cert::findByCertId($certFullId);
		if (empty($cert)) {
            throw new Exception('Unknown certificate', Exception::ERROR_UNKNOWN_CERT_ID);
        }

        return $cert;
	}

}

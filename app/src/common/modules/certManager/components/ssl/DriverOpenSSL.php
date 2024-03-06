<?php
namespace common\modules\certManager\components\ssl;

use common\helpers\FileHelper;
use common\modules\certManager\components\ssl\Driver;
use common\modules\certManager\components\ssl\X509FileModel;
use Exception;
use Yii;

class DriverOpenSSL extends Driver
{
	const DEFAULT_HASH_ALGORITM = 'sha256';

	protected $keyTypesMap = [
		'RSA'	=> OPENSSL_KEYTYPE_RSA,
		'DSA'	=> OPENSSL_KEYTYPE_DSA,
		'DH'	=> OPENSSL_KEYTYPE_DH,
	];

    protected $cipherAlgoMap = [
        'RC2-40'	=> OPENSSL_CIPHER_RC2_40,
		'RC2-128'	=> OPENSSL_CIPHER_RC2_128,
		'RC2-64'	=> OPENSSL_CIPHER_RC2_64,
		'DES'		=> OPENSSL_CIPHER_DES,
		'3DES'		=> OPENSSL_CIPHER_3DES,
		'AES-128'	=> OPENSSL_CIPHER_AES_128_CBC,
		'AES-192'	=> OPENSSL_CIPHER_AES_192_CBC,
		'AES-256'	=> OPENSSL_CIPHER_AES_256_CBC,
    ];

	protected $signAlgoMap = [
		'SHA1'	=> OPENSSL_ALGO_SHA1,
		'MD5'	=> OPENSSL_ALGO_MD5,
		'MD4'	=> OPENSSL_ALGO_MD4,
		'SHA256'=> OPENSSL_ALGO_SHA256,
	];

	/**
	 * Генерация пары ключей
	 *
	 * @param type $password
	 * @param type $size длина приватной части ключа
	 * @return type
	 */
	public function generateKeys($password = null, $size = self::KEY_SIZE, $hash_algorithm = self::DEFAULT_HASH_ALGORITM)
	{
		$config = [
			'digest_alg' => $hash_algorithm,
			'private_key_bits' => $size, // Size of Key.
			'private_key_type' => OPENSSL_KEYTYPE_RSA,
		];

		$res = openssl_pkey_new($config);
        $privateKey = null;
		openssl_pkey_export($res, $privateKey, $password);

		// Generate the public key for the private key
		$privateKeyDetails = openssl_pkey_get_details($res);

		// Free the private Key.
		openssl_free_key($res);

		return $this->clearNlSymbol([
			'private'	=> $privateKey,
			'public'	=> $privateKeyDetails['key']
		]);
	}

	public function openPrivateKey($pem, $password = null)
	{
		$pKey = openssl_pkey_get_private($this->fixPemFilePath($pem), $password);

		if (!$pKey) {
			throw new Exception('Cannot open private key. OpenSSL error: "' . openssl_error_string().'"', self::ERROR_PKEY_INVALID);
		}

		return $pKey;
	}

	/**
	 * Генерация сертификата на основе приватного ключа
	 *
	 * @param array $dn certificate data
	 * @param string $privateKey PEM formatted filename or string
	 * @param string $password
	 * @return string PEM
	 */
	public function generateCertificateRequest($dn, $privateKey, $password = null)
    {
		$pKey = $this->openPrivateKey($privateKey, $password);

        // Generate a certificate signing request
		$csr = openssl_csr_new(!empty($dn) ? $dn : [], $pKey);
        $csrout = '';
		openssl_csr_export($csr, $csrout);
		openssl_free_key($pKey);

		return $csrout;
	}

	/**
	 * Подписать заявку на сертификат
	 *
	 * @param type $csr
	 * @param type $cacert
	 * @param type $privateKey PEM format
	 * @param type $password
	 * @param type $days
	 * @param type $optional
	 * @return type signed certificate PEM
	 */

	public function signCertificateRequest($csr, $cacert, $privateKey, $password = null, $days = self::CERT_DAYS, $optional = null, $hash_algorithm = self::DEFAULT_HASH_ALGORITM)
	{
		$pKey = $this->openPrivateKey($privateKey, $password);
		$cert = openssl_csr_sign($this->fixPemFilePath($csr), $cacert, $pKey, $days, [
			'digest_alg' => $hash_algorithm,
			'private_key_type' => OPENSSL_KEYTYPE_RSA,
		]);
        $certout = '';
		openssl_x509_export($cert, $certout);
		openssl_free_key($pKey);

		return $this->clearNlSymbol($certout);
	}

	/**
	 *
	 * @param type $dn
	 * @param type $days
	 * @param type $privateKey
	 * @param type $password
	 * @return type
	 */

	public function generateSelfSignedCertificate($dn, $days, $privateKey, $password)
	{
		$csr = $this->generateCertificateRequest($dn, $privateKey, $password);
		$cert = $this->signCertificateRequest($csr, null, $privateKey, $password, $days);

		return $cert;
	}

	/**
	 * @param type $filePath path to cert file
	 * @return array
	 * referenced only in common\modules\certManager\Module.php but not used
	 */
	public function getCertificateData($filePath)
    {
		return openssl_x509_parse($this->fixPemFilePath($filePath), false);
	}

	/**
	 * @param $filePath
	 * @return X509FileModel
	 * @deprecated since CYB-915
	 * referenced only in TerminalController and AppController but not used
	 */
	public function getCertificate($filePath)
    {
		return X509FileModel::loadFile($filePath);
	}

	/**
	 *
	 * @param type $cert PEM or file
	 * @return type
	 */
	public function getCertificateFingerprint($cert)
    {
        if (is_file($cert)) {
            $cert = file_get_contents($cert);
        }

		return mb_convert_case(openssl_x509_fingerprint($cert, 'sha1', false), MB_CASE_UPPER);
	}

	/**
	 *
	 * @param type $cert
	 * @return type
	 */
	public function getCertificatePublic ($cert )
	{
		return openssl_pkey_get_public($this->fixPemFilePath($cert));
	}

	/**
	 * Sign string data
	 * @param string $data
	 * @param type $privateKeyPath
	 * @param type $password
	 * @return type
	 */
	public function sign($data, $privateKeyPath, $password = null)
	{
		$privateKey = $this->openPrivateKey($privateKeyPath, $password);
        $signature = '';
		openssl_sign($data, $signature, $privateKey);

		return $signature;
	}

	/**
	 *
	 * @param type $data
	 * @param type $signature
	 * @param type $pub_key
	 * @param type $signature_alg
	 */
    public function verify($data , $signature , $pubKey)
    {
        return openssl_verify($data, $signature, $this->fixPemFilePath($pubKey));
    }

	/**
     *
     * @param string|null $data - raw data
     * @param string $signatureBase64 - base64 encoded binary string or path to file
     * @param string $certBody - certificate body
	 * @param type $isDetached
	 * @return type
	 */
    public function verifyPkcs7($data, $signatureBase64, $certBody, $isDetached)
    {
        $signature = base64_decode($signatureBase64);
        if (substr_count($signature, 'file://')) {
            $file = str_replace('file://', '', $signature);
		} else {
			$isTmpFile = true;
			$file = Yii::getAlias('@temp/signature' . FileHelper::uniqueName() . '.tmp');
            file_put_contents($file, $signature);
		}

		// Так как certPath больше не передается, но для шелл-команды требуется файл,
		// то нужно поместить данные сертификата в файл.
		//  м.б. оставить также возможность передать файл?
		$certFile = Yii::getAlias('@temp/cert' . FileHelper::uniqueName() . '.tmp');
		file_put_contents($certFile, $certBody);

        $out = [];
        exec('openssl cms -verify -noverify -in ' . $file . ' -inform DER -signer ' . $certFile . ' 2>&1', $out);
        $success = (isset($out[0]) && 'Verification successful' === $out[0]);

		@unlink($certFile);
		if ($isTmpFile) {
			@unlink($file);
		}

        return $success;
    }

	/**
	 * Fix for filepath
	 * @param type $pem
	 */
	protected function fixPemFilePath($pem)
    {
		if (!$this->isPemString($pem)) { // не PEM, а путь
			$pem = 'file://' . $pem;
		}

		return $pem;
	}

	/**
	 * Проверка строки на PEM формат
	 *
	 * @todo нужно уточнить признаки PEM формата, вероятно библиотека openSSL имеет средства
	 * @param type $string
	 * @return boolean
	 */
	protected function isPemString($string)
    {
		if (!is_string($string) || empty($string)) {
			return false;
		}

		return $string[0] == '-';
	}

    private function clearNlSymbol($data)
    {
        if (is_array($data)) {
            foreach($data as $key => $value) {
                $data[$key] = rtrim($value, "\n\r");
            }
        } else {
            $data = rtrim($data, "\n\r");
        }

        return $data;
    }

}
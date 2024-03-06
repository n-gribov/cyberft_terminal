<?php

namespace common\components\cryptography\drivers;

use Exception;
use Yii;

/**
 * OpenSSL driver class
 *
 * @property string $logCategory Category for log
 */
class DriverOpenSSL
{
    /**
     * @var string $logCategory Category for log
     */
    public $logCategory = 'error';

    /**
	 * Verify data
	 *
	 * @param string         $data          Data to verify
	 * @param string         $signature     Signature to verify
	 * @param string         $certificate   Certificate or public key. File path or string in PEM format
     * @param integer|string $signature_alg Signature algorithms
     * @see http://php.net/manual/en/openssl.signature-algos.php
     * @see http://php.net/manual/en/function.openssl-get-md-methods.php
	 * @return boolean
	 */
	public function verify($data, $signature, $certificate, $signature_alg = 'sha256')
	{
		try {
			$key			 = $this->getPublicKey($certificate);
			$verifyResult	 = openssl_verify($data, $signature, $key, $signature_alg);
			openssl_free_key($key);

			return $verifyResult === 1;

		} catch (Exception $ex) {
			$this->log($ex->getMessage());
		}

		return false;
	}

    /**
     * Get certificate fingerprint
     *
     * @param string  $certificate    Certificate. X.509 resource or string. String may contain key path or key in PEM format
     * @param string  $hash_algorithm Hash algorithms
     * @param boolean $raw_output     Output type. Raw or hex
     * @return string|boolean Return certificate fingerprint or false on error
     * @see http://php.net/manual/en/function.openssl-x509-fingerprint.php
     * @throws Exception
     */
    public function getFingerprint($certificate, $hash_algorithm = 'sha1', $raw_output = false)
    {
        try {
            $cert = $this->prepareCertificate($certificate);
            if ($cert === false){
                throw new Exception("Bad certificate data[{$certificate}]");
            }

            return mb_strtoupper(openssl_x509_fingerprint($cert, $hash_algorithm, $raw_output));
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
        }

        return false;
    }

	/**
	 * Get public key
	 *
	 * @param resource|string $certificate Certificate. X.509 resource or string. String may contain key path or key in PEM format
	 * @return resource
	 * @throws Exception
	 * @see http://php.net/manual/en/function.openssl-pkey-get-public.php
	 */
	public function getPublicKey($certificate)
	{
        try {
            $key = $this->prepareCertificate($certificate);
            if ($key === false) {
                throw new Exception("Bad certificate data[{$certificate}]");
            }

            $publicKey = openssl_pkey_get_public($key);
            if (!$publicKey) {
                throw new Exception('Cannot open public key. OpenSSL error: ['.openssl_error_string().']');
            }

            return $publicKey;
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
        }

        return false;

	}

    /**
     * Prepare certificate to use in PHP openssl functions
     *
     * @param type $certificate
     * @return resource|string|boolean Return resource if certificate is resource from openssl_x509_read,
     *                                 string with PEM or with correct path for openssl functions or
     *                                 false on error
     * @see http://php.net/manual/en/openssl.certparams.php
     * @throws Exception
     */
    protected function prepareCertificate($certificate)
    {
        try {

            if (is_resource($certificate) || $this->isPemString($certificate)) {
                return $certificate;
            }

            if (!is_file($certificate)) {
                throw new Exception("Certificate path[{$certificate}] is not a certificate or public key file");
            }

            return 'file://' . $certificate;
        } catch (Exception $ex) {
            $this->log($ex->getMessage());
        }

        return false;
    }

	/**
	 * Check string on PEM format
	 *
	 * @param string $pem String to checking
	 * @return boolean
	 */
	protected function isPemString($pem)
	{
		return strpos($pem, '-----BEGIN') === 0;
	}

    /**
     * Log message
     *
     * @param string $message Log message
     */
    protected function log($message)
    {
        Yii::info($message, $this->logCategory);
    }

}
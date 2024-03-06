<?php

/**
 * xmlseclibs.php
 *
 * Copyright (c) 2007-2010, Robert Richards <rrichards@cdatazone.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Robert Richards nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author     Robert Richards <rrichards@cdatazone.org>
 * @copyright  2007-2011 Robert Richards <rrichards@cdatazone.org>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    1.3.0
 */

namespace common\components\xmlsec\xmlseclibs;

use common\components\xmlsec\xmlseclibs\XMLSecEnc;
use common\helpers\FileHelper;
use DOMElement;
use Exception;
use Yii;

/**
 * XML security key class
 */
class XMLSecurityKey
{
    const TRIPLEDES_CBC = 'http://www.w3.org/2001/04/xmlenc#tripledes-cbc';
    const AES128_CBC = 'http://www.w3.org/2001/04/xmlenc#aes128-cbc';
    const AES192_CBC = 'http://www.w3.org/2001/04/xmlenc#aes192-cbc';
    const AES256_CBC = 'http://www.w3.org/2001/04/xmlenc#aes256-cbc';
    const RSA_1_5 = 'http://www.w3.org/2001/04/xmlenc#rsa-1_5';
    const RSA_OAEP_MGF1P = 'http://www.w3.org/2001/04/xmlenc#rsa-oaep-mgf1p';
    const DSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#dsa-sha1';
    const RSA_SHA1 = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    const RSA_SHA256 = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';

    const LIBRARY_OPENSSL = 'openssl';
    const LIBRARY_MCRYPT = 'mcrypt';

    public $type = 0;
    public $key = null;
    public $passphrase = "";
    public $iv = null;
    public $name = null;
    public $keyChain = null;
    public $isEncrypted = false;
    public $encryptedCtx = null;
    public $guid = null;
    public $parsedCertData = null;
    private $cryptParams = [];

    /**
     * @var string $_verifyDataPath Verify data path
     */
    private $_verifyDataPath;

    /**
     * @var string $_verifySignPath Verify signature path
     */
    private $_verifySignPath;

    /**
     * @var string $_verifyCertPath Verify certification path
     */
    private $_verifyCertPath;

    /**
     * @var string $_verifyKeyPath Verify key path
     */
    private $_verifyKeyPath;

    /**
     * @var string $_verifyHashAlg Verify hash algorithm
     */
    private $_verifyHashAlg;

    /**
     * This variable contains the certificate as a string if this key represents an X509-certificate.
     * If this key doesn't represent a certificate, this will be null.
     */
    private $x509Certificate = null;

    /**
     * @var array $_hashAlgo Hash algorithms
     */
    private $_hashAlg = [
        'rsa' => '-sha256',
        'gost' => '-md_gost94',
    ];

    /* This variable contains the certificate thunbprint if we have loaded an X509-certificate. */
    private $X509Thumbprint = null;

    public function __construct($type, $params = null)
    {
        srand();
        switch ($type) {
            case (XMLSecurityKey::TRIPLEDES_CBC):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_MCRYPT;
                $this->cryptParams['cipher'] = MCRYPT_TRIPLEDES;
                $this->cryptParams['mode'] = MCRYPT_MODE_CBC;
                $this->cryptParams['method'] = XMLSecurityKey::TRIPLEDES_CBC;
                $this->cryptParams['keysize'] = 24;
                break;
            case (XMLSecurityKey::AES128_CBC):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_MCRYPT;
                $this->cryptParams['cipher'] = MCRYPT_RIJNDAEL_128;
                $this->cryptParams['mode'] = MCRYPT_MODE_CBC;
                $this->cryptParams['method'] = XMLSecurityKey::AES128_CBC;
                $this->cryptParams['keysize'] = 16;
                break;
            case (XMLSecurityKey::AES192_CBC):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_MCRYPT;
                $this->cryptParams['cipher'] = MCRYPT_RIJNDAEL_128;
                $this->cryptParams['mode'] = MCRYPT_MODE_CBC;
                $this->cryptParams['method'] = XMLSecurityKey::AES192_CBC;
                $this->cryptParams['keysize'] = 24;
                break;
            case (XMLSecurityKey::AES256_CBC):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_MCRYPT;
                $this->cryptParams['cipher'] = MCRYPT_RIJNDAEL_128;
                $this->cryptParams['mode'] = MCRYPT_MODE_CBC;
                $this->cryptParams['method'] = XMLSecurityKey::AES256_CBC;
                $this->cryptParams['keysize'] = 32;
                break;
            case (XMLSecurityKey::RSA_1_5):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_OPENSSL;
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['method'] = XMLSecurityKey::RSA_1_5;
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');

            case (XMLSecurityKey::RSA_OAEP_MGF1P):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_OPENSSL;
                $this->cryptParams['padding'] = OPENSSL_PKCS1_OAEP_PADDING;
                $this->cryptParams['method'] = XMLSecurityKey::RSA_OAEP_MGF1P;
                $this->cryptParams['hash'] = null;
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');

            case (XMLSecurityKey::RSA_SHA1):
                $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_OPENSSL;
                $this->cryptParams['method'] = XMLSecurityKey::RSA_SHA1;
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['openssl_algo'] = OPENSSL_ALGO_SHA1;
                $this->cryptParams['hash'] = '-sha1';
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');

            case (XMLSecurityKey::RSA_SHA256):
                            $this->cryptParams['library'] = XMLSecurityKey::LIBRARY_OPENSSL;
                $this->cryptParams['method'] = XMLSecurityKey::RSA_SHA256;
                $this->cryptParams['padding'] = OPENSSL_PKCS1_PADDING;
                $this->cryptParams['digest'] = 'SHA256';
                $this->cryptParams['openssl_algo'] = OPENSSL_ALGO_SHA256;
                $this->cryptParams['hash'] = '-sha256';
                if (is_array($params) && !empty($params['type'])) {
                    if ($params['type'] == 'public' || $params['type'] == 'private') {
                        $this->cryptParams['type'] = $params['type'];
                        break;
                    }
                }
                throw new Exception('Certificate "type" (private/public) must be passed via parameters');

            default:
                throw new Exception('Invalid Key Type');
            }
            $this->type = $type;
    }

    /**
     * Retrieve the key size for the symmetric encryption algorithm..
     *
     * If the key size is unknown, or this isn't a symmetric encryption algorithm,
     * null is returned.
     *
     * @return int|null  The number of bytes in the key.
     */
    public function getSymmetricKeySize()
    {
        if (!isset($this->cryptParams['keysize'])) {
            return null;
        }

        return $this->cryptParams['keysize'];
    }

    public function generateSessionKey()
    {
        if (!isset($this->cryptParams['keysize'])) {
            throw new Exception('Unknown key size for type "' . $this->type . '".');
        }
        $keysize = $this->cryptParams['keysize'];

        if (function_exists('openssl_random_pseudo_bytes')) {
            /* We have PHP >= 5.3 - use openssl to generate session key. */
            $key = openssl_random_pseudo_bytes($keysize);
        } else {
            /* Generating random key using iv generation routines */
            $key = mcrypt_create_iv($keysize, MCRYPT_RAND);
        }

        if ($this->type === XMLSecurityKey::TRIPLEDES_CBC) {
            /* Make sure that the generated key has the proper parity bits set.
             * Mcrypt doesn't care about the parity bits, but others may care.
             */
            for ($i = 0; $i < strlen($key); $i++) {
                $byte = ord($key[$i]) & 0xfe;
                $parity = 1;
                for ($j = 1; $j < 8; $j++) {
                    $parity ^= ($byte >> $j) & 1;
                }
                $byte |= $parity;
                $key[$i] = chr($byte);
            }
        }

        $this->key = $key;

        return $key;
    }

    public static function getRawThumbprint($cert)
    {
        $arCert = explode("\n", $cert);
        $data = '';
        $inData = false;

        foreach ($arCert AS $curData) {
            if (!$inData) {
                if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) == 0) {
                    $inData = true;
                }
            } else {
                if (strncmp($curData, '-----END CERTIFICATE', 20) == 0) {
                    $inData = false;
                    break;
                }
                $data .= trim($curData);
            }
        }

        if (!empty($data)) {
            return strtolower(hash('sha1', base64_decode($data)));
        }

        return null;
    }

    public function loadKey($key, $isFile = false, $isCert = false)
    {
        if ($isFile) {
            $this->key = file_get_contents($key);
        } else {
            $this->key = $key;
        }
        if ($isCert) {
            $this->key = openssl_x509_read($this->key);
            $this->parsedCertData = openssl_x509_parse($this->key, false);
            openssl_x509_export($this->key, $str_cert);
            $this->x509Certificate = $str_cert;
            $this->key = $str_cert;
        } else {
            $this->x509Certificate = null;
        }
        if ($this->cryptParams['library'] == XMLSecurityKey::LIBRARY_OPENSSL) {
            if ($this->cryptParams['type'] == 'public') {
                if ($isCert) {
                    /* Load the thumbprint if this is an X509 certificate. */
                    $this->X509Thumbprint = self::getRawThumbprint($this->key);
                }
                $this->key = openssl_get_publickey($this->key);
            } else {
                $this->key = openssl_get_privatekey($this->key, $this->passphrase);
            }
        } else if ($this->cryptParams['cipher'] == MCRYPT_RIJNDAEL_128) {
            /* Check key length */
            switch ($this->type) {
                case (XMLSecurityKey::AES256_CBC):
                    if (strlen($this->key) < 25) {
                        throw new Exception('Key must contain at least 25 characters for this cipher');
                    }
                    break;
                case (XMLSecurityKey::AES192_CBC):
                    if (strlen($this->key) < 17) {
                        throw new Exception('Key must contain at least 17 characters for this cipher');
                    }
                    break;
            }
        }
    }

    /**
     * Load key for CLI OpenSSL verify action
     *
     * @param type $key Key
     * @param boolean $isFile Is file status
     */
    public function loadKeyVerify($key, $isFile = false)
    {
        if ($isFile) {
            $this->key = file_get_contents($key);
        } else {
            $this->key = $key;
        }
    }

    private function encryptMcrypt($data)
    {
        $td = mcrypt_module_open($this->cryptParams['cipher'], '', $this->cryptParams['mode'], '');
        $this->iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $this->key, $this->iv);
        if ($this->cryptParams['mode'] == MCRYPT_MODE_CBC) {
            $bs = mcrypt_enc_get_block_size($td);
            for ($datalen0 = $datalen = strlen($data); (($datalen % $bs) != ($bs - 1)); $datalen++) {
                $data .= chr(rand(1, 127));
            }
            $data .= chr($datalen - $datalen0 + 1);
        }
        $encrypted_data = $this->iv . mcrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);

        return $encrypted_data;
    }

    private function decryptMcrypt($data)
    {
        $td = mcrypt_module_open($this->cryptParams['cipher'], '', $this->cryptParams['mode'], '');
        $iv_length = mcrypt_enc_get_iv_size($td);

        $this->iv = substr($data, 0, $iv_length);
        $data = substr($data, $iv_length);

        mcrypt_generic_init($td, $this->key, $this->iv);
        $decrypted_data = mdecrypt_generic($td, $data);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        if ($this->cryptParams['mode'] == MCRYPT_MODE_CBC) {
            $dataLen = strlen($decrypted_data);
            $paddingLength = substr($decrypted_data, $dataLen - 1, 1);
            $decrypted_data = substr($decrypted_data, 0, $dataLen - ord($paddingLength));
        }

        return $decrypted_data;
    }

    private function encryptOpenSSL($data)
    {
        if ($this->cryptParams['type'] == 'public') {
            if (!openssl_public_encrypt($data, $encrypted_data, $this->key, $this->cryptParams['padding'])) {
                throw new Exception('XMLSecurityKey:encryptOpenSSL: Failure encrypting data using public key');
            }
        } else {
            if (!openssl_private_encrypt($data, $encrypted_data, $this->key, $this->cryptParams['padding'])) {
                throw new Exception('XMLSecurityKey:encryptOpenSSL: Failure encrypting data using private key');
            }
        }

        return $encrypted_data;
    }

    private function decryptOpenSSL($data)
    {
        $decrypted = null;

        if ($this->cryptParams['type'] == 'public') {
            if (!openssl_public_decrypt($data, $decrypted, $this->key, $this->cryptParams['padding'])) {
                throw new Exception('XMLSecurityKey:decryptOpenSSL: Failure decrypting data using public key');
            }
        } else {
            if (!openssl_private_decrypt($data, $decrypted, $this->key, $this->cryptParams['padding'])) {
                throw new Exception('XMLSecurityKey:decryptOpenSSL: Failure decrypting data using private key');
            }
        }

        return $decrypted;
    }

    private function signOpenSSL($data)
    {
        $algo = OPENSSL_ALGO_SHA1;

        if (isset($this->cryptParams['openssl_algo'])) {
            $algo = $this->cryptParams['openssl_algo'];
        }
        if (!openssl_sign($data, $signature, $this->key, $algo)) {
            throw new Exception('Failure Signing Data: ' . openssl_error_string() . ' - ' . $algo);

            return;
        }

        return $signature;
    }

    private function verifyOpenSSL($data, $signature)
    {
        $algo = OPENSSL_ALGO_SHA256;
        if (!empty($this->cryptParams['digest'])) {
            $algo = $this->cryptParams['digest'];
        }

        return openssl_verify($data, $signature, $this->key, $algo);
    }

    /**
     * Verify data signature using OpenSSL cli
     *
     * @param string $data Data to verify
     * @param string $signature Signature
     * @return int 1 if the signature is correct, 0 if it is incorrect, and
     * -1 on error.
     */
    private function verifyCliOpenSSL($data, $signature)
    {
        $output = null;
        $return_var = null;

        if (!$this->saveVerifyData($data, $signature)) {
            return -1;
        }

        if (!$this->getHashAlg()) {
            return -1;
        }

        $hashAlg = $this->_verifyHashAlg;

        if ($this->_verifyHashAlg !== $this->_hashAlg['gost']) {
            $hashAlg = $this->cryptParams['hash']; // @todo: Костыль, нужен рефакторинг. Зависимость от getHashAlg()
        }

        $command = "openssl dgst {$hashAlg} -verify {$this->_verifyKeyPath} -signature {$this->_verifySignPath} {$this->_verifyDataPath}";
        exec($command, $output, $return_var);
        $this->deleteVerifyFiles();

        if (($return_var === 0) && isset($output[0]) && ($output[0] === 'Verified OK')) {
            return 1;
        }

        return 0;
    }

    public function encryptData($data)
    {
        switch ($this->cryptParams['library']) {
            case XMLSecurityKey::LIBRARY_MCRYPT:
                return $this->encryptMcrypt($data);
                break;
            case XMLSecurityKey::LIBRARY_OPENSSL:
                return $this->encryptOpenSSL($data);
                break;
        }
    }

    public function decryptData($data)
    {
        switch ($this->cryptParams['library']) {
            case XMLSecurityKey::LIBRARY_MCRYPT:
                return $this->decryptMcrypt($data);
                break;
            case XMLSecurityKey::LIBRARY_OPENSSL:
                return $this->decryptOpenSSL($data);
                break;
        }
    }

    public function signData($data)
    {
        switch ($this->cryptParams['library']) {
            case XMLSecurityKey::LIBRARY_OPENSSL:
                return $this->signOpenSSL($data);
        }
    }

    public function verifySignature($data, $signature)
    {
        switch ($this->cryptParams['library']) {
            case XMLSecurityKey::LIBRARY_OPENSSL:
                return $this->verifyCliOpenSSL($data, $signature);
        }
    }

    public function getAlgorith() {
        return $this->cryptParams['method'];
    }

    static function makeAsnSegment($type, $string) {
        switch ($type) {
            case 0x02:
                if (ord($string) > 0x7f) {
                    $string = chr(0) . $string;
                }
                break;
            case 0x03:
                $string = chr(0) . $string;
                break;
        }

        $length = strlen($string);

        if ($length < 128) {
            $output = sprintf("%c%c%s", $type, $length, $string);
        } else if ($length < 0x0100) {
            $output = sprintf("%c%c%c%s", $type, 0x81, $length, $string);
        } else if ($length < 0x010000) {
            $output = sprintf("%c%c%c%c%s", $type, 0x82, $length / 0x0100, $length % 0x0100, $string);
        } else {
            $output = null;
        }

        return($output);
    }

    /* Modulus and Exponent must already be base64 decoded */

    static function convertRSA($modulus, $exponent)
    {
        /* make an ASN publicKeyInfo */
        $exponentEncoding = XMLSecurityKey::makeAsnSegment(0x02, $exponent);
        $modulusEncoding = XMLSecurityKey::makeAsnSegment(0x02, $modulus);
        $sequenceEncoding = XMLSecurityKey:: makeAsnSegment(0x30, $modulusEncoding . $exponentEncoding);
        $bitstringEncoding = XMLSecurityKey::makeAsnSegment(0x03, $sequenceEncoding);
        $rsaAlgorithmIdentifier = pack("H*", "300D06092A864886F70D0101010500");
        $publicKeyInfo = XMLSecurityKey::makeAsnSegment(0x30, $rsaAlgorithmIdentifier . $bitstringEncoding);

        /* encode the publicKeyInfo in base64 and add PEM brackets */
        $publicKeyInfoBase64 = base64_encode($publicKeyInfo);
        $encoding = "-----BEGIN PUBLIC KEY-----\n";
        $offset = 0;
        while ($segment = substr($publicKeyInfoBase64, $offset, 64)) {
            $encoding = $encoding . $segment . "\n";
            $offset += 64;
        }

        return $encoding . "-----END PUBLIC KEY-----\n";
    }

    public function serializeKey($parent) { }

    /**
     * Retrieve the X509 certificate this key represents.
     *
     * Will return the X509 certificate in PEM-format if this key represents
     * an X509 certificate.
     *
     * @return  The X509 certificate or null if this key doesn't represent an X509-certificate.
     */
    public function getX509Certificate()
    {
        return $this->x509Certificate;
    }

    /* Get the thumbprint of this X509 certificate.
     *
     * Returns:
     *  The thumbprint as a lowercase 40-character hexadecimal number, or null
     *  if this isn't a X509 certificate.
     */
    public function getX509Thumbprint()
    {
        return $this->X509Thumbprint;
    }

    /**
     * Create key from an EncryptedKey-element.
     *
     * @param DOMElement $element  The EncryptedKey-element.
     * @return XMLSecurityKey  The new key.
     */
    public static function fromEncryptedKeyElement(DOMElement $element)
    {
        $objenc = new XMLSecEnc();
        $objenc->setNode($element);

        if (!$objKey = $objenc->locateKey()) {
            throw new Exception("Unable to locate algorithm for this Encrypted Key");
        }

        $objKey->isEncrypted = true;
        $objKey->encryptedCtx = $objenc;

        XMLSecEnc::staticLocateKeyInfo($objKey, $element);

        return $objKey;
    }

    /**
     * Функция возвращает значение атрибута сертификата, если он существует
     * @param string $name Имя атрибута сертификата
     * @return string
     */
    public function getCertAttribute($name)
    {
        if ($this->parsedCertData) {
            if (is_array($this->parsedCertData) && array_key_exists($name, $this->parsedCertData)) {
                return $this->parsedCertData[$name];
            }
        }

        return '';
    }

    /**
     * Save data, signature and key to files
     *
     * @param string $data Data to save
     * @param string $signature Signature to save
     * @return boolean
     */
    private function saveVerifyData($data, $signature)
    {
        $dataPath = Yii::getAlias('@temp/') . FileHelper::uniqueName();
        $signPath = $dataPath . '.sig';
        $certPath = $dataPath . '.pem';
        $keyPath = $dataPath . '.key';

        if (file_put_contents($dataPath, $data) !== false) {
            $this->_verifyDataPath = $dataPath;
        } else {
            return false;
        }

        if (file_put_contents($signPath, $signature) !== false) {
            $this->_verifySignPath = $signPath;
        } else {
            return false;
        }

        if (file_put_contents($certPath, $this->key) !== false) {
            $this->_verifyCertPath = $certPath;
        } else {
            return false;
        }

        return $this->extractPubKey($keyPath);
    }

    /**
     * Extract public key from certificate
     *
     * @param string $keyPath Key path
     * @return boolean
     */
    private function extractPubKey($keyPath)
    {
        $output = null;
        $return_var = null;

        $command = "openssl x509 -pubkey -noout -in {$this->_verifyCertPath} > {$keyPath}";
        exec($command, $output, $return_var);

        if ($return_var === 0) {
            $this->_verifyKeyPath = $keyPath;
            return true;
        }

        return false;
    }

    /**
     * Get hash algorithm
     * @todo refactoring: Как минимум переименовать метод (судя по названию он геттер, но логика определения и установки значения...бред)+оценить качсество кода  
     * @return boolean
     */
    private function getHashAlg()
    {
        $output = null;
        $return_var = null;

        $command = "openssl x509 -in {$this->_verifyCertPath} -text -noout  | grep 'Signature Algorithm'";
        exec($command, $output, $return_var);

        if ($return_var !== 0) {
            return false;
        }

        foreach ($output as $value) {
            $value = substr(trim($value), strlen('Signature Algorithm: '));
            switch ($value) {
                case 'GOST R 34.11-94 with GOST R 34.10-2001':
                    $this->_verifyHashAlg = $this->_hashAlg['gost'];
                    break;
                default :
                    $this->_verifyHashAlg = $this->_hashAlg['rsa'];
                    break;
            }
        }

        return true;
    }

    /**
     * Delete verify files
     */
    private function deleteVerifyFiles()
    {
        if (is_file($this->_verifyDataPath)) {
            unlink($this->_verifyDataPath);
        }

        if (is_file($this->_verifySignPath)) {
            unlink($this->_verifySignPath);
        }

        if (is_file($this->_verifyCertPath)) {
            unlink($this->_verifyCertPath);
        }

        if (is_file($this->_verifyKeyPath)) {
            unlink($this->_verifyKeyPath);
        }
    }

    public static function getAllKeyClass()
    {
        return [
            self::TRIPLEDES_CBC,
            self::AES128_CBC,
            self::AES192_CBC,
            self::AES256_CBC,
            self::RSA_1_5,
            self::RSA_OAEP_MGF1P,
            self::DSA_SHA1,
            self::RSA_SHA1,
            self::RSA_SHA256
        ];
    }
}

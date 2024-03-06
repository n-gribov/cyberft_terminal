<?php

namespace common\components\xmlsec;

use common\base\DOMCyberXml;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\helpers\FileHelper;
use common\helpers\Uuid;
use common\modules\certManager\components\ssl\X509FileModel;
use DOMDocument;
use DOMElement;
use DOMXPath;
use Exception;
use Yii;
use yii\base\InvalidValueException;

/**
 * Переходной класс для поддержки внешнего инструмента подписания.
 * Является усеченной копией класса XMLSecurityDsig, в которой порефакторены методы подписания и верификации
 * За недостающими методами или дальнейшей миграцией методов обращаться в класс XMLSecurityDsig.
 */

class SigningFacility
{
	const SHA1 = 'http://www.w3.org/2000/09/xmldsig#sha1';
	const SHA256 = 'http://www.w3.org/2001/04/xmlenc#sha256';

	const SECDSIG_PREFIX = 'secdsig';
	const XMLDSIGNS = 'http://www.w3.org/2000/09/xmldsig#';

	const SIGNATURE_TEMPLATE = <<< XML
<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
    <SignedInfo>
        <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
        <SignatureMethod/>
        <Reference URI="">
            <Transforms>
                <Transform Algorithm="http://www.w3.org/2002/06/xmldsig-filter2">
                    <XPath xmlns="http://www.w3.org/2002/06/xmldsig-filter2" Filter="subtract">//*[local-name()='SignatureContainer' and namespace-uri()='http://cyberft.ru/xsd/cftdoc.01']</XPath>
                    <XPath xmlns="http://www.w3.org/2002/06/xmldsig-filter2" Filter="subtract">//*[local-name()='TraceList' and namespace-uri()='http://cyberft.ru/xsd/cftdoc.01']</XPath>
                    <XPath xmlns="http://www.w3.org/2002/06/xmldsig-filter2" Filter="subtract">//*[local-name()='DocumentHistory' and namespace-uri()='http://cyberft.ru/xsd/cftdoc.01']</XPath>
                </Transform>
            </Transforms>
            <DigestMethod/>
            <DigestValue/>
        </Reference>
    </SignedInfo>
    <SignatureValue></SignatureValue>
    <KeyInfo>
        <X509Data>
            <X509SubjectName></X509SubjectName>
            <X509Certificate></X509Certificate>
        </X509Data>
        <KeyName></KeyName>
    </KeyInfo>
</Signature>
XML;

	public $sigNode = null;
	private $_xpath = null;
	protected $canonicalMethod = null;
    public $terminalId;

	public function __construct()
	{
		$sigdoc = new DOMDocument();
		$sigdoc->loadXML(static::SIGNATURE_TEMPLATE, LIBXML_PARSEHUGE);
		$this->sigNode = $sigdoc->documentElement;
	}

	public function getXPathObj()
	{
		if (empty($this->_xpath) && !empty($this->sigNode)) {
			$xpath = new DOMXPath($this->sigNode->ownerDocument);
			$xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
			$this->_xpath = $xpath;
		}

		return $this->_xpath;
	}

	public static function calculateDigest($digestAlgorithm, $data)
	{
		switch ($digestAlgorithm) {
			case static::SHA1:
				$alg = 'sha1';
				break;
			case static::SHA256:
				$alg = 'sha256';
				break;
			case static::SHA512:
				$alg = 'sha512';
				break;
			case static::RIPEMD160:
				$alg = 'ripemd160';
				break;
			default:
				throw new Exception("Cannot validate digest: Unsupported Algorithm <$digestAlgorithm>");
		}

        if (function_exists('hash')) {
			return base64_encode(hash($alg, $data, true));
		} else if (function_exists('mhash')) {
			$alg = 'MHASH_' . strtoupper($alg);

            return base64_encode(mhash(constant($alg), $data));
		} else if ($alg === 'sha1') {
			return base64_encode(sha1($data, true));
		} else {
			throw new Exception('xmlseclibs is unable to calculate a digest. Maybe you need the mhash library?');
		}
	}

    /**
     * Функция добавляет информацию о подписании
     * @param $parentRef
     * @param null $xpath
     * @throws Exception
     */
    protected function buildSigningInfo($parentRef, $certificate, $xpath = null)
    {
        if (!$parentRef instanceof DOMElement) {
            throw new Exception('Invalid parent Node parameter');
        }
        $baseDoc = $parentRef->ownerDocument;

        if (empty($xpath)) {
            $xpath = new DOMXPath($parentRef->ownerDocument);
            $xpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);
        }

        $signatureId = Uuid::generate();

        $parentRef->setAttribute('Id', 'id_' . $signatureId);

        $object = $baseDoc->createElementNS(static::XMLDSIGNS, 'Object');
        $parentRef->appendChild($object);
// XADES ISOLATED
//        $reference = $parentRef->getElementsByTagName('Reference');
//        $referenceURI = $reference[1]->getAttribute('URI');
//        $referenceURI = str_replace('#', '', $referenceURI);
//        $qualifyingProperties = $baseDoc->createElementNS('http://uri.etsi.org/01903/v1.3.2#', 'QualifyingProperties');

        $qualifyingProperties = $baseDoc->createElement('QualifyingProperties');
        $object->appendChild($qualifyingProperties);

        $signedProperties = $baseDoc->createElement('SignedProperties');
        $qualifyingProperties->appendChild($signedProperties);
// XADES ISOLATED
//      $signedProperties->setAttribute('Id', $referenceURI);
//      $qualifyingProperties->setAttribute('Target', '#id_' . $signatureId);

        $signedSignatureProperties = $baseDoc->createElement('SignedSignatureProperties');
        $signedProperties->appendChild($signedSignatureProperties);

        // Время подписания
        $signingTime = $baseDoc->createElement('SigningTime', date('c'));
        $signedSignatureProperties->appendChild($signingTime);

        // Информация о подписанте
        $signingCertificate = $baseDoc->createElement('SigningCertificate');
        $signedSignatureProperties->appendChild($signingCertificate);

        $signaturePolicyIdentifier = $baseDoc->createElement('SignaturePolicyIdentifier');
        $signedSignatureProperties->appendChild($signaturePolicyIdentifier);

        $signaturePolicyImplied = $baseDoc->createElement('SignaturePolicyImplied');
        $signaturePolicyIdentifier->appendChild($signaturePolicyImplied);

        $cert = $baseDoc->createElement('Cert');
        $signingCertificate->appendChild($cert);

        $certDigest = $baseDoc->createElement('CertDigest');
        $cert->appendChild($certDigest);

        $digestMethod = $baseDoc->createElementNS(static::XMLDSIGNS, 'DigestMethod');
        $digestMethod->setAttribute('Algorithm', static::SHA1);
        $certDigest->appendChild($digestMethod);

        // Получение digestValue
        if (!$this->terminalId) {
            $this->terminalId = Yii::$app->terminals->defaultTerminal->terminalId;
        }

        $x509Info = X509FileModel::loadData($certificate);

        $value = static::calculateDigest(static::SHA1, $x509Info->body);

        $digestValue = $baseDoc->createElementNS(static::XMLDSIGNS, 'DigestValue', $value);
        $certDigest->appendChild($digestValue);

        $issuerSerial = $baseDoc->createElement('IssuerSerial');
        $cert->appendChild($issuerSerial);

        $issuerName = $baseDoc->createElementNS(static::XMLDSIGNS, 'X509IssuerName');
        $issuerName->appendChild($baseDoc->createTextNode($x509Info->issuerName));
        $issuerSerial->appendChild($issuerName);

        $issuerSerialNumber = $baseDoc->createElementNS(static::XMLDSIGNS, 'X509SerialNumber', $x509Info->serialNumber);
        $issuerSerial->appendChild($issuerSerialNumber);

// XADES ISOLATED
//        $propertiesXml = $baseDoc->saveXML($signedProperties);
//        $propertiesHash = hash('sha1', $propertiesXml);
//        $reference = $parentRef->getElementsByTagName('Reference');
//        $referenceValue = $reference[1]->childNodes[1];
//        $referenceValue->nodeValue = $propertiesHash;

        return $signatureId;
    }

    public static function signatureFromTemplate($template = null)
    {
        if (is_null($template)) {
            $template = static::SIGNATURE_TEMPLATE;
        }

        $sigdoc = new DOMDocument();
		$sigdoc->loadXML(static::SIGNATURE_TEMPLATE, LIBXML_PARSEHUGE);

		return $sigdoc->documentElement;
    }

	/**
	 * Функция выполняет подписание XML-документа по технологии XML Signature,
	 * используя формат файлов XML-CyberFT
	 * @param DOMDocument $domDocument Документ для подписания
	 * @param string $privateKey Приватный ключ для подписания
	 * @param string $passphrase Пароль приватного ключа
	 * @param string $fingerprint Отпечаток сертификата
	 * @param array $config Конфигурация, устанавливающая параметры подписи
	 * @return boolean Возвращает true в случае успешного завершения и false
	 * в противном случае.
	 */
	public function signDocument($domDocument, $privateKey, $passphrase, $fingerprint, $certificate, $config)
	{
        // Создаем объект для поиска узла, в котором будет храниться подпись
		$docXpath = new DOMXPath($domDocument);
		$docXpath->registerNamespace($config['defaultNamespacePrefix'], $config['defaultNamespace']);

		// Получаем заголовок xml-документа, в который будет добавлена подпись
		$headerNode = $docXpath->query($config['headerPath']);
		if ($headerNode->length) {
			// Получаем узел заголовка, куда будет впоследствии добавлена подпись
			$headerNode = $headerNode->item(0);
		} else {
			return false;
		}

		$sigNode = static::signatureFromTemplate();
    	$sigXpath = new DOMXPath($sigNode->ownerDocument);
        $sigXpath->registerNamespace(static::SECDSIG_PREFIX, static::XMLDSIGNS);

		$sMethod = $sigXpath->query('./secdsig:SignedInfo/secdsig:SignatureMethod', $sigNode)->item(0);
		$sMethod->setAttribute('Algorithm', $config['keyClass']);

		$algo = $sigXpath->query('./secdsig:SignedInfo/secdsig:Reference/secdsig:DigestMethod', $sigNode)->item(0);
		$algo->setAttribute('Algorithm', $config['algorithm']);

		$keyName = $sigXpath->query('./secdsig:KeyInfo/secdsig:KeyName', $sigNode)->item(0);
        $keyName->nodeValue = $fingerprint;

        try {
            // Добавляем время подписания
            $signatureId = $this->buildSigningInfo($sigNode, $certificate, $sigXpath);

            // Добавляем подпись в структуру документа, создавая тэг-контейнер подписи
            $containerNode = $domDocument->createElementNS($config['defaultNamespace'], $config['signatureContainerTag']);
            $headerNode->appendChild($containerNode);

            $signatureElement = $domDocument->importNode($sigNode, true);
            $signatureXml = $signatureElement->ownerDocument->saveXML($signatureElement);
            DOMCyberXml::insertBefore($containerNode, $signatureXml, null);

            $keyPath = Yii::getAlias('@temp/k' . FileHelper::uniqueName());
            file_put_contents($keyPath, $privateKey);

            $certPath = Yii::getAlias('@temp/c' . FileHelper::uniqueName());
            file_put_contents($certPath, $certificate);

            $command = 'LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib/:${LD_LIBRARY_PATH}"'
                        . ' cyberft-crypt sign --cert="' . $certPath . '"'
                        . ' --key="' . $keyPath . '"'
                        . ' --provider=cryptocom --cpagent=builtin --facility=syslog'
                        . ' --sigpath="//*[@Id=\'id_' . $signatureId . '\']"';


            $pipes = [];

            $descriptorspec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new Exception('Error running cyberft-crypt');
            }

            fwrite($pipes[0], base64_encode($passphrase) . PHP_EOL . $domDocument->saveXML());
            fclose($pipes[0]);

            $out = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            if (!empty($error)) {
                throw new Exception($error);
            }

            proc_close($process);
            unlink($keyPath);
            unlink($certPath);

            $domDocument->loadXML($out);

        } catch (InvalidValueException $ex) {
            // Обработка исключения в случае отсутствия сертификата контроллера
            Yii::error($ex->getMessage(), 'system');
            // Создание нового исключения для джоба подписания
            throw new InvalidValueException('failed to sign - ' . $ex->getMessage());
        } catch (\Exception $ex) {
            // Обработка остальных исключений
            Yii::error($ex->getMessage(), 'system');

            return false;
        }

		return true;
	}

    /**
	 * Функция верифицирует подпись с указанными параметрами
	 * @param int $signaturePos номер подписи, подлежащая проверке
	 * @param string $certBody Сертификат для проверки подписи
	 * @return boolean
	 */
	public function verifySignature(\DOMElement $signature, $certBody, $containerPos = 1, $signaturePos = 1)
	{
        $domDocument = $signature->ownerDocument;

        $certPath = Yii::getAlias('@temp/c' . FileHelper::uniqueName());
        file_put_contents($certPath, $certBody);

        $command = 'LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib/:${LD_LIBRARY_PATH}"'
                    . ' cyberft-crypt verify --cert="' . $certPath . '"'
                    . ' --provider=cryptocom --cpagent=builtin --facility=syslog'
//                    . ' --sigpath="//*[local-name()=\'Signatures\'][1]/*[local-name()=\'Signature\'][' . $signaturePos . ']"';
                    . ' --sigpath="//*[local-name()=\'Header\'][1]/*[local-name()=\'SignatureContainer\'][' . $containerPos . ']/*[local-name()=\'Signature\'][' . $signaturePos . ']"';
        try {

            $pipes = [];

            $descriptorspec = [
                0 => ['pipe', 'r'],
                2 => ['pipe', 'w'],
            ];

            $process = proc_open($command, $descriptorspec, $pipes);

            if (!is_resource($process)) {
                throw new Exception('Error running cyberft-crypt');
            }

            fwrite($pipes[0], $domDocument->saveXML());
            fclose($pipes[0]);

            $error = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            if (!empty($error)) {
                throw new Exception($error);
            }

            proc_close($process);

            unlink($certPath);
        } catch (\Exception $ex) {
            Yii::error($ex->getMessage(), 'system');

            return false;
        }

		return true;
	}

    public static function getFacility($useCompatibility = true)
    {
        return !$useCompatibility && self::checkCyberftCrypt()
                ? new SigningFacility()
                : new XMLSecurityDSig();
    }

    private static function checkCyberftCrypt()
    {
        ob_start();

        passthru('LD_LIBRARY_PATH="/usr/local/openssl-1.1.1/lib/:${LD_LIBRARY_PATH}" cyberft-crypt');
        $out = ob_get_contents();

        ob_end_clean();

        return strpos($out, 'command not found') === false;
    }

}
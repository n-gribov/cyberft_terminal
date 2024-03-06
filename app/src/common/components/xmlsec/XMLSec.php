<?php
namespace common\components\xmlsec;

use common\components\xmlsec\xmlseclibs\XMLSecEnc;
use common\components\xmlsec\xmlseclibs\XMLSeclibsHelper;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\components\xmlsec\xmlseclibs\XMLSecurityKey;
use common\modules\autobot\models\Autobot;
use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Exception;
use Yii;
use yii\base\BaseObject;

class XMLSec extends BaseObject
{
	/* Canonicalization */
	const XML_C14N = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
	/* Transform */
	const ENVELOPED = 'http://www.w3.org/2000/09/xmldsig#enveloped-signature';

	/**
	 * Константы, описывающие имена неймспейсов, которым принадлежат разные
	 * классы подписей XML-документов
	 */

	/**
	 * Префикс неймспейса документа по-умолчанию
	 */
	const DEFAULT_NS_PFX = 'doc';
	/**
	 * Имя неймспейса по-умолчанию
	 */
	const DEFAULT_NS = 'http://cyberft.ru/xsd/cftdoc.01';
	/**
	 * Префикс неймспейса SimpleCyberFT-подписи
	 */
	const CFT_NS_PFX = 'cftsign';
	/**
	 * Имя неймспейса SimpleCyberFT-подписи
	 */
	const CFT_NS = 'http://cyberft.ru/xsd/cftsign.01';
	/**
	 * Префикс неймспейса XMLDSIG-подписи
	 */
	const XMLDSIG_NS_PFX = 'secdsig';
	/**
	 * Имя неймспейса XMLDSIG-подписи
	 */
	const XMLDSIG_NS = 'http://www.w3.org/2000/09/xmldsig#';

	/**
	 * Константы-типы подписей для документа
	 */
	const SIGNATURE_TYPE_UNKNOWN = 'unknown';
	const SIGNATURE_TYPE_SIMPLE_CYBERFT = 'simplecyberft';
	const SIGNATURE_TYPE_XML_SIGNATURE = 'xmlsignature';

	/**
	 * схема для валидации подписи SimpleCyberFT
	 */
	const XSD_CFT = '@common/components/xmlsec/resources/xsd/CyberFT_SimpleSignature_v1.1.xsd';
	const XSD_XMLDSIG = '@common/components/xmlsec/resources/xsd/xmldsig-core-schema.xsd';

    private $_facilityList = [];
    private $_certManager;
    private $_certsData = [];

	public static $knownSignatures = [
		self::SIGNATURE_TYPE_SIMPLE_CYBERFT => [
			'namespace' => self::CFT_NS,
			'prefix' => self::CFT_NS_PFX,
			'xsd' => self::XSD_CFT,
		],
		self::SIGNATURE_TYPE_XML_SIGNATURE => [
			'namespace' => self::XMLDSIG_NS,
			'prefix' => self::XMLDSIG_NS_PFX,
			'xsd' => self::XSD_XMLDSIG,
		],
	];

	// Полная конфигурация подписания/верификации
	public $config = [
		'defaultNamespacePrefix' => self::DEFAULT_NS_PFX,
		'defaultNamespace' => self::DEFAULT_NS,
		'canonicalMethod' => self::XML_C14N,
		'algorithm' => XMLSecurityDSig::SHA256,
		'keyClass' => XMLSecurityKey::RSA_SHA256,
		'signatureClass' => self::ENVELOPED,
		'transformationName' => XMLSecurityDSig::XPATH_TRANSFORM_ALGO,
		'signatureContainerTag' => 'SignatureContainer',
	];

	public function __construct($config = [])
	{
		// Вычисляемые параметры конфигурации создаем здесь.
		// Компонент может нормально работать при отсутствии внешней конфигурации.
		$this->config['headerPath'] =
				'/' . XMLSeclibsHelper::nsNode(self::DEFAULT_NS_PFX, 'Document')
				. '/' . XMLSeclibsHelper::nsNode(self::DEFAULT_NS_PFX, 'Header');
		$this->config['transformation'] = [
			'query' => 'not(ancestor-or-self::'
						. XMLSeclibsHelper::nsNode(self::DEFAULT_NS_PFX, 'SignatureContainer')
						. ' or ancestor-or-self::'
						. XMLSeclibsHelper::nsNode(self::DEFAULT_NS_PFX, 'TraceList')
                        . ' or ancestor-or-self::'
						. XMLSeclibsHelper::nsNode(self::DEFAULT_NS_PFX, 'DocumentHistory')
						. ')',
			'namespaces' => [
				self::DEFAULT_NS_PFX => self::DEFAULT_NS,
			],
		];

        $this->_certManager = Yii::$app->getModule('certManager');

		// При необходимости компонент может инициализироваться внешней конфигурацией
		parent::__construct($config);
	}

	/**
	 * Инвентаризация подписей
	 */

	/**
	 * Функция возвращает все возможные подписи, которые может обнаружить
	 * в документе.
	 * @param DOMDocument $objDoc
	 * @return DOMNodeList
	 */
	public function locateAllSignatureContainers($objDoc)
	{
		if ($objDoc instanceof DOMDocument) {
			$doc = $objDoc;
		} else {
			$doc = $objDoc->ownerDocument;
		}
		if ($doc) {
			$xpath = new DOMXPath($doc);
			$xpath->registerNamespace($this->config['defaultNamespacePrefix'], $this->config['defaultNamespace']);
			$query = '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], 'Document')
						. '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], 'Header')
						. '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], $this->config['signatureContainerTag']);
			$nodeset = $xpath->query($query, $objDoc);

			return $nodeset;
		}

		return NULL;
	}

	/**
	 * Функция принимает массив узлов, содержащих подписи, найденные функцией
	 * locateAllSignatureContainers(), и проверяет их на соответствие декларированным
	 * типам подписей (XML Signature и Simple CyberFT), используя XSD-схемы.
	 * Возвращает true, если типы подписей опознаны, и false, если при анализе
	 * хотя бы одной из подписей произошла ошибка.
	 * @param DOMNodeList $containersArray Список найденных подписей
	 * @return boolean
	 */
	public function checkAllSignatureContainers($containersArray)
	{
		foreach ($containersArray as $signatureContainer) {
			// Если тип хотя бы одной подписи не определен
			if ($this->determineSignatureType($signatureContainer) == self::SIGNATURE_TYPE_UNKNOWN) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Функция ищет все подписи, содержащиеся в документе.
	 * Возвращает двумерный массив найденных подписей, разбитый по типам подписей.
	 * [
	 *		'тип1' => [[0] = > подпись, ... [n] => подпись],
	 *		...
	 * ]
	 * Если подписи не обнаружены, то возвращается пустой массив.
	 * @param DOMDocument $domDocument Документ для проверки
	 * @return array Массив найденных подписей, разбитый по типам подписей.
	 */
	public function locateAllSignatures($domDocument)
	{
		$allSignatures = [];
		foreach (self::$knownSignatures as $signatureType => $signatureConfig) {
			$allSignatures[$signatureType] =
				$this->locateSignatures($domDocument, $signatureConfig['prefix'], $signatureConfig['namespace']);
		}

		// Возвращаем массив найденный подписей, разбитых по типу подписи.
		return $allSignatures;
	}

	/**
	 * Функция возвращает подписи, которые принадлежат указанному неймспейсу.
	 * Служит для различения подписей XMLDSIG и cftsign.
	 * Необходима в случае, когда документ имеет более 1 подписи.
	 * @param DOMDocument $domDocument
	 * @param string $nsPrefix - префикс неймспейса, которому принадлежит подпись
	 * @param string $ns - имя неймспейса, которому принадлежит подпись
	 * @return DOMNodeList
	 */
	protected function locateSignatures($domDocument, $nsPrefix, $ns)
	{
		if ($domDocument instanceof DOMDocument) {
			$doc = $domDocument;
		} else {
			$doc = $domDocument->ownerDocument;
		}
		if ($doc) {
			$xpath = new DOMXPath($doc);
			$xpath->registerNamespace($this->config['defaultNamespacePrefix'], $this->config['defaultNamespace']);
			$xpath->registerNamespace($nsPrefix, $ns);
			$query = '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], 'Document')
						. '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], 'Header')
						. '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], $this->config['signatureContainerTag'])
						. '/' . XMLSeclibsHelper::nsNode($nsPrefix, 'Signature');

			$nodeset = $xpath->query($query, $domDocument);

			return $nodeset;
		}

		return NULL;
	}

	/**
	 * Функция возвращает общее число всех подписей, содержащихся в указанном
	 * массиве подписей.
	 * @param array $allSignatures Массив подписей, найденных функцией locateAllSignatures().
	 * @return int Число подписей
	 */
	public function getSignaturesNumber($allSignatures)
	{
		$totalSignaturesCnt = 0;

		foreach ($allSignatures as $signaturesList) {
			$totalSignaturesCnt += $signaturesList->length;
		}

		return $totalSignaturesCnt;
	}

	/**
	 * Функция возвращает true, если массив содержит хотя бы 1 подпись любого
	 * класса, иначе возвращает false.
	 * @param array $allSignatures Массив подписей, найденных функцией locateAllSignatures().
	 * @return boolean
	 */
	public function hasSignatures($allSignatures)
	{
		return ($this->getSignaturesNumber($allSignatures) > 0);
	}

	/**
	 * Функция возвращает число подписей класса $type, содержащихся в массиве
	 * подписей документа.
	 * @param array $allSignatures Массив подписей, найденных функцией locateAllSignatures().
	 * @param stirng $type Тип подписи
	 * @return int
	 */
	public function getSignaturesNumberByType($allSignatures, $type)
	{
		if (is_array($allSignatures) && array_key_exists($type, $allSignatures)) {
			return $allSignatures[$type]->length;
		}

		return 0;
	}

	/**
	 * Функция определяет тип подписи и возвращает код типа. Если тип не определен,
	 * то возвращается SIGNATURE_TYPE_UNKNOWN.
	 * @param DOMNode $signatureContainer
	 * @return string
	 */
	public function determineSignatureType($signatureContainer)
	{
		foreach (self::$knownSignatures as $signatureType => $signatureConfig) {
			$xpath = new DOMXPath($signatureContainer->ownerDocument);
			$xpath->registerNamespace($signatureConfig['prefix'], $signatureConfig['namespace']);

			$query = './' . XMLSeclibsHelper::nsNode($signatureConfig['prefix'], 'Signature');
			$nodeset = $xpath->query($query, $signatureContainer);

			if ($nodeset && ($signature = $nodeset->item(0))) {
				// Порождаем новый документ для проверки подписи относительно XSD-схемы
				$domDoc = new DOMDocument();
				$domDoc->appendChild($domDoc->importNode($signature, true));

				// Выполняем проверку по схеме
				if ($domDoc->schemaValidate(Yii::getAlias($signatureConfig['xsd']))) {

					return $signatureType;
				}
			}
		}

		// Нет сигнатуры или она не опознана
		return self::SIGNATURE_TYPE_UNKNOWN;
	}

	/**
	 * Функция возвращает узел подписи класса $type с номером $index, или false,
	 * если требуемая подпись не существует.
	 * @param array $allSignatures Массив подписей, найденных функцией locateAllSignatures().
	 * @param int $index
	 * @return DOMNode|false
	 */
	public function getSignatureNode($allSignatures, $type, $index)
	{
		if ($index >= 0 && $index < $this->getSignaturesNumberByType($allSignatures, $type)) {
			return $allSignatures[$type]->item($index); // Возвращаем найденную сигнатуру
		}

		return false; // Нет такой сигнатуры
	}

	/**
	 * Функция возвращает отпечаток сертификата для указанной подписи.
	 * Если произошла ошибка, то возвращается false.
	 * @param $signature Узел
	 * @return string|false
	 */
	public function getFingerprint($signature)
	{
        $query = './secdsig:KeyInfo/secdsig:KeyName';

        return $this->getSignatureData($signature, $query);
	}

    public function getSubjectName($signature)
    {
        $query = './secdsig:KeyInfo/secdsig:X509Data/secdsig:X509SubjectName';

        return $this->getSignatureData($signature, $query);
    }
    public function getCertData($signature)
    {
        $query = './secdsig:KeyInfo//secdsig:X509Data/secdsig:X509Certificate';

        return $this->getSignatureData($signature, $query);
    }

    /**
     * Функция возвращает время подписания для указанной подписи
     * @param $signature
     * @return bool|string
     */

    public function getSigningTime($signature)
    {
        // Запрос без учета неймспейсов
        $query = "./*[local-name()='Object']
                 /*[local-name()='QualifyingProperties']
                 /*[local-name()='SignedProperties']
                 /*[local-name()='SignedSignatureProperties']
                 /*[local-name()='SigningTime']";

        return $this->getSignatureData($signature, $query);
    }

    /**
     * Функция получения данных по запросу из блока подписи
     * @param $signature
     * @param $query
     * @return bool|string
     */
    protected function getSignatureData($signature, $query)
    {
        $xpath = new DOMXPath($signature->ownerDocument);
        $xpath->registerNamespace(self::XMLDSIG_NS_PFX, self::XMLDSIG_NS);
        $nodeset = $xpath->query($query, $signature);
        $signingData = $nodeset->item(0);

        if ($signingData) {
            return $signingData->nodeValue;
        }

        return false;
    }


	/**
	 * Функция определяет тип подписи CyptoPro по наличию в нем алгоритма GOST
	 * Если произошла ошибка, то возвращается false.
	 * @param $signature Узел
	 * @return string|false
	 */
	public function isCryptoPro($signature)
	{
		$xpath = new DOMXPath($signature->ownerDocument);

		// Пытаемся определить отпечаток для разных типов подписей
		$xpath->registerNamespace(self::XMLDSIG_NS_PFX, self::XMLDSIG_NS);
		$query = "./secdsig:SignedInfo/secdsig:SignatureMethod[@Algorithm='http://www.w3.org/2001/04/xmldsig-more#gostr34102001-gostr3411']";
		$nodeset = $xpath->query($query, $signature);

		return !empty($nodeset->item(0));
	}

	/**
	 * Подписание/верификация документов
	 */

	/**
	 * Функция выполняет подписание XML-документа по технологии XML Signature,
	 * используя формат файлов XML-CyberFT
     * @param string $terminalId Терминал отправителя
	 * @param DOMDocument $domDocument Документ для подписания
	 * @param string $privateKey Приватный ключ для подписания
	 * @param string $passphrase Пароль приватного ключа
	 * @param string $fingerprint Отпечаток сертификата
     * @param string $certificate
	 * @return boolean Возвращает true в случае успешного завершения и false
	 * в противном случае.
	 */
	public function signDocument($terminalId, $domDocument, $privateKey, $passphrase, $fingerprint, $certificate)
	{
        $useCompatibility = \Yii::$app->settings->get('app', $terminalId)->useCompatibleSigning;

		$facility = $this->getFacility($useCompatibility);
        $facility->terminalId = $terminalId;

        return $facility->signDocument($domDocument, $privateKey, $passphrase, $fingerprint, $certificate, $this->config);
	}

	/**
	 * Функция выполняет верификацию текущей подписи открытым ключом из сертификата
	 * $publicKey.
	 * @param string $certBody тело сертификата.
	 * @return boolean
	 * @throws Exception
	 */
	public function verifySignature($signature, $certBody, $containerPos = 1, $signaturePos = 1)
	{
        /**                                                                              *
         * Never use compatible settings on verify since we are backward-compatible here *
         *                          |                                                    *
         *                          V                                                    */
        return $this->getFacility(false)->verifySignature($signature, $certBody, $containerPos, $signaturePos);
	}

	/**
	 * Строка подписи может формироваться криптомодулем CyberFT тонкого клиента.
	 * Следующие функции предназначены для формирования подписи с данными, получаемыми
	 * из тонкого клиента.
	 */

	/**
	 * Функция экстрактирует из документа данные, над которыми выполняется подписание.
	 * Требуется для выполнения подписания данных на тонком клиенте.
	 * @param type $data
	 * @return string Строка данных для подписания
	 */
	public function extractData($data)
	{
		// Создаем объект для проверки
		$myExtractor = new XMLSecurityDSig();

		return $myExtractor->extractData($data, $this->config);
	}

	/**
	 * Функция формирует подпись для XML-документа по технологии XML Signature,
	 * используя формат файлов XML-CyberFT.
	 * Подпись не вычисляется, а получается в качестве параметра. Вычислителем
	 * подписи является внешний криптомодуль из тонкого клиента CyberFT.
	 * @param DOMDocument $data Документ для подписания
	 * @param string $fingerprint Отпечаток сертификата
	 * @param string $signature Подпись
	 * @return boolean Возвращает true в случае успешного завершения и false
	 * в противном случае.
	 */
	public function injectSignature($data, $signature, $certBody, $terminalId = null)
	{
		// Создаем объект для проверки
		$myInjector = new XMLSecurityDSig();
		$myInjector->terminalId = $terminalId;

		return $myInjector->injectSignature($data, $signature, $certBody, $this->config);
	}

    public function rejectSignatures($data)
    {
        $nodes = $data->getElementsByTagName($this->config['signatureContainerTag']);

        while ($nodes->length) {
            $current = $nodes->item(0);
            $parent = $current->parentNode;
            $parent->removeChild($current);
        }

        return true;
    }
	/**
	 * Функция ищет узел с шифрованными данными и, в случае успеха, возвращает его.
	 * Иначе возвращается null.
	 * @param DOMDocument|DOMNode $data
	 * @return DOMNode|null
	 */
	public function locateEncryptedData($data)
	{
		$myEncrypter = new XMLSecEnc();

		return $myEncrypter->locateEncryptedData($data);
	}

	/**
	 * Функция выполняет шифрование содержимого CyberXML.
	 * Объект шифрования - все содержимое тега Body.
	 * Шифрование данных выполняется TripleDES с использованием генерируемого
	 * сессионного пароля.
	 * Пароль передается вместе с зашифрованными данными, также в зашифрованном
	 * виде.
	 * Для шифрации сессионного пароля используется RSA_1_5. Пароль шифруется
	 * открытым ключом получателя.
	 * Функция модифицирует DOM XML-документа.
	 * @param DOMDocument $data XML-документ, подлежащий шифрованию
	 * @param string $cert Путь к сертификату ключа, при помощи которого шифруется
	 * сессионный пароль TripleDES.
	 */
	public function encrypt($data, $cert, $xpathQuery = null)
	{
		$certs = [];

        if (is_array($cert)) {
            $certs = $cert;
        } else {
            $certs[] = $cert;
        }

		// Создаем объект-ключ типа TripleDES
		$mySecurityKey = new XMLSecurityKey(XMLSecurityKey::TRIPLEDES_CBC);
		// Генерируем сессионный пароль, при помощи которого будем шифровать данные
		$mySecurityKey->generateSessionKey();

		// Создаем объект-шифратор
		$myEncryptor = new XMLSecEnc();

		// Создаем объект для поиска шифруемого узла
		$xpath = new DOMXPath($data);
		// Необходимые для поиска неймспейсы
		$xpath->registerNamespace($this->config['defaultNamespacePrefix'], $this->config['defaultNamespace']);

        if (is_null($xpathQuery)) {
            // Получаем тело документа, которое будет видоизменено при шифровании
            $xpathQuery = '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], 'Document')
                . '/' . XMLSeclibsHelper::nsNode($this->config['defaultNamespacePrefix'], 'Body');
        }

		$nodeset = $xpath->query($xpathQuery);
		if ($nodeset->length) {
			$node = $nodeset->item(0); // Получаем нужный узел
		} else {
			return false; // Ошибка, нечего шифровать
		}
		// Указываем найденный узел как шифруемый узел
		$myEncryptor->setNode($node);
		// Шифруем сессионный пароль

        foreach($certs as $cert) {
            // Создаем объект-ключ типа RSA_SHA1, соответствующий открытому ключу получателя.
            // Используется для шифрования сесссионного пароля.
            $siteKey = new XMLSecurityKey(XMLSecurityKey::RSA_1_5, ['type' => 'public']);
            // Подгружаем указанный сертификат из файла
            $siteKey->loadKey($cert, false, true);
            // Добавляем информацию о сессионном ключе зашифрованным сертификатом получателя
            $myEncryptor->encryptKey($siteKey, $mySecurityKey);
        }

		// Метод шифрование - содержимое указанного тега
		$myEncryptor->type = XMLSecEnc::CONTENT;
		// Выполняем комплекс работ по шифрованию и трансформации тела документа
		// для сохранения шифрованных данных и сведений о шифровании
		$myEncryptor->encryptNode($mySecurityKey, true);

		return true; // Успешное шифрование
	}

    public function encryptData($data, $useBase64 = false)
    {
        $terminalId = Yii::$app->terminals->getCurrentTerminalId();

        if (isset($this->_certsData[$terminalId])) {
            $cert = $this->_certsData[$terminalId];
        } else {
            $autobot = Autobot::find()
                ->joinWith('controller.terminal')
                ->where(['terminal.terminalId' => $terminalId, 'primary' => true])
                ->one();

            if (!$autobot) {
                throw new \Exception("Can't find autobot key for " . $terminalId);
            }

            $cert = $autobot->certificate;

            $this->_certsData[$terminalId] = $cert;
        }

        $encryptedData = $this->createEncryptedData($data, $cert);

        if ($useBase64) {
            return base64_encode($encryptedData);
        } else {
            return $encryptedData;
        }
    }

    public function createEncryptedData($data, $cert)
    {
        $securitySettings = Yii::$app->settings->get('Security');

        $mySecurityKey = new XMLSecurityKey(XMLSecurityKey::TRIPLEDES_CBC);
        $mySecurityKey->key = $securitySettings->sessionKey;

        $myEncryptor = new XMLSecEnc();

        $siteKey = new XMLSecurityKey(XMLSecurityKey::RSA_1_5, ['type' => 'public']);
        $siteKey->loadKey($cert, false, true);

        $myEncryptor->encryptKey($siteKey, $mySecurityKey);

        return $mySecurityKey->encryptData($data);
    }


	/**
	 * Функция дешифрует содержимое CyberXML.
	 * Объект дешифрации - все содержимое тега Body.
	 * Дешифрация данных выполняется TripleDES с использованием сохраненного вместе
	 * с зашифрованными данными сессионного пароля.
	 * Пароль также хранится в зашифрованном виде.
	 * Для дешифрации сессионного пароля используется RSA_1_5. Пароль дешифруется
	 * закрытым ключом получателя.
	 * @param DOMDocument $data XML-документ, подлежащий дешифрации
	 * @param string $privateKey Путь к закрытому ключу получателя, при помощи
	 * которого дешифруется хранимый сессионный пароль TripleDES.
	 * @param string $privatePassword Пароль закрытого ключа получателя
	 */
	public function decrypt($data, $privateKey, $privatePassword)
	{
		// Создаем объект для дешифрации документа
		$myDecryptor = new XMLSecEnc();
		// Ищем зашифрованные данные в документе
		$encryptedData = $this->locateEncryptedData($data);
		if (!$encryptedData) {
			// Нечего дешифровывать
			return true;
		}
		// Алгоритм дешифрации будет применен к найденному узлу
		$myDecryptor->setNode($encryptedData);
		// Определяем тип шифрования по метаданным, сохраненным при шифровании
		$myDecryptor->type = $encryptedData->getAttribute('Type');
		// Находим зашифрованный сессионный пароль
		if (!$objKey = $myDecryptor->locateKey()) {
			throw new Exception('We know the secret key, but not the algorithm');
		}

		$key = null; // Дешифрованный сессионный пароль

		// Ищем в метаданных сведения о способе шифрования и его атрибутах
		if (($objKeyInfo = $myDecryptor->locateKeyInfo($objKey))) {
			// Если сессионный пароль зашифрован
			if ($objKeyInfo->isEncrypted) {
				$objencKey = $objKeyInfo->encryptedCtx;
				// Используем пароль для закрытого ключа
				$objKeyInfo->passphrase = $privatePassword;
				// Загружаем закрытый ключ из указанного файла
				$objKeyInfo->loadKey($privateKey, false, false);
				// Выполняем дешифрацию сессионного пароля
				$key = $objencKey->decryptKey($objKeyInfo);
				// Помещаем расшифрованный пароль в объект-ключ
				$objKey->loadKey($key);
			}
		}

		// Пытаемся выполнить дешифрацию данных из тела документа
		if (($decrypt = $myDecryptor->decryptNode($objKey, true))) {
			return true; // Успешная дешифрация
		}

		return false; // Ошибка дешифрации
	}

    public function decryptData($data, $useBase64 = false)
    {
        $terminalId = Yii::$app->terminals->getCurrentTerminalId();

        $autobot = Yii::$app->getModule('autobot');

        $keyData = $autobot->getAutobotKeyData($terminalId);

        if ($useBase64) {
            $data = base64_decode($data);
        }

        $decryptedData = $this->createDecryptedData($data, $keyData['privateKey'], $keyData['privatePassword'], $keyData['fingerprint']);

        return $decryptedData;
    }

    public function createDecryptedData($data, $privateKey, $privatePassword, $fingerprint)
    {
        $securitySettings = Yii::$app->settings->get('Security');
        $key = $securitySettings->sessionKey;

        $xmlSecKey = new XMLSecurityKey(XMLSecurityKey::TRIPLEDES_CBC, ['type' => 'private']);
        $xmlSecKey->name = $fingerprint;
        $xmlSecKey->passphrase = $privatePassword;
        $xmlSecKey->loadKey($key, false, false);

        return $xmlSecKey->decryptData($data);
    }

    /**
     * Функция дешифрует содержимое CyberXML.
     * Объект дешифрации - все содержимое тега Body.
     * Дешифрация данных выполняется TripleDES с использованием сохраненного вместе
     * с зашифрованными данными сессионного пароля.
     * Пароль также хранится в зашифрованном виде.
     * Для дешифрации сессионного пароля используется RSA_1_5. Пароль дешифруется
     * закрытым ключом получателя.
     * @param DOMDocument $dom XML-документ, подлежащий дешифрации
     */
    public function decryptByMultipleKeys($dom, $certs)
    {
        // Ищем зашифрованные данные в документе
        $encryptedData = $this->locateEncryptedData($dom);
        if (!$encryptedData) {
            // Нечего дешифровывать
            return true;
        }

        // Создаем объект для дешифрации документа
        $myDecryptor = new XMLSecEnc();
        $myDecryptor->setNode($encryptedData);
        // Алгоритм дешифрации будет применен к найденному узлу
        // Определяем тип шифрования по метаданным, сохраненным при шифровании
        $myDecryptor->type = $encryptedData->getAttribute('Type');

        $key = null;

        // Находим и расшифровываем зашифрованный сессионный пароль
        if (!$key = $this->locateEncryptedKeys($dom, $encryptedData, $certs)) {
            throw new Exception('Session key not found');
        }

        /**
         * Создаем и конфигурируем объект XMLSecurityKey в соответствии параметрами,
         * заданными в структуре зашифрованных данных
         * @var XMLSecurityKey
         */
        $xmlSecKey = $myDecryptor->locateKey();
        // Помещаем расшифрованный пароль в объект
        $xmlSecKey->loadKey($key);

        // Пытаемся выполнить дешифрацию данных из тела документа
        if (($decrypt = $myDecryptor->decryptNode($xmlSecKey, true))) {
            return true; // Успешная дешифрация
        }

        return false; // Ошибка дешифрации
    }

    public function locateEncryptedKeys($doc, $encryptedData, $certs)
    {
        if ($doc) {
            $xpath = new DOMXPath($doc);
            $xpath->registerNamespace('xmlsecdsig', XMLSecurityDSig::XMLDSIGNS);
            $query = './xmlsecdsig:KeyInfo';
            $nodeset = $xpath->query($query, $encryptedData);

            $decryptKey = function ($objKeyInfo, $cert) {
                if ($objKeyInfo->isEncrypted) {
                    $objencKey = $objKeyInfo->encryptedCtx;

                    // Используем пароль для закрытого ключа
                    $objKeyInfo->passphrase = $cert['privatePassword'];

                    // Загружаем закрытый ключ
                    $objKeyInfo->loadKey($cert['privateKey'], false, false);

                    // Выполняем дешифрацию сессионного пароля
                    return $objencKey->decryptKey($objKeyInfo);
                }
            };

            for ($i = 0; $i < $nodeset->length; $i++) {
                if (($encmeth = $nodeset->item($i))) {
					$encryptedKeys = $encmeth->getElementsByTagName('EncryptedKey');
					foreach($encryptedKeys as $node){
	                    // Находим зашифрованный сессионный пароль
	                    $objKeyInfo = XMLSecurityKey::fromEncryptedKeyElement($node);

	                    $key = null;
	                    // Если отсутствует тэг "name", то файл зашифрован "старым" способом с одним ключом
	                    if (!is_null($objKeyInfo->name)) {
	                        if (isset($certs[$objKeyInfo->name])) {
	                            // Выполняем дешифрацию сессионного пароля
	                            $key = $decryptKey($objKeyInfo, $certs[$objKeyInfo->name]);

	                            if ($key) {
	                                return $key;
	                            }
	                        }
	                    } else {
	                        foreach ($certs as $cert) {
	                            // Выполняем дешифрацию сессионного пароля
	                            $key = $decryptKey($objKeyInfo, $cert);

	                            if ($key) {
	                                return $key;
	                            }
	                        }
	                    }
                	}
                }
            }
        }

        return null;
    }

    public function setConfig($config = [])
    {
    	$this->config = array_merge($this->config, $config);
    }

    private function getFacility($useCompatibility = true)
    {
        /**
         * cache facility list for each setting if called multiple times in one session
         */
        if (!isset($this->_facilityList[$useCompatibility])) {
            $this->_facilityList[$useCompatibility] = SigningFacility::getFacility($useCompatibility);
        }

        return $this->_facilityList[$useCompatibility];

    }

}

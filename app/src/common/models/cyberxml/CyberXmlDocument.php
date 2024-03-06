<?php
namespace common\models\cyberxml;

use common\base\BaseType;
use common\base\DOMCyberXml;
use common\base\XmlBoundModel;
use common\components\storage\StoredFile;
use common\components\xmlsec\XMLSec;
use common\components\xmlsec\xmlseclibs\XMLSecurityDSig;
use common\components\xmlsec\xmlseclibs\XMLSecurityKey;
use common\helpers\CryptoProHelper;
use common\helpers\SigningHelper;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\components\ssl\X509FileModel;
use common\modules\certManager\models\Cert;
use common\modules\certManager\Module;
use DOMDocument;
use DOMNode;
use DOMXPath;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;

/**
 * Description of CyberXML
 *
 * @property string $docId
 * @property string $senderId
 * @property string $receiverId
 * @property string $docType
 * @property string $docDate
 * @property string $currency
 * @property float $sum
 * @property int $count
 * @property string $encoding
 * @property string $mimeType
 * @property string $filename
 * @property array $historyEvents
 */
class CyberXmlDocument extends XmlBoundModel
{
    const XSD_SCHEMA = '@common/models/cyberxml/resources/xsd/DocEnvelope_v1.7.xsd';
	const ROOT_ELEMENT = 'Document';
	const BODY_ELEMENT = 'Body';
	const DEFAULT_NS_PREFIX = 'doc';
	const DEFAULT_NS_URI = 'http://cyberft.ru/xsd/cftdoc.01';
	const DEFAULT_CONTENT = 'common\models\cyberxml\CyberXmlContent';

    const SIGNATURE_TEMPLATE =
        '<cpsign:Signature xmlns:cpsign="http://www.w3.org/2000/09/xmldsig#" Id="id_{SIGNATURE_ID}">
            <cpsign:SignedInfo>
                <cpsign:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                <cpsign:SignatureMethod Algorithm="{SIGNATURE_ALGO}"/>
                <cpsign:Reference URI="#{REFERENCE_ID}">
                    <cpsign:DigestMethod Algorithm="{DIGEST_ALGO}"/>
                    <cpsign:DigestValue></cpsign:DigestValue>
                </cpsign:Reference>
            </cpsign:SignedInfo>
            <cpsign:SignatureValue></cpsign:SignatureValue>
            <cpsign:KeyInfo>
                <cpsign:KeyName>{FINGERPRINT}</cpsign:KeyName>
            </cpsign:KeyInfo>
            <cpsign:Object>
            <cpsign:QualifyingProperties>
                <cpsign:SignedProperties>
                    <cpsign:SignedSignatureProperties>
                        <cpsign:SigningTime>{SIGNING_TIME}</cpsign:SigningTime>
                    </cpsign:SignedSignatureProperties>
                </cpsign:SignedProperties>
            </cpsign:QualifyingProperties>
        </cpsign:Object>
        </cpsign:Signature>';

	private $_storageId;

	/**
	 * Файл из хранилища
	 * @var StoredFile $_storedFile
	 */
	private $_storedFile;

	/**
	 * @var CyberXmlContent
	 */
	private $_contentModel;

    //Модель с данными документа конкретного типа
    public $_typeModel;

	public static function read($storageId): CyberXmlDocument
	{
		return new CyberXmlDocument(['storageId' => $storageId]);
	}

    public static function loadTypeModel(BaseType $typeModel): CyberXmlDocument
    {
        $cyx = new CyberXmlDocument();
        $cyx->docType = $typeModel->getTransportType();
        $cyx->_typeModel = $typeModel;

        return $cyx;
    }

    public function setTypeModel($typeModel)
    {
        $this->docType = $typeModel->type;
        $this->_typeModel = $typeModel;
        if (method_exists($this->_contentModel, 'setTypeModel')) {
            $this->_contentModel->setTypeModel($typeModel);
        }
    }

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->initDOM();

		/**
		 * Если задан файл при инициализации объекта
		 */
		if ($this->_storageId) {
			$this->loadXML($this->getStoredFile()->getData());
		}
	}

    public function getSender()
    {
        return $this->senderId;
    }

	/**
	 * @inheritdoc
	 */
	public function attributes()
	{
		return ArrayHelper::merge(parent::attributes(), [
			'storageId'
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function boundAttributes()
	{
		return [
			'docId'		=> '//doc:Document/doc:Header/doc:DocId',
			'docDate'		=> '//doc:Document/doc:Header/doc:DocDate',
			'senderId'		=> '//doc:Document/doc:Header/doc:SenderId',
			'receiverId'	=> '//doc:Document/doc:Header/doc:ReceiverId',
			'docType'		=> '//doc:Document/doc:Header/doc:DocType',
			'currency'	=> '//doc:Document/doc:Header/doc:DocDetails/doc:PaymentRegisterInfo/@cur',
			'sum'	=> '//doc:Document/doc:Header/doc:DocDetails/doc:PaymentRegisterInfo/@sum',
			'count'	=> '//doc:Document/doc:Header/doc:DocDetails/doc:PaymentRegisterInfo/@count',
			'encoding'	=> [
				'xpath'		=> '//doc:Document/doc:Body/@encoding',
				//'default'	=> 'base64',
			],
			'mimeType'	=> [
				'xpath'		=> '//doc:Document/doc:Body/@mimeType',
				'default'	=> 'application/xml',
			],
			'filename'	=> [
				'xpath'		=> '//doc:Document/doc:Body/@filename',
			],
            'attachmentUUID' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:AttachmentUUID'
            ],
            'clientTerminalVersion' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:ClientTerminalInfo/@version',
				'default'	=> Yii::$app->version,
            ],
            'clientTerminalType' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:ClientTerminalInfo/@type',
                'default'	=> 'TerminalCyberFT',
            ],
            'componentInfoVersion' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:ClientTerminalInfo/doc:ComponentInfo/@version',
                'default'	=> Yii::$app->cryptography->getCyberftCryptVersion(),
            ],
            'componentInfoName' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:ClientTerminalInfo/doc:ComponentInfo/@name',
                'default'	=> 'CyberFTSign',
            ],
            'statementType' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:StatementType'
            ],
            'historyEvents' => [
                'xpath' => '//doc:Document/doc:Header/doc:DocDetails/doc:DocumentHistory',
                'default' => [],
            ],
		];
	}

	/**
	 * @return CyberXmlContent
	 */
	public function getContent()
	{
        if (is_null($this->_contentModel)) {

			$contentClassName = Yii::$app->registry->getTypeContentClass($this->docType);

            if (empty($contentClassName)) {
				$contentClassName = self::DEFAULT_CONTENT;
			}

			$this->_contentModel = new $contentClassName([
				'parent' => $this
			]);

			/**
			 * @var $body \DOMNode
			 */
			$body = $this->getBodyNode(false);
			$contentNode = self::getContentNode($body);

			if (!is_null($contentNode)) {
				$this->_contentModel->loadFromNode($contentNode);
			}
		}

		return $this->_contentModel;
	}


	public function pushBody()
	{
        $body = $this->getBodyNode(true);

        while ($body->hasChildNodes()) {
            $body->removeChild($body->childNodes->item(0));
        }

        //$bodyFrag = $this->_dom->createDocumentFragment();
        //$bodyFrag->appendXML((string) $this->_contentModel);
        //$body->appendChild($bodyFrag);

        /**
         * Не допустить появления префиксов default
         */
        DOMCyberXml::insertBefore($body, (string) $this->_contentModel);
	}

	protected function getBodyNode($force = false)
	{
		$body = null;

		$nodes = $this->_dom->getElementsByTagNameNS(self::DEFAULT_NS_URI, self::BODY_ELEMENT);

		if ($nodes->length) {
			$body = $nodes->item(0);
		} else if ($force) {
			$body = $this->_dom->createElementNS(self::DEFAULT_NS_URI, self::BODY_ELEMENT);
			$this->_dom->documentElement->appendChild($body);
		}

		return $body;
	}

	/**
	 * Функция возвращает первый встреченный дочерний узел указанного узла, не
	 * являющийся текстовым узлом.
	 * @param DOMNode $body
	 * @return DOMNode
	 */
	protected static function getContentNode($body)
	{
        if ($body instanceof DOMNode) {
            foreach($body->childNodes as $node) {
                if ($node->nodeName != '#text') {
                    return $node;
                }
            }
        }

		return null;
	}

	public function beforeUpdateDOM()
	{
		parent::beforeUpdateDOM();

        $contentModel = $this->content;

		if ($contentModel->isDirty() || !is_null($this->_typeModel)) {
            $contentData = $contentModel->getDocumentData();
            $contentModel->markDirty();

            foreach ($contentData as $attribute => $value) {
                $this->$attribute = $value;
            }
		}
	}

	public function afterUpdateDOM()
	{
		parent::afterUpdateDOM();
 		if ($this->content->isDirty()) {
			$this->pushBody();
		}
	}

	public function getStorageId()
	{
		return $this->_storageId;
	}

	public function setStorageId($id)
	{
		if ($this->_storageId != $id) {
            $this->_storageId = $id;
            $this->_storedFile = null;
        }
	}

	public function getStoredFile()
	{
		if (is_null($this->_storedFile)) {
			if (!empty($this->_storageId)) {
				$this->setStoredFile(Yii::$app->storage->get($this->_storageId));
			}
		}

		return $this->_storedFile;
	}

	public function setStoredFile(StoredFile $file)
	{
		$this->_storedFile = $file;
		$this->_storageId = $file->id;
	}

	/**
	 * Функция генерирует подпись типа XML Signature и добавляет ее в DOM
	 * XML-документа.
     * @param string $terminalId Терминал отправителя
	 * @param string $privateKey Приватный ключ для подписания
	 * @param string $privatePassword Пароль приватного ключа
	 * @param string $fingerprint Отпечаток сертификата ключа
     * @param string $certificate
	 * @return boolean
	 */
	public function sign($terminalId, $privateKey, $privatePassword, $fingerprint, $certificate)
	{
		$mySigner = Yii::$app->xmlsec;
		$settings = Yii::$app->settings->get('Security');
		$mySigner->setConfig([
			'algorithm' => XMLSecurityDSig::SHA256,
			'keyClass'  => XMLSecurityKey::RSA_SHA256
		]);

		return $mySigner->signDocument($terminalId, $this->_dom, $privateKey, $privatePassword, $fingerprint, $certificate);
	}

	/**
	 * Функция верифицирует все имеющиеся подписи (сопряжение с CertManager)
	 * @return boolean
	 */
	public function verify($certBody = null)
	{
		// Определяем режим проверки подписи (обязателен/нет)
		// по дефолту всегда true
		$strictVerification = true;

		/**
		 * @var $mySignVerifier XMLSec
		 */
		$mySignVerifier = Yii::$app->xmlsec;

		// Получаем и проверяем по схеме все контейнеры подписей, содержащихся в документе
		$allSignatureContainers = $mySignVerifier->locateAllSignatureContainers($this->_dom);
		if ($mySignVerifier->checkAllSignatureContainers($allSignatureContainers) === false) {
			$this->addError('signatures', "Type of one or more signatures can't be determined");

			return false;
		}

        $containers = [];

        $containerPos = 1;
        foreach($allSignatureContainers as $container) {
            $sigPos = 1;
            $signatures = $container->getElementsByTagName('Signature');
            foreach($signatures as $signature) {
                $containers[$containerPos][$sigPos++] = $signature;
                $sigPos++;
            }

            $containerPos++;
        }

        // Если нет ни одного контейнера,
        // то результат зависит от установленного уровня строгости проверки подписи
		if (!count($containers)) {
			return !$strictVerification;
		}

        // Верифицируем подписи всех классов
        if (!empty($certBody)) {
            return $this->verifyXmlSigWithCert($containers, $certBody);
        }

        return $this->verifyXmlSig($containers);
	}

    protected function verifyXmlSigWithCert($containers, $certBody)
    {
		/**
		 * @var $mySignVerifier XMLSec
		 */
		$mySignVerifier = Yii::$app->xmlsec;

        $cert = X509FileModel::loadData($certBody);

//        $certModel = new \common\modules\certManager\models\Cert();
//        $certModel->setCertificate($cert);

//        if (!$certModel->validateCertificate() || !$certModel->isValid()) {
//            $this->addError('signatures', "Certificate is not valid, expired or blocked: {$this->senderId}-{$cert->fingerprint}");
//
//            return false;
//        }

        $fingerprintFound = false;

        foreach($containers as $containerPos => $signatureList) {
            // Верифицируем все подписи класса XMLDSIG
            foreach($signatureList as $signaturePos => $signature) {

                if ($mySignVerifier->isCryptoPro($signature)) {
                    continue;
                }

                // Получаем информацию об отпечатке сертификата
                if (($fingerprint = $mySignVerifier->getFingerprint($signature)) === false) {
                    $this->addError('signatures', "Unable to find fingerprint inside XMLDSIG signature #{$signaturePos}");

                    return false;
                }

                if ($fingerprint != $cert->fingerprint) {
                    continue;
                }

                $fingerprintFound = true;

                // Пытаемся выполнить верификацию подписи
                if (!$mySignVerifier->verifySignature($signature, $certBody, $containerPos, $signaturePos)) {
                    // Не продолжаем проверку, если произошла ошибка верификации
                    $this->addError('signatures', 'Verification failed with cert ' . $cert->fingerprint);

                    return false;
                }
            }
        }

        if (!$fingerprintFound) {
            $this->addError('signatures', 'fingerprint ' . $cert->fingerprint . ' not found');

            return false;
        }

		return true;
    }

	/**
	 * Функция проверяет все подписи XMLDSIG
	 * @param array $containers
	 * @return boolean
	 */
	protected function verifyXmlSig($containers)
	{
		/** @var XMLSec $mySignVerifier */
		$mySignVerifier = Yii::$app->xmlsec;
		$myCertManager = Yii::$app->getModule('certManager');

        foreach($containers as $containerPos => $signatureList) {
            // Верифицируем все подписи класса XMLDSIG
            foreach($signatureList as $signaturePos => $signature) {

                if ($mySignVerifier->isCryptoPro($signature)) {
                    continue;
                }

                // Получаем информацию об отпечатке сертификата
                if (($fingerprint = $mySignVerifier->getFingerprint($signature)) === false) {
                    $this->addError('signatures', "Unable to find fingerprint inside XMLDSIG signature #{$signaturePos}");

                    return false;
                }

                // Обращаемся к менеджеру сертификатов для получения сертификата,
                // соответствующего отправителю и отпечатку
                try {
                    if (($myCert = $myCertManager->getCertificateByAddress($this->senderId, $fingerprint)) === null) {
                        $this->addError('signatures', "No certificate found: {$this->senderId}-{$fingerprint}");

                        return false;
                    }

                    if (!$myCert->isValid()) {
                        $this->addError('signatures', "Certificate is expired or blocked: {$this->senderId}-{$fingerprint}");

                        return false;
                    }

                    // Пытаемся выполнить верификацию подписи
                    if (!$mySignVerifier->verifySignature($signature, $myCert->body, $containerPos, $signaturePos)) {
                        // Не продолжаем проверку, если произошла ошибка верификации
                        $this->addError('signatures', 'Verification failed');

                        return false;
                    }

                } catch (ErrorException $ex) {
                    $this->addError('signatures', $ex->getMessage());

                    return false;
                }
            }
        }

		return true;
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
	 * @param string $cert Путь к сертификату ключа, при помощи которого шифруется
	 * сессионный пароль TripleDES.
	 */
	public function encrypt($cert)
	{
        if (!$this->isReport()) {
            $myEncryptor = Yii::$app->xmlsec;
            return $myEncryptor->encrypt($this->_dom, $cert, null);
        }

        return true;
	}

	/**
	 * Функция дешифрует содержимое CyberXML.
	 * Объект дешифрации - все содержимое тега Body.
	 * Дешифрация данных выполняется TripleDES с использованием сохраненного вместе
	 * с зашифрованными данными сессионного пароля.
	 * Пароль также хранится в зашифрованном виде.
	 * Для дешифрации сессионного пароля используется RSA_1_5. Пароль дешифруется
	 * закрытым ключом получателя.
	 * @param string $privateKey Путь к закрытому ключу получателя, при помощи
	 * которого дешифруется хранимый сессионный пароль TripleDES.
	 * @param string $privatePassword Пароль закрытого ключа получателя
	 */
	public function decrypt($certs = null)
	{
        if (empty($certs)) {
            $certs = SigningHelper::getDecryptKeys($this);
        }

    	return Yii::$app->xmlsec->decryptByMultipleKeys($this->_dom, $certs);
	}

	/**
	 * Функция определяет, является ли данный CyberXML зашифрованным.
	 * Возвращает true, если зашифрован. Иначе - false.
	 * @return boolean
	 */
	public function isEncrypted()
	{
		$myEncryptor = Yii::$app->xmlsec;

		// Ищем зашифрованные части XML-документа
		$encryptedData = $myEncryptor->locateEncryptedData($this->_dom);

		return !is_null($encryptedData);
	}

	/**
	 * Функция возвращает true, если данный документ должен быть отмечен исходящим ACk'ом,
	 * иначе возвращает false.
	 */
	public function isAckable()
	{
	   // Мы должны отвечать Ack'ом на все документы, не являющиеся Ack'ами и ChkAck'ами.
		return !in_array($this->docType, ['CFTAck', 'CFTChkAck']);
	}

	/**
	 * CFTStatusReport или CFTAck
	 * @return boolean
	 */
	public function isReport()
	{
		return in_array($this->docType, ['CFTStatusReport', 'CFTAck', 'CFTChkAck', 'StatusReport']);
	}

	/**
	 * Функция выполняет валидацию документа по XSD-схеме.
	 * Возвращает true в случае успешной валидации и false в случае ошибки
	 * @return boolean Результат валидации
	 */
	public function validateXSD()
	{
		return $this->validateByScheme(static::XSD_SCHEMA);
	}

	public function injectSignature($signature, $certBody)
	{
		$mySigner = Yii::$app->xmlsec;
		$mySigner->setConfig([
			'algorithm' => XMLSecurityDSig::SHA256,
			'keyClass'  => XMLSecurityKey::RSA_SHA256
		]);

		return $mySigner->injectSignature($this->_dom, $signature, $certBody, $this->senderId);
	}

    public function rejectSignatures()
    {
        $mySigner = Yii::$app->xmlsec;
        return $mySigner->rejectSignatures($this->_dom);
    }

	public function extractSignData()
	{
		$mySigner = Yii::$app->xmlsec;
		$mySigner->setConfig([
			'algorithm' => XMLSecurityDSig::SHA256,
			'keyClass'  => XMLSecurityKey::RSA_SHA256
		]);

		return $mySigner->extractData($this->_dom);
	}

	/**
	 * Get list of signatures with information about each signature
	 *
	 * @return boolean|array Return array with data: ['fingerprint', 'name', 'email', 'phone', 'role']
	 */
	public function getSignatureList($roleFilter = null)
	{
		$result = [];

		/**
		 * @var $mySignVerifier XMLSec
		 */
		$xmlsec = \Yii::$app->xmlsec;

		// Get all signature containers
		$allSignatureContainers = $xmlsec->locateAllSignatureContainers($this->_dom);
		if ($xmlsec->checkAllSignatureContainers($allSignatureContainers) === false) {
			$this->addError('signatures', "Type of one or more signatures can't be determined");

			return false;
		}

		// Get all signatures from document
		$allSignatures = $xmlsec->locateAllSignatures($this->_dom);
		if ($xmlsec->hasSignatures($allSignatures) === false) {
			return $result;
		}

		// Get each signature
		foreach ($allSignatures as $signatureClass => $signaturesList) {
			switch ($signatureClass){
				case XMLSec::SIGNATURE_TYPE_XML_SIGNATURE:
					$certInfo = $this->getXMLSigInfo($signaturesList, $roleFilter);

					if ($certInfo !== false){
						$result = array_merge($result, $certInfo);
					}
					break;
				case XMLSec::SIGNATURE_TYPE_SIMPLE_CYBERFT:
					$certInfo = $this->getSimpleCyberFtInfo($signaturesList);
					if ($certInfo !== false){
						$result = array_merge($result, $certInfo);
					}
					break;
			}
		}

		return $result;
	}

	/**
	 * Get certificate info from XMLSig signature
	 *
	 * @param array $signaturesList List of XMLSec signatures
	 * @return array|boolean
	 */
	private function getXMLSigInfo($signaturesList, $roleFilter = null)
	{
		$signList = [];

		/** @var XMLSec $xmlsec */
		$xmlsec = Yii::$app->xmlsec;

		for ($signatureCnt = 0; $signatureCnt < $signaturesList->length; $signatureCnt++) {
			$signature = $signaturesList->item($signatureCnt);

			// Get fingerprint
			$fingerprint = $xmlsec->getFingerprint($signature);
			if ($fingerprint === false) {
				$this->addError('signatures', "Unable to find fingerprint inside XMLDSIG signature #{$signatureCnt}");

                return false;
			}

            $role = Cert::ROLE_SIGNER;
            $count = Autobot::find()->where(['fingerprint' => $fingerprint])->count();
            if ($count) {
                $role = Cert::ROLE_SIGNER_BOT;
            }
            if ($roleFilter && $roleFilter != $role) {
                continue;
            }
            $name = $xmlsec->getSubjectName($signature);
            $pos = strpos($name, 'CN=');
            if ($pos !== false) {
                $pos2 = strpos($name, ';', $pos + 3);
                if ($pos2 === false) {
                    $pos2 = strlen($name);
                }
                $name = substr($name, $pos  + 3, $pos2);
            }
            if (!$name) {
                $certBody = $xmlsec->getCertData($signature);
                if ($certBody) {
                    $x509 = X509FileModel::loadData($certBody);
                    $name = $x509->getSubjectCN();
                }
            }
			if (!$name) {
                $certInfo2 = $this->getCertificateInfo($fingerprint);
                if ($certInfo2) {
                    $name = $certInfo2['name'];
                    $role = $certInfo2['role'];
                }
            }
            $certInfo = [
                'fingerprint' => $fingerprint,
                'name' => $name,
                'role' => $role
            ];

            // Get signing time
            $signingTime = $xmlsec->getSigningTime($signature);
            $certInfo['signingTime'] = $signingTime
                    ? date('Y-m-d H:i:s', strtotime($signingTime))
                    : '';

			$signList[] = $certInfo;
		}

		return $signList;
	}

	/**
	 * Get certificate info for Simple CyberFT signature
	 *
	 * @param array $signaturesList List of simple CyberFT signatures
	 * @return array|boolean
	 */
	private function getSimpleCyberFtInfo($signaturesList)
	{
		$signList = [];

		for ($signatureCnt = 0; $signatureCnt < $signaturesList->length; $signatureCnt++){
			$signature = $signaturesList->item($signatureCnt);

			// Get fingerprint
			$xpath = new DOMXPath($signature->ownerDocument);
			$xpath->registerNamespace('cftsign', 'http://cyberft.ru/xsd/cftsign.01');
			$query1 = './cftsign:KeyInfo/cftsign:KeyName';
			$nodeset1 = $xpath->query($query1, $signature);
			$keyName = $nodeset1->item(0);
			if ($keyName) {
				$fingerprint = $keyName->nodeValue;
			} else {
				$this->addError('signatures', "Unable to find fingerprint inside SimpleCyberFT signature #{$signatureCnt}");

                return false;
			}

            /** @var XMLSec $xmlsec */
            $xmlsec = Yii::$app->xmlsec;
            $name = $xmlsec->getSubjectName($signature);
            $pos = strpos($name, 'CN=');
            if ($pos !== false) {
                $pos2 = strpos($name, ';', $pos + 3);
                if ($pos2 === false) {
                    $pos2 = strlen($name);
                }
                $name = substr($name, $pos  + 3, $pos2);
            }
            if (!$name) {
                $certBody = $xmlsec->getCertData($signature);
                if ($certBody) {
                    $x509 = X509FileModel::loadData($certBody);
                    $name = $x509->getSubjectCN();
                }
            }
			if (!$name) {
                $certInfo2 = $this->getCertificateInfo($fingerprint);
                $name = $certInfo2['name'];
            }
            $certInfo = [
                'fingerprint' => $fingerprint,
                'name' => $name,
//                'email' => Yii::t('edm', 'Unknown signature'),
//                'phone' => Yii::t('edm', 'Unknown signature'),
//                'post' => Yii::t('edm', 'Unknown signature'),
//                'role' => Yii::t('edm', 'Unknown signature'),
            ];

            // Get signing time
            $signingTime = $xmlsec->getSigningTime($signature);
            $certInfo['signingTime'] = $signingTime
                    ? date('Y-m-d H:i:s', strtotime($signingTime))
                    : '';

			$signList[] = $certInfo;
		}

		return $signList;
	}

	/**
	 * Get certificate info
	 *
	 * @param string $fingerprint Certificate fingerprint
	 * @return array|boolean
	 */
	private function getCertificateInfo($fingerprint)
	{
		/** @var Module $myCertManager */

		$myCertManager = Yii::$app->getModule('certManager');

		try {
			$myCert = $myCertManager->getCertificateByAddress($this->senderId, $fingerprint);
			if (is_null($myCert)) {
                return [
                    'fingerprint' => $fingerprint,
                    'name' => Yii::t('edm', 'Unknown signature'),
                    'email' => Yii::t('edm', 'Unknown signature'),
                    'phone' => Yii::t('edm', 'Unknown signature'),
                    'post' => Yii::t('edm', 'Unknown signature'),
                    'role' => Yii::t('edm', 'Unknown signature'),
                ];
			}

			return [
				'fingerprint' => $myCert->fingerprint,
				'name' => $myCert->fullName,
				'email' => $myCert->email,
				'phone' => $myCert->phone,
				'post' => $myCert->post,
				'role' => $myCert->role,
			];
		} catch (ErrorException $ex) {
			$this->addError('signatures', $ex->getMessage());

            return false;
		}
	}

    public static function getTypeModel($storageId, $params = [])
    {
        if ($storageId) {
            $cyx = CyberXmlDocument::read($storageId);

            return $cyx->getContent()->getTypeModel($params);
        }

        return false;
    }

    public function getPrefixSignature()
    {
        return 'id_';
    }

    public function getSignatureTemplate($signatureId, $fingerprint, $algo, $certBody = null)
    {
        $bodyNode = $this->getBodyNode();
        $signedDataNode = $this->getContentNode($bodyNode);
        $contentNode = $signedDataNode->getElementsByTagName('Content')->item(0);
        $rawDataNode = $contentNode->getElementsByTagName('RawData')->item(0);
        $signaturesNode = $signedDataNode->getElementsByTagName('Signatures')->item(0);

        $referenceId = $rawDataNode->getAttribute('Id');
        if (empty($referenceId)) {
            throw new Exception('Empty Reference ID');
        }

        $signatureTemplate = str_replace('{REFERENCE_ID}', $referenceId, static::SIGNATURE_TEMPLATE);
        $signatureTemplate = CryptoProHelper::updateSignatureTemplate(
            $signatureTemplate, $signatureId, $fingerprint, $algo, $certBody
        );

        $signatureDom = new DOMDocument();
        $signatureDom->loadXML($signatureTemplate);
        $signaturesNode->appendChild($signaturesNode->ownerDocument->importNode($signatureDom->firstChild, true));

        return $this->_dom->saveXML();
    }

    public function loadFromString($string)
    {
        return $this->loadXml($string);
    }

    public function pushHistoryEvents(): void
    {
        /** @var CyberXmlDocumentHistoryEvent[] $events */
        $events = $this->_attributes['historyEvents'] ?? [];

        $this->removeHistory();
        if (count($events) === 0) {
            return;
        }

        $xpath = $this->getBoundAttributeXpath('historyEvents');
        $historyNode = $this->forceCreateElement($xpath);

        foreach ($events as $event) {
            $eventNode = $this->_dom->createElementNS(static::DEFAULT_NS_URI, 'Event');
            $eventNode->setAttribute('time', $event->time);
            $eventNode->setAttribute('type', $event->type);
            if ($event->userName) {
                $userNameNode = $this->_dom->createElementNS(static::DEFAULT_NS_URI, 'UserName', $event->userName);
                $eventNode->appendChild($userNameNode);
            }
            if ($event->userIp) {
                $userIpNode = $this->_dom->createElementNS(static::DEFAULT_NS_URI, 'UserIp', $event->userIp);
                $eventNode->appendChild($userIpNode);
            }
            $historyNode->appendChild($eventNode);
        }
    }

    public function fetchHistoryEvents(): void
    {
        $this->_attributes['historyEvents'] = [];

        $xpath = $this->getBoundAttributeXpath('historyEvents');

        /** @var \DOMNodeList $nodeList */
        $nodeList = $this->_xpath->query($xpath);
        if ($nodeList->length === 0) {
            return;
        }

        /** @var \DOMNode $node */
        foreach ($nodeList[0]->childNodes as $node) {
            $this->_attributes['historyEvents'][] = CyberXmlDocumentHistoryEvent::fromDomNode($node);;
        }
    }

    private function removeHistory(): void
    {
        $xpath = $this->getBoundAttributeXpath('historyEvents');
        $this->createXpath();
        $historyNodeList = $this->_xpath->query($xpath);
        if ($historyNodeList->length > 0) {
            $node = $historyNodeList->item(0);
            $node->parentNode->removeChild($node);
        }
    }

    public function replaceTypeModel(BaseType $typeModel): CyberXmlDocument
    {
        $cyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
        $cyxDoc->docId = $this->docId;
        $cyxDoc->docType = $typeModel->getTransportType();
        $cyxDoc->docDate = $this->docDate;
        $cyxDoc->senderId = $this->senderId;
        $cyxDoc->receiverId = $this->receiverId;
        return $cyxDoc;
    }
}

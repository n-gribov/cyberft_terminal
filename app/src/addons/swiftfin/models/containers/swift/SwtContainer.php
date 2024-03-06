<?php

namespace addons\swiftfin\models\containers\swift;

use addons\swiftfin\DocumentModule;
use addons\swiftfin\models\containers\BaseContainer;
use addons\swiftfin\models\documents\mt\MtBaseDocument;
use addons\swiftfin\models\documents\mt\MtUniversalDocument;
use addons\swiftfin\models\SwiftFinDictBank;
use DateTime;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * @property string $content
 * @property string $contentPrintable
 * @property string $contentReadable
 * @property array $supportedTypes
 * @property MtBaseDocument $contentModel
 *
 * @author fuzz
 */

class SwtContainer extends BaseContainer
{
	const BASIC_HEADER_LENGTH = 25;
	const APPLICATION_HEADER_LENGTH = 17;
	const ADDRESS_LENGTH = 12;
	const FORMAT = 'swt';
	const TYPE_UNDEFINED = 0;

	const DIRECTION_IN  = 'I';
	const DIRECTION_OUT = 'O';

	public $basicHeader;
	public $applicationHeader;
	public $userHeader;
	public $trailerBlock;

	public $messageType = 'swift';

	public $applicationId = 'F';
	public $serviceId = '01';

	public $direction = self::DIRECTION_IN;
	public $messagePriority = 'N';

	public $terminalCode;

	protected $_sender;
	protected $_recipient;
	protected $_sessionNumber;
	protected $_inputSequenceNumber;
	protected $_outputSequenceNumber;
	protected $_inputDate;
	protected $_inputTime;
	protected $_outputDate;
	protected $_outputTime;
	protected $_bankPriority;
	protected $_messageUserReference;

    protected $_checkSum = null;

	private $_contentType;
	private $_content;

    private $_contentTypeSuffix;

	/**
	 * @var DateTime
	 */
	private $_inputDateTime;

	public function setSourceFile($path)
	{
		parent::setSourceFile($path);

		if (isset($path) && is_file($path)) {
			$this->_inputDateTime = new DateTime();
			$this->_inputDateTime->setTimestamp(filemtime($this->_sourceFile));
		}
	}

	public static function isTrueFormat($content)
	{
		return (1 === preg_match_all("/(\{\s*1\s*\:)/", $content));
	}

	/**
	 * @return mixed
	 */
	public function getSender()
	{
		return $this->_sender;
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setSender($value)
	{
		$this->_sender = $value; #TerminalId::extract($value);

		return $this;
	}

	public function setBankPriority($value)
	{
		$this->_bankPriority = trim($value);
	}

	public function setMessageUserReference($value)
	{
		$this->_messageUserReference = trim($value);
	}

	public function getMessageUserReference()
	{
		return $this->_messageUserReference;
	}

	public function getBankPriority()
	{
		return $this->_bankPriority;
	}

	/**
	 * @return mixed
	 */
	public function getRecipient()
	{
		return $this->_recipient;
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setRecipient($value)
	{
		$this->_recipient = $value;

		return $this;
	}

	/**
	 * Возвращает Session Number
	 * @return string padded 4
	 */
	public function getSessionNumber()
	{
		return str_pad($this->_sessionNumber, 4, '0', STR_PAD_LEFT);
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setSessionNumber($value)
	{
		$this->_sessionNumber = $value;

		return $this;
	}

	/**
	 * Возвращает Input Sequence Number
	 * @return string padded 6
	 */
	public function getInputSequenceNumber()
	{
		return str_pad($this->_inputSequenceNumber, 6, '0', STR_PAD_LEFT);
	}

	/**
	 * Возвращает Output Sequence Number
	 * @return string padded 6
	 */
	public function getOutputSequenceNumber()
	{
		return str_pad($this->_outputSequenceNumber, 6, '0', STR_PAD_LEFT);
	}

	/**
	 * @param string $value
	 * @return $this
	 */
	public function setInputSequenceNumber($value)
	{
		$this->_inputSequenceNumber = $value;

		return $this;
	}

	public function setOutputSequenceNumber($value)
	{
		$this->_outputSequenceNumber = $value;

		return $this;
	}

    /**
     * Set input date
     *
     * @param mixed $date Input date
     */
    public function setInputDateTime($date)
    {
        try {
            if (!is_int($date)) {
                $date = strtotime($date);
            }

            $this->_inputDateTime = new DateTime();
			$this->_inputDateTime->setTimestamp($date);
        } catch (\Exception $ex) {
            Yii::warning($ex->getMessage());
        }
    }

	/**
	 * @inheritdoc
	 * @return array
	 */
	public function attributes()
	{
		return ArrayHelper::merge(parent::attributes(), [
			'contentType', 'content'
		]);
	}

	public function getPath()
	{
		return $this->getSourceFile();
	}

	public function loadData($data = null)
	{
		parent::loadData($data);

        $content = $this->getRawContents();

		$parsed = $this->parse($content);

        $pos = strpos($content, '{');
        if ($pos > 0) {
            $this->_checkSum = substr($content, 0, $pos);
        }

		$this->basicHeader = empty($parsed[1]) ? null : $parsed[1];
		$this->applicationHeader = empty($parsed[2]) ? null : $parsed[2];
		$this->userHeader = empty($parsed[3]) ? null : $parsed[3];
		$this->trailerBlock = empty($parsed[5]) ? null : $parsed[5];
		$this->setContent(empty($parsed[4]) ? null : trim($parsed[4]));

		$this->parseMessageFields();

		/**
		 * @todo Определить тип документа и установить сценарий
		 */

        return true;
	}

	/**
	 * Нужно для совместимости c XML
	 * @return string
	 */
	public function getContentText()
	{
		return $this->getContent();
	}

	/**
	 * @param string $value
	 */
	public function setContent($value)
	{
		$this->_content = trim(trim($value, '-'));
	}

	/**
	 * @return string|null
	 */
	public function getContent()
	{
		return $this->_content;
	}

	/**
	 * @param string $value
	 */
	public function setContentType($value)
	{
        if (mb_strlen($value) > 3) {
            $this->_contentType = mb_substr($value, 0, 3);
            $this->_contentTypeSuffix = mb_substr($value, 3);
        } else {
            $this->_contentType = $value;
            $this->_contentTypeSuffix = null;
        }
	}

	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->_contentType . $this->_contentTypeSuffix;
	}

	public function rules()
	{
		return [
			[
				['sender', 'recipient', 'messageType', 'contentType', 'content'],
				'required',
			],
			['basicHeader', 'string', 'length' => self::BASIC_HEADER_LENGTH],
			['applicationHeader', 'string', 'min' => self::APPLICATION_HEADER_LENGTH],
			[['sender', 'recipient'], 'string', 'length' => [11, 12]],
			[['contentType'], 'string', 'length' => [3, 8]],
			['serviceId', 'in', 'range' => ['01', '21']],
			['messageType', 'default', 'value' => 'swift'],
			['messageType', 'in', 'range' => ['swift', 'cyberft']],
			['messagePriority', 'in', 'range' => ['S', 'N', 'U']],
			['bankPriority', 'string', 'length' => 4],
		];
	}

	public function scenarios()
	{
		return [
			self::SCENARIO_DEFAULT => [
				'messageType', 'contentType', 'sender', 'recipient', 'content', 'terminalCode'
			],
			'wizard' => [
				'messageType', 'contentType', 'sender', 'recipient', 'terminalCode'
			],
		];
	}

	public function attributeLabels()
	{
		return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'type'         => Yii::t('doc', 'Type'),
                'typeCode'     => Yii::t('doc', 'Type'),
                'sender'       => Yii::t('doc', 'Sender'),
                'recipient'    => Yii::t('doc', 'Recipient'),
                'contentType'  => Yii::t('doc', 'Document body'),
                'terminalCode' => Yii::t('doc', 'Terminal code'),
                'content'      => Yii::t('doc', 'Content'),
            ]
        );
    }

	public function getSupportedTypes()
	{
		return DocumentModule::getInstance()->mtDispatcher->getTypesLabels();
	}

	public function getIsCyberftNetwork()
	{
		return (bool)mb_substr_count($this->recipient,'@');
	}

	public function parseMessageFields()
	{
		$this->direction     = substr($this->applicationHeader, 0, 1);
		$this->applicationId = substr($this->basicHeader, 0, 1);
		$this->serviceId     = substr($this->basicHeader, 1, 2);
		$this->inputSequenceNumber = substr($this->basicHeader, 0, 1);
		$this->sessionNumber = substr($this->basicHeader, 0, 1);

		preg_match("/\{113\:(?P<bankPriority>[^\}]*)\}/", $this->userHeader, $match);
		if (!empty($match['bankPriority'])) {
			$this->setBankPriority($match['bankPriority']);
		}

		preg_match("/\{108\:(?P<mur>[^\}]*)\}/", $this->userHeader, $match);
		if (!empty($match['mur'])) {
			$this->setMessageUserReference($match['mur']);
		}

        preg_match("/\{119\:(?P<suffix>[^\}]*)\}/", $this->userHeader, $match);
		if (!empty($match['suffix'])) {
			$this->_contentTypeSuffix = $match['suffix'];
		}

		/**
		 * @todo Поддержать определение messageType
		 */
		$this->messageType  = 'swift';
		$this->_contentType = substr($this->applicationHeader, 1, 3);

		if (self::DIRECTION_IN == $this->direction) {
			$this->sender    = substr($this->basicHeader, 3, self::ADDRESS_LENGTH);
			$this->recipient = substr($this->applicationHeader, 4, self::ADDRESS_LENGTH);
			$this->messagePriority = substr($this->applicationHeader, 16, 1);
		} else { // direction 'O'
			$this->sender    = substr($this->applicationHeader, 14, self::ADDRESS_LENGTH);
			$this->recipient = substr($this->basicHeader, 3, self::ADDRESS_LENGTH);
			$this->messagePriority = substr($this->applicationHeader, -1, 1);
		}
	}

	public function updateMessageFields()
	{
		$this->basicHeader = trim(
			$this->applicationId
			. $this->serviceId
			. (self::DIRECTION_IN == $this->direction ? $this->sender : $this->recipient)
			. $this->sessionNumber
			. $this->outputSequenceNumber
		);

        $this->userHeader = '';

		if (!empty($this->_messageUserReference)) {
			$this->userHeader .= '{108:' . $this->_messageUserReference . '}';
		}

		if (!empty($this->_bankPriority)) {
			$this->userHeader .= '{113:' . $this->_bankPriority . '}';
		}

        if (!empty($this->_contentTypeSuffix)) {
            $this->userHeader .= '{119:' . $this->_contentTypeSuffix . '}';
        }

		if (!empty($this->basicHeader)) {
			/**
			 * @todo Уточнить: длина 25 символов не документирована в нотации Swift формата. Зачем добивать?
			 */
			$this->basicHeader = str_pad($this->basicHeader, self::BASIC_HEADER_LENGTH, '0');
		}

		/**
		 * Application header
		 */
		if (self::DIRECTION_IN == $this->direction) {
			$this->applicationHeader = trim(
				$this->direction
				. $this->_contentType
				. $this->recipient
				. $this->messagePriority
				//. '' // Delivery monitoring
				//. '' // Obsolescence period
			);
		} else { // "O" direction
			$this->applicationHeader = trim(
				$this->direction
				. $this->_contentType
				/** Input time with respect to the sender */
				. ($this->_inputDateTime ? $this->_inputDateTime->format('Hiymd') : '0000000000')
				/** The Message Input Reference (MIR), including input date, with Sender's address */
				. $this->sender
				. $this->sessionNumber . $this->inputSequenceNumber
				/** ymd: Output date with respect to Receiver */
				/** hm: Output time with respect to Receiver */
                . date('ymdhm')
				. $this->messagePriority
			);
		}
	}

	/**
	 * @param string $input
	 * @return array|bool
	 */
	protected function parse($input)
	{
		// разбиение пакета на сообщения по признаку начала собщения "{1:"
		$document = preg_replace('/(\{\s*1\s*\:)/', '%message%${1}', $input);
		$document = explode('%message%', $document);
		array_shift($document); // убрать пустой элемент

		// не обрабатываются файлы с более чем одним сообщением
		if (empty($document) || count($document) > 1) {
			return false;
		}

		$document = reset($document);

		$fields = [];
		$matches = [];
		// разбиение сообщения на поля
		preg_match_all('/(\{([^{}]|(?R))*\})/s', $document, $matches);
		if (!empty($matches) && !empty($matches[1])) {
			$fields = $matches[1];
		}

		$result = [];
		// Формирование документа из полей сообщения
		foreach ($fields as $field) {
			preg_match('/^\s*\{\s*([^\:]+)\:(.*)\}$/s', $field, $matches);
			$result[trim($matches[1])] = $matches[2];
		}

		return $result;
	}


	/**
	 * @return string
	 */
	public function export()
	{
		return $this->pack();
	}

	/**
	 * @return string
	 */
	public function pack()
	{
		$result = [
			"{1:{$this->basicHeader}}",
			"{2:{$this->applicationHeader}}",
		];

		if ($this->userHeader) {
			$result[] = "{3:{$this->userHeader}}";
		}

		if (($content = $this->getContent())) {
			$result[] = "{4:\n" . trim(trim($content, '-')) . "\r\n-}";
		}

		if ($this->trailerBlock) {
			$result[] = "{5:{$this->trailerBlock}}";
		}

        $msg = join('', $result);

        $settings = Yii::$app->settings->get('swiftfin:Swiftfin');

        if ($settings->exportChecksum) {
            $len = strlen($msg) + 4;
            $checkSum = chr($len % 256) . chr($len >> 8) . chr(0) . chr(0);
            return $checkSum . $msg;
        }

        return chr(1) . $msg . hex2bin('030D0A');
	}

	/**
	 * Updates model source file
	 */
	public function save()
	{
		$this->updateMessageFields();

		if (!file_put_contents($this->sourceFile, $this->pack())) {
			throw new Exception(Yii::t('app', 'Cannot write file {path}', ['path' => $this->sourceFile]));
		}

		return true;
	}


    //public function get

	public function getTypeCode()
	{
		return $this->messageType . '/' . $this->contentType;
	}

	/**
	 * Получаем модель по содержащемуся контенту документа
	 * Измененение состояния документа НЕ ВЛИЯЕТ на последуюший текстовый вид документа
	 * Для изменения текстового вида, необходимо осуществить замену через $this->setContent($value)
	 * @return MtUniversalDocument | MtBaseDocument
	 */
	public function getContentModel()
	{
		if ($this->_content && $this->_contentType) {
			$model = Yii::$app->getModule('swiftfin')->mtDispatcher->instantiateMt($this->contentType);
			$model->setBody($this->_content);

			return $model;
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
	{
		if (isset($this->getContentModel()->currency)) {
			return $this->getContentModel()->currency;
		} else {
			return null;
		}
	}

	/**
	 * @return float
	 */
	public function getSum()
	{
		if (isset($this->getContentModel()->sum)) {
			return (float)str_replace(',', '.', $this->getContentModel()->sum);
		} else {
			return 0;
		}
	}

	/**
	 * @return string
	 */
	public function getDate()
	{
		if (isset($this->getContentModel()->date)) {
			return $this->getContentModel()->date;
		} else {
			return null;
		}
	}

	/**
	 * @return string
	 */
	public function getOperationReference()
    {
        $contentModel = $this->getContentModel();

        if ($contentModel) {
            if (isset($contentModel->operationReference)) {
                return $contentModel->operationReference;
            /** @todo временный подкостылик, для подержки operationReference в документах где это есть
             * требуется переработка, т.к. далеко не во всех документах тэг 20 находится на первом уровне
             * соотв. необходимо замапить альяс через конфиги там где это возможно
             */
            } else if ($contentModel->getNode('20')) {
                return $contentModel->getNode('20')->getValue();
            }
        }

        return null;
	}

	/**
	 * Возвращает Input Date из mir - только с направлением Output!
	 * @return string
	 */
	public function getInputDate()
	{
		if (self::DIRECTION_OUT == $this->direction) {
			return substr($this->applicationHeader, 8, 6);
		}

		return '000000';
	}

	/**
	 * Возвращает Output Date из блока 2 - только с направлением Output!
	 * @return string
	 */
	public function getOutputDate()
	{
		if (self::DIRECTION_OUT == $this->direction) {
			return substr($this->applicationHeader, 36, 6);
		}

		return '000000';
	}

	/**
	 * Возвращает Input Time из блока 2 - только с направлением Output!
	 * @return string
	 */
	public function getInputTime()
	{
		if (self::DIRECTION_OUT == $this->direction) {
			return substr($this->applicationHeader, 4, 4);
		}

		return '0000';
	}

	/**
	 * Возвращает Output time из блока 2 - только с направлением Output!
	 * @return string
	 */
	public function getOutputTime()
	{
		if (self::DIRECTION_OUT === $this->direction) {
			return substr($this->applicationHeader, 42, 4);
		}

		return '0000';
	}

	/**
	 * Возвращает Message Input Reference из блока 2 - только с направлением Output!
	 * @return string
	 */
	public function getMIR()
	{
		if (self::DIRECTION_OUT === $this->direction) {
			return substr($this->applicationHeader, 8, 28);
		}

        return null;
	}

	public function getContentReadable()
	{
		return !is_null($this->contentModel) ? $this->contentModel->dataReadable : null;
	}

    private function patchAddress($address)
    {
        $swiftCode = substr($address, 0, 8);
        $branchCode = substr($address, strlen($address) == 12 ? 9 : 8);
        $bank = SwiftFinDictBank::findOne(['swiftCode' => $swiftCode, 'branchCode' => $branchCode]);
        if (!empty($bank)) {
            $address .= PHP_EOL . "\t\t\t" . $bank->name;
            if (!empty($bank->address)) {
                $address .= PHP_EOL . "\t\t\t" . $bank->address;
            }
        }

        return $address;
    }

	public function getContentPrintable()
	{
		return "____________________________________________________________________
--------------------------- Message Header -------------------------
Swift {$this->directionReadable}\t:\t{$this->typeCode}
Sender\t\t:\t{$this->patchAddress($this->sender)}
Receiver\t:\t{$this->patchAddress($this->recipient)}
--------------------- Instance Type and Transmission ---------------
Priority/Delivery : {$this->messagePriorityReadable}
--------------------------- Message Text ---------------------------
{$this->contentReadable}
-------------------------- Message Source --------------------------
{$this->rawText}
" . ( $this->trailerBlock != ''
		? "--------------------------- Message Trailer ------------------------" . PHP_EOL . $this->trailerBlock
		: ''
	);
	}

	public function getMessagePriorityReadable()
	{
		switch($this->messagePriority)
		{
			case 'N':
				return 'Normal';
			case 'U':
				return 'Urgent';
			case 'S':
				return 'System';
			default :
				return 'N/a';
		}
	}

	public function getDirectionReadable()
	{
		switch($this->direction)
		{
			case self::DIRECTION_IN:
				return 'Input';
			case self::DIRECTION_OUT:
				return 'Output';
			default :
				return 'N/a';
		}
	}

	/**
	 * Функция возвращает набор атрибутов, которые необходимы модели Document
	 * для экстрактирования данных из SWT-документа.
	 */
	public function getDocumentData() {
		return [
			'operationReference' => ($this->getContentModel()
				? $this->getContentModel()->operationReference
				: ''),
			'valueDate' => $this->getValueDate(),
			'typeCode'         => $this->typeCode,
			'sender'           => $this->sender,
			'receiver'         => $this->recipient,
			'nestedItemsCount' => 1,
			'sum'              => $this->getSum(),
			'count'            => 1,
			'currency' => $this->getCurrency(),
		];
	}

	/**
	 * Функция возвращает полный текст SWT.
	 * Используется для запечатывания данного вида содержимого в CyberXML-контейнер
	 * @return string
	 */
	public function getRawText()
	{
        $this->updateMessageFields();

		return $this->pack();
	}

	/**
	 * Функция добывает дату валютирования из MT-документа.
	 * Алгоритм получения даты основан на документе
	 *	Стандарты МТ версии ноября 2013 г.
	 *	Общая информация, 8.8.3
	 *  Для получения значения в конфигурации MT должен быть прописан алиас valueDate,
	 * а для 500-го семейства еще и valueCode (см. 541 для уточнения).
	 * @return string
	 */
	public function getValueDate()
	{
		/**
		 * Получение алиаса может породить \Exception.
		 */
		try {
			$valueCode = (string)$this->getContentModel()->valueCode;
		} catch(\Exception $ex) {
			$valueCode = null;
		}

		try {
			$valueDate = (string)$this->getContentModel()->date;
		} catch(\Exception $ex) {
			$valueDate = null;
		}

		/**
		 * Наличие даты валютирования для 500-го семейства МТшек зависит от
		 * значения определителя (д.б. SETT, иначе это не валютирование!).
		 */
		if (!is_null($valueCode) && $valueCode !== 'SETT') {
			return null;
		}

		// Возвращаем саму дату, если она есть
		return $valueDate;
	}

}

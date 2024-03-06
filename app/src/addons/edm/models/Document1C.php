<?php
namespace addons\edm\models;
use addons\edm\helpers\Dict;
use addons\edm\validators\CbrKeyingValidator;
use addons\edm\validators\CorrespondentAccountValidator;

/**
 * Class Document1C
 * @property string $recipient
 * @package addons\edm\models
 */
abstract class Document1C extends Document {

	const TYPE       = 'unknown1C';
	const TYPE_LABEL = 'Неопознаный 1C'; // Используется при формировании тела 1С документа
	const LABEL      = 'Неопознаный 1C';

	public $version = '1.0';
	/**
	 * Указатель кодировки документа
	 * !!! warning !!! на самом деле внутри системы сформированный документ всегда будет в utf8
	 * но при экспорте необходимо менять кодировку на cp1251
	 * @var string
	 */
	public $encoding          = 'Windows';
	public $softNameSender    = 'CyberFT';
	public $softNameRecipient = 'Бухгалтерский учет, редакция 4.2';
	public $dateCreated;
	public $timeCreated;

	public $openingBalance = 0;
	public $debitTurnover = 0;
	public $creditTurnover = 0;
	public $closingBalance = 0;

	public $documentDateFrom;
	public $documentDateBefore;
	public $organizationCheckingAccount;

	public $sum;
	public $vat; // НДС
	public $paymentType;
	public $paymentCondition1;
	public $paymentCondition2;
	public $paymentCondition3;
	public $acceptPeriod;
	public $payerName;
	public $payerName1;
	public $payerName2;
	public $payerName3;
	public $payerName4;
	public $payerCheckingAccount;
	public $payerCorrespondentAccount;
	public $payerDateEnrollment;
	public $payerBank1;
	public $payerBank2;
	public $payerBik;
	public $beneficiaryBank1;
	public $beneficiaryBank2;
	public $beneficiaryBik;
	public $beneficiaryCheckingAccount;
	public $beneficiaryCorrespondentAccount;
	public $beneficiaryDateDebiting;
	public $beneficiaryName;
	public $beneficiaryName1;
	public $beneficiaryName2;
	public $beneficiaryName3;
	public $beneficiaryName4;
	public $payType;
	public $priority;
	public $documentSendDate;
	public $senderStatus;
	public $payerInn;
	public $payerKpp;
	public $beneficiaryInn;
	public $beneficiaryKpp;
	public $indicatorKbk;
	public $okato;
	public $indicatorReason = 0;
	public $indicatorPeriod = 0;
	public $indicatorNumber = 0;
	public $indicatorDate = 0;
	public $indicatorType = 0;
	protected $_paymentPurpose;
	public $paymentPurposeNds;
	public $paymentPurpose1;
	public $paymentPurpose2;
	public $paymentPurpose3;
	public $code;
    public $maturity;
    public $paymentOrderPaymentPurpose;
    public $backingField;

	/**
	 * Карта тэг => аттрибут
	 * @var array
	 */
	private $_tagAttributes = [];

	public function init()
    {
		parent::init();
		$this->dateCreated = date('d.m.Y');
		$this->timeCreated = date('H:i:s');
	}

	public function setDate($date)
    {
		parent::setDate($date);
		$this->documentDateBefore = $date;
		$this->documentDateFrom   = $date;
	}

	public function getDocumentType()
    {
		return static::TYPE_LABEL;
	}

	public function setDocumentType($value)
    {
		// сделано только для возможности импорта из файла, дабы не лезла ошибка
	}

	public function getCurrency()
    {
		return 'RUB';
	}

	/**
	 * @return mixed
	 */
	public function getPaymentPurpose()
    {
		if (isset($this->paymentPurposeNds)) {
			return $this->_paymentPurpose.($this->_paymentPurpose ? ' ' : null) . $this->paymentPurposeNds;
		}

		return $this->_paymentPurpose;
	}

	public function getRawPaymentPurpose()
    {
		return $this->_paymentPurpose;
	}

	/**
	 * @param mixed $paymentPurpose
	 */
	public function setPaymentPurpose($paymentPurpose)
    {
		$this->_paymentPurpose = $paymentPurpose;
	}

	/**
	 * @return null|string
	 */
	public function getRecipient()
    {
		if (!$this->payerCheckingAccount) {
			return null;
		}
		$bank = DictBank::findOne([
			'bik' => $this->payerBik
		]);

        return (isset($bank->terminalId) ? $bank->terminalId : null);
	}

	/**
	 * @return string
	 */
	public function getPayerAccount()
    {
		return $this->payerCheckingAccount;
	}

	/**
	 * @param string $payerAccount
	 * @return $this
	 */
	public function setPayerAccount($payerAccount)
    {
		if (!$this->payerCheckingAccount) {
			$this->payerCheckingAccount = $payerAccount;
		}

		return $this;
	}

	/**
	 * @return string
	 */
	public function getBeneficiaryAccount()
    {
		return $this->beneficiaryCheckingAccount;
	}

	/**
	 * @param string $beneficiaryAccount
	 * @return $this
	 */
	public function setBeneficiaryAccount($beneficiaryAccount)
    {
		if (!$this->beneficiaryCheckingAccount) {
			$this->beneficiaryCheckingAccount = $beneficiaryAccount;
		}

		return $this;
	}

	/**
	 * @param string $attribute
	 * @param array  $params
	 * @return bool
	 */
	public function validateRecipient($attribute = 'recipient', $params = [])
    {
		if (!$this->getRecipient()) {
			$this->addError('payerBik',	\Yii::t('edm', 'You must set terminal identifier in bank or contractor dictionary'));
		}
	}

	/**
	 * @return string
	 */
	public function getBody()
    {
		$tags        = $this->attributeTags();
		$headerTags  = $this->headerAttributeTags();
		$accountTags = $this->checkingAccountTags();

		$str = "1CClientBankExchange\r\n";
		// служебные заголовки
		foreach ($headerTags as $k => $v) {
			if (isset($this->$k)) {
				$str .= ($v ? "$v=" : null) . "{$this->$k}\r\n";
			}
		}

		$str .= "СекцияРасчСчет\r\n";
		foreach ($accountTags as $k => $v) {
			if (isset($this->$k)) {
				$str .= ($v ? "$v=" : null) . "{$this->$k}\r\n";
			}
		}
		$str .= "КонецРасчСчет\r\n";

		foreach ($tags as $k => $v) {
			// ну такой вариант костылика, потомки не серчайте см. CYB-1531 @todo разрулите через rules
			if (isset($this->$k) && $this->$k || $k == 'beneficiaryCorrespondentAccount') {
				$str .= ($v ? "$v=" : null) . "{$this->$k}\r\n";
			}
		}
		$str .= "КонецДокумента\r\n";
		$str .= "КонецФайла\r\n";
		return $str;
	}

	/**
	 * @return string
	 */
	public function getBodyReadable()
    {
		$labels = $this->attributeLabels();
		$str    = '';
		foreach ($labels as $k => $v) {
			if (isset($this->$k)) {
				$str .= ($v ? "$v: " : null) . "{$this->$k}\r\n";
			}
		}

		return $str;
	}

	/**
	 * @return string
	 */
	public function getBodyPrintable()
    {
		return $this->getBodyReadable();
	}

	/**
	 * @return array
	 */
	public function rules()
    {
		return array_merge(parent::rules(), [
			[
				'senderStatus', 'in', 'range' => [
				'01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16',
				'17', '18', '19', '20', '21', '22', '23', '24', '25', '26'
			]
			],

			[['payerInn', 'beneficiaryInn'], 'string', 'length' => [9, 12]],
			[['payerInn', 'beneficiaryInn'], 'match', 'pattern' => '/^[0-9]{9,12}$/'],

			[['payerKpp', 'beneficiaryKpp', 'payerBik', 'beneficiaryBik'], 'string', 'length' => [9, 9]],
			[['payerKpp', 'beneficiaryKpp', 'payerBik', 'beneficiaryBik'], 'match', 'pattern' => '/^[0-9]{9}$/'],

			[
				[
					'payerCheckingAccount', 'payerCorrespondentAccount', 'beneficiaryCheckingAccount',
					'beneficiaryCorrespondentAccount'
				],
				'string', 'length' => [20, 20]
			],
			[
				[
					'payerCheckingAccount', 'payerCorrespondentAccount', 'beneficiaryCheckingAccount',
					'beneficiaryCorrespondentAccount'
				],
				'match', 'pattern' => '/^[0-9]{20}$/'
			],
			['payerCheckingAccount', CbrKeyingValidator::className(), 'bikKey' => 'payerBik'],
			['beneficiaryCheckingAccount', CbrKeyingValidator::className(), 'bikKey' => 'beneficiaryBik'],

			['payerCorrespondentAccount', CorrespondentAccountValidator::className(), 'bikKey' => 'payerBik'],
			['beneficiaryCorrespondentAccount', CorrespondentAccountValidator::className(), 'bikKey' => 'beneficiaryBik'],

			[['payType', 'priority'], 'in', 'range' => array_keys(Dict::priority())],

			['indicatorKbk', 'string', 'length' => [1, 20]],
			['indicatorKbk', 'match', 'pattern' => '/^[0-9 ]{1,20}$/'],

			['okato', 'string', 'length' => [1, 11]],
			['okato', 'match', 'pattern' => '/^[0-9 ]{1,11}$/'],

			[['indicatorReason', 'indicatorType'], 'string', 'length' => [1, 2]],

			[['indicatorPeriod'], 'string', 'length' => [1, 10]],

			[
				'paymentPurpose', 'string', 'length' => [0, 210],
				'message' => 'Общая длина поля '.$this->getAttributeLabel('paymentPurpose').' не должно превышать {max} символов'
			],

			[['acceptPeriod', 'vat'], 'number'],
			['sum', 'double', 'min' => 1, 'max' => 99999999999999],
			['documentSendDate', 'date', 'format' => 'd.M.yyyy'],
			[['paymentPurposeNds', 'vat', 'payerDateEnrollment', 'beneficiaryDateDebiting'], 'safe'],
            ['code', 'integer'],
            ['code', 'default', 'value' => 0],
		]);
	}

	/**
	 * @return array
	 */
	public function attributeLabels()
    {
		return [
			// Общие сведения
			'version'                         => 'Номер версии формата обмена',
			'encoding'                        => 'Кодировка файла',
			'softNameSender'                  => 'Программа-отправитель',
			'softNameRecipient'               => 'Программа-получатель',
			'dateCreated'                     => 'Дата формирования файла',
			'timeCreated'                     => 'Время формирования файла',

			// Сведения об условиях отбора передаваемых данных
			'documentDateFrom'                => 'Дата начала интервала',
			'documentDateBefore'              => 'Дата конца интервала',
			'organizationCheckingAccount'     => 'Расчетный счет организации',
			'documentType'                    => 'Вид документа',

			// Секция платежного документа
			'number'                          => 'Номер документа',
			'date'                            => 'Дата документа',
			'sum'                             => 'Сумма платежа',
			'vat'                             => 'Ставка НДС',
			'senderStatus'                    => 'Статус составителя расчетного документа',
			'paymentType'                     => 'Вид платежа',
			'payerName'                       => 'Плательщик', // наименование
			'payerName1'                      => 'Плательщик стр. 1',
			'payerName2'                      => 'Плательщик стр. 2',
			'payerName3'                      => 'Плательщик стр. 3',
			'payerName4'                      => 'Плательщик стр. 4',
			'payerInn'                        => 'ИНН плательщика',
			'payerKpp'                        => 'КПП плательщика',
			'payerAccount'                    => 'Расчетный счет плательщика',
			'payerCheckingAccount'            => 'Расчетный счет плательщика',
			'payerCorrespondentAccount'       => 'Корсчет банка плательщика',
			'payerDateEnrollment'             => 'Дата списания средств с р/с',
			'payerBank1'                      => 'Банк плательщика',
			'payerBank2'                      => 'Город банка плательщика',
			'payerBik'                        => 'БИК банка плательщика',
			'beneficiaryName'                 => 'Получатель', // наименование
			'beneficiaryName1'                => 'Получатель стр. 1',
			'beneficiaryName2'                => 'Получатель стр. 2',
			'beneficiaryName3'                => 'Получатель стр. 3',
			'beneficiaryName4'                => 'Получатель стр. 4',
			'beneficiaryInn'                  => 'ИНН получателя',
			'beneficiaryKpp'                  => 'КПП получателя',
			'beneficiaryAccount'              => 'Расчетный счет получателя',
			'beneficiaryCheckingAccount'      => 'Расчетный счет получателя',
			'beneficiaryCorrespondentAccount' => 'Корсчет банка получателя',
			'beneficiaryDateDebiting'         => 'Дата поступления средств на р/с',
			'beneficiaryBank1'                => 'Банк получателя',
			'beneficiaryBank2'                => 'Город банка получателя',
			'beneficiaryBik'                  => 'БИК банка получателя',
			'payType'                         => 'Вид оплаты',
			'priority'                        => 'Очередность платежа',
			'indicatorKbk'                    => 'Показатель кода бюджетной классификации',
			'okato'                           => 'Код ОКТМО территории, на которой мобилизуются денежные средства от уплаты налога, сбора и иного платежа',
			'indicatorReason'                 => 'Показатель основания налогового платежа',
			'indicatorPeriod'                 => 'Показатель налогового периода / Код таможенного органа',
			'indicatorNumber'                 => 'Показатель номера документа',
			'indicatorDate'                   => 'Показатель даты документа',
			'indicatorType'                   => 'Показатель типа платежа',
			'paymentPurpose'                  => 'Назначение платежа',
			'paymentPurposeNds'               => 'В т.ч. НДС',
			'paymentPurpose1'                 => 'Назначение платежа стр.1',
			'paymentPurpose2'                 => 'Назначение платежа стр.2',
			'paymentPurpose3'                 => 'Назначение платежа стр.3',
			'paymentPurpose4'                 => 'Назначение платежа стр.4',
			'paymentPurpose5'                 => 'Назначение платежа стр.5',
			'paymentPurpose6'                 => 'Назначение платежа стр.6',
			'code'                            => 'Уникальный идентификатор платежа',
			'paymentCondition1'               => 'Условие оплаты, стр. 1',
			'paymentCondition2'               => 'Условие оплаты, стр. 2',
			'paymentCondition3'               => 'Условие оплаты, стр. 3',
			'acceptPeriod'                    => 'Срок акцепта, количество дней',
			'documentSendDate'                => 'Дата отсылки документов',
		];
	}

	public function headerAttributeTags()
    {
		return [
			// Общие сведения
			'version'                     => 'ВерсияФормата',
			'encoding'                    => 'Кодировка',
			'softNameSender'              => 'Отправитель',
//			'softNameRecipient'           => 'Получатель',
			'dateCreated'                 => 'ДатаСоздания',
			'timeCreated'                 => 'ВремяСоздания',

			// Сведения об условиях отбора передаваемых данных
			'documentDateFrom'            => 'ДатаНачала',
			'documentDateBefore'          => 'ДатаКонца',
			'organizationCheckingAccount' => 'РасчСчет',
			'documentType'                => 'Документ',
		];
	}

	public function checkingAccountTags()
    {
		return [
			'documentDateFrom'     => 'ДатаНачала',
			'documentDateBefore'   => 'ДатаКонца',
			'payerCheckingAccount' => 'РасчСчет',
			'openingBalance'       => 'НачальныйОстаток',
			'debitTurnover'        => 'ВсегоПоступило',
			'creditTurnover'       => 'ВсегоСписано',
			'closingBalance'       => 'КонечныйОстаток'
		];
	}

	/**
	 * Служит для мапинга на тело документа в формате 1С
	 * @return array
	 */
	public function attributeTags()
    {
		return [
			'documentType'				      => 'СекцияДокумент',
			'number'                          => 'Номер',
			'date'                            => 'Дата',
			'sum'                             => 'Сумма',
			'senderStatus'                    => 'СтатусСоставителя',
			'paymentType'                     => 'ВидПлатежа',
			'payerName'                       => 'Плательщик', // наименование
			'payerName1'                      => 'Плательщик1',
			'payerName2'                      => 'Плательщик2',
			'payerName3'                      => 'Плательщик3',
			'payerName4'                      => 'Плательщик4',
			'payerInn'                        => 'ПлательщикИНН',
			'payerKpp'                        => 'ПлательщикКПП',
			'payerAccount'                    => 'ПлательщикСчет',
			'payerCheckingAccount'            => 'ПлательщикРасчСчет',
			'payerCorrespondentAccount'       => 'ПлательщикКорсчет',
			'payerDateEnrollment'             => 'ДатаСписано',
			'payerBank1'                      => 'ПлательщикБанк1',
			'payerBank2'                      => 'ПлательщикБанк2',
			'payerBik'                        => 'ПлательщикБИК',
			'beneficiaryName'                 => 'Получатель', // наименование
			'beneficiaryName1'                => 'Получатель1',
			'beneficiaryName2'                => 'Получатель2',
			'beneficiaryName3'                => 'Получатель3',
			'beneficiaryName4'                => 'Получатель4',
			'beneficiaryBank1'                => 'ПолучательБанк1',
			'beneficiaryBank2'                => 'ПолучательБанк2',
			'beneficiaryBik'                  => 'ПолучательБИК',
			'beneficiaryAccount'              => 'ПолучательСчет',
			'beneficiaryCheckingAccount'      => 'ПолучательРасчСчет',
			'beneficiaryCorrespondentAccount' => 'ПолучательКорсчет',
			'beneficiaryDateDebiting'         => 'ДатаПоступило',
			'beneficiaryInn'                  => 'ПолучательИНН',
			'beneficiaryKpp'                  => 'ПолучательКПП',
			'payType'                         => 'ВидОплаты',
			'priority'                        => 'Очередность',
			'indicatorKbk'                    => 'ПоказательКБК',
			'okato'                           => 'ОКАТО',
			'indicatorReason'                 => 'ПоказательОснования',
			'indicatorPeriod'                 => 'ПоказательПериода',
			'indicatorNumber'                 => 'ПоказательНомера',
			'indicatorDate'                   => 'ПоказательДаты',
			'indicatorType'                   => 'ПоказательТипа',
			'paymentPurpose'                  => 'НазначениеПлатежа',
			'paymentPurpose1'                 => 'НазначениеПлатежа1',
			'paymentPurpose2'                 => 'НазначениеПлатежа2',
			'paymentPurpose3'                 => 'НазначениеПлатежа3',
			'code'                            => 'Код',
			'paymentCondition1'               => 'УсловиеОплаты1',
			'paymentCondition2'               => 'УсловиеОплаты2',
			'paymentCondition3'               => 'УсловиеОплаты3',
			'acceptPeriod'                    => 'СрокАкцепта',
			'documentSendDate'                => 'ДатаОтсылкиДок',
		];
	}

	/**
	 * @param $tag
	 * @param $value
	 */
	public function setTag($tag, $value)
    {
		$attribute = $this->getAttributeByTag($tag);
		if ($attribute) {
			$this->$attribute = $value;
		}
	}

	public function setTags($tags)
    {
		foreach ($tags as $tag => $value) {
			$this->setTag($tag, $value);
		}
	}

	/**
	 * @param $tag
	 * @return mixed|null
	 */
	public function getTag($tag)
    {
		$attribute = $this->getAttributeByTag($tag);
		if (isset($this->$attribute)) {
			return $this->$attribute;
		}

        return null;
	}

	/**
	 * @return array
	 */
	public function getTags()
    {
		$data = [];
		$tags = $this->attributeTags();
		foreach ($tags as $attribute => $tag) {
			$data[$tag] = $this->$attribute;
		}

        return $data;
	}

	/**
	 * @param $tag
	 * @return bool
	 */
	public function hasTag($tag)
    {
		return (bool)$this->getAttributeByTag($tag);
	}

	/**
	 * @param $tag
	 * @return mixed
	 */
	protected function getAttributeByTag($tag)
    {
		$map = $this->getTagAttributes();

        return isset($map[$tag])?$map[$tag]:null;
	}

	/**
	 * @return array
	 */
	protected function getTagAttributes()
    {
		if (!empty($this->_tagAttributes)) {
			return $this->_tagAttributes;
		}

        return $this->_tagAttributes = array_merge(
			array_flip($this->attributeTags()),
			array_flip($this->headerAttributeTags()),
			array_flip($this->checkingAccountTags())
		);
	}

	protected function parse($string)
    {
		$rows = preg_split('/[\\r\\n]+/', $string);
		foreach ($rows as $row) {
			$t = explode('=', trim($row), 2);
			if (count($t) === 1) {
				continue;
			} else {
				list ($k, $v) = $t;
			}

			if (($attribute = $this->getAttributeByTag($k))) {
				$this->$attribute = $v;
			} else {
				$this->addError('body',
					\Yii::t('edm', 'Row ({row}) was ignored because "{parameter}" not supported', [
						'row'       => $row,
						'parameter' => $k
					])
				);
			}
		}
	}

}
<?php
namespace addons\swiftfin\models\documents\mt;

use addons\swiftfin\models\documents\mt\MtBaseDocument;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Description of MtDocument
 *
 * @author fuzz
 */
class Mt202Document extends MtBaseDocument {

	const DOC_TYPE = '202';

	const ORDERING_INSTITUTION_52A = 'A';
	const ORDERING_INSTITUTION_52D = 'D';

	const SENDER_CORRESPONDENT_53A = 'A';
	const SENDER_CORRESPONDENT_53B = 'B';
	const SENDER_CORRESPONDENT_53D = 'D';

	const RECEIVER_CORRESPONDENT_54A = 'A';
	const RECEIVER_CORRESPONDENT_54B = 'B';
	const RECEIVER_CORRESPONDENT_54D = 'D';

	const INTERMEDIARY_56A = 'A';
	const INTERMEDIARY_56D = 'D';

	const ACCOUNT_WITH_INSTITUTION_57A = 'A';
	const ACCOUNT_WITH_INSTITUTION_57B = 'B';
	const ACCOUNT_WITH_INSTITUTION_57D = 'D';

	const BENEFICIARY_INSTITUTION_58A = 'A';
	const BENEFICIARY_INSTITUTION_58D = 'D';

	public $timeIndication;

	// transactionReferenceNumber 20
	public $transactionReferenceNumber;

	// relatedReference 21
	public $relatedReference = 'NONREF';

	// dateCurrencySettledAmount 32A
	private $_dateCurrencySettledAmount;
	public $date;
	protected $_currency = 'RUB';
	protected $_settledAmount;

	// orderingInstitution 52a
	// orderingInstitution/Option 52A
	private $_orderingInstitutionA;
	public $orderingInstitutionReqA;
	public $orderingInstitutionTextA;
	// orderingInstitution/Option 52D
	private $_orderingInstitutionD;
	public $orderingInstitutionReqD;
	public $orderingInstitutionTextD;
	// 52a option type
	public $orderingInstitutionVariation = null; /*self::ORDERING_INSTITUTION_52A*/

	// senderCorrespondent 53a
	// senderCorrespondent/Option A
	private $_senderCorrespondentA;
	public $senderCorrespondentReqA;
	public $senderCorrespondentTextA;
	// senderCorrespondent/Option B
	private $_senderCorrespondentB;
	public $senderCorrespondentReqB;
	public $senderCorrespondentTextB;
	// senderCorrespondent/Option D
	private $_senderCorrespondentD;
	public $senderCorrespondentReqD;
	public $senderCorrespondentTextD;
	// 53a option type
	public $senderCorrespondentVariation = null;

	// receiverCorrespondent 54a
	private $_receiverCorrespondentA;
	public $receiverCorrespondentReqA;
	public $receiverCorrespondentTextA;
	private $_receiverCorrespondentB;
	public $receiverCorrespondentReqB;
	public $receiverCorrespondentTextB;
	private $_receiverCorrespondentD;
	public $receiverCorrespondentReqD;
	public $receiverCorrespondentTextD;
	public $receiverCorrespondentVariation = null;

	// intermediary 56a
	// intermediary 56A
	private $_intermediaryA;
	public $intermediaryReqA;
	public $intermediaryTextA;
	// intermediary 56D
	private $_intermediaryD;
	public $intermediaryReqD;
	public $intermediaryTextD;
	// 56a option type
	public $intermediaryVariation = null; /*self::INTERMEDIARY_56A*/

	// accountWithInstitution 57a
	// accountWithInstitution 57A
	private $_accountWithInstitutionA;
	public $accountWithInstitutionReqA;
	public $accountWithInstitutionTextA;
	// accountWithInstitution 57B
	private $_accountWithInstitutionB;
	public $accountWithInstitutionReqB;
	public $accountWithInstitutionTextB;
	// accountWithInstitution 57D
	private $_accountWithInstitutionD;
	public $accountWithInstitutionReqD;
	public $accountWithInstitutionTextD;
	// 57a option type
	public $accountWithInstitutionVariation = null; /*self::ACCOUNT_WITH_INSTITUTION_57A;*/

	// beneficiaryInstitution 58a
	// beneficiaryInstitution 58A
	protected $_beneficiaryInstitutionA;
	public $beneficiaryInstitutionReqA;
	public $beneficiaryInstitutionTextA;
	// beneficiaryInstitution 58D
	protected $_beneficiaryInstitutionD;
	public $beneficiaryInstitutionReqD;
	public $beneficiaryInstitutionTextD;
	// 58a option type
	public $beneficiaryInstitutionVariation = null; /*self::BENEFICIARY_INSTITUTION_58A;*/

	// senderToReceiverInformation 72
	public $senderToReceiverInformation;

	public function attributes()
	{
		return ArrayHelper::merge(parent::attributes(), [
			// transactionReferenceNumber 20
			//'transactionReferenceNumber',
			// relatedReference 21
			//'relatedReference',
			// dateCurrencySettledAmount 32A
			'currency',
			'settledAmount',
			'dateCurrencySettledAmount',
			// orderingInstitution 52A
			'orderingInstitutionA',
			// orderingInstitution 52D
			'orderingInstitutionD',
			// 52a option type
			//'orderingInstitutionVariation',

			/* 53a */
			'senderCorrespondentA',
			'senderCorrespondentB',
			'senderCorrespondentD',
			/* 54a */
			'receiverCorrespondentA',
			'receiverCorrespondentB',
			'receiverCorrespondentD',

			// senderCorrespondent 53B
			//'senderCorrespondent',
			// intermediary 56A
			'intermediaryA',
			// intermediary 56D
			'intermediaryD',
			// 56a option type
			//'intermediaryVariation',
			// accountWithInstitution 57A
			'accountWithInstitutionA',
			// accountWithInstitution 57B
			'accountWithInstitutionB',
			// accountWithInstitution 57D
			'accountWithInstitutionD',
			// 57a option type
			//'accountWithInstitutionVariation',
			// beneficiaryInstitution 58A
			'beneficiaryInstitutionA',
			// beneficiaryInstitution 58D
			'beneficiaryInstitutionD',
			// 58a option type
			//'beneficiaryInstitutionVariation',
			// senderToReceiverInformation 72
			//'senderToReceiverInformation',
		]);
	}

	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			// 20
			'transactionReferenceNumber'  => Yii::t('doc/mt', 'Transaction Reference Number'),
			// 21
			'relatedReference'            => Yii::t('doc/mt', 'Related Reference'),
			// 13C
			'timeIndication'              => Yii::t('doc/mt', 'Time indication'),
			// 32A
			'dateCurrencySettledAmount'   => Yii::t('doc/mt', 'Value Date / Currency / Interbank Settled Amount'),
			// 52a
			'orderingInstitution'         => Yii::t('doc/mt', 'Ordering Institution'),
			// Все опции 52a
			// 52a option A
			'orderingInstitutionA'        => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'orderingInstitutionReqA'     => Yii::t('doc/mt', 'Party Identifier'),
			'orderingInstitutionTextA'    => Yii::t('doc/mt', 'BIC'),
			// 52a option D
			'orderingInstitutionD'        => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'orderingInstitutionReqD'     => Yii::t('doc/mt', 'Party Identifier'),
			'orderingInstitutionTextD'    => Yii::t('doc/mt', 'Name & Address'),

			// 53a
			'senderCorrespondent'         => Yii::t('doc/mt', 'Sender\'s Correspondent'),
			// 53a option A
			'senderCorrespondentA'        => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'senderCorrespondentReqA'     => Yii::t('doc/mt', 'Party Identifier'),
			'senderCorrespondentTextA'    => Yii::t('doc/mt', 'BIC'),
			// 53a option B
			'senderCorrespondentB'        => Yii::t('doc/mt', 'Party Identifier / Location'),
			'senderCorrespondentReqB'     => Yii::t('doc/mt', 'Party Identifier'),
			'senderCorrespondentTextB'    => Yii::t('doc/mt', 'Location'),
			// 53a option D
			'senderCorrespondentD'        => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'senderCorrespondentReqD'     => Yii::t('doc/mt', 'Party Identifier'),
			'senderCorrespondentTextD'    => Yii::t('doc/mt', 'Name & Address'),

			// 54a
			'receiverCorrespondent'       => Yii::t('doc/mt', 'Receiver\'s Correspondent'),
			// 54a option A
			'receiverCorrespondentA'      => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'receiverCorrespondentReqA'   => Yii::t('doc/mt', 'Party Identifier'),
			'receiverCorrespondentTextA'  => Yii::t('doc/mt', 'BIC'),
			// 54a option B
			'receiverCorrespondentB'      => Yii::t('doc/mt', 'Party Identifier / Location'),
			'receiverCorrespondentReqB'   => Yii::t('doc/mt', 'Party Identifier'),
			'receiverCorrespondentTextB'  => Yii::t('doc/mt', 'Location'),
			// 54a option D
			'receiverCorrespondentD'      => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'receiverCorrespondentReqD'   => Yii::t('doc/mt', 'Party Identifier'),
			'receiverCorrespondentTextD'  => Yii::t('doc/mt', 'Name & Address'),

			// 56a
			'intermediary'                => Yii::t('doc/mt', 'Intermediary'),
			// Все опции 56a
			// 56a option A
			'intermediaryA'               => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'intermediaryReqA'            => Yii::t('doc/mt', 'Party Identifier'),
			'intermediaryTextA'           => Yii::t('doc/mt', 'BIC'),
			// 56a option D
			'intermediaryD'               => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'intermediaryReqD'            => Yii::t('doc/mt', 'Party Identifier'),
			'intermediaryTextD'           => Yii::t('doc/mt', 'Name & Address'),
			// 57a
			'accountWithInstitution'      => Yii::t('doc/mt', 'Account With Institution'),
			// Все опции 57a
			// 57a option A
			'accountWithInstitutionA'     => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'accountWithInstitutionReqA'  => Yii::t('doc/mt', 'Party Identifier'),
			'accountWithInstitutionTextA' => Yii::t('doc/mt', 'BIC'),
			// 57a option B
			'accountWithInstitutionB'     => Yii::t('doc/mt', 'Party Identifier / Location'),
			'accountWithInstitutionReqB'  => Yii::t('doc/mt', 'Party Identifier'),
			'accountWithInstitutionTextB' => Yii::t('doc/mt', 'Location'),
			// 57a option D
			'accountWithInstitutionD'     => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'accountWithInstitutionReqD'  => Yii::t('doc/mt', 'Party Identifier'),
			'accountWithInstitutionTextD' => Yii::t('doc/mt', 'Name & Address'),

			// 58a
			'beneficiaryInstitution'      => Yii::t('doc/mt', 'Beneficiary Institution'),
			// Все опции 58a
			// 58a option A
			'beneficiaryInstitutionA'     => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'beneficiaryInstitutionReqA'  => Yii::t('doc/mt', 'Party Identifier'),
			'beneficiaryInstitutionTextA' => Yii::t('doc/mt', 'BIC'),
			// 58a option D
			'beneficiaryInstitutionD'     => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'beneficiaryInstitutionReqD'  => Yii::t('doc/mt', 'Party Identifier'),
			'beneficiaryInstitutionTextD' => Yii::t('doc/mt', 'Name & Address'),
			// 72
			'senderToReceiverInformation' => Yii::t('doc/mt', 'Sender to Receiver Information'),
		]);
	}

	public function attributeTags()
	{
		return [
			'transactionReferenceNumber'  => '20',
			'relatedReference'            => '21',
			'timeIndication'              => '13C',
			'dateCurrencySettledAmount'   => '32A',
			// orderingInstitution/Option A
			'orderingInstitutionA'        => '52A',
			// orderingInstitution/Option D
			'orderingInstitutionD'        => '52D',
			'senderCorrespondentA'        => '53A',
			'senderCorrespondentB'        => '53B',
			'senderCorrespondentD'        => '53D',
			'receiverCorrespondentA'      => '54A',
			'receiverCorrespondentB'      => '54B',
			'receiverCorrespondentD'      => '54D',

			// 'receiverCorrespondent'	  => '54A',

			// intermediary/Option A
			'intermediaryA'               => '56A',
			// intermediary/Option D
			'intermediaryD'               => '56D',
			// accountWithInstitution/Option A
			'accountWithInstitutionA'     => '57A',
			// accountWithInstitution/Option B
			'accountWithInstitutionB'     => '57B',
			// accountWithInstitution/Option D
			'accountWithInstitutionD'     => '57D',
			// beneficiaryInstitution/Option A
			'beneficiaryInstitutionA'     => '58A',
			// beneficiaryInstitution/Option D
			'beneficiaryInstitutionD'     => '58D',
			'senderToReceiverInformation' => '72',
		];
	}

	public function rules()
	{
		return  ArrayHelper::merge(parent::rules(), [
			[
				[/*
					'transactionReferenceNumber',
					'relatedReference',
					'dateCurrencySettledAmount',
					// 'senderCorrespondent',
					// 'accountWithInstitution',
					'beneficiaryInstitution',
					'senderToReceiverInformation',
				 *
				 */
				],
				'required'
			],
			//Ограничение по длине
			[
				[
					'transactionReferenceNumber',
					'relatedReference',
				],
				'string',
				'max' => 16,
 			],
		]);
	}

	public function getOperationReference()
	{
		return $this->transactionReferenceNumber;
	}

	/**
	 * Функция преобразует переданную ей строку в набор атрибутов модели
	 * Разбирает ДатуВалютуСумму
	 * @todo Сделан "быстрый костыль" для работоспособности. Необходимо оперировать со словарем валют
	 * @param string $value
	 */
	public function setDateCurrencySettledAmount($value)
	{
		$this->_dateCurrencySettledAmount = $value;

		/**
		 * @todo Костыль для бага MaskedInput: {6,8}-дата, возвращаемая этим полем содержит
		 * год из 4-х цифр, что недопустимо стандартом SWIFT (д.б. 2 цифры). Пока оставлено
		 * так, чтобы переход из текстового вида в форму проходил безболезненно.
		 */
		if (
            preg_match(
                "/([0-9]{6,8})(" . implode('|', array_keys(MtBaseDocument::$currencyIsoCodes)) . ")([0-9,.]{1,15})/",
                $value,
                $found
            ) == 1
        ) {
			$this->date = $found[1];
			$this->_currency = $found[2];
			$this->settledAmount = $found[3];
		}
	}

	public function getDateCurrencySettledAmount()
	{
		if (is_null($this->_dateCurrencySettledAmount)) {
			if (!empty($this->date) && !empty($this->settledAmount)) {
				return preg_replace('/[^\d]+/', '', $this->date)
						. $this->_currency
						. strtr(sprintf("%.2f", $this->settledAmount), ['.' => ',']);
			}
		}

		return $this->_dateCurrencySettledAmount;
	}

	public function setSettledAmount($value)
	{
		$this->_settledAmount = (float) strtr($value, [',' => '.']);
	}

	public function getSettledAmount()
	{
		return $this->_settledAmount;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
    {
		return $this->_currency;
	}

	public function setCurrency($value)
    {
		$this->_currency = $value;
	}

	/**
	 * @return float
	 */
	public function getSum()
    {
		return (float)$this->settledAmount;
	}

	/**
	 * orderingInstitution[A|D]
	 * @return string
	 */
	public function getOrderingInstitutionA()
	{
		return trim($this->orderingInstitutionReqA . PHP_EOL . $this->orderingInstitutionTextA);
	}

	public function setOrderingInstitutionA($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->orderingInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_orderingInstitutionD))
			{
				// Теперь активна именно эта опция
				$this->orderingInstitutionVariation = self::ORDERING_INSTITUTION_52A;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->orderingInstitutionVariation == self::ORDERING_INSTITUTION_52A)
		{
			$this->_orderingInstitutionA = $value;

			$value = explode(PHP_EOL, $value);
			$this->orderingInstitutionReqA = array_shift($value);
			$this->orderingInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getSenderCorrespondentA()
    {
		return trim($this->senderCorrespondentReqA . PHP_EOL . $this->senderCorrespondentTextA);
	}

	/**
	 * @param $value
	 */
	function setSenderCorrespondentA($value)
    {
		if (empty($this->senderCorrespondentVariation)) {
			if (!is_null($value) && is_null($this->_senderCorrespondentD) && is_null($this->_senderCorrespondentB)) {
				$this->senderCorrespondentVariation = self::SENDER_CORRESPONDENT_53A;
			}
		}
		if ($this->senderCorrespondentVariation == self::SENDER_CORRESPONDENT_53A) {
			$this->_senderCorrespondentA    = $value;
			$value                          = explode(PHP_EOL, $value);
			$this->senderCorrespondentReqA  = array_shift($value);
			$this->senderCorrespondentTextA = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getSenderCorrespondentB()
    {
		return trim($this->senderCorrespondentReqB . PHP_EOL . $this->senderCorrespondentTextB);
	}

	/**
	 * @param $value
	 */
	function setSenderCorrespondentB($value)
    {
		if (empty($this->senderCorrespondentVariation)) {
			if (!is_null($value) && is_null($this->_senderCorrespondentD) && is_null($this->_senderCorrespondentA)) {
				$this->senderCorrespondentVariation = self::SENDER_CORRESPONDENT_53B;
			}
		}
		if ($this->senderCorrespondentVariation == self::SENDER_CORRESPONDENT_53B) {
			$this->_senderCorrespondentB    = $value;
			$value                          = explode(PHP_EOL, $value);
			$this->senderCorrespondentReqB  = array_shift($value);
			$this->senderCorrespondentTextB = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getSenderCorrespondentD()
    {
		return trim($this->senderCorrespondentReqD . PHP_EOL . $this->senderCorrespondentTextD);
	}

	/**
	 * @param $value
	 */
	function setSenderCorrespondentD($value)
    {
		if (empty($this->senderCorrespondentVariation)) {
			if (!is_null($value) && is_null($this->_senderCorrespondentA) && is_null($this->_senderCorrespondentB)) {
				$this->senderCorrespondentVariation = self::SENDER_CORRESPONDENT_53D;
			}
		}
		if ($this->senderCorrespondentVariation == self::SENDER_CORRESPONDENT_53D) {
			$this->_senderCorrespondentD    = $value;
			$value                          = explode(PHP_EOL, $value);
			$this->senderCorrespondentReqD  = array_shift($value);
			$this->senderCorrespondentTextD = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getReceiverCorrespondentA()
    {
		return trim($this->receiverCorrespondentReqA . PHP_EOL . $this->receiverCorrespondentTextA);
	}

	/**
	 * @param $value
	 */
	function setReceiverCorrespondentA($value)
    {
		if (empty($this->receiverCorrespondentVariation)) {
			if (!is_null($value) && is_null($this->_receiverCorrespondentD) && is_null($this->_receiverCorrespondentB)) {
				$this->receiverCorrespondentVariation = 'A';
			}
		}
		if ($this->receiverCorrespondentVariation == 'A') {
			$this->_receiverCorrespondentA    = $value;
			$value                            = explode(PHP_EOL, $value);
			$this->receiverCorrespondentReqA  = array_shift($value);
			$this->receiverCorrespondentTextA = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getReceiverCorrespondentB()
    {
		return trim($this->receiverCorrespondentReqB . PHP_EOL . $this->receiverCorrespondentTextB);
	}

	/**
	 * @param $value
	 */
	function setReceiverCorrespondentB($value)
    {
		if (empty($this->receiverCorrespondentVariation)) {
			if (!is_null($value) && is_null($this->_receiverCorrespondentD) && is_null($this->_receiverCorrespondentA)) {
				$this->receiverCorrespondentVariation = 'B';
			}
		}
		if ($this->receiverCorrespondentVariation == 'B') {
			$this->_receiverCorrespondentB    = $value;
			$value                            = explode(PHP_EOL, $value);
			$this->receiverCorrespondentReqB  = array_shift($value);
			$this->receiverCorrespondentTextB = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getReceiverCorrespondentD()
    {
		return trim($this->receiverCorrespondentReqD . PHP_EOL . $this->receiverCorrespondentTextD);
	}

	/**
	 * @param $value
	 */
	function setReceiverCorrespondentD($value)
    {
		if (empty($this->receiverCorrespondentVariation)) {
			if (!is_null($value) && is_null($this->_receiverCorrespondentA) && is_null($this->_receiverCorrespondentB)) {
				$this->receiverCorrespondentVariation = 'D';
			}
		}
		if ($this->receiverCorrespondentVariation == 'D') {
			$this->_receiverCorrespondentD    = $value;
			$value                            = explode(PHP_EOL, $value);
			$this->receiverCorrespondentReqD  = array_shift($value);
			$this->receiverCorrespondentTextD = join(PHP_EOL, $value);
		}
	}

	public function getOrderingInstitutionD()
	{
		return trim($this->orderingInstitutionReqD . PHP_EOL . $this->orderingInstitutionTextD);
	}

	public function setOrderingInstitutionD($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->orderingInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_orderingInstitutionA))
			{
				// Теперь активна именно эта опция
				$this->orderingInstitutionVariation = self::ORDERING_INSTITUTION_52D;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->orderingInstitutionVariation == self::ORDERING_INSTITUTION_52D)
		{
			$this->_orderingInstitutionD = $value;

			$value = explode(PHP_EOL, $value);
			$this->orderingInstitutionReqD = array_shift($value);
			$this->orderingInstitutionTextD = join(PHP_EOL, $value);
		}
	}

    /**
     * intermediary[A|D]
     */
	public function getIntermediaryA()
	{
		return trim($this->intermediaryReqA . PHP_EOL . $this->intermediaryTextA);
	}

	public function setIntermediaryA($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->intermediaryVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_intermediaryD))
			{
				// Теперь активна именно эта опция
				$this->intermediaryVariation = self::INTERMEDIARY_56A;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->intermediaryVariation == self::INTERMEDIARY_56A)
		{
			$this->_intermediaryA = $value;
			$value = explode(PHP_EOL, $value);
			$this->intermediaryReqA = array_shift($value);
			$this->intermediaryTextA = join(PHP_EOL, $value);
		}
	}

	public function getIntermediaryD()
	{
		return trim($this->intermediaryReqD . PHP_EOL . $this->intermediaryTextD);
	}

	public function setIntermediaryD($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->intermediaryVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_intermediaryA))
			{
				// Теперь активна именно эта опция
				$this->intermediaryVariation = self::INTERMEDIARY_56D;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->intermediaryVariation == self::INTERMEDIARY_56D)
		{
			$this->_intermediaryD = $value;
			$value = explode(PHP_EOL, $value);
			$this->intermediaryReqD = array_shift($value);
			$this->intermediaryTextD = join(PHP_EOL, $value);
		}
	}

    /*
     * accountWithInstitution[A|B|D]
     */
	public function getAccountWithInstitutionA()
	{
		return trim($this->accountWithInstitutionReqA . PHP_EOL . $this->accountWithInstitutionTextA);
	}

	public function setAccountWithInstitutionA($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->accountWithInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_accountWithInstitutionB) && is_null($this->_accountWithInstitutionD))
			{
				// Теперь активна именно эта опция
				$this->accountWithInstitutionVariation = self::ACCOUNT_WITH_INSTITUTION_57A;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->accountWithInstitutionVariation == self::ACCOUNT_WITH_INSTITUTION_57A)
		{
			$this->_accountWithInstitutionA = $value;
			$value = explode(PHP_EOL, $value);
			$this->accountWithInstitutionReqA = array_shift($value);
			$this->accountWithInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	public function getAccountWithInstitutionB()
	{
		return trim($this->accountWithInstitutionReqB . PHP_EOL . $this->accountWithInstitutionTextB);
	}

	public function setAccountWithInstitutionB($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->accountWithInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_accountWithInstitutionA) && is_null($this->_accountWithInstitutionD))
			{
				// Теперь активна именно эта опция
				$this->accountWithInstitutionVariation = self::ACCOUNT_WITH_INSTITUTION_57B;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->accountWithInstitutionVariation == self::ACCOUNT_WITH_INSTITUTION_57B)
		{
			$this->_accountWithInstitutionB = $value;
			$value = explode(PHP_EOL, $value);
			$this->accountWithInstitutionReqB = array_shift($value);
			$this->accountWithInstitutionTextB = join(PHP_EOL, $value);
		}
	}

	public function getAccountWithInstitutionD()
	{
		return trim($this->accountWithInstitutionReqD . PHP_EOL . $this->accountWithInstitutionTextD);
	}

	public function setAccountWithInstitutionD($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->accountWithInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_accountWithInstitutionA) && is_null($this->_accountWithInstitutionB))
			{
				// Теперь активна именно эта опция
				$this->accountWithInstitutionVariation = self::ACCOUNT_WITH_INSTITUTION_57D;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->accountWithInstitutionVariation == self::ACCOUNT_WITH_INSTITUTION_57D)
		{
			$this->_accountWithInstitutionD = $value;
			$value = explode(PHP_EOL, $value);
			$this->accountWithInstitutionReqD = array_shift($value);
			$this->accountWithInstitutionTextD = join(PHP_EOL, $value);
		}
	}

    /*
     * beneficiaryInstitution[A|D];
     */
	public function getBeneficiaryInstitutionA()
	{
		return trim($this->beneficiaryInstitutionReqA . PHP_EOL . $this->beneficiaryInstitutionTextA);
	}

	public function setBeneficiaryInstitutionA($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->beneficiaryInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_beneficiaryInstitutionD))
			{
				// Теперь активна именно эта опция
				$this->beneficiaryInstitutionVariation = self::BENEFICIARY_INSTITUTION_58A;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->beneficiaryInstitutionVariation == self::BENEFICIARY_INSTITUTION_58A)
		{
			$this->_beneficiaryInstitutionA = $value;
			$value = explode(PHP_EOL, $value);
			$this->beneficiaryInstitutionReqA = array_shift($value);
			$this->beneficiaryInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	public function getBeneficiaryInstitutionD()
	{
		return trim($this->beneficiaryInstitutionReqD . PHP_EOL . $this->beneficiaryInstitutionTextD);
	}

	public function setBeneficiaryInstitutionD($value)
	{
		// Если вход осуществлен из текстового представления (тип опции не определен)
		if (empty($this->beneficiaryInstitutionVariation))
		{
			// Если значение для данной опции существует и значения альтернативных
			// опций еще не установлены
			if (!is_null($value) && is_null($this->_beneficiaryInstitutionA))
			{
				// Теперь активна именно эта опция
				$this->beneficiaryInstitutionVariation = self::BENEFICIARY_INSTITUTION_58D;
			}
		}
		// Если требуется заполнить данными именно эту опцию
		if ($this->beneficiaryInstitutionVariation == self::BENEFICIARY_INSTITUTION_58D)
		{
			$this->_beneficiaryInstitutionD = $value;
			$value = explode(PHP_EOL, $value);
			$this->beneficiaryInstitutionReqD = array_shift($value);
			$this->beneficiaryInstitutionTextD = join(PHP_EOL, $value);
		}
	}

	public function attributeReadableTags()
	{
		return [
			'timeIndication'              => '13C',
			'transactionReferenceNumber'  => '20',
			'relatedReference'            => '21',
			'dateCurrencySettledAmount'   => '32A',
			'orderingInstitution'         => [
				// orderingInstitution/Option A
				'orderingInstitutionA' => '52A',
				// orderingInstitution/Option D
				'orderingInstitutionD' => '52D',
			],
			'senderCorrespondent'         => [
				// senderCorrespondent/Option A
				'senderCorrespondentA' => '53A',
				// senderCorrespondent/Option B
				'senderCorrespondentB' => '53B',
				// senderCorrespondent/Option D
				'senderCorrespondentD' => '53D',
			],
			'receiverCorrespondent'       => [
				// senderCorrespondent/Option A
				'receiverCorrespondentA' => '54A',
				// senderCorrespondent/Option B
				'receiverCorrespondentB' => '54B',
				// senderCorrespondent/Option D
				'receiverCorrespondentD' => '54D',
			],
			'intermediary'                => [
				// intermediary/Option A
				'intermediaryA' => '56A',
				// intermediary/Option D
				'intermediaryD' => '56D',
			],
			'accountWithInstitution'      => [
				// accountWithInstitution/Option A
				'accountWithInstitutionA' => '57A',
				// accountWithInstitution/Option B
				'accountWithInstitutionB' => '57B',
				// accountWithInstitution/Option D
				'accountWithInstitutionD' => '57D',
			],
			'beneficiaryInstitution'      => [
				// beneficiaryInstitution/Option A
				'beneficiaryInstitutionA' => '58A',
				// beneficiaryInstitution/Option D
				'beneficiaryInstitutionD' => '58D',
			],
			'senderToReceiverInformation' => '72',
		];
	}

}
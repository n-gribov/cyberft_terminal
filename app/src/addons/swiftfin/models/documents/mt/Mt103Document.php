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
class Mt103Document extends MtBaseDocument {

	const DOC_TYPE = '103';

	const ORDERING_CUSTOMER_50A = 'A';
	const ORDERING_CUSTOMER_50F = 'F';
	const ORDERING_CUSTOMER_50K = 'K';

	const ORDERING_INSTITUTION_52A = 'A';
	const ORDERING_INSTITUTION_52D = 'D';

	const SENDER_CORRESPONDENT_53A = 'A';
	const SENDER_CORRESPONDENT_53B = 'B';
	const SENDER_CORRESPONDENT_53D = 'D';

	const RECEIVER_CORRESPONDENT_54A = 'A';
	const RECEIVER_CORRESPONDENT_54B = 'B';
	const RECEIVER_CORRESPONDENT_54D = 'D';

	const RECEIVER_CORRESPONDENT_55A = 'A';
	const RECEIVER_CORRESPONDENT_55B = 'B';
	const RECEIVER_CORRESPONDENT_55D = 'D';

	const INTERMEDIARY_INSTITUTION_56A = 'A';
	const INTERMEDIARY_INSTITUTION_56C = 'C'; // Не используется в SWIFT-RUR
	const INTERMEDIARY_INSTITUTION_56D = 'D';

	const ACCOUNT_WITH_INSTITUTION_57A = 'A';
	const ACCOUNT_WITH_INSTITUTION_57B = 'B'; // Не используется в SWIFT-RUR
	const ACCOUNT_WITH_INSTITUTION_57C = 'C'; // Не используется в SWIFT-RUR
	const ACCOUNT_WITH_INSTITUTION_57D = 'D';

	const BENEFICIARY_CUSTOMER_59A = 'A';
	const BENEFICIARY_CUSTOMER_59NLO = 'NLO';

	// 20
	public $senderReference;
	// 23B
	public $bankOperationCode;
	// 23E
	public $instructionCode;
	// 26T
	public $transactionTypeCode;
	// 32A
	private $_dateCurrencySettledAmount;
	public $date;
	protected $_currency = 'RUB';
	protected $_settledAmount;

	// orderingCustomer 50a
	// private $_orderingCustomer;
	// orderingCustomer/Option A
	private $_orderingCustomerA;
	public $orderingCustomerReqA;
	public $orderingCustomerTextA;
	// orderingCustomer/Option F
	private $_orderingCustomerF;
	public $orderingCustomerReqF;
	public $orderingCustomerTextF;
	// orderingCustomer/Option K
	private $_orderingCustomerK;
	public $orderingCustomerReqK;
	public $orderingCustomerTextK;
	// 50a option type
	public $orderingCustomerVariation = null; /*self::ORDERING_CUSTOMER_50A;*/

	// orderingInstitution 52a
	// private $_orderingInstitution;
	// orderingInstitution/Option A
	private $_orderingInstitutionA;
	public $orderingInstitutionReqA;
	public $orderingInstitutionTextA;
	// orderingInstitution/Option D
	private $_orderingInstitutionD;
	public $orderingInstitutionReqD;
	public $orderingInstitutionTextD;
	// 52a option type
	public $orderingInstitutionVariation = null; /*self::ORDERING_INSTITUTION_52A;*/

	/* Replaced by orderingInstitution
	private $_originatorBank;
	public $originatorBankReq;
	public $originatorBankText;
	 */

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

	// thirdReimbursementInstitution 55a
	private $_thirdReimbursementInstitutionA;
	public $thirdReimbursementInstitutionReqA;
	public $thirdReimbursementInstitutionTextA;
	private $_thirdReimbursementInstitutionB;
	public $thirdReimbursementInstitutionReqB;
	public $thirdReimbursementInstitutionTextB;
	private $_thirdReimbursementInstitutionD;
	public $thirdReimbursementInstitutionReqD;
	public $thirdReimbursementInstitutionTextD;
	public $thirdReimbursementInstitutionVariation = null;

	// 53B
//	private $_beneficiaryBank;
//	public $beneficiaryBankReq;
//	public $beneficiaryBankText;

	// intermediaryInstitution 56a
	// private $_intermediaryInstitution;
	// intermediaryInstitution/Option A
	private $_intermediaryInstitutionA;
	public $intermediaryInstitutionReqA;
	public $intermediaryInstitutionTextA;
	// intermediaryInstitution/Option D
	private $_intermediaryInstitutionD;
	public $intermediaryInstitutionReqD;
	public $intermediaryInstitutionTextD;
	// 56a option type
	public $intermediaryInstitutionVariation = null; /*self::INTERMEDIARY_INSTITUTION_56A;*/

	// accountWithInstitution 57a
	// private $_accountWithInstitution;
	// accountWithInstitution/Option A
	private $_accountWithInstitutionA;
	public $accountWithInstitutionReqA;
	public $accountWithInstitutionTextA;
	// accountWithInstitution/Option D
	private $_accountWithInstitutionD;
	public $accountWithInstitutionReqD;
	public $accountWithInstitutionTextD;
	// 57a option type
	public $accountWithInstitutionVariation = null; /*self::ACCOUNT_WITH_INSTITUTION_57A;*/

	// beneficiaryCustomer 59a
	// private $_beneficiaryCustomer;
	// beneficiaryCustomer/Option A
	private $_beneficiaryCustomerA;
	public $beneficiaryCustomerReqA;
	public $beneficiaryCustomerTextA;
	// beneficiaryCustomer/No letter option
	private $_beneficiaryCustomerNLO;
	public $beneficiaryCustomerReqNLO;
	public $beneficiaryCustomerTextNLO;
	// 59a option type
	public $beneficiaryCustomerVariation = null; /*self::BENEFICIARY_CUSTOMER_59A;*/
	// 70
	public $remittanceInformation;
	// 71A
	public $detailsOfCharges;
	// 72
	public $senderToReceiverInformation;
	// 77B
	public $regulatoryReporting;

//	public $instructionCode;
	private $_currencyInstructedAmount;
	public $instructedCurrency;
	protected $_instructedAmount;
	public $exchangeRate;
	public $sendingInstitution;
	public $senderCharges;
	public $receiverCharges;
	public $timeIndication;
	public $envelopeContents;


	public function attributes()
	{
		return ArrayHelper::merge(parent::attributes(), [
			'currency',
			'settledAmount',
			'dateCurrencySettledAmount',

			'orderingCustomerA',
			'orderingCustomerF',
			'orderingCustomerK',

			'orderingInstitutionA',
			'orderingInstitutionD',

			'senderCorrespondentA',
			'senderCorrespondentB',
			'senderCorrespondentD',

			'receiverCorrespondentA',
			'receiverCorrespondentB',
			'receiverCorrespondentD',

			'thirdReimbursementInstitutionA',
			'thirdReimbursementInstitutionB',
			'thirdReimbursementInstitutionD',
			/*
			'originatorBank',
			 */
			'intermediaryInstitutionA',
			'intermediaryInstitutionD',
			'accountWithInstitutionA',
			'accountWithInstitutionD',
			/*
			'beneficiaryBank',
			 */
			'beneficiaryCustomerA',
			'beneficiaryCustomerNLO',
			'currencyInstructedAmount',
			'instructedCurrency',
			'instructedAmount',
			'senderCharges',
		]);
	}

	public function attributeLabels() {
		return [
			'data'                       => Yii::t('doc/mt', 'Document data'),

			'senderReference'            => Yii::t('doc/mt', 'Sender\'s reference'),
			'timeIndication'             => Yii::t('doc/mt', 'Time indication'),
			'bankOperationCode'          => Yii::t('doc/mt', 'Bank operation code'),
			'instructionCode'            => Yii::t('doc/mt', 'Instruction code'),
			'transactionTypeCode'        => Yii::t('doc/mt', 'Transaction type code'),

			'dateCurrencySettledAmount'  => Yii::t('doc/mt', 'Value Date / Currency / Interbank Settled Amount'),
			'currencyInstructedAmount'   => Yii::t('doc/mt', 'Currency / Instructed Amount'),
			'exchangeRate'               => Yii::t('doc/mt', 'Exchange Rate'),
			// 50a
			'orderingCustomer'           => Yii::t('doc/mt', 'Ordering Customer'),
			// Все опции 50a
			// 50a option A
			'orderingCustomerA'          => Yii::t('doc/mt', 'Account / BIC/BEI'),
			'orderingCustomerReqA'       => Yii::t('doc/mt', 'Account'),
			'orderingCustomerTextA'      => Yii::t('doc/mt', 'BIC/BEI'),
			// 50a option F
			'orderingCustomerF'          => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'orderingCustomerReqF'       => Yii::t('doc/mt', 'Party Identifier'),
			'orderingCustomerTextF'      => Yii::t('doc/mt', 'Name & Address'),
			// 50a option K
			'orderingCustomerK'          => Yii::t('doc/mt', 'Account / Name & Address'),
			'orderingCustomerReqK'       => Yii::t('doc/mt', 'Account'),
			'orderingCustomerTextK'      => Yii::t('doc/mt', 'Name & Address'),

			'sendingInstitution'         => Yii::t('doc/mt', 'Sending Institution'),
			// 52a
			'orderingInstitution'        => Yii::t('doc/mt', 'Ordering Institution'),
			// Все опции 52a
			// 52a option A
			'orderingInstitutionA'       => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'orderingInstitutionReqA'    => Yii::t('doc/mt', 'Party Identifier'),
			'orderingInstitutionTextA'   => Yii::t('doc/mt', 'BIC'),
			// 52a option D
			'orderingInstitutionD'       => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'orderingInstitutionReqD'    => Yii::t('doc/mt', 'Party Identifier'),
			'orderingInstitutionTextD'   => Yii::t('doc/mt', 'Name & Address'),
			/*
			'originatorBank'				=> Yii::t('doc/mt', 'Originator\'s Bank'),
			 */

			// 53a
			'senderCorrespondent'        => Yii::t('doc/mt', 'Sender\'s Correspondent'),
			// 53a option A
			'senderCorrespondentA'       => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'senderCorrespondentReqA'    => Yii::t('doc/mt', 'Party Identifier'),
			'senderCorrespondentTextA'   => Yii::t('doc/mt', 'BIC'),
			// 53a option B
			'senderCorrespondentB'       => Yii::t('doc/mt', 'Party Identifier / Location'),
			'senderCorrespondentReqB'    => Yii::t('doc/mt', 'Party Identifier'),
			'senderCorrespondentTextB'   => Yii::t('doc/mt', 'Location'),
			// 53a option D
			'senderCorrespondentD'       => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'senderCorrespondentReqD'    => Yii::t('doc/mt', 'Party Identifier'),
			'senderCorrespondentTextD'   => Yii::t('doc/mt', 'Name & Address'),

			// 54a
			'receiverCorrespondent'      => Yii::t('doc/mt', 'Receiver\'s Correspondent'),
			// 54a option A
			'receiverCorrespondentA'     => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'receiverCorrespondentReqA'  => Yii::t('doc/mt', 'Party Identifier'),
			'receiverCorrespondentTextA' => Yii::t('doc/mt', 'BIC'),
			// 54a option B
			'receiverCorrespondentB'     => Yii::t('doc/mt', 'Party Identifier / Location'),
			'receiverCorrespondentReqB'  => Yii::t('doc/mt', 'Party Identifier'),
			'receiverCorrespondentTextB' => Yii::t('doc/mt', 'Location'),
			// 54a option D
			'receiverCorrespondentD'     => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'receiverCorrespondentReqD'  => Yii::t('doc/mt', 'Party Identifier'),
			'receiverCorrespondentTextD' => Yii::t('doc/mt', 'Name & Address'),

			// 55a
			'thirdReimbursementInstitution'      => Yii::t('doc/mt', 'Third Reimbursement Institution'),
			// 55a option A
			'thirdReimbursementInstitutionA'     => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'thirdReimbursementInstitutionReqA'  => Yii::t('doc/mt', 'Party Identifier'),
			'thirdReimbursementInstitutionTextA' => Yii::t('doc/mt', 'BIC'),
			// 55a option B
			'thirdReimbursementInstitutionB'     => Yii::t('doc/mt', 'Party Identifier / Location'),
			'thirdReimbursementInstitutionReqB'  => Yii::t('doc/mt', 'Party Identifier'),
			'thirdReimbursementInstitutionTextB' => Yii::t('doc/mt', 'Location'),
			// 55a option D
			'thirdReimbursementInstitutionD'     => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'thirdReimbursementInstitutionReqD'  => Yii::t('doc/mt', 'Party Identifier'),
			'thirdReimbursementInstitutionTextD' => Yii::t('doc/mt', 'Name & Address'),

			// 56a
			'intermediaryInstitution'      => Yii::t('doc/mt', 'Intermediary Institution'),
			// Все опции 56a
			// 56a option A
			'intermediaryInstitutionA'     => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'intermediaryInstitutionReqA'  => Yii::t('doc/mt', 'Party Identifier'),
			'intermediaryInstitutionTextA' => Yii::t('doc/mt', 'BIC'),
			// 56a option D
			'intermediaryInstitutionD'     => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'intermediaryInstitutionReqD'  => Yii::t('doc/mt', 'Party Identifier'),
			'intermediaryInstitutionTextD' => Yii::t('doc/mt', 'Name & Address'),
			// 57a
			'accountWithInstitution'       => Yii::t('doc/mt', 'Account With Institution'),
			// Все опции 57a
			// 57a option A
			'accountWithInstitutionA'      => Yii::t('doc/mt', 'Party Identifier / BIC'),
			'accountWithInstitutionReqA'   => Yii::t('doc/mt', 'Party Identifier'),
			'accountWithInstitutionTextA'  => Yii::t('doc/mt', 'BIC'),
			// 57a option D
			'accountWithInstitutionD'      => Yii::t('doc/mt', 'Party Identifier / Name & Address'),
			'accountWithInstitutionReqD'   => Yii::t('doc/mt', 'Party Identifier'),
			'accountWithInstitutionTextD'  => Yii::t('doc/mt', 'Name & Address'),
			/*
			'beneficiaryBank'				=> Yii::t('doc/mt', 'Beneficiary Bank'),
			 */
			// 59a
			'beneficiaryCustomer'          => Yii::t('doc/mt', 'Beneficiary Customer'),
			// Все опции 59a
			// 59a option A
			'beneficiaryCustomerA'         => Yii::t('doc/mt', 'Account / BIC/BEI'),
			'beneficiaryCustomerReqA'      => Yii::t('doc/mt', 'Account'),
			'beneficiaryCustomerTextA'     => Yii::t('doc/mt', 'BIC/BEI'),
			// 59a No letter option
			'beneficiaryCustomerNLO'       => Yii::t('doc/mt', 'Account / Name & Address'),
			'beneficiaryCustomerReqNLO'    => Yii::t('doc/mt', 'Account'),
			'beneficiaryCustomerTextNLO'   => Yii::t('doc/mt', 'Name & Address'),

			'remittanceInformation'        => Yii::t('doc/mt', 'Remittance Information'),
			'detailsOfCharges'             => Yii::t('doc/mt', 'Details of Charges'),
			'senderCharges'                => Yii::t('doc/mt', 'Sender\'s Charges'),
			'receiverCharges'              => Yii::t('doc/mt', 'Receiver\'s Charges'),
			'senderToReceiverInformation'  => Yii::t('doc/mt', 'Sender to Receiver Information'),
			'regulatoryReporting'          => Yii::t('doc/mt', 'Regulatory Reporting'),
			'envelopeContents'             => Yii::t('doc/mt', 'Envelope Contents'),
		];
	}

	public function attributeTags()
	{
		return [
			'senderReference'				=> '20',
			'timeIndication'				=> '13C',
			'bankOperationCode'				=> '23B',
			'instructionCode'				=> '23E',
			'transactionTypeCode'			=> '26T',
			'dateCurrencySettledAmount'		=> '32A',
			'currencyInstructedAmount'		=> '33B',
			'exchangeRate'					=> '36',
			// orderingCustomer/Option A
			'orderingCustomerA'				=> '50A',
			// orderingCustomer/Option F
			'orderingCustomerF'				=> '50F',
			// orderingCustomer/Option K
			'orderingCustomerK'				=> '50K',
			'sendingInstitution'			=> '51A',
			// orderingInstitution/Option A
			'orderingInstitutionA'			=> '52A',
			// orderingInstitution/Option D
			'orderingInstitutionD'			=> '52D',
			/* Replaced by orderingInstitution A/D
			'originatorBank'				=> '52D',
			 */
			'senderCorrespondentA'			=> '53A',
			'senderCorrespondentB'			=> '53B',
			'senderCorrespondentD'			=> '53D',
			'receiverCorrespondentA'		=> '54A',
			'receiverCorrespondentB'		=> '54B',
			'receiverCorrespondentD'		=> '54D',
			'thirdReimbursementInstitutionA'=> '55A',
			'thirdReimbursementInstitutionB'=> '55B',
			'thirdReimbursementInstitutionD'=> '55D',
			// intermediaryInstitution/Option A
			'intermediaryInstitutionA'		=> '56A',
			// intermediaryInstitution/Option D
			'intermediaryInstitutionD'		=> '56D',
			// accountWithInstitution/Option A
			'accountWithInstitutionA'		=> '57A',
			// accountWithInstitution/Option D
			'accountWithInstitutionD'		=> '57D',
			/*
			'beneficiaryBank'				=> '57D',
			 */
			// beneficiaryCustomer/Option A
			'beneficiaryCustomerA'			=> '59A',
			// beneficiaryCustomer/No letter option
			'beneficiaryCustomerNLO'		=> '59',
			'remittanceInformation'			=> '70',
			'detailsOfCharges'				=> '71A',
			'senderCharges'					=> '71F',
			'receiverCharges'				=> '71G',
			'senderToReceiverInformation'	=> '72',
			'regulatoryReporting'			=> '77B',
			'envelopeContents'			    => '77T',
		];
	}

	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [
			[
				[
					/*
					'senderReference',
					'bankOperationCode',
					'dateCurrencySettledAmount',
					//'orderingCustomerA',
					//'orderingCustomerF',
					//'orderingCustomerK',
					'beneficiaryCustomer',
					'detailsOfCharges',
					 */
				],
				'required'
			],

			//Ограничение по длине
			[
				[
					'orderingCustomerTextA', 'orderingCustomerTextF', 'orderingCustomerTextK',
					/* Replaced by orderingInstitutionText[A|D]
					'originatorBankText',
					 */
					'orderingInstitutionTextA',	'orderingInstitutionTextD',
					'senderCorrespondentTextA',	'senderCorrespondentTextB',	'senderCorrespondentTextD',
					'intermediaryInstitutionTextA', 'intermediaryInstitutionTextD',
					'accountWithInstitutionTextA', 'accountWithInstitutionTextD',
					/* Replaced by accountWithInstitutionText[A|D]
					'beneficiaryBankText',
					 */
					'beneficiaryCustomerTextA', 'beneficiaryCustomerTextNLO',
					'remittanceInformation',
					'regulatoryReporting'
				],
				'string',
				'max' => 255,
 			],

			['bankOperationCode', 'in', 'range' => array_keys($this->getBankOperationCodeLabels())],
			['detailsOfCharges', 'in', 'range' => array_keys($this->getDetailsOfChargesLabels())],
		]);
	}

	/**
	 * @return array
	 */
	public function getDetailsOfChargesLabels() {
		return [
			'OUR' => 'OUR',
			'SHA' => 'SHA',
			'BEN' => 'BEN',
		];
	}

	/**
	 * @return array
	 */
	public function getInstructionCodeLabels() {
		return [
			'SDVA' => 'SDVA',
			'INTC' => 'INTC',
			'REPA' => 'REPA',
			'CORT' => 'CORT',
			'HOLD' => 'HOLD',
			'CHQB' => 'CHQB',
			'PHOB' => 'PHOB',
			'TELB' => 'TELB',
			'PHON' => 'PHON',
			'TELE' => 'TELE',
			'PHOI' => 'PHOI',
			'TELI' => 'TELI',
		];
	}

	public function getBankOperationCodeLabels(){
		return [
			'CRED' => 'CRED',
			'CRTS' => 'CRTS',
			'SPAY' => 'SPAY',
			'SPRI' => 'SPRI',
			'SSTD' => 'SSTD',
		];
	}

	/**
	 * @return array
	 */
//	public function getTransactionTypeCodeLabels() {
//		return [
//			'S01' => 'налогоплательщик (плательщик сборов) – юридическое лицо',
//			'S02' => 'налоговый агент',
//			'S03' => "организация федеральной почтовой связи, оформившая расчетный документ на
//перечислении в бюджетную систему Российской Федерации налоов, сборов, таможенных и
//иных платежей от внешнеэкономической дятельности и иных платежей, уплачиваемых
//физическими лицами;",
//			'S04' => 'налоговый орган',
//			'S05' => 'территориальные органы Федеральной службы судебных приставов',
//			'S06' => 'участник внешнеэкономической деятельности – юридическое лицо',
//			'S07' => 'таможенный орган',
//			'S08' => 'плательщик иных, осуществляющий перечисление платежей в бюджетную систему
//Российской Федерации;(кроме платежей, администрируемых налоговыми органами)',
//			'S09' => 'налогоплательщик (плательщик сборов) - индивидуальный предприниматель',
//			'S10' => "налогоплательщик (плательщик сборов) – нотариус, занимающийся частной практикой;",
//			'S11' => "налогоплательщик (плательщик сборов) - адвокат, учредивший адвокатский кабинет;",
//			'S12' => "налогоплательщик (плательщик сборов) - глава крестьянского (фермерского) хозяйства;",
//			'S13' => "налогоплательщик (плательщик сборов) - иное физическое лицо - клиент банка (владелец счета)",
//			'S14' => "налогоплательщик (плательщик сборов), производящий выплаты физическим лицам \n(подпункт 1 пункта 1 статьи 235 Налогового кодекса Российской Федерации)",
//			'S15' => "кредитная организация (ее филиал), оформившая расчетный документ на общую сумму на\nперечисление в бюджетную систему Российской Федерации налогов, сборов, таможенных \n платежей и иных платежей, уплачиваемых физическими лицами без открытия банковского \nсчета.",
//			'S16' => "участник внешнеэкономической деятельности - физическое лицо",
//			'S17' => "участник внешнеэкономической деятельности - индивидуальный предприниматель",
//			'S18' => "плательщик таможенных платежей, не являющийся декларантом, на которого \nзаконодательством Российской Федерации возложена обязанность по уплате таможенных платежей",
//			'S19' => "организации и их филиалы (далее - организации), оформившие расчетный документ на \nперечисление на счет органа Федерального казначейства денежных средств, удержанных из
//заработка (дохода) должника - физического лица в счет погашения задолженности по \nтаможенным платежам на основании исполнительного документа, направленного в организацию в установленном порядке",
//			'S20' => "кредитная организация (ее филиал), оформившая расчетный документ по каждому\n
//платежу физического лица на перечисление таможенных платежей, уплачиваемых физическими лицами без открытия банковского счета."		];
//	}

	public function getOperationReference()
	{
		return $this->senderReference;
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
			$this->currency = $found[2];
			$this->settledAmount = $found[3];
		}
	}

	public function getDateCurrencySettledAmount()
	{
		if (is_null($this->_dateCurrencySettledAmount)) {
			if (!empty($this->date) && !empty($this->settledAmount)) {
				return preg_replace('/[^\d]+/', '', $this->date)
						. $this->currency
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
	 * @param string $value
	 */
	public function setCurrencyInstructedAmount($value)
    {
		$this->_currencyInstructedAmount = $value;
		if (
            preg_match(
                "/(" . implode('|', array_keys(MtBaseDocument::$currencyIsoCodes)) . ")([0-9,.]{1,15})/",
                $value,
                $found
            ) == 1
        ) {
			$this->instructedCurrency = $found[1];
			$this->instructedAmount   = (float) strtr($found[2], [',' => '.']);
		}
	}

	/**
	 * @return string
	 */
	public function getCurrencyInstructedAmount()
    {
		if ($this->instructedCurrency && $this->instructedAmount) {
			return $this->instructedCurrency . strtr(sprintf("%.2f", $this->instructedAmount), ['.' => ',']);
		}

        return null;
	}

	public function setInstructedAmount($value)
	{
		$this->_instructedAmount = (float) strtr($value, [',' => '.']);
	}

	public function getInstructedAmount()
	{
		return $this->_instructedAmount;
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

	public function setOrderingCustomerA($value)
	{
		if (empty($this->orderingCustomerVariation))
		{
			if (!is_null($value) && is_null($this->_orderingCustomerF) && is_null($this->_orderingCustomerK))
			{
				$this->orderingCustomerVariation = self::ORDERING_CUSTOMER_50A;
			}
		}
		if ($this->orderingCustomerVariation == self::ORDERING_CUSTOMER_50A)
		{
			$this->_orderingCustomerA = $value;
			$value = explode(PHP_EOL, $value);
			$this->orderingCustomerReqA = array_shift($value);
			$this->orderingCustomerTextA = join(PHP_EOL, $value);
		}
	}

	public function getOrderingCustomerA()
	{
		return trim($this->orderingCustomerReqA . PHP_EOL . $this->orderingCustomerTextA);
	}

	public function setOrderingCustomerF($value)
	{
		if (empty($this->orderingCustomerVariation))
		{
			if (!is_null($value) && is_null($this->_orderingCustomerA) && is_null($this->_orderingCustomerK))
			{
				$this->orderingCustomerVariation = self::ORDERING_CUSTOMER_50F;
			}
		}
		if ($this->orderingCustomerVariation == self::ORDERING_CUSTOMER_50F)
		{
			$this->_orderingCustomerF = $value;
			$value = explode(PHP_EOL, $value);
			$this->orderingCustomerReqF = array_shift($value);
			$this->orderingCustomerTextF = join(PHP_EOL, $value);
		}
	}

	public function getOrderingCustomerF()
	{
		return trim($this->orderingCustomerReqF . PHP_EOL . $this->orderingCustomerTextF);
    }

	public function setOrderingCustomerK($value)
	{
		if (empty($this->orderingCustomerVariation))
		{
			if (!is_null($value) && is_null($this->_orderingCustomerA) && is_null($this->_orderingCustomerF))
			{
				$this->orderingCustomerVariation = self::ORDERING_CUSTOMER_50K;
			}
		}
		if ($this->orderingCustomerVariation == self::ORDERING_CUSTOMER_50K)
		{
			$this->_orderingCustomerK = $value;
			$value = explode(PHP_EOL, $value);
			$this->orderingCustomerReqK = array_shift($value);
			$this->orderingCustomerTextK = join(PHP_EOL, $value);
		}
	}

	public function getOrderingCustomerK()
	{
		return trim($this->orderingCustomerReqK . PHP_EOL . $this->orderingCustomerTextK);
	}

	function getOrderingInstitutionA()
	{
		return trim($this->orderingInstitutionReqA . PHP_EOL . $this->orderingInstitutionTextA);
	}

	function setOrderingInstitutionA($value)
	{
		if (empty($this->orderingInstitutionVariation))
		{
			if (!is_null($value) && is_null($this->_orderingInstitutionD))
			{
				$this->orderingInstitutionVariation = self::ORDERING_INSTITUTION_52A;
			}
		}
		if ($this->orderingInstitutionVariation == self::ORDERING_INSTITUTION_52A)
		{
			$this->_orderingInstitutionA = $value;
			$value = explode(PHP_EOL, $value);
			$this->orderingInstitutionReqA = array_shift($value);
			$this->orderingInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	function getOrderingInstitutionD()
	{
		return trim($this->orderingInstitutionReqD . PHP_EOL . $this->orderingInstitutionTextD);
	}

	function setOrderingInstitutionD($value)
	{
		if (empty($this->orderingInstitutionVariation))
		{
			if (!is_null($value) && is_null($this->_orderingInstitutionA))
			{
				$this->orderingInstitutionVariation = self::ORDERING_INSTITUTION_52D;
			}
		}
		if ($this->orderingInstitutionVariation == self::ORDERING_INSTITUTION_52D)
		{
			$this->_orderingInstitutionD = $value;
			$value = explode(PHP_EOL, $value);
			$this->orderingInstitutionReqD = array_shift($value);
			$this->orderingInstitutionTextD = join(PHP_EOL, $value);
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
			if (
                !is_null($value)
                && is_null($this->_receiverCorrespondentA)
                && is_null($this->_receiverCorrespondentB)
            ) {
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

	/**
	 * @return string
	 */
	function getThirdReimbursementInstitutionA()
    {
		return trim($this->thirdReimbursementInstitutionReqA . PHP_EOL . $this->thirdReimbursementInstitutionTextA);
	}

	/**
	 * @param $value
	 */
	function setThirdReimbursementInstitutionA($value)
    {
		if (empty($this->thirdReimbursementInstitutionVariation)) {
			if (
                !is_null($value)
                && is_null($this->_thirdReimbursementInstitutionB)
                && is_null($this->_thirdReimbursementInstitutionD)
            ) {
				$this->thirdReimbursementInstitutionVariation = 'A';
			}
		}
		if ($this->thirdReimbursementInstitutionVariation == 'A') {
			$this->_thirdReimbursementInstitutionA    = $value;
			$value                                    = explode(PHP_EOL, $value);
			$this->thirdReimbursementInstitutionReqA  = array_shift($value);
			$this->thirdReimbursementInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getThirdReimbursementInstitutionB()
    {
		return trim($this->thirdReimbursementInstitutionReqB . PHP_EOL . $this->thirdReimbursementInstitutionTextB);
	}

	/**
	 * @param $value
	 */
	function setThirdReimbursementInstitutionB($value)
    {
		if (empty($this->thirdReimbursementInstitutionVariation)) {
			if (
                !is_null($value)
                && is_null($this->_thirdReimbursementInstitutionA)
                && is_null($this->_thirdReimbursementInstitutionD)
            ) {
				$this->thirdReimbursementInstitutionVariation = 'B';
			}
		}
		if ($this->thirdReimbursementInstitutionVariation == 'B') {
			$this->_thirdReimbursementInstitutionB    = $value;
			$value                                    = explode(PHP_EOL, $value);
			$this->thirdReimbursementInstitutionReqB  = array_shift($value);
			$this->thirdReimbursementInstitutionTextB = join(PHP_EOL, $value);
		}
	}

	/**
	 * @return string
	 */
	function getThirdReimbursementInstitutionD()
    {
		return trim($this->thirdReimbursementInstitutionReqD . PHP_EOL . $this->thirdReimbursementInstitutionTextD);
	}

	/**
	 * @param $value
	 */
	function setThirdReimbursementInstitutionD($value)
    {
		if (empty($this->thirdReimbursementInstitutionVariation)) {
			if (!is_null($value) && is_null($this->_thirdReimbursementInstitutionA) && is_null($this->_thirdReimbursementInstitutionB)) {
				$this->thirdReimbursementInstitutionVariation = 'D';
			}
		}
		if ($this->thirdReimbursementInstitutionVariation == 'D') {
			$this->_thirdReimbursementInstitutionD    = $value;
			$value                                    = explode(PHP_EOL, $value);
			$this->thirdReimbursementInstitutionReqD  = array_shift($value);
			$this->thirdReimbursementInstitutionTextD = join(PHP_EOL, $value);
		}
	}

	function getBeneficiaryBank()
	{
		return trim($this->beneficiaryBankReq . PHP_EOL . $this->beneficiaryBankText);
	}

	function setBeneficiaryBank($value)
	{
		$this->_beneficiaryBank = $value;

		$value = explode(PHP_EOL, $value);
		$this->beneficiaryBankReq = array_shift($value);
		$this->beneficiaryBankText = join(PHP_EOL, $value);
	}

	function getBeneficiaryCustomerA()
	{
		return trim($this->beneficiaryCustomerReqA . PHP_EOL . $this->beneficiaryCustomerTextA);
	}

	function setBeneficiaryCustomerA($value)
	{
		if (empty($this->beneficiaryCustomerVariation))
		{
			if (!is_null($value) && is_null($this->_beneficiaryCustomerNLO))
			{
				$this->beneficiaryCustomerVariation = self::BENEFICIARY_CUSTOMER_59A;
			}
		}
		if ($this->beneficiaryCustomerVariation == self::BENEFICIARY_CUSTOMER_59A)
		{
			$this->_beneficiaryCustomerA = $value;
			$value = explode(PHP_EOL, $value);
			$this->beneficiaryCustomerReqA = array_shift($value);
			$this->beneficiaryCustomerTextA = join(PHP_EOL, $value);
		}
	}

	function getBeneficiaryCustomerNLO()
	{
		return trim($this->beneficiaryCustomerReqNLO . PHP_EOL . $this->beneficiaryCustomerTextNLO);
	}

	function setBeneficiaryCustomerNLO($value)
	{
		if (empty($this->beneficiaryCustomerVariation))
		{
			if (!is_null($value) && is_null($this->_beneficiaryCustomerA))
			{
				$this->beneficiaryCustomerVariation = self::BENEFICIARY_CUSTOMER_59NLO;
			}
		}
		if ($this->beneficiaryCustomerVariation == self::BENEFICIARY_CUSTOMER_59NLO)
		{
			$this->_beneficiaryCustomerNLO = $value;
			$value = explode(PHP_EOL, $value);
			$this->beneficiaryCustomerReqNLO = array_shift($value);
			$this->beneficiaryCustomerTextNLO = join(PHP_EOL, $value);
		}
	}

	function getIntermediaryInstitutionA()
	{
		return trim($this->intermediaryInstitutionReqA . PHP_EOL . $this->intermediaryInstitutionTextA);
	}

	function setIntermediaryInstitutionA($value)
	{
		if (empty($this->intermediaryInstitutionVariation))
		{
			if (!is_null($value) && is_null($this->_intermediaryInstitutionD))
			{
				$this->intermediaryInstitutionVariation = self::INTERMEDIARY_INSTITUTION_56A;
			}
		}
		if ($this->intermediaryInstitutionVariation == self::INTERMEDIARY_INSTITUTION_56A)
		{
			$this->_intermediaryInstitutionA = $value;
			$value = explode(PHP_EOL, $value);
			$this->intermediaryInstitutionReqA = array_shift($value);
			$this->intermediaryInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	function getIntermediaryInstitutionD()
	{
		return trim($this->intermediaryInstitutionReqD . PHP_EOL . $this->intermediaryInstitutionTextD);
	}

	function setIntermediaryInstitutionD($value)
	{
		if (empty($this->intermediaryInstitutionVariation))
		{
			if (!is_null($value) && is_null($this->_intermediaryInstitutionA))
			{
				$this->intermediaryInstitutionVariation = self::INTERMEDIARY_INSTITUTION_56D;
			}
		}
		if ($this->intermediaryInstitutionVariation == self::INTERMEDIARY_INSTITUTION_56D)
		{
			$this->_intermediaryInstitutionD = $value;
			$value = explode(PHP_EOL, $value);
			$this->intermediaryInstitutionReqD = array_shift($value);
			$this->intermediaryInstitutionTextD = join(PHP_EOL, $value);
		}
	}

	function getAccountWithInstitutionA()
	{
		return trim($this->accountWithInstitutionReqA . PHP_EOL . $this->accountWithInstitutionTextA);
	}

	function setAccountWithInstitutionA($value)
	{
		if (empty($this->accountWithInstitutionVariation))
		{
			if (!is_null($value) && is_null($this->_accountWithInstitutionD))
			{
				$this->accountWithInstitutionVariation = self::ACCOUNT_WITH_INSTITUTION_57A;
			}
		}
		if ($this->accountWithInstitutionVariation == self::ACCOUNT_WITH_INSTITUTION_57A)
		{
			$this->_accountWithInstitutionA = $value;
			$value = explode(PHP_EOL, $value);
			$this->accountWithInstitutionReqA = array_shift($value);
			$this->accountWithInstitutionTextA = join(PHP_EOL, $value);
		}
	}

	function getAccountWithInstitutionD()
	{
		return trim($this->accountWithInstitutionReqD . PHP_EOL . $this->accountWithInstitutionTextD);
	}

	function setAccountWithInstitutionD($value)
	{
		if (empty($this->accountWithInstitutionVariation))
		{
			if (!is_null($value) && is_null($this->_accountWithInstitutionD))
			{
				$this->accountWithInstitutionVariation = self::ACCOUNT_WITH_INSTITUTION_57D;
			}
		}
		if ($this->accountWithInstitutionVariation == self::ACCOUNT_WITH_INSTITUTION_57D)
		{
			$this->_accountWithInstitutionD = $value;
			$value = explode(PHP_EOL, $value);
			$this->accountWithInstitutionReqD = array_shift($value);
			$this->accountWithInstitutionTextD = join(PHP_EOL, $value);
		}
	}

	public function attributeReadableTags() {
		return [
			'senderReference'             => '20',
			'timeIndication'              => '13C',
			'bankOperationCode'           => '23B',
			'instructionCode'             => '23E',
			'transactionTypeCode'         => '26T',
			'dateCurrencySettledAmount'   => '32A',
			'currencyInstructedAmount'    => '33B',
			'exchangeRate'                => '36',
			'orderingCustomer'            => [
				// orderingCustomer/Option A
				'orderingCustomerA' => '50A',
				// orderingCustomer/Option F
				'orderingCustomerF' => '50F',
				// orderingCustomer/Option K
				'orderingCustomerK' => '50K',
			],
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
			'receiverCorrespondent'         => [
				// senderCorrespondent/Option A
				'receiverCorrespondentA' => '54A',
				// senderCorrespondent/Option B
				'receiverCorrespondentB' => '54B',
				// senderCorrespondent/Option D
				'receiverCorrespondentD' => '54D',
			],
			'thirdReimbursementInstitution' => [
				// senderCorrespondent/Option A
				'thirdReimbursementInstitutionA' => '55A',
				// senderCorrespondent/Option B
				'thirdReimbursementInstitutionB'  => '55B',
				// senderCorrespondent/Option D
				'thirdReimbursementInstitutionD'  => '55D',
			],
			'intermediaryInstitution'     => [
				// intermediaryInstitution/Option A
				'intermediaryInstitutionA' => '56A',
				// intermediaryInstitution/Option D
				'intermediaryInstitutionD' => '56D',
			],
			'accountWithInstitution'      => [
				// accountWithInstitution/Option A
				'accountWithInstitutionA' => '57A',
				// accountWithInstitution/Option D
				'accountWithInstitutionD' => '57D',
			],
			'beneficiaryCustomer'         => [
				// beneficiaryCustomer/Option A
				'beneficiaryCustomerA'   => '59A',
				// beneficiaryCustomer/No letter option
				'beneficiaryCustomerNLO' => '59',
			],
			'remittanceInformation'       => '70',
			'detailsOfCharges'            => '71A',
			'senderCharges'               => '71F',
			'receiverCharges'             => '71G',
			'senderToReceiverInformation' => '72',
			'regulatoryReporting'         => '77B',
			'envelopeContents'            => '77T',
		];
	}

}
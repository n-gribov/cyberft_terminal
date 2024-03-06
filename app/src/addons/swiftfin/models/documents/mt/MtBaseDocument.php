<?php

namespace addons\swiftfin\models\documents\mt;

use Yii;
use yii\base\Model;
use yii\helpers\StringHelper;

/**
 * Description of MtDocument
 *
 * @author fuzz
 */
abstract class MtBaseDocument extends Model implements MtInterface {

	public $label;
	public $formable = true;
	public $view;

	const EOL = PHP_EOL;
	const MAX_STR_LENGTH = 255;

	public $validatorPath = "@addons/swiftfin/models/documents/mt/validator";

	/**
	 * Коды валют по ISO 4217.
	 * Сначала идут некоторые избранные валюты для быстрого доступа, далее -
	 * прочие коды в порядке увеличения их кода ISO.
	 * @var array
	 */
	static public $currencyIsoCodes = [
		'RUB' => 'RUB', // 643 - РОССИЙСКИЙ РУБЛЬ
		'RUR' => 'RUR', // 810 - РОССИЙСКИЙ РУБЛЬ
		'EUR' => 'EUR', // 978 - ЕВРО
		'USD' => 'USD', // 840 - ДОЛЛАР США
		'CHF' => 'CHF', // 756 - ШВЕЙЦАРСКИЙ ФРАНК
		'CNY' => 'CNY', // 156 - КИТАЙСКИЙ ЮАНЬ
		'GBP' => 'GBP', // 826 - ФУНТ СТЕРЛИНГОВ
		'KZT' => 'KZT', // 398 - ТЕНГЕ (КАЗАХСКИЙ)
		'JPY' => 'JPY', // 392 - ЙЕНА
		// Прочие коды валют
		'AED' => 'AED', // 784 - ДИРХАМ (ОАЭ)
		'AFN' => 'AFN', // 971 - АФГАНИ
		'ALL' => 'ALL', // 008 - ЛЕК
		'AMD' => 'AMD', // 051 - АРМЯНСКИЙ ДРАМ
		'ANG' => 'ANG', // 532 - НИДЕРЛАНДСКИЙ АНТИЛЬСКИЙ ГУЛЬДЕН
		'AOA' => 'AOA', // 973 - КВАНЗА
		'ARS' => 'ARS', // 032 - АРГЕНТИНСКОЕ ПЕСО
		'AUD' => 'AUD', // 036 - АВСТРАЛИЙСКИЙ ДОЛЛАР
		'AWG' => 'AWG', // 533 - АРУБАНСКИЙ ФЛОРИН
		'AZN' => 'AZN', // 944 - АЗЕРБАЙДЖАНСКИЙ МАНАТ
		'BAM' => 'BAM', // 977 - КОНВЕРТИРУЕМАЯ МАРКА
		'BBD' => 'BBD', // 052 - БАРБАДОССКИЙ ДОЛЛАР
		'BDT' => 'BDT', // 050 - ТАКА
		'BGN' => 'BGN', // 975 - БОЛГАРСКИЙ ЛЕВ
		'BHD' => 'BHD', // 048 - БАХРЕЙНСКИЙ ДИНАР
		'BIF' => 'BIF', // 108 - БУРУНДИЙСКИЙ ФРАНК
		'BMD' => 'BMD', // 060 - БЕРМУДСКИЙ ДОЛЛАР
		'BND' => 'BND', // 096 - БРУНЕЙСКИЙ ДОЛЛАР
		'BOB' => 'BOB', // 068 - БОЛИВИАНО
		'BRL' => 'BRL', // 986 - БРАЗИЛЬСКИЙ РЕАЛ
		'BSD' => 'BSD', // 044 - БАГАМСКИЙ ДОЛЛАР
		'BTN' => 'BTN', // 064 - НГУЛТРУМ
		'BWP' => 'BWP', // 072 - ПУЛА
		'BYR' => 'BYR', // 974 - БЕЛОРУССКИЙ РУБЛЬ
		'BZD' => 'BZD', // 084 - БЕЛИЗСКИЙ ДОЛЛАР
		'CAD' => 'CAD', // 124 - КАНАДСКИЙ ДОЛЛАР
		'CDF' => 'CDF', // 976 - КОНГОЛЕЗСКИЙ ФРАНК
		'CLP' => 'CLP', // 152 - ЧИЛИЙСКОЕ ПЕСО
		'COP' => 'COP', // 170 - КОЛУМБИЙСКОЕ ПЕСО
		'COU' => 'COU', // 970 - ЕДИНИЦА РЕАЛЬНОЙ СТОИМОСТИ
		'CRC' => 'CRC', // 188 - КОСТАРИКАНСКИЙ КОЛОН
		'CUC' => 'CUC', // 931 - КОНВЕРТИРУЕМОЕ ПЕСО
		'CUP' => 'CUP', // 192 - КУБИНСКОЕ ПЕСО
		'CVE' => 'CVE', // 132 - ЭСКУДО КАБО-ВЕРДЕ
		'CZK' => 'CZK', // 203 - ЧЕШСКАЯ КРОНА
		'DJF' => 'DJF', // 262 - ФРАНК ДЖИБУТИ
		'DKK' => 'DKK', // 208 - ДАТСКАЯ КРОНА
		'DOP' => 'DOP', // 214 - ДОМИНИКАНСКОЕ ПЕСО
		'DZD' => 'DZD', // 012 - АЛЖИРСКИЙ ДИНАР
		'EGP' => 'EGP', // 818 - ЕГИПЕТСКИЙ ФУНТ
		'ERN' => 'ERN', // 232 - НАКФА
		'ETB' => 'ETB', // 230 - ЭФИОПСКИЙ БЫР
		'FJD' => 'FJD', // 242 - ДОЛЛАР ФИДЖИ
		'FKP' => 'FKP', // 238 - ФУНТ ФОЛКЛЕНДСКИХ ОСТРОВОВ
		'GEL' => 'GEL', // 981 - ЛАРИ
		'GHS' => 'GHS', // 936 - ГАНСКИЙ СЕДИ
		'GIP' => 'GIP', // 292 - ГИБРАЛТАРСКИЙ ФУНТ
		'GMD' => 'GMD', // 270 - ДАЛАСИ
		'GNF' => 'GNF', // 324 - ГВИНЕЙСКИЙ ФРАНК
		'GTQ' => 'GTQ', // 320 - КЕТСАЛЬ
		'GYD' => 'GYD', // 328 - ГАЙАНСКИЙ ДОЛЛАР
		'HKD' => 'HKD', // 344 - ГОНКОНГСКИЙ ДОЛЛАР
		'HNL' => 'HNL', // 340 - ЛЕМПИРА
		'HRK' => 'HRK', // 191 - ХОРВАТСКАЯ КУНА
		'HTG' => 'HTG', // 332 - ГУРД
		'HUF' => 'HUF', // 348 - ФОРИНТ
		'IDR' => 'IDR', // 360 - РУПИЯ
		'ILS' => 'ILS', // 376 - НОВЫЙ ИЗРАИЛЬСКИЙ ШЕКЕЛЬ
		'INR' => 'INR', // 356 - ИНДИЙСКАЯ РУПИЯ
		'IQD' => 'IQD', // 368 - ИРАКСКИЙ ДИНАР
		'IRR' => 'IRR', // 364 - ИРАНСКИЙ РИАЛ
		'ISK' => 'ISK', // 352 - ИСЛАНДСКАЯ КРОНА
		'JMD' => 'JMD', // 388 - ЯМАЙСКИЙ ДОЛЛАР
		'JOD' => 'JOD', // 400 - ИОРДАНСКИЙ ДИНАР
		'KES' => 'KES', // 404 - КЕНИЙСКИЙ ШИЛЛИНГ
		'KGS' => 'KGS', // 417 - СОМ
		'KHR' => 'KHR', // 116 - РИЕЛЬ
		'KMF' => 'KMF', // 174 - ФРАНК КОМОР
		'KPW' => 'KPW', // 408 - СЕВЕРОКОРЕЙСКАЯ ВОНА
		'KRW' => 'KRW', // 410 - ВОНА
		'KWD' => 'KWD', // 414 - КУВЕЙТСКИЙ ДИНАР
		'KYD' => 'KYD', // 136 - ДОЛЛАР ОСТРОВОВ КАЙМАН
		'LAK' => 'LAK', // 418 - КИП
		'LBP' => 'LBP', // 422 - ЛИВАНСКИЙ ФУНТ
		'LKR' => 'LKR', // 144 - ШРИ-ЛАНКИЙСКАЯ РУПИЯ
		'LRD' => 'LRD', // 430 - ЛИБЕРИЙСКИЙ ДОЛЛАР
		'LSL' => 'LSL', // 426 - ЛОТИ
		'LYD' => 'LYD', // 434 - ЛИВИЙСКИЙ ДИНАР
		'MAD' => 'MAD', // 504 - МАРОККАНСКИЙ ДИРХАМ
		'MDL' => 'MDL', // 498 - МОЛДАВСКИЙ ЛЕЙ
		'MGA' => 'MGA', // 969 - МАЛАГАСИЙСКИЙ АРИАРИ
		'MKD' => 'MKD', // 807 - ДЕНАР
		'MMK' => 'MMK', // 104 - КЬЯТ
		'MNT' => 'MNT', // 496 - ТУГРИК
		'MOP' => 'MOP', // 446 - ПАТАКА
		'MRO' => 'MRO', // 478 - УГИЯ
		'MUR' => 'MUR', // 480 - МАВРИКИЙСКАЯ РУПИЯ
		'MVR' => 'MVR', // 462 - РУФИЯ
		'MWK' => 'MWK', // 454 - КВАЧА
		'MXN' => 'MXN', // 484 - МЕКСИКАНСКОЕ ПЕСО
		'MYR' => 'MYR', // 458 - МАЛАЙЗИЙСКИЙ РИНГГИТ
		'MZN' => 'MZN', // 943 - МОЗАМБИКСКИЙ МЕТИКАЛ
		'NAD' => 'NAD', // 516 - ДОЛЛАР НАМИБИИ
		'NGN' => 'NGN', // 566 - НАЙРА
		'NIO' => 'NIO', // 558 - ЗОЛОТАЯ КОРДОБА
		'NOK' => 'NOK', // 578 - НОРВЕЖСКАЯ КРОНА
		'NPR' => 'NPR', // 524 - НЕПАЛЬСКАЯ РУПИЯ
		'NZD' => 'NZD', // 554 - НОВОЗЕЛАНДСКИЙ ДОЛЛАР
		'OMR' => 'OMR', // 512 - ОМАНСКИЙ РИАЛ
		'PAB' => 'PAB', // 590 - БАЛЬБОА
		'PEN' => 'PEN', // 604 - НОВЫЙ СОЛЬ
		'PGK' => 'PGK', // 598 - КИНА
		'PHP' => 'PHP', // 608 - ФИЛИППИНСКОЕ ПЕСО
		'PKR' => 'PKR', // 586 - ПАКИСТАНСКАЯ РУПИЯ
		'PLN' => 'PLN', // 985 - ЗЛОТЫЙ
		'PYG' => 'PYG', // 600 - ГУАРАНИ
		'QAR' => 'QAR', // 634 - КАТАРСКИЙ РИАЛ
		'RON' => 'RON', // 946 - НОВЫЙ РУМЫНСКИЙ ЛЕЙ
		'RSD' => 'RSD', // 941 - СЕРБСКИЙ ДИНАР
		'RWF' => 'RWF', // 646 - ФРАНК РУАНДЫ
		'SAR' => 'SAR', // 682 - САУДОВСКИЙ РИЯЛ
		'SBD' => 'SBD', // 090 - ДОЛЛАР СОЛОМОНОВЫХ ОСТРОВОВ
		'SCR' => 'SCR', // 690 - СЕЙШЕЛЬСКАЯ РУПИЯ
		'SDG' => 'SDG', // 938 - СУДАНСКИЙ ФУНТ
		'SEK' => 'SEK', // 752 - ШВЕДСКАЯ КРОНА
		'SGD' => 'SGD', // 702 - СИНГАПУРСКИЙ ДОЛЛАР
		'SHP' => 'SHP', // 654 - ФУНТ СВЯТОЙ ЕЛЕНЫ
		'SLL' => 'SLL', // 694 - ЛЕОНЕ
		'SOS' => 'SOS', // 706 - СОМАЛИЙСКИЙ ШИЛЛИНГ
		'SRD' => 'SRD', // 968 - СУРИНАМСКИЙ ДОЛЛАР
		'SSP' => 'SSP', // 728 - ЮЖНОСУДАНСКИЙ ФУНТ
		'STD' => 'STD', // 678 - ДОБРА
		'SVC' => 'SVC', // 222 - САЛЬВАДОРСКИЙ КОЛОН
		'SYP' => 'SYP', // 760 - СИРИЙСКИЙ ФУНТ
		'SZL' => 'SZL', // 748 - ЛИЛАНГЕНИ
		'THB' => 'THB', // 764 - БАТ
		'TJS' => 'TJS', // 972 - СОМОНИ
		'TMT' => 'TMT', // 934 - НОВЫЙ ТУРКМЕНСКИЙ МАНАТ
		'TND' => 'TND', // 788 - ТУНИССКИЙ ДИНАР
		'TOP' => 'TOP', // 776 - ПААНГА
		'TRY' => 'TRY', // 949 - ТУРЕЦКАЯ ЛИРА
		'TTD' => 'TTD', // 780 - ДОЛЛАР ТРИНИДАДА И ТОБАГО
		'TWD' => 'TWD', // 901 - НОВЫЙ ТАЙВАНЬСКИЙ ДОЛЛАР
		'TZS' => 'TZS', // 834 - ТАНЗАНИЙСКИЙ ШИЛЛИНГ
		'UAH' => 'UAH', // 980 - ГРИВНА
		'UGX' => 'UGX', // 800 - УГАНДИЙСКИЙ ШИЛЛИНГ
		'UYI' => 'UYI', // 940 - УРУГВАЙСКОЕ ПЕСО В ИНДЕКСИРОВАННЫХ ЕДИНИЦАХ
		'UYU' => 'UYU', // 858 - УРУГВАЙСКОЕ ПЕСО
		'UZS' => 'UZS', // 860 - УЗБЕКСКИЙ СУМ
		'VEF' => 'VEF', // 937 - БОЛИВАР
		'VND' => 'VND', // 704 - ДОНГ
		'VUV' => 'VUV', // 548 - ВАТУ
		'WST' => 'WST', // 882 - ТАЛА
		'XAF' => 'XAF', // 950 - ФРАНК КФА ВЕАС
		'XCD' => 'XCD', // 951 - ВОСТОЧНО-КАРИБСКИЙ ДОЛЛАР
		'XDR' => 'XDR', // 960 - СДР (СПЕЦИАЛЬНЫЕ ПРАВА ЗАИМСТВОВАНИЯ)
		'XOF' => 'XOF', // 952 - ФРАНК КФА ВСЕАО
		'XPF' => 'XPF', // 953 - ФРАНК КФП
		'YER' => 'YER', // 886 - ЙЕМЕНСКИЙ РИАЛ
		'ZAR' => 'ZAR', // 710 - РЭНД
		'ZMW' => 'ZMW', // 967 - ЗАМБИЙСКАЯ КВАЧА
		'ZWL' => 'ZWL', // 932 - ДОЛЛАР ЗИМБАБВЕ
	];

	protected $_rawBody;

	public function preprocessAttributes()
	{
		foreach($this->attributes() as $attribute)
		{
			$value = StringHelper::truncate($this->$attribute, static::MAX_STR_LENGTH, '');
			$value = $this->transliterateString($value);
			$this->$attribute = $value;
		}
	}

	public function attributeTags()
	{
		return [];
	}

	public function attributeLabels()
	{
		return [
			'body'	=> Yii::t('doc/mt', 'Document data'),
		];
	}

	/**
	 * Should be inherited
	 * @return string
	 */
	public function getOperationReference()
    {
		return '';
	}

	public function getType()
	{
		return static::DOC_TYPE;
	}

	public function setType($value)
    {

	}

	public function getAttributeConfig($attribute)
	{
		$config = $this->attributeConfig();

		return isset($config[$attribute]) ? $config[$attribute] : '';
	}

	public function scenarios()
	{
		return [
			// Твик, для того чтобы срабатывал load()
			self::SCENARIO_DEFAULT => $this->attributes()
		];
	}

	public function getAttributeTag($attribute)
	{
		$tags = $this->getAttributeSafeTags();

		return isset($tags[$attribute]) ? $tags[$attribute] : '';
	}

	public function getTagAttribute($tag)
	{
		$tags = $this->getAttributeSafeTags();
		$attributes = array_flip($tags);

		return isset($attributes[$tag]) ? $attributes[$tag] : '';
	}

	public function getAttributeSafeTags()
	{
		$tags = $this->attributeTags();

		// Refactored below. Causes multiple extra getter calls
		// $tags = array_intersect_key($tags, $this->attributes);

		// Выдаем только те теги, к которым имеются атрибуты
		$tags = array_intersect_key($tags, array_flip($this->attributes()));

		return $tags;
	}

	/**
	 * @param $value
	 */
	public function setBody($value)
    {
		$this->loadFromString($value);
		$this->setRawBody($value);
	}

	/**
	 * Тело документа, то есть собранные в строку аттрибуты модели
	 * @return type
	 */
	public function getBody()
    {
		return $this->packData();
	}

	/**
	 * @param $value
	 * @return $this
	 */
	public function setRawBody($value)
    {
		$this->_rawBody = $value;

		return $this;
	}

	/**
	 * Отдаем неизмененные данные установленные ранее через setBody
	 * @return string
	 */
	public function getRawBody()
    {
		return $this->_rawBody;
	}

	public function getDataReadable()
	{
		/**
		 * @todo Возможно, переключение языка не лучшее решение проблемы.
		 * Требуется разобраться c функцией packData(), чтобы в рамках этой
		 * задачи она не транслировала английские метки в русские
		 */
		$myCurrentlySavedLang = \Yii::$app->language;
		\Yii::$app->language = 'en';
		$packedData = $this->packReadableData();
		\Yii::$app->language = $myCurrentlySavedLang;

		return $packedData;
	}

	/**
	 * @inheritdoc
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		$result = parent::setAttributes($values, $safeOnly);
		$this->_rawBody = $this->packData();

		return $result;
	}

	/**
	 * Экспорт модели в итоговый MT формат
	 *
	 * @return string
	 */
	public function packData($readableFormat = false)
	{
		$result = '';

		foreach ($this->getAttributeSafeTags() as $attribute => $tag) {
			$value = trim($this->$attribute);
			if (!empty($value)) {
				if ($readableFormat) {
					$label = $this->getAttributeLabel($attribute);
					$result.= ":{$tag}: {$label}" . self::EOL . $value . self::EOL;
				} else {
					$result.= ":{$tag}:{$value}" . self::EOL;
				}
			}
		}

		$result = trim($result);

		return !empty($result) ? trim($result) . self::EOL . '-' : '';
	}

	/**
	 * Загрузить данные из строки в MT формате
	 * @param $data
	 * @return bool
	 */
	protected function loadFromString($data)
	{
		$data = self::parseMtString($data);
		if (!$data) {
			$this->addError('data', Yii::t('doc/mt', 'Invalid data format'));

			return false;
		}

		foreach($data as $tag => $value) {
			if ($attribute = $this->getTagAttribute($tag)) {
				$this->$attribute = $value;
			} else {
				$this->addError('data', Yii::t('doc/mt', 'Invalid data tag ":{tag}:"', ['tag' => $tag]));
			}
		}

		return true;
	}

	public static function parseMtString($data)
	{
		$data = trim($data, '-');
		$data = explode(':', $data);
		array_shift($data);
		$tags = $values = [];
		foreach ($data as $k => $v) {
			$v = trim($v);
			if ($k % 2) {
				array_push($values, $v);
			} else {
				array_push($tags, $v);
			}
		}

		if (count($values) != count($tags)) {
			return false;
		}

		return array_combine($tags, $values);
	}

	/**
	 * Валидация МТ документа с помощью внешнего перлового компонента
	 *
	 * @return boolean
	 */

	public function validateExternal()
	{
		$this->_rawBody = $this->packData();

		$template = <<<EOT
From: %s
To: %s
Content-Type: swift/%s
Begin
%s
End
EOT;
		/**
		 * Подставляются тестовые данные отправителя-получателя,
		 * чтобы не замешивать здесь логику с родительскими объектами
		 * Dependency Inversion or Death muthafucka!
		 */
		$envelope = sprintf($template,
			Yii::$app->terminals->address,
			'TESTRUM3A001',
			$this->type,
			base64_encode($this->data)
		);

		$cwd = Yii::getAlias($this->validatorPath);

		$descriptorspec = [
			0 => ['pipe', 'r'],
			1 => ['pipe', 'w'],
		];

		$process = proc_open(
			"/usr/bin/perl {$cwd}/swift_validate.pl",
			$descriptorspec,
			$pipes,
			$cwd
		);

		$result = '';
		if (is_resource($process)) {
			fwrite($pipes[0], $envelope);
			fclose($pipes[0]);

			$result = trim(stream_get_contents($pipes[1]));
			fclose($pipes[1]);

			proc_close($process);
		}

		if (1 == $result[0]) {
			$lines = explode(PHP_EOL, $result);
			array_shift($lines);

			foreach ($lines as $line) {
				$line = explode(':', $line);
				$message = trim ($line[0]);
				$field = 'data';
				$tag = '';
				if (!empty($line[1])) {
					if ($tag = trim($line[1])) {
						$field = $this->getTagAttribute($tag);
					}
				}

				$this->addError($field,
						Yii::t('doc/mt', $message)
						. ( !empty($tag)
								? ' "' . ":{$tag}: " . $this->getAttributeLabel($field) . '"'
								: ''
						)
					);
			}

			return false;
		}

		return true;
	}

	public function validate($attributeNames = null, $clearErrors = true)
	{
		parent::validate($attributeNames, $clearErrors);

		if (is_null($attributeNames)) {
			$this->validateExternal();
        }

		return !$this->hasErrors();
	}


	protected function transliterateString($value)
	{
		$search = [
			'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
			'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
			'\'', '’', '‘', '`',
		];

		$replace = [
			'A', 'B', 'V', 'G', 'D', 'E', 'o', 'J', 'Z', 'I', 'i', 'K', 'L', 'M', 'N', 'O', 'P',
			'R', 'S', 'T', 'U', 'F', 'H', 'C', 'c', 'Q', 'q', 'x', 'Y', 'X', 'e', 'u', 'a',
			'j', 'j', 'j', 'j',
		];

		$searchLower = $search;
		array_walk($searchLower, function (&$item) {
			$item = mb_strtolower($item, Yii::$app->charset);
		});

		$value = str_replace($searchLower, $search, $value);

		return str_replace($search, $replace, $value);
	}

	// Функция возвращает тэги атрибутов, которые должны формировать читаемую версию документа.
	// Должна переопределяться для каждого из видов документов, и учитывать
	// структуру составных атрибутов и вариаций опций документа.
	public function attributeReadableTags()
	{
		return $this->attributeTags();
	}

	// Функция возвращает тэги атрибутов, которые должны формировать читаемую
	// версию документа.
	public function getReadableAttributeSafeTags()
	{
		$tags = $this->attributeReadableTags();
		// Выдаем только те теги, к которым имеются атрибуты
		//$tags = array_intersect_key($tags, $this->attributes);

		return $tags;
	}

	// Функция превращает набор атрибутов в строку-читаемый вид документа
	// Учитывает возможную вложенность атрибутов, реализованных как опции документа
	public function packReadableData()
	{
		$result = "";

		foreach ($this->getReadableAttributeSafeTags() as $attribute => $tag) {
			// Здесь обрабатываем вариативные опции
			if (is_array($tag)) {
				// Общее название опции
				$title = $this->getAttributeLabel($attribute);
				// Из всех вариантов опций выбираем только первый непустой
				// Если все варианты опции пусты, то она вообще не должна попасть
				// в результирующий документ
				foreach ($tag as $subAttribute => $subTag) {
					$value = trim($this->$subAttribute);
					if (!empty($value)) {
						// Формируем тело опции с заголовком опции
						$label = $this->getAttributeLabel($subAttribute);
						$result.= ":{$subTag}: {$title}" . self::EOL . "{$label}" . self::EOL . "{$value}" . self::EOL;

						break; // Отображаем только первую непустую опцию
					}
				}
			} else {
                // Обычные атрибуты
				$value = trim($this->$attribute);
				if (!empty($value)) {
					$label = $this->getAttributeLabel($attribute);
					$result.= ":{$tag}: {$label}" . self::EOL . "{$value}" . self::EOL;
				}
			}
		}

		$result = trim($result);

		return !empty($result) ? trim($result) . self::EOL . '-' : '';
	}

	/**
	 * Функция возвращает значение атрибута Дата валютирования
	 * @return string
	 */
	public function getValueDate()
	{
		return null;
    }

}
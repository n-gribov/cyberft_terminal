<?php
namespace addons\swiftfin\models\documents\mt\mtUniversal;

/**
 * Class MtMask
 * @package addons\swiftfin\models\documents\mt\mtUniversal
 * @todo    переписать на нормальный объект, иначе вывих мозга легко заработать, на данный момент просили сильно не
 *          рефакторить
 * @todo	настоятельно рекомендую ничего не править без написания теста по ВСЕМ маскам (см. test/mt-unique-mask)
 * Если что-то глючит с парсином, не пытайся это исправить точечно, это приведет к неминуемым системным багам
 * Делаешь правки для какого-то конкретного случая, убедись что все остальные маски работают корректно!
 * Я день потратил на точечные исправления багов и 3 на то, чтобы привести все в нормальное состояние через откат
 * изменений к изначальному состоянию и небольших доработок самого интерпретатора масок
 */
abstract class MtMask
{
	/**
	 * @return array
	 */
	public static function maskOptionRegexps()
    {
		return [
			'n' => "[0-9]",
			'a' => "[A-Z]",
			'c' => "[0-9A-Z\@]", // дополнительно включаем @ чтобы корректно валидировались наши адреса
			'h' => "[0-9A-F]",
			'x' => "[A-z0-9" . preg_quote("/-?:().,'+{}") . " ]",
			'y' => "[A-Z0-9" . preg_quote(".,-()/='+:?!\"%&*<>; ") . "]",
			'z' => "[A-Z0-9" . preg_quote(".,-()/='+:?!%&*<>;{@# \n\r") . "]",
			'e' => "[ ]",
			'd' => "[0-9\,]",
			'N' => "[\+\-]",
		];
	}

	/**
	 * @param $option
	 * @return mixed
	 */
	public static function maskOptionRegexp($option)
    {
		return self::maskOptionRegexps()[$option];
	}

	/**
	 * Для фильтрации посимвольного ввода, может потребоваться формирование отдельного регулярного выражения
	 * в том числе и для составных масок
	 * @param $mask
	 * @return string
	 */
	public static function maskFilterRegexp($mask)
    {
		$regexp = null;

		if (preg_match_all('/([nachxyzedN]{1})/', $mask, $found)) {
			$options = array_unique($found[0]);
			foreach ($options as $option) {
				$regexp .= self::maskOptionRegexp($option);
			}
		}

		return '['.str_replace(['[',']'], '', $regexp).']';
	}

	/**
	 * @param array $attributes
	 * @param array $maskScheme
	 * @return string
	 */
	public static function mtValueByMaskScheme($attributes, $maskScheme, $translitDecode, Tag $tag = null)
    {
		/**
		 * @warning по идее можно и через формирование паттерна сделать, но не правильно хотя бы
		 * из-за наличия разделителей между строками и бог весть еще каких нереализованных и неувиденных фич
		 */
		$value     = '';
		$delimiter = null;
        $pos = 0;
        $firstAttrEmpty = false;
		foreach ($attributes as $v) {
            $pos++;
			if (empty($v)) {
                // то ли фича, то ли костыль (CYB-2509) для необязательных первых элементов:
                // если элемент пустой, и он самый первый, и дальше есть еще элементы,
                // то сигнализировать сборщику, что нужно будет пристегнуть delimiter перед value,
                // даже если value будет на этот момент пустым
                if ($pos == 1 && count($attributes) > 1) {
                    $firstAttrEmpty = true;
                }

				next($maskScheme['scheme']);

				continue;
			}

			$mask = current($maskScheme['scheme']);
            $isTransliterable = isset($translitDecode[$pos])
                    && $translitDecode[$pos]
                    && self::isMaskItemTransliterable($mask);

			if (isset($mask['delimiter']) && $mask['delimiter']) {
				$delimiter = $mask['delimiter'];
			} elseif (isset($mask['scheme'][0]['delimiter']) && $mask['scheme'][0]['delimiter']) {
				$delimiter = $mask['scheme'][0]['delimiter'];
			}

          //  echo "name = " . $tag->name . " v=" . $v . " self transliterable=" . self::isMaskItemTransliterable($mask) . "<br>\n";
            

			$value .=
				/** если у нас значение уже приросло и был ранее указано использование разделителя, ставим его
				 * (на случай нескольких опциональных полей на следующей строке)
				 * дважды использован не будет, т.к. пустые значения мы пропускаем */
				(($value || $firstAttrEmpty) && $delimiter ? $delimiter : null)
				. (isset($mask['prefix']) ? $mask['prefix'] : null)
				. (isset($mask['code']) ? $mask['code'] : null)
				. ($isTransliterable ? MtHelper::translitDecode($v, $tag) : $v)
				. (isset($mask['postfix']) ? $mask['postfix'] : null);

			$delimiter = null; // сбрасываем разделитель, если был то отработал
            $firstAttrEmpty = false; // сбрасываем флаг первого пустого атрибута, если был то отработал

			next($maskScheme['scheme']);
		}

		reset($maskScheme['scheme']);

		return $value;
	}

	/**
	 * @param array $mask
	 * @return bool
	 */
	protected static function isMaskItemTransliterable($mask)
    {
		return (isset($mask['option']) && $mask['option'] !== 'n');
	}

	/**
	 * Разбор маски поля и формирование детальной схемы
	 * @param string $mask
	 * @return array
	 */
	public static function maskScheme($mask)
    {
		/**
		 * в некоторых случаях не удается автоматически соотнести маску со схемой поля
		 * для этого вводим доп. разделитель, присутствие которого однозначно говорит о структуре подполей
		 */
		$strictDelimiter = "~";
		if (substr_count($mask, $strictDelimiter)) {
			$struct = explode($strictDelimiter, $mask);
			foreach ($struct as $i => &$v) {
				$maskScheme = self::parseMask($v, $i);
				if (count($maskScheme) === 1) {
					$v = $maskScheme[0];
					continue;
				}
				$v           = self::generalizeMaskScheme($maskScheme);
				$v['scheme'] = $maskScheme;
			}
			return $struct;
		} else {
			return self::parseMask($mask);
		}
	}

	/**
	 * Обобщение схемы
	 * @param $maskScheme
	 * @return array
	 */
	public static function generalizeMaskScheme($maskScheme)
    {
		$generalScheme = [
			'mask'          => null,
			'length'        => 0,
			'rows'          => 0,
			// пока считаем что всегда есть минимум одна строка @todo подумать над использованием, пока затычка не всегда верная
			'isOptional'    => false,
			'delimiter'     => null,
			'prefix'        => null,
			'code'          => null,
			'regexpValue'   => null,
			'regexpMt'      => null,
			'regexpMtKey'   => null,
			'regexpMtNamed' => null,
			'postfix'       => null,
			'scheme'        => $maskScheme,
		];

		$c = count($maskScheme);
		for ($i = 0; $i < $c; $i++) {
			$generalScheme['mask'] .= $maskScheme[$i]['mask'];
			$generalScheme['length'] += $maskScheme[$i]['length'];
			$generalScheme['rows'] += $maskScheme[$i]['rows'];
			$generalScheme['regexpValue'] .= $maskScheme[$i]['regexpMt'];
			$generalScheme['regexpMt'] .= $maskScheme[$i]['regexpMt'];
			$generalScheme['regexpMtNamed'] .= self::maskRegexpMtValue($maskScheme[$i], $maskScheme[$i]['regexpMtKey']);
		}

		$generalScheme['regexpMtKey'] = MtHelper::getIdentifierByString(serialize($generalScheme));

		$length = strlen($generalScheme['mask']);
		if ($generalScheme['mask'][0] === '[' && $generalScheme['mask'][$length - 1] === ']') {
			$generalScheme['isOptional'] = true;
		}

		// забираем разделитель первого элемента к себе наверх
		// он понадобится при построении формы
		if (isset($maskScheme[0]['delimiter'])) {
			$generalScheme['delimiter'] = $maskScheme[0]['delimiter'];
		}

		return $generalScheme;
	}

	/**
	 * @return string
	 */
	protected static function maskValuePattern()
    {
		return "((?P<rows>[0-9]{1,3})\*|)(?P<length>[0-9]{1,4}|)(?P<lengthStrict>\!|)(?P<option>n|a|c|h|x|y|z|e|d|N)";
	}

	/**
	 * @param string $mask
	 * @return array
	 */
	protected static function parseMask($mask, $keyPrefix = null)
    {
		/**
		 * prefix:
		 * "." добавлена для работы с RUR (ЦБ)
		 * code:
		 * в первую подмаску 0-9 добавлены в связи с кастомными масками от ЦБ
		 * вторая подмаска добавлена также для работы с масками ЦБ
		 */
		$pattern =
			"/"
			. "(?P<delimiter>[\r\n]{1,2}|)"
			. "(?P<isOptionalStart>\[|)"
			. "(?P<prefix>\/{1,2}|\:|\,|\.|)"
			. "(?P<code>[A-Z0-9]{2,16}\/|[A-Z]{2,16}|)"
			// !!! + отвечает насколько жадно будет парситься маска,
			. "(?P<value>(" . self::maskValuePattern() . ")+)"
			. "(?P<postfix>[\/]{1,2}|\:|)"
			. "(?P<isOptionalEnd>\]|)"
			. "/m";

        $found = [];
		if (!preg_match_all($pattern, $mask, $found, PREG_SET_ORDER)) {
			return null;
		}

        $struct = [];
		$c      = count($found);
		for ($i = 0; $i < $c; $i++) {
			$item = [
				'mask'            => $found[$i][0],
				'delimiter'       => $found[$i]['delimiter'],
				'isOptionalStart' => (bool)$found[$i]['isOptionalStart'],
				'prefix'          => $found[$i]['prefix'],
				'code'            => $found[$i]['code'],
				'value'           => $found[$i]['value'],
				'postfix'         => $found[$i]['postfix'],
				'isOptionalEnd'   => (bool)$found[$i]['isOptionalEnd'],
			];
			if ($found[$i]['isOptionalStart'] && $found[$i]['isOptionalEnd']) {
				$item['isOptional'] = true;
			} else {
				$item['isOptional'] = false;
			}
			$item['regexpValue']   = self::maskValueRegexp($item);
			$item['regexpMt']      = self::maskRegexpMtValue($item, null);
			$item['regexpMtKey']   = MtHelper::getIdentifierByString($keyPrefix . serialize($item) . $i);
			$item['regexpMtNamed'] = self::maskRegexpMtValue($item, $item['regexpMtKey']);

            if (isset($found[$i]['option'])) {
                $item['option'] = $found[$i]['option'];
            }

			$struct[$i] = $item;
		}

		return $struct;
	}

	/**
	 * @param array $maskItemScheme
	 * @return string
	 */
	public static function maskValueRegexp(&$maskItemScheme)
    {
		$regexp               = '';
		$maskItemScheme['length'] = 0;
		$maskItemScheme['rows']   = 0;

		preg_match_all("/" . self::maskValuePattern() . "/", $maskItemScheme['value'], $found, PREG_SET_ORDER);
		$c = count($found);
		for ($i = 0; $i < $c; $i++) {
			$found[$i]['length'] = $found[$i]['length'] ? $found[$i]['length'] : 0;
			$found[$i]['rows'] = $found[$i]['rows'] ? $found[$i]['rows'] : 0;
			$regexp .= self::maskOptionRegexp($found[$i]['option']);

			/**
			 * В regexp некоторых опций входит перевод строки, но на некоторых масках регулярка строится
             * недостаточная для корректного парсинга значений, особенно если первая строка опциональная
			 * поэтому наиболее надежным способом будет включать перевод строки в регулярку
             * только в случае заданного многострочного поля
             *
			 * см. документацию Standards_MT_General_Information раздел 6
			 */
			// временно отключил,
			// @todo скорее всего в какой-то запаре придумано, если багов с парсингом многострочных полей или аттрибутов
			// не будет, удалить нафиг
//			if ($found[$i]['rows'] > 1 && in_array($found[$i]['option'], ['x', 'z'])) {
//				$regexp = str_replace(']', '\v]', $regexp);
//			}

			if ($found[$i]['lengthStrict']) {
				$regexp .= '{' . $found[$i]['length'] . '}';
			} else if ($found[$i]['length']) {
				$regexp .= '{1,' . $found[$i]['length'] . '}';
			} else {
				$regexp .= '{1,}';
			}

			/**
			 * ограничиваем кол-во входящих строк
			 */
			if (isset($found[$i]['rows']) && $found[$i]['rows'] > 1) {
				$regexp = "({$regexp}([\r\n]{1,2}|$)){1,{$found[$i]['rows']}}";
			}

			$maskItemScheme['length'] += $found[$i]['length'];
			$maskItemScheme['rows'] += $found[$i]['rows'];
		}

		return $regexp;
	}


	/**
	 * Формируем выраажение для парсинга значения пришедшего из MT документа
	 * при этом обозначаем именно значение, которое доступно для изменения именем
	 * @param array $maskItemScheme извиняюсь за подход, но в такой реализации в maskItemScheme
	 *                              добавляюю реакцию на указатель на предыдущий элемент маски и реакцию на него,
	 *                              а именно prevItem, задавать его придется перед вызовом
	 * @param string|null $keyName
	 * @param bool $ignoreDelimiter
	 * @return string
	 */
	public static function maskRegexpMtValue($maskItemScheme, $keyName = null, $ignoreDelimiter = false)
    {
		$regexp = '';

		if ($keyName) {
			$regexp .= '(?P<' . $keyName . '>' . $maskItemScheme['regexpValue'] . ')';
		} else {
			$regexp .= $maskItemScheme['regexpValue'];
		}

		if (isset($maskItemScheme['code'])) {
			$regexp = $maskItemScheme['code'] . $regexp;
		}

		if (isset($maskItemScheme['prefix'])) {
			$regexp = preg_quote($maskItemScheme['prefix']) . $regexp;
		}

		if (isset($maskItemScheme['postfix'])) {
			$regexp .= preg_quote($maskItemScheme['postfix']);
		}

		// разделитель используем только когда формируем выражение с поименованным элементом
		if (!$ignoreDelimiter && isset($maskItemScheme['delimiter']) && $maskItemScheme['delimiter']) {
			$regexp = "(?:[\r\n]{1,2}|^)" . $regexp;
		}

		if (isset($maskItemScheme['isOptional']) && $maskItemScheme['isOptional']) {
			$regexp = '(?:' . $regexp . '|)';
		}

		return $regexp;
	}

	/**
	 * Generate regexp validation rule
	 * @param $attribute
	 * @param $scheme
	 * @return array|null
	 */
	public static function getRegexpValidatorByMaskScheme($attribute, $label, $scheme)
    {
		/**
		 * @todo из-за недостаточной отлаженности механизма интерпретации и применения масок,
		 * в ненужном в общем то месте, встречаются вложенные, такие маски не используем во избежании кривой валидации
		 * необходимо провести рефакторинг
		 */
		if (isset($scheme['scheme']) && count($scheme['scheme']) == 1) {
			$scheme = $scheme['scheme'][key($scheme['scheme'])];
		} else if (!isset($scheme['regexpValue']) || !$scheme['regexpValue']) {
			return null;
		}

		return [
			/** @warning вот уж напасть, до JS регулярка доходит с обрезанными крайними символами */
			$attribute, 'match',
			'pattern'                => '~^' . $scheme['regexpValue'] . '$~',
			'message'                => \Yii::t('doc/mt',
				'Value "{value}" does not match the mask "{mask}"', [
					'mask'      => trim($scheme['mask']),
					'attribute' => $label
				]
			),
			'enableClientValidation' => false,
		];
	}

	/**
	 * Generate string validation rule
	 * @param $attribute
	 * @param $scheme
	 * @return array|null
	 */
	public static function getStringValidatorByMaskScheme($attribute, $scheme)
    {
		if (!isset($scheme['length']) || !$scheme['length']) {
			return null;
		}
		$rule = [$attribute, 'string'];

		// т.к. в рамках маски могут быть многострочные атрибуты, добавляем к длине 2 для учета переводов строки (/r/n|/n)
		// +-1 символ считаем приемлемым допущением, т.к. следом за этим валидатором всегда генерится валидатор по регулярному выражению
		//$maxLength = ($scheme['rows'] > 0 ? ($scheme['length'] + 2) * $scheme['rows'] : $scheme['length']);
		//if (isset($scheme['lengthStrict']) && $scheme['lengthStrict']) {
		//	$rule['length'] = $maxLength;
		//} else {
		//	$rule['length'] = [$scheme['length']];
		//}

		return $rule;
	}

}
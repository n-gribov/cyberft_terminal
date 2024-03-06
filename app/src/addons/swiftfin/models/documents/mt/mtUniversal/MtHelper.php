<?php
namespace addons\swiftfin\models\documents\mt\mtUniversal;

class MtHelper
{
    private static $noTranslate = [
        '/RPP/' => 5,
        '/UIP/' => 5,
        '/RPO/' => 5,
        '/DAS/' => 5,
        '/NZP/' => 5,
        '/ACC/' => 5,
        '/INT/' => 5,
        '/REC/' => 5,
        '/INS/' => 5,
        'POST' => 4,
        'TELG' => 4,
        'ELEK' => 4,
        'BESP' => 4
    ];

	/**
	 * Схема транслитерации строки
	 * @return array
	 */
	public static function translitEncodeMap(Tag $tag = null)
    {
		return [
			'А' => "A",
			'Б' => "B",
			'В' => "V",
			'Г' => "G",
			'Д' => "D",
			'Е' => "E",
			'Ё' => "o",
			'Ж' => "J",
			'З' => "Z",
			'И' => "I",
			'Й' => "i",
			'К' => "K",
			'Л' => "L",
			'М' => "M",
			'Н' => "N",
			'О' => "O",
			'П' => "P",
			'Р' => "R",
			'С' => "S",
			'Т' => "T",
			'У' => "U",
			'Ф' => "F",
			'Х' => "H",
			'Ц' => "C",
			'Ч' => "c",
			'Ш' => "Q",
			'Щ' => "q",
			'Ъ' => "x",
			'Ы' => "Y",
			'Ь' => "X",
			'Э' => "e",
			'Ю' => "u",
			'Я' => "a",
			"'" => "j",
			'’' => "j",
			'‘' => "j",
			'`' => "j",
			'№' => "n",
			'#' => "n",
			'%' => "p",
			'&' => "d",
			'!' => "b",
			'$' => "s",
			';' => "v",
			'|' => "/",
			'_' => "z",
			'=' => "r",
			'<' => "(",
			'>' => ")",
			'[' => "(",
			']' => ")",
			'{' => "(",
			'}' => ")",
			'”' => "m",
			'“' => "m",
			'"' => "m",
			'«' => "m",
			'»' => "m",
			'*' => "f",
			// !warning текущая логика транслитерации портит значения некоторых полей, отключаю данный символ
			// @todo доработать транслитерацию значений и аттрибутов тэга в соотв. с полной логикой транслитерации
			// можно использовать текущую логику обратной транслитерации
//			'@' => "f",
			'^' => "f",
			'~' => "f",
		];
	}

	/**
	 * Схема ДЕтранслитерации
	 * просто ревертнуть схему транслитерации нельзя, т.к. есть отличия
	 * @return array
	 */
	public static function translitDecodeMap(Tag $tag = null)
    {
		return [
			"A" => 'А',
			"B" => 'Б',
			"V" => 'В',
			"G" => 'Г',
			"D" => 'Д',
			"E" => 'Е',
			"o" => 'Ё',
			"J" => 'Ж',
			"Z" => 'З',
			"I" => 'И',
			"i" => 'Й',
			"K" => 'К',
			"L" => 'Л',
			"M" => 'М',
			"N" => 'Н',
			"O" => 'О',
			"P" => 'П',
			"R" => 'Р',
			"S" => 'С',
			"T" => 'Т',
			"U" => 'У',
			"F" => 'Ф',
			"H" => 'Х',
			"C" => 'Ц',
			"c" => 'Ч',
			"Q" => 'Ш',
			"q" => 'Щ',
			"x" => 'Ъ',
			"Y" => 'Ы',
			"X" => 'Ь',
			"e" => 'Э',
			"u" => 'Ю',
			"a" => 'Я',
			//"j" => "'",
			"n" => '№',
			"p" => '%',
			"d" => '&',
			"b" => ',',
			"s" => '$',
			"v" => ';',
			"z" => '_',
			"r" => '=',
			"m" => '"',
			"f" => '*',
		];
	}

	/**
	 * @param string $value
	 * @return string
	 */
	public static function translitEncode($value, Tag $tag = null)
    {
		$map = self::translitEncodeMap($tag);
		$cyrillic = array_keys($map);
		$latin = array_values($map);

		// тут на всякий случай кирилицу в нижнем регистре приводим к верхнему для корректной работы транслитерации
		$searchLower = $cyrillic;

		array_walk($searchLower, function (&$item) {
			$item = mb_strtolower($item, \Yii::$app->charset);
		});

		$value = str_replace($searchLower, $cyrillic, $value);

		return str_replace($cyrillic, $latin, $value);
	}

	/**
	 * @param string $value
	 * @return string
	 * @throws \Exception
	 */
	public static function translitDecode($value, Tag $tag = null)
    {
        // Специфические случаи транслитерации здесь.
        // Если их будет слишком много, нужно писать разные классы транслитераторов,
        // а из MtHelper сделать фабрику, которая инстанцирует нужный класс по переданному ей тэгу.
        // Также инстансы классов нужно будет кешировать в фабрике, чтобы не инстанцировать повторно на каждый тэг.

        $out = '';
        
 		$map = self::translitDecodeMap($tag);

        $length = strlen($value);
        for ($pos = 0; $pos < $length; $pos++) {
            $c = $value{$pos};
            if ($c == 'j') {
                if ($pos < $length - 1) {
                    $newPos = strpos($value, 'j', $pos + 1);

                    if ($newPos === false) {
                        $newPos = $length - 1;
                    }

                    $chunk = substr($value, $pos + 1, $newPos - $pos - 1);

                    if ($tag->name == '70' || $tag->name == '72') {

                        if (substr($chunk, 0, 3) == '(VO') {
                            $p = strlen($chunk) -1;
                            if ($chunk{$p} == ')') {
                                $chunk{0} = '{';
                                $chunk{$p} = '}';
                            }
                        }
                    }

                    $out .= $chunk;
                    $pos = $newPos;
                }

                continue;
            }

            foreach(self::$noTranslate as $pattern => $patternLength) {
                if ($pos + $patternLength < $length) {
                    if (substr($value, $pos, $patternLength) == $pattern) {
                        $out .= $pattern;
                        $pos += $patternLength - 1;
                        continue 2;
                    }
                }
            }

            if (isset($map[$c])) {
                $out .= $map[$c];
            } else {
                $out .= $c;
            }
        }

        return $out;
	}

	/**
	 * @param $str
	 * @return string
	 */
	public static function getIdentifierByString($str)
    {
		return 'k' . substr(base_convert(hash('sha256', $str), 10, 36), 0, 8);
	}
}
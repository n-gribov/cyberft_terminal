<?php

namespace common\helpers;

use addons\edm\models\PaymentStatusReport\PaymentStatusReportType;
use NumberFormatter;
use Yii;

class NumericHelper
{
    const SUFFIX_0 = 0; // 'документов'
    const SUFFIX_1 = 1; // 'документ', строго 1 (для еnglish)
    const SUFFIX_2 = 2; // 'документа'
    const SUFFIX_21 = 3; // 'документ', типа '21 документ'

    /**
     * Выдает нужное окончание для русских числительных
     */
    public static function getPluralSuffix($num)
    {
        if ($num == 1) {
            return self::SUFFIX_1;
        }

        if ($num > 20) {
            $num %= 20;
        }

        switch ($num) {
            case 1:
                return self::SUFFIX_21;
            case 2:
            case 3:
            case 4:
                return self::SUFFIX_2;
            default:
                return self::SUFFIX_0;
        }
    }

    public static function getFloatValue($sum)
    {
        if (is_float($sum)) {
            return $sum;
        }

        $nf = new NumberFormatter(Yii::$app->language, NumberFormatter::DECIMAL);

        return floatval($nf->parse($sum));
    }

    public static function num2str($num, $currency)
    {
        $nul = 'ноль';
        $ten = [
            ['', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'],
            ['', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять']
        ];
        $a20 = ['десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать',
            'шестнадцать', 'семнадцать','восемнадцать', 'девятнадцать'];
        $tens = [2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто'];
        $hundred = ['', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот'];
        $unit = [];

        if ($currency == 'USD') {
            // Для валютного значения вводим другие обозначения денежных единиц
            $unit[] = ['цент', 'цента', 'центов', 1];
            $unit[] = ['доллар США', 'доллара США', 'долларов США', 0];
        } elseif ($currency == 'EUR') {
            $unit[] = ['цент', 'цента', 'центов', 1];
            $unit[] = ['евро', 'евро', 'евро', 0];
        } elseif ($currency == 'RUB' || $currency == 'RUR') {
            $unit[] = ['копейка', 'копейки', 'копеек', 1];
            $unit[] = ['рубль', 'рубля', 'рублей', 0];
        } else {
            $unit[] = ['цент', 'цента', 'центов', 1];
            $unit[] = [$currency, $currency, $currency, 0];
        }

        $unit[] = ['тысяча', 'тысячи', 'тысяч', 1];
        $unit[] = ['миллион', 'миллиона', 'миллионов', 0];
        $unit[] = ['миллиард', 'милиарда', 'миллиардов', 0];

        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();

        if (intval($rub) > 0) {
            foreach(str_split($rub, 3) as $uk => $v) {
                if (!intval($v)) {
                    continue;
                }

                $uk = sizeof($unit) - $uk - 1;
                $gender = $unit[$uk][3];

                list($i1, $i2, $i3) = array_map('intval', str_split($v,1));
                $out[] = $hundred[$i1];

                if ($i2 > 1) {
                    $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3];
                } else{
                    $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3];
                }

                if ($uk > 1) {
                    $out[] = self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
                }
            }
        } else {
            $out[] = $nul;
        }

        $out[] = self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . self::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop

        return trim(preg_replace('/ {2,}/', ' ', join(' ', $out)));
    }

    public static function morph($num, $f1, $f2, $f5)
    {
        $num = abs(intval($num)) % 100;

        if ($num > 10 && $num < 20) {
            return $f5;
        }

        $num %= 10;

        if ($num > 1 && $num < 5) {
            return $f2;
        }

        if ($num == 1) {
            return $f1;
        }

        return $f5;
    }

}
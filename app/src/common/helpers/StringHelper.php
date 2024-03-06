<?php

namespace common\helpers;

use yii\helpers\BaseStringHelper;

class StringHelper extends BaseStringHelper
{
    /**
     * @inheritdoc
     */
    public static function utf8($content, $encoding = null)
    {
        if ($encoding && !static::isUtf8($content)) {
            return static::toUtf8($content, $encoding);
        }

        return $content;
    }

    public static function toUtf8($content, $encoding)
    {
        return mb_convert_encoding($content, 'UTF-8', $encoding);
    }

    public static function isUtf8($content)
    {
        return mb_check_encoding($content, 'UTF-8');
    }

    public static function mb_wordwrap($str, $width)
    {
        // обычная фунция php wordwrap не мультибайтная

        $lines = [];
        $words = explode(' ', $str);

        $currentLength = 0;
        $basePos = 0;

        foreach($words as $pos => $word) {
            $wordLength = mb_strlen($word);
            $currentLength += $wordLength;
            if ($basePos < $pos) {
                $currentLength++;
            }
            if ($currentLength > $width) {
                if ($basePos < $pos) {
                    $lines[] = implode(' ', array_slice($words, $basePos, $pos - $basePos));
                    $currentLength = $wordLength;
                    $basePos = $pos;
                }
                if ($wordLength >= $width) {
                    $lines[] = $word;
                    $basePos++;
                    $currentLength = 0;
                }
            }
        }

        if ($currentLength) {
            $lines[] = implode(' ', array_slice($words, $basePos));
        }

        return $lines;
    }

    /**
     * Проверяет наличие BOM
     * @param type $body
     * @return type
     */
    public static function hasBOM($body)
    {
        return (strlen($body) > 2
                && ord($body[0]) == 0xef
                && ord($body[1]) == 0xbb
                && ord($body[2]) == 0xbf
        );
    }

    /**
     * Удаляет BOM
     * @param type $body
     * @return type
     */
    public static function fixBOM($body)
    {
        return static::hasBOM($body) ? substr($body, 3) : $body;
    }

    /**
     * функции mb_substr() и mb_strpos() не учитывают наличие BOM
     * и в случае с BOM начало строки у этих функций будет не 0, а 1
     * @param type $body
     * @return type
     */
    public static function getBOMOffset($body)
    {
        return (int) static::hasBOM($body);
    }

    public static function mtMultiLine($str)
    {
        //убираем спецсимволы, для того, чтобы строка переносилась при 35 символах
        $str = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $str);
        $lines = str_split($str, 35);
        $mtMultiLine = '';

        foreach ($lines as $line) {
            $mtMultiLine .= $line.'<br/>';
        }

        return $mtMultiLine;
    }

    public static function packString($s, $before = null, $after = null)
    {
        if (!$before) {
            $before = 1024;
        }

        if (!$after) {
            $after = 32;
        }

        if (strlen($s) < $before) {
            return $s;
        }

        return substr($s, 0, $before) . '...' . substr($s, -$after);
    }

    public static function mb_lcfirst($string)
    {
        if ($string === null) {
            return null;
        }

        $first = mb_substr($string, 0, 1);

        return mb_convert_case($first, MB_CASE_LOWER) . mb_substr($string, 1);
    }

    public static function hasXmlHeader($body)
    {
        $header = mb_substr($body, static::getBOMOffset($body), 5);

        return $header == '<?xml';
    }

    public static function fixXmlHeader($body)
    {
        $body = static::fixBOM($body);

        if (mb_substr($body, 0, 5) != '<?xml') {
            $body = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $body;
        }

        return $body;
    }

    public static function removeXMLHeader($body)
    {
        return trim(preg_replace('/^.*?\<\?xml.*\?>/uim', '', $body));
    }

}


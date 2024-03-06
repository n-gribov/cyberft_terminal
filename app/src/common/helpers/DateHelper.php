<?php

namespace common\helpers;

class DateHelper
{
    const DATETIME_FORMAT   = 'php:Y-m-d H:i:s';
    const DATE_FORMAT       = 'php:Y-m-d';
    const TIME_FORMAT       = 'php:H:i:s';

    /**
     * Convert date
     *
     * @param string  $dateStr  Date to convert
     * @param string  $type     Type of need date
     * @param string  $format   Date format
     * @see http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#asDate()-detail
     * @return string
     */
    public static function convert($dateStr, $type = 'date', $format = null)
    {
        switch ($type) {
            case 'datetime':
                $fmt = ($format == null) ? self::DATETIME_FORMAT : $format;
                break;
            case 'time':
                $fmt = ($format == null) ? self::TIME_FORMAT : $format;
                break;
            default :
                $fmt = ($format == null) ? self::DATE_FORMAT : $format;
                break;
        }
        return \Yii::$app->formatter->asDate($dateStr, $fmt);
    }

    public static function formatDate($date, $type = 'date')
    {
        // Если дата пустая, то возвращаем пустую строку
        if (empty($date)) {
            return '';
        }

        // Собираем возможные регулярные выражения,
        // для определения формата даты нужного для
        // createFromFormat

        $datePatterns = [
            [
                '/([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})\+([0-9]{1,2}):([0-9]{1,2})/',
                'Y-m-d\TH:i:sT'
            ],
            [
                '/([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})T([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/',
                'Y-m-d\TH:i:s'
            ],
            [
                '/([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})\s([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/',
                'Y-m-d H:i:s'
            ],
            [
                '/([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})/',
                'Y-m-d'
            ],
            [
                '/([0-9]{1,2})\-([0-9]{1,2})\-([0-9]{4})/',
                'd-m-Y'
            ],
            [
                '/([0-9]{4})\.([0-9]{1,2})\.([0-9]{1,2})/',
                'Y.m.d'
            ],
            [
                '/([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})/',
                'd.m.Y'
            ],
        ];

        $dateFormat = '';

        foreach($datePatterns as $pattern) {
            if (preg_match($pattern[0], $date)) {
                $dateFormat = $pattern[1];

                break;
            }
        }

        switch($type) {
            case 'datetime':
                $format = 'd.m.Y H:i:s';
                break;
            case 'date':
            default:
                $format = 'd.m.Y';
        }

        $dateObj = \DateTime::createFromFormat($dateFormat, $date);
        if (!empty($dateObj)) {
			$timestamp = $dateObj->getTimestamp();

            return date($format, $timestamp);
		} else {
			return $date;
		}
    }

    /**
     * Функция корректно конвертирует timestamp в нужный формат
     * Yii2 formatter представляет время с опережением
     */
    public static function convertFromTimestamp($timestamp, $type = 'date')
    {
        switch ($type) {
            case 'datetime':
                $fmt = 'Y-m-d H:i:s';

                break;
            case 'time':
                $fmt = 'Y-m-d';

                break;
            default:
                $fmt = 'H:i:s';
                break;
        }

        return date($fmt, $timestamp);
    }

    public static function convertFormat(string $value, string $from, string $to): ?string
    {
        $dateTime = \DateTime::createFromFormat($from, $value);
        if ($dateTime === false) {
            return null;
        }
        return $dateTime->format($to);
    }

    public static function getMinutesFromNow(\DateTimeInterface $dateTime): int
    {
        $now = new \DateTime();
        return intval(($dateTime->getTimestamp() - $now->getTimestamp()) / 60);
    }
}

<?php

namespace addons\edm\models\IBank\V2;

use addons\edm\models\IBank\IBankDocumentsPack;
use common\helpers\FileHelper;

class IBankV2Parser
{
    const HEADER_REGEX = '/^Content-Type=doc\/([\w\d]+)/';
    const COMPLEX_ROW_REGEX = '/^(\w+)\.(\d)\.(.+)=(.+)$/'; //INFO.0.COLUMN=VALUE

    public static function parse($content, $filepath)
    {
        $rawRows = preg_split('/(?:\r\n|\r|\n)/', $content);
        $rows = [];

        foreach ($rawRows as $row) {
            if (stristr($row, '=') !== false) {
                $rows[] = $row;
            } else {
                $lastElement = array_pop($rows);
                $lastElement .= ' ' . $row;
                $rows[] = $lastElement;
            }
        }

        $data = [];
        $type = null;
        $filename = FileHelper::mb_basename($filepath);
        $isInsideDocument = false;
        $documents = [];

        foreach ($rows as $row) {
            if (preg_match(self::HEADER_REGEX, $row, $matches)) {
                if ($isInsideDocument) {
                    $documents[] = new IBankV2Document($type, $data, $filename);
                    $data = [];
                    $type = null;
                }
                $type = $matches[1];
                $isInsideDocument = true;
            } else if (!$isInsideDocument) {
                throw new \Exception("Invalid document header: $row");
            } else {
                preg_match(self::COMPLEX_ROW_REGEX, $row, $matches);

                if (empty($matches)) {
                    $tagValueData = explode('=', trim($row), 2);

                    if (count($tagValueData) != 2) {
                        throw new \Exception("Invalid tag format: {$row}");
                    }

                    list($tag, $value) = $tagValueData;

                    $tag = trim($tag);
                    $value = trim($value);
                    $value = self::decodeValue($value);

                    if (empty($tag) || empty($value)) {
                        continue;
                    }

                    $data[$tag] = $value;
                } else {
                    $group = $matches[1];
                    $number = $matches[2];
                    $field = $matches[3];
                    $value = $matches[4];

                    $data[$group][$number][$field] = self::decodeValue($value);
                }
            }
        }

        $documents[] = new IBankV2Document($type, $data, $filename);

        return new IBankDocumentsPack(2, $documents);
    }

    public static function isIBankV2Document($content)
    {
        $rows = preg_split('/(?:\r\n|\r|\n)/', $content);
        if (empty($rows)) {
            return false;
        }

        return preg_match(self::HEADER_REGEX, $rows[0]) === 1;
    }

    private static function decodeValue($value)
    {
        return $value === '' ? null : iconv('windows-1251', 'UTF-8', $value);
    }

}
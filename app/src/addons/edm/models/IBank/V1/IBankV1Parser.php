<?php

namespace addons\edm\models\IBank\V1;

use addons\edm\models\IBank\IBankDocumentsPack;
use common\helpers\FileHelper;

class IBankV1Parser
{
    const HEADER_REGEX = '/^#1\|([a-z0-9]+)(_)?([a-zA-Z]+)?(?:_v(\d+))?$/i';

    public static function parse($content, $filePath)
    {
        $rows = preg_split('/(?:\r\n|\r|\n)/', $content);

        $data = [];
        $type = null;
        $isInsideDocument = false;
        $isTable = false;
        $isAttachment = false;
        $table = [];
        $tableRow = [];
        $attachment = [];
        $attachmentRow = [];
        $documents = [];

        $fileName = FileHelper::mb_basename($filePath);

        foreach ($rows as $row) {
            if (!$isInsideDocument) {
                if ($row === '') {
                    continue;
                }
                if (preg_match(self::HEADER_REGEX, $row, $matches)) {
                    $type = $matches[1];
                    $isInsideDocument = true;
                } else {
                    throw new \Exception("Invalid document header: $row");
                }
            } elseif ($row === ';end') {
                $documents[] = new IBankV1Document($type, $data, $fileName);
                $type = null;
                $data = [];
                $isInsideDocument = false;
            } elseif ($row === ';NestedTable') {
                $isTable = true;
            } elseif ($row === ';EndNestedTable') {
                $isTable = false;
                if (!empty($tableRow)) {
                    $table[] = $tableRow;
                }
                $data[] = $table;
                $table = [];
                $tableRow = [];
            } elseif ($row === ';EndNestedTableRow') {
                $table[] = $tableRow;
                $tableRow = [];
            } elseif ($row === ';attachment') {
                $isAttachment = true;
            } elseif ($row === ';endattachmentfile') {
                $attachment[] = $attachmentRow;
                $attachmentRow = [];
            } elseif ($row === ';endattachment') {
                $isAttachment = false;
                if (!empty($attachmentRow)) {
                    $attachment[] = $attachmentRow;
                }
                $data[] = $attachment;
                $attachment = [];
                $attachmentRow = [];
            } elseif ($isTable) {
                $tableRow[] = static::decodeValue($row);
            } elseif ($isAttachment) {
                $attachmentRow[] = static::decodeValue($row);
            } else {
                $data[] = static::decodeValue($row);
            }
        }

        if ($isInsideDocument) {
            throw new \Exception('No end tag found');
        }

        if (!empty($table) || $isTable) {
            throw new \Exception('Got unclosed table');
        }

        if (!empty($tableRow)) {
            throw new \Exception('Got unclosed table row');
        }

        return new IBankDocumentsPack(1, $documents);
    }

    public static function isIBankV1Document($content)
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

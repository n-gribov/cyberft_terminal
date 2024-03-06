<?php

namespace common\helpers\vtb;

use common\models\vtbxml\documents\BSDocumentAttachment;


class BSDocumentAttachmentSerializer
{
    const ATTACHMENT_ATTRIBUTES = ['fileName', 'icon16Content', 'icon32Content', 'fileContent'];
    const HEX_BYTE_LENGTH = 2; // 1 byte in hex = 2 chars
    const LENGTH_FIELD_LENGTH = 4; // length of attachment part is stored in 4 bytes

    /**
     * @param BSDocumentAttachment[] $attachments
     * @return string
     */
    public static function serialize($attachments)
    {
        if (empty($attachments)) {
            return null;
        }

        return array_reduce(
            $attachments,
            function ($carry, $attachment) {
                return $carry . static::serializeAttachment($attachment);
            },
            ''
        );
    }

    private static function serializeAttachment(BSDocumentAttachment $attachment)
    {
        if (empty($attachment->fileName) || empty($attachment->fileContent)) {
            return null;
        }

        $attachmentAttributes = [
            'fileName' => iconv('UTF-8', 'windows-1251', $attachment->fileName),
            'icon16Content' => $attachment->icon16Content,
            'icon32Content' => $attachment->icon32Content,
            'fileContent' => $attachment->fileContent,
        ];

        return array_reduce(
            array_keys($attachmentAttributes),
            function ($carry, $attribute) use ($attachmentAttributes) {
                $partContent = $attachmentAttributes[$attribute];
                $length = strlen($partContent);
                $lengthEncoded = static::encodeLength($length);
                return $carry . $lengthEncoded . $partContent;
            },
            ''
        );
    }

    /**
     * @param $attachmentContent
     * @return BSDocumentAttachment[]
     */
    public static function deserialize($attachmentContent)
    {
        $attachmentContentHex = bin2hex($attachmentContent);
        $attachmentAttributesList = static::ATTACHMENT_ATTRIBUTES;

        $attachments = [];
        $offset = 0;
        $i = 0;
        while ($offset < strlen($attachmentContent)) {
            if ($i === 0) {
                $attachmentAttributes = [
                    'fileName' => null,
                    'icon16Content' => null,
                    'icon32Content' => null,
                    'fileContent' => null,
                ];
            }

            $attribute = $attachmentAttributesList[$i];
            $partLength = static::extractPartLength($offset, $attachmentContentHex);
            $attachmentAttributes[$attribute] = static::extractPart(
                $offset + static::LENGTH_FIELD_LENGTH,
                $partLength,
                $attachmentContentHex
            );
            $offset += static::LENGTH_FIELD_LENGTH + $partLength;
            if ($i === count($attachmentAttributesList) - 1) {
                $i = 0;
                $attachmentAttributes['fileName'] = iconv('windows-1251', 'UTF-8', $attachmentAttributes['fileName']);
                $attachments[] = new BSDocumentAttachment($attachmentAttributes);
            } else {
                $i++;
            }
        }

        return $attachments;
    }

    private static function extractPartLength($offset, $attachmentContentHex)
    {
        $partLengthHex = substr(
            $attachmentContentHex,
            $offset * static::HEX_BYTE_LENGTH,
            static::LENGTH_FIELD_LENGTH * static::HEX_BYTE_LENGTH
        );
        return static::decodeLength($partLengthHex);
    }

    private static function extractPart($offset, $length, $attachmentContentHex)
    {
        $partHex = substr(
            $attachmentContentHex,
            $offset * static::HEX_BYTE_LENGTH,
            $length * static::HEX_BYTE_LENGTH
        );
        return hex2bin($partHex);
    }

    private static function encodeLength($length)
    {
        $lengthHex = str_pad(
            dechex($length),
            static::LENGTH_FIELD_LENGTH * static::HEX_BYTE_LENGTH,
            '0',
            STR_PAD_LEFT
        );
        return array_reduce(
            array_reverse(str_split($lengthHex, static::HEX_BYTE_LENGTH)),
            function ($carry, $byteHex) {
                return $carry . hex2bin($byteHex);
            },
            ''
        );
    }

    private static function decodeLength($partLengthHex)
    {
        return hexdec(static::reverseHexValue($partLengthHex));
    }

    private static function reverseHexValue($value)
    {
        $bytes = str_split($value, static::HEX_BYTE_LENGTH);
        return implode('', array_reverse($bytes));
    }
}

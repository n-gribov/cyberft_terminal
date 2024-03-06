<?php

namespace common\models\cyberxml;

use common\modules\monitor\models\MonitorLogAR;
use yii\base\Model;

class CyberXmlDocumentHistoryEvent extends Model
{
    public $type;
    public $time;
    public $userName;
    public $userIp;

    public static function fromDomNode(\DOMNode $node): self
    {
        return new self([
            'type'     => self::getAttributeValue($node, 'type'),
            'time'     => self::getAttributeValue($node, 'time'),
            'userName' => self::getChildNodeValue($node, 'UserName'),
            'userIp'   => self::getChildNodeValue($node, 'UserIp'),
        ]);
    }

    public static function fromMonitorLogRecord(MonitorLogAR $logRecord): self
    {
        return new self([
            'type'     => $logRecord->eventCode,
            'time'     => date('c', $logRecord->dateCreated),
            'userName' => $logRecord->user ? $logRecord->user->name : null,
            'userIp'   => $logRecord->ip,
        ]);
    }

    private static function getAttributeValue(\DOMNode $node, string $name): ?string
    {
        $attribute = $node->attributes->getNamedItem($name);
        return $attribute === null ? null : $attribute->textContent;
    }

    private static function getChildNodeValue(\DOMNode $node, string $tagName): ?string
    {
        /** @var \DOMElement $childNode */
        foreach ($node->childNodes as $childNode) {
            if ($childNode->tagName === $tagName) {
                return $childNode->textContent;
            }
        }

        return null;
    }
}

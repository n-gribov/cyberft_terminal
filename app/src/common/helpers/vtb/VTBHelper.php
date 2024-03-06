<?php

namespace common\helpers\vtb;

use addons\edm\models\DictOrganization;
use common\document\Document;
use common\document\DocumentFormatGroup;
use common\models\Terminal;
use common\models\TerminalRemoteId;
use Yii;

class VTBHelper
{
    public static function isGatewayTerminal($terminalAddress)
    {
        $vtbTerminalAddress = static::getGatewayTerminalAddress();
        if (empty($vtbTerminalAddress) || empty($terminalAddress)) {
            return false;
        }

        return $terminalAddress === $vtbTerminalAddress;
    }

    public static function getVTBCustomerId($terminalAddress)
    {
        $terminal = Terminal::findOne(['terminalId' => $terminalAddress]);
        if ($terminal === null) {
            return null;
        }

        $vtbTerminalAddress = static::getGatewayTerminalAddress();
        if (!$vtbTerminalAddress) {
            return null;
        }

        $terminalRemoteId = $terminal
            ->getRemoteIds()
            ->where(['terminalReceiver' => $vtbTerminalAddress])
            ->one();

        return $terminalRemoteId !== null ? $terminalRemoteId->remoteId : null;
    }

    public static function getGatewayTerminalAddress()
    {
        return DocumentFormatGroup::getTerminalAddressByGroup(DocumentFormatGroup::VTB);
    }

    /**
     * @param integer $customerId
     * @return DictOrganization|null
     */
    public static function getOrganizationByVTBCustomerId($customerId)
    {
        $vtbTerminalAddress = VTBHelper::getGatewayTerminalAddress();
        if (empty($vtbTerminalAddress)) {
            return null;
        }

        $terminalRemoteId = TerminalRemoteId::find()
            ->where([
                'terminalReceiver' => $vtbTerminalAddress,
                'remoteId' => $customerId
            ])
            ->one();

        return $terminalRemoteId !== null
            ? DictOrganization::findOne(['terminalId' => $terminalRemoteId->terminalId])
            : null;
    }

    public static function isVTBDocument(Document $document)
    {
        $typeModelClass = Yii::$app->registry->getTypeModelClass($document->type);
        return is_subclass_of($typeModelClass, '\addons\edm\models\BaseVTBDocument\BaseVTBDocumentType');
    }

    public static function generateDocumentNumber()
    {
        $timestamp = str_replace('.', '', microtime(true));

        return substr($timestamp, -11, 11) . rand(0, 9999);
    }

    public static function isCancellableDocument(Document $document)
    {
        if ($document->direction !== Document::DIRECTION_OUT) {
            return false;
        }

        if ($document->status !== Document::STATUS_DELIVERED) {
            return false;
        }

        if (!self::isVTBDocument($document)) {
            return false;
        }

        $extModel = $document->extModel;
        if ($extModel !== null && $extModel->hasAttribute('businessStatus')) {
            if ($extModel->businessStatus === 'RJCT') {
                return false;
            }
        }

        return true;
    }

}

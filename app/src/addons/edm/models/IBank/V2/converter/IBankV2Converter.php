<?php

namespace addons\edm\models\IBank\V2\converter;

use addons\edm\models\IBank\V2\IBankV2Document;
use common\base\BaseType;

interface IBankV2Converter
{
    /**
     * Метод создаёт тайп-модель
     * @param IBankV2Document $ibankDocument
     * @param string $senderTerminalId
     * @param string $recipientTerminalId
     * @param string[] $attachmentsFilesPaths
     * @return BaseType
     */
    public function createTypeModel(
        IBankV2Document $ibankDocument, $senderTerminalId, $recipientTerminalId,
        $attachmentsFilesPaths = []
    );
}
<?php

namespace addons\edm\models\IBank\V1\converter;

use addons\edm\models\IBank\V1\IBankV1Document;
use common\base\BaseType;

interface IBankV1Converter
{
    /**
     * Метод создаёт тайп-модель
     * @param IBankV1Document $ibankDocument
     * @param string $senderTerminalId
     * @param string $recipientTerminalId
     * @param string[] $attachmentsFilesPaths
     * @return BaseType
     */
    public function createTypeModel(
        IBankV1Document $ibankDocument, $senderTerminalId, $recipientTerminalId,
        $attachmentsFilesPaths = []
    );
}

<?php

namespace addons\edm\models\IBank\common\converter;

use addons\edm\models\IBank\common\IBankDocument;
use common\base\BaseType;

interface IBankConverter
{
    /**
     * Метод создаёт тайп-модель
     * @param IBankDocument $ibankDocument
     * @param type $senderTerminalId
     * @param type $recipientTerminalId
     * @param type $attachmentsFilesPaths
     * @return BaseType
     */
    public function createTypeModel(
        IBankDocument $ibankDocument,
        $senderTerminalId,
        $recipientTerminalId,
        $attachmentsFilesPaths = []
    ): BaseType;

    public function createExtModelAttributes(BaseType $typeModel): array;

    public function getExtModelClass(): string;
}

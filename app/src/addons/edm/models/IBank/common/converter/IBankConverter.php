<?php

namespace addons\edm\models\IBank\common\converter;

use addons\edm\models\IBank\common\IBankDocument;
use common\base\BaseType;

interface IBankConverter
{
    public function createTypeModel(
        IBankDocument $ibankDocument,
        $senderTerminalId,
        $recipientTerminalId,
        $attachmentsFilesPaths = []
    ): BaseType;

    public function createExtModelAttributes(BaseType $typeModel): array;

    public function getExtModelClass(): string;
}

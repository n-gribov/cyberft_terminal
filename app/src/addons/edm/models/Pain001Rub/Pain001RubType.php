<?php

namespace addons\edm\models\Pain001Rub;

use addons\ISO20022\models\Pain001Type;

class Pain001RubType extends Pain001Type
{
    const TYPE = 'pain.001.RUB';
    const TRANSPORT_TYPE = Pain001Type::TYPE;

    public function getDate(): ?string
    {
        return (string)$this->_xml->CstmrCdtTrfInitn->GrpHdr->CreDtTm ?: null;
    }
}

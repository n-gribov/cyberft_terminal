<?php

namespace addons\edm\models\VTBContractUnReg;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\ContractUnReg;

class VTBContractUnRegType extends BaseVTBDocumentType
{
    const TYPE = 'VTBContractUnReg';
    const VTB_DOCUMENT_CLASS = ContractUnReg::class;
}
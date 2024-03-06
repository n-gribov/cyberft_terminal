<?php

namespace addons\edm\models\VTBContractReg;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\ContractReg;

class VTBContractRegType extends BaseVTBDocumentType
{
    const TYPE = 'VTBContractReg';
    const VTB_DOCUMENT_CLASS = ContractReg::class;
}

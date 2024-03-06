<?php

namespace addons\edm\models\VTBContractChange;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\ContractChange;

class VTBContractChangeType extends BaseVTBDocumentType
{
    const TYPE = 'VTBContractChange';
    const VTB_DOCUMENT_CLASS = ContractChange::class;
}
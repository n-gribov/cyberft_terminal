<?php

namespace addons\edm\models\VTBCredReg;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\CredReg;

class VTBCredRegType extends BaseVTBDocumentType
{
    const TYPE = 'VTBCredReg';
    const VTB_DOCUMENT_CLASS = CredReg::class;
}

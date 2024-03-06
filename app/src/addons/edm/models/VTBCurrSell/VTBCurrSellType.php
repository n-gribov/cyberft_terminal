<?php

namespace addons\edm\models\VTBCurrSell;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\CurrSell;

class VTBCurrSellType extends BaseVTBDocumentType
{
    const TYPE = 'VTBCurrSell';
    const VTB_DOCUMENT_CLASS = CurrSell::class;
}

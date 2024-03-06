<?php

namespace addons\edm\models\VTBCurrBuy;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\CurrBuy;

class VTBCurrBuyType extends BaseVTBDocumentType
{
    const TYPE = 'VTBCurrBuy';
    const VTB_DOCUMENT_CLASS = CurrBuy::class;
}

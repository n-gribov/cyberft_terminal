<?php

namespace addons\edm\models\VTBCurrDealInquiry181i;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\CurrDealInquiry181i;

class VTBCurrDealInquiry181iType extends BaseVTBDocumentType
{
    const TYPE = 'VTBCurrDealInquiry181i';
    const VTB_DOCUMENT_CLASS = CurrDealInquiry181i::class;
}

<?php

namespace addons\edm\models\VTBCurrConversion;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\CurrConversion;

class VTBCurrConversionType extends BaseVTBDocumentType
{
    const TYPE = 'VTBCurrConversion';
    const VTB_DOCUMENT_CLASS = CurrConversion::class;
}
<?php

namespace addons\edm\models\VTBCurrConversion;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBCurrConversionCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBCurrConversion';
    const TYPE_MODEL_CLASS = VTBCurrConversionType::class;
}
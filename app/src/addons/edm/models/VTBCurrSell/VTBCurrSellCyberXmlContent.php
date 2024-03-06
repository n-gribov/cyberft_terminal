<?php

namespace addons\edm\models\VTBCurrSell;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBCurrSellCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBCurrSell';
    const TYPE_MODEL_CLASS = VTBCurrSellType::class;
}

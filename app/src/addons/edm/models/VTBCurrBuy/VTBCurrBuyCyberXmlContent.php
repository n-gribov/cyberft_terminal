<?php

namespace addons\edm\models\VTBCurrBuy;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBCurrBuyCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBCurrBuy';
    const TYPE_MODEL_CLASS = VTBCurrBuyType::class;
}

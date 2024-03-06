<?php

namespace addons\edm\models\VTBContractReg;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBContractRegCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBContractReg';
    const TYPE_MODEL_CLASS = VTBContractRegType::class;
}

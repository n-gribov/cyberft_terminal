<?php

namespace addons\edm\models\VTBContractUnReg;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBContractUnRegCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBContractUnReg';
    const TYPE_MODEL_CLASS = VTBContractUnRegType::class;
}
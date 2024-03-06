<?php

namespace addons\edm\models\VTBCredReg;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBCredRegCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBCredReg';
    const TYPE_MODEL_CLASS = VTBCredRegType::class;
}

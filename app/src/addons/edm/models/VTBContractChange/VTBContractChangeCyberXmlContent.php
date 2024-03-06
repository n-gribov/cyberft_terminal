<?php

namespace addons\edm\models\VTBContractChange;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBContractChangeCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBContractChange';
    const TYPE_MODEL_CLASS = VTBContractChangeType::class;
}
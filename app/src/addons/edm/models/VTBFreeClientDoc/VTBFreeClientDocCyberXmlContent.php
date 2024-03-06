<?php

namespace addons\edm\models\VTBFreeClientDoc;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBFreeClientDocCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBFreeClientDoc';
    const TYPE_MODEL_CLASS = VTBFreeClientDocType::class;
}

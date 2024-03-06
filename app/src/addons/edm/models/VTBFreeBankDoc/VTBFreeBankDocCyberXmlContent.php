<?php

namespace addons\edm\models\VTBFreeBankDoc;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBFreeBankDocCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBFreeBankDoc';
    const TYPE_MODEL_CLASS = VTBFreeBankDocType::class;
}

<?php

namespace addons\edm\models\VTBStatementRu;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBStatementRuCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBStatementRu';
    const TYPE_MODEL_CLASS = VTBStatementRuType::class;
}

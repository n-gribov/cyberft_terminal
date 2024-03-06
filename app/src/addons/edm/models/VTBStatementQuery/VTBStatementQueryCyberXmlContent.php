<?php

namespace addons\edm\models\VTBStatementQuery;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBStatementQueryCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBStatementQuery';
    const TYPE_MODEL_CLASS = VTBStatementQueryType::class;
}

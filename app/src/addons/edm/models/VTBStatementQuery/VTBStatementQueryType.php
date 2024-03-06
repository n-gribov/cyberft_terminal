<?php

namespace addons\edm\models\VTBStatementQuery;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\StatementQuery;

class VTBStatementQueryType extends BaseVTBDocumentType
{
    const TYPE = 'VTBStatementQuery';
    const VTB_DOCUMENT_CLASS = StatementQuery::class;
}

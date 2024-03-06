<?php

namespace addons\edm\models\VTBStatementRu;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\StatementRu;

class VTBStatementRuType extends BaseVTBDocumentType
{
    const TYPE = 'VTBStatementRu';
    const VTB_DOCUMENT_CLASS = StatementRu::class;
}

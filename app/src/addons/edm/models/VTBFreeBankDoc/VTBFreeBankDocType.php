<?php

namespace addons\edm\models\VTBFreeBankDoc;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\FreeBankDoc;

class VTBFreeBankDocType extends BaseVTBDocumentType
{
    const TYPE = 'VTBFreeBankDoc';
    const VTB_DOCUMENT_CLASS = FreeBankDoc::class;
}

<?php

namespace addons\edm\models\VTBFreeClientDoc;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\FreeClientDoc;

class VTBFreeClientDocType extends BaseVTBDocumentType
{
    const TYPE = 'VTBFreeClientDoc';
    const VTB_DOCUMENT_CLASS = FreeClientDoc::class;
}

<?php

namespace addons\edm\models\VTBTransitAccPayDoc;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\TransitAccPayDoc;

class VTBTransitAccPayDocType extends BaseVTBDocumentType
{
    const TYPE = 'VTBTransitAccPayDoc';
    const VTB_DOCUMENT_CLASS = TransitAccPayDoc::class;
}

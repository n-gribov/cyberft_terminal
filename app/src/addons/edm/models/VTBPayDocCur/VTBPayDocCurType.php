<?php

namespace addons\edm\models\VTBPayDocCur;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\PayDocCur;

class VTBPayDocCurType extends BaseVTBDocumentType
{
    const TYPE = 'VTBPayDocCur';
    const VTB_DOCUMENT_CLASS = PayDocCur::class;
}

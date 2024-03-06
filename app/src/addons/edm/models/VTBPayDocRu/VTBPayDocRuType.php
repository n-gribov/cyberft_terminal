<?php

namespace addons\edm\models\VTBPayDocRu;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\PayDocRu;

class VTBPayDocRuType extends BaseVTBDocumentType
{
    const TYPE = 'VTBPayDocRu';
    const VTB_DOCUMENT_CLASS = PayDocRu::class;
}

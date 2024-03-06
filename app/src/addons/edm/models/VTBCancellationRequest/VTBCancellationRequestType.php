<?php

namespace addons\edm\models\VTBCancellationRequest;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentType;
use common\models\vtbxml\documents\CancellationRequest;

class VTBCancellationRequestType extends BaseVTBDocumentType
{
    const TYPE = 'VTBCancellationRequest';
    const VTB_DOCUMENT_CLASS = CancellationRequest::class;
}

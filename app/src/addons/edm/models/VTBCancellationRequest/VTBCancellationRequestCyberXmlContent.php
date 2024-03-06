<?php

namespace addons\edm\models\VTBCancellationRequest;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBCancellationRequestCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBCancellationRequest';
    const TYPE_MODEL_CLASS = VTBCancellationRequestType::class;
}

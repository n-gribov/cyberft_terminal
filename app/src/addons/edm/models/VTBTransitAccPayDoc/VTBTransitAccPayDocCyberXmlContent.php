<?php

namespace addons\edm\models\VTBTransitAccPayDoc;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBTransitAccPayDocCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBTransitAccPayDoc';
    const TYPE_MODEL_CLASS = VTBTransitAccPayDocType::class;
}

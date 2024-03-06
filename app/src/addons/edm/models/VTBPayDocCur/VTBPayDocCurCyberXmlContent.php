<?php

namespace addons\edm\models\VTBPayDocCur;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBPayDocCurCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBPayDocCur';
    const TYPE_MODEL_CLASS = VTBPayDocCurType::class;
}

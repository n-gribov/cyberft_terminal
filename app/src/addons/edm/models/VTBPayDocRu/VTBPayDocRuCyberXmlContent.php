<?php
namespace addons\edm\models\VTBPayDocRu;

use addons\edm\models\BaseVTBDocument\BaseVTBDocumentCyberXmlContent;

class VTBPayDocRuCyberXmlContent extends BaseVTBDocumentCyberXmlContent
{
    const ROOT_ELEMENT = 'VTBPayDocRu';
    const TYPE_MODEL_CLASS = VTBPayDocRuType::class;
}

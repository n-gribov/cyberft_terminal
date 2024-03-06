<?php

namespace addons\edm\models\SBBOLPayDocRu;

use addons\edm\models\BaseSBBOLDocument\BaseSBBOLRequestDocumentCyberXmlContent;

class SBBOLPayDocRuCyberXmlContent extends BaseSBBOLRequestDocumentCyberXmlContent
{
    const TYPE_MODEL_CLASS = SBBOLPayDocRuType::class;
    const ROOT_ELEMENT = 'SBBOLPayDocRu';
}

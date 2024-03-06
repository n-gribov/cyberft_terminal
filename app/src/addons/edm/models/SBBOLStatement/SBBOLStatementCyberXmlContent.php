<?php

namespace addons\edm\models\SBBOLStatement;

use addons\edm\models\BaseSBBOLDocument\BaseSBBOLResponseDocumentCyberXmlContent;

class SBBOLStatementCyberXmlContent extends BaseSBBOLResponseDocumentCyberXmlContent
{
    const TYPE_MODEL_CLASS = SBBOLStatementType::class;
    const ROOT_ELEMENT = 'SBBOLStatement';
}
